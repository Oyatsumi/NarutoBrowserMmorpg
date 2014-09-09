<?

include('config.php');
include('lib.php');
$link = opendb();
$prefix = $dbsettings["prefix"];

// Thanks to Predrag Supurovic from php.net for this function!
function dobatch ($p_query) {
  $query_split = preg_split ("/[;]+/", $p_query);
  foreach ($query_split as $command_line) {
   $command_line = trim($command_line);
   if ($command_line != '') {
     $query_result = mysql_query($command_line);
     if ($query_result == 0) {
       break;
     };
   };
  };
  return $query_result;
}

if (isset($_POST["submit"])) {
    
$control = $prefix . "_control";
$users = $prefix . "_users";
$query = <<<END
DROP TABLE IF EXISTS `$control`;
CREATE TABLE `$control` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `gamename` varchar(50) NOT NULL default '',
  `gamesize` smallint(5) unsigned NOT NULL default '0',
  `gameopen` tinyint(3) unsigned NOT NULL default '0',
  `gameurl` varchar(200) NOT NULL default '',
  `adminemail` varchar(100) NOT NULL default '',
  `forumtype` tinyint(3) unsigned NOT NULL default '0',
  `forumaddress` varchar(200) NOT NULL default '',
  `class1name` varchar(50) NOT NULL default '',
  `class2name` varchar(50) NOT NULL default '',
  `class3name` varchar(50) NOT NULL default '',
  `diff1name` varchar(50) NOT NULL default '',
  `diff1mod` float unsigned NOT NULL default '0',
  `diff2name` varchar(50) NOT NULL default '',
  `diff2mod` float unsigned NOT NULL default '0',
  `diff3name` varchar(50) NOT NULL default '',
  `diff3mod` float unsigned NOT NULL default '0',
  `compression` tinyint(3) unsigned NOT NULL default '0',
  `verifyemail` tinyint(3) unsigned NOT NULL default '0',
  `shownews` tinyint(3) unsigned NOT NULL default '0',
  `showbabble` tinyint(3) unsigned NOT NULL default '0',
  `showonline` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
END;
if (dobatch($query) == 1) { echo "Control table upgraded.<br />"; } else { echo "Error upgrading Control table."; }
unset($query);

$query = <<<END
INSERT INTO `$control` VALUES (1, 'Dragon Knight', 250, 0, '', '', 1, '', 'Mage', 'Warrior', 'Paladin', 'Easy', '1', 'Medium', '1.2', 'Hard', '1.5', 1, 1, 1, 1, 1);
END;
if (dobatch($query) == 1) { echo "Control table populated.<br />"; } else { echo "Error populating Control table."; }
unset($query);

$query = mysql_query("SELECT * FROM $users ORDER BY id") or die(mysql_error());
$errors = 0; $errorlist = "";
while ($row = mysql_fetch_array($query)) {
    $id = $row["id"];
    $oldspells = explode(",",$row["spells"]);
    $newspells = "0,";
    $oldtowns = explode(",",$row["towns"]);
    $newtowns = "0,";
    foreach($oldspells as $a => $b) {
        if ($b == 1) { $newspells .= "$a,"; }
    }
    $newspells = rtrim($newspells,",");
    foreach($oldtowns as $c => $d) {
        if ($d == 1) { $newtowns .= "$c,"; }
    }
    $newtowns = rtrim($newtowns,",");
    $update = mysql_query("UPDATE $users SET spells='$newspells',towns='$newtowns',verify='1' WHERE id='$id' LIMIT 1");
    if ($update == false) { $errors++; $errorlist .= mysql_error() . "<br />"; } else { echo "User $id upgraded.<br />"; }
}
if ($errors != 0) {
    echo "<br /><b><span style=\"color:red\">The following errors occurred while upgrading the users list:</span></b><br />$errorlist";
} else {
    echo "<br /><b>The upgrade completed successfully. Please log in to the game and visit the control panel to update your main game settings.<br /><br />You should also delete this file from your Dragon Knight directory for security reasons.</b>";
}
    
} else {
    
    echo "Click the button below to run the upgrade script.<br /><form action=\"upgrade_to_110.php\" method=\"post\"><input type=\"submit\" name=\"submit\" value=\"Upgrade\" /></form>";
    die();
    
}

?>