<?php // fight.php :: Handles all fighting action.

function fight() { // One big long function that determines the outcome of the fight.
    
		
	
	
    global $userrow, $controlrow;
    if ($userrow["currentaction"] != "Fighting") { display("Tentativa de trapaça detectada.", "Error"); }
    $pagearray = array();
    $playerisdead = 0;
	
	//Graficos
		$pagearray["grafico"] = $userrow["avatar"]."_stance.gif";
    
    $pagearray["magiclist"] = "";
    $userspells = explode(",",$userrow["spells"]);
    $spellquery = 
	doquery("SELECT id,name,mp FROM {{table}}", "spells");
    while ($spellrow = mysql_fetch_array($spellquery)) {
        $spell = false;
        foreach ($userspells as $a => $b) {
            if ($b == $spellrow["id"]) { $spell = true; }
        }
        if ($spell == true) {
		$chakra = $spellrow["mp"];
		           $pagearray["magiclist"] .= "<option value=\"".$spellrow["id"]."\">".$spellrow["name"]." _ CH: $chakra</option>\n";
        }
        unset($spell);
    }
    if ($pagearray["magiclist"] == "") { $pagearray["magiclist"] = "<option value=\"0\">None</option>\n"; }
    $magiclist = $pagearray["magiclist"];
    
    $chancetoswingfirst = 1;

    // First, check to see if we need to pick a monster.
    if ($userrow["currentfight"] == 1) {
        
        if ($userrow["latitude"] < 0) { $userrow["latitude"] *= -1; } // Equalize negatives.
        if ($userrow["longitude"] < 0) { $userrow["longitude"] *= -1; } // Ditto.
        $maxlevel = floor(max($userrow["latitude"]+5, $userrow["longitude"]+5) / 5); // One mlevel per five spaces.
        if ($maxlevel < 1) { $maxlevel = 1; }
        $minlevel = $maxlevel - 2;
        if ($minlevel < 1) { $minlevel = 1; }
        
        
        // Pick a monster.
        $monsterquery = doquery("SELECT * FROM {{table}} WHERE level>='$minlevel' AND level<='$maxlevel' ORDER BY RAND() LIMIT 1", "monsters");
        $monsterrow = mysql_fetch_array($monsterquery);
        $userrow["currentmonster"] = $monsterrow["id"];
        $userrow["currentmonsterhp"] = rand((($monsterrow["maxhp"]/5)*4),$monsterrow["maxhp"]);
        if ($userrow["difficulty"] == 2) { $userrow["currentmonsterhp"] = ceil($userrow["currentmonsterhp"] * $controlrow["diff2mod"]); }
        if ($userrow["difficulty"] == 3) { $userrow["currentmonsterhp"] = ceil($userrow["currentmonsterhp"] * $controlrow["diff3mod"]); }
        $userrow["currentmonstersleep"] = 0;
        $userrow["currentmonsterimmune"] = $monsterrow["immune"];
        
        $chancetoswingfirst = rand(1,10) + ceil(sqrt($userrow["dexterity"]));
        if ($chancetoswingfirst > (rand(1,7) + ceil(sqrt($monsterrow["maxdam"])))) { $chancetoswingfirst = 1; } else { $chancetoswingfirst = 0; }
        
        unset($monsterquery);
        unset($monsterrow);
        
    }
    
    // Next, get the monster statistics.
    $monsterquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["currentmonster"]."' LIMIT 1", "monsters");
    $monsterrow = mysql_fetch_array($monsterquery);
    $pagearray["monstername"] = $monsterrow["name"];
    
    // Do run stuff.
    if (isset($_POST["run"])) {

        $chancetorun = rand(4,10) + ceil(sqrt($userrow["dexterity"]));
        if ($chancetorun > (rand(1,5) + ceil(sqrt($monsterrow["maxdam"])))) { $chancetorun = 1; } else { $chancetorun = 0; }
        
        if ($chancetorun == 0) { 
            $pagearray["yourturn"] = "Você tentou fugir mas foi bloqueado pela frente!<br /><br />";
            $pagearray["monsterhp"] = "HP do Inimigo: " . $userrow["currentmonsterhp"] . "<br /><br />";
            $pagearray["monsterturn"] = "";
			
            if ($userrow["currentmonstersleep"] != 0) { // Check to wake up.
                $chancetowake = rand(1,15);
				//atributo inteligencia
				$chancetowake = $chancetowake + floor($userrow["inteligencia"]/100);
                if ($chancetowake > $userrow["currentmonstersleep"]) {
                    $userrow["currentmonstersleep"] = 0;
                    $pagearray["monsterturn"] .= "O inimigo acordou.<br />";
                } else {
                    $pagearray["monsterturn"] .= "O inimigo continua dormindo.<br />";
                }
            }
            if ($userrow["currentmonstersleep"] == 0) { // Only do this if the monster is awake.
                $tohit = ceil(rand($monsterrow["maxdam"]*.5,$monsterrow["maxdam"]));
                if ($userrow["difficulty"] == 2) { $tohit = ceil($tohit * $controlrow["diff2mod"]); }
                if ($userrow["difficulty"] == 3) { $tohit = ceil($tohit * $controlrow["diff3mod"]); }
                $toblock = ceil(rand($userrow["defensepower"]*.75,$userrow["defensepower"])/4);
                $tododge = rand(1,150);
				//atributo agilidade antes: $tododge <= sqrt($userrow["dexterity"])
			$tododge = $tododge - floor($userrow["agilidade"]*2/100);
                if ($tododge <= sqrt($userrow["dexterity"])) {
                    $tohit = 0; $pagearray["monsterturn"] .= "Você fugiu de um ataque. Nenhum dano foi recebido.<br />";
                    $persondamage = 0;
                } else {
                    $persondamage = $tohit - $toblock;
                    if ($persondamage < 1) { $persondamage = 1; }
                    if ($userrow["currentuberdefense"] != 0) {
                        $persondamage -= ceil($persondamage * ($userrow["currentuberdefense"]/100));
                    }
                    if ($persondamage < 1) { $persondamage = 1; }
                }
                $pagearray["monsterturn"] .= "O inimigo te atacou provocando $persondamage de dano.<br /><br />";
                $userrow["currenthp"] -= $persondamage;
                if ($userrow["currenthp"] <= 0) {
                    $newgold = ceil($userrow["gold"]/2);
                    $newhp = ceil($userrow["maxhp"]/4);
                    $updatequery = doquery("UPDATE {{table}} SET currenthp='$newhp',currentaction='In Town',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentfight='0',latitude='0',longitude='0',gold='$newgold' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
                    $playerisdead = 1;
                }
            }
        }

        $updatequery = doquery("UPDATE {{table}} SET currentaction='Exploring' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
        header("Location: index.php");
        die();
		
			
        
    // Do fight stuff.
    } elseif (isset($_POST["fight"])) {
        
		//Graficos
		$pagearray["grafico"] = $userrow["avatar"]."_ataque.gif";
		
        // Your turn.
        $pagearray["yourturn"] = "";
        $tohit = ceil(rand($userrow["attackpower"]*.75,$userrow["attackpower"])/3);
        $toexcellent = rand(1,150);
		
		//atributo determinacao // antes $toexcellent <= sqrt($userrow["strength"])
		$determinacao = sqrt($userrow["strength"]) + ($userrow["determinacao"]*2/100);
        if ($toexcellent <= $determinacao) { $tohit *= 2; $pagearray["yourturn"] .= "Hit excelente!<br />"; }
        $toblock = ceil(rand($monsterrow["armor"]*.75,$monsterrow["armor"])/3);        
        $tododge = rand(1,200);
		//atributo precisao //  antes $tododge <= sqrt($monsterrow["armor"])
		$tododge = $tododge + floor($userrow["precisao"]*3/100);
        if ($tododge <= sqrt($monsterrow["armor"])) { 
            $tohit = 0; $pagearray["yourturn"] .= "O inimigo está fugindo. Nenhum dano foi recebido por ele.<br />"; 
            $monsterdamage = 0;
        } else {
            $monsterdamage = $tohit - $toblock;
            if ($monsterdamage < 1) { $monsterdamage = 1; }
            if ($userrow["currentuberdamage"] != 0) {
                $monsterdamage += ceil($monsterdamage * ($userrow["currentuberdamage"]/100));
            }
        }
        $pagearray["yourturn"] .= "Você atacou o inimigo provocando $monsterdamage de dano.<br /><br />";
        $userrow["currentmonsterhp"] -= $monsterdamage;
        $pagearray["monsterhp"] = "HP do Inimigo: " . $userrow["currentmonsterhp"] . "<br /><br />";
        if ($userrow["currentmonsterhp"] <= 0) {
            $updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
            header("Location: index.php?do=victory");
            die();
        }
        
        // Monster's turn.
        $pagearray["monsterturn"] = "";
        if ($userrow["currentmonstersleep"] != 0) { // Check to wake up.
            $chancetowake = rand(1,15);
			//atributo inteligencia
				$chancetowake = $chancetowake + floor($userrow["inteligencia"]/100);
            if ($chancetowake > $userrow["currentmonstersleep"]) {
                $userrow["currentmonstersleep"] = 0;
                $pagearray["monsterturn"] .= "O inimigo acordou.<br />";
            } else {
                $pagearray["monsterturn"] .= "O inimigo continua a dormir.<br />";
            }
        }
        if ($userrow["currentmonstersleep"] == 0) { // Only do this if the monster is awake.
            $tohit = ceil(rand($monsterrow["maxdam"]*.5,$monsterrow["maxdam"]));
            if ($userrow["difficulty"] == 2) { $tohit = ceil($tohit * $controlrow["diff2mod"]); }
            if ($userrow["difficulty"] == 3) { $tohit = ceil($tohit * $controlrow["diff3mod"]); }
            $toblock = ceil(rand($userrow["defensepower"]*.75,$userrow["defensepower"])/4);
            $tododge = rand(1,150);
			//atributo agilidade antes: $tododge <= sqrt($userrow["dexterity"])
			$tododge = $tododge - floor($userrow["agilidade"]*2/100);
            if ($tododge <= sqrt($userrow["dexterity"])) {
                $tohit = 0; $pagearray["monsterturn"] .= "Você fugiu do ataque do inimigo. Nenhum dano foi causado.<br />";
                $persondamage = 0;
            } else {
                $persondamage = $tohit - $toblock;
                if ($persondamage < 1) { $persondamage = 1; }
                if ($userrow["currentuberdefense"] != 0) {
                    $persondamage -= ceil($persondamage * ($userrow["currentuberdefense"]/100));
                }
                if ($persondamage < 1) { $persondamage = 1; }
            }
            $pagearray["monsterturn"] .= "O inimigo atacou você, provocando $persondamage de dano.<br /><br />";
            $userrow["currenthp"] -= $persondamage;
            if ($userrow["currenthp"] <= 0) {
                $newgold = ceil($userrow["gold"]/2);
                $newhp = ceil($userrow["maxhp"]/4);
                $updatequery = doquery("UPDATE {{table}} SET currenthp='$newhp',currentaction='In Town',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentfight='0',latitude='0',longitude='0',gold='$newgold' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
                $playerisdead = 1;
            }
        }
        
    // Do spell stuff.
    } elseif (isset($_POST["spell"])) {
	
	//Graficos
		$pagearray["grafico"] = $userrow["avatar"]."_jutsu.gif";
		
		// Your turn.
        $pickedspell = $_POST["userspell"];
        if ($pickedspell == 0) { display("Você deve selecionar um Jutsu primeiro. Por favor volte e tente novamente.", "Error"); die(); }
        
        $newspellquery = doquery("SELECT * FROM {{table}} WHERE id='$pickedspell' LIMIT 1", "spells");
        $newspellrow = mysql_fetch_array($newspellquery);
        $spell = false;
        foreach($userspells as $a => $b) {
            if ($b == $pickedspell) { $spell = true; }
        }
        if ($spell != true) { display("Você ainda não aprendeu esse Jutsu. Por favor volte e tente novamente.", "Error"); die(); }
        if ($userrow["currentmp"] < $newspellrow["mp"]) { display("Você não tem Chakra suficiente para usar esse Jutsu. Por favor volte e tente novamente.", "Error"); die(); }
        
        if ($newspellrow["type"] == 1) { // Heal spell.
            $newhp = $userrow["currenthp"] + $newspellrow["attribute"];
            if ($userrow["maxhp"] < $newhp) { $newspellrow["attribute"] = $userrow["maxhp"] - $userrow["currenthp"]; $newhp = $userrow["currenthp"] + $newspellrow["attribute"]; }
            $userrow["currenthp"] = $newhp;
            $userrow["currentmp"] -= $newspellrow["mp"];
            $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"]." e ganhou ".$newspellrow["attribute"]." Pontos de Vida.<br /><br />";
        } elseif ($newspellrow["type"] == 2) { // Hurt spell.
            if ($userrow["currentmonsterimmune"] == 0) {
                $monsterdamage = rand((($newspellrow["attribute"]/6)*5), $newspellrow["attribute"]);
                $userrow["currentmonsterhp"] -= $monsterdamage;
                $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"]." e causou $monsterdamage de dano.<br /><br />";
            } else {
                $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"].", mas o inimigo é imune à seu Jutsu.<br /><br />";
            }
            $userrow["currentmp"] -= $newspellrow["mp"];
        } elseif ($newspellrow["type"] == 3) { // Sleep spell.
            if ($userrow["currentmonsterimmune"] != 2) {
                $userrow["currentmonstersleep"] = $newspellrow["attribute"];
                $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"].". O inimigo está dormindo.<br /><br />";
            } else {
                $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"].", mas o inimigo é imune à ele.<br /><br />";
            }
            $userrow["currentmp"] -= $newspellrow["mp"];
        } elseif ($newspellrow["type"] == 4) { // +Damage spell.
            $userrow["currentuberdamage"] = $newspellrow["attribute"];
            $userrow["currentmp"] -= $newspellrow["mp"];
            $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"]." e ganhou ".$newspellrow["attribute"]."% de dano até o fim da batalha.<br /><br />";
        } elseif ($newspellrow["type"] == 5) { // +Defense spell.
            $userrow["currentuberdefense"] = $newspellrow["attribute"];
            $userrow["currentmp"] -= $newspellrow["mp"];
            $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"]." e ganhou ".$newspellrow["attribute"]."% de defesa até o fim da batalha.<br /><br />";            
        }
            
        $pagearray["monsterhp"] = "HP do Inimigo: " . $userrow["currentmonsterhp"] . "<br /><br />";
        if ($userrow["currentmonsterhp"] <= 0) {
            $updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='0',currenthp='".$userrow["currenthp"]."',currentmp='".$userrow["currentmp"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
            header("Location: index.php?do=victory");
            die();
        }
        
        // Monster's turn.
        $pagearray["monsterturn"] = "";
        if ($userrow["currentmonstersleep"] != 0) { // Check to wake up.
            $chancetowake = rand(1,15);
			//atributo inteligencia
				$chancetowake = $chancetowake + floor($userrow["inteligencia"]/100);
            if ($chancetowake > $userrow["currentmonstersleep"]) {
                $userrow["currentmonstersleep"] = 0;
                $pagearray["monsterturn"] .= "O inimigo acordou.<br />";
            } else {
                $pagearray["monsterturn"] .= "O inimigo ainda está dormindo.<br />";
            }
        }
        if ($userrow["currentmonstersleep"] == 0) { // Only do this if the monster is awake.
            $tohit = ceil(rand($monsterrow["maxdam"]*.5,$monsterrow["maxdam"]));
            if ($userrow["difficulty"] == 2) { $tohit = ceil($tohit * $controlrow["diff2mod"]); }
            if ($userrow["difficulty"] == 3) { $tohit = ceil($tohit * $controlrow["diff3mod"]); }
            $toblock = ceil(rand($userrow["defensepower"]*.75,$userrow["defensepower"])/4);
            $tododge = rand(1,150);
			//atributo agilidade antes: $tododge <= sqrt($userrow["dexterity"])
			$tododge = $tododge - floor($userrow["agilidade"]*2/100);
            if ($tododge <= sqrt($userrow["dexterity"])) {
                $tohit = 0; $pagearray["monsterturn"] .= "Você fugiu do ataque inimigo. Nenhum dano foi causado.<br />";
                $persondamage = 0;
            } else {
                if ($tohit <= $toblock) { $tohit = $toblock + 1; }
                $persondamage = $tohit - $toblock;
                if ($userrow["currentuberdefense"] != 0) {
                    $persondamage -= ceil($persondamage * ($userrow["currentuberdefense"]/100));
                }
                if ($persondamage < 1) { $persondamage = 1; }
            }
            $pagearray["monsterturn"] .= "O inimigo te atacou, causando $persondamage de dano.<br /><br />";
            $userrow["currenthp"] -= $persondamage;
            if ($userrow["currenthp"] <= 0) {
                $newgold = ceil($userrow["gold"]/2);
                $newhp = ceil($userrow["maxhp"]/4);
                $updatequery = doquery("UPDATE {{table}} SET currenthp='$newhp',currentaction='In Town',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentfight='0',latitude='0',longitude='0',gold='$newgold' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
                $playerisdead = 1;
            }
        }
    
    // Do a monster's turn if person lost the chance to swing first. Serves him right!
    } elseif ( $chancetoswingfirst == 0 ) {
        $pagearray["yourturn"] = "O inimigo te atacou antes que estivesse preparado!<br /><br />";
        $pagearray["monsterhp"] = "HP do Inimigo: " . $userrow["currentmonsterhp"] . "<br /><br />";
        $pagearray["monsterturn"] = "";
        if ($userrow["currentmonstersleep"] != 0) { // Check to wake up.
            $chancetowake = rand(1,15);
			//atributo inteligencia
				$chancetowake = $chancetowake + floor($userrow["inteligencia"]/100);
            if ($chancetowake > $userrow["currentmonstersleep"]) {
                $userrow["currentmonstersleep"] = 0;
                $pagearray["monsterturn"] .= "O inimigo acordou.<br />";
            } else {
                $pagearray["monsterturn"] .= "O inimigo ainda está dormindo.<br />";
            }
        }
        if ($userrow["currentmonstersleep"] == 0) { // Only do this if the monster is awake.
            $tohit = ceil(rand($monsterrow["maxdam"]*.5,$monsterrow["maxdam"]));
            if ($userrow["difficulty"] == 2) { $tohit = ceil($tohit * $controlrow["diff2mod"]); }
            if ($userrow["difficulty"] == 3) { $tohit = ceil($tohit * $controlrow["diff3mod"]); }
            $toblock = ceil(rand($userrow["defensepower"]*.75,$userrow["defensepower"])/4);
            $tododge = rand(1,150);
			//atributo agilidade antes: $tododge <= sqrt($userrow["dexterity"])
			$tododge = $tododge - floor($userrow["agilidade"]*2/100);
            if ($tododge <= sqrt($userrow["dexterity"])) {
                $tohit = 0; $pagearray["monsterturn"] .= "Você fugiu do ataque inimigo. Nenhum dano foi causado.<br />";
                $persondamage = 0;
            } else {
                $persondamage = $tohit - $toblock;
                if ($persondamage < 1) { $persondamage = 1; }
                if ($userrow["currentuberdefense"] != 0) {
                    $persondamage -= ceil($persondamage * ($userrow["currentuberdefense"]/100));
                }
                if ($persondamage < 1) { $persondamage = 1; }
            }
            $pagearray["monsterturn"] .= "O inimigo te atacou, causando $persondamage de dano.<br /><br />";
            $userrow["currenthp"] -= $persondamage;
            if ($userrow["currenthp"] <= 0) {
                $newgold = ceil($userrow["gold"]/2);
                $newhp = ceil($userrow["maxhp"]/4);
                $updatequery = doquery("UPDATE {{table}} SET currenthp='$newhp',currentaction='In Town',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentfight='0',latitude='0',longitude='0',gold='$newgold' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
                $playerisdead = 1;
            }
        }

    } else {
        $pagearray["yourturn"] = "";
        $pagearray["monsterhp"] = "HP do Inimigo: " . $userrow["currentmonsterhp"] . "<br /><br />";
        $pagearray["monsterturn"] = "";
    }
    
    $newmonster = $userrow["currentmonster"];

    $newmonsterhp = $userrow["currentmonsterhp"];
    $newmonstersleep = $userrow["currentmonstersleep"];
    $newmonsterimmune = $userrow["currentmonsterimmune"];
    $newuberdamage = $userrow["currentuberdamage"];
    $newuberdefense = $userrow["currentuberdefense"];
    $newfight = $userrow["currentfight"] + 1;
    $newhp = $userrow["currenthp"];
    $newmp = $userrow["currentmp"];    
    
if ($playerisdead != 1) { 
$pagearray["command"] = <<<END
Comando?<br /><br />
<form action="index.php?do=fight" method="post">
<input type="submit" name="fight" value="Atacar" /><br /><br />
<select name="userspell"><option value="0">Escolha um Jutsu</option>$magiclist</select> <input type="submit" name="spell" value="Usar" /><br /><br />
<input type="submit" name="run" value="Correr" /><br /><br />
</form>
END;
    $updatequery = doquery("UPDATE {{table}} SET currentaction='Fighting',currenthp='$newhp',currentmp='$newmp',currentfight='$newfight',currentmonster='$newmonster',currentmonsterhp='$newmonsterhp',currentmonstersleep='$newmonstersleep',currentmonsterimmune='$newmonsterimmune',currentuberdamage='$newuberdamage',currentuberdefense='$newuberdefense' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
} else {
    
	
	//Graficos
   $pagearray["grafico"] = $userrow["avatar"]."_morto.gif";
   $pagearray["porcent"] = "50%";
	
    $pagearray["command"] = "<b>Você morreu.</b><br /><br />Como consequencia, você perdeu metade de seus Ryou. De qualquer forma, lhe foi dado metade dos seus Pontos de Vida para continuar sua jornada.<br /><br />Você pode voltar para a <a href=\"index.php\">cidade</a>, e esperamos que se sinta melhor da próxima vez.";
}
    
    // Finalize page and display it.
	if ($pagearray["porcent"] == "") {$pagearray["porcent"] = "30%";}
	$template = gettemplate("fight");
    $page = parsetemplate($template,$pagearray);
    
    display($page, "Lutando");
    
}

function victory() {
    
    global $userrow, $controlrow;
    
    if ($userrow["currentmonsterhp"] != 0) { header("Location: index.php?do=fight"); die(); }
    if ($userrow["currentfight"] == 0) { header("Location: index.php"); die(); }
    
    $monsterquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["currentmonster"]."' LIMIT 1", "monsters");
    $monsterrow = mysql_fetch_array($monsterquery);
    
    $exp = rand((($monsterrow["maxexp"]/6)*5),$monsterrow["maxexp"]);
    if ($exp < 1) { $exp = 1; }
    if ($userrow["difficulty"] == 2) { $exp = ceil($exp * $controlrow["diff2mod"]); }
    if ($userrow["difficulty"] == 3) { $exp = ceil($exp * $controlrow["diff3mod"]); }
    if ($userrow["expbonus"] != 0) { $exp += ceil(($userrow["expbonus"]/100)*$exp); }
    $gold = rand((($monsterrow["maxgold"]/6)*5),$monsterrow["maxgold"]);
    if ($gold < 1) { $gold = 1; }
    if ($userrow["difficulty"] == 2) { $gold = ceil($gold * $controlrow["diff2mod"]); }
    if ($userrow["difficulty"] == 3) { $gold = ceil($gold * $controlrow["diff3mod"]); }
    if ($userrow["goldbonus"] != 0) { $gold += ceil(($userrow["goldbonus"]/100)*$exp); }
    if ($userrow["experience"] + $exp < 16777215) { $newexp = $userrow["experience"] + $exp; $warnexp = ""; } else { $newexp = $userrow["experience"]; $exp = 0; $warnexp = "Você aumentou seus pontos de experiência."; }
    if ($userrow["gold"] + $gold < 16777215) { $newgold = $userrow["gold"] + $gold; $warngold = ""; } else { $newgold = $userrow["gold"]; $gold = 0; $warngold = "Você aumentou seus Ryou."; }
    
    $levelquery = doquery("SELECT * FROM {{table}} WHERE id='".($userrow["level"]+1)."' LIMIT 1", "levels");
    if (mysql_num_rows($levelquery) == 1) { $levelrow = mysql_fetch_array($levelquery); }
    
    if ($userrow["level"] < 100) {
        if ($newexp >= $levelrow[$userrow["charclass"]."_exp"]) {
            $newhp = $userrow["maxhp"] + $levelrow[$userrow["charclass"]."_hp"];
            $newmp = $userrow["maxmp"] + $levelrow[$userrow["charclass"]."_mp"];
            $newtp = $userrow["maxtp"] + $levelrow[$userrow["charclass"]."_tp"];
            $newstrength = $userrow["strength"] + $levelrow[$userrow["charclass"]."_strength"];
            $newdexterity = $userrow["dexterity"] + $levelrow[$userrow["charclass"]."_dexterity"];
            $newattack = $userrow["attackpower"] + $levelrow[$userrow["charclass"]."_strength"];
            $newdefense = $userrow["defensepower"] + $levelrow[$userrow["charclass"]."_dexterity"];
            $newlevel = $levelrow["id"];
			$novospontosamostrar = ceil(($levelrow[$userrow["charclass"]."_strength"] + $levelrow[$userrow["charclass"]."_dexterity"])/2);
			$novospontosdedistrubuicao = $userrow["pontoatributos"] + $novospontosamostrar;
            
            if ($levelrow[$userrow["charclass"]."_spells"] != 0) {
                $userspells = $userrow["spells"] . ",".$levelrow[$userrow["charclass"]."_spells"];
                $newspell = "spells='$userspells',";
                $spelltext = "Você aprendeu um novo Jutsu.<br />";
            } else { $spelltext = ""; $newspell=""; }
            
			
			$updatequery = doquery("UPDATE {{table}} SET pontoatributos='$novospontosdedistrubuicao' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
			
			//Graficos
            $page = "
			<table width=\"100%\">
<tr><td align=\"center\"><center><img src=\"images/title_fighting.gif\" alt=\"Fighting\" /></center></td></tr></table>
			
			<table><tr><td width=\"310\" valign=\"middle\"><center>
			<br><br>Parabéns. Você derrotou ".$monsterrow["name"].".<br />E ganhou $exp de experiência. $warnexp <br />Ganhou $gold de Ryou. $warngold <br /><br /><b>Você passou de nível!</b><br /><br />Você ganhou ".$levelrow[$userrow["charclass"]."_hp"]." Pontos de Vida.<br />Você ganhou ".$levelrow[$userrow["charclass"]."_mp"]." Pontos de Chakra.<br />Você ganhou ".$levelrow[$userrow["charclass"]."_tp"]." Pontos de Viagem.<br />Você ganhou $novospontosamostrar Pontos de Distribuição.<br />Você ganhou ".$levelrow[$userrow["charclass"]."_strength"]." de ataque.<br />Você ganhou ".$levelrow[$userrow["charclass"]."_dexterity"]." de defesa.<br />$spelltext<br />Você pode continuar <a href=\"index.php\">explorando</a>.</center>
			
			</td><td>
			
			
<table width=\"165\" height=\"175\" background=\"layoutnovo/graficos/fundo.png\" style=\"background-repeat:no-repeat;;background-position:left top\"><tr height=\"30%\"><td></td></tr><tr><td><center><img src=\"layoutnovo/graficos/".$userrow["avatar"]."_ganhou.gif\"></center>
</td></tr><tr  height=\"15\"><td></td></tr></table>


</td></tr></table>
			";
            $title = "O sábio te serviu bem!";
            $dropcode = "";
			
			
			
        } else {
            $newhp = $userrow["maxhp"];
            $newmp = $userrow["maxmp"];
            $newtp = $userrow["maxtp"];
            $newstrength = $userrow["strength"];
            $newdexterity = $userrow["dexterity"];
            $newattack = $userrow["attackpower"];
            $newdefense = $userrow["defensepower"];
            $newlevel = $userrow["level"];
            $newspell = "";
			
					
			//Graficos
			$page = "
			<table width=\"100%\">
<tr><td align=\"center\"><center><img src=\"images/title_fighting.gif\" alt=\"Fighting\" /></center></td></tr></table>
			
			<table><tr><td width=\"310\" valign=\"middle\"><center>
			
			<br><br>Parabéns. Você derrotou ".$monsterrow["name"].".<br />Você ganhou $exp de experiência. $warnexp <br />Você ganhou $gold Ryou. $warngold <br /><br /></center>
			
			</td><td>
			
			
<table width=\"165\" height=\"175\" background=\"layoutnovo/graficos/fundo.png\" style=\"background-repeat:no-repeat;;background-position:left top\"><tr height=\"30%\"><td></td></tr><tr><td><center><img src=\"layoutnovo/graficos/".$userrow["avatar"]."_ganhou.gif\"></center>
</td></tr><tr  height=\"15\"><td></td></tr></table>


</td></tr></table>
			";
            
			//colocando probabilidade de sorte // antes tava rand(1,30) == 1
			$sorte = floor($userrow["sorte"]*2/100 + 1);
            if (rand(1,20) <= $sorte) {
                $dropquery = doquery("SELECT * FROM {{table}} WHERE mlevel <= '".$monsterrow["level"]."' ORDER BY RAND() LIMIT 1", "drops");
                $droprow = mysql_fetch_array($dropquery);
                $dropcode = "dropcode='".$droprow["id"]."',";
                $page .= "<center>Esse inimigo dropou um item. <a href=\"index.php?do=drop\">Clique aqui</a> para equipar o item, ou você pode continuar <a href=\"index.php\">explorando</a> e ignorar o item.</center>";
            } else { 
                $dropcode = "";
				//alterado
                $page .= "<center>Você pode continuar <a href=\"index.php\">explorando</a>.</center>";
            }

            $title = "Vitória!";
        }
    }

    $updatequery = doquery("UPDATE {{table}} SET currentaction='Exploring',level='$newlevel',maxhp='$newhp',maxmp='$newmp',maxtp='$newtp',strength='$newstrength',dexterity='$newdexterity',attackpower='$newattack',defensepower='$newdefense', $newspell currentfight='0',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentuberdamage='0',currentuberdefense='0',$dropcode experience='$newexp',gold='$newgold' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
    

    display($page, $title);
    
}

function drop() {
    
    global $userrow;
    
    if ($userrow["dropcode"] == 0) { header("Location: index.php"); die(); }
    
    $dropquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["dropcode"]."' LIMIT 1", "drops");
    $droprow = mysql_fetch_array($dropquery);
    
    if (isset($_POST["submit"])) {
        
        $slot = $_POST["slot"];
        
        if ($slot == 0) { display("Por favor volte e selecione um slot do inventáriao para continuar.","Error"); }
        
        if ($userrow["slot".$slot."id"] != 0) {
            
            $slotquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["slot".$slot."id"]."' LIMIT 1", "drops");
            $slotrow = mysql_fetch_array($slotquery);
            
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
            
            $newname = addslashes($droprow["name"]);
            $query = doquery("UPDATE {{table}} SET slot".$_POST["slot"]."name='$newname',slot".$_POST["slot"]."id='".$droprow["id"]."',$old1[0]='".$userrow[$old1[0]]."',$old2[0]='".$userrow[$old2[0]]."',$new1[0]='".$userrow[$new1[0]]."',$new2[0]='".$userrow[$new2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."',currenthp='".$userrow["currenthp"]."',currentmp='".$userrow["currentmp"]."',currenttp='".$userrow["currenttp"]."',dropcode='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
            
        } else {
            
            $new1 = explode(",",$droprow["attribute1"]);
            if ($droprow["attribute2"] != "X") { $new2 = explode(",",$droprow["attribute2"]); } else { $new2 = array(0=>"maxhp",1=>0); }
            
            $userrow[$new1[0]] += $new1[1];
            $userrow[$new2[0]] += $new2[1];
            if ($new1[0] == "strength") { $userrow["attackpower"] += $new1[1]; }
            if ($new1[0] == "dexterity") { $userrow["defensepower"] += $new1[1]; }
            if ($new2[0] == "strength") { $userrow["attackpower"] += $new2[1]; }
            if ($new2[0] == "dexterity") { $userrow["defensepower"] += $new2[1]; }
            
            $newname = addslashes($droprow["name"]);
            $query = doquery("UPDATE {{table}} SET slot".$_POST["slot"]."name='$newname',slot".$_POST["slot"]."id='".$droprow["id"]."',$new1[0]='".$userrow[$new1[0]]."',$new2[0]='".$userrow[$new2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."',dropcode='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
            
        }
        $page = "O item foi equipado. Você pode continuar <a href=\"index.php\">explorando</a>.";
        display($page, "Item Drop");
        
    }
    
    $attributearray = array("maxhp"=>"Max HP",
                            "maxmp"=>"Max CH",
                            "maxtp"=>"Max TP",
                            "defensepower"=>"Poder de Defesa",
                            "attackpower"=>"Poder de Ataque",
                            "strength"=>"Pontos de Ataque",
                            "dexterity"=>"Pontos de Defesa",
                            "expbonus"=>"Bônus de Experiência",
                            "goldbonus"=>"Bônus de Ryou");
    
    $page = "O inimigo dropou o seguinte item: <b>".$droprow["name"]."</b><br /><br />";
    $page .= "Esse item tem o(s) seguinte(s) atributo(s):<br />";
    
    $attribute1 = explode(",",$droprow["attribute1"]);
    $page .= $attributearray[$attribute1[0]];
    if ($attribute1[1] > 0) { $page .= " +" . $attribute1[1] . "<br />"; } else { $page .= $attribute1[1] . "<br />"; }
    
    if ($droprow["attribute2"] != "X") { 
        $attribute2 = explode(",",$droprow["attribute2"]);
        $page .= $attributearray[$attribute2[0]];
        if ($attribute2[1] > 0) { $page .= " +" . $attribute2[1] . "<br />"; } else { $page .= $attribute2[1] . "<br />"; }
    }
    
    $page .= "<br />Selecione um slot do inventário da lista abaixo para equipar o item. Se o slot estiver cheio, o Item antigo será descartado.";
    $page .= "<form action=\"index.php?do=drop\" method=\"post\"><select name=\"slot\"><option value=\"0\">Escolha:</option><option value=\"1\">Slot 1: ".$userrow["slot1name"]."</option><option value=\"2\">Slot 2: ".$userrow["slot2name"]."</option><option value=\"3\">Slot 3: ".$userrow["slot3name"]."</option></select> <input type=\"submit\" name=\"submit\" value=\"Submit\" /></form>";
    $page .= "Você pode também continuar <a href=\"index.php\">explorando</a> e descartar esse item.";
    
    display($page, "Item Drop");
    
}
    

function dead() {
    
    $page = "<b>Você morreu.</b><br /><br />Como consequência, você perdeu metade do seu Ryou. De qualque forma, lhe foi dado metade dos seus Pontos de Vida para continuar sua jornada.<br /><br />Você pode voltar para a <a href=\"index.php\">cidade</a>, e esperamos que se sinta melhor da próxima vez.";
        
}



?>