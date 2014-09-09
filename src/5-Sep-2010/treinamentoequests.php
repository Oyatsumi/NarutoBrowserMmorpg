<?php // users.php :: Handles user account functions.


/*$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);*/


if ($valorlib == ""){//valor para nao redeclarar esses scripts.
include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();
}



		


if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "treinamento") { treinamento(); }
	
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
	
					if ($userrow["currentaction"] == "Fighting") {header('Location: /narutorpg/index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }					
					if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

	
//separando o treinamento.
$treinamento = explode(";",$userrow["treinamento"]);
$quantidade = count($treinamento) - 2;

$updatequery = doquery("UPDATE {{table}} SET imagem='treinando.png' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");

for ($i = 0; $i <= $quantidade; $i++){
	$subtreino = explode(",",$treinamento[$i]);
	
	
	$off = "";
	$frase = "";
	//variaveis para a função em javascript.
	if ($subtreino[0] == "Jutsu de Busca"){
		$recompensa = "Utilização do Jutsu de Busca.";
		$requerimento = "Nenhum.";	
		$localtreino = "Vila da Areia.";
		$linkdotreino = "jutsudebusca.php?do=aprendendo2";
		$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) {$off = "2";$frase = "Treino não Disponível"; }
    $townrow = mysql_fetch_array($townquery);
if ($townrow["id"] != 5) {$off = "2";$frase = "Treino não Disponível";}//vila da areia
	}
	elseif ($subtreino[0] == "Senjutsu"){
		$recompensa = "Aquisição da Arte Eremita, o Senjutsu. Quando ativado, lhe concederá 10% de aumento de Ataque e Defesa.";
		$requerimento = "Ser um Genin.";	
		$localtreino = "Montanha Myoboku.";
		$linkdotreino = "senjutsu.php?do=aprendendo2";
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
	
	
	if ($frase == ""){$frase = "Treinar Agora";}
			
	$html .= "<table width=\"100%\"><tr bgcolor=\"#613003\"><td width=\"*\"><font color=\"white\">".$subtreino[0]."</font></td><td width=\"17\"><img src=\"images/".$relogio.".gif\" title=\"".$relogiotitle."\"></td><td width=\"17\"><a href=\"$linkdotreino\"><img src=\"images/treinar$off.gif\" title=\"$frase\" border=\"0\"></a></td><td width=\"84\">$pedra</td><td width=\"20\"><a href=\"javascript:mostrartreino('$i', '$recompensa', '$requerimento', '$localtreino')\"><img src=\"images/setabaixo.gif\" title=\"Mostrar Dados\" border=\"0\"></a></td><td width=\"20\"><a href=\"javascript:escondertreino('$i')\"><img src=\"images/setacima.gif\" title=\"Esconder Dados\" border=\"0\"></a></td></tr></table><div id=\"elemento$i\"></div><br>";

		
}//fim for
	
	//se não houver nada.
	if (($html == "") && ($conteudodois == "")){header('Location: /narutorpg/index.php?conteudo=Você ainda não adquiriu nenhum treinamento.');die(); }
	if ($conteudo != ""){$conteudo = "<center><font color=brown>".$conteudo."</font></center><br>";}
	
	$page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/treinamento.gif\" /></center></td></tr></table>
	$conteudo
	$conteudodois
	$html";
			 
			 display($page, "Treinamento", false, false, false);
	die();
	
	
	
	
}

?>