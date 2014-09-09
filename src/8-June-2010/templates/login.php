<?php
$template = <<<THEVERYENDOFYOU
<form action="login.php?do=login" method="post">
<table width="75%">
<tr><td width="30%">Username:</td><td><input type="text" size="30" name="username" /></td></tr>
<tr><td>Password:</td><td><input type="password" size="30" name="password" /></td></tr>
<tr><td>Remember me?</td><td><input type="checkbox" name="rememberme" value="yes" /> Yes</td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Log In" /></td></tr>
<tr><td colspan="2">Checking the "Remember Me" option will store your login information in a cookie so you don't have to enter it next time you get online.<br /><br />Want to play? You gotta <a href="users.php?do=register">register your own character.</a><br /><br />You may also <a href="users.php?do=changepassword">change your password</a>, or <a href="users.php?do=lostpassword">request a new one</a> if you've lost yours.</td></tr>
</table>
</form>
THEVERYENDOFYOU;
?>