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
				if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
			/*fim do teste */
		
 sleep(10);
 
$userrow["currenthp"] += 1;
$userrow["currentmp"] += 2;
$userrow["currentnp"] += 1;
if ($userrow["currenthp"] > $userrow["maxhp"]) {$userrow["currenthp"] = $userrow["maxhp"];}
if ($userrow["currentmp"] > $userrow["maxmp"]) {$userrow["currentmp"] = $userrow["maxmp"];}
if ($userrow["currentnp"] > $userrow["maxnp"]) {$userrow["currentnp"] = $userrow["maxnp"];}
$updatequery = doquery("UPDATE {{table}} SET currentmp='".$userrow["currentmp"]."', currentnp='".$userrow["currentnp"]."',currenthp='".$userrow["currenthp"]."', recuperarvida='0' WHERE charname='$usuariologadonome' LIMIT 1","users");

	
    $indexconteudo = "Você recuperou 1 de HP, 2 de CH e 1 de NP...<meta HTTP-EQUIV='refresh' CONTENT='1;URL=encherhp.php'>";
	$valorlib = 1; //para nao repetir o lib.php
	$indexconteudo = "<center><table  bgcolor=\"#452202\"><tr><td width=\"18\"></td><td width=\"*\"><center><font color=white>Descansando</font></center></td><td width=\"18\"><a href=\"index.php\"><img src=\"images/deletar2.jpg\" title=\"Parar de Descansar\"  alt=\"X\" border=\"0\"></a></td></tr><tr><td background=\"layoutnovo/menumeio/meio2.png\" colspan=\"3\"><font color=\"black\"><center>".$indexconteudo."</center></font></td></tr></table></center>";
	include('index.php');
	die();
    
	
	}









$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
			/*fim do teste */
				


	

	$updatequery = doquery("UPDATE {{table}} SET recuperarvida='1' WHERE charname='$usuariologadonome' LIMIT 1","users");

	



    $indexconteudo = "Aguarde... Você está descansando...<meta HTTP-EQUIV='refresh' CONTENT='1;URL=encherhp.php'>";
	$valorlib = 1; //para nao repetir o lib.php
	$indexconteudo = "<center><table  bgcolor=\"#452202\"><tr><td width=\"18\"></td><td width=\"*\"><center><font color=white>Descansando</font></center></td><td width=\"18\"><a href=\"index.php\"><img src=\"images/deletar2.jpg\" title=\"Parar de Descansar\"  alt=\"X\" border=\"0\"></a></td></tr><tr><td background=\"layoutnovo/menumeio/meio2.png\" colspan=\"3\"><font color=\"black\"><center>".$indexconteudo."</center></font></td></tr></table></center>";
	include('index.php');
	die();
    
}






















elseif ($acao == "In Town") {

if ($recuperar == 1) {
global $topvar;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	

		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
			/*fim do teste */
		
 sleep(3);
 
$userrow["currenthp"] += 2;
$userrow["currentmp"] += 3;
$userrow["currentnp"] += 3;
if ($userrow["currenthp"] > $userrow["maxhp"]) {$userrow["currenthp"] = $userrow["maxhp"];}
if ($userrow["currentmp"] > $userrow["maxmp"]) {$userrow["currentmp"] = $userrow["maxmp"];}
if ($userrow["currentnp"] > $userrow["maxnp"]) {$userrow["currentnp"] = $userrow["maxnp"];}
$updatequery = doquery("UPDATE {{table}} SET currenthp='".$userrow["currenthp"]."',currentmp='".$userrow["currentmp"]."', currentnp='".$userrow["currentnp"]."',recuperarvida='0' WHERE charname='$usuariologadonome' LIMIT 1","users");

	
	
	
	
	    $indexconteudo = "Você recuperou 2 de HP, 3 de CH e 3 de NP...<meta HTTP-EQUIV='refresh' CONTENT='1;URL=encherhp.php'>";
	$valorlib = 1; //para nao repetir o lib.php
	$indexconteudo = "<center><table  bgcolor=\"#452202\"><tr><td width=\"18\"></td><td width=\"*\"><center><font color=white>Descansando</font></center></td><td width=\"18\"><a href=\"index.php\"><img src=\"images/deletar2.jpg\" title=\"Parar de Descansar\"  alt=\"X\" border=\"0\"></a></td></tr><tr><td background=\"layoutnovo/menumeio/meio2.png\" colspan=\"3\"><font color=\"black\"><center>".$indexconteudo."</center></font></td></tr></table></center>";
	include('index.php');
	die();

    
	
	}









$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
			/*fim do teste */
				


	

	$updatequery = doquery("UPDATE {{table}} SET recuperarvida='1' WHERE charname='$usuariologadonome' LIMIT 1","users");


	
		
	    $indexconteudo = "Aguarde... Você está descansando...<meta HTTP-EQUIV='refresh' CONTENT='1;URL=encherhp.php'>";
	$valorlib = 1; //para nao repetir o lib.php
	$indexconteudo = "<center><table  bgcolor=\"#452202\"><tr><td width=\"18\"></td><td width=\"*\"><center><font color=white>Descansando</font></center></td><td width=\"18\"><a href=\"index.php\"><img src=\"images/deletar2.jpg\" title=\"Parar de Descansar\"  alt=\"X\" border=\"0\"></a></td></tr><tr><td background=\"layoutnovo/menumeio/meio2.png\" colspan=\"3\"><font color=\"black\"><center>".$indexconteudo."</center></font></td></tr></table></center>";
	include('index.php');
	die();

}










else{
	
	if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
	
}









?>