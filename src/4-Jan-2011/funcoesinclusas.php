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

















if (!function_exists('personagemmissao')){
function personagemmissao($cont, $townrow){
global $userrow, $valorlib;
$valorlib = 2;//para não declarar lib novamente.
$cont = " 
<center><table width=\"462\" cellspacing=\"0\" cellpadding=\"0\"><tr background=\"layoutnovo/personagem/cima.jpg\"><td width=\"50\"></td><td height=\"21\"></td><td width=\"8\"></td></tr><tr background=\"layoutnovo/personagem/meio.jpg\"><td></td><td><img src=\"layoutnovo/personagem/".$townrow["id"].".png\" align=\"left\"><b>".$townrow["kage"]." diz:</b><br>
".$cont."
</td><td></td></tr><tr background=\"layoutnovo/personagem/baixo.jpg\"><td></td><td height=\"21\"></td><td></td></tr></table></center><br>
";

include('treinamentoequests.php');
treinamento($cont);
die();
}}





if (!function_exists('personagemgeral')){
function personagemgeral($cont, $avatar, $avatarnome, $sempular){
global $userrow, $valorlib;
if ($sempular == ""){$sempular = "<br>";}else{$sempular = "";}
$valorlib = 2;//para não declarar lib novamente.
$cont = "<center><table width=\"462\" cellspacing=\"0\" cellpadding=\"0\" background=\"layoutnovo/personagem/meio.jpg\"><tr background=\"layoutnovo/personagem/cima.jpg\"><td background=\"layoutnovo/personagem/cima.jpg\" height=\"21\"></td></tr><tr background=\"layoutnovo/personagem/meio.jpg\"><td><table width=\"100%\"><tr><td width=\"50\"></td><td width=\"*\"><img src=\"layoutnovo/personagem/".$avatar.".png\" align=\"left\"><b>".$avatarnome." diz:</b><br>
".$cont."
</td><td width=\"8\"></td></tr></table>   </td></tr><tr background=\"layoutnovo/personagem/baixo.jpg\"><td background=\"layoutnovo/personagem/baixo.jpg\" height=\"21\"></td></tr></table></center>$sempular
";

return $cont;
}}







if (!function_exists('elementoganhador')){
function elementoganhador($primeiro, $segundo, &$pontosdeelemento){

//Win referente a multiplicação dos danos.
if (($primeiro == "agua") && ($segundo == "fogo")){$win = 3/2; $mult = $userrow['sorte'];}
elseif (($primeiro == "fogo") && ($segundo == "agua")){$win = 1/2; $mult = $userrow['determinacao'];}
elseif (($primeiro == "fogo") && ($segundo == "vento")){$win = 3/2; $mult = $userrow['determinacao'];}
elseif (($primeiro == "vento") && ($segundo == "fogo")){$win = 1/2; $mult = $userrow['precisao'];}
elseif (($primeiro == "vento") && ($segundo == "raio")){$win = 3/2; $mult = $userrow['precisao'];}
elseif (($primeiro == "raio") && ($segundo == "vento")){$win = 1/2; $mult = $userrow['agilidade'];}
elseif (($primeiro == "raio") && ($segundo == "terra")){$win = 3/2; $mult = $userrow['agilidade'];}
elseif (($primeiro == "terra") && ($segundo == "raio")){$win = 1/2; $mult = $userrow['inteligencia'];}
elseif (($primeiro == "terra") && ($segundo == "agua")){$win = 3/2; $mult = $userrow['inteligencia'];}
elseif (($primeiro == "agua") && ($segundo == "terra")){$win = 1/2; $mult = $userrow['sorte'];}
else{$win = 1; $mult = 0;}

if ($pontosdeelemento != ""){$pontosdeelemento = ceil($mult/20);}

return $win;

}}





















if (!function_exists('conteudoexplic')){
function conteudoexplic($iditem, $tipo, $objeto, $durabilidade){
	
	if (($iditem != 0) && ($iditem != "")){
	
		if ($tipo <= 3){	
			$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$iditem."' LIMIT 1", "items");
			$itemsrow = mysql_fetch_array($itemsquery);
			
			
			
			if ($tipo == 1){
				if ($itemsrow["attribute"] >= 0){
				$conteudo = "Poder de Ataque: <font color=blue>+".$itemsrow["attribute"]."</font><br>";}else{
				$conteudo = "Poder de Ataque: <font color=red>+".$itemsrow["attribute"]."</font><br>";
				}
			}elseif (($tipo > 1) && ($tipo <= 3)){
				if ($itemsrow["attribute"] >= 0){
				$conteudo = "Poder de Defesa: <font color=blue>+".$itemsrow["attribute"]."</font><br>";}else{
				$conteudo = "Poder de Defesa: <font color=red>+".$itemsrow["attribute"]."</font><br>";
				}
			}
			
			
			if ($itemsrow["special"] != "X"){$novaatr = explode(",",$itemsrow["special"]); 
			//renomear
			if ($novaatr[0] == "defensepower"){$novaatr[0] = "Poder de Defesa";}
			elseif ($novaatr[0] == "attackpower"){$novaatr[0] = "Poder de Ataque";}
			elseif ($novaatr[0] == "droprate"){$novaatr[0] = "Chance de Drop"; $fim = "%";}
			elseif ($novaatr[0] == "strength"){$novaatr[0] = "Força";}
			elseif ($novaatr[0] == "dexterity"){$novaatr[0] = "Destreza";}
			elseif ($novaatr[0] == "maxhp"){$novaatr[0] = "Max HP";}
			elseif ($novaatr[0] == "maxmp"){$novaatr[0] = "Max MP";}
			elseif ($novaatr[0] == "maxtp"){$novaatr[0] = "Max TP";}
			elseif ($novaatr[0] == "maxnp"){$novaatr[0] = "Max NP";}
			elseif ($novaatr[0] == "maxep"){$novaatr[0] = "Max EP";}
			elseif ($novaatr[0] == "goldbonus"){$novaatr[0] = "Bônus de Ryou"; $fim = "%";}
			elseif ($novaatr[0] == "expbonus"){$novaatr[0] = "Bônus de Experiência"; $fim = "%";}
			//fim renomear
			if ($novaatr[1] > 0){$novaatr[1] = "<font color=blue>+".$novaatr[1]."$fim</font>";}else{$novaatr[1] = "<font color=red>".$novaatr[1]."$fim</font>";}
			$conteudo .= ucfirst($novaatr[0]).": ".$novaatr[1]."<br>";}
			$conteudo .= "Durabilidade: $durabilidade";
			
			$retornar = "explicdrop('$objeto', '".$itemsrow["name"]."', '$conteudo', '1', '1')";
			
		}
		elseif(($tipo <= 6) && ($tipo > 3)){//se for maior que 3
			$itemsquery = doquery("SELECT * FROM {{table}} WHERE id='".$iditem."' LIMIT 1", "drops");
			$itemsrow = mysql_fetch_array($itemsquery);
		
		
			if ($itemsrow["attribute1"] != "X"){
				$novaatr = explode(",", $itemsrow["attribute1"]);	
					//renomear
					if ($novaatr[0] == "defensepower"){$novaatr[0] = "Poder de Defesa";}
					elseif ($novaatr[0] == "attackpower"){$novaatr[0] = "Poder de Ataque";}
					elseif ($novaatr[0] == "droprate"){$novaatr[0] = "Chance de Drop"; $fim = "%";}
					elseif ($novaatr[0] == "strength"){$novaatr[0] = "Força";}
					elseif ($novaatr[0] == "dexterity"){$novaatr[0] = "Destreza";}
					elseif ($novaatr[0] == "maxhp"){$novaatr[0] = "Max HP";}
					elseif ($novaatr[0] == "maxmp"){$novaatr[0] = "Max MP";}
					elseif ($novaatr[0] == "maxtp"){$novaatr[0] = "Max TP";}
					elseif ($novaatr[0] == "maxnp"){$novaatr[0] = "Max NP";}
					elseif ($novaatr[0] == "maxep"){$novaatr[0] = "Max EP";}
					elseif ($novaatr[0] == "goldbonus"){$novaatr[0] = "Bônus de Ryou"; $fim = "%";}
					elseif ($novaatr[0] == "expbonus"){$novaatr[0] = "Bônus de Experiência"; $fim = "%";}
					//fim renomear
				if ($novaatr[1] > 0){$novaatr[1] = "<font color=blue>+".$novaatr[1]."$fim</font>";}else{$novaatr[1] = "<font color=red>".$novaatr[1]."$fim</font>";}
				$conteudo .= ucfirst($novaatr[0]).": ".$novaatr[1];

			}
			if ($itemsrow["attribute2"] != "X"){
				$novaatr = explode(",", $itemsrow["attribute2"]);
					//renomear
					if ($novaatr[0] == "defensepower"){$novaatr[0] = "Poder de Defesa";}
					elseif ($novaatr[0] == "attackpower"){$novaatr[0] = "Poder de Ataque";}
					elseif ($novaatr[0] == "droprate"){$novaatr[0] = "Chance de Drop"; $fim = "%";}
					elseif ($novaatr[0] == "strength"){$novaatr[0] = "Força";}
					elseif ($novaatr[0] == "dexterity"){$novaatr[0] = "Destreza";}
					elseif ($novaatr[0] == "maxhp"){$novaatr[0] = "Max HP";}
					elseif ($novaatr[0] == "maxmp"){$novaatr[0] = "Max MP";}
					elseif ($novaatr[0] == "maxtp"){$novaatr[0] = "Max TP";}
					elseif ($novaatr[0] == "maxnp"){$novaatr[0] = "Max NP";}
					elseif ($novaatr[0] == "maxep"){$novaatr[0] = "Max EP";}
					elseif ($novaatr[0] == "goldbonus"){$novaatr[0] = "Bônus de Ryou"; $fim = "%";}
					elseif ($novaatr[0] == "expbonus"){$novaatr[0] = "Bônus de Experiência"; $fim = "%";}
					//fim renomear	
				if ($novaatr[1] > 0){$novaatr[1] = "<font color=blue>+".$novaatr[1]."$fim</font>";}else{$novaatr[1] = "<font color=red>".$novaatr[1]."$fim</font>";}
				$conteudo .= "<br>".ucfirst($novaatr[0]).": ".$novaatr[1];
				
			}
		$conteudo .= "<br>Durabilidade: $durabilidade";
		$retornar = "explicdrop('$objeto', '".$itemsrow["name"]."', '$conteudo', '1', '1')";
		}
		
	return $retornar;
	}

}}

?>