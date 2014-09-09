<?php
$template = <<<THEVERYENDOFYOU
<form action="users.php?do=doardinheiro" method="post">
<table width="100%">
<tr><td colspan="2">Complete os campos abaixo corretamente:</td></tr>
<tr><td width="20%">Doar Para(Nome do Jogador):</td><td><input type="text" name="username" size="30" maxlength="30" /></td></tr>
<tr><td width="20%">Quantidade de Ryou:</td><td><input type="text" name="oldpass" size="20" /></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Doar" /> <input type="reset" name="reset" value="Apagar Campos" /></td></tr>
</table>
</form>
THEVERYENDOFYOU;
?>