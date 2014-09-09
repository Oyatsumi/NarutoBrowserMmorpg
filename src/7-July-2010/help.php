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
<h1><? echo $controlrow["gamename"]; ?> Ajuda</h1>
[ <a href="index.php">Voltar ao Jogo</a> ]

<br /><br />

<h3>Tabela do Conteúdo</h3>
<ul>
<li /><a href="#intro">Introdução</a>
<li /><a href="#classes">Especialização</a>
<li /><a href="#difficulties">Dificuldade de Leveis</a>
<li /><a href="#intown">Jogando: Em uma cidade</a>
<li /><a href="#exploring">Jogando: Explorando & Lutando</a>
<li /><a href="#status">Jogando: Painel de Status</a>
<li /><a href="#items">Spoilers: Itens & Drops</a>
<li /><a href="#monsters">Spoilers: Monstros</a>
<li /><a href="#spells">Spoilers: Jutsus</a>
<li /><a href="#levels">Spoilers: Leveis</a>
<li /><a href="#credits">Créditos</a>
</ul>



<h3><a name="intro"></a>Introdução</h3>
Em primeiro lugar, eu gostaria de dizer obrigado por jogar nosso jogo(em nome de toda comunidade Nigeru). O sistema de jogo Naruto! Nigeru RPG é o resultado de vários meses de planejamento, codificação e testes. A idéia original era criar um jogo baseado no universo do anime naruto. Na sua forma atual, apenas o nome dos personagens e suas propriedades realmente se assemelha ao jogo. Mas você ainda tomará o conhecimento que parte da maioria das informações são fícticias.

Este é o primeiro jogo que nós já fizemos, pelo menos neste sistema, e foi definitivamente uma experiência positiva. Foi difícil às vezes, mas ainda assim foi muito divertido de fazer, e ainda mais divertido para jogar. E eu espero usar essa experiência para que vocês jogadores apreciem o máximo e se divirtam.
.<br /><br />
Mais uma vez, obrigado por jogar!<br /><br />
<i>Oyatsumi e Devilolz</i><br />
<i>Programador e Resource Adder</i><br />
<a href="http://www.nigeru.com" target="_new">Nigeru Animes</a><br /><br />
[ <a href="#top">Topo</a> ]

<br /><br />

<h3><a name="classes"></a>Especializações dos Personagens</h3>
Existem três especializações ou classes no jogo. As principais diferenças entre as classes são Jutsus que você se tem acesso, a rapidez com que você sobe de nível, e a quantidade de HP/Chakra/Força/Destreza que você ganha por nível. Abaixo está um esquema básico de cada uma das classes de personagem. Para informações mais detalhadas sobre os personagens, por favor, veja a tabela de níveis, na parte inferior desta página.
<br /><br />
<b><? echo $controlrow["class1name"]; ?></b>
<ul>
  <li />Rápido de subir nível
    <li />Muitos Pontos de Vida  (HP)
    <li />Muitos Pontos de Chakra (CH)
    <li />Pouca Força
    <li />Baixa Destreza
    <li />5 Jutsus de cura
    <li />5 Jutsus de dano
    <li />3 Jutsus de ilusão
    <li />3 Jutsus de aumento de defesa
    <li />0 Jutsus de aumento de ataque 
</ul>
<b><? echo $controlrow["class2name"]; ?></b>
<ul>
<li />Velocidade média de subir nível
    <li />Medianos Pontos de Vida (HP)
    <li />Poucos Pontos de Chakra  (CH)
    <li />Grande Força
    <li />Pouca Destreza
    <li />3 Jutsus de cura
    <li />3 Jutsus de dano
    <li />2 Jutsus de ilusão
    <li />3 Jutsus de aumento de defesa
    <li />3 Jutsus de aumento de ataque
</ul>
<b><? echo $controlrow["class3name"]; ?></b>
<ul>
    <li />Velocidade abaixo da média de subir nível
    <li />Medianos Pontos de Vida  (HP)
    <li />Medianos Pontos de Chakra (CH)
    <li />Pouca Força
    <li />Muita Destreza
    <li />4 Jutsus de cura
    <li />4 Jutsus de dano
    <li />3 Jutsus de ilusão
    <li />2 Jutsus de aumento de defesa
    <li />2 Jutsus de aumento de ataque
</ul>
[ <a href="#top">Topo</a> ]

<br /><br />

<h3><a name="difficulties"></a>Difficulty Levels</h3>
<i><? echo $controlrow["gamename"]; ?></i> inclui a habilidade de jogar com um dos três níveis de dificuldade. Todas as estatísticas do monstro e do jogo são definidas em um número base. No entanto, usando uma dificuldade elevada, algumas estatísticas são aumentadas. A quantidade de pontos de vida de um monstro sobe, o que significa que vai demorar mais tempo para matar. Mas a quantidade de experiência e de Ryou que você ganhar ao matá-lo também sobe. Assim, o jogo se torna um pouco mais difícil, mas é também mais compensador. A seguir estão os três níveis de dificuldade e os seus multiplicadores, que se aplica a HP do monstro, experiência e drop de Ryou...
<ul>
<li /><? echo $controlrow["diff1name"] . ": <b>" . $controlrow["diff1mod"] . "</b>"; ?>
<li /><? echo $controlrow["diff2name"] . ": <b>" . $controlrow["diff2mod"] . "</b>(Ou seja, mais 20%)"; ?>
<li /><? echo $controlrow["diff3name"] . ": <b>" . $controlrow["diff3mod"] . "</b>(Ou seja, mais 50%)"; ?>
</ul>
[ <a href="#top">Topo</a> ]

<br /><br />

<h3><a name="intown"></a>Jogando: Em uma cidade</h3>
Quando se começa a jogar um novo jogo, a primeira coisa a se ver é a cidade(os locais). As cidades(locais) tem seis funções básicas: Curar-se, Comprar itens, Comprar mapas, exebir informações do jogo, guardar itens no banco e envio de itens para outros jogadores.<br /><br />
Para curar-se, clique em "Descansar numa pousada" no link do topo da tela. Pousadas de cada cidade tem preços diferente - algumas são baratas, outras são bem caras. Não importa em qual cidade você está, as pousadas tem sempre a mesma função: Restaurar ao máximo seus pontos de vida/chakra/viagem. Explorando (no campo), você está livre para usar magias de cura para restaurar seus pontos de vida, mas quando você estiver com poucos pontos de magia, a única forma de restaurá-los é em uma pousada. <br /><br />
Comprando bandanas e coletes: é feita através do link chamado de "comprar armas / coletes". Nem todos os itens estão disponíveis em cada cidade, de modo a fim de obter os itens mais poderosos, você precisa explorar algumas cidades. Uma vez que você clicou no link, você é apresentado à uma lista de itens disponíveis na loja desta cidade. À esquerda de cada item haverá um ícone que representa o seu tipo: arma, colete ou bandana. A quantidade de ataque / defesa, assim como os preços dos itens, são exibidos à direita do nome do item. Você perceberá que alguns itens têm um asterisco vermelho (<span class="highlight">*</span>) ao lado de seus nomes. Estes são itens que vem com atributos especiais que modificam características do seu perfil do personagem. Veja a tabela Itens e Drops, na parte inferior da página para obter mais informações sobre os itens especiais.<br /><br />
Mapas são a terceira função nas cidades. Comprando um mapa de uma cidade você a coloca como opção de viagem no painel à esquerda. Uma vez comprado o mapa de uma cidade, você pode clicar no seu nome e você vai saltar direto para aquela cidade. Viajando desta forma você gastará pontos de viagem, portanto, você só vai poder viajar para muitas cidades se você tiver bastantes pontos de viagem.<br /><br />
Outra função das cidades é mostrar informações postas pela administração do servidor e estatísticas. Isto inclui a lista de jogadores que estão online e o Babble Box.<br /><br />

Por fim, mas não menos importante, as funções troca e banco, cujas só funcionam <b>exclusivamente dentro das cidades</b>, para armazenar item no banco você pagará uma taxa de 20 ryou, esta taxa é fixa e não há variação estando em qualquer cidade, pode-se armazenar além de itens, dinheiro (ryou), havendo também uma taxa de 20 ryou. Só poderá ser depositado 25% do total de seu dinheiro (ryou) por vez. <br /><br />

[ <a href="#top">Topo</a> ]

<br /><br />

<h3><a name="exploring"></a>Jogando: Explorando & Lutando</h3>
Depois que você aprendeu as funções da cidade, você está livre para começar a explorar o mundo. Use a bússola, botões no painel de coordenadas à esquerda para se mover. O mundo do jogo é basicamente uma grande praça, dividida em quatro quadrantes. Cada quadrante tem <? echo $controlrow["gamesize"]; ?> espaços quadrados. A primeira cidade é normalmente localizada em (0N, 0E). Clique no botão Norte da cidade e agora você vai estar em (1N, 0E). Da mesma forma, se você clicar no botão Oeste agora, você vai estar em (1N, 1W).Obs: Os níveis dos monstros aumentam de acordo com o distanciamento da cidade incial.<br /><br />
Enquanto você está explorando, você irá ocasionalmente encontrar alguns monstros. Como em praticamente qualquer outro jogo de RPG, você e o monstro se revezarão batendo uns aos outros na tentativa de reduzir cada um dos outros pontos de vida para zero. Uma vez que você encontrar um monstro, a tela muda de exploração para a tela de combate.<br /><br />
Quando a luta começa, você verá o nome do monstro e pontos de vida e o jogo irá pedir o seu primeiro comando. Em seguida, você começa a escolher se você quer lutar, usar um Jutsu, ou fugir. Note, no entanto, que algumas vezes o monstro tem a chance de batê-lo primeiro.<br /><br />
A Luta botão é bem simples: você ataca o monstro, e o montante do dano causado é baseada no seu poder de ataque e armadura do monstro. Em cima disso, há duas outras coisas que podem acontecer: um excelente acerto, o que duplica o seu dano de ataque total, e o monstro escapar de seu ataque, o que resulta em você não causar nenhum dano ao monstro.<br /><br />
O botão de Jutsu permite que você escolha um Jutsu disponível e lançá-lo. Veja a lista de Jutsus na parte inferior da página para obter mais informações sobre Jutsus.<br /><br />
Finalmente, há o botão Fugir, que lhe permite fugir de uma luta, se o monstro for muito poderoso. É possível que o monstro impedeça-o de correr e atacar. Portanto, se seus pontos de vida são baixos, você tende a ficar em torno de áreas onde tem monstros que você sabe que não pode causar muito dano a você. Uma vez que você teve sua vez, o monstro também terá a sua vez. Também é possível você esquivar do ataque do monstro e não ter prejuízo.<br /><br />
O resultado final de uma luta é você ou o monstro sendo derrubado a zero pontos de vida. Se vencer, o monstro morre e vai lhe dar uma certa experiência e ryou. Há também uma chance de que o monstro drope um item, que pode ser colocado em um dos três slots de inventário para dar-lhe pontos extra no seu perfil do personagem. Se você perder e morrer, metade do seu ryou é retirado - no entanto, você terá metade de seus pontos de vida para ajudá-lo a voltar à cidade (por exemplo, se você não tem ouro suficiente para pagar um Inn, há a necessidade de matar um monstros de baixo nível para conseguir o dinheiro).<br /><br />
Quando a luta terminar, você pode continuar a explorar até achar um novo monstro para batalhar.<br /><br />
[ <a href="#top">Topo</a> ]

<br /><br />

<h3><a name="status"></a>Jogando: Painel de Status</h3>
Existem dois pais de status: esquerda e direita.<br /><br />
O painel da esquerda mostra sua posição e seu status Na Cidade, Explorando, Lutando e a lista de viagem para ir à outras cidades. Na parte inferior do painel da esquerda há também uma lista de funções do jogo. O painel da direita mostra apenas o stats do personagem, seu inventário, e Jutsus de cura.<br /><br />
A seção "personagem" mostra as estatísticas mais importantes. Ele também exibe as barras de status para os seus pontos de vida atuais, pontos de magia e pontos de viagem. Essas barras de status são coloridas de verde, amarelo ou vermelho, dependendo do seu valor atual de cada stat.<br /><br />
A seção rápida de Jutsus permite você usar qualquer jutsu de cura que você aprendeu. Você pode usar estes Jutsus a qualquer momento, se você está na cidade ou explorando. Estes não podem ser utilizadas durante as lutas. <br /><br />
[ <a href="#top">Topo</a> ]

<br /><br />

<h3><a name="items"></a>Spoilers: Itens & Drops</h3>
<a href="help_items.php">Clique aqui</a> para ir à a página de Itens & Drops.<br /><br />
[ <a href="#top">Topo</a> ]

<br /><br />

<h3><a name="monsters"></a>Spoilers: Monstros</h3>
<a href="help_monsters.php">Clique aqui</a> para ir à a página de Monstros.<br /><br />
[ <a href="#top">Topo</a> ]

<br /><br />

<h3><a name="spells"></a>Spoilers: Jutsus</h3>
<a href="help_spells.php">Clique aqui</a> para ir à pagina de Jutsus.<br /><br />
[ <a href="#top">Topo</a> ]

<br /><br />

<h3><a name="levels"></a>Spoilers: Leveis</h3>
<a href="help_levels.php">Clique aqui</a> para ir à pagina de Leveis.<br /><br />
[ <a href="#top">Topo</a> ]

<br /><br />

<h3><a name="credits"></a>Créditos</h3>
<ul>
<li /><b>Lances de programação e design foram feitos por Oyatsumi</b>.<br /><br />
<li />Todo o conteúdo do jogo foi adicionado e alterado por Devilolz Oyatsumi.<br /><br />
<li />O Rpg foi feito em conjunto com a comunidade Nigeru Animes.<br /><br />
<b>Idéias:</b>
<ul>
<li />Devilolz
<li />Oyatsumi
<li />Ikky(Victor Hugo)
<li />Jagynowfly(Vinícius)
<li />Shury(Cláudio)
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
<li />E claro, obrigado à você por estar jogando.<br /><br />
</ul>
[ <a href="#top">Topo</a> ]

<br /><br /><br />

Por favor visite os seguintes sites para maiores informações:<br />
<a href="http://www.nigeru.com" target="_blank">Nigeru Animes</a><br /><br />
[ <a href="#top">Topo</a> ]


<br /><br />
<center><table width="90%"><tr>
<td width="25%" align="center">Powered by <a href="http://nigeru.com" target="_new">Nigeru Animes</a></td><td width="25%" align="center">&copy; 2010 by Oyatsumi</td>
</tr></table></center>
</td>
<td width="56"></td>
</tr>
<tr>
<td colspan="3" background="layoutnovo/menumeio/baixo.png" width="671" height="62"></td></tr>
</table>

</td></tr></table></center>
</body>

</html>