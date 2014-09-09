<?php // index.php :: Primary program script, evil alien overlord, you decide.
//mostrar frase
$indexconteudo2 = $_GET['conteudo'];
if ($indexconteudo2 != ""){$indexconteudo = "<font color=brown><center>".$indexconteudo2."</font></center>";}

if ($valorlib == ""){//valor para nao redeclarar esses scripts.
include('lib.php');
include('cookies.php');
}
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
if ($controlrow["gameopen"] == 0) { 
if ($userrow["authlevel"] != 1){
display("Foi encontrado um bug no jogo. O mesmo estará fechado até o lançamento da próxima versão. Por favor volte mais tarde e desculpe o transtorno.","Fechado"); die(); 
}
}
// Force verify if the user isn't verified yet.
if ($controlrow["verifyemail"] == 1 && $userrow["verify"] != 1) { header("Location: users.php?do=verify"); die(); }
// Block user if he/she has been banned.
if ($userrow["authlevel"] == 2) { die("Sua conta foi bloqueada. Por favor tenta novamente mais tarde."); }
//Não deixa fazer nada se estiver em um duelo.
if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

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
    elseif ($do[0] == "move") { include('explore.php'); move();}
	elseif ($do[0] == "andar") { $lat = $_GET['latitude']; $long = $_GET['longitude'];include('explore.php'); andar($lat,$long); }
    
    // Fighting functions.
    elseif ($do[0] == "fight") { include('fight.php'); fight(); }
    elseif ($do[0] == "victory") { include('fight.php'); victory(); }
    elseif ($do[0] == "drop") { include('fight.php'); drop(); }
    elseif ($do[0] == "dead") { include('fight.php'); dead(); }
	
	   // Fighting functions.
    elseif ($do[0] == "duelo") { include('duelo.php'); duelo(); }
      
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
        $title = "Na Cidade";
		    } elseif ($userrow["currentaction"] == "Exploring") {
        $page = doexplore();
        $title = "Explorando";
		    } elseif ($userrow["currentaction"] == "Fighting")  {
        $page = dofight();
        $title = "Lutando";
		    }
    
    display($page, $title);
    
}

function dotown() { // Spit out the main town page.
    
    global $userrow, $controlrow, $numqueries;
	
	//imagem da cidade, etc
		    global $imagemtv;//imagem da tv...
	if ($imagemtv == ""){
		$updatequery = doquery("UPDATE {{table}} SET imagem='nacidade.png' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");}else{
	$updatequery = doquery("UPDATE {{table}} SET imagem='$imagemtv' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");}
	
	
	
    $townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
    if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); }
    $townrow = mysql_fetch_array($townquery);
    
    // News box. Grab latest news entry and display it. Something a little more graceful coming soon maybe.
    if ($controlrow["shownews"] == 1) { 
        $newsquery = doquery("SELECT * FROM {{table}} ORDER BY id DESC LIMIT 1", "news");
        $newsrow = mysql_fetch_array($newsquery);
        $townrow["news"] = "<table width=\"95%\"><tr><td align=\"center\"><center><img src=\"images/ultimas.gif\"></center></td></tr><tr><td>\n";
        $townrow["news"] .= "<span class=\"light\">[".prettydate($newsrow["postdate"])."]</span><br />".nl2br($newsrow["content"]);
        $townrow["news"] .= "</td></tr></table>\n";
    } else { $townrow["news"] = ""; }
    
    // Who's Online. Currently just members. Guests maybe later.
    if ($controlrow["showonline"] == 1) {
        $onlinequery = doquery("SELECT * FROM {{table}} WHERE UNIX_TIMESTAMP(onlinetime) >= '".(time()-600)."' ORDER BY charname", "users");
        $townrow["whosonline"] = "<table width=\"95%\"><tr><td align=\"center\"><center><img src=\"images/online.gif\"></center></td></tr><tr><td>\n";
        $townrow["whosonline"] .= "Há <b>" . mysql_num_rows($onlinequery) . "</b> usuários online nos últimos 10 minutos: ";
        while ($onlinerow = mysql_fetch_array($onlinequery)) { 
		$querycor = doquery("SELECT * FROM {{table}} WHERE charname='".$onlinerow["charname"]."' LIMIT 1","users");
		$usercor = mysql_fetch_array($querycor);
		if ($usercor["authlevel"] == 1){ $link = " id=\"adm\" ";}
		elseif ($usercor["acesso"] == 2){ $link = " id=\"tutor\" ";}
		elseif ($usercor["acesso"] == 3){ $link = " id=\"gm\" ";}
		$townrow["whosonline"] .= "<a href=\"javascript: mostrarchar('".$onlinerow["charname"]."');\" ".$link.">".$onlinerow["charname"]."</a>" . ", "; }
        $townrow["whosonline"] = rtrim($townrow["whosonline"], ", ");
        $townrow["whosonline"] .= "</td></tr></table>\n";
    } else { $townrow["whosonline"] = ""; }
    
    if ($controlrow["showbabble"] == 1) {
        $townrow["babblebox"] = "<table width=\"95%\"><tr><td align=\"center\"><img src=\"images/texto.gif\"></td></tr><tr><td>\n";
        $townrow["babblebox"] .= "<iframe src=\"index.php?do=babblebox\" name=\"sbox\" width=\"100%\" height=\"250\" frameborder=\"0\" id=\"bbox\">Your browser does not support inline frames! The Babble Box will not be available until you upgrade to a newer <a href=\"http://www.mozilla.org\" target=\"_new\">browser</a>.</iframe>";
        $townrow["babblebox"] .= "</td></tr></table>\n";
    } else { $townrow["babblebox"] = ""; }
	
	//imagem do hokage
	global $indexconteudo; //mostrar valores na index.
	$townrow["indexconteudo"] = $indexconteudo;
 if($townrow["id"] == 1){$townrow["kage"] = "Hokage";}
 if($townrow["id"] == 2){$townrow["kage"] = "Fukasaku & Shima";}
 if($townrow["id"] == 3){$townrow["kage"] = "Tsuchikage";}
 if($townrow["id"] == 4){$townrow["kage"] = "Mizukage";}
 if($townrow["id"] == 5){$townrow["kage"] = "Kazekage";}
 if($townrow["id"] == 6){$townrow["kage"] = "Raikage";}
 if($townrow["id"] == 7){$townrow["kage"] = "Shodaime";}
 if($townrow["id"] == 8){$townrow["kage"] = "Tobi";}
 //outros / senjutsu, jutsu de busca, etc...
  $townrow["outros"] = "";
   if($townrow["id"] == 5){$townrow["outros"] = "<li /><a href=\"jutsudebusca.php?do=jutsu\">Treinar o Jutsu de Busca</a>";}
   if($townrow["id"] == 2){$townrow["outros"] = "<li /><a href=\"senjutsu.php?do=jutsu\">Treinar Senjutsu</a><li /><a href=\"senjutsu.php?do=chamar\">Chamar Fukasaku & Shima Para o Grupo</a>";}
  

  
    $page = gettemplate("towns");
    $page = parsetemplate($page, $townrow);
    
    return $page;
    
}

function doexplore() { // Just spit out a blank exploring page.
    global $imagemtv;//imagem da tv...
	 global $userrow, $controlrow, $numqueries;
	
	//imagem da cidade, etc
	if ($imagemtv == ""){
	$updatequery = doquery("UPDATE {{table}} SET imagem='explorando.png' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");}else{
	$updatequery = doquery("UPDATE {{table}} SET imagem='$imagemtv' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");}
	
	
	//jogadores no mesmo mapa:
	$usersquery = doquery("SELECT * FROM {{table}} WHERE longitude='".$userrow["longitude"]."' AND latitude='".$userrow["latitude"]."' AND UNIX_TIMESTAMP(onlinetime) >= '".(time()-600)."' AND charname!='".$userrow["charname"]."' ORDER BY level DESC", "users");
	while ($usersrow = mysql_fetch_array($usersquery)) {
     
       
    $usuariosmapa = $usuariosmapa."<li /><a href=\"jogadormesmomapa.php?nomedochar=".$usersrow["charname"]."\">".$usersrow["charname"]."</a>";
	

}
if($usuariosmapa == "") {$usuariosmapa = "Nenhum.";}
	
    // Exploring without a GET string is normally when they first log in, or when they've just finished fighting.
$nome = $userrow["avatar"]."_run.gif";
global $indexconteudo; //mostrar valores na index.




$page = <<<END
<table width="100%">
<tr><td align="center"><center><img src="images/title_exploring.gif" alt="Explorando" /></center>
$indexconteudo
</td></tr>
<tr><td>


<table><tr><td width="310" valign="center">
<b>Opções do Mapa</b>:<br>
<ul>
<li /><a href="encherhp.php">Sentar e descansar</a>
</ul><br>
<b>Jogadores Online no Mesmo Mapa</b>:<br>
<ul>
$usuariosmapa
</ul><br>
</td><td>

<table width="165" height="175" background="layoutnovo/graficos/fundo2.png" style="background-repeat:no-repeat;;background-position:left top"><tr height="10%"><td></td></tr><tr ><td valign="bottom"><center><img src="layoutnovo/graficos/$nome"></center>
</td></tr><tr><td></td></tr></table>

</td></tr></table>

Você está explorando o mapa, nada aconteceu. Continue explorando usando os botões de direção ou o menu Viajar Para.

</td></tr>
</table>
END;

    return $page;
        
}

function dofight() { // Redirect to fighting.
    
	 global $userrow, $controlrow, $numqueries, $indexconteudo;
	 $userrow["indexconteudo"] = $indexconteudo;
	
	//imagem da cidade, etc
	    global $imagemtv;//imagem da tv...

		$updatequery = doquery("UPDATE {{table}} SET imagem='batalhando.png' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	
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
	
			//durabilidade
	$durabm = explode(",",$userrow["durabilidade"]);
	for ($i = 1; $i < 7; $i ++){
	if ($durabm[$i] == "X"){$durabm[$i] = "*";}
	$userrow[durabm.$i] = $durabm[$i];
	}
	
    
    // Make page tags for XHTML validation.
    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"
    . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\">\n"
    . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
	
    $embaixo = "<center><font color=\"white\">Link do Personagem:</font><br><input type=\"text\" size=\"20\" value=\"http://nigeru.com/narutorpg/mostrarchar.php?nomechar=".$userrow["charname"]."\"></center>";
    $charsheet = gettemplate("showchar");
    $page = $xml . gettemplate("minimal").$embaixo;
    $array = array("content"=>parsetemplate($charsheet, $userrow), "title"=>"Informação do Personagem");
    echo parsetemplate($page, $array);
    die();
    
}

function onlinechar($id) {
    global $topvar;
$topvar = true;
	
    global $controlrow;
    $userquery = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "users");
    if (mysql_num_rows($userquery) == 1) { $userrow = mysql_fetch_array($userquery); } else { echo "<font color=white>Nenhum usuário</font>."; }
    
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
    
	//sefor administrador
	if ($userrow["authlevel"] == 1) {$userrow["adm"] = "<font color=green>Administrador</font><br><br>";}
	elseif ($userrow["acesso"] == 2){$userrow["adm"] = "<font color=orange>Tutor</font><br><br>";}
	elseif ($userrow["acesso"] == 3){$userrow["adm"] = "<font color=blue>GameMaster</font><br><br>";}
	else {$userrow["adm"] = "";}
	
		//durabilidade
	$durabm = explode(",",$userrow["durabilidade"]);
	for ($i = 1; $i < 7; $i ++){
	if ($durabm[$i] == "X"){$durabm[$i] = "*";}
	$userrow[durabm.$i] = $durabm[$i];
	}
	
	
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
	
	if ($userrow["senjutsuhtml"] != ""){ $userrow["magiclist"] = "<font color=darkgreen>Senjutsu</font><br>".$userrow["magiclist"];}
	if ($userrow["jutsudebuscahtml"] != ""){ $userrow["magiclist"] = "<font color=darkgreen>Jutsu de Busca</font><br>".$userrow["magiclist"];}
	
	    // Make page tags for XHTML validation.
    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"
    . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\">\n"
    . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
	

	
	    
    $charsheet = gettemplate("onlinechar");
    $page = $xml . gettemplate("minimal");
    $array = array("content"=>parsetemplate($charsheet, $userrow), "title"=>"Informação do Personagem");
    echo parsetemplate($page, $array);
    die();
    
}

function showmap() {
    
    global $userrow; 
    
    // Make page tags for XHTML validation.
    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"
    . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\">\n"
    . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
	
	//tamanho do mapa
	$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
	$controlrow = mysql_fetch_array($controlquery);
	$tamanhomapa = $controlrow["gamesize"];
    
    $page = $xml . gettemplate("minimal");
	$pagina = "<center><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" background=\"images/mapateste.jpg\">";
	$quantosquadrados = floor($tamanhomapa*2/20);
	$quadrantesfaltando = $tamanhomapa*2 - ($quantosquadrados*20); $clonefaltando = $quadrantesfaltando;
	$coordinicio = $tamanhomapa.",".($tamanhomapa*-1);
	$inicioi = $tamanhomapa;
	$inicioj = $tamanhomapa*-1;
	$aux = explode(",",$coordinicio);
	for ($i = 0; $i <= 19; $i ++){
		$pagina .= "<tr>";
		if ($quadrantesfaltando > 0){$addi = 1; $quadrantesfaltando -= 1;}else{$addi = 0;}
		$aux[0] = $inicioi - ($quantosquadrados * $i) - $addfimi;
		if ($i != 0){$auximostrar = $aux[0] - 1;}else{$auximostrar = $aux[0];}
		for ($j = 0; $j <= 19; $j ++){
			if ($clonefaltando > 0){$addj = 1; $clonefaltando -= 1;}else{$addj = 0;}
			$aux[1] = $inicioj + ($quantosquadrados * $j) + $addfimj;
			if ($j != 0){$auxjmostrar = $aux[1] + 1;}else{$auxjmostrar = $aux[1];}
			if (($userrow["latitude"] <= $auximostrar) && ($userrow["latitude"] >= ($aux[0] - $quantosquadrados - $addi)) && ($userrow["longitude"] >= $auxjmostrar) && ($userrow["longitude"] <= ($aux[1] + $quantosquadrados + $addj))){
				$mostrar = "certo19.gif";$title = "[".$auximostrar.",".$auxjmostrar."] até [".($aux[0] - $quantosquadrados - $addi).",".($aux[1] + $quantosquadrados + $addj)."] Você está aqui.";
			}else{$mostrar = "gif19.gif";$title = "[".$auximostrar.",".$auxjmostrar."] até [".($aux[0] - $quantosquadrados - $addi).",".($aux[1] + $quantosquadrados + $addj)."]";}
			$pagina .= "<td style=\"border:1px #000000 solid\"><img src=\"images/$mostrar\" title=\"$title\" border=\"0\"></td>";

			$addfimj += $addj;
		}
		$pagina .= "</tr>";

		$addfimi += $addi;
	}
	$pagina .= "</table>";
    $array = array("content"=>$pagina, "title"=>"Mapa Global - 20x20");
    echo parsetemplate($page, $array);
    die();
    
}

function babblebox() {
    
    global $userrow;
    
    if (isset($_POST["babble"])) {
        $safecontent = makesafe($_POST["babble"]);
        if ($safecontent == "" || $safecontent == " ") { //blank post. do nothing.
        } else { $insert = doquery("INSERT INTO {{table}} SET id='',posttime=NOW(),author='".$userrow["charname"]."',babble='$safecontent'", "babble"); 
		}
        header("Location: index.php?do=babblebox");
        die();
    }
	

    
    $babblebox = array("content"=>"");
    $bg = 1;
    $babblequery = doquery("SELECT * FROM {{table}} ORDER BY id DESC LIMIT 100", "babble");
    while ($babblerow = mysql_fetch_array($babblequery)) {
	//cor do nick
	$querycor = doquery("SELECT * FROM {{table}} WHERE charname='".$babblerow["author"]."' LIMIT 1", "users");
	$usercor = mysql_fetch_array($querycor);
	if ($usercor["authlevel"] == 1){ $link = "<font color=darkgreen>";$link2 = "</font>";}
	elseif ($usercor["acesso"] == 2){ $link = "<font color=darkorange>";$link2 = "</font>";}
	elseif ($usercor["acesso"] == 3){  $link = "<font color=darkblue>";$link2 = "</font>";}
	else { $link = "<font color=black>";$link2 = "</font>";}
	
        if ($bg == 1) { $new = "<div style=\"width:98%; background-color:#eeeeee;\">[$link<b>".$babblerow["author"]."</b>$link2] $link".$babblerow["babble"]."$link2</div>\n"; $bg = 2; }
        else { $new = "<div style=\"width:98%; background-color:#ffffff;\">[$link<b>".$babblerow["author"]."</b>$link2] $link".stripslashes($babblerow["babble"])."$link2</div>\n"; $bg = 1; } 
        $babblebox["content"] = $new . $babblebox["content"];
    }
    $babblebox["content"] .= "<center><form action=\"index.php?do=babblebox\" method=\"post\"><input type=\"text\" name=\"babble\" size=\"15\" maxlength=\"120\" /><br /><input type=\"submit\" name=\"submit\" value=\"Falar\" /> <input type=\"reset\" name=\"reset\" value=\"Apagar\" /></form></center>";
    
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