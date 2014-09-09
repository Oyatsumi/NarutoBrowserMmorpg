<?php
$template = <<<THEVERYENDOFYOU
<head>
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
td {
  border-style: none;
  padding: 3px;
  vertical-align: top;
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
winpops=window.open(popurl,"","width=210,height=500,scrollbars")
}
function openmappopup(){
var popurl="index.php?do=showmap"
winpops=window.open(popurl,"","width=520,height=520,scrollbars")
}
</script>
</head>
<body><center>
<table cellspacing="0" width="90%"><tr>
<td class="top" colspan="3">
  <table width="100%"><tr><td><img src="images/logo.gif" alt="{{dkgamename}}" title="{{dkgamename}}" border="0" /></td><td style="text-align:right; vertical-align:middle;">{{topnav}}</td></tr></table>
</td>
</tr><tr>
<td class="left">{{leftnav}}</td>
<td class="middle">{{content}}</td>
<td class="right">{{rightnav}}</td>
</tr>
</table><br />
<table class="copyright" width="90%"><tr>
<td width="25%" align="center">Powered by <a href="http://nigeru.com" target="_new">Nigeru Animes</a></td><td width="25%" align="center">&copy; 2010 by Oyatsumi</td><td width="25%" align="center">{{totaltime}} Seconds, {{numqueries}} Queries</td><td width="25%" align="center">Version {{version}} {{build}}</td>
</tr></table>
</center></body>
</html>
THEVERYENDOFYOU;
?>