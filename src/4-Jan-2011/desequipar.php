<?php // bp.

global $deletar, $qual, $userrow;

if ($deletar == false){
include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();
}

if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }
if (($userrow["currentaction"] == "Fighting") && ($deletar == false)) {header('Location: /narutorpg/index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die();}

if ($qual == ""){
$qual = $_GET['qual'];}


//restrição e finalização se a mochila estiver cheia.
$bpcertonum = 0;
for ($i = 4;$i > 0; $i--){
	if ($userrow["bp".$i] == "None"){
		$bpcertonum = $i;
	}
	if (($i == 1) && ($bpcertonum == 0) && ($deletar == false)){
		header('Location: /narutorpg/index.php?conteudo=Sua mochila já está cheia.'); die();
	}
}

//finalizando se não houver item.
if (($qual == 1)&&($userrow["weaponname"] == "None")){header('Location: /narutorpg/'.$userrow["pagina"]);die();}
elseif (($qual == 2)&&($userrow["armorname"] == "None")){header('Location: /narutorpg/'.$userrow["pagina"]);die();}
elseif (($qual == 3)&&($userrow["shieldname"] == "None")){header('Location: /narutorpg/index.php'); die();}
elseif (($qual == 4)&&($userrow["slot1name"] == "None")){header('Location: /narutorpg/'.$userrow["pagina"]);die();}
elseif (($qual == 5)&&($userrow["slot2name"] == "None")){header('Location: /narutorpg/'.$userrow["pagina"]);die();}
elseif (($qual == 6)&&($userrow["slot3name"] == "None")){header('Location: /narutorpg/'.$userrow["pagina"]);die();}


//se qual for menor que 1 e maior que 6
if ($qual > 6) {header('Location: /narutorpg/index.php'); die();}
if ($qual < 1) {header('Location: /narutorpg/index.php'); die();}


//verificando o qual e direcionando à cada bloco de código

if ($qual == 1){//para arma
	$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["weaponid"]."' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
	$userrow["attackpower"] -= $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$userrow["weaponname"]);
	if ($variavelpcontinuar[1] != ""){
		$userrow["attackpower"] -= $variavelpcontinuar[1]*20;//15 - de bonus
	}
	if ($itemsrow["special"] != "X"){
	$atributo2 = explode(",",$itemsrow["special"]);
	if ($atributo2[0] == "strength") { $userrow["attackpower"] -= $atributo2[1]; }
    elseif ($atributo2[0] == "dexterity") { $userrow["defensepower"] -= $atributo2[1]; }
	$userrow[$atributo2[0]] -= $atributo2[1];
	}	
	//fim dos bonus
	
	//dividir durabilidade.
	$variaveldur = explode(",",$userrow["durabilidade"]);
	
	//adicionar na bp
	if ($deletar == false){
	$userrow["bp".$bpcertonum] = $userrow["weaponname"].",".$userrow["weaponid"].",".$qual.",".$variaveldur[$qual];
	}
	
	//ajeitar durabilidade
	$variaveldur[$qual] = "X";
	$userrow["durabilidade"] = $variaveldur[0].",".$variaveldur[1].",".$variaveldur[2].",".$variaveldur[3].",".$variaveldur[4].",".	$variaveldur[5].",".$variaveldur[6];
	
	//retirar item
	$userrow["weaponname"] = "None";
	$userrow["weaponid"] = 0;
}













elseif ($qual == 2){//para colete
	$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["armorid"]."' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
	$userrow["defensepower"] -= $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$userrow["armorname"]);
	if ($variavelpcontinuar[1] != ""){
		$userrow["defensepower"] -= $variavelpcontinuar[1]*20;//15 - de bonus
	}
	if ($itemsrow["special"] != "X"){
	$atributo2 = explode(",",$itemsrow["special"]);
	if ($atributo2[0] == "strength") { $userrow["attackpower"] -= $atributo2[1]; }
    elseif ($atributo2[0] == "dexterity") { $userrow["defensepower"] -= $atributo2[1]; }
	$userrow[$atributo2[0]] -= $atributo2[1];
	}	
	//fim dos bonus
	
	//dividir durabilidade.
	$variaveldur = explode(",",$userrow["durabilidade"]);
	
	//adicionar na bp
	if ($deletar == false){
	$userrow["bp".$bpcertonum] = $userrow["armorname"].",".$userrow["armorid"].",".$qual.",".$variaveldur[$qual];
	}
	
	//ajeitar durabilidade
	$variaveldur[$qual] = "X";
	$userrow["durabilidade"] = $variaveldur[0].",".$variaveldur[1].",".$variaveldur[2].",".$variaveldur[3].",".$variaveldur[4].",".	$variaveldur[5].",".$variaveldur[6];
	
	//retirar item
	$userrow["armorname"] = "None";
	$userrow["armorid"] = 0;
}













elseif ($qual == 3){//para bandana
	$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["shieldid"]."' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
	$userrow["defensepower"] -= $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$userrow["shieldname"]);
	if ($variavelpcontinuar[1] != ""){
		$userrow["defensepower"] -= $variavelpcontinuar[1]*20;//15 - de bonus
	}
	if ($itemsrow["special"] != "X"){
	$atributo2 = explode(",",$itemsrow["special"]);
	if ($atributo2[0] == "strength") { $userrow["attackpower"] -= $atributo2[1]; }
    elseif ($atributo2[0] == "dexterity") { $userrow["defensepower"] -= $atributo2[1]; }
	$userrow[$atributo2[0]] -= $atributo2[1];
	}	
	//fim dos bonus
	
	//dividir durabilidade.
	$variaveldur = explode(",",$userrow["durabilidade"]);
	
	//adicionar na bp
	if ($deletar == false){
	$userrow["bp".$bpcertonum] = $userrow["shieldname"].",".$userrow["shieldid"].",".$qual.",".$variaveldur[$qual];
	}
	
	//ajeitar durabilidade
	$variaveldur[$qual] = "X";
	$userrow["durabilidade"] = $variaveldur[0].",".$variaveldur[1].",".$variaveldur[2].",".$variaveldur[3].",".$variaveldur[4].",".	$variaveldur[5].",".$variaveldur[6];
	
	//retirar item
	$userrow["shieldname"] = "None";
	$userrow["shieldid"] = 0;
}




















elseif ($qual == 4){//para slot1
	$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["slot1id"]."' LIMIT 1", "drops");
    $itemsrow = mysql_fetch_array($itemsquery);
	
	//inicio dos bonus
	if ($itemsrow["attribute1"] != "X"){
	$atributo = explode(",",$itemsrow["attribute1"]);
	if ($atributo[0] == "strength") { $userrow["attackpower"] -= $atributo[1]; }
    elseif ($atributo[0] == "dexterity") { $userrow["defensepower"] -= $atributo[1]; }
	$userrow[$atributo[0]] -= $atributo[1];
	}

	if ($itemsrow["attribute2"] != "X"){
	$atributo2 = explode(",",$itemsrow["attribute2"]);
	if ($atributo2[0] == "strength") { $userrow["attackpower"] -= $atributo2[1]; }
    elseif ($atributo2[0] == "dexterity") { $userrow["defensepower"] -= $atributo2[1]; }
	$userrow[$atributo2[0]] -= $atributo2[1];
	}	
	//fim dos bonus
	
	//dividir durabilidade.
	$variaveldur = explode(",",$userrow["durabilidade"]);
	
	//adicionar na bp
	if ($deletar == false){
	$userrow["bp".$bpcertonum] = $userrow["slot1name"].",".$userrow["slot1id"].",".$qual.",".$variaveldur[$qual];
	}
	
	//ajeitar durabilidade
	$variaveldur[$qual] = "X";
	$userrow["durabilidade"] = $variaveldur[0].",".$variaveldur[1].",".$variaveldur[2].",".$variaveldur[3].",".$variaveldur[4].",".	$variaveldur[5].",".$variaveldur[6];
	
	//retirar item
	$userrow["slot1name"] = "None";
	$userrow["slot1id"] = 0;
}


















elseif ($qual == 5){//para slot2
	$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["slot2id"]."' LIMIT 1", "drops");
    $itemsrow = mysql_fetch_array($itemsquery);
	
	//inicio dos bonus
	if ($itemsrow["attribute1"] != "X"){
	$atributo = explode(",",$itemsrow["attribute1"]);
	if ($atributo[0] == "strength") { $userrow["attackpower"] -= $atributo[1]; }
    elseif ($atributo[0] == "dexterity") { $userrow["defensepower"] -= $atributo[1]; }
	$userrow[$atributo[0]] -= $atributo[1];
	}

	if ($itemsrow["attribute2"] != "X"){
	$atributo2 = explode(",",$itemsrow["attribute2"]);
	if ($atributo2[0] == "strength") { $userrow["attackpower"] -= $atributo2[1]; }
    elseif ($atributo2[0] == "dexterity") { $userrow["defensepower"] -= $atributo2[1]; }
	$userrow[$atributo2[0]] -= $atributo2[1];
	}	
	//fim dos bonus
	
	//dividir durabilidade.
	$variaveldur = explode(",",$userrow["durabilidade"]);
	
	//adicionar na bp
	if ($deletar == false){
	$userrow["bp".$bpcertonum] = $userrow["slot2name"].",".$userrow["slot2id"].",".$qual.",".$variaveldur[$qual];
	}
	
	//ajeitar durabilidade
	$variaveldur[$qual] = "X";
	$userrow["durabilidade"] = $variaveldur[0].",".$variaveldur[1].",".$variaveldur[2].",".$variaveldur[3].",".$variaveldur[4].",".	$variaveldur[5].",".$variaveldur[6];
	
	//retirar item
	$userrow["slot2name"] = "None";
	$userrow["slot2id"] = 0;
}


















elseif ($qual == 6){//para slot3
	$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["slot3id"]."' LIMIT 1", "drops");
    $itemsrow = mysql_fetch_array($itemsquery);
	
	//inicio dos bonus
	if ($itemsrow["attribute1"] != "X"){
	$atributo = explode(",",$itemsrow["attribute1"]);
	if ($atributo[0] == "strength") { $userrow["attackpower"] -= $atributo[1]; }
    elseif ($atributo[0] == "dexterity") { $userrow["defensepower"] -= $atributo[1]; }
	$userrow[$atributo[0]] -= $atributo[1];
	}

	if ($itemsrow["attribute2"] != "X"){
	$atributo2 = explode(",",$itemsrow["attribute2"]);
	if ($atributo2[0] == "strength") { $userrow["attackpower"] -= $atributo2[1]; }
    elseif ($atributo2[0] == "dexterity") { $userrow["defensepower"] -= $atributo2[1]; }
	$userrow[$atributo2[0]] -= $atributo2[1];
	}	
	//fim dos bonus
	
	//dividir durabilidade.
	$variaveldur = explode(",",$userrow["durabilidade"]);
	
	//adicionar na bp
	if ($deletar == false){
	$userrow["bp".$bpcertonum] = $userrow["slot3name"].",".$userrow["slot3id"].",".$qual.",".$variaveldur[$qual];
	}
	
	//ajeitar durabilidade
	$variaveldur[$qual] = "X";
	$userrow["durabilidade"] = $variaveldur[0].",".$variaveldur[1].",".$variaveldur[2].",".$variaveldur[3].",".$variaveldur[4].",".	$variaveldur[5].",".$variaveldur[6];
	
	//retirar item
	$userrow["slot3name"] = "None";
	$userrow["slot3id"] = 0;
}







	    $updatequery = doquery("UPDATE {{table}} SET durabilidade='".$userrow["durabilidade"]."',goldbonus='".$userrow["goldbonus"]."',expbonus='".$userrow["expbonus"]."',currenttp='".$userrow["currenttp"]."',bpimagem='".$userrow["bpimagem"]."',bp4='".$userrow["bp4"]."',bp3='".$userrow["bp3"]."',bp2='".$userrow["bp2"]."',bp1='".$userrow["bp1"]."',currenthp='".$userrow["currenthp"]."',currentmp='".$userrow["currentmp"]."', maxhp='".$userrow["maxhp"]."',maxmp='".$userrow["maxmp"]."',maxtp='".$userrow["maxtp"]."',strength='".$userrow["strength"]."',dexterity='".$userrow["dexterity"]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."', agilidade='".$userrow["agilidade"]."', determinacao='".$userrow["determinacao"]."', precisao='".$userrow["precisao"]."', sorte='".$userrow["sorte"]."', inteligencia='".$userrow["inteligencia"]."', bancogeral='".$userrow["bancogeral"]."', weaponname='".$userrow["weaponname"]."', shieldname='".$userrow["shieldname"]."', armorname='".$userrow["armorname"]."', slot1name='".$userrow["slot1name"]."', slot2name='".$userrow["slot2name"]."', slot3name='".$userrow["slot3name"]."', weaponid='".$userrow["weaponid"]."', shieldid='".$userrow["shieldid"]."', armorid='".$userrow["armorid"]."', slot1id='".$userrow["slot1id"]."', slot2id='".$userrow["slot2id"]."', slot3id='".$userrow["slot3id"]."',droprate='".$userrow["droprate"]."',maxnp='".$userrow["maxnp"]."',maxep='".$userrow["maxep"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
	
	
		
		
	if ($deletar == false){
		header('Location: /narutorpg/'.$userrow["pagina"]);
		
	}

?>