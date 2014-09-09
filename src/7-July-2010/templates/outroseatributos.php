<?php
$template = <<<THEVERYENDOFYOU
<form action="outroseatributos.php?do=atributos" method="post">
<table width="100%">
<tr><td colspan="2">Complete os campos abaixo corretamente:</td></tr>
<tr><td width="20%">Agilidade: </td><td>Adicionar Pontos: <input type="text" name="agilidadep" size="10" maxlength="30" /></td></tr>
<tr><td width="20%">Sorte: </td><td>Adicionar Pontos: <input type="text" name="sortep" size="10" maxlength="30" /></td></tr>
<tr><td width="20%">Determinação: </td><td>Adicionar Pontos: <input type="text" name="determinacaop" size="10" maxlength="30" /></td></tr>
<tr><td width="20%">Precisão: </td><td>Adicionar Pontos: <input type="text" name="precisaop" size="10" maxlength="30" /></td></tr>
<tr><td width="20%">Inteligência: </td><td>Adicionar Pontos: <input type="text" name="inteligenciap" size="10" maxlength="30" /></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Distribuir Pontos" /> <input type="reset" name="reset" value="Apagar Campos" /></td></tr>
</table>
</form>
<br>
<b>Legenda</b>:
<ul>
<li /><b>Agilidade</b>: Aumenta a probabilidade de fuga de um ataque inimigo.
<li /><b>Sorte</b>: Aumenta a probabilidade de drop de um item.
<li /><b>Precisão</b>: Aumenta a probabilidade de acerto de ataque no inimigo.
<li /><b>Inteligência</b>: Aumenta a probabilidade do inimigo continuar dormindo.
<li /><b>Determinação</b>: Aumenta a probabilidade de ataques críticos para <font color=red>ataques <b>físicos</b></font>.
</ul><br><br>
Quando terminar você pode <a href="index.php">voltar à página principal</a>.
THEVERYENDOFYOU;
?>