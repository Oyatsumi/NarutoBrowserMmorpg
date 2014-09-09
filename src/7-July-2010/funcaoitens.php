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
	
	}
	



function banco() {
global $topvar;
$topvar = true;
$qual = $_GET['qual'];
$f = $_GET['f'];

    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] != "In Town") {display("Você só pode acessar essa função quando estiver em uma cidade! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
			if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
						
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
		
				$ryoudepositado = $userrow["banco_grana"];
		
			$dinheirototal = $usuriologadodinheiro + $ryoudepositado;
		
		
		if (isset($_POST["submit"])) {
        extract($_POST);
		
		
		
				//DEPOSITAR GRANA
		if ($deposito != "") { 
		if (!is_numeric($deposito)) {header('Location: /narutorpg/funcaoitens.php?do=banco&f=3');die(); }
		$deposito = floor($deposito);
		if ($deposito < 1) {header('Location: /narutorpg/funcaoitens.php?do=banco&f=4');die(); }
	
		$porcentagemconta = floor(90*$dinheirototal/100);
		$dinheirosuposto = $deposito + $ryoudepositado;
				if ($dinheirosuposto > 90*$dinheirototal/100) {header('Location: /narutorpg/funcaoitens.php?do=banco&f=5');die(); }
				if ($deposito > $usuriologadodinheiro) {header('Location: /narutorpg/funcaoitens.php?do=banco&f=6');die(); }
		
			
		$ryoudepositado += floor($deposito);
		$usuriologadodinheiro -= floor($deposito);
		
		$updatequery = doquery("UPDATE {{table}} SET banco_grana='$ryoudepositado' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET gold='$usuriologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");}
		
		
		
		//RETIRAR GRANA
		if ($retirar != "") {
		if (!is_numeric($retirar)) {header('Location: /narutorpg/funcaoitens.php?do=banco&f=7');die(); }
		$retirar = floor($retirar);
		if ($retirar < 1) {header('Location: /narutorpg/funcaoitens.php?do=banco&f=8');die(); }
		if ($retirar > 99999) { display("Você não pode retirar mais que 99999 Ryou.","Erro",false,false,false);die(); }
		if ($retirar > $ryoudepositado) { display("Você não pode retirar mais que seu dinheiro no banco.","Erro",false,false,false);die(); }
				
		$ryoudepositado -= floor($retirar);
		$usuriologadodinheiro += floor($retirar);
		
		$updatequery = doquery("UPDATE {{table}} SET banco_grana='$ryoudepositado' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET gold='$usuriologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		
		header('Location: /narutorpg/funcaoitens.php?do=banco');

		
		  die();
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
	$tabeladeequipamentos .= "<tr bgcolor=\"$bgcolor\"><td><img src=\"images/$img.gif\" /></td><td>".$userrow[$nome]."</td><td><a href=\"funcaoitens.php?do=depositar&qual=$i\"> <img border=\"0\" src=\"images/setapdireita.gif\" title=\"Depositar Item\" alt=\"->\" /></a></td></tr>";
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
	if ($mochilaseparar[2] == 1) {$img = "icon_weapon";}
	if ($mochilaseparar[2] == 2) {$img = "icon_armor";}
	if ($mochilaseparar[2] == 3) {$img = "icon_shield";}
	if ($mochilaseparar[2] > 3) {$img = "orb";}
	$tabelabackpack .= "<tr bgcolor=\"$bgcolor\"><td><img src=\"images/$img.gif\" /></td><td>".$mochilaseparar[0]."</td><td><a href=\"funcaoitens.php?do=depositarbp&qual=$j\"> <img border=\"0\" src=\"images/setapdireita.gif\" title=\"Depositar Item\" alt=\"->\" /></a></td></tr>";
	}
	$tabelabackpack .="</table>";
	
	//inicio tabela equips no banco.
	$tabeladobanco = "<table>
	<tr bgcolor=\"#452202\"><td colspan=4><font color=white>Itens no Banco</font></td></tr>
	<tr bgcolor=\"#613003\"><td><font color=white>*</font></td><td><font color=white>*</font></td><td><font color=white>*</font></td><td><font color=white>Nome</font></td></tr>";
	if ($userrow["bancogeral"] != "None"){
	$equipscomtudo = explode(";",$userrow["bancogeral"]);
	$quant = count($equipscomtudo) - 2;
	$i = 0;
	for ($i = 0; $i <= $quant ; $i++){
	$itemseparado = explode(",",$equipscomtudo[$i]);
	//saber que tipo de arma é
	if ($itemseparado[2] == 1) {$tipo = "weapon"; $img = "icon_weapon";}
	if ($itemseparado[2] == 2) {$tipo = "armor"; $img = "icon_armor";}
	if ($itemseparado[2] == 3) {$tipo = "shield"; $img = "icon_shield";}
	if ($itemseparado[2] > 3) {$qnumero = $i - 3; $tipo = "slot".$qnumero; $img = "orb";}
	//fundo da tabela
	$fundo = $i % 2;
	if ($fundo == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
	//fim fundo tabela
	$tabeladobanco .= "<tr bgcolor=\"$bgcolor\"><td><a href=\"funcaoitens.php?do=retirar&qual=$i\"><img src=\"images/setapesquerda.gif\" border=\"0\" title=\"Retirar Item\" alt=\"<-\" /></a></td>
	<td><a href=\"funcaoitens.php?do=banco&qual=$i\" title=\"Doar Item\" alt=\"X\" ><img src=\"images/gift.gif\" alt=\"X\" border=\"0\" /></a></td>
	<td><img src=\"images/$img.gif\" /></td>
	<td>".$itemseparado[0]."</td></tr>";
	
	}//fim for
	}//fim if
	else{$tabeladobanco .= "<tr bgcolor=\"#E4D094\"><td>*</td><td>*</td><td>*</td><td>Nenhum Item</font></td></tr>";}
	$tabeladobanco .= "</table>";
	//fim tabela do banco.
	
	
	
	
	//se for pra doar o item:
	if ($qual != ""){$tabeladobanco .= "<br><br><center><table><tr bgcolor=\"#613003\"><td><font color=white>Doar Item Para:</font></td></tr>
	<tr bgcolor=\"#FFF1C7\"><td><form action=\"funcaoitens.php?do=enviaritem&qual=$qual\" method=\"post\">
	<input type=\"text\" name=\"jogador\" size=\"20\" /><input type=\"submit\" name=\"submit\" value=\"OK!\" /></form></td></tr></table></center>";}
	
	
	
	//item enviado com sucesso
	if ($f == 1) {$mostraritemenviado = "<center><font color=brown>Seu Item foi Enviado com Sucesso.</font></center><br>";}
	if ($f == 3) {$mostrarpartecimaryou = "<center><font color=brown>A quantidade de Ryou à depositar deve ser um número.</font></center><br>";}
	if ($f == 4) {$mostrarpartecimaryou = "<center><font color=brown>Você não pode depositar menos que 1 Ryou.</font></center><br>";}
	if ($f == 5) {$mostrarpartecimaryou = "<center><font color=brown>Você não pode ter uma quantia maior que $porcentagemconta Ryou no Banco, que representa 90% do seu Ryou total, o que está depositado e o que está no seu personagem.</font></center><br>";}
	if ($f == 6) {$mostrarpartecimaryou = "<center><font color=brown>Você não pode depositar mais que a sua quantidade de Ryou.</font></center><br>";}
	if ($f == 7) {$mostrarpartecimaryou = "<center><font color=brown>A quantidade de Ryou à retirar deve ser um número.</font></center><br>";}
	if ($f == 8) {$mostrarpartecimaryou = "<center><font color=brown>Você não pode retirar menos que 1 Ryou.</font></center><br>";}
	
	
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/banco.gif\" /></center></td></tr></table>
	$mostrarpartecimaryou
	<center>
	<table><tr><td>
	<table><tr bgcolor=\"#452202\"><td><font color=white>Ryou Equipado</font></td></tr>
	<tr bgcolor=\"#FFF1C7\"><td>$usuriologadodinheiro</td></tr></table>
	</td><td>
	<table><tr bgcolor=\"#452202\"><td><font color=white>Ryou no Banco</font></td></tr>
	<tr bgcolor=\"#FFF1C7\"><td>$ryoudepositado</td></tr></table>
	</td></tr></table>
	</center>
	
	".gettemplate("banco")."<br>
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
		if ($userrow["currentaction"] != "In Town") {display("Você só pode acessar essa função quando estiver em uma cidade! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
			if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
			if ($qual > 6) {display("Tentativa de trapaça ou erro detectado.","Erro",false,false,false);die(); }
			if ($qual < 1) {display("Tentativa de trapaça ou erro detectado.","Erro",false,false,false);die(); }
			
			if ($userrow["bancogeral"] != "None") {//inicio
			$novavariavelteste = explode(";",$userrow["bancogeral"]);
			if (count($novavariavelteste) >= 30){display("Você não pode ter mais que 30 itens no Banco.","Erro",false,false,false);die(); }
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
	if ($userrow[$nome] == "None"){header('Location: /narutorpg/funcaoitens.php?do=banco');die(); }
		
		if ($qual <= 3) {//para weapon armor e shield
	$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow[$id]."' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
	
	if ($itemsrow["special"] != "X"){
	$atributo = explode(",",$itemsrow["special"]);
		
	$userrow[$atributo[0]] -= $atributo[1];
	}
	
	//verificar se é arma escudo ou armadura:
	if ($itemsrow["type"] == 1) {//ataque
	$userrow["attackpower"] -= $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$userrow[$nome]);
	if ($variavelpcontinuar[1] != ""){
	$userrow["attackpower"] -= $variavelpcontinuar[1]*15;//15 - de bonus
	}
	//fim dos bonus
	
	}
	else {//sendo de defesa
	$userrow["defensepower"] -= $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$userrow[$nome]);
	if ($variavelpcontinuar[1] != ""){
	$userrow["defensepower"] -= $variavelpcontinuar[1]*15;//15 - de bonus
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
	}
	
	
	//segundo:
	if ($droprow["attribute2"] != "X"){
	$atributo2 = explode(",",$droprow["attribute2"]);
	$userrow[$atributo2[0]] -= $atributo2[1];
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
	
	
	//adicionando os stats
	    $updatequery = doquery("UPDATE {{table}} SET durabilidade='".$userrow["durabilidade"]."',goldbonus='".$userrow["goldbonus"]."',expbonus='".$userrow["expbonus"]."',maxhp='".$userrow["maxhp"]."',maxmp='".$userrow["maxmp"]."',maxtp='".$userrow["maxtp"]."',strength='".$userrow["strength"]."',dexterity='".$userrow["dexterity"]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."', agilidade='".$userrow["agilidade"]."', determinacao='".$userrow["determinacao"]."', precisao='".$userrow["precisao"]."', sorte='".$userrow["sorte"]."', inteligencia='".$userrow["inteligencia"]."', bancogeral='".$userrow["bancogeral"]."', $nome='None', $id='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
		
	
	
	header('Location: /narutorpg/funcaoitens.php?do=banco');

	

	
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
		if ($userrow["currentaction"] != "In Town") {display("Você só pode acessar essa função quando estiver em uma cidade! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
			if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
			
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
	if ($var == 4){ display("Você já possui um(a) ".$frasemostrar." equipado(a) e não possui espaços livres na sua Mochila. Libere algum espaço.","Erro",false,false,false);die(); }
	
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
				
				$updatequery = doquery("UPDATE {{table}} SET bp4='".$userrow["bp4"]."',bp3='".$userrow["bp3"]."',bp2='".$userrow["bp2"]."',bp1='".$userrow["bp1"]."', bancogeral='".$userrow["bancogeral"]."' LIMIT 1", "users");
	
	
			header('Location: /narutorpg/funcaoitens.php?do=banco'); die();
				
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
	if ($var == 4){ display("Você não possui Slots Livres para Equipar e também não possui espaço livre em sua Mochila. Libere algum espaço.","Erro",false,false,false);die(); }
	
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
				
				$updatequery = doquery("UPDATE {{table}} SET bp4='".$userrow["bp4"]."',bp3='".$userrow["bp3"]."',bp2='".$userrow["bp2"]."',bp1='".$userrow["bp1"]."', bancogeral='".$userrow["bancogeral"]."' LIMIT 1", "users");
	
	
			header('Location: /narutorpg/funcaoitens.php?do=banco'); die();
		
		
		}else{ if($userrow["slot1id"] == 0){$slotcerto = "slot1";}elseif($userrow["slot2id"] == 0){$slotcerto = "slot2";}elseif($userrow["slot3id"] == 0){$slotcerto = "slot3";}}//fechou else2//dizer qual slot está livre.
	}//fim else1
	//fechou o fim se ja estiver equipado.	
		
		
	if ($itemseparado[2] <= 3) {//para weapon armor e shield e arma de atk
	$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$itemseparado[1]."' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
	
	if ($itemsrow["special"] != "X"){
	$atributo = explode(",",$itemsrow["special"]);
		
	$userrow[$atributo[0]] += $atributo[1];
	}
	
	//verificar se é arma escudo ou armadura:
	if ($itemsrow["type"] == 1) {//ataque
	$userrow["attackpower"] += $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$itemseparado[0]);
	if ($variavelpcontinuar[1] != ""){
	$userrow["attackpower"] += $variavelpcontinuar[1]*15;//15 + de bonus
	}
	//fim dos bonus
	
	}
	else {//sendo de defesa
	$userrow["defensepower"] += $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$itemseparado[0]);
	if ($variavelpcontinuar[1] != ""){
	$userrow["defensepower"] += $variavelpcontinuar[1]*15;//15 + de bonus
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
	}
	
	
	//segundo:
	if ($droprow["attribute2"] != "X"){
	$atributo2 = explode(",",$droprow["attribute2"]);
	$userrow[$atributo2[0]] += $atributo2[1];
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
	$durab = explode(",",$userrow["durabilidade"]);
	$durab[$qual] = $itemseparado[3];
	$userrow["durabilidade"] = "X,".$durab[1].",".$durab[2].",".$durab[3].",".$durab[4].",".$durab[5].",".$durab[6];
	
	
	}else{//aqui é se os itens forem somente para a backpack.
	if ($userrow["bp4"] != "None") {$var = 1;}else{$bpcerto = "bp4";}
	if ($userrow["bp3"] != "None") {$var += 1;}else{$bpcerto = "bp3";}
	if ($userrow["bp2"] != "None") {$var += 1;}else{$bpcerto = "bp2";}
	if ($userrow["bp1"] != "None") {$var += 1;}else{$bpcerto = "bp1";}
	if ($var == 4){ display("Você não possui espaços livres na sua Mochila. Libere algum espaço.","Erro",false,false,false);die(); }
	
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
	    $updatequery = doquery("UPDATE {{table}} SET bp4='".$userrow["bp4"]."',bp3='".$userrow["bp3"]."',bp2='".$userrow["bp2"]."',bp1='".$userrow["bp1"]."',durabilidade='".$userrow["durabilidade"]."',goldbonus='".$userrow["goldbonus"]."',expbonus='".$userrow["expbonus"]."',maxhp='".$userrow["maxhp"]."',maxmp='".$userrow["maxmp"]."',maxtp='".$userrow["maxtp"]."',strength='".$userrow["strength"]."',dexterity='".$userrow["dexterity"]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."', agilidade='".$userrow["agilidade"]."', determinacao='".$userrow["determinacao"]."', precisao='".$userrow["precisao"]."', sorte='".$userrow["sorte"]."', inteligencia='".$userrow["inteligencia"]."', bancogeral='".$userrow["bancogeral"]."', weaponname='".$userrow["weaponname"]."', shieldname='".$userrow["shieldname"]."', armorname='".$userrow["armorname"]."', slot1name='".$userrow["slot1name"]."', slot2name='".$userrow["slot2name"]."', slot3name='".$userrow["slot3name"]."', weaponid='".$userrow["weaponid"]."', shieldid='".$userrow["shieldid"]."', armorid='".$userrow["armorid"]."', slot1id='".$userrow["slot1id"]."', slot2id='".$userrow["slot2id"]."', slot3id='".$userrow["slot3id"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
	
	
	header('Location: /narutorpg/funcaoitens.php?do=banco');

	

	
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
		if ($userrow["currentaction"] != "In Town") {display("Você só pode acessar essa função quando estiver em uma cidade! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
			if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
			
		$quantidade = count($itensnobanco) - 1;

			
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
		
		
		if (isset($_POST["submit"])) {
        extract($_POST);
		
		
		
			$itensnobanco = explode(";",$userrow["bancogeral"]);
			$quantidade = count($itensnobanco) - 1;
			
			//fim
			if ($qual >= $quantidade) {display("Tentativa de trapaça ou erro detectado.","Erro",false,false,false);die(); }
			
	$userquery2 = doquery("SELECT * FROM {{table}} WHERE charname='$jogador' LIMIT 1", "users");
	if (mysql_num_rows($userquery2) != 1) { display("Não existe nenhum Jogador com esse Nome para Doar o Item.","Erro",false,false,false);die(); }
    $userpara = mysql_fetch_array($userquery2);
			
			
			//outro jogador
			if ($userpara["bancogeral"] != "None") {//inicio
			$novavariavelteste = explode(";",$userpara["bancogeral"]);
			if (count($novavariavelteste) >= 30){display("O Jogador o qual você quer enviar o Item, possui mais de 30 Itens no Banco.","Erro",false,false,false);die(); }
			}//fim
			if ($userrow["bancogeral"] == "None"){display("Você não pode doar Itens, você não tem nenhum no Banco.","Erro",false,false,false);die();}
		
		
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
	

	
	
	//adicionando os stats
	    $updatequery = doquery("UPDATE {{table}} SET bancogeral='".$userrow["bancogeral"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
		 $updatequery = doquery("UPDATE {{table}} SET bancogeral='".$userpara["bancogeral"]."' WHERE id='".$userpara["id"]."' LIMIT 1", "users");
	
	
	header('Location: /narutorpg/funcaoitens.php?do=banco&f=1');

	

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
		if ($userrow["currentaction"] != "In Town") {display("Você só pode acessar essa função quando estiver em uma cidade! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
			if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
			if ($qual > 4) {display("Tentativa de trapaça ou erro detectado.","Erro",false,false,false);die(); }
			if ($qual < 1) {display("Tentativa de trapaça ou erro detectado.","Erro",false,false,false);die(); }
			
			if ($userrow["bp".$qual] == "None") { header('Location: /narutorpg/funcaoitens.php?do=banco');die(); }
			
			if ($userrow["bancogeral"] != "None") {//inicio
			$novavariavelteste = explode(";",$userrow["bancogeral"]);
			if (count($novavariavelteste) >= 30){display("Você não pode ter mais que 30 itens no Banco.","Erro",false,false,false);die(); }
			}//fim
			
			
			//adicionando o item ao banco
			if ($userrow["bancogeral"]  == "None"){
			$userrow["bancogeral"] = $userrow["bp".$qual].";";}else{
			$userrow["bancogeral"] .= $userrow["bp".$qual].";";}
			
			
			//retirando o item
			$userrow["bp".$qual] = "None";
			
			 $updatequery = doquery("UPDATE {{table}} SET bancogeral='".$userrow["bancogeral"]."', bp$qual='".$userrow["bp".$qual]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
			 
			 header('Location: /narutorpg/funcaoitens.php?do=banco');die(); 
	
}


	
	
?>