<?php // enche o hp.



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();

//status de recuperação
$recuperar = $userrow["recuperarvida"];
$acao = $userrow["currentaction"];








if ($acao == "Exploring") {

if ($recuperar == 1) {
global $topvar;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	

		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
			/*fim do teste */
		
 sleep(10);
 
$userrow["currenthp"] += 1;
$userrow["currentmp"] += 5;
if ($userrow["currenthp"] > $userrow["maxhp"]) {$userrow["currenthp"] = $userrow["maxhp"];}
if ($userrow["currentmp"] > $userrow["maxmp"]) {$userrow["currentmp"] = $userrow["maxmp"];}
$updatequery = doquery("UPDATE {{table}} SET currenthp='".$userrow["currenthp"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET currentmp='".$userrow["currentmp"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET recuperarvida='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET imagem='descansando.png' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
    $page = "Você recuperou 1 de HP e 5 de Chakra...<meta HTTP-EQUIV='refresh' CONTENT='1;URL=encherhp.php'><br><br><a href=\"index.php\">Parar de descansar</a>.";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Descansar", false, false, false); 
    
	
	}









$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
			/*fim do teste */
				


	

	$updatequery = doquery("UPDATE {{table}} SET recuperarvida='1' WHERE charname='$usuariologadonome' LIMIT 1","users");
	$updatequery = doquery("UPDATE {{table}} SET imagem='descansando.png' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
    $page = "Aguarde... Você está descansando...<meta HTTP-EQUIV='refresh' CONTENT='1;URL=encherhp.php'><br><br><a href=\"index.php\">Parar de descansar</a>.";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Descansar", false, false, false); 
    
}

















if ($acao == "In Town") {

if ($recuperar == 1) {
global $topvar;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	

		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
			/*fim do teste */
		
 sleep(3);
 
$userrow["currenthp"] += 1;
$userrow["currentmp"] += 5;
if ($userrow["currenthp"] > $userrow["maxhp"]) {$userrow["currenthp"] = $userrow["maxhp"];}
if ($userrow["currentmp"] > $userrow["maxmp"]) {$userrow["currentmp"] = $userrow["maxmp"];}
$updatequery = doquery("UPDATE {{table}} SET currenthp='".$userrow["currenthp"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET currentmp='".$userrow["currentmp"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET recuperarvida='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
    $page = "Você recuperou 1 de HP e 5 de Chakra...<meta HTTP-EQUIV='refresh' CONTENT='1;URL=encherhp.php'><br><br><a href=\"index.php\">Parar de descansar</a>.";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Descansar", false, false, false); 
    
	
	}









$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {display("Você não pode acessar essa função no meio de uma batalha!","Erro",false,false,false);die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
			/*fim do teste */
				


	

	$updatequery = doquery("UPDATE {{table}} SET recuperarvida='1' WHERE charname='$usuariologadonome' LIMIT 1","users");
	$updatequery = doquery("UPDATE {{table}} SET imagem='descansando.png' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
    $page = "Aguarde... Você está descansando...<meta HTTP-EQUIV='refresh' CONTENT='1;URL=encherhp.php'><br><br><a href=\"index.php\">Parar de descansar</a>.";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Descansar", false, false, false); 
    
}










?>