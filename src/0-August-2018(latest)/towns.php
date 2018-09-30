<?php // towns.php :: Handles all actions you can do in town.
include('funcoesinclusas.php');

$userrow = checkcookies();
	if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode utilizar uma cidade enquanto estiver em um Duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }
	
function inn() { // Staying at the inn resets all expendable stats to their max values.
    
	
		
		
    global $userrow, $numqueries;

    $townquery = doquery("SELECT name,innprice FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
    if (mysqli_num_rows($townquery) != 1) { display("Tentativa de trapaça detectada.<br>", "Error"); }
    $townrow = mysqli_fetch_array($townquery);
    
    if ($userrow["gold"] < $townrow["innprice"]) {header("Location: ./index.php?conteudo=Você não tem Ryou suficiente para passar a noite numa pousada.");die(); }
    
    if (isset($_POST["submit"])) {
        
        $newgold = $userrow["gold"] - $townrow["innprice"];
        $query = doquery("UPDATE {{table}} SET gold='$newgold',currenthp='".$userrow["maxhp"]."',currentmp='".$userrow["maxmp"]."',currenttp='".$userrow["maxtp"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
		header("Location: ./index.php?conteudo=Você descansou e acordou se sentindo regenarado e pronto pra ação.");die();
        
    } elseif (isset($_POST["cancel"])) {
        
        header("Location: index.php"); die();
         
    } else {
        
        $title = "Pousada";
        $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/descansar.gif\" /></center></td></tr></table>".personagemgeral("Descansar numa pousada vai encher o seu HP, CH e TP ao seu máximo.
<br />Uma noite de sono na pousada vai te custar: <b>" . $townrow["innprice"] . " Ryou</b>. Está tudo bem?<br /><br /><form action=\"index.php?do=inn\" method=\"post\"><div class=\"buttons\"><button type=\"submit\" class=\"positive\" name=\"submit\"><img src=\"layoutnovo/dropmenu/b1.gif\"> Sim</button><button type=\"reset\" class=\"negative\" name=\"reset\" onclick=\"javascript: location.href = 'index.php'\"><img src=\"layoutnovo/dropmenu/b3.gif\"> Não</button></div></form>", 'personagem4', "Mike");
        
    }
    
    display($page, $title);
    
}

function buy() { // Displays a list of available items for purchase.
    
    global $userrow, $numqueries, $conteudo;
    
    $townquery = doquery("SELECT name,itemslist FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
    if (mysqli_num_rows($townquery) != 1) { display("Tentativa de trapaça detectada.<br>", "Error"); }
    $townrow = mysqli_fetch_array($townquery);
    
    $itemslist = explode(",",$townrow["itemslist"]);
    $querystring = "";
    foreach($itemslist as $a=>$b) {
        $querystring .= "id='$b' OR ";
    }
    $querystring = rtrim($querystring, " OR ");
    
	if ($conteudo == ""){$conteudo = personagemgeral("Comprando armas, o seu Poder de Ataque aumentar?. Comprando coletes e bandanas, o seu Poder de Defesa aumentar?.<br /><br />Clique no nome de um item para comprá-lo. Os itens que têm um <font color=\"red\">*</font> ao lado do nome, também possuem outros atributos além do original.", 'personagem1', 'Anari');}
	
    $itemsquery = doquery("SELECT * FROM {{table}} WHERE $querystring ORDER BY id", "items");
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/shop.gif\" /></center></td></tr></table>".$conteudo."<center>Os seguintes itens estão à disposição nessa cidade:<br /><br />\n";
    $page .= "<table width=\"85%\" style=\"border:1px #000000 solid\" cellspacing=\"0\" cellpadding=\"0\">
	<tr bgcolor=\"#452202\"><td><font color=white>Tipo</font></td><td><font color=white>Nome</font><td><font color=white>Atributo</font></td><td><font color=white>Preço</font></td><td><font color=white>Imagem</font></td></tr>";
	include('funcoesinclusas.php');
    while ($itemsrow = mysqli_fetch_array($itemsquery)) {
			//fundo da tabela
			$fundo += 1; $fundo2 = $fundo % 2;
			if ($fundo2 == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
			//fim fundo tabela
        if ($itemsrow["type"] == 1) { $attrib = "Poder de Ataque:"; } else  { $attrib = "Poder de Defesa:"; }
        $page .= "<tr bgcolor=\"$bgcolor\" style=\"border:1px #000000 solid\"><td width=\"4%\">";
        if ($itemsrow["type"] == 1) { $page .= "<img src=\"images/icon_weapon.gif\" alt=\"weapon\" /></td>"; }
        if ($itemsrow["type"] == 2) { $page .= "<img src=\"images/icon_armor.gif\" alt=\"armor\" /></td>"; }
        if ($itemsrow["type"] == 3) { $page .= "<img src=\"images/icon_shield.gif\" alt=\"shield\" /></td>"; }
        if ($userrow["weaponid"] == $itemsrow["id"] || $userrow["armorid"] == $itemsrow["id"] || $userrow["shieldid"] == $itemsrow["id"]) {
			$agorajava = conteudoexplic($itemsrow["id"], $itemsrow["type"], 'idatr', '*');
            $page .= "<td width=\"32%\"><span class=\"light\">".$itemsrow["name"]."</span></td><td width=\"32%\"><span class=\"light\">$attrib ".$itemsrow["attribute"]."</span></td><td width=\"32%\"><span class=\"light\">Já Equipado</span></td><td><div><img src=\"layoutnovo/equipamentos/".$itemsrow["id"].".gif\" style=\"border:2px #e4d094 solid;\" onmouseover=\"$agorajava\" id=\"idatr\" onmouseout=\"fecharexplic();\"></div></td></tr>\n";
        } else {
            if ($itemsrow["special"] != "X") { $specialdot = "<span class=\"highlight\">&#42;</span>"; } else { $specialdot = ""; }
			$agorajava = conteudoexplic($itemsrow["id"], $itemsrow["type"], 'idatr', '*');
            $page .= "<td width=\"32%\"><b><a href=\"index.php?do=buy2:".$itemsrow["id"]."\">".$itemsrow["name"]."</a>$specialdot</b></td><td width=\"32%\">$attrib <b>".$itemsrow["attribute"]."</b></td><td width=\"32%\">Preço: <b>".$itemsrow["buycost"]." Ryou</b></td><td><div><img src=\"layoutnovo/equipamentos/".$itemsrow["id"].".gif\" style=\"border:2px #e4d094 solid;\" onmouseover=\"$agorajava\" id=\"idatr\" onmouseout=\"fecharexplic();\"></div></td></tr>\n";
        }
    }
    $page .= "</table><br />";
    $page .= "Se você mudou de ideia, pode retornar à <a href=\"index.php\">cidade</a>.</center>";
    $title = "Comprar Itens";
    
    display($page, $title);
    
}

function buy2($id) { // Confirm user's intent to purchase item.
    
    global $userrow, $numqueries;
    
    $townquery = doquery("SELECT name,itemslist FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
    if (mysqli_num_rows($townquery) != 1) { display("Tentativa de trapaça detectada.<br>", "Error"); }
    $townrow = mysqli_fetch_array($townquery);
    $townitems = explode(",",$townrow["itemslist"]);
    if (! in_array($id, $townitems)) { display("Tentativa de trapaça detectada.<br>", "Error"); }
    
    $itemsquery = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "items");
    $itemsrow = mysqli_fetch_array($itemsquery);
    
    if ($userrow["gold"] < $itemsrow["buycost"]) { global $conteudo;
	$conteudo = personagemgeral("Você não tem Ryou suficiente para comprar esse item.<br /><br />Você pode retornar à <a href=\"index.php\">cidade</a>, <a href=\"index.php?do=buy\">shop</a>, ou usar os botões de direção para continuar explorando.", 'personagem1', 'Anari');
    buy(); die();}
    
    if ($itemsrow["type"] == 1) {
        if ($userrow["weaponid"] != 0) { 
            $itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["weaponid"]."' LIMIT 1", "items");
            $itemsrow2 = mysqli_fetch_array($itemsquery2);
            $page = personagemgeral("Você está comprando o(a) ".$itemsrow["name"].", então eu vou comprar o seu <b>".$itemsrow2["name"]."</b> por ".ceil($itemsrow2["buycost"]/4)." Ryou. Tudo bem?<br /><br /><center><form action=\"index.php?do=buy3:$id\" method=\"post\"><div class=\"buttons\"><button type=\"submit\" class=\"positive\" name=\"submit\"><img src=\"layoutnovo/dropmenu/b1.gif\"> Sim</button>
<button type=\"reset\" class=\"negative\" name=\"reset\" onclick=\"javascript: location.href = 'index.php?do=buy'\"><img src=\"layoutnovo/dropmenu/b3.gif\"> Não</button>
</div></form>", "personagem1", "Anari");
        } else {
            $page = personagemgeral("Você está comprando o(a) ".$itemsrow["name"].", está tudo certo?<br /><br /><center><form action=\"index.php?do=buy3:$id\" method=\"post\"><div class=\"buttons\"><button type=\"submit\" class=\"positive\" name=\"submit\"><img src=\"layoutnovo/dropmenu/b1.gif\"> Sim</button>
<button type=\"reset\" class=\"negative\" name=\"reset\" onclick=\"javascript: location.href = 'index.php?do=buy'\"><img src=\"layoutnovo/dropmenu/b3.gif\"> Não</button>
</div></form>", "personagem1", "Anari");
        }
    } elseif ($itemsrow["type"] == 2) {
        if ($userrow["armorid"] != 0) { 
            $itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["armorid"]."' LIMIT 1", "items");
            $itemsrow2 = mysqli_fetch_array($itemsquery2);
            $page = personagemgeral("Você está comprando o(a) ".$itemsrow["name"].", então eu vou comprar o seu: <b>".$itemsrow2["name"]."</b> por ".ceil($itemsrow2["buycost"]/4)." Ryou. Está tudo bem?<br /><br /><center><form action=\"index.php?do=buy3:$id\" method=\"post\"><div class=\"buttons\"><button type=\"submit\" class=\"positive\" name=\"submit\"><img src=\"layoutnovo/dropmenu/b1.gif\"> Sim</button>
<button type=\"reset\" class=\"negative\" name=\"reset\" onclick=\"javascript: location.href = 'index.php?do=buy'\"><img src=\"layoutnovo/dropmenu/b3.gif\"> Não</button>
</div></form>", "personagem1", "Anari");
        } else {
            $page = personagemgeral("Você está comprando o(a) ".$itemsrow["name"].", está tudo certo?<br /><br /><center><form action=\"index.php?do=buy3:$id\" method=\"post\"><div class=\"buttons\"><button type=\"submit\" class=\"positive\" name=\"submit\"><img src=\"layoutnovo/dropmenu/b1.gif\"> Sim</button>
<button type=\"reset\" class=\"negative\" name=\"reset\" onclick=\"javascript: location.href = 'index.php?do=buy'\"><img src=\"layoutnovo/dropmenu/b3.gif\"> Não</button>
</div></form>", "personagem1", "Anari");
        }
    } elseif ($itemsrow["type"] == 3) {
        if ($userrow["shieldid"] != 0) { 
            $itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["shieldid"]."' LIMIT 1", "items");
            $itemsrow2 = mysqli_fetch_array($itemsquery2);
            $page = personagemgeral("Você está comprando o(a) ".$itemsrow["name"].", então eu vou comprar o seu: <b>".$itemsrow2["name"]."</b> por ".ceil($itemsrow2["buycost"]/4)." Ryou. Está bem?<br /><br /><center><form action=\"index.php?do=buy3:$id\" method=\"post\"><div class=\"buttons\"><button type=\"submit\" class=\"positive\" name=\"submit\"><img src=\"layoutnovo/dropmenu/b1.gif\"> Sim</button>
<button type=\"reset\" class=\"negative\" name=\"reset\" onclick=\"javascript: location.href = 'index.php?do=buy'\"><img src=\"layoutnovo/dropmenu/b3.gif\"> Não</button>
</div></form>", "personagem1", "Anari");
        } else {
            $page = personagemgeral("Você está comprando o(a) ".$itemsrow["name"].", está bem?<br /><br /><center><form action=\"index.php?do=buy3:$id\" method=\"post\"><div class=\"buttons\"><button type=\"submit\" class=\"positive\" name=\"submit\"><img src=\"layoutnovo/dropmenu/b1.gif\"> Sim</button>
<button type=\"reset\" class=\"negative\" name=\"reset\" onclick=\"javascript: location.href = 'index.php?do=buy'\"><img src=\"layoutnovo/dropmenu/b3.gif\"> Não</button>
</div></form>", "personagem1", "Anari");
        }
    }
    
	global $conteudo;
	$conteudo = $page;
    buy();
   
}

function buy3($id) { // Update user profile with new item & stats.
    
    if (isset($_POST["cancel"])) { header("Location: index.php"); die(); }
    
    global $userrow;
    
    $townquery = doquery("SELECT name,itemslist FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
    if (mysqli_num_rows($townquery) != 1) { display("Tentativa de trapaça detectada.<br>", "Error"); }
    $townrow = mysqli_fetch_array($townquery);
    $townitems = explode(",",$townrow["itemslist"]);
    if (! in_array($id, $townitems)) { display("Tentativa de trapaça detectada.<br>", "Error"); }
    
    $itemsquery = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "items");
    $itemsrow = mysqli_fetch_array($itemsquery);
    
    if ($userrow["gold"] < $itemsrow["buycost"]) { global $conteudo;
	$conteudo = personagemgeral("Você não tem Ryou suficiente para comprar esse item.<br /><br />Você pode retornar à <a href=\"index.php\">cidade</a>, <a href=\"index.php?do=buy\">shop</a>, ou usar os botões de direção para continuar explorando.", 'personagem1', 'Anari');
    buy(); die();}
    
    if ($itemsrow["type"] == 1) { // weapon
    	
    	// Check if they already have an item in the slot.
        if ($userrow["weaponid"] != 0) { 
            $itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["weaponid"]."' LIMIT 1", "items");
            $itemsrow2 = mysqli_fetch_array($itemsquery2);
        } else {
            $itemsrow2 = array("attribute"=>0,"buycost"=>0,"special"=>"X");
        }
        
        // Special item fields.
        $specialchange1 = "";
        $specialchange2 = "";
        if ($itemsrow["special"] != "X") {
            $special = explode(",",$itemsrow["special"]);
            $tochange = $special[0];
            $userrow[$tochange] = $userrow[$tochange] + $special[1];
            $specialchange1 = "$tochange='".$userrow[$tochange]."',";
            if ($tochange == "strength") { $userrow["attackpower"] += $special[1]; }
            if ($tochange == "dexterity") { $userrow["defensepower"] += $special[1]; }
        }
        if ($itemsrow2["special"] != "X") {
            $special2 = explode(",",$itemsrow2["special"]);
            $tochange2 = $special2[0];
            $userrow[$tochange2] = $userrow[$tochange2] - $special2[1];
            $specialchange2 = "$tochange2='".$userrow[$tochange2]."',";
            if ($tochange2 == "strength") { $userrow["attackpower"] -= $special2[1]; }
            if ($tochange2 == "dexterity") { $userrow["defensepower"] -= $special2[1]; }
        }
        
        // New stats.
        $newgold = $userrow["gold"] + ceil($itemsrow2["buycost"]/4) - $itemsrow["buycost"];
        $newattack = $userrow["attackpower"] + $itemsrow["attribute"] - $itemsrow2["attribute"];
        $newid = $itemsrow["id"];
        $newname = $itemsrow["name"];
        $userid = $userrow["id"];
        if ($userrow["currenthp"] > $userrow["maxhp"]) { $newhp = $userrow["maxhp"]; } else { $newhp = $userrow["currenthp"]; }
        if ($userrow["currentmp"] > $userrow["maxmp"]) { $newmp = $userrow["maxmp"]; } else { $newmp = $userrow["currentmp"]; }
        if ($userrow["currenttp"] > $userrow["maxtp"]) { $newtp = $userrow["maxtp"]; } else { $newtp = $userrow["currenttp"]; }
		if ($userrow["currentnp"] > $userrow["maxnp"]) { $newnp = $userrow["maxnp"]; } else { $newnp = $userrow["currentnp"]; }
		if ($userrow["currentep"] > $userrow["maxep"]) { $newep = $userrow["maxep"]; } else { $newep = $userrow["currentep"]; }
        
		//Nova durabilidade.
		$durab = explode(",", $userrow['durabilidade']);
		$novadurab = "-,";
		for ($j = 1; $j < 6; $j++){ 
		if($j == 1){$novadurab .= "X,";}else{$novadurab .= $durab[$j].",";}
		}
		$novadurab .= $durab[6];
				
        // Final update.
        $updatequery = doquery("UPDATE {{table}} SET $specialchange1 $specialchange2 gold='$newgold', attackpower='$newattack', weaponid='$newid', weaponname='$newname', currenthp='$newhp', currentmp='$newmp', currenttp='$newtp',currentnp='$newnp',currentep='$newep',sorte='".$userrow["sorte"]."',agilidade='".$userrow["agilidade"]."',determinacao='".$userrow["determinacao"]."',precisao='".$userrow["precisao"]."',inteligencia='".$userrow["inteligencia"]."',droprate='".$userrow["droprate"]."', durabilidade='$novadurab' WHERE id='$userid' LIMIT 1", "users");
        
    } elseif ($itemsrow["type"] == 2) { // Armor

    	// Check if they already have an item in the slot.
        if ($userrow["armorid"] != 0) { 
            $itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["armorid"]."' LIMIT 1", "items");
            $itemsrow2 = mysqli_fetch_array($itemsquery2);
        } else {
            $itemsrow2 = array("attribute"=>0,"buycost"=>0,"special"=>"X");
        }
        
        // Special item fields.
        $specialchange1 = "";
        $specialchange2 = "";
        if ($itemsrow["special"] != "X") {
            $special = explode(",",$itemsrow["special"]);
            $tochange = $special[0];
            $userrow[$tochange] = $userrow[$tochange] + $special[1];
            $specialchange1 = "$tochange='".$userrow[$tochange]."',";
            if ($tochange == "strength") { $userrow["attackpower"] += $special[1]; }
            if ($tochange == "dexterity") { $userrow["defensepower"] += $special[1]; }
        }
        if ($itemsrow2["special"] != "X") {
            $special2 = explode(",",$itemsrow2["special"]);
            $tochange2 = $special2[0];
            $userrow[$tochange2] = $userrow[$tochange2] - $special2[1];
            $specialchange2 = "$tochange2='".$userrow[$tochange2]."',";
            if ($tochange2 == "strength") { $userrow["attackpower"] -= $special2[1]; }
            if ($tochange2 == "dexterity") { $userrow["defensepower"] -= $special2[1]; }
        }
        
        // New stats.
        $newgold = $userrow["gold"] + ceil($itemsrow2["buycost"]/4) - $itemsrow["buycost"];
        $newdefense = $userrow["defensepower"] + $itemsrow["attribute"] - $itemsrow2["attribute"];
        $newid = $itemsrow["id"];
        $newname = $itemsrow["name"];
        $userid = $userrow["id"];
        if ($userrow["currenthp"] > $userrow["maxhp"]) { $newhp = $userrow["maxhp"]; } else { $newhp = $userrow["currenthp"]; }
        if ($userrow["currentmp"] > $userrow["maxmp"]) { $newmp = $userrow["maxmp"]; } else { $newmp = $userrow["currentmp"]; }
        if ($userrow["currenttp"] > $userrow["maxtp"]) { $newtp = $userrow["maxtp"]; } else { $newtp = $userrow["currenttp"]; }
		if ($userrow["currentnp"] > $userrow["maxnp"]) { $newnp = $userrow["maxnp"]; } else { $newnp = $userrow["currentnp"]; }
		if ($userrow["currentep"] > $userrow["maxep"]) { $newep = $userrow["maxep"]; } else { $newep = $userrow["currentep"]; }
        
		//Nova durabilidade.
		$durab = explode(",", $userrow['durabilidade']);
		$novadurab = "-,";
		for ($j = 1; $j < 6; $j++){ 
		if($j == 2){$novadurab .= "X,";}else{$novadurab .= $durab[$j].",";}
		}
		$novadurab .= $durab[6];
		
        // Final update.
        $updatequery = doquery("UPDATE {{table}} SET $specialchange1 $specialchange2 gold='$newgold', defensepower='$newdefense', armorid='$newid', armorname='$newname', currenthp='$newhp', currentmp='$newmp', currenttp='$newtp', currentnp='$newnp',currentep='$newep',sorte='".$userrow["sorte"]."',agilidade='".$userrow["agilidade"]."',determinacao='".$userrow["determinacao"]."',precisao='".$userrow["precisao"]."',inteligencia='".$userrow["inteligencia"]."',droprate='".$userrow["droprate"]."', durabilidade='$novadurab' WHERE id='$userid' LIMIT 1", "users");

    } elseif ($itemsrow["type"] == 3) { // Shield

    	// Check if they already have an item in the slot.
        if ($userrow["shieldid"] != 0) { 
            $itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["shieldid"]."' LIMIT 1", "items");
            $itemsrow2 = mysqli_fetch_array($itemsquery2);
        } else {
            $itemsrow2 = array("attribute"=>0,"buycost"=>0,"special"=>"X");
        }
        
        // Special item fields.
        $specialchange1 = "";
        $specialchange2 = "";
        if ($itemsrow["special"] != "X") {
            $special = explode(",",$itemsrow["special"]);
            $tochange = $special[0];
            $userrow[$tochange] = $userrow[$tochange] + $special[1];
            $specialchange1 = "$tochange='".$userrow[$tochange]."',";
            if ($tochange == "strength") { $userrow["attackpower"] += $special[1]; }
            if ($tochange == "dexterity") { $userrow["defensepower"] += $special[1]; }
        }
        if ($itemsrow2["special"] != "X") {
            $special2 = explode(",",$itemsrow2["special"]);
            $tochange2 = $special2[0];
            $userrow[$tochange2] = $userrow[$tochange2] - $special2[1];
            $specialchange2 = "$tochange2='".$userrow[$tochange2]."',";
            if ($tochange2 == "strength") { $userrow["attackpower"] -= $special2[1]; }
            if ($tochange2 == "dexterity") { $userrow["defensepower"] -= $special2[1]; }
        }
        
        // New stats.
        $newgold = $userrow["gold"] + ceil($itemsrow2["buycost"]/4) - $itemsrow["buycost"];
        $newdefense = $userrow["defensepower"] + $itemsrow["attribute"] - $itemsrow2["attribute"];
        $newid = $itemsrow["id"];
        $newname = $itemsrow["name"];
        $userid = $userrow["id"];
        if ($userrow["currenthp"] > $userrow["maxhp"]) { $newhp = $userrow["maxhp"]; } else { $newhp = $userrow["currenthp"]; }
        if ($userrow["currentmp"] > $userrow["maxmp"]) { $newmp = $userrow["maxmp"]; } else { $newmp = $userrow["currentmp"]; }
        if ($userrow["currenttp"] > $userrow["maxtp"]) { $newtp = $userrow["maxtp"]; } else { $newtp = $userrow["currenttp"]; }
		if ($userrow["currentnp"] > $userrow["maxnp"]) { $newnp = $userrow["maxnp"]; } else { $newnp = $userrow["currentnp"]; }
		if ($userrow["currentep"] > $userrow["maxep"]) { $newep = $userrow["maxep"]; } else { $newep = $userrow["currentep"]; }
        
		//Nova durabilidade.
		$durab = explode(",", $userrow['durabilidade']);
		$novadurab = "-,";
		for ($j = 1; $j < 6; $j++){ 
		if($j == 3){$novadurab .= "X,";}else{$novadurab .= $durab[$j].",";}
		}
		$novadurab .= $durab[6];
		
        // Final update.
        $updatequery = doquery("UPDATE {{table}} SET $specialchange1 $specialchange2 gold='$newgold', defensepower='$newdefense', shieldid='$newid', shieldname='$newname', currenthp='$newhp', currentmp='$newmp', currenttp='$newtp', currentnp='$newnp',currentep='$newep',sorte='".$userrow["sorte"]."',agilidade='".$userrow["agilidade"]."',determinacao='".$userrow["determinacao"]."',precisao='".$userrow["precisao"]."',inteligencia='".$userrow["inteligencia"]."',droprate='".$userrow["droprate"]."', durabilidade='$novadurab' WHERE id='$userid' LIMIT 1", "users");        
    
    }
    
	global $conteudo;
	$conteudo = personagemgeral('O negócio foi realizado com sucesso. Muito obrigado(a) por comprar esse item!', 'personagem1', 'Anari');
    buy();

}

function maps() { // List maps the user can buy.
    
    global $userrow, $numqueries, $conteudo;
    
    $mappedtowns = explode(",",$userrow["towns"]);
    
	if ($conteudo == ""){$conteudo = personagemgeral("Ao comprar um mapa, você poderá viajar para essa cidade sempre que quiser pelo menu <b>Viajar</b>, e para viajar, é necessário possuir TP(Pontos de Viagem).", 'personagem2', 'Hanashi');}
	
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/comprarmapas.gif\" /></center></td></tr></table>".$conteudo;
    $page .= "<center>Clique no nome de uma vila/cidade para comprar seu mapa.<br /><br />\n";
	$page .= "<table width=\"90%\" style=\"border:1px #000000 solid\" cellspacing=\"0\" cellpadding=\"0\">
	<tr bgcolor=\"#452202\"><td colspan=\"4\"><font color=white><center>Comprar Mapas</center></font></td></tr>";
  

	
	
    $townquery = doquery("SELECT * FROM {{table}} ORDER BY id", "towns");
		    while ($townrow = mysqli_fetch_array($townquery)) {
			//fundo da tabela
			$fundo += 1; $fundo2 = $fundo % 2;
			if ($fundo2 == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
			//fim fundo tabela
        
        if ($townrow["latitude"] >= 0) { $latitude = $townrow["latitude"] . "N,"; } else { $latitude = ($townrow["latitude"]*-1) . "S,"; }
        if ($townrow["longitude"] >= 0) { $longitude = $townrow["longitude"] . "E"; } else { $longitude = ($townrow["longitude"]*-1) . "W"; }
        
        $mapped = false;
        foreach($mappedtowns as $a => $b) {
            if ($b == $townrow["id"]) { $mapped = true; }
			
        }
		
        if ($mapped == false) {
		if ($townrow["id"] < 8){//menos akatsuki e maiores
            $page .= "<tr bgcolor=\"$bgcolor\"><td width=\"25%\"><a href=\"index.php?do=maps2:".$townrow["id"]."\">".$townrow["name"]."</a></td><td width=\"25%\">Preço: ".$townrow["mapprice"]." gold</td><td width=\"50%\" colspan=\"2\">Compre para revelar mais detalhes.</td></tr>\n";
       } } else {
            $page .= "<tr bgcolor=\"$bgcolor\"><td width=\"25%\"><span class=\"light\">".$townrow["name"]."</span></td><td width=\"25%\"><span class=\"light\">Já foi comprado.</span></td><td width=\"35%\"><span class=\"light\">Localização: $latitude $longitude</span></td><td width=\"15%\"><span class=\"light\">TP: ".$townrow["travelpoints"]."</span></td></tr>\n";
       }
        
    }
    
    $page .= "</table><br />\n";
    $page .= "Se você mudou de ideia, você pode retornar à <a href=\"index.php\">cidade</a>.</center>";
    
    display($page, "Comprar Mapas");
    
}

function maps2($id) { // Confirm user's intent to purchase map.
    
    global $userrow, $numqueries, $conteudo;
    
	//menos akatsuki e maiores
	if ($id > 7) {header('Location: ./index.php?do=maps');die(); }
	
    $townquery = doquery("SELECT name,mapprice FROM {{table}} WHERE id='$id' LIMIT 1", "towns");
    $townrow = mysqli_fetch_array($townquery);
    
    if ($userrow["gold"] < $townrow["mapprice"]) { global $conteudo;
	$conteudo = personagemgeral("Você não tem Ryou suficiente para comprar esse mapa.<br /><br />Você pode retornar à <a href=\"index.php\">cidade</a>, <a href=\"index.php?do=maps\">shop</a>, ou usar os botões de direção para continuar explorando.", "personagem2", "Hanashi"); maps(); die();}
    
    $conteudo = personagemgeral("Você está comprando o mapa de ".$townrow["name"].". Tudo certo?<br /><br /><form action=\"index.php?do=maps3:$id\" method=\"post\"><div class=\"buttons\"><button type=\"submit\" class=\"positive\" name=\"submit\"><img src=\"layoutnovo/dropmenu/b1.gif\"> Sim</button>
<button type=\"reset\" class=\"negative\" name=\"reset\" onclick=\"javascript: location.href = 'index.php?do=maps'\"><img src=\"layoutnovo/dropmenu/b3.gif\"> Não</button>
</div></form>", "personagem2", "Hanashi");
    maps();

}

function maps3($id) { // Add new map to user's profile.
    
    if (isset($_POST["cancel"])) { header("Location: index.php"); die(); }
    
    global $userrow, $numqueries;
    
    $townquery = doquery("SELECT name,mapprice FROM {{table}} WHERE id='$id' LIMIT 1", "towns");
    $townrow = mysqli_fetch_array($townquery);
    
    if ($userrow["gold"] < $townrow["mapprice"]) { global $conteudo; 
	$conteudo = personagemgeral("Você não tem dinheiro suficiente para comprar esse mapa.<br /><br />Você pode retornar à <a href=\"index.php\">cidade</a>, <a href=\"index.php?do=maps\">shop</a>, ou usar os botões de direção para continuar explorando.", "personagem2", "Hanashi"); maps(); die();}
    
    $mappedtowns = $userrow["towns"].",$id";
    $newgold = $userrow["gold"] - $townrow["mapprice"];
    
    $updatequery = doquery("UPDATE {{table}} SET towns='$mappedtowns',gold='$newgold' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
    
	global $conteudo; 
	$conteudo = personagemgeral("Obrigado por comprar esse mapa.<br /><br />Você pode retornar à <a href=\"index.php\">cidade</a>, <a href=\"index.php?do=maps\">shop</a>, ou usar os botões de direção para continuar explorando.", "personagem2", "Hanashi"); maps(); die();
    
}

function travelto($id, $usepoints=true) { // Send a user to a town from the Travel To menu.
    
    global $userrow, $numqueries;
    
    if ($userrow["currentaction"] == "Fighting") { header("Location: index.php?do=fight"); die(); }
    
    $townquery = doquery("SELECT name,travelpoints,latitude,longitude FROM {{table}} WHERE id='$id' LIMIT 1", "towns");
    $townrow = mysqli_fetch_array($townquery);
    
    if ($usepoints==true) { 
        if ($userrow["currenttp"] < $townrow["travelpoints"]) { 
			
			header('Location: ./index.php?conteudo=Você não tem TP suficiente para viajar até lá. Por favor tente novamente quando você tiver mais TP.'); die();
         
        }
        $mapped = explode(",",$userrow["towns"]);
        if (!in_array($id, $mapped)) { display("Tentativa de trapaça detectada.<br>", "Error"); }
    }
    
    if (($userrow["latitude"] == $townrow["latitude"]) && ($userrow["longitude"] == $townrow["longitude"])) { display("Você já está nessa cidade. <a href=\"index.php\">Clique aqui</a> para retornar para a janela principal da cidade.<br>", "Viajar Para"); die(); }
    
    if ($usepoints == true) { $newtp = $userrow["currenttp"] - $townrow["travelpoints"]; } else { $newtp = $userrow["currenttp"]; }
    
    $newlat = $townrow["latitude"];
    $newlon = $townrow["longitude"];
    $newid = $userrow["id"];
    
    // If they got here by exploring, add this town to their map.
    $mapped = explode(",",$userrow["towns"]);
    $town = false;
    foreach($mapped as $a => $b) {
        if ($b == $id) { $town = true; }
    }
    $mapped = implode(",",$mapped);
    if ($town == false) { 
        $mapped .= ",$id";
        $mapped = "towns='".$mapped."',";
    } else { 
        $mapped = "towns='".$mapped."',";
    }
    
    $updatequery = doquery("UPDATE {{table}} SET currentaction='In Town',$mapped currenttp='$newtp',latitude='$newlat',longitude='$newlon' WHERE id='$newid' LIMIT 1", "users");
    
    $page = "Você chegou a(o) ".$townrow["name"].". Agora você pode <a href=\"index.php\">entrar nessa cidade</a>.<br>";
    display($page, "Cidade");
    
}
    

?>