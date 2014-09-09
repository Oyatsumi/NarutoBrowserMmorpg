<?php
$template = <<<THEVERYENDOFYOU
<form action="users.php?do=doaritem" method="post">
<table width="100%">
<tr><td colspan="2">Complete os campos para Envio:<br><font color="red">Para enviar um item para outro jogador, há um custo de 40 Ryou.<br>Ao doar, isso se fará irreversível.</font></td></tr>
<tr><td width="20%">Doar Para(Nome do Jogador):</td><td><input type="text" name="username" size="30" maxlength="30" /></td></tr>
<tr><td width="20%">Enviar:</td><td>

<select name="Combobox1" size="1" id="Combobox1">
<option selected value="0">Escolha</option>
<option value="1">Arma que está no meu Banco</option>
<option value="2">Colete que está no meu Banco</option>
<option value="3">Bandana que está no meu Banco</option>
<option value="4">Slot 1 que está no meu Banco</option>
<option value="5">Slot 2 que está no meu Banco</option>
<option value="6">Slot 3 que está no meu Banco</option>

</td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Doar" /> <input type="reset" name="reset" value="Apagar Campos" /></td></tr>
</table>
</form>
THEVERYENDOFYOU;
?>