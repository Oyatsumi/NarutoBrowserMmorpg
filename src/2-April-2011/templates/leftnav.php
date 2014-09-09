<?php

//matriz mapa / contas / ajeitamento.

include('minimap.php');
include('historico.php');
$monstro = explode(";",$userrow["ultmonstro"]);

if ($userrow["pmsnovas"] == 1){$imagem = "lergif.gif";}else{$imagem = "ler1.jpg";}

$template = <<<THEVERYENDOFYOU

<br><br>

<table width="215">

<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/buttons/coordenada.png"></td></tr><tr background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td>


Use os botões de direção para se movimentar:<br><br>
<center>$minimap</center>
<center><a href="javascript:openmappopup()">Ver Mapa Completo</a></center><br>
<form action="index.php?do=move" method="post">
<center>
<input name="north" type="submit" value="Norte" /><br />
<input name="west" type="submit" value="Oeste" /><input name="east" type="submit" value="Leste" /><br />
<input name="south" type="submit" style="width:53px" value="Sul" />
</center><br>
</form><center>
Latitude: {{latitude}}<br />
Longitude: {{longitude}}</center>




</td>
<td width="5"></td></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>

</td></tr>
</table><br />










<table width="215">

<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/buttons/local.png"></td></tr><tr   background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td >
<center>{{townslist}}</center>
<div style="position: absolute; visibility: hidden">
<table width="100%" height="5"><tr><td><img src="images/comandos.jpg"></td><td align="right"><img src="images/viajar.jpg"></td></tr></table></div>
<center>{{adminlink}}<div id="procurarjog"></div>
<a href="pm.php?do=ler"><img title="Ler Mensagens Privadas" border="0" id="msg1" src="images/$imagem" onmouseover="document.getElementById('msg1').src = 'images/ler2.jpg';" onmouseout="document.getElementById('msg1').src = 'images/$imagem';"/></a><a href="mainmsg.php?do2=enviarpm"><img title="Enviar Mensagens Privadas" border="0" id="msg2" src="images/enviar1.jpg" onmouseover="document.getElementById('msg2').src = 'images/enviar2.jpg';" onmouseout="document.getElementById('msg2').src = 'images/enviar1.jpg';"/></a>
</center>
</td>
<td width="5"></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>
</td></tr>
</table><br />
















<table width="215">

<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/buttons/historico.png"></td></tr><tr   background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td >


<center><div style="position:relative; top:-6px"><table cellspacing="0" cellpadding="0" border="0" background="images/fundosasuke.gif"><tr><td background="images/sasuke2.jpg" style="background-repeat:no-repeat;" height="178" width="175"><div style="padding-top: 89px; padding-left: 19px;"><div style="height: 40px; width: 140px;"><font color="#452202">$monstro[0], morto $monstro[1] vez(es).</font></div></div></td></tr>
<tr><td background="images/fundosasuke.gif"><center><font color="#452202">$fimh</font></center></td></tr><tr><td background="images/sasukebaixo.jpg" height="40"></td></tr></table></div></center>


</td>
<td width="5"></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>
</td></tr>
</table><br/>















THEVERYENDOFYOU;
?>