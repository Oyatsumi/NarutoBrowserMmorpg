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
if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: /narutorpg/index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
			if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }




$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysql_fetch_array($townquery);
	
if ($townrow["id"] != 2) {header('Location: /narutorpg/treinamentoequests.php?do=treinamento&conteudo=Você não pode usar essa função fora da Montanha Myoboku.');die(); }


$updatequery = doquery("UPDATE {{table}} SET imagem='senjutsu.png' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");


$townrow["kage"] = "Fukasaku";
$townrow["id"] = 2;
$conteudodois = " 
 Olá pequeno ninja, você veio até mim à procura da Arte Eremita? O Senjutsu é um tipo de jutsu utiliza a energia da natureza, potanto seus Pontos de Natureza, para obtê-lo é preciso 12 treinamentos em intervalos de 900 minutos(15 horas). Quando ativado, ele concederá 20% a mais de ataque e defesa para que você possa enfrentar seu inimigo. Se mesmo assim você ainda não desistiu, podemos <a href=\"senjutsu.php?do=aprendendo2\">começar agora mesmo</a> o treinamento.
";
include('funcoesinclusas.php');
personagemmissao($conteudodois, $townrow);
die();


}
















function aprendendo2(){

global $topvar;
global $userrow;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: /narutorpg/index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }


$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysql_fetch_array($townquery);
	
if ($townrow["id"] != 2) {header('Location: /narutorpg/treinamentoequests.php?do=treinamento&conteudo=Você não pode treinar esse Jutsu fora da Montanha Myoboku.');die(); }

if ($userrow["graduacao"] == "Estudante da Academia") {header('Location: /narutorpg/treinamentoequests.php?do=treinamento&conteudo=Você não pode fazer esse treinamento se não for ao menos um Genin.');die(); }



$updatequery = doquery("UPDATE {{table}} SET imagem='senjutsu.png' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");



//função se passou o tempo necessário ou não.
$today = date("j/n/Y");
$todayhour = date("H:i:s");
$nomedojutsu = "Senjutsu"; //nome do jutsu pra buscar.
$tempoprapassar = 900; //tempo em minutos
//colocando o jutsu no campo, nome, quantos ainda tem q treinar, treinar ao total, dia e hora do ultimo treino, tempo pra passar em minutos. A HORA É - 2 DA HORA DO BRASIL.
if ($userrow["treinamento"] != "None"){
	$treinos = explode(";",$userrow["treinamento"]);
	for($i = 0; $i < (count($treinos) - 1); $i++){// 
		$subtreinos = explode(",",$treinos[$i]);
		if ($subtreinos[0] == $nomedojutsu){
					//área die.
					if ($inicio == true){header("Location: /narutorpg/treinamentoequests.php?do=treinamento&conteudo=Você já possui o(a) ".$nomedojutsu." na sua lista de treinamento.");die();}
					if ($subtreinos[1] == $subtreinos[2]){header("Location: /narutorpg/treinamentoequests.php?do=treinamento&conteudo=Você já completou o treinamento do(a) ".$nomedojutsu.".");die();}
					include('funcoesinclusas.php');
					$retorno = tempojutsu($subtreinos[3], $subtreinos[4], $subtreinos[5]);
					if ($retorno != "ok"){header("Location: /narutorpg/treinamentoequests.php?do=treinamento&conteudo=Você ainda não pode treinar, é preciso aguardar ".$retorno." minuto(s) até que você possa treinar o(a) ".$nomedojutsu." novamente.");die();}
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
		header("Location: /narutorpg/treinamentoequests.php?do=treinamento&conteudo=Você adicionou o(a) ".$nomedojutsu." à sua tabela de treinamentos.");die(); 
	}else{//se for concluído então
	
	
		//se o jutsu for concluído
		if (($valor1 >= $valor2) && ($achou = true)){
			$subtrienos2[1] = $subtrienos2[2]; //pra não ficar um maior que o outro.
				//o que ganha no jutsu:
$updatequery = doquery("UPDATE {{table}} SET senjutsuhtml='fechado',treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow['charname']."' LIMIT 1","users");				//fim do que ganha.

			header("Location: /narutorpg/treinamentoequests.php?do=treinamento&conteudo=Você completou o treinamento do(a) ".$nomedojutsu.". Parabéns!");die(); 
			
		}else{//treinou e não completou o jutsu.
		$updatequery = doquery("UPDATE {{table}} SET treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");	
			header("Location: /narutorpg/treinamentoequests.php?do=treinamento&conteudo=Você treinou o(a) ".$nomedojutsu.". Você poderá treinar novamente dentro de ".$tempoprapassar." minutos.");die();		
		}//fim else		
		
		
		
		}//fim do else
	
	
	
		


}else{$userrow["treinamento"] = $nomedojutsu.",0,12,".$today.",".$todayhour.",0;";
$updatequery = doquery("UPDATE {{table}} SET treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
header("Location: /narutorpg/treinamentoequests.php?do=treinamento&conteudo=Você adicionou o(a) ".$nomedojutsu." a sua tabela de treinamentos.");die(); }




			//atualizar
		$updatequery = doquery("UPDATE {{table}} SET treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");	
			
	    
}















function usar() {
global $topvar;
global $userrow;
$topvar = true;
global $dir;

    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		

		if ($userrow["senjutsuhtml"] != ""){//o jogador possui o senjutsu.
			$segundospassarpsenjutsu = 3;
			if ($userrow["senjutsuhtml"] == "fechado"){//possui e o olho está fechado.
					$today = date("j/n/Y");
					$todayhour = date("H:i:s");
					$userrow["atkdefsenjutsu"] = floor(($userrow["attackpower"]*20)/100).",".floor(($userrow["defensepower"]*20)/100);
					$userrow["attackpower"] += floor(($userrow["attackpower"]*20)/100);
					$userrow["defensepower"] += floor(($userrow["defensepower"]*20)/100);
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
			$updatequery = doquery("UPDATE {{table}} SET atkdefsenjutsu='".$userrow["atkdefsenjutsu"]."',attackpower='".$userrow["attackpower"]."',defensepower='".$userrow["defensepower"]."',senjutsuhtml='".$userrow["senjutsuhtml"]."',senjutsutimer='".$userrow["senjutsutimer"]."',currentnp='".$userrow["currentnp"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");	
			
			if ($dir == ""){
				header('Location: /narutorpg/'.$userrow["pagina"].'');die();	
			}
			
		}else{//caso não possua o senjutsu.
			header('Location: /narutorpg/index.php?conteudo=Você precisa adquirir o senjutsu antes de usá-lo!');die();	
		}

}














function cancelar() {
global $topvar;
global $userrow;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
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
			
			header('Location: /narutorpg/'.$userrow["pagina"].'');die();
			
		}else{//caso não possua o senjutsu.
			header('Location: /narutorpg/index.php?conteudo=O Senjutsu já foi desativado ou você não tem NP suficiente!');die();	
		}
		

}





















function chamar() {
global $userrow;
global $topvar;
$topvar = true;
if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: /narutorpg/index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
			if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

$usuariologadonome = $userrow["charname"];


$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysql_fetch_array($townquery);
	
if ($townrow["id"] != 2) {header('Location: /narutorpg/index.php?conteudo=Você não pode usar essa função fora da Montanha Myoboku.');die(); }
if ($userrow["acesso"] > 1) {header('Location: /narutorpg/index.php?conteudo=Gamemasters não podem trocar seu avatar.');die(); }


	$updatequery = doquery("UPDATE {{table}} SET avatar='16' WHERE charname='$usuariologadonome' LIMIT 1","users");

include('funcoesinclusas.php');



 $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/grupar.gif\" /></center></td></tr></table>
";
$page2 = "Está precisando de mim? Você quer que eu entre no seu grupo? Não há problema, Shima e eu iremos com você em sua jornada. Agora fazemos parte do seu grupo!
<br><a href=\"index.php\">Voltar à pagina de jogo</a>.";

$page .= personagemgeral($page2, '2', 'Fukasaku');

    display($page, "Chamar", false, false, false); 

}






?>