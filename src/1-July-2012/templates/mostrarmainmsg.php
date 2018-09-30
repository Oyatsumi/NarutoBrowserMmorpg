<?php
global $mainmsg, $userrow;
$explodirmsg = explode(",,",$userrow['mainmsg']);

//Justu de busca, formulário de preenchimento do nome.
if ($explodirmsg[0] == 1){
					

					if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
					die(); }
							
			if ($userrow["jutsudebuscahtml"] == 0) {header("Location: ./index.php?conteudo=Você não pode usar esse jutsu sem ter treinado!");die();}
							
				$nomebotao = "botaobusca";
				$mainmsg = "<center><form action=\"mainmsg.php?do2=usarjutsubusca\" method=\"post\">
			<br>Qual jogador voc&ecirc; quer procurar? <br><img src=\"images/seta.gif\">Custo (<font color=\"darkblue\">CH: 30</font>).<br><br>
			Nome do Jogador:<br> <input type=\"text\" name=\"nomedaprocura\">
			<input type=\"submit\" id=\"$nomebotao\" name=\"submit\" value=\"OK\" style=\"width:28px\">";
				$nomeimagem = "byakugan";
				$titulojanela = "ATIVANDO O JUTSU DE BUSCA";
						
		
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
//Enviar pm.
elseif ($explodirmsg[0] == 2){
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		
	$nomebotao = "botaomsg3";
    $mainmsg = "<center><form action=\"mainmsg.php?do2=enviarpm\" method=\"post\">
Nome do Jogador:<br>
<input type=\"text\" size=\"20\" name=\"nomedaprocura\" value=\"".$explodirmsg[1]."\"><br><br>
Mensagem para o Jogador:<br><input name=\"txtmensagem\" type=\"text\" maxlength=\"100\" style=\"width:150px\">
<input type=\"submit\" name=\"submit\" id=\"$nomebotao\" value=\"OK\" style=\"width:28px\"><br>
<font color=brown>Voc&ecirc; s&oacute; pode enviar mensagens com at&eacute; 100 caracteres.</font>
</form></center>";
		$nomeimagem = "naruto";
		$titulojanela = "ENVIAR MENSAGEM PRIVADA";
						
		
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
//Senjutsu mensagem.
elseif ($explodirmsg[0] == 3){
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		
    $mainmsg = "Voc&ecirc; ativou o senjutsu e como consequ&ecirc;ncia obteve:<br><br><img src=\"images/seta.gif\">+20% de Poder de Ataque<br><img src=\"images/seta.gif\">+20% de Poder de Defesa<br><br>Com dura&ccedil;&atilde;o at&eacute; o fim de seus <font color=\"darkgreen\">NP(Natural Points)</font>.<br><br>Ao ativar o senjutsu você gastará 1 <font color=\"darkgreen\">NP</font> à cada 3 segundos, o tempo continua contando mesmo que você não atualize sua página de jogo.";
		$nomeimagem = "senjutsu";
		$titulojanela = "você ativou o Senjutsu";
						
		
}
	
	
	
	
	
	
	
	
	
	
	
//Mensagem do fórum.
elseif ($explodirmsg[0] == 4){
	
		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		
    $mainmsg = "Ao utilizar o fórum você está sob as condições de regra do mesmo. Evite postar coisas sem sentido ou sem conteúdo, evite os posts iguais e o flood. Aqui seu tópico pode ser apagado sem nenhum aviso prévio. Você pode e deve postar qualquer tipo de tópico que tenha como sentido e conteúdo o próprio rpg. Muito obrigado pela sua compreenção.";
		$nomeimagem = "sasuke";
		$titulojanela = "usando o fórum in-game";
						
		
}




	
	
	
	

elseif($explodirmsg[0] == "None"){
	
	$mainmsg = "";
	
	}

else{
	$variavelaux = explode("#$%;",$userrow['mainmsg']);
	$nomeimagem = $variavelaux[0];
	$titulojanela = $variavelaux[1];
	$mainmsg = $variavelaux[2];	
}
	
	
	
	
		
	
	

if (($userrow['mainmsg'] != "None")  && ($userrow != false)){$updatequery = doquery("UPDATE {{table}} SET mainmsg='None' WHERE charname='".$userrow['charname']."' LIMIT 1","users");
$titulojanela = utf8_decode($titulojanela);
$mainmsg = utf8_decode($mainmsg);
$titulojanela = strtoupper($titulojanela);
$mainmsg = "<table width=\"330\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" background=\"layoutnovo/dropmenu/grande/meio.gif\"><tr><td background=\"layoutnovo/dropmenu/grande/cima.gif\" height=\"28\" style=\"background-repeat:no-repeat;;background-position:left top\"><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr><td width=\"262\"><div style=\"position: relative; padding-left:6px;\"><font size=\"+1\" face=\"impact\">$titulojanela</font></div></td><td width=\"*\"><img src=\"layoutnovo/dropmenu/grande/arrastar.gif\" border=\"0\"><a href=\"javascript: fechargrande()\" title=\"Fechar Janela\"><img src=\"layoutnovo/dropmenu/grande/fechar.jpg\" border=\"0\"></a></td></tr></table></td></tr><tr><td background=\"layoutnovo/dropmenu/grande/meio.gif\"><div style=\"padding-left:10px; padding-right:10px;\"><img src=\"layoutnovo/dropmenu/grande/avatares/$nomeimagem.jpg\" align=\"left\"><center>$mainmsg</center></div></td></tr><tr><td height=\"5\"></td></tr><tr><td background=\"layoutnovo/dropmenu/grande/fim.gif\" height=\"6\"></td></tr></table>";
}
?>