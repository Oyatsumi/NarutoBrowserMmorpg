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

if ($userrow == false) { display("Por favor fa?a o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa a??o.","Erro",false,false,false);
		die(); }
		
		$usuariologadonome = $userrow["charname"];		
	
$caixademensagem = $userrow["caixadepm"];
$mensagemseparada = explode("@#$%!;;;", $caixademensagem);



for ($i = 0; $i < 25; $i++){
    if ($mensagemseparada[$i] == ""){$mensagemseparada[$i] = "Não há mensagem aqui.";}
}

$paginafinal = "<table width=\"100%\"><tr bgcolor=\"#613003\"><td><font color=white><center>Últimas Mensagens Privadas</center></font></td></tr>";
$auxiliar = 1;
for ($i = 24; $i > 0; $i--){
	if ($mensagemseparada[$i] != "Não há mensagem aqui."){
		$fundo = $auxiliar % 2;
		if ($fundo == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
		$paginafinal .= "<tr bgcolor=\"$bgcolor\"><td style=\"border:1px #000000 solid\">".$mensagemseparada[$i]."</td></tr>";	
		$auxiliar += 1;
	}
}
$paginafinal .= "</table>";
	

	



	$updatequery = doquery("UPDATE {{table}} SET pmsnovas='0' WHERE charname='$usuariologadonome' LIMIT 1","users");
	
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
    /* testando se est? logado */
		//include('cookies.php');
		//$userrow = checkcookies();
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		

if (isset($_POST["submit"])) {
        extract($_POST);
	
//dados do jogador da procura	
if (strlen($txtmensagem) > 100){header('Location: ./pm.php?conteudo=A mensagem que você quer enviar tem mais de 100 caracteres!');die(); }

$userquery = doquery("SELECT * FROM {{table}} WHERE charname='$nomedaprocura' LIMIT 1","users");
if (mysqli_num_rows($userquery) != 1) { display("Não existe nenhum usuário com esse Nome.","Erro",false,false,false);die(); }
$userpara = mysqli_fetch_array($userquery);

$usuariologadonome = $userrow["charname"];
$outrousuario = $userpara["charname"];

if (($userpara["caixadepm"] == "None") || ($userpara["caixadepm"] == "")) {$updatequery = doquery("UPDATE {{table}} SET caixadepm='@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;@#$%!;;;' WHERE charname='$outrousuario' LIMIT 1","users");}


$caixademensagem = $userpara["caixadepm"];
$mensagemseparada = explode("@#$%!;;;", $caixademensagem);

for ($i = 1; $i < 25; $i++){
$novacaixademensagem .= $mensagemseparada[$i]."@#$%!;;;";
}
		
	//cor das msgs		
	if ($userrow["authlevel"] == 1){ $link = " id=\"adm\" "; $cor = "<font color=\"green\">"; $cor2 = "</font>";}
	elseif ($userrow["acesso"] == 2){ $link = " id=\"tutor\" "; $cor = "<font color=\"orange\">"; $cor2 = "</font>";}
	elseif ($userrow["acesso"] == 3){ $link = " id=\"gm\" "; $cor = "<font color=\"blue\">"; $cor2 = "</font>";}
$novacaixademensagem .= "[$cor<b><a href=\"mainmsg.php?do2=enviarpmr&nomedochar=$usuariologadonome\" $link title=\"Enviar Mensagem\">".$usuariologadonome."</a></b>$cor2] $cor".strip_tags($txtmensagem).$cor2;


$updatequery = doquery("UPDATE {{table}} SET caixadepm='$novacaixademensagem', pmsnovas='1' WHERE charname='$outrousuario' LIMIT 1","users");

header('Location: ./pm.php?do=ler&conteudo=Sua mensagem foi enviada com sucesso!');die(); 
 die();
}





	$nomebotao = "botaomsg";
    $conteudo = "<center><form action=\"pm.php?do=enviar\" method=\"post\">
Nome do Jogador:<br>
<input type=\"text\" size=\"20\" name=\"nomedaprocura\" value=\"$nomedochar\"><br>
Mensagem para o Jogador:<br><input name=\"txtmensagem\" type=\"text\" maxlength=\"100\" style=\"width:280px\">
<input type=\"submit\" name=\"submit\" id=\"$nomebotao\" style=\"height:5px;\" value=\"OK\"><br>
<font color=brown>Você só pode enviar mensagens com até 100 caracteres.</font>
</form></center>
";
	$valorlib = 1; //para nao repetir o lib.php
	$conteudo = "<center><table  bgcolor=\"#452202\"><tr><td width=\"18\"></td><td width=\"*\"><center><font color=white>Enviar Mensagem</font></center></td><td width=\"18\"><a href=\"pm.php?do=ler\"><img src=\"images/deletar2.jpg\" title=\"Fechar\"  alt=\"X\" border=\"0\"></a></td></tr><tr><td background=\"layoutnovo/menumeio/meio2.png\" colspan=\"3\"><font color=\"black\"><center>".$conteudo."</center></font></td></tr></table></center><br>";
	ler();
	die();


}





?>