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
    if ($spell != true) { header('Location: /narutorpg/index.php?conteudo=Você ainda não aprendeu esse Jutsu.');die(); }
    if ($spellrow["type"] != 1) { header('Location: /narutorpg/index.php?conteudo=Esse não é um Jutsu medicinal.');die();}
    if ($userrow["currentmp"] < $spellrow["mp"]) {header('Location: /narutorpg/index.php?conteudo=Você não tem Chakra suficiente para usar esse Jutsu.');die(); }
    if ($userrow["currentaction"] == "Fighting") { header('Location: /narutorpg/index.php?do=fight&conteudo=Você não pode usar os Jutsus da Lista Rápida de Jutsus durante uma batalha. Por favor selecione o Jutsu Medicinal que você gostaria de usar pela seleção de Jutsus na janela principal de luta para continuar.');die(); }
    if ($userrow["currenthp"] == $userrow["maxhp"]) { header('Location: /narutorpg/index.php?conteudo=Seus Pontos de Vida já estão cheios. Você não precisa de um Jutsu Medicinal agora.');die();}
    
    $newhp = $userrow["currenthp"] + $spellrow["attribute"];
    if ($userrow["maxhp"] < $newhp) { $spellrow["attribute"] = $userrow["maxhp"] - $userrow["currenthp"]; $newhp = $userrow["currenthp"] + $spellrow["attribute"]; }
    $newmp = $userrow["currentmp"] - $spellrow["mp"];
    
    $updatequery = doquery("UPDATE {{table}} SET currenthp='$newhp', currentmp='$newmp' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
    
    header("Location: /narutorpg/index.php?conteudo=Você usou o ".$spellrow["name"]." e ganhou ".$spellrow["attribute"]." Pontos de Vida.");die();
    
}

?>