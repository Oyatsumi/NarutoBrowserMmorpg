<?php
$template = <<<THEVERYENDOFYOU
<td><form action="outroseatributos.php?do=atributos" method="post">
<table>
<tr  bgcolor="#452202"><td colspan="2"><center><font color="white">Distribuir Pontos</font></center></td></tr>
<tr bgcolor="#FFF1C7"><td >Agilidade<img src="images/raio.gif" title="Elemento Relâmpago"> </td><td>Adicionar Pontos: <input type="text" name="agilidadep" size="10" maxlength="30" /></td></tr>
<tr bgcolor="#E4D094"><td >Sorte<img src="images/agua.gif" title="Elemento Água"> </td><td>Adicionar Pontos: <input type="text" name="sortep" size="10" maxlength="30" /></td></tr>
<tr bgcolor="#FFF1C7"><td>Determinação<img src="images/fogo.gif" title="Elemento Fogo"> </td><td>Adicionar Pontos: <input type="text" name="determinacaop" size="10" maxlength="30" /></td></tr>
<tr bgcolor="#E4D094"><td  >Precisão<img src="images/vento.gif" title="Elemento Vento"> </td><td>Adicionar Pontos: <input type="text" name="precisaop" size="10" maxlength="30" /></td></tr>
<tr bgcolor="#FFF1C7"><td  >Inteligência<img src="images/terra.gif" title="Elemento Terra"> </td><td>Adicionar Pontos: <input type="text" name="inteligenciap" size="10" maxlength="30" /></td></tr>
<tr bgcolor="#E4D094"><td colspan="2"><div class="buttons" style="margin-left: 3px;"><center><button type="submit" class="positive" name="submit"><img src="layoutnovo/dropmenu/b1.gif"> Adicionar</button>
<button type="reset" class="negative" name="reset"><img src="layoutnovo/dropmenu/b3.gif"> Apagar</button>
</center></div></td></tr>
</table></td></tr></table></center>
</form>
<b>Legenda</b>:
<ul>
<li /><b>Agilidade</b>: Aumenta a probabilidade de fuga de um ataque inimigo.
<li /><b>Sorte</b>: Aumenta a probabilidade de drop de um item.
<li /><b>Precisão</b>: Aumenta a probabilidade de acerto de ataque no inimigo.
<li /><b>Inteligência</b>: Aumenta a probabilidade do inimigo continuar dormindo.
<li /><b>Determinação</b>: Aumenta a probabilidade de ataques críticos para <font color=red>ataques <b>físicos</b></font>.
<li />Todos os atributos aumentam a força de jutsus de seus elementos correspondentes.
</ul>
THEVERYENDOFYOU;
?>