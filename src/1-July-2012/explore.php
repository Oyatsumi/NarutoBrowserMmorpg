<?php // explore.php :: Handles all map exploring, chances to fight, etc.

//aumentar np à medida que o personagem anda no mapa.
global $userrow;
$userrow["currentnp"] += 1;
if ($userrow["currentnp"] > $userrow["maxnp"]){$userrow["currentnp"] = $userrow["maxnp"];}
$updatequery = doquery("UPDATE {{table}} SET currentnp='".$userrow["currentnp"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");

if (!function_exists('move')){
function move() {
    
    global $userrow, $controlrow;
    
    if ($userrow["currentaction"] == "Fighting") { header("Location: index.php?do=fight"); die(); }
    
    $latitude = $userrow["latitude"];
    $longitude = $userrow["longitude"];
    if (isset($_POST["north"])) { $latitude++; if ($latitude > $controlrow["gamesize"]) { $latitude = $controlrow["gamesize"]; } }
    if (isset($_POST["south"])) { $latitude--; if ($latitude < ($controlrow["gamesize"]*-1)) { $latitude = ($controlrow["gamesize"]*-1); } }
    if (isset($_POST["east"])) { $longitude++; if ($longitude > $controlrow["gamesize"]) { $longitude = $controlrow["gamesize"]; } }
    if (isset($_POST["west"])) { $longitude--; if ($longitude < ($controlrow["gamesize"]*-1)) { $longitude = ($controlrow["gamesize"]*-1); } }
    
    $townquery = doquery("SELECT id FROM {{table}} WHERE latitude='$latitude' AND longitude='$longitude' LIMIT 1", "towns");
    if (mysql_num_rows($townquery) > 0) {
        $townrow = mysql_fetch_array($townquery);
        include('towns.php');
        travelto($townrow["id"], false);
        die();
    }
    
    $chancetofight = rand(1,4);
    if ($chancetofight == 1) { 
        $action = "currentaction='Fighting', currentfight='1',";
    } else {
        $action = "currentaction='Exploring',";
    }

    
    $updatequery = doquery("UPDATE {{table}} SET $action latitude='$latitude', longitude='$longitude', dropcode='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
    header("Location: index.php");
    
}}














if (!function_exists('andar')){
function andar($lat, $long) { //move com o script de andar
    
    global $userrow, $controlrow;
    
    if ($userrow["currentaction"] == "Fighting") { header("Location: index.php?do=fight"); die(); }
    
    $latitude = $userrow["latitude"];
    $longitude = $userrow["longitude"];
	
	//operações pra saber que lado vai
	if ($long == $longitude){$acao = "";}
	if ($lat == $latitude){$acao = "";}
	if ($lat < $latitude) {$acao = "south";}
	if ($lat > $latitude){$acao = "north";}
	if ($long > $longitude){$acao = "east";}
	if ($long < $longitude){$acao = "weast";}
	
	
	if ($acao == "") { header("Location: ./index.php?conteudo=".utf8_decode('Você chegou em seu destino.').""); die(); }
    elseif ($acao == "north") { $latitude++; if ($latitude > $controlrow["gamesize"]) { $latitude = $controlrow["gamesize"]; } }
    elseif ($acao == "south") { $latitude--; if ($latitude < ($controlrow["gamesize"]*-1)) { $latitude = ($controlrow["gamesize"]*-1); } }
    elseif ($acao == "east") { $longitude++; if ($longitude > $controlrow["gamesize"]) { $longitude = $controlrow["gamesize"]; } }
    elseif ($acao == "weast") { $longitude--; if ($longitude < ($controlrow["gamesize"]*-1)) { $longitude = ($controlrow["gamesize"]*-1); } }
	
	
    
    $townquery = doquery("SELECT id FROM {{table}} WHERE latitude='$latitude' AND longitude='$longitude' LIMIT 1", "towns");
    if (mysql_num_rows($townquery) > 0) {
        $townrow = mysql_fetch_array($townquery);
        include('towns.php');
        travelto($townrow["id"], false);
        die();
    }
    
    $chancetofight = rand(1,4);
    if ($chancetofight == 1) { 
        $action = "currentaction='Fighting', currentfight='1',";
    } else {
        $action = "currentaction='Exploring',";
    }

    
    $updatequery = doquery("UPDATE {{table}} SET $action latitude='$latitude', longitude='$longitude', dropcode='0' WHERE id='".$userrow["id"]."' LIMIT 1", "users");

	header("Location: index.php?re=true&latitude=".$lat."&longitude=".$long."");
	die();

    
}}

?>