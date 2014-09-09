<?php 
include('lib.php'); 
$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);
ob_start("ob_gzhandler");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><? echo $controlrow["gamename"]; ?> Help</title>
<style type="text/css">
body {
  background-image: url(images/background.jpg);
  color: black;
  font: 11px verdana;
}
table {
  border-style: none;
  padding: 0px;
  font: 11px verdana;
}
td {
  border-style: none;
  padding: 3px;
  vertical-align: top;
}
td.top {
  border-bottom: solid 2px black;
}
td.left {
  width: 150px;
  border-right: solid 2px black;
}
td.right {
  width: 150px;
  border-left: solid 2px black;
}
a {
    color: #663300;
    text-decoration: none;
    font-weight: bold;
}
a:hover {
    color: #330000;
}
.small {
  font: 10px verdana;
}
.highlight {
  color: red;
}
.light {
  color: #999999;
}
.title {
  border: solid 1px black;
  background-color: #eeeeee;
  font-weight: bold;
  padding: 5px;
  margin: 3px;
}
.copyright {
  border: solid 1px black;
  background-color: #eeeeee;
  font: 10px verdana;
}
</style>
</head>
<body>
<a name="top"></a>
<h1><? echo $controlrow["gamename"]; ?> Help: Levels</h1>
[ <a href="help.php">Return to Help</a> | <a href="index.php">Return to the game</a> ]

<br /><br /><hr />

<table width="50%" style="border: solid 1px black" cellspacing="0" cellpadding="0">
<tr><td colspan="8" bgcolor="#ffffff"><center><b><? echo $controlrow["class1name"]; ?> Levels</b></center></td></tr>
<tr><td><b>Level</b><td><b>Exp.</b></td><td><b>HP</b></td><td><b>MP</b></td><td><b>TP</b></td><td><b>Strength</b></td><td><b>Dexterity</b></td><td><b>Spell</b></td></tr>
<?
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
    if ($itemsrow["id"] != 100) { echo "<tr><td $color width=\"12%\">".$itemsrow["id"]."</td><td $color width=\"12%\">".number_format($itemsrow["1_exp"])."</td><td $color width=\"12%\">".$itemsrow["1_hp"]."</td><td $color width=\"12%\">".$itemsrow["1_mp"]."</td><td $color width=\"12%\">".$itemsrow["1_tp"]."</td><td $color width=\"12%\">".$itemsrow["1_strength"]."</td><td $color width=\"12%\">".$itemsrow["1_dexterity"]."</td><td $color width=\"12%\">$spell</td></tr>\n"; }
}
?>
</table>
<br /><br />
<table width="50%" style="border: solid 1px black" cellspacing="0" cellpadding="0">
<tr><td colspan="8" bgcolor="#ffffff"><center><b><? echo $controlrow["class2name"]; ?> Levels</b></center></td></tr>
<tr><td><b>Level</b><td><b>Exp.</b></td><td><b>HP</b></td><td><b>MP</b></td><td><b>TP</b></td><td><b>Strength</b></td><td><b>Dexterity</b></td><td><b>Spell</b></td></tr>
<?
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
    if ($itemsrow["id"] != 100) { echo "<tr><td $color width=\"12%\">".$itemsrow["id"]."</td><td $color width=\"12%\">".number_format($itemsrow["2_exp"])."</td><td $color width=\"12%\">".$itemsrow["2_hp"]."</td><td $color width=\"12%\">".$itemsrow["2_mp"]."</td><td $color width=\"12%\">".$itemsrow["2_tp"]."</td><td $color width=\"12%\">".$itemsrow["2_strength"]."</td><td $color width=\"12%\">".$itemsrow["2_dexterity"]."</td><td $color width=\"12%\">$spell</td></tr>\n"; }
}
?>
</table>
<br /><br />
<table width="50%" style="border: solid 1px black" cellspacing="0" cellpadding="0">
<tr><td colspan="8" bgcolor="#ffffff"><center><b><? echo $controlrow["class3name"]; ?> Levels</b></center></td></tr>
<tr><td><b>Level</b><td><b>Exp.</b></td><td><b>HP</b></td><td><b>MP</b></td><td><b>TP</b></td><td><b>Strength</b></td><td><b>Dexterity</b></td><td><b>Spell</b></td></tr>
<?
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
    if ($itemsrow["id"] != 100) { echo "<tr><td $color width=\"12%\">".$itemsrow["id"]."</td><td $color width=\"12%\">".number_format($itemsrow["3_exp"])."</td><td $color width=\"12%\">".$itemsrow["3_hp"]."</td><td $color width=\"12%\">".$itemsrow["3_mp"]."</td><td $color width=\"12%\">".$itemsrow["3_tp"]."</td><td $color width=\"12%\">".$itemsrow["3_strength"]."</td><td $color width=\"12%\">".$itemsrow["3_dexterity"]."</td><td $color width=\"12%\">$spell</td></tr>\n"; }
}
?>
</table>
<br />
Experience points listed are total values up until that point. All other values are just the new amount that you gain for each level.
<br /><br />
<table class="copyright" width="100%"><tr>
<td width="50%" align="center">Powered by <a href="http://nigeru.com" target="_new">Nigeru Animes</a></td><td width="50%" align="center">&copy; 2010 by Oyatsumi</td>
</tr></table>
</body>
</html>