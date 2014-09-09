<?php
$template = <<<THEVERYENDOFYOU
<table width="100%">
<tr><td align="center"><center><img src="images/town_{{id}}.gif" alt="Bem-vindo à {{name}}" title="Bem-vindo à {{name}}" /></center>
{{indexconteudo}}
<center><table width="450"><tr><td><img src="layoutnovo/avatares/kages/{{id}}.png" align="left"><br><b>{{kage}} diz:</b><br>
Bem vindo(a) ao meu país. Aqui você pode completar missões, descansar, descansar em uma pousada para recuperar totalmente seu HP, Chakra e Pontos de Viagem... Você também pode completar as missões do meu país. Sinta-se a vontade... Para ficar mais forte, você pode comprar Armas, Coletes e Bandanas... Para viajar para outros países, você pode comprar Mapas, ou descobrir sua localização sozinho...</td></tr></table></center>

</td></tr>
<tr><td>
<b>Opções da Cidade:</b><br /><br>
Funções de Compras:<br>
<ul>
<li /><a href="index.php?do=buy">Comprar Arma/Colete/Bandana</a>
<li /><a href="index.php?do=maps">Comprar Mapas</a>
</ul>
Funções de Recuperar HP, Chakra e Pontos de Viagem:<br>
<ul>
<li /><a href="encherhp.php">Sentar e descansar</a>
<li /><a href="index.php?do=inn">Descansar numa Pousada</a>
</ul>
Funções de Banco e Correio:<br>
<ul>
<li /><a href="users.php?do=banco">Acessar meu Banco</a>
<li /><a href="users.php?do=doaritem">Enviar Item</a>
<li /><a href="users.php?do=doardinheiro">Enviar Ryou</a>
</ul>
Outras Funções:<br>
<ul>
<li /><a href="alquimia.php?do=fundir">Alquimia de Itens</a>
<li /><a href="missoes.php?do=missao">Completar Missões</a>
<li /><a href="graduacao.php?do=graduacao">Graduar-se</a>
<li /><a href="fala.php?do=falar">Recolher Informações</a>
{{outros}}
</ul>
</td></tr>
<tr><td><center>
{{news}}
<br />
<table width="95%">
<tr><td width="50%">
{{whosonline}}
</td><td>
{{babblebox}}
</td></tr>
</table>
</td></tr>
</table>
THEVERYENDOFYOU;
?>