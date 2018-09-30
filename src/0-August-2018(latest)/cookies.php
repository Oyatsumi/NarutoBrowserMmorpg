<?php // cookies.php :: Handles cookies. (Mmm, tasty!)

function checkcookies() {

    include('config.php');
    
    $row = false;
    
    if (isset($_COOKIE["dkgame"])) {
        
        // COOKIE FORMAT:
        // {ID} {USERNAME} {PASSWORDHASH} {REMEMBERME}
        $theuser = explode(" ",$_COOKIE["dkgame"]);
        $query = doquery("SELECT * FROM {{table}} WHERE username='$theuser[1]'", "users");
        if (mysqli_num_rows($query) != 1) { die("Cookie inválido (Erro 1). Por favor apague os cookies do seu navegador e logue novamente."); }
        $row = mysqli_fetch_array($query);
        if ($row["id"] != $theuser[0]) { die("Cookie inválido (Erro 2). Por favor apague os cookies do seu navegador e logue novamente."); }
        if (md5($row["password"] . "--" . $dbsettings["secretword"]) !== $theuser[2]) { die("Cookie inválido (Erro 3). Por favor apague os cookies do seu navegador e logue novamente."); }
        
        // If we've gotten this far, cookie should be valid, so write a new one.
        $newcookie = implode(" ",$theuser);
        if ($theuser[3] == 1) { $expiretime = time()+31536000; } else { $expiretime = 0; }
        setcookie ("dkgame", $newcookie, $expiretime, "/", "", 0);
        $onlinequery = doquery("UPDATE {{table}} SET onlinetime=NOW() WHERE id='$theuser[0]' LIMIT 1", "users");
        
    }
        
    return $row;
    
}

?>