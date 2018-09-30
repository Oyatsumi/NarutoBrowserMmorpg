<?php 
include('lib.php'); 
$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);


include('cookies.php');
$userrow = checkcookies();


$page = "
<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/ajuda_titulo.gif\" /></center></td></tr></table>
<center>[ <a href=\"help.php\">Voltar à ajuda</a> | <a href=\"index.php\">Voltar ao jogo</a> ]</center><br>

<br />

<table width=\"100%\" style=\"border: solid 1px black\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td colspan=\"8\" bgcolor=\"#ffffff\"><center><b>".$controlrow["class1name"]." Levels</b></center></td></tr>
<tr><td><b>Level</b><td><b>Exp.</b></td><td><b>HP</b></td><td><b>CH</b></td><td><b>TP</b></td><td><b>Força</b></td><td><b>Destreza</b></td><td><b>Jutsu</b></td></tr>";

$count = 1;
$itemsquery = doquery("SELECT id,1_exp,1_hp,1_mp,1_tp,1_strength,1_dexterity,1_spells FROM {{table}} ORDER BY id", "levels");
$spellsquery = doquery("SELECT * FROM {{table}} ORDER BY id", "spells");
$spells = array();
while ($spellsrow = mysql_fetch_array($spellsquery)) {
    $spells[$spellsrow["id"]] = $spellsrow;
}
while ($itemsrow = mysql_fetch_array($itemsquery)) {
    if ($count == 1) { $color = "bgcolor=\"#ffffff\""; $count = 2; } else { $color = ""; $count = 1; }
    if ($itemsrow["1_spells"] != 0) { $spell = $spells[$itemsrow["1_spells"]]["name"]; } else { $spell = "<span class=\"light\">None</span>"; }
    if ($itemsrow["id"] != 100) { $page .= "<tr><td $color width=\"12%\">".$itemsrow["id"]."</td><td $color width=\"12%\">".number_format($itemsrow["1_exp"])."</td><td $color width=\"12%\">".$itemsrow["1_hp"]."</td><td $color width=\"12%\">".$itemsrow["1_mp"]."</td><td $color width=\"12%\">".$itemsrow["1_tp"]."</td><td $color width=\"12%\">".$itemsrow["1_strength"]."</td><td $color width=\"12%\">".$itemsrow["1_dexterity"]."</td><td $color width=\"12%\">$spell</td></tr>\n"; }
}

$page .= "
</table>
<br /><br />
<table width=\"100%\" style=\"border: solid 1px black\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td colspan=\"8\" bgcolor=\"#ffffff\"><center><b>".$controlrow["class2name"]." Levels</b></center></td></tr>
<tr><td><b>Level</b><td><b>Exp.</b></td><td><b>HP</b></td><td><b>CH</b></td><td><b>TP</b></td><td><b>Força</b></td><td><b>Destreza</b></td><td><b>Jutsu</b></td></tr>
";
$count = 1;
$itemsquery = doquery("SELECT id,2_exp,2_hp,2_mp,2_tp,2_strength,2_dexterity,2_spells FROM {{table}} ORDER BY id", "levels");
$spellsquery = doquery("SELECT * FROM {{table}} ORDER BY id", "spells");
$spells = array();
while ($spellsrow = mysql_fetch_array($spellsquery)) {
    $spells[$spellsrow["id"]] = $spellsrow;
}
while ($itemsrow = mysql_fetch_array($itemsquery)) {
    if ($count == 1) { $color = "bgcolor=\"#ffffff\""; $count = 2; } else { $color = ""; $count = 1; }
    if ($itemsrow["2_spells"] != 0) { $spell = $spells[$itemsrow["2_spells"]]["name"]; } else { $spell = "<span class=\"light\">None</span>"; }
    if ($itemsrow["id"] != 100) { $page .= "<tr><td $color width=\"12%\">".$itemsrow["id"]."</td><td $color width=\"12%\">".number_format($itemsrow["2_exp"])."</td><td $color width=\"12%\">".$itemsrow["2_hp"]."</td><td $color width=\"12%\">".$itemsrow["2_mp"]."</td><td $color width=\"12%\">".$itemsrow["2_tp"]."</td><td $color width=\"12%\">".$itemsrow["2_strength"]."</td><td $color width=\"12%\">".$itemsrow["2_dexterity"]."</td><td $color width=\"12%\">$spell</td></tr>\n"; }
}
$page .= "
</table>
<br /><br />
<table width=\"100%\" style=\"border: solid 1px black\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td colspan=\"8\" bgcolor=\"#ffffff\"><center><b>".$controlrow["class3name"]." Levels</b></center></td></tr>
<tr><td><b>Level</b><td><b>Exp.</b></td><td><b>HP</b></td><td><b>CH</b></td><td><b>TP</b></td><td><b>Força</b></td><td><b>Destreza</b></td><td><b>Jutsu</b></td></tr>
";

$count = 1;
$itemsquery = doquery("SELECT id,3_exp,3_hp,3_mp,3_tp,3_strength,3_dexterity,3_spells FROM {{table}} ORDER BY id", "levels");
$spellsquery = doquery("SELECT * FROM {{table}} ORDER BY id", "spells");
$spells = array();
while ($spellsrow = mysql_fetch_array($spellsquery)) {
    $spells[$spellsrow["id"]] = $spellsrow;
}
while ($itemsrow = mysql_fetch_array($itemsquery)) {
    if ($count == 1) { $color = "bgcolor=\"#ffffff\""; $count = 2; } else { $color = ""; $count = 1; }
    if ($itemsrow["3_spells"] != 0) { $spell = $spells[$itemsrow["3_spells"]]["name"]; } else { $spell = "<span class=\"light\">None</span>"; }
    if ($itemsrow["id"] != 100) { $page .= "<tr><td $color width=\"12%\">".$itemsrow["id"]."</td><td $color width=\"12%\">".number_format($itemsrow["3_exp"])."</td><td $color width=\"12%\">".$itemsrow["3_hp"]."</td><td $color width=\"12%\">".$itemsrow["3_mp"]."</td><td $color width=\"12%\">".$itemsrow["3_tp"]."</td><td $color width=\"12%\">".$itemsrow["3_strength"]."</td><td $color width=\"12%\">".$itemsrow["3_dexterity"]."</td><td $color width=\"12%\">$spell</td></tr>\n"; }
}

$page .= "
</table>
<br />
<b>Ex.:</b> Para level 2 você precisa ganhar 15 de Exp, para o level 3, você precisa ganhar 45 de Exp.
</center>";

display($page, "Ajuda - Leveis", false, false, false); 

?>