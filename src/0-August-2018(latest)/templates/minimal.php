<?php
global $userrow;

if ($userrow == false){
	$widthall = 700;
	$widthp = 13;
	$widths = 0;
}else{
	$widthall = 1028;
	$widthp = 233;
	$widths = 220;
}

//Variável da div da janela transparente de mensagens.
global $mainmsg;
include('mostrarmainmsg.php');


//imagem do nome da caixa conteúdo
global $conteudoglobal;
if ($conteudoglobal == ""){if ($userrow["currentaction"] == "Fighting"){$img = "vcbatalha";}
elseif ($userrow["currentaction"] == "Exploring"){$img = "vcexpl";}
elseif ($userrow["currentaction"] == "In Town"){$img = "vcvila";}
else{$img = "outro";}
}

include('getheader.php');

//para os icons funcionarem, tem que escrever ,progress;
$template = <<<THEVERYENDOFYOU

<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>  
<title>{{title}}</title>
<style type="text/css" media="screen">@import "novobotao.css";</style>
<style type="text/css">
body {
  background-image: url(images/background.jpg);
  color: #000000;
  font: 12px tahoma;
  margin-top : 0px;

}
table {
  border-style: none;
  padding: 0px;
  font: 11px tahoma;
  }
#divchat {
    padding: 2px;
    border: solid 1px black;
    margin: 2px;
    text-align: left;
}
input {
border:1px #000000 solid;
background-color:#FBF5DB;
 }
 textarea {
border:1px #000000 solid;
background-color:#FBF5DB;
 }
 select {
border:1px #000000 solid;
background-color:#FBF5DB;
 }
 #formback{
	 display:block;
	 background-image: url("layoutnovo/dropmenu/fundo.jpg");margin-top:	13px;margin-left:30px;margin-right:30px;
 }
 fieldset {padding:3px;padding-bottom:6px;padding-top:15px;background-image: url("layoutnovo/dropmenu/narutoformback.jpg");background-repeat: no-repeat;background-position: bottom right;	border: solid 1px black; display:block;position:relative;

}
td {
  border-style: none;
  padding: 3px;
  vertical-align: top;
  text-align: left;
}
td.top {
  border-bottom: solid 2px black;
}
td.left {
  width: 180px;
  border-right: solid 2px black;

  }
  

td.right {
  width: 180px;
  border-left: solid 2px black;
}
a {
    color: #663300;
    text-decoration: none;
    font-weight: bold;
	cursor: url(kunai.cur);
}
#java {
    color: #c8beb1;

}
#adm{
 	color: #0a9214;
}
#gm{
 	color: #0430bc;
}
#vermelho{
 	color: #ff0000;
}
#tutor{
 	color: #c5a111;
}
#dropid{
 	color: #000000;
	font-weight: normal;
}
	
		legend {  
		position:absolute;
		text-transform:		uppercase;
		font-size:			1.3em;
		padding:			5px;
		margin-left:		1em;
		color:				#ffffff;
		background:			#452202;
		display:block;
		margin-top:-30px;
	}

a:hover {
    color: #330000;
}
.small {
  font: 11px tahoma;
}
.highlight {
  color: red;
}
.light {
  color: #999999;
}
.title {
  border: solid 1px black;
  background-color: #eeeeee;
  font-weight: bold;
  padding: 5px;
  margin: 3px;
}
.copyright {
  border: solid 1px black;
  background-color: #eeeeee;
  font: 11px tahoma;
}
</style>
<script>
function opencharpopup(){
var soteste = document.location.href;
var popurl="index.php?do=showchar"
winpops=window.open(popurl,"","width=250,height=500,scrollbars")
}
function mostrarchar(nome){
var popurl="mostrarchar.php?nomechar=" + nome;
winpops=window.open(popurl,"","width=250,height=500,scrollbars")
}
function openmappopup(azul){
if (azul == undefined){
var popurl="index.php?do=showmap"}else{
var popurl="index.php?do=showmap&lugarazul="+azul;}
winpops=window.open(popurl,"","width=559,height=559,scrollbars")
}
function openmaprespawn(item){
if (item == undefined){
var popurl="index.php?do=showmap"}else{
var popurl="index.php?do=showmap&item="+item;}	
winpops=window.open(popurl,"","width=559,height=559,scrollbars")
}
function openmapmonster(nome){
if (nome == undefined){
var popurl="index.php?do=showmap"}else{
var popurl="index.php?do=showmap&monstro="+nome;}	
winpops=window.open(popurl,"","width=559,height=559,scrollbars")
}
function openchatpopup(){
var popurl="index.php?do=babblebox&tamanho=55"
winpops=window.open(popurl,"","width=400,height=400,scrollbars")
}
function mostrarjogadores(passandoconteudo){
document.getElementById('jogadoresmapa').innerHTML=passandoconteudo;
}
function fecharjogadores(){
document.getElementById('jogadoresmapa').innerHTML="";
}
function procurarjogador(){
document.getElementById('procurarjog').innerHTML = "<center><form onsubmit=\"var nome = document.getElementById('campo').value; mostrarchar(nome);\"><input type='text' name='jogador' id='campo'><br><input type='submit' id='blahblah' name='submit' value='' style='height:5px;'></form></center>"; sumirbotao('blahblah');
document.getElementById('naruto').innerHTML = "Escreva o nome do jogador no campo.";
}
function mostrartreino(numero, recompensa, requerimento, localtreino){
nome = "elemento" + numero;
document.getElementById(nome).innerHTML="<table width=\"100%\"><tr bgcolor=\"#E4D094\"><td width=\"110\">Requerimento:</td><td>" + requerimento + "</td></tr><tr bgcolor=\"#FFF1C7\"><td>Recompensa:</td><td>" + recompensa + "</td></tr><tr bgcolor=\"#E4D094\"><td>Local de Treino:</td><td>" + localtreino + "</td></tr></table>";
}
function mostrarquest(numero, recompensa, requerimento, localtreino, nivel, conclusao, itemprocurado, local, monstroprocurado){
nome = "elemento" + numero;
if ((local == undefined) || (local == '')) {local = "";}else{local = " <a href=\"javascript: openmappopup('" + local + "')\"><img src=\"images/maximizar.gif\" border=\"0\" title=\"Mostrar Local no Mapa\"></a>";}
if ((monstroprocurado == undefined) || (monstroprocurado == '')){monstroprocurado = "";}else{monstroprocurado = "<a href=\"javascript: openmapmonster('" + monstroprocurado + "')\"><img src=\"images/maximizar.gif\" border=\"0\" title=\"Mostrar Respawn do " + monstroprocurado + "\"></a>";}
if ((itemprocurado == undefined) || (itemprocurado == "")){
document.getElementById(nome).innerHTML="<table width=\"100%\"><tr bgcolor=\"#E4D094\"><td width=\"130\">Requerimento: " + monstroprocurado + local + "</td><td>" + requerimento + "</td></tr><tr bgcolor=\"#FFF1C7\"><td>Recompensa:</td><td>" + recompensa + "</td></tr><tr bgcolor=\"#E4D094\"><td>Local de Conclusão:</td><td>" + localtreino + "</td></tr><tr bgcolor=\"#FFF1C7\"><td>Nível:</td><td>" + nivel + "</td></tr><tr bgcolor=\"#E4D094\"><td>Conclusão:</td><td>" + conclusao + "</td></tr></table>";}
else{document.getElementById(nome).innerHTML="<table width=\"100%\"><tr bgcolor=\"#E4D094\"><td width=\"130\">Requerimento: " + monstroprocurado + local + "<a href=\"javascript: openmaprespawn('" + itemprocurado + "')\"><img src=\"images/maximizar.gif\" border=\"0\" title=\"Mostrar Onde Dropa o(a) " + itemprocurado + "\"></a></td><td>" + requerimento + "</td></tr><tr bgcolor=\"#FFF1C7\"><td>Recompensa:</td><td>" + recompensa + "</td></tr><tr bgcolor=\"#E4D094\"><td>Local de Conclusão:</td><td>" + localtreino + "</td></tr><tr bgcolor=\"#FFF1C7\"><td>Nível:</td><td>" + nivel + "</td></tr><tr bgcolor=\"#E4D094\"><td>Conclusão:</td><td>" + conclusao + "</td></tr></table>";}
}
function mostrargraduacao(nomeelemento, requerimento, ganhos, itemprocurado){
if (itemprocurado == undefined) {itemprocurado = "";}else{itemprocurado = " <a href=\"javascript: openmaprespawn('" + itemprocurado + "')\"><img src=\"images/maximizar.gif\" border=\"0\" title=\"Mostrar Onde Dropa o Item\"></a>";}
document.getElementById(nomeelemento).innerHTML = "<table width=\"100%\"><tr bgcolor=\"#E4D094\"><td width=\"130\">Requerimento: " + itemprocurado + "</td><td>" + requerimento + "</td></tr><tr bgcolor=\"#FFF1C7\"><td>Bônus:</td><td>" + ganhos + "</td></tr></table>";
}
function escondertreino(numero){
nome = "elemento" + numero;
document.getElementById(nome).innerHTML="";
}
function sumirbotao(id){
	document.getElementById(id).style.visibility = 'hidden';
}
function novousuario(){
	document.getElementById('menujavascript').innerHTML = "<a href=\"javascript: resetarmenu();\"><img src=\"images/setamenujava.gif\" border=\"0\" align=\"left\"></a> <font color=white><a href=\"users.php?do=register\" id=\"java\">Criar Conta</a> | <a href=\"ativarconta.php\" id=\"java\">Ativar Conta</a></font>";
}
function site(){
	document.getElementById('menujavascript').innerHTML = "<a href=\"javascript: resetarmenu();\"><img src=\"images/setamenujava.gif\" border=\"0\" align=\"left\"></a> <font color=white><a href=\"index.php\" id=\"java\">Home</a> | <a href=\"rank.php\" id=\"java\">Rank</a></font>";
}
function minhaconta(){
	document.getElementById('menujavascript').innerHTML = "<a href=\"javascript: resetarmenu();\"><img src=\"images/setamenujava.gif\" border=\"0\" align=\"left\"></a> <font color=white><a href=\"users.php?do=changepassword\" id=\"java\">Mudar Senha</a> | <a href=\"users.php?do=lostpassword\" id=\"java\">Requerer Nova Senha</a> | <a href=\"login.php?do=login\" id=\"java\">Entrar</a></font>";
}
function ajuda(){
	document.getElementById('menujavascript').innerHTML = "<a href=\"javascript: resetarmenu();\"><img src=\"images/setamenujava.gif\" border=\"0\" align=\"left\"></a> <font color=white><a href=\"help.php\" id=\"java\">Ajuda Geral</a> | <a href=\"help_monsters.php\" id=\"java\">Inimigos</a> | <a href=\"help_spells.php\" id=\"java\">Jutsus</a> | <a href=\"help_items.php\" id=\"java\">Itens</a> | <a href=\"help_levels.php\" id=\"java\">Level</a></font>";
}
function resetarmenu(){
	document.getElementById('menujavascript').innerHTML = "<font color=white><a href=\"javascript: site();\" id=\"java\">Jogo</a> | <a href=\"javascript: novousuario();\" id=\"java\">Novo Usuário</a> | <a href=\"javascript: minhaconta();\" id=\"java\">Minha Conta</a> | <a href=\"javascript: ajuda();\" id=\"java\">Ajuda</a> | <a href=\"login.php?do=logout\" id=\"java\">Sair</a></font>";
}

var tempX = 0;
var tempY = 0;
var IE = document.all?true:false;
if (!IE) {document.captureEvents(Event.MOUSEMOVE);}
document.onmousemove = getMouseXY;

function getMouseXY(e) {
  if (IE) { // grab the x-y pos.s if browser is IE
  if (document.documentElement && !document.documentElement.scrollTop){    tempX = event.clientX + document.documentElement.scrollLeft
    tempY = event.clientY + document.documentElement.scrollTop}
// IE6 +4.01 but no scrolling going on
	else if (document.documentElement && document.documentElement.scrollTop){    tempX = event.clientX + document.documentElement.scrollLeft
    tempY = event.clientY + document.documentElement.scrollTop}
// IE6 +4.01 and user has scrolled
	else if (document.body && document.body.scrollTop){    tempX = event.clientX + document.body.scrollLeft
    tempY = event.clientY + document.body.scrollTop}
// IE5 or DTD 3.2 

  } else {  // grab the x-y pos.s if browser is NS
    tempX = e.pageX
    tempY = e.pageY
  }  
  // catch possible negative values in NS4
  if (tempX < 0){tempX = 0}
  if (tempY < 0){tempY = 0}  
  // show the position values in the form named Show
  // in the text fields named MouseX and MouseY

  return true
}

function menudrop(objeto, titulo, conteudo, x, y){
	document.getElementById(objeto).onclick = getMouseXY;

	var localx = parseInt(tempX) + parseInt(x) - 175;
	var localy = parseInt(tempY) + parseInt(y);
	document.getElementById("dropmenu").style.left = localx + "px";
	document.getElementById("dropmenu").style.top = localy + "px";
	document.getElementById('dropmenu').innerHTML = "<div style=\"position:relative;width:174px;background-image: url(layoutnovo/dropmenu/fundotabela.gif);z-index:0;background-repeat:repeat-y;\"><div style=\"background-image:url(layoutnovo/dropmenu/cima.gif);height:27px;z-index:1\"><div style=\"padding-top:10px;padding-right:14px\"><center>" + titulo + "</center><div style=\"position:absolute; left:153px;top:11px\"><a href=\"javascript:fechardrop();\"><img src=\"layoutnovo/dropmenu/direita/fechar.gif\" border=\"0\"></a></div></div></div><div style=\"background-image: url(layoutnovo/dropmenu/fundotabela.gif);z-index:0;background-repeat:repeat-y;padding-top:3px\"><div style=\"padding-left:11px;padding-right:11px;padding-top:3px;padding-bottom:2px\">" + conteudo + "</div><div style=\"background-image:url(layoutnovo/dropmenu/fim.gif);height:7px\"></div></div></div>";
}

function menudropdir(objeto, titulo, conteudo, x, y){
	document.getElementById(objeto).onclick = getMouseXY;

	var localx = parseInt(tempX) + parseInt(x) + 20;
	var localy = parseInt(tempY) + parseInt(y);
	document.getElementById("dropmenu").style.left = localx + "px";
	document.getElementById("dropmenu").style.top = localy + "px";
	document.getElementById('dropmenu').innerHTML = "<div style=\"position:relative;width:174px;background-image: url(layoutnovo/dropmenu/direita/fundotabela.gif);z-index:0;background-repeat:repeat-y;\"><div style=\"background-image:url(layoutnovo/dropmenu/direita/cima.gif);height:27px;z-index:1\"><div style=\"padding-top:10px;padding-right:14px\"><center>" + titulo + "</center><div style=\"position:absolute; left:155px;top:11px\"><a href=\"javascript:fechardrop();\"><img src=\"layoutnovo/dropmenu/direita/fechar.gif\" border=\"0\"></a></div></div></div><div style=\"background-image: url(layoutnovo/dropmenu/direita/fundotabela.gif);z-index:0;background-repeat:repeat-y;padding-top:3px\"><div style=\"padding-left:11px;padding-right:11px;padding-top:3px;padding-bottom:2px\">" + conteudo + "</div><div style=\"background-image:url(layoutnovo/dropmenu/direita/fim.gif);height:7px\"></div></div></div>";
}
function explicdrop(objeto, titulo, conteudo, x, y){
	document.getElementById(objeto).onclick = getMouseXY;

	var localx = parseInt(tempX) + parseInt(x) - 95;
	var localy = parseInt(tempY) + parseInt(y) + 20;
	document.getElementById("explicmenu").style.left = localx + "px";
	document.getElementById("explicmenu").style.top = localy + "px";
	document.getElementById('explicmenu').innerHTML = "<div style=\"position:relative;width:174px\"><div style=\"background-image:url(layoutnovo/dropmenu/transparente/cima.png);height:2px;z-index:1\"></div><div style=\"background-image: url(layoutnovo/dropmenu/transparente/fundotabela.png);z-index:0;background-repeat:repeat-y;padding-top:3px\"><center>" + titulo + "</center><div style=\"padding-left:11px;padding-right:11px;padding-top:7px;padding-bottom:2px\">" + conteudo + "</div><div style=\"background-image:url(layoutnovo/dropmenu/transparente/fim.png);height:7px\"></div></div></div>";
}

function yesorno(objeto, conteudo, yes, no){
	document.getElementById(objeto).onclick = getMouseXY;

	var localx = parseInt(tempX) - 80;
	var localy = parseInt(tempY);
	document.getElementById("explicmenu").style.left = localx + "px";
	document.getElementById("explicmenu").style.top = localy + "px";
	document.getElementById('explicmenu').innerHTML = "<table width=\"174\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" background=\"layoutnovo/dropmenu/transparente/fundotabela.png\" style=\"background-repeat:no-repeat;;background-position:center center\" ><tr><td background=\"layoutnovo/dropmenu/transparente/cima.png\" style=\"background-repeat:no-repeat;\" height=\"20\"><center><div style=\"padding-top: 3px\">" + conteudo + "</div></center></td></tr><tr><td><center><table><tr><td></td><td><center><a href=\"" + yes + "\" title=\"Sim\"><img src=\"images/aceitar.gif\" border=\"0\"></a><a href=\"" + no + "\" title=\"Não\"><img src=\"images/deletar.gif\" border=\"0\"></a></center></td></tr></table></center></td></tr><tr><td background=\"layoutnovo/dropmenu/transparente/fim.png\" height=\"7\"></td></tr></table>";
}


function fecharexplic(){
	document.getElementById('explicmenu').innerHTML = "";
}

function fechardrop(){
	document.getElementById('dropmenu').innerHTML = "";
}

function fechargrande(){
	document.getElementById('mainmsg').innerHTML = "";
}

function mostrarpass(id){
	if (document.getElementById(id).type == "password"){
		document.getElementById(id).type = "text";}
	else{
		document.getElementById(id).type = "password";
	}
}	

function opcaochar(nome){
	menudropdir("char", nome, "<img src=\"images/seta.gif\"><a href=\"javascript: mostrarchar('" + nome + "')\" id=\"dropid\">Visualizar Perfil</a><br><img src=\"images/seta.gif\"><a href=\"mainmsg.php?do2=enviarpm&nomedochar=" + nome + "\" id=\"dropid\">Enviar Mensagem</a>", "1", "1");
}

</script>

</head>
<body>

{{content}}
<div id="explicmenu" style="z-index:5;position:absolute;left:1px;top:1px" align="left"></div>








</body>

</html>



THEVERYENDOFYOU;
?>