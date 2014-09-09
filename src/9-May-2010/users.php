<?php // users.php :: Handles user account functions.

include('lib.php');
$link = opendb();

if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "register") { register(); }
    elseif ($do == "verify") { verify(); }
    elseif ($do == "lostpassword") { lostpassword(); }
    elseif ($do == "changepassword") { changepassword(); }
	elseif ($do == "doardinheiro") { doardinheiro(); }
	elseif ($do == "banco") { banco(); }
	elseif ($do == "doaritem") { doaritem(); }
	elseif ($do == "troca1") { troca1(); }
	elseif ($do == "troca2") { troca2(); }
	elseif ($do == "troca3") { troca3(); }
	elseif ($do == "troca4") { troca4(); }
    
}

function register() { // Register a new account.
    
    $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
    $controlrow = mysql_fetch_array($controlquery);
    
    if (isset($_POST["submit"])) {
        
        extract($_POST);
        
        $errors = 0; $errorlist = "";
        
        // Process username.
        if ($username == "") { $errors++; $errorlist .= "Username field is required.<br />"; }
        if (preg_match("/[^A-z0-9_\-]/", $username)==1) { $errors++; $errorlist .= "Username must be alphanumeric.<br />"; } // Thanks to "Carlos Pires" from php.net!
        $usernamequery = doquery("SELECT username FROM {{table}} WHERE username='$username' LIMIT 1","users");
        if (mysql_num_rows($usernamequery) > 0) { $errors++; $errorlist .= "Username already taken - unique username required.<br />"; }
        
        // Process charname.
        if ($charname == "") { $errors++; $errorlist .= "Character Name field is required.<br />"; }
        if (preg_match("/[^A-z0-9_\-]/", $charname)==1) { $errors++; $errorlist .= "Character Name must be alphanumeric.<br />"; } // Thanks to "Carlos Pires" from php.net!
        $characternamequery = doquery("SELECT charname FROM {{table}} WHERE charname='$charname' LIMIT 1","users");
        if (mysql_num_rows($characternamequery) > 0) { $errors++; $errorlist .= "Character Name already taken - unique Character Name required.<br />"; }
    
        // Process email address.
        if ($email1 == "" || $email2 == "") { $errors++; $errorlist .= "Email fields are required.<br />"; }
        if ($email1 != $email2) { $errors++; $errorlist .= "Emails don't match.<br />"; }
        if (! is_email($email1)) { $errors++; $errorlist .= "Email isn't valid.<br />"; }
        $emailquery = doquery("SELECT email FROM {{table}} WHERE email='$email1' LIMIT 1","users");
        if (mysql_num_rows($emailquery) > 0) { $errors++; $errorlist .= "Email already taken - unique email address required.<br />"; }
        
        // Process password.
        if (trim($password1) == "") { $errors++; $errorlist .= "Password field is required.<br />"; }
        if (preg_match("/[^A-z0-9_\-]/", $password1)==1) { $errors++; $errorlist .= "Password must be alphanumeric.<br />"; } // Thanks to "Carlos Pires" from php.net!
        if ($password1 != $password2) { $errors++; $errorlist .= "Passwords don't match.<br />"; }
        $password = md5($password1);
        
        if ($errors == 0) {
            
            if ($controlrow["verifyemail"] == 1) {
                $verifycode = "";
                for ($i=0; $i<8; $i++) {
                    $verifycode .= chr(rand(65,90));
                }
            } else {
                $verifycode='1';
            }
            
            $query = doquery("INSERT INTO {{table}} SET id='',regdate=NOW(),verify='$verifycode',username='$username',password='$password',email='$email1',charname='$charname',charclass='$charclass',difficulty='$difficulty'", "users") or die(mysql_error());
            
            if ($controlrow["verifyemail"] == 1) {
                if (sendregmail($email1, $verifycode) == true) {
                    $page = "Your account was created successfully.<br /><br />You should receive an Account Verification email shortly. You will need the verification code contained in that email before you are allowed to log in. Once you have received the email, please visit the <a href=\"users.php?do=verify\">Verification Page</a> to enter your code and start playing.";
                } else {
                    $page = "Your account was created successfully.<br /><br />However, there was a problem sending your verification email. Please check with the game administrator to help resolve this problem.";
                }
            } else {
                $page = "Your account was created succesfully.<br /><br />You may now continue to the <a href=\"login.php?do=login\">Login Page</a> and continue playing ".$controlrow["gamename"]."!";
            }
            
        } else {
            
            $page = "The following error(s) occurred when your account was being made:<br /><span style=\"color:red;\">$errorlist</span><br />Please go back and try again.";
            
        }
        
    } else {
        
        $page = gettemplate("register");
        if ($controlrow["verifyemail"] == 1) { 
            $controlrow["verifytext"] = "<br /><span class=\"small\">A verification code will be sent to the address above, and you will not be able to log in without first entering the code. Please be sure to enter your correct email address.</span>";
        } else {
            $controlrow["verifytext"] = "";
        }
        $page = parsetemplate($page, $controlrow);
        
    }
    
    $topnav = "<a href=\"login.php?do=login\"><img src=\"images/button_login.gif\" alt=\"Log In\" border=\"0\" /></a><a href=\"users.php?do=register\"><img src=\"images/button_register.gif\" alt=\"Register\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" border=\"0\" /></a>";
    display($page, "Register", false, false, false);
    
}

function verify() {
    
    if (isset($_POST["submit"])) {
        extract($_POST);
        $userquery = doquery("SELECT username,email,verify FROM {{table}} WHERE username='$username' LIMIT 1","users");
        if (mysql_num_rows($userquery) != 1) { die("No account with that username."); }
        $userrow = mysql_fetch_array($userquery);
        if ($userrow["verify"] == 1) { die("Your account is already verified."); }
        if ($userrow["email"] != $email) { die("Incorrect email address."); }
        if ($userrow["verify"] != $verify) { die("Incorrect verification code."); }
        // If we've made it this far, should be safe to update their account.
        $updatequery = doquery("UPDATE {{table}} SET verify='1' WHERE username='$username' LIMIT 1","users");
        display("Your account was verified successfully.<br /><br />You may now continue to the <a href=\"login.php?do=login\">Login Page</a> and start playing the game.<br /><br />Thanks for playing!","Verify Email",false,false,false);
    }
    $page = gettemplate("verify");
    $topnav = "<a href=\"login.php?do=login\"><img src=\"images/button_login.gif\" alt=\"Log In\" border=\"0\" /></a><a href=\"users.php?do=register\"><img src=\"images/button_register.gif\" alt=\"Register\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" border=\"0\" /></a>";
    display($page, "Verify Email", false, false, false);
    
}

function lostpassword() {
    
    if (isset($_POST["submit"])) {
        extract($_POST);
        $userquery = doquery("SELECT email FROM {{table}} WHERE email='$email' LIMIT 1","users");
        if (mysql_num_rows($userquery) != 1) { die("No account with that email address."); }
        $newpass = "";
        for ($i=0; $i<8; $i++) {
            $newpass .= chr(rand(65,90));
        }
        $md5newpass = md5($newpass);
        $updatequery = doquery("UPDATE {{table}} SET password='$md5newpass' WHERE email='$email' LIMIT 1","users");
        if (sendpassemail($email,$newpass) == true) {
            display("Your new password was emailed to the address you provided.<br /><br />Once you receive it, you may <a href=\"login.php?do=login\">Log In</a> and continue playing.<br /><br />Thank you.","Lost Password",false,false,false);
        } else {
            display("There was an error sending your new password.<br /><br />Please check with the game administrator for more information.<br /><br />We apologize for the inconvience.","Lost Password",false,false,false);
        }
        die();
    }
    $page = gettemplate("lostpassword");
    $topnav = "<a href=\"login.php?do=login\"><img src=\"images/button_login.gif\" alt=\"Log In\" border=\"0\" /></a><a href=\"users.php?do=register\"><img src=\"images/button_register.gif\" alt=\"Register\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" border=\"0\" /></a>";
    display($page, "Lost Password", false, false, false);
    
}

function changepassword() {
    
    if (isset($_POST["submit"])) {
        extract($_POST);
        $userquery = doquery("SELECT * FROM {{table}} WHERE username='$username' LIMIT 1","users");
        if (mysql_num_rows($userquery) != 1) { die("No account with that username."); }
        $userrow = mysql_fetch_array($userquery);
        if ($userrow["password"] != md5($oldpass)) { die("The old password you provided was incorrect."); }
        if (preg_match("/[^A-z0-9_\-]/", $newpass1)==1) { die("New password must be alphanumeric."); } // Thanks to "Carlos Pires" from php.net!
        if ($newpass1 != $newpass2) { die("New passwords don't match."); }
        $realnewpass = md5($newpass1);
        $updatequery = doquery("UPDATE {{table}} SET password='$realnewpass' WHERE username='$username' LIMIT 1","users");
        if (isset($_COOKIE["dkgame"])) { setcookie("dkgame", "", time()-100000, "/", "", 0); }
        display("Your password was changed successfully.<br /><br />You have been logged out of the game to avoid cookie errors.<br /><br />Please <a href=\"login.php?do=login\">log back in</a> to continue playing.","Change Password",false,false,false);
        die();
    }
    $page = gettemplate("changepassword");
    $topnav = "<a href=\"login.php?do=login\"><img src=\"images/button_login.gif\" alt=\"Log In\" border=\"0\" /></a><a href=\"users.php?do=register\"><img src=\"images/button_register.gif\" alt=\"Register\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" border=\"0\" /></a>";
    display($page, "Change Password", false, false, false); 
    
}

function sendpassemail($emailaddress, $password) {
    
    $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
    $controlrow = mysql_fetch_array($controlquery);
    extract($controlrow);
    
$email = <<<END
You or someone using your email address submitted a Lost Password application on the $gamename server, located at $gameurl. 

We have issued you a new password so you can log back into the game.

Your new password is: $password

Thanks for playing.
END;

    $status = mymail($emailaddress, "$gamename Lost Password", $email);
    return $status;
    
}

function sendregmail($emailaddress, $vercode) {
    
    $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
    $controlrow = mysql_fetch_array($controlquery);
    extract($controlrow);
    $verurl = $gameurl . "?do=verify";
    
$email = <<<END
You or someone using your email address recently signed up for an account on the $gamename server, located at $gameurl.

This email is sent to verify your registration email. In order to begin using your account, you must verify your email address. 
Please visit the Verification Page ($verurl) and enter the code below to activate your account.
Verification code: $vercode

If you were not the person who signed up for the game, please disregard this message. You will not be emailed again.
END;

    $status = mymail($emailaddress, "$gamename Account Verification", $email);
    return $status;
    
}

function mymail($to, $title, $body, $from = '') { // thanks to arto dot PLEASE dot DO dot NOT dot SPAM at artoaaltonen dot fi.

    $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
    $controlrow = mysql_fetch_array($controlquery);
    extract($controlrow);
    

  $from = trim($from);

  if (!$from) {
   $from = '<'.$controlrow["adminemail"].'>';
  }

  $rp    = $controlrow["adminemail"];
  $org    = '$gameurl';
  $mailer = 'PHP';

  $head  = '';
  $head  .= "Content-Type: text/plain \r\n";
  $head  .= "Date: ". date('r'). " \r\n";
  $head  .= "Return-Path: $rp \r\n";
  $head  .= "From: $from \r\n";
  $head  .= "Sender: $from \r\n";
  $head  .= "Reply-To: $from \r\n";
  $head  .= "Organization: $org \r\n";
  $head  .= "X-Sender: $from \r\n";
  $head  .= "X-Priority: 3 \r\n";
  $head  .= "X-Mailer: $mailer \r\n";

  $body  = str_replace("\r\n", "\n", $body);
  $body  = str_replace("\n", "\r\n", $body);

  return mail($to, $title, $body, $head);
  
}


















function doardinheiro() {
    /* testando se está logado */
		include('cookies.php');
		$userrow = checkcookies();
		if ($userrow == false) { die("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação."); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
			/*fim do teste */
				
	/* OLDPASS É A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
        $userquery = doquery("SELECT * FROM {{table}} WHERE charname='$username' LIMIT 1","users");
		
		

        if (mysql_num_rows($userquery) != 1) { die("Não existe nenhuma conta com esse Nome."); }
        $userrow = mysql_fetch_array($userquery);
		if ($usuariologadoid == $userrow["id"]) { die("Você não pode doar Ryou para si mesmo.");}
		if (!is_numeric($oldpass)) { die("A quantidade de Ryou deve ser um número."); }
		/*if ($userrow["password"] != md5($oldpass)) { die("The old password you provided was incorrect."); }
        /*$realnewpass = md5($newpass1); */
		if ($oldpass > $usuriologadodinaheiro) { die("Você não pode doar mais do que a sua quantidade de Ryou."); }
		if ($oldpass < 1) { die("Você não pode doar menos que 1 Ryou."); }
		$dinheirototal = $userrow["gold"] + $oldpass;
		$dinheirousuariologadodepois = $usuriologadodinaheiro - $oldpass;
				
		$updatequery = doquery("UPDATE {{table}} SET gold='$dinheirototal' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET gold='$dinheirousuariologadodepois' WHERE charname='$usuariologadonome' LIMIT 1","users");
        
				
        display("O dinheiro foi retirado da sua conta e doado com sucesso.<br /><br />Você pode <a href=\"index.php\">clicar aqui</a> para continuar jogando ou <a href=\"users.php?do=doardinheiro\">doar mais Ryou</a>.","Doar Ryou",false,false,false);
        die();
    }
    $page = "<b>Seu Ryou</b>: $usuriologadodinaheiro<br><br>".gettemplate("doardinheiro");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Doar Ryou", false, false, false); 
    
}











function banco() {
    /* testando se está logado */
		include('cookies.php');
		$userrow = checkcookies();
		if ($userrow == false) { die("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação."); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
		
		$armaid = $userrow["weaponid"];
		$armaduraid = $userrow["armorid"];
		$escudoid = $userrow["shieldid"];
		$arma = $userrow["weaponname"];
		$armadura = $userrow["armorname"];
		$escudo = $userrow["shieldname"];
		
		$idslot1 = $userrow["slot1id"];
		$idslot2 = $userrow["slot2id"];
		$idslot3 = $userrow["slot3id"];
		$nomeslot1 = $userrow["slot1name"];
		$nomeslot2 = $userrow["slot2name"];
		$nomeslot3 = $userrow["slot3name"];
		
		/* BANCO DE TROCAS */
		$banco1 = $userrow["banconome1"];
		$banco2 = $userrow["banconome2"];
		$banco3 = $userrow["banconome3"];
		$banco4 = $userrow["banconome4"];
		$banco5 = $userrow["banconome5"];
		$banco6 = $userrow["banconome6"];
		
		$banco1id = $userrow["bancoid1"];
		$banco2id = $userrow["bancoid2"];
		$banco3id = $userrow["bancoid3"];
		$banco4id = $userrow["bancoid4"];
		$banco5id = $userrow["bancoid5"];
		$banco6id = $userrow["bancoid6"];
		
		/* ARMAZENAMENTO */
		
		$b2banco1id = $userrow["b2_bancoid1"];
		$b2banco2id = $userrow["b2_bancoid2"];
		$b2banco3id = $userrow["b2_bancoid3"];
		$b2banco4id = $userrow["b2_bancoid4"];
		$b2banco5id = $userrow["b2_bancoid5"];
		$b2banco6id = $userrow["b2_bancoid6"];
		
		$b2banco1 = $userrow["b2_banconome1"];
		$b2banco2 = $userrow["b2_banconome2"];
		$b2banco3 = $userrow["b2_banconome3"];
		$b2banco4 = $userrow["b2_banconome4"];
		$b2banco5 = $userrow["b2_banconome5"];
		$b2banco6 = $userrow["b2_banconome6"];
		
		
		
		
			/*fim do teste */
				
	/* OLDPASS É A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
		
        
		
		if ($usuriologadodinheiro < 20) { die("Você precisa ter mais que 20 Ryou para utilizar o banco.");}
		
		
		/* BANCO DE TROCAS */
		
		if ($Combobox1 == 1) { 
		$updatequery = doquery("UPDATE {{table}} SET weaponid='$banco1id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET weaponname='$banco1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid1='$armaid' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome1='$arma' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox2 == 1) { 
		$updatequery = doquery("UPDATE {{table}} SET armorid='$banco2id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET armorname='$banco2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid2='$amarduraid' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome2='$armadura' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox3 == 1) { 
		$updatequery = doquery("UPDATE {{table}} SET shieldid='$banco3id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET shieldname='$banco3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid3='$escudoid' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome3='$escudo' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox4 == 1) { 
		$updatequery = doquery("UPDATE {{table}} SET slot1id='$banco4id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot1name='$banco4' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid4='$idslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome4='$nomeslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox5 == 1) { 
		$updatequery = doquery("UPDATE {{table}} SET slot2id='$banco5id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot2name='$banco5' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid5='$idslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome5='$nomeslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox6 == 1) { 
		$updatequery = doquery("UPDATE {{table}} SET slot3id='$banco6id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot3name='$banco6' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid6='$idslot3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome6='$nomeslot3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		/*DELETAR ARMA DO BANCO */
		
		if ($Combobox1 == 2) { 
		$updatequery = doquery("UPDATE {{table}} SET bancoid1='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome1='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox2 == 2) { 
		$updatequery = doquery("UPDATE {{table}} SET bancoid2='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome2='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		if ($Combobox3 == 2) { 
		$updatequery = doquery("UPDATE {{table}} SET bancoid3='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome3='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox4 == 2) { 
		$updatequery = doquery("UPDATE {{table}} SET bancoid4='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome4='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox5 == 2) { 
		$updatequery = doquery("UPDATE {{table}} SET bancoid5='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome5='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox6 == 2) { 
		$updatequery = doquery("UPDATE {{table}} SET bancoid6='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome6='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		
		
		
		/* BANCO DE ARMAZENAMENTO */
		
		
		if ($Combobox1 == 3) { 
		$updatequery = doquery("UPDATE {{table}} SET weaponid='$b2banco1id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET weaponname='$b2banco1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_bancoid1='$armaid' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_banconome1='$arma' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox2 == 3) { 
		$updatequery = doquery("UPDATE {{table}} SET armorid='$b2banco2id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET armorname='$b2banco2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_bancoid2='$amarduraid' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_banconome2='$armadura' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox3 == 3) { 
		$updatequery = doquery("UPDATE {{table}} SET shieldid='$b2banco3id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET shieldname='$b2banco3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_bancoid3='$escudoid' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_banconome3='$escudo' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox4 == 3) { 
		$updatequery = doquery("UPDATE {{table}} SET slot1id='$b2banco4id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot1name='$b2banco4' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_bancoid4='$idslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_banconome4='$nomeslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox5 == 3) { 
		$updatequery = doquery("UPDATE {{table}} SET slot2id='$b2banco5id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot2name='$b2banco5' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_bancoid5='$idslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_banconome5='$nomeslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox6 == 3) { 
		$updatequery = doquery("UPDATE {{table}} SET slot3id='$b2banco6id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot3name='$b2banco6' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_bancoid6='$idslot3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_banconome6='$nomeslot3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		
		$usuriologadodinheiro = $usuriologadodinheiro - 20;
		
				
		$updatequery = doquery("UPDATE {{table}} SET gold='$usuriologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");
		
        
         display("O processo bancário foi completado com sucesso e te custou 20 Ryou.<br /><br />Você pode <a href=\"index.php\">clicar aqui</a> para continuar jogando ou <a href=\"users.php?do=banco\">voltar ao banco</a>.","Banco",false,false,false);
        die();
    }

    $page = "<b>Seu Ryou</b>: $usuriologadodinheiro<br><br><table border=0 cellspacing=0 cellpadding=0><tr><td>
	
	<b><font color=orange>Equipado:</font></b><br>
		<b>Arma</b>: $arma<br><b>Colete</b>: $armadura<br><b>Bandana</b>: $escudo<br><b>Slot 1</b>: $nomeslot1<br><b>Slot 2</b>: $nomeslot2<br><b>Slot 3</b>: $nomeslot3<br><br>
	</td><td width=20></td><td>
	
	<b><font color=orange>Banco de Trocas:</font></b><br>
	<b>Arma</b>: $banco1<br><b>Colete</b>: $banco2<br><b>Bandana</b>: $banco3<br><b>Slot 1</b>: $banco4<br><b>Slot 2</b>: $banco5<br><b>Slot 3</b>: $banco6<br><br><td width=20></td>
	
	<td>
	<b><font color=orange>Banco de Armazenamento:</font></b><br>
		<b>Arma</b>: $b2banco1<br><b>Colete</b>: $b2banco2<br><b>Bandana</b>: $b2banco3<br><b>Slot 1</b>: $b2banco4<br><b>Slot 2</b>: $b2banco5<br><b>Slot 3</b>: $b2banco6<br><br></td>
	</td></tr></table>"
		.gettemplate("banco");
    $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
	
    display($page, "Banco", false, false, false); 
	
    
}













function doaritem() {
    /* testando se está logado */
		include('cookies.php');
		$userrow = checkcookies();
		if ($userrow == false) { die("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação."); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
		
		$banco1 = $userrow["banconome1"];
		$banco2 = $userrow["banconome2"];
		$banco3 = $userrow["banconome3"];
		$banco4 = $userrow["banconome4"];
		$banco5 = $userrow["banconome5"];
		$banco6 = $userrow["banconome6"];
		
		$banco1id = $userrow["bancoid1"];
		$banco2id = $userrow["bancoid2"];
		$banco3id = $userrow["bancoid3"];
		$banco4id = $userrow["bancoid4"];
		$banco5id = $userrow["bancoid5"];
		$banco6id = $userrow["bancoid6"];
			/*fim do teste */
				
	/* OLDPASS É A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
        $userquery = doquery("SELECT * FROM {{table}} WHERE charname='$username' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { die("Não existe nenhuma conta com esse Nome."); }
        $userrow = mysql_fetch_array($userquery);
		if ($usuariologadoid == $userrow["id"]) { die("Você não pode doar Item para si mesmo.");}
				/*if ($userrow["password"] != md5($oldpass)) { die("The old password you provided was incorrect."); }
        /*$realnewpass = md5($newpass1); */
		if ($usuriologadodinheiro < 40) { die("Você não pode doar um Item com menos de 40 Ryou.");}
		
		if ($Combobox1 == 1) {
		if ($userrow["bancoid1"] != 0) { die("O jogador o qual você quer doar o Item, já possui uma Arma no Banco de Trocas.");}
		
		else{ 
		$updatequery = doquery("UPDATE {{table}} SET bancoid1='$banco1id' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome1='$banco1' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid1='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome1='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		}
		
		
		if ($Combobox1 == 2) {
		if ($userrow["bancoid2"] != 0) { die("O jogador o qual você quer doar o Item, já possui um Colete no Banco de Trocas.");}
		
		else{ 
		$updatequery = doquery("UPDATE {{table}} SET bancoid2='$banco2id' WHERE charname'$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome2='$banco2' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid2='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome2='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		}
		
		
		if ($Combobox1 == 3) {
		if ($userrow["bancoid3"] != 0) { die("O jogador o qual você quer doar o Item, já possui uma Bandana no Banco de Trocas.");}
		else{ 
		$updatequery = doquery("UPDATE {{table}} SET bancoid3='$banco3id' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome3='$banco3' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid3='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome3='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		}
		
		if ($Combobox1 == 4) {
		if ($userrow["bancoid4"] != 0) { die("O jogador o qual você quer doar o Item, já possui um Item no Slot 1 no Banco de Trocas.");}
		else{ 
		$updatequery = doquery("UPDATE {{table}} SET bancoid4='$banco4id' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome4='$banco4' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid4='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome4='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		}
		
		if ($Combobox1 == 5) {
		if ($userrow["bancoid5"] != 0) { die("O jogador o qual você quer doar o Item, já possui um Item no Slot 2 no Banco de Trocas.");}
		
		else{ 
		$updatequery = doquery("UPDATE {{table}} SET bancoid5='$banco5id' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome5='$banco5' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid5='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome5='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		}
		
		if ($Combobox1 == 6) {
		if ($userrow["bancoid6"] != 0) { die("O jogador o qual você quer doar o Item, já possui um Item no Slot 3 no Banco de Trocas.");}
		
		else{ 
		$updatequery = doquery("UPDATE {{table}} SET bancoid6='$banco6id' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome6='$banco6' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid6='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome6='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		}
		
		if ($Combobox1 == 0) {die("Primeiro selecione uma ação.");}
		$usuriologadodinheiro = $usuriologadodinheiro - 40;
				
		
		$updatequery = doquery("UPDATE {{table}} SET gold='$usuriologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");
        
				
        display("O Item foi retirado da sua conta e doado com sucesso. Foram retirados da sua conta, 40 Ryou.<br /><br />Você pode <a href=\"index.php\">clicar aqui</a> para continuar jogando ou <a href=\"users.php?do=doaritem\">doar mais Item</a>.","Doar Item",false,false,false);
        die();
    }
    $page = "<b>Seu Ryou</b>: $usuriologadodinheiro<br><br><b>Arma no Banco</b>: $banco1<br><b>Colete no Banco</b>: $banco2<br><b>Bandana no Banco</b>: $banco3<br><b>Slot 1 no Banco</b>: $banco4<br><b>Slot2 no Banco</b>: $banco5<br><b>Slot3 no Banco</b>: $banco6<br><br>".gettemplate("doaritem");
    $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Doar Item", false, false, false); 
    
}

















function troca1() {
    /* testando se está logado */
		include('cookies.php');
		$userrow = checkcookies();
		if ($userrow == false) { die("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação."); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
			/*fim do teste */
			
			/* BANCO DE TROCAS */
		$banco1 = $userrow["banconome1"];
		$banco2 = $userrow["banconome2"];
		$banco3 = $userrow["banconome3"];
		$banco4 = $userrow["banconome4"];
		$banco5 = $userrow["banconome5"];
		$banco6 = $userrow["banconome6"];
		
		$banco1id = $userrow["bancoid1"];
		$banco2id = $userrow["bancoid2"];
		$banco3id = $userrow["bancoid3"];
		$banco4id = $userrow["bancoid4"];
		$banco5id = $userrow["bancoid5"];
		$banco6id = $userrow["bancoid6"]; 
			
						
	/* OLDPASS É A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
		 $userquery = doquery("SELECT * FROM {{table}} WHERE charname='$username' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { die("Não existe nenhuma conta com esse Nome."); }
        $userrow = mysql_fetch_array($userquery);
		
		
		if ($Combobox1 == 0) { die("Primeiro selecione um Item."); }
		        
		$updatequery = doquery("UPDATE {{table}} SET trocanomeitem='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocaiditem='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanomejogador='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanumero='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocaposicaoitem='0' WHERE charname='$usuariologadonome' LIMIT 1","users");

//colocando a posição do item, qual item é.
		$updatequery = doquery("UPDATE {{table}} SET trocaposicaoitem='$Combobox1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		
		if ($Combobox1 == 1) {$nomeitempronto = $banco1; $iditempronto = $banco1id;}
		if ($Combobox1 == 2) {$nomeitempronto = $banco2;$iditempronto = $banco2id;}
		if ($Combobox1 == 3) {$nomeitempronto = $banco3;$iditempronto = $banco3id;}
		if ($Combobox1 == 4) {$nomeitempronto = $banco4;$iditempronto = $banco4id;}
		if ($Combobox1 == 5) {$nomeitempronto = $banco5;$iditempronto = $banco5id;}
		if ($Combobox1 == 6) {$nomeitempronto = $banco6;$iditempronto = $banco6id;}
		//confere se o item se a casa do jogador a ser doado está vazia
		$nomedoslot = bancoid.$Combobox1;
		if ($userrow["$nomedoslot"] != 0) { die("O Jogador o qual você realizar a troca, já possui um Item no Banco de Trocas referente ao item que você quer trocar."); }
				
		
		$updatequery = doquery("UPDATE {{table}} SET trocanomeitem='$nomeitempronto' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocaiditem='$iditempronto' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanomejogador='$username' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanumero='1' WHERE charname='$username' LIMIT 1","users");
		
      	
		
        
				
        display("A primeira etapa da troca foi realizada. Envie esse link: <font color=red>http://nigeru.com/narutorpg/users.php?do=troca2</font> para o Jogador o qual você deseja realizar a Troca.<br /><br />Você pode <a href=\"index.php\">clicar aqui</a> para continuar jogando, caso não queira mais realizar uma troca, ou ainda <a href=\"users.php?do=troca1\">iniciar uma nova Troca</a>.","Realizar Troca",false,false,false);
        die();
    }
    $page = "<b>Seu Ryou</b>: $usuriologadodinaheiro<br><br>
	
	<b><font color=orange>Seu Banco de Trocas:</font></b><br>
	<b>Arma</b>: $banco1<br><b>Colete</b>: $banco2<br><b>Bandana</b>: $banco3<br><b>Slot 1</b>: $banco4<br><b>Slot 2</b>: $banco5<br><b>Slot 3</b>: $banco6<br><br>
	
	".gettemplate("troca1");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Realizar Troca", false, false, false); 
    
}




function troca2() {
    /* testando se está logado */
		include('cookies.php');
		$userrow = checkcookies();
		if ($userrow == false) { die("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação."); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
			/*fim do teste */
			
			/* BANCO DE TROCAS */
		$banco1 = $userrow["banconome1"];
		$banco2 = $userrow["banconome2"];
		$banco3 = $userrow["banconome3"];
		$banco4 = $userrow["banconome4"];
		$banco5 = $userrow["banconome5"];
		$banco6 = $userrow["banconome6"];
		
		$banco1id = $userrow["bancoid1"];
		$banco2id = $userrow["bancoid2"];
		$banco3id = $userrow["bancoid3"];
		$banco4id = $userrow["bancoid4"];
		$banco5id = $userrow["bancoid5"];
		$banco6id = $userrow["bancoid6"]; 
		
		$numerodatroca = $userrow["trocanumero"]; 
		
		
		 if ($numerodatroca != 1) { die("Respeite a ordem da troca, envie esse link para o outro jogador."); }
						
	/* OLDPASS É A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
		
		$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$username' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { die("Não existe nenhuma conta com esse Nome."); }
		$userrow = mysql_fetch_array($userquery);
			
       		if (!is_numeric($oldpass)) { die("A quantidade de Ryou deve ser um número."); }
		
		
	
		
		
		if ($oldpass > $usuriologadodinheiro) { die("Você não pode trocar mais Ryou que a sua quantidade."); }
		if ($oldpass < 1) { die("Você não pode trocar menos que 1 Ryou."); }
		if ($oldpass > 999999999) { die("Você não pode utilizar um número com 10 algarismos."); }
		
		$nomedoitemprontop = $oldpass." Ryou";
		$updatequery = doquery("UPDATE {{table}} SET trocanomeitem='$nomedoitemprontop' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocaiditem='$oldpass' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanomejogador='$username' WHERE charname='$usuariologadonome' LIMIT 1","users");
				
		//número da troca
		$updatequery = doquery("UPDATE {{table}} SET trocanumero='2' WHERE charname='$username' LIMIT 1","users");
		
      	
		
        
				
        display("A segunda etapa da troca foi realizada. Envie esse link: <font color=red>http://nigeru.com/narutorpg/users.php?do=troca3</font> para o Jogador o qual você deseja realizar a Troca.<br /><br />Você pode <a href=\"index.php\">clicar aqui</a> para continuar jogando, caso não queira mais realizar uma troca, ou ainda <a href=\"users.php?do=troca1\">iniciar uma nova Troca</a>.","Realizar Troca",false,false,false);
        die();
    }
    $page = "<b>Seu Ryou</b>: $usuriologadodinheiro<br><br>".gettemplate("troca2");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Realizar Troca", false, false, false); 
    
}

function troca3() {
    /* testando se está logado */
		include('cookies.php');
		$userrow = checkcookies();
		if ($userrow == false) { die("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação."); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
			/*fim do teste */
			
			/* BANCO DE TROCAS */
		$banco1 = $userrow["banconome1"];
		$banco2 = $userrow["banconome2"];
		$banco3 = $userrow["banconome3"];
		$banco4 = $userrow["banconome4"];
		$banco5 = $userrow["banconome5"];
		$banco6 = $userrow["banconome6"];
		
		$banco1id = $userrow["bancoid1"];
		$banco2id = $userrow["bancoid2"];
		$banco3id = $userrow["bancoid3"];
		$banco4id = $userrow["bancoid4"];
		$banco5id = $userrow["bancoid5"];
		$banco6id = $userrow["bancoid6"]; 
		
		$trocanomeitemjogador = $userrow["trocanomeitem"];
		$nomedojogadordatroca = $userrow["trocanomejogador"];
				
		$numerodatrocadurante = $userrow["trocanumero"];
		
		
			 $userquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { die("Não existe nenhuma conta com o nome do Jogador da Troca."); }
		$userrow = mysql_fetch_array($userquery);
		$itemdetrocajogador2 = $userrow["trocanomeitem"];
		$nomedojogadordatroca2 = $userrow["trocanomejogador"];
		if ($nomedojogadordatroca2 != $usuariologadonome) {$fim = true;}
		if ($fim){ die("Você não pode comandar uma Troca que não é pra você. <a href=\"users.php?do=troca1\">Clique aqui</a> para realizar uma nova troca."); }
		if ($numerodatrocadurante != 2) { die("Respeite a ordem da troca, envie esse link para o outro jogador."); }
						
	/* OLDPASS É A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
		
      
		
		
		
		if ($numerodatrocadurante != 2){ die("Não foi possível realizar uma troca, recomece uma nova troca <a href=\"users.php?do=troca1\">clicando aqui</a>."); }
		
		
		
		
		
		//número da troca
			$updatequery = doquery("UPDATE {{table}} SET trocanumero='3' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		
      	
		
        
				
        display("A terceira etapa da troca foi realizada. Envie esse link: <font color=red>http://nigeru.com/narutorpg/users.php?do=troca4</font> para o Jogador o qual você deseja realizar a Troca.<br /><br />Você pode <a href=\"index.php\">clicar aqui</a> para continuar jogando, caso não queira mais realizar uma troca, ou ainda <a href=\"users.php?do=troca1\">iniciar uma nova Troca</a>.","Realizar Troca",false,false,false);
        die();
    }
    $page = "<b>Troca atual:</b><br>$trocanomeitemjogador por $itemdetrocajogador2.".gettemplate("troca3");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Realizar Troca", false, false, false); 
    
}






function troca4() {
    /* testando se está logado */
		include('cookies.php');
		$userrow = checkcookies();
		if ($userrow == false) { die("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação."); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
			/*fim do teste */
			
			/* BANCO DE TROCAS */
		$banco1 = $userrow["banconome1"];
		$banco2 = $userrow["banconome2"];
		$banco3 = $userrow["banconome3"];
		$banco4 = $userrow["banconome4"];
		$banco5 = $userrow["banconome5"];
		$banco6 = $userrow["banconome6"];
		
		$banco1id = $userrow["bancoid1"];
		$banco2id = $userrow["bancoid2"];
		$banco3id = $userrow["bancoid3"];
		$banco4id = $userrow["bancoid4"];
		$banco5id = $userrow["bancoid5"];
		$banco6id = $userrow["bancoid6"]; 
		
		$trocanomeitemjogador = $userrow["trocanomeitem"];
		$nomedojogadordatroca = $userrow["trocanomejogador"];
		$iddojogadordatroca = $userrow["trocaiditem"];
		$posicaoitem = $userrow["trocaposicaoitem"];
				
		$numerodatrocadurante = $userrow["trocanumero"];
			
			
			
			$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { die("Não existe nenhuma conta com o nome do Jogador da Troca."); }
		$userrow = mysql_fetch_array($userquery);
		$itemdetrocajogador2 = $userrow["trocanomeitem"];
		$nomedojogadordatroca2 = $userrow["trocanomejogador"];
		$iddojogadordatroca2 = $userrow["trocaiditem"];
		$posicaoitem2 = $userrow["trocaposicaoitem"];
		$dinheirodojogador2 = $userrow["gold"];
		if ($nomedojogadordatroca2 != $usuariologadonome) {$fim = true;}
		if ($fim){ die("Você não pode comandar uma Troca que não é pra você. <a href=\"users.php?do=troca1\">Clique aqui</a> para realizar uma nova troca."); }
		if ($numerodatrocadurante != 3) { die("Respeite a ordem da troca, envie esse link para o outro jogador."); }
						
	/* OLDPASS É A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
		
        
		
		
		//if ($numerodatrocadurante != 3){ die("Não foi possível realizar uma troca, recomece uma nova troca <a href=\"users.php?do=troca1\">clicando aqui</a>."); }
		
		
		
		//trocando
		if ($posicaoitem2 == 1) {
		$updatequery = doquery("UPDATE {{table}} SET banconome1='$itemdetrocajogador2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid1='$iddojogadordatroca2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		
		$updatequery = doquery("UPDATE {{table}} SET banconome1='None' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid1='0' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");}
		
		if ($posicaoitem2 == 2) {
		$updatequery = doquery("UPDATE {{table}} SET banconome2='$itemdetrocajogador2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid2='$iddojogadordatroca2' WHERE charname='$usuariologadonome' LIMIT 1","users");	
		
		$updatequery = doquery("UPDATE {{table}} SET banconome2='None' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid2='0' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");}
		
		if ($posicaoitem2 == 3) {
		$updatequery = doquery("UPDATE {{table}} SET banconome3='$itemdetrocajogador2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid3='$iddojogadordatroca2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		
			$updatequery = doquery("UPDATE {{table}} SET banconome3='None' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid3='0' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");}
		
		if ($posicaoitem2 == 4) {
		$updatequery = doquery("UPDATE {{table}} SET banconome4='$itemdetrocajogador2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid4='$iddojogadordatroca2' WHERE charname='$usuariologadonome' LIMIT 1","users");	
		
		$updatequery = doquery("UPDATE {{table}} SET banconome4='None' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid4='0' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");}
		
		if ($posicaoitem2 == 5) {
		$updatequery = doquery("UPDATE {{table}} SET banconome5='$itemdetrocajogador2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid5='$iddojogadordatroca2' WHERE charname='$usuariologadonome' LIMIT 1","users");	
		
		$updatequery = doquery("UPDATE {{table}} SET banconome5='None' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid5='0' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");}
		
		if ($posicaoitem2 == 6) {
		$updatequery = doquery("UPDATE {{table}} SET banconome6='$itemdetrocajogador2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid6='$iddojogadordatroca2' WHERE charname='$usuariologadonome' LIMIT 1","users");
			$updatequery = doquery("UPDATE {{table}} SET banconome6='None' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid6='0' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");}
		
		//trocandojogador2
		$dinheirofinalpronto = $dinheirodojogador2 + $iddojogadordatroca;
		$dinheirofinalpronto2 = $usuriologadodinheiro - $iddojogadordatroca;
		$updatequery = doquery("UPDATE {{table}} SET gold='$dinheirofinalpronto' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		
		$updatequery = doquery("UPDATE {{table}} SET gold='$dinheirofinalpronto2' WHERE charname='$usuariologadonome' LIMIT 1","users");

		
		
      	//resetando os itens da troca
		$updatequery = doquery("UPDATE {{table}} SET trocanomeitem='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocaiditem='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanomejogador='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanumero='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocaposicaoitem='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		
		$updatequery = doquery("UPDATE {{table}} SET trocanomeitem='None' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocaiditem='0' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanomejogador='None' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanumero='0' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocaposicaoitem='0' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		
		
        
				
        display("A troca foi realizada com sucesso e seu Item foi enviado para o Banco de Trocas.<br /><br />Você pode <a href=\"index.php\">clicar aqui</a> para continuar jogando, caso não queira mais realizar uma troca, ou ainda <a href=\"users.php?do=troca1\">iniciar uma nova Troca</a>.","Realizar Troca",false,false,false);
        die();
		
		
    }
	
	
    $page = "<b>Troca atual:</b><br>$trocanomeitemjogador por $itemdetrocajogador2.".gettemplate("troca4");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Realizar Troca", false, false, false); 
    
}
?>