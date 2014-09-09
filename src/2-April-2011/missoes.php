<?php // users.php :: Handles user account functions.


/*$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);*/



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();


		

	

if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "missao") { missao(); }
	elseif ($do == "missaotempo") { missaotempo(); }
	elseif ($do == "fazermissao") { fazermissao(); }
	}
	
	
function passarconteudo($cont, $townrow){
global $userrow, $valorlib;
$valorlib = 2;//para não declarar lib novamente.
$cont = "<center><table width=\"462\" cellspacing=\"0\" cellpadding=\"0\" background=\"layoutnovo/personagem/meio.jpg\"><tr background=\"layoutnovo/personagem/cima.jpg\"><td background=\"layoutnovo/personagem/cima.jpg\" height=\"21\"></td></tr><tr background=\"layoutnovo/personagem/meio.jpg\"><td><table width=\"100%\"><tr><td width=\"50\"></td><td width=\"*\"><img src=\"layoutnovo/personagem/".$townrow["id"].".png\" align=\"left\"><b>".$townrow["kage"]." diz:</b><br>
".$cont."
</td><td width=\"8\"></td></tr></table>   </td></tr><tr background=\"layoutnovo/personagem/baixo.jpg\"><td background=\"layoutnovo/personagem/baixo.jpg\" height=\"21\"></td></tr></table></center><br>
";
include('treinamentoequests.php');
quests($cont);
die();
}



function missao() {
global $topvar;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
		global $userrow;

		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		if ($userrow["currentaction"] != "In Town") {if ($userrow["currentaction"] == "Fighting"){header('Location: ./index.php?do=fight&conteudo=Você só pode acessar essa função dentro de uma cidade!');die();}else{header('Location: ./treinamentoequests.php?do=quests&conteudo=Você só pode acessar essa função dentro de uma cidade!');die();} }
					if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }					
					if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }


		
	

$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysql_fetch_array($townquery);




//explode missão
$missaoexplode = explode(",",$userrow["missao"]);





//inicio missao 1
if ($missaoexplode[0] == 1) {

if ($townrow["id"] != 1) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}


$foi = 0;
$nome = "Fartura do Fogo";
if ($userrow["slot1name"] == $nome) {$foi = 1;}
if ($userrow["slot2name"] == $nome) {$foi = 2;}
if ($userrow["slot3name"] == $nome) {$foi = 3;}
for ($i = 1; $i <= 4; $i++){
$parcial = explode(",",$userrow["bp".$i]);
if ($parcial[0] == $nome) {$foi = $i + 3;}
}
//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}
if($foi <= 3) {passarconteudo('Primeiro coloque o item <b>Fartura do Fogo</b> na sua mochila.', $townrow);
}
//completou
if ($foi > 0){

if ($foi <= 3){
passarconteudo('Para completar a Missão coloque o item <font color=brown>'.$nome.'</font> na sua mochila.', $townrow);
}else{
$updatequery = doquery("UPDATE {{table}} SET bp".($foi - 3)."='None' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
}
$updatequery = doquery("UPDATE {{table}} SET missao='2,0,10,1' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}

}//fim missão 1



























//inicio missao 2
elseif ($missaoexplode[0] == 2) {

if ($townrow["id"] != 1) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);
}

$foi = 0;
$resultarray = fazermissao($missaoexplode, $townrow);
$novosdados = explode(",", $resultarray);

//para saber se treinou a missão ou não.
if ($novosdados[1] != $missaoexplode[1]){
if ($novosdados[1] == $novosdados[2]){//completou
	$novogold = $userrow["gold"] + 50;
	$updatequery = doquery("UPDATE {{table}} SET missao='3,0,10,1', gold='$novogold', missaoultdata='None' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso e ganhou sua recompensa.', $townrow);
}else{//nao completou
	$updatequery = doquery("UPDATE {{table}} SET missao='$resultarray', missaoultdata='".date("j/n/Y").";".date("H:i:s")."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	passarconteudo('Você completou mais uma parte da Missão '.$missaoexplode[0].', e em breve poderá aumentar sua porcentagem de conclusão novamente.', $townrow);
}//fim else
}else{passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);}

}//fim missão 2







































//inicio missao 3
elseif ($missaoexplode[0] == 3) {

if ($townrow["id"] != 4) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}


$foi = 0;
$explodir = explode(",", $userrow["ultmonstro"]);
if ($explodir[0] == "Lobo Rubro") {$foi = 1;}

//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}

//completou
if ($foi > 0){

$userrow["gold"] += 100;
$updatequery = doquery("UPDATE {{table}} SET missao='4,0,5,1',gold='".$userrow["gold"]."',missaoswitch='0' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}

}//fim missão 3

















//inicio missao 4
elseif ($missaoexplode[0] == 4) {

if ($townrow["id"] != 1) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);
}
$foi = 0;
$resultarray = fazermissao($missaoexplode, $townrow);
$novosdados = explode(",", $resultarray);

//para saber se treinou a missão ou não.
if ($novosdados[1] != $missaoexplode[1]){
if ($novosdados[1] == $novosdados[2]){//completou
	$novogold = $userrow["gold"] + 30;
	$updatequery = doquery("UPDATE {{table}} SET missao='5,0,0,0', gold='$novogold', missaoultdata='None' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso e ganhou sua recompensa.', $townrow);
}else{//nao completou
	$updatequery = doquery("UPDATE {{table}} SET missao='$resultarray', missaoultdata='".date("j/n/Y").";".date("H:i:s")."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	passarconteudo('Você completou mais uma parte da Missão '.$missaoexplode[0].', e em breve poderá aumentar sua porcentagem de conclusão novamente.', $townrow);
}//fim else
}else{passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);}

}//fim missão 4


















//inicio missao 5
elseif ($missaoexplode[0] == 5) {

if ($townrow["id"] != 1) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}

$foi = 0;
$nome = "Boneca Perdida";
if ($userrow["slot1name"] == $nome) {$foi = 1;}
if ($userrow["slot2name"] == $nome) {$foi = 2;}
if ($userrow["slot3name"] == $nome) {$foi = 3;}
for ($i = 1; $i <= 4; $i++){
$parcial = explode(",",$userrow["bp".$i]);
if ($parcial[0] == $nome) {$foi = $i + 3;}
}
//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}
if($foi <= 3) {passarconteudo('Primeiro coloque o item <b>Fartura do Fogo</b> na sua mochila.', $townrow);
}
//completou
if ($foi > 0){

if ($foi <= 3){
passarconteudo('Para completar a Missão coloque o item '.$nome.' na sua mochila.', $townrow);
}else{
$updatequery = doquery("UPDATE {{table}} SET bp".($foi - 3)."='None' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
}
$userrow["gold"] += 100;
$updatequery = doquery("UPDATE {{table}} SET missao='6,0,0,0',gold='".$userrow["gold"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}

}//fim missão 5



























//inicio missao 6
elseif ($missaoexplode[0] == 6) {

if ($townrow["id"] != 1) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}

$foi = 0;
if ($userrow["missaoswitch"] == 1) {$foi = 1;}

//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}

//completou
if ($foi > 0){

$userrow["gold"] += 300;
$updatequery = doquery("UPDATE {{table}} SET missao='7,0,0,0',gold='".$userrow["gold"]."',missaoswitch='0' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}
}//fim missão 6























//inicio missao 7
elseif ($missaoexplode[0] == 20) {

if ($townrow["id"] != 1) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}


$foi = 0;
$explodir = explode(",", $userrow["ultmonstro"]);
if ($explodir[0] == "Zabuza"){$foi = 1;}


//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}

//completou
if ($foi > 0){

$userrow["gold"] += 350;
$updatequery = doquery("UPDATE {{table}} SET missao='8,0,10,6',gold='".$userrow["gold"]."',missaoswitch='0' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}

}//fim missão 7






















//inicio missao 8
elseif ($missaoexplode[0] == 8) {

if ($townrow["id"] != 2) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);
}
$foi = 0;
$resultarray = fazermissao($missaoexplode, $townrow);
$novosdados = explode(",", $resultarray);

//para saber se treinou a missão ou não.
if ($novosdados[1] != $missaoexplode[1]){
if ($novosdados[1] == $novosdados[2]){//completou
	$novogold = $userrow["gold"] + 500;
	$updatequery = doquery("UPDATE {{table}} SET missao='9,0,0,0', gold='$novogold', missaoultdata='None' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso e ganhou sua recompensa.', $townrow);
}else{//nao completou
	$updatequery = doquery("UPDATE {{table}} SET missao='$resultarray', missaoultdata='".date("j/n/Y").";".date("H:i:s")."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	passarconteudo('Você completou mais uma parte da Missão '.$missaoexplode[0].', e em breve poderá aumentar sua porcentagem de conclusão novamente.', $townrow);
}//fim else
}else{passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);}

}//fim missão 8
















//inicio missao 9
elseif ($missaoexplode[0] == 9) {

if ($townrow["id"] != 3) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}


$foi = 0;
if ($userrow["missaoswitch"] == 1) {$foi = 1;}

//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}

//completou
if ($foi > 0){

$userrow["gold"] += 300;
$updatequery = doquery("UPDATE {{table}} SET missao='10,0,0,0',gold='".$userrow["gold"]."',missaoswitch='0' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}
}//fim missão 9




















//inicio missao 10
elseif ($missaoexplode[0] == 10) {

if ($townrow["id"] != 6) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}

$foi = 0;
$nome = "Rastro de Hachibi";
if ($userrow["slot1name"] == $nome) {$foi = 1;}
if ($userrow["slot2name"] == $nome) {$foi = 2;}
if ($userrow["slot3name"] == $nome) {$foi = 3;}
for ($i = 1; $i <= 4; $i++){
$parcial = explode(",",$userrow["bp".$i]);
if ($parcial[0] == $nome) {$foi = $i + 3;}
}
//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}
if($foi <= 3) {passarconteudo('Primeiro coloque o item <b>Fartura do Fogo</b> na sua mochila.', $townrow);
}
//completou
if ($foi > 0){

if ($foi <= 3){
passarconteudo('Para completar a Missão coloque o item '.$nome.' na sua mochila.', $townrow);
}else{
$updatequery = doquery("UPDATE {{table}} SET bp".($foi - 3)."name='Máscara do Ritual,47,4,X' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
}
$userrow["gold"] += 1000;
$updatequery = doquery("UPDATE {{table}} SET missao='11,0,5,12',gold='".$userrow["gold"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}

}//fim missão 10




























//inicio missao 11
elseif ($missaoexplode[0] == 11) {

if ($townrow["id"] != 6) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);
}
$foi = 0;
$resultarray = fazermissao($missaoexplode, $townrow);
$novosdados = explode(",", $resultarray);

//para saber se treinou a missão ou não.
if ($novosdados[1] != $missaoexplode[1]){
if ($novosdados[1] == $novosdados[2]){//completou
	$novogold = $userrow["gold"] + 400;
	$updatequery = doquery("UPDATE {{table}} SET missao='12,0,5,60', gold='$novogold', missaoultdata='None' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso e ganhou sua recompensa.', $townrow);
}else{//nao completou
	$updatequery = doquery("UPDATE {{table}} SET missao='$resultarray', missaoultdata='".date("j/n/Y").";".date("H:i:s")."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	passarconteudo('Você completou mais uma parte da Missão '.$missaoexplode[0].', e em breve poderá aumentar sua porcentagem de conclusão novamente.', $townrow);
}//fim else
}else{passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);}

}//fim missão 11


















//inicio missao 12
elseif ($missaoexplode[0] == 12) {

if ($townrow["id"] != 5) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);
}
$foi = 0;
$resultarray = fazermissao($missaoexplode, $townrow);
$novosdados = explode(",", $resultarray);

//para saber se treinou a missão ou não.
if ($novosdados[1] != $missaoexplode[1]){
if (($novosdados[1] == $novosdados[2]) && ($userrow['missaoswitch'] == 1)){//completou composto
	$novogold = $userrow["gold"] + 5000;
	$updatequery = doquery("UPDATE {{table}} SET missao='13,0,0,0', gold='$novogold', missaoultdata='None', missaoswitch='0' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso e ganhou sua recompensa.', $townrow);
}else{//nao completou
	$updatequery = doquery("UPDATE {{table}} SET missao='$resultarray', missaoultdata='".date("j/n/Y").";".date("H:i:s")."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	if ($novosdados[1] != $novosdados[2]){
	passarconteudo('Você completou mais uma parte da Missão '.$missaoexplode[0].', e em breve poderá aumentar sua porcentagem de conclusão novamente.', $townrow);}else{passarconteudo('Você ainda não completou todo o requerimento da Missão.', $townrow);}	
}//fim else
}else{passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);}

}//fim missão 12














//inicio missao 13
elseif ($missaoexplode[0] == 13) {

if ($townrow["id"] != 4) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}


$foi = 0;
$explodir = explode(",", $userrow["ultmonstro"]);
if ($explodir[0] == "Nibi") {$foi = 1;}

//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}

//completou
if ($foi > 0){

$userrow["gold"] += 400;
$updatequery = doquery("UPDATE {{table}} SET missao='14,0,0,0',gold='".$userrow["gold"]."',missaoswitch='0' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}

}//fim missão 13









//inicio missao 14
elseif ($missaoexplode[0] == 14) {

if ($townrow["id"] != 3) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}


$foi = 0;
$explodir = explode(",", $userrow["ultmonstro"]);
if (($explodir[0] == "Jiroubou Selo 1") && ($explodir[1] >= 3)) {$foi = 1;}

//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}

//completou
if ($foi > 0){

$userrow["gold"] += 400;
$updatequery = doquery("UPDATE {{table}} SET missao='15,0,0,0',gold='".$userrow["gold"]."',missaoswitch='0' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}

}//fim missão 14









//inicio missao 15
elseif ($missaoexplode[0] == 15) {

if ($townrow["id"] != 1) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}


$foi = 0;
for ($i = 1; $i <= 4; $i++){
	$parcial = explode(",",$userrow["bp".$i]);
	if ($parcial[0] == "Runa de Chakra") {$foi = $i;}
}
if ($foi == 0){passarconteudo('Primeiro coloque o item <b>Runa de Chakra</b> na sua mochila e complete o resto do requerimento da missão.', $townrow);}
if ($userrow["missaoswitch"] != 1) {$foi = 0;}

//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}

//completou
if ($foi > 0){

$userrow["gold"] += 1000;
$updatequery = doquery("UPDATE {{table}} SET missao='16,0,0,0',gold='".$userrow["gold"]."',missaoswitch='0', bp$foi='Flauta de Tayuya,48,4,X' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}

}//fim missão 15












//inicio missao 16
elseif ($missaoexplode[0] == 16) {

if ($townrow["id"] != 5) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}


$foi = 0;
$explodir = explode(",", $userrow["ultmonstro"]);
if ($explodir[0] == "Ningendo") {$foi = 1;}

//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}

//completou
if ($foi > 0){

$userrow["gold"] += 5000;
$updatequery = doquery("UPDATE {{table}} SET missao='17,0,10,6',gold='".$userrow["gold"]."',missaoswitch='0' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}

}//fim missão 16








//inicio missao 17
elseif ($missaoexplode[0] == 17) {

if ($townrow["id"] != 5) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);
}
$foi = 0;
$resultarray = fazermissao($missaoexplode, $townrow);
$novosdados = explode(",", $resultarray);

//para saber se treinou a missão ou não.
if ($novosdados[1] != $missaoexplode[1]){
if ($novosdados[1] == $novosdados[2]){//completou
	$novogold = $userrow["gold"] + 2000;
	$updatequery = doquery("UPDATE {{table}} SET missao='18,0,0,0', gold='$novogold', missaoultdata='None' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso e ganhou sua recompensa.', $townrow);
}else{//nao completou
	$updatequery = doquery("UPDATE {{table}} SET missao='$resultarray', missaoultdata='".date("j/n/Y").";".date("H:i:s")."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
	passarconteudo('Você completou mais uma parte da Missão '.$missaoexplode[0].', e em breve poderá aumentar sua porcentagem de conclusão novamente.', $townrow);
}//fim else
}else{passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);}

}//fim missão 17










//inicio missao 18
elseif ($missaoexplode[0] == 18) {

if ($townrow["id"] != 1) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}


$foi = 0;
for ($i = 1; $i <= 4; $i++){
	$parcial = explode(",",$userrow["bp".$i]);
	if ($parcial[0] == "Força Inútil") {$foi = $i;}
}
if ($foi == 0){passarconteudo('Primeiro coloque o item <b>Força Inútil</b> na sua mochila e complete o resto do requerimento da missão.', $townrow);}
if ($userrow["missaoswitch"] != 1) {$foi = 0;}

//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}

//completou
if ($foi > 0){

$userrow["gold"] += 0;
$updatequery = doquery("UPDATE {{table}} SET missao='19,0,0,0',gold='".$userrow["gold"]."',missaoswitch='0', bp$foi='Cajado Enma,45,1,X' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}

}//fim missão 18










//inicio missao 19
elseif ($missaoexplode[0] == 19) {

if ($townrow["id"] != 1) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}


$foi2 = 0; $foi = 0;
if ($userrow['avatar'] == 16) {$foi2 = 1;}

for ($i = 1; $i <= 4; $i++){
	$parcial = explode(",",$userrow["bp".$i]);
	if ($parcial[0] == "None") {$foi = $i;}
}

//nao completou
if ($foi2 == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}

//completou
if ($foi2 > 0){

	$bancoexp = $userrow['bancogeral'];
	$msg = "";
	if ($foi > 0){$bpitem = "Amuleto de Sapo,49,4,X";}else{$bancoexp = $userrow['bancogeral']."Amuleto de Sapo,49,4,X;"; $msg = " O item <b>Amuleto de Sapo</b> foi transferido para seu banco, uma vez que sua mochila já está cheia."; $foi = 1; $bpitem = $userrow['bp1'];}

$userrow["gold"] += 0;
$updatequery = doquery("UPDATE {{table}} SET missao='20,0,0,0',gold='".$userrow["gold"]."',missaoswitch='0', bancogeral='$bancoexp', bp$foi='$bpitem' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!'.$msg, $townrow);
}

}//fim missão 19










//inicio missao 20
elseif ($missaoexplode[0] == 20) {

if ($townrow["id"] != 1) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}


$foi = 0;
$explodir = explode(",", $userrow["ultmonstro"]);
if ($explodir[0] == "Gakido") {$foi = 1;}
for ($i = 1; $i <= 4; $i++){
	$parcial = explode(",",$userrow["bp".$i]);
	if ($parcial[0] == "None") {$foi2 = $i;}
}

//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}

//completou
if ($foi > 0){

	$bancoexp = $userrow['bancogeral'];
	$msg = "";
	if ($foi2 > 0){$bpitem = "Amuleto de Sapo,49,4,X";}else{$bancoexp = $userrow['bancogeral']."Anel da Sorte,50,4,X;"; $msg = " O item <b>Amuleto de Sapo</b> foi transferido para seu banco, uma vez que sua mochila já está cheia."; $foi2 = 1; $bpitem = $userrow['bp1'];}

$userrow["gold"] += 7000;
$updatequery = doquery("UPDATE {{table}} SET missao='21,0,0,0',gold='".$userrow["gold"]."',missaoswitch='0', bancogeral='$bancoexp', bp$foi2='$bpitem' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!'.$msg, $townrow);
}

}//fim missão 20








//inicio missao 21
elseif ($missaoexplode[0] == 21) {

if ($townrow["id"] != 4) {
passarconteudo('Não há missões nessa vila/cidade no momento.', $townrow);}


$foi = 0;
for ($i = 1; $i <= 4; $i++){
	$parcial = explode(",",$userrow["bp".$i]);
	if ($parcial[0] == "Alma da Névoa") {$foi = $i;}
}
if ($foi == 0){passarconteudo('Primeiro coloque o item <b>Alma da Névoa</b> na sua mochila e complete o resto do requerimento da missão.', $townrow);}
if ($userrow["missaoswitch"] != 1) {$foi = 0;}

//nao completou
if ($foi == 0){
	passarconteudo('Você ainda não completou/possui o requerimento da Missão '.$missaoexplode[0].'.', $townrow);
}

//completou
if ($foi > 0){

$userrow["gold"] += 0;
$updatequery = doquery("UPDATE {{table}} SET missao='22,0,0,0',gold='".$userrow["gold"]."',missaoswitch='0', bp$foi='Protetor de Honra da Névoa,46,3,X' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
passarconteudo('Parabéns! Você completou a Missão '.$missaoexplode[0].' com sucesso!', $townrow);
}

}//fim missão 21







//}//fim das missões    
}








































function fazermissao($missaorow, $townrowant){

global $topvar;
global $userrow;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }


$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysql_fetch_array($townquery);
	
if ($townrow["id"] != $townrowant["id"]) {header('Location: ./treinamentoequests.php?do=quests&conteudo=Você não pode fazer essa Missão fora do(a) '.$townrowant["name"].'');die(); }


//função se passou o tempo necessário ou não.
$today = date("j/n/Y");
$todayhour = date("H:i:s");
$nomedojutsu = $missaorow[0]; //nome do jutsu pra buscar.
$tempoprapassar = $missaorow[3]; //tempo em minutos
//colocando o jutsu no campo, nome, quantos ainda tem q treinar, treinar ao total, dia e hora do ultimo treino, tempo pra passar em minutos. A HORA É - 2 DA HORA DO BRASIL.

					//área die.
					if ($missaorow[1] == $missaorow[2]){header("Location: ./treinamentoequests.php?do=quests&conteudo=Você já completou a Missão ".$nomedojutsu.".");die();}
					include('funcoesinclusas.php');
					
					if ($userrow["missaoultdata"] != "None"){
						//separar
						$vetoraqui = explode(";", $userrow["missaoultdata"]);
						$retorno = tempojutsu($vetoraqui[0], $vetoraqui[1], $missaorow[3]);
						if ($retorno != "ok"){header("Location: ./treinamentoequests.php?do=quests&conteudo=Você ainda não pode praticar/fazer a missão, é preciso aguardar ".$retorno." minuto(s) até que você possa praticar/fazer a missão novamente.");die();}
					}
					//fim area die.
					
					$missaorow[1] += 1;
					if ($missaorow[1] > $missaorow[2]){$missaorow[1] = $missaorow[2];}
					

			return $missaorow[0].",".$missaorow[1].",".$missaorow[2].",".$missaorow[3];

			//retornar
			 
}
?>