<?php // heal.php :: Handles stuff from the Quick Spells menu. (Healing spells only... other spells are handled in fight.php.)

function healspells($id) {
    
    global $userrow;
    
    $userspells = explode(",",$userrow["spells"]);
    $spellquery = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "spells");
    $spellrow = mysql_fetch_array($spellquery);
    
    // All the various ways to error out.
    $spell = false;
    foreach ($userspells as $a => $b) {
        if ($b == $id) { $spell = true; }
    }
    if ($spell != true) { display("Você ainda não aprendeu esse Jutsu. Por favor volte e tente novamente.", "Error"); die(); }
    if ($spellrow["type"] != 1) { display("Esse não é um Jutsu medicinal. Por favor volte e tente novamente.", "Error"); die(); }
    if ($userrow["currentmp"] < $spellrow["mp"]) { display("Você não tem Chakra suficiente para usar esse Jutsu. Por favor volte e tente novamente.", "Error"); die(); }
    if ($userrow["currentaction"] == "Fighting") { display("Você não pode usar Jutsus os Jutsus da Lista Rápida de Jutsus durante uma batalha. Por favor volte e selecione o Jutsu Medicinal que você gostaria de usar pela seleção de Jutsus na janela principal de luta para continuar.", "Error"); die(); }
    if ($userrow["currenthp"] == $userrow["maxhp"]) { display("Seus Pontos de Vida já estão cheios. Você não precisa de um Jutsu Medicinal agora.", "Error"); die(); }
    
    $newhp = $userrow["currenthp"] + $spellrow["attribute"];
    if ($userrow["maxhp"] < $newhp) { $spellrow["attribute"] = $userrow["maxhp"] - $userrow["currenthp"]; $newhp = $userrow["currenthp"] + $spellrow["attribute"]; }
    $newmp = $userrow["currentmp"] - $spellrow["mp"];
    
    $updatequery = doquery("UPDATE {{table}} SET currenthp='$newhp', currentmp='$newmp' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
    
    display("Você usou o Jutsu: ".$spellrow["name"]." e ganhou ".$spellrow["attribute"]." Pontos de Vida. Você pode continuar <a href=\"index.php\">explorando</a>.", "Jutsu Medicinal");
    die();
    
}

?>