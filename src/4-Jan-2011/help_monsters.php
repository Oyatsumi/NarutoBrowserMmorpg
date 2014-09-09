<?php 
include('lib.php'); 
$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);

include('cookies.php');
$userrow = checkcookies();

$ordenar = $_GET['ord'];
if ($ordenar == ""){$ordenar = "level";}

$page = "
<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/ajuda_titulo.gif\" /></center></td></tr></table>
<center>[ <a href=\"help.php\">Voltar à ajuda</a> | <a href=\"index.php\">Voltar ao jogo</a> ]</center>

<br />

<table width=\"100%\" style=\"border: solid 1px black\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td colspan=\"9\" bgcolor=\"#ffffff\"><center><b>Monstros</b></center></td></tr>
<tr><td><b><a href=\"help_monsters.php?ord=name\" title=\"Ordenar\">Nome</a></b></td><td><b><a href=\"help_monsters.php?ord=maxhp\" title=\"Ordenar\">Max HP</a></b></td><td><b><a href=\"help_monsters.php?ord=maxdam\" title=\"Ordenar\">Max Dano</a></b></td><td><b><a href=\"help_monsters.php?ord=armor\" title=\"Ordenar\">Defesa</a></b></td><td><b><a href=\"help_monsters.php?ord=level\" title=\"Ordenar\">Level</a></b></td><td><b><a href=\"help_monsters.php?ord=maxexp\" title=\"Ordenar\">Max Exp</a></b></td><td><b><a href=\"help_monsters.php?ord=maxgold\" title=\"Ordenar\">Max Ryou</a></b></td><td><b><a href=\"help_monsters.php?ord=immune\" title=\"Ordenar\">Imunidade</a></b></td><td><b><a href=\"help_monsters.php?ord=elemento\" title=\"Ordenar\">Elem.</a></b></td></tr>
";
$count = 1;
$itemsquery = doquery("SELECT * FROM {{table}} ORDER BY $ordenar, level", "monsters");
while ($itemsrow = mysql_fetch_array($itemsquery)) {
    if ($count == 1) { $color = "bgcolor=\"#ffffff\""; $count = 2; } else { $color = ""; $count = 1; }
	if ($itemsrow['elemento'] == "agua"){$itemsrow['elemento'] == "Água";}
    if ($itemsrow["immune"] == 0) { $immune = "<span class=\"light\">None</span>"; } elseif ($itemsrow["immune"] == 1) { $immune = "Dano"; } else { $immune = "Dano & Sono"; }
    $page .= "<tr><td $color width=\"30%\">".$itemsrow["name"]."</td><td $color width=\"10%\">".$itemsrow["maxhp"]."</td><td $color width=\"10%\">".$itemsrow["maxdam"]."</td><td $color width=\"10%\">".$itemsrow["armor"]."</td><td $color width=\"10%\">".$itemsrow["level"]."</td><td $color width=\"10%\">".$itemsrow["maxexp"]."</td><td $color width=\"10%\">".$itemsrow["maxgold"]."</td><td $color width=\"20%\">$immune</td><td $color><img src=\"images/".$itemsrow['elemento'].".gif\" title=\"Elemento ".ucfirst($itemsrow['elemento'])."\"></td></tr>\n";
}
$page .= "
</table>";

display($page, "Ajuda - Inimigos", false, false, false); 

?>