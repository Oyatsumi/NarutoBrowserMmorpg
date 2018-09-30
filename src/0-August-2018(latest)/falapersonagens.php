<?php // users.php :: Handles user account functions.


/*$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysqli_fetch_array($controlquery);*/



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();
include('funcoesinclusas.php');


$missaoexplode = explode(",",$userrow["missao"]);

if (($missaoexplode[0] == 6) && ($userrow['latitude'] == -101) && ($userrow['longitude'] == 102)){
	global $valorlib, $indexconteudo;
	
	$fala = "Ol� ".$userrow['charname']."! Voc� veio at� aqui procurando algo sobre a arte eremita? Est� falando com a pessoa certa! Muitos tentaram mas nunca conseguiram adquirir tamanho controle de energia natural, ao adquirir os poderes da arte eremita, voc� certamente estar� em um n�vel superior... Uma hora ser� a sua vez! Treine �rduamente at� seu chakra esgotar e voc� ser� um s�bio eremita algum dia! Leve essa informa��o at� a Vila da Folha para completar a sua miss�o.";
	$indexconteudo = personagemgeral("$fala", 'personagem5', 'Mila', 'sem<br>');
	$valorlib = 1; //para n�o repetir o lib.php
	$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	include('index.php');
	die();
}


if (($missaoexplode[0] == 9) && ($userrow['latitude'] == 124) && ($userrow['longitude'] == -75)){
	global $valorlib, $indexconteudo;
	
	$fala = "Ol� ".$userrow['charname']."! Gostaria de obter informa��es da Espada Raiga? Bem... Ao utilizar as Espadas Raiga, a mesma invoca a energia da naruteza Raiton. Sendo assim, seu golpe possui uma for�a muito elevada perante a todos os outros ataques Raiton por usar a fonte natural do pr�prio Raio. Ouvi rumores de que as Espadas Raiga podem ser obtidas por Alquimia de uma outra espada e um item de Konoha...";
	$indexconteudo = personagemgeral("$fala", 'personagem3', 'Nakima', 'sem<br>');
	$valorlib = 1; //para n�o repetir o lib.php
	$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	include('index.php');
	die();
}






if (($missaoexplode[0] == 12) && ($userrow['latitude'] == 5) && ($userrow['longitude'] == -8)){
	global $valorlib, $indexconteudo;
	
	$fala = "Ol� ".$userrow['charname']."! Est�o precisando da minha ajuda na Vila da Areia? Tudo bem! J� estou voltando at� a Vila, v� na frente e avise a meu irm�o que em breve estarei l�.";
	$indexconteudo = personagemgeral("$fala", 'temari', 'Temari', 'sem<br>');
	$valorlib = 1; //para n�o repetir o lib.php
	$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	include('index.php');
	die();
}




if (($missaoexplode[0] == 15)&& ($userrow['latitude'] == 171) && ($userrow['longitude'] == 171)){
	global $valorlib, $indexconteudo;
	
	$fala = "Ol� ".$userrow['charname']."! Voc� j� ouviu falar sobre Alquimia? A alquimia � uma arte onde � poss�vel de certa forma, fundir materiais e originar um novo. Alguns itens com o prefixo 'Alma..' s�o poss�veis de serem fundidos pela alquimia... Leve essa informa��o at� a Vila da Folha para ajudar na finaliza��o de sua miss�o.";
	$indexconteudo = personagemgeral("$fala", 'personagem6', 'Shinomori', 'sem<br>');
	$valorlib = 1; //para n�o repetir o lib.php
	$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	include('index.php');
	die();
}











if (($missaoexplode[0] == 18)&& ($userrow['latitude'] == 99) && ($userrow['longitude'] == 26)){
	global $valorlib, $indexconteudo;
	
	$fala = "Ol� ".$userrow['charname']."! Eu ouvi rumores de que o cajado usado pelo terceiro Hokage ainda existe, e que ele possui poderes extraordin�rios... Voc� pode retornar at� a Vila da Folha para completar sua miss�o...";
	$indexconteudo = personagemgeral("$fala", 'personagem9', 'Hikaru', 'sem<br>');
	$valorlib = 1; //para n�o repetir o lib.php
	$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	include('index.php');
	die();
}









if (($missaoexplode[0] == 21)&& ($userrow['latitude'] == 100) && ($userrow['longitude'] == -120)){
	global $valorlib, $indexconteudo;
	
	$fala = "Ol� ".$userrow['charname']."! Aparentemente eu n�o me sentia muito bem na Vila da N�voa, h� muita corrup��o por l� e muito mist�rio... Quer dizer que a Mizukage est� me procurando ent�o? ... Vou voltar a Vila para ter conhecimento sobre o que ela quer comigo... Voc� pode voltar at� a Vila para receber sua recompensa.";
	$indexconteudo = personagemgeral("$fala", 'personagem8', 'Mishigan', 'sem<br>');
	$valorlib = 1; //para n�o repetir o lib.php
	$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	include('index.php');
	die();
}


header("Location: index.php");

?>