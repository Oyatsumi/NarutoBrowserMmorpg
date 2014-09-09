<?php // bp.



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();

		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);die(); }

$qual = $_GET['qual'];


//se qual for menor que 1 e  maior que 4
if ($qual > 4) {header('Location: /narutorpg/index.php'); die();}
if ($qual < 1) {header('Location: /narutorpg/index.php'); die();}

//se o item tiver vazio.
$variavelbp = "bp".$qual;
if ($userrow[$variavelbp] == "None"){header('Location: /narutorpg/index.php'); die();}


//separando
$itemseparadobp = explode(",",$userrow[$variavelbp]);

if ($itemseparadobp[1] == "hp"){
$userrow["currenthp"] += $itemseparadobp[2];
if ($userrow["currenthp"] > $userrow["maxhp"]) {$userrow["currenthp"] = $userrow["maxhp"];}
$userrow["bp".$qual] = "None";
}
elseif ($itemseparadobp[1] == "mp"){
$userrow["currentmp"] += $itemseparadobp[2];
if ($userrow["currentmp"] > $userrow["maxmp"]) {$userrow["currentmp"] = $userrow["maxmp"];}
$userrow["bp".$qual] = "None";
}



else{//se for um equip

	//se tiver na batalha
	if ($userrow["currentaction"] == "Fighting") {display("Você não pode equipar um item no meio de uma batalha!","Erro",false,false,false);die(); }


//retirando o item equipado
			if ($itemseparadobp[2] > 6) {display("Tentativa de trapaça ou erro detectado.","Erro",false,false,false);die(); }
			

		$usuariologadoid = $userrow["id"];
		$usuariologadonome = $userrow["charname"];
		$usuriologadodinheiro = $userrow["gold"];
		
	
	//pra saber qual foi o item.
	if ($itemseparadobp[2] == 1) {$nomedoslot = "weapon";}
	if ($itemseparadobp[2] == 2) {$nomedoslot = "armor";}
	if ($itemseparadobp[2] == 3) {$nomedoslot = "shield";}
	if ($itemseparadobp[2] == 4) {$nomedoslot = "slot3"; $itemseparadobp[2] = 6;}
	if ($itemseparadobp[2] == 5) {$nomedoslot = "slot3"; $itemseparadobp[2] = 6;}
	if ($itemseparadobp[2] == 6) {$nomedoslot = "slot3";}
	$id = $nomedoslot."id";
	$nome = $nomedoslot."name";
	//fechou
	

	
	//se tem item
	if ($userrow[$nome] != "None"){
		
	if ($itemseparadobp[2] <= 3) {//para weapon armor e shield
	$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow[$id]."' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
	
	if ($itemsrow["special"] != "X"){
	$atributo = explode(",",$itemsrow["special"]);
	$userrow[$atributo[0]] -= $atributo[1];
	if ($atributo[0] == "strength") { $userrow["attackpower"] -= $atributo[1]; }
    elseif ($atributo[0] == "dexterity") { $userrow["defensepower"] -= $atributo[1]; }
	}
	
	//verificar se é arma escudo ou armadura:
	if ($itemsrow["type"] == 1) {//ataque
	$userrow["attackpower"] -= $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$userrow[$nome]);
	if ($variavelpcontinuar[1] != ""){
	$userrow["attackpower"] -= $variavelpcontinuar[1]*20;//15 - de bonus
	}
	//fim dos bonus
	
	}
	else {//sendo de defesa
	$userrow["defensepower"] -= $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$userrow[$nome]);
	if ($variavelpcontinuar[1] != ""){
	$userrow["defensepower"] -= $variavelpcontinuar[1]*20;//15 - de bonus
	}
	//fim dos bonus
	
	}
	//pronto
	

	
	}
	
	if ($itemseparadobp[2] > 3) {//para slots
	$dropquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow[$id]."' LIMIT 1", "drops");
                $droprow = mysql_fetch_array($dropquery);
				
	//atributos
	//primeiro:
	if ($droprow["attribute1"] != "X"){
	$atributo1 = explode(",",$droprow["attribute1"]);
	$userrow[$atributo1[0]] -= $atributo1[1];
	if ($atributo1[0] == "strength") { $userrow["attackpower"] -= $atributo1[1]; }
    elseif ($atributo1[0] == "dexterity") { $userrow["defensepower"] -= $atributo1[1]; }
	}
	
	
	//segundo:
	if ($droprow["attribute2"] != "X"){
	$atributo2 = explode(",",$droprow["attribute2"]);
	$userrow[$atributo2[0]] -= $atributo2[1];
	if ($atributo2[0] == "strength") { $userrow["attackpower"] -= $atributo2[1]; }
    elseif ($atributo2[0] == "dexterity") { $userrow["defensepower"] -= $atributo2[1]; }
	}
	//fechou
	}
	
	
	//adicionando item à backpack
	
	//durabilidade
	$durab = explode(",",$userrow["durabilidade"]);
	
	$userrow[$variavelbp] = $userrow[$nome].",".$userrow[$id].",".$itemseparadobp[2].",".$durab[$itemseparadobp[2]];
	//fim adicionando a backpack
	
	//retirando a durabilidade
	$durab[$itemseparadobp[2]] = "X";
	$userrow["durabilidade"] = "-,".$durab[1].",".$durab[2].",".$durab[3].",".$durab[4].",".$durab[5].",".$durab[6];
	
	
	if ($itemseparadobp[2] > 3){
	//andando com os slots
	$userrow["slot3id"] = $userrow["slot2id"];
	$userrow["slot3name"] = $userrow["slot2name"];
	$userrow["slot2name"] = $userrow["slot1name"];
	$userrow["slot2id"] = $userrow["slot1id"];
	//andando com a durabilidade
	$dur = explode(",",$userrow["durabilidade"]);
	$dur[6] = $dur[5]; $dur[5] = $dur[4];
	$userrow["durabilidade"] = "-,";
		for ($i = 1; $i < 7; $i++){
			if ($i != 6){$userrow["durabilidade"] .= $dur[$i].",";}else{$userrow["durabilidade"] .= $dur[$i];}
		}
	//fim durabilidade
	}
	
}else{//fim se tem item
$userrow[$variavelbp] = "None";
	if ($itemseparadobp[2] > 3){
	//andando com os slots
	$userrow["slot3id"] = $userrow["slot2id"];
	$userrow["slot3name"] = $userrow["slot2name"];
	$userrow["slot2name"] = $userrow["slot1name"];
	$userrow["slot2id"] = $userrow["slot1id"];
	}//fim if
	//andando com a durabilidade
	$dur = explode(",",$userrow["durabilidade"]);
	$dur[6] = $dur[5]; $dur[5] = $dur[4];
	$userrow["durabilidade"] = "-,";
		for ($i = 1; $i < 7; $i++){
			if ($i != 6){$userrow["durabilidade"] .= $dur[$i].",";}else{$userrow["durabilidade"] .= $dur[$i];}
		}
	//fim durabilidade
}
//continuando sem retirar item nenhum que já estava equipado.
































//equipar o item no jogador.
		

		
	//fim se algo já estiver equipado no jogador
	//pra saber qual foi o item.
	if ($itemseparadobp[2] == 1) {$nomedoslot = "weapon";}
	if ($itemseparadobp[2] == 2) {$nomedoslot = "armor";}
	if ($itemseparadobp[2] == 3) {$nomedoslot = "shield";}
	if ($itemseparadobp[2] == 4) {$nomedoslot = "slot1";}
	if ($itemseparadobp[2] == 5) {$nomedoslot = "slot1"; $itemseparadobp[2] = 4;}
	if ($itemseparadobp[2] == 6) {$nomedoslot = "slot1"; $itemseparadobp[2] = 4;}
	$id = $nomedoslot."id";
	$nome = $nomedoslot."name";
	//fechou

		
		
	if ($itemseparadobp[2] <= 3) {//para weapon armor e shield e arma de atk
	$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$itemseparadobp[1]."' LIMIT 1", "items");
    $itemsrow = mysql_fetch_array($itemsquery);
	
	if ($itemsrow["special"] != "X"){
	$atributo = explode(",",$itemsrow["special"]);
	$userrow[$atributo[0]] += $atributo[1];
	if ($atributo[0] == "strength") { $userrow["attackpower"] += $atributo[1]; }
    elseif ($atributo[0] == "dexterity") { $userrow["defensepower"] += $atributo[1]; }
	}
	
	//verificar se é arma escudo ou armadura:
	if ($itemsrow["type"] == 1) {//ataque
	$userrow["attackpower"] += $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$itemseparadobp[0]);
	if ($variavelpcontinuar[1] != ""){
	$userrow["attackpower"] += $variavelpcontinuar[1]*20;//15 + de bonus
	}
	//fim dos bonus
	
	}
	else {//sendo de defesa
	$userrow["defensepower"] += $itemsrow["attribute"];
	//verificar bonus +1, +2
	$variavelpcontinuar = explode("+",$itemseparadobp[0]);
	if ($variavelpcontinuar[1] != ""){
	$userrow["defensepower"] += $variavelpcontinuar[1]*20;//15 + de bonus
	}
	//fim dos bonus
	
	}
	//pronto
	

	
	}
	
	if ($itemseparadobp[2] > 3) {//para slots
	$dropquery = doquery("SELECT * FROM {{table}} WHERE id='".$itemseparadobp[1]."' LIMIT 1", "drops");
                $droprow = mysql_fetch_array($dropquery);

	//atributos
	//primeiro:
	if ($droprow["attribute1"] != "X"){
	$atributo1 = explode(",",$droprow["attribute1"]);
	$userrow[$atributo1[0]] += $atributo1[1];
	if ($atributo1[0] == "strength") { $userrow["attackpower"] += $atributo1[1]; }
    elseif ($atributo1[0] == "dexterity") { $userrow["defensepower"] += $atributo1[1]; }
	}
	
	
	//segundo:
	if ($droprow["attribute2"] != "X"){
	$atributo2 = explode(",",$droprow["attribute2"]);
	$userrow[$atributo2[0]] += $atributo2[1];
	if ($atributo2[0] == "strength") { $userrow["attackpower"] += $atributo2[1]; }
    elseif ($atributo2[0] == "dexterity") { $userrow["defensepower"] += $atributo2[1]; }
	}
	//fechou
	}
	
	
	
	
	
	//colocando o item no jogador
	if ($itemseparadobp[2] <= 3) {//para weapon armor e shield e arma de atk
	$userrow[$nome] = $itemseparadobp[0];
	$userrow[$id] = $itemseparadobp[1];
	//colocando a durabilidade
	$durab = explode(",",$userrow["durabilidade"]);
	$durab[$itemseparadobp[2]] = $itemseparadobp[3];
	$userrow["durabilidade"] = "-,".$durab[1].",".$durab[2].",".$durab[3].",".$durab[4].",".$durab[5].",".$durab[6];
	
	}else{//para slots
	$novo1 = "slot1name";
	$novo2 = "slot1id";
	$userrow[$novo1] = $itemseparadobp[0];
	$userrow[$novo2] = $itemseparadobp[1];
	//colocando a durabilidade
	$durab = explode(",",$userrow["durabilidade"]);
	$durab[4] = $itemseparadobp[3];
	$userrow["durabilidade"] = "-,".$durab[1].",".$durab[2].",".$durab[3].",".$durab[4].",".$durab[5].",".$durab[6];
	}
	




















}//se for item de equipamento..



	    $updatequery = doquery("UPDATE {{table}} SET durabilidade='".$userrow["durabilidade"]."',goldbonus='".$userrow["goldbonus"]."',expbonus='".$userrow["expbonus"]."',currenttp='".$userrow["currenttp"]."',bpimagem='".$userrow["bpimagem"]."',bp4='".$userrow["bp4"]."',bp3='".$userrow["bp3"]."',bp2='".$userrow["bp2"]."',bp1='".$userrow["bp1"]."',currenthp='".$userrow["currenthp"]."',currentmp='".$userrow["currentmp"]."', maxhp='".$userrow["maxhp"]."',maxmp='".$userrow["maxmp"]."',maxtp='".$userrow["maxtp"]."',strength='".$userrow["strength"]."',dexterity='".$userrow["dexterity"]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."', agilidade='".$userrow["agilidade"]."', determinacao='".$userrow["determinacao"]."', precisao='".$userrow["precisao"]."', sorte='".$userrow["sorte"]."', inteligencia='".$userrow["inteligencia"]."', bancogeral='".$userrow["bancogeral"]."', weaponname='".$userrow["weaponname"]."', shieldname='".$userrow["shieldname"]."', armorname='".$userrow["armorname"]."', slot1name='".$userrow["slot1name"]."', slot2name='".$userrow["slot2name"]."', slot3name='".$userrow["slot3name"]."', weaponid='".$userrow["weaponid"]."', shieldid='".$userrow["shieldid"]."', armorid='".$userrow["armorid"]."', slot1id='".$userrow["slot1id"]."', slot2id='".$userrow["slot2id"]."', slot3id='".$userrow["slot3id"]."',droprate='".$userrow["droprate"]."',maxnp='".$userrow["maxnp"]."',maxep='".$userrow["maxep"]."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");



header('Location: /narutorpg/'.$userrow["pagina"].'');




?>