<?php 

include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();





if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "troca") {troca(); }
	elseif ($do == "troca2") {troca2(); }
	elseif ($do == "troca3") {troca3(); }
	elseif ($do == "troca4") {troca4(); }

	
	}





function troca() {
global $topvar;
$topvar = true;
$qual = $_GET['qual'];
$jogador = $_GET['jogador'];
$msg = $_GET['msg'];
$html = $_GET['html'];

if ($jogador != ""){$msg = "Para realizar uma troca com o jogador ".$jogador.", pressione o botão Realizar Nova Troca.";}

    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }

			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
		
	$ryounomomento = $userrow["gold"];
		
		
	//tabela dos itens da mochila.
	$g = 1;
	for ($i = 1; $i < 5; $i++){
	if ($userrow["bp".$i] != "None"){
	$itemseparado = explode(",",$userrow["bp".$i]);
	//saber que tipo de arma é
	$img = "orb_img";
	if ($itemseparado[2] == 1) {$tipo = "weapon"; $img = "icon_weapon";}
	if ($itemseparado[2] == 2) {$tipo = "armor"; $img = "icon_armor";}
	if ($itemseparado[2] == 3) {$tipo = "shield"; $img = "icon_shield";}
	if ($itemseparado[2] > 3) {$qnumero = $i - 3; $tipo = "slot".$qnumero; $img = "orb";}
	if ($itemseparado[1] == "mp") {$img = "potion";}
	if ($itemseparado[1] == "hp") {$img = "potion";}
	if ($itemseparado[1] == "bp") {$img = "backpack_pequena";}
	if ($itemseparado[1] == "dia") {$img = "diamond";}
	if ($itemseparado[1] == "per") {$img = "parchment";}
	if ($itemseparado[1] == "hm") {$img = "potion";}
	if ($itemseparado[1] == "hmt") {$img = "potion";}
	if ($itemseparado[1] == "tp") {$img = "potion";}
	if ($itemseparado[1] == "bk") {$img = "book";}
	if ($itemseparado[3] == "X") {$dur = "*";}else{$dur = $itemseparado[3];}
	//fundo da tabela
	$g += 1;
	$fundo = $g % 2;
	if ($fundo == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
	//fim fundo tabela
		
	$tabelamochila .= "<tr bgcolor=\"$bgcolor\"><td><input type=\"radio\" id=\"qual\" name=\"qual\" value=\"$i\"></td>
	<td><img src=\"images/$img.gif\" title=\"Durabilidade: ".$dur."\"></td>
	<td>$itemseparado[0]</td></tr>";
		
	}//fim do if
	}//fim do for
	
	//se a mochila tiver em branco.
	if ($tabelamochila == ""){
	$tabelamochila = "<tr><td></td><td></td><td>Nenhum Item</td></tr>";
	}
	
	
					//msg de erro
					if ($msg != ""){
					$mostrarmsg = "<center><font color=brown>$msg</font></center><br>";
					}	
					
					
					
					
					//html mais.. ou seja, troca2 , pra ativar a troca 3
					if ($html == 1){
					
					if ($userrow["trocajogador1"] != "None" && $userrow["trocajogador2"] != "None"){
					if ($msg == "Aguardando o outro jogador no processo de troca."){header('Location: ./troca.php?do=troca&html=1');die(); }
					$img2 = "orb_img";
					$img1 = "orb_img";
							//mostrar os itens para troca e perguntar se aceita.
							$itemtrocaseparado = explode(";",$userrow["trocajogador1"]);
							if ($itemtrocaseparado[3] != "None"){//se a troca contiver um item.
							$subitemdividido = explode(",",$itemtrocaseparado[1]);
							
															//saber que tipo de arma é
								$img = "orb";
								if ($itemtrocaseparado[2] == 1) {$img1 = "icon_weapon";}
								if ($itemtrocaseparado[2] == 2) {$img1 = "icon_armor";}
								if ($itemtrocaseparado[2] == 3) {$img1 = "icon_shield";}
								if ($itemtrocaseparado[2] > 3) {$img1 = "orb";}
								if ($itemtrocaseparado[1] == "mp") {$img1 = "potion";}
								if ($itemtrocaseparado[1] == "hp") {$img1 = "potion";}
								if ($itemtrocaseparado[1] == "bp") {$img1 = "backpack_pequena";}
								if ($itemtrocaseparado[1] == "dia") {$img1 = "diamond";}
								if ($itemtrocaseparado[1] == "per") {$img1 = "parchment";}
								if ($itemtrocaseparado[1] == "hm") {$img1 = "potion";}
								if ($itemtrocaseparado[1] == "hmt") {$img1 = "potion";}
								if ($itemtrocaseparado[1] == "tp") {$img1 = "potion";}
								if ($itemtrocaseparado[3] == "X") {$dur1 = "*";}else{$dur1 = $itemtrocaseparado[3];}
							
							
							}else{$itemtrocaseparado[1] = "Nenhum Item";}//se não houve item.
							//mostrar os itens para troca e perguntar se aceita. JOGADOR 2
							$itemtrocaseparado2 = explode(";",$userrow["trocajogador2"]);
							if ($itemtrocaseparado2[3] != "None"){//se a troca contiver um item.
							$subitemdividido2 = explode(",",$itemtrocaseparado2[1]);
							
							
								//saber que tipo de arma é
								$img = "orb_img";
								if ($itemtrocaseparado2[2] == 1) {$img2 = "icon_weapon";}
								if ($itemtrocaseparado2[2] == 2) {$img2 = "icon_armor";}
								if ($itemtrocaseparado2[2] == 3) {$img2 = "icon_shield";}
								if ($itemtrocaseparado2[2] > 3) {$img2 = "orb";}
								if ($itemtrocaseparado2[1] == "mp") {$img2 = "potion";}
								if ($itemtrocaseparado2[1] == "hp") {$img2 = "potion";}
								if ($itemtrocaseparado2[1] == "bp") {$img2 = "backpack_pequena";}
								if ($itemtrocaseparado2[1] == "dia") {$img2 = "diamond";}
								if ($itemtrocaseparado2[1] == "per") {$img2 = "parchment";}
								if ($itemtrocaseparado2[1] == "hm") {$img2 = "potion";}
								if ($itemtrocaseparado2[1] == "hmt") {$img2 = "potion";}
								if ($itemtrocaseparado2[1] == "tp") {$img2 = "potion";}
								if ($itemtrocaseparado2[3] == "X") {$dur2 = "*";}else{$dur2 = $itemtrocaseparado2[3];}
							
							}else{$itemtrocaseparado2[1] = "Nenhum Item";}//se não houve item.
							
							
							$nomepaparecerali = $userrow["charname"];
							if ($subitemdividido[0] == ""){$subitemdividido[0] = "<font color=gray>Nenhum Item</font>";}
							if ($subitemdividido2[0] == ""){$subitemdividido2[0] = "<font color=gray>Nenhum Item</font>";}
							
							$htmlmais = "<center><table><tr bgcolor=\"#613003\"><td colspan=\"5\"><font color=white><center>Troca</center></font></td></tr>
							<tr bgcolor=\"#613003\"><td><font color=white>*</font></td><td><font color=white>".$nomepaparecerali."</font></td><td><font color=white>*</font></td><td><font color=white>*</font></td><td><font color=white>".$itemtrocaseparado[0]."</font></td></tr>
							<tr bgcolor=\"#FFF1C7\"><td><img src=\"images/$img1.gif\" title=\"Durabilidade: ".$dur1."\"></td><td>".$subitemdividido[0]."</td><td><img src=\"images/setadupla.gif\"></td><td><img src=\"images/$img2.gif\" title=\"Durabilidade: ".$dur2."\"></td><td>".$subitemdividido2[0]."</td></tr>
							<tr bgcolor=\"#E4D094\"><td><img src=\"images/ryou.gif\"></td><td>".$itemtrocaseparado[2]."</td><td><img src=\"images/setadupla.gif\"></td><td><img src=\"images/ryou.gif\"></td><td>".$itemtrocaseparado2[2]."</td></tr>
							<tr><td colspan=\"5\"><center><a href=\"troca.php?do=troca3\"><img src=\"images/aceitar.gif\" border=\"0\" title=\"Aceitar Troca\" alt=\"ACEITAR\"></a><a href=\"troca.php?do=troca\"><img border=\"0\" src=\"images/deletar.gif\" title=\"Aceitar Troca\" alt=\"CANCELAR\"></a></center></td></tr></table></center>";
							
							
					
					}else{//fim do trocajogador1 e 2 atualizar a pagina.
					
									
					$htmlmais = "<meta HTTP-EQUIV='refresh' CONTENT='2;URL=troca.php?html=1&do=troca&msg=Aguardando o outro jogador no processo de troca.'>";
					
					}//fim do else
					
					}//fim html
					
					
					
					
					
					
					if ($html == 2){
					
					$explodir = explode(";",$userrow["trocajogador1"]);
					$jogador = $explodir[0];
					
							//conferindo jogador
							$userquery2 = doquery("SELECT * FROM {{table}} WHERE charname='$jogador' LIMIT 1", "users");
							//if (mysql_num_rows($userquery2) != 1) { header('Location: ./troca.php?do=troca&msg=Houve um erro com o nome do outro jogador ao realizar a troca, por favor comece essa troca novamente.');die(); }
							$userpara = mysql_fetch_array($userquery2);
							
					if ($userpara["trocaswitch"] == 0){
					$htmlmais = "<meta HTTP-EQUIV='refresh' CONTENT='2;URL=troca.php?html=2&do=troca&msg=Aguardando o outro jogador aceitar a troca.'>";
						if ($userrow["trocajogador1"] == "None" || $userrow["trocajogador2"] == "None"){
								header('Location: ./troca.php?do=troca4');
						}
					
					}else{//fim trocaswitch = 0
					
					header('Location: ./troca.php?do=troca4');
					
					}//fim do else
					
					}//fim html = 2
					

	//pagina
	$page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/troca.gif\" /></center></td></tr></table>
	$mostrarmsg
			 <form action=\"troca.php?do=troca2\" method=\"post\">
			 <center><table><tr><td>
			 
			 <table bgcolor=\"#613003\"><tr><td><font color=white>Nome do Jogador:</font></td></tr>
			 <tr bgcolor=\"#E4D094\"><td><input type=\"text\" name=\"jogador\" size=\"20\" value=\"$jogador\"/></td></tr>
			 </table>
			 
			 <br>
			 <table bgcolor=\"#613003\"><td><font color=white>Ryou para Trocar:</font></td></tr>
			 <tr bgcolor=\"#FFF1C7\"><td valign=\"middle\"><center>Meu Ryou: $ryounomomento</center></td></tr>
			 <tr bgcolor=\"#E4D094\"><td><input type=\"text\" name=\"ryoutroca\" size=\"20\" /></td></tr>
			 </table>
			 
			 </td><td width=\"20\"></td><td>
			 <table><tr bgcolor=\"#613003\"><td colspan=\"3\"><font color=white>Escolha o Item(Mochila)</font></td></tr>
			 <tr bgcolor=\"#613003\"><td><font color=white>*</font></td><td><font color=white>*</font></td><td><font color=white>Nome</font></td></tr>
			 $tabelamochila
			 </table><br>
			 <center><input type=\"submit\" name=\"submit\" value=\"Realizar Nova Troca\" /></center>
			 </td></tr></table></form></center>
			 
			 $htmlmais";
			 
			 display($page, "Troca", false, false, false); 
	
}





















function troca2() {
global $topvar;
$topvar = true;


    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }

			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
		
	$ryounomomento = $userrow["gold"];
		
		
		if (isset($_POST["submit"])) {
        extract($_POST);
		//resetando
		$userrow["trocajogador1"] = "None";

		if($ryoutroca == ""){$ryoutroca = 0;}
		if($qual == ""){$qual = 0;}
		
	//conferindo jogador
	$userquery2 = doquery("SELECT * FROM {{table}} WHERE charname='$jogador' LIMIT 1", "users");
	if (mysql_num_rows($userquery2) != 1) { header('Location: ./troca.php?do=troca&msg=Não existe nenhum jogador com esse nome para realizar uma troca.');die(); }
    $userpara = mysql_fetch_array($userquery2);
	//quantidade de ryou
	if (!is_numeric($ryoutroca)){header('Location: ./troca.php?do=troca&msg=O campo quantidade de Ryou precisa ser um número ou precisa ser deixado em branco.');die(); }
	if ($ryoutroca < 0){header('Location: ./troca.php?do=troca&msg=O campo quantidade de Ryou precisa ser maior que 0.');die(); }
	if ($ryoutroca > $userrow["gold"]){header('Location: ./troca.php?do=troca&msg=Você não pode trocar mais Ryou que você possui.');die(); }
	//sem item na bp
	if ($userrow["bp".$qual] == "None"){header('Location: ./troca.php?do=troca&msg=Você não possui nenhum item no slot da backpack que selecionou.');die(); }
	if ($qual > 4){header('Location: ./troca.php?do=troca&msg=Erro ou tentativa de trapaça.');die(); }
	if ($qual < 0){header('Location: ./troca.php?do=troca&msg=Erro ou tentativa de trapaça.');die(); }
	if($qual == 0){$qual = "";}
	//mesmo mapa erro.
	if ($userrow["latitude"] != $userpara["latitude"]){header('Location: ./troca.php?do=troca&msg=Você precisa estar no mesmo mapa que o outro jogador para realizar uma troca.');die(); }
	if ($userrow["longitude"] != $userpara["longitude"]){header('Location: ./troca.php?do=troca&msg=Você precisa estar no mesmo mapa que o outro jogador para realizar uma troca.');die(); }
	//mesmo jogador.
	if ($userrow["charname"] == $userpara["charname"]){header('Location: ./troca.php?do=troca&msg=Você não pode realizar uma troca com você mesmo.');die(); }
	
	//se nao enviar ryou
	if ($ryoutroca == "") {$ryoutroca = 0;}
	
	if ($qual != ""){//se for enviar um item.
	//enviar para backpack do outro jogador
	if ($userpara["bp4"] != "None") {$var = 1;}else{$bpcerto = "bp4";}
	if ($userpara["bp3"] != "None") {$var += 1;}else{$bpcerto = "bp3";}
	if ($userpara["bp2"] != "None") {$var += 1;}else{$bpcerto = "bp2";}
	if ($userpara["bp1"] != "None") {$var += 1;}else{$bpcerto = "bp1";}
	if ($var == 4){ header("Location: ./troca.php?do=troca&msg=O jogador o qual você quer realizar uma troca, não possui espaços livres em sua Mochila. Peça-o para esvaziar um espaço.");die(); }
	
	
	$userrow["trocajogador1"] = $jogador.";".$userrow["bp".$qual].";".$ryoutroca.";".$bpcerto;
	$userpara["trocajogador2"] = $jogador.";".$userrow["bp".$qual].";".$ryoutroca.";".$bpcerto;
	
	}else{//fim do se for enviar um item. ///else é se não tiver envio de item
	
	$userrow["trocajogador1"] = $jogador.";None;".$ryoutroca.";None";
	$userpara["trocajogador2"] = $jogador.";None;".$ryoutroca.";None";
	
	}//fim else
	
	
	

	
	
	}//fim get form
	
	
		//histórico adicionando.. historico
	$historico = $userpara["historico"];
	if ($historico == "None"){
		$historico = "O jogador ".$userrow["charname"]." está te chamando para realizar uma troca. <a href=\"troca.php?do=troca&jogador=".$userrow["charname"]."\" target=\"_top\">ACEITAR</a>.";
	}else{
		$historico .= ";;O jogador ".$userrow["charname"]." está te chamando para realizar uma troca. <a href=\"troca.php?do=troca&jogador=".$userrow["charname"]."\" target=\"_top\">ACEITAR</a>.";
	}
	 $updatequery = doquery("UPDATE {{table}} SET historico='".$historico."' WHERE id='".$userpara["id"]."' LIMIT 1", "users");
	
	
	
	
	$updatequery = doquery("UPDATE {{table}} SET trocajogador1='".$userrow["trocajogador1"]."', trocajogador2='".$userrow["trocajogador2"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
	$updatequery = doquery("UPDATE {{table}} SET trocajogador1='".$userpara["trocajogador1"]."', trocajogador2='".$userpara["trocajogador2"]."' WHERE id='".$userpara["id"]."' LIMIT 1", "users");
	
	
	 header("Location: ./troca.php?do=troca&html=1&msg=Aguardando o outro jogador no processo de troca...");
	
}




































function troca3() {
global $topvar;
$topvar = true;


    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }

			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
		
	$ryounomomento = $userrow["gold"];
	
	if ($userrow["trocajogador1"] == "None" || $userrow["trocajogador2"] == "None"){
	header('Location: ./troca.php?do=troca&msg=Houve um erro na conclusão da troca, por favor recomece a troca novamente.');die(); }
	
	$separartroca = explode(";",$userrow["trocajogador1"]);
	$jogador = $separartroca[0];
		
	//conferindo jogador
	$userquery2 = doquery("SELECT * FROM {{table}} WHERE charname='$jogador' LIMIT 1", "users");
	if (mysql_num_rows($userquery2) != 1) { header('Location: ./troca.php?do=troca&msg=Houve um erro na conclusão da troca, por favor recomece a troca novamente.');die(); }
    $userpara = mysql_fetch_array($userquery2);
	//quantidade de ryou
	
	if ($userrow["latitude"] != $userpara["latitude"]){header('Location: ./troca.php?do=troca&msg=Você precisa estar no mesmo mapa que o outro jogador para concluir essa troca.');die(); }
	if ($userrow["longitude"] != $userpara["longitude"]){header('Location: ./troca.php?do=troca&msg=Você precisa estar no mesmo mapa que o outro jogador para concluir essa troca.');die(); }
	
	
	if ($userrow["trocajogador1"] != $userpara["trocajogador2"]){
	header('Location: ./troca.php?do=troca&msg=Houve um erro na conclusão da troca, por favor recomece a troca novamente.');die(); }
	if ($userrow["trocajogador2"] != $userpara["trocajogador1"]){
	header('Location: ./troca.php?do=troca&msg=Houve um erro na conclusão da troca, por favor recomece a troca novamente.');die(); }
	
	
	
		$updatequery = doquery("UPDATE {{table}} SET trocaswitch='1' WHERE id='".$userrow["id"]."' LIMIT 1", "users");

	
	 header("Location: ./troca.php?do=troca&html=2&msg=Aguardando o outro jogador aceitar a troca.");
	
}






































function troca4() {
global $topvar;
$topvar = true;


    /* testando se está logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }

			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
		
	$ryounomomento = $userrow["gold"];
	
	if ($userrow["trocajogador1"] == "None" || $userrow["trocajogador2"] == "None"){
	header('Location: ./troca.php?do=troca&msg=Troca realizada com sucesso.');die(); }
	
	$separartroca = explode(";",$userrow["trocajogador1"]);
	$jogador = $separartroca[0];
		
	//conferindo jogador
	$userquery2 = doquery("SELECT * FROM {{table}} WHERE charname='$jogador' LIMIT 1", "users");
	if (mysql_num_rows($userquery2) != 1) { header('Location: ./troca.php?do=troca&msg=Houve um erro na conclusão da troca, por favor recomece a troca novamente.');die(); }
    $userpara = mysql_fetch_array($userquery2);
	//quantidade de ryou
	
	if ($userrow["latitude"] != $userpara["latitude"]){header('Location: ./troca.php?do=troca&msg=Você precisa estar no mesmo mapa que o outro jogador para concluir essa troca.');die(); }
	if ($userrow["longitude"] != $userpara["longitude"]){header('Location: ./troca.php?do=troca&msg=Você precisa estar no mesmo mapa que o outro jogador para concluir essa troca.');die(); }
	
	
	if ($userrow["trocajogador1"] != $userpara["trocajogador2"]){
	header('Location: ./troca.php?do=troca&msg=Houve um erro na conclusão da troca, por favor recomece a troca novamente.');die(); }
	if ($userrow["trocajogador2"] != $userpara["trocajogador1"]){
	header('Location: ./troca.php?do=troca&msg=Houve um erro na conclusão da troca, por favor recomece a troca novamente.');die(); }
	
	
	

	
	if ($separartroca[3] != "None"){
	
	//saber qual é o slot da bp q ta o item da troca.
	$soma = 0;
	if ($userrow["bp1"] == $separartroca[1]) {$ocerto = "bp1";}else{$soma += 1;}
	if ($userrow["bp2"] == $separartroca[1]) {$ocerto = "bp2";}else{$soma += 1;}
	if ($userrow["bp3"] == $separartroca[1]) {$ocerto = "bp3";}else{$soma += 1;}
	if ($userrow["bp4"] == $separartroca[1]) {$ocerto = "bp4";}else{$soma += 1;}
	if ($soma == 4){header('Location: ./troca.php?do=troca&msg=Houve um erro na conclusão da troca, por favor recomece a troca novamente.');die(); }
	//fim saber backpack.
	
	
	$userpara[$separartroca[3]] = $separartroca[1];
	$userrow[$ocerto] = "None";
	}
	
	
	
	
	
	
	
	
	
	
	
	$separartroca2 = explode(";",$userrow["trocajogador2"]);
	
	
	if ($separartroca2[3] != "None"){
	
	//saber qual é o slot da bp q ta o item da troca.
	$soma = 0;
	if ($userpara["bp1"] == $separartroca2[1]) {$ocerto2 = "bp1";}else{$soma += 1;}
	if ($userpara["bp2"] == $separartroca2[1]) {$ocerto2 = "bp2";}else{$soma += 1;}
	if ($userpara["bp3"] == $separartroca2[1]) {$ocerto2 = "bp3";}else{$soma += 1;}
	if ($userpara["bp4"] == $separartroca2[1]) {$ocerto2 = "bp4";}else{$soma += 1;}
	if ($soma == 4){header('Location: ./troca.php?do=troca&msg=Houve um erro na conclusão da troca, por favor recomece a troca novamente.');die(); }
	//fim saber backpack.
	
	
	$userrow[$separartroca2[3]] = $separartroca2[1];
	$userpara[$ocerto2] = "None";
	}
	
	
	
	$userrow["gold"] += $separartroca2[2];
	$userrow["gold"] -= $separartroca[2];
	
	$userpara["gold"] += $separartroca[2];
	$userpara["gold"] -= $separartroca2[2];
	
	
		$updatequery = doquery("UPDATE {{table}} SET trocaswitch='0',gold='".$userrow["gold"]."',trocajogador1='None',trocajogador2='None',bp1='".$userrow["bp1"]."',bp2='".$userrow["bp2"]."',bp3='".$userrow["bp3"]."',bp4='".$userrow["bp4"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");


		$updatequery = doquery("UPDATE {{table}} SET trocaswitch='0',gold='".$userpara["gold"]."',trocajogador1='None',trocajogador2='None',bp1='".$userpara["bp1"]."',bp2='".$userpara["bp2"]."',bp3='".$userpara["bp3"]."',bp4='".$userpara["bp4"]."' WHERE id='".$userpara["id"]."' LIMIT 1", "users");

	
	 header("Location: ./troca.php?do=troca&msg=Troca realizada com sucesso.");
	
}


?>