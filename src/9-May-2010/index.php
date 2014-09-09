<?php // index.php :: Primary program script, evil alien overlord, you decide.

if (file_exists('install.php')) { die("Please delete <b>install.php</b> from your Dragon Knight directory before continuing."); }
include('lib.php');
include('cookies.php');
$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);

// Login (or verify) if not logged in.
$userrow = checkcookies();
if ($userrow == false) { 
    if (isset($_GET["do"])) {
        if ($_GET["do"] == "verify") { header("Location: users.php?do=verify"); die(); }
    }
    header("Location: login.php?do=login"); die(); 
}
// Close game.
if ($controlrow["gameopen"] == 0) { display("The game is currently closed for maintanence. Please check back later.","Game Closed"); die(); }
// Force verify if the user isn't verified yet.
if ($controlrow["verifyemail"] == 1 && $userrow["verify"] != 1) { header("Location: users.php?do=verify"); die(); }
// Block user if he/she has been banned.
if ($userrow["authlevel"] == 2) { die("Your account has been blocked. Please try back later."); }

if (isset($_GET["do"])) {
    $do = explode(":",$_GET["do"]);
    
    // Town functions.
    if ($do[0] == "inn") { include('towns.php'); inn(); }
    elseif ($do[0] == "buy") { include('towns.php'); buy(); }
    elseif ($do[0] == "buy2") { include('towns.php'); buy2($do[1]); }
    elseif ($do[0] == "buy3") { include('towns.php'); buy3($do[1]); }
    elseif ($do[0] == "sell") { include('towns.php'); sell(); }
    elseif ($do[0] == "maps") { include('towns.php'); maps(); }
    elseif ($do[0] == "maps2") { include('towns.php'); maps2($do[1]); }
    elseif ($do[0] == "maps3") { include('towns.php'); maps3($do[1]); }
    elseif ($do[0] == "gotown") { include('towns.php'); travelto($do[1]); }
    
    // Exploring functions.
    elseif ($do[0] == "move") { include('explore.php'); move(); }
    
    // Fighting functions.
    elseif ($do[0] == "fight") { include('fight.php'); fight(); }
    elseif ($do[0] == "victory") { include('fight.php'); victory(); }
    elseif ($do[0] == "drop") { include('fight.php'); drop(); }
    elseif ($do[0] == "dead") { include('fight.php'); dead(); }
    
    // Misc functions.
    elseif ($do[0] == "verify") { header("Location: users.php?do=verify"); die(); }
    elseif ($do[0] == "spell") { include('heal.php'); healspells($do[1]); }
    elseif ($do[0] == "showchar") { showchar(); }
    elseif ($do[0] == "onlinechar") { onlinechar($do[1]); }
    elseif ($do[0] == "showmap") { showmap(); }
    elseif ($do[0] == "babblebox") { babblebox(); }
    elseif ($do[0] == "ninja") { ninja(); }
    
} else { donothing(); }

function donothing() {
    
    global $userrow;

    if ($userrow["currentaction"] == "In Town") {
        $page = dotown();
        $title = "In Town";
    } elseif ($userrow["currentaction"] == "Exploring") {
        $page = doexplore();
        $title = "Exploring";
    } elseif ($userrow["currentaction"] == "Fighting")  {
        $page = dofight();
        $title = "Fighting";
    }
    
    display($page, $title);
    
}

function dotown() { // Spit out the main town page.
    
    global $userrow, $controlrow, $numqueries;
    
    $townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
    if (mysql_num_rows($townquery) == 0) { display("There is an error with your user account, or with the town data. Please try again.","Error"); }
    $townrow = mysql_fetch_array($townquery);
    
    // News box. Grab latest news entry and display it. Something a little more graceful coming soon maybe.
    if ($controlrow["shownews"] == 1) { 
        $newsquery = doquery("SELECT * FROM {{table}} ORDER BY id DESC LIMIT 1", "news");
        $newsrow = mysql_fetch_array($newsquery);
        $townrow["news"] = "<table width=\"95%\"><tr><td class=\"title\">Latest News</td></tr><tr><td>\n";
        $townrow["news"] .= "<span class=\"light\">[".prettydate($newsrow["postdate"])."]</span><br />".nl2br($newsrow["content"]);
        $townrow["news"] .= "</td></tr></table>\n";
    } else { $townrow["news"] = ""; }
    
    // Who's Online. Currently just members. Guests maybe later.
    if ($controlrow["showonline"] == 1) {
        $onlinequery = doquery("SELECT * FROM {{table}} WHERE UNIX_TIMESTAMP(onlinetime) >= '".(time()-600)."' ORDER BY charname", "users");
        $townrow["whosonline"] = "<table width=\"95%\"><tr><td class=\"title\">Who's Online</td></tr><tr><td>\n";
        $townrow["whosonline"] .= "There are <b>" . mysql_num_rows($onlinequery) . "</b> user(s) online within the last 10 minutes: ";
        while ($onlinerow = mysql_fetch_array($onlinequery)) { $townrow["whosonline"] .= "<a href=\"index.php?do=onlinechar:".$onlinerow["id"]."\">".$onlinerow["charname"]."</a>" . ", "; }
        $townrow["whosonline"] = rtrim($townrow["whosonline"], ", ");
        $townrow["whosonline"] .= "</td></tr></table>\n";
    } else { $townrow["whosonline"] = ""; }
    
    if ($controlrow["showbabble"] == 1) {
        $townrow["babblebox"] = "<table width=\"95%\"><tr><td class=\"title\">Babble Box</td></tr><tr><td>\n";
        $townrow["babblebox"] .= "<iframe src=\"index.php?do=babblebox\" name=\"sbox\" width=\"100%\" height=\"250\" frameborder=\"0\" id=\"bbox\">Your browser does not support inline frames! The Babble Box will not be available until you upgrade to a newer <a href=\"http://www.mozilla.org\" target=\"_new\">browser</a>.</iframe>";
        $townrow["babblebox"] .= "</td></tr></table>\n";
    } else { $townrow["babblebox"] = ""; }
    
    $page = gettemplate("towns");
    $page = parsetemplate($page, $townrow);
    
    return $page;
    
}

function doexplore() { // Just spit out a blank exploring page.
    
    // Exploring without a GET string is normally when they first log in, or when they've just finished fighting.
    
$page = <<<END
<table width="100%">
<tr><td class="title"><img src="images/title_exploring.gif" alt="Exploring" /></td></tr>
<tr><td>
You are exploring the map, and nothing has happened. Continue exploring using the direction buttons or the Travel To menus.
</td></tr>
</table>
END;

    return $page;
        
}

function dofight() { // Redirect to fighting.
    
    header("Location: index.php?do=fight");
    
}

function showchar() {
    
    global $userrow, $controlrow;
    
    // Format various userrow stuffs.
    $userrow["experience"] = number_format($userrow["experience"]);
    $userrow["gold"] = number_format($userrow["gold"]);
    if ($userrow["expbonus"] > 0) { 
        $userrow["plusexp"] = "<span class=\"light\">(+".$userrow["expbonus"]."%)</span>"; 
    } elseif ($userrow["expbonus"] < 0) {
        $userrow["plusexp"] = "<span class=\"light\">(".$userrow["expbonus"]."%)</span>";
    } else { $userrow["plusexp"] = ""; }
    if ($userrow["goldbonus"] > 0) { 
        $userrow["plusgold"] = "<span class=\"light\">(+".$userrow["goldbonus"]."%)</span>"; 
    } elseif ($userrow["goldbonus"] < 0) { 
        $userrow["plusgold"] = "<span class=\"light\">(".$userrow["goldbonus"]."%)</span>";
    } else { $userrow["plusgold"] = ""; }
    
    $levelquery = doquery("SELECT ". $userrow["charclass"]."_exp FROM {{table}} WHERE id='".($userrow["level"]+1)."' LIMIT 1", "levels");
    $levelrow = mysql_fetch_array($levelquery);
    if ($userrow["level"] < 99) { $userrow["nextlevel"] = number_format($levelrow[$userrow["charclass"]."_exp"]); } else { $userrow["nextlevel"] = "<span class=\"light\">None</span>"; }

    if ($userrow["charclass"] == 1) { $userrow["charclass"] = $controlrow["class1name"]; }
    elseif ($userrow["charclass"] == 2) { $userrow["charclass"] = $controlrow["class2name"]; }
    elseif ($userrow["charclass"] == 3) { $userrow["charclass"] = $controlrow["class3name"]; }
    
    if ($userrow["difficulty"] == 1) { $userrow["difficulty"] = $controlrow["diff1name"]; }
    elseif ($userrow["difficulty"] == 2) { $userrow["difficulty"] = $controlrow["diff2name"]; }
    elseif ($userrow["difficulty"] == 3) { $userrow["difficulty"] = $controlrow["diff3name"]; }
    
    $spellquery = doquery("SELECT id,name FROM {{table}}","spells");
    $userspells = explode(",",$userrow["spells"]);
    $userrow["magiclist"] = "";
    while ($spellrow = mysql_fetch_array($spellquery)) {
        $spell = false;
        foreach($userspells as $a => $b) {
            if ($b == $spellrow["id"]) { $spell = true; }
        }
        if ($spell == true) {
            $userrow["magiclist"] .= $spellrow["name"]."<br />";
        }
    }
    if ($userrow["magiclist"] == "") { $userrow["magiclist"] = "None"; }
    
    // Make page tags for XHTML validation.
    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"
    . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\">\n"
    . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
    
    $charsheet = gettemplate("showchar");
    $page = $xml . gettemplate("minimal");
    $array = array("content"=>parsetemplate($charsheet, $userrow), "title"=>"Character Information");
    echo parsetemplate($page, $array);
    die();
    
}

function onlinechar($id) {
    
    global $controlrow;
    $userquery = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "users");
    if (mysql_num_rows($userquery) == 1) { $userrow = mysql_fetch_array($userquery); } else { display("No such user.", "Error"); }
    
    // Format various userrow stuffs.
    $userrow["experience"] = number_format($userrow["experience"]);
    $userrow["gold"] = number_format($userrow["gold"]);
    if ($userrow["expbonus"] > 0) { 
        $userrow["plusexp"] = "<span class=\"light\">(+".$userrow["expbonus"]."%)</span>"; 
    } elseif ($userrow["expbonus"] < 0) {
        $userrow["plusexp"] = "<span class=\"light\">(".$userrow["expbonus"]."%)</span>";
    } else { $userrow["plusexp"] = ""; }
    if ($userrow["goldbonus"] > 0) { 
        $userrow["plusgold"] = "<span class=\"light\">(+".$userrow["goldbonus"]."%)</span>"; 
    } elseif ($userrow["goldbonus"] < 0) { 
        $userrow["plusgold"] = "<span class=\"light\">(".$userrow["goldbonus"]."%)</span>";
    } else { $userrow["plusgold"] = ""; }
    
    $levelquery = doquery("SELECT ". $userrow["charclass"]."_exp FROM {{table}} WHERE id='".($userrow["level"]+1)."' LIMIT 1", "levels");
    $levelrow = mysql_fetch_array($levelquery);
    $userrow["nextlevel"] = number_format($levelrow[$userrow["charclass"]."_exp"]);

    if ($userrow["charclass"] == 1) { $userrow["charclass"] = $controlrow["class1name"]; }
    elseif ($userrow["charclass"] == 2) { $userrow["charclass"] = $controlrow["class2name"]; }
    elseif ($userrow["charclass"] == 3) { $userrow["charclass"] = $controlrow["class3name"]; }
    
    if ($userrow["difficulty"] == 1) { $userrow["difficulty"] = $controlrow["diff1name"]; }
    elseif ($userrow["difficulty"] == 2) { $userrow["difficulty"] = $controlrow["diff2name"]; }
    elseif ($userrow["difficulty"] == 3) { $userrow["difficulty"] = $controlrow["diff3name"]; }
    
    $charsheet = gettemplate("onlinechar");
    $page = parsetemplate($charsheet, $userrow);
    display($page, "Character Information");
    
}

function showmap() {
    
    global $userrow; 
    
    // Make page tags for XHTML validation.
    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"
    . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\">\n"
    . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
    
    $page = $xml . gettemplate("minimal");
    $array = array("content"=>"<center><img src=\"images/map.png\" alt=\"Mapa\" /></center>", "title"=>"Map");
    echo parsetemplate($page, $array);
    die();
    
}

function babblebox() {
    
    global $userrow;
    
    if (isset($_POST["babble"])) {
        $safecontent = makesafe($_POST["babble"]);
        if ($safecontent == "" || $safecontent == " ") { //blank post. do nothing.
        } else { $insert = doquery("INSERT INTO {{table}} SET id='',posttime=NOW(),author='".$userrow["charname"]."',babble='$safecontent'", "babble"); }
        header("Location: index.php?do=babblebox");
        die();
    }
    
    $babblebox = array("content"=>"");
    $bg = 1;
    $babblequery = doquery("SELECT * FROM {{table}} ORDER BY id DESC LIMIT 20", "babble");
    while ($babblerow = mysql_fetch_array($babblequery)) {
        if ($bg == 1) { $new = "<div style=\"width:98%; background-color:#eeeeee;\">[<b>".$babblerow["author"]."</b>] ".$babblerow["babble"]."</div>\n"; $bg = 2; }
        else { $new = "<div style=\"width:98%; background-color:#ffffff;\">[<b>".$babblerow["author"]."</b>] ".stripslashes($babblerow["babble"])."</div>\n"; $bg = 1; } 
        $babblebox["content"] = $new . $babblebox["content"];
    }
    $babblebox["content"] .= "<center><form action=\"index.php?do=babblebox\" method=\"post\"><input type=\"text\" name=\"babble\" size=\"15\" maxlength=\"120\" /><br /><input type=\"submit\" name=\"submit\" value=\"Babble\" /> <input type=\"reset\" name=\"reset\" value=\"Clear\" /></form></center>";
    
    // Make page tags for XHTML validation.
    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"
    . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\">\n"
    . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
    $page = $xml . gettemplate("babblebox");
    echo parsetemplate($page, $babblebox);
    die();

}

function ninja() {
    header("Location: http://www.se7enet.com/img/shirtninja.jpg");
}

?>