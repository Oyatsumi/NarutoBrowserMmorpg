<?php
$template = <<<THEVERYENDOFYOU
<font color=red>Galera, infelizmente, na transição do RPG 1.0 para o 2.0, as contas tiveram que ser deletadas... Bem... quem estava jogando antes, vai ganhar algo de acordo com o level que tinha, só falar com o Oyatsumi ou o Devilolz dentro do jogo. Obrigado e desculpe-nos qualquer coisa.</font><br><br>
<form action="login.php?do=login" method="post">
<table width="75%">
<tr><td width="30%">Nome de Usuário:</td><td><input type="text" size="30" name="username" /></td></tr>
<tr><td>Senha:</td><td><input type="password" size="30" name="password" /></td></tr>
<tr><td>Lembrar-me?</td><td><input type="checkbox" name="rememberme" value="yes" /> Sim</td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Entrar" /></td></tr>
<tr><td colspan="2">Marcando a opção "Lembrar-me" sua informação será guardada num cookie e você não terá que fazer login da próxima vez que você entrar no jogo.<br /><br />Quer jogar? Você precisa <a href="users.php?do=register">registrar seu próprio personagem.</a><br /><br />Você também pode <a href="users.php?do=changepassword">mudar sua senha</a>, ou <a href="users.php?do=lostpassword">requerer uma nova senha</a> se você perdeu a sua.</td></tr>
</table>
</form>
THEVERYENDOFYOU;
?>