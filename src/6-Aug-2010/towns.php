<?php // towns.php :: Handles all actions you can do in town.
$userrow = checkcookies();
	if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode utilizar uma cidade enquanto estiver em um Duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }
	
function inn() { // Staying at the inn resets all expendable stats to their max values.
    
	
		
		
    global $userrow, $numqueries;

    $townquery = doquery("SELECT name,innprice FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
    if (mysql_num_rows($townquery) != 1) { display("Tentativa de trapaça detectada.", "Error"); }
    $townrow = mysql_fetch_array($townquery);
    
    if ($userrow["gold"] < $townrow["innprice"]) { display("Você não tem Ryou suficiente para passar a noite numa pousada.<br /><br />Você pode retornar para a <a href=\"index.php\">cidade</a>, ou usar um dos botões de direção para continuar explorando.", "Pousada"); die(); }
    
    if (isset($_POST["submit"])) {
        
        $newgold = $userrow["gold"] - $townrow["innprice"];
        $query = doquery("UPDATE {{table}} SET gold='$newgold',currenthp='".$userrow["maxhp"]."',currentmp='".$userrow["maxmp"]."',currenttp='".$userrow["maxtp"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
        $title = "Pousada";
        $page = "Você acordou se sentindo regenarado e pronto pra ação.<br /><br />Você pode retornar para a <a href=\"index.php\">cidade</a>, ou usar um dos botões de direção para começar a explorar.";
        
    } elseif (isset($_POST["cancel"])) {
        
        header("Location: index.php"); die();
         
    } else {
        
        $title = "Pousada";
        $page = "Descansar numa pousada vai encher o seu HP, CH, e TP ao seu máximo.<br /><br />\n";
        $page .= "Uma noite de sono na pousada vai te custar: <b>" . $townrow["innprice"] . " Ryou</b>. Está tudo bem?<br /><br />\n";
        $page .= "<form action=\"index.php?do=inn\" method=\"post\">\n";
        $page .= "<input type=\"submit\" name=\"submit\" value=\"Sim\" /> <input type=\"submit\" name=\"cancel\" value=\"Não\" />\n";
        $page .= "</form>\n";
        
    }
    
    display($page, $title);
    
}

function buy() { // Displays a list of available items for purchase.
    
    global $userrow, $numqueries;
    
    $townquery = doquery("SELECT name,itemslist FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
    if (mysql_num_rows($townquery) != 1) { display("Tentativa de trapaça detectada.", "Error"); }
    $townrow = mysql_fetch_array($townquery);
    
    $itemslist = explode(",",$townrow["itemslist"]);
    $querystring = "";
    foreach($itemslist as $a=>$b) {
        $querystring .= "id='$b' OR ";
    }
    $querystring = rtrim($querystring, " OR ");
    
    $itemsquery = doquery("SELECT * FROM {{table}} WHERE $querystring ORDER BY id", "items");
    $page = "Comprando armas, o seu Poder de Ataque aumentará. Comprando coletes e bandanas, o seu Poder de Defesa aumentará.<br /><br />Clique no nome de um item para comprá-lo. Você pode ver a tabela completa de itens, com todos os atributos, uma vez que aqui no shop só mostra o Poder de Ataque, ou poder de Defesa, clicando <a href=\"http://www.nigeru.com/narutorpg/help_items.php\" target=\"_blank\">aqui</a>.<br /><br />Os seguintes itens estão à disposição nessa cidade:<br /><br />\n";
    $page .= "<table width=\"80%\">\n";
    while ($itemsrow = mysql_fetch_array($itemsquery)) {
        if ($itemsrow["type"] == 1) { $attrib = "Poder de Ataque:"; } else  { $attrib = "Poder de Defesa:"; }
        $page .= "<tr><td width=\"4%\">";
        if ($itemsrow["type"] == 1) { $page .= "<img src=\"images/icon_weapon.gif\" alt=\"weapon\" /></td>"; }
        if ($itemsrow["type"] == 2) { $page .= "<img src=\"images/icon_armor.gif\" alt=\"armor\" /></td>"; }
        if ($itemsrow["type"] == 3) { $page .= "<img src=\"images/icon_shield.gif\" alt=\"shield\" /></td>"; }
        if ($userrow["weaponid"] == $itemsrow["id"] || $userrow["armorid"] == $itemsrow["id"] || $userrow["shieldid"] == $itemsrow["id"]) {
            $page .= "<td width=\"32%\"><span class=\"light\">".$itemsrow["name"]."</span></td><td width=\"32%\"><span class=\"light\">$attrib ".$itemsrow["attribute"]."</span></td><td width=\"32%\"><span class=\"light\">Já comprado</span></td></tr>\n";
        } else {
            if ($itemsrow["special"] != "X") { $specialdot = "<span class=\"highlight\">&#42;</span>"; } else { $specialdot = ""; }
            $page .= "<td width=\"32%\"><b><a href=\"index.php?do=buy2:".$itemsrow["id"]."\">".$itemsrow["name"]."</a>$specialdot</b></td><td width=\"32%\">$attrib <b>".$itemsrow["attribute"]."</b></td><td width=\"32%\">Preço: <b>".$itemsrow["buycost"]." Ryou</b></td></tr>\n";
        }
    }
    $page .= "</table><br />\n";
    $page .= "Se você mudou de ideia, pode retornar à <a href=\"index.php\">cidade</a>.\n";
    $title = "Comprar Itens";
    
    display($page, $title);
    
}

function buy2($id) { // Confirm user's intent to purchase item.
    
    global $userrow, $numqueries;
    
    $townquery = doquery("SELECT name,itemslist FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
    if (mysql_num_rows($townquery) != 1) { display("Tentativa de trapaça detectada.", "Error"); }
    $townrow = mysql_fetch_array($townquery);
    $townitems = explode(",",$townrow["itemslist"]);
    if (! in_array($id, $townitems)) { display("Tentativa de trapaça detectada.", "Error"); }
    
    $itemsquery = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
    
    if ($userrow["gold"] < $itemsrow["buycost"]) { display("Você não tem Ryou suficiente para comprar esse item.<br /><br />Você pode retornar à <a href=\"index.php\">cidade</a>, <a href=\"index.php?do=buy\">shop</a>, ou usar os botões de direção para continuar explorando.", "Comprar Itens"); die(); }
    
    if ($itemsrow["type"] == 1) {
        if ($userrow["weaponid"] != 0) { 
            $itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["weaponid"]."' LIMIT 1", "items");
            $itemsrow2 = mysql_fetch_array($itemsquery2);
            $page = "Você está comprando: ".$itemsrow["name"].", então eu vou comprar o seu ".$itemsrow2["name"]." por ".ceil($itemsrow2["buycost"]/4)." Ryou. Tudo bem?<br /><br /><form action=\"index.php?do=buy3:$id\" method=\"post\"><input type=\"submit\" name=\"submit\" value=\"Sim\" /> <input type=\"submit\" name=\"cancel\" value=\"Não\" /></form>";
        } else {
            $page = "Você está comprando: ".$itemsrow["name"].", está tudo certo?<br /><br /><form action=\"index.php?do=buy3:$id\" method=\"post\"><input type=\"submit\" name=\"submit\" value=\"Sim\" /> <input type=\"submit\" name=\"cancel\" value=\"Não\" /></form>";
        }
    } elseif ($itemsrow["type"] == 2) {
        if ($userrow["armorid"] != 0) { 
            $itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["armorid"]."' LIMIT 1", "items");
            $itemsrow2 = mysql_fetch_array($itemsquery2);
            $page = "Você está comprando: ".$itemsrow["name"].", então eu vou comprar: ".$itemsrow2["name"]." por ".ceil($itemsrow2["buycost"]/4)." Ryou. Está tudo bem?<br /><br /><form action=\"index.php?do=buy3:$id\" method=\"post\"><input type=\"submit\" name=\"submit\" value=\"Sim\" /> <input type=\"submit\" name=\"cancel\" value=\"Não\" /></form>";
        } else {
            $page = "Você está comprando: ".$itemsrow["name"].", está tudo certo?<br /><br /><form action=\"index.php?do=buy3:$id\" method=\"post\"><input type=\"submit\" name=\"submit\" value=\"Sim\" /> <input type=\"submit\" name=\"cancel\" value=\"Não\" /></form>";
        }
    } elseif ($itemsrow["type"] == 3) {
        if ($userrow["shieldid"] != 0) { 
            $itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["shieldid"]."' LIMIT 1", "items");
            $itemsrow2 = mysql_fetch_array($itemsquery2);
            $page = "Você está comprando: ".$itemsrow["name"].", então eu vou comprar: ".$itemsrow2["name"]." por ".ceil($itemsrow2["buycost"]/4)." Ryou. Está bem?<br /><br /><form action=\"index.php?do=buy3:$id\" method=\"post\"><input type=\"submit\" name=\"submit\" value=\"Sim\" /> <input type=\"submit\" name=\"cancel\" value=\"Não\" /></form>";
        } else {
            $page = "Você está comprando: ".$itemsrow["name"].", está bem?<br /><br /><form action=\"index.php?do=buy3:$id\" method=\"post\"><input type=\"submit\" name=\"submit\" value=\"Sim\" /> <input type=\"submit\" name=\"cancel\" value=\"Não\" /></form>";
        }
    }
    
    $title = "Comprar Itens";
    display($page, $title);
   
}

function buy3($id) { // Update user profile with new item & stats.
    
    if (isset($_POST["cancel"])) { header("Location: index.php"); die(); }
    
    global $userrow;
    
    $townquery = doquery("SELECT name,itemslist FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
    if (mysql_num_rows($townquery) != 1) { display("Tentativa de trapaça detectada.", "Error"); }
    $townrow = mysql_fetch_array($townquery);
    $townitems = explode(",",$townrow["itemslist"]);
    if (! in_array($id, $townitems)) { display("Tentativa de trapaça detectada.", "Error"); }
    
    $itemsquery = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
    
    if ($userrow["gold"] < $itemsrow["buycost"]) { display("Você não tem Ryou suficiente para comprar esse item.<br /><br />Você pode voltar à <a href=\"index.php\">cidade</a>, <a href=\"index.php?do=buy\">shop</a>, ou usar os botões de direção para continuar explorando.", "Comprar Itens"); die(); }
    
    if ($itemsrow["type"] == 1) { // weapon
    	
    	// Check if they already have an item in the slot.
        if ($userrow["weaponid"] != 0) { 
            $itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["weaponid"]."' LIMIT 1", "items");
            $itemsrow2 = mysql_fetch_array($itemsquery2);
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
        
        // Final update.
        $updatequery = doquery("UPDATE {{table}} SET $specialchange1 $specialchange2 gold='$newgold', attackpower='$newattack', weaponid='$newid', weaponname='$newname', currenthp='$newhp', currentmp='$newmp', currenttp='$newtp' WHERE id='$userid' LIMIT 1", "users");
        
    } elseif ($itemsrow["type"] == 2) { // Armor

    	// Check if they already have an item in the slot.
        if ($userrow["armorid"] != 0) { 
            $itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["armorid"]."' LIMIT 1", "items");
            $itemsrow2 = mysql_fetch_array($itemsquery2);
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
        
        // Final update.
        $updatequery = doquery("UPDATE {{table}} SET $specialchange1 $specialchange2 gold='$newgold', defensepower='$newdefense', armorid='$newid', armorname='$newname', currenthp='$newhp', currentmp='$newmp', currenttp='$newtp' WHERE id='$userid' LIMIT 1", "users");

    } elseif ($itemsrow["type"] == 3) { // Shield

    	// Check if they already have an item in the slot.
        if ($userrow["shieldid"] != 0) { 
            $itemsquery2 = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["shieldid"]."' LIMIT 1", "items");
            $itemsrow2 = mysql_fetch_array($itemsquery2);
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
        
        // Final update.
        $updatequery = doquery("UPDATE {{table}} SET $specialchange1 $specialchange2 gold='$newgold', defensepower='$newdefense', shieldid='$newid', shieldname='$newname', currenthp='$newhp', currentmp='$newmp', currenttp='$newtp' WHERE id='$userid' LIMIT 1", "users");        
    
    }
    
    display("Obrigado por comprar esse item.<br /><br />Você pode retornar à <a href=\"index.php\">cidade</a>, <a href=\"index.php?do=buy\">shop</a>, ou usar os botões de direção pra continuar explorando.", "Buy Items");

}

function maps() { // List maps the user can buy.
    
    global $userrow, $numqueries;
    
    $mappedtowns = explode(",",$userrow["towns"]);
    
    $page = "Comprar mapas irá colocar a cidade no seu menu <b>Viajar Para</b>, e ao viajar, isso irá te custar TP.<br /><br />\n";
    $page .= "Clique no nome de uma cidade para comprar seu mapa.<br /><br />\n";
    $page .= "<table width=\"90%\">\n";
    
    $townquery = doquery("SELECT * FROM {{table}} ORDER BY id", "towns");
		    while ($townrow = mysql_fetch_array($townquery)) {
        
        if ($townrow["latitude"] >= 0) { $latitude = $townrow["latitude"] . "N,"; } else { $latitude = ($townrow["latitude"]*-1) . "S,"; }
        if ($townrow["longitude"] >= 0) { $longitude = $townrow["longitude"] . "E"; } else { $longitude = ($townrow["longitude"]*-1) . "W"; }
        
        $mapped = false;
        foreach($mappedtowns as $a => $b) {
            if ($b == $townrow["id"]) { $mapped = true; }
			
        }
		
        if ($mapped == false) {
		if ($townrow["id"] < 8){//menos akatsuki e maiores
            $page .= "<tr><td width=\"25%\"><a href=\"index.php?do=maps2:".$townrow["id"]."\">".$townrow["name"]."</a></td><td width=\"25%\">Preço: ".$townrow["mapprice"]." gold</td><td width=\"50%\" colspan=\"2\">Compre para revelar mais detalhes.</td></tr>\n";
       } } else {
            $page .= "<tr><td width=\"25%\"><span class=\"light\">".$townrow["name"]."</span></td><td width=\"25%\"><span class=\"light\">Já foi comprado.</span></td><td width=\"35%\"><span class=\"light\">Localização: $latitude $longitude</span></td><td width=\"15%\"><span class=\"light\">TP: ".$townrow["travelpoints"]."</span></td></tr>\n";
       }
        
    }
    
    $page .= "</table><br />\n";
    $page .= "Se você mudou de ideia, você pode retornar à <a href=\"index.php\">cidade</a>.\n";
    
    display($page, "Comprar Mapas");
    
}

function maps2($id) { // Confirm user's intent to purchase map.
    
    global $userrow, $numqueries;
    
	//menos akatsuki e maiores
	if ($id > 7) {header('Location: /narutorpg/index.php?do=maps');die(); }
	
    $townquery = doquery("SELECT name,mapprice FROM {{table}} WHERE id='$id' LIMIT 1", "towns");
    $townrow = mysql_fetch_array($townquery);
    
    if ($userrow["gold"] < $townrow["mapprice"]) { display("Você não tem Ryou suficiente para comprar esse mapa.<br /><br />Você pode retornar à <a href=\"index.php\">cidade</a>, <a href=\"index.php?do=maps\">shop</a>, ou usar os botões de direção para continuar explorando.", "Comprar Mapas"); die(); }
    
    $page = "Você está comprando o mapa de ".$townrow["name"].". Tudo certo?<br /><br /><form action=\"index.php?do=maps3:$id\" method=\"post\"><input type=\"submit\" name=\"submit\" value=\"Sim\" /> <input type=\"submit\" name=\"cancel\" value=\"Não\" /></form>";
    
    display($page, "Comprar Mapas");
    
}

function maps3($id) { // Add new map to user's profile.
    
    if (isset($_POST["cancel"])) { header("Location: index.php"); die(); }
    
    global $userrow, $numqueries;
    
    $townquery = doquery("SELECT name,mapprice FROM {{table}} WHERE id='$id' LIMIT 1", "towns");
    $townrow = mysql_fetch_array($townquery);
    
    if ($userrow["gold"] < $townrow["mapprice"]) { display("Você não tem dinheiro suficiente para comprar esse mapa.<br /><br />Você pode retornar à <a href=\"index.php\">cidade</a>, <a href=\"index.php?do=maps\">shop</a>, ou usar os botões de direção para continuar explorando.", "Comprar Mapas"); die(); }
    
    $mappedtowns = $userrow["towns"].",$id";
    $newgold = $userrow["gold"] - $townrow["mapprice"];
    
    $updatequery = doquery("UPDATE {{table}} SET towns='$mappedtowns',gold='$newgold' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
    
    display("Obrigado por comprar esse mapa.<br /><br />Você pode retornar à <a href=\"index.php\">cidade</a>, <a href=\"index.php?do=maps\">shop</a>, ou usar os botões de direção para continuar explorando.", "Comprar Mapas");
    
}

function travelto($id, $usepoints=true) { // Send a user to a town from the Travel To menu.
    
    global $userrow, $numqueries;
    
    if ($userrow["currentaction"] == "Fighting") { header("Location: index.php?do=fight"); die(); }
    
    $townquery = doquery("SELECT name,travelpoints,latitude,longitude FROM {{table}} WHERE id='$id' LIMIT 1", "towns");
    $townrow = mysql_fetch_array($townquery);
    
    if ($usepoints==true) { 
        if ($userrow["currenttp"] < $townrow["travelpoints"]) { 
            display("Você não tem TP suficiente para viajar até lá. Por favor volte quando você tiver mais TP.", "Viajar Para"); die(); 
        }
        $mapped = explode(",",$userrow["towns"]);
        if (!in_array($id, $mapped)) { display("Tentativa de trapaça detectada.", "Error"); }
    }
    
    if (($userrow["latitude"] == $townrow["latitude"]) && ($userrow["longitude"] == $townrow["longitude"])) { display("Você já está nessa cidade. <a href=\"index.php\">Clique aqui</a> para retornar para a janela principal da cidade.", "Viajar Para"); die(); }
    
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
    
    $page = "Você viajou para ".$townrow["name"].". Agora você pode <a href=\"index.php\">entrar nessa cidade</a>.";
    display($page, "Viajar Para");
    
}
    

?>