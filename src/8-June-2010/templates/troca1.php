<?php
$template = <<<THEVERYENDOFYOU
<form action="users.php?do=troca1" method="post">
<table width="100%">
<tr><td colspan="2">Complete os campos abaixo corretamente:</td></tr>
<tr><td width="20%">Trocar com(Nome do Jogador):</td><td><input type="text" name="username" size="30" maxlength="30" /></td></tr>
<tr><td width="20%">Escolha o Item:</td><td>

<select name="Combobox1" size="1" id="Combobox1">
<option selected value="0">Escolha</option>
<option value="1">Arma do Banco de Trocas</option>
<option value="2">Colete do Banco de Trocas</option>
<option value="3">Bandana do Banco de Trocas</option>
<option value="4">Slot 1 do Banco de Trocas</option>
<option value="5">Slot 2 do Banco de Trocas</option>
<option value="6">Slot 3 do Banco de Trocas</option>

</td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Realizar Troca" /> <input type="reset" name="reset" value="Apagar Campos" /></td></tr>
</table>
</form>
THEVERYENDOFYOU;
?>