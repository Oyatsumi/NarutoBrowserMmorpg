<?php // users.php :: Handles user account functions.

include('lib.php');
$link = opendb();

if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "register") { register(); }
    elseif ($do == "verify") { verify(); }
    elseif ($do == "lostpassword") { lostpassword(); }
    elseif ($do == "changepassword") { changepassword(); }
    
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


?>