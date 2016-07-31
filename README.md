Naruto Browser Mmorpg
===================

A fully functional open source naruto PHP/Javascript/CSS/HTML/SQL based online browser MMORPG.


Facebook official group:
https://www.facebook.com/groups/957796797569032/



Attention! Don't try to host this game on your machine because it may induce various errors on the php interpreter, instead, use an online host such as the one provided within this document. In order to put the game working you may follow the instructions below:

1) Upload all the files within "src" to the host of your preference.
	-> You can also create an account here and do it: http://www.freewebhostingarea.com/
	
2) Upload the .sql on the database (sql) folder from your phpMyAdmin on the "import" option, then, the database will be
created and populated.
	-> Change the database name "databaseName" (on the .sql file) according to your preferences and the name of your mySQL table.
	
3) Change the config.php file within the "src" folder to your mysql connection parameters, don't change the prefix variable.

4) Open your domain in your browser and login with:
	-> Account: Oyatsumi
	-> Pass.: 123456 


Ps.: The game is in portuguese most of the time, sorry about that. You may change or do whatever you want.
This code is under the MIT license.
Please provide credits.

Ps2.: You may access "admin.php" to alter some stuff of the game, but beware, some images are linked to them such as the
"monsters cards".

Ps3.: To play without an admin account ("authlevel == 1"), remove this code from index.php:
	// Close game.
	if ($controlrow["gameopen"] == 0) { 
	if ($userrow["authlevel"] != 1){
	display("Foi encontrado um bug no jogo. O mesmo estará fechado até o lançamento da próxima versão. Por favor volte mais tarde e desculpe o transtorno.","Fechado"); die();
	}
	}
	
Once you create a new account it is a normal player account, not an admin account and the game won't run unless you remove the piece of code above.



You may, instead, alter the "gameopen" column on dk_control table from 0 to 1 to open the game.


Ps4.: You may change the theme of the game to whatever you want to and keep using the engine. You can also use the psds and the already done graphics as you wish (included here).


By Oyatsumi ( Érick Oliveira Rodrigues ).


For test purposes you may access:
http://narutorpg.orgfree.com/index.php

And login with:
->Oyatsumi
->123456


Attention! If someone is logged on the account already you won't be able to login. Sorry about that.
Also, here is a link with a video demonstration: https://www.youtube.com/watch?v=dKdVN4uJirU
Recently, someone just broke the online demo, so for now what works is the video.

Here's a link with a series of psds used on the game, in case that could help >
https://drive.google.com/file/d/0BxJH7y8eF0sjcmNsVGtNbFlNZkU/edit?usp=sharing
