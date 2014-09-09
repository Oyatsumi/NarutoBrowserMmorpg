<?php
$template = <<<THEVERYENDOFYOU
<form action="funcaoitens.php?do=banco" method="post">


<center><table><tr><td>
<table>
<tr bgcolor="#452202"><td><font color="white">Depositar Ryou:</font></td></tr>


<tr bgcolor="#E4D094">
<td><input type="text" name="deposito" size="20" /></td></tr>
<tr ><td><center><input type="submit" name="submit" value="" style="height:5px;" id="botao1" /></center></td></tr>
</table>
</td>
<td>

<table>
<tr bgcolor="#452202"><td><font color="white">Retirar Ryou:</font></td></tr>
<tr bgcolor="#E4D094">
<td><input type="text" name="retirar" size="20" /></td></tr>
<tr ><td><center><input type="submit" name="submit" value="" style="height:5px;" id="botao2"/></center></td></tr>

</table>
<script type="text/javascript" language="JavaScript">sumirbotao('botao1');sumirbotao('botao2');</script>

</td>
</tr>
</table>

</form>
</center>
THEVERYENDOFYOU;
?>