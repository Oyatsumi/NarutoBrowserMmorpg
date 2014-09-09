<?php // users.php :: Handles user account functions.


/*$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);*/



include('lib.php');
$link = opendb();
include('cookies.php');
$userrow = checkcookies();



//mudar avatar
$nomedochar = $_GET['nomedochar'];

global $topvar;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();


		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
							if ($userrow["currentaction"] == "Fighting") {header('Location: /narutorpg/index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
							if ($userrow["currentaction"] == "In Town") {display("Você não pode acessar essa função dentro de uma cidade!","Erro",false,false,false);die(); }
					if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }



 $userquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomedochar' LIMIT 1","users");
		
		

        if (mysql_num_rows($userquery) != 1) { display("Não existe nenhum jogador com esse Nome.","Erro",false,false,false);die(); }
        $userpara = mysql_fetch_array($userquery);
			/*if ($userrow["password"] != md5($oldpass)) { die("The old password you provided was incorrect."); }
        /*$realnewpass = md5($newpass1); */
		$localiz = $userpara["id"];
		
		
			/*fim do teste */
			
			
			 $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/opcoesjogador.gif\" /></center></td></tr></table>
	
	<b>Opções Relacionadas ao Jogador</b>: <br>
	<ul>
	<li /><a href=\"index.php?do=onlinechar:$localiz\">Ver Perfil</a>
	<li /><a href=\"users.php?do=troca1&nomedochar=$nomedochar\">Realizar uma Troca</a>
	<li /><a href=\"users.php?do=batalha1&nomedochar=$nomedochar\">Realizar um Duelo</a>
	<li /><a href=\"pm.php?do=enviar&nomedochar=$nomedochar\">Enviar uma Mensagem</a>
	</ul>";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Opções de Mesmo Mapa", false, false, false); 

?>