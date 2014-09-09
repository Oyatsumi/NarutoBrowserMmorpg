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
<title><? echo $controlrow["gamename"]; ?> Rank</title>
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
<h1><? echo $controlrow["gamename"]; ?> : Rank por Defesa</h1>
[ <a href="index.php">Voltar ao Jogo</a> | <a href="rank.php">Rank por Level</a> | <a href="rank_ataque.php">Rank por Ataque</a> | <a href="rank_defesa.php">Rank por Defesa</a> | <a href="rank_ryou.php">Rank por Ryou</a> ]

<br /><br /><hr />

<table width="60%" style="border: solid 1px black" cellspacing="0" cellpadding="0">
<tr><td colspan="5" bgcolor="#ffffff"><center><b>Rank por Defesa</b></center></td></tr>
<tr><td><b>Defesa</b></td><td><b>Level</b></td><td><b>Nome</b></td></tr>
<?
$count = 1;
$usersquery = doquery("SELECT * FROM {{table}} ORDER BY dexterity DESC", "users");
while ($usersrow = mysql_fetch_array($usersquery)) {
    if ($count == 1) { $color = "bgcolor=\"#ffffff\""; $count = 2; } else { $color = ""; $count = 1; }  
        
		 if ($usersrow["authlevel"] != 1) {
    echo "<tr><td $color width=\"5%\">".$usersrow["dexterity"]."</td><td $color width=\"5%\">".$usersrow["level"]."</td><td $color width=\"30%\">".$usersrow["charname"]."</td></tr>\n";
	}
}
?>
</table>
<br />
<br />
<table class="copyright" width="100%"><tr>
<td width="50%" align="center">Powered by <a href="http://nigeru.com" target="_new">Nigeru Animes</a></td><td width="50%" align="center">&copy; 2010 by Oyatsumi</td>
</tr></table>
</body>
</html>