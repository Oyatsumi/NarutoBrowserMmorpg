<?php 

include('lib.php');

$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysqli_fetch_array($controlquery);



$nomechar = $_GET['nomechar'];






    $userquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomechar' LIMIT 1", "users");
    if (mysqli_num_rows($userquery) == 1) { $userrow = mysqli_fetch_array($userquery); } else { echo "Nenhum usuário."; die();}
    
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
	if ($userrow["authlevel"] == 1) {$userrow["adm"] = "<font color=green>Administrador</font><br>";}
	elseif ($userrow["acesso"] == 2){$userrow["adm"] = "<font color=orange>Tutor</font><br>";}
	elseif ($userrow["acesso"] == 3){$userrow["adm"] = "<font color=blue>GameMaster</font><br>";}
	else {$userrow["adm"] = "";}
	
		//durabilidade
	$durabm = explode(",",$userrow["durabilidade"]);
	for ($i = 1; $i < 7; $i ++){
	if ($durabm[$i] == "X"){$durabm[$i] = "*";}
	$userrow["durabm".$i] = $durabm[$i];
	}
	
	//Stat dos equipamentos
		//atributo dos itens
	include('funcoesinclusas.php');
	$userrow["armaatr"] = conteudoexplic($userrow["weaponid"], '1', 'armaatr', $durabm[1]);	
	$userrow["shieldatr"] = conteudoexplic($userrow["shieldid"], '3', 'shieldatr', $durabm[3]);	
	$userrow["armoratr"] = conteudoexplic($userrow["armorid"], '2', 'armoratr', $durabm[2]);	
	$userrow["slot1atr"] = conteudoexplic($userrow["slot1id"], '4', 'slot1atr', $durabm[4]);
	$userrow["slot2atr"] = conteudoexplic($userrow["slot2id"], '4', 'slot2atr', $durabm[5]);
	$userrow["slot3atr"] = conteudoexplic($userrow["slot3id"], '4', 'slot3atr', $durabm[6]);
	
	
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
	
	if ($userrow["jutsudebuscahtml"] == 1){ $userrow["magiclist"] = "<font color=darkgreen>Jutsu de Busca</font><br>".$userrow["magiclist"];}
	if ($userrow["senjutsuhtml"] != ""){ $userrow["magiclist"] = "<font color=darkred>Senjutsu</font><br>".$userrow["magiclist"];}

	

	
	    // Make page tags for XHTML validation.
    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"
    . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"DTD/xhtml1-transitional.dtd\">\n"
    . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";


	
	$embaixo = "<center><font color=\"white\">Link do Personagem:</font><br><input type=\"text\" size=\"20\" value=\"http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/mostrarchar.php?nomechar=".$userrow["charname"]."\"></center>";
    $charsheet = gettemplate("onlinechar");
    $page = $xml . gettemplate("minimal").$embaixo;
    $array = array("content"=>parsetemplate($charsheet, $userrow), "title"=>"Informação do Personagem");
    echo parsetemplate($page, $array);
    die();
	
    



?>