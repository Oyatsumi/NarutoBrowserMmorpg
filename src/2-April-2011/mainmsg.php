<?php



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();

//Pra ir lá no primary.
global $userrow;


//Parte das funções.
if (isset($_GET["do2"])) {
    
    $do = $_GET["do2"];
	if ($do == "usarjutsubusca"){usarjutsubusca();}
	elseif ($do == "enviarpm"){enviarpm();}

	}



//Justu de busca, formulário de preenchimento do nome.
function usarjutsubusca(){
			global $userrow;	
			
			if (isset($_POST["submit"])) {
					extract($_POST);
						
						$pagina = "";
						if ($userrow["jutsudebuscahtml"] == 0) {$pagina = "Você não pode usar esse jutsu sem ter treinado!";}
						//dados do jogador da procura	
						$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomedaprocura' LIMIT 1","users");
						if (mysql_num_rows($userquery) != 1) {$pagina = "Não existe nenhuma conta com esse Nome.";}
						$userpara = mysql_fetch_array($userquery);
						
						
						$mp = $userrow["currentmp"];
						$usuariologadonome = $userrow["charname"];
						
						
						if ($mp < 30) {$pagina = "Esse jutsu requer 30 de Chakra para ser usado.";}
						$mpquesobrou = $mp - 30;
						
						if ($pagina == "") {
						$updatequery = doquery("UPDATE {{table}} SET currentmp='$mpquesobrou' WHERE charname='$usuariologadonome' LIMIT 1","users");
						if ($userpara["longitude"] >= 0) {$userpara["longitude"] .= "E";}
						if ($userpara["latitude"] >= 0) {$userpara["latitude"] .= "N";}
						if ($userpara["longitude"] < 0) {$userpara["longitude"] *= -1; $userpara["longitude"] .= "W";}
						if ($userpara["latitude"] < 0) {$userpara["latitude"] *= -1; $userpara["latitude"] .= "S";}
						$pagina = "O jogador ".$userpara["charname"]." est&aacute; na coordenada: [".$userpara["latitude"].",".$userpara["longitude"]."].";
						
						//jogadores online:
							$usersqueryd = doquery("SELECT * FROM {{table}} WHERE UNIX_TIMESTAMP(onlinetime) >= '".(time()-61)."' AND charname='$nomedaprocura' LIMIT 1", "users");
						if (mysql_num_rows($usersqueryd) != 1) {$pagina = $pagina."<br>Este jogador est&aacute; <font color=red>Offline</font>. ";
						$pagina .= "<a href=\"javascript: openmappopup(\'".$userpara["latitude"]."/".$userpara["longitude"]."\')\"><img src=\"images/maximizar.gif\" border=\"0\" title=\"Mostrar Localiza&ccedil;&atilde;o no Mapa\"></a>";
						}
						else{$pagina = $pagina."<br>Este jogador est&aacute; <font color=green>Online</font>.";}
						
						}//fim if		
						
				$pagina = "<br>".$pagina;
				$completo = "byakugan#$%;JUTSU DE BUSCA ATIVADO#$%;".$pagina;
				$updatequery = doquery("UPDATE {{table}} SET mainmsg='$completo' WHERE charname='".$userrow['charname']."' LIMIT 1","users");
				header('Location: ./'.$userrow["pagina"].'');	
				die();
						 
			}
			


				$updatequery = doquery("UPDATE {{table}} SET mainmsg='1' WHERE charname='".$userrow['charname']."' LIMIT 1","users");
						
		
	} 
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
//Enviar pm	
function enviarpm(){
$nomedochar = $_GET['nomedochar'];
global $userrow;
if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		

		if (isset($_POST["submit"])) {
					extract($_POST);
				
			//dados do jogador da procura	
			$pagina = "";
			if (strlen($txtmensagem) > 100){$pagina = "A mensagem que você quer enviar tem mais de 100 caracteres!"; }
			$antispam = explode(" ", $txtmensagem);
			for ($j = 0; $j < sizeof($txtmensagem); $j++){
				if (strlen($antispam[$j]) > 58){$pagina = "Voc&ecirc; ativou nosso sistema de bloqueio de spam, enviando uma palavra muito grande, por favor n&atilde;o repita isso novamente.";}
			}
			
			$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomedaprocura' LIMIT 1","users");
			if (mysql_num_rows($userquery) != 1) { $pagina = "Não existe nenhum usuário com esse Nome.";}
			
			if ($pagina == ""){
					$userpara = mysql_fetch_array($userquery);
					
					$usuariologadonome = $userrow["charname"];
					$outrousuario = $userpara["charname"];
					
					if (($userpara["caixadepm"] == "None") || ($userpara["caixadepm"] == "")) {$updatequery = doquery("UPDATE {{table}} SET caixadepm='@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;' WHERE charname='$outrousuario' LIMIT 1","users"); $userpara["caixadepm"] = "@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;";}
					
					
					$caixademensagem = $userpara["caixadepm"];
					$mensagemseparada = explode("@#$%!;;;", $caixademensagem);
					
					for ($i = 1; $i < 25; $i++){
					$novacaixademensagem .= $mensagemseparada[$i]."@#$%!;;;";
					}
							
						//cor das msgs		
						if ($userrow["authlevel"] == 1){ $link = " id=\"adm\" "; $cor = "<font color=\"green\">"; $cor2 = "</font>";}
						elseif ($userrow["acesso"] == 2){ $link = " id=\"tutor\" "; $cor = "<font color=\"orange\">"; $cor2 = "</font>";}
						elseif ($userrow["acesso"] == 3){ $link = " id=\"gm\" "; $cor = "<font color=\"blue\">"; $cor2 = "</font>";}
					$novacaixademensagem .= "[$cor<b><a href=\"mainmsg.php?do2=enviarpm&nomedochar=$usuariologadonome\" $link title=\"Enviar Mensagem\">".$usuariologadonome."</a></b>$cor2] $cor".strip_tags($txtmensagem).$cor2;
					
					
					$updatequery = doquery("UPDATE {{table}} SET caixadepm='$novacaixademensagem', pmsnovas='1' WHERE charname='$outrousuario' LIMIT 1","users");
					
					$pagina = "Sua mensagem foi enviada com sucesso!"; 
					$pagina = "<br>".$pagina;
					$completo = "naruto#$%;ENVIAR MENSAGEM PRIVADA#$%;".$pagina;
					$updatequery = doquery("UPDATE {{table}} SET mainmsg='$completo' WHERE charname='".$userrow['charname']."' LIMIT 1","users");
					header('Location: ./'.$userrow["pagina"].'');	
					die();
				 
			}//fim do if pagina = ""
				
				$pagina = "<font color=\"darkred\">N&atilde;o foi poss&iacute;vel enviar sua mensagem.</font> ".$pagina; 
				$pagina = "<br>".$pagina;
				$completo = "naruto#$%;ENVIAR MENSAGEM PRIVADA#$%;".$pagina;
				$updatequery = doquery("UPDATE {{table}} SET mainmsg='$completo' WHERE charname='".$userrow['charname']."' LIMIT 1","users");
				header('Location: ./'.$userrow["pagina"].'');	
				die();
				
		}//fim do post submit.


$updatequery = doquery("UPDATE {{table}} SET mainmsg='2,,$nomedochar' WHERE charname='".$userrow['charname']."' LIMIT 1","users");

}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
header('Location: ./'.$userrow["pagina"].'');	
die();
	
	
	

?>