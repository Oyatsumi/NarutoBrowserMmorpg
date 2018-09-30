<?php // forum.php :: Internal forums script for the game.

include('lib.php');
include('cookies.php');
$link = opendb();
$userrow = checkcookies();
if ($userrow == false) { display("Esse fórum é reservado apenas à jogadores registrados.", "Forum"); die(); }
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);

// Close game.
if (($controlrow["gameopen"] == 0) && ($userrow['authlevel'] != 1)) { display("O jogo está fechado para manutenção no momento, por favor volte mais tarde.","Jogo Fechado"); die(); }
// Force verify if the user isn't verified yet.
if ($controlrow["verifyemail"] == 1 && $userrow["verify"] != 1) { header("Location: users.php?do=verify"); die(); }
// Block user if he/she has been banned.
if ($userrow["authlevel"] == 2) { die("Sua conta foi bloqueada. Por favor tente novamente mais tarde."); }

if (isset($_GET["do"])) {
	$do = explode(":",$_GET["do"]);
	
	if ($do[0] == "thread") { showthread($do[1], $do[2]); }
	elseif ($do[0] == "new") { newthread(); }
	elseif ($do[0] == "reply") { reply(); }
	elseif ($do[0] == "list") { donothing($do[1]); }
	elseif ($do[0] == "deletar") { deletar($do[1]); }
	elseif ($do[0] == "msg") { msg(); }
	
} else { donothing(0); }

function donothing($start=0) {
	
	//extraindo categoria
	extract($_POST);
	if ($categoria == "geral"){$categoria = "";}
	elseif ($categoria != ""){$categoria = "AND categoria='$categoria'";}

	global $userrow;
	$conteudo = $_GET['conteudo'];
	if ($conteudo != ""){
	$conteudo = "<font color=brown><center>".strip_tags(utf8_decode($conteudo))."</font></center><br>";}
    $query = doquery("SELECT * FROM {{table}} WHERE parent='0' $categoria ORDER BY newpostdate DESC LIMIT 30", "forum");
    $page = "<center><table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/title_forum.gif\" /></center></td></tr></table></center>$conteudo<table width=\"100%\"><tr><td style=\"padding:1px; background-color:black;\"><table width=\"100%\" style=\"margins:0px;\" cellspacing=\"1\" cellpadding=\"3\"><tr><td colspan=\"3\" style=\"background-color:#452202;\"><center><font color=\"white\">Tópicos Criados</font></center></td></tr><tr></tr><tr><th width=\"50%\" style=\"background-color:#E4D094;\">T&oacute;pico</th><th width=\"10%\" style=\"background-color:#E4D094;\">Respostas</th><th style=\"background-color:#E4D094;\">Último Post</th></tr>\n";
    $count = 1;
	$page = utf8_decode($page);
    if (mysql_num_rows($query) == 0) { 
        $page .= "<tr><td style=\"background-color:#FFF1C7;\" colspan=\"3\"><b>Nenhum t&oacute;pico nesse f&oacute;rum ou categoria.</b></td></tr>\n";
    } else { 
        while ($row = mysql_fetch_array($query)) {
        	if ($count == 1) {
				//cor das msgs
				$userquery2 = doquery("SELECT * FROM {{table}} WHERE charname='".$row['author']."' LIMIT 1","users");
				$userpara = mysql_fetch_array($userquery2);		
				if ($userpara["authlevel"] == 1){ $link = " id=\"adm\" "; $cor = "<font color=\"green\">"; $cor2 = "</font>";}
				elseif ($userpara["acesso"] == 2){ $link = " id=\"tutor\" "; $cor = "<font color=\"orange\">"; $cor2 = "</font>";}
				elseif ($userpara["acesso"] == 3){ $link = " id=\"gm\" "; $cor = "<font color=\"blue\">"; $cor2 = "</font>";}
				else{$link = ""; $cor = ""; $cor2 = "";}
				if (($userrow['authlevel'] == 1) || ($userrow['acesso'] == 3)){$linkdeletar = " <a href=\"javascript: yesorno('vermelho', 'Deletar Topico?', 'forum.php?do=deletar:".$row['id']."', 'javascript: fecharexplic()');\" title=\"Deletar T&oacute;pico\" id=\"vermelho\">x</a>";}
            	$page .= "<tr><td style=\"background-color:#FFF1C7;\"><a $link href=\"forum.php?do=thread:".$row["id"].":0\">".$row["title"]."</a></td><td style=\"background-color:#FFF1C7;\">".$row["replies"]."</td><td style=\"background-color:#FFF1C7;\">".padraodate($row["newpostdate"])."$linkdeletar</td></tr>\n";
            	$count = 2;
            } else {
				//cor das msgs
				$userquery2 = doquery("SELECT * FROM {{table}} WHERE charname='".$row['author']."' LIMIT 1","users");
				$userpara = mysql_fetch_array($userquery2);		
				if ($userpara["authlevel"] == 1){ $link = " id=\"adm\" "; $cor = "<font color=\"green\">"; $cor2 = "</font>";}
				elseif ($userpara["acesso"] == 2){ $link = " id=\"tutor\" "; $cor = "<font color=\"orange\">"; $cor2 = "</font>";}
				elseif ($userpara["acesso"] == 3){ $link = " id=\"gm\" "; $cor = "<font color=\"blue\">"; $cor2 = "</font>";}
				else{$link = ""; $cor = ""; $cor2 = "";}
				if (($userrow['authlevel'] == 1) || ($userrow['acesso'] == 3)){$linkdeletar = " <a href=\"javascript: yesorno('vermelho', 'Deletar Topico?', 'forum.php?do=deletar:".$row['id']."', 'javascript: fecharexplic()');\" title=\"Deletar T&oacute;pico\" id=\"vermelho\">x</a>";}
                $page .= "<tr><td style=\"background-color:#FFF1C7;\"><a $link href=\"forum.php?do=thread:".$row["id"].":0\">".$row["title"]."</a></td><td style=\"background-color:#FFF1C7;\">".$row["replies"]."</td><td style=\"background-color:#FFF1C7;\">".padraodate($row["newpostdate"])."$linkdeletar</td></tr>\n";
                $count = 1;
            }
        }
    }
    $page .= "</table></td></tr></table><center><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td>
<form name=\"categoriaselecao\" action=\"forum.php\" method=\"post\" >
<select name=\"categoria\" style=\"width:170px\" onchange=\"this.form.submit()\">
<option selected>Categoria</option>
<option value=\"geral\">Todos</option>
<option value=\"duvidas\">D&uacute;vidas</option>
<option value=\"tutoriais\">Tutoriais</option>
<option value=\"bate-papo\">Bate-Papo</option>
<option value=\"reclamacoes\">Reclama&ccedil;&otilde;es</option>
</select>
</form></td><td>

<a href=\"forum.php?do=new\"><img src=\"images/novotopico.gif\" title=\"Criar Novo T&oacute;pico\" border=\"0\" style=\"padding-top:2px\"></a></td></tr></table></center>";
    
    display($page, "Forum");
    
}

function showthread($id, $start) {
	global $userrow;

    $query = doquery("SELECT * FROM {{table}} WHERE id='$id' OR parent='$id' ORDER BY id LIMIT $start,15", "forum");
    $query2 = doquery("SELECT title FROM {{table}} WHERE id='$id' LIMIT 1", "forum");
    $row2 = mysql_fetch_array($query2);
    $page = "<center><table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/title_forum.gif\" /></center></td></tr></table></center><table width=\"100%\"><tr><td style=\"padding:1px; background-color:black;\"><table width=\"100%\" style=\"margins:0px;\" cellspacing=\"1\" cellpadding=\"3\"><tr><td colspan=\"3\" style=\"background-color:#452202;\"><font color=\"white\"><center>T&oacute;pico e Respostas</center></font></td></tr><tr><td colspan=\"2\" style=\"background-color:#E4D094;\"><b><a href=\"forum.php\">In&iacute;cio</a> <img src=\"images/seta.gif\"> ".$row2["title"]."</b></td></tr>\n";
    $count = 1;
    while ($row = mysql_fetch_array($query)) {
        if ($count == 1) {
				//cor das msgs
				$userquery2 = doquery("SELECT * FROM {{table}} WHERE charname='".$row['author']."' LIMIT 1","users");
				$userpara = mysql_fetch_array($userquery2);		
				if ($userpara["authlevel"] == 1){ $link = " id=\"adm\" "; $cor = "<font color=\"green\">"; $cor2 = "</font>";}
				elseif ($userpara["acesso"] == 2){ $link = " id=\"tutor\" "; $cor = "<font color=\"orange\">"; $cor2 = "</font>";}
				elseif ($userpara["acesso"] == 3){ $link = " id=\"gm\" "; $cor = "<font color=\"blue\">"; $cor2 = "</font>";}
				else{$link = ""; $cor = ""; $cor2 = "";}
				if (($userrow['authlevel'] == 1) || ($userrow['acesso'] == 3)){$linkdeletar = " <a href=\"javascript: yesorno('vermelho', 'Deletar Resposta?', 'forum.php?do=deletar:".$row['id']."', 'javascript: fecharexplic()');\" title=\"Deletar Resposta\" id=\"vermelho\">x</a>";}
            $page .= "<tr><td width=\"25%\" style=\"background-color:#FFF1C7; vertical-align:top;\"><span class=\"small\"><b>$cor<a $link href=\"javascript: opcaochar('".$row["author"]."');\">".$row["author"]."</a>$cor2$linkdeletar</b><br />".prettyforumdate($row["postdate"])."</td><td style=\"background-color:#FFF1C7; vertical-align:top;\">".nl2br($row["content"])."</td></tr>\n";
            $count = 2;
        } else {
				//cor das msgs
				$userquery2 = doquery("SELECT * FROM {{table}} WHERE charname='".$row['author']."' LIMIT 1","users");
				$userpara = mysql_fetch_array($userquery2);		
				if ($userpara["authlevel"] == 1){ $link = " id=\"adm\" "; $cor = "<font color=\"darkgreen\">"; $cor2 = "</font>";}
				elseif ($userpara["acesso"] == 2){ $link = " id=\"tutor\" "; $cor = "<font color=\"darkorange\">"; $cor2 = "</font>";}
				elseif ($userpara["acesso"] == 3){ $link = " id=\"gm\" "; $cor = "<font color=\"darkblue\">"; $cor2 = "</font>";}
				else{$link = ""; $cor = ""; $cor2 = "";}
				if (($userrow['authlevel'] == 1) || ($userrow['acesso'] == 3)){$linkdeletar = " <a href=\"javascript: yesorno('vermelho', 'Deletar Resposta?', 'forum.php?do=deletar:".$row['id']."', 'javascript: fecharexplic()');\" title=\"Deletar Resposta\" id=\"vermelho\">x</a>";}
				
            $page .= "<tr><td width=\"25%\" style=\"background-color:#FFF1C7; vertical-align:top;\"><span class=\"small\"><b>$cor<a $link href=\"javascript: opcaochar('".$row["author"]."');\">".$row["author"]."</a>$cor2$linkdeletar</b><br />".prettyforumdate($row["postdate"])."</td><td style=\"background-color:#FFF1C7; vertical-align:top;\">".nl2br($row["content"])."</td></tr>\n";
            $count = 1;
        }
    }
    $page .= "</table></td></tr></table><br />";
    $page .= "<table width=\"100%\"><tr><td><b>Responder esse T&oacute;pico:</b><br /><form action=\"forum.php?do=reply\" method=\"post\"><input type=\"hidden\" name=\"parent\" value=\"$id\" /><input type=\"hidden\" name=\"title\" value=\"Re: ".$row2["title"]."\" /><textarea name=\"content\" rows=\"7\" cols=\"40\"></textarea><br /><input type=\"submit\" name=\"submit\" value=\"Enviar\" /> <input type=\"reset\" name=\"reset\" value=\"Apagar\" /></form></td></tr></table>";
    
    display($page, "Forum");
    
}

function reply() {

    global $userrow;
	extract($_POST);
	$query = doquery("INSERT INTO {{table}} SET id='',postdate=NOW(),newpostdate=NOW(),author='".$userrow["charname"]."',parent='$parent',replies='0',title='$title',content='$content'", "forum");
	$query2 = doquery("UPDATE {{table}} SET newpostdate=NOW(),replies=replies+1 WHERE id='$parent' LIMIT 1", "forum");
	header("Location: forum.php?do=thread:$parent:0");
	die();
	
}

function newthread() {

    global $userrow;
    
    if (isset($_POST["submit"])) {
        extract($_POST);
		if (strlen($title) > 50){header("Location: forum.php?conteudo=O título do tópico é grande demais.");die();}
		if (strlen($title) > 2000){header("Location: forum.php?conteudo=A mensagem do seu tópico é grande demais.");die();}
		
        $query = doquery("INSERT INTO {{table}} SET id='',postdate=NOW(),newpostdate=NOW(),author='".$userrow["charname"]."',parent='0',replies='0',title='$title',content='$content',categoria='$categoria'", "forum");
        header("Location: forum.php");
        die();
    }
    
    $page = "<center><table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/title_forum.gif\" /></center></td></tr></table></center><table width=\"100%\"><tr><td><table width=\"100%\" style=\"margins:0px;border: 1px solid black;\" cellspacing=\"0\" cellpadding=\"0\"><tr><td style=\"background-color:#452202;\"><font color=\"white\"><center>Menu</center></font></td></tr><tr><td colspan=\"2\" style=\"background-color:#E4D094;border:1px #000000 solid\"><b><a href=\"forum.php\">In&iacute;cio</a> <img src=\"images/seta.gif\"> Criando Novo T&oacute;pico</b></td></tr></table><br><form action=\"forum.php?do=new\" method=\"post\">T&iacute;tulo do T&oacute;pico:<br /><input type=\"text\" name=\"title\" size=\"50\" maxlength=\"50\" /><br /><br />
Categoria:	<br>
<select name=\"categoria\" style=\"width:170px\">
<option value=\"geral\">Sem Categoria</option>
<option value=\"duvidas\">D&uacute;vidas</option>
<option value=\"tutoriais\">Tutoriais</option>
<option value=\"bate-papo\">Bate-Papo</option>
<option value=\"reclamacoes\">Reclama&ccedil;&otilde;es</option>
</select>
<br><br>
Mensagem:<br />
	<textarea name=\"content\" rows=\"5\" cols=\"40\"></textarea><br /><br /><input type=\"submit\" name=\"submit\" value=\"Enviar\" /> <input type=\"reset\" name=\"reset\" value=\"Apagar\" /></form></td></tr></table>";
    display($page, "Forum");
    
}


function deletar($id) {

    global $userrow;
	if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
	die();}
    
    if (($userrow['authlevel'] == 1) || ($userpara["acesso"] >= 3)){
		
		$chatquery = doquery("DELETE FROM {{table}} WHERE id='".$id."' LIMIT 1", "forum");
		$chatquery = doquery("DELETE FROM {{table}} WHERE parent='".$id."'", "forum");
		header("Location: forum.php?conteudo=Deletado com sucesso.");
        die();		
		}else{
			header("Location: forum.php?conteudo=Você não tem permissão pra isso.");
		}
		
}




function msg() {
			global $userrow;
			
			$updatequery = doquery("UPDATE {{table}} SET mainmsg='4' WHERE charname='".$userrow['charname']."' LIMIT 1","users");

			header("Location: forum.php");
	
		
}
	
?>