<?php // explore.php :: Handles all map exploring, chances to fight, etc.



include('funcoesinclusas.php');

function conteudo($townrow){

if ($townrow["id"] == 5){$add3 = "<a href=\"jutsudebusca.php?do=jutsu\"><img src=\"images/24/busca.gif\" border=\"0\" title=\"Treinar 'Jutsu de Busca'\"></a>";}
elseif ($townrow["id"] == 2){$add3 = "<a href=\"senjutsu.php?do=jutsu\"><img src=\"images/24/senjutsu.gif\" border=\"0\" title=\"Treinar Senjutsu\"></a><a href=\"senjutsu.php?do=chamar\"><img src=\"images/24/chamar.gif\" border=\"0\" title=\"Chamar Fukasaku & Shima\"></a>";}

//opções de cada cidade.
if ($townrow['id'] != 8) {$add1 .= "<a href=\"index.php?do=maps\"><img src=\"images/24/mapa.gif\" border=\"0\" title=\"Comprar Mapas\"></a>";
}
if (($townrow['id'] == 1) || ($townrow['id'] == 3)){$add2 .= "<a href=\"graduacao.php?do=graduacao\"><img src=\"images/24/graduacao.gif\" border=\"0\" title=\"Graduar-se\"></a>";}
if (($townrow['id'] == 2) || ($townrow['id'] == 5)){ $add3 .= "<a href=\"alquimia.php?do=fundir\"><img src=\"images/24/alquimia.gif\" border=\"0\" title=\"Alquimia de Itens\"></a>";}
//fim das opções de cada cidade.


$cima = "<table width=\"80%\" style=\"border:1px #452202 solid\" background=\"images/fundocidade.png\"><tr><td bgcolor=\"#452202\"><font color=\"white\">Compra e Venda</font></td></tr>
<tr><td><a href=\"index.php?do=buy\"><img src=\"images/24/shop.gif\" border=\"0\" title=\"Comprar Arma/Colete/Bandana\"></a>$add1</td></tr></table>";

$esquerda = "<table width=\"80%\" style=\"border:1px #452202 solid\" background=\"images/fundocidade.png\"><tr><td bgcolor=\"#452202\"><font color=\"white\">Administrativos</font></td></tr>
<tr><td><a href=\"funcaoitens.php?do=banco\"><img src=\"images/24/banco.gif\" border=\"0\" title=\"Acessar o Banco\"></a><a href=\"treinamentoequests.php?do=quests\"><img src=\"images/24/missoes.gif\" border=\"0\" title=\"Completar Missoes\"></a>$add2</td></tr></table>";

$direita = "<table width=\"90%\" style=\"border:1px #452202 solid\" background=\"images/fundocidade.png\"><tr><td bgcolor=\"#452202\"><font color=\"white\">Outros</font></td></tr>
<tr><td><a href=\"index.php?do=inn\"><img src=\"images/24/pousada.gif\" border=\"0\" title=\"Descansar numa Pousada\"></a>$add3</td></tr></table>";


return "<center><table width=\"487\" height=\"284\" background=\"images/meiocidade_".$townrow['id'].".jpg\" style=\"background-repeat:no-repeat;background-position:center center;\"><tr><td><br><br><br><br><br><br>$esquerda</td><td><br><br><br><br><br><br><br><br>$cima</td><td><br><br><br><br><br><br><br><br><br>$direita</td></tr></table></center>";

}
?>