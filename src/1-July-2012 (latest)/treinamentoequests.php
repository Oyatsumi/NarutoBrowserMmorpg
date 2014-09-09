<?php // users.php :: Handles user account functions.


/*$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);*/


if ($valorlib == ""){//valor para nao redeclarar esses scripts.
include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();
}
include('funcoesinclusas.php');



		


if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "treinamento") { treinamento(); }
	elseif ($do == "quests") { quests(); }
	
	}

function treinamento($conteudodois) {
global $topvar;

$topvar = true;
$conteudo = $_GET['conteudo'];

    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
		global $userrow;

		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
	
					if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }					
					if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

	
//separando o treinamento.
$treinamento = explode(";",$userrow["treinamento"]);
$quantidade = count($treinamento) - 2;

for ($i = 0; $i <= $quantidade; $i++){
	$subtreino = explode(",",$treinamento[$i]);
	
	
	$off = "";
	$frase = "";
	//variaveis para a função em javascript.
	if ($subtreino[0] == "Jutsu de Busca"){
		$recompensa = "Utilização do Jutsu de Busca.";
		$requerimento = "Possuir o Byakugan como Jutsu Ocular.";	
		$localtreino = "Vila da Areia.";
		$linkdotreino = "<a href=\"jutsudebusca.php?do=aprendendo2\">"; $linkdotreino2 = "</a>";
		$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Treino não Disponível";}
    $townrow = mysql_fetch_array($townquery);
if ($townrow["id"] != 5) {$off = "2";$frase = "Treino não Disponível";}//vila da areia
	}
	elseif ($subtreino[0] == "Senjutsu"){
		$recompensa = "Aquisição da Arte Eremita, o Senjutsu. Quando ativado, lhe concederá 20% de aumento de Ataque e Destreza.";
		$requerimento = "Ser um Genin.";	
		$localtreino = "Montanha Myoboku.";
		$linkdotreino = "<a href=\"senjutsu.php?do=aprendendo2\">"; $linkdotreino2 = "</a>";
		$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Treino não Disponível";}
    $townrow = mysql_fetch_array($townquery);
if ($townrow["id"] != 2) {$off = "2";$frase = "Treino não Disponível";}//montanha myoboku
	}
	//fim jutsus separados.
	


	
	
	/*qual imagem...
	if ($subtreino[1] == $subtreino[2]){
		$qual = "aceitar.gif";	
	}else{$qual = "deletar.gif";}*/
	
	//porcentagem da pedra
	$porcentagem = ($subtreino[1] * 100)/$subtreino[2];
	if ($porcentagem == 0){$pedra = "<img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem > 0) && ($porcentagem < 20)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem >= 20) && ($porcentagem < 40)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem >= 40) && ($porcentagem < 60)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem >= 60) && ($porcentagem < 80)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem >= 80) && ($porcentagem < 100)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif ($porcentagem == 100){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";$off = "2";$frase = "Treino Concluído";}
	//fim porcentagem pedra.
	
		//tempo..
	include('funcoesinclusas.php');
	$retorno = tempojutsu($subtreino[3], $subtreino[4], $subtreino[5]);
	if ($retorno == "ok"){$relogio = "relogio2";$relogiotitle = "Tempo de Espera Concluído";}else{
		$relogio = "relogio"; $relogiotitle = "Restam ".$retorno." Minutos de Espera até o Próximo Treinamento";$off = "2";$frase = "Treino não Disponível";}
	if ($subtreino[1] == $subtreino[2]){$relogio = "relogio2";$relogiotitle = "Treinamento Concluído";$off = "2";$frase = "Treinamento Concluído";
	}//fim tempo.
	
	//sem link p treino
	if ($subtreino[1] == $subtreino[2]){$linkdotreino = ""; $linkdotreino2 = "";}	
	
	if ($frase == ""){$frase = "Treinar Agora";}
			
	$html .= "<table width=\"100%\"><tr bgcolor=\"#613003\"><td width=\"*\"><font color=\"white\">".$subtreino[0]."</font></td><td width=\"17\"><img src=\"images/".$relogio.".gif\" title=\"".$relogiotitle."\"></td><td width=\"17\">$linkdotreino<img src=\"images/treinar$off.gif\" title=\"$frase\" border=\"0\">$linkdotreino2</td><td width=\"84\">$pedra</td><td width=\"20\"><a href=\"javascript:mostrartreino('$i', '$recompensa', '$requerimento', '$localtreino')\"><img src=\"images/setabaixo.gif\" title=\"Mostrar Dados\" border=\"0\"></a></td><td width=\"20\"><a href=\"javascript:escondertreino('$i')\"><img src=\"images/setacima.gif\" title=\"Esconder Dados\" border=\"0\"></a></td></tr></table><div id=\"elemento$i\"></div><br>";

		
}//fim for
	
	//se não houver nada.
	if (($html == "") && ($conteudodois == "")){header('Location: ./index.php?conteudo=Você ainda não adquiriu nenhum treinamento.');die(); }
	
	//para aparecer a fala dentro do pergaminho.
	if(($conteudodois == "") && ($conteudo == "") && ($townrow['name'] == "")){$conteudodois = "<br>";}
	if(($conteudodois == "") && ($conteudo != "") && (mysql_num_rows($townquery) != 0)){$conteudodois = personagemgeral($conteudo, $townrow["id"],$townrow["kage"]); $conteudo = "";}
	elseif(($conteudodois == "") && ($conteudo == "") && ($townrow['name'] != "")){$conteudodois = personagemgeral('Olá '.$userrow['charname'].'! você está na(o) '.$townrow['name'].', pretende treinar algum Jutsu? Posso ajudar em alguma coisa? Use as opções abaixo para completar treinamentos.', $townrow["id"],$townrow["kage"]); $conteudo = "";}
	
	
			
	if ($conteudo != ""){$conteudo = "<center><font color=brown>".$conteudo."</font></center><br>";}
	
	$page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/treinamento.gif\" /></center></td></tr></table>
	$conteudo
	$conteudodois
	$html";
			 
			 display($page, "Treinamento", false, false, false);
	die();
	
	
	
	
}















































function quests($conteudodois) {
global $topvar;

$topvar = true;
$conteudo = $_GET['conteudo'];

    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
		global $userrow;

		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
	
					if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }					
					if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

	
//separando o missões aux.

$quests = explode(";",$userrow["questsaux"]);
$quantidade = count($quests) - 2;
if ($userrow["questsaux"] == "None"){$quantidade = 0;}

for ($i = 0; $i <= $quantidade - 1; $i++){
	$subtreino = explode(",",$quests[$i]);
		
	$off = "";
	$frase = "";
	
	
	$conclusao = "Não Obrigatória.";
	
	//variaveis para a função em javascript.
	if ($subtreino[0] == "Jutsu de Busca"){
		$recompensa = "Utilização do Jutsu de Busca.";
		$requerimento = "Nenhum.";	
		$localtreino = "Vila da Areia.";
		$nivel = "Rank ?";
		$linkdotreino = "<a href=\"jutsudebusca.php?do=aprendendo2\">"; $linkdotreino2 = "</a>";
		$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível";}
    $townrow = mysql_fetch_array($townquery);
if ($townrow["id"] != 5) {$off = "2";$frase = "Não Disponível";}//vila da areia
	}
	elseif ($subtreino[0] == "Senjutsu"){
		$recompensa = "Aquisição da Arte Eremita, o Senjutsu. Quando ativado, lhe concederá 10% de aumento de Ataque e Destreza.";
		$requerimento = "Ser um Genin.";	
		$localtreino = "Montanha Myoboku.";
		$nivel = "Rank ?";
		$linkdotreino = "<a href=\"senjutsu.php?do=aprendendo2\">"; $linkdotreino2 = "</a>";
		$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível";}
    $townrow = mysql_fetch_array($townquery);
if ($townrow["id"] != 2) {$off = "2";$frase = "Não Disponível";}//montanha myoboku
	}



	
	
	/*qual imagem...
	if ($subtreino[1] == $subtreino[2]){
		$qual = "aceitar.gif";	
	}else{$qual = "deletar.gif";}*/
	
	//porcentagem da pedra
	$porcentagem = ceil(($subtreino[1] * 100)/$subtreino[2]);
	if ($porcentagem == 0){$pedra = "<img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem > 0) && ($porcentagem < 20)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem >= 20) && ($porcentagem < 40)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem >= 40) && ($porcentagem < 60)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem >= 60) && ($porcentagem < 80)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem >= 80) && ($porcentagem < 100)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif ($porcentagem == 100){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";$off = "2";$frase = "Missão Concluída";}
	//fim porcentagem pedra.
	

	
		//tempo..
	include('funcoesinclusas.php');
	$retorno = tempojutsu($subtreino[3], $subtreino[4], $subtreino[5]);
	if ($retorno == "ok"){$relogio = "relogio2";$relogiotitle = "Tempo de Espera Concluído";
	}else{
		$relogio = "relogio"; $relogiotitle = "Restam ".$retorno." Minutos de Espera";$off = "2";$frase = "Não Disponível";}
	if ($subtreino[1] == $subtreino[2]){$relogio = "relogio2";$relogiotitle = "Missão Concluída";$off = "2";$frase = "Missão Concluída";
	}//fim tempo.
	
	//sem treino
	if ($subtreino[1] == $subtreino[2]){$linkdotreino = ""; $linkdotreino2 = "";}	


	if ($frase == ""){$frase = "Fazer Missão Agora";}
	
		//se não tiver o q treinar
	if (($subtreino[1] == 0) && ($subtreino[2] == 0)){
		$relogio = "relogio2"; $relogiotitle = "Sem Tempo de Espera para essa Missão";
		$off = "";$frase = "Completar Missão";
	}
	
			
	$html .= "<table width=\"100%\"><tr bgcolor=\"#613003\"><td width=\"*\"><font color=\"white\">".$subtreino[0]."</font></td><td width=\"17\"><img src=\"images/".$relogio.".gif\" title=\"".$relogiotitle."\"></td><td width=\"17\">$linkdotreino<img src=\"images/treinar$off.gif\" title=\"$frase\" border=\"0\">$linkdotreino2</td><td width=\"84\">$pedra</td><td width=\"20\"><a href=\"javascript:mostrarquest('$i"."aux"."', '$recompensa', '$requerimento', '$localtreino', '$nivel', '$conclusao')\"><img src=\"images/setabaixo.gif\" title=\"Mostrar Dados\" border=\"0\"></a></td><td width=\"20\"><a href=\"javascript:escondertreino('$i')\"><img src=\"images/setacima.gif\" title=\"Esconder Dados\" border=\"0\"></a></td></tr></table><div id=\"elemento$iaux\"></div><br>";

		
}//fim for









































//atualizando dados
global $userrow;
$atualizar = doquery("SELECT missao, missaoultdata FROM {{table}} WHERE charname='".$userrow["charname"]."' LIMIT 1", "users");
if (mysql_num_rows($atualizar) == 0) { display("Há um erro com sua conta. Por favor tente novamente.","Error"); die();}
$atualizardados = mysql_fetch_array($atualizar);
$userrow["missao"] = $atualizardados["missao"];
$userrow["missaoultdata"] = $atualizardados["missaoultdata"];
//fim do atualizando dados.


//separando o missões obrigatórias.
$quests = explode(",",$userrow["missao"]);
$quantidade = $quests[0];
$tempojutsuatual = explode(";", $userrow["missaoultdata"]);

for ($i = 1; $i <= $quantidade; $i++){
$subtreino = explode(",",$userrow["missao"]);
		
	$off = "";
	$frase = "";
	$porcentagem = 0;
	
	
	$conclusao = "Obrigatória.";
	

		//missão 1
		if ($i == 1){
			$subtreino[0] = "Missão ".$i.": Fartura do Fogo!";
			$recompensa = "Nenhuma.";
			$requerimento = "Levar o item <b>Fartura do Fogo</b> até a Vila da Folha.";
			$itemprocurado = ", 'Fartura do Fogo'";	//deixar formatação.
			$localtreino = "Vila da Folha.";
			$nivel = "Rank E.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 1) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		//missão 2
		if ($i == 2){
			$subtreino[0] = "Missão ".$i.": Lixos no Caminho!";
			$recompensa = "50 Ryou.";
			$requerimento = "Konoha está precisando de ajuda comunitária para limpeza da cidade. Colete o lixo 10 vezes à cada 1 minuto para completar essa missão.";
			$itemprocurado = "";	//deixar formatação.
			$localtreino = "Vila da Folha.";
			$nivel = "Rank E.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 1) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		
		//missão 3
		if ($i == 3){
			$subtreino[0] = "Missão ".$i.": Konoha Precisa de Ajuda!";
			$recompensa = "100 Ryou.";
			$requerimento = "Procure pelo <b>Lobo Rubro</b> que está causando problemas aos arredores da Vila da Folha, mate-o e venha imediatamente até a Vila da Folha.";
			$itemprocurado = ", '', '', 'Lobo Rubro'";	//deixar formatação.
			$localtreino = "Vila da Folha.";
			$nivel = "Rank E.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 1) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
	
		
		
		
		//missão 4
		if ($i == 4){
			$subtreino[0] = "Missão ".$i.": Bagunça Total!";
			$recompensa = "30 Ryou.";
			$requerimento = "Seu quarto está bagunçado. Arrume o seu quarto 5 vezes à cada 1 minuto para completar essa missão.";
			$itemprocurado = "";	//deixar formatação.
			$localtreino = "Vila da Folha.";
			$nivel = "Rank E.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 1) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		
		//missão 5
		if ($i == 5){
			$subtreino[0] = "Missão ".$i.": Boneca Perdida!";
			$recompensa = "100 Ryou.";
			$requerimento = "Uma criança da vila perdeu sua boneca. Você precisa levar o item <b>Boneca Perdida</b> até a Vila da Folha.";
			$itemprocurado = ", 'Boneca Perdida'";	//deixar formatação.
			$localtreino = "Vila da Folha.";
			$nivel = "Rank D.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 1) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		//missão 6
		if ($i == 6){
			$subtreino[0] = "Missão ".$i.": Arte Eremita!";
			$recompensa = "300 Ryou.";
			$requerimento = "Procure nas proximidades da Montanha Myoboku, alguém que possa te informar um pouco mais sobre a arte eremita, e então, traga o seu novo conhecimento até a Vila da Folha.";
			$itemprocurado = ", '', '-100/101'";	//deixar formatação.
			$localtreino = "Vila da Folha.";
			$nivel = "Rank D.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 1) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		//missão 7
		if ($i == 7){
			$subtreino[0] = "Missão ".$i.": Cabeça de Zabuza Momochi!";
			$recompensa = "350 Ryou.";
			$requerimento = "A Vila da Folha precisa que você procure e mate <b>Zabuza</b> e então volte imediatamente até a Vila da Folha para pegar sua recompensa.";
			$itemprocurado = "";	//deixar formatação.
			$localtreino = "Vila da Folha.";
			$nivel = "Rank C.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 1) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		
		
		
		//missão 8
		if ($i == 8){
			$subtreino[0] = "Missão ".$i.": Perigo à Solta!";
			$recompensa = "500 Ryou.";
			$requerimento = "Há uma infestação de roedores espalhados pela Montanha Myoboku, ajude a acabar com essa praga. Você deve realizar esse processo 10 vezes em intervalos de 6 minutos para completar essa missão.";
			$itemprocurado = "";	//deixar formatação.
			$localtreino = "Montanha Myoboku.";
			$nivel = "Rank D.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 2) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		





		//missão 9
		if ($i == 9){
			$subtreino[0] = "Missão ".$i.": Espadas Raiga!";
			$recompensa = "300 Ryou.";
			$requerimento = "Procure Nakami nas proximidades da Vila da Pedra, recolha informações sobre as Espadas Raiga que ela possui e então leve as informações até a Vila da Pedra para reconhecimento do Kage.";
			$itemprocurado = ", '', '124/-75'";	//deixar formatação.
			$localtreino = "Vila da Pedra.";
			$nivel = "Rank D.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 3) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		
		//missão 10
		if ($i == 10){
			$subtreino[0] = "Missão ".$i.": Rasto de Hachibi!";
			$recompensa = "1000 Ryou, <font color=\"grey\">(x1)</font>Máscara do Ritual.";
			$requerimento = "Leve até a Vila da Nuvem o item <b>Rastro de Hachibi</b>.";
			$itemprocurado = ", 'Rastro de Hachibi'";	//deixar formatação.
			$localtreino = "Vila da Nuvem.";
			$nivel = "Rank B.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 6) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		
		//missão 11
		if ($i == 11){
			$subtreino[0] = "Missão ".$i.": Passando Conhecimento!";
			$recompensa = "400 Ryou.";
			$requerimento = "Ajude um dos estudantes da academia a aprender um novo jutsu. Você deve fazer isso 5 vezes em intervalos de 12 minutos.";
			$itemprocurado = "";	//deixar formatação.
			$localtreino = "Vila da Nuvem.";
			$nivel = "Rank D.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 6) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}








		//missão 12
		if ($i == 12){
			$subtreino[0] = "Missão ".$i.": Compondo a Vila da Areia!";
			$recompensa = "5000 Ryou.";
			$requerimento = "A Vila da Areia está precisando que Temari retorne até a mesma. Encontre-a na proximidade da Vila da Folha. Para completar essa missão, também é necessário ajudar na missão de expedição à um campo de batalha da Vila da Areia, portanto, mantenha total suporte realizando essa missão 5 vezes em intervalos de 60 segundos.";
			$itemprocurado = ", '', '5/-8'";	//deixar formatação.
			$localtreino = "Vila da Areia.";
			$nivel = "Rank B.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 5) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}




		//missão 13
		if ($i == 13){
			$subtreino[0] = "Missão ".$i.": Em Busca de Nibi!";
			$recompensa = "400 Ryou.";
			$requerimento = "Procure por <b>Nibi</b> próximo à Vila da Névoa, mate-o e venha imediatamente até a Vila.";
			$itemprocurado = ", '', '', 'Nibi'";	//deixar formatação.
			$localtreino = "Vila da Névoa.";
			$nivel = "Rank B.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 4) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		//missão 14
		if ($i == 14){
			$subtreino[0] = "Missão ".$i.": Vingança do Som!";
			$recompensa = "400 Ryou.";
			$requerimento = "Mate <b>Jiroubou Selo 1</b> três vezes seguidas e venha imediatamente até a Vila da Pedra.";
			$itemprocurado = ", '', '', 'Jiroubou Selo 1'";	//deixar formatação.
			$localtreino = "Vila da Pedra.";
			$nivel = "Rank C.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 3) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		
		//missão 15
		if ($i == 15){
			$subtreino[0] = "Missão ".$i.": Flauta Surpresa!";
			$recompensa = "1000 Ryou, Item: <b>Flauta de Tayuya</b>.";
			$requerimento = "Obter o item <b>Runa de Chakra</b>, procurar por Shinomori próximo à Vila da Nuvem e trazer as informações disponibilizadas por ela até a Vila da Folha.";
			$itemprocurado = ", 'Runa de Chakra', '171/171'";	//deixar formatação.
			$localtreino = "Vila da Folha.";
			$nivel = "Rank B.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 1) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}





		//missão 16
		if ($i == 16){
			$subtreino[0] = "Missão ".$i.": Eliminando Pein #1!";
			$recompensa = "5000 Ryou.";
			$requerimento = "Derrotar <b>Ningendo</b>, um dos corpos de Pein e vá imediatamente até a Vila da Areia.";
			$itemprocurado = ", '', '', 'Ningendo'";	//deixar formatação.
			$localtreino = "Vila da Areia.";
			$nivel = "Rank A.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 5) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		
		
		
		
		
		
		//missão 17
		if ($i == 17){
			$subtreino[0] = "Missão ".$i.": Ajuda ao Kazekage!";
			$recompensa = "2000 Ryou.";
			$requerimento = "Ajude o Kazekage a organizar a função de todos os jounins da vila. Você deve realizar essa operação 10 vezes em intervalos de 6 minutos.";
			$itemprocurado = "";	//deixar formatação.
			$localtreino = "Vila da Areia.";
			$nivel = "Rank C.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 5) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		
		
		
		
		//missão 18
		if ($i == 18){
			$subtreino[0] = "Missão ".$i.": Cajado Enma!";
			$recompensa = "Item: <b>Cajado Enma</b>.";
			$requerimento = "Procure por Hikaru para adquirir informações sobre o <b>Cajado Enma</b> e retorne até a Vila da Folha.";
			$itemprocurado = ", 'Força Inútil', '99/26'";	//deixar formatação.
			$localtreino = "Vila da Folha.";
			$nivel = "Rank C.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 1) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		
		
		//missão 19
		if ($i == 19){
			$subtreino[0] = "Missão ".$i.": Ataque na Vila da Folha!";
			$recompensa = "Item: <b>Amuleto de Sapo</b>.";
			$requerimento = "A Vila da Folha precisa de ajuda, vá até a Montanha Myoboku e traga Fukasaku e Shima até a Vila da Folha.";
			$itemprocurado = "";	//deixar formatação.
			$localtreino = "Vila da Folha.";
			$nivel = "Rank B.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 1) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}



	
	
	
	
	
	
	
		//missão 20
		if ($i == 20){
			$subtreino[0] = "Missão ".$i.": Eliminando Pein #2!";
			$recompensa = "7000 Ryou, Item: <b>Anel da Sorte</b>.";
			$requerimento = "Derrotar <b>Gakido</b>, um dos corpos de Pein e vá imediatamente até a Vila da Folha.";
			$itemprocurado = ", '', '', 'Gakido'";	//deixar formatação.
			$localtreino = "Vila da Folha.";
			$nivel = "Rank A.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 1) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
		
		
		
		
		
		
		
		
		
		//missão 21
		if ($i == 21){
			$subtreino[0] = "Missão ".$i.": Em Busca do Desaparecido!";
			$recompensa = "Item: <b>Protetor de Honra da Névoa</b>.";
			$requerimento = "Localize Mishigan, converse com ele sobre o seu desaparecimento da Vila da Névoa e então volte até a Vila para receber sua recompensa, traga consigo o item <b>Alma da Névoa</b>.";
			$itemprocurado = ", 'Alma da Névoa'";	//deixar formatação.
			$localtreino = "Vila da Névoa.";
			$nivel = "Rank C.";
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
	if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Não Disponível"; }
		$townrow = mysql_fetch_array($townquery);
	if ($townrow["id"] != 4) {$off = "2";$frase = "Não Disponível";}//vila da folha
			if ($quests[0] > $i){$porcentagem = 100; $frase = "Missão Concluída"; $off = 2;}
		}
		
	//porcentagem da pedra
	if ($porcentagem != 100){if ($subtreino[1] == 0){$porcentagem = 0;}else{
	$porcentagem = ceil(($subtreino[1] * 100)/$subtreino[2]);}}
	if ($porcentagem == 0){$pedra = "<img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem > 0) && ($porcentagem < 20)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem >= 20) && ($porcentagem < 40)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem >= 40) && ($porcentagem < 60)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem >= 60) && ($porcentagem < 80)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif (($porcentagem >= 80) && ($porcentagem < 100)){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedravazia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";}
	elseif ($porcentagem == 100){$pedra = "<img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\"><img src=\"images/pedracheia.gif\" title=\"Porcentagem Concluída: ".$porcentagem."%\">";$off = "2";$frase = "Missão Concluída";}
	//fim porcentagem pedra.


	
		//tempo..
	include('funcoesinclusas.php');
	$retorno = tempojutsu($tempojutsuatual[0], $tempojutsuatual[1], $subtreino[3]);
	if (($retorno == "ok") || ($i < $quantidade)){$relogio = "relogio2";$relogiotitle = "Tempo de Espera Concluído"; $linkdotreino = "<a href=\"missoes.php?do=missao\">"; $linkdotreino2 = "</a>";
	if ($i < $quantidade){$linkdotreino = ""; $linkdotreino2 = "";}
	}else{$linkdotreino = "<a href=\"missoes.php?do=missao\">"; $linkdotreino2 = "</a>";
		$relogio = "relogio"; $relogiotitle = "Restam ".$retorno." Minutos de Espera";$off = "2";$frase = "Não Disponível"; }
	if ($subtreino[1] == $subtreino[2]){$linkdotreino = ""; $linkdotreino2 = ""; $relogio = "relogio2";$relogiotitle = "Missão Concluída";$off = "2";$frase = "Missão Concluída";
	}//fim tempo.
	
	
	if ($frase == ""){$frase = "Fazer Missão Agora";}
	
		//se não tiver o q treinar
	if (($subtreino[1] == 0) && ($subtreino[2] == 0)){
		$relogio = "relogio2"; $relogiotitle = "Sem Tempo de Espera para essa Missão";
		$off = "";$frase = "Completar Missão";
		$linkdotreino = "<a href=\"missoes.php?do=missao\">"; 
		$linkdotreino2 = "</a>";
	}
	
	if ($i < $quantidade){
			$linkdotreino = ""; $linkdotreino2 = "";
			$frase = "Missão Concluída";
			$relogiotitle = "Missão Concluída";
			$relogio = "relogio2";
			$off = 2;
	}
			
	$html .= "<table width=\"100%\"><tr bgcolor=\"#613003\"><td width=\"*\"><font color=\"white\">".$subtreino[0]."</font></td><td width=\"17\"><img src=\"images/".$relogio.".gif\" title=\"".$relogiotitle."\"></td><td width=\"17\">$linkdotreino<img src=\"images/treinar$off.gif\" title=\"$frase\" border=\"0\">$linkdotreino2</td><td width=\"84\">$pedra</td><td width=\"20\"><a href=\"javascript:mostrarquest('$i', '$recompensa', '$requerimento', '$localtreino', '$nivel', '$conclusao'$itemprocurado)\"><img src=\"images/setabaixo.gif\" title=\"Mostrar Dados\" border=\"0\"></a></td><td width=\"20\"><a href=\"javascript:escondertreino('$i')\"><img src=\"images/setacima.gif\" title=\"Esconder Dados\" border=\"0\"></a></td></tr></table><div id=\"elemento$i\"></div><br>";

}//fim for





















	
	//se não houver nada.
	if (($html == "") && ($conteudodois == "")){header('Location: ./index.php?conteudo=Você ainda não adquiriu nenhuma missão.');die(); }
	
	
	//para aparecer as falas dentro do pergaminho, caso não haja nada e caso seja uma mensagem vermelha.
	if(($conteudodois == "") && ($conteudo == "") && ($townrow['name'] == "")){$conteudodois = "<br>";}
	if(($conteudodois == "") && ($conteudo != "") && (mysql_num_rows($townquery) != 0)){$conteudodois = personagemgeral($conteudo, $townrow["id"],$townrow["kage"]); $conteudo = "";}
	elseif(($conteudodois == "") && ($conteudo == "") && ($townrow['name'] != "")){$conteudodois = personagemgeral('Olá '.$userrow['charname'].'! Você está na(o) '.$townrow['name'].', pretende completar alguma missão? Posso ajudar em alguma coisa? Use as opções abaixo para completar missões.', $townrow["id"],$townrow["kage"]); $conteudo = "";}
	
	
	if ($conteudo != ""){$conteudo = "<center><font color=brown>".strip_tags($conteudo)."</font></center><br>";}
		
	
	$page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/missao.gif\" /></center></td></tr></table>
	$conteudo
	$conteudodois
	$html";
			 
			 display($page, "Missões", false, false, false);
	die();
	
	
	
	
}


?>