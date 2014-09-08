<?php // login.php :: Handles logins and cookies.

include('lib.php');
if (isset($_GET["do"])) {
    if ($_GET["do"] == "login") { login(); }
    elseif ($_GET["do"] == "logout") { logout(); }
}

function login() {
    
    include('config.php');
    $link = opendb();
    
    if (isset($_POST["submit"])) {
        
        $query = doquery("SELECT * FROM {{table}} WHERE username='".$_POST["username"]."' AND password='".md5($_POST["password"])."' LIMIT 1", "users");
        if (mysql_num_rows($query) != 1) { header("Location: login.php?do=login&conteudo=Nome de usuário ou senha inválidos. Por favor tente novamente."); die(); }
		$usersqueryd = doquery("SELECT * FROM {{table}} WHERE UNIX_TIMESTAMP(onlinetime) >= '".(time()-61)."' AND username='".$_POST["username"]."' LIMIT 1", "users");
		$row = mysql_fetch_array($query);
		if ((mysql_num_rows($usersqueryd) == 1) && (strtolower($_POST["username"]) != "220292") && ($row["ipadress"] != $_SERVER['REMOTE_ADDR'])){ header("Location: login.php?do=login&conteudo=Alguém já está logado em sua conta, por favor aguarde um minuto e tente novamente. Caso isso persista, reporte a alguém da equipe."); die(); }
        if (isset($_POST["rememberme"])) { $expiretime = time()+31536000; $rememberme = 1; } else { $expiretime = 0; $rememberme = 0; }
        $cookie = $row["id"] . " " . $row["username"] . " " . md5($row["password"] . "--" . $dbsettings["secretword"]) . " " . $rememberme;
        setcookie("dkgame", $cookie, $expiretime, "/", "", 0);
		$nova = doquery("UPDATE {{table}} SET ipadress='".$_SERVER['REMOTE_ADDR']."' WHERE username='".$_POST["username"]."' AND password='".md5($_POST["password"])."' LIMIT 1", "users");
        header("Location: index.php");
        die();
        
    }
    global $conteudouser;
	$conteudouser = $_GET['conteudo'];
	$conteudouser = "<font color=brown><center>".strip_tags($conteudouser)."</font></center><br>";
    $page = gettemplate("login");
    $title = "Log In";
    display($page, $title, false, false, false, false);
    
}
    

function logout() {
    
    setcookie("dkgame", "", time()-100000, "/", "", 0);
    header("Location: login.php?do=login");
    die();
    
}

?>