<?php 
include('lib.php'); 
$link = opendb();
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);
ob_start("ob_gzhandler");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><? echo $controlrow["gamename"]; ?> Help</title>
<style type="text/css">
body {
  background-image: url(images/background.jpg);
  color: black;
  font: 11px verdana;
}
table {
  border-style: none;
  padding: 0px;
  font: 11px verdana;
}
td {
  border-style: none;
  padding: 3px;
  vertical-align: top;
}
td.top {
  border-bottom: solid 2px black;
}
td.left {
  width: 150px;
  border-right: solid 2px black;
}
td.right {
  width: 150px;
  border-left: solid 2px black;
}
a {
    color: #663300;
    text-decoration: none;
    font-weight: bold;
}
a:hover {
    color: #330000;
}
.small {
  font: 10px verdana;
}
.highlight {
  color: red;
}
.light {
  color: #999999;
}
.title {
  border: solid 1px black;
  background-color: #eeeeee;
  font-weight: bold;
  padding: 5px;
  margin: 3px;
}
.copyright {
  border: solid 1px black;
  background-color: #eeeeee;
  font: 10px verdana;
}
</style>
</head>
<body>
<a name="top"></a>
<h1><? echo $controlrow["gamename"]; ?> Help</h1>
[ <a href="index.php">Return to the game</a> ]

<br /><br /><hr />

<h3>Table of Contents</h3>
<ul>
<li /><a href="#intro">Introduction</a>
<li /><a href="#classes">Character Classes</a>
<li /><a href="#difficulties">Difficulty Levels</a>
<li /><a href="#intown">Playing The Game: In Town</a>
<li /><a href="#exploring">Playing The Game: Exploring & Fighting</a>
<li /><a href="#status">Playing The Game: Status Panels</a>
<li /><a href="#items">Spoilers: Items & Drops</a>
<li /><a href="#monsters">Spoilers: Monsters</a>
<li /><a href="#spells">Spoilers: Spells</a>
<li /><a href="#levels">Spoilers: Levels</a>
<li /><a href="#credits">Credits</a>
</ul>

<hr />

<h3><a name="intro"></a>Introduction</h3>
Firstly, I'd like to say thank you for playing my game. The <i>Dragon Knight</i> game engine is the result of several months of 
planning, coding and testing. The original idea was to create a web-based tribute to the NES game, <i>Dragon 
Warrior</i>. In its current iteration, only the underlying fighting system really resembles that game, as almost 
everything else in DK has been made bigger and better. But you should still recognize bits and pieces as stemming
from <i>Dragon Warrior</i> and other RPGs of old.<br /><br />
This is the first game I've ever written, and it has definitely been a positive experience. It got difficult at
times, admittedly, but it was still a lot of fun to write, and even more fun to play. And I hope to use this
experience so that if I ever want to create another game it will be even better than this one.<br /><br />
If you are a site administrator, and would like to install a copy of DK on your own server, you may visit the
<a href="http://dragon.se7enet.com/dev.php" target="_new">development site</a> for <i>Dragon Knight</i>. This page 
includes the downloadable game souce code, as well as some other resources that developers and administrators may
find valuable.<br /><br />
Once again, thanks for playing!<br /><br />
<i>Jamin Seven</i><br />
<i>Dragon Knight creator</i><br />
<a href="http://www.se7enet.com" target="_new">My Homepage</a><br />
<a href="http://dragon.se7enet.com/dev.php" target="_new">Dragon Knight Homepage</a><br ><br />
[ <a href="#top">Top</a> ]

<br /><br /><hr />

<h3><a name="classes"></a>Character Classes</h3>
There are three character classes in the game. The main differences between the classes are what spells you get
access to, the speed with which you level up, and the amount of HP/MP/strength/dexterity you gain per level. Below
is a basic outline of each of the character classes. For more detailed information about the characters, please
view the Levels table at the bottom of this page. Also, note that the outline below refers to the stock class setup
for the game. If your administrator has used his/her own class setup, this information may not be accurate.<br /><br />
<b><? echo $controlrow["class1name"]; ?></b>
<ul>
<li />Fast level-ups
<li />High hit points
<li />High magic points
<li />Low strength
<li />Low dexterity
<li />5 heal spells
<li />5 hurt spells
<li />3 sleep spells
<li />3 +defense spells
<li />0 +attack spells
</ul>
<b><? echo $controlrow["class2name"]; ?></b>
<ul>
<li />Medium level-ups
<li />Medium hit points
<li />Low magic points
<li />High strength
<li />Low dexterity
<li />3 heal spells
<li />3 hurt spells
<li />2 sleep spells
<li />3 +defense spells
<li />3 +attack spells
</ul>
<b><? echo $controlrow["class3name"]; ?></b>
<ul>
<li />Slow level-ups
<li />Medium hit points
<li />Medium magic points
<li />Low strength
<li />High dexterity
<li />4 heal spells
<li />4 hurt spells
<li />3 sleep spells
<li />2 +defense spells
<li />2 +attack spells
</ul>
[ <a href="#top">Top</a> ]

<br /><br /><hr />

<h3><a name="difficulties"></a>Difficulty Levels</h3>
<i><? echo $controlrow["gamename"]; ?></i> includes the ability to play using one of three difficulty levels.
All monster statistics in the game are set at a base number. However, using a difficulty multiplier, certain statistics
are increased. The amount of hit points a monster has goes up, which means it will take longer to kill. But the amount
of experience and gold you gain from killing it also goes up. So the game is a little bit harder, but it is also more
rewarding. The following are the three difficulty levels and their statistic multiplier, which applies to the monster's
HP, experience drop, and gold drop.
<ul>
<li /><? echo $controlrow["diff1name"] . ": <b>" . $controlrow["diff1mod"] . "</b>"; ?>
<li /><? echo $controlrow["diff2name"] . ": <b>" . $controlrow["diff2mod"] . "</b>"; ?>
<li /><? echo $controlrow["diff3name"] . ": <b>" . $controlrow["diff3mod"] . "</b>"; ?>
</ul>
[ <a href="#top">Top</a> ]

<br /><br /><hr />

<h3><a name="intown"></a>Playing The Game: In Town</h3>
When you begin a new game, the first thing you see is the Town screen. Towns serve four primary functions: healing, buying items,
buying maps, and displaying game information.<br /><br />
To heal yourself, click the "Rest at the Inn" link at the top of the town screen. Each town's Inn has a different price - some towns
are cheap, others are expensive. No matter what town you're in, the Inns always serve the same function: they restore your current
hit points, magic points, and travel points to their maximum amounts. Out in the field, you are free to use healing spells to restore
your hit points, but when you run low on magic points, the only way to restore them is at an Inn.<br /><br />
Buying weapons and armor is accomplished through the appropriately-named "Buy Weapons/Armor" link. Not every item is available in
every town, so in order to get the most powerful items, you'll need to explore some of the outer towns. Once you've clicked the link,
you are presented with a list of items available in this town's store. To the left of each item is an icon that represents its type:
weapon, armor or shield. The amount of attack/defense power, as well as the item's price, are displayed to the right of the item name.
You'll notice that some items have a red asterisk (<span class="highlight">*</span>) next to their names. These are items that come
with special attributes that modify other parts of your character profile. See the Items & Drops table at the bottom of this page for
more information about special items.<br /><br />
Maps are the third function in towns. Buying a map to a town places the town in your Travel To box in the left status panel. Once
you've purchased a town's map, you can click its name from your Travel To box and you will jump to that town. Travelling this way
costs travel points, though, and you'll only be able to visit towns if you have enough travel points.<br /><br />
The final function in towns is displaying game information and statistics. This includes the latest news post made by the game
administrator, a list of players who have been online recently, and the Babble Box.<br /><br />
[ <a href="#top">Top</a> ]

<br /><br /><hr />

<h3><a name="exploring"></a>Playing The Game: Exploring & Fighting</h3>
Once you're done in town, you are free to start exploring the world. Use the compass buttons on the left status panel to move around.
The game world is basically a big square, divided into four quadrants. Each quadrant is <? echo $controlrow["gamesize"]; ?> spaces
square. The first town is usually located at (0N,0E). Click the North button from the first town, and now you'll be at (1N,0E).
Likewise, if you now click the West button, you'll be at (1N,1W). Monster levels increase with every 5 spaces you move outward 
from (0N,0E).<br /><br />
While you're exploring, you will occasionally run into monsters. As in pretty much any other RPG game, you and the monster take turns
hitting each other in an attempt to reduce each other's hit points to zero. Once you run into a monster, the Exploring screen changes 
to the Fighting screen.<br /><br />
When a fight begins, you'll see the monster's name and hit points, and the game will ask you for your first command. You then get to
pick whether you want to fight, use a spell, or run away. Note, though, that sometimes the monster has the chance to hit you
first.<br /><br />
The Fight button is pretty straightforward: you attack the monster, and the amount of damage dealt is based on your attack power and
the monster's armor. On top of that, there are two other things that can happen: an Excellent Hit, which doubles your total attack
damage; and a monster dodge, which results in you doing no damage to the monster.<br /><br />
The Spell button allows you to pick an available spell and cast it. See the Spells list at the bottom of this page for more information
about spells.<br /><br />
Finally, there is the Run button, which lets you run away from a fight if the monster is too powerful. Be warned, though: it is
possible for the monster to block you from running and attack you. So if your hit points are low, you may fare better by staying
around monsters that you know can't do much damage to you.<br /><br />
Once you've had your turn, the monster also gets his turn. It is also possible for you to dodge the monster's attack and take no
damage.<br /><br />
The end result of a fight is either you or the monster being knocked down to zero hit points. If you win, the monster dies and will
give you a certain amount of experience and gold. There is also a chance that the monster will drop an item, which you can put into
one of the three inventory slots to give you extra points in your character profile. If you lose and die, half of your gold is taken
away - however, you are given back a few hit points to help you make it back to town (for example, if you don't have enough gold to
pay for an Inn, and need to kill a couple low-level monsters to get the money).<br /><br />
When the fight is over, you can continue exploring until you find another monster to beat into submission.<br /><br />
[ <a href="#top">Top</a> ]

<br /><br /><hr />

<h3><a name="status"></a>Playing The Game: Status Panels</h3>
There are two status panels on the game screen: left and right.<br /><br />
The left panel inclues your current location and play status (In Town, Exploring, Fighting), compass buttons for movement, and the
Travel To list for jumping between towns. At the bottom of the left panel is also a list of game functions.<br /><br />
The right panel displays some character statistics, your inventory, and quick spells.<br /><br />
The Character section shows the most important character statistics. It also displays the status bars for your current hit points,
magic points and travel points. These status bars are colored either green, yellow or red depending on your current amount of each
stat. There is also a link to pop up your list of extended statistics, which shows more detailed character information.<br /><br />
The Fast Spells section lists any Heal spells you've learned. You may use these links any time you are in town or exploring to cast
the heal spell. These may not be used during fights, however - you have to use the Spells box on the fight screen for that.
[ <a href="#top">Top</a> ]

<br /><br /><hr />

<h3><a name="items"></a>Spoilers: Items & Drops</h3>
<a href="help_items.php">Click here</a> for the Items & Drops spoiler page.<br /><br />
[ <a href="#top">Top</a> ]

<br /><br /><hr />

<h3><a name="monsters"></a>Spoilers: Monsters</h3>
<a href="help_monsters.php">Click here</a> for the Monsters spoiler page.<br /><br />
[ <a href="#top">Top</a> ]

<br /><br /><hr />

<h3><a name="spells"></a>Spoilers: Spells</h3>
<a href="help_spells.php">Click here</a> for the Spells spoiler page.<br /><br />
[ <a href="#top">Top</a> ]

<br /><br /><hr />

<h3><a name="levels"></a>Spoilers: Levels</h3>
<a href="help_levels.php">Click here</a> for the Levels spoiler page.<br /><br />
[ <a href="#top">Top</a> ]

<br /><br /><hr />

<h3><a name="credits"></a>Credits</h3>
<ul>
<li /><b>All program code and stock graphics for the game were created by Jamin Seven</b>.<br /><br />
<li />Major props go to a few people on the PHP manual site, for help with various chunks of code. The specific people are listed in the source code.<br /><br />
<li />Super monkey love goes to Enix and the developers of <i>Dragon Warrior</i>. If it weren't for you guys, my game never would have been made.<br /><br />
<li />Mega props go to Dalez from GameFAQs for his DW3 experience chart, which was where I got my experience levels from.<br /><br />
<li />Mad crazy ninja love goes to the following people for help and support throughout the development process:<br /><br />
<b>Ideas:</b> (whether they got used or not)
<ul>
<li />kushet
<li />lghtning
<li />Ebolamonkey3000
<li />Crimson Scythe
<li />SilDeath
</ul><br />
<b>Beta Testing:</b> (forums name if applicable, character name otherwise)
<ul>
<li />Ebolamonkey3000
<li />lisi
<li />Junglist
<li />Crimson Scythe
<li />Sk8erpunk69
<li />lghtning
<li />kushet
<li />SilDeath
<li />lowrider4life
<li />dubiin
<li />Sam Wise The Great
</ul><br />
<li />Apologies and lots of happy naked love to anyone I forgot.<br /><br />
<li />And of course, thanks to <b>you</b> for playing my game!<br /><br />
<li /><a href="../index.php?do=ninja">NINJA!</a>
</ul>
[ <a href="#top">Top</a> ]

<br /><br /><hr /><br />

Please visit the following sites for more information:<br />
<a href="http://www.se7enet.com" target="_new">Se7enet</a> (Jamin's homepage)<br />
<a href="http://dragon.se7enet.com/dev.php" target="_new">Dragon Knight</a> (official DK homepage)<br />
<a href="http://se7enet.com/forums" target="_new">Forums</a> (official DK forums)<br /><br />
All original coding and graphics for the <i>Dragon Knight</i> game engine are &copy; 2003-2005 by Jamin Seven.<br /><br />
[ <a href="#top">Top</a> ]
<br /><br />
<table class="copyright" width="100%"><tr>
<td width="50%" align="center">Powered by <a href="http://nigeru.com" target="_new">Nigeru Animes</a></td><td width="50%" align="center">&copy; 2010 by Oyatsumi</td>
</tr></table>
</body>
</html>