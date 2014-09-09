<?php // enche o hp.



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();




if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "jutsu") { jutsu(); }
	elseif ($do == "aprendendo2") { aprendendo2(); }
	elseif ($do == "chamar") { chamar(); }
	}





function jutsu() {
global $userrow;
global $topvar;
$topvar = true;
if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
			if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }




$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysql_fetch_array($townquery);
	
if ($townrow["id"] != 2) {display("Você não pode usar essa função fora da Montanha Myoboku.","Error"); die();}


 $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/senjutsu.gif\" /></center></td></tr></table>
 
 <table><tr><td>
 <img src=\"layoutnovo/avatares/kages/2.png\" align=\"left\"><b>Fukasaku diz:</b><br>
 Olá pequeno ninja, você veio até mim à procura do Senjutsu? O Senjutsu é um tipo de jutsu que precisa de 15 horas de treinamento para ser aperfeiçoado, treinando árduamente. Ele o dará 40 de força e 40 de destreza para enfrentar o inimigo. Se mesmo assim você ainda não desistiu, podemos <a href=\"senjutsu.php?do=aprendendo2\">começar ou continuar agora mesmo</a> o treinamento.<br>


<br><a href=\"index.php\">Voltar à pagina de jogo</a>.
</td></tr></table>
";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Senjutsu", false, false, false); 

}
















function aprendendo2(){

global $topvar;
global $userrow;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }


$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysql_fetch_array($townquery);
	
if ($townrow["id"] != 2) {display("Você não pode usar essa função fora da Montanha Myoboku.","Error"); die();}

if ($userrow["graduacao"] == "Estudante da Academia") {display("Você não pode fazer esse treinamento se não for ao menos um Genin.","Error"); die();}



		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
		$tempopacabar = $userrow["senjutsutimer"];
		$tempoacabar2 = $tempopacabar % 60; 
		$tempopacabarminutos = floor(($userrow["senjutsutimer"] % 3600)/60);
		$tempopacabarhoras = floor($tempopacabar/3600);
		$jutsudebuscaswitch = $userrow["senjutsuswitch"];
			/*fim do teste */
			
			
			
			
//acabou o jutsu aqui.			
if ($tempopacabar == 0) {


$jutsufinal = "<font color=red>Usuário de Senjutsu</font><br>";
$updatequery = doquery("UPDATE {{table}} SET senjutsuhtml='$jutsufinal' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET imagem='senjutsu.png' WHERE charname='$usuariologadonome' LIMIT 1","users");


if ($jutsudebuscaswitch == 0) {$updatequery = doquery("UPDATE {{table}} SET senjutsuswitch='5' WHERE charname='$usuariologadonome' LIMIT 1","users");
$userrow["strength"] += 40;
$userrow["dexterity"] += 40;
$updatequery = doquery("UPDATE {{table}} SET strength='".$userrow["strength"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET dexterity='".$userrow["dexterity"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
}
elseif ($jutsudebuscaswitch == 1) {$updatequery = doquery("UPDATE {{table}} SET senjutsuswitch='5' WHERE charname='$usuariologadonome' LIMIT 1","users");
$userrow["strength"] += 40;
$userrow["dexterity"] += 40;
$updatequery = doquery("UPDATE {{table}} SET strength='".$userrow["strength"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET dexterity='".$userrow["dexterity"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
}


$page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/senjutsu.gif\" /></center></td></tr></table>
 
 <table><tr><td>
 <img src=\"layoutnovo/avatares/kages/2.png\" align=\"left\"><br><b>Fukasaku diz:</b><br>
 Parabéns! Você aprendeu e desenvolveu o Senjutsu com grande destreza!<br>Jamais pensei que você fosse capaz de aperfeiçoá-lo, seu senjutsu ficará ativado sempre. Sua força aumentou em 40 e sua destreza também aumentou em 40.

<br><br><a href=\"index.php\">Voltar à pagina de jogo</a>.
</td></tr></table>


";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Senjutsu", false, false, false); 

die();
}

	
			
			
			
			
			
			
			
			
			
			
			//intermediario



sleep(8);
 
$userrow["senjutsutimer"] -= 10;
if ($userrow["senjutsutimer"] < 1) {$userrow["senjutsutimer"] = 0;}



$updatequery = doquery("UPDATE {{table}} SET senjutsutimer='".$userrow["senjutsutimer"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET senjutsuswitch='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET imagem='senjutsu.png' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
	
     $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/senjutsu.gif\" /></center></td></tr></table>
 
 <table><tr><td>
 <img src=\"layoutnovo/avatares/kages/2.png\" align=\"left\"><br><b>Fukasaku diz:</b><br>
 Ainda faltam $tempopacabarhoras hora(s), $tempopacabarminutos minuto(s) e $tempoacabar2 segundos para acabar o treinamento.

<br><br><a href=\"index.php\">Voltar à pagina de jogo</a>.
</td></tr></table>

<meta HTTP-EQUIV='refresh' CONTENT='1;URL=senjutsu.php?do=aprendendo2'>

";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Senjutsu", false, false, false); 


die();

	    
}










function chamar() {
global $userrow;
global $topvar;
$topvar = true;
if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
			if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

$usuariologadonome = $userrow["charname"];


$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysql_fetch_array($townquery);
	
if ($townrow["id"] != 2) {display("Você não pode usar essa função fora da Montanha Myoboku.","Error"); die();}


	$updatequery = doquery("UPDATE {{table}} SET avatar='16' WHERE charname='$usuariologadonome' LIMIT 1","users");


 $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/grupar.gif\" /></center></td></tr></table>
 
 <table><tr><td>
 <img src=\"layoutnovo/avatares/kages/2.png\" align=\"left\"><br><br><b>Fukasaku diz:</b><br>
 Está precisando de mim? Você quer que eu entre no seu grupo? Não há problema, Shima e eu iremos com você em sua jornada. Agora fazemos parte do seu grupo!


<br><a href=\"index.php\">Voltar à pagina de jogo</a>.
</td></tr></table>
";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Chamar", false, false, false); 

}






?>