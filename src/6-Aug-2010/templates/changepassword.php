<?php
$template = <<<THEVERYENDOFYOU
<form action="users.php?do=changepassword" method="post">
<table width="100%">
<tr><td colspan="2">Use o formulário abaixo para mudar sua senha. Todos os campos são requeridos. Novas senhas necessitam ser alfanuméricos de tamanho 10 ou menos.</td></tr>
<tr><td width="20%">Nome de Usuário:</td><td><input type="text" name="username" size="30" maxlength="30" /></td></tr>
<tr><td>Antiga Senha:</td><td><input type="password" name="oldpass" size="20" /></td></tr>
<tr><td>Nova Senha:</td><td><input type="password" name="newpass1" size="20" maxlength="10" /></td></tr>
<tr><td>Verificar Nova Senha:</td><td><input type="password" name="newpass2" size="20" maxlength="10" /><br /><br /><br /></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Enviar" /> <input type="reset" name="reset" value="Resetar" /></td></tr>
</table>
</form>
THEVERYENDOFYOU;
?>