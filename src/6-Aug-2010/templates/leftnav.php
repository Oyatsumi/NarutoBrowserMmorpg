<?php

$template = <<<THEVERYENDOFYOU
<center><table width="206" border="0" cellspacing="0" cellpadding="0" background="layoutnovo/menuslados/meio.png">
<tr><td height="187" width="224" colspan="3" background="layoutnovo/menuslados/naruto.png"></td></tr>
<tr background="layoutnovo/menuslados/meio.png"><td width="5"></td><td><center>{{topnav}}</center></td><td width="5"></td>
</tr><tr>
<td width="206" colspan="3" height="21" background="layoutnovo/menuslados/baixo.png"></td>
</tr></table></center>
<br>




<table width="215">
<tr><td><center><img src="layoutnovo/buttons/coordenada.png" alt="Location" title="Location" /></center></td></tr>
<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/menuslados/cima.png"></td></tr><tr background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td>
Use os botões de direção para se movimentar:<br><br>
<form action="index.php?do=move" method="post">
<center>
<input name="north" type="submit" value="Norte" /><br />
<input name="west" type="submit" value="Oeste" /><input name="east" type="submit" value="Leste" /><br />
<input name="south" type="submit" style="width:53px" value="Sul" />
</center>
</form><br><center>
Latitude: {{latitude}}<br />
Longitude: {{longitude}}<br /><br></center>
<center><img src="layoutnovo/menuslados/{{imagem}}"></center>
<center><a href="javascript:openmappopup()">Ver Mapa</a></center>


</td>
<td width="5"></td></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>

</td></tr>
</table><br />

<table width="215">
<tr><td><center><img src="layoutnovo/buttons/local.png" alt="Towns" title="Towns" /></center></td></tr>
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