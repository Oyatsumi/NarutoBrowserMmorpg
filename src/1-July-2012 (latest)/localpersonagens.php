<?php // local dos personagens.

global $opcoesnovas, $userrow;
$missaoexplode = explode(",",$userrow["missao"]);

//personagem 1
if (($missaoexplode[0] == 6) && ($userrow['latitude'] == -101) && ($userrow['longitude'] == 102)){$opcoesnovas = "<a href=\"falapersonagens.php\"><img src=\"images/24/conversar.gif\" border=\"0\" title=\"Conversar\" alt=\"X\"></a>";
}else{$opcoesnovas = "";}
if (($missaoexplode[0] == 6) && ($i == -101) && ($j == 102)){
$matriz[$i][$j]["imagem"] = "images/24/arvore_personagem1.gif";
$matriz[$i][$j]["title2"] = "NPC: Mila";
$matriz[$i][$j]["imagemtd"] = ""; 
}
//fim do personagem 1




//personagem 2
if (($missaoexplode[0] == 9) && ($userrow['latitude'] == 124) && ($userrow['longitude'] == -75)){$opcoesnovas = "<a href=\"falapersonagens.php\"><img src=\"images/24/conversar.gif\" border=\"0\" title=\"Conversar\" alt=\"X\"></a>";
}else{$opcoesnovas = "";}
if (($missaoexplode[0] == 9) && ($i == 124) && ($j == -75)){
$matriz[$i][$j]["imagem"] = "images/24/arvore_personagem2.gif";
$matriz[$i][$j]["title2"] = "NPC: Nakima";
$matriz[$i][$j]["imagemtd"] = ""; 
}
//fim do personagem 2






//personagem 3
if (($missaoexplode[0] == 12) && ($userrow['latitude'] == 5) && ($userrow['longitude'] == -8)){$opcoesnovas = "<a href=\"falapersonagens.php\"><img src=\"images/24/conversar.gif\" border=\"0\" title=\"Conversar\" alt=\"X\"></a>";
}else{$opcoesnovas = "";}
if (($missaoexplode[0] == 12) && ($i == 5) && ($j == -8)){
$matriz[$i][$j]["imagem"] = "images/24/arvore_personagem3.gif";
$matriz[$i][$j]["title2"] = "NPC: Temari";
$matriz[$i][$j]["imagemtd"] = ""; 
}
//fim do personagem 3





//personagem 4
if (($missaoexplode[0] == 15) && ($userrow['latitude'] == 171) && ($userrow['longitude'] == 171)){$opcoesnovas = "<a href=\"falapersonagens.php\"><img src=\"images/24/conversar.gif\" border=\"0\" title=\"Conversar\" alt=\"X\"></a>";
}else{$opcoesnovas = "";}
if (($missaoexplode[0] == 15) && ($i == 171) && ($j == 171)){
$matriz[$i][$j]["imagem"] = "images/24/arvore_personagem4.gif";
$matriz[$i][$j]["title2"] = "NPC: Shinomori";
$matriz[$i][$j]["imagemtd"] = ""; 
}
//fim do personagem 4






//personagem 5
if (($missaoexplode[0] == 18) && ($userrow['latitude'] == 99) && ($userrow['longitude'] == 26)){$opcoesnovas = "<a href=\"falapersonagens.php\"><img src=\"images/24/conversar.gif\" border=\"0\" title=\"Conversar\" alt=\"X\"></a>";
}else{$opcoesnovas = "";}
if (($missaoexplode[0] == 18) && ($i == 99) && ($j == 26)){
$matriz[$i][$j]["imagem"] = "images/24/arvore_personagem5.gif";
$matriz[$i][$j]["title2"] = "NPC: Hikaru";
$matriz[$i][$j]["imagemtd"] = ""; 
}
//fim do personagem 5









//personagem 6
if (($missaoexplode[0] == 21) && ($userrow['latitude'] == 100) && ($userrow['longitude'] == -120)){$opcoesnovas = "<a href=\"falapersonagens.php\"><img src=\"images/24/conversar.gif\" border=\"0\" title=\"Conversar\" alt=\"X\"></a>";
}else{$opcoesnovas = "";}
if (($missaoexplode[0] == 21) && ($i == 100) && ($j == -120)){
$matriz[$i][$j]["imagem"] = "images/24/arvore_personagem6.gif";
$matriz[$i][$j]["title2"] = "NPC: Mishigan";
$matriz[$i][$j]["imagemtd"] = ""; 
}
//fim do personagem 6
?>