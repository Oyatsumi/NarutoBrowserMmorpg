<?php 
include('lib.php'); 
$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);


include('cookies.php');
$userrow = checkcookies();

$ordenar = $_GET['ord'];
$ordenar2 = $_GET['ord2'];

if ($ordenar == ""){$ordenar = "type";}
if ($ordenar2 == ""){$ordenar2 = "mlevel";}

$page = "

<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/ajuda_titulo.gif\" /></center></td></tr></table>
<center>[ <a href=\"help.php\">Voltar à Ajuda</a> | <a href=\"index.php\">Voltar ao jogo</a> ]</center>

<br />

<table width=\"100%\" style=\"border: solid 1px black\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td colspan=\"5\" bgcolor=\"#ffffff\"><center><b>Itens</b></center></td></tr>
<tr><td><b><a href=\"help_items.php?ord=type\" title=\"Ordenar\">Tipo</a></b></td><td><b><a href=\"help_items.php?ord=name\" title=\"Ordenar\">Nome</a></b></td><td><b><a href=\"help_items.php?ord=buycost\" title=\"Ordenar\">Custo</a></b></td><td><b><a href=\"help_items.php?ord=attribute\" title=\"Ordenar\">Atributo</a></b></td><td><b><a href=\"help_items.php?ord=special\" title=\"Ordenar\">Especial</a></b></td></tr>";

$count = 1;
$itemsquery = doquery("SELECT * FROM {{table}} ORDER BY $ordenar, attribute", "items");
while ($itemsrow = mysql_fetch_array($itemsquery)) {
    if ($count == 1) { $color = "bgcolor=\"#ffffff\""; $count = 2; } else { $color = ""; $count = 1; }
    if ($itemsrow["type"] == 1) { $image = "weapon"; $power = "Ataque"; } elseif ($itemsrow["type"] == 2) { $image = "armor"; $power = "Defesa"; } else { $image = "shield"; $power = "Defesa"; }
    if ($itemsrow["special"] != "X") {
        $special = explode(",",$itemsrow["special"]);
        if ($special[0] == "maxhp") { $attr = "Max HP"; }
        elseif ($special[0] == "maxmp") { $attr = "Max CH"; }
        elseif ($special[0] == "maxtp") { $attr = "Max TP"; }
        elseif ($special[0] == "goldbonus") { $attr = "Bônus Ryou (%)"; }
        elseif ($special[0] == "expbonus") { $attr = "Bônus Experiência (%)"; }
        elseif ($special[0] == "strength") { $attr = "Força"; }
        elseif ($special[0] == "dexterity") { $attr = "Destreza"; }
        elseif ($special[0] == "attackpower") { $attr = "Poder de Ataque"; }
        elseif ($special[0] == "defensepower") { $attr = "Poder de Defesa"; }
		elseif ($special[0] == "agilidade") { $attr = "Agilidade"; }
		elseif ($special[0] == "sorte") { $attr = "Sorte"; }
		elseif ($special[0] == "determinacao") { $attr = "Determinação"; }
		elseif ($special[0] == "precisao") { $attr = "Precisão"; }
		elseif ($special[0] == "inteligencia") { $attr = "Inteligência"; }
		elseif ($special[0] == "droprate") { $attr = "Chance de Drop"; }
		elseif ($special[0] == "maxnp") { $attr = "Max NP"; }
		elseif ($special[0] == "maxep") { $attr = "Max EP"; }
		elseif ($special[0] == "droprate") { $attr = "Chance de Drop (%)"; }
        else { $attr = $special[0]; }
        if ($special[1] > 0) { $stat = "+" . $special[1]; } else { $stat = $special[1]; }
        $bigspecial = "$attr $stat";
    } else { $bigspecial = "<span class=\"light\">None</span>"; }
    $page .= "<tr><td $color width=\"5%\"><img src=\"images/icon_$image.gif\" alt=\"$image\"></td><td $color width=\"30%\">".$itemsrow["name"]."</td><td $color width=\"20%\">".$itemsrow["buycost"].\" Ryou</td><td $color width=\"20%\">".$itemsrow["attribute"].\" Poder de $power </td><td $color width=\"25%\">$bigspecial</td></tr>\n";
}

$page .= "
</table>
<br />
<br />
<table width=\"100%\" style=\"border: solid 1px black\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td colspan=\"4\" bgcolor=\"#ffffff\"><center><b>Drops</b></center></td></tr>
<tr><td><b><a href=\"help_items.php?ord2=name\" title=\"Ordenar\">Nome</a></b></td><td><a href=\"help_items.php?ord2=mlevel\" title=\"Level do Inimigo que Dropa - Ordenar\"><b>Level</b></a></td><td><b>Atributo 1</b></td><td><b>Atributo 2</b></td></tr>";

$count = 1;
$itemsquery = doquery("SELECT * FROM {{table}} ORDER BY $ordenar2", "drops");
while ($itemsrow = mysql_fetch_array($itemsquery)) {
    if ($count == 1) { $color = "bgcolor=\"#ffffff\""; $count = 2; } else { $color = ""; $count = 1; }
    if ($itemsrow["attribute1"] != "X") {
        $special1 = explode(",",$itemsrow["attribute1"]);
        if ($special1[0] == "maxhp") { $attr1 = "Max HP"; }
        elseif ($special1[0] == "maxmp") { $attr1 = "Max CH"; }
        elseif ($special1[0] == "maxtp") { $attr1 = "Max TP"; }
        elseif ($special1[0] == "goldbonus") { $attr1 = "Bônus Ryou (%)"; }
        elseif ($special1[0] == "expbonus") { $attr1 = "Bônus Experiência (%)"; }
        elseif ($special1[0] == "strength") { $attr1 = "Força"; }
        elseif ($special1[0] == "dexterity") { $attr1 = "Destreza"; }
        elseif ($special1[0] == "attackpower") { $attr1 = "Poder de Ataque"; }
        elseif ($special1[0] == "defensepower") { $attr1 = "Poder de Defesa"; }
		elseif ($special1[0] == "agilidade") { $attr1 = "Agilidade"; }
		elseif ($special1[0] == "sorte") { $attr1 = "Sorte"; }
		elseif ($special1[0] == "determinacao") { $attr1 = "Determinação"; }
		elseif ($special1[0] == "precisao") { $attr1 = "Precisão"; }
		elseif ($special1[0] == "inteligencia") { $attr1 = "Inteligência"; }
		elseif ($special1[0] == "maxnp") { $attr1 = "Max NP"; }
		elseif ($special1[0] == "maxep") { $attr1 = "Max EP"; }
		elseif ($special1[0] == "droprate") { $attr1 = "Chance de Drop (%)"; }
        else { $attr1 = $special1[0]; }
        if ($special1[1] > 0) { $stat1 = "+" . $special1[1]; } else { $stat1 = $special1[1]; }
        $bigspecial1 = "$attr1 $stat1";
    } else { $bigspecial1 = "<span class=\"light\">None</span>"; }
    if ($itemsrow["attribute2"] != "X") {
        $special2 = explode(",",$itemsrow["attribute2"]);
        if ($special2[0] == "maxhp") { $attr2 = "Max HP"; }
        elseif ($special2[0] == "maxmp") { $attr2 = "Max CH"; }
        elseif ($special2[0] == "maxtp") { $attr2 = "Max TP"; }
        elseif ($special2[0] == "goldbonus") { $attr2 = "Bônus Ryou (%)"; }
        elseif ($special2[0] == "expbonus") { $attr2 = "Bônus Experiência (%)"; }
        elseif ($special2[0] == "strength") { $attr2 = "Força"; }
        elseif ($special2[0] == "dexterity") { $attr2 = "Destreza"; }
        elseif ($special2[0] == "attackpower") { $attr2 = "Poder de Ataque"; }
        elseif ($special2[0] == "defensepower") { $attr2 = "Poder de Defesa"; }
		elseif ($special2[0] == "agilidade") { $attr2 = "Agilidade"; }
		elseif ($special2[0] == "sorte") { $attr2 = "Sorte"; }
		elseif ($special2[0] == "determinacao") { $attr2 = "Determinação"; }
		elseif ($special2[0] == "precisao") { $attr2 = "Precisão"; }
		elseif ($special2[0] == "inteligencia") { $attr2 = "Inteligência"; }
		elseif ($special2[0] == "maxnp") { $attr2 = "Max NP"; }
		elseif ($special2[0] == "maxep") { $attr2 = "Max EP"; }
		elseif ($special2[0] == "droprate") { $attr2 = "Chance de Drop (%)"; }
        else { $attr2 = $special2[0]; }
        if ($special2[1] > 0) { $stat2 = "+" . $special2[1]; } else { $stat2 = $special2[1]; }
        $bigspecial2 = "$attr2 $stat2";
    } else { $bigspecial2 = "<span class=\"light\">None</span>"; }
    $page .= "<tr><td $color width=\"25%\">".$itemsrow["name"]."</td><td $color width=\"15%\">".$itemsrow["mlevel"]."</td><td $color width=\"30%\">$bigspecial1</td><td $color width=\"30%\">$bigspecial2</td></tr>\n";
}
$page .= "
</table>
</center>";

display($page, "Ajuda - Itens", false, false, false); 

?>