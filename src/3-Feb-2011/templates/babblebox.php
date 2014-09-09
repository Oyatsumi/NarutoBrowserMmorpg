<?php
$template = <<<THEVERYENDOFYOU
<head>
<title>Chat Global</title>
<style type="text/css" media="screen">@import "novobotao.css";</style>
<style type="text/css">
body {
  background-image: url(layoutnovo/menumeio/meio2.png);
  color: black;
  font: 11px verdana;
  margins: 0px;
  padding: 0px;
}
div {
    padding: 2px;
    border: solid 1px black;
    margin: 2px;
    text-align: left;
}
.buttons{
	 border: solid 0px black;
}
a {
    color: #663300;
    text-decoration: none;
    font-weight: bold;
}
a:hover {
    color: #330000;
}
</style>
<script>
function mostrarchar(nome){
var popurl="mostrarchar.php?nomechar=" + nome;
winpops=window.open(popurl,"","width=250,height=500,scrollbars")
}
</script>
</head>
<body onload="window.scrollTo(0,99999)">
{{content}}
</body>
</html>
THEVERYENDOFYOU;
?>