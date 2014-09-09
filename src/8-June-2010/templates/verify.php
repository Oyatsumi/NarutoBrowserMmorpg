<?php
$template = <<<THEVERYENDOFYOU
<form action="users.php?do=verify" method="post">
<table width="80%">
<tr><td colspan="2">Thank you for registering a character. Please enter your username, email address, and the verification code
that was emailed to you to unlock your character.</td></tr>
<tr><td width="20%">Username:</td><td><input type="text" name="username" size="30" maxlength="30" /></td></tr>
<tr><td>Email Address:</td><td><input type="text" name="email" size="30" maxlength="100" /></td></tr>
<tr><td>Verification Code:</td><td><input type="text" name="verify" size="10" maxlength="8" /><br /><br /><br /></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Submit" /> <input type="reset" name="reset" value="Reset" /></td></tr>
</table>
</form>
THEVERYENDOFYOU;
?>