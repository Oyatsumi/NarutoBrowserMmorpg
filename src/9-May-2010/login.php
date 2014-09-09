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
        if (mysql_num_rows($query) != 1) { die("Invalid username or password. Please go back and try again."); }
        $row = mysql_fetch_array($query);
        if (isset($_POST["rememberme"])) { $expiretime = time()+31536000; $rememberme = 1; } else { $expiretime = 0; $rememberme = 0; }
        $cookie = $row["id"] . " " . $row["username"] . " " . md5($row["password"] . "--" . $dbsettings["secretword"]) . " " . $rememberme;
        setcookie("dkgame", $cookie, $expiretime, "/", "", 0);
        header("Location: index.php");
        die();
        
    }
    
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