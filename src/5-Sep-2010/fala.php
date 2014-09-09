<?php // users.php :: Handles user account functions.


/*$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);*/



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();


		

	

if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "falar") { falar(); }
	}

function falar() {
global $topvar;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
		global $userrow;

		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		if ($userrow["currentaction"] != "In Town") {display("Você só pode acessar essa função quando estiver em uma cidade! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
					if ($userrow["currentaction"] == "Fighting") {header('Location: /narutorpg/index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }					
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










//meio da missao 6
if ($missao2 == 6) {


	
if ($townrow["id"] != 4) {display("<center><img src=\"images/fala.gif\"></center><center><table width=\"450\"><tr><td><b>Você não pode usar essa função fora do Páis da Água.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}






$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='$usuariologadonome' LIMIT 1","users");


$falaaberta = "
<b>Mizukuri diz</b>:<br>
Olá forasteiro, você está procurando informação sobre Alquimia?<br>
Posso te contar o que eu sei sobre o assunto...<br>
O processo de Alquimia, foi usado na primeira grande guerra ninja, com objetivo de criar espadas de elemento Raiton, esse processo foi completado com sucesso, apesar da grande dificuldade... Durante o processo de Alquimia, você trabalha os compostos de dois materiais distintos e as junta em um só material...<br>
Bem... isso é tudo que eu sei...
";
}//fim 












//aldeã de konoha
if ($townrow["id"] == 1) {


	




$falaaberta = "
<b>Chouji diz</b>:<br>
Tome cuidado amigo, à medida que você se afasta mais do País do Fogo, inimigos mais fortes virão...<br>
Seja bem cauteloso...<br><br>
<b>Ino diz:</b><br>
Você não quer comprar flores?<br><br>
<b>Shizune diz:</b><br>
Você parece bem cansado...<br>
Descanse um pouco... Você vai se sentir melhor...<br><br>
<b>Kiba diz:</b><br>
A cara 5 quadrados para fora da cidade, em qualquer direção, o nível dos monstros sobe em 1...<br><br>
<b>Gai-sensei diz:</b><br>
Meu pupilo, o tempo para recuperar HP e Chakra dentro da cidade, é diferente do tempo pra recuperar fora dela...<br>
Na cidade, você demora 3 segundos pra encher 1 HP e fora das cidades, você demora 10 segundos...<br><br>
<b>Tenten diz:</b><br>
Na página de ajuda, você pode conferir as tabelas de monstros, itens e tudo mais!<br><br>
<b>Neji diz:</b><br>
Cuidado para não morrer!<br>
Se você morrer você perde metade do seu Ryou que está com você!";
}//fim 















//meio da missao 9
if ($missao2 == 9) {


	
if ($townrow["id"] != 2) {display("<center><img src=\"images/fala.gif\"></center><center><table width=\"450\"><tr><td><b>Você não pode usar essa função fora da Montanha Myoboku.</td></tr></table></center>","Error"); die();}
//acaba aquidisplay("Você não pode usar essa função fora do País do Fogo.","Error"); die();}






$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='$usuariologadonome' LIMIT 1","users");


$falaaberta = "
<b>Velho Sapo Ancião diz</b>:<br>
Olá meu jovem...<br>
Percebo que você quer ter informações sobre as Espadas Raiga...<br>
Hhmm...<br>
Tente não brincar com isso... As Espadas Raiga não são para brincadeira...<br>
Vou direto ao assunto...<br>
Um grande shinobi do País do Vento, conseguiu controlar as Espadas Raiga...<br>
Fazendo a alquimia de dois itens muito importantes... Um deles era uma Espada...<br>
Essa informação é tudo que fiquei sabendo na época...
";
}//fim 









//aldeã do vento
if ($townrow["id"] == 5) {


	




$falaaberta = "
<b>Temari diz</b>:<br>
A akatsuki está agindo...<br>
Melhor tomar cuidado...
";
}//fim 













  
	if ($falaaberta == ""){$falafinal = "Nenhum aldeão quis te dar atenção nesse país...";}

	

	
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/fala.gif\" /></center></td></tr></table>
	$falaaberta
	$falafinal<br><br>
	Você pode <a href=\"index.php\">retornar à tela principal</a>.
	
";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Procurar Informações", false, false, false); 
    
}














?>