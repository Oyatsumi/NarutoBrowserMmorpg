<?php
$template = <<<THEVERYENDOFYOU
<head>
<title>{{title}}</title>
<style type="text/css">
body {
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
</head>
<body><center>
<table width="90%"><tr>
<td width="150" style="border-right: solid 1px black;">
<b><u>DK Administration</u></b><br /><br />
<b>Links:</b><br />
<a href="admin.php">Admin Home</a><br />
<a href="index.php">Game Home</a><br /><br />
<b>Primary Data:</b><br />
<a href="admin.php?do=main">Main Settings</a><br />
<a href="admin.php?do=news">Add News Post</a><br />
<a href="admin.php?do=users">Edit Users</a><br /><br />
<b>Game Data:</b><br />
<a href="admin.php?do=items">Edit Items</a><br />
<a href="admin.php?do=drops">Edit Drops</a><br />
<a href="admin.php?do=towns">Edit Towns</a><br />
<a href="admin.php?do=monsters">Edit Monsters</a><br />
<a href="admin.php?do=levels">Edit Levels</a><br />
<a href="admin.php?do=spells">Edit Spells</a><br />
</td><td>
{{content}}
</td></tr></table>
<br />
<table class="copyright" width="90%"><tr>
<td width="25%" align="center">Powered by <a href="http://nigeru.com" target="_new">Nigeru Animes</a></td><td width="25%" align="center">&copy; 2010 by Oyatsumi</td><td width="25%" align="center">{{totaltime}} Seconds, {{numqueries}} Queries</td><td width="25%" align="center">Version {{version}} {{build}}</td>
</center></body>
</html>
THEVERYENDOFYOU;
?>