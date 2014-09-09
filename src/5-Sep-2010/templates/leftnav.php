<?php

//matriz mapa / contas / ajeitamento.

include('minimap.php');


$template = <<<THEVERYENDOFYOU
<center><table width="206" border="0" cellspacing="0" cellpadding="0" background="layoutnovo/menuslados/meio.png">
<tr><td height="210" width="224" colspan="3" background="layoutnovo/menuslados/naruto.png"></td></tr>
<tr background="layoutnovo/menuslados/meio.png"><td width="5"></td><td><center>{{topnav}}</center></td><td width="5"></td>
</tr><tr>
<td width="206" colspan="3" height="21" background="layoutnovo/menuslados/baixo.png"></td>
</tr></table></center>
<br>




<table width="215">
<tr><td><center><img src="layoutnovo/buttons/coordenada.png" alt="Localização" title="Localização" /></center></td></tr>
<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/menuslados/cima.png"></td></tr><tr background="layoutnovo/menuslados/meio.png">
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
</center>
</form><center><br>
Latitude: {{latitude}}<br />
Longitude: {{longitude}}</center>




</td>
<td width="5"></td></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>

</td></tr>
</table><br />
















<table width="215">
<tr><td><center><img src="layoutnovo/buttons/historico.png" alt="Histórico" title="Histórico" /></center></td></tr>
<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/menuslados/cima.png"></td></tr><tr   background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td >

<center><table><tr><td><img src="images/parchment.gif" title="Pergaminhos">{{pergaminho}}</td><td><img src="images/diamond.gif" title="Diamantes">{{diamante}}</td></tr></table></center>

<iframe name="hist" id="hist" style="width:179px;height:101px;" src="historico.php" frameborder="0">Seu browser não suporta frames.</iframe><br><br>
<center><a href="historico.php" target="hist">Atualizar Histórico</a>.</center><br>
<center><img src="layoutnovo/menuslados/{{imagem}}"></center>
</td>
<td width="5"></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>
</td></tr>
</table><br/>









<table width="215">
<tr><td><center><img src="layoutnovo/buttons/local.png" alt="Cidades" title="Cidades" /></center></td></tr>
<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/menuslados/cima.png"></td></tr><tr   background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td >

{{currenttown}}
Viajar Para:<br />
{{townslist}}

</td>
<td width="5"></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>
</td></tr>
</table><br />

<table width="215">
<tr><td><center><img src="layoutnovo/buttons/funcionais.png" alt="Functions" title="Functions" /></center></td></tr>
<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/menuslados/cima.png"></td></tr><tr  background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td>

{{forumslink}}
{{adminlink}}
Funções com Jogadores:<br>
<a href="users.php?do=troca1">Realizar uma Troca</a><br />
<a href="users.php?do=batalha1">Realizar um Duelo</a><br /><br>
Funções nas Cidades:<br>
<a href="users.php?do=doardinheiro">Enviar Ryou</a><br />
<a href="users.php?do=doaritem">Enviar Item</a><br />
<a href="users.php?do=banco">Acessar o Banco</a><br /><br>
Outras Funções:<br>
<a href="users.php?do=procurarjogador">Visualizar Personagem</a><br>
<a href="rank.php" target="_blank">Rank</a><br>
<a href="javascript:openchatpopup()">Chat</a><br>
<a href="users.php?do=changepassword">Mudar Senha</a><br />
<a href="login.php?do=logout">Sair</a><br />
<a href="help.php" target="_blank">Ajuda</a>

</td>
<td width="5"></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>

</td></tr>
</table><br />




<table width="215">
<tr><td><center><img src="layoutnovo/buttons/mensagens.png" alt="Mensagens" title="Mensagens" /></center></td></tr>
<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/menuslados/cima.png"></td></tr><tr  background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td>

<center>{{pmsnovas}}</center>

<a href="pm.php?do=ler">Ler Minhas Mensagens</a><br />
<a href="pm.php?do=enviar">Enviar uma Mensagem</a><br />


</td>
<td width="5"></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>

</td></tr>
</table><br />
THEVERYENDOFYOU;
?>