<?php
$template = <<<THEVERYENDOFYOU
<form action="login.php?do=login" method="post">
<center><table bgcolor="#452202"><tr ><td><font color="white"><center>Informativo</center></font></td></tr><tr><td bgcolor="#E4D094"><font color="red"><center>O jogo está desativado até a versão <b>3.0</b> e haverá infelizmente, um reset nas contas, devido ao bug, que alterou os stats de todos os personagens do jogo.</center></font></td></tr>
<tr><td bgcolor="#FFF1C7"><center>Não há previsão de lançamento do jogo, mas estou trabalhando árduamente para terminá-lo, sendo otimista, acho que ainda vai demorar uns meses, e também será testado para evitar novos bugs.</center></td></tr></center>
</table><br><br>
<table width="100%">
<tr><td width="30%">Nome de Usuário:</td><td><input type="text" size="30" name="username" /></td></tr>
<tr><td>Senha:</td><td><input type="password" size="30" name="password" /></td></tr>
<tr><td>Lembrar-me?</td><td><input type="checkbox" name="rememberme" value="yes" /> Sim</td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Entrar" /></td></tr>
<tr><td colspan="2">Marcando a opção "Lembrar-me" sua informação será guardada num cookie e você não terá que fazer login da próxima vez que você entrar no jogo.<br /><br />Quer jogar? Você precisa <a href="users.php?do=register">registrar seu próprio personagem.</a><br /><br />Você também pode <a href="users.php?do=changepassword">mudar sua senha</a>, ou <a href="users.php?do=lostpassword">requerer uma nova senha</a> se você perdeu a sua.
<br><br>
Caso você tenha se registrado e não tenha recebido o e-mail de confirmação, você pode <a href="ativarconta.php">ativar sua conta</a> agora mesmo.
</td></tr>
</table>
</form>
THEVERYENDOFYOU;
?>