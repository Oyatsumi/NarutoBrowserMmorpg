<?php // enche o hp.



if ($valorlib == ""){//valor para nao redeclarar esses scripts.
include('lib.php');
$link = opendb();
include('cookies.php');
$userrow = checkcookies();
}



if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "enviar") { enviar(); }
	elseif ($do == "ler") { ler(); }
	}





function ler() {
global $userrow;
global $topvar,$conteudo;
$topvar = true;
if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		
		$usuariologadonome = $userrow["charname"];		
	
$caixademensagem = $userrow["caixadepm"];
$mensagemseparada = explode("§ªº", $caixademensagem);

if ($mensagemseparada[0] == ""){$mensagemseparada[0] = "Não há mensagem aqui.";}
if ($mensagemseparada[1] == ""){$mensagemseparada[1] = "Não há mensagem aqui.";}
if ($mensagemseparada[2] == ""){$mensagemseparada[2] = "Não há mensagem aqui.";}
if ($mensagemseparada[3] == ""){$mensagemseparada[3] = "Não há mensagem aqui.";}
if ($mensagemseparada[4] == ""){$mensagemseparada[4] = "Não há mensagem aqui.";}
if ($mensagemseparada[5] == ""){$mensagemseparada[5] = "Não há mensagem aqui.";}
if ($mensagemseparada[6] == ""){$mensagemseparada[6] = "Não há mensagem aqui.";}
	

$paginafinal = "<table width=\"100%\"><tr bgcolor=\"#613003\"><td><font color=white><center>Mensagens</center></font></td></tr><tr bgcolor=\"#FFF1C7\"><td>".$mensagemseparada[6]."</td></tr><tr bgcolor=\"#E4D094\"><td>".$mensagemseparada[5]."</td></tr><tr bgcolor=\"#FFF1C7\"><td>".$mensagemseparada[4]."</td></tr><tr bgcolor=\"#E4D094\"><td>".$mensagemseparada[3]."</td></tr><tr bgcolor=\"#FFF1C7\"><td>".$mensagemseparada[2]."</td></tr><tr  bgcolor=\"#E4D094\"><td>".$mensagemseparada[1]."</td></tr><tr bgcolor=\"#FFF1C7\"><td>".$mensagemseparada[0]."</td></tr></table>";



	



	$updatequery = doquery("UPDATE {{table}} SET pmsnovas='' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/pm.gif\" /></center></td></tr></table>
 $conteudo
$paginafinal

";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Jutsu de Busca", false, false, false); 
    
}














function enviar(){
$nomedochar = $_GET['nomedochar'];
global $topvar;
global $userrow,$conteudo;
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

if ($userpara["caixadepm"] == "None") {$updatequery = doquery("UPDATE {{table}} SET caixadepm='§ªº§ªº§ªº§ªº§ªº§ªº' WHERE charname='$outrousuario' LIMIT 1","users");}


$caixademensagem = $userpara["caixadepm"];
$mensagemseparada = explode("§ªº", $caixademensagem);

$novacaixademensagem = $mensagemseparada[1]."§ªº".$mensagemseparada[2]."§ªº".$mensagemseparada[3]."§ªº".$mensagemseparada[4]."§ªº".$mensagemseparada[5]."§ªº".$mensagemseparada[6]."§ªº<b><a href=\"pm.php?do=enviar&nomedochar=$usuariologadonome\">".$usuariologadonome."</a> disse</b>: ".$txtmensagem;

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

	$nomebotao = "botaomsg";
    $conteudo = "<center><form action=\"pm.php?do=enviar\" method=\"post\">
Nome do Jogador:<br>
<input type=\"text\" size=\"20\" name=\"nomedaprocura\" value=\"$nomedochar\"><br>
Mensagem para o Jogador:<br><input name=\"txtmensagem\" type=\"text\" maxlength=\"100\" style=\"width:291px\"><br>
<font color=brown>Você só pode enviar mensagens com até 100 caracteres.</font><br>
<input type=\"submit\" name=\"submit\" id=\"$nomebotao\" style=\"height:5px;\" value=\"\">
</form></center>

<script type=\"text/javascript\" language=\"JavaScript\">sumirbotao('".$nomebotao."');sumirbotao('".$nomebotao."');</script>";
	$valorlib = 1; //para nao repetir o lib.php
	$conteudo = "<center><table  bgcolor=\"#452202\"><tr><td width=\"18\"></td><td width=\"*\"><center><font color=white>Enviar Mensagem</font></center></td><td width=\"18\"><a href=\"pm.php?do=ler\"><img src=\"images/deletar2.jpg\" title=\"Fechar\"  alt=\"X\" border=\"0\"></a></td></tr><tr><td background=\"layoutnovo/menumeio/meio2.png\" colspan=\"3\"><font color=\"black\"><center>".$conteudo."</center></font></td></tr></table></center><br>";
	ler();
	die();


}





?>