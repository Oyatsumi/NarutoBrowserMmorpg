Naruto Browser Mmorpg
===================

An open source naruto PHP/Javascript/CSS/HTML/SQL based online MMORPG.


Facebook official group:
https://www.facebook.com/groups/957796797569032/



Attention! Don't try to host this game on your machine coz it may induce various errors, instead, use an online host such
as the one provided within this document.

1) Upload all the files within "src" to the host of your preference.
	-> You can also create an account here and do it: http://www.freewebhostingarea.com/
	
2) Upload the .sql on the database (sql) folder from your phpMyAdmin on the "import" option, then, the database will
created and populated.
	-> Change the database name "databaseName" according to your preferences.
	
3) Change the config.php file within the "src" folder to your mysql connection parameters, don't change the prefix variable.

4) Open your domain in your browser and login with:
	-> Account: Oyatsumi
	-> Pass.: 123456 


Ps.: The game is in portuguese most of the time, sorry about that. You may change or do what you want.
This code is under the MIT license.
Please provide credits.

Ps2.: You may access "admin.php" to alter some stuff of the game, but beware, some images are linked to them such as the
"monsters cards".

Ps3.: For you to play without an admin account ("authlevel == 1"), remove this code from index.php, for instance:
Once you create a new account it is a normal account, not an admin account and the game won't run.

	// Close game.
	if ($controlrow["gameopen"] == 0) { 
	if ($userrow["authlevel"] != 1){
	display("Foi encontrado um bug no jogo. O mesmo estará fechado até o lançamento da próxima versão. Por favor volte mais tarde e desculpe o transtorno.","Fechado"); die();
	}
	}


By Oyatsumi (Érick Oliveira Rodrigues).


For test purposes you may access:
http://narutorpg.orgfree.com/index.php

And login with:
->Oyatsumi
->123456
