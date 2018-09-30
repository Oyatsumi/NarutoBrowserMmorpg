<?php // users.php :: Handles user account functions.


/*$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);*/



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();







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
	elseif ($do == "batalha1") { batalha1(); }
	elseif ($do == "resetarduelo") { resetarduelo(); }
	elseif ($do == "duelo") { duelo(); }
	elseif ($do == "procurarjogador") { procurarjogador(); }
    
}

function register() { // Register a new account.
    

    $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
    $controlrow = mysql_fetch_array($controlquery);


    
    if (isset($_POST["submit"])) {
        
        extract($_POST);
        
        $errors = 0; $errorlist = "";
        if (strlen($password1) > 10){ $errors++; $errorlist .= "Senha n�o pode conter mais que 10 caracteres.<br />"; }
		if (strlen($username) > 30){ $errors++; $errorlist .= "Nome da Conta n�o pode conter mais que 30 caracteres.<br />"; }
        // Process username.
        if ($username == "") { $errors++; $errorlist .= "Nome da conta � necess�rio.<br />"; }
        if (preg_match("/[^A-z0-9_\-]/", $username)==1) { $errors++; $errorlist .= "Nome da sua conta deve ser alfanum�rico(n�o pode conter espa�os).<br />"; } // Thanks to "Carlos Pires" from php.net!
        $usernamequery = doquery("SELECT username FROM {{table}} WHERE username='$username' LIMIT 1","users");
        if (mysql_num_rows($usernamequery) > 0) { $errors++; $errorlist .= "Essa conta j� existe.<br />"; }
        
        // Process charname.
        if ($charname == "") { $errors++; $errorlist .= "Campo de Nome do Personagem � necess�rio.<br />"; }
        if (preg_match("/[^A-z0-9_\-]/", $charname)==1) { $errors++; $errorlist .= "Nome do personagem deve ser alfanum�rico(n�o pode conter espa�os).<br />"; } // Thanks to "Carlos Pires" from php.net!
        $characternamequery = doquery("SELECT charname FROM {{table}} WHERE charname='$charname' LIMIT 1","users");
        if (mysql_num_rows($characternamequery) > 0) { $errors++; $errorlist .= "Esse nome de personagem j� est� em uso.<br />"; }
    
        // Process email address.
        if ($email1 == "" || $email2 == "") { $errors++; $errorlist .= "Campo de e-mail � necess�rio.<br />"; }
        if ($email1 != $email2) { $errors++; $errorlist .= "Os e-mails n�o coincidem.<br />"; }
        if (! is_email($email1)) { $errors++; $errorlist .= "O e-mail n�o � v�lido.<br />"; }
        $emailquery = doquery("SELECT email FROM {{table}} WHERE email='$email1' LIMIT 1","users");
        if (mysql_num_rows($emailquery) > 0) { $errors++; $errorlist .= "Esse e-mail j� est� em uso.<br />"; }
        
        // Process password.
        if (trim($password1) == "") { $errors++; $errorlist .= "Campo de senha � necess�rio.<br />"; }
        if (preg_match("/[^A-z0-9_\-]/", $password1)==1) { $errors++; $errorlist .= "Sua senha deve ser alfanum�rica.<br />"; } // Thanks to "Carlos Pires" from php.net!
        if ($password1 != $password2) { $errors++; $errorlist .= "Os campos de senha n�o coincidem.<br />"; }
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
                    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/criarconta.gif\" /></center></td></tr></table>Sua conta foi criada com sucesso.<br /><br />Voc� deve receber um e-mail de confirma��o logo. � preciso verificar o c�digo que est� presente no seu e-mail para ativar a sua conta. Uma vez recebido o e-mail, por favor visite a  <a href=\"users.php?do=verify\">P�gina de Verifica��o</a> para ativar sua conta e come�ar a jogar.";
                } else {
                    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/criarconta.gif\" /></center></td></tr></table>Sua conta foi criada com sucesso.<br /><br />De qualquer forma, houve um erro durante o envio do seu e-mail de confirma��o. Por favor cheque com o Administrador do jogo para resolver seu problema. Voc� tamb�m pode ativar sua conta pelo site clicando <a href=\"ativarconta.php\">aqui</a>.";
                }
            } else {
                $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/criarconta.gif\" /></center></td></tr></table>Sua conta foi criada com sucesso.<br /><br />Voc� pode agora acessar a <a href=\"login.php?do=login\">P�gina de Login</a> e continuar jogando ".$controlrow["gamename"]."!";
            }
            
        } else {
            
            $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/criarconta.gif\" /></center></td></tr></table><form id=\"formback\"><fieldset id=\"field2\"><legend>Dados da Conta</legend>O(s) seguinte(s) erro(s) ocorreram enquanto sua conta estava sendo feita:<br /><span style=\"color:red;\">$errorlist</span><br />Por favor <a href=\"javascript: history.back();\">volte</a> e tente novamente.</fieldset></form>";
            
        }
        
    } else {
        
        $page = gettemplate("register");
        if ($controlrow["verifyemail"] == 1) { 
            $controlrow["verifytext"] = "<br /><span class=\"small\">Um c�digo de verifica��o ser� enviado para seu e-mail, voc� n�o est� habilitado a jogar antes de ativar sua conta com o c�digo presente em seu e-mail. Esteja ciente de que � preciso preencher o campo e-mail com um e-mail v�lido.</span>";
        } else {
            $controlrow["verifytext"] = "";
        }


        $page = parsetemplate($page, $controlrow);
        
    }
    
    $topnav = "<a href=\"login.php?do=login\"><img src=\"images/button_login.gif\" alt=\"Log In\" border=\"0\" /></a><a href=\"users.php?do=register\"><img src=\"images/button_register.gif\" alt=\"Register\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" border=\"0\" /></a>";
    

display($page, "Registrar", false, false, false);

    
}

function verify() {
    
    if (isset($_POST["submit"])) {
        extract($_POST);
        $userquery = doquery("SELECT username,email,verify FROM {{table}} WHERE username='$username' LIMIT 1","users");
        if (mysql_num_rows($userquery) != 1) { die("Nenhuma conta com esse nome."); }
        $userrow = mysql_fetch_array($userquery);
        if ($userrow["verify"] == 1) { die("Sua conta j� est� verificada."); }
        if ($userrow["email"] != $email) { die("E-mail incorreto."); }
        if ($userrow["verify"] != $verify) { die("C�digo de verifica��o incorreto."); }
        // If we've made it this far, should be safe to update their account.
        $updatequery = doquery("UPDATE {{table}} SET verify='1' WHERE username='$username' LIMIT 1","users");
        display("<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/minhaconta.gif\" /></center></td></tr></table>Sua conta foi ativada com sucesso..<br /><br />Voc� pode acessar a <a href=\"login.php?do=login\">P�gina de Login</a> e come�ar a jogar agora mesmo.<br /><br />Obrigado por jogar!","Verificar Email",false,false,false);
    }
    $page = gettemplate("verify");
    $topnav = "<a href=\"login.php?do=login\"><img src=\"images/button_login.gif\" alt=\"Log In\" border=\"0\" /></a><a href=\"users.php?do=register\"><img src=\"images/button_register.gif\" alt=\"Register\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" border=\"0\" /></a>";
    display($page, "Verify Email", false, false, false);
    
}

function lostpassword() {
    
    if (isset($_POST["submit"])) {
        extract($_POST);
        $userquery = doquery("SELECT email FROM {{table}} WHERE email='$email' LIMIT 1","users");
        if (mysql_num_rows($userquery) != 1) { header("Location: users.php?do=lostpassword&conteudo=N�o h� uma conta com esse e-mail."); die();}
        $newpass = "";
        for ($i=0; $i<8; $i++) {
            $newpass .= chr(rand(65,90));
        }
        $md5newpass = md5($newpass);
        $updatequery = doquery("UPDATE {{table}} SET password='$md5newpass' WHERE email='$email' LIMIT 1","users");
        if (sendpassemail($email,$newpass) == true) {
            display("<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/minhaconta.gif\" /></center></td></tr></table><fieldset id=\"field2\"><legend>Recuperar Senha</legend>Sua nova senha foi enviada para seu e-mail.<br /><br />Quando receb�-la, voc� pode fazer o <a href=\"login.php?do=login\">Log In</a> e continuar jogando.<br /><br />Obrigado.</fieldset>","Senha Perdida",false,false,false);
        } else {
			header("Location: users.php?do=lostpassword&conteudo=Houve um erro no envio da sua nova senha. Por favor cheque com o Administrador ou algu�m da equipe para mais informa��es. Pedimos desculpas pelo transtorno."); die();
        }
        die();
    }
	global $conteudouser;
	$conteudouser = $_GET['conteudo'];
	$conteudouser = "<font color=brown><center>".strip_tags($conteudouser)."</font></center>";
    $page = gettemplate("lostpassword");
    $topnav = "<a href=\"login.php?do=login\"><img src=\"images/button_login.gif\" alt=\"Log In\" border=\"0\" /></a><a href=\"users.php?do=register\"><img src=\"images/button_register.gif\" alt=\"Register\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" border=\"0\" /></a>";
    display($page, "Senha Perdida", false, false, false);
    
}

function changepassword() {
    
    if (isset($_POST["submit"])) {
        extract($_POST);
        $userquery = doquery("SELECT * FROM {{table}} WHERE username='$username' LIMIT 1","users");
        if (mysql_num_rows($userquery) != 1) {header("Location: users.php?do=changepassword&conteudo=N�o h� uma conta com esse nome."); die(); }
        $userrow = mysql_fetch_array($userquery);
        if ($userrow["password"] != md5($oldpass)) {header("Location: users.php?do=changepassword&conteudo=A senha antiga est� incorreta."); die();}
        if (preg_match("/[^A-z0-9_\-]/", $newpass1)==1) {header("Location: users.php?do=changepassword&conteudo=A nova senha precisa ser alfanum�rica."); die();} // Thanks to "Carlos Pires" from php.net!
        if ($newpass1 != $newpass2) { header("Location: users.php?do=changepassword&conteudo=Novas senhas n�o coincidem.");die();}
        $realnewpass = md5($newpass1);
        $updatequery = doquery("UPDATE {{table}} SET password='$realnewpass' WHERE username='$username' LIMIT 1","users");
        if (isset($_COOKIE["dkgame"])) { setcookie("dkgame", "", time()-100000, "/", "", 0); }
        display("<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/minhaconta.gif\" /></center></td></tr></table><fieldset id=\"field2\"><legend>Mudar Senha</legend>Sua senha foi modificada com sucesso.<br /><br />Voc� foi desconectado do jogo para evitar erros.<br /><br />Por favor, <a href=\"login.php?do=login\">fa�a o login novamente</a> para continuar jogando.<br></fieldset>","Mudar Senha",false,false,false);
        die();
    }
	global $conteudouser;
	$conteudouser = $_GET['conteudo'];
	$conteudouser = "<font color=brown><center>".strip_tags($conteudouser)."</font></center>";
    $page = gettemplate("changepassword");
    $topnav = "<a href=\"login.php?do=login\"><img src=\"images/button_login.gif\" alt=\"Log In\" border=\"0\" /></a><a href=\"users.php?do=register\"><img src=\"images/button_register.gif\" alt=\"Register\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" border=\"0\" /></a>";
    display($page, "Mudar Senha", false, false, false); 
    
}

function sendpassemail($emailaddress, $password) {
    
    $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
    $controlrow = mysql_fetch_array($controlquery);
    extract($controlrow);
    
$email = <<<END
Voc� ou algu�m usando a sua conta de e-mail, utilizou a fun��o de Senha Perdida do jogo $gamename, localizado em $gameurl. 

N�s estamos te enviando uma nova senha, ent�o voc� poder� entrar novamente no jogo.

Sua nova senha �: $password

Obrigado por jogar.
Nigeru Animes.
END;

    $status = mymail($emailaddress, "$gamename Senha Perdida", $email);
    return $status;
    
}

function sendregmail($emailaddress, $vercode) {
    
    $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
    $controlrow = mysql_fetch_array($controlquery);
    extract($controlrow);
    $verurl = $gameurl . "?do=verify";
    
$email = <<<END
Voc� ou algu�m usando sua conta de e-mail, recentemente se registrou no jogo $gamename, localizado em $gameurl.

Esse e-mail � enviado para verificar seu e-mail de registro. Para come�ar a utilizar a sua conta, voc� deve verificar o seu e-mail. 
Por favor visite a P�gina de Verifica��o: ($verurl) e preencha com o c�digo de ativa��o abaixo:
C�digo de Ativa��o: $vercode

Se voc� n�o � a pessoa que se registrou no jogo, por favor ignore essa mensagem. Voc� n�o receber� outro e-mail.
END;

    $status = mymail($emailaddress, "$gamename Verifica��o da Conta", $email);
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
global $topvar;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		//$userrow = checkcookies();
		global $userrow;

		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);
		die(); }
		if ($userrow["currentaction"] != "In Town") {if ($userrow["currentaction"] == "Fighting"){header('Location: ./index.php?do=fight&conteudo=Voc� s� pode acessar essa fun��o dentro de uma cidade!');die();}else{header('Location: ./index.php?conteudo=Voc� s� pode acessar essa fun��o dentro de uma cidade!');die();} }
					if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
					if ($userrow["level"] < 5) {display("Voc� n�o pode acessar essa fun��o se seu level for menor que 5!","Erro",false,false,false);die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
			/*fim do teste */
				
	/* OLDPASS � A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
        $userquery = doquery("SELECT * FROM {{table}} WHERE charname='$username' LIMIT 1","users");
		
		

        if (mysql_num_rows($userquery) != 1) { display("<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/minhaconta.gif\" /></center></td></tr></table>N�o existe nenhuma conta com esse Nome.","Erro",false,false,false);die(); }
        $userrow = mysql_fetch_array($userquery);
		if ($usuariologadoid == $userrow["id"]) { display("Voc� n�o pode doar Ryou para si mesmo.","Erro",false,false,false);die();}
		if (!is_numeric($oldpass)) { display("A quantidade de Ryou deve ser um n�mero.","Erro",false,false,false);die(); }
		$oldpass = floor($oldpass);
		/*if ($userrow["password"] != md5($oldpass)) { die("The old password you provided was incorrect."); }
        /*$realnewpass = md5($newpass1); */
		if ($oldpass > $usuriologadodinaheiro) { display("Voc� n�o pode doar mais do que a sua quantidade de Ryou.","Erro",false,false,false);die(); }
		if ($oldpass < 1) { display("Voc� n�o pode doar menos que 1 Ryou.","Erro",false,false,false);die(); }
		$dinheirototal = $userrow["gold"] + $oldpass;
		$dinheirousuariologadodepois = $usuriologadodinaheiro - $oldpass;
				
		$updatequery = doquery("UPDATE {{table}} SET gold='$dinheirototal' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET gold='$dinheirousuariologadodepois' WHERE charname='$usuariologadonome' LIMIT 1","users");
        
				
        display("O dinheiro foi retirado da sua conta e doado com sucesso.<br /><br />Voc� pode <a href=\"index.php\">clicar aqui</a> para continuar jogando ou <a href=\"users.php?do=doardinheiro\">doar mais Ryou</a>.","Doar Ryou",false,false,false);
        die();
    }
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/doaryou.gif\" /></center></td></tr></table>
	
	<b>Seu Ryou</b>: $usuriologadodinaheiro<br><br>".gettemplate("doardinheiro");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Doar Ryou", false, false, false); 
    
}











function banco() {
global $topvar;
$topvar = true;

    /* testando se est� logado */
	
	
		//include('cookies.php');
		// $userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] != "In Town") {if ($userrow["currentaction"] == "Fighting"){header('Location: ./index.php?do=fight&conteudo=Voc� s� pode acessar essa fun��o dentro de uma cidade!');die();}else{header('Location: ./index.php?conteudo=Voc� s� pode acessar essa fun��o dentro de uma cidade!');die();} }
			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
						
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
		
	
		
		$ryoudepositado = $userrow["banco_grana"];
		
			$dinheirototal = $usuriologadodinheiro + $ryoudepositado;
		
		
		
		
			/*fim do teste */
				
	/* OLDPASS � A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
		
        

		
		//DEPOSITAR GRANA
		if ($deposito != "") { 
		if (!is_numeric($deposito)) { display("A quantidade de Ryou � repositar deve ser um n�mero.","Erro",false,false,false);die(); }
		$deposito = floor($deposito);
		if ($deposito < 1) { display("Voc� n�o pode depositar menos que 1 Ryou.","Erro",false,false,false);die(); }
	
		$porcentagemconta = floor(90*$dinheirototal/100);
		$dinheirosuposto = $deposito + $ryoudepositado;
				if ($dinheirosuposto > 90*$dinheirototal/100) { display("Voc� n�o pode ter uma quantia maior que $porcentagemconta Ryou no Banco, que representa 90% do seu Ryou total, o que est� depositado e o que est� no seu personagem.","Erro",false,false,false);die(); }
				if ($deposito > $usuriologadodinheiro) { display("Voc� n�o pode depositar mais que a sua quantidade de Ryou.","Erro",false,false,false);die(); }
		
			
		$ryoudepositado += floor($deposito);
		$usuriologadodinheiro -= floor($deposito);
		
		$updatequery = doquery("UPDATE {{table}} SET banco_grana='$ryoudepositado' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET gold='$usuriologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		
		
		//RETIRAR GRANA
		if ($retirar != "") {
		if (!is_numeric($retirar)) { display("A quantidade de Ryou � retirar deve ser um n�mero.","Erro",false,false,false);die(); }
		$retirar = floor($retirar);
		if ($retirar < 1) { display("Voc� n�o pode retirar menos que 1 Ryou.","Erro",false,false,false);die(); }
		if ($retirar > 99999) { display("Voc� n�o pode retirar mais que 99999 Ryou.","Erro",false,false,false);die(); }
		if ($retirar > $ryoudepositado) { display("Voc� n�o pode retirar mais que seu dinheiro no banco.","Erro",false,false,false);die(); }
				
		$ryoudepositado -= floor($retirar);
		$usuriologadodinheiro += floor($retirar);
		
		$updatequery = doquery("UPDATE {{table}} SET banco_grana='$ryoudepositado' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET gold='$usuriologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		
		/* BANCO DE TROCAS */
		
		
		//ARMAS
		if ($Combobox1 == 1) { 
		
		// STATUS DOS ITENS
		global $userrow;
		   // Special item fields.
		  $specialchange1 = "";
        $specialchange2 = "";
		//esse � o q tira
		   	if ($armaid != 0) {
		$itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='$armaid' LIMIT 1", "items");
         $itemsrow2 = mysql_fetch_array($itemsquery2);
		 
		   if ($itemsrow2["special"] != "X") {
            $special2 = explode(",",$itemsrow2["special"]);
            $tochange2 = $special2[0];
            $userrow[$tochange2] = $userrow[$tochange2] - $special2[1];
            $specialchange2 = "$tochange2='".$userrow[$tochange2]."',";
            if ($tochange2 == "strength") { $userrow["attackpower"] -= $special2[1]; }
            if ($tochange2 == "dexterity") { $userrow["defensepower"] -= $special2[1]; }
        }else{ $itemsrow["special"] = "X";}
		 
		 } else {$itemsrow2["attribute"] = 0; }
		 
		 //esse � o que poe o item equipado
		 if ($banco1id != 0) {
		 		$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='$banco1id' LIMIT 1","items");
		$itemsrow = mysql_fetch_array($itemsquery);
		
		
        if ($itemsrow["special"] != "X") {
            $special = explode(",",$itemsrow["special"]);
            $tochange = $special[0];
            $userrow[$tochange] = $userrow[$tochange] + $special[1];
            $specialchange1 = "$tochange='".$userrow[$tochange]."',";
            if ($tochange == "strength") { $userrow["attackpower"] += $special[1]; }
            if ($tochange == "dexterity") { $userrow["defensepower"] += $special[1]; }
        }else{ $itemsrow["special"] = "X";}
		} else {$itemsrow["attribute"] = 0;}
		   
       
      
		//checar itens
	
		
		$newattack = $userrow["attackpower"] + $itemsrow["attribute"] - $itemsrow2["attribute"];
		if ($userrow["currenthp"] > $userrow["maxhp"]) { $newhp = $userrow["maxhp"]; } else { $newhp = $userrow["currenthp"]; }
        if ($userrow["currentmp"] > $userrow["maxmp"]) { $newmp = $userrow["maxmp"]; } else { $newmp = $userrow["currentmp"]; }
        if ($userrow["currenttp"] > $userrow["maxtp"]) { $newtp = $userrow["maxtp"]; } else { $newtp = $userrow["currenttp"]; }
			//upload stats
		 $updatequery = doquery("UPDATE {{table}} SET $specialchange1 $specialchange2 attackpower='$newattack', currenthp='$newhp', currentmp='$newmp', currenttp='$newtp' WHERE charname='$usuariologadonome' LIMIT 1", "users");  
		//fim
		
		$updatequery = doquery("UPDATE {{table}} SET weaponid='$banco1id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET weaponname='$banco1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid1='$armaid' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome1='$arma' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
		
		}
		
		if ($Combobox2 == 1) { 
		
		// STATUS DOS ITENS
		global $userrow;
		   // Special item fields.
		  $specialchange1 = "";
        $specialchange2 = "";
		//esse � o q tira
		   	if ($armaduraid != 0) {
		$itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='$armaduraid' LIMIT 1", "items");
         $itemsrow2 = mysql_fetch_array($itemsquery2);
		 
		   if ($itemsrow2["special"] != "X") {
            $special2 = explode(",",$itemsrow2["special"]);
            $tochange2 = $special2[0];
            $userrow[$tochange2] = $userrow[$tochange2] - $special2[1];
            $specialchange2 = "$tochange2='".$userrow[$tochange2]."',";
            if ($tochange2 == "strength") { $userrow["attackpower"] -= $special2[1]; }
            if ($tochange2 == "dexterity") { $userrow["defensepower"] -= $special2[1]; }
        }else{ $itemsrow["special"] = "X";}
		 
		 } else {$itemsrow2["attribute"] = 0; }
		 
		 //esse � o que poe o item equipado
		 if ($banco2id != 0) {
		 		$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='$banco2id' LIMIT 1","items");
		$itemsrow = mysql_fetch_array($itemsquery);
		
		
        if ($itemsrow["special"] != "X") {
            $special = explode(",",$itemsrow["special"]);
            $tochange = $special[0];
            $userrow[$tochange] = $userrow[$tochange] + $special[1];
            $specialchange1 = "$tochange='".$userrow[$tochange]."',";
            if ($tochange == "strength") { $userrow["attackpower"] += $special[1]; }
            if ($tochange == "dexterity") { $userrow["defensepower"] += $special[1]; }
        }else{ $itemsrow["special"] = "X";}
		} else {$itemsrow["attribute"] = 0;}
		   
       
      
		//checar itens
	
		
		 $newdefense = $userrow["defensepower"] + $itemsrow["attribute"] - $itemsrow2["attribute"];
		if ($userrow["currenthp"] > $userrow["maxhp"]) { $newhp = $userrow["maxhp"]; } else { $newhp = $userrow["currenthp"]; }
        if ($userrow["currentmp"] > $userrow["maxmp"]) { $newmp = $userrow["maxmp"]; } else { $newmp = $userrow["currentmp"]; }
        if ($userrow["currenttp"] > $userrow["maxtp"]) { $newtp = $userrow["maxtp"]; } else { $newtp = $userrow["currenttp"]; }
			//upload stats
		 $updatequery = doquery("UPDATE {{table}} SET $specialchange1 $specialchange2 defensepower='$newdefense', currenthp='$newhp', currentmp='$newmp', currenttp='$newtp' WHERE charname='$usuariologadonome' LIMIT 1", "users");  
		//fim
		
		$updatequery = doquery("UPDATE {{table}} SET armorid='$banco2id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET armorname='$banco2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid2='$armaduraid' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome2='$armadura' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox3 == 1) { 
		
		// STATUS DOS ITENS
		global $userrow;
		   // Special item fields.
		  $specialchange1 = "";
        $specialchange2 = "";
		//esse � o q tira
		   	if ($escudoid != 0) {
		$itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='$escudoid' LIMIT 1", "items");
         $itemsrow2 = mysql_fetch_array($itemsquery2);
		 
		   if ($itemsrow2["special"] != "X") {
            $special2 = explode(",",$itemsrow2["special"]);
            $tochange2 = $special2[0];
            $userrow[$tochange2] = $userrow[$tochange2] - $special2[1];
            $specialchange2 = "$tochange2='".$userrow[$tochange2]."',";
            if ($tochange2 == "strength") { $userrow["attackpower"] -= $special2[1]; }
            if ($tochange2 == "dexterity") { $userrow["defensepower"] -= $special2[1]; }
        }else{ $itemsrow["special"] = "X";}
		 
		 } else {$itemsrow2["attribute"] = 0; }
		 
		 //esse � o que poe o item equipado
		 if ($banco3id != 0) {
		 		$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='$banco3id' LIMIT 1","items");
		$itemsrow = mysql_fetch_array($itemsquery);
		
		
        if ($itemsrow["special"] != "X") {
            $special = explode(",",$itemsrow["special"]);
            $tochange = $special[0];
            $userrow[$tochange] = $userrow[$tochange] + $special[1];
            $specialchange1 = "$tochange='".$userrow[$tochange]."',";
            if ($tochange == "strength") { $userrow["attackpower"] += $special[1]; }
            if ($tochange == "dexterity") { $userrow["defensepower"] += $special[1]; }
        }else{ $itemsrow["special"] = "X";}
		} else {$itemsrow["attribute"] = 0;}
		   
       
      
		//checar itens
	
		
		 $newdefense = $userrow["defensepower"] + $itemsrow["attribute"] - $itemsrow2["attribute"];
		if ($userrow["currenthp"] > $userrow["maxhp"]) { $newhp = $userrow["maxhp"]; } else { $newhp = $userrow["currenthp"]; }
        if ($userrow["currentmp"] > $userrow["maxmp"]) { $newmp = $userrow["maxmp"]; } else { $newmp = $userrow["currentmp"]; }
        if ($userrow["currenttp"] > $userrow["maxtp"]) { $newtp = $userrow["maxtp"]; } else { $newtp = $userrow["currenttp"]; }
			//upload stats
		 $updatequery = doquery("UPDATE {{table}} SET $specialchange1 $specialchange2 defensepower='$newdefense', currenthp='$newhp', currentmp='$newmp', currenttp='$newtp' WHERE charname='$usuariologadonome' LIMIT 1", "users");  
		//fim
		
		$updatequery = doquery("UPDATE {{table}} SET shieldid='$banco3id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET shieldname='$banco3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid3='$escudoid' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome3='$escudo' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox4 == 1) { 
		
		
		//AJEITAR SLOTS
		global $userrow;
		$dropquery = doquery("SELECT * FROM {{table}} WHERE id='$banco4id' LIMIT 1", "drops");
		$slotquery = doquery("SELECT * FROM {{table}} WHERE id='$idslot1' LIMIT 1", "drops");
            $slotrow = mysql_fetch_array($slotquery);
    $droprow = mysql_fetch_array($dropquery);
	$variavel = 0;
	if ($banco4id != 0) { $variavel += 1;}
	if ($idslot1 != 0) { $variavel += 1; }
	
	if ($variavel == 2) { //se tem item nos 2 slots
		
            
            $old1 = explode(",",$slotrow["attribute1"]);
            if ($slotrow["attribute2"] != "X") { $old2 = explode(",",$slotrow["attribute2"]); } else { $old2 = array(0=>"maxhp",1=>0); }
            $new1 = explode(",",$droprow["attribute1"]);
            if ($droprow["attribute2"] != "X") { $new2 = explode(",",$droprow["attribute2"]); } else { $new2 = array(0=>"maxhp",1=>0); }
            
            $userrow[$old1[0]] -= $old1[1];
            $userrow[$old2[0]] -= $old2[1];
            if ($old1[0] == "strength") { $userrow["attackpower"] -= $old1[1]; }
            if ($old1[0] == "dexterity") { $userrow["defensepower"] -= $old1[1]; }
            if ($old2[0] == "strength") { $userrow["attackpower"] -= $old2[1]; }
            if ($old2[0] == "dexterity") { $userrow["defensepower"] -= $old2[1]; }
            
            $userrow[$new1[0]] += $new1[1];
            $userrow[$new2[0]] += $new2[1];
            if ($new1[0] == "strength") { $userrow["attackpower"] += $new1[1]; }
            if ($new1[0] == "dexterity") { $userrow["defensepower"] += $new1[1]; }
            if ($new2[0] == "strength") { $userrow["attackpower"] += $new2[1]; }
            if ($new2[0] == "dexterity") { $userrow["defensepower"] += $new2[1]; }
            
            if ($userrow["currenthp"] > $userrow["maxhp"]) { $userrow["currenthp"] = $userrow["maxhp"]; }
            if ($userrow["currentmp"] > $userrow["maxmp"]) { $userrow["currentmp"] = $userrow["maxmp"]; }
            if ($userrow["currenttp"] > $userrow["maxtp"]) { $userrow["currenttp"] = $userrow["maxtp"]; }
            
            
            $query = doquery("UPDATE {{table}} SET $old1[0]='".$userrow[$old1[0]]."',$old2[0]='".$userrow[$old2[0]]."',$new1[0]='".$userrow[$new1[0]]."',$new2[0]='".$userrow[$new2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."',currenthp='".$userrow["currenthp"]."',currentmp='".$userrow["currentmp"]."',currenttp='".$userrow["currenttp"]."' WHERE charname='$usuariologadonome' LIMIT 1", "users");
			 } if ($variavel == 1) {//se n�o tem item no slot
            
            $new1 = explode(",",$droprow["attribute1"]);
            if ($droprow["attribute2"] != "X") { $new2 = explode(",",$droprow["attribute2"]); } else { $new2 = array(0=>"maxhp",1=>0);
			}
			$old1 = explode(",",$slotrow["attribute1"]);
            if ($slotrow["attribute2"] != "X") { $old2 = explode(",",$slotrow["attribute2"]); } else { $old2 = array(0=>"maxhp",1=>0);} $userrow[$new1[0]] += $new1[1];
            $userrow[$new2[0]] += $new2[1];
            if ($new1[0] == "strength") { $userrow["attackpower"] += $new1[1]; }
            if ($new1[0] == "dexterity") { $userrow["defensepower"] += $new1[1]; }
            if ($new2[0] == "strength") { $userrow["attackpower"] += $new2[1]; }
            if ($new2[0] == "dexterity") { $userrow["defensepower"] += $new2[1]; }
			
			 $userrow[$old1[0]] -= $old1[1];
            $userrow[$old2[0]] -= $old2[1];
            if ($old1[0] == "strength") { $userrow["attackpower"] -= $old1[1]; }
            if ($old1[0] == "dexterity") { $userrow["defensepower"] -= $old1[1]; }
            if ($old2[0] == "strength") { $userrow["attackpower"] -= $old2[1]; }
            if ($old2[0] == "dexterity") { $userrow["defensepower"] -= $old2[1]; }
            
			if($banco4id != 0) {
                      $query = doquery("UPDATE {{table}} SET $new1[0]='".$userrow[$new1[0]]."',$new2[0]='".$userrow[$new2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."' WHERE charname='$usuariologadonome' LIMIT 1", "users");}
			if($idslot1 != 0) {
					  $query = doquery("UPDATE {{table}} SET $old1[0]='".$userrow[$old1[0]]."',$old2[0]='".$userrow[$old2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."' WHERE charname='$usuariologadonome' LIMIT 1", "users");}
					  
					  }
			
			
			//termina aqui
		
		
		$updatequery = doquery("UPDATE {{table}} SET slot1id='$banco4id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot1name='$banco4' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid4='$idslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome4='$nomeslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox4 == 3) { 
		
		
		
		
		
		$updatequery = doquery("UPDATE {{table}} SET slot1id='$idslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot1name='$nomeslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot2id='$idslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot2name='$nomeslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		if ($Combobox4 == 4) { 
		$updatequery = doquery("UPDATE {{table}} SET slot1id='$idslot3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot1name='$nomeslot3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot3id='$idslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot3name='$nomeslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox5 == 3) { 
		$updatequery = doquery("UPDATE {{table}} SET slot2id='$idslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot2name='$nomeslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot1id='$idslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot1name='$nomeslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		if ($Combobox5 == 4) { 
		$updatequery = doquery("UPDATE {{table}} SET slot2id='$idslot3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot2name='$nomeslot3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot3id='$idslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot3name='$nomeslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox6 == 3) { 
		$updatequery = doquery("UPDATE {{table}} SET slot3id='$idslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot3name='$nomeslot1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot1id='$idslot3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot1name='$nomeslot3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		if ($Combobox6 == 4) { 
		$updatequery = doquery("UPDATE {{table}} SET slot2id='$idslot3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot2name='$nomeslot3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot3id='$idslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot3name='$nomeslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox5 == 1) { 
		
		//AJEITAR SLOTS
		global $userrow;
		$dropquery = doquery("SELECT * FROM {{table}} WHERE id='$banco5id' LIMIT 1", "drops");
		$slotquery = doquery("SELECT * FROM {{table}} WHERE id='$idslot2' LIMIT 1", "drops");
            $slotrow = mysql_fetch_array($slotquery);
    $droprow = mysql_fetch_array($dropquery);
	$variavel = 0;
	if ($banco5id != 0) { $variavel += 1;}
	if ($idslot2 != 0) { $variavel += 1; }
	
	if ($variavel == 2) { //se tem item nos 2 slots
		
            
            $old1 = explode(",",$slotrow["attribute1"]);
            if ($slotrow["attribute2"] != "X") { $old2 = explode(",",$slotrow["attribute2"]); } else { $old2 = array(0=>"maxhp",1=>0); }
            $new1 = explode(",",$droprow["attribute1"]);
            if ($droprow["attribute2"] != "X") { $new2 = explode(",",$droprow["attribute2"]); } else { $new2 = array(0=>"maxhp",1=>0); }
            
            $userrow[$old1[0]] -= $old1[1];
            $userrow[$old2[0]] -= $old2[1];
            if ($old1[0] == "strength") { $userrow["attackpower"] -= $old1[1]; }
            if ($old1[0] == "dexterity") { $userrow["defensepower"] -= $old1[1]; }
            if ($old2[0] == "strength") { $userrow["attackpower"] -= $old2[1]; }
            if ($old2[0] == "dexterity") { $userrow["defensepower"] -= $old2[1]; }
            
            $userrow[$new1[0]] += $new1[1];
            $userrow[$new2[0]] += $new2[1];
            if ($new1[0] == "strength") { $userrow["attackpower"] += $new1[1]; }
            if ($new1[0] == "dexterity") { $userrow["defensepower"] += $new1[1]; }
            if ($new2[0] == "strength") { $userrow["attackpower"] += $new2[1]; }
            if ($new2[0] == "dexterity") { $userrow["defensepower"] += $new2[1]; }
            
            if ($userrow["currenthp"] > $userrow["maxhp"]) { $userrow["currenthp"] = $userrow["maxhp"]; }
            if ($userrow["currentmp"] > $userrow["maxmp"]) { $userrow["currentmp"] = $userrow["maxmp"]; }
            if ($userrow["currenttp"] > $userrow["maxtp"]) { $userrow["currenttp"] = $userrow["maxtp"]; }
            
            
            $query = doquery("UPDATE {{table}} SET $old1[0]='".$userrow[$old1[0]]."',$old2[0]='".$userrow[$old2[0]]."',$new1[0]='".$userrow[$new1[0]]."',$new2[0]='".$userrow[$new2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."',currenthp='".$userrow["currenthp"]."',currentmp='".$userrow["currentmp"]."',currenttp='".$userrow["currenttp"]."' WHERE charname='$usuariologadonome' LIMIT 1", "users");
			 } if ($variavel == 1) {//se n�o tem item no slot
            
            $new1 = explode(",",$droprow["attribute1"]);
            if ($droprow["attribute2"] != "X") { $new2 = explode(",",$droprow["attribute2"]); } else { $new2 = array(0=>"maxhp",1=>0);
			}
			$old1 = explode(",",$slotrow["attribute1"]);
            if ($slotrow["attribute2"] != "X") { $old2 = explode(",",$slotrow["attribute2"]); } else { $old2 = array(0=>"maxhp",1=>0);} $userrow[$new1[0]] += $new1[1];
            $userrow[$new2[0]] += $new2[1];
            if ($new1[0] == "strength") { $userrow["attackpower"] += $new1[1]; }
            if ($new1[0] == "dexterity") { $userrow["defensepower"] += $new1[1]; }
            if ($new2[0] == "strength") { $userrow["attackpower"] += $new2[1]; }
            if ($new2[0] == "dexterity") { $userrow["defensepower"] += $new2[1]; }
			
			 $userrow[$old1[0]] -= $old1[1];
            $userrow[$old2[0]] -= $old2[1];
            if ($old1[0] == "strength") { $userrow["attackpower"] -= $old1[1]; }
            if ($old1[0] == "dexterity") { $userrow["defensepower"] -= $old1[1]; }
            if ($old2[0] == "strength") { $userrow["attackpower"] -= $old2[1]; }
            if ($old2[0] == "dexterity") { $userrow["defensepower"] -= $old2[1]; }
            
			if($banco5id != 0) {
                      $query = doquery("UPDATE {{table}} SET $new1[0]='".$userrow[$new1[0]]."',$new2[0]='".$userrow[$new2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."' WHERE charname='$usuariologadonome' LIMIT 1", "users");}
			if($idslot2 != 0) {
					  $query = doquery("UPDATE {{table}} SET $old1[0]='".$userrow[$old1[0]]."',$old2[0]='".$userrow[$old2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."' WHERE charname='$usuariologadonome' LIMIT 1", "users");}
					  
					  }
			
			
			//termina aqui
			
			
		$updatequery = doquery("UPDATE {{table}} SET slot2id='$banco5id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET slot2name='$banco5' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid5='$idslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome5='$nomeslot2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox6 == 1) { 
		
		
		//AJEITAR SLOTS
		global $userrow;
		$dropquery = doquery("SELECT * FROM {{table}} WHERE id='$banco6id' LIMIT 1", "drops");
		$slotquery = doquery("SELECT * FROM {{table}} WHERE id='$idslot3' LIMIT 1", "drops");
            $slotrow = mysql_fetch_array($slotquery);
    $droprow = mysql_fetch_array($dropquery);
	$variavel = 0;
	if ($banco6id != 0) { $variavel += 1;}
	if ($idslot3 != 0) { $variavel += 1; }
	
	if ($variavel == 2) { //se tem item nos 2 slots
		
            
            $old1 = explode(",",$slotrow["attribute1"]);
            if ($slotrow["attribute2"] != "X") { $old2 = explode(",",$slotrow["attribute2"]); } else { $old2 = array(0=>"maxhp",1=>0); }
            $new1 = explode(",",$droprow["attribute1"]);
            if ($droprow["attribute2"] != "X") { $new2 = explode(",",$droprow["attribute2"]); } else { $new2 = array(0=>"maxhp",1=>0); }
            
            $userrow[$old1[0]] -= $old1[1];
            $userrow[$old2[0]] -= $old2[1];
            if ($old1[0] == "strength") { $userrow["attackpower"] -= $old1[1]; }
            if ($old1[0] == "dexterity") { $userrow["defensepower"] -= $old1[1]; }
            if ($old2[0] == "strength") { $userrow["attackpower"] -= $old2[1]; }
            if ($old2[0] == "dexterity") { $userrow["defensepower"] -= $old2[1]; }
            
            $userrow[$new1[0]] += $new1[1];
            $userrow[$new2[0]] += $new2[1];
            if ($new1[0] == "strength") { $userrow["attackpower"] += $new1[1]; }
            if ($new1[0] == "dexterity") { $userrow["defensepower"] += $new1[1]; }
            if ($new2[0] == "strength") { $userrow["attackpower"] += $new2[1]; }
            if ($new2[0] == "dexterity") { $userrow["defensepower"] += $new2[1]; }
            
            if ($userrow["currenthp"] > $userrow["maxhp"]) { $userrow["currenthp"] = $userrow["maxhp"]; }
            if ($userrow["currentmp"] > $userrow["maxmp"]) { $userrow["currentmp"] = $userrow["maxmp"]; }
            if ($userrow["currenttp"] > $userrow["maxtp"]) { $userrow["currenttp"] = $userrow["maxtp"]; }
            
            
            $query = doquery("UPDATE {{table}} SET $old1[0]='".$userrow[$old1[0]]."',$old2[0]='".$userrow[$old2[0]]."',$new1[0]='".$userrow[$new1[0]]."',$new2[0]='".$userrow[$new2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."',currenthp='".$userrow["currenthp"]."',currentmp='".$userrow["currentmp"]."',currenttp='".$userrow["currenttp"]."' WHERE charname='$usuariologadonome' LIMIT 1", "users");
			 } if ($variavel == 1) {//se n�o tem item no slot
            
            $new1 = explode(",",$droprow["attribute1"]);
            if ($droprow["attribute2"] != "X") { $new2 = explode(",",$droprow["attribute2"]); } else { $new2 = array(0=>"maxhp",1=>0);
			}
			$old1 = explode(",",$slotrow["attribute1"]);
            if ($slotrow["attribute2"] != "X") { $old2 = explode(",",$slotrow["attribute2"]); } else { $old2 = array(0=>"maxhp",1=>0);} $userrow[$new1[0]] += $new1[1];
            $userrow[$new2[0]] += $new2[1];
            if ($new1[0] == "strength") { $userrow["attackpower"] += $new1[1]; }
            if ($new1[0] == "dexterity") { $userrow["defensepower"] += $new1[1]; }
            if ($new2[0] == "strength") { $userrow["attackpower"] += $new2[1]; }
            if ($new2[0] == "dexterity") { $userrow["defensepower"] += $new2[1]; }
			
			 $userrow[$old1[0]] -= $old1[1];
            $userrow[$old2[0]] -= $old2[1];
            if ($old1[0] == "strength") { $userrow["attackpower"] -= $old1[1]; }
            if ($old1[0] == "dexterity") { $userrow["defensepower"] -= $old1[1]; }
            if ($old2[0] == "strength") { $userrow["attackpower"] -= $old2[1]; }
            if ($old2[0] == "dexterity") { $userrow["defensepower"] -= $old2[1]; }
            
			if($banco6id != 0) {
                      $query = doquery("UPDATE {{table}} SET $new1[0]='".$userrow[$new1[0]]."',$new2[0]='".$userrow[$new2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."' WHERE charname='$usuariologadonome' LIMIT 1", "users");}
			if($idslot3 != 0) {
					  $query = doquery("UPDATE {{table}} SET $old1[0]='".$userrow[$old1[0]]."',$old2[0]='".$userrow[$old2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."' WHERE charname='$usuariologadonome' LIMIT 1", "users");}
					  
					  }
			
			
			//termina aqui
		
		
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
		
		
		if ($Combobox1 == 5) { 
		$updatequery = doquery("UPDATE {{table}} SET bancoid1='$b2banco1id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome1='$b2banco1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_bancoid1='$banco1id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_banconome1='$banco1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox2 == 5) { 
		$updatequery = doquery("UPDATE {{table}} SET bancoid2='$b2banco2id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome2='$b2banco2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_bancoid2='$banco2id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_banconome2='$banco2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox3 == 5) { 
		$updatequery = doquery("UPDATE {{table}} SET bancoid3='$b2banco3id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome3='$b2banco3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_bancoid3='$banco3id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_banconome3='$banco3' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox4 == 5) { 
		$updatequery = doquery("UPDATE {{table}} SET bancoid4='$b2banco4id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome4='$b2banco4' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_bancoid4='$banco4id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_banconome4='$banco4' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox5 == 5) { 
		$updatequery = doquery("UPDATE {{table}} SET bancoid5='$b2banco5id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome5='$b2banco5' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_bancoid5='$banco5id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_banconome5='$banco5' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		if ($Combobox6 == 5) { 
		$updatequery = doquery("UPDATE {{table}} SET bancoid6='$b2banco6id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome6='$b2banco6' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_bancoid6='$banco6id' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET b2_banconome6='$banco6' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		
		
		
		
		$usuriologadodinheiro = $usuriologadodinheiro - 0;
		
				
		$updatequery = doquery("UPDATE {{table}} SET gold='$usuriologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");
		
        
         display("O processo banc�rio foi completado com sucesso.<br /><br />Voc� pode <a href=\"index.php\">clicar aqui</a> para continuar jogando ou <a href=\"users.php?do=banco\">voltar ao banco</a>.","Banco",false,false,false);
        die();
    }

    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/banco.gif\" /></center></td></tr></table>
	
	<b>Seu Ryou</b>: $usuriologadodinheiro / <b>Ryou no Banco</b>: $ryoudepositado<br><br><table border=0 cellspacing=0 cellpadding=0><tr><td>
	
	<b><font color=brown>Equipado:</font></b><br>
		<b>Arma</b>: $arma<br><b>Colete</b>: $armadura<br><b>Bandana</b>: $escudo<br><b>Slot 1</b>: $nomeslot1<br><b>Slot 2</b>: $nomeslot2<br><b>Slot 3</b>: $nomeslot3<br><br>
	</td><td width=20></td><td>
	
	<b><font color=brown>Banco de Trocas:</font></b><br>
	<b>Arma</b>: $banco1<br><b>Colete</b>: $banco2<br><b>Bandana</b>: $banco3<br><b>Slot 1</b>: $banco4<br><b>Slot 2</b>: $banco5<br><b>Slot 3</b>: $banco6<br><br><td width=20></td>
	
	<td>
	<b><font color=brown>Banco de Armazenamento:</font></b><br>
		<b>Arma</b>: $b2banco1<br><b>Colete</b>: $b2banco2<br><b>Bandana</b>: $b2banco3<br><b>Slot 1</b>: $b2banco4<br><b>Slot 2</b>: $b2banco5<br><b>Slot 3</b>: $b2banco6<br><br></td>
	</td>
	
	
	
	</tr></table>"
		.gettemplate("banco");
    $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
	
    display($page, "Banco", false, false, false); 
	
    
}













function doaritem() {
global $topvar;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		//$userrow = checkcookies();
		global $userrow;
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);die(); }
			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
			if ($userrow["level"] < 5) {display("Voc� n�o pode acessar essa fun��o se seu level for menor que 5!","Erro",false,false,false);die(); }
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
				
	/* OLDPASS � A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
        $userquery = doquery("SELECT * FROM {{table}} WHERE charname='$username' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { display("N�o existe nenhuma conta com esse Nome.","Erro",false,false,false);die(); }
        $userrow = mysql_fetch_array($userquery);
		if ($usuariologadoid == $userrow["id"]) { display("Voc� n�o pode doar Item para si mesmo.","Erro",false,false,false);die();}
				/*if ($userrow["password"] != md5($oldpass)) { die("The old password you provided was incorrect."); }
        /*$realnewpass = md5($newpass1); */
		if ($usuriologadodinheiro < 40) { display("Voc� n�o pode doar um Item com menos de 40 Ryou.","Erro",false,false,false);die();}
		
		if ($Combobox1 == 1) {
		if ($userrow["bancoid1"] != 0) { display("O jogador o qual voc� quer doar o Item, j� possui uma Arma no Banco de Trocas.","Erro",false,false,false);die();}
		
		else{ 
		$updatequery = doquery("UPDATE {{table}} SET bancoid1='$banco1id' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome1='$banco1' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid1='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome1='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		}
		
		
		if ($Combobox1 == 2) {
		if ($userrow["bancoid2"] != 0) { display("O jogador o qual voc� quer doar o Item, j� possui um Colete no Banco de Trocas.","Erro",false,false,false);die();}
		
		else{ 
		$updatequery = doquery("UPDATE {{table}} SET bancoid2='$banco2id' WHERE charname'$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome2='$banco2' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid2='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome2='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		}
		
		
		if ($Combobox1 == 3) {
		if ($userrow["bancoid3"] != 0) { display("O jogador o qual voc� quer doar o Item, j� possui uma Bandana no Banco de Trocas.","Erro",false,false,false);die();}
		else{ 
		$updatequery = doquery("UPDATE {{table}} SET bancoid3='$banco3id' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome3='$banco3' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid3='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome3='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		}
		
		if ($Combobox1 == 4) {
		if ($userrow["bancoid4"] != 0) { display("O jogador o qual voc� quer doar o Item, j� possui um Item no Slot 1 no Banco de Trocas.","Erro",false,false,false);die();}
		else{ 
		$updatequery = doquery("UPDATE {{table}} SET bancoid4='$banco4id' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome4='$banco4' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid4='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome4='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		}
		
		if ($Combobox1 == 5) {
		if ($userrow["bancoid5"] != 0) { display("O jogador o qual voc� quer doar o Item, j� possui um Item no Slot 2 no Banco de Trocas.","Erro",false,false,false);die();}
		
		else{ 
		$updatequery = doquery("UPDATE {{table}} SET bancoid5='$banco5id' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome5='$banco5' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid5='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome5='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		}
		
		if ($Combobox1 == 6) {
		if ($userrow["bancoid6"] != 0) { display("O jogador o qual voc� quer doar o Item, j� possui um Item no Slot 3 no Banco de Trocas.","Erro",false,false,false);die();}
		
		else{ 
		$updatequery = doquery("UPDATE {{table}} SET bancoid6='$banco6id' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome6='$banco6' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET bancoid6='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET banconome6='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		}
		}
		
		if ($Combobox1 == 0) {display("Primeiro selecione uma a��o.","Erro",false,false,false);die();}
		$usuriologadodinheiro = $usuriologadodinheiro - 40;
				
		
		$updatequery = doquery("UPDATE {{table}} SET gold='$usuriologadodinheiro' WHERE charname='$usuariologadonome' LIMIT 1","users");
        
				
        display("O Item foi retirado da sua conta e doado com sucesso. Foram retirados da sua conta, 40 Ryou.<br /><br />Voc� pode <a href=\"index.php\">clicar aqui</a> para continuar jogando ou <a href=\"users.php?do=doaritem\">doar mais Item</a>.","Doar Item",false,false,false);
        die();
    }
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/doaritem.gif\" /></center></td></tr></table>
	
	
	<b>Seu Ryou</b>: $usuriologadodinheiro<br><br><b><font color=brown>Seu Banco de Trocas:</font></b><br><b>Arma</b>: $banco1<br><b>Colete</b>: $banco2<br><b>Bandana</b>: $banco3<br><b>Slot 1</b>: $banco4<br><b>Slot2</b>: $banco5<br><b>Slot3</b>: $banco6<br><br>".gettemplate("doaritem");
    $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Doar Item", false, false, false); 
    
}

















function troca1() {

global $topvar;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		 //$userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);die(); }
					if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
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
			
						
	/* OLDPASS � A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
		 $userquery = doquery("SELECT * FROM {{table}} WHERE charname='$username' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { display("N�o existe nenhuma conta com esse Nome.","Erro",false,false,false);die(); }
        $userrow = mysql_fetch_array($userquery);
		
		
		//testando se os dois jogadores est�o no mesmo mapa
	$monsterquery = doquery("SELECT * FROM {{table}} WHERE charname='$username' LIMIT 1", "users");
    $jogador2row = mysql_fetch_array($monsterquery);
    if ($userrow["longitude"] != $jogador2row["longitude"]) {display("Voc� s� pode trocar com um jogador que est� no mesmo mapa que o seu! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
		if ($userrow["latitude"] != $jogador2row["latitude"]) {display("Voc� s� pode trocar com um jogador que est� no mesmo mapa que o seu! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
		//acaba aqui
		
		
		if ($Combobox1 == 0) { display("Primeiro selecione um Item.","Erro",false,false,false);die(); }
		        
		$updatequery = doquery("UPDATE {{table}} SET trocanomeitem='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocaiditem='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanomejogador='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanumero='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocaposicaoitem='0' WHERE charname='$usuariologadonome' LIMIT 1","users");

//colocando a posi��o do item, qual item �.
		$updatequery = doquery("UPDATE {{table}} SET trocaposicaoitem='$Combobox1' WHERE charname='$usuariologadonome' LIMIT 1","users");
		
		if ($Combobox1 == 1) {$nomeitempronto = $banco1; $iditempronto = $banco1id;}
		if ($Combobox1 == 2) {$nomeitempronto = $banco2;$iditempronto = $banco2id;}
		if ($Combobox1 == 3) {$nomeitempronto = $banco3;$iditempronto = $banco3id;}
		if ($Combobox1 == 4) {$nomeitempronto = $banco4;$iditempronto = $banco4id;}
		if ($Combobox1 == 5) {$nomeitempronto = $banco5;$iditempronto = $banco5id;}
		if ($Combobox1 == 6) {$nomeitempronto = $banco6;$iditempronto = $banco6id;}
		//confere se o item se a casa do jogador a ser doado est� vazia
		$nomedoslot = bancoid.$Combobox1;
		if ($userrow["$nomedoslot"] != 0) { display("O Jogador o qual voc� realizar a troca, j� possui um Item no Banco de Trocas referente ao item que voc� quer trocar.","Erro",false,false,false);die(); }
				
		
		$updatequery = doquery("UPDATE {{table}} SET trocanomeitem='$nomeitempronto' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocaiditem='$iditempronto' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanomejogador='$username' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanumero='1' WHERE charname='$username' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanomejogador='$usuariologadonome' WHERE charname='$username' LIMIT 1","users");
		
		
      	
		
        
				
        display("A primeira etapa da troca foi realizada. Envie esse link: <font color=red>http://nigeru.com/narutorpg/users.php?do=troca2</font> para o Jogador o qual voc� deseja realizar a Troca.<br /><br />Voc� pode <a href=\"index.php\">clicar aqui</a> para continuar jogando, caso n�o queira mais realizar uma troca, ou ainda <a href=\"users.php?do=troca1\">iniciar uma nova Troca</a>.","Realizar Troca",false,false,false);
        die();
    }
    $page = "
	<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/troca.gif\" /></center></td></tr></table>
	
	<b>Seu Ryou</b>: $usuriologadodinaheiro<br><br>
	
	<b><font color=brown>Seu Banco de Trocas:</font></b><br>
	<b>Arma</b>: $banco1<br><b>Colete</b>: $banco2<br><b>Bandana</b>: $banco3<br><b>Slot 1</b>: $banco4<br><b>Slot 2</b>: $banco5<br><b>Slot 3</b>: $banco6<br><br>
	
	".gettemplate("troca1");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Realizar Troca", false, false, false); 
    
}




function troca2() {
global $topvar;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		 //$userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
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
		$username = $userrow["trocanomejogador"];
		
		 if ($numerodatroca != 1) { display("Respeite a ordem da troca, envie esse link para o outro jogador.","Erro",false,false,false);die(); }
						
	/* OLDPASS � A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
		
		$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$username' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { display("N�o existe nenhuma conta com esse Nome.","Erro",false,false,false);die(); }
		$userrow = mysql_fetch_array($userquery);
			
       		if (!is_numeric($oldpass)) { display("A quantidade de Ryou deve ser um n�mero.","Erro",false,false,false);die(); }
			$oldpass = floor($oldpass);
		
		
	//testando se os dois jogadores est�o no mesmo mapa
	$monsterquery = doquery("SELECT * FROM {{table}} WHERE charname='$username' LIMIT 1", "users");
    $jogador2row = mysql_fetch_array($monsterquery);
   if ($userrow["longitude"] != $jogador2row["longitude"]) {display("Voc� s� pode trocar com um jogador que est� no mesmo mapa que o seu! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
		if ($userrow["latitude"] != $jogador2row["latitude"]) {display("Voc� s� pode trocar com um jogador que est� no mesmo mapa que o seu! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
		//acaba aqui
		
		
		if ($oldpass > $usuriologadodinheiro) {display("Voc� n�o pode trocar mais Ryou que a sua quantidade.","Erro",false,false,false); die(); }
		if ($oldpass < 1) {display("Voc� n�o pode trocar menos que 1 Ryou.","Erro",false,false,false); die(); }
		if ($oldpass > 999999999) {display("Voc� n�o pode utilizar um n�mero com 10 algarismos.","Erro",false,false,false); die(); }
		
		$nomedoitemprontop = $oldpass." Ryou";
		$updatequery = doquery("UPDATE {{table}} SET trocanomeitem='$nomedoitemprontop' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocaiditem='$oldpass' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET trocanomejogador='$username' WHERE charname='$usuariologadonome' LIMIT 1","users");
				
		//n�mero da troca
		$updatequery = doquery("UPDATE {{table}} SET trocanumero='2' WHERE charname='$username' LIMIT 1","users");
		
      	
		
        
				
        display("A segunda etapa da troca foi realizada. Envie esse link: <font color=red>http://nigeru.com/narutorpg/users.php?do=troca3</font> para o Jogador o qual voc� deseja realizar a Troca.<br /><br />Voc� pode <a href=\"index.php\">clicar aqui</a> para continuar jogando, caso n�o queira mais realizar uma troca, ou ainda <a href=\"users.php?do=troca1\">iniciar uma nova Troca</a>.","Realizar Troca",false,false,false);
        die();
    }
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/troca.gif\" /></center></td></tr></table>
	
	<b>Seu Ryou</b>: $usuriologadodinheiro<br><br><b>Trocar com</b>: $username<br><br>".gettemplate("troca2");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Realizar Troca", false, false, false); 
    
}

function troca3() {
global $topvar;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		 //$userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
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
		if (mysql_num_rows($userquery) != 1) { display("N�o existe nenhuma conta com o nome do Jogador.","Erro",false,false,false);die(); }
		$userrow = mysql_fetch_array($userquery);
		$itemdetrocajogador2 = $userrow["trocanomeitem"];
		$nomedojogadordatroca2 = $userrow["trocanomejogador"];
		if ($nomedojogadordatroca2 != $usuariologadonome) {$fim = true;}
		if ($fim){ display("Voc� n�o pode comandar uma Troca que n�o � pra voc�. <a href=\"users.php?do=troca1\">Clique aqui</a> para realizar uma nova troca.","Erro",false,false,false);die(); }
		if ($numerodatrocadurante != 2) { display("Respeite a ordem da troca, envie esse link para o outro jogador.","Erro",false,false,false);die(); }
		
		//testando se os dois jogadores est�o no mesmo mapa
	$monsterquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomedojogadordatroca2' LIMIT 1", "users");
    $jogador2row = mysql_fetch_array($monsterquery);
    if ($userrow["longitude"] != $jogador2row["longitude"]) {display("Voc� s� pode trocar com um jogador que est� no mesmo mapa que o seu! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
		if ($userrow["latitude"] != $jogador2row["latitude"]) {display("Voc� s� pode trocar com um jogador que est� no mesmo mapa que o seu! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
		//acaba aqui
						
	/* OLDPASS � A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
		
      
		
		
		
		if ($numerodatrocadurante != 2){  display("N�o foi poss�vel realizar uma troca, recomece uma nova troca <a href=\"users.php?do=troca1\">clicando aqui</a>.","Erro",false,false,false);die(); }
		
		
		
		
		
		//n�mero da troca
			$updatequery = doquery("UPDATE {{table}} SET trocanumero='3' WHERE charname='$nomedojogadordatroca' LIMIT 1","users");
		
      	
		
        
				
        display("A terceira etapa da troca foi realizada. Envie esse link: <font color=red>http://nigeru.com/narutorpg/users.php?do=troca4</font> para o Jogador o qual voc� deseja realizar a Troca.<br /><br />Voc� pode <a href=\"index.php\">clicar aqui</a> para continuar jogando, caso n�o queira mais realizar uma troca, ou ainda <a href=\"users.php?do=troca1\">iniciar uma nova Troca</a>.","Realizar Troca",false,false,false);
        die();
    }
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/troca.gif\" /></center></td></tr></table>
	
	<b>Troca atual:</b><br>$trocanomeitemjogador por $itemdetrocajogador2.".gettemplate("troca3");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Realizar Troca", false, false, false); 
    
}






function troca4() {
global $topvar;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		// $userrow = checkcookies();
		global $userrow;
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);die(); }
					if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
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
		if (mysql_num_rows($userquery) != 1) { display("N�o existe nenhuma conta com o nome do Jogador.","Erro",false,false,false);die(); }
		$userrow = mysql_fetch_array($userquery);
		$itemdetrocajogador2 = $userrow["trocanomeitem"];
		$nomedojogadordatroca2 = $userrow["trocanomejogador"];
		$iddojogadordatroca2 = $userrow["trocaiditem"];
		$posicaoitem2 = $userrow["trocaposicaoitem"];
		$dinheirodojogador2 = $userrow["gold"];
		if ($nomedojogadordatroca2 != $usuariologadonome) {$fim = true;}
		if ($fim){  display("Voc� n�o pode comandar uma Troca que n�o � pra voc�. <a href=\"users.php?do=troca1\">Clique aqui</a> para realizar uma nova troca.","Erro",false,false,false);die(); }
		if ($numerodatrocadurante != 3) { display("Respeite a ordem da troca, envie esse link para o outro jogador.","Erro",false,false,false);die(); }
						
	/* OLDPASS � A QUANTIDADE DE DINHEIRO DOADO */
	
	//testando se os dois jogadores est�o no mesmo mapa
	$monsterquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomedojogadordatroca2' LIMIT 1", "users");
    $jogador2row = mysql_fetch_array($monsterquery);
    if ($userrow["longitude"] != $jogador2row["longitude"]) {display("Voc� s� pode trocar com um jogador que est� no mesmo mapa que o seu! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
		if ($userrow["latitude"] != $jogador2row["latitude"]) {display("Voc� s� pode trocar com um jogador que est� no mesmo mapa que o seu! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
		//acaba aqui
	
	
    if (isset($_POST["submit"])) {
        extract($_POST);
		
        
		
		
		//if ($numerodatrocadurante != 3){ die("N�o foi poss�vel realizar uma troca, recomece uma nova troca <a href=\"users.php?do=troca1\">clicando aqui</a>."); }
		
		
		
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
		
		
        
				
        display("A troca foi realizada com sucesso e seu Item foi enviado para o Banco de Trocas.<br /><br />Voc� pode <a href=\"index.php\">clicar aqui</a> para continuar jogando, caso n�o queira mais realizar uma troca, ou ainda <a href=\"users.php?do=troca1\">iniciar uma nova Troca</a>.","Realizar Troca",false,false,false);
        die();
		
		
    }
	
	
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/troca.gif\" /></center></td></tr></table>
	
	<b>Troca atual:</b><br>$trocanomeitemjogador por $itemdetrocajogador2.".gettemplate("troca4");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Realizar Troca", false, false, false); 
    
}






















function batalha1() {
global $topvar;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		 //$userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
			/*fim do teste */
			
			 
			 //dados de batalha
		$batalhanome = $userrow["batalha_nome"];
		$batalhaid = $userrow["batalha_id"];
		$batalhahp = $userrow["batalha_hp"];
		
		//fim
		$hpjogador = $userrow["currenthp"];
		$mpjogador = $userrow["currentmp"];
	
		
		
		

    if (isset($_POST["submit"])) {
        extract($_POST);
		
		//DADOS JOGADOR 2
		$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$jogador' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { display("N�o existe nenhuma conta com esse nome.","Erro",false,false,false);die(); }
		
		$userrow = mysql_fetch_array($userquery);
		if ($usuariologadonome == $jogador) {  display("Voc� n�o pode batalhar com voc� mesmo.","Erro",false,false,false);die();}
		$nomedojogador2 = $userrow["charname"];
		$batalhanome2 = $userrow["batalha_nome"];
		$batalhaid2 = $userrow["batalha_id"];
		$batalhahp2 = $userrow["batalha_hp"];
		$hpjogador2 = $userrow["currenthp"];
		$mpjogador2 = $usarrow["currentmp"];
				
		
	//testando se os dois jogadores est�o no mesmo mapa
	$monsterquery = doquery("SELECT * FROM {{table}} WHERE charname='$jogador' LIMIT 1", "users");
    $jogador2row = mysql_fetch_array($monsterquery);
    if ($userrow["longitude"] != $jogador2row["longitude"]) {display("Voc� s� pode duelar com um jogador que est� no mesmo mapa que o seu! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
		if ($userrow["latitude"] != $jogador2row["latitude"]) {display("Voc� s� pode duelar com um jogador que est� no mesmo mapa que o seu! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
		//acaba aqui
     		
		
	
     $updatequery = doquery("UPDATE {{table}} SET currentmonstersleep='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentuberdamage='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentuberdefense='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_nome='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_hp='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonsterimmune='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonster='' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_timer='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_timer2='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$updatequery = doquery("UPDATE {{table}} SET batalha_id='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$updatequery = doquery("UPDATE {{table}} SET batalha_acao='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		
		
		
		$updatequery = doquery("UPDATE {{table}} SET batalha_nome='$jogador' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_hp='$hpjogador' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='$hpjogador2' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonsterimmune='$mpjogador2' WHERE charname='$usuariologadonome' LIMIT 1","users");
			$updatequery = doquery("UPDATE {{table}} SET batalha_timer='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
			$updatequery = doquery("UPDATE {{table}} SET batalha_timer2='5' WHERE charname='$usuariologadonome' LIMIT 1","users");
		
				
		$updatequery = doquery("UPDATE {{table}} SET batalha_id='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$updatequery = doquery("UPDATE {{table}} SET batalha_id='1' WHERE charname='$jogador' LIMIT 1","users");
		
		
		
        
				
        display("<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/duelo.gif\" /></center></td></tr></table>A primeira etapa para come�ar um duelo foi realizada. Envie esse link: <font color=red>http://nigeru.com/narutorpg/users.php?do=batalha1</font> para o Jogador o qual voc� deseja realizar o Duelo.<br><br>Se os dois jogadores j� preencheram a p�gina, acessem esse link: <font color=red><a href=\"http://nigeru.com/narutorpg/users.php?do=duelo\"> http://nigeru.com/narutorpg/users.php?do=duelo</a></font> ao mesmo tempo.<br /><br />Voc� pode <a href=\"index.php\">clicar aqui</a> para continuar jogando, caso n�o queira mais realizar um duelo, ou ainda <a href=\"users.php?do=batalha1\">iniciar um novo Duelo</a>.","Realizar Duelo",false,false,false);
        die();
    }
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/duelo.gif\" /></center></td></tr></table>".gettemplate("batalha1");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Realizar Duelo", false, false, false); 
    
}










function resetarduelo() {
global $topvar;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		 //$userrow = checkcookies();
		 global $userrow;
		 
		 		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);die(); }
		if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
			/*fim do teste */
			
			 
			 //dados de batalha
		$batalhanome = $userrow["batalha_nome"];
		$batalhaid = $userrow["batalha_id"];
		$batalhahp = $userrow["batalha_hp"];
		
		//fim
		$hpjogador = $userrow["currenthp"];
		$mpjogador = $userrow["currentmp"];
	
		
		
						

   
		
		
		$updatequery = doquery("UPDATE {{table}} SET currentmonstersleep='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentuberdamage='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentuberdefense='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_nome='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_hp='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonsterimmune='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonster='' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_timer='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_timer2='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$updatequery = doquery("UPDATE {{table}} SET batalha_id='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$updatequery = doquery("UPDATE {{table}} SET batalha_acao='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
			
	
	
	
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/duelo.gif\" /></center></td></tr></table>Seu duelo atual foi resetado com sucesso. Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Realizar Duelo", false, false, false); 
    
}

























function duelo() {
global $topvar;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		 //$userrow = checkcookies();
		 global $userrow;
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);die(); }
		
			if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
			/*fim do teste */
			
			 
			 //dados de batalha
		$batalhanome = $userrow["batalha_nome"];
		$batalhaid = $userrow["batalha_id"];
		$batalhahp = $userrow["batalha_hp"];
		
		//fim
		$hpjogador = $userrow["currenthp"];
		$hpdosegundojogador = $userrow["currentmonsterhp"];
		$mpjogador = $userrow["currentmp"];
		$mpjogador2 = $userrow["currentmonsterimmune"];
	
		//fim
				$timer = $userrow["batalha_timer"];
				$timeraux = $userrow["batalha_timer2"];
		$mosternofim = $userrow["batalha_acao"];
		
			
	
		
		if ($mosternofim == $usuariologadonome.": ") {$mosternofim = "N�o houve uma a��o na rodada passada.";}
		if ($mosternofim == "None") {$mosternofim = "N�o houve uma a��o na rodada passada.";}
		
		
		
		//colocando pra duelar somente no mesmo mapa
	$monsterquery = doquery("SELECT * FROM {{table}} WHERE charname='$batalhanome' LIMIT 1", "users");
    $jogador2row = mysql_fetch_array($monsterquery);
  	
		if ($userrow["longitude"] != $jogador2row["longitude"]) {display("Voc� s� pode duelar com um jogador que est� no mesmo mapa que o seu! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
		if ($userrow["latitude"] != $jogador2row["latitude"]) {display("Voc� s� pode duelar com um jogador que est� no mesmo mapa que o seu! Clique <a href=\"index.php\">aqui</a> para voltar ao jogo.","Erro",false,false,false);die(); }
		/*//fazer o timer 2
					$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$batalhanome' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { die("N�o existe nenhuma conta com esse nome."); }
		$userrow = mysql_fetch_array($userquery);
		$timer2 = $userrow["batalha_timer"];
		$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$usuariologadonome' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { die("N�o existe nenhuma conta com esse nome."); }
		$userrow = mysql_fetch_array($userquery);
		//fim
        
		//if ($batalhaid != 1) {die("Respeite a ordem de Duelo, envie esse link para o outro Jogador."); }
		if  ($timer < 2) {if ($timer2 > 1){
		$timer2 = $timer2 - 1;
		$updatequery = doquery("UPDATE {{table}} SET batalha_timer='$timer2' WHERE charname='$batalhanome' LIMIT 1","users");
		die("Aguarde...$timer segundos. Turno do segundo jogador.<meta HTTP-EQUIV='refresh' CONTENT='1;URL=users.php?do=duelo'>"); }
		else{
		$updatequery = doquery("UPDATE {{table}} SET batalha_timer='6' WHERE charname='$usuariologadonome' LIMIT 1","users");}
		} */
		
						
				//TIMER ANTIGO FALHO
		/*$timer -= 5;
		$updatequery = doquery("UPDATE {{table}} SET batalha_timer='$timer' WHERE charname='$usuariologadonome' LIMIT 1","users");
				if ($timer < 1) { 
				if ($timeraux > 5) {
				$updatequery = doquery("UPDATE {{table}} SET batalha_timer='6' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$updatequery = doquery("UPDATE {{table}} SET batalha_timer2='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
				
				}
				
				else{
				$timeraux += 1;
				$updatequery = doquery("UPDATE {{table}} SET batalha_timer='6' WHERE charname='$batalhanome' LIMIT 1","users");
				$updatequery = doquery("UPDATE {{table}} SET batalha_timer2='$timeraux' WHERE charname='$usuariologadonome' LIMIT 1","users");
				die("Aguarde o seu turno novamente.<meta HTTP-EQUIV='refresh' CONTENT='1;URL=users.php?do=duelo'>"); 
				
				}
				}*/
				//fazer duelo primeiro
				if($batalhanome == None) {
				display("Primeiro voc� deve escolher algu�m para Duelar. Clique <a href=\"users.php?do=batalha1\">aqui</a>, para fazer isso.","Realizar Duelo",false,false,false);
				die(); }
				
				
				
				
				
				
				
				//VITORIA E DERROTA.
				if($hpjogador == 0) {
				display("
				
				<table width=\"100%\">
<tr><td align=\"center\"><center><img src=\"images/duelo.gif\" alt=\"Duelo\" /></center></td></tr></table>
			
			<table><tr><td width=\"310\" valign=\"middle\"><center>
				
				<br><br>A��o do �ltimo turno:<br>".$mosternofim."<br><br>Voc� perdeu a batalha!<br>O seu HP � 0!<br><br><b>Vencedor:</b> $batalhanome<br><b>Perdedor:</b> $usuariologadonome
				</center>
			
			</td><td>
			
			
<table width=\"165\" height=\"175\" background=\"layoutnovo/graficos/fundo.png\" style=\"background-repeat:no-repeat;;background-position:left top\"><tr height=\"50%\"><td></td></tr><tr><td><center><img src=\"layoutnovo/graficos/".$userrow["avatar"]."_morto.gif\"></center>
</td></tr><tr  height=\"15\"><td></td></tr></table>



</td></tr></table>
				
				
				","Realizar Duelo",false,false,false);
				$updatequery = doquery("UPDATE {{table}} SET currentmonstersleep='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentuberdamage='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentuberdefense='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_nome='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_hp='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonsterimmune='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonster='' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_timer='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_acao='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
				$updatequery = doquery("UPDATE {{table}} SET batalha_id='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
				
				die(); }
				
				if($hpdosegundojogador == 0){
				display("
				
				<table width=\"100%\">
<tr><td align=\"center\"><center><img src=\"images/duelo.gif\" alt=\"Duelo\" /></center></td></tr></table>
			
			<table><tr><td width=\"310\" valign=\"middle\"><center>
				
				<br><br>A��o do �ltimo turno:<br>".$mosternofim."<br><br>Voc� ganhou a batalha!<br>O HP do seu Oponente � 0!<br><br><br><b>Vencedor:</b> $usuariologadonome<br><b>Perdedor:</b> $batalhanome
				
				</center>
			
			</td><td>
			
			

<table width=\"165\" height=\"175\" background=\"layoutnovo/graficos/fundo.png\" style=\"background-repeat:no-repeat;;background-position:left top\"><tr height=\"30%\"><td></td></tr><tr><td><center><img src=\"layoutnovo/graficos/".$userrow["avatar"]."_ganhou.gif\"></center>
</td></tr><tr  height=\"15\"><td></td></tr></table>



</td></tr></table>
				","Realizar Duelo",false,false,false);
				
				$updatequery = doquery("UPDATE {{table}} SET currentmonstersleep='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentuberdamage='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentuberdefense='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_nome='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_hp='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonsterimmune='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET currentmonster='' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_timer='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET batalha_acao='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
		
				$updatequery = doquery("UPDATE {{table}} SET batalha_id='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
				die(); }
				
				
				//VEZ DE QUAL JOGADOR
				
				if($batalhaid == 0) {
				display("Aguarde o seu turno novamente. O outro Jogador precisa fazer seu movimento.<meta HTTP-EQUIV='refresh' CONTENT='1;URL=users.php?do=duelo'>","Realizar Duelo",false,false,false);
				die(); }

					
						
				//JOGADOR SPELLS
				  $pagearray = array();
    $playerisdead = 0;
    
	//Graficos
		$pagearray["grafico"] = $userrow["avatar"]."_stance.gif";
	
    $pagearray["magiclist"] = "";
    $userspells = explode(",",$userrow["spells"]);
    $spellquery = doquery("SELECT id,name FROM {{table}}", "spells");
    while ($spellrow = mysql_fetch_array($spellquery)) {
	        $spell = false;
        foreach ($userspells as $a => $b) {
            if ($b == $spellrow["id"]) { $spell = true; }
        }
        if ($spell == true) {
				
			//if ($spellrow["type"] != 3) {//menos spell de sleep
            $pagearray["magiclist"] .= "<option value=\"".$spellrow["id"]."\">".$spellrow["name"]."</option>\n";
			//}//termina aqui o spell
        }
        unset($spell);
    }
    if ($pagearray["magiclist"] == "") { $pagearray["magiclist"] = "<option value=\"0\">None</option>\n"; }
    $magiclist = $pagearray["magiclist"];
    
    	$chancetoswingfirst = 1;
		
		
		//FIM JOGADOR SPELLS		
						
						
						
						
						
						

    if (isset($_POST["duelo"])) {
        extract($_POST);
		
		
		//Graficos
		$pagearray["grafico"] = $userrow["avatar"]."_ataque.gif";
		
		//DADOS JOGADOR 1
		$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$usuariologadonome' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { display("N�o existe nenhuma conta com esse nome.","Erro",false,false,false);die(); }
		$userrow = mysql_fetch_array($userquery);
		
		// DADOS JOGADOR 2.
    $monsterquery = doquery("SELECT * FROM {{table}} WHERE charname='$batalhanome' LIMIT 1", "users");
    $jogador2row = mysql_fetch_array($monsterquery);
    $pagearray["monstername"] = $jogador2row["charname"];
	$batalhahp2 = $jogador2row["batalha_hp"];
		
		   // Your turn.
        $pagearray["yourturn"] = "";
        $tohit = ceil(rand($userrow["attackpower"]*.75,$userrow["attackpower"])/3);
        $toexcellent = rand(1,150);
		//atributo determinacao // antes $toexcellent <= sqrt($userrow["strength"])
		$determinacao = sqrt($userrow["strength"]) + ($userrow["determinacao"]*2/100);
		
        if ($toexcellent <= $determinacao) { $tohit *= 2; $pagearray["yourturn"] .= "Hit Excelente!<br />"; }
        $toblock = ceil(rand(($jogador2row["defensepower"]/10)*.75,($jogador2row["defensepower"]/10))/3);        
        $tododge = rand(1,200);
		//atributo precisao //  antes $tododge <= sqrt($monsterrow["armor"])
		$tododge = $tododge + floor($userrow["precisao"]*3/100);
		//agilidade
		$tododgejogador2agilidade = sqrt($jogador2row["dexterity"]/6) + floor($jogador2row["agilidade"]*2/100);
        if ($tododge <= $tododgejogador2agilidade) { 
            $tohit = 0; $pagearray["yourturn"] .= "O jogador est� desviando. Nenhum dano foi recebido por ele.<br />"; 
            $monsterdamage = 0;
        } else {
            $monsterdamage = $tohit - $toblock;
            if ($monsterdamage < 1) { $monsterdamage = 1; }
            if ($userrow["currentuberdamage"] != 0) {
                $monsterdamage += ceil($monsterdamage * ($userrow["currentuberdamage"]/100));
            }
						
			
        
		
		
		 //parte teste, timer:
		/*	 $updatequery = doquery("UPDATE {{table}} SET batalha_timer='1' WHERE charname='$usuariologadonome' LIMIT 1","users");
	  $updatequery = doquery("UPDATE {{table}} SET batalha_timer='6' WHERE charname='$batalhanome' LIMIT 1","users");
	  //fim timer  */
	 
	  
        $pagearray["yourturn"] .= "Voc� atacou o jogador provocando $monsterdamage de dano.<br /><br />";
		
        $userrow["currentmonsterhp"] -= $monsterdamage;
		$pagearray["monsterhp"] = "HP do Jogador: " . $userrow["currentmonsterhp"] . "<br /><br />";
		
		
		//atualizando os dados de batalha, hp etc
		   $updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='".$userrow["currentmonsterhp"]."', currenthp='$hpjogador' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
		   $updatequery = doquery("UPDATE {{table}} SET batalha_hp='".$userrow["currentmonsterhp"]."', currenthp='".$userrow["currentmonsterhp"]."', currentmonsterimmune='".$userrow["currentmp"]."', currentmonsterhp='$hpjogador' WHERE charname='$batalhanome' LIMIT 1", "users");
		
		
		
	       
		}//fim da parte duelo
        // JOGADOR ESTA MORTO.
	 $variaveldapagina = "<b>".$usuariologadonome."</b>: ".$pagearray["yourturn"];
		$updatequery = doquery("UPDATE {{table}} SET batalha_acao='$variaveldapagina' WHERE charname='$batalhanome' LIMIT 1","users");  
	
			
		$updatequery = doquery("UPDATE {{table}} SET batalha_id='1' WHERE charname='$batalhanome' LIMIT 1","users");
				$updatequery = doquery("UPDATE {{table}} SET batalha_id='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
        
				
        display("<b>A��es</b>:<br>$variaveldapagina<br><br>Aguarde o seu turno novamente.<meta HTTP-EQUIV='refresh' CONTENT='5;URL=users.php?do=duelo'>","Realizar Duelo",false,false,false);
        die();
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	if (isset($_POST["spell"])) {
	
	//Graficos
		$pagearray["grafico"] = $userrow["avatar"]."_jutsu.gif";
	
      $healaravida = 0;  
        // Your turn.
        $pickedspell = $_POST["userspell"];
        if ($pickedspell == 0) { display("Voc� deve selecionar um Jutsu primeiro. Por favor volte e tente novamente.", "Error"); die(); }
        
        $newspellquery = doquery("SELECT * FROM {{table}} WHERE id='$pickedspell' LIMIT 1", "spells");
        $newspellrow = mysql_fetch_array($newspellquery);
        $spell = false;
        foreach($userspells as $a => $b) {
            if ($b == $pickedspell) { $spell = true; }
        }
        if ($spell != true) { display("Voc� ainda n�o aprendeu esse Jutsu. Por favor volte e tente novamente.", "Error"); die(); }
        if ($userrow["currentmp"] < $newspellrow["mp"]) { display("Voc� n�o tem Chakra suficiente para usar esse Jutsu. Por favor volte e tente novamente.", "Error"); die(); }
		
		
		
        if ($newspellrow["type"] == 1) { // Heal spell.
            $newhp = $userrow["currenthp"] + $newspellrow["attribute"];
            if ($userrow["maxhp"] < $newhp) { $newspellrow["attribute"] = $userrow["maxhp"] - $userrow["currenthp"]; $newhp = $userrow["currenthp"] + $newspellrow["attribute"]; }
            $userrow["currenthp"] = $newhp;
            $userrow["currentmp"] -= $newspellrow["mp"];
            $pagearray["yourturn"] = "Voc� usou o Jutsu: ".$newspellrow["name"]." e ganhou ".$newspellrow["attribute"]." Pontos de Vida.<br /><br />";
			
			//adicionando pontos de vida
			$hpjogador += $newspellrow["attribute"];
			if ($userrow["maxhp"] < $hpjogador) {$hpjogador = $userrow["maxhp"];}
			$healaravida = 1;
					
			
        } elseif ($newspellrow["type"] == 2) { // Hurt spell.
		$variaveltb = 0;
            if ($variaveltb == 0) {
                $monsterdamage = rand((($newspellrow["attribute"]/6)*5), $newspellrow["attribute"]);
                $userrow["currentmonsterhp"] -= $monsterdamage;
                $pagearray["yourturn"] = "Voc� usou o Jutsu: ".$newspellrow["name"]." e causou $monsterdamage de dano.<br /><br />";
            } else {
                $pagearray["yourturn"] = "Voc� usou o Jutsu: ".$newspellrow["name"].", mas o jogador � imune � seu Jutsu.<br /><br />";
            }
			
			 			//monstro imune ao sleep, eu adicionei
        $variavel = 2;
            $userrow["currentmp"] -= $newspellrow["mp"];
			} 
			/*elseif ($newspellrow["type"] == 3) { // Sleep spell.
            if ($variavel != 2) {
                $userrow["currentmonstersleep"] = $newspellrow["attribute"];
                $pagearray["yourturn"] = "Voc� usou o Jutsu: ".$newspellrow["name"].". O inimigo est� dormindo.<br /><br />";
            } else {
                $pagearray["yourturn"] = "Voc� usou o Jutsu: ".$newspellrow["name"].", mas o jogador � imune � ele.<br /><br />";
            }*/
			elseif ($newspellrow["type"] == 3) { // Sleep spell.
               $pagearray["yourturn"] = "Voc� usou o Jutsu: ".$newspellrow["name"].", mas os jogadores s�o imunes � ele.<br /><br />";
            
			
			 
	  
            $userrow["currentmp"] -= $newspellrow["mp"];
        } elseif ($newspellrow["type"] == 4) { // +Damage spell.
            $userrow["currentuberdamage"] = $newspellrow["attribute"];
            $userrow["currentmp"] -= $newspellrow["mp"];
            $pagearray["yourturn"] = "Voc� usou o Jutsu: ".$newspellrow["name"]." e ganhou ".$newspellrow["attribute"]."% de dano at� o fim da batalha.<br /><br />";
			
			
	  
	  
        } elseif ($newspellrow["type"] == 5) { // +Defense spell.
            $userrow["currentuberdefense"] = $newspellrow["attribute"];
            $userrow["currentmp"] -= $newspellrow["mp"];
            $pagearray["yourturn"] = "Voc� usou o Jutsu: ".$newspellrow["name"]." e ganhou ".$newspellrow["attribute"]."% de defesa at� o fim da batalha.<br /><br />";
			
			        
        }
            
			
			
			
			
			//atualizando os dados de batalha, hp etc
			
		   $updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='".$userrow["currentmonsterhp"]."', currentuberdefense='".$userrow["currentuberdefense"]."', currentmp='".$userrow["currentmp"]."', currentuberdamage='".$userrow["currentuberdamage"]."', currentuberdamage='".$userrow["currentuberdamage"]."', currenthp='$hpjogador'  WHERE id='".$userrow["id"]."' LIMIT 1", "users");
		   
		   $updatequery = doquery("UPDATE {{table}} SET batalha_hp='".$userrow["currentmonsterhp"]."', currenthp='".$userrow["currentmonsterhp"]."', currentmonsterimmune='$hpjogador', currentmonsterhp='$hpjogador' WHERE charname='$batalhanome' LIMIT 1", "users");
		   
		 /*  if ($healarvida != 0){
		   $updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='$healaravida' WHERE charname='$batalhanome' LIMIT 1","users");}*/
			
			
			//terminando
			
        $variaveldapagina = "<b>".$usuariologadonome."</b>: ".$pagearray["yourturn"];
		$updatequery = doquery("UPDATE {{table}} SET batalha_acao='$variaveldapagina' WHERE charname='$batalhanome' LIMIT 1","users");
						
			
		$updatequery = doquery("UPDATE {{table}} SET batalha_id='1' WHERE charname='$batalhanome' LIMIT 1","users");
				$updatequery = doquery("UPDATE {{table}} SET batalha_id='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
        
				
        display("<b>A��es</b>:<br>$variaveldapagina<br><br>Aguarde o seu turno novamente.<meta HTTP-EQUIV='refresh' CONTENT='5;URL=users.php?do=duelo'>","Realizar Duelo",false,false,false);
            die();
        }
		
	
	
	
	
	
	
	
	
	
	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	
	
	
	
	
	//CONTEUDO DA PAGINA AQUI !!!
	if ($pagearray["grafico"] == ""){$pagearray["grafico"] = $userrow["avatar"]."_stance.gif";}
    $page = "<table width=\"100%\"><tr><td width=\"100%\"align=\"center\"><center><img src=\"images/duelo.gif\" /></center></td></tr></table>
	
	
			
			<table><tr><td width=\"310\" valign=\"middle\">
	
	A��o do �ltimo turno:<br>$mosternofim<br><br>
	<table><tr><td><b>Seus Dados:</b><br><b>Nome</b>: $usuariologadonome<br><b>HP</b>: $hpjogador<br><b>CH</b>: $mpjogador</td>
	
	<td width=20></td><td><b>Desafiante</b><br><b>Nome</b>: $batalhanome<br><b>HP</b>: ".$userrow["currentmonsterhp"]."<br><b>CH</b>: $mpjogador2</td></tr></table>
	
	
	
	
	<br><br>Comando?<br /><br />
<form action=\"users.php?do=duelo\" method=\"post\">
<input type=\"submit\" name=\"duelo\" value=\"Atacar\" /><br /><br />
<select name=\"userspell\"><option value=\"0\">Escolha um Jutsu</option>$magiclist</select> <input type=\"submit\" name=\"spell\" value=\"Usar\" /><br /><br />



			
			</td><td>
			
			
			
			
			<table width=\"165\" height=\"175\" background=\"layoutnovo/graficos/fundo.png\" style=\"background-repeat:no-repeat;;background-position:left top\"><tr height=\"30%\"><td></td></tr><tr><td><center><img src=\"layoutnovo/graficos/".$pagearray["grafico"]."\"></center>
</td></tr><tr  height=\"15\"><td></td></tr></table>
			



</td></tr></table>


</form><br><h2><a href=\"users.php?do=resetarduelo\">Cancelar Duelo</a></h2>"
	
	
	
	
	
	
	
	
	;
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Realizar Duelo", false, false, false); 
    
}


















function procurarjogador() {
global $topvar;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		//$userrow = checkcookies();
		global $userrow;

		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);
		die(); }

					if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinaheiro = $userrow["gold"];
			/*fim do teste */
				
	/* OLDPASS � A QUANTIDADE DE DINHEIRO DOADO */
	
    if (isset($_POST["submit"])) {
        extract($_POST);
        $userquery = doquery("SELECT * FROM {{table}} WHERE charname='$username' LIMIT 1","users");
		
		

        if (mysql_num_rows($userquery) != 1) { display("N�o existe nenhuma conta com esse Nome.","Erro",false,false,false);die(); }
        $userrow = mysql_fetch_array($userquery);
			/*if ($userrow["password"] != md5($oldpass)) { die("The old password you provided was incorrect."); }
        /*$realnewpass = md5($newpass1); */
		$localiz = $userrow["id"];
		header("Location: index.php?do=onlinechar:$localiz");
		
		
		}
		
		
    $page = gettemplate("procurarjogador");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Procurar Jogador", false, false, false); 
    
}

?>