<?php
// index.php :: Primary program script, evil alien overlord, you decide.
//mostrar frase


$indexconteudo2 = $_GET['conteudo'];

$lat = $_GET['latitude']; $long = $_GET['longitude']; $re = $_GET['re'];


if ($indexconteudo2 != ""){
	$indexconteudo = "<div style=\"position:relative; z-index: 2\"><font color=brown><center>". strip_tags($indexconteudo2)."</font></center></div>";
	}
if (($re != "") && ($long != "") && ($lat != "")){
$htmlnapag = "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=index.php?do=andar&latitude=".$lat."&longitude=".$long."&conteudo=Seu personagem está indo até a coordenada.'>";}


global $valorlib, $link;
if ($valorlib == ""){//valor para nao redeclarar esses scripts.
include('lib.php');
include('cookies.php');
}
$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysqli_fetch_array($controlquery);


// Login (or verify) if not logged in.
global $userrow;
$userrow = checkcookies();
if ($userrow == false) {
    if (isset($_GET["do"])) {
        if ($_GET["do"] == "verify") { header("Location: users.php?do=verify"); die(); }
    }
    header("Location: login.php?do=login"); die();
}

//Verificando o auto andar.
if (($userrow["latitude"] == $lat) && ($userrow["longitude"] == $long) && ($indexconteudo == "") && ($re != ""))
{$htmlnapag = ""; $indexconteudo = "<center><font color=brown>Você chegou em seu destino.</font></center><br>";}

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
//N?o deixa fazer nada se estiver em um duelo.
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
	elseif ($do[0] == "andar") { include('explore.php'); andar($lat,$long); }

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
	elseif ($do[0] == "babbleboxpage") { babbleboxpage('460', '10'); }

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

    $townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
    if (mysqli_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); }
    $townrow = mysqli_fetch_array($townquery);

    // News box. Grab latest news entry and display it. Something a little more graceful coming soon maybe.
    if ($controlrow["shownews"] == 1) {
        $newsquery = doquery("SELECT * FROM {{table}} ORDER BY id DESC LIMIT 1", "news");
        $newsrow = mysqli_fetch_array($newsquery);
        $townrow["news"] = "<table width=\"95%\"><tr><td align=\"center\"><center><img src=\"images/ultimas.gif\"></center></td></tr><tr><td>\n";
        $townrow["news"] .= "<span class=\"light\">[".prettydate($newsrow["postdate"])."]</span><br />".nl2br($newsrow["content"]);
        $townrow["news"] .= "</td></tr></table>\n";
    } else { $townrow["news"] = ""; }

    // Who's Online. Currently just members. Guests maybe later.
    if ($controlrow["showonline"] == 1) {
        $onlinequery = doquery("SELECT * FROM {{table}} WHERE UNIX_TIMESTAMP(onlinetime) >= '".(time()-600)."' ORDER BY charname", "users");
        $townrow["whosonline"] = "<table width=\"95%\"><tr><td align=\"center\"><center><img src=\"images/online.gif\"></center></td></tr><tr><td>\n";
        $townrow["whosonline"] .= "Há <b>" . mysqli_num_rows($onlinequery) . "</b> usuários online nos últimos 10 minutos: ";
        while ($onlinerow = mysqli_fetch_array($onlinequery)) {
		$querycor = doquery("SELECT * FROM {{table}} WHERE charname='".$onlinerow["charname"]."' LIMIT 1","users");
		$usercor = mysqli_fetch_array($querycor);
		if ($usercor["authlevel"] == 1){ $link = " id=\"adm\" ";}
		elseif ($usercor["acesso"] == 2){ $link = " id=\"tutor\" ";}
		elseif ($usercor["acesso"] == 3){ $link = " id=\"gm\" ";}
		$townrow["whosonline"] .= "<a href=\"javascript: opcaochar('".$onlinerow["charname"]."')\" ".$link.">".$onlinerow["charname"]."</a>" . ", "; }
        $townrow["whosonline"] = rtrim($townrow["whosonline"], ", ");
        $townrow["whosonline"] .= "</td></tr></table>\n";
    } else { $townrow["whosonline"] = ""; }

    if ($controlrow["showbabble"] == 1) {
        $townrow["babblebox"] = "<table width=\"100%\"><tr><td align=\"center\"><center><img src=\"images/texto.gif\"></center></td></tr></table></center>";
        $townrow["babblebox"] .= babbleboxpage('460','10');
    } else { $townrow["babblebox"] = ""; }

	//mostrar valores de texto na p?gina.
	global $indexconteudo, $htmlnapag; //mostrar valores na index.
	$townrow["indexconteudo"] = $indexconteudo;
	$townrow["htmlnapag"] = $htmlnapag;

	 //outros / senjutsu, jutsu de busca, etc...
	 include('cidadesconteudo.php');
	 $townrow['fimconteudo'] = conteudo($townrow);


    $page = gettemplate("towns");
    $page = parsetemplate($page, $townrow);

    return $page;

}

function doexplore() { // Just spit out a blank exploring page.
	 global $userrow, $controlrow, $numqueries;



	//jogadores no mesmo mapa:
	$usuariosmapa = "<table width=\'100%\'>";
	$contagem = 0;
	$usersquery = doquery("SELECT * FROM {{table}} WHERE longitude='".$userrow["longitude"]."' AND latitude='".$userrow["latitude"]."' AND UNIX_TIMESTAMP(onlinetime) >= '".(time()-600)."' AND charname!='".$userrow["charname"]."' ORDER BY level DESC", "users");
	while ($usersrow = mysqli_fetch_array($usersquery)) {
		//fundo da tabela
		$fundo = $$contagem % 2;
		if ($fundo == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
		$contagem += 1;

		if ($usersrow["authlevel"] == 1){ $link = " id=\'adm\' "; $cor = "<font color=\"green\">"; $cor2 = "</font>";}
		elseif ($usersrow["acesso"] == 2){ $link = " id=\'tutor\' "; $cor = "<font color=\"orange\">"; $cor2 = "</font>";}
		elseif ($usersrow["acesso"] == 3){ $link = " id=\'gm\' "; $cor = "<font color=\"blue\">"; $cor2 = "</font>";}
		else{$link = ""; $cor = ""; $cor2 = "";}
		$usuariosmapa .= "<tr bgcolor=$bgcolor><td width=\'20\'><img src=\'images/vila_".$usersrow["alinhamento"].".gif\' title=\'Vila da ".ucfirst($usersrow["alinhamento"])."\' alt=\'X\'></td><td width=\'20\'><a href=\'troca.php?do=troca&jogador=".$usersrow["charname"]."\'><img src=\'images/troca_mini.gif\' border=\'0\' title=\'Realizar Troca\' alt=\'X\'></a></td><td width=\'*\'><a href=javascript:opcaochar(\'".$usersrow["charname"]."\') title=\'Level: ".$usersrow["level"]."\' $linka>".$usersrow["charname"]." </a></td></tr>";



}
$usuariosmapa .= "</table>";

if($usuariosmapa == "<table width=\'100%\'></table>") {$usuariosmapa = "<table width=\'100%\'><tr><td bgcolor=\'#E4D094\'>Nenhum</td></tr></table>";}

    // Exploring without a GET string is normally when they first log in, or when they've just finished fighting.
$nome = $userrow["avatar"]."_run.gif";
global $indexconteudo, $htmlnapag; //mostrar valores na index.

//Colocando o drop nas op??es de jogo do mapa.
if ($userrow["dropcode"] != 0){$drop = "<a href=\"index.php?do=drop\"><img src=\"images/24/exclamacao.gif\" title=\"Resgatar Drop\" alt=\"X\" border=\"0\"></a>";}

//Op??es novas do mapa.
global $opcoesnovas;
include('localpersonagens.php');

//Chat de cada mapa.
$chatquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow['latitude']."' AND longitude='".$userrow['longitude']."' ORDER by id", "chatmap");
$chat = "<br><br><br><center><table width=\"90%\"><tr bgcolor=\"#452202\"><td width=\"*\"><center><font color=\"white\">Chat do Mapa</font></center></td></tr></table>";
$chat .= "<table width=\"90%\" >";
$i = 0;
while ($chatrow = mysqli_fetch_array($chatquery)){
		$i += 1;
		//if (($chatrow['id'] < $menorid) || ($menorid == "")){$menorid = $chatrow['id'];}
		//fundo da tabela
		$fundo = $i % 2;
		if ($fundo == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
		$corquery = doquery("SELECT acesso, authlevel FROM {{table}} WHERE charname='".$chatrow['name']."' LIMIT 1", "users");
		$usercor = mysqli_fetch_array($corquery);
		$cor = ""; $cor2 = ""; $link = "";
		if ($usercor["authlevel"] == 1){ $link = " id=\"adm\" "; $cor = "<font color=\"green\">"; $cor2 = "</font>";}
		elseif ($usercor["acesso"] == 2){ $link = " id=\"tutor\" "; $cor = "<font color=\"orange\">"; $cor2 = "</font>";}
		elseif ($usercor["acesso"] == 3){ $link = " id=\"gm\" "; $cor = "<font color=\"blue\">"; $cor2 = "</font>";}
		$chat .= "<tr bgcolor=\"$bgcolor\" ><td style=\"border:1px #000000 solid\">[<b><a href=\"javascript: opcaochar('".$chatrow['name']."')\" $link>".$chatrow['name']."</a></b>] $cor".$chatrow['fala']."$cor2</td></tr>";


}
$chat .= "</table></center>";

$page = <<<END
<table width="100%">
<tr><td align="center"><center><img src="images/title_exploring.gif" alt="Explorando" /></center>
$indexconteudo
$htmlnapag
</td></tr>
<tr><td>

<div style="position: relative; top: -25px; margin-bottom: -30px;">
<table><tr><td width="310">
<br><br>
<table width="100%" bgcolor="#452202"><tr><td><font color="white">Opções do Mapa</font></td></tr>
<tr><td background="layoutnovo/menumeio/meio2.png">$drop<a href="encherhp.php"><img src="images/24/descansar.gif" border="0" title="Descansar" alt="X"></a>$opcoesnovas</td></tr>
</table>
<br>
<table width="100%" bgcolor="#452202"><tr><td width="*"><font color="white">Jogadores Online no Mesmo Mapa</font></td><td width="20"><a href="javascript: mostrarjogadores('$usuariosmapa');"><img src="images/setabaixo.gif" title="Mostrar Jogadores" alt="X" border="0"></a></td><td width="20"><a href="javascript: fecharjogadores();"><img src="images/setacima.gif" title="Fechar Jogadores" alt="X" border="0"></a></td></tr></table>
<div id="jogadoresmapa"></div>

</td><td valign="middle">

<div style="z-index: 1; position:relative">
<table width="165" height="175" background="layoutnovo/graficos/fundo2.png" style="background-repeat:no-repeat;;background-position:left top"><tr height="10%"><td></td></tr><tr ><td valign="bottom"><center><img src="layoutnovo/graficos/$nome"></center>
</td></tr><tr><td></td></tr></table></div>

</td></tr></table>

Você está explorando o mapa, nada aconteceu. Continue explorando usando os botões de direção ou o menu Viajar.
$chat
<center><form action="chat.php?do=chatmap" method="post"> <input type="text" name="fala" style="width:400px;" maxlength="200"><input type="submit" id="submit2" name="submit" value="OK" style="margin-left:2px;width:28px"></form></center>
</div></td></tr>
</table>
END;

    return $page;

}

function dofight() { // Redirect to fighting.

	 global $userrow, $controlrow, $numqueries, $indexconteudo;
	 $userrow["indexconteudo"] = $indexconteudo;

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
    $levelrow = mysqli_fetch_array($levelquery);
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
    while ($spellrow = mysqli_fetch_array($spellquery)) {
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
    if (mysqli_num_rows($userquery) == 1) { $userrow = mysqli_fetch_array($userquery); } else { echo "<font color=white>Nenhum usuário</font>."; }

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
    $levelrow = mysqli_fetch_array($levelquery);
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
    while ($spellrow = mysqli_fetch_array($spellquery)) {
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

	$maprespawn = $_GET['resp'];
    $maprespawn2 = explode(",",$maprespawn);
	$maprespawn3[1] = explode("/",$maprespawn2[0]);
	$maprespawn3[2] = explode("/",$maprespawn2[1]);

	$lugarazul = $_GET['lugarazul'];
	$lugararray = explode("/",$lugarazul);

	$respawnmonstro = $_GET['monstro'];

	//pesquisar respawn item.
	$item = $_GET['item'];
	if ($item != ""){
		$var = "_2"; //para trocar as imagens no mapa...
		$itemquery = doquery("SELECT * FROM {{table}} WHERE name='".$item."' LIMIT 1", "drops");
		if (mysqli_num_rows($itemquery) == 0) { die("Houve um erro ao pesquisar o item.");}
   		$droparray = mysqli_fetch_array($itemquery);
		$maprespawn3[1][0] = 5*$droparray["mlevel"] - 5;
		$maprespawn3[1][1] = -5*$droparray["mlevel"] + 5;
		$maprespawn3[2][0] = -5*$droparray["mlevel"] + 5;
		$maprespawn3[2][1] = 5*$droparray["mlevel"] - 5;
	}

	//pesquisar respawn monstro.
	elseif ($respawnmonstro != ""){
		$mquery = doquery("SELECT * FROM {{table}} WHERE name='".$respawnmonstro."' LIMIT 1", "monsters");
		if (mysqli_num_rows($mquery) == 0) { die("Houve um erro ao pesquisar o inimigo.");}
		$mrow = mysqli_fetch_array($mquery);
		if ($mrow['level'] == 1){$mrow['level'] = 2;}
		//7 porque estava havendo varia??o.
		$maprespawn3[1][0] = 5*$mrow['level'] - 7;
		$maprespawn3[1][1] = -5*$mrow["level"] + 7;
		$maprespawn3[2][0] = -5*$mrow["level"] + 7;
		$maprespawn3[2][1] = 5*$mrow["level"] - 7;

	}


    // Make page tags for XHTML validation.
    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"
    . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\">\n"
    . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";

	//tamanho do mapa
	$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
	$controlrow = mysqli_fetch_array($controlquery);
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

			//Respawn mapa item.
			if ((($maprespawn3[1][0] != "") && ((($maprespawn3[1][0] >= $auximostrar) || ($maprespawn3[1][0] >= ($aux[0] - $quantosquadrados - $addi)))  && (($maprespawn3[1][1] <= $auxjmostrar) || ($maprespawn3[1][1] <= ($aux[1] + $quantosquadrados + $addj)))))
			&&

			(($maprespawn3[2][0] <= ($aux[0] - $quantosquadrados - $addi)) || ($maprespawn3[2][0] <= $auximostrar)) && (($maprespawn3[2][1] >= ($aux[1] + $quantosquadrados + $addj)) || ($maprespawn3[2][1] >= $auxjmostrar))
			)
			{$lugararray[0] = $auximostrar; $lugararray[1] = $auxjmostrar;}
			//fim respawn mapa item

			//Respawn mapa monstro.
			$tamanho2 = $tamanhomapa/20 * 2;//Respawn de monstro abaixo, primeira op??o do if.
			if ((($respawnmonstro != "") && ((($maprespawn3[1][0] - $tamanho2 >= $auximostrar) || ($maprespawn3[1][0] - $tamanho2 >= ($aux[0] - $quantosquadrados - $addi)))  && (($maprespawn3[1][1] + $tamanho2 <= $auxjmostrar) || ($maprespawn3[1][1] + $tamanho2 <= ($aux[1] + $quantosquadrados + $addj)))))
			&&

			(($maprespawn3[2][0] + $tamanho2 <= ($aux[0] - $quantosquadrados - $addi)) || ($maprespawn3[2][0] + $tamanho2 <= $auximostrar)) && (($maprespawn3[2][1] - $tamanho2 >= ($aux[1] + $quantosquadrados + $addj)) || ($maprespawn3[2][1] - $tamanho2 >= $auxjmostrar))
			)
			{$lugararray[0] = 5000; $lugararray[1] = 5000;}
			//fim respawn mapa monstro

			if ((($lugararray[0] <= $auximostrar) && ($lugararray[0] >= ($aux[0] - $quantosquadrados - $addi)) && ($lugararray[1] >= $auxjmostrar) && ($lugararray[1] <= ($aux[1] + $quantosquadrados + $addj))) && (($userrow["latitude"] <= $auximostrar) && ($userrow["latitude"] >= ($aux[0] - $quantosquadrados - $addi)) && ($userrow["longitude"] >= $auxjmostrar) && ($userrow["longitude"] <= ($aux[1] + $quantosquadrados + $addj)))){
					if($respawnmonstro != ""){$fraseali = "Você pode encontrar o(a) ".$respawnmonstro." aqui, no seu quadrante.";}else{$fraseali = "O que/quem você procura está no seu quadrante.";}//respawn mapa
				$mostrar = "certoazulmeio19$var.gif";$title = "[".$auximostrar.",".$auxjmostrar."] até [".($aux[0] - $quantosquadrados - $addi).",".($aux[1] + $quantosquadrados + $addj)."] ".$fraseali;


			}elseif (($lugararray[0] <= $auximostrar) && ($lugararray[0] >= ($aux[0] - $quantosquadrados - $addi)) && ($lugararray[1] >= $auxjmostrar) && ($lugararray[1] <= ($aux[1] + $quantosquadrados + $addj))){
					if($respawnmonstro != ""){$fraseali = "Você pode encontrar o(a) ".$respawnmonstro." aqui.";}else{$fraseali = "O que/quem você procura está aqui.";}//respawn mapa
				$mostrar = "certoazul19$var.gif";$title = "[".$auximostrar.",".$auxjmostrar."] até [".($aux[0] - $quantosquadrados - $addi).",".($aux[1] + $quantosquadrados + $addj)."] ".$fraseali;


			}elseif (($userrow["latitude"] <= $auximostrar) && ($userrow["latitude"] >= ($aux[0] - $quantosquadrados - $addi)) && ($userrow["longitude"] >= $auxjmostrar) && ($userrow["longitude"] <= ($aux[1] + $quantosquadrados + $addj))){
				$mostrar = "certo19$var.gif";$title = "[".$auximostrar.",".$auxjmostrar."] até [".($aux[0] - $quantosquadrados - $addi).",".($aux[1] + $quantosquadrados + $addj)."] Você está aqui.";


			}else{$mostrar = "gif19$var.gif";$title = "[".$auximostrar.",".$auxjmostrar."] até [".($aux[0] - $quantosquadrados - $addi).",".($aux[1] + $quantosquadrados + $addj)."]";}

							//Come?o respawn mapa.
							if (($respawnmonstro != "") && ((($maprespawn3[1][0] - $tamanho2) >= $auximostrar) && ($maprespawn3[2][0] + $tamanho2 <= ($aux[0] - $quantosquadrados - $addi)) && ($maprespawn3[1][1] + $tamanho2 <= $auxjmostrar) && ($maprespawn3[2][1] - $tamanho2 >= ($aux[1] + $quantosquadrados + $addj)))){
								if ($mostrar != "certoazulmeio19$var.gif"){
								$mostrar = "gif19$var.gif";$title = "[".$auximostrar.",".$auxjmostrar."] até [".($aux[0] - $quantosquadrados - $addi).",".($aux[1] + $quantosquadrados + $addj)."]";
								}else{
									$mostrar = "certo19$var.gif";$title = "[".$auximostrar.",".$auxjmostrar."] até [".($aux[0] - $quantosquadrados - $addi).",".($aux[1] + $quantosquadrados + $addj)."] Você está aqui.";
								}
							}//Fim respawn mapa.

			//para mostrar respawn item
			if ($var == "_2"){ if($mostrar == "gif19$var.gif"){$titulonovo = " $item pode ser dropado(a) aqui.";}elseif($mostrar == "certo19$var.gif"){$titulonovo = " $item pode ser dropado(a) aqui e você está aqui.";}elseif($mostrar == "certoazul19$var.gif"){$titulonovo = "";}elseif($mostrar == "certoazulmeio19$var.gif"){$titulonovo = " Você está aqui.";}
				$titleexp = explode("]", $title); $title = $titleexp[0]."]".$titleexp[1]."]".$titulonovo;}
			//fim para mostrar respawn item
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
	
	//tamanho do campo da fala.
	$tamanho = $_GET['tamanho'];
	if ($tamanho >= 300){$tamanho = 350;}
	if ($tamanho == ""){$tamanho = 400;}
	if ($tamanho == 55){$tamanho = 350;}
    
    global $userrow;
    
    if (isset($_POST["babble"])) {
        $safecontent = makesafe($_POST["babble"]);
        if ($safecontent == "" || $safecontent == " ") { //blank post. do nothing.
        } else { $insert = doquery("INSERT INTO {{table}} SET id='',posttime=NOW(),author='".$userrow["charname"]."',babble='$safecontent'", "babble"); 
		}
		if ($tamanho != 15){$colocar = "&tamanho=55";}
        header("Location: index.php?do=babblebox$colocar");
        die();
    }

    
    $babblebox = array("content"=>"");
    $bg = 1;
    $babblequery = doquery("SELECT * FROM {{table}} ORDER BY id DESC LIMIT 120", "babble");
    while ($babblerow = mysqli_fetch_array($babblequery)) {
	//cor do nick
	$querycor = doquery("SELECT * FROM {{table}} WHERE charname='".$babblerow["author"]."' LIMIT 1", "users");
	$usercor = mysqli_fetch_array($querycor);
	if ($usercor["authlevel"] == 1){ $link = "<font color=darkgreen>";$link2 = "</font>"; $linkn = " id=\"adm\" ";}
	elseif ($usercor["acesso"] == 2){ $link = "<font color=darkorange>";$link2 = "</font>"; $linkn = " id=\"tutor\" ";}
	elseif ($usercor["acesso"] == 3){  $link = "<font color=darkblue>";$link2 = "</font>"; $linkn = " id=\"gm\" ";}
	else { $link = "<font color=black>";$link2 = "</font>"; $linkn = "";}

	
        if ($bg == 1) { $new = "<div style=\"width:98%; background-color:#eeeeee;\">[<a href=\"javascript: mostrarchar('".$babblerow["author"]."');\" $linkn title=\"Visualizar Perfil\">$link<b>".$babblerow["author"]."</b>$link2</a>] $link".$babblerow["babble"]."$link2</div>\n"; $bg = 2; }
        else { $new = "<div style=\"width:98%; background-color:#ffffff;\">[<a href=\"javascript: mostrarchar('".$babblerow["author"]."');\" $linkn title=\"Visualizar Perfil\">$link<b>".$babblerow["author"]."</b>$link2</a>] $link".stripslashes($babblerow["babble"])."$link2</div>\n"; $bg = 1; } 
        $babblebox["content"] = $new . $babblebox["content"];
    }
	if ($tamanho != 15){$colocar = "&tamanho=55";}
    $babblebox["content"] .= "<center><form action=\"index.php?do=babblebox&$colocar\" method=\"post\"><input type=\"text\" name=\"babble\" style=\"width:".$tamanho."px\" maxlength=\"120\" /><br /><div class=\"buttons\" style=\"padding-left: 9px;\"><center><button type=\"submit\" class=\"positive\" name=\"submit\"><img src=\"layoutnovo/dropmenu/b1.gif\"> Falar</button></form><button type=\"submit\" class=\"standard\" name=\"submit\"><img src=\"layoutnovo/dropmenu/b2.gif\"> Atualizar</button>
</center></div>";
	//if ($tamanho != 15){$babblebox["content"] .= "<form action=\"index.php?do=babblebox&tamanho=55\" method=\"post\"><div class=\"buttons\" style=\"padding-left: 9px;\"></div></form></center>";}else{$babblebox["content"] .= "</center>";}
    
    // Make page tags for XHTML validation.
    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"
    . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\">\n"
    . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
    $page = $xml . gettemplate("babblebox");
    echo parsetemplate($page, $babblebox);
    die();

}





function babbleboxpage($tamanho, $quantidade) {
	
	//tamanho do campo da fala.
	if ($tamanho == ""){$tamanho = 400;}
	if ($quantidade == ""){$quantidade = 10;}
	
    
    global $userrow;
    
    if (isset($_POST["babble"])) {
        $safecontent = makesafe($_POST["babble"]);
        if ($safecontent == "" || $safecontent == " ") { //blank post. do nothing.
        } else { $insert = doquery("INSERT INTO {{table}} SET id='',posttime=NOW(),author='".$userrow["charname"]."',babble='$safecontent'", "babble"); 
		}
        header('Location: index.php');
        die();
    }
	

    
    $babblebox = array("content"=>"");
    $bg = 1;
    $babblequery = doquery("SELECT * FROM {{table}} ORDER BY id DESC LIMIT $quantidade", "babble");
    while ($babblerow = mysqli_fetch_array($babblequery)) {
	//cor do nick
	$querycor = doquery("SELECT * FROM {{table}} WHERE charname='".$babblerow["author"]."' LIMIT 1", "users");
	$usercor = mysqli_fetch_array($querycor);
	if ($usercor["authlevel"] == 1){ $link = "<font color=darkgreen>";$link2 = "</font>"; $linkn = " id=\"adm\" ";}
	elseif ($usercor["acesso"] == 2){ $link = "<font color=darkorange>";$link2 = "</font>"; $linkn = " id=\"tutor\" ";}
	elseif ($usercor["acesso"] == 3){  $link = "<font color=darkblue>";$link2 = "</font>"; $linkn = " id=\"gm\" ";}
	else { $link = "<font color=black>";$link2 = "</font>"; $linkn = "";}

	
        if ($bg == 1) { $new = "<div style=\"width:98%; background-color:#eeeeee;\" id=\"divchat\">[<a href=\"javascript: mostrarchar('".$babblerow["author"]."');\" $linkn title=\"Visualizar Perfil\">$link<b>".$babblerow["author"]."</b>$link2</a>] $link".$babblerow["babble"]."$link2</div>\n"; $bg = 2; }
        else { $new = "<div style=\"width:98%; background-color:#ffffff;\" id=\"divchat\">[<a href=\"javascript: mostrarchar('".$babblerow["author"]."');\" $linkn title=\"Visualizar Perfil\">$link<b>".$babblerow["author"]."</b>$link2</a>] $link".stripslashes($babblerow["babble"])."$link2</div>\n"; $bg = 1; } 
        $babblebox["content"] = $new . $babblebox["content"];
		
    }
    $babblebox["content"] .= "<center><form action=\"index.php?do=babbleboxpage\" method=\"post\"><input type=\"text\" name=\"babble\" style=\"width:".$tamanho."px\" maxlength=\"120\" /><br /><button type=\"submit\" id=\"uffuff\" value=\"\"></form><script type=\"text/javascript\" language=\"JavaScript\">sumirbotao('uffuff');sumirbotao('uffuff');</script>
</center>";


return $babblebox["content"];

}


?>