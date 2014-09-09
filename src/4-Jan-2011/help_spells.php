<?php 
include('lib.php'); 
$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);


include('cookies.php');
$userrow = checkcookies();

$ordenar = $_GET['ord'];
if ($ordenar == ""){$ordenar = "type";}

$page = "
<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/ajuda_titulo.gif\" /></center></td></tr></table>
<center>[ <a href=\"help.php\">Voltar à ajuda</a> | <a href=\"index.php\">Voltar ao jogo</a> ]</center>

<br />

<table width=\"100%\" style=\"border: solid 1px black\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td colspan=\"8\" bgcolor=\"#ffffff\"><center><b>Jutsus</b></center></td></tr>
<tr><td><a href=\"help_spells.php?ord=name\" title=\"Ordenar\"><b>Nome</b></td><td><a href=\"help_spells.php?ord=mp\" title=\"Ordenar\"><b>Custo</b></a></td><td><b><a href=\"help_spells.php?ord=type\" title=\"Ordenar\">Tipo</a></b></td><td><b><a href=\"help_spells.php?ord=attribute\" title=\"Ordenar\">Poder</a></b></td><td><a href=\"help_spells.php?ord=elemento\" title=\"Ordenar\">Elemento</a></td></tr>
";

$count = 1;
$itemsquery = doquery("SELECT * FROM {{table}} ORDER BY $ordenar, attribute", "spells");
while ($itemsrow = mysql_fetch_array($itemsquery)) {
    if ($count == 1) { $color = "bgcolor=\"#ffffff\""; $count = 2; } else { $color = ""; $count = 1; }
    if ($itemsrow["type"] == 1) { $type = "Medicinal"; }
    elseif ($itemsrow["type"] == 2) { $type = "Dano"; }
    elseif ($itemsrow["type"] == 3) { $type = "Sono"; }
    elseif ($itemsrow["type"] == 4) { $type = "+Dano (%)"; }
    elseif ($itemsrow["type"] == 5) { $type = "+Defesa (%)"; }
    $page .= "<tr><td $color width=\"25%\">".$itemsrow["name"]."</td><td $color width=\"25%\">".$itemsrow["mp"]."</td><td $color width=\"25%\">$type</td><td $color width=\"15%\">".$itemsrow["attribute"]."</td><td $color><center><img src=\"images/".$itemsrow['elemento'].".gif\" title=\"Elemento ".ucfirst($itemsrow['elemento'])."\"></center></td></tr>\n";
}
$page .= "
</table>
<ul>
<li /><b>Medicinal</b> esse tipo sempre te dá o maximo de cura possível.
<li /><b>Dano</b> esse tipo causa nem sempre o máximo dano ao monstro, dependendo da defesa do monstro.
<li /><b>Sono</b> esse tipo coloca o monstro pra dormir. O mesmo tem alguma chance em 15 de permanecer dormindo em cada turno, dependendo do nível do Jutsu.
<li /><b>+Dano</b> esse tipo aumenta o seu total de ataque pela porcentagem até o fim da luta.
<li /><b>+Defesa</b> esse tipo reduz o dano do ataque que você recebe pela porcentagem até o fim da luta.
</ul>
</center>";
display($page, "Ajuda - Jutsus", false, false, false); 
?>