<?php // lib.php :: Common functions used throughout the program.

header("Content-Type: text/html;charset=ISO-8859-1");

$starttime = getmicrotime();
$numqueries = 0;
$version = "3.0";
$build = "";


// Handling for servers with magic_quotes turned on.
// Example from php.net.
if (get_magic_quotes_gpc()) {

   $_POST = array_map('stripslashes_deep', $_POST);
   $_GET = array_map('stripslashes_deep', $_GET);
   $_COOKIE = array_map('stripslashes_deep', $_COOKIE);

}
$_POST = array_map('addslashes_deep', $_POST);
$_POST = array_map('html_deep', $_POST);
$_GET = array_map('addslashes_deep', $_GET);
$_GET = array_map('html_deep', $_GET);
$_COOKIE = array_map('addslashes_deep', $_COOKIE);
$_COOKIE = array_map('html_deep', $_COOKIE);

function stripslashes_deep($value) {
    
   $value = is_array($value) ?
               array_map('stripslashes_deep', $value) :
               stripslashes($value);
   return $value;
   
}

function addslashes_deep($value) {
    
   $value = is_array($value) ?
               array_map('addslashes_deep', $value) :
               addslashes($value);
   return $value;
   
}

function html_deep($value) {
    
   $value = is_array($value) ?
               array_map('html_deep', $value) :
               htmlspecialchars($value);
   return $value;
   
}

function opendb() { // Open database connection.

    include('config.php');
    extract($dbsettings);
    $link = mysql_connect($server, $user, $pass) or die(mysql_error());
    mysql_select_db($name) or die(mysql_error());
    return $link;

}

function doquery($query, $table) { // Something of a tiny little database abstraction layer.
    
    include('config.php');
    global $numqueries;
    $sqlquery = mysql_query(str_replace("{{table}}", $dbsettings["prefix"] . "_" . $table, $query)) or die(mysql_error());
    $numqueries++;
    return $sqlquery;

}

function gettemplate($templatename) { // SQL query for the template.

    $filename = "templates/" . $templatename . ".php";
    include("$filename");
    return $template;
    
}

function parsetemplate($template, $array) { // Replace template with proper content.
    
    foreach($array as $a => $b) {
        $template = str_replace("{{{$a}}}", $b, $template);
    }
    return $template;
    
}

function getmicrotime() { // Used for timing script operations.

    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 

}

function prettydate($uglydate) { // Change the MySQL date format (YYYY-MM-DD) into something friendlier.

    return date("j F, Y", mktime(0,0,0,substr($uglydate, 5, 2),substr($uglydate, 8, 2),substr($uglydate, 0, 4)));

}
function padraodate($uglydate) { // Change the MySQL date format (YYYY-MM-DD) into something friendlier.

    $var1 = explode(' ',$uglydate);
	$var2 = explode('-', $var1[0]);
	return $var2[2].'-'.$var2[1].'-'.$var2[0].' / '.$var1[1];

}

function prettyforumdate($uglydate) { // Change the MySQL date format (YYYY-MM-DD) into something friendlier.

    return date("j F, Y", mktime(0,0,0,substr($uglydate, 5, 2),substr($uglydate, 8, 2),substr($uglydate, 0, 4)));

}

function is_email($email) { // Thanks to "mail(at)philipp-louis.de" from php.net!

    return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));

}

function makesafe($d) {
    
    $d = str_replace("\t","",$d);
    $d = str_replace("<","&#60;",$d);
    $d = str_replace(">","&#62;",$d);
    $d = str_replace("\n","",$d);
    $d = str_replace("|","??",$d);
    $d = str_replace("  "," &nbsp;",$d);
    return $d;
    
}

function admindisplay($content, $title) { // Finalize page and output to browser.
    
    global $numqueries, $userrow, $controlrow, $starttime, $version, $build;
    if (!isset($controlrow)) {
        $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
        $controlrow = mysql_fetch_array($controlquery);
    }
    
    $template = gettemplate("admin");
    
    // Make page tags for XHTML validation.
    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?>\n"
	
	. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n"
	

    . "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";

    $finalarray = array(
        "title"=>$title,
        "content"=>$content,
        "totaltime"=>round(getmicrotime() - $starttime, 4),
        "numqueries"=>$numqueries,
        "version"=>$version,
        "build"=>$build);
    $page = parsetemplate($template, $finalarray);
    $page = $xml . $page;

    if ($controlrow["compression"] == 1) { ob_start("ob_gzhandler"); }
    echo $page;
    die();
    
}




function display($content, $title, $topnav=true, $leftnav=true, $rightnav=true, $badstart=false) { // Finalize page and output to browser.
    
    global $numqueries, $userrow, $controlrow, $version, $build;
    if (!isset($controlrow)) {
        $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
        $controlrow = mysql_fetch_array($controlquery);
    }
    if ($badstart == false) { global $starttime; } else { $starttime = $badstart; }
    
    // Make page tags for XHTML validation.
    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"
    . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\">\n"
    . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";

    $template = gettemplate("primary");
	
    if ($userrow == true){$rightnav = gettemplate("rightnav"); $leftnav = gettemplate("leftnav");};
    //if ($rightnav == true) { $rightnav = gettemplate("rightnav"); } else { $rightnav = ""; }
    //if ($leftnav == true) { $leftnav = gettemplate("leftnav"); } else { $leftnav = ""; }
	//if ($topnav == true) {
        //$topnav = "<a href=\"index.php\"><img src=\"images/voltar.gif\" alt=\"Voltar a Jogar\" border=\"0\" title=\"Voltar ao Jogo\" /></a><a href=\"login.php?do=logout\"><img src=\"images/sair.gif\" alt=\"Sair\" title=\"Sair\" border=\"0\" /></a><a href=\"help.php\" target=\"_blank\" ><img src=\"images/ajuda.gif\" alt=\"Ajuda\" title=\"Ajuda\" border=\"0\" /></a>";
    //} else {
        //$topnav = "<a href=\"login.php?do=login\"><img src=\"images/button_login.gif\" alt=\"Log In\" title=\"Log In\" border=\"0\" /></a><brA> <a href=\"users.php?do=register\"><img src=\"images/button_register.gif\" alt=\"Register\" title=\"Register\" border=\"0\" /></a><br> <a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" title=\"Help\" target=\"_blank\" border=\"0\" /></a>";
    //}
	
		
	//USERROW = false.
	if ($userrow == false) {$topnav = "";$rightnav = "";$leftnav = "";}
	
		
	
    
    if (isset($userrow)) {
        
        // Get userrow again, in case something has been updated.
        $userquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["id"]."' LIMIT 1", "users");
        unset($userrow);
        $userrow = mysql_fetch_array($userquery);
        
	
		
		


        // Current town name.
        if ($userrow["currentaction"] == "In Town") {
            $townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
            $townrow = mysql_fetch_array($townquery);
            $userrow["currenttown"] = "<div id=\"naruto\">Bem-vindo a(o) ".$townrow["name"].".</div>";
        } elseif ($userrow["currentaction"] == "Exploring") {
            $userrow["currenttown"] = "<div id=\"naruto\">Voc&ecirc; est&aacute; explorando o mapa.</div>";
        }else{
			$userrow["currenttown"] = "<div id=\"naruto\">Voc&ecirc; est&aacute; em uma batalha.</div>";	
		}
        
        if ($controlrow["forumtype"] == 0) { $userrow["forumslink"] = ""; }
        elseif ($controlrow["forumtype"] == 1) { $userrow["forumslink"] = "<a href=\"forum.php\">Forum</a><br />"; }
        elseif ($controlrow["forumtype"] == 2) { $userrow["forumslink"] = "<a href=\"".$controlrow["forumaddress"]."\">Forum</a><br />"; }
        
		
		
        // Format various userrow stuffs...
        if ($userrow["latitude"] < 0) { $userrow["latitude"] = $userrow["latitude"] * -1 . "S"; } else { $userrow["latitude"] .= "N"; }
        if ($userrow["longitude"] < 0) { $userrow["longitude"] = $userrow["longitude"] * -1 . "W"; } else { $userrow["longitude"] .= "E"; }
        $userrow["experience"] = number_format($userrow["experience"]);
        $userrow["gold"] = number_format($userrow["gold"]);
        if ($userrow["authlevel"] == 1) { $userrow["adminlink"] = "<a href=\"admin.php\">Admin</a><br />"; } else { $userrow["adminlink"] = ""; }
        
        // HP/MP/TP bars.
        $stathp = ceil($userrow["currenthp"] / $userrow["maxhp"] * 100);
        if ($userrow["maxmp"] != 0) { $statmp = ceil($userrow["currentmp"] / $userrow["maxmp"] * 100); } else { $statmp = 0; }
        $stattp = ceil($userrow["currenttp"] / $userrow["maxtp"] * 100);
		 if ($userrow["maxep"] != 0) { $statep = ceil($userrow["currentep"] / $userrow["maxep"] * 100); } else { $statep = 0; }
		 if ($userrow["maxnp"] != 0) { $statnp = ceil($userrow["currentnp"] / $userrow["maxnp"] * 100); } else { $statnp = 0; }
        $stattable = "<table width=\"150\"><tr><td width=\"20%\">\n";
        $stattable .= "<table cellspacing=\"0\" cellpadding=\"0\"><tr><td style=\"padding:0px; width:15px; height:100px; border:solid 1px black; vertical-align:bottom;\">\n";
        if ($stathp >= 66) { $stattable .= "<div style=\"padding:0px; height:".$stathp."px; border-top:solid 1px black; background-image:url(images/barra_vermelho1.gif);\"><img src=\"images/barra_vermelho1.gif\" title=\"Pontos de Vida\" alt=\"\" /></div>"; }
        if ($stathp < 66 && $stathp >= 33) { $stattable .= "<div style=\"padding:0px; height:".$stathp."px; border-top:solid 1px black; background-image:url(images/barra_vermelho2.gif);\"><img src=\"images/barra_vermelho2.gif\" alt=\"\" /></div>"; }
        if ($stathp < 33) { $stattable .= "<div style=\"padding:0px; height:".$stathp."px; border-top:solid 1px black; background-image:url(images/barra_vermelho3.gif);\"><img src=\"images/barra_vermelho3.gif\" alt=\"\" /></div>"; }
        $stattable .= "</td></tr></table></td><td width=\"20%\">\n";
        $stattable .= "<table cellspacing=\"0\" cellpadding=\"0\"><tr><td style=\"padding:0px; width:15px; height:100px; border:solid 1px black; vertical-align:bottom;\">\n";
		
        if ($statmp >= 66) { $stattable .= "<div style=\"padding:0px; height:".$statmp."px; border-top:solid 1px black; background-image:url(images/barra_azul1.gif);\"><img src=\"images/barra_azul1.gif\" alt=\"\" /></div>"; }
        if ($statmp < 66 && $statmp >= 33) { $stattable .= "<div style=\"padding:0px; height:".$statmp."px; border-top:solid 1px black; background-image:url(images/barra_azul2.gif);\"><img src=\"images/barra_azul2.gif\" alt=\"\" /></div>"; }
        if ($statmp < 33) { $stattable .= "<div style=\"padding:0px; height:".$statmp."px; border-top:solid 1px black; background-image:url(images/barra_azul3.gif);\"><img src=\"images/barra_azul3.gif\" alt=\"\" /></div>"; }
        $stattable .= "</td></tr></table></td><td width=\"20%\">\n";
        $stattable .= "<table cellspacing=\"0\" cellpadding=\"0\"><tr><td style=\"padding:0px; width:15px; height:100px; border:solid 1px black; vertical-align:bottom;\">\n";
		
		if ($stattp >= 66) { $stattable .= "<div style=\"padding:0px; height:".$stattp."px; border-top:solid 1px black; background-image:url(images/barra_amarelo1.gif);\"><img src=\"images/barra_amarelo1.gif\" alt=\"\" /></div>"; }
        if ($stattp < 66 && $stattp >= 33) { $stattable .= "<div style=\"padding:0px; height:".$stattp."px; border-top:solid 1px black; background-image:url(images/barra_amarelo2.gif);\"><img src=\"images/barra_amarelo2.gif\" alt=\"\" /></div>"; }
        if ($stattp < 33) { $stattable .= "<div style=\"padding:0px; height:".$stattp."px; border-top:solid 1px black; background-image:url(images/barra_amarelo3.gif);\"><img src=\"images/barra_amarelo3.gif\" alt=\"\" /></div>"; }
        $stattable .= "</td></tr></table></td><td width=\"20%\">\n";
        $stattable .= "<table cellspacing=\"0\" cellpadding=\"0\"><tr><td style=\"padding:0px; width:15px; height:100px; border:solid 1px black; vertical-align:bottom;\">\n";
		
		if ($statnp >= 66) { $stattable .= "<div style=\"padding:0px; height:".$statnp."px; border-top:solid 1px black; background-image:url(images/barra_verde1.gif);\"><img src=\"images/barra_verde1.gif\" alt=\"\" /></div>"; }
        if ($statnp < 66 && $statnp >= 33) { $stattable .= "<div style=\"padding:0px; height:".$statnp."px; border-top:solid 1px black; background-image:url(images/barra_verde2.gif);\"><img src=\"images/barra_verde2.gif\" alt=\"\" /></div>"; }
        if ($statnp < 33) { $stattable .= "<div style=\"padding:0px; height:".$statnp."px; border-top:solid 1px black; background-image:url(images/barra_verde3.gif);\"><img src=\"images/barra_verde3.gif\" alt=\"\" /></div>"; }
        $stattable .= "</td></tr></table></td><td width=\"20%\">\n";
        $stattable .= "<table cellspacing=\"0\" cellpadding=\"0\"><tr><td style=\"padding:0px; width:15px; height:100px; border:solid 1px black; vertical-align:bottom;\">\n";
		
		 if ($statep >= 66) { $stattable .= "<div style=\"padding:0px; height:".$statep."px; border-top:solid 1px black; background-image:url(images/barra_roxa1.gif);\"><img src=\"images/barra_roxa1.gif\" alt=\"\" /></div>"; }
        if ($statep < 66 && $statep >= 33) { $stattable .= "<div style=\"padding:0px; height:".$statep."px; border-top:solid 1px black; background-image:url(images/barra_roxa2.gif);\"><img src=\"images/barra_roxa2.gif\" alt=\"\" /></div>"; }
        if ($statep < 33) { $stattable .= "<div style=\"padding:0px; height:".$statep."px; border-top:solid 1px black; background-image:url(images/barra_roxa3.gif);\"><img src=\"images/barra_roxa3.gif\" alt=\"\" /></div>"; }
        $stattable .= "</td></tr></table></td>\n";
        $stattable .= "</tr><tr><td>HP</td><td>CH</td><td>TP</td><td>NP</td><td>EP</td></tr></table>\n";
        $userrow["statbars"] = $stattable;
		
		
        
        // Now make numbers stand out if they're low.
        if ($userrow["currenthp"] <= ($userrow["maxhp"]/5)) { $userrow["currenthp"] = "<blink><span class=\"highlight\"><b>*".$userrow["currenthp"]."*</b></span></blink>"; }
        if ($userrow["currentmp"] <= ($userrow["maxmp"]/5)) { $userrow["currentmp"] = "<blink><span class=\"highlight\"><b>*".$userrow["currentmp"]."*</b></span></blink>"; }

        $spellquery = doquery("SELECT id,name,type FROM {{table}}","spells");
        $userspells = explode(",",$userrow["spells"]);
        $userrow["magiclist"] = "";
        while ($spellrow = mysql_fetch_array($spellquery)) {
            $spell = false;
            foreach($userspells as $a => $b) {
                if ($b == $spellrow["id"] && $spellrow["type"] == 1) { $spell = true; }
            }
            if ($spell == true) {
                $userrow["magiclist"] .= "<a href=\"index.php?do=spell:".$spellrow["id"]."\">".$spellrow["name"]."</a><br />";
            }
        }
        if ($userrow["magiclist"] == "") { $userrow["magiclist"] = "None"; }
        
		//Comandos para os personagens, em cima das cidades.
		$comandospersonagem = "<div id=\"comandosdentro\" style=\"z-index: 3;\"><table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" background=\"images/fundoviajar.png\" style=\"border:1px #000000 solid\"><tr style=\"border:1px #ffefb7 solid\"><td style=\"border:1px #ffefb7 solid\"><a href=\"troca.php?do=troca\"><img src=\"images/24/troca.gif\" title=\"Realizar uma Troca\" border=\"0\"></a></td><td style=\"border:1px #ffefb7 solid\"><a href=\"users.php?do=batalha1\"><img src=\"images/24/duelar.gif\" title=\"Realizar um Duelo\" border=\"0\"></a></td><td style=\"border:1px #ffefb7 solid\"><a href=\"encherhp.php\"><img src=\"images/24/descansar.gif\" title=\"Descansar\" border=\"0\"></a></td><td style=\"border:1px #ffefb7 solid\"><a href=\"javascript:openchatpopup()\"><img src=\"images/24/conversar.gif\" title=\"Abrir Chat Global\" border=\"0\"></a></td><td style=\"border:1px #ffefb7 solid\"><a href=\"javascript: procurarjogador();\"><img src=\"images/24/visualizar.gif\" title=\"Visualizar um Personagem\" border=\"0\"></a></td></tr>";
		//Parte provavelmente VIP?
		$comandospersonagem .= "<tr style=\"border:1px #ffefb7 solid\">";
		$comandospersonagem .= "<td style=\"border:1px #ffefb7 solid\"><a href=\"forum.php?do=msg\" title=\"Abrir o F&oacute;rum In-Game\"><img src=\"images/24/forum.gif\" border=\"0\"></a></td>";
		for ($h = 1; $h <=4; $h++){
		$comandospersonagem .= "<td style=\"border:1px #ffefb7 solid\"><img src=\"images/24/gif24.gif\"></td>";
		}
		$comandospersonagem .= "</tr></table></div><br><br>";
		
		//Qual Browser.
		include('funcoesinclusas.php');
		$var = browser();
		if ($var == "Internet Explorer (MSIE/Compatible)"){$var = "71px";}else{$var = "77px";}
		
		
        // Travel To list.
        $townslist = explode(",",$userrow["towns"]);
        $townquery2 = doquery("SELECT * FROM {{table}} ORDER BY id", "towns");
        $userrow["townslist"] = "<center><div style=\"width:179px;background-image:url('images/naruto.jpg');background-repeat:no-repeat; background-position: 1px 1px;\"><table><tr><td><div style=\"padding-right: 11px;padding-left: 15px;padding-bottom: $var; padding-top: 12px;\" id=\"editar\"><center><table><tr><td height=\"35\" valign=\"middle\"><center>".$userrow["currenttown"]."</center></td></tr></table></center></div></td></tr><tr><td><center><div id=\"cidadesfora\">$comandospersonagem<div id=\"cidadesdentro\" style=\"z-index: 2; display: block;\"><table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" background=\"images/fundoviajar.png\" style=\"border:1px #000000 solid\"><tr style=\"border:1px #000000 solid\"></center>";
		$quantas = 0;
        while ($townrow2 = mysql_fetch_array($townquery2)) {
            $town = false;
            foreach($townslist as $a => $b) {
                if ($b == $townrow2["id"]) { $town = true; }
            }
			if (($townrow2["id"] % 6) == 0){$userrow["townslist"] .= "</tr><tr style=\"border:1px #ffefb7 solid\">";}
            if ($town == true) { 
				
				if ($townrow2['latitude'] < 0){$lat = ($townrow2['latitude'] * -1)."S";}else{$lat = $townrow2['latitude']."N";}
				if ($townrow2['longitude'] < 0){$log = ($townrow2['longitude'] * -1)."W";}else{$log = $townrow2['longitude']."E";}
				
                $userrow["townslist"] .= "<td style=\"border:1px #ffefb7 solid\"><a href=\"index.php?do=gotown:".$townrow2["id"]."\"><img src=\"images/24/cidade_".$townrow2["id"].".gif\" title=\"[$lat,$log] ".$townrow2["name"]." - ".$townrow2['travelpoints']." TP\" border=\"0\"></a></td>";  

            }else{
				$userrow["townslist"] .= "<td style=\"border:1px #ffefb7 solid\"><img src=\"images/24/cidade_".$townrow2["id"]."p.gif\" border=\"0\"></td>"; 
			}
			$quantas = $townrow2["id"];
        }
		for ($i = $quantas; $i < 10; $i ++){
				$userrow["townslist"] .= "<td style=\"border:1px #ffefb7 solid\" background=\"images/fundocasa.jpg\"><img src=\"images/24/gif24.gif\" border=\"0\"></td>"; 
			}
		$userrow["townslist"] .= "</tr></table></div></div></center></td></tr></table></div>";
        
    } else {
        $userrow = array();
    }




    $finalarray = array(
        "dkgamename"=>$controlrow["gamename"],
        "title"=>$title,
        "content"=>$content,
        "rightnav"=>parsetemplate($rightnav,$userrow),
        "leftnav"=>parsetemplate($leftnav,$userrow),
        "topnav"=>$topnav,
        "totaltime"=>round(getmicrotime() - $starttime, 4),
        "numqueries"=>$numqueries,
        "version"=>$version,
        "build"=>$build);
    $page = parsetemplate($template, $finalarray);
    $page = $xml . $page;
    
    if ($controlrow["compression"] == 1) { ob_start("ob_gzhandler"); }
    echo $page;
    die();
    
}


?>