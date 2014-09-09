<?php
$template = <<<THEVERYENDOFYOU
<form action="users.php?do=register" method="post">
<table width="80%">
<tr><td width="20%">Nome de Usuário:</td><td><input type="text" name="username" size="30" maxlength="30" /><br />O nome de usuário deve conter 30 caracteres ou menos e ser alfanumério(não pode conter espaços).<br /><br /><br /></td></tr>
<tr><td>Senha:</td><td><input type="password" name="password1" size="30"/></td></tr>
<tr><td>Verificar Senha:</td><td><input type="password" name="password2" size="30" /><br />A senha deve conter 10 caracteres ou menos e ser alfanumérica.<br /><br /><br /></td></tr>
<tr><td>E-mail:</td><td><input type="text" name="email1" size="30" maxlength="100" /></td></tr>
<tr><td>Verificar E-Mail:</td><td><input type="text" name="email2" size="30" maxlength="100" />{{verifytext}}<br /><br /><br /></td></tr>
<tr><td>Nome do Personagem:</td><td><input type="text" name="charname" size="30" maxlength="30" /></td></tr>
<tr><td>Especialização do Personagem:</td><td><select name="charclass"><option value="1">{{class1name}}</option><option value="2">{{class2name}}</option><option value="3">{{class3name}}</option></select></td></tr>
<tr><td>Dificuldade:</td><td><select name="difficulty"><option value="1">{{diff1name}}</option><option value="2">{{diff2name}}</option><option value="3">{{diff3name}}</option></select></td></tr>
<tr><td colspan="2">Veja a <a href="help.php">Ajuda</a> para mais informações sobre Especialização dos Personagens e sobre os leveis de dificuldade.<br /><br /></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Enviar" /> <input type="reset" name="reset" value="Resetar" /></td></tr>
</table>
</form>
THEVERYENDOFYOU;
?>