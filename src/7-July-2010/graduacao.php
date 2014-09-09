<?php // users.php :: Handles user account functions.


/*$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);*/



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();


		

	

if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "graduar") { graduar(); }
	elseif ($do == "graduacao") { graduacao(); }
	}

function graduar() {
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
		$graduacao = $userrow["graduacao"];
		$missao = $userrow["missao"];
		



//cidade para imagem do kage
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





//graduar genin
if ($graduacao == "Estudante da Academia") {

if($missao < 5) {display("<center><img src=\"images/graduacao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Você precisa completar 5 Missões para graduar-se um Genin.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}


$graduacaoaberta = "Parabéns! Agora você é um <b>Genin</b>!<br>
Você ganhou:<br>
<ul>
<li />20 Pontos de Distribuição.
<li />10 Pontos de Vida.
<li />10 Pontos de Chakra.
</ul>";

$userrow["pontoatributos"] += 20;
$userrow["maxhp"] += 10;
$userrow["currenthp"] += 10;
$userrow["maxmp"] += 10;
$userrow["currentmp"] += 10;

$updatequery = doquery("UPDATE {{table}} SET pontoatributos='".$userrow["pontoatributos"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET maxhp='".$userrow["maxhp"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET currenthp='".$userrow["currenthp"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET maxmp='".$userrow["maxmp"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET currentmp='".$userrow["currentmp"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET graduacao='Genin' WHERE charname='$usuariologadonome' LIMIT 1","users");

}
//fim graduar genin












//graduar chuunin
if ($graduacao == "Genin") {


$foi = 0;
if ($userrow["slot1name"] == "Esperança do Chuunin") {$foi = 1;}
if ($userrow["slot2name"] == "Esperança do Chuunin") {$foi = 2;}
if ($userrow["slot3name"] == "Esperança do Chuunin") {$foi = 3;}


if($foi = 0) {display("<center><img src=\"images/graduacao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Você precisa trazer o item <font color=red>Esperança do Chuunin</font>.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}


$graduacaoaberta = "Parabéns! Agora você é um <b>Chuunin</b>!<br>
Você ganhou:<br>
<ul>
<li />30 Pontos de Distribuição.
<li />20 de Força.
<li />20 de Destreza.
</ul>";

$userrow["pontoatributos"] += 30;
$userrow["strength"] += 20;
$userrow["dexterity"] += 20;


$updatequery = doquery("UPDATE {{table}} SET pontoatributos='".$userrow["pontoatributos"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");

$updatequery = doquery("UPDATE {{table}} SET strength='".$userrow["strength"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET dexterity='".$userrow["dexterity"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET graduacao='Chuunin' WHERE charname='$usuariologadonome' LIMIT 1","users");


//tirando o item
$updatequery = doquery("UPDATE {{table}} SET slot".$foi."name='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET slot".$foi."id='0' WHERE charname='$usuariologadonome' LIMIT 1","users");


}
//fim graduar chuunin












//graduar jounin
if ($graduacao == "Chuunin") {


$foi = 0;
if ($userrow["slot1name"] == "Força Inútil") {$foi = 1;}
if ($userrow["slot2name"] == "Força Inútil") {$foi = 2;}
if ($userrow["slot3name"] == "Força Inútil") {$foi = 3;}
$maisfoi = 0;
if ($missao > 11) {$maisfoi = 5;}
if ($userrow["level"] > 24) {$maisfoi += 5;}

$dedicaofinal = $maisfoi + $foi;

if($dedicaofinal < 11) {display("<center><img src=\"images/graduacao.gif\"></center><center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><b>".$townrow["kage"]." diz:</b><br>
Você precisa completar 12 Missões, ser level 25 ou maior e trazer o Item <font color=red>Força Inútil</font> para graduar-se um Jounin.<br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>","Error"); die();}


$graduacaoaberta = "Parabéns! Agora você é um <b>Jounin</b>!<br>
Você ganhou:<br>
<ul>
<li />100 Pontos de Distribuição.
<li />5 de Força.
<li />Você ganhou o Item: <font color=red>Vitória do Jounin</font>.
</ul>";

$userrow["pontoatributos"] += 100;
$userrow["goldbonus"] += 10;
$userrow["expbonus"] += 10;


$updatequery = doquery("UPDATE {{table}} SET pontoatributos='".$userrow["pontoatributos"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET goldbonus='".$userrow["goldbonus"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET expbonus='".$userrow["expbonus"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET graduacao='Jounin' WHERE charname='$usuariologadonome' LIMIT 1","users");



//colocando a vitoria do jounin
$updatequery = doquery("UPDATE {{table}} SET slot".$foi."name='Vitória do Jounin' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET slot".$foi."id='43' WHERE charname='$usuariologadonome' LIMIT 1","users");


}
//fim graduar jounin















  
	if ($graduacaoaberta == ""){$missoesabertas2 = "Não há graduações disponíveis no momento.";}
	if ($graduacaoaberta != "") {$missoesabertas2 = "Espero que você se torne um grande ninja!";}
	
	//conteudo finalda pag
	$conteudofinal = "

<center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><br><b>".$townrow["kage"]." diz:</b><br>
$missoesabertas2<br>De qualquer forma, boa sorte no seu caminho ninja!<br>Você pode também voltar para a <a href=\"index.php\">tela principal</a>.</td></tr></table></center>";
//acaba aqui
	
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/graduacao.gif\" /></center></td></tr></table>
	$conteudofinal<br>$graduacaoaberta
";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Graduação", false, false, false); 
    
}











function graduacao() {
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
		$graduacao = $userrow["graduacao"];
		$missao = $userrow["missao"];


//cidade para imagem do kage
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




$page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/graduacao.gif\" /></center></td></tr></table>
	<center><table width=\"450\"><tr><td><img src=\"layoutnovo/avatares/kages/".$townrow["id"].".png\" align=\"left\"><br><br><b>".$townrow["kage"]." diz:</b><br>
Olá jovem aprendiz, você pode consultar a tabela de graduação abaixo. Ao clicar em graduar-se você será enviado para a página de graduação e será graduado automaticamente, caso possua os requerimentos.<br><br>Você pode voltar para <a href=\"index.php\">tela principal</a>.</td></tr></table></center>

<table cellpadding=\"5\" cellspacing=\"5\" width=\"100%\"><tr><td bgcolor=\"#FFFFFF\"><center><b>Genin</b> - Requerimentos</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Ser um Estudante da Academia</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Completar 5 Missões Ninjas</center></td></tr></table>

<table cellpadding=\"5\" cellspacing=\"5\" width=\"100%\"><tr><td bgcolor=\"#FFFFFF\"><center><b>Chuunin</b> - Requerimentos</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Ser um Genin</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Trazer o Item: <font color=red>Esperança do Chuunin</font></center></td></tr></table>

<table cellpadding=\"5\" cellspacing=\"5\" width=\"100%\"><tr><td bgcolor=\"#FFFFFF\"><center><b>Jounin</b> - Requerimentos</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Ser um Chuunin</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Completar 12 Missões Ninjas</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Ser level 25 ou maior</center></td></tr><tr><td bgcolor=\"#DED6BB\"><center>Trazer o Item: <font color=red>Força Inútil</font></center></td></tr></table>

<br>
<center><a href=\"graduacao.php?do=graduar\">Graduar-se</a>.</center>

";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Graduação", false, false, false); 



}




?>