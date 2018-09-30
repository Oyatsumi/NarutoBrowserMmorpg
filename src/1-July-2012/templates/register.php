<?php
$template = <<<THEVERYENDOFYOU
<table width="100%"><tr><td width="100%" align="center"><center><img src="images/criarconta.gif" /></center></td></tr></table>$conteudo
<form action="users.php?do=register" method="post" id="formback"><fieldset id="field2"><legend>Dados da Conta</legend>
<table width="80%">
<tr><td width="30%">Nome da Conta:</td><td><input type="password" name="username" size="30" maxlength="30" id="nomedaconta1"/><a href="javascript: mostrarpass('nomedaconta1');"><img src="layoutnovo/dropmenu/b4.gif" title="Mostrar/Ocultar Nome da Conta" border="0"></a><br />O nome da conta deve conter 30 caracteres ou menos e ser alfanumério(não pode conter espaços). É aconselhável que você use o nome da sua conta diferente do nome do seu personagem, para garantir uma segurança maior.<br /><br /><br /></td></tr>
<tr><td>Senha:</td><td><input id="p1senha" type="password" name="password1" size="30"/><a href="javascript: mostrarpass('p1senha');"><img src="layoutnovo/dropmenu/b4.gif" title="Mostrar/Ocultar Senha" border="0"></a></td></tr>
<tr><td>Verificar Senha:</td><td><input type="password" name="password2" size="30" id="p2senha"/><a href="javascript: mostrarpass('p2senha');"><img src="layoutnovo/dropmenu/b4.gif" title="Mostrar/Ocultar Senha" border="0"></a><br />A senha deve conter 10 caracteres ou menos e ser alfanumérica.<br /><br /><br /></td></tr>
<tr><td>E-mail:</td><td><input type="text" name="email1" size="30" maxlength="100" /></td></tr>
<tr><td>Verificar E-Mail:</td><td><input type="text" name="email2" size="30" maxlength="100" />{{verifytext}}<br /><br /><br /></td></tr>
<tr><td>Nome do Personagem:</td><td><input type="text" name="charname" size="30" maxlength="30" /></td></tr>
<tr><td>Especialização do Personagem:</td><td><select name="charclass"><option value="1">{{class1name}}</option><option value="2">{{class2name}}</option><option value="3">{{class3name}}</option></select></td></tr>
<tr><td>Dificuldade:</td><td><select name="difficulty"><option value="1">{{diff1name}}</option><option value="2">{{diff2name}}</option><option value="3">{{diff3name}}</option></select></td></tr>
<tr><td colspan="2">Veja a <a href="help.php">Ajuda</a> para mais informações sobre Especialização dos Personagens e sobre os leveis de dificuldade.<br /><br /></td></tr>
<tr><td colspan="2">
<center><div class="buttons"><button type="submit" class="positive" name="submit"><img src="layoutnovo/dropmenu/b1.gif"> Criar Conta</button>
<button type="reset" class="negative" name="reset"><img src="layoutnovo/dropmenu/b3.gif"> Resetar</button>
</div></center>
</td></tr>
</table>
</fieldset></form>
THEVERYENDOFYOU;
?>