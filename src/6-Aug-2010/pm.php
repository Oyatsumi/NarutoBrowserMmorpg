<?php // enche o hp.



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();




if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "enviar") { enviar(); }
	elseif ($do == "ler") { ler(); }
	}





function ler() {
global $userrow;
global $topvar;
$topvar = true;
if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		
		$usuariologadonome = $userrow["charname"];		
	
$caixademensagem = $userrow["caixadepm"];
$mensagemseparada = explode("§", $caixademensagem);

if ($mensagemseparada[0] == ""){$mensagemseparada[0] = "Não há mensagem aqui.";}
if ($mensagemseparada[1] == ""){$mensagemseparada[1] = "Não há mensagem aqui.";}
if ($mensagemseparada[2] == ""){$mensagemseparada[2] = "Não há mensagem aqui.";}
if ($mensagemseparada[3] == ""){$mensagemseparada[3] = "Não há mensagem aqui.";}
if ($mensagemseparada[4] == ""){$mensagemseparada[4] = "Não há mensagem aqui.";}
if ($mensagemseparada[5] == ""){$mensagemseparada[5] = "Não há mensagem aqui.";}
if ($mensagemseparada[6] == ""){$mensagemseparada[6] = "Não há mensagem aqui.";}
	

$paginafinal = $mensagemseparada[6]."<br><br>".$mensagemseparada[5]."<br><br>".$mensagemseparada[4]."<br><br>".$mensagemseparada[3]."<br><br>".$mensagemseparada[2]."<br><br>".$mensagemseparada[1]."<br><br>".$mensagemseparada[0];



	



	$updatequery = doquery("UPDATE {{table}} SET pmsnovas='' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/pm.gif\" /></center></td></tr></table>
 
<b>Sua caixa de mensagem</b>:<br><br><br>
$paginafinal
<br><br><br>
Você pode <a href=\"index.php\">voltar para a página principal</a>.
";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Jutsu de Busca", false, false, false); 
    
}














function enviar(){
$nomedochar = $_GET['nomedochar'];
global $topvar;
global $userrow;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		

if (isset($_POST["submit"])) {
        extract($_POST);
	
//dados do jogador da procura	
$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomedaprocura' LIMIT 1","users");
if (mysql_num_rows($userquery) != 1) { display("Não existe nenhum usuário com esse Nome.","Erro",false,false,false);die(); }
$userpara = mysql_fetch_array($userquery);

$usuariologadonome = $userrow["charname"];
$outrousuario = $userpara["charname"];

if ($userpara["caixadepm"] == "None") {$updatequery = doquery("UPDATE {{table}} SET caixadepm='§§§§§§' WHERE charname='$outrousuario' LIMIT 1","users");}


$caixademensagem = $userpara["caixadepm"];
$mensagemseparada = explode("§", $caixademensagem);

$novacaixademensagem = $mensagemseparada[1]."§".$mensagemseparada[2]."§".$mensagemseparada[3]."§".$mensagemseparada[4]."§".$mensagemseparada[5]."§".$mensagemseparada[6]."§<b><a href=\"pm.php?do=enviar&nomedochar=$usuariologadonome\">".$usuariologadonome."</a> disse</b>: ".$txtmensagem;

$mensagempiscando = "<blink><font color=red>Você tem novas mensagens.</font></blink><br><br>";

$updatequery = doquery("UPDATE {{table}} SET caixadepm='$novacaixademensagem' WHERE charname='$outrousuario' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET pmsnovas='$mensagempiscando' WHERE charname='$outrousuario' LIMIT 1","users");


 $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/pm.gif\" /></center></td></tr></table>
 Sua mensagem foi enviada com sucesso.<br><br>
 Voltar para <a href=\"index.php\">tela principal</a>.";
 
  $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Sistema de PM", false, false, false);
 die();
}




 $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/pm.gif\" /></center></td></tr></table>
<form action=\"pm.php?do=enviar\" method=\"post\">
Para qual jogador enviar a mensagem?<br><br>
Nome do Jogador:<br> <input type=\"text\" name=\"nomedaprocura\" value=\"$nomedochar\"><br>
Mensagem para o Jogador:<br><input name=\"txtmensagem\" type=\"text\" maxlength=\"100\" style=\"width:391px\"><br>
<font color=brown>Você só pode enviar mensagens com até 100 caracteres.</font><br>
<input type=\"submit\" name=\"submit\" value=\"Enviar!\">
</form>
<br>
<br>
Voltar à <a href=\"index.php\">tela principal</a>.
";

 $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Sistema de PM", false, false, false);
}





?>