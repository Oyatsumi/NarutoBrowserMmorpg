<?php
global $userrow;

if ($userrow == false){
	$widthall = 700;
	$widthp = 0;
	$widths = 0;
}else{
	$widthall = 990;
	$widthp = 204;
	$widths = 204;
}

//Variável da div da janela transparente de mensagens.
global $mainmsg;
include('mostrarmainmsg.php');


include('getheader.php');

//para os icons funcionarem, tem que escrever ,progress;
$template = <<<THEVERYENDOFYOU

<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>  
<title>NarutoOnlineRpg.com :: {{title}}</title>
<style type="text/css" media="screen">@import "novobotao.css";</style>
<style type="text/css">
body {
  background-image: url(images/background.jpg);
  color: #000000;
  font: 11px tahoma;
  margin-top : 0px;
  cursor: url(kunaitransp.cur);

}
div {
	  font: 12px tahoma;
}
table {
  border-style: none;
  padding: 0px;
  font: 12px tahoma;
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
#brancofino{
 	color: #ffffff;
	font-weight: normal;
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
function menuprincipal(conteudo, x, y){
	document.getElementById("menuprincipal").style.left = x + "px";
	document.getElementById("menuprincipal").style.top = y + "px";
	document.getElementById('menuprincipal').innerHTML = "<div style=\"position:relative;z-index:0\"><div style=\"background-image:url(layoutnovo/dropmenu/menucima/cima.png);height:5px;z-index:1;background-repeat:no-repeat\"></div><div style=\"background-image:url(layoutnovo/dropmenu/menucima/meio.png);z-index:0;padding-top:0px\"><div style=\"padding-left:6px;padding-right:11px;padding-top:3px;padding-bottom:5px\">" + conteudo + "</div></div></div>";
}
function explicdrop(objeto, titulo, conteudo, x, y){
	document.getElementById(objeto).onclick = getMouseXY;

	var localx = parseInt(tempX) + parseInt(x) - 175;
	var localy = parseInt(tempY) + parseInt(y) - 5;
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

function fecharmenuprincipal(){
	document.getElementById('menuprincipal').innerHTML = "";
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
<body style="position:relative;left: -10px;" onclick="fecharmenuprincipal()">
<div style="z-index: 1;">
    <center>
<table cellpadding="0" cellspacing="0" border="0" width="$widthall" background="layoutnovo/titulo/cima.jpg" style="background-repeat:no-repeat;background-position:center top;"><tr><td><center>

<table cellspacing="0" cellpadding="0" border="0"><td width="$widthp"><center>{{leftnav}}</center></td><td>
<table width="551" cellspacing="0" cellpadding="0" border="0" ><tr><td height="214">

<div style="position:relative">
<div style="position:absolute;top:183px;left:471px"><a href="index.php"><img src="images/24/gif24.gif" border="0" height="19" width="20" title="Voltar"/></a></div>
<div style="position:absolute;top:183px;left:496px"><a href="javascript:menuprincipal('<a id=\'brancofino\' href=\'help.php\'>Geral</a><br><a href=\'help_monsters.php\' id=\'brancofino\'>Inimigos</a><br><a href=\'help_items.php\' id=\'brancofino\'>Itens</a><br><a href=\'help_spells.php\' id=\'brancofino\'>Jutsus</a><br><a href=\'help_levels.php\' id=\'brancofino\'>Level</a>', '497', '205')"><img src="images/24/gif24.gif" border="0" height="19" width="20" title="Ajuda"/></a></div>
<div style="position:absolute;top:183px;left:521px"><a href="login.php?do=logout"><img src="images/24/gif24.gif" border="0" height="19" width="20" title="Sair"/></a></div>

<div style="position:absolute;top:12px;left:120px"><a href="javascript:menuprincipal('<img src=\'images/setabranca.gif\'><a id=\'brancofino\' href=\'index.php\'>Home</a><br><img src=\'images/setabranca.gif\'><a href=\'rank.php\' id=\'brancofino\'>Rank</a>', '126', '28')"><img src="images/24/gif24.gif" border="0" height="20" width="43"/></a></div>

<div style="position:absolute;top:12px;left:168px"><a href="javascript:menuprincipal('<img src=\'images/setabranca.gif\'><a id=\'brancofino\' href=\'users.php?do=register\'>Criar Conta</a><br><img src=\'images/setabranca.gif\'><a href=\'ativarconta.php\' id=\'brancofino\'>Ativar Conta</a>', '200', '28')"><img src="images/24/gif24.gif" border="0" height="20" width="110"/></a></div>

<div style="position:absolute;top:12px;left:280px"><a href="javascript:menuprincipal('<img src=\'images/setabranca.gif\'><a id=\'brancofino\' href=\'users.php?do=changepassword\'>Mudar Senha</a><br><img src=\'images/setabranca.gif\'><a href=\'users.php?do=lostpassword\' id=\'brancofino\'>Requerer Nova Senha</a><br><img src=\'images/setabranca.gif\'><a href=\'login.php?do=login\' id=\'brancofino\'>Entrar</a>', '300', '28')"><img src="images/24/gif24.gif" border="0" height="20" width="100"/></a></div>

<div style="position:absolute;top:12px;left:383px"><a href="javascript:menuprincipal('<img src=\'images/setabranca.gif\'><a id=\'brancofino\' href=\'login.php?do=logout\'>Deslogar</a>', '390', '28')"><img src="images/24/gif24.gif" border="0" height="20" width="45"/></a></div>

<div id="menuprincipal" style="position:absolute;z-index:10"></div>
</div>


<!-- lugar das divs !-->



</td></tr>

<tr background="layoutnovo/titulo/meio.jpg" width="551"><td background="layoutnovo/titulo/meio.jpg">
<table cellspacing="0" cellpadding="0" border="0"><tr><td width="2"></td><td><div style="position:relative;top:-10px">{{content}}</div>
<br />
<center><table width="90%"><tr>
<td width="25%" align="center">Powered by <a href="http://nigeru.com" target="_new">Nigeru Animes</a></td><td width="25%" align="center">&copy; 2010 by Oyatsumi</td><td width="25%" align="center">{{totaltime}} Segundos, {{numqueries}} Queries</td><td width="25%" align="center">Versão {{version}} {{build}}</td>
</tr></table></center>
</td><td></td></tr></table>

</td></tr>
<tr background="layoutnovo/titulo/fim.jpg" height="12" width="551"><td background="layoutnovo/titulo/fim.jpg" ></td></tr>
</table>


</td><td width="$widths"><center>{{rightnav}}</center></td></tr></table>

</center>
</td>

</tr></table></center></div>

<div id="dropmenu" style="z-index:6;position:absolute;left:1px;top:1px" align="left"></div>
<div id="explicmenu" style="z-index:5;position:absolute;left:1px;top:1px" align="left"></div>
<div id="char" style="z-index:5;position:absolute;left:1px;top:1px" align="left"></div>
<div id="mainmsg" style="z-index:7;position:fixed;left:38%;top:32%;opacity:0.9;filter:alpha(opacity=90)" align="left">$mainmsg</div>








</body>

</html>



THEVERYENDOFYOU;
?>