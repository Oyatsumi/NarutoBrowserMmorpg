<?php // enche o hp.




if ($valorlib2 == ""){//valor para nao redeclarar esses scripts.
include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();
}



if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
	if ($do == "usar") { usar(); }
	elseif ($do == "cancelar") { cancelar(); }

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
		$mainmsg2 = "None";

		if ($userrow["senjutsuhtml"] != ""){//o jogador possui o senjutsu.
			$segundospassarpsenjutsu = 3;
			if ($userrow["senjutsuhtml"] == "fechado"){//possui e o olho está fechado.
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
			
		}else{//caso não possua o senjutsu.
			header('Location: ./index.php?conteudo=Você precisa adquirir o senjutsu antes de usá-lo!');die();	
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
			
			header('Location: ./'.$userrow["pagina"].'');die();
			
		}else{//caso não possua o senjutsu.
			header('Location: ./index.php?conteudo=O Senjutsu já foi desativado ou você não tem NP suficiente!');die();	
		}
		

}























?>