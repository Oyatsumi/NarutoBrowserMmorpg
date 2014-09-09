<?php
$template = <<<THEVERYENDOFYOU
<head>
<title>shoutbox</title>
<style type="text/css">
body {
  background-image: url(images/background.jpg);
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
a {
    color: #663300;
    text-decoration: none;
    font-weight: bold;
}
a:hover {
    color: #330000;
}
</style>
</head>
<body onload="window.scrollTo(0,99999)">
{{content}}
</body>
</html>
THEVERYENDOFYOU;
?>