<?php // login.php :: Handles logins and cookies.

include('lib.php');
    include('config.php');
    $link = opendb();




    
    if (isset($_POST["submit"])) {
	
       
        $query = doquery("SELECT * FROM {{table}} WHERE email='".$_POST["mail"]."' LIMIT 1", "users");
        if (mysql_num_rows($query) != 1) { display("Não há nenhuma conta registrada com esse e-mail.", "Error"); die(); }
        $row = mysql_fetch_array($query);

		 $updatequery = doquery("UPDATE {{table}} SET verify='1' WHERE email='".$_POST["mail"]."' LIMIT 1", "users");

      
	$page = "Sua conta foi ativada com sucesso.
	<br><br>
	Voltar para <a href=\"index.php\">página inicial</a>."
	;
    $title = "Ativar Conta";
    display($page, $title, false, false, false, false);
	die();
        
    }
    
    $page = "
	Para ativar sua conta e começar a jogar o Naruto! Nigeru RPG! Preencha o campo abaixo:<br><br>
	<form action=\"ativarconta.php\" method=\"post\">
	<center>Digite seu e-mail:<br>
	<input type=\"text\" size=\"40\" name=\"mail\" /><br><br>
	<input type=\"submit\" name=\"submit\" value=\"Ativar\" />
	</form></center>"
	;
    $title = "Ativar Conta";
    display($page, $title, false, false, false, false);
    


?>