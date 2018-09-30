<?php // login.php :: Handles logins and cookies.

include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();




    
    if (isset($_POST["submit"])) {
	
       
        $query = doquery("SELECT * FROM {{table}} WHERE email='".$_POST["mail"]."' LIMIT 1", "users");
        if (mysql_num_rows($query) != 1) { header("Location: ativarconta.php?conteudo=Não há uma conta registrada com esse e-mail."); die();}
        $row = mysql_fetch_array($query);

		 $updatequery = doquery("UPDATE {{table}} SET verify='1' WHERE email='".$_POST["mail"]."' LIMIT 1", "users");

      
	header("Location: ativarconta.php?conteudo=Sua conta foi/está ativada com sucesso."); die(); 
        
    }
	
	$conteudo = $_GET['conteudo'];
	$conteudo = "<font color=brown><center>".strip_tags($conteudo)."</font></center>";
    
    $page = "
	<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/ativarconta.gif\" /></center></td></tr></table>$conteudo
	<form action=\"ativarconta.php\" method=\"post\" id=\"formback\">
	<fieldset id=\"field2\"><legend>Ativar Conta</legend>
	Para ativar sua conta e começar a jogar o Naruto! Nigeru RPG! Preencha o campo abaixo:<br><br><center>
	<font color=brown>Lembrando que não é aconselhável ativar a sua conta por aqui. É sempre bom ter um e-mail para recuperar sua senha em casos extremos. De preferência, ative sua conta pelo seu e-mail.</font></center>
	<br>
	
	
	<center>Digite seu e-mail:<br>
	<input type=\"text\" size=\"40\" name=\"mail\" /><br><br>
	<div class=\"buttons\"><button type=\"submit\" class=\"positive\" name=\"submit\"><img src=\"layoutnovo/dropmenu/b1.gif\" alt=\"\"/> Ativar Conta</button></div>
	</fieldset></form></center>"
	;
    $title = "Ativar Conta";
    display($page, $title, false, false, false, false);
    


?>