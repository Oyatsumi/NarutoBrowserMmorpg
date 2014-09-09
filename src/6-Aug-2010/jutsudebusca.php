<?php // enche o hp.



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();




if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "jutsu") { jutsu(); }
	elseif ($do == "aprendendo2") { aprendendo2(); }
	elseif ($do == "usar") { usar(); }
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

$longitude = $userrow["longitude"];
$latitude = $userrow["latitude"];



if ($longitude != -130) {display("Você não pode usar essa função fora do País do Vento. Tentativa de trapaça detectada.","Erro",false,false,false); die();}
if ($latitude != -130) {display("Você não pode usar essa função fora do País do Vento. Tentativa de trapaça detectada.","Erro",false,false,false); die();}

$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysql_fetch_array($townquery);
if ($townrow["id"] != 5) {display("Você não pode usar essa função fora do País do Vento.","Error"); die();}


 $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/jutsudebusca.gif\" /></center></td></tr></table>
 
 <table><tr><td>
 <img src=\"layoutnovo/avatares/kazekage.png\" align=\"left\"><b>Kazekage diz:</b><br>
 Olá pequeno ninja, você veio até mim à procura do Jutsu de Busca? O Jutsu de busca é um jutsu que precisa de 2 horas para ser aperfeiçoado, treinando árduamente. Ao usar o jutsu, você recebe as coordenadas do jogador que está procurando...<br>
 Se mesmo assim você ainda não desistiu, podemos <a href=\"jutsudebusca.php?do=aprendendo2\">começar ou continuar agora mesmo</a> o treinamento.

<br><br><a href=\"index.php\">Voltar à pagina de jogo</a>.
</td></tr></table>
";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Jutsu de Busca", false, false, false); 

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

$longitude = $userrow["longitude"];
$latitude = $userrow["latitude"];

if ($longitude != -130) {display("Você não pode usar essa função fora do País do Vento. Tentativa de trapaça detectada.","Erro",false,false,false); die();}
if ($latitude != -130) {display("Você não pode usar essa função fora do País do Vento. Tentativa de trapaça detectada.","Erro",false,false,false); die();}

$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysql_fetch_array($townquery);
if ($townrow["id"] != 5) {display("Você não pode usar essa função fora do País do Vento.","Error"); die();}



		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
		$tempopacabar = $userrow["jutsudebusca"];
		$tempoacabar2 = $tempopacabar % 60; 
		$tempopacabarminutos = floor(($userrow["jutsudebusca"] % 3600) / 60);
		$tempopacabarhoras = floor($tempopacabar/3600);
		$jutsudebuscaswitch = $userrow["jutsudebuscaswitch"];
			/*fim do teste */
			

			
			
//acabou o jutsu aqui.			
if ($tempopacabar == 0) {


$jutsufinal = "<a href=\"jutsudebusca.php?do=usar\">Jutsu de Busca</a><br>";
$updatequery = doquery("UPDATE {{table}} SET jutsudebuscahtml='$jutsufinal' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET imagem='nacidade.png' WHERE charname='$usuariologadonome' LIMIT 1","users");


$page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/jutsudebusca.gif\" /></center></td></tr></table>
 
 <table><tr><td>
 <img src=\"layoutnovo/avatares/kazekage.png\" align=\"left\"><b>Kazekage diz:</b><br>
 Parabéns! Você aprendeu o Jutsu de Busca!<br>Jamais pensei que você fosse capaz de aperfeiçoar esse Jutsu, use-o com sabedoria.

<br><br><a href=\"index.php\">Voltar à pagina de jogo</a>.
</td></tr></table>


";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Jutsu de Busca", false, false, false); 

die();
}

	
			
			
			
			
			
			
			
			
			
			



sleep(8);
 
$userrow["jutsudebusca"] -= 10;
if ($userrow["jutsudebusca"] < 1) {$userrow["jutsudebusca"] = 0;}



$updatequery = doquery("UPDATE {{table}} SET jutsudebusca='".$userrow["jutsudebusca"]."' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET jutsudebuscaswitch='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET imagem='treinando.png' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
	
     $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/jutsudebusca.gif\" /></center></td></tr></table>
 
 <table><tr><td>
 <img src=\"layoutnovo/avatares/kazekage.png\" align=\"left\"><b>Kazekage diz:</b><br>
 Ainda faltam $tempopacabarhoras hora(s), $tempopacabarminutos minuto(s) e $tempoacabar2 segundos para acabar o treinamento.

<br><br><a href=\"index.php\">Voltar à pagina de jogo</a>.
</td></tr></table>

<meta HTTP-EQUIV='refresh' CONTENT='1;URL=jutsudebusca.php?do=aprendendo2'>

";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Jutsu de Busca", false, false, false); 


die();

			
			
			
				



    
}














function usar(){
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

if ($userrow["jutsudebuscahtml"] == "") {display("Você não pode usar esse jutsu sem ter treinado!","Erro",false,false,false);die(); }

if (isset($_POST["submit"])) {
        extract($_POST);
	
//dados do jogador da procura	
$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomedaprocura' LIMIT 1","users");
if (mysql_num_rows($userquery) != 1) { display("Não existe nenhuma conta com esse Nome.","Erro",false,false,false);die(); }
$userpara = mysql_fetch_array($userquery);


$mp = $userrow["currentmp"];
$usuariologadonome = $userrow["charname"];

$pagina = "";
if ($mp < 31) {$pagina = "Esse jutsu requer 30 de Chakra para ser usado.";}
$mpquesobrou = $mp - 30;

if ($pagina == "") {
$updatequery = doquery("UPDATE {{table}} SET currentmp='$mpquesobrou' WHERE charname='$usuariologadonome' LIMIT 1","users");
if ($userpara["longitude"] > 0) {$userpara["longitude"] .= "E";}
if ($userpara["latitude"] > 0) {$userpara["latitude"] .= "N";}
if ($userpara["longitude"] < 0) {$userpara["longitude"] *= -1; $userpara["longitude"] .= "W";}
if ($userpara["latitude"] < 0) {$userpara["latitude"] *= -1; $userpara["latitude"] .= "S";}
$pagina = "O jogador ".$userpara["charname"].", está na coordenada: ".$userpara["latitude"].", ".$userpara["longitude"].".";

//jogadores online:
	$usersqueryd = doquery("SELECT * FROM {{table}} WHERE UNIX_TIMESTAMP(onlinetime) >= '".(time()-600)."' AND charname='$nomedaprocura' LIMIT 1", "users");
if (mysql_num_rows($usersqueryd) != 1) {$pagina = $pagina."<br>Este jogador está <font color=red>Offline</font>.";}
else{$pagina = $pagina."<br>Este jogador está <font color=green>Online</font>.";}

}



 $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/jutsudebusca.gif\" /></center></td></tr></table>
 $pagina<br><br>
 Voltar para <a href=\"index.php\">tela principal</a>.";
 
  $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Jutsu de Busca", false, false, false);
 die();
}




 $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/jutsudebusca.gif\" /></center></td></tr></table>
<form action=\"jutsudebusca.php?do=usar\" method=\"post\">
Qual jogador você quer procurar a localização?<br><br>
Nome do Jogador:<br> <input type=\"text\" name=\"nomedaprocura\"><br>
<input type=\"submit\" name=\"submit\" value=\"Procurar!\">
</form>
<br><br>
Voltar à <a href=\"index.php\">tela principal</a>.
";

 $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Jutsu de Busca", false, false, false);
}





?>