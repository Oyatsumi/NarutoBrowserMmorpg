<?php
global $userrow;
if ($userrow["authlevel"] == 1) {$userrow["adm"] = "<font color=green>Administrador</font><br>";}
elseif ($userrow["acesso"] == 2){$userrow["adm"] = "<font color=orange>Tutor</font><br>";}
elseif ($userrow["acesso"] == 3){$userrow["adm"] = "<font color=blue>GameMaster</font><br>";}
else {$userrow["adm"] = "";}

//durabilidade
$durabm = explode(",",$userrow["durabilidade"]);
for ($i = 1; $i < 7; $i ++){
if ($durabm[$i] == "X"){$durabm[$i] = "*";}
}

if ($userrow["senjutsuhtml"] != ""){ $userrow["magiclist"] = "<font color=darkgreen>Senjutsu<br></font>".$userrow["magiclist"];}
if ($userrow["jutsudebuscahtml"] != ""){ $userrow["magiclist"] = "<font color=darkgreen>Jutsu de Busca</font><br>".$userrow["magiclist"];}


$template = <<<THEVERYENDOFYOU
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<table width="215">

<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/buttons/personagem.png"></td></tr><tr background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td>
<center><img src="layoutnovo/avatares/{{avatar}}.jpg"></center><br>
<b>{{charname}}</b><br />{{adm}}

Dificuldade {{difficulty}}<br />
Especialização: {{charclass}}<br />
Graduação: {{graduacao}}<br><br>

Level: {{level}}<br />
Experiência: {{experience}} {{plusexp}}<br />
Próximo Level: {{nextlevel}}<br />
Ryou: {{gold}} {{plusgold}}<br />
Pontos de Vida: {{currenthp}} / {{maxhp}}<br />
Chakra: {{currentmp}} / {{maxmp}}<br />
Pontos de Viagem: {{currenttp}} / {{maxtp}}<br />
Pontos Naturais: {{currentnp}} / {{maxnp}}<br />
Pontos Elementais: {{currentep}} / {{maxep}}<br />
Chance de Drop: <font color="gray">+{{droprate}}%</font><br /><br />

Força: {{strength}}<br />
Destreza: {{dexterity}}<br />
Poder de Ataque: {{attackpower}}<br />
Poder de Defesa: {{defensepower}}<br /><br>

Agilidade: {{agilidade}}<br>
Sorte: {{sorte}}<br>
Determinação: {{determinacao}}<br>
Precisão: {{precisao}}<br>
Inteligência: {{inteligencia}}<br>

</td>
<td width="5"></td></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>

</td></tr>
</table><br />

<table width="215">

<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/buttons/inventario.png"></td></tr><tr background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td>


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
<tr><td height="34" style="width: 0px;"></td><td background="layoutnovo/equipamentos/drops/{{slot1id}}.png" width="37" style="background-repeat:no-repeat;;background-position:left top" ></td><td background="layoutnovo/equipamentos/drops/{{slot2id}}.png" width="36" style="background-repeat:no-repeat;;background-position:left top"></td><td background="layoutnovo/equipamentos/drops/{{slot3id}}.png" width="35" style="background-repeat:no-repeat;;background-position:left top"></td><td></td></tr>
<tr height="4"><td colspan="7"></td></tr>
</table>
</center>

<table width="100%">
<tr><td><img src="images/icon_weapon.gif" alt="Arma" title="Durabilidade: {{durabm1}}" /></td><td width="100%">Arma: {{weaponname}}</td></tr>
<tr><td><img src="images/icon_armor.gif" alt="Colete" title="Durabilidade: {{durabm2}}" /></td><td width="100%">Colete: {{armorname}}</td></tr>
<tr><td><img src="images/icon_shield.gif" alt="Bandana" title="Durabilidade: {{durabm3}}" /></td><td width="100%">Bandana: {{shieldname}}</td></tr>
<tr><td><img src="images/orb.gif" alt="Item Adicional" title="Durabilidade: {{durabm4}}" /></td><td width="100%">Slot 1: {{slot1name}}</td></tr>
<tr><td><img src="images/orb.gif" alt="Item Adicional" title="Durabilidade: {{durabm5}}" /></td><td width="100%">Slot 2: {{slot2name}}</td></tr>
<tr><td><img src="images/orb.gif" alt="Item Adicional" title="Durabilidade: {{durabm6}}" /></td><td width="100%">Slot 3: {{slot3name}}</td></tr>
</table>

</td>
<td width="5"></td></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>

</td></tr>
</table><br />

<table width="215">

<tr><td>

<table border="0" cellpadding=0 cellspacing=0 background="layoutnovo/menuslados/meio.png">
<tr><td colspan="3" width="224" height="34" background="layoutnovo/buttons/jutsus.png"></td></tr><tr background="layoutnovo/menuslados/meio.png">
<td width="5"></td>
<td>

{{magiclist}}

</td>
<td width="5"></td></td>
</tr><tr><td colspan="3" width="224" height="21" background="layoutnovo/menuslados/baixo.png"></td></tr></table>

</td></tr>
</table><br />
THEVERYENDOFYOU;
?>