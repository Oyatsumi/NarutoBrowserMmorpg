<?php
$template = <<<THEVERYENDOFYOU
<table width="100%">
<tr><td class="title"><img src="images/town_{{id}}.gif" alt="Welcome to {{name}}" title="Welcome to {{name}}" /></td></tr>
<tr><td>
<b>Town Options:</b><br />
<ul>
<li /><a href="index.php?do=inn">Rest at the Inn</a>
<li /><a href="index.php?do=buy">Buy Weapons/Armor</a>
<li /><a href="index.php?do=maps">Buy Maps</a>
</ul>
</td></tr>
<tr><td><center>
{{news}}
<br />
<table width="95%">
<tr><td width="50%">
{{whosonline}}
</td><td>
{{babblebox}}
</td></tr>
</table>
</td></tr>
</table>
THEVERYENDOFYOU;
?>