<?php
$template = <<<THEVERYENDOFYOU
<form action="users.php?do=lostpassword" method="post">
<table width="80%">
<tr><td colspan="2">If you've lost your password, enter your email address below and you will be sent a new one.</td></tr>
<tr><td width="20%">Email Address:</td><td><input type="text" name="email" size="30" maxlength="100" /></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Submit" /> <input type="reset" name="reset" value="Reset" /></td></tr>
</table>
</form>
THEVERYENDOFYOU;
?>