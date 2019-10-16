Naruto Browser Mmorpg
===================

A fully functional open source naruto PHP/Javascript/CSS/HTML/SQL based online browser MMO RPG.

Updated in 2018, fixed some bugs that made it fully functional (at least for the basic functions)!


Facebook official group:
https://www.facebook.com/groups/957796797569032/



Attention! Don't try to host this game on your machine because it may induce various errors on the php interpreter, instead, use an online host such as the one provided within this document. In order to put the game working you may follow the instructions below:

1) Upload all the files within "src" to the host of your preference.
	
	-> You can also create an account here and do it: http://www.freewebhostingarea.com/
	
2) Acess the SQL_database of your choice and upload the .sql on the database (sql) folder from your phpMyAdmin on the "import" option, then. Afterwards, the database will be populated with the tables of the game.
	
	-> Change the database name (on the top of your .sql file, e.g., Database: `newname`) according to your preferences and the name of your mySQL table.
	
3) Change the config.php file within the "src" folder to your mysql connection parameters, don't change the prefix "dk".
	
	-> The SQL file contains the tables named with this prefix, so please do not change it.

4) Open your domain in your browser and login with (admin account):

	-> Account: Oyatsumi
	
	-> Pass.: 123456 
	

You can (now) register and login with any account, if you want to create a normal player-account.

Ps.: The game is in portuguese (the dialogs and stuff), sorry about that. You may change or do whatever you want.
This code is under the MIT license.
Please provide credits.

Ps2.: You may access "admin.php" to alter some stuff, but beware, some images within the project folder are linked to some contents in the dataset. The "monster cards" are directly connected.

Ps3.: To play without an admin account ("authlevel == 1"), remove this piece of code from your index.php:

	
	if ($controlrow["gameopen"] == 0) {
	if ($userrow["authlevel"] != 1){
	display("Foi encontrado um bug no jogo. O mesmo estará fechado até o lançamento da próxima versão. Por favor volte mais tarde e 	desculpe o transtorno.","Fechado"); die();
	}
	}
	
Ps4.: You may change the theme of the game to whatever you want and keep using the engine. You can also use the psds as you wish (included here).

By Oyatsumi ( Érick Oliveira Rodrigues ).



Here is a link with a video demonstration: https://www.youtube.com/watch?v=dKdVN4uJirU



Here's a link with a series of psds used in the game, in case that could help >
https://drive.google.com/file/d/0BxJH7y8eF0sjcmNsVGtNbFlNZkU/edit?usp=sharing
