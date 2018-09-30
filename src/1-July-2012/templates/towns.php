<?php
$template = <<<THEVERYENDOFYOU
<table width="100%">
<tr><td align="center">
<center><img src="images/town_{{id}}.gif" alt="Bem-vindo à {{name}}" title="Bem-vindo à {{name}}" /></center>
{{indexconteudo}}
{{htmlnapag}}
<br>
{{fimconteudo}}

</td></tr>
<tr><td><center>
{{news}}
<table width="95%">
<tr><td width="50%">
<center>{{whosonline}}</center>
</td></tr><tr><td>
{{babblebox}}
</td></tr>
</table>
</td></tr>
</table>
THEVERYENDOFYOU;
?>