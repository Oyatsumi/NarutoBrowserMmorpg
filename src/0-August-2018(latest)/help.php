<?php 
include('lib.php'); 
$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysqli_fetch_array($controlquery);

include('cookies.php');
$userrow = checkcookies();


$page = "

<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/ajuda_titulo.gif\" /></center></td></tr></table>
<center>[ <a href=\"index.php\">Voltar ao Jogo</a> ]</center>


<h3>Tabela do Conte?do</h3>
<ul>
<li /><a href=\"#intro\">Introdu??o</a>
<li /><a href=\"#classes\">Especializa??o</a>
<li /><a href=\"#difficulties\">N?veis de Dificuldade</a>
<li /><a href=\"#intown\">Jogando: Em uma cidade</a>
<li /><a href=\"#exploring\">Jogando: Explorando & Lutando</a>
<li /><a href=\"#status\">Jogando: Painel de Status</a>
<li /><a href=\"#items\">Spoilers: Itens & Drops</a>
<li /><a href=\"#monsters\">Spoilers: Monstros</a>
<li /><a href=\"#spells\">Spoilers: Jutsus</a>
<li /><a href=\"#levels\">Spoilers: Leveis</a>
<li /><a href=\"#credits\">Cr?ditos</a>
</ul>



<h3><a name=\"intro\"></a>Introdu??o</h3>
Em primeiro lugar, eu gostaria de dizer obrigado por jogar nosso jogo(em nome de toda comunidade Nigeru). O sistema de jogo Naruto! Nigeru RPG ? o resultado de v?rios meses de planejamento, codifica??o e testes. A id?ia original era criar um jogo baseado no universo do anime naruto. Na sua forma atual, apenas o nome dos personagens e suas propriedades realmente se assemelha ao jogo. Mas voc? ainda tomar? o conhecimento que parte da maioria das informa??es s?o f?cticias.

Este ? o primeiro jogo que n?s j? fizemos, pelo menos neste sistema, e foi definitivamente uma experi?ncia positiva. Foi dif?cil ?s vezes, mas ainda assim foi muito divertido de fazer, e ainda mais divertido para jogar. E eu espero usar essa experi?ncia para que voc?s jogadores apreciem o m?ximo e se divirtam.
.<br /><br />
Mais uma vez, obrigado por jogar!<br /><br />
<i>Oyatsumi e Devilolz</i><br />
<i>Programador e Resource Adder</i><br />
<a href=\"http://www.nigeru.com\"target=\"_new\">Nigeru Animes</a><br /><br />
[ <a href=\"#top\">Topo</a> ]

<br /><br />

<h3><a name=\"classes\"></a>Especializa??es dos Personagens</h3>
Existem tr?s especializa??es ou classes no jogo. As principais diferen?as entre as classes s?o Jutsus que voc? se tem acesso, a rapidez com que voc? sobe de n?vel, e a quantidade de HP/Chakra/For?a/Destreza que voc? ganha por n?vel. Abaixo est? um esquema b?sico de cada uma das classes de personagem. Para informa??es mais detalhadas sobre os personagens, por favor, veja a tabela de n?veis, na parte inferior desta p?gina.
<br /><br />
<b>".$controlrow["class1name"]."</b>
<ul>
  <li />R?pido de subir n?vel
    <li />Muitos Pontos de Vida  (HP)
    <li />Muitos Pontos de Chakra (CH)
    <li />Pouca For?a
    <li />Baixa Destreza
    <li />5 Jutsus de cura
    <li />5 Jutsus de dano
    <li />3 Jutsus de ilus?o
    <li />3 Jutsus de aumento de defesa
    <li />0 Jutsus de aumento de ataque 
</ul>
<b>".$controlrow["class2name"]."</b>
<ul>
<li />Velocidade m?dia de subir n?vel
    <li />Medianos Pontos de Vida (HP)
    <li />Poucos Pontos de Chakra  (CH)
    <li />Grande For?a
    <li />Pouca Destreza
    <li />3 Jutsus de cura
    <li />3 Jutsus de dano
    <li />2 Jutsus de ilus?o
    <li />3 Jutsus de aumento de defesa
    <li />3 Jutsus de aumento de ataque
</ul>
<b>".$controlrow["class3name"]."</b>
<ul>
    <li />Velocidade abaixo da m?dia de subir n?vel
    <li />Medianos Pontos de Vida  (HP)
    <li />Medianos Pontos de Chakra (CH)
    <li />Pouca For?a
    <li />Muita Destreza
    <li />4 Jutsus de cura
    <li />4 Jutsus de dano
    <li />3 Jutsus de ilus?o
    <li />2 Jutsus de aumento de defesa
    <li />2 Jutsus de aumento de ataque
</ul>
[ <a href=\"#top\">Topo</a> ]

<br /><br />

<h3><a name=\"difficulties\"></a>N?veis de Dificuldade</h3>
<i>".$controlrow["gamename"]."</i> inclui a habilidade de jogar com um dos tr?s n?veis de dificuldade. Todas as estat?sticas do monstro e do jogo s?o definidas em um n?mero base. No entanto, usando uma dificuldade elevada, algumas estat?sticas s?o aumentadas. A quantidade de pontos de vida de um monstro sobe, o que significa que vai demorar mais tempo para matar. Mas a quantidade de experi?ncia e de Ryou que voc? ganhar ao mat?-lo tamb?m sobe. Assim, o jogo se torna um pouco mais dif?cil, mas ? tamb?m mais compensador. A seguir est?o os tr?s n?veis de dificuldade e os seus multiplicadores, que se aplica a HP do monstro, experi?ncia e drop de Ryou...
<ul>
<li />".$controlrow["diff1name"].": <b>".$controlrow["diff1mod"]."</b>
<li />".$controlrow["diff2name"].": <b>".$controlrow["diff2mod"]."</b>(Ou seja, mais 20%)
<li />".$controlrow["diff3name"].": <b>".$controlrow["diff3mod"]."</b>(Ou seja, mais 50%)
</ul>
[ <a href=\"#top\">Topo</a> ]

<br /><br />

<h3><a name=\"intown\"></a>Jogando: Em uma cidade</h3>
Quando se come?a a jogar um novo jogo, a primeira coisa a se ver ? a cidade(os locais). As cidades(locais) tem seis fun??es b?sicas: Curar-se, Comprar itens, Comprar mapas, exebir informa??es do jogo, guardar itens no banco e envio de itens para outros jogadores.<br /><br />
Para curar-se, clique em 'Descansar numa pousada' no link do topo da tela. Pousadas de cada cidade tem pre?os diferente - algumas s?o baratas, outras s?o bem caras. N?o importa em qual cidade voc? est?, as pousadas tem sempre a mesma fun??o: Restaurar ao m?ximo seus pontos de vida/chakra/viagem. Explorando (no campo), voc? est? livre para usar magias de cura para restaurar seus pontos de vida, mas quando voc? estiver com poucos pontos de magia, a ?nica forma de restaur?-los ? em uma pousada. <br /><br />
Comprando bandanas e coletes: ? feita atrav?s do link chamado de 'comprar armas / coletes'. Nem todos os itens est?o dispon?veis em cada cidade, de modo a fim de obter os itens mais poderosos, voc? precisa explorar algumas cidades. Uma vez que voc? clicou no link, voc? ? apresentado ? uma lista de itens dispon?veis na loja desta cidade. ? esquerda de cada item haver? um ?cone que representa o seu tipo: arma, colete ou bandana. A quantidade de ataque / defesa, assim como os pre?os dos itens, s?o exibidos ? direita do nome do item. Voc? perceber? que alguns itens t?m um asterisco vermelho (<span class=\"highlight\">*</span>) ao lado de seus nomes. Estes s?o itens que vem com atributos especiais que modificam caracter?sticas do seu perfil do personagem. Veja a tabela Itens e Drops, na parte inferior da p?gina para obter mais informa??es sobre os itens especiais.<br /><br />
Mapas s?o a terceira fun??o nas cidades. Comprando um mapa de uma cidade voc? a coloca como op??o de viagem no painel ? esquerda. Uma vez comprado o mapa de uma cidade, voc? pode clicar no seu nome e voc? vai saltar direto para aquela cidade. Viajando desta forma voc? gastar? pontos de viagem, portanto, voc? s? vai poder viajar para muitas cidades se voc? tiver bastantes pontos de viagem.<br /><br />
Outra fun??o das cidades ? mostrar informa??es postas pela administra??o do servidor e estat?sticas. Isto inclui a lista de jogadores que est?o online e o Babble Box.<br /><br />

Por fim, mas n?o menos importante, as fun??es troca e banco, cujas s? funcionam <b>exclusivamente dentro das cidades</b>, para armazenar item no banco voc? pagar? uma taxa de 20 ryou, esta taxa ? fixa e n?o h? varia??o estando em qualquer cidade, pode-se armazenar al?m de itens, dinheiro (ryou), havendo tamb?m uma taxa de 20 ryou. S? poder? ser depositado 25% do total de seu dinheiro (ryou) por vez. <br /><br />

[ <a href=\"#top\">Topo</a> ]

<br /><br />

<h3><a name=\"exploring\"></a>Jogando: Explorando & Lutando</h3>
Depois que voc? aprendeu as fun??es da cidade, voc? est? livre para come?ar a explorar o mundo. Use a b?ssola, bot?es no painel de coordenadas ? esquerda para se mover. O mundo do jogo ? basicamente uma grande pra?a, dividida em quatro quadrantes. Cada quadrante tem ".$controlrow["gamesize"]." espa?os quadrados. A primeira cidade ? normalmente localizada em (0N, 0E). Clique no bot?o Norte da cidade e agora voc? vai estar em (1N, 0E). Da mesma forma, se voc? clicar no bot?o Oeste agora, voc? vai estar em (1N, 1W).Obs: Os n?veis dos monstros aumentam de acordo com o distanciamento da cidade incial.<br /><br />
Enquanto voc? est? explorando, voc? ir? ocasionalmente encontrar alguns monstros. Como em praticamente qualquer outro jogo de RPG, voc? e o monstro se revezar?o batendo uns aos outros na tentativa de reduzir cada um dos outros pontos de vida para zero. Uma vez que voc? encontrar um monstro, a tela muda de explora??o para a tela de combate.<br /><br />
Quando a luta come?a, voc? ver? o nome do monstro e pontos de vida e o jogo ir? pedir o seu primeiro comando. Em seguida, voc? come?a a escolher se voc? quer lutar, usar um Jutsu, ou fugir. Note, no entanto, que algumas vezes o monstro tem a chance de bat?-lo primeiro.<br /><br />
A Luta bot?o ? bem simples: voc? ataca o monstro, e o montante do dano causado ? baseada no seu poder de ataque e armadura do monstro. Em cima disso, h? duas outras coisas que podem acontecer: um excelente acerto, o que duplica o seu dano de ataque total, e o monstro escapar de seu ataque, o que resulta em voc? n?o causar nenhum dano ao monstro.<br /><br />
O bot?o de Jutsu permite que voc? escolha um Jutsu dispon?vel e lan??-lo. Veja a lista de Jutsus na parte inferior da p?gina para obter mais informa??es sobre Jutsus.<br /><br />
Finalmente, h? o bot?o Fugir, que lhe permite fugir de uma luta, se o monstro for muito poderoso. ? poss?vel que o monstro impede?a-o de correr e atacar. Portanto, se seus pontos de vida s?o baixos, voc? tende a ficar em torno de ?reas onde tem monstros que voc? sabe que n?o pode causar muito dano a voc?. Uma vez que voc? teve sua vez, o monstro tamb?m ter? a sua vez. Tamb?m ? poss?vel voc? esquivar do ataque do monstro e n?o ter preju?zo.<br /><br />
O resultado final de uma luta ? voc? ou o monstro sendo derrubado a zero pontos de vida. Se vencer, o monstro morre e vai lhe dar uma certa experi?ncia e ryou. H? tamb?m uma chance de que o monstro drope um item, que pode ser colocado em um dos tr?s slots de invent?rio para dar-lhe pontos extra no seu perfil do personagem. Se voc? perder e morrer, metade do seu ryou ? retirado - no entanto, voc? ter? metade de seus pontos de vida para ajud?-lo a voltar ? cidade (por exemplo, se voc? n?o tem ouro suficiente para pagar um Inn, h? a necessidade de matar um monstros de baixo n?vel para conseguir o dinheiro).<br /><br />
Quando a luta terminar, voc? pode continuar a explorar at? achar um novo monstro para batalhar.<br /><br />
[ <a href=\"#top\">Topo</a> ]

<br /><br />

<h3><a name=\"status\"></a>Jogando: Painel de Status</h3>
Existem dois pais de status: esquerda e direita.<br /><br />
O painel da esquerda mostra sua posi??o e seu status Na Cidade, Explorando, Lutando e a lista de viagem para ir ? outras cidades. Na parte inferior do painel da esquerda h? tamb?m uma lista de fun??es do jogo. O painel da direita mostra apenas o stats do personagem, seu invent?rio, e Jutsus de cura.<br /><br />
A se??o personagem mostra as estat?sticas mais importantes. Ele tamb?m exibe as barras de status para os seus pontos de vida atuais, pontos de magia e pontos de viagem. Essas barras de status s?o coloridas de verde, amarelo ou vermelho, dependendo do seu valor atual de cada stat.<br /><br />
A se??o r?pida de Jutsus permite voc? usar qualquer jutsu de cura que voc? aprendeu. Voc? pode usar estes Jutsus a qualquer momento, se voc? est? na cidade ou explorando. Estes n?o podem ser utilizadas durante as lutas. <br /><br />
[ <a href=\"#top\">Topo</a> ]

<br /><br />

<h3><a name=\"items\"></a>Spoilers: Itens & Drops</h3>
<a href=\"help_items.php\">Clique aqui</a> para ir ? a p?gina de Itens & Drops.<br /><br />
[ <a href=\"#top\">Topo</a> ]

<br /><br />

<h3><a name=\"monsters\"></a>Spoilers: Monstros</h3>
<a href=\"help_monsters.php\">Clique aqui</a> para ir ? a p?gina de Monstros.<br /><br />
[ <a href=\"#top\">Topo</a> ]

<br /><br />

<h3><a name=\"spells\"></a>Spoilers: Jutsus</h3>
<a href=\"help_spells.php\">Clique aqui</a> para ir ? pagina de Jutsus.<br /><br />
[ <a href=\"#top\">Topo</a> ]

<br /><br />

<h3><a name=\"levels\"></a>Spoilers: Leveis</h3>
<a href=\"help_levels.php\">Clique aqui</a> para ir ? pagina de Leveis.<br /><br />
[ <a href=\"#top\">Topo</a> ]

<br /><br />

<h3><a name=\"credits\"></a>Cr?ditos</h3>
<ul>
<li /><b>Lances de programa??o e design foram feitos por Oyatsumi</b>.<br /><br />
<li />Todo o conte?do do jogo foi adicionado e alterado por Devilolz Oyatsumi.<br /><br />
<li />O Rpg foi feito em conjunto com a comunidade Nigeru Animes.<br /><br />
<b>Id?ias:</b>
<ul>
<li />Devilolz
<li />Oyatsumi
<li />Ikky(Victor Hugo)
<li />Jagynowfly(Vin?cius)
<li />Shury(Cl?udio)
<li />Zangetsu(Felipe)
<li />Sasukee
</ul><br />
<b>Beta Testers:</b>
<ul>
<li />Devilolz
<li />Oyatsumi
<li />Ikky(Victor Hugo)
<li />Zangetsu(Felipe)
</ul><br />
<li />E claro, obrigado ? voc? por estar jogando.<br /><br />
</ul>
[ <a href=\"#top\">Topo</a> ]

<br /><br /><br />

Por favor visite os seguintes sites para maiores informa??es:<br />
<a href=\"http://www.nigeru.com\"target=\"_blank\">Nigeru Animes</a><br /><br />
[ <a href=\"#top\">Topo</a> ]";


display($page, "Ajuda", false, false, false); 

?>