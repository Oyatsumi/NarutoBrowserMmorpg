<?php
global $conteudouser;
$template = <<<THEVERYENDOFYOU
<table width="100%"><tr><td width="100%" align="center"><center><img src="images/minhaconta.gif" /></center></td></tr></table>$conteudouser
<form action="users.php?do=lostpassword" method="post" id="formback"><fieldset id="field2"><legend>Recuperar Senha</legend>
<table width="80%">
<tr><td colspan="2">Se você perdeu sua senha, entre com seu e-mail abaixo e você receberá uma nova.</td></tr>
<tr><td width="20%">Endereço de E-mail:</td><td><input type="text" name="email" size="30" maxlength="100" /></td></tr>
<tr><td colspan="2"><center><div class="buttons"><button type="submit" class="positive" name="submit"><img src="layoutnovo/dropmenu/b1.gif"> Enviar Senha</button>
<button type="reset" class="negative" name="reset"><img src="layoutnovo/dropmenu/b3.gif"> Resetar</button>
</div></center></td></tr>
</table></fieldset>
</form>
THEVERYENDOFYOU;
?>