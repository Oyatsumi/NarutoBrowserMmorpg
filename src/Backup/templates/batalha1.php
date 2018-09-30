<?php
$nomedochar = $_GET['nomedochar'];
$template = <<<THEVERYENDOFYOU
<form action="users.php?do=batalha1" method="post">
<table width="100%">
<tr><td colspan="2">Complete os campos abaixo corretamente:</td></tr>
<tr><td width="20%">Duelar com(Nome do Jogador):</td><td><input type="text" name="jogador" size="30" maxlength="30"  value="$nomedochar" /></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Duelar" /> <input type="reset" name="reset" value="Apagar Campos" /></td></tr>
</table>
</form>
THEVERYENDOFYOU;
?>