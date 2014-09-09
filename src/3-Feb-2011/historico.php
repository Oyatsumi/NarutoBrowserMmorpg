<?php 

/*include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();

echo "<style type=\"text/css\">";
echo "a {
    color: #663300;
    text-decoration: none;
    font-weight: bold;
	cursor: url(kunai.cur);
}
a:hover {
    color: #330000;
	</style>";
echo "<center><body background=\"layoutnovo/menumeio/meio2.png\">";*/
$fimh .= "<table border=\"0\" width=\"140\"><tr><td><font face=\"verdana\" size=\"1\">";
$fimh .= "<center><img src=\"images/repartir.jpg\"></center>";

if ($userrow["historico"] != "None"){
$historico = explode(";;",$userrow["historico"]);
$quantos = count($historico) - 1;
//deletar ultimo
while ($quantos > 4){
	
	for ($i = 1; $i <= $quantos; $i++){
		if ($i == $quantos){
		$historico2 .= $historico[$i];
		}else{
		$historico2 .= $historico[$i].";;";
		}
	}
	 $updatequery = doquery("UPDATE {{table}} SET historico='".$historico2."' WHERE id='".$userrow["id"]."' LIMIT 1", "users");
	 $userrow["historico"] = $historico2;
	 $historico = explode(";;",$userrow["historico"]);	
	 $quantos = count($historico) - 1;
}
for ($i = $quantos; $i > -1; $i--){
	if ($i == 0){$fimh .= $historico[$i];}
	else{$fimh .= $historico[$i]."<center><img src=\"images/repartir.jpg\"></center>";}
}
$fimh .= "</font></td></tr></table>";
}else{
$fimh .= "Não há mensagens no histórico.";
}

$fimh .= "</center>";

echo $fim;

?>