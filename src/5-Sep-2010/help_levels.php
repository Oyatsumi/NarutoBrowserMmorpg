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
<title><? echo $controlrow["gamename"]; ?> Ajuda</title>
<style type="text/css">
body {
  background-color: #000000;
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
<center>
<table><tr><td height="220"><center><img src="layoutnovo/titulo.jpg" /></center></td></tr><tr><td>

<table border="0" cellspacing="0" cellpadding="0" background="layoutnovo/menumeio/meio.png" style="background-repeat:repeat-y;;background-position:left top"><tr>
<td colspan="3" background="layoutnovo/menumeio/cima.png" style="background-repeat:repeat-y;;background-position:left top" width="671" height="62"></td>
</tr>
<tr background="layoutnovo/menumeio/meio.png" style="background-repeat:repeat-y;;background-position:left top">
<td width="65"></td>
<td width="500">
<a name="top"></a>
<h1><? echo $controlrow["gamename"]; ?> Ajuda: Leveis</h1>
[ <a href="help.php">Voltar à ajuda</a> | <a href="index.php">Voltar ao jogo</a> ]

<br /><br /><hr />

<table width="100%" style="border: solid 1px black" cellspacing="0" cellpadding="0">
<tr><td colspan="8" bgcolor="#ffffff"><center><b><? echo $controlrow["class1name"]; ?> Levels</b></center></td></tr>
<tr><td><b>Level</b><td><b>Exp.</b></td><td><b>HP</b></td><td><b>CH</b></td><td><b>TP</b></td><td><b>Força</b></td><td><b>Destreza</b></td><td><b>Jutsu</b></td></tr>
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
<table width="100%" style="border: solid 1px black" cellspacing="0" cellpadding="0">
<tr><td colspan="8" bgcolor="#ffffff"><center><b><? echo $controlrow["class2name"]; ?> Levels</b></center></td></tr>
<tr><td><b>Level</b><td><b>Exp.</b></td><td><b>HP</b></td><td><b>CH</b></td><td><b>TP</b></td><td><b>Força</b></td><td><b>Destreza</b></td><td><b>Jutsu</b></td></tr>
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
<table width="100%" style="border: solid 1px black" cellspacing="0" cellpadding="0">
<tr><td colspan="8" bgcolor="#ffffff"><center><b><? echo $controlrow["class3name"]; ?> Levels</b></center></td></tr>
<tr><td><b>Level</b><td><b>Exp.</b></td><td><b>HP</b></td><td><b>CH</b></td><td><b>TP</b></td><td><b>Força</b></td><td><b>Destreza</b></td><td><b>Jutsu</b></td></tr>
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
<b>Ex.:</b> Para level 1 você precisa ganhar 15 de Exp, para o level dois, você precisa ganhar 45 de Exp.<br />
Já os outros valores são fixos que você ganha à cada level.
<br /><br />
<center><table width="90%"><tr>
<td width="25%" align="center">Powered by <a href="http://nigeru.com" target="_new">Nigeru Animes</a></td><td width="25%" align="center">&copy; 2010 by Oyatsumi</td>
</tr></table></center>
</td>
<td width="60"></td>
</tr>
<tr>
<td colspan="3" background="layoutnovo/menumeio/baixo.png" width="671" height="62"></td></tr>
</table>

</td></tr></table></center>
</body>
</html>