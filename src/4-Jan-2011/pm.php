<?php // enche o hp.



if ($valorlib == ""){//valor para nao redeclarar esses scripts.
include('lib.php');
$link = opendb();
include('cookies.php');
$userrow = checkcookies();
}



if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "enviar") { enviar(); }
	elseif ($do == "ler") { ler(); }
	}





function ler() {
global $userrow;
global $topvar,$conteudo;
$topvar = true;

if ($conteudo == ""){
$conteudo = $_GET['conteudo'];
if ($conteudo != ""){$conteudo = "<center><font color=brown>".strip_tags($conteudo)."</font><center><br>";}
}

if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		
		$usuariologadonome = $userrow["charname"];		
	
$caixademensagem = $userrow["caixadepm"];
$mensagemseparada = explode("§ªº", $caixademensagem);



for ($i = 0; $i < 16; $i++){
    if ($mensagemseparada[$i] == ""){$mensagemseparada[$i] = "Não há mensagem aqui.";}
}

$paginafinal = "<table width=\"100%\"><tr bgcolor=\"#613003\"><td><font color=white><center>Mensagens</center></font></td></tr>";
$auxiliar = 1;
for ($i = 15; $i > 0; $i--){
	if ($mensagemseparada[$i] != "Não há mensagem aqui."){
		$fundo = $auxiliar % 2;
		if ($fundo == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
		$paginafinal .= "<tr bgcolor=\"$bgcolor\"><td>".$mensagemseparada[$i]."</td></tr>";	
		$auxiliar += 1;
	}
}
$paginafinal .= "</table>";
	

	



	$updatequery = doquery("UPDATE {{table}} SET pmsnovas='' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/pm.gif\" /></center></td></tr></table>
 $conteudo
$paginafinal

";
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Jutsu de Busca", false, false, false); 
    
}














function enviar(){
$nomedochar = $_GET['nomedochar'];
global $topvar;
global $userrow,$conteudo;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		

if (isset($_POST["submit"])) {
        extract($_POST);
	
//dados do jogador da procura	
if (strlen($txtmensagem) > 100){header('Location: /narutorpg/pm.php?conteudo=A mensagem que você quer enviar tem mais de 100 caracteres!');die(); }

$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomedaprocura' LIMIT 1","users");
if (mysql_num_rows($userquery) != 1) { display("Não existe nenhum usuário com esse Nome.","Erro",false,false,false);die(); }
$userpara = mysql_fetch_array($userquery);

$usuariologadonome = $userrow["charname"];
$outrousuario = $userpara["charname"];

if (($userpara["caixadepm"] == "None") or ($userpara["caixadepm"] == "")) {$updatequery = doquery("UPDATE {{table}} SET caixadepm='§ªº§ªº§ªº§ªº§ªº§ªº§ªº§ªº§ªº§ªº§ªº§ªº§ªº§ªº§ªº' WHERE charname='$outrousuario' LIMIT 1","users");}


$caixademensagem = $userpara["caixadepm"];
$mensagemseparada = explode("§ªº", $caixademensagem);

for ($i = 1; $i < 16; $i++){
$novacaixademensagem .= $mensagemseparada[$i]."§ªº";
}
$novacaixademensagem .= "<b><a href=\"pm.php?do=enviar&nomedochar=$usuariologadonome\">".$usuariologadonome."</a> disse</b>: ".strip_tags($txtmensagem);

$mensagempiscando = "<blink><font color=red>Você tem novas mensagens.</font></blink><br><br>";

$updatequery = doquery("UPDATE {{table}} SET caixadepm='$novacaixademensagem' WHERE charname='$outrousuario' LIMIT 1","users");
$updatequery = doquery("UPDATE {{table}} SET pmsnovas='$mensagempiscando' WHERE charname='$outrousuario' LIMIT 1","users");


header('Location: /narutorpg/pm.php?do=ler&conteudo=Sua mensagem foi enviada com sucesso!');die(); 
 die();
}





	$nomebotao = "botaomsg";
    $conteudo = "<center><form action=\"pm.php?do=enviar\" method=\"post\">
Nome do Jogador:<br>
<input type=\"text\" size=\"20\" name=\"nomedaprocura\" value=\"$nomedochar\"><br>
Mensagem para o Jogador:<br><input name=\"txtmensagem\" type=\"text\" maxlength=\"100\" style=\"width:291px\"><br>
<input type=\"submit\" name=\"submit\" id=\"$nomebotao\" style=\"height:5px;\" value=\"\"><br>
<font color=brown>Você só pode enviar mensagens com até 100 caracteres.</font>
</form></center>

<script type=\"text/javascript\" language=\"JavaScript\">sumirbotao('".$nomebotao."');sumirbotao('".$nomebotao."');</script>";
	$valorlib = 1; //para nao repetir o lib.php
	$conteudo = "<center><table  bgcolor=\"#452202\"><tr><td width=\"18\"></td><td width=\"*\"><center><font color=white>Enviar Mensagem</font></center></td><td width=\"18\"><a href=\"pm.php?do=ler\"><img src=\"images/deletar2.jpg\" title=\"Fechar\"  alt=\"X\" border=\"0\"></a></td></tr><tr><td background=\"layoutnovo/menumeio/meio2.png\" colspan=\"3\"><font color=\"black\"><center>".$conteudo."</center></font></td></tr></table></center><br>";
	ler();
	die();


}





?>