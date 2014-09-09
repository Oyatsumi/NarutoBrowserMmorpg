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
<h1><? echo $controlrow["gamename"]; ?> Ajuda: Monstros</h1>
[ <a href="help.php">Voltar à ajuda</a> | <a href="index.php">Voltar ao jogo</a> ]

<br /><br /><hr />

<table width="100%" style="border: solid 1px black" cellspacing="0" cellpadding="0">
<tr><td colspan="8" bgcolor="#ffffff"><center><b>Monstros</b></center></td></tr>
<tr><td><b>Nome</b></td><td><b>Max HP</b></td><td><b>Max Dano</b></td><td><b>Defesa</b></td><td><b>Level</b></td><td><b>Max Exp.</b></td><td><b>Max Ryou</b></td><td><b>Imunidade</b></td></tr>
<?
$count = 1;
$itemsquery = doquery("SELECT * FROM {{table}} ORDER BY id", "monsters");
while ($itemsrow = mysql_fetch_array($itemsquery)) {
    if ($count == 1) { $color = "bgcolor=\"#ffffff\""; $count = 2; } else { $color = ""; $count = 1; }
    if ($itemsrow["immune"] == 0) { $immune = "<span class=\"light\">None</span>"; } elseif ($itemsrow["immune"] == 1) { $immune = "Dano"; } else { $immune = "Dano & Sono"; }
    echo "<tr><td $color width=\"30%\">".$itemsrow["name"]."</td><td $color width=\"10%\">".$itemsrow["maxhp"]."</td><td $color width=\"10%\">".$itemsrow["maxdam"]."</td><td $color width=\"10%\">".$itemsrow["armor"]."</td><td $color width=\"10%\">".$itemsrow["level"]."</td><td $color width=\"10%\">".$itemsrow["maxexp"]."</td><td $color width=\"10%\">".$itemsrow["maxgold"]."</td><td $color width=\"20%\">$immune</td></tr>\n";
}
?>
</table>
<br />
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