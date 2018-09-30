<?php 

include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();







if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "banco") { banco(); }
    elseif ($do == "depositar") { depositar(); }
	elseif ($do == "retirar") { retirar(); }
	elseif ($do == "enviaritem") { enviaritem(); }
	elseif ($do == "depositarbp") { depositarbp(); }
	elseif ($do == "diretoparabp") { diretoparabp(); }
	elseif ($do == "deletaritem") { deletaritem(); }
	elseif ($do == "doarryou") { doarryou(); }
	
	}
	



function banco() {
global $topvar;
$topvar = true;
$qual = $_GET['qual'];
$f = $_GET['f'];
$ry = $_GET['ry'];
$bi = $_GET['bi'];
$br = $_GET['br'];
$deletar = $_GET['deletar'];
$enviar = $_GET['enviar'];
$hacont = $_GET['hacont'];
$depositovar = $_GET['depositovar'];

    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] != "In Town") {if ($userrow["currentaction"] == "Fighting"){header('Location: ./index.php?do=fight&conteudo=Você só pode acessar essa função dentro de uma cidade!');die();}else{header('Location: ./index.php?conteudo=Você só pode acessar essa função dentro de uma cidade!');die();} }
			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
						
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
		
				$ryoudepositado = $userrow["banco_grana"];
		
			$dinheirototal = $usuriologadodinheiro + $ryoudepositado;
			
			
	//conta da porcentagem à ser depositada no banco.
	$porcentagemconta = floor(90*$dinheirototal/100);
		
		
		if (isset($_POST["submit"])) {
        extract($_POST);
		
		
		
				//DEPOSITAR GRANA
		if ($deposito != "") { 
		if (!is_numeric($deposito)) {header('Location: ./funcaoitens.php?do=banco&f=3');die(); }
		$deposito = floor($deposito);
		if ($deposito < 1) {header('Location: ./funcaoitens.php?do=banco&f=4');die(); }
	
		$porcentagemconta = floor(90*$dinheirototal/100);
		$dinheirosuposto = $deposito + $ryoudepositado;
		
							//dinheiro maior que o possível adicionado.						
							if ($dinheirosuposto > 90*$dinheirototal/100) {
							$deposito = (floor(90*$dinheirototal/100)) - $ryoudepositado;
							$hacont = 1;}
							
							
				if ($deposito > $usuriologadodinheiro) {$deposito = $usuriologadodinheiro;}
		
			
		$ryoudepositado += floor($deposito);
		$usuriologadodinheiro -= floor($deposito);
		
		$updatequery = doquery("UPDATE {{table}} SET banco_grana='$ryoudepositado' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET gold='$usuriologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");}else{$deposito = 0;}
		
		
		
		//RETIRAR GRANA
		if ($retirar != "") {
		if (!is_numeric($retirar)) {header('Location: ./funcaoitens.php?do=banco&f=7');die(); }
		$retirar = floor($retirar);
		if ($retirar < 1) {header('Location: ./funcaoitens.php?do=banco&f=8');die(); }
		if ($retirar > 99999) { header('Location: ./funcaoitens.php?do=banco&br=Você não pode retirar mais que 99999 Ryou.');die(); }
		if ($retirar > $ryoudepositado) { header('Location: ./funcaoitens.php?do=banco&br=Você não pode retirar mais que seu dinheiro no banco.');die(); }
				
		$ryoudepositado -= floor($retirar);
		$usuriologadodinheiro += floor($retirar);
		
		$updatequery = doquery("UPDATE {{table}} SET banco_grana='$ryoudepositado' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET gold='$usuriologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}else{$retirar = 0;}
		
		
		if ($hacont == ""){
		header("Location: ./funcaoitens.php?do=banco&br=Você depositou ".$deposito." Ryou e retirou ".$retirar." Ryou do Banco.");

		
		  die();
		  }else{//hacont
		  
		  		header("Location: ./funcaoitens.php?do=banco&hacont=$hacont&depositovar=$deposito&br=Você depositou ".$deposito." Ryou e retirou ".$retirar." Ryou do Banco.");
				  die();
		  
		  }//fim hacont
		  
    }
	
	




				//durabilidade
				$duraequip = explode(",",$userrow["durabilidade"]);
				for ($i = 1; $i < 7; $i ++){
				if ($duraequip[$i] == "X"){$duraequip[$i] = "*";}
				}
		
	//display do banco... contas e sistemática(equipamentos equipados).
	$tabeladeequipamentos = "<table>
	<tr bgcolor=\"#452202\"><td colspan=3><font color=white>Itens Equipados</font></td></tr>
	<tr bgcolor=\"#613003\"><td><font color=white>*</font></td><td><font color=white>Nome</font></td><td><font color=white>*</font></td></tr>";
	for ($i = 1; $i < 7; $i++){//i = variavel que define o equip.
	if ($i == 1) {$tipo = "weapon"; $img = "icon_weapon";}
	if ($i == 2) {$tipo = "armor"; $img = "icon_armor";}
	if ($i == 3) {$tipo = "shield"; $img = "icon_shield";}
	if ($i > 3) {$qnumero = $i - 3; $tipo = "slot".$qnumero; $img = "orb";}
	//fundo da tabela
	$fundo = $i % 2;
	if ($fundo == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
	//fim fundo tabela
	$nome = $tipo."name";
	$id = $tipo."id";
	$tabeladeequipamentos .= "<tr bgcolor=\"$bgcolor\"><td><img src=\"images/$img.gif\" title=\"Durabilidade: ".$duraequip[$i]."\" /></td><td>".$userrow[$nome]."</td><td><a href=\"funcaoitens.php?do=depositar&qual=$i\"> <img border=\"0\" src=\"images/setapdireita.gif\" title=\"Depositar Item\" alt=\"->\" /></a></td></tr>";
	}
	$tabeladeequipamentos .= "</table>";
	//fim tabela de equipamentos.
	
	
	
	
	//inicio da tabela da backpack
	$tabelabackpack = "<table>
	<tr bgcolor=\"#452202\"><td colspan=3><font color=white>Mochila</font></td></tr>
	<tr bgcolor=\"#613003\"><td><font color=white>*</font></td><td><font color=white>Nome</font></td><td><font color=white>*</font></td></tr>";
	for ($j = 1;$j < 5; $j++){
	//fundo da tabela
	$fundo = $j % 2;
	if ($fundo == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
	//fim fundo tabela
	$mochilaseparar = explode(",",$userrow["bp".$j]);
	include('funcoesinclusas.php');
	iconeitemmochila($mochilaseparar, $img, $dur);
	$tabelabackpack .= "<tr bgcolor=\"$bgcolor\"><td><img src=\"images/$img.gif\" title=\"Durabilidade: ".$dur."\"/></td><td>".$mochilaseparar[0]."</td><td><a href=\"funcaoitens.php?do=depositarbp&qual=$j\"> <img border=\"0\" src=\"images/setapdireita.gif\" title=\"Depositar Item\" alt=\"->\" /></a></td></tr>";
	}
	$tabelabackpack .="</table>";
	
	//inicio tabela equips no banco.
	$tabeladobanco = "<table>
	<tr bgcolor=\"#452202\"><td colspan=6><font color=white>Itens no Banco</font></td></tr>
	<tr bgcolor=\"#613003\"><td><font color=white>*</font></td><td><font color=white>*</font></td><td><font color=white>*</font></td><td><font color=white>*</font></td><td><font color=white>*</font></td><td><font color=white>Nome</font></td></tr>";
	if ($userrow["bancogeral"] != "None"){
	$equipscomtudo = explode(";",$userrow["bancogeral"]);
	$quant = count($equipscomtudo) - 2;
	$i = 0;
	for ($i = 0; $i <= $quant ; $i++){
	$itemseparado = explode(",",$equipscomtudo[$i]);
	//saber que tipo de arma é
	$img = "orb_img";
	if ($itemseparado[2] == 1) {$tipo = "weapon"; $img = "icon_weapon";}
	elseif ($itemseparado[2] == 2) {$tipo = "armor"; $img = "icon_armor";}
	elseif ($itemseparado[2] == 3) {$tipo = "shield"; $img = "icon_shield";}
	elseif (($itemseparado[2] > 3) && ($itemseparado[2] < 7)) {$qnumero = $i - 3; $tipo = "slot".$qnumero; $img = "orb";}
	else {	include('funcoesinclusas.php');
	iconeitemmochila($itemseparado, $img, $dur);}
	//fundo da tabela
	$fundo = $i % 2;
	if ($fundo == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
	//fim fundo tabela
	$tabeladobanco .= "<tr bgcolor=\"$bgcolor\"><td><a href=\"funcaoitens.php?do=retirar&qual=$i\"><img src=\"images/setapesquerda.gif\" border=\"0\" title=\"Retirar Item\" alt=\"<-\" /></a></td><td><a href=\"funcaoitens.php?do=diretoparabp&qual=$i\"><img src=\"images/backpack_pequena.gif\" border=\"0\" title=\"Enviar Para Mochila\" alt=\"X\"></a></td>
	<td><a href=\"funcaoitens.php?do=banco&qual=$i&enviar=ok\" title=\"Doar Item\" alt=\"X\" ><img src=\"images/gift.gif\" alt=\"X\" border=\"0\" /></a></td><td><a href=\"funcaoitens.php?do=banco&qual=$i&deletar=$itemseparado[0]\"><img src=\"images/deletar.gif\" border=\"0\" title=\"Deletar Item\" alt=\"X\"></a></td>
	<td><img src=\"images/$img.gif\" title=\"Durabilidade: ".$dur."\" /></td>
	<td>".$itemseparado[0]."</td></tr>";
	
	}//fim for
	}//fim if
	else{$tabeladobanco .= "<tr bgcolor=\"#E4D094\"><td>*</td><td>*</td><td>*</td><td>*</td><td>*</td><td>Nenhum Item</font></td></tr>";}
	$tabeladobanco .= "</table>";
	//fim tabela do banco.
	
	
	
	
	//se for pra doar o item:
	if ($qual != ""){
	if ($enviar != ""){
	$tabeladobanco .= "<br><br><center><table border=\"0\"><tr bgcolor=\"#452202\"><td width=\"*\"><font color=white>Doar Item Para(Jogador):</font></td><td width=\"14\"><a href=\"funcaoitens.php?do=banco\"><img border=\"0\" src=\"images/deletar2.jpg\" title=\"Cancelar\"></a></td></tr>
	<tr bgcolor=\"#FFF1C7\"><td colspan=\"2\"><form action=\"funcaoitens.php?do=enviaritem&qual=$qual\" method=\"post\">
	<input type=\"text\" name=\"jogador\" size=\"20\" /><input type=\"submit\" name=\"submit\" value=\"OK!\"/></form></td></tr></table></center>";}}
	
	if ($qual != ""){
	if ($deletar != ""){
	$tabeladobanco .= "<br><br><center><table width=\"190\"><tr bgcolor=\"#613003\"><td><font color=white>Deletar</font></td></tr>
	<tr bgcolor=\"#FFF1C7\"><td>Você tem certeza que deseja deletar o item $deletar ?<br><center>
	<a href=\"funcaoitens.php?do=deletaritem&qual=$qual\"><img border=\"0\" src=\"images/aceitar.gif\" title=\"Deletar Item\" alt=\"X\"></a><a href=\"funcaoitens.php?do=banco\"><img border=\"0\" src=\"images/deletar.gif\" title=\"Não Deletar Item\" alt=\"X\"></a></center></td></tr></table></center>";}}
	
	
	
	//item enviado com sucesso
	if ($f == 1) {$mostraritemenviado = "<center><font color=brown>Seu Item foi Enviado com Sucesso.</font></center><br>";}
	if ($f == 3) {$mostrarpartecimaryou = "<center><font color=brown>A quantidade de Ryou à depositar deve ser um número.</font></center><br>";}
	if ($f == 4) {$mostrarpartecimaryou = "<center><font color=brown>Você não pode depositar menos que 1 Ryou.</font></center><br>";}
	if ($f == 5) {$mostrarpartecimaryou = "<center><font color=brown>Você não pode ter uma quantia maior que $porcentagemconta Ryou no Banco, que representa 90% do seu Ryou total, o que está depositado e o que está no seu personagem.</font></center><br>";}
	if ($f == 6) {$mostrarpartecimaryou = "<center><font color=brown>Você não pode depositar mais que a sua quantidade de Ryou.</font></center><br>";}
	if ($f == 7) {$mostrarpartecimaryou = "<center><font color=brown>A quantidade de Ryou à retirar deve ser um número.</font></center><br>";}
	if ($f == 8) {$mostrarpartecimaryou = "<center><font color=brown>Você não pode retirar menos que 1 Ryou.</font></center><br>";}
	if ($f == 10) {$mostraritemenviado = "<center><font color=brown>Item deletado com sucesso.</font></center><br>";}

	
	
	if ($hacont == ""){//se o hacont, que é o treco de 90% tiver vazio, então:
	
	if ($f == ""){
	if ($bi != ""){
	$mostraritemenviado = "<center><font color=brown>$bi</font></center><br>";
	}}	
	
	if ($f == ""){
	if ($br != ""){
	$mostrarpartecimaryou = "<center><font color=brown>$br</font></center><br>";
	}}	
	
	}else{//else do if hacont...
			
	//deposito maior que 90%
	$mostrarpartecimaryou .= "<center><font color=brown>Você depositou um valor maior que 90% de seu Ryou Total<i>(no Banco e Equipado)</i>, portanto foi depositado apenas <font color=red>$depositovar</font> Ryou, que é a quantia máxima que você pode depositar em seu Banco no momento.</font></center><br>";
	
	}//fim do if hacont...

	
	
	
	if ($ry != ""){
	$enviarryouaqui = "<center><form action=\"funcaoitens.php?do=doarryou\" method=\"post\"><table><tr bgcolor=\"#613003\"><td><font color=white>Doar Ryou</font></td></tr>
	<tr bgcolor=\"#E4D094\"><td><center>Doar Para(Jogador):</td></tr>
	<tr bgcolor=\"#FFF1C7\"><td><input type=\"text\" name=\"jogador\" size=\"20\" /></td></tr>
	<tr bgcolor=\"#E4D094\"><td><center>Quantidade(Ryou):</center></td></tr>
	<tr bgcolor=\"#FFF1C7\"><td><input type=\"text\" name=\"quantidaderyou\" size=\"20\" /></td></tr>
	<tr bgcolor=\"#E4D094\"><td><center><input type=\"submit\" name=\"submit\" value=\"OK!\" /></center></form></td></tr>
	<tr bgcolor=\"#FFF1C7\"><td><center><a href=\"funcaoitens.php?do=banco\"><img src=\"images/deletar.gif\" border=\"0\" title=\"Fechar essa caixa.\" alt=\"X\"></a></td></tr>
	</table></center>
	";}
	
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/banco.gif\" /></center></td></tr></table>
	$mostrarpartecimaryou
	<center>
	<table><tr><td>
	<table><tr bgcolor=\"#452202\"><td colspan=\"2\"><font color=white>Ryou Equipado</font></td></tr>
	<tr bgcolor=\"#E4D094\"><td width=\"20\"><img src=\"images/ryou.gif\" title=\"Ryou Equipado\"></td><td>$usuriologadodinheiro</td></tr></table>
	</td><td>
	<table><tr bgcolor=\"#452202\"><td colspan=\"3\"><font color=white>Ryou no Banco</font></td></tr>
	<tr bgcolor=\"#E4D094\"><td width=\"20\"><a href=\"funcaoitens.php?do=banco&ry=1\"><img src=\"images/gift.gif\" border=\"0\" title=\"Doar Ryou\"></a></td><td width=\"20\"><img src=\"images/ryou.gif\" title=\"Ryou no Banco\"></td><td>$ryoudepositado</td></tr></table>
	</td></tr></table>
	</center>
	
	".gettemplate("banco")."$enviarryouaqui
	<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/bancodeitens.gif\" /></center></td></tr></table>
	$mostraritemenviado
	<center><table><tr><td>
	$tabeladeequipamentos<br>
	$tabelabackpack
	</td><td>
	$tabeladobanco
	</td></tr></table></center>
	";
	
	
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Banco", false, false, false); 
    		
		
		
}







function depositar() {
 /*$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
	
	$dropquery = doquery("SELECT * FROM {{table}} WHERE mlevel <= '".$monsterrow["level"]."' ORDER BY RAND() LIMIT 1", "drops");
                $droprow = mysql_fetch_array($dropquery);
*/

global $topvar;
$topvar = true;
$qual = $_GET['qual'];

    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] != "In Town") {if ($userrow["currentaction"] == "Fighting"){header('Location: ./index.php?do=fight&conteudo=Você só pode acessar essa função dentro de uma cidade!');die();}else{header('Location: ./index.php?conteudo=Você só pode acessar essa função dentro de uma cidade!');die();} }
			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
			if ($qual > 6) {display("Tentativa de trapaça ou erro detectado.","Erro",false,false,false);die(); }
			if ($qual < 1) {display("Tentativa de trapaça ou erro detectado.","Erro",false,false,false);die(); }
			
			if ($userrow["bancogeral"] != "None") {//inicio
			$novavariavelteste = explode(";",$userrow["bancogeral"]);
			
					//quantidade banco VIP
					if ($userrow["acesso"] > 0){$quantositens = 60;}else{$quantositens = 30;}
			
			if (count($novavariavelteste) >= $quantositens){header("Location: ./funcaoitens.php?do=banco&bi=Você não pode ter mais que ".$quantositens." itens no Banco.");die(); }
			}//fim
			
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
		
	
	//pra saber qual foi o item.
	if ($qual == 1) {$nomedoslot = "weapon";}
	if ($qual == 2) {$nomedoslot = "armor";}
	if ($qual == 3) {$nomedoslot = "shield";}
	if ($qual == 4) {$nomedoslot = "slot1";}
	if ($qual == 5) {$nomedoslot = "slot2";}
	if ($qual == 6) {$nomedoslot = "slot3";}
	$id = $nomedoslot."id";
	$nome = $nomedoslot."name";
	//fechou
	
	//se nao tem item
	if ($userrow[$nome] == "None"){header('Location: ./funcaoitens.php?do=banco');die(); }
		
		if ($qual <= 3) {//para weapon armor e shield
	$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow[$id]."' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
	
	if ($itemsrow["special"] != "X"){
	$atributo = explode(",",$itemsrow["special"]);
	$userrow[$atributo[0]] -= $atributo[1];
	if ($atributo[0] == "strength") { $userrow["attackpower"] -= $atributo[1]; }
    elseif ($atributo[0] == "dexterity") { $userrow["defensepower"] -= $atributo[1]; }
	}
	
	//verificar se é arma escudo ou armadura:
	if ($itemsrow["type"] == 1) {//ataque
	$userrow["attackpower"] -= $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$userrow[$nome]);
	if ($variavelpcontinuar[1] != ""){
	$userrow["attackpower"] -= $variavelpcontinuar[1]*20;//15 - de bonus
	}
	//fim dos bonus
	
	}
	else {//sendo de defesa
	$userrow["defensepower"] -= $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$userrow[$nome]);
	if ($variavelpcontinuar[1] != ""){
	$userrow["defensepower"] -= $variavelpcontinuar[1]*20;//15 - de bonus
	}
	//fim dos bonus
	
	}
	//pronto
	

	
	}
	
	if ($qual > 3) {//para slots
	$dropquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow[$id]."' LIMIT 1", "drops");
                $droprow = mysql_fetch_array($dropquery);
				
	//atributos
	//primeiro:
	if ($droprow["attribute1"] != "X"){
	$atributo1 = explode(",",$droprow["attribute1"]);
	$userrow[$atributo1[0]] -= $atributo1[1];
	if ($atributo1[0] == "strength") { $userrow["attackpower"] -= $atributo1[1]; }
    elseif ($atributo1[0] == "dexterity") { $userrow["defensepower"] -= $atributo1[1]; }

	}
	
	
	//segundo:
	if ($droprow["attribute2"] != "X"){
	$atributo2 = explode(",",$droprow["attribute2"]);
	$userrow[$atributo2[0]] -= $atributo2[1];
	if ($atributo2[0] == "strength") { $userrow["attackpower"] -= $atributo2[1]; }
    elseif ($atributo2[0] == "dexterity") { $userrow["defensepower"] -= $atributo2[1]; }
	}
	//fechou
	}
	
	//durabilidade
	$durab = explode(",",$userrow["durabilidade"]);	

	
	//adicionando item ao banco
	if ($userrow["bancogeral"] != "None") {//adicionando o item ao banco.
	$userrow["bancogeral"] .= $userrow[$nome].",".$userrow[$id].",".$qual.",".$durab[$qual].";";
	}else{
	$userrow["bancogeral"] = $userrow[$nome].",".$userrow[$id].",".$qual.",".$durab[$qual].";";
	}
	
	//retirando a durabilidade
	$durab[$qual] = "X";
	$userrow["durabilidade"] = "-,".$durab[1].",".$durab[2].",".$durab[3].",".$durab[4].",".$durab[5].",".$durab[6];
	
	
	//adicionando os stats
	    $updatequery = doquery("UPDATE {{table}} SET durabilidade='".$userrow["durabilidade"]."',goldbonus='".$userrow["goldbonus"]."',expbonus='".$userrow["expbonus"]."',maxhp='".$userrow["maxhp"]."',maxmp='".$userrow["maxmp"]."',maxtp='".$userrow["maxtp"]."',strength='".$userrow["strength"]."',dexterity='".$userrow["dexterity"]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."', agilidade='".$userrow["agilidade"]."', determinacao='".$userrow["determinacao"]."', precisao='".$userrow["precisao"]."', sorte='".$userrow["sorte"]."', inteligencia='".$userrow["inteligencia"]."',droprate='".$userrow["droprate"]."',maxnp='".$userrow["maxnp"]."',maxep='".$userrow["maxep"]."', bancogeral='".$userrow["bancogeral"]."', $nome='None', $id='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
		
	
	
	header('Location: ./funcaoitens.php?do=banco');

	

	
}















































function retirar() {
 /*$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
	
	$dropquery = doquery("SELECT * FROM {{table}} WHERE mlevel <= '".$monsterrow["level"]."' ORDER BY RAND() LIMIT 1", "drops");
                $droprow = mysql_fetch_array($dropquery);
*/

global $topvar;
$topvar = true;
$qual = $_GET['qual'];

    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] != "In Town") {if ($userrow["currentaction"] == "Fighting"){header('Location: ./index.php?do=fight&conteudo=Você só pode acessar essa função dentro de uma cidade!');die();}else{header('Location: ./index.php?conteudo=Você só pode acessar essa função dentro de uma cidade!');die();} }
			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
			
			$itensnobanco = explode(";",$userrow["bancogeral"]);
			$quantidade = count($itensnobanco) - 1;
			
			//fim
			if ($qual >= $quantidade) {display("Tentativa de trapaça ou erro detectado.","Erro",false,false,false);die(); }
						
		
			
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
		
	

	
	//se nao tem item
	if ($itensnobanco[$qual] == ""){header('Location: http://nigeru.com/narutorpg/funcaoitens.php?do=banco');die(); }
		
	//separação de subitens:
	$itemseparado = explode(",",$itensnobanco[$qual]);
	
	//aqui se os itens são para somente a backpack ou podem ser equipados também.
	if (is_numeric($itemseparado[1])) {
	
		
	//fim se algo já estiver equipado no jogador
	//pra saber qual foi o item.
	if ($itemseparado[2] == 1) {$nomedoslot = "weapon"; $frasemostrar = "Arma";}
	if ($itemseparado[2] == 2) {$nomedoslot = "armor"; $frasemostrar = "Colete";}
	if ($itemseparado[2] == 3) {$nomedoslot = "shield"; $frasemostrar = "Bandana";}
	if ($itemseparado[2] == 4) {$nomedoslot = "slot1"; $frasemostrar = "Slot 1";}
	if ($itemseparado[2] == 5) {$nomedoslot = "slot2"; $frasemostrar = "Slot 2";}
	if ($itemseparado[2] == 6) {$nomedoslot = "slot3"; $frasemostrar = "Slot 3";}
	$id = $nomedoslot."id";
	$nome = $nomedoslot."name";
	//fechou
	if ($itemseparado[2] <= 3){//para armor e shield e arma de atk
	if ($userrow[$id] != 0) {//enviar para bp
	//enviar para backpack
	if ($userrow["bp4"] != "None") {$var = 1;}else{$bpcerto = "bp4";}
	if ($userrow["bp3"] != "None") {$var += 1;}else{$bpcerto = "bp3";}
	if ($userrow["bp2"] != "None") {$var += 1;}else{$bpcerto = "bp2";}
	if ($userrow["bp1"] != "None") {$var += 1;}else{$bpcerto = "bp1";}
	if ($var == 4){ header("Location: ./funcaoitens.php?do=banco&bi=Você já possui um(a) ".$frasemostrar." equipado(a) e não possui espaços livres na sua Mochila. Libere algum espaço.");die(); }
	
				$userrow[$bpcerto] = $itensnobanco[$qual];
	
	
				//retirando equipe do banco
				$userrow["bancogeral"] = "";
				$qcerto = $quantidade - 1; //numero de vezes
				for($i = 0; $i <= $qcerto; $i++){
				if ($i != $qual){//pra nao contar o item que está querendo retirar do banco.
				$userrow["bancogeral"] .= $itensnobanco[$i].";";
				}
				}
				
				
				//se o banco estiver zerado.
				if ($userrow["bancogeral"] == "") {$userrow["bancogeral"] = "None";}
				
				$updatequery = doquery("UPDATE {{table}} SET bp4='".$userrow["bp4"]."',bp3='".$userrow["bp3"]."',bp2='".$userrow["bp2"]."',bp1='".$userrow["bp1"]."', bancogeral='".$userrow["bancogeral"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
	
	
			header('Location: ./funcaoitens.php?do=banco'); die();
				
				}//fim do 	$userrow[$id] != 0
	}else{//else1
			if($userrow["slot1id"] != 0){$saberitens = 1;}
			if($userrow["slot2id"] != 0){$saberitens += 1;}
			if($userrow["slot3id"] != 0){$saberitens += 1;}
			if($saberitens == ""){$saberitens = 0;}
		if ($saberitens == 3){//item para equipar nos slots enviar para backpack
		
			//enviar para backpack
	if ($userrow["bp4"] != "None") {$var = 1;}else{$bpcerto = "bp4";}
	if ($userrow["bp3"] != "None") {$var += 1;}else{$bpcerto = "bp3";}
	if ($userrow["bp2"] != "None") {$var += 1;}else{$bpcerto = "bp2";}
	if ($userrow["bp1"] != "None") {$var += 1;}else{$bpcerto = "bp1";}
	if ($var == 4){ header('Location: ./funcaoitens.php?do=banco&bi=Você não possui Slots Livres para Equipar e também não possui espaço livre em sua Mochila. Libere algum espaço.');die(); }
	
				$userrow[$bpcerto] = $itensnobanco[$qual];
	
	
				//retirando equipe do banco
				$userrow["bancogeral"] = "";
				$qcerto = $quantidade - 1; //numero de vezes
				for($i = 0; $i <= $qcerto; $i++){
				if ($i != $qual){//pra nao contar o item que está querendo retirar do banco.
				$userrow["bancogeral"] .= $itensnobanco[$i].";";
				}
				}
				
				
				//se o banco estiver zerado.
				if ($userrow["bancogeral"] == "") {$userrow["bancogeral"] = "None";}
				
				$updatequery = doquery("UPDATE {{table}} SET bp4='".$userrow["bp4"]."',bp3='".$userrow["bp3"]."',bp2='".$userrow["bp2"]."',bp1='".$userrow["bp1"]."', bancogeral='".$userrow["bancogeral"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
	
	
			header('Location: ./funcaoitens.php?do=banco'); die();
		
		
		}else{ if($userrow["slot1id"] == 0){$slotcerto = "slot1"; $durabnumcerto = 1;}elseif($userrow["slot2id"] == 0){$slotcerto = "slot2"; $durabnumcerto = 2;}elseif($userrow["slot3id"] == 0){$slotcerto = "slot3"; $durabnumcerto = 3;}}//fechou else2//dizer qual slot está livre.
	}//fim else1
	//fechou o fim se ja estiver equipado.	
		
		
	if ($itemseparado[2] <= 3) {//para weapon armor e shield e arma de atk
	$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$itemseparado[1]."' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
	
	if ($itemsrow["special"] != "X"){
	$atributo = explode(",",$itemsrow["special"]);
	$userrow[$atributo[0]] += $atributo[1];
	if ($atributo[0] == "strength") { $userrow["attackpower"] += $atributo[1]; }
    elseif ($atributo[0] == "dexterity") { $userrow["defensepower"] += $atributo[1]; }
	}
	
	//verificar se é arma escudo ou armadura:
	if ($itemsrow["type"] == 1) {//ataque
	$userrow["attackpower"] += $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$itemseparado[0]);
	if ($variavelpcontinuar[1] != ""){
	$userrow["attackpower"] += $variavelpcontinuar[1]*20;//15 + de bonus
	}
	//fim dos bonus
	
	}
	else {//sendo de defesa
	$userrow["defensepower"] += $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$itemseparado[0]);
	if ($variavelpcontinuar[1] != ""){
	$userrow["defensepower"] += $variavelpcontinuar[1]*20;//15 + de bonus
	}
	//fim dos bonus
	
	}
	//pronto
	

	
	}
	
	if ($itemseparado[2] > 3) {//para slots
	$dropquery = doquery("SELECT * FROM {{table}} WHERE id='".$itemseparado[1]."' LIMIT 1", "drops");
                $droprow = mysql_fetch_array($dropquery);
				
	//atributos
	//primeiro:
	if ($droprow["attribute1"] != "X"){
	$atributo1 = explode(",",$droprow["attribute1"]);
	$userrow[$atributo1[0]] += $atributo1[1];
	if ($atributo1[0] == "strength") { $userrow["attackpower"] += $atributo1[1]; }
    elseif ($atributo1[0] == "dexterity") { $userrow["defensepower"] += $atributo1[1]; }
	}
	
	
	//segundo:
	if ($droprow["attribute2"] != "X"){
	$atributo2 = explode(",",$droprow["attribute2"]);
	$userrow[$atributo2[0]] += $atributo2[1];
	if ($atributo2[0] == "strength") { $userrow["attackpower"] += $atributo2[1]; }
    elseif ($atributo2[0] == "dexterity") { $userrow["defensepower"] += $atributo2[1]; }
	}
	//fechou
	}
	
	
	
	
	
	//colocando o item no jogador
	if ($itemseparado[2] <= 3) {//para weapon armor e shield e arma de atk
	$userrow[$nome] = $itemseparado[0];
	$userrow[$id] = $itemseparado[1];
	}else{//para slots
	$novo1 = $slotcerto."name";
	$novo2 = $slotcerto."id";
	$userrow[$novo1] = $itemseparado[0];
	$userrow[$novo2] = $itemseparado[1];
	}
	
	
	//retirando equipe do banco
	$userrow["bancogeral"] = "";
	$qcerto = $quantidade - 1; //numero de vezes
	for($i = 0; $i <= $qcerto; $i++){
	if ($i != $qual){//pra nao contar o item que está querendo retirar do banco.
	$userrow["bancogeral"] .= $itensnobanco[$i].";";
	}
	}
	
	
	//se o banco estiver zerado.
	if ($userrow["bancogeral"] == "") {$userrow["bancogeral"] = "None";}
	
	//durabilidade
	if ($durabnumcerto != ""){$qq = $durabnumcerto + 3;}else{$qq = $itemseparado[2];}
	$durab = explode(",",$userrow["durabilidade"]);
	$durab[$qq] = $itemseparado[3];
	$userrow["durabilidade"] = "-,".$durab[1].",".$durab[2].",".$durab[3].",".$durab[4].",".$durab[5].",".$durab[6];
	
	
	}else{//aqui é se os itens forem somente para a backpack.
	if ($userrow["bp4"] != "None") {$var = 1;}else{$bpcerto = "bp4";}
	if ($userrow["bp3"] != "None") {$var += 1;}else{$bpcerto = "bp3";}
	if ($userrow["bp2"] != "None") {$var += 1;}else{$bpcerto = "bp2";}
	if ($userrow["bp1"] != "None") {$var += 1;}else{$bpcerto = "bp1";}
	if ($var == 4){ header('Location: ./funcaoitens.php?do=banco&bi=Você não possui espaços livres na sua Mochila. Libere algum espaço.');die(); }
	
	$userrow[$bpcerto] = $itensnobanco[$qual];
	
	
	//retirando equipe do banco
	$userrow["bancogeral"] = "";
	$qcerto = $quantidade - 1; //numero de vezes
	for($i = 0; $i <= $qcerto; $i++){
	if ($i != $qual){//pra nao contar o item que está querendo retirar do banco.
	$userrow["bancogeral"] .= $itensnobanco[$i].";";
	}
	}
	
	
	//se o banco estiver zerado.
	if ($userrow["bancogeral"] == "") {$userrow["bancogeral"] = "None";}
	
	
	}//fim se os itens forem só pra backpack
	
	//adicionando os stats
	    $updatequery = doquery("UPDATE {{table}} SET bp4='".$userrow["bp4"]."',bp3='".$userrow["bp3"]."',bp2='".$userrow["bp2"]."',bp1='".$userrow["bp1"]."',durabilidade='".$userrow["durabilidade"]."',goldbonus='".$userrow["goldbonus"]."',expbonus='".$userrow["expbonus"]."',maxhp='".$userrow["maxhp"]."',maxmp='".$userrow["maxmp"]."',maxtp='".$userrow["maxtp"]."',strength='".$userrow["strength"]."',dexterity='".$userrow["dexterity"]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."', agilidade='".$userrow["agilidade"]."', determinacao='".$userrow["determinacao"]."', precisao='".$userrow["precisao"]."', sorte='".$userrow["sorte"]."', inteligencia='".$userrow["inteligencia"]."', bancogeral='".$userrow["bancogeral"]."', weaponname='".$userrow["weaponname"]."', shieldname='".$userrow["shieldname"]."', armorname='".$userrow["armorname"]."', slot1name='".$userrow["slot1name"]."', slot2name='".$userrow["slot2name"]."', slot3name='".$userrow["slot3name"]."', weaponid='".$userrow["weaponid"]."', shieldid='".$userrow["shieldid"]."', armorid='".$userrow["armorid"]."', slot1id='".$userrow["slot1id"]."', slot2id='".$userrow["slot2id"]."', slot3id='".$userrow["slot3id"]."',droprate='".$userrow["droprate"]."',maxnp='".$userrow["maxnp"]."',maxep='".$userrow["maxep"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
	
	
	header('Location: ./funcaoitens.php?do=banco');

	

	
}







































function enviaritem() {
 /*$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
	
	$dropquery = doquery("SELECT * FROM {{table}} WHERE mlevel <= '".$monsterrow["level"]."' ORDER BY RAND() LIMIT 1", "drops");
                $droprow = mysql_fetch_array($dropquery);
*/

global $topvar;
$topvar = true;
$qual = $_GET['qual'];

    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] != "In Town") {if ($userrow["currentaction"] == "Fighting"){header('Location: ./index.php?do=fight&conteudo=Você só pode acessar essa função dentro de uma cidade!');die();}else{header('Location: ./index.php?conteudo=Você só pode acessar essa função dentro de uma cidade!');die();} }
			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
			
		$quantidade = count($itensnobanco) - 1;

			
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
		
		
		if (isset($_POST["submit"])) {
        extract($_POST);
		
		
		
			$itensnobanco = explode(";",$userrow["bancogeral"]);
			$quantidade = count($itensnobanco) - 1;
			
			//fim
			if ($qual >= $quantidade) {display("Tentativa de trapaça ou erro detectado(a).","Erro",false,false,false);die(); }
			if (strtolower($jogador) == strtolower($userrow["charname"])) {header('Location: ./funcaoitens.php?do=banco&bi=Você não pode doar item para você mesmo.');die();}
			
	$userquery2 = doquery("SELECT * FROM {{table}} WHERE charname='$jogador' LIMIT 1", "users");
	if (mysql_num_rows($userquery2) != 1) { header('Location: ./funcaoitens.php?do=banco&bi=Não existe nenhum Jogador com esse Nome para Doar o Item.');die(); }
    $userpara = mysql_fetch_array($userquery2);
			
			
			//outro jogador
			if ($userpara["bancogeral"] != "None") {//inicio
			$novavariavelteste = explode(";",$userpara["bancogeral"]);
			
			//quantidade banco VIP
					if ($userpara["acesso"] > 0){$quantositens = 60;}else{$quantositens = 30;}
			
			if (count($novavariavelteste) >= $quantositens){header("Location: ./funcaoitens.php?do=banco&bi=O Jogador o qual você quer enviar o Item, possui mais de ".$quantositens." Itens no Banco.");die(); }
			}//fim
			if ($userrow["bancogeral"] == "None"){header('Location: ./funcaoitens.php?do=banco&bi=Você não pode doar Itens, você não tem nenhum no Banco.');die();}
		
		
//conferindo banco.
$itemseparado = explode(";",$userrow["bancogeral"]);

	//colocando o item no jogador
	if ($userpara["bancogeral"] != "None"){
	$userpara["bancogeral"] .= $itemseparado[$qual].";";
	}else{$userpara["bancogeral"] = $itemseparado[$qual].";";}
	
	
	//retirando equipe do banco
	$userrow["bancogeral"] = "";
	$qcerto = $quantidade - 1; //numero de vezes
	for($i = 0; $i <= $qcerto; $i++){
	if ($i != $qual){//pra nao contar o item que está querendo retirar do banco.
	$userrow["bancogeral"] .= $itensnobanco[$i].";";
	}
	}
	
	
	//se o banco estiver zerado.
	if ($userrow["bancogeral"] == "") {$userrow["bancogeral"] = "None";}
	
	
	//histórico adicionando.. historico
	$teste = explode(",",$itemseparado[$qual]);
	$historico = $userpara["historico"];
	if ($historico == "None"){
		$historico = "O jogador ".$userrow["charname"]." o enviou o item <font color=\"brown\">".$teste[0]."</font>, que se encontra em seu banco.";
	}else{
		$historico .= ";;O jogador ".$userrow["charname"]." o enviou o item <font color=\"brown\">".$teste[0]."</font>, que se encontra em seu banco.";
	}
	
	//adicionando os stats
	    $updatequery = doquery("UPDATE {{table}} SET bancogeral='".$userrow["bancogeral"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
		 $updatequery = doquery("UPDATE {{table}} SET bancogeral='".$userpara["bancogeral"]."' WHERE id='".$userpara["id"]."' LIMIT 1", "users");
		 $updatequery = doquery("UPDATE {{table}} SET historico='".$historico."' WHERE id='".$userpara["id"]."' LIMIT 1", "users");
	
	
	header('Location: ./funcaoitens.php?do=banco&f=1');

	

}//end if submit
	
}
























function depositarbp() {
global $topvar;
$topvar = true;
$qual = $_GET['qual'];

    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] != "In Town") {if ($userrow["currentaction"] == "Fighting"){header('Location: ./index.php?do=fight&conteudo=Você só pode acessar essa função dentro de uma cidade!');die();}else{header('Location: ./index.php?conteudo=Você só pode acessar essa função dentro de uma cidade!');die();} }
			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
			if ($qual > 4) {display("Tentativa de trapaça ou erro detectado.","Erro",false,false,false);die(); }
			if ($qual < 1) {display("Tentativa de trapaça ou erro detectado.","Erro",false,false,false);die(); }
			
			if ($userrow["bp".$qual] == "None") { header('Location: ./funcaoitens.php?do=banco');die(); }
			
			if ($userrow["bancogeral"] != "None") {//inicio
			$novavariavelteste = explode(";",$userrow["bancogeral"]);
			
			//quantidade banco VIP
					if ($userrow["acesso"] > 0){$quantositens = 60;}else{$quantositens = 30;}
			
			if (count($novavariavelteste) >= $quantositens){header("Location: ./funcaoitens.php?do=banco&bi=Você não pode ter mais que ".$quantositens." itens no Banco.");die(); }
			}//fim
			
			
			//adicionando o item ao banco
			if ($userrow["bancogeral"]  == "None"){
			$userrow["bancogeral"] = $userrow["bp".$qual].";";}else{
			$userrow["bancogeral"] .= $userrow["bp".$qual].";";}
			
			
			//retirando o item
			$userrow["bp".$qual] = "None";
			
			 $updatequery = doquery("UPDATE {{table}} SET bancogeral='".$userrow["bancogeral"]."', bp$qual='".$userrow["bp".$qual]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
			 
			 header('Location: ./funcaoitens.php?do=banco');die(); 
	
}
























function diretoparabp() {
global $topvar;
$topvar = true;
$qual = $_GET['qual'];

    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] != "In Town") {if ($userrow["currentaction"] == "Fighting"){header('Location: ./index.php?do=fight&conteudo=Você só pode acessar essa função dentro de uma cidade!');die();}else{header('Location: ./index.php?conteudo=Você só pode acessar essa função dentro de uma cidade!');die();} }
			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }


			$itensnobanco = explode(";",$userrow["bancogeral"]);
			$quantidade = count($itensnobanco) - 1;
			
		//enviar para backpack
	if ($userrow["bp4"] != "None") {$var = 1;}else{$bpcerto = "bp4";}
	if ($userrow["bp3"] != "None") {$var += 1;}else{$bpcerto = "bp3";}
	if ($userrow["bp2"] != "None") {$var += 1;}else{$bpcerto = "bp2";}
	if ($userrow["bp1"] != "None") {$var += 1;}else{$bpcerto = "bp1";}
	if ($var == 4){ header('Location: ./funcaoitens.php?do=banco&bi=Você não possui Slots Livres para Equipar e também não possui espaço livre em sua Mochila. Libere algum espaço.');die(); }
	
				$userrow[$bpcerto] = $itensnobanco[$qual];
	
	
				//retirando equipe do banco
				$userrow["bancogeral"] = "";
				$qcerto = $quantidade - 1; //numero de vezes
				for($i = 0; $i <= $qcerto; $i++){
				if ($i != $qual){//pra nao contar o item que está querendo retirar do banco.
				$userrow["bancogeral"] .= $itensnobanco[$i].";";
				}
				}
				
				
				//se o banco estiver zerado.
				if ($userrow["bancogeral"] == "") {$userrow["bancogeral"] = "None";}
				
				$updatequery = doquery("UPDATE {{table}} SET bp4='".$userrow["bp4"]."',bp3='".$userrow["bp3"]."',bp2='".$userrow["bp2"]."',bp1='".$userrow["bp1"]."', bancogeral='".$userrow["bancogeral"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
	
	
			header('Location: ./funcaoitens.php?do=banco'); die();
		
	
}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
function deletaritem() {
global $topvar;
$topvar = true;
$qual = $_GET['qual'];

    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] != "In Town") {if ($userrow["currentaction"] == "Fighting"){header('Location: ./index.php?do=fight&conteudo=Você só pode acessar essa função dentro de uma cidade!');die();}else{header('Location: ./index.php?conteudo=Você só pode acessar essa função dentro de uma cidade!');die();} }
			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }

			$itensnobanco = explode(";",$userrow["bancogeral"]);
			$quantidade = count($itensnobanco) - 1;

	
	
				//retirando equipe do banco
				$userrow["bancogeral"] = "";
				$qcerto = $quantidade - 1; //numero de vezes
				for($i = 0; $i <= $qcerto; $i++){
				if ($i != $qual){//pra nao contar o item que está querendo retirar do banco.
				$userrow["bancogeral"] .= $itensnobanco[$i].";";
				}
				}
				
				
				//se o banco estiver zerado.
				if ($userrow["bancogeral"] == "") {$userrow["bancogeral"] = "None";}
				
				$updatequery = doquery("UPDATE {{table}} SET  bancogeral='".$userrow["bancogeral"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
	
	
			header('Location: ./funcaoitens.php?do=banco&f=10'); die();
		
	
}



























function doarryou() {
global $topvar;
$topvar = true;
$qual = $_GET['qual'];

    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] != "In Town") {if ($userrow["currentaction"] == "Fighting"){header('Location: ./index.php?do=fight&conteudo=Você só pode acessar essa função dentro de uma cidade!');die();}else{header('Location: ./index.php?conteudo=Você só pode acessar essa função dentro de uma cidade!');die();} }
			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
			
			
			if ($userrow["level"] < 5) {
			$indexconteudo = "Você não pode acessar essa função se seu level for menor que 5!";
			$valorlib = 1; //para nao repetir o lib.php
			include('index.php');
			die();
			}



			if (isset($_POST["submit"])) {
        extract($_POST);
        $userquery = doquery("SELECT * FROM {{table}} WHERE charname='$jogador' LIMIT 1","users");
		
		

        if (mysql_num_rows($userquery) != 1) {  header('Location: ./funcaoitens.php?do=banco&br=Não existe nenhum jogador com esse Nome.');die(); }
        $userpara = mysql_fetch_array($userquery);
		if ($userrow["id"] == $userpara["id"]) { header('Location: ./funcaoitens.php?do=banco&br=Você não pode doar Ryou para si mesmo.');die();}
		if (!is_numeric($quantidaderyou)) { header('Location: ./funcaoitens.php?do=banco&br=A quantidade de Ryou deve ser um número.');die(); }
		$quantidaderyou = floor($quantidaderyou);
		/*if ($userrow["password"] != md5($oldpass)) { die("The old password you provided was incorrect."); }
        /*$realnewpass = md5($newpass1); */
		if ($quantidaderyou > $userrow["banco_grana"]) { header('Location: ./funcaoitens.php?do=banco&br=Você não pode doar mais do que a sua quantidade de Ryou no banco.');die(); }
		if ($quantidaderyou < 1) { header('Location: ./funcaoitens.php?do=banco&br=Você não pode doar menos que 1 Ryou.');die(); }
		
		$dinheirototal = $userpara["gold"] + $quantidaderyou;
		$dinheirousuariologadodepois = $userrow["banco_grana"] - $quantidaderyou;
				
		$updatequery = doquery("UPDATE {{table}} SET gold='$dinheirototal' WHERE charname='$jogador' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banco_grana='$dinheirousuariologadodepois' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	
	
			header('Location: ./funcaoitens.php?do=banco&br=Ryou enviado com sucesso.'); die();
		
	
}

}
?>