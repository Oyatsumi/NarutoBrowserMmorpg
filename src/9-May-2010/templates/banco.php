<?php
$template = <<<THEVERYENDOFYOU
<form action="users.php?do=banco" method="post">
<table width="100%">
<tr><td colspan="2">Edite o seu Inventário:<br><font color="red">Lembrando que, cada vez que você utiliza o banco, você têm que pagar
uma taxa de 20 Ryou.<br>Tome cuidado com suas ações! Elas são irreversíveis!</font></td></tr>

<tr><td width=20>Arma:</td><td><select name="Combobox1" size="1" id="Combobox1">
<option selected value="0">Manter</option>
<option value="1">Enviar ou Retirar do Banco de Trocas</option>
<option value="3">Enviar ou Retirar do Banco de Armazenamento</option>
<option value="2">Deletar Arma do Banco de Trocas</option>
</td></tr>

</select></td></tr>

<tr><td width=20>Colete:</td>
<td><select name="Combobox2" size="1" id="Combobox2">
<option selected value="0">Manter</option>
<option value="1">Enviar ou Retirar do Banco de Trocas</option>
<option value="3">Enviar ou Retirar do Banco de Armazenamento</option>
<option value="2">Deletar Colete do Banco de Trocas</option></td></tr>


<tr><td width=20>Bandana:</td>
<td><select name="Combobox3" size="1" id="Combobox3">
<option selected value="0">Manter</option>
<option value="1">Enviar ou Retirar do Banco de Trocas</option>
<option value="3">Enviar ou Retirar do Banco de Armazenamento</option>
<option value="2">Deletar Bandana do Banco de Trocas</option></td></tr>

<tr><td width=20>Slot1:</td>
<td><select name="Combobox4" size="1" id="Combobox4">
<option selected value="0">Manter</option>
<option value="1">Enviar ou Retirar do Banco de Trocas</option>
<option value="3">Enviar ou Retirar do Banco de Armazenamento</option>
<option value="2">Deletar Slot 1 do Banco de Trocas</option></td></tr>

<tr><td width=20>Slot2:</td>
<td><select name="Combobox5" size="1" id="Combobox5">
<option selected value="0">Manter</option>
<option value="1">Enviar ou Retirar do Banco de Trocas</option>
<option value="3">Enviar ou Retirar do Banco de Armazenamento</option>
<option value="2">Deletar Slot 2 do Banco de Trocas</option></td></tr>

<tr><td width=20>Slot3:</td>
<td><select name="Combobox6" size="1" id="Combobox6">
<option selected value="0">Manter</option>
<option value="1">Enviar ou Retirar do Banco de Trocas</option>
<option value="3">Enviar ou Retirar do Banco de Armazenamento</option>
<option value="2">Deletar Slot 3 do Banco de Trocas</option></td></tr>

<tr><td colspan="2"><input type="submit" name="submit" value="Fazer Alterações" /> </tr>
</table>
</form>
THEVERYENDOFYOU;
?>