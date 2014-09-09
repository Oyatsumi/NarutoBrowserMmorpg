<?php
$template = <<<THEVERYENDOFYOU
Here is the character profile for <b>{{charname}}</b>.<br /><br />
When you're finished, you may <a href="index.php">return to town</a>.<br /><br />
<table width="200">
<tr><td class="title"><img src="images/button_character.gif" alt="Character" title="Character" /></td></tr>
<tr><td>
<b>{{charname}}</b><br /><br />

Difficulty: {{difficulty}}<br />
Class: {{charclass}}<br /><br />

Level: {{level}}<br />
Experience: {{experience}}<br />
Gold: {{gold}}<br />
Hit Points: {{currenthp}} / {{maxhp}}<br />
Magic Points: {{currentmp}} / {{maxmp}}<br />
Travel Points: {{currenttp}} / {{maxtp}}<br /><br />

Strength: {{strength}}<br />
Dexterity: {{dexterity}}<br />
Attack Power: {{attackpower}}<br />
Defense Power: {{defensepower}}<br />
</td></tr>
</table><br />

<table width="200">
<tr><td class="title"><img src="images/button_inventory.gif" alt="Inventory" title="Inventory" /></td></tr>
<tr><td>
<table width="100%">
<tr><td><img src="images/icon_weapon.gif" alt="Weapon" title="Weapon" /></td><td width="100%">Weapon: {{weaponname}}</td></tr>
<tr><td><img src="images/icon_armor.gif" alt="Armor" title="Armor" /></td><td width="100%">Armor: {{armorname}}</td></tr>
<tr><td><img src="images/icon_shield.gif" alt="Shield" title="Shield" /></td><td width="100%">Shield: {{shieldname}}</td></tr>
</table>
Slot 1: {{slot1name}}<br />
Slot 2: {{slot2name}}<br />
Slot 3: {{slot3name}}
</td></tr>
</table><br />
THEVERYENDOFYOU;
?>