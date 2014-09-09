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
<h1><? echo $controlrow["gamename"]; ?> Help: Items & Drops</h1>
[ <a href="help.php">Return to Help</a> | <a href="index.php">Return to the game</a> ]

<br /><br /><hr />

<table width="60%" style="border: solid 1px black" cellspacing="0" cellpadding="0">
<tr><td colspan="5" bgcolor="#ffffff"><center><b>Items</b></center></td></tr>
<tr><td><b>Type</b></td><td><b>Name</b></td><td><b>Cost</b></td><td><b>Attribute</b></td><td><b>Special</b></td></tr>
<?
$count = 1;
$itemsquery = doquery("SELECT * FROM {{table}} ORDER BY id", "items");
while ($itemsrow = mysql_fetch_array($itemsquery)) {
    if ($count == 1) { $color = "bgcolor=\"#ffffff\""; $count = 2; } else { $color = ""; $count = 1; }
    if ($itemsrow["type"] == 1) { $image = "weapon"; $power = "Attack"; } elseif ($itemsrow["type"] == 2) { $image = "armor"; $power = "Defense"; } else { $image = "shield"; $power = "Defense"; }
    if ($itemsrow["special"] != "X") {
        $special = explode(",",$itemsrow["special"]);
        if ($special[0] == "maxhp") { $attr = "Max HP"; }
        elseif ($special[0] == "maxmp") { $attr = "Max MP"; }
        elseif ($special[0] == "maxtp") { $attr = "Max TP"; }
        elseif ($special[0] == "goldbonus") { $attr = "Gold Bonus (%)"; }
        elseif ($special[0] == "expbonus") { $attr = "Experience Bonus (%)"; }
        elseif ($special[0] == "strength") { $attr = "Strength"; }
        elseif ($special[0] == "dexterity") { $attr = "Dexterity"; }
        elseif ($special[0] == "attackpower") { $attr = "Attack Power"; }
        elseif ($special[0] == "defensepower") { $attr = "Defense Power"; }
        else { $attr = $special[0]; }
        if ($special[1] > 0) { $stat = "+" . $special[1]; } else { $stat = $special[1]; }
        $bigspecial = "$attr $stat";
    } else { $bigspecial = "<span class=\"light\">None</span>"; }
    echo "<tr><td $color width=\"5%\"><img src=\"images/icon_$image.gif\" alt=\"$image\"></td><td $color width=\"30%\">".$itemsrow["name"]."</td><td $color width=\"20%\">".$itemsrow["buycost"]." Gold</td><td $color width=\"20%\">".$itemsrow["attribute"]." $power Power</td><td $color width=\"25%\">$bigspecial</td></tr>\n";
}
?>
</table>
<br />
<br />
<table width="60%" style="border: solid 1px black" cellspacing="0" cellpadding="0">
<tr><td colspan="4" bgcolor="#ffffff"><center><b>Drops</b></center></td></tr>
<tr><td><b>Name</b></td><td><b>Monster Level</b></td><td><b>Attribute 1</b></td><td><b>Attribute 2</b></td></tr>
<?
$count = 1;
$itemsquery = doquery("SELECT * FROM {{table}} ORDER BY id", "drops");
while ($itemsrow = mysql_fetch_array($itemsquery)) {
    if ($count == 1) { $color = "bgcolor=\"#ffffff\""; $count = 2; } else { $color = ""; $count = 1; }
    if ($itemsrow["attribute1"] != "X") {
        $special1 = explode(",",$itemsrow["attribute1"]);
        if ($special1[0] == "maxhp") { $attr1 = "Max HP"; }
        elseif ($special1[0] == "maxmp") { $attr1 = "Max MP"; }
        elseif ($special1[0] == "maxtp") { $attr1 = "Max TP"; }
        elseif ($special1[0] == "goldbonus") { $attr1 = "Gold Bonus (%)"; }
        elseif ($special1[0] == "expbonus") { $attr1 = "Experience Bonus (%)"; }
        elseif ($special1[0] == "strength") { $attr1 = "Strength"; }
        elseif ($special1[0] == "dexterity") { $attr1 = "Dexterity"; }
        elseif ($special1[0] == "attackpower") { $attr1 = "Attack Power"; }
        elseif ($special1[0] == "defensepower") { $attr1 = "Defense Power"; }
        else { $attr1 = $special1[0]; }
        if ($special1[1] > 0) { $stat1 = "+" . $special1[1]; } else { $stat1 = $special1[1]; }
        $bigspecial1 = "$attr1 $stat1";
    } else { $bigspecial1 = "<span class=\"light\">None</span>"; }
    if ($itemsrow["attribute2"] != "X") {
        $special2 = explode(",",$itemsrow["attribute2"]);
        if ($special2[0] == "maxhp") { $attr2 = "Max HP"; }
        elseif ($special2[0] == "maxmp") { $attr2 = "Max MP"; }
        elseif ($special2[0] == "maxtp") { $attr2 = "Max TP"; }
        elseif ($special2[0] == "goldbonus") { $attr2 = "Gold Bonus (%)"; }
        elseif ($special2[0] == "expbonus") { $attr2 = "Experience Bonus (%)"; }
        elseif ($special2[0] == "strength") { $attr2 = "Strength"; }
        elseif ($special2[0] == "dexterity") { $attr2 = "Dexterity"; }
        elseif ($special2[0] == "attackpower") { $attr2 = "Attack Power"; }
        elseif ($special2[0] == "defensepower") { $attr2 = "Defense Power"; }
        else { $attr2 = $special2[0]; }
        if ($special2[1] > 0) { $stat2 = "+" . $special2[1]; } else { $stat2 = $special2[1]; }
        $bigspecial2 = "$attr2 $stat2";
    } else { $bigspecial2 = "<span class=\"light\">None</span>"; }
    echo "<tr><td $color width=\"25%\">".$itemsrow["name"]."</td><td $color width=\"15%\">".$itemsrow["mlevel"]."</td><td $color width=\"30%\">$bigspecial1</td><td $color width=\"30%\">$bigspecial2</td></tr>\n";
}
?>
</table>
<br />
<table class="copyright" width="100%"><tr>
<td width="50%" align="center">Powered by <a href="http://nigeru.com" target="_new">Nigeru Animes</a></td><td width="50%" align="center">&copy; 2010 by Oyatsumi</td>
</tr></table>
</body>
</html>