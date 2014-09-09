<?php 
/*$var = "10/9/2010";
$var2 = "16:0:0";
$bla = tempojutsu($var, $var2, 120);
echo $bla;*/



if (!function_exists('tempojutsu')){
function tempojutsu ($data, $hora, $tempoprapassar){
// data formato : dd/mm/aaaa
//hora formato : hh:mm:ss
//tempo pra passar formato : segundos

//1 dia = 86400 segundos ou 1440 minutos.

//lembrar que a hora padrão é -2 horas do brasil.

	
	$datajutsu = explode("/", $data);
	$today = date("j/n/Y"); 
	$datahoje = explode("/", $today);
	


	
	//quantos anos a frente à tras ou mesmo.
		$quantosanos = ($datahoje[2] - $datajutsu[2]);
	
	//quantos meses, diferença das datas.
		$mesquantos = ($datahoje[1] - $datajutsu[1]);
		$mesquantos += ($quantosanos *12);

		
	//quantos dias
		$quantosdias = ($datahoje[0] - $datajutsu[0]);
		$quantosdias += $mesquantos * 30;

		
	//quantas horas
		$horajutsu = explode(":",$hora);
		$todayhour = date("H:i:s"); 
		$horaagora = explode(":", $todayhour);
		

		//quantas minutos pra segundos
			$quantosmin = ($horaagora[0] - $horajutsu[0]) * 60;//60 minutos
			$quantosmin += ($horaagora[1] - $horajutsu[1]);
			
		
			
	//adicionando os dias nos minutos...
	$quantosmin += $quantosdias * 1440;
			

	
	if ($quantosmin >= $tempoprapassar) {return "ok";}else{
		$tempoprapassar -= $quantosmin;
		return $tempoprapassar;}	 //se for verdadeiro retorna true, se falso retorna o tempo que ainda falta pra passar.
	
	
}}





















//em segundos...
if (!function_exists('tempopassarsg')){
function tempopassarsg ($data, $hora, $tempoprapassar){
// data formato : dd/mm/aaaa
//hora formato : hh:mm:ss
//tempo pra passar formato : segundos

//1 dia = 86400 segundos ou 1440 minutos.

//lembrar que a hora padrão é -2 horas do brasil.


	
	$datajutsu = explode("/", $data);
	$today = date("j/n/Y"); 
	$datahoje = explode("/", $today);
	


	
	//quantos anos a frente à tras ou mesmo.
		$quantosanos = ($datahoje[2] - $datajutsu[2]);
	
	//quantos meses, diferença das datas.
		$mesquantos = ($datahoje[1] - $datajutsu[1]);
		$mesquantos += ($quantosanos *12);

		
	//quantos dias
		$quantosdias = ($datahoje[0] - $datajutsu[0]);
		$quantosdias += $mesquantos * 30;

		
	//quantas horas
		$horajutsu = explode(":",$hora);
		$todayhour = date("H:i:s"); 
		$horaagora = explode(":", $todayhour);
		

		//quantas minutos pra segundos
			$quantosmin = ($horaagora[0] - $horajutsu[0]) * 60;//60 minutos
			$quantosmin += ($horaagora[1] - $horajutsu[1]);
			
		
			
	//adicionando os dias nos minutos...
	$quantosmin += $quantosdias * 1440;
	
	
	//conta
	$quantosseg = $quantosmin*60;
	$quantosseg += ($horaagora[2] - $horajutsu[2]);
			

	
	if ($quantosseg >= $tempoprapassar) {return "0-".$quantosseg;}else{
		$tempoprapassar -= $quantosseg;
		return $tempoprapassar."-".$quantosseg;}	 //se for verdadeiro retorna true, se falso retorna o tempo que ainda falta pra passar.
	
	
}}




























if (!function_exists('senjutsu')){
function senjutsu(){
	global $userrow;
	if ($userrow["senjutsuhtml"] != ""){//o jogador possui o senjutsu.
			
			$segundospassarpsenjutsu = 3;
			if ($userrow["senjutsuhtml"] == "fechado"){//possui e o olho está fechado.
					header('Location: /narutorpg/index.php');die();
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
	}
	
}}


?>