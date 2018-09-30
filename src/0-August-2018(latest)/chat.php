<?php // bp.

include('lib.php');
$link = opendb();

include('cookies.php');
$userrow = checkcookies();



if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "chatmap") { chatmap(); }
	}
	
	
//<form action=\"jutsudebusca.php?do=usar\" method=\"post\">	
function chatmap(){
	
	global $userrow;
	
	
	if (isset($_POST["submit"])) {
        extract($_POST);
		
		if ($fala == ""){header('Location: index.php'); die();}
		
		$chatquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow['latitude']."' AND longitude='".$userrow['longitude']."' order by id", "chatmap");
		$i = 0;
		while ($chatrow = mysqli_fetch_array($chatquery)){
			$i += 1;
			if (($chatrow['id'] < $menorid) || ($menorid == "")){$menorid = $chatrow['id'];}
		}
		
		if ($i < 10){
			$chatquery = doquery("INSERT INTO {{table}} (name, fala, latitude, longitude) VALUES ('".$userrow['charname']."','$fala', '".$userrow['latitude']."', '".$userrow['longitude']."')", "chatmap");
		}else{
			$chatquery = doquery("DELETE FROM {{table}} WHERE id='".$menorid."' LIMIT 1", "chatmap");
			$chatquery = doquery("INSERT INTO {{table}} (name, fala, latitude, longitude) VALUES ('".$userrow['charname']."','$fala', '".$userrow['latitude']."', '".$userrow['longitude']."')", "chatmap");						
		}
		
	}//fim isset submit.
	
	header('Location: index.php'); die();
	
}
	
	

?>