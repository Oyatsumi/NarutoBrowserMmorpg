<?PHP		
global $userrow;	
global $arvore;
			//novo quadrante arvores
			$arvore[1]["i"] = 5;
			$arvore[1]["j"] = 2;
			$arvore[2]["i"] = 3;
			$arvore[2]["j"] = 4;
			$arvore[3]["i"] = 1;
			$arvore[3]["j"] = 3;
			$arvore[4]["i"] = 3;
			$arvore[4]["j"] = 1;
			$arvore[5]["i"] = 4;
			$arvore[5]["j"] = 5;
			$arvore[6]["i"] = 2;
			$arvore[6]["j"] = 5;

if (!function_exists('resetar')){
function resetar(){
	global $arvore;
			$arvore[1]["i"] = 5;
			$arvore[1]["j"] = 2;
			$arvore[2]["i"] = 3;
			$arvore[2]["j"] = 4;
			$arvore[3]["i"] = 1;
			$arvore[3]["j"] = 3;
			$arvore[4]["i"] = 3;
			$arvore[4]["j"] = 1;
			$arvore[5]["i"] = 4;
			$arvore[5]["j"] = 5;
			$arvore[6]["i"] = 2;
			$arvore[6]["j"] = 5;
}
			
function arvore(){
	global $arvore;
		
				$arvore[1]["i"] += 1; if ($arvore[1]["i"] > 5){$arvore[1]["i"] = 1;}
				$arvore[1]["j"] += 1; if ($arvore[1]["j"] > 5){$arvore[1]["j"] = 1;}
				$arvore[2]["i"] += 1; if ($arvore[2]["i"] > 5){$arvore[2]["i"] = 1;}
				$arvore[2]["j"] += 1; if ($arvore[2]["j"] > 5){$arvore[2]["j"] = 1;}
				$arvore[3]["i"] += 1; if ($arvore[3]["i"] > 5){$arvore[3]["i"] = 1;}
				$arvore[3]["j"] += 1; if ($arvore[3]["j"] > 5){$arvore[3]["j"] = 1;}	
				$arvore[4]["i"] += 1; if ($arvore[4]["i"] > 5){$arvore[4]["i"] = 1;}
				$arvore[4]["j"] += 1; if ($arvore[4]["j"] > 5){$arvore[4]["j"] = 1;}
				$arvore[5]["i"] += 1; if ($arvore[5]["i"] > 5){$arvore[5]["i"] = 1;}
				$arvore[5]["j"] += 1; if ($arvore[5]["j"] > 5){$arvore[5]["j"] = 1;}
				$arvore[6]["i"] += 1; if ($arvore[6]["i"] > 5){$arvore[6]["i"] = 1;}
				$arvore[6]["j"] += 1; if ($arvore[6]["j"] > 5){$arvore[6]["j"] = 1;}
}}
			$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
			$controlrow = mysql_fetch_array($controlquery);
			$tamanhomapa = $controlrow["gamesize"];
			
			for ($g = -5; $g <=5; $g += 5){
				for ($t = -5; $t <=5; $t += 5){
					resetar();
					$latitude = $userrow["latitude"] + $g;
					$longitude =  $userrow["longitude"] + $t;
					$proxquadi= $latitude % 5; $proxquadi = $latitude -($proxquadi - 1);
					$proxquadj = $longitude % 5; $proxquadj = $longitude -($proxquadj - 1);
					$quantosquadrantespassaram = (($tamanhomapa*2/5) * (($proxquadi - 1)/5)) + (($proxquadj - 1)/5) + ($tamanhomapa*2/5);
					$divisao = $quantosquadrantespassaram % 5;
					for ($u = 1; $u <= $divisao; $u++){
						
						arvore();
						
					}
					$matriz[($proxquadi - 1) + $arvore[1]["i"]][($proxquadj - 1) + $arvore[1]["j"]]["imagem"] = "images/24/arvore_random".$arvore[1]["i"].".gif";
					$matriz[($proxquadi - 1) + $arvore[2]["i"]][($proxquadj - 1) + $arvore[2]["j"]]["imagem"] = "images/24/arvore_random".$arvore[2]["i"].".gif";
					$matriz[($proxquadi - 1) + $arvore[3]["i"]][($proxquadj - 1) + $arvore[3]["j"]]["imagem"] = "images/24/arvore_random".$arvore[3]["i"].".gif";
					$matriz[($proxquadi - 1) + $arvore[4]["i"]][($proxquadj - 1) + $arvore[4]["j"]]["imagem"] = "images/24/arvore_random".$arvore[4]["i"].".gif";
					$matriz[($proxquadi - 1) + $arvore[5]["i"]][($proxquadj - 1) + $arvore[5]["j"]]["imagem"] = "images/24/arvore_radom".$arvore[5]["i"].".gif";
					$matriz[($proxquadi - 1) + $arvore[6]["i"]][($proxquadj - 1) + $arvore[6]["j"]]["imagem"] = "images/24/arvore_random".$arvore[6]["i"].".gif";
				}
			}
			//fim quadrante arvores



for ($i = ($userrow["latitude"] + 2); $i >= ($userrow["latitude"] - 2); $i--){
	for ($j = ($userrow["longitude"] - 2); $j <= ($userrow["longitude"] + 2); $j++){
			//coordenadas
			if ($j > 0) {$jcoord = $j."E";}
			if ($i > 0) {$icoord = $i."N";}
			if ($j < 0) {$jcoord = $j * -1; $jcoord .= "W";}
			if ($i < 0) {$icoord = $i * -1; $icoord .= "S";}
			if ($i == 0) {$icoord = $i."N";}
			if ($j == 0) {$jcoord = $j."E";}
			//fim coordenadas
			/*//inicio quadrantes árvores
			$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
			$controlrow = mysql_fetch_array($controlquery);
			$tamanhomapa = $controlrow["mapsize"];
			$proxquadi= $i % 5; $proxquadi = $i -($proxquadi - 1);
			$proxquadj = $j % 5; $proxquadj = $j -($proxquadj - 1);
			$quantosquadrantespassaram = (($tamanhomapa*2/5) * ((($proxquadi - 1)/5) - 1)) + (($proxquadj - 1)/5) + ($tamanhomapa*2/5);
			$arvore[1]["i"] = 5;
			$arvore[1]["j"] = 2;
			$arvore[2]["i"] = 4;
			$arvore[2]["j"] = 3;
			$arvore[3]["i"] = 1;
			$arvore[3]["j"] = 2;
			
			for ($h = 0; $h <= ($quantosquadrantespassaram + 1); $h++){
				$arvore[1]["i"] += 1; if ($arvore[1]["i"] > 5){$arvore[1]["i"] = 1;};
				$arvore[1]["j"] += 1; if ($arvore[1]["j"] > 5){$arvore[1]["j"] = 1;};
				$arvore[2]["i"] += 1; if ($arvore[2]["i"] > 5){$arvore[2]["i"] = 1;};
				$arvore[2]["j"] += 1; if ($arvore[2]["j"] > 5){$arvore[2]["j"] = 1;};
				$arvore[3]["i"] += 1; if ($arvore[3]["i"] > 5){$arvore[3]["i"] = 1;};
				$arvore[3]["j"] += 1; if ($arvore[3]["j"] > 5){$arvore[3]["j"] = 1;};
				if ($h = ($quantosquadrantespassaram - 1)){
						$matriz[(($proxquadi - 1) + $arvore[1]["i"])][(($proxquadj - 1) + $arvore[1]["j"]) - 5]["imagem"] = "images/24/arvore.gif";
						$matriz[(($proxquadi - 1) + $arvore[2]["i"])][(($proxquadj - 1) + $arvore[2]["j"]) - 5]["imagem"] = "images/24/arvore.gif";
						$matriz[(($proxquadi - 1) + $arvore[3]["i"])][(($proxquadj - 1) + $arvore[3]["j"]) - 5]["imagem"] = "images/24/arvore.gif";
				}
				elseif ($h = ($quantosquadrantespassaram)){
						$matriz[(($proxquadi - 1) + $arvore[1]["i"])][(($proxquadj - 1) + $arvore[1]["j"])]["imagem"] = "images/24/arvore.gif";
						$matriz[(($proxquadi - 1) + $arvore[2]["i"])][(($proxquadj - 1) + $arvore[2]["j"])]["imagem"] = "images/24/arvore.gif";
						$matriz[(($proxquadi - 1) + $arvore[3]["i"])][(($proxquadj - 1) + $arvore[3]["j"])]["imagem"] = "images/24/arvore.gif";
				}
				elseif ($h = ($quantosquadrantespassaram + 1)){
						$matriz[(($proxquadi - 1) + $arvore[1]["i"])][(($proxquadj - 1) + $arvore[1]["j"]) + 5]["imagem"] = "images/24/arvore.gif";
						$matriz[(($proxquadi - 1) + $arvore[2]["i"])][(($proxquadj - 1) + $arvore[2]["j"]) + 5]["imagem"] = "images/24/arvore.gif";
						$matriz[(($proxquadi - 1) + $arvore[3]["i"])][(($proxquadj - 1) + $arvore[3]["j"]) + 5]["imagem"] = "images/24/arvore.gif";
				}
			}
			//fim quadrante árvorese...*/
			

			
			$numjogadores = 0;
			$townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$i."' AND longitude='".$j."' LIMIT 1", "towns");
			if (mysql_num_rows($townquery) == 0) {
				if ($matriz[$i][$j]["imagem"] == ""){
					$matriz[$i][$j]["imagem"] = "images/24/gif24.gif";
				}
				$matriz[$i][$j]["title"] = "";
			}else{
				$townrow = mysql_fetch_array($townquery);
				$matriz[$i][$j]["imagem"] = "images/24/cidade".$townrow["id"].".gif";
				$matriz[$i][$j]["title"] = "[".$icoord.",".$jcoord."]".$townrow["name"];
			}
			//jogadores no mesmo mapa:
			$paraquery = doquery("SELECT * FROM {{table}} WHERE longitude='".$j."' AND latitude='".$i."' AND UNIX_TIMESTAMP(onlinetime) >= '".(time()-600)."' ORDER BY level DESC", "users");
			$numjogadores = 0;
			$true = false;
			while ($usuariorow = mysql_fetch_array($paraquery)) {
					$true = false;
					$numjogadores += 1;
					//variavel auxiliar pro icone das arvores
					$varaux = explode("_",$matriz[$i][$j]["imagem"]);
					if ((($matriz[$i][$j]["imagem"] == "images/24/gif24.gif") || ($varaux[0] == "images/24/arvore")) && ($numjogadores > 1)){
							$matriz[$i][$j]["imagem"] = "images/24/acampamento.gif";	
							$matriz[$i][$j]["title"] = "";						
					}elseif (($numjogadores == 1) && (($matriz[$i][$j]["imagem"] == "images/24/gif24.gif") || ($varaux[0] == "images/24/arvore"))){
					$matriz[$i][$j]["imagem"] = "layoutnovo/graficos/".$usuariorow["avatar"]."_minimap.gif";
					$matriz[$i][$j]["title"] = "[".$icoord.",".$jcoord."]".$usuariorow["charname"].".";
					$true = true;
					}
			 }
	
			if ($matriz[$i][$j]["title"] == ""){$matriz[$i][$j]["title"] .= "[".$icoord.",".$jcoord."]";}elseif (($true != true) && ($numjogadores != 1)) {
				$matriz[$i][$j]["title"] .= " - ".$numjogadores." Jogador(es) no Mapa.";}
		
	}//fim for
}//fim for
//fim contas e acertamento dos vetores.







//html para o minimap.
$minimap = "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" background=\"images/background_minimapa.jpg\" style=\"border:1px #000000 solid\">";
for ($i = ($userrow["latitude"] + 2); $i >= ($userrow["latitude"] - 2); $i--){
	$minimap .= "<tr>";
	for ($j = ($userrow["longitude"] - 2); $j <= ($userrow["longitude"] + 2); $j++){
		$minimap .= "<td style=\"border:1px #ffefb7 solid\"><img src=\"".$matriz[$i][$j]["imagem"]."\" title=\"".$matriz[$i][$j]["title"]."\" border=\"0\"></td>";
	}
	$minimap .= "</tr>";
}
$minimap .= "</table>";

?>