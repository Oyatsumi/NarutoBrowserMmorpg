<?php
//slots para backpack
for ($i = 1; $i < 5; $i ++){
if ($userrow["bp".$i] != "None"){
$varbackpack[$i] = explode(",",$userrow["bp".$i]);
$mostrar = "";
$pastadoslot = "";
if ($varbackpack[$i][2] > 3) {$pastadoslot = "drops/";}
if ($varbackpack[$i][1] == "hp") {$varbackpack[$i][1] == "hp".$varbackpack[$i][2]; $mostrar = " HP + ".$varbackpack[$i][2];}
if ($varbackpack[$i][1] == "mp") {$varbackpack[$i][1] == "hp".$varbackpack[$i][2]; $mostrar = " CH + ".$varbackpack[$i][2];}
$bpcodigo[$i] = "<a href=\"backpack.php?qual=$i\"><img src=\"layoutnovo/equipamentos/$pastadoslot".$varbackpack[$i][1].".png\" width=\"34\" height=\"34\" hspace=\"0\" vspace=\"0\" border=\"0\" title=\"".$varbackpack[$i][0]."$mostrar\" /></a>";}else{
$bpcodigo[$i] = "<img src=\"images/gif.gif\" width=\"34\" height=\"34\" hspace=\"0\" vspace=\"0\" border=\"0\"/></a>";}
}//fim for
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
</table>

<table border="5" cellspacing="0" 
cellpadding="0" background="layoutnovo/equipamentos/drops/fundo.png" style="background-repeat:no-repeat;;background-position:left top" width="128">
<tr height="3"></tr>
<tr><td height="34" style="width: 0px;"></td><td background="layoutnovo/equipamentos/drops/{{slot1id}}.png" width="36" style="background-repeat:no-repeat;;background-position:left top" ></td><td background="layoutnovo/equipamentos/drops/{{slot2id}}.png" width="37" style="background-repeat:no-repeat;;background-position:left top"></td><td background="layoutnovo/equipamentos/drops/{{slot3id}}.png" width="35" style="background-repeat:no-repeat;;background-position:left top"></td><td></td></tr>
<tr height="4"><td colspan="7"></td></tr>
</table>
</center>

<table width="100%">
<tr><td><img src="images/icon_weapon.gif" alt="Weapon" title="Weapon" /></td><td width="100%">Arma: {{weaponname}}</td></tr>
<tr><td><img src="images/icon_armor.gif" alt="Armor" title="Armor" /></td><td width="100%">Colete: {{armorname}}</td></tr>
<tr><td><img src="images/icon_shield.gif" alt="Shield" title="Shield" /></td><td width="100%">Bandana: {{shieldname}}</td></tr>
<tr><td><img src="images/orb.gif" alt="Item Adicional" title="Item Adicional" /></td><td width="100%">Slot 1: {{slot1name}}</td></tr>
<tr><td><img src="images/orb.gif" alt="Item Adicional" title="Item Adicional" /></td><td width="100%">Slot 2: {{slot2name}}</td></tr>
<tr><td><img src="images/orb.gif" alt="Item Adicional" title="Item Adicional" /></td><td width="100%">Slot 3: {{slot3name}}</td></tr>
</table>

</td></tr>
</table><br />
THEVERYENDOFYOU;
?>