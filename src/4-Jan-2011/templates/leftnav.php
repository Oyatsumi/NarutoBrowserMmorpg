<?php

//matriz mapa / contas / ajeitamento.

include('minimap.php');


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

</td>
<td width="5"></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>
</td></tr>
</table><br />







<table width="215">

<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/buttons/funcionais.png"></td></tr><tr  background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td>


<center>{{forumslink}}
{{adminlink}}<div id="procurarjog"></div>
<table  height="95" width="100%" background="images/fundofuncionais.jpg"><tr><td>
<center><br><table width="94%" style="border:1px #452202 solid" background="images/fundocidade.png"><tr><td bgcolor="#452202"><font color="white">Funções Gerais</font></td></tr>
<tr><td><a href="troca.php?do=troca"><img src="images/24/troca.gif" title="Realizar uma Troca" border="0"></a><a href="users.php?do=batalha1"><img src="images/24/duelar.gif" title="Realizar um Duelo" border="0"></a><a href="encherhp.php"><img src="images/24/descansar.gif" title="Descansar" border="0"></a><a href="javascript:openchatpopup()"><img src="images/24/conversar.gif" title="Abrir Chat Global" border="0"></a><a href="javascript: procurarjogador();"><img src="images/24/visualizar.gif" title="Visualizar um Personagem" border="0"></a></td></tr></table>
</center>
</td></tr></table>
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

<center><table><tr><td><img src="images/parchment.gif" title="Pergaminhos">{{pergaminho}}</td><td><img src="images/diamond.gif" title="Diamantes">{{diamante}}</td></tr></table></center>
<center>Último Inimigo Morto:<br>
{{ultmonstro}}</center>
<iframe name="hist" id="hist" style="width:179px;height:101px;" src="historico.php" frameborder="0">Seu browser não suporta frames.</iframe><br><br>
<center><a href="historico.php" target="hist">Atualizar Histórico</a>.</center><br>
<center><img src="layoutnovo/menuslados/{{imagem}}"></center>
</td>
<td width="5"></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>
</td></tr>
</table><br/>















<table width="215">

<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/buttons/mensagens.png"></td></tr><tr  background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td>

<center>{{pmsnovas}}</center>

<center><table cellpadding="0" cellspacing="0" background="images/fundomail.jpg" height="125" style="background-repeat:no-repeat;;background-position:center top"><tr><td><br><br><a href="pm.php?do=ler"><img title="Ler Mensagens" border="0" id="msg1" src="images/lermsg1.png" onmouseover="document.getElementById('msg1').src = 'images/lermsg2.png';" onmouseout="document.getElementById('msg1').src = 'images/lermsg1.png';"/></a></td><td width="20"></td><td><br><br>
<a href="pm.php?do=enviar"><img title="Enviar Mensagens" border="0" id="msg2" src="images/enviarmsg1.png" onmouseover="document.getElementById('msg2').src = 'images/enviarmsg2.png';" onmouseout="document.getElementById('msg2').src = 'images/enviarmsg1.png';"/></a><br /></td></tr></table></center>


</td>
<td width="5"></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>

</td></tr>
</table><br />
THEVERYENDOFYOU;
?>