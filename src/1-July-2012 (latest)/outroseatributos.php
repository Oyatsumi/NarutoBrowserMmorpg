<?php // enche o hp.



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();

//status de recuperação



if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "atributos") { atributos(); }
	}





function atributos() {
global $topvar;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
		global $userrow;



		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		
		//chamando variaveis de atributo
		$agilidade = $userrow["agilidade"];
$determinacao = $userrow["determinacao"];
$sorte = $userrow["sorte"];
$precisao = $userrow["precisao"];
$inteligencia = $userrow["inteligencia"];
$pontoatributos = $userrow["pontoatributos"];

//outra
	$usuariologadonome = $userrow["charname"];
		
		
		if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

					if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
				
	
		
			
				
	
	
    if (isset($_POST["submit"])) {
        extract($_POST);
		if ($agilidadep == ""){$agilidadep = 0;}
		if ($sortep == ""){$sortep = 0;}
		if ($determinacaop == ""){$determinacaop = 0;}
		if ($precisaop == ""){$precisaop = 0;}
		if ($inteligenciap == ""){$inteligenciap = 0;}
		if (!is_numeric($agilidadep)) { header('Location: ./outroseatributos.php?do=atributos&conteudo=O campo de agilidade deve ser um número ou deixado em branco.');die(); }
		if (!is_numeric($sortep)) { header('Location: ./outroseatributos.php?do=atributos&conteudo=O campo de sorte deve ser um número ou deixado em branco.');die();}
		if (!is_numeric($determinacaop)) {  header('Location: ./outroseatributos.php?do=atributos&conteudo=O campo de determinação deve ser um número ou deixado em branco.');die(); }
		if (!is_numeric($precisaop)) { header('Location: ./outroseatributos.php?do=atributos&conteudo=O campo de precisão deve ser um número ou deixado em branco.');die(); }
		if (!is_numeric($inteligenciap)) { header('Location: ./outroseatributos.php?do=atributos&conteudo=O campo de inteligência deve ser um número ou deixado em branco.');die();}
		$agilidadep = floor($agilidadep);
		$sortep = floor($sortep);
		$determinacaop = floor($determinacaop);
		$precisaop = floor($precisaop);
		$inteligenciap = floor($inteligenciap);
		
		
        $pontostotal = $agilidadep + $sortep + $determinacaop + $precisaop + $inteligenciap;

		/*if ($userrow["password"] != md5($oldpass)) { die("The old password you provided was incorrect."); }
        /*$realnewpass = md5($newpass1); */
		if ($pontoatributos == 0) { header('Location: ./outroseatributos.php?do=atributos&conteudo=Você não possui pontos para distribuição.');die();}
		if ($pontostotal > $pontoatributos) { header('Location: ./outroseatributos.php?do=atributos&conteudo=Você não pode distribuir mais pontos que '.$pontoatributos.' pontos.');die();}

		
			
		$pontosrestantes = $pontoatributos - $pontostotal;
			
$determinacao += $determinacaop;
$sorte += $sortep;
$precisao += $precisaop;
$inteligencia += $inteligenciap;
$agilidade += $agilidadep;
				
		$updatequery = doquery("UPDATE {{table}} SET agilidade='$agilidade' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET determinacao='$determinacao' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET precisao='$precisao' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET inteligencia='$inteligencia' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET sorte='$sorte' WHERE charname='$usuariologadonome' LIMIT 1","users");
		$updatequery = doquery("UPDATE {{table}} SET pontoatributos='$pontosrestantes' WHERE charname='$usuariologadonome' LIMIT 1","users");
		
        
				
       header('Location: ./outroseatributos.php?do=atributos&conteudo=Seus pontos foram distribuidos corretamente.');die();
    }
	
	$conteudo = $_GET['conteudo'];
	if ($conteudo != ""){$conteudo = "<center><font color=\"brown\">".strip_tags($conteudo)."</font></center><br>";}
    $page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/distribuir.gif\" /></center></td></tr></table>
	$conteudo
	<center><table cellpadding=\"0\" cellspacing=\"0\"><tr><td><table><tr><td colspan=\"2\" bgcolor=\"#452202\"><center><font color=\"white\">Meus Pontos</font></center></td></tr>
	<tr bgcolor=\"#E4D094\"><td>Pontos para Distribuir </td><td>$pontoatributos</td></tr>
	<tr bgcolor=\"#FFF1C7\"><td>Agilidade<img src=\"images/raio.gif\" title=\"Elemento Relâmpago\"></td><td>$agilidade</td></tr>
	<tr bgcolor=\"#E4D094\"><td>Sorte<img src=\"images/agua.gif\" title=\"Elemento Água\"></td><td>$sorte</td></tr>
	<tr bgcolor=\"#FFF1C7\"><td>Determinação<img src=\"images/fogo.gif\" title=\"Elemento Fogo\"></td><td>$determinacao</td></tr>
	<tr bgcolor=\"#E4D094\"><td>Precisão<img src=\"images/vento.gif\" title=\"Elemento Vento\"></td><td>$precisao</td></tr>
	<tr bgcolor=\"#FFF1C7\"><td>Inteligência<img src=\"images/terra.gif\" title=\"Elemento Terra\"></td><td>$inteligencia</td></tr>
</table></td>".gettemplate("outroseatributos");
   $topnav = "<a href=\"index.php\"><img src=\"images/jogar.gif\" alt=\"Voltar a Jogar\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Ajuda\" border=\"0\" /></a>";
    display($page, "Distribuir Pontos", false, false, false); 
    
}
















?>