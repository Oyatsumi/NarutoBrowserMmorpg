<?php // users.php :: Handles user account functions.


/*$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);*/



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();



//mudar avatar
$action = $_GET['action'];
$nome = $userrow["charname"];

if ($action != "") {
if ($userrow["acesso"] >= 3) {header("Location: index.php?conteudo=GameMasters não podem mudar seu avatar.");die();}
if ((($action >= "13") || ($action != 10) || ($action != 2)) && ($userrow["acesso"] <= 0)) {header("Location: index.php?conteudo=Você só pode selecionar esse avatar se sua conta for uma conta VIP.");die();}
if ($action > 16) {header("Location: index.php?conteudo=Tentativa de trapaça detectada.");die();}
if ($action == "") {$action = 1;}
if (!is_numeric($action)) { display("O que você estava pensando? Quer bugar seu personagem?","Erro",false,false,false);die(); }
if ($action < 0) {$action = 1;}
if ($action > 17) {$action = 1;}
if ($action != 0) {$updatequery = doquery("UPDATE {{table}} SET avatar='$action' WHERE charname='$nome' LIMIT 1","users");
header("Location: index.php?conteudo=Avatar selecionado com sucesso.");}
//fim

}


if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "avatar") { avatar(); }
	
	       
}



function avatar() {
global $topvar;
$topvar = true;
global $userrow;


 $page = gettemplate("avatar");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Trocar Avatar", false, false, false); 

}


?>