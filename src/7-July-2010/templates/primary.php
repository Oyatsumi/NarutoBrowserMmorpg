<?php
$template = <<<THEVERYENDOFYOU

<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>  
<title>{{title}}</title>
<style type="text/css">
body {
  background-image: url(images/background.jpg);
  color: black;
  font: 11px verdana;
}
table {
  border-style: none;
  padding: 0px;
  font: 11px verdana;
  }
input {
border:1px #000000 solid;
background-color:#FBF5DB;
 }
 select {
border:1px #000000 solid;
background-color:#FBF5DB;
 }
td {
  border-style: none;
  padding: 3px;
  vertical-align: top;
  text-align: left;
}
td.top {
  border-bottom: solid 2px black;
}
td.left {
  width: 180px;
  border-right: solid 2px black;

  }
  

td.right {
  width: 180px;
  border-left: solid 2px black;
}
a {
    color: #663300;
    text-decoration: none;
    font-weight: bold;
}
a:hover {
    color: #330000;
}
.small {
  font: 10px verdana;
}
.highlight {
  color: red;
}
.light {
  color: #999999;
}
.title {
  border: solid 1px black;
  background-color: #eeeeee;
  font-weight: bold;
  padding: 5px;
  margin: 3px;
}
.copyright {
  border: solid 1px black;
  background-color: #eeeeee;
  font: 10px verdana;
}
</style>
<script>
function opencharpopup(){
var popurl="index.php?do=showchar"
winpops=window.open(popurl,"","width=250,height=500,scrollbars")
}
function openmappopup(){
var popurl="index.php?do=showmap"
winpops=window.open(popurl,"","width=520,height=520,scrollbars")
}
function openchatpopup(){
var popurl="http://nigeru.com/narutorpg/chat/index.html"
winpops=window.open(popurl,"","width=600,height=550,scrollbars")
}
</script>
</head>
<body><center>
<table width="1100"><tr>
<td width="215"><br /><br /><br /><br /><br />{{leftnav}}</td>
<td>

<table><tr><td height="220"><center><img src="layoutnovo/titulo.jpg" /></center></td></tr><tr><td>

<table border="0" cellspacing="0" cellpadding="0" background="layoutnovo/menumeio/meio.png"><tr>
<td colspan="3" background="layoutnovo/menumeio/cima.png" width="671" height="68"></td>
</tr>
<tr background="layoutnovo/menumeio/meio.png">
<td width="65"></td>
<td>
{{content}}
<br /><br />
<center><table width="90%"><tr>
<td width="25%" align="center">Powered by <a href="http://nigeru.com" target="_new">Nigeru Animes</a></td><td width="25%" align="center">&copy; 2010 by Oyatsumi</td><td width="25%" align="center">{{totaltime}} Segundos, {{numqueries}} Queries</td><td width="25%" align="center">Versão {{version}} {{build}}</td>
</tr></table></center>
</td>
<td width="36"></td>
</tr>
<tr>
<td colspan="3" background="layoutnovo/menumeio/baixo.png" width="671" height="68"></td></tr>
</table>

</td></tr></table>


</td>
<td width="225"><br /><br /><br /><br /><br />{{rightnav}}</td>
</tr></table>













</center></body>
</html>
THEVERYENDOFYOU;
?>