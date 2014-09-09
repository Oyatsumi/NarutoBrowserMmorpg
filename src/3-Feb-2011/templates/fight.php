<?php
$template = <<<THEVERYENDOFYOU
<table width="100%">
<tr><td align="center"><center><img src="images/title_fighting.gif" alt="Fighting" /></center>{{indexconteudo}}</td></tr>
<tr><td>
<div style="position: relative; top: -15px; margin-bottom: -30px;">
<table><tr><td width="310"><br>
{{dados}}
<center>Você está lutando com: <b>{{monstername}}</b>.<br /><br></center>
{{yourturn}}
{{monsterturn}}
{{command}}
</td><td>



<table width="165" height="175" background="layoutnovo/graficos/fundo.png" style="background-repeat:no-repeat;;background-position:left top"><tr height="{{porcent}}"><td></td></tr><tr ><td valign="bottom"><center><img src="layoutnovo/graficos/{{grafico}}"></center>
</td></tr><tr><td></td></tr></table>



</td></tr></table>
</div>
</td></tr>
</table>
THEVERYENDOFYOU;
?>