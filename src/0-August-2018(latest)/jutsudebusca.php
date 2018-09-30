<?php // enche o hp.



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();



if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "jutsu") { jutsu(); }
	elseif ($do == "aprendendo2") { aprendendo2(); }
	elseif ($do == "usar") {unset($_GET["do"]); usar(); }
	}





function jutsu() {
global $userrow;
global $topvar;
$topvar = true;
if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
			if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Voc� n�o pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

$longitude = $userrow["longitude"];
$latitude = $userrow["latitude"];


$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysqli_num_rows($townquery) == 0) { display("H� um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysqli_fetch_array($townquery);
if ($townrow["id"] != 5) {header('Location: ./index.php?conteudo=Voc� n�o pode usar essa fun��o fora da Vila da Areia.');die(); }


$townrow["kage"] = "Kazekage";
$townrow["id"] = 5;
 $conteudodois = " 

 Ol� pequeno ninja, voc� veio at� mim � procura do Jutsu de Busca? O Jutsu de busca � um jutsu que precisa ser treinado 10 vezes em intervalos de duas horas � cada treinamento, para ent�o ser aperfei�oado. A finalidade do Jutsu � revelar as coordenadas do jogador que voc� est� procurando e informar se ele est� online ou n�o.<br>
 Se voc� ainda quer obter esse jutsu, podemos <a href=\"jutsudebusca.php?do=aprendendo2&inicio=true\">come�ar agora mesmo</a> o treinamento do Jutsu de Busca.

";
include('funcoesinclusas.php');
personagemmissao($conteudodois, $townrow);
die();

}
















function aprendendo2(){
$inicio = $_GET['inicio'];
global $topvar;
global $userrow;
$topvar = true;
    /* testando se est� logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!');die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Voc� n�o pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

$longitude = $userrow["longitude"];
$latitude = $userrow["latitude"];

$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysqli_num_rows($townquery) == 0) { display("H� um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
    $townrow = mysqli_fetch_array($townquery);
if ($townrow["id"] != 5) {header('Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� n�o pode treinar esse Jutsu fora da Vila da Areia.');die(); }



//fun��o se passou o tempo necess�rio ou n�o.
$today = date("j/n/Y");
$todayhour = date("H:i:s");
$nomedojutsu = "Jutsu de Busca"; //nome do jutsu pra buscar.
$tempoprapassar = 120; //tempo em minutos
//colocando o jutsu no campo, nome, quantos ainda tem q treinar, treinar ao total, dia e hora do ultimo treino, tempo pra passar em minutos. A HORA � - 2 DA HORA DO BRASIL.
if ($userrow["treinamento"] != "None"){
	$treinos = explode(";",$userrow["treinamento"]);
	for($i = 0; $i < (count($treinos) - 1); $i++){// 
		$subtreinos = explode(",",$treinos[$i]);
		if ($subtreinos[0] == $nomedojutsu){
					//�rea die.
					if ($inicio == true){header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� j� possui o(a) ".$nomedojutsu." na sua lista de treinamento.");die();}
					if ($subtreinos[1] == $subtreinos[2]){header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� j� completou o treinamento do(a) ".$nomedojutsu.".");die();}
					include('funcoesinclusas.php');
					$retorno = tempojutsu($subtreinos[3], $subtreinos[4], $subtreinos[5]);
					if ($retorno != "ok"){header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� ainda n�o pode treinar, � preciso aguardar ".$retorno." minuto(s) at� que voc� possa treinar o(a) ".$nomedojutsu." novamente.");die();}
					//fim area die.
			$i = count($treinos);
			$achou = true;
			//colocando tudo no lugar:
			$userrow["treinamento"] = "";
			for($j = 0; $j < (count($treinos) - 1); $j++){
				$subtreinos2 = explode(",",$treinos[$j]);
				if ($subtreinos2[0] != $nomedojutsu){
					$userrow["treinamento"] .= $treinos[$j].";";
				}else{//se for igual o nome da busca do jutsu
					$subtreinos2[1] += 1;
					$userrow["treinamento"] .= $subtreinos2[0].",".$subtreinos2[1].",".$subtreinos2[2].",".$today.",".$todayhour.",".$tempoprapassar.";";
					$valor1 = $subtreinos2[1];
					$valor2 = $subtreinos2[2];
				}//fim segundo if
			}//fim segundo for
			
			
		}//fimif
	}//fimfor
	
	if ($achou != true){//adicionando se nao for encontrado
		$userrow["treinamento"] .= $nomedojutsu.",0,10,".$today.",".$todayhour.",0;";
		$updatequery = doquery("UPDATE {{table}} SET treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
		header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� adicionou o(a) ".$nomedojutsu." � sua tabela de treinamentos.");die(); 
	}else{//se for conclu�do ent�o
	
	
		//se o jutsu for conclu�do
		if (($valor1 >= $valor2) && ($achou = true)){
			$subtrienos2[1] = $subtrienos2[2]; //pra n�o ficar um maior que o outro.
				//o que ganha no jutsu:
				$jutsufinal = 1;
$updatequery = doquery("UPDATE {{table}} SET jutsudebuscahtml='$jutsufinal' WHERE charname='".$userrow['charname']."' LIMIT 1","users");				//fim do que ganha.
		$updatequery = doquery("UPDATE {{table}} SET treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");	
			header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� completou o treinamento do(a) ".$nomedojutsu.". Parab�ns!");die(); 
			
		}else{//treinou e n�o completou o jutsu.
		$updatequery = doquery("UPDATE {{table}} SET treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");	
			header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� treinou o(a) ".$nomedojutsu.". Voc� poder� treinar novamente dentro de ".$tempoprapassar." minutos.");die();		
		}//fim else		
		
		
		
		}//fim do else
	
	
	
		


}else{$userrow["treinamento"] = $nomedojutsu.",0,10,".$today.",".$todayhour.",0;";
$updatequery = doquery("UPDATE {{table}} SET treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");
header("Location: ./treinamentoequests.php?do=treinamento&conteudo=Voc� adicionou o(a) ".$nomedojutsu." a sua tabela de treinamentos.");die(); }




			//atualizar
		$updatequery = doquery("UPDATE {{table}} SET treinamento='".$userrow["treinamento"]."' WHERE charname='".$userrow["charname"]."' LIMIT 1","users");	
			
				



    
}














function usar(){
global $topvar;
global $userrow;
$topvar = true;
global $valorlib, $indexconteudo, $controlrow;

    /* testando se est� logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a��o.","Erro",false,false,false);
		die(); }
				if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Voc� n�o pode acessar essa fun��o no meio de uma batalha!'); die(); }
				if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Voc� n�o pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

if ($userrow["jutsudebuscahtml"] == 0) {header("Location: ./index.php?conteudo=Voc� n�o pode usar esse jutsu sem ter treinado!");die();}


if (isset($_POST["submit"])) {
        extract($_POST);
				
			//dados do jogador da procura	
			$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomedaprocura' LIMIT 1","users");
			if (mysqli_num_rows($userquery) != 1) {header("Location: ./index.php?conteudo=N�o existe nenhuma conta com esse Nome.");die();}
			$userpara = mysqli_fetch_array($userquery);
			
			
			$mp = $userrow["currentmp"];
			$usuariologadonome = $userrow["charname"];
			
			$pagina = "";
			if ($mp < 30) {$pagina = "Esse jutsu requer 30 de Chakra para ser usado.";}
			$mpquesobrou = $mp - 30;
			
			if ($pagina == "") {
			$updatequery = doquery("UPDATE {{table}} SET currentmp='$mpquesobrou' WHERE charname='$usuariologadonome' LIMIT 1","users");
			if ($userpara["longitude"] > 0) {$userpara["longitude"] .= "E";}
			if ($userpara["latitude"] > 0) {$userpara["latitude"] .= "N";}
			if ($userpara["longitude"] < 0) {$userpara["longitude"] *= -1; $userpara["longitude"] .= "W";}
			if ($userpara["latitude"] < 0) {$userpara["latitude"] *= -1; $userpara["latitude"] .= "S";}
			$pagina = "O jogador ".$userpara["charname"]." est� na coordenada: ".$userpara["latitude"].", ".$userpara["longitude"].".";
			
			//jogadores online:
				$usersqueryd = doquery("SELECT * FROM {{table}} WHERE UNIX_TIMESTAMP(onlinetime) >= '".(time()-61)."' AND charname='$nomedaprocura' LIMIT 1", "users");
			if (mysqli_num_rows($usersqueryd) != 1) {$pagina = $pagina."<br>Este jogador est� <font color=red>Offline</font>. ";
			$pagina .= "<a href=\"javascript: openmappopup('".$userpara["latitude"]."/".$userpara["longitude"]."')\"><img src=\"images/maximizar.gif\" border=\"0\" title=\"Mostrar Localiza��o no Mapa\"></a>";
			}
			else{$pagina = $pagina."<br>Este jogador est� <font color=green>Online</font>.";}
			
			}//fim if
			
			

			 
	$indexconteudo = "<center>".$pagina."</center>";
	$valorlib = 1; //para nao repetir o lib.php
	$indexconteudo = "<center><table  bgcolor=\"#452202\"><tr><td width=\"18\"></td><td width=\"*\"><center><font color=white>Jutsu de Busca</font></center></td><td width=\"18\"><a href=\"index.php\"><img src=\"images/deletar2.jpg\" title=\"Fechar\"  alt=\"X\" border=\"0\"></a></td></tr><tr><td background=\"layoutnovo/menumeio/meio2.png\" colspan=\"3\"><font color=\"black\"><center>".$indexconteudo."</center></font></td></tr></table></center>";
	include('index.php');
	die();
			 
}

	$nomebotao = "botaobusca";
    $indexconteudo = "<center><form action=\"jutsudebusca.php?do=usar\" method=\"post\">
Qual jogador voc� quer procurar? CH: 30.<br><input type=\"submit\" id=\"$nomebotao\" name=\"submit\" value=\"\" style=\"height:5px;\"><br>
Nome do Jogador:<br> <input type=\"text\" name=\"nomedaprocura\">

</form></center><script type=\"text/javascript\" language=\"JavaScript\">sumirbotao('".$nomebotao."');sumirbotao('".$nomebotao."');</script>";
	$valorlib = 1; //para nao repetir o lib.php
	$indexconteudo = "<center><table  bgcolor=\"#452202\"><tr><td width=\"18\"></td><td width=\"*\"><center><font color=white>Jutsu de Busca</font></center></td><td width=\"18\"><a href=\"index.php\"><img src=\"images/deletar2.jpg\" title=\"Fechar\"  alt=\"X\" border=\"0\"></a></td></tr><tr><td background=\"layoutnovo/menumeio/meio2.png\" colspan=\"3\"><font color=\"black\"><center>".$indexconteudo."</center></font></td></tr></table></center>";
	include('index.php');
	die();

}







?>