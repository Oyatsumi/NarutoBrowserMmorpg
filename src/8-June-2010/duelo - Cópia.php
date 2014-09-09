<?php // users.php :: Handles user account functions.



$link = opendb();


    /* testando se está logado */
		
		$userrow = checkcookies();
		if ($userrow == false) { die("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação."); }
		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
			/*fim do teste */
			
			 
			 //dados de batalha
		$batalhanome = $userrow["batalha_nome"];
		$batalhaid = $userrow["batalha_id"];
		$batalhahp = $userrow["batalha_hp"];
		
		//fim
		$hpjogador = $userrow["maxhp"];
		$timer = $userrow["batalha_timer"];
		
		//fazer o timer 2
		$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$batalhanome' LIMIT 1","users");
		if (mysql_num_rows($userquery) != 1) { die("Não existe nenhuma conta com esse nome."); }
		$userrow = mysql_fetch_array($userquery);
		$timer2 = $userrow["batalha_timer"];
		unset($userquery);
        unset($userrow);
		//fim
        
		//if ($batalhaid != 1) {die("Respeite a ordem de Duelo, envie esse link para o outro Jogador."); }
		if  ($timer < 2) {if ($timer2 > 1){
		$timer2 -= 1;
		$updatequery = doquery("UPDATE {{table}} SET batalha_timer='$timer2' WHERE charname='$batalhanome' LIMIT 1","users");
		die("Aguarde...$timer segundos. Turno do segundo jogador.<meta HTTP-EQUIV='refresh' CONTENT='1;URL=duelo.php'>"); }
		else{
		$updatequery = doquery("UPDATE {{table}} SET batalha_timer='6' WHERE charname='$usuariologadonome' LIMIT 1","users");}
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
function duelo() { // One big long function that determines the outcome of the fight.
    
    global $userrow, $controlrow;
    if ($userrow["currentaction"] != "Fighting") { display("Tentativa de trapaça detectada.", "Error"); }
    $pagearray = array();
    $playerisdead = 0;
    
    $pagearray["magiclist"] = "";
    $userspells = explode(",",$userrow["spells"]);
    $spellquery = doquery("SELECT id,name FROM {{table}}", "spells");
    while ($spellrow = mysql_fetch_array($spellquery)) {
        $spell = false;
        foreach ($userspells as $a => $b) {
            if ($b == $spellrow["id"]) { $spell = true; }
        }
        if ($spell == true) {
            $pagearray["magiclist"] .= "<option value=\"".$spellrow["id"]."\">".$spellrow["name"]."</option>\n";
        }
        unset($spell);
    }
    if ($pagearray["magiclist"] == "") { $pagearray["magiclist"] = "<option value=\"0\">None</option>\n"; }
    $magiclist = $pagearray["magiclist"];
    
    $chancetoswingfirst = 1;
	

    // Pick a monster.
        $monsterquery = doquery("SELECT * FROM {{table}} WHERE charname='$batalhanome' LIMIT 1", "users");
        $jogador2row = mysql_fetch_array($monsterquery);
        $userrow["currentmonsterhp"] = rand((($jogador2row["maxhp"]/5)*4),$jogador2row["maxhp"]);
        if ($userrow["difficulty"] == 2) { $userrow["currentmonsterhp"] = ceil($userrow["currentmonsterhp"] * $controlrow["diff2mod"]); }
        if ($userrow["difficulty"] == 3) { $userrow["currentmonsterhp"] = ceil($userrow["currentmonsterhp"] * $controlrow["diff3mod"]); }
                       $userrow["currentmonstersleep"] = 0;
        unset($monsterquery);
        unset($jogador2row);
        
    }
    
    // Next, get the monster statistics.
    $monsterquery = doquery("SELECT * FROM {{table}} WHERE charname='$batalhanome' LIMIT 1", "users");
    $jogador2row = mysql_fetch_array($monsterquery);
    $pagearray["monstername"] = $jogador2row["charname"];
    
           
    // Do fight stuff.
    if (isset($_POST["duelo"])) {
        
        // Your turn.
        $pagearray["yourturn"] = "";
        $tohit = ceil(rand($userrow["attackpower"]*.75,$userrow["attackpower"])/3);
        $toexcellent = rand(1,150);
        if ($toexcellent <= sqrt($userrow["strength"])) { $tohit *= 2; $pagearray["yourturn"] .= "Hit Excelente!<br />"; }
        $toblock = ceil(rand($jogador2row["defensepower"]*.75,$jogador2row["defensepower"])/3);        
        $tododge = rand(1,200);
        if ($tododge <= sqrt($jogador2row["dexterity"])) { 
            $tohit = 0; $pagearray["yourturn"] .= "O jogador desviando. Nenhum dano foi recebido por ele.<br />"; 
            $monsterdamage = 0;
        } else {
            $monsterdamage = $tohit - $toblock;
            if ($monsterdamage < 1) { $monsterdamage = 1; }
            if ($userrow["currentuberdamage"] != 0) {
                $monsterdamage += ceil($monsterdamage * ($userrow["currentuberdamage"]/100));
            }
			
			
        }
		
		
		 //parte teste, timer:
			 $updatequery = doquery("UPDATE {{table}} SET batalha_timer='1' WHERE charname='$usuariologadonome' LIMIT 1","users");
	  $updatequery = doquery("UPDATE {{table}} SET batalha_timer='6' WHERE charname='$batalhanome' LIMIT 1","users");
	  //fim timer
	  
	  
        $pagearray["yourturn"] .= "Você atacou o jogador provocando $monsterdamage de dano.<br /><br />";
		
        $userrow["currentmonsterhp"] -= $monsterdamage;
		 $userrow["batalha_hp"] = $userrow["currentmonsterhp"];
        $pagearray["monsterhp"] = "HP do Jogador: " . $userrow["currentmonsterhp"] . "<br /><br />";
        if ($userrow["currentmonsterhp"] <= 0) {
            $updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
			$updatequery = doquery("UPDATE {{table}} SET batalha_hp='0' WHERE charname='$batalhanome' LIMIT 1", "users");
			 $updatequery = doquery("UPDATE {{table}} SET currenthp='$hpjogador',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentfight='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
			
            header("Location: index.php?do=ganhou"); //editar ganhou
            die();
        }
        
        // JOGADOR ESTA MORTO.
       
            if ($userrow["currenthp"] <= 0) {
                $newgold = ceil($userrow["gold"]/2);
                $newhp = ceil($userrow["maxhp"]/4);
                $updatequery = doquery("UPDATE {{table}} SET currenthp='$hpjogador',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentfight='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
                $playerisdead = 1;
            }
      
        
    // Do spell stuff.
    } elseif (isset($_POST["spell"])) {
        
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
		
		//monstro imune ao sleep, eu adicionei
        $userrow["currentmonsterimmune"] = 2;
		
        if ($newspellrow["type"] == 1) { // Heal spell.
            $newhp = $userrow["currenthp"] + $newspellrow["attribute"];
            if ($userrow["maxhp"] < $newhp) { $newspellrow["attribute"] = $userrow["maxhp"] - $userrow["currenthp"]; $newhp = $userrow["currenthp"] + $newspellrow["attribute"]; }
            $userrow["currenthp"] = $newhp;
            $userrow["currentmp"] -= $newspellrow["mp"];
            $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"]." e ganhou ".$newspellrow["attribute"]." Pontos de Vida.<br /><br />";
			
			 //parte teste, timer:
			 $updatequery = doquery("UPDATE {{table}} SET batalha_timer='1' WHERE charname='$usuariologadonome' LIMIT 1","users");
	  $updatequery = doquery("UPDATE {{table}} SET batalha_timer='6' WHERE charname='$batalhanome' LIMIT 1","users");
	  //fim timer
			
			
        } elseif ($newspellrow["type"] == 2) { // Hurt spell.
            if ($userrow["currentmonsterimmune"] == 0) {
                $monsterdamage = rand((($newspellrow["attribute"]/6)*5), $newspellrow["attribute"]);
                $userrow["currentmonsterhp"] -= $monsterdamage;
                $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"]." e causou $monsterdamage de dano.<br /><br />";
            } else {
                $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"].", mas o jogador é imune à seu Jutsu.<br /><br />";
            }
			
			 //parte teste, timer:
			 $updatequery = doquery("UPDATE {{table}} SET batalha_timer='1' WHERE charname='$usuariologadonome' LIMIT 1","users");
	  $updatequery = doquery("UPDATE {{table}} SET batalha_timer='6' WHERE charname='$batalhanome' LIMIT 1","users");
	  //fim timer
			
            $userrow["currentmp"] -= $newspellrow["mp"];
			} 
			elseif ($newspellrow["type"] == 3) { // Sleep spell.
            if ($userrow["currentmonsterimmune"] != 2) {
                $userrow["currentmonstersleep"] = $newspellrow["attribute"];
                $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"].". O inimigo está dormindo.<br /><br />";
            } else {
                $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"].", mas o jogador é imune à ele.<br /><br />";
            }
			
			 //parte teste, timer:
			 $updatequery = doquery("UPDATE {{table}} SET batalha_timer='1' WHERE charname='$usuariologadonome' LIMIT 1","users");
	  $updatequery = doquery("UPDATE {{table}} SET batalha_timer='6' WHERE charname='$batalhanome' LIMIT 1","users");
	  //fim timer
	  
	  
            $userrow["currentmp"] -= $newspellrow["mp"];
        } elseif ($newspellrow["type"] == 4) { // +Damage spell.
            $userrow["currentuberdamage"] = $newspellrow["attribute"];
            $userrow["currentmp"] -= $newspellrow["mp"];
            $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"]." e ganhou ".$newspellrow["attribute"]."% de dano até o fim da batalha.<br /><br />";
			
			 //parte teste, timer:
			 $updatequery = doquery("UPDATE {{table}} SET batalha_timer='1' WHERE charname='$usuariologadonome' LIMIT 1","users");
	  $updatequery = doquery("UPDATE {{table}} SET batalha_timer='6' WHERE charname='$batalhanome' LIMIT 1","users");
	  //fim timer
	  
	  
        } elseif ($newspellrow["type"] == 5) { // +Defense spell.
            $userrow["currentuberdefense"] = $newspellrow["attribute"];
            $userrow["currentmp"] -= $newspellrow["mp"];
            $pagearray["yourturn"] = "Você usou o Jutsu: ".$newspellrow["name"]." e ganhou ".$newspellrow["attribute"]."% de defesa até o fim da batalha.<br /><br />";
			
			 //parte teste, timer:
			 $updatequery = doquery("UPDATE {{table}} SET batalha_timer='1' WHERE charname='$usuariologadonome' LIMIT 1","users");
	  $updatequery = doquery("UPDATE {{table}} SET batalha_timer='6' WHERE charname='$batalhanome' LIMIT 1","users");
	  //fim timer            
        }
            
        $pagearray["monsterhp"] = "HP do Jogador: " . $userrow["currentmonsterhp"] . "<br /><br />";
        if ($userrow["currentmonsterhp"] <= 0) {
            $updatequery = doquery("UPDATE {{table}} SET currentmonsterhp='0',currenthp='".$userrow["currenthp"]."',currentmp='".$userrow["currentmp"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
           $updatequery = doquery("UPDATE {{table}} SET batalha_hp='0' WHERE charname='$batalhanome' LIMIT 1", "users");
		   $updatequery = doquery("UPDATE {{table}} SET currenthp='$hpjogador',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentfight='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
            header("Location: index.php?do=ganhou"); //editar ganhou
            die();
        }
        
        
		//editar player morto
            if ($userrow["currenthp"] <= 0) {
                $newgold = ceil($userrow["gold"]/2);
                $newhp = ceil($userrow["maxhp"]/4);
                $updatequery = doquery("UPDATE {{table}} SET currenthp='$hpjogador',currentmonster='0',currentmonsterhp='0',currentmonstersleep='0',currentmonsterimmune='0',currentfight='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
                $playerisdead = 1;
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
Command?<br /><br />
<form action="index.php?do=duelo" method="post">
<input type="submit" name="duelo" value="Duelar" /><br /><br />
<select name="userspell"><option value="0">Escolha Um</option>$magiclist</select> <input type="submit" name="spell" value="Spell" /><br /><br />
<input type="submit" name="run" value="Run" /><br /><br />
</form>
END;
    $updatequery = doquery("UPDATE {{table}} SET currentaction='Fighting',currenthp='$newhp',currentmp='$newmp',currentfight='$newfight',currentmonster='$newmonster',currentmonsterhp='$newmonsterhp',currentmonstersleep='$newmonstersleep',currentmonsterimmune='$newmonsterimmune',currentuberdamage='$newuberdamage',currentuberdefense='$newuberdefense' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
} else {
    $pagearray["command"] = "<b>Você morreu.</b><br /><br />Como consequencia, você perdeu metade de seus Ryou. De qualquer forma, lhe foi dado metade dos seus Pontos de Vida para continuar sua jornada.<br /><br />Você pode voltar para a <a href=\"index.php\">cidade</a>, e esperamos que se sinta melhor da próxima vez.";
}
    
    // Finalize page and display it.
    $template = gettemplate("duelo");
    $page = parsetemplate($template,$pagearray);
    
    display($page, "Duelo");
    
}










?>