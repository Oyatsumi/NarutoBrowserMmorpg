<?php // users.php :: Handles user account functions.


/*$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);*/



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();


		

	

if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "missao") { missao(); }
	elseif ($do == "missaotempo") { missaotempo(); }
	}

function missao() {
global $topvar;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
		global $userrow;

		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		if ($userrow["currentaction"] != "In Town") {display("Você só pode acessar essa função quando estiver em uma cidade! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
					if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }					
					if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

		//dados do personagem
		$usuariologadonome = $userrow["charname"];
		$usuariologadodinheiro = $userrow["gold"];
		
		
		$missao = $userrow["missao"];
		$longitude = $userrow["longitude"];
		$latitude = $userrow["latitude"];
		$missaoswitch = $userrow["missaoswitch"];
		$missaotimer = $userrow["missaotimer"];
		//outro é missaoswitch
		//missaotimer é o de tempo com padrão 600
$missao2 = $missao + 1;
//fim dos dados


$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysql_fetch_array($townquery);




 //imagem do hokage
 if($townrow["id"] == 1){$townrow["kage"] = "Hokage";}
 if($townrow["id"] == 2){$townrow["kage"] = "Fukasaku & Shima";}
 if($townrow["id"] == 3){$townrow["kage"] = "Tsuchikage";}
 if($townrow["id"] == 4){$townrow["kage"] = "Mizukage";}
 if($townrow["id"] == 5){$townrow["kage"] = "Kazekage";}
 if($townrow["id"] == 6){$townrow["kage"] = "Raikage";}
 if($townrow["id"] == 7){$townrow["kage"] = "Shodaime";}
 if($townrow["id"] == 8){$townrow["kage"] = "Tobi";} 






//inicio missao 1
if ($missao2 == 1) {


	
if ($townrow["id"] != 1) {display("<center><img src=\"images/missao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Não há missões nesse país no momento.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}

$townrow["kage"] = "Hokage";


$foi = 0;
if ($userrow["slot1name"] == "Missão 1: Fartura do Fogo") {$foi = 1;}
if ($userrow["slot2name"] == "Missão 1: Fartura do Fogo") {$foi = 2;}
if ($userrow["slot3name"] == "Missão 1: Fartura do Fogo") {$foi = 3;}


//nao completou
if ($foi == 0){
$missoesabertas = "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"5\"><tr><td bgcolor=\"#FFFFFF\"><center>
<b>Missão 1</b></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center><b>RANK D</b> - <font color=brown>Recompensa</font>: <font color=\"gray\">Nenhuma</font></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Traga ao País do Fogo o Item: 
<font color=brown>Missão 1: Fartura do Fogo</font>.</td></tr></table></center>";
}

//completou
if ($foi > 0){

$updatequery = doquery("UPDATE {{table}} SET slot".$foi."name='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET slot".$foi."id='0' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET missao='1' WHERE charname='$usuariologadonome' LIMIT 1","users");

$missoesabertas = "Parabéns! Você completou a Missão 1 com sucesso. Você pode <a href=\"index.php\">voltar a página inicial</a>, ou <a href=\"missoes.php?do=missao\">fazer mais missões</a>.<br>";}
}//fim missão 1















//inicio missao 2
if ($missao2 == 2) {


	
if ($townrow["id"] != 1) {display("<center><img src=\"images/missao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Não há missões nesse país no momento.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}

$townrow["kage"] = "Hokage";



$foi = 0;
if ($userrow["missaotimer"] == 0) {$foi = 1;}



//nao completou
if ($foi == 0){
$missoesabertas = "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"5\"><tr><td bgcolor=\"#FFFFFF\"><center>
<b>Missão 2</b></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center><b>RANK D</b> - <font color=brown>Recompensa</font>: 50 Ryou</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Nessa missão, você terá que recolher todo o lixo do país do fogo e levará 10 minutos pra completar essa missão.</td></tr></table></center><center><a href=\"missoes.php?do=missaotempo\">Começar ou continuar agora mesmo!</a></center>";


}

//completou
if ($foi > 0){

$usuariologadodinheiro += 50;

$updatequery = doquery("UPDATE {{table}} SET missaotimer='600' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET gold='$usuariologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET missao='2' WHERE charname='$usuariologadonome' LIMIT 1","users");

$missoesabertas = "Parabéns! Você completou a Missão 2 com sucesso e ganhou 50 Ryou de recompensa. Você pode <a href=\"index.php\">voltar a página inicial</a>, ou <a href=\"missoes.php?do=missao\">fazer mais missões</a>.<br>";}
}//fim missão 2


















//inicio missao 3
if ($missao2 == 3) {


	
if ($townrow["id"] != 1) {display("<center><img src=\"images/missao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Não há missões nesse país no momento.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}

$townrow["kage"] = "Hokage";



$foi = 0;
if ($userrow["missaotimer"] == 0) {$foi = 1;}



//nao completou
if ($foi == 0){
$missoesabertas = "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"5\"><tr><td bgcolor=\"#FFFFFF\"><center>
<b>Missão 3</b></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center><b>RANK D</b> - <font color=brown>Recompensa</font>: 100 Ryou</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Nessa missão, você terá que pintar o muro da casa de um fazendeiro, de branco, e levará 10 minutos pra completar essa missão.</td></tr></table></center><center><a href=\"missoes.php?do=missaotempo\">Começar ou continuar agora mesmo!</a></center>";


}

//completou
if ($foi > 0){

$usuariologadodinheiro += 100;

$updatequery = doquery("UPDATE {{table}} SET missaotimer='300' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET gold='$usuariologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET missao='3' WHERE charname='$usuariologadonome' LIMIT 1","users");

$missoesabertas = "Parabéns! Você completou a Missão 3 com sucesso e ganhou 100 Ryou de recompensa. Você pode <a href=\"index.php\">voltar a página inicial</a>, ou <a href=\"missoes.php?do=missao\">fazer mais missões</a>.<br>";}
}//fim missão 3














//inicio missao 4
if ($missao2 == 4) {


	
if ($townrow["id"] != 1) {display("<center><img src=\"images/missao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Não há missões nesse país no momento.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}

$townrow["kage"] = "Hokage";



$foi = 0;
if ($userrow["missaotimer"] == 0) {$foi = 1;}



//nao completou
if ($foi == 0){
$missoesabertas = "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"5\"><tr><td bgcolor=\"#FFFFFF\"><center>
<b>Missão 4</b></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center><b>RANK D</b> - <font color=brown>Recompensa</font>: 20 Ryou</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Nessa missão, você terá que arrumar o quarto de um dos aldeões e levará 5 minutos pra completar essa missão.</td></tr></table></center><center><a href=\"missoes.php?do=missaotempo\">Começar ou continuar agora mesmo!</a></center>";


}

//completou
if ($foi > 0){

$usuariologadodinheiro += 20;

$updatequery = doquery("UPDATE {{table}} SET missaotimer='600' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET gold='$usuariologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET missao='4' WHERE charname='$usuariologadonome' LIMIT 1","users");

$missoesabertas = "Parabéns! Você completou a Missão 4 com sucesso e ganhou 20 Ryou de recompensa. Você pode <a href=\"index.php\">voltar a página inicial</a>, ou <a href=\"missoes.php?do=missao\">fazer mais missões</a>.<br>";}
}//fim missão 4















//inicio missao 5
if ($missao2 == 5) {


	
if ($townrow["id"] != 1) {display("<center><img src=\"images/missao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Não há missões nesse país no momento.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}

$townrow["kage"] = "Hokage";



$foi = 0;
if ($userrow["slot1name"] == "Missão 5: Boneca Perdida") {$foi = 1;}
if ($userrow["slot2name"] == "Missão 5: Boneca Perdida") {$foi = 2;}
if ($userrow["slot3name"] == "Missão 5: Boneca Perdida") {$foi = 3;}



//nao completou
if ($foi == 0){
$missoesabertas = "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"5\"><tr><td bgcolor=\"#FFFFFF\"><center>
<b>Missão 5</b></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center><b>RANK D</b> - <font color=brown>Recompensa</font>: 100 Ryou</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Uma criança perdeu sua boneca, traga ao país do fogo o item: <font color=red>Missão 5: Boneca Perdida</font>.</td></tr></table></center>";


}

//completou
if ($foi > 0){

$usuariologadodinheiro += 100;

$updatequery = doquery("UPDATE {{table}} SET gold='$usuariologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");


$updatequery = doquery("UPDATE {{table}} SET slot".$foi."name='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET slot".$foi."id='0' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET missao='5' WHERE charname='$usuariologadonome' LIMIT 1","users");

$missoesabertas = "Parabéns! Você completou a Missão 5 com sucesso e recebeu 100 Ryou de recompensa. Você pode <a href=\"index.php\">voltar a página inicial</a>, ou <a href=\"missoes.php?do=missao\">fazer mais missões</a>.<br>";}

}//fim missão 5













//inicio missao 6
if ($missao2 == 6) {


	
if ($townrow["id"] != 1) {display("<center><img src=\"images/missao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Não há missões nesse país no momento.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}

$townrow["kage"] = "Hokage";



$foi = 0;
if ($userrow["missaoswitch"] == 1) {$foi = 1;}



//nao completou
if ($foi == 0){
$missoesabertas = "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"5\"><tr><td bgcolor=\"#FFFFFF\"><center>
<b>Missão 6</b></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center><b>RANK B</b> - <font color=brown>Recompensa</font>: 300 Ryou</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Vasculhe informações aldeões do País da Água, e portanto traga todo seu conhecimento sobre Alquimia que você encontrar para o País do Fogo.</td></tr></table></center>";


}

//completou
if ($foi > 0){

$usuariologadodinheiro += 300;

$updatequery = doquery("UPDATE {{table}} SET gold='$usuariologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET missaoswitch='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET missao='6' WHERE charname='$usuariologadonome' LIMIT 1","users");

$missoesabertas = "Parabéns! Você completou a Missão 6 com sucesso e recebeu 300 Ryou de recompensa. Você pode <a href=\"index.php\">voltar a página inicial</a>, ou <a href=\"missoes.php?do=missao\">fazer mais missões</a>.<br>";}

}//fim missão 6

















//inicio missao 7
if ($missao2 == 7) {


	
if ($townrow["id"] != 1) {display("<center><img src=\"images/missao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Não há missões nesse país no momento.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}

$townrow["kage"] = "Hokage";



$foi = 0;
if ($userrow["missaotimer"] == 0) {$foi = 1;}



//nao completou
if ($foi == 0){
$missoesabertas = "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"5\"><tr><td bgcolor=\"#FFFFFF\"><center>
<b>Missão 7</b></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center><b>RANK D</b> - <font color=brown>Recompensa</font>: 50 Ryou</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Nessa missão, você terá que ajudar uma família do Páis do Fogo a empurrar uma carroça que quebrou a roda na estrada até sua casa e levará 10 minutos para completar essa missão.</td></tr></table></center><center><a href=\"missoes.php?do=missaotempo\">Começar ou continuar agora mesmo!</a></center>";


}

//completou
if ($foi > 0){

$usuariologadodinheiro += 50;

$updatequery = doquery("UPDATE {{table}} SET missaotimer='3600' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET gold='$usuariologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET missao='7' WHERE charname='$usuariologadonome' LIMIT 1","users");

$missoesabertas = "Parabéns! Você completou a Missão 7 com sucesso e ganhou 100 Ryou de recompensa. Você pode <a href=\"index.php\">voltar a página inicial</a>, ou <a href=\"missoes.php?do=missao\">fazer mais missões</a>.<br>";}
}//fim missão 7














//inicio missao 8
if ($missao2 == 8) {


	
if ($townrow["id"] != 3) {display("<center><img src=\"images/missao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Não há missões nesse país no momento.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}

$townrow["kage"] = "Tsuchikage";



$foi = 0;
if ($userrow["missaotimer"] == 0) {$foi = 1;}



//nao completou
if ($foi == 0){
$missoesabertas = "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"5\"><tr><td bgcolor=\"#FFFFFF\"><center>
<b>Missão 8</b></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center><b>RANK C</b> - <font color=brown>Recompensa</font>: 500 Ryou</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Nessa missão, você terá construir uma casa para um dos aldeões rico do Páis da Terra e levará 1 hora para completar essa missão.</td></tr></table></center><center><a href=\"missoes.php?do=missaotempo\">Começar ou continuar agora mesmo!</a></center>";


}

//completou
if ($foi > 0){

$usuariologadodinheiro += 500;

$updatequery = doquery("UPDATE {{table}} SET missaotimer='3600' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET gold='$usuariologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET missao='8' WHERE charname='$usuariologadonome' LIMIT 1","users");

$missoesabertas = "Parabéns! Você completou a Missão 8 com sucesso e ganhou 500 Ryou de recompensa. Você pode <a href=\"index.php\">voltar a página inicial</a>, ou <a href=\"missoes.php?do=missao\">fazer mais missões</a>.<br>";}
}//fim missão 8















//inicio missao 9
if ($missao2 == 9) {


	
if ($townrow["id"] != 3) {display("<center><img src=\"images/missao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Não há missões nesse país no momento.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}

$townrow["kage"] = "Tsuchikage";



$foi = 0;
if ($userrow["missaoswitch"] == 1) {$foi = 1;}



//nao completou
if ($foi == 0){
$missoesabertas = "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"5\"><tr><td bgcolor=\"#FFFFFF\"><center>
<b>Missão 9</b></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center><b>RANK B</b> - <font color=brown>Recompensa</font>: 300 Ryou</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Espione na Montanha Mobyoku, qualquer informação que leve o País da Terra a chegar mais perto das Espadas Raiga.</td></tr></table></center>";


}

//completou
if ($foi > 0){

$usuariologadodinheiro += 300;

$updatequery = doquery("UPDATE {{table}} SET gold='$usuariologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET missaoswitch='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET missao='9' WHERE charname='$usuariologadonome' LIMIT 1","users");

$missoesabertas = "Parabéns! Você completou a Missão 9 com sucesso e recebeu 300 Ryou de recompensa. Você pode <a href=\"index.php\">voltar a página inicial</a>, ou <a href=\"missoes.php?do=missao\">fazer mais missões</a>.<br>";}

}//fim missão 9

















//inicio missao 10
if ($missao2 == 10) {


	
if ($townrow["id"] != 6) {display("<center><img src=\"images/missao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Não há missões nesse país no momento.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}

$townrow["kage"] = "Raikage";



$foi = 0;
if ($userrow["slot1name"] == "Missão 10: Rastro de Hachibi") {$foi = 1;}
if ($userrow["slot2name"] == "Missão 10: Rastro de Hachibi") {$foi = 2;}
if ($userrow["slot3name"] == "Missão 10: Rastro de Hachibi") {$foi = 3;}



//nao completou
if ($foi == 0){
$missoesabertas = "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"5\"><tr><td bgcolor=\"#FFFFFF\"><center>
<b>Missão 10</b></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center><b>RANK A</b> - <font color=brown>Recompensa</font>: 1000 Ryou</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Traga para o País do Trovão o Item: <font color=red>Missão 10: Rastro de Hachibi</font>.</td></tr></table></center>";


}

//completou
if ($foi > 0){

$usuariologadodinheiro += 1000;

$updatequery = doquery("UPDATE {{table}} SET gold='$usuariologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");


$updatequery = doquery("UPDATE {{table}} SET slot".$foi."name='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET slot".$foi."id='0' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET missao='10' WHERE charname='$usuariologadonome' LIMIT 1","users");

$missoesabertas = "Parabéns! Você completou a Missão 10 com sucesso e recebeu 1000 Ryou de recompensa. Você pode <a href=\"index.php\">voltar a página inicial</a>, ou <a href=\"missoes.php?do=missao\">fazer mais missões</a>.<br>";}

}//fim missão 10


























//inicio missao 11
if ($missao2 == 11) {


	
if ($townrow["id"] != 6) {display("<center><img src=\"images/missao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Não há missões nesse país no momento.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}

$townrow["kage"] = "Raikage";



$foi = 0;
if ($userrow["missaotimer"] == 0) {$foi = 1;}



//nao completou
if ($foi == 0){
$missoesabertas = "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"5\"><tr><td bgcolor=\"#FFFFFF\"><center>
<b>Missão 11</b></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center><b>RANK C</b> - <font color=brown>Recompensa</font>: 400 Ryou</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Você precisa ensinar os shinobis da academia do Trovão, alguns truques básicos de ninjutsu e levará 1 hora para compeltar essa missão.</td></tr></table></center><center><a href=\"missoes.php?do=missaotempo\">Começar ou continuar agora mesmo!</a></center>";


}

//completou
if ($foi > 0){

$usuariologadodinheiro += 400;

$updatequery = doquery("UPDATE {{table}} SET missaotimer='18000' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET gold='$usuariologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET missao='11' WHERE charname='$usuariologadonome' LIMIT 1","users");

$missoesabertas = "Parabéns! Você completou a Missão 11 com sucesso e ganhou 400 Ryou de recompensa. Você pode <a href=\"index.php\">voltar a página inicial</a>, ou <a href=\"missoes.php?do=missao\">fazer mais missões</a>.<br>";}
}//fim missão 11















//inicio missao 12
if ($missao2 == 12) {


	
if ($townrow["id"] != 5) {display("<center><img src=\"images/missao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Não há missões nesse país no momento.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}

$townrow["kage"] = "Kazekage";



$foi = 0;
if ($userrow["missaotimer"] == 0) {$foi = 1;}



//nao completou
if ($foi == 0){
$missoesabertas = "<center><table border=\"1\" cellpadding=\"5\" cellspacing=\"5\"><tr><td bgcolor=\"#FFFFFF\"><center>
<b>Missão 12</b></center></td></tr><tr><td bgcolor=\"#DED6BB\"><center><b>RANK B</b> - <font color=brown>Recompensa</font>: 1000 Ryou</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Nessa missão, você será o auxiliar de treinamento do Kazekage, e para completá-la você levará 5 horas.</td></tr></table></center><center><a href=\"missoes.php?do=missaotempo\">Começar ou continuar agora mesmo!</a></center>";


}

//completou
if ($foi > 0){

$usuariologadodinheiro += 1000;

$updatequery = doquery("UPDATE {{table}} SET missaotimer='3600' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET gold='$usuariologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET missao='12' WHERE charname='$usuariologadonome' LIMIT 1","users");

$missoesabertas = "Parabéns! Você completou a Missão 12 com sucesso e ganhou 1000 Ryou de recompensa. Você pode <a href=\"index.php\">voltar a página inicial</a>, ou <a href=\"missoes.php?do=missao\">fazer mais missões</a>.<br>";}
}//fim missão 12
















//}//fim das missões

			 
			 
			 
			 
			 
			 
			 
			
			 
			 
			 
			 
				
				
				
				
				
				
		
 /*//imagem do hokage
 if($townrow["id"] == 1){$townrow["kage"] = "Hokage";}
 if($townrow["id"] == 2){$townrow["kage"] = "Fukasaku & Shima";}
 if($townrow["id"] == 3){$townrow["kage"] = "Tsuchikage";}
 if($townrow["id"] == 4){$townrow["kage"] = "Mizukage";}
 if($townrow["id"] == 5){$townrow["kage"] = "Kazekage";}
 if($townrow["id"] == 6){$townrow["kage"] = "Raikage";}
 if($townrow["id"] == 7){$townrow["kage"] = "Shodaime";}
 if($townrow["id"] == 8){$townrow["kage"] = "Tobi";} */

  
	if ($missoesabertas == ""){$missoesabertas2 = "Não há missões disponíveis para você aqui.";}
	if ($missoesabertas != "") {$missoesabertas2 = "Há missões disponíveis em minha cidade.";}
	
	//conteudo finalda pag
	$conteudofinal = "

<center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><b>".$townrow["kage"]." diz:</b><br>
$missoesabertas2<br>De qualquer forma, boa sorte nas missões, pequeno aprendiz.<br>Você pode também voltar para a <a href=\"index.php\">tela principal</a>.</td></tr></table></center>";
//acaba aqui
	
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/missao.gif\" /></center></td></tr></table>
	$conteudofinal
	$missoesabertas
	
";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Missões", false, false, false); 
    
}










function missaotempo(){


global $topvar;
global $userrow;
$topvar = true;
 	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }


$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysql_fetch_array($townquery);

$usuariologadonome = $userrow["charname"];
$missaotemposwitch = $userrow["missaotemposwitch"];
$missaotimer = $userrow["missaotimer"];
$missaotimer2 =$missaotimer % 60;
$missaominutos = floor(($userrow["missaotimer"] % 3600)/60);

$missaohoras = floor($missaotimer/3600);



 //imagem do hokage
 if($townrow["id"] == 1){$townrow["kage"] = "Hokage";}
 if($townrow["id"] == 2){$townrow["kage"] = "Fukasaku & Shima";}
 if($townrow["id"] == 3){$townrow["kage"] = "Tsuchikage";}
 if($townrow["id"] == 4){$townrow["kage"] = "Mizukage";}
 if($townrow["id"] == 5){$townrow["kage"] = "Kazekage";}
 if($townrow["id"] == 6){$townrow["kage"] = "Raikage";}
 if($townrow["id"] == 7){$townrow["kage"] = "Shodaime";}
 if($townrow["id"] == 8){$townrow["kage"] = "Tobi";} 



if ($missaotimer == 0){$page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/missao.gif\" /></center></td></tr></table>



<table><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br>
<b>".$townrow["kage"]." diz:</b><br>
Você completou sua missão com sucesso.. <br>Você pode voltar para a <a href=\"missoes.php?do=missao\">página de missões</a>.<br>
<a href=\"index.php\">Pausar Missão</a>.</td></tr></table>";

 $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Completando Missões", false, false, false); 
	
die();

}










sleep(8);

$missaotimer -= 10;
if ($missaotimer < 0) { $missaotimer = 0;}

$updatequery = doquery("UPDATE {{table}} SET missaotemposwitch='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET missaotimer='$missaotimer' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET imagem='missao.png' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/missao.gif\" /></center></td></tr></table>
	
<table><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br>
<b>".$townrow["kage"]." diz:</b><br>
Você está completando sua missão.. <br>Ainda restam $missaohoras hora(s), $missaominutos minuto(s) e $missaotimer2 segundos para completar sua missão.<br>
<a href=\"index.php\">Pausar Missão</a>.</td></tr></table>

<meta HTTP-EQUIV='refresh' CONTENT='1;URL=missoes.php?do=missaotempo'>

";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Completando Missões", false, false, false); 

die();




}

?>