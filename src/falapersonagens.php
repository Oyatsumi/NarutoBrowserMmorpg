<?php // users.php :: Handles user account functions.


/*$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);*/



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();
include('funcoesinclusas.php');


$missaoexplode = explode(",",$userrow["missao"]);

if (($missaoexplode[0] == 6) && ($userrow['latitude'] == -101) && ($userrow['longitude'] == 102)){
	global $valorlib, $indexconteudo;
	
	$fala = "Olá ".$userrow['charname']."! Você veio até aqui procurando algo sobre a arte eremita? Está falando com a pessoa certa! Muitos tentaram mas nunca conseguiram adquirir tamanho controle de energia natural, ao adquirir os poderes da arte eremita, você certamente estará em um nível superior... Uma hora será a sua vez! Treine árduamente até seu chakra esgotar e você será um sábio eremita algum dia! Leve essa informação até a Vila da Folha para completar a sua missão.";
	$indexconteudo = personagemgeral("$fala", 'personagem5', 'Mila', 'sem<br>');
	$valorlib = 1; //para não repetir o lib.php
	$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	include('index.php');
	die();
}


if (($missaoexplode[0] == 9) && ($userrow['latitude'] == 124) && ($userrow['longitude'] == -75)){
	global $valorlib, $indexconteudo;
	
	$fala = "Olá ".$userrow['charname']."! Gostaria de obter informações da Espada Raiga? Bem... Ao utilizar as Espadas Raiga, a mesma invoca a energia da naruteza Raiton. Sendo assim, seu golpe possui uma força muito elevada perante a todos os outros ataques Raiton por usar a fonte natural do próprio Raio. Ouvi rumores de que as Espadas Raiga podem ser obtidas por Alquimia de uma outra espada e um item de Konoha...";
	$indexconteudo = personagemgeral("$fala", 'personagem3', 'Nakima', 'sem<br>');
	$valorlib = 1; //para não repetir o lib.php
	$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	include('index.php');
	die();
}






if (($missaoexplode[0] == 12) && ($userrow['latitude'] == 5) && ($userrow['longitude'] == -8)){
	global $valorlib, $indexconteudo;
	
	$fala = "Olá ".$userrow['charname']."! Estão precisando da minha ajuda na Vila da Areia? Tudo bem! Já estou voltando até a Vila, vá na frente e avise a meu irmão que em breve estarei lá.";
	$indexconteudo = personagemgeral("$fala", 'temari', 'Temari', 'sem<br>');
	$valorlib = 1; //para não repetir o lib.php
	$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	include('index.php');
	die();
}




if (($missaoexplode[0] == 15)&& ($userrow['latitude'] == 171) && ($userrow['longitude'] == 171)){
	global $valorlib, $indexconteudo;
	
	$fala = "Olá ".$userrow['charname']."! Você já ouviu falar sobre Alquimia? A alquimia é uma arte onde é possível de certa forma, fundir materiais e originar um novo. Alguns itens com o prefixo 'Alma..' são possíveis de serem fundidos pela alquimia... Leve essa informação até a Vila da Folha para ajudar na finalização de sua missão.";
	$indexconteudo = personagemgeral("$fala", 'personagem6', 'Shinomori', 'sem<br>');
	$valorlib = 1; //para não repetir o lib.php
	$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	include('index.php');
	die();
}











if (($missaoexplode[0] == 18)&& ($userrow['latitude'] == 99) && ($userrow['longitude'] == 26)){
	global $valorlib, $indexconteudo;
	
	$fala = "Olá ".$userrow['charname']."! Eu ouvi rumores de que o cajado usado pelo terceiro Hokage ainda existe, e que ele possui poderes extraordinários... Você pode retornar até a Vila da Folha para completar sua missão...";
	$indexconteudo = personagemgeral("$fala", 'personagem9', 'Hikaru', 'sem<br>');
	$valorlib = 1; //para não repetir o lib.php
	$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	include('index.php');
	die();
}









if (($missaoexplode[0] == 21)&& ($userrow['latitude'] == 100) && ($userrow['longitude'] == -120)){
	global $valorlib, $indexconteudo;
	
	$fala = "Olá ".$userrow['charname']."! Aparentemente eu não me sentia muito bem na Vila da Névoa, há muita corrupção por lá e muito mistério... Quer dizer que a Mizukage está me procurando então? ... Vou voltar a Vila para ter conhecimento sobre o que ela quer comigo... Você pode voltar até a Vila para receber sua recompensa.";
	$indexconteudo = personagemgeral("$fala", 'personagem8', 'Mishigan', 'sem<br>');
	$valorlib = 1; //para não repetir o lib.php
	$updatequery = doquery("UPDATE {{table}} SET missaoswitch='1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	include('index.php');
	die();
}


header("Location: index.php");

?>