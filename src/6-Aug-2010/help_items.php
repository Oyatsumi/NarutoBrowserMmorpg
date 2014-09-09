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
<h1><? echo $controlrow["gamename"]; ?> Ajuda: Itens & Drops</h1>
[ <a href="help.php">Voltar à Ajuda</a> | <a href="index.php">Voltar ao jogo</a> ]

<br /><br /><hr />

<table width="100%" style="border: solid 1px black" cellspacing="0" cellpadding="0">
<tr><td colspan="5" bgcolor="#ffffff"><center><b>Itens</b></center></td></tr>
<tr><td><b>Tipo</b></td><td><b>Nome</b></td><td><b>Custo</b></td><td><b>Atributo</b></td><td><b>Especial</b></td></tr>
<?
$count = 1;
$itemsquery = doquery("SELECT * FROM {{table}} ORDER BY id", "items");
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
        else { $attr = $special[0]; }
        if ($special[1] > 0) { $stat = "+" . $special[1]; } else { $stat = $special[1]; }
        $bigspecial = "$attr $stat";
    } else { $bigspecial = "<span class=\"light\">None</span>"; }
    echo "<tr><td $color width=\"5%\"><img src=\"images/icon_$image.gif\" alt=\"$image\"></td><td $color width=\"30%\">".$itemsrow["name"]."</td><td $color width=\"20%\">".$itemsrow["buycost"]." Gold</td><td $color width=\"20%\">".$itemsrow["attribute"]." Poder de $power </td><td $color width=\"25%\">$bigspecial</td></tr>\n";
}
?>
</table>
<br />
<br />
<table width="100%" style="border: solid 1px black" cellspacing="0" cellpadding="0">
<tr><td colspan="4" bgcolor="#ffffff"><center><b>Drops</b></center></td></tr>
<tr><td><b>Nome</b></td><td><b>Level do Monstro</b></td><td><b>Atributo 1</b></td><td><b>Atributo 2</b></td></tr>
<?
$count = 1;
$itemsquery = doquery("SELECT * FROM {{table}} ORDER BY id", "drops");
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
		elseif ($special[0] == "agilidade") { $attr = "Agilidade"; }
		elseif ($special[0] == "sorte") { $attr = "Sorte"; }
		elseif ($special[0] == "determinacao") { $attr = "Determinação"; }
		elseif ($special[0] == "precisao") { $attr = "Precisão"; }
		elseif ($special[0] == "inteligencia") { $attr = "Inteligência"; }
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
		elseif ($special[0] == "agilidade") { $attr = "Agilidade"; }
		elseif ($special[0] == "sorte") { $attr = "Sorte"; }
		elseif ($special[0] == "determinacao") { $attr = "Determinação"; }
		elseif ($special[0] == "precisao") { $attr = "Precisão"; }
		elseif ($special[0] == "inteligencia") { $attr = "Inteligência"; }
        else { $attr2 = $special2[0]; }
        if ($special2[1] > 0) { $stat2 = "+" . $special2[1]; } else { $stat2 = $special2[1]; }
        $bigspecial2 = "$attr2 $stat2";
    } else { $bigspecial2 = "<span class=\"light\">None</span>"; }
    echo "<tr><td $color width=\"25%\">".$itemsrow["name"]."</td><td $color width=\"15%\">".$itemsrow["mlevel"]."</td><td $color width=\"30%\">$bigspecial1</td><td $color width=\"30%\">$bigspecial2</td></tr>\n";
}
?>
</table>
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