<?php
$template = <<<THEVERYENDOFYOU
Aqui está o perfil do personagem: <b>{{charname}}</b>.<br /><br />
Quando você terminar, você pode <a href="index.php">retornar à tela de jogo</a>.<br /><br />
<table width="200">
<tr><td><img src="layoutnovo/buttons/personagem2.png" alt="Character" title="Character" /></td></tr>
<tr><td>
<center><img src="layoutnovo/avatares/{{avatar}}.png"></center><br>
<b>{{charname}}</b><br />
{{adm}}{{senjutsuhtml}}<br>
Dificuldade: {{difficulty}}<br />
Especialização: {{charclass}}<br />
Graduação: {{graduacao}}<br><br>

Level: {{level}}<br />
Experiência: {{experience}}<br />
Ryou: {{gold}}<br />
Pontos de Vida: {{currenthp}} / {{maxhp}}<br />
Chakra: {{currentmp}} / {{maxmp}}<br />
Pontos de Viagem: {{currenttp}} / {{maxtp}}<br /><br />

Força: {{strength}}<br />
Destreza: {{dexterity}}<br />
Poder de Ataque: {{attackpower}}<br />
Poder de Defesa: {{defensepower}}<br /><br>

Agilidade: {{agilidade}}<br>
Sorte: {{sorte}}<br>
Determinação: {{determinacao}}<br>
Precisão: {{precisao}}<br>
Inteligência: {{inteligencia}}<br>
</td></tr>
</table><br />





<table width="200">
<tr><td ><img src="layoutnovo/buttons/inventario2.png" alt="Inventory" title="Inventory" /></td></tr>
<tr><td>

<center>
<table border="0" cellspacing="0" cellpadding="0" background="layoutnovo/equipamentos/equipamentos.png" width="168" style="background-repeat:no-repeat;;background-position:left top"><tr>
<td height="15" colspan="4"></td></tr><tr>
<td height="37" width="23"></td><td width="31"></td><td width="31" style="background-repeat:no-repeat;;background-position:left top" background="layoutnovo/equipamentos/{{shieldid}}.png"></td><td colspan="2"></td></tr><tr>

<td  height="34" width="23"></td><td width="31" style="background-repeat:no-repeat;;background-position:left top" background="layoutnovo/equipamentos/{{weaponid}}.png"></td><td width="31" style="background-repeat:no-repeat;;background-position:left top" background="layoutnovo/equipamentos/{{armorid}}.png"></td><td style="background-repeat:no-repeat;;background-position:left top" background="layoutnovo/equipamentos/{{weaponid}}d.png"></td></tr>
<tr>
<td colspan="4" height="35"></td>
</table></center>

<table width="100%">
<tr><td><img src="images/icon_weapon.gif" alt="Weapon" title="Weapon" /></td><td width="100%">Arma: {{weaponname}}</td></tr>
<tr><td><img src="images/icon_armor.gif" alt="Armor" title="Armor" /></td><td width="100%">Colete: {{armorname}}</td></tr>
<tr><td><img src="images/icon_shield.gif" alt="Shield" title="Shield" /></td><td width="100%">Bandana: {{shieldname}}</td></tr>
</table>
Slot 1: {{slot1name}}<br />
Slot 2: {{slot2name}}<br />
Slot 3: {{slot3name}}
</td></tr>
</table><br />
THEVERYENDOFYOU;
?>