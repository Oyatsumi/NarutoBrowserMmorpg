
<style type="text/css">
body {background-color:#000; font-family:Verdana, Geneva, sans-serif; font-size:10px}
a.pag:link {color: #c4911c; text-decoration: none}
a.pag:visited {color: #c4911c; text-decoration: none}
a.pag:hover {color: #c4911c; text-decoration: underline; 
color: #c4911c;;
}
a.pag:active {text-decoration: none}
</style>



<?php 

echo "<table width=\"200\"><tr><td>
<div id=\"menu\">
<center><a class=\"pag\" href=\"javascript: aumentar('menu');\"><img src=\"images/os/menu.jpg\" border=\"0\"></a></center>
</div>


<div id=\"menuconteudo\">
</div>

<br>";




echo "
<div id=\"minhaconta\">
<center><a class=\"pag\" href=\"javascript: aumentar('minhaconta');\"><img src=\"images/os/minhaconta.jpg\" border=\"0\"></a></center>
</div>


<div id=\"minhacontaconteudo\">
</div>

<br>";





echo "
<div id=\"ajuda\">
<center><a class=\"pag\" href=\"javascript: aumentar('ajuda');\"><img src=\"images/os/ajuda.jpg\" border=\"0\"></a></center>
</div>

<div id=\"ajudaconteudo\">
</div>

</td></tr></table>";

?>




<script language="javascript">
function aumentar(nome){
		document.getElementById(nome).innerHTML = "<center><a class=\"pag\" href=\"javascript: fechar('" + nome + "');\"><img src=\"images/os/" + nome + "2.jpg\" border=\"0\"></center>";
		if (nome == 'menu'){ 
			var conteudo = "<center><a class=\"pag\" href=\"index.php\">Principal</a></center>";
		
		}
		
		document.getElementById(nome+'conteudo').innerHTML = "<table><tr><td>" + conteudo + "<center><img src=\"images/os/baixo.jpg\"></center></td></tr></table>";
		
	
		
}

function fechar(nome){
		document.getElementById(nome).innerHTML = "<center><a class=\"pag\" href=\"javascript: aumentar('" + nome + "');\"><img src=\"images/os/" + nome + ".jpg\" border=\"0\"></center>";
		
		document.getElementById(nome+'conteudo').innerHTML = "";
		
	
		
}

</script>