<?php // fight.php :: Handles all fighting action.

function fight() { // One big long function that determines the outcome of the fight.
    
	$avisochakra = $_GET['avisochakra'];
	
	
	
    global $userrow, $controlrow;
    if ($userrow["currentaction"] != "Fighting") { display("Tentativa de trapaça detectada.<br>", "Error"); }
    $pagearray = array();
    $playerisdead = 0;
	
	//Graficos
		$pagearray["grafico"] = $userrow["avatar"]."_stance.gif";
		 //frase da batalha
   global $indexconteudo;
   $pagearray["indexconteudo"] = $indexconteudo;
    
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
        $aux1 = $userrow["latitude"];
		$aux2 = $userrow["longitude"];
        if ($aux1 < 0) { $aux1 *= -1; } // Equalize negatives. Mudei pro mapa não inverter...
        if ($aux2 < 0) { $aux2 *= -1; } // Ditto.
        $maxlevel = floor(max($aux1+5, $aux2+5) / 5); // One mlevel per five spaces.
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
	
	//Dados do monstro
	if ($monsterrow["immune"] == 0) { $immune = "<span class=\"light\">None</span>"; } elseif ($monsterrow["immune"] == 1) { $immune = "Dano"; } else { $immune = "Dano&Sono"; }
	$pagearray["dados"] = "<div style=\"height: 145px; width: 295px; background-image: url('layoutnovo/monstros/".$monsterrow['id'].".jpg')\"><div style=\"width:285px;font-size:15px;padding-left:11px;padding-top:10px;color:#e4d094;font-family:impact\">".$monsterrow['name']."</div><div style=\"position:relative;top:-19px;font-size:15px;padding-left:10px;color:#442800;text-shadow: 0 0 5px #ffffcc;font-family:impact\">".$monsterrow['name']."</div>
	<div style=\"position:relative;padding-left:78px;top:-11px\"><table bgcolor=\"#452202\" style=\"width:211px;height:99px;\"><tr bgcolor=\"#e0d4af\"><td ><center>Level</center></td><td ><center>Imunidade</center></td><td ><center>Elemento</center></td>
	
	</tr><tr bgcolor=\"#fff1c7\"><td ><center>".$monsterrow["level"]."</center></td><td ><center>$immune</center></td><td ><center><img src=\"images/".$monsterrow["elemento"].".gif\" title=\"Elemento ".ucfirst($monsterrow["elemento"])."\"></center></td>
	
	
	</tr><tr bgcolor=\"#e0d4af\" style=\"height:2px;\"><td ><center><font color=\"darkred\">HP</font></center></td><td ><center>Ataque</center></td><td ><center>Defesa</center></td></tr>
	
	<tr bgcolor=\"#fff1c7\"><td ><center><div id=\"danoalt\">".$userrow["currentmonsterhp"]."</div></center></td><td ><center>".$monsterrow["maxdam"]."</center></td><td ><center>".$monsterrow["armor"]."</center></td></tr>
	
	
</table></div></div><br>";

    
    // Do run stuff.
    if (isset($_POST["run"])) {

        $chancetorun = rand(4,10) + ceil(sqrt($userrow["dexterity"]));
        if ($chancetorun > (rand(1,5) + ceil(sqrt($monsterrow["maxdam"])))) { $chancetorun = 1; } else { $chancetorun = 0; }
        
        if ($chancetorun == 0) { 
            $pagearray["yourturn"] = "Você tentou fugir mas foi bloqueado pelo inimigo!<br /><br />";
            $pagearray["monsterhp"] = "<script language=\"javascript\">document.getElementById('danoalt').innerHTML = '".$userrow["currentmonsterhp"]."';</script>";
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
                $pagearray["monsterturn"] .= "O inimigo provocou no total, $persondamage de dano.<br /><br />";
                $userrow["currenthp"] -= $persondamage;
                if ($userrow["currenthp"] <= 0) {
                    $newgold = ceil($userrow["gold"]/2);
                    $newhp = ceil($userrow["maxhp"]/4);
					//morrer alinhamento
					if ($userrow["alinhamento"] == "folha"){$idcid = 1;}
					elseif ($userrow["alinhamento"] == "pedra"){$idcid = 3;}
					elseif ($userrow["alinhamento"] == "nevoa"){$idcid = 4;}
					elseif ($userrow["alinhamento"] == "areia"){$idcid = 5;}
					elseif ($userrow["alinhamento"] == "nuvem"){$idcid = 6;}
					$townqueryali = doquery("SELECT * FROM {{table}} WHERE id='$idcid' LIMIT 1", "towns");
		if (mysql_num_rows($townquery) == 0) { display("Erro 101: Houve um problema com o seu alinhamento.","Error"); }
					$townrowali = mysql_fetch_array($townqueryali);
					//fim morrer alinhamento
                    $updatequery = doquery("UPDATE {{table}} SET currenthp='$newhp',currentaction='In Town',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentfight='0',latitude='".$townrowali["latitude"]."',longitude='".$townrowali["longitude"]."',gold='$newgold' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
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
				include('funcoesinclusas.php'); //Colocando o dano de elemento.
				$query2 = doquery("SELECT elemento FROM {{table}} WHERE id='".$userrow['currentmonster']."' LIMIT 1", "monsters");
				$monsterrow2 = mysql_fetch_array($query2);
				$multiplicacaopontos = 0;
				$monsterdamagemult = elementoganhador($userrow['elementoatk'], $monsterrow2['elemento'], $multiplicacaopontos);
				if ($userrow['currentep'] == 0){$monsterdamagemult = 1;}
				if ($monsterdamagemult == 3/2){$cont = " Seu ataque físico foi super efetivo.";}
				elseif ($monsterdamagemult == 1/2){$cont = " Seu ataque físico foi pouco efetivo.";}else{$cont = "";}
				//Fim de colocando o dano de elemento.
				$monsterdamage += ceil($monsterdamage * ($userrow["currentuberdamage"]/100));
				$monsterdamage *= $monsterdamagemult;
				$monsterdamage = $monsterdamage + ceil($monsterdamage * $multiplicacaopontos/100);
            }
        }
        $pagearray["yourturn"] .= "Você atacou o inimigo provocando $monsterdamage de dano.$cont<br /><br />";
        $userrow["currentmonsterhp"] -= $monsterdamage;
        $pagearray["monsterhp"] = "<script language=\"javascript\">document.getElementById('danoalt').innerHTML = '".$userrow["currentmonsterhp"]."';</script>";
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
                $tohit = 0; $pagearray["monsterturn"] .= "Você fugiu do ataque inimigo. ";
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
				//morrer alinhamento
				if ($userrow["alinhamento"] == "folha"){$idcid = 1;}
				elseif ($userrow["alinhamento"] == "pedra"){$idcid = 3;}
				elseif ($userrow["alinhamento"] == "nevoa"){$idcid = 4;}
				elseif ($userrow["alinhamento"] == "areia"){$idcid = 5;}
				elseif ($userrow["alinhamento"] == "nuvem"){$idcid = 6;}
				$townqueryali = doquery("SELECT * FROM {{table}} WHERE id='$idcid' LIMIT 1", "towns");
    if (mysql_num_rows($townquery) == 0) { display("Erro 101: Houve um problema com o seu alinhamento.","Error"); }
    			$townrowali = mysql_fetch_array($townqueryali);
				//fim morrer alinhamento
                $updatequery = doquery("UPDATE {{table}} SET currenthp='$newhp',currentaction='In Town',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentfight='0',latitude='".$townrowali["latitude"]."',longitude='".$townrowali["longitude"]."',gold='$newgold' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
                $playerisdead = 1;
            }
        }
        
    // Do spell stuff.
    } elseif (isset($_POST["spell"])) {
	
	//Graficos
		$pagearray["grafico"] = $userrow["avatar"]."_jutsu.gif";
		
		// Your turn.
        $pickedspell = $_POST["userspell"];
        if ($pickedspell == 0) { header("Location: ./index.php?do=fight&avisochakra=Você deve selecionar um Jutsu primeiro.");die();}
        
        $newspellquery = doquery("SELECT * FROM {{table}} WHERE id='$pickedspell' LIMIT 1", "spells");
        $newspellrow = mysql_fetch_array($newspellquery);
        $spell = false;
        foreach($userspells as $a => $b) {
            if ($b == $pickedspell) { $spell = true; }
        }
        if ($spell != true) { display("Você ainda não aprendeu esse Jutsu. Por favor volte e tente novamente.", "Error"); die(); }
        if ($userrow["currentmp"] < $newspellrow["mp"]) { header("Location: ./index.php?do=fight&avisochakra=Você não tem chakra suficiente para usar o(a) ".$newspellrow["name"].".");die(); } //chakra jutsu.
        
        if ($newspellrow["type"] == 1) { // Heal spell.
            $newhp = $userrow["currenthp"] + $newspellrow["attribute"];
            if ($userrow["maxhp"] < $newhp) { $newspellrow["attribute"] = $userrow["maxhp"] - $userrow["currenthp"]; $newhp = $userrow["currenthp"] + $newspellrow["attribute"]; }
            $userrow["currenthp"] = $newhp;
            $userrow["currentmp"] -= $newspellrow["mp"];
            $pagearray["yourturn"] = "Você usou: ".$newspellrow["name"]." e ganhou ".$newspellrow["attribute"]." Pontos de Vida.<br /><br />";
        } elseif ($newspellrow["type"] == 2) { // Hurt spell.
            if ($userrow["currentmonsterimmune"] == 0) {
                $monsterdamage = rand((($newspellrow["attribute"]/6)*5), $newspellrow["attribute"]);
				include('funcoesinclusas.php'); //Colocando o dano de elemento.
				$query2 = doquery("SELECT elemento FROM {{table}} WHERE id='".$userrow['currentmonster']."' LIMIT 1", "monsters");
				$monsterrow2 = mysql_fetch_array($query2);
				$multiplicacaopontos = 0; //inicializar.
				$monsterdamagemult = elementoganhador($newspellrow['elemento'], $monsterrow2['elemento'], $multiplicacaopontos);
                $userrow["currentmonsterhp"] -= $monsterdamage * $monsterdamagemult;
				$userrow["currentmonsterhp"] -= ceil($monsterdamage * $monsterdamagemult * $multiplicacaopontos/100);
				if ($monsterdamagemult == 3/2){$cont = " Seu jutsu foi super efetivo.";}
				elseif ($monsterdamagemult == 1/2){$cont = " Seu jutsu foi pouco efetivo.";}else{$cont = "";}
				//Fim de colocando o dano de elemento.
                $pagearray["yourturn"] = "Você usou: ".$newspellrow["name"]." e causou $monsterdamage de dano.$cont<br /><br />";
            } else {
                $pagearray["yourturn"] = "Você usou: ".$newspellrow["name"].", mas o inimigo é imune a seu Jutsu.<br /><br />";
            }
            $userrow["currentmp"] -= $newspellrow["mp"];
        } elseif ($newspellrow["type"] == 3) { // Sleep spell.
            if ($userrow["currentmonsterimmune"] != 2) {
                $userrow["currentmonstersleep"] = $newspellrow["attribute"];
                $pagearray["yourturn"] = "Você usou: ".$newspellrow["name"].". O inimigo está dormindo.<br /><br />";
            } else {
                $pagearray["yourturn"] = "Você usou: ".$newspellrow["name"].", mas o inimigo é imune à ele.<br /><br />";
            }
            $userrow["currentmp"] -= $newspellrow["mp"];
        } elseif ($newspellrow["type"] == 4) { // +Damage spell.
            $userrow["currentuberdamage"] = $newspellrow["attribute"];
            $userrow["currentmp"] -= $newspellrow["mp"];
            $pagearray["yourturn"] = "Você usou: ".$newspellrow["name"]." e ganhou ".$newspellrow["attribute"]."% de dano até o fim da batalha.<br /><br />";
        } elseif ($newspellrow["type"] == 5) { // +Defense spell.
            $userrow["currentuberdefense"] = $newspellrow["attribute"];
            $userrow["currentmp"] -= $newspellrow["mp"];
            $pagearray["yourturn"] = "Você usou: ".$newspellrow["name"]." e ganhou ".$newspellrow["attribute"]."% de defesa até o fim da batalha.<br /><br />";            
        }
            
       $pagearray["monsterhp"] = "<script language=\"javascript\">document.getElementById('danoalt').innerHTML = '".$userrow["currentmonsterhp"]."';</script>";
        if ($userrow["currentmonsterhp"] <= 0) {
            $updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='0',currenthp='".$userrow["currenthp"]."',currentmp='".$userrow["currentmp"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
            header("Location: index.php?do=victory");
            die();
        }
        
        // Monster's turn.
        $pagearray["monsterturn"] = "";
        if ($userrow["currentmonstersleep"] != 0) { // Check to wake up.
            $chancetowake = rand(1,15);
			//atributo inteligencia, lembrar que o atributo do spell de dormir, é o chancetowake
				$chancetowake = $chancetowake - floor($userrow["inteligencia"]/100);
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
                $tohit = 0; $pagearray["monsterturn"] .= "Você fugiu do ataque inimigo. Não houve um dano.<br />";
                $persondamage = 0;
            } else {
                if ($tohit <= $toblock) { $tohit = $toblock + 1; }
                $persondamage = $tohit - $toblock;
                if ($userrow["currentuberdefense"] != 0) {
                    $persondamage -= ceil($persondamage * ($userrow["currentuberdefense"]/100));
                }
                if ($persondamage < 1) { $persondamage = 1; }
            }
				include('funcoesinclusas.php'); //Colocando o dano de elemento.
				$query2 = doquery("SELECT elemento FROM {{table}} WHERE id='".$userrow['currentmonster']."' LIMIT 1", "monsters");
				$monsterrow2 = mysql_fetch_array($query2);
				$explodirelemento = explode(",", $userrow['elemento']);
				$monsterdamagemult = elementoganhador($monsterrow2['elemento'], $explodirelemento[0], $naousado);
				if ($monsterdamagemult == 3/2){$cont = " O ataque inimigo foi super efetivo.";}
				elseif ($monsterdamagemult == 1/2){$cont = " O ataque inimigo foi pouco efetivo.";}else{$cont = "";}
				//Fim de colocando o dano de elemento.
            $pagearray["monsterturn"] .= "O inimigo te atacou, causando $persondamage de dano.$cont<br /><br />";
            $userrow["currenthp"] -= $persondamage * $monsterdamagemult;
            if ($userrow["currenthp"] <= 0) {
                $newgold = ceil($userrow["gold"]/2);
                $newhp = ceil($userrow["maxhp"]/4);
				//morrer alinhamento
				if ($userrow["alinhamento"] == "folha"){$idcid = 1;}
				elseif ($userrow["alinhamento"] == "pedra"){$idcid = 3;}
				elseif ($userrow["alinhamento"] == "nevoa"){$idcid = 4;}
				elseif ($userrow["alinhamento"] == "areia"){$idcid = 5;}
				elseif ($userrow["alinhamento"] == "nuvem"){$idcid = 6;}
				$townqueryali = doquery("SELECT * FROM {{table}} WHERE id='$idcid' LIMIT 1", "towns");
    if (mysql_num_rows($townquery) == 0) { display("Erro 101: Houve um problema com o seu alinhamento.","Error"); }
    			$townrowali = mysql_fetch_array($townqueryali);
				//fim morrer alinhamento
                $updatequery = doquery("UPDATE {{table}} SET currenthp='$newhp',currentaction='In Town',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentfight='0',latitude='".$townrowali["latitude"]."',longitude='".$townrowali["longitude"]."',gold='$newgold' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
                $playerisdead = 1;
            }
        }
    
    // Do a monster's turn if person lost the chance to swing first. Serves him right!
    } elseif ( $chancetoswingfirst == 0 ) {
        $pagearray["yourturn"] = "O inimigo te atacou antes que estivesse preparado!<br /><br />";
        $pagearray["monsterhp"] = "<script language=\"javascript\">document.getElementById('danoalt').innerHTML = '".$userrow["currentmonsterhp"]."';</script>";
        $pagearray["monsterturn"] = "";
        if ($userrow["currentmonstersleep"] != 0) { // Check to wake up.
            $chancetowake = rand(1,15);
			//atributo inteligencia
				$chancetowake = $chancetowake - floor($userrow["inteligencia"]/100);
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
				//morrer alinhamento
				if ($userrow["alinhamento"] == "folha"){$idcid = 1;}
				elseif ($userrow["alinhamento"] == "pedra"){$idcid = 3;}
				elseif ($userrow["alinhamento"] == "nevoa"){$idcid = 4;}
				elseif ($userrow["alinhamento"] == "areia"){$idcid = 5;}
				elseif ($userrow["alinhamento"] == "nuvem"){$idcid = 6;}
				$townqueryali = doquery("SELECT * FROM {{table}} WHERE id='$idcid' LIMIT 1", "towns");
    if (mysql_num_rows($townquery) == 0) { display("Erro 101: Houve um problema com o seu alinhamento.","Error"); }
    			$townrowali = mysql_fetch_array($townqueryali);
				//fim morrer alinhamento
                $updatequery = doquery("UPDATE {{table}} SET currenthp='$newhp',currentaction='In Town',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentfight='0',latitude='".$townrowali["latitude"]."',longitude='".$townrowali["longitude"]."',gold='$newgold' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
                $playerisdead = 1;
            }
        }

    } else {
        $pagearray["yourturn"] = "";
        $pagearray["monsterhp"] = "<script language=\"javascript\">document.getElementById('danoalt').innerHTML = '".$userrow["currentmonsterhp"]."';</script>";
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
if ($avisochakra != "") { $avisochakra2 .= "<font color=red>".$avisochakra."</font><br><br>";}//chakra mensagem
$pagearray["command"] = <<<END
Comando?<br /><br />
<form action="index.php?do=fight" method="post">
<input type="submit" name="fight" value="Atacar" /><br /><br />
<select name="userspell"><option value="0">Escolha um Jutsu</option>$magiclist</select> <input type="submit" name="spell" value="Usar" /><br /><br />
$avisochakra2
<input type="submit" name="run" value="Correr" />
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
	//Dados do monstro
	if ($monsterrow["immune"] == 0) { $immune = "<span class=\"light\">None</span>"; } elseif ($monsterrow["immune"] == 1) { $immune = "Dano"; } else { $immune = "Dano & Sono"; }
	$pagearray["dados"] = "<div style=\"height: 145px; width: 295px; background-image: url('layoutnovo/monstros/".$monsterrow['id'].".jpg')\"><div style=\"width:285px;font-size:15px;padding-left:11px;padding-top:10px;color:#e4d094;font-family:impact\">".$monsterrow['name']."</div><div style=\"position:relative;top:-19px;font-size:15px;padding-left:10px;color:#442800;text-shadow: 0 0 5px #ffffcc;font-family:impact\">".$monsterrow['name']."</div>
	<div style=\"position:relative;padding-left:78px;top:-11px\"><table bgcolor=\"#452202\" style=\"width:211px;height:99px;\"><tr bgcolor=\"#e0d4af\"><td ><center>Level</center></td><td ><center>Imunidade</center></td><td ><center>Elemento</center></td>
	
	</tr><tr bgcolor=\"#fff1c7\"><td ><center>".$monsterrow["level"]."</center></td><td ><center>$immune</center></td><td ><center><img src=\"images/".$monsterrow["elemento"].".gif\" title=\"Elemento ".ucfirst($monsterrow["elemento"])."\"></center></td>
	
	
	</tr><tr bgcolor=\"#e0d4af\" style=\"height:2px;\"><td ><center><font color=\"darkred\">HP</font></center></td><td ><center>Ataque</center></td><td ><center>Defesa</center></td></tr>
	
	<tr bgcolor=\"#fff1c7\"><td ><center><div id=\"danoalt\"><font color=\"red\"><blink>".$userrow["currentmonsterhp"]."</blink></font></div></center></td><td ><center>".$monsterrow["maxdam"]."</center></td><td ><center>".$monsterrow["armor"]."</center></td></tr>
	
	
</table></div></div>";
    
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
			$novospontosamostrar = 5;
			$novospontosdedistrubuicao = $userrow["pontoatributos"] + $novospontosamostrar;
			$novopontonatural = $userrow["maxnp"] + 5;
            
            if ($levelrow[$userrow["charclass"]."_spells"] != 0) {
                $userspells = $userrow["spells"] . ",".$levelrow[$userrow["charclass"]."_spells"];
                $newspell = "spells='$userspells',";
                $spelltext = "<br>Você aprendeu um novo Jutsu.";
            } else { $spelltext = ""; $newspell=""; }
            
			
			$updatequery = doquery("UPDATE {{table}} SET pontoatributos='$novospontosdedistrubuicao', maxnp='".$novopontonatural."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
			
			//Adicionando último monstro morto na contagem:
			$mnstexpl = explode(",", $userrow['ultmonstro']);
			if ($mnstexpl[0] == $monsterrow["name"]){$userrow['ultmonstro'] = $monsterrow["name"].",".($mnstexpl[1] + 1);}
			else{$userrow['ultmonstro'] = $monsterrow["name"].",1";}
			
			//Ver se vai ter item que vai quebrar.
			$durabexplode2 = explode(",", $userrow['durabilidade']);
			$msgfim = "";
			for ($h = 1; $h < 7; $h++){
				if ($durabexplode2[$h] != "X"){$durabexplode2[$h] -= 1;}
				if (($durabexplode2[$h] == "0") && ($h == 1)){$msgfim = "<br><font color=\"brown\">Seu item <b>".$userrow['weaponname']."</b> quebrou.</font>";}
				elseif (($durabexplode2[$h] == "0") && ($h == 2)){$msgfim .= "<br><font color=\"brown\">Seu item <b>".$userrow['armorname']."</b> quebrou.</font>";}
				elseif (($durabexplode2[$h] == "0") && ($h == 3)){$msgfim .= "<br><font color=\"brown\">Seu item <b>".$userrow['shieldname']."</b> quebrou.</font>";}
				elseif (($durabexplode2[$h] == "0") && ($h == 4)){$msgfim .= "<br><font color=\"brown\">Seu item <b>".$userrow['slot1name']."</b> quebrou.</font>";}
				elseif (($durabexplode2[$h] == "0") && ($h == 5)){$msgfim .= "<br><font color=\"brown\">Seu item <b>".$userrow['slot2name']."</b> quebrou.</font>";}
				elseif (($durabexplode2[$h] == "0") && ($h == 6)){$msgfim .= "<br><font color=\"brown\">Seu item <b>".$userrow['slot3name']."</b> quebrou.</font>";}
			}
			
			//Graficos
            $page = "
			<table width=\"100%\">
<tr><td align=\"center\"><center><img src=\"images/title_fighting.gif\" alt=\"Fighting\" /></center></td></tr></table>
			
			<table><tr><td width=\"310\" valign=\"middle\">".$pagearray["dados"]."
			
			
			</td><td>
			
<div style=\"position:relative; top: -10px;\"><div style=\"z-index: 1; position: relative\">	
<table width=\"165\" height=\"175\" background=\"layoutnovo/graficos/fundo.png\" style=\"background-repeat:no-repeat;;background-position:left top\"><tr height=\"30%\"><td></td></tr><tr><td><center><img src=\"layoutnovo/graficos/".$userrow["avatar"]."_ganhou.gif\"></center>
</td></tr><tr  height=\"15\"><td></td></tr></table>
</div>

</td></tr></table></div>

<center>
<div style=\"width:530px;position:relative;height:270px;top:-12px\">
<img src=\"images/leewin.png\" style=\"position:absolute;left:8px;z-index:2\">
<div style=\"z-index: 1;position:absolute;top:12px;left:220px;\">

			<div style=\"position:relative;width:280px\">
				<div style=\"height:12px;width:280px;background-image:url(layoutnovo/batalha/cimawin.png)\"></div>
				<div style=\"width:280px;background-image:url(layoutnovo/batalha/meiowin.png);color:white\">
				<div style=\"padding:10px 10px 10px 10px;text-align:left\">
				<img src=\"layoutnovo/batalha/moeda.png\" align=\"left\">
			Parabéns. Você derrotou o inimigo <b>".$monsterrow["name"]."</b>.<br><br><b>Você passou de nível!</b><br /><br>Você ganhou:<br><table><tr><td><img src=\"images/setabranca.gif\">+ $exp de experiência. $warnexp <br /><img src=\"images/setabranca.gif\">+ $gold de Ryou. $warngold <br /><img src=\"images/setabranca.gif\">+".$levelrow[$userrow["charclass"]."_hp"]." Pontos de Vida.<br /><img src=\"images/setabranca.gif\">+".$levelrow[$userrow["charclass"]."_mp"]." Pontos de Chakra.<br /><img src=\"images/setabranca.gif\">+".$levelrow[$userrow["charclass"]."_tp"]." Pontos de Viagem.<br /><img src=\"images/setabranca.gif\">+$novospontosamostrar Pontos de Distribuição.<br /><img src=\"images/setabranca.gif\">+5 Pontos Naturais.<br /><img src=\"images/setabranca.gif\">+".$levelrow[$userrow["charclass"]."_strength"]." de ataque.<br /><img src=\"images/setabranca.gif\">+".$levelrow[$userrow["charclass"]."_dexterity"]." de defesa.$msgfim$spelltext</td></tr></table>
			</div>
				</div>
				<div style=\"height:12px;width:280px;background-image:url(layoutnovo/batalha/baixowin.png)\"></div>
			</div>
			

			
</div></div>					
</center><br>

<center>Você pode continuar <a href=\"index.php\">explorando</a>.</center></div>
			";
            $title = "Você passou de nível!";
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
            
			//colocando probabilidade de sorte // antes tava rand(1,30) == 1 // droprate também..
			$sorte = floor($userrow["sorte"]*2/100 + 1);
			$droprat = ceil(25 - (22*$userrow["droprate"]/100));
            if (rand(1,$droprat) <= $sorte) {
                $dropquery = doquery("SELECT * FROM {{table}} WHERE mlevel <= '".$monsterrow["level"]."' ORDER BY RAND() LIMIT 1", "drops");
                $droprow = mysql_fetch_array($dropquery);
                $dropcode = "dropcode='".$droprow["id"]."',";
                $page = "<br><center>Esse inimigo dropou um item. Clique na exclamação acima para equipar o item, ou você pode continuar <a href=\"index.php\">explorando</a> e ignorar o item.</center>";
				$mostrarexc = "<center><a href=\"index.php?do=drop\"><img border=\"0\" src=\"images/exclamacao.gif\" title=\"Pegar o Drop\" alt=\"Pegar o Drop\"></a></center><br>";
            } else { 
                $dropcode = "";
				//alterado
                $page = "<br><center>Você pode continuar <a href=\"index.php\">explorando</a>.</center>";
            }

            $title = "Vitória!";

			
			//Adicionando último monstro morto na contagem:
			$mnstexpl = explode(",", $userrow['ultmonstro']);
			if ($mnstexpl[0] == $monsterrow["name"]){$userrow['ultmonstro'] = $monsterrow["name"].",".($mnstexpl[1] + 1);}
			else{$userrow['ultmonstro'] = $monsterrow["name"].",1";}
			
			//Ver se vai ter item que vai quebrar.
			$durabexplode2 = explode(",", $userrow['durabilidade']);
			$msgfim = "";
			for ($h = 1; $h < 7; $h++){
				if ($durabexplode2[$h] != "X"){$durabexplode2[$h] -= 1;}
				if (($durabexplode2[$h] == "0") && ($h == 1)){$msgfim = "<br><font color=\"brown\">Seu item <b>".$userrow['weaponname']."</b> quebrou.</font>";}
				elseif (($durabexplode2[$h] == "0") && ($h == 2)){$msgfim .= "<br><font color=\"brown\">Seu item <b>".$userrow['armorname']."</b> quebrou.</font>";}
				elseif (($durabexplode2[$h] == "0") && ($h == 3)){$msgfim .= "<br><font color=\"brown\">Seu item <b>".$userrow['shieldname']."</b> quebrou.</font>";}
				elseif (($durabexplode2[$h] == "0") && ($h == 4)){$msgfim .= "<br><font color=\"brown\">Seu item <b>".$userrow['slot1name']."</b> quebrou.</font>";}
				elseif (($durabexplode2[$h] == "0") && ($h == 5)){$msgfim .= "<br><font color=\"brown\">Seu item <b>".$userrow['slot2name']."</b> quebrou.</font>";}
				elseif (($durabexplode2[$h] == "0") && ($h == 6)){$msgfim .= "<br><font color=\"brown\">Seu item <b>".$userrow['slot3name']."</b> quebrou.</font>";}
			}
			
			//Sorte de Batalha
			$sorterand = rand(1,10);
			if ($sorterand <= 1){
				$randomizado = rand(1,2); $userrow['currentep'] += $randomizado; if ($userrow['currentep'] > $userrow['maxep']){$userrow['currentep'] = $userrow['maxep'];}
				$sortebatalha = "<center><div style=\"position:relative\"><img src=\"images/sorte_ep.jpg\"><div style=\"position:absolute;left:55px;top:30px;color:white;\"><img src=\"images/setabranca.gif\">+ $randomizado EP</div></div></center>";}elseif ($sorterand == 2){
				$randomizado = rand(1,15); $randomizado += floor(($monsterrow["level"] * 2 * $randomizado)/100); $userrow['currenttp'] += $randomizado;if ($userrow['currenttp'] > $userrow['maxtp']){$userrow['currenttp'] = $userrow['maxtp'];}
				$sortebatalha = "<center><div style=\"position:relative\"><img src=\"images/sorte_tp.jpg\"><div style=\"position:absolute;left:55px;top:30px;color:white;\"><img src=\"images/setabranca.gif\">+ $randomizado TP</div></div></center>";}elseif (($sorterand >= 3) && ($sorterand <= 4)){
				$randomizado = rand(1,10);$randomizado += floor(($monsterrow["level"] * 2 * $randomizado)/100); $userrow['currentmp'] += $randomizado;if ($userrow['currentmp'] > $userrow['maxmp']){$userrow['currentmp'] = $userrow['maxmp'];}
				$sortebatalha = "<center><div style=\"position:relative\"><img src=\"images/sorte_mp.jpg\"><div style=\"position:absolute;left:55px;top:30px;color:white;\"><img src=\"images/setabranca.gif\">+ $randomizado CH</div></div></center>";}elseif (($sorterand >= 5) && ($sorterand <= 6)){
				$randomizado = rand(1,20);$randomizado += floor(($monsterrow["level"] * 2 * $randomizado)/100); $userrow['currenthp'] += $randomizado;if ($userrow['currenthp'] > $userrow['maxhp']){$userrow['currenthp'] = $userrow['maxhp'];}
				$sortebatalha = "<center><div style=\"position:relative\"><img src=\"images/sorte_hp.jpg\"><div style=\"position:absolute;left:55px;top:30px;color:white;\"><img src=\"images/setabranca.gif\">+ $randomizado HP</div></div></center>";}elseif ($sorterand >= 7){
				$randomizado = rand(1,5);$randomizado += floor(($monsterrow["level"] * 2 * $randomizado)/100); $userrow['currentnp'] += $randomizado;if ($userrow['currentnp'] > $userrow['maxnp']){$userrow['currentnp'] = $userrow['maxnp'];}
				$sortebatalha = "<center><div style=\"position:relative\"><img src=\"images/sorte_np.jpg\"><div style=\"position:absolute;left:55px;top:30px;color:white;\"><img src=\"images/setabranca.gif\">+ $randomizado NP</div></div></center>";}
			
				
			//Graficos também abaixo:
			$page = "
			<table width=\"100%\">
<tr><td align=\"center\"><center><img src=\"images/title_fighting.gif\" alt=\"Fighting\" /></center></td></tr></table>
			
			<table><tr><td width=\"310\" valign=\"middle\">".$pagearray["dados"]."<br><center>
			

			
			<div style=\"position:relative;width:280px\">
				<div style=\"height:12px;width:280px;background-image:url(layoutnovo/batalha/cimawin.png)\"></div>
				<div style=\"width:280px;background-image:url(layoutnovo/batalha/meiowin.png);color:white\">
				<div style=\"padding:8px 8px 8px 8px;text-align:left\">
				<img src=\"layoutnovo/batalha/moeda.png\" align=\"left\">
			Parabéns. Você derrotou <b>".$monsterrow["name"]."</b>.<br /><br>Você ganhou:<br><table><tr><td><img src=\"images/setabranca.gif\">+$exp de experiência. $warnexp <br /><img src=\"images/setabranca.gif\">+ $gold Ryou. $warngold $msgfim
			<br /></td></tr></table>
			</div>
				</div>
				<div style=\"height:12px;width:280px;background-image:url(layoutnovo/batalha/baixowin.png)\"></div>
			</div>
			
			
			
			</center>
		

			
			</td><td>
			
<div style=\"z-index: 1; position:relative\">			
<table width=\"165\" height=\"175\" background=\"layoutnovo/graficos/fundo.png\" style=\"background-repeat:no-repeat;;background-position:left top\"><tr height=\"30%\"><td></td></tr><tr><td><center><img src=\"layoutnovo/graficos/".$userrow["avatar"]."_ganhou.gif\"></center>
</td></tr><tr  height=\"15\"><td></td></tr></table></div>
<center>$mostrarexc</center>

$sortebatalha

</div>
</td></tr></table>
			".$page;
			
			
        }
    }
	
	    $updatequery = doquery("UPDATE {{table}} SET currentaction='Exploring',level='$newlevel',maxhp='$newhp',maxmp='$newmp',maxtp='$newtp',strength='$newstrength',dexterity='$newdexterity',attackpower='$newattack',defensepower='$newdefense', $newspell currentfight='0',currentmonster='0',currentmonsterhp='0',currenthp='".$userrow['currenthp']."',currentmp='".$userrow['currentmp']."',currenttp='".$userrow['currenttp']."',currentep='".$userrow['currentep']."', currentnp='".$userrow['currentnp']."',currentmonstersleep='0',currentmonsterimmune='0',currentuberdamage='0',currentuberdefense='0',$dropcode experience='$newexp',gold='$newgold', ultmonstro='".$userrow['ultmonstro']."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");

	
	//Atualizando vetor.
	$uquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow['id']."' LIMIT 1", "users");
    $userrow = mysql_fetch_array($uquery);
	
			//Quebrando arma com durabilidade.
			global $qual, $deletar, $userrow;
			$durabexplode = explode(",", $userrow['durabilidade']);
			for ($h = 1; $h < 7; $h++){
				if ($durabexplode[$h] != "X"){$durabexplode[$h] -= 1;}
				if (($durabexplode[$h] == "0") && ($h == 1)){$deletar = true; $qual = 1; include('desequipar.php');}
				elseif (($durabexplode[$h] == "0") && ($h == 2)){$deletar = true; $qual = 2; include('desequipar.php');}
				elseif (($durabexplode[$h] == "0") && ($h == 3)){$deletar = true; $qual = 3; include('desequipar.php');}
				elseif (($durabexplode[$h] == "0") && ($h == 4)){$deletar = true; $qual = 4; include('desequipar.php');}
				elseif (($durabexplode[$h] == "0") && ($h == 5)){$deletar = true; $qual = 5; include('desequipar.php');}
				elseif (($durabexplode[$h] == "0") && ($h == 6)){$deletar = true; $qual = 6; include('desequipar.php');}
				if ($durabexplode[$h] == "0"){$durabexplode[$h] = "X";}
			}
			$novadurabilidade = "-,".$durabexplode[1].",".$durabexplode[2].",".$durabexplode[3].",".$durabexplode[4].",".$durabexplode[5].",".$durabexplode[6];
			$updatequery = doquery("UPDATE {{table}} SET durabilidade='$novadurabilidade' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");		
			//Fim quebrar item.
    

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
		
						//id do item
						if (($slot > 0) && ($slot < 4)){
							$iddoitem = $userrow["slot".$slot."id"];
						}else{
							$slot -= 3;
							$bpitem = explode(",",$userrow["bp".$slot]);
							$iddoitem = $bpitem[1];
							$true = true;
						}
       
	  if ($true != true){
					if ($userrow["slot".$slot."id"] != 0) {
												
						$slotquery = doquery("SELECT * FROM {{table}} WHERE id='".$iddoitem."' LIMIT 1", "drops");
						$slotrow = mysql_fetch_array($slotquery);
						
						$old1 = explode(",",$slotrow["attribute1"]);
						if ($slotrow["attribute2"] != "X") { $old2 = explode(",",$slotrow["attribute2"]); } else { $old2 = array(0=>"maxhp",1=>0); }
						$new1 = explode(",",$droprow["attribute1"]);
						if ($droprow["attribute2"] != "X") { $new2 = explode(",",$droprow["attribute2"]); } else { $new2 = array(0=>"maxhp",1=>0); }
						
						$userrow[$old1[0]] -= $old1[1];
						$userrow[$old2[0]] -= $old2[1];
						if ($old1[0] == "strength") { $userrow["attackpower"] -= $old1[1]; }
						elseif ($old1[0] == "dexterity") { $userrow["defensepower"] -= $old1[1]; }
						if ($old2[0] == "strength") { $userrow["attackpower"] -= $old2[1]; }
						elseif ($old2[0] == "dexterity") { $userrow["defensepower"] -= $old2[1]; }
			
						
						$userrow[$new1[0]] += $new1[1];
						$userrow[$new2[0]] += $new2[1];
						if ($new1[0] == "strength") { $userrow["attackpower"] += $new1[1]; }
						elseif ($new1[0] == "dexterity") { $userrow["defensepower"] += $new1[1]; }
						if ($new2[0] == "strength") { $userrow["attackpower"] += $new2[1]; }
						elseif ($new2[0] == "dexterity") { $userrow["defensepower"] += $new2[1]; }
						
						if ($userrow["currenthp"] > $userrow["maxhp"]) { $userrow["currenthp"] = $userrow["maxhp"]; }
						if ($userrow["currentmp"] > $userrow["maxmp"]) { $userrow["currentmp"] = $userrow["maxmp"]; }
						if ($userrow["currenttp"] > $userrow["maxtp"]) { $userrow["currenttp"] = $userrow["maxtp"]; }
						if ($userrow["currentnp"] > $userrow["maxnp"]) { $userrow["currentnp"] = $userrow["maxnp"]; }
						if ($userrow["currentep"] > $userrow["maxep"]) { $userrow["currentep"] = $userrow["maxep"]; }
						$newname = addslashes($droprow["name"]);
						
						$query = doquery("UPDATE {{table}} SET slot".$_POST["slot"]."name='$newname',slot".$_POST["slot"]."id='".$droprow["id"]."',$old1[0]='".$userrow[$old1[0]]."',$old2[0]='".$userrow[$old2[0]]."',$new1[0]='".$userrow[$new1[0]]."',$new2[0]='".$userrow[$new2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."',currenthp='".$userrow["currenthp"]."',currentmp='".$userrow["currentmp"]."',currenttp='".$userrow["currenttp"]."',currentnp='".$userrow["currentnp"]."',currentep='".$userrow["currentep"]."',sorte='".$userrow["sorte"]."',agilidade='".$userrow["agilidade"]."',determinacao='".$userrow["determinacao"]."',precisao='".$userrow["precisao"]."',inteligencia='".$userrow["inteligencia"]."',droprate='".$userrow["droprate"]."',maxnp='".$userrow["maxnp"]."',maxep='".$userrow["maxep"]."', dropcode='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
					
						
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
						$query = doquery("UPDATE {{table}} SET slot".$_POST["slot"]."name='$newname',slot".$_POST["slot"]."id='".$droprow["id"]."',$new1[0]='".$userrow[$new1[0]]."',$new2[0]='".$userrow[$new2[0]]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."',dropcode='0',currentnp='".$userrow["currentnp"]."',currentep='".$userrow["currentep"]."',sorte='".$userrow["sorte"]."',agilidade='".$userrow["agilidade"]."',determinacao='".$userrow["determinacao"]."',precisao='".$userrow["precisao"]."',inteligencia='".$userrow["inteligencia"]."',droprate='".$userrow["droprate"]."', maxnp='".$userrow["maxnp"]."',maxep='".$userrow["maxep"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
						
					}
	  	}else{//elsefim if... slot and slot
				$newname = addslashes($droprow["name"]);
				$itempronto = $newname.",".$droprow["id"].",4,X";
				$query = doquery("UPDATE {{table}} SET bp".$slot."='$itempronto', dropcode='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
		}//fim if.. slot and slot...
				
      header("Location: ./index.php?conteudo=O item foi equipado ou adicionado à mochila com sucesso.");die();
        
    }//fim do submit...
    
    $attributearray = array("maxhp"=>"Max HP",
                            "maxmp"=>"Max CH",
                            "maxtp"=>"Max TP",
                            "defensepower"=>"Poder de Defesa",
                            "attackpower"=>"Poder de Ataque",
                            "strength"=>"Força",
                            "dexterity"=>"Armadura",
                            "expbonus"=>"Bônus de Experiência",
                            "goldbonus"=>"Bônus de Ryou",
							"sorte"=>"Sorte",
							"agilidade"=>"Agilidade",
							"determinacao"=>"Determinação",
							"precisao"=>"Precisão",
							"inteligencia"=>"Inteligência",
							"droprate"=>"Chance de Drop",
							"maxnp"=>"Max NP",
							"maxep"=>"Max EP");
    
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/drop.gif\" /></center></td></tr></table><center><table><tr><td width=\"50%\"><center>O inimigo dropou o seguinte item: <br><br><table><tr><td><table><tr bgcolor=\"#613003\"><td><center><font color=white><b>".$droprow["name"]."</b></font></center></td></tr>";
    
    $attribute1 = explode(",",$droprow["attribute1"]);
    $page .= "<tr bgcolor=\"#E4D094\"><td>".$attributearray[$attribute1[0]];
    if ($attribute1[1] > 0) { $page .= " +" . $attribute1[1] . "</td></tr>"; } else { $page .= " ". $attribute1[1] . "</td></tr>"; }
    
    if ($droprow["attribute2"] != "X") { 
        $attribute2 = explode(",",$droprow["attribute2"]);
        $page .= "<tr bgcolor=\"#FFF1C7\"><td>".$attributearray[$attribute2[0]];
        if ($attribute2[1] > 0) { $page .= " +" . $attribute2[1] . "</td></tr>"; } else { $page .= " -". $attribute2[1] . "</td></tr>"; }
    }
	include('funcoesinclusas.php');
	$agorajava = conteudoexplic($droprow["id"], '4', 'idatr', '*');
	$page .= "</table></td><td><div style=\"padding-top: 3px\"><img src=\"layoutnovo/equipamentos/drops/".$droprow["id"].".gif\" style=\"border:2px #e4d094 solid;\" id=\"idatr\" onmouseout=\"fecharexplic();\" onmouseover=\"$agorajava\"></div></td></tr></table></center><br>";
    
	//backpack mostrar
	for($h = 1; $h <= 4; $h ++){
	$fundo = $h % 2;
	if ($fundo == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
		if ($userrow["bp".$h] != "None"){
			$bpitem = explode(",",$userrow["bp".$h]);
			$botao .= "<tr bgcolor=\"$bgcolor\"><td width=\"10\"><input type=\"radio\" id=\"slot\" name=\"slot\" value=\"".(3 + $h)."\"></td><td width=\"20\"><img src=\"images/backpack_pequena.gif\" title=\"Mochila Slot ".$h."\"></td><td>".$bpitem[0]."</td></tr>";
		}else{
			$botao .= "<tr bgcolor=\"$bgcolor\"><td width=\"10\"><input type=\"radio\" id=\"slot\" name=\"slot\" value=\"".(3 + $h)."\"></td><td width=\"20\"><img src=\"images/backpack_pequena.gif\" title=\"Mochila Slot ".$h."\"></td><td><font color=gray>None</font></td></tr>";
		}
	}
	
    $page .= "<center>Selecione um slot do inventário da lista ao lado para equipar o item. <font color=\"red\">Se o slot estiver cheio, o Item antigo será descartado</font>.<br><br>Caso esteja escrito <font color=\"grey\">None</font> em uma das opções ao lado, indica uma posição livre.</center></td><td>";
    $page .= "<center><form action=\"index.php?do=drop\" method=\"post\"><table><tr bgcolor=\"#613003\"><td colspan=\"6\"><center><font color=white>Onde Equipar?</font></center></td>
	<tr bgcolor=\"#E4D094\"><td width=\"10\"><input type=\"radio\" id=\"slot\" name=\"slot\" value=\"1\"></td><td width=\"20\"><center><img src=\"images/orb.gif\" title=\"Slot 1\"></center></td><td>".$userrow["slot1name"]."</td></tr>
	<tr bgcolor=\"#FFF1C7\"><td width=\"10\"><input type=\"radio\" id=\"slot\" name=\"slot\" value=\"2\"></td><td width=\"20\"><center><img src=\"images/orb.gif\" title=\"Slot 2\"></center></td><td>".$userrow["slot2name"]."</td></tr>
	<tr bgcolor=\"#E4D094\"><td width=\"10\"><input type=\"radio\" id=\"slot\" name=\"slot\" value=\"3\"></td><td width=\"20\"><center><img src=\"images/orb.gif\" title=\"Slot 3\"></center></td><td>".$userrow["slot3name"]."</td></tr>
	$botao
	</table>
 <input type=\"submit\" name=\"submit\" value=\"OK!\" /></form></center></td></tr></table><br>";
    $page .= "<center>Você também pode continuar <a href=\"index.php\">explorando</a> e descartar esse item.</center>";
    
    display($page, "Item Drop");
    
}
    

function dead() {
    
    $page = "<b>Você morreu.</b><br /><br />Como consequência, você perdeu metade do seu Ryou. De qualque forma, lhe foi dado metade dos seus Pontos de Vida para continuar sua jornada.<br /><br />Você pode voltar para a <a href=\"index.php\">vila</a> que você está alinhado. Esperamos que se sinta melhor quando acordar.";
        
}



?>