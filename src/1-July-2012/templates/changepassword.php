<?php
global $conteudouser;
$template = <<<THEVERYENDOFYOU
<table width="100%"><tr><td width="100%" align="center"><center><img src="images/minhaconta.gif" /></center></td></tr></table>$conteudouser
<form action="users.php?do=changepassword" method="post" id="formback"><fieldset id="field2"><legend>Mudar Senha</legend>
<table width="100%">
<tr><td colspan="2">Use o formulário abaixo para mudar sua senha. Todos os campos são requeridos. Novas senhas necessitam ser alfanuméricas de tamanho 10 ou menos.</td></tr>
<tr><td width="20%">Nome de Usuário:</td><td><input type="text" name="username" size="30" maxlength="30" /></td></tr>
<tr><td>Antiga Senha:</td><td><input type="password" name="oldpass" size="20" /></td></tr>
<tr><td>Nova Senha:</td><td><input type="password" name="newpass1" size="20" maxlength="10" /></td></tr>
<tr><td>Verificar Nova Senha:</td><td><input type="password" name="newpass2" size="20" maxlength="10" /><br /><br /><br /></td></tr>
<tr><td colspan="2"><center><div class="buttons"><button type="submit" class="positive" name="submit"><img src="layoutnovo/dropmenu/b1.gif"> Mudar Senha</button>
<button type="reset" class="negative" name="reset"><img src="layoutnovo/dropmenu/b3.gif"> Resetar</button>
</div></center></td></tr>
</table>
</fieldset></form>
THEVERYENDOFYOU;
?>