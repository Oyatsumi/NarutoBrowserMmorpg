<?php
$template = <<<THEVERYENDOFYOU
<center><img src="images/procurar.gif" alt="Procurar Jogador" /></center><br>
<form action="users.php?do=procurarjogador" method="post">
<table width="100%">
<tr><td colspan="2">Complete o campo abaixo corretamente:</td></tr>
<tr><td width="20%">Procurar(Nome do Jogador):</td><td><input type="text" name="username" size="30" maxlength="30" /></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Procurar" /> <input type="reset" name="reset" value="Apagar Campo" /></td></tr>
</table>
</form>
THEVERYENDOFYOU;
?>