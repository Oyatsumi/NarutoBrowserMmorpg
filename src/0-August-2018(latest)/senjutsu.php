<?php // enche o hp.




if ($valorlib2 == ""){//valor para nao redeclarar esses scripts.
include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();
}



if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "jutsu") { jutsu(); }
	elseif ($do == "aprendendo2") { aprendendo2(); }
	elseif ($do == "usar") { usar(); }
	elseif ($do == "cancelar") { cancelar(); }
	elseif ($do == "chamar") { chamar(); }
	}





function jutsu() {
global $userrow;
global $topvar;
$topvar = true;
if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
			if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Voc� n�o pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }




$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysqli_num_rows($townquery) == 0) { display("H� um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysqli_fetch_array($townquery);
	
if ($townrow["id"] != 2) {header('Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� n�o pode usar essa fun��o fora da Montanha Myoboku.');die(); }


$townrow["kage"] = "Fukasaku";
$townrow["id"] = 2;
$conteudodois = " 
 Ol� pequeno ninja, voc� veio at� mim � procura da Arte Eremita? O Senjutsu � um tipo de jutsu utiliza a energia da natureza, ou seja, Pontos de Natureza(NP), para chegar � perfei��o do Senjutsu voc� precisa completar 12 treinamentos em intervalos de 900 minutos(15 horas) cada um. Quando ativado, ele conceder� 20% a mais de ataque e defesa para que voc� possa enfrentar seu inimigo. Se mesmo assim voc� ainda n�o desistiu, podemos <a href=\"senjutsu.php?do=aprendendo2\">come�ar agora mesmo</a> o treinamento.
";
include('funcoesinclusas.php');
personagemmissao($conteudodois, $townrow);
die();


}
















function aprendendo2(){

global $topvar;
global $userrow;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Voc� n�o pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }


$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysqli_num_rows($townquery) == 0) { display("H� um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysqli_fetch_array($townquery);
	
if ($townrow["id"] != 2) {header('Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� n�o pode treinar esse Jutsu fora da Montanha Myoboku.');die(); }

if ($userrow["graduacao"] == "Estudante da Academia") {header('Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� n�o pode fazer esse treinamento se n�o for ao menos um Genin.');die(); }


//fun��o se passou o tempo necess�rio ou n�o.
$today = date("j/n/Y");
$todayhour = date("H:i:s");
$nomedojutsu = "Senjutsu"; //nome do jutsu pra buscar.
$tempoprapassar = 900; //tempo em minutos
//colocando o jutsu no campo, nome, quantos ainda tem q treinar, treinar ao total, dia e hora do ultimo treino, tempo pra passar em minutos. A HORA � - 2 DA HORA DO BRASIL.
if ($userrow["treinamento"] != "None"){
	$treinos = explode(";",$userrow["treinamento"]);
	for($i = 0; $i < (count($treinos) - 1); $i++){// 
		$subtreinos = explode(",",$treinos[$i]);
		if ($subtreinos[0] == $nomedojutsu){
					//�rea die.
					if ($inicio == true){header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� j� possui o(a) ".$nomedojutsu." na sua lista de treinamento.");die();}
					if ($subtreinos[1] == $subtreinos[2]){header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� j� completou o treinamento do(a) ".$nomedojutsu.".");die();}
					include('funcoesinclusas.php');
					$retorno = tempojutsu($subtreinos[3], $subtreinos[4], $subtreinos[5]);
					if ($retorno != "ok"){header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� ainda n�o pode treinar, � preciso aguardar ".$retorno." minuto(s) at� que voc� possa treinar o(a) ".$nomedojutsu." novamente.");die();}
					//fim area die.
			$i = count($treinos);
			$achou = true;
			//colocando tudo no lugar:
			$userrow["treinamento"] = "";
			for($j = 0; $j < (count($treinos) - 1); $j++){
				$subtreinos2 = explode(",",$treinos[$j]);
				if ($subtreinos2[0] != $nomedojutsu){
					$userrow["treinamento"] .= $treinos[$j].";";
				}else{//se for igual o nome da busca do jutsu
					$subtreinos2[1] += 1;
					$userrow["treinamento"] .= $subtreinos2[0].",".$subtreinos2[1].",".$subtreinos2[2].",".$today.",".$todayhour.",".$tempoprapassar.";";
					$valor1 = $subtreinos2[1];
					$valor2 = $subtreinos2[2];
				}//fim segundo if
			}//fim segundo for
			
			
		}//fimif
	}//fimfor
	
	if ($achou != true){//adicionando se nao for encontrado
		$userrow["treinamento"] .= $nomedojutsu.",0,12,".$today.",".$todayhour.",0;";
		$updatequery = doquery("UPDATE {{table}} SET treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
		header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� adicionou o(a) ".$nomedojutsu." � sua tabela de treinamentos.");die(); 
	}else{//se for conclu�do ent�o
	
	
		//se o jutsu for conclu�do
		if (($valor1 >= $valor2) && ($achou = true)){
			$subtrienos2[1] = $subtrienos2[2]; //pra n�o ficar um maior que o outro.
				//o que ganha no jutsu:
$updatequery = doquery("UPDATE {{table}} SET senjutsuhtml='fechado',treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow['charname']."' LIMIT 1","users");				//fim do que ganha.

			header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� completou o treinamento do(a) ".$nomedojutsu.". Parab�ns!");die(); 
			
		}else{//treinou e n�o completou o jutsu.
		$updatequery = doquery("UPDATE {{table}} SET treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");	
			header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� treinou o(a) ".$nomedojutsu.". Voc� poder� treinar novamente dentro de ".$tempoprapassar." minutos.");die();		
		}//fim else		
		
		
		
		}//fim do else
	
	
	
		


}else{$userrow["treinamento"] = $nomedojutsu.",0,12,".$today.",".$todayhour.",0;";
$updatequery = doquery("UPDATE {{table}} SET treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� adicionou o(a) ".$nomedojutsu." a sua tabela de treinamentos.");die(); }




			//atualizar
		$updatequery = doquery("UPDATE {{table}} SET treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");	
			
	    
}















function usar() {
global $topvar;
global $userrow;
$topvar = true;
global $dir;

    /* testando se est� logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);
		die(); }
		$mainmsg2 = "None";

		if ($userrow["senjutsuhtml"] != ""){//o jogador possui o senjutsu.
			$segundospassarpsenjutsu = 3;
			if ($userrow["senjutsuhtml"] == "fechado"){//possui e o olho est� fechado.
					$today = date("j/n/Y");
					$todayhour = date("H:i:s");
					$userrow["atkdefsenjutsu"] = floor(($userrow["attackpower"]*20)/100).",".floor(($userrow["defensepower"]*20)/100);
					$userrow["attackpower"] += floor(($userrow["attackpower"]*20)/100);
					$userrow["defensepower"] += floor(($userrow["defensepower"]*20)/100);
					if ($userrow['currentnp'] != 0){$mainmsg2 = 3;}
					$userrow["senjutsuhtml"] = "senjutsu";
					$userrow["senjutsutimer"] = $today.",".$todayhour.",1"; //de 5 em 5 segundos.
			}else{//se o olho estiver aberto
					include('funcoesinclusas.php');
					$cont = explode(",",$userrow["senjutsutimer"]);
					$resultado = tempopassarsg($cont[0],$cont[1],$cont[2]);
					$resultexplo = explode("-",$resultado);
					$userrow["currentnp"] -= floor($resultexplo[1]/$segundospassarpsenjutsu);
					if ($userrow["currentnp"] < 0){$userrow["currentnp"] = 0;}
					if ($userrow["currentnp"] == 0){//se acabar o np
						$userrow["senjutsutimer"] = "None";
						$retir = explode(",", $userrow["atkdefsenjutsu"]);
						$userrow["attackpower"] -= $retir[0];
						$userrow["defensepower"] -= $retir[1];
						$userrow["senjutsuhtml"] = "fechado";
						$userrow["atkdefsenjutsu"] = "0,0";
					}else{//se nao for = 0;
						if ($resultexplo[1] > $segundospassarpsenjutsu){
							$today = date("j/n/Y");
							$todayhour = date("H:i:s");
							$userrow["senjutsutimer"] = $today.",".$todayhour.",1"; //de 5 em 5 segundos.
							$userrow["senjutsuhtml"] = "senjutsu";
						}
					}
					
			}
			//atualizando tudo.
			$updatequery = doquery("UPDATE {{table}} SET atkdefsenjutsu='".$userrow["atkdefsenjutsu"]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."',senjutsuhtml='".$userrow["senjutsuhtml"]."',senjutsutimer='".$userrow["senjutsutimer"]."',currentnp='".$userrow["currentnp"]."', mainmsg='$mainmsg2' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");	
			
			if ($dir == ""){
				header('Location: ./'.$userrow["pagina"].'');die();	
			}
			
		}else{//caso n�o possua o senjutsu.
			header('Location: ./index.php?conteudo=Voc� precisa adquirir o senjutsu antes de us�-lo!');die();	
		}

}














function cancelar() {
global $topvar;
global $userrow;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);
		die(); }
		
		
		if (($userrow["senjutsuhtml"] != "") && ($userrow["senjutsuhtml"] == "senjutsu")){//o jogador possui o senjutsu.
			$segundospassarpsenjutsu = 3;
			$userrow["senjutsuhtml"] = "fechado";
			include('funcoesinclusas.php');
			$cont = explode(",",$userrow["senjutsutimer"]);
			$resultado = tempopassarsg($cont[0],$cont[1],$cont[2]);
			$resultexplo = explode("-",$resultado);
			$userrow["currentnp"] -= floor($resultexplo[1]/$segundospassarpsenjutsu);
			if ($userrow["currentnp"] < 0){$userrow["currentnp"] = 0;}
			$userrow["senjutsutimer"] = "None";
			$retir = explode(",", $userrow["atkdefsenjutsu"]);
			$userrow["attackpower"] -= $retir[0];
			$userrow["defensepower"] -= $retir[1];
			$userrow["atkdefsenjutsu"] = "0,0";
			
			//atualizando tudo.
			$updatequery = doquery("UPDATE {{table}} SET atkdefsenjutsu='".$userrow["atkdefsenjutsu"]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."',senjutsuhtml='".$userrow["senjutsuhtml"]."',senjutsutimer='".$userrow["senjutsutimer"]."',currentnp='".$userrow["currentnp"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");	
			
			header('Location: ./'.$userrow["pagina"].'');die();
			
		}else{//caso n�o possua o senjutsu.
			header('Location: ./index.php?conteudo=O Senjutsu j� foi desativado ou voc� n�o tem NP suficiente!');die();	
		}
		

}





















function chamar() {
global $userrow;
global $topvar;
$topvar = true;
if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
			if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Voc� n�o pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

$usuariologadonome = $userrow["charname"];


$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysqli_num_rows($townquery) == 0) { display("H� um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysqli_fetch_array($townquery);
	
if ($townrow["id"] != 2) {header('Location: ./index.php?conteudo=Voc� n�o pode usar essa fun��o fora da Montanha Myoboku.');die(); }
if ($userrow["acesso"] > 1) {header('Location: ./index.php?conteudo=Gamemasters n�o podem trocar seu avatar.');die(); }


	$updatequery = doquery("UPDATE {{table}} SET avatar='16' WHERE charname='$usuariologadonome' LIMIT 1","users");

include('funcoesinclusas.php');



 $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/grupar.gif\" /></center></td></tr></table>
";
$page2 = "Est� precisando de mim? Voc� quer que eu entre no seu grupo? N�o h� problema, Shima e eu iremos com voc� em sua jornada. Agora fazemos parte do seu grupo!
<br><a href=\"index.php\">Voltar � pagina de jogo</a>.";

$page .= personagemgeral($page2, '2', 'Fukasaku');

    display($page, "Chamar", false, false, false); 

}






?>