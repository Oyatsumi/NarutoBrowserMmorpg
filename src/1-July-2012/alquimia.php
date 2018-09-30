<?php // users.php :: Handles user account functions.


/*$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysql_fetch_array($controlquery);*/



include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();


//não pode se graduar
$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
if (mysql_num_rows($townquery) == 0) { display("Há um erro com sua conta, ou com os dados da cidade. Por favor tente novamente.","Error"); die();}
$townrow = mysql_fetch_array($townquery);
if (($townrow['id'] != 2) && ($townrow['id'] != 5)){header('Location: index.php?conteudo=Você não pode usar a alquimia aqui, uma tentativa de trapaça foi detectada!');die();}		

	

if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "fundir") { fundir(); }
	}

function fundir() {
$frase = $_GET['frase'];
global $topvar;
$topvar = true;
    /* testando se está logado */
		//include('cookies.php');
		//$userrow = checkcookies();
		global $userrow;

		if ($userrow == false) { display("Por favor faça o <a href=\"login.php?do=login\">log in</a> no jogo antes de executar essa ação.","Erro",false,false,false);
		die(); }
		if ($userrow["currentaction"] != "In Town") {if ($userrow["currentaction"] == "Fighting"){header('Location: ./index.php?do=fight&conteudo=Você só pode acessar essa função dentro de uma cidade!');die();}else{header('Location: ./index.php?conteudo=Você só pode acessar essa função dentro de uma cidade!');die();} }
					if ($userrow["currentaction"] == "Fighting") {header('Location: ./index.php?do=fight&conteudo=Você não pode acessar essa função no meio de uma batalha!');die(); }
					
					if ($userrow["batalha_timer2"] == 5) {global $topvar;
$topvar = true; display("Você não pode fazer nenhum movimento enquanto estiver em um duelo. Clique <a href=\"users.php?do=resetarduelo\">aqui</a>, para resetar seu Duelo atual. ","Erro",false,false,false);die(); }

		
		$usuariologadonome = $userrow["charname"];
		
		
		
		
	//oque vai aparecer na página:
	$tabelabapagina = "<form action=\"alquimia.php?do=fundir\" method=\"post\"><table>
	<tr bgcolor=\"#452202\"><td colspan=3><font color=white>Mochila</font></td></tr>
	<tr bgcolor=\"#613003\"><td><font color=white>*</font></td><td><font color=white>*</font></td><td><font color=white>Nome</font></td></tr>";
	for ($j = 1;$j < 5; $j++){
	//fundo da tabela
	$fundo = $j % 2;
	if ($fundo == 0) {$bgcolor = "#E4D094";}else{$bgcolor = "#FFF1C7";}
	//fim fundo tabela
	$mochilaseparar = explode(",",$userrow["bp".$j]);
	include('funcoesinclusas.php');
	iconeitemmochila($mochilaseparar, $img, $dur);
	$tabelabapagina .= "<tr bgcolor=\"$bgcolor\"><td><input type=\"checkbox\" name=\"bpnome".$j."\" value =\"".$userrow["bp".$j]."\"></td><td><img src=\"images/$img.gif\" title=\"Durabilidade: ".$dur."\"/></td><td>".$mochilaseparar[0]."</td></tr>";
	}
	$tabelabapagina .="<tr><td colspan=\"3\"><center><input type=\"submit\" name=\"submit\" value=\"Fundir Itens\" /> </center></td></tr></table></form>";	
	//fim do que vai aparecer na pagina
	

	
    if (isset($_POST["submit"])) {
        extract($_POST);
		
		
		if (($userrow['bp1'] != $bpnome1) && ($bpnome1 != '')){header("Location: ./alquimia.php?do=fundir&frase=Tentativa de trapaça detectada.");die();}
		if (($userrow['bp2'] != $bpnome2) && ($bpnome2 != '')){header("Location: ./alquimia.php?do=fundir&frase=Tentativa de trapaça detectada.");die();}
		if (($userrow['bp3'] != $bpnome3) && ($bpnome3 != '')){header("Location: ./alquimia.php?do=fundir&frase=Tentativa de trapaça detectada.");die();}
        if (($userrow['bp4'] != $bpnome4) && ($bpnome4 != '')){header("Location: ./alquimia.php?do=fundir&frase=Tentativa de trapaça detectada.");die();}
		
		
		//saber quantos itens foram selecionados para fundir.
		$qantos = 0;
		if (($bpnome1 != "") && ($bpnome1 != 'None')){$qantos += 1;$novoarray1 = explode(",", $bpnome1); $bpnome1 = $novoarray1[0];if ($novoarray1[2] <= 3) {$comp1 = "layoutnovo/equipamentos/".$novoarray1[1].".gif";}else{$comp1 = "layoutnovo/equipamentos/drops/".$novoarray1[1].".gif";}}
		if (($bpnome2 != "") && ($bpnome2 != 'None')){$qantos += 1;$novoarray2 = explode(",", $bpnome2); $bpnome2 = $novoarray2[0];if ($novoarray2[2] <= 3) {$comp2 = "layoutnovo/equipamentos/".$novoarray2[1].".gif";}else{$comp2 = "layoutnovo/equipamentos/drops/".$novoarray2[1].".gif";}}
		if (($bpnome3 != "")&& ($bpnome3 != 'None')){$qantos += 1;$novoarray3 = explode(",", $bpnome3); $bpnome3 = $novoarray3[0];if ($novoarray3[2] <= 3) {$comp3 = "layoutnovo/equipamentos/".$novoarray3[1].".gif";}else{$comp3 = "layoutnovo/equipamentos/drops/".$novoarray3[1].".gif";}}
		if (($bpnome4 != "") && ($bpnome4 != 'None')){$qantos += 1;$novoarray4 = explode(",", $bpnome4); $bpnome4 = $novoarray4[0];if ($novoarray4[2] <= 3) {$comp4 = "layoutnovo/equipamentos/".$novoarray4[1].".gif";}else{$comp4 = "layoutnovo/equipamentos/drops/".$novoarray4[1].".gif";}}
		//fim
		
		
		
		$usuariologadonome = $userrow["charname"];
		
		
if ($qantos == 2){//se forem dois itens que estão sendo fundidos...		

				
				// ESPADAS RAIGA
				   //item1 
				$certo = 0;    
			  if ($bpnome1 == "Espadas de Rai"){$numerobancop = 1; $certo += 1;}
			  elseif ($bpnome2 == "Espadas de Rai"){$numerobancop = 2;$certo += 1;}
			  elseif ($bpnome3 == "Espadas de Rai"){$numerobancop = 3;$certo += 1;}
			  elseif ($bpnome4 == "Espadas de Rai"){$numerobancop = 4;$certo += 1;}

			  //item 2
			  if ($bpnome1 == "Memória de Konoha"){$numerobancos = 1;$certo += 1;}
			  elseif ($bpnome2== "Memória de Konoha"){$numerobancos = 2;$certo += 1;}
			  elseif ($bpnome3 == "Memória de Konoha"){$numerobancos = 3;$certo += 1;}
			  elseif ($bpnome4 == "Memória de Konoha"){$numerobancos = 4;$certo += 1;}

			  
					if($certo == 2) {
				
				   //retirando o item 2 da backpack
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancos='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$resultado = "Espadas Raiga,34,1,X";
				//colocando o item da fusão
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancop='$resultado' WHERE charname='$usuariologadonome' LIMIT 1","users");


				//mostrar a resposta
				$oqueaconteceu = "Parabéns! Sua fusão ocorreu com sucesso! Você fundiu Espadas de Rai e Memória de Konoha e obteve Espadas Raiga!";
				}
				
			

						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						// PROTETOR DA AREIA
				   //item1     
				   $certo = 0;  
			  if ($bpnome1 == "Protetor Branco"){$numerobancop = 1;$certo += 1;}
			  elseif ($bpnome2 == "Protetor Branco"){$numerobancop = 2;$certo += 1;}
			  elseif ($bpnome3 == "Protetor Branco"){$numerobancop = 3;$certo += 1;}
			  elseif ($bpnome4 == "Protetor Branco"){$numerobancop = 4;$certo += 1;}
	
				  //item 2
			  if ($bpnome1 == "Alma da Areia"){$numerobancos = 1;$certo += 1;}
			  elseif ($bpnome2 == "Alma da Areia"){$numerobancos = 2;$certo += 1;}
			  elseif ($bpnome3 == "Alma da Areia"){$numerobancos = 3;$certo += 1;}
			  elseif ($bpnome4 == "Alma da Areia"){$numerobancos = 4;$certo += 1;}
			
			  
				if($certo == 2) {
					
									
				   //retirando o item 2 da backpack
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancos='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$resultado = "Protetor da Areia,36,3,X";
				//colocando o item da fusão
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancop='$resultado' WHERE charname='$usuariologadonome' LIMIT 1","users");
			
				//mostrar a resposta
				$oqueaconteceu = "Parabéns! Sua fusão ocorreu com sucesso! Você fundiu Protetor Branco e Alma da Areia e obteve Protetor da Areia!";
				
				}
				
			
			
						
						
						
						
						
						
						
						
						
						
						
						// PROTETOR DA nÉVOA
						$certo = 0; 
				   //item1     
			  if ($bpnome1  == "Protetor Branco"){$numerobancop = 1;$certo += 1;}
			  elseif ($bpnome2  == "Protetor Branco"){$numerobancop = 2;$certo += 1;}
			  elseif ($bpnome3  == "Protetor Branco"){$numerobancop = 3;$certo += 1;}
			  elseif ($bpnome4  == "Protetor Branco"){$numerobancop = 4;$certo += 1;}

			  //item 2
			   if ($bpnome1 == "Alma da Névoa"){$numerobancos = 1;$certo += 1;}
			  elseif ($bpnome2 == "Alma da Névoa"){$numerobancos = 2;$certo += 1;}
			  elseif ($bpnome3 == "Alma da Névoa"){$numerobancos = 3;$certo += 1;}
			  elseif ($bpnome4 == "Alma da Névoa"){$numerobancos = 4;$certo += 1;}
		
			  
					if($certo == 2) {
						
							   //retirando o item 2 da backpack
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancos='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$resultado = "Protetor da Névoa,37,3,X";
				//colocando o item da fusão
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancop='$resultado' WHERE charname='$usuariologadonome' LIMIT 1","users");
				
				//mostrar a resposta
				$oqueaconteceu = "Parabéns! Sua fusão ocorreu com sucesso! Você fundiu Protetor Branco e Alma da Névoa e obteve Protetor da Névoa!";
				}
				
			
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
								// PROTETOR Do SOM
								$certo = 0; 
				   //item1     
			  if ($bpnome1 == "Protetor Branco"){$numerobancop = 1;$certo += 1;}
			  elseif ($bpnome2 == "Protetor Branco"){$numerobancop = 2;$certo += 1;}
			  elseif ($bpnome3 == "Protetor Branco"){$numerobancop = 3;$certo += 1;}
			  elseif ($bpnome4 == "Protetor Branco"){$numerobancop = 4;$certo += 1;}

			  //item 2
			   if ($bpnome1 == "Alma do Som"){$numerobancos = 1;$certo += 1;}
			  elseif ($bpnome2 == "Alma do Som"){$numerobancos = 2;$certo += 1;}
			  elseif ($bpnome3 == "Alma do Som"){$numerobancos = 3;$certo += 1;}
			  elseif ($bpnome4 == "Alma do Som"){$numerobancos = 4;$certo += 1;}

			  
					if($certo == 2) {
						
						 //retirando o item 2 da backpack
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancos='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$resultado = "Protetor do Som,38,3,X";
				//colocando o item da fusão
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancop='$resultado' WHERE charname='$usuariologadonome' LIMIT 1","users");
				
				//mostrar a resposta
				$oqueaconteceu = "Parabéns! Sua fusão ocorreu com sucesso! Você fundiu Protetor Branco e Alma do Som e obteve Protetor do Som!";
				}
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
							// MASCARA OININ
							$certo = 0;  
				   //item1     
			  if ($bpnome1 == "Máscara ANBU"){$numerobancop = 1;$certo += 1;}
			  elseif ($bpnome2 == "Máscara ANBU"){$numerobancop = 2;$certo += 1;}
			 elseif ($bpnome3 == "Máscara ANBU"){$numerobancop = 3;$certo += 1;}
			  elseif ($bpnome4 == "Máscara ANBU"){$numerobancop = 4;$certo += 1;}

			  //item 2
			   if ($banco1 == "Chakra da Brisa"){$numerobancos = 1;$certo += 1;}
			  elseif ($banco2 == "Chakra da Brisa"){$numerobancos = 2;$certo += 1;}
			  elseif ($banco3 == "Chakra da Brisa"){$numerobancos = 3;$certo += 1;}
			  elseif ($banco4 == "Chakra da Brisa"){$numerobancos = 4;$certo += 1;}

			  
					if($certo == 2) {
						
							   //retirando o item 2 da backpack
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancos='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$resultado = "Máscara Oinin,41,3,X";
				//colocando o item da fusão
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancop='$resultado' WHERE charname='$usuariologadonome' LIMIT 1","users");
				
				 
				//mostrar a resposta
				$oqueaconteceu = "Parabéns! Sua fusão ocorreu com sucesso! Você fundiu Máscara ANBU e Chakra da Brisa e obteve Máscara Oinin!";
				}
				
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 //BLUSA ESPIRITO DA NEVOA
					 $certo = 0;
				   //item1     
			  if ($bpnome1 == "Blusa Simples"){$numerobancop = 1;$certo += 1;}
			  elseif ($bpnome2 == "Blusa Simples"){$numerobancop = 2;$certo += 1;}
			  elseif ($bpnome3 == "Blusa Simples"){$numerobancop = 3;$certo += 1;}
			  elseif ($bpnome4 == "Blusa Simples"){$numerobancop = 4;$certo += 1;}

			  //item 2
			  if ($banco1 == "Espírito da Névoa"){$numerobancos = 1;$certo += 1;}
			  elseif ($banco2 == "Espírito da Névoa"){$numerobancos = 2;$certo += 1;}
			  elseif ($banco3 == "Espírito da Névoa"){$numerobancos = 3;$certo += 1;}
			  elseif ($banco4 == "Espírito da Névoa"){$numerobancos = 4;$certo += 1;}

			  
					if($certo == 2) {
						
						   //retirando o item 2 da backpack
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancos='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$resultado = "Blusa Espírito da Névoa,42,2,X";
				//colocando o item da fusão
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancop='$resultado' WHERE charname='$usuariologadonome' LIMIT 1","users");
				
				//mostrar a resposta
				$oqueaconteceu = "Parabéns! Sua fusão ocorreu com sucesso! Você fundiu Blusa Simples e Espírito da Névoa e obteve Blusa Espírito da Névoa!";
				}
				
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
						 //PROTETOR DE HONRA DE KONOHA
						 $certo = 0; 
				   //item1     
			  if ($bpnome1 == "Protetor de Konoha"){$numerobancop = 1;$certo += 1;}
			  elseif ($bpnome2 == "Protetor de Konoha"){$numerobancop = 2;$certo += 1;}
			  elseif ($bpnome3 == "Protetor de Konoha"){$numerobancop = 3;$certo += 1;}
			  elseif ($bpnome4 == "Protetor de Konoha"){$numerobancop = 4;$certo += 1;}
			  
			  //item 2
			   if ($bpnome1 == "Destreza Súbita"){$numerobancos = 1;$certo += 1;}
			  elseif ($bpnome2 == "Destreza Súbita"){$numerobancos = 2;$certo += 1;}
			  elseif ($bpnome3 == "Destreza Súbita"){$numerobancos = 3;$certo += 1;}
			  elseif ($bpnome4 == "Destreza Súbita"){$numerobancos = 4;$certo += 1;}

			  
					if($certo == 2) {
						
						  //retirando o item 2 da backpack
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancos='None' WHERE charname='$usuariologadonome' LIMIT 1","users");
				$resultado = "Protetor de Honra de Konoha,43,3,X";
				//colocando o item da fusão
				$updatequery = doquery("UPDATE {{table}} SET bp$numerobancop='$resultado' WHERE charname='$usuariologadonome' LIMIT 1","users");
						
						
				//mostrar a resposta
				$oqueaconteceu = "Parabéns! Sua fusão ocorreu com sucesso! Você fundiu Protetor de Konoha e Destreza Súbita e obteve Protetor de Honra de Konoha!";
				}
					 
					 
					 
					 
					 
					 
					 
}//fim dos itens que são 2... $qantos = 2...
					 
					 
					 
						
				
				
				
				
				
			if ($oqueaconteceu == "") {$oqueaconteceu = "Infelizmente você não pode fundir esse(s) iten(s)...";}
        $frase = $oqueaconteceu;
		//header("Location: ./alquimia.php?do=fundir&frase=".$oqueaconteceu);die(); 
    }
	include('funcoesinclusas.php');
	if ($frase != "") {$frase = "<center><font color=brown>".$frase."</font></center><br>";}
	if ($resultado == ''){$resultado = 'images/alquimiagif.gif';}else{$arrayresultado = explode(",",$resultado); $agorajava = conteudoexplic($arrayresultado[1], $arrayresultado[2], 'idatr', '*');if ($arrayresultado[2] <= 3){$resultado = "layoutnovo/equipamentos/".$arrayresultado[1].".gif";}else{$resultado = "layoutnovo/equipamentos/drops/".$arrayresultado[1].".gif";}}
	if (($comp1 == '') || ($comp1 == 'None')){$comp1 = 'images/bpalquimia.jpg';}
	if (($comp2 == '') || ($comp2 == 'None')){$comp2 = 'images/bpalquimia.jpg';}
	if (($comp3 == '')|| ($comp3 == 'None')){$comp3 = 'images/bpalquimia.jpg';}
	if (($comp4 == '')|| ($comp4 == 'None')){$comp4 = 'images/bpalquimia.jpg';}
	if ($novoarray1 != ''){$agorajava1 = conteudoexplic($novoarray1[1], $novoarray1[2], 'idatr', '*');}
	if ($novoarray2 != ''){$agorajava2 = conteudoexplic($novoarray2[1], $novoarray2[2], 'idatr', '*');}
	if ($novoarray3 != ''){$agorajava3 = conteudoexplic($novoarray3[1], $novoarray3[2], 'idatr', '*');}
	if ($novoarray4 != ''){$agorajava4 = conteudoexplic($novoarray4[1], $novoarray4[2], 'idatr', '*');}
	$page = "<table width=\"100%\"><tr><td width=\"100%\" align=\"center\"><center><img src=\"images/alquimia.gif\" /></center></td></tr></table>
$frase	

<center>Selecione os itens que você gostaria de fundir:<table>
<tr><td><center><div style=\"background: url(images/alquimiafundo.jpg); width:180px; heigth:650px\"><br><br><img src=\"$resultado\" style=\"border:2px #e4d094 solid;\" id=\"idatr\" onmouseover=\"$agorajava\" onmouseout=\"fecharexplic();\"><br><br><img src=\"$comp1\" style=\"border:2px #e4d094 solid;\" onmouseover=\"$agorajava1\" onmouseout=\"fecharexplic();\"><img onmouseover=\"$agorajava2\" onmouseout=\"fecharexplic();\" src=\"$comp2\" style=\"border:2px #e4d094 solid;\"><img onmouseover=\"$agorajava3\" onmouseout=\"fecharexplic();\" src=\"$comp3\" style=\"border:2px #e4d094 solid;\"><img onmouseover=\"$agorajava4\" onmouseout=\"fecharexplic();\" src=\"$comp4\" style=\"border:2px #e4d094 solid;\"><br><br><br><br></div></center></td>

<td>
<center>$tabelabapagina</center>
</td></tr>


</table></center>

";

    display($page, "Alquimia", false, false, false); 
    
}

?>