<?php



$template = <<<THEVERYENDOFYOU


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<center>
<div style="position:relative; width:204px;text-align:left;font-family:tahoma;font-size:12px">
<div style="background-image: url(layoutnovo/buttons/personagem.png);height:97px;z-index:1"></div>
<div style="z-index:0;background-image: url(layoutnovo/buttons/meio.png)">
<div style="position:relative;padding-left:7px;padding-right:7px;top:-25px;z-index:2">


<center><img src="layoutnovo/avatares/{{avatar}}.jpg"></center>
<b>{{charname}}</b><br />{{adm}}

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
Chance de Drop: <font color="gray">(+{{droprate}}%)</font><br /><br />

Força: {{strength}}<br />
Destreza: {{dexterity}}<br />
Poder de Ataque: {{attackpower}}<br />
Poder de Defesa: {{defensepower}}<br /><br>

Agilidade: {{agilidade}}<br>
Sorte: {{sorte}}<br>
Determinação: {{determinacao}}<br>
Precisão: {{precisao}}<br>
Inteligência: {{inteligencia}}<br>


</div>
</div>
<div style="position:relative;top:-32px;z-index:1;background-image: url(layoutnovo/buttons/fim.png);height:51px;"></div>
</div></center>







<center>
<div style="position:relative; width:204px;text-align:left;font-family:tahoma;font-size:12px">
<div style="background-image: url(layoutnovo/buttons/inventario.png);height:97px;z-index:1"></div>
<div style="z-index:0;background-image: url(layoutnovo/buttons/meio.png)">
<div style="position:relative;padding-left:7px;padding-right:7px;top:-25px;z-index:2">


<center>
<div style="position:relative;background-image:url(layoutnovo/equipamentos/equipamentos.png);width:168px;height:116px;background-repeat:no-repeat">
<div style="position:absolute;top:14px;left:66px"><img src="layoutnovo/equipamentos/{{shieldid}}.gif" onMouseOver="{{shieldatr}}" onmouseout="fecharexplic();" id="shieldatr" border="0"></div>
<div style="position:absolute;top:52px;left:29px"><img src="layoutnovo/equipamentos/{{weaponid}}.gif" border="0" onMouseOver="{{armaatr}}" onmouseout="fecharexplic();" id="armaatr"></div>
<div style="position:absolute;top:52px;left:103px"><img src="layoutnovo/equipamentos/{{weaponid}}d.gif" border="0" onMouseOver="{{armaatr}}" onmouseout="fecharexplic();" id="armaatr"></div>
<div style="position:absolute;top:52px;left:66px"><img src="layoutnovo/equipamentos/{{armorid}}.gif" border="0" onMouseOver="{{armoratr}}" onmouseout="fecharexplic();" id="armoratr"></div>
</div>



<table border="5" cellspacing="0" 
cellpadding="0" background="layoutnovo/equipamentos/drops/fundo.png" style="background-repeat:no-repeat;;background-position:left top" width="128">
<tr height="3"></tr>
<tr><td height="34" style="width: 0px;"></td><td background="layoutnovo/equipamentos/drops/{{slot1id}}.gif" width="37" style="background-repeat:no-repeat;;background-position:left top" ><img src="images/gif30.gif" onMouseOver="{{slot1atr}}" onmouseout="fecharexplic();" id="slot1atr" border="0"></td><td background="layoutnovo/equipamentos/drops/{{slot2id}}.gif" width="37" style="background-repeat:no-repeat;;background-position:left top"><img src="images/gif30.gif" onMouseOver="{{slot2atr}}" onmouseout="fecharexplic();" id="slot2atr" border="0"></td><td background="layoutnovo/equipamentos/drops/{{slot3id}}.gif" width="35" style="background-repeat:no-repeat;;background-position:left top"><img src="images/gif30.gif" onMouseOver="{{slot3atr}}" onmouseout="fecharexplic();" id="slot3atr" border="0"></td><td></td></tr>
<tr height="4"><td colspan="7"></td></tr>
</table>
</center>

<table width="100%" style="font-family:tahoma;font-size:12px">
<tr><td><img src="images/icon_weapon.gif" alt="Arma" title="Durabilidade: {{durabm1}}" /></td><td width="100%">Arma: {{weaponname}}</td></tr>
<tr><td><img src="images/icon_armor.gif" alt="Colete" title="Durabilidade: {{durabm2}}" /></td><td width="100%">Colete: {{armorname}}</td></tr>
<tr><td><img src="images/icon_shield.gif" alt="Bandana" title="Durabilidade: {{durabm3}}" /></td><td width="100%">Bandana: {{shieldname}}</td></tr>
<tr><td><img src="images/orb.gif" alt="Item Adicional" title="Durabilidade: {{durabm4}}" /></td><td width="100%">Slot 1: {{slot1name}}</td></tr>
<tr><td><img src="images/orb.gif" alt="Item Adicional" title="Durabilidade: {{durabm5}}" /></td><td width="100%">Slot 2: {{slot2name}}</td></tr>
<tr><td><img src="images/orb.gif" alt="Item Adicional" title="Durabilidade: {{durabm6}}" /></td><td width="100%">Slot 3: {{slot3name}}</td></tr>
</table>

</div>
</div>
<div style="position:relative;top:-32px;z-index:1;background-image: url(layoutnovo/buttons/fim.png);height:51px;"></div>
</div></center>








<center>
<div style="position:relative; width:204px;text-align:left;font-family:tahoma;font-size:12px">
<div style="background-image: url(layoutnovo/buttons/jutsus.png);height:97px;z-index:1"></div>
<div style="z-index:0;background-image: url(layoutnovo/buttons/meio.png)">
<div style="position:relative;padding-left:7px;padding-right:7px;top:-32px;z-index:2"><center>
{{magiclist}}
</center>
</div>
</div>
<div style="position:relative;top:-32px;z-index:1;background-image: url(layoutnovo/buttons/fim.png);height:51px;"></div>
</div></center>
THEVERYENDOFYOU;
?>