<?php // heal.php :: Handles stuff from the Quick Spells menu. (Healing spells only... other spells are handled in fight.php.)

function healspells($id) {
    
    global $userrow;
    
    $userspells = explode(",",$userrow["spells"]);
    $spellquery = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "spells");
    $spellrow = mysqli_fetch_array($spellquery);
    
    // All the various ways to error out.
    $spell = false;
    foreach ($userspells as $a => $b) {
        if ($b == $id) { $spell = true; }
    }
    if ($spell != true) { header('Location: ./index.php?conteudo=Voc� ainda n�o aprendeu esse Jutsu.');die(); }
    if ($spellrow["type"] != 1) { header('Location: ./index.php?conteudo=Esse n�o � um Jutsu medicinal.');die();}
    if ($userrow["currentmp"] < $spellrow["mp"]) {header('Location: ./index.php?conteudo=Voc� n�o tem Chakra suficiente para usar esse Jutsu.');die(); }
    if ($userrow["currentaction"] == "Fighting") { header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode usar os Jutsus da Lista R�pida de Jutsus durante uma batalha. Por favor selecione o Jutsu Medicinal que voc� gostaria de usar pela sele��o de Jutsus na janela principal de luta para continuar.');die(); }
    if ($userrow["currenthp"] == $userrow["maxhp"]) { header('Location: ./index.php?conteudo=Seus Pontos de Vida j� est�o cheios. Voc� n�o precisa de um Jutsu Medicinal agora.');die();}
    
    $newhp = $userrow["currenthp"] + $spellrow["attribute"];
    if ($userrow["maxhp"] < $newhp) { $spellrow["attribute"] = $userrow["maxhp"] - $userrow["currenthp"]; $newhp = $userrow["currenthp"] + $spellrow["attribute"]; }
    $newmp = $userrow["currentmp"] - $spellrow["mp"];
    
    $updatequery = doquery("UPDATE {{table}} SET currenthp='$newhp', currentmp='$newmp' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
    
    header("Location: ./index.php?conteudo=Voc� usou o ".$spellrow["name"]." e ganhou ".$spellrow["attribute"]." Pontos de Vida.");die();
    
}

?>