<?php // admin.php :: primary administration script.

include('lib.php');
include('cookies.php');
$link = opendb();
$userrow = checkcookies();
if ($userrow == false) { die("Por favor fa�a o <a href=\"login.php?do=login\">log in</a> antes de usar o Painel de Controle."); }
if ($userrow["authlevel"] != 1) { die("Voc� precisa ter privil�gios de administrador para acessar isso."); }
if ($userrow["charname"] != "Oyatsumi") { die("Voc� n�o pode acessar isso."); }
$controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
$controlrow = mysqli_fetch_array($controlquery);

if (isset($_GET["do"])) {
    $do = explode(":",$_GET["do"]);
    
    if ($do[0] == "main") { main(); }
    elseif ($do[0] == "items") { items(); }
    elseif ($do[0] == "edititem") { edititem($do[1]); }
    elseif ($do[0] == "drops") { drops(); }
    elseif ($do[0] == "editdrop") { editdrop($do[1]); }
    elseif ($do[0] == "towns") { towns(); }
    elseif ($do[0] == "edittown") { edittown($do[1]); }
    elseif ($do[0] == "monsters") { monsters(); }
    elseif ($do[0] == "editmonster") { editmonster($do[1]); }
    elseif ($do[0] == "levels") { levels(); }
    elseif ($do[0] == "editlevel") { editlevel(); }
    elseif ($do[0] == "spells") { spells(); }
    elseif ($do[0] == "editspell") { editspell($do[1]); }
    elseif ($do[0] == "users") { users(); }
    elseif ($do[0] == "edituser") { edituser($do[1]); }
    elseif ($do[0] == "news") { addnews(); }
    
} else { donothing(); }

function donothing() {
    
    $page = "Welcome to the Administration section. Use the links on the left bar to control and edit various elements of the game.<br /><br />Please note that the control panel has been created mostly as a shortcut for certain individual settings. It is meant for use primarily with editing one thing at a time. If you need to completely replace an entire table (say, to replace all stock monsters with your own new ones), it is suggested that you use a more in-depth database tool such as <a href=\"http://www.phpmyadmin.net\" target=\"_new\">phpMyAdmin</a>. Also, you may want to have a copy of the Dragon Knight development kit, available from the <a href=\"http://dragon.se7enet.com/dev.php\">Dragon Knight homepage</a>.<br /><br />Also, you should be aware that certain portions of the DK code are dependent on the formatting of certain database results (for example, the special attributes on item drops). While I have attempted to point these out throughout the admin script, you should definitely pay attention and be careful when editing some fields, because mistakes in the database content may result in script errors or your game breaking completely.";
    admindisplay($page, "Admin Home");
    
}

function main() {
    
    if (isset($_POST["submit"])) {
        extract($_POST);
        $errors = 0;
        $errorlist = "";
        if ($gamename == "") { $errors++; $errorlist .= "Game name is required.<br />"; }
        if (($gamesize % 5) != 0) { $errors++; $errorlist .= "Map size must be divisible by five.<br />"; }
        if (!is_numeric($gamesize)) { $errors++; $errorlist .= "Map size must be a number.<br />"; }
        if ($forumtype == 2 && $forumaddress == "") { $errors++; $errorlist .= "You must specify a forum address when using the External setting.<br />"; }
        if ($class1name == "") { $errors++; $errorlist .= "Class 1 name is required.<br />"; }
        if ($class2name == "") { $errors++; $errorlist .= "Class 2 name is required.<br />"; }
        if ($class3name == "") { $errors++; $errorlist .= "Class 3 name is required.<br />"; }
        if ($diff1name == "") { $errors++; $errorlist .= "Difficulty 1 name is required.<br />"; }
        if ($diff2name == "") { $errors++; $errorlist .= "Difficulty 2 name is required.<br />"; }
        if ($diff3name == "") { $errors++; $errorlist .= "Difficulty 3 name is required.<br />"; }
        if ($diff2mod == "") { $errors++; $errorlist .= "Difficulty 2 value is required.<br />"; }
        if ($diff3mod == "") { $errors++; $errorlist .= "Difficulty 3 value is required.<br />"; }
        
        if ($errors == 0) { 
            $query = doquery("UPDATE {{table}} SET gamename='$gamename',gamesize='$gamesize',forumtype='$forumtype',forumaddress='$forumaddress',compression='$compression',class1name='$class1name',class2name='$class2name',class3name='$class3name',diff1name='$diff1name',diff2name='$diff2name',diff3name='$diff3name',diff2mod='$diff2mod',diff3mod='$diff3mod',gameopen='$gameopen',verifyemail='$verifyemail',gameurl='$gameurl',adminemail='$adminemail',shownews='$shownews',showonline='$showonline',showbabble='$showbabble' WHERE id='1' LIMIT 1", "control");
            admindisplay("Settings updated.","Main Settings");
        } else {
            admindisplay("<b>Errors:</b><br /><div style=\"color:red;\">$errorlist</div><br />Please go back and try again.", "Main Settings");
        }
    }
    
    global $controlrow;
    
$page = <<<END
<b><u>Main Settings</u></b><br />
These options control several major settings for the overall game engine.<br /><br />
<form action="admin.php?do=main" method="post">
<table width="90%">
<tr><td width="20%"><span class="highlight">Game Open:</span></td><td><select name="gameopen"><option value="1" {{open1select}}>Open</option><option value="0" {{open0select}}>Closed</option></select><br /><span class="small">Close the game if you are upgrading or working on settings and don't want to cause odd errors for end-users. Closing the game will completely halt all activity.</span></td></tr>
<tr><td width="20%">Game Name:</td><td><input type="text" name="gamename" size="30" maxlength="50" value="{{gamename}}" /><br /><span class="small">Default is "Dragon Knight". Change this if you want to change to call your game something different.</span></td></tr>
<tr><td width="20%">Game URL:</td><td><input type="text" name="gameurl" size="50" maxlength="100" value="{{gameurl}}" /><br /><span class="small">Please specify the full URL to your game installation ("http://www.server.com/dkpath/index.php").  This gets used in the registration email sent to users. If you leave this field blank or incorrect, users may not be able to register correctly.</span></td></tr>
<tr><td width="20%">Admin Email:</td><td><input type="text" name="adminemail" size="30" maxlength="100" value="{{adminemail}}" /><br /><span class="small">Please specify your email address. This gets used when the game has to send an email to users.</span></td></tr>
<tr><td width="20%">Map Size:</td><td><input type="text" name="gamesize" size="3" maxlength="3" value="{{gamesize}}" /><br /><span class="small">Default is 250. This is the size of each map quadrant. Note that monster levels increase every 5 spaces, so you should ensure that you have at least (map size / 5) monster levels total, otherwise there will be parts of the map without any monsters, or some monsters won't ever get used. Ex: with a map size of 250, you should have 50 monster levels total.</span></td></tr>
<tr><td width="20%">Forum Type:</td><td><select name="forumtype"><option value="0" {{selecttype0}}>Disabled</option><option value="1" {{selecttype1}}>Internal</option><option value="2" {{selecttype2}}>External</option></select><br /><span class="small">'Disabled' removes the forum link. 'Internal' uses the built-in (and very stripped-down) forum program included with Dragon Knight, if you don't have your own forums software already installed. 'External' uses the address provided below and links to your own forums software.</span></td></tr>
<tr><td width="20%">External Forum:</td><td><input type="text" name="forumaddress" size="30" maxlength="200" value="{{forumaddress}}" /><br /><span class="small">If the above value is set to 'External,' please specify the complete URL to your forums here.</span></td></tr>
<tr><td width="20%">Page Compression:</td><td><select name="compression"><option value="0" {{selectcomp0}}>Disabled</option><option value="1" {{selectcomp1}}>Enabled</option></select><br /><span class="small">Enable page compression if it is supported by your server, and this will greatly reduce the amount of bandwidth required by the game.</span></td></tr>
<tr><td width="20%">Email Verification:</td><td><select name="verifyemail"><option value="0" {{selectverify0}}>Disabled</option><option value="1" {{selectverify1}}>Enabled</option></select><br /><span class="small">Make users verify their email address for added security.</span></td></tr>
<tr><td width="20%">Show News:</td><td><select name="shownews"><option value="0" {{selectnews0}}>No</option><option value="1" {{selectnews1}}>Yes</option></select><br /><span class="small">Toggle display of the Latest News box in towns.</td></tr>
<tr><td width="20%">Show Who's Online:</td><td><select name="showonline"><option value="0" {{selectonline0}}>No</option><option value="1" {{selectonline1}}>Yes</option></select><br /><span class="small">Toggle display of the Who's Online box in towns.</span></td></tr>
<tr><td width="20%">Show Babblebox:</td><td><select name="showbabble"><option value="0" {{selectbabble0}}>No</option><option value="1" {{selectbabble1}}>Yes</option></select><br /><span class="small">Toggle display of the Babble Box in towns.</span></td></tr>
<tr><td width="20%">Class 1 Name:</td><td><input type="text" name="class1name" size="20" maxlength="50" value="{{class1name}}" /><br /></td></tr>
<tr><td width="20%">Class 2 Name:</td><td><input type="text" name="class2name" size="20" maxlength="50" value="{{class2name}}" /><br /></td></tr>
<tr><td width="20%">Class 3 Name:</td><td><input type="text" name="class3name" size="20" maxlength="50" value="{{class3name}}" /><br /></td></tr>
<tr><td width="20%">Difficulty 1 Name:</td><td><input type="text" name="diff1name" size="20" maxlength="50" value="{{diff1name}}" /><br /></td></tr>
<tr><td width="20%">Difficulty 2 Name:</td><td><input type="text" name="diff2name" size="20" maxlength="50" value="{{diff2name}}" /><br /></td></tr>
<tr><td width="20%">Difficulty 2 Value:</td><td><input type="text" name="diff2mod" size="3" maxlength="3" value="{{diff2mod}}" /><br /><span class="small">Default is 1.2. Specify factoral value for medium difficulty here.</span></td></tr>
<tr><td width="20%">Difficulty 3 Name:</td><td><input type="text" name="diff3name" size="20" maxlength="50" value="{{diff3name}}" /><br /></td></tr>
<tr><td width="20%">Difficulty 3 Value:</td><td><input type="text" name="diff3mod" size="3" maxlength="3" value="{{diff3mod}}" /><br /><span class="small">Default is 1.5. Specify factoral value for hard difficulty here.</span></td></tr>
</table>
<input type="submit" name="submit" value="Submit" /> <input type="reset" name="reset" value="Reset" />
</form>
END;

    if ($controlrow["forumtype"] == 0) { $controlrow["selecttype0"] = "selected=\"selected\" "; } else { $controlrow["selecttype0"] = ""; }
    if ($controlrow["forumtype"] == 1) { $controlrow["selecttype1"] = "selected=\"selected\" "; } else { $controlrow["selecttype1"] = ""; }
    if ($controlrow["forumtype"] == 2) { $controlrow["selecttype2"] = "selected=\"selected\" "; } else { $controlrow["selecttype2"] = ""; }
    if ($controlrow["compression"] == 0) { $controlrow["selectcomp0"] = "selected=\"selected\" "; } else { $controlrow["selectcomp0"] = ""; }
    if ($controlrow["compression"] == 1) { $controlrow["selectcomp1"] = "selected=\"selected\" "; } else { $controlrow["selectcomp1"] = ""; }
    if ($controlrow["verifyemail"] == 0) { $controlrow["selectverify0"] = "selected=\"selected\" "; } else { $controlrow["selectverify0"] = ""; }
    if ($controlrow["verifyemail"] == 1) { $controlrow["selectverify1"] = "selected=\"selected\" "; } else { $controlrow["selectverify1"] = ""; }
    if ($controlrow["shownews"] == 0) { $controlrow["selectnews0"] = "selected=\"selected\" "; } else { $controlrow["selectnews0"] = ""; }
    if ($controlrow["shownews"] == 1) { $controlrow["selectnews1"] = "selected=\"selected\" "; } else { $controlrow["selectnews1"] = ""; }
    if ($controlrow["showonline"] == 0) { $controlrow["selectonline0"] = "selected=\"selected\" "; } else { $controlrow["selectonline0"] = ""; }
    if ($controlrow["showonline"] == 1) { $controlrow["selectonline1"] = "selected=\"selected\" "; } else { $controlrow["selectonline1"] = ""; }
    if ($controlrow["showbabble"] == 0) { $controlrow["selectbabble0"] = "selected=\"selected\" "; } else { $controlrow["selectbabble0"] = ""; }
    if ($controlrow["showbabble"] == 1) { $controlrow["selectbabble1"] = "selected=\"selected\" "; } else { $controlrow["selectbabble1"] = ""; }
    if ($controlrow["gameopen"] == 1) { $controlrow["open1select"] = "selected=\"selected\" "; } else { $controlrow["open1select"] = ""; }
    if ($controlrow["gameopen"] == 0) { $controlrow["open0select"] = "selected=\"selected\" "; } else { $controlrow["open0select"] = ""; }

    $page = parsetemplate($page, $controlrow);
    admindisplay($page, "Main Settings");

}

function items() {
    
    $query = doquery("SELECT id,name FROM {{table}} ORDER BY id", "items");
    $page = "<b><u>Edit Items</u></b><br />Click an item's name to edit it.<br /><br /><table width=\"50%\">\n";
    $count = 1;
    while ($row = mysqli_fetch_array($query)) {
        if ($count == 1) { $page .= "<tr><td width=\"8%\" style=\"background-color: #eeeeee;\">".$row["id"]."</td><td style=\"background-color: #eeeeee;\"><a href=\"admin.php?do=edititem:".$row["id"]."\">".$row["name"]."</a></td></tr>\n"; $count = 2; }
        else { $page .= "<tr><td width=\"8%\" style=\"background-color: #ffffff;\">".$row["id"]."</td><td style=\"background-color: #ffffff;\"><a href=\"admin.php?do=edititem:".$row["id"]."\">".$row["name"]."</a></td></tr>\n"; $count = 1; }
    }
    if (mysqli_num_rows($query) == 0) { $page .= "<tr><td width=\"8%\" style=\"background-color: #eeeeee;\">No items found.</td></tr>\n"; }
    $page .= "</table>";
    admindisplay($page, "Edit Items");
    
}

function edititem($id) {
    
    if (isset($_POST["submit"])) {
        
        extract($_POST);
        $errors = 0;
        $errorlist = "";
        if ($name == "") { $errors++; $errorlist .= "Name is required.<br />"; }
        if ($buycost == "") { $errors++; $errorlist .= "Cost is required.<br />"; }
        if (!is_numeric($buycost)) { $errors++; $errorlist .= "Cost must be a number.<br />"; }
        if ($attribute == "") { $errors++; $errorlist .= "Attribute is required.<br />"; }
        if (!is_numeric($attribute)) { $errors++; $errorlist .= "Attribute must be a number.<br />"; }
        if ($special == "" || $special == " ") { $special = "X"; }
        
        if ($errors == 0) { 
            $query = doquery("UPDATE {{table}} SET name='$name',type='$type',buycost='$buycost',attribute='$attribute',special='$special' WHERE id='$id' LIMIT 1", "items");
            admindisplay("Item updated.","Edit Items");
        } else {
            admindisplay("<b>Errors:</b><br /><div style=\"color:red;\">$errorlist</div><br />Please go back and try again.", "Edit Items");
        }        
        
    }   
        
    
    $query = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "items");
    $row = mysqli_fetch_array($query);

$page = <<<END
<b><u>Edit Items</u></b><br /><br />
<form action="admin.php?do=edititem:$id" method="post">
<table width="90%">
<tr><td width="20%">ID:</td><td>{{id}}</td></tr>
<tr><td width="20%">Name:</td><td><input type="text" name="name" size="30" maxlength="30" value="{{name}}" /></td></tr>
<tr><td width="20%">Type:</td><td><select name="type"><option value="1" {{type1select}}>Weapon</option><option value="2" {{type2select}}>Armor</option><option value="3" {{type3select}}>Shield</option></select></td></tr>
<tr><td width="20%">Cost:</td><td><input type="text" name="buycost" size="5" maxlength="10" value="{{buycost}}" /> gold</td></tr>
<tr><td width="20%">Attribute:</td><td><input type="text" name="attribute" size="5" maxlength="10" value="{{attribute}}" /><br /><span class="small">How much the item adds to total attackpower (weapons) or defensepower (armor/shields).</span></td></tr>
<tr><td width="20%">Special:</td><td><input type="text" name="special" size="30" maxlength="50" value="{{special}}" /><br /><span class="small">Should be either a special code or <span class="highlight">X</span> to disable. Edit this field very carefully because mistakes to formatting or field names can create problems in the game.</span></td></tr>
</table>
<input type="submit" name="submit" value="Submit" /> <input type="reset" name="reset" value="Reset" />
</form>
<b>Special Codes:</b><br />
Special codes can be added in the item's Special field to give it extra user attributes. Special codes are in the format <span class="highlight">attribute,value</span>. <span class="highlight">Attribute</span> can be any database field from the Users table - however, it is suggested that you only use the ones from the list below, otherwise things can get freaky. <span class="highlight">Value</span> may be any positive or negative whole number. For example, if you want a weapon to give an additional 50 max hit points, the special code would be <span class="highlight">maxhp,50</span>.<br /><br />
Suggested user fields for special codes:<br />
maxhp - max hit points<br />
maxmp - max magic points<br />
maxtp - max travel points<br />
goldbonus - gold bonus, in percent<br />
expbonus - experience bonus, in percent<br />
strength - strength (which also adds to attackpower)<br />
dexterity - dexterity (which also adds to defensepower)<br />
attackpower - total attack power<br />
defensepower - total defense power
END;
    
    if ($row["type"] == 1) { $row["type1select"] = "selected=\"selected\" "; } else { $row["type1select"] = ""; }
    if ($row["type"] == 2) { $row["type2select"] = "selected=\"selected\" "; } else { $row["type2select"] = ""; }
    if ($row["type"] == 3) { $row["type3select"] = "selected=\"selected\" "; } else { $row["type3select"] = ""; }
    
    $page = parsetemplate($page, $row);
    admindisplay($page, "Edit Items");
    
}

function drops() {
    
    $query = doquery("SELECT id,name FROM {{table}} ORDER BY id", "drops");
    $page = "<b><u>Edit Drops</u></b><br />Click an item's name to edit it.<br /><br /><table width=\"50%\">\n";
    $count = 1;
    while ($row = mysqli_fetch_array($query)) {
        if ($count == 1) { $page .= "<tr><td width=\"8%\" style=\"background-color: #eeeeee;\">".$row["id"]."</td><td style=\"background-color: #eeeeee;\"><a href=\"admin.php?do=editdrop:".$row["id"]."\">".$row["name"]."</a></td></tr>\n"; $count = 2; }
        else { $page .= "<tr><td width=\"8%\" style=\"background-color: #ffffff;\">".$row["id"]."</td><td style=\"background-color: #ffffff;\"><a href=\"admin.php?do=editdrop:".$row["id"]."\">".$row["name"]."</a></td></tr>\n"; $count = 1; }
    }
    if (mysqli_num_rows($query) == 0) { $page .= "<tr><td width=\"8%\" style=\"background-color: #eeeeee;\">No items found.</td></tr>\n"; }
    $page .= "</table>";
    admindisplay($page, "Edit Drops");
    
}

function editdrop($id) {
    
    if (isset($_POST["submit"])) {
        
        extract($_POST);
        $errors = 0;
        $errorlist = "";
        if ($name == "") { $errors++; $errorlist .= "Name is required.<br />"; }
        if ($mlevel == "") { $errors++; $errorlist .= "Monster level is required.<br />"; }
        if (!is_numeric($mlevel)) { $errors++; $errorlist .= "Monster level must be a number.<br />"; }
        if ($attribute1 == "" || $attribute1 == " " || $attribute1 == "X") { $errors++; $errorlist .= "First attribute is required.<br />"; }
        if ($attribute2 == "" || $attribute2 == " ") { $attribute2 = "X"; }
        
        if ($errors == 0) { 
            $query = doquery("UPDATE {{table}} SET name='$name',mlevel='$mlevel',attribute1='$attribute1',attribute2='$attribute2' WHERE id='$id' LIMIT 1", "drops");
            admindisplay("Item updated.","Edit Drops");
        } else {
            admindisplay("<b>Errors:</b><br /><div style=\"color:red;\">$errorlist</div><br />Please go back and try again.", "Edit Drops");
        }        
        
    }   
        
    
    $query = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "drops");
    $row = mysqli_fetch_array($query);

$page = <<<END
<b><u>Edit Drops</u></b><br /><br />
<form action="admin.php?do=editdrop:$id" method="post">
<table width="90%">
<tr><td width="20%">ID:</td><td>{{id}}</td></tr>
<tr><td width="20%">Name:</td><td><input type="text" name="name" size="30" maxlength="30" value="{{name}}" /></td></tr>
<tr><td width="20%">Monster Level:</td><td><input type="text" name="mlevel" size="5" maxlength="10" value="{{mlevel}}" /><br /><span class="small">Minimum monster level that will drop this item.</span></td></tr>
<tr><td width="20%">Attribute 1:</td><td><input type="text" name="attribute1" size="30" maxlength="50" value="{{attribute1}}" /><br /><span class="small">Must be a special code. First attribute cannot be disabled. Edit this field very carefully because mistakes to formatting or field names can create problems in the game.</span></td></tr>
<tr><td width="20%">Attribute 2:</td><td><input type="text" name="attribute2" size="30" maxlength="50" value="{{attribute2}}" /><br /><span class="small">Should be either a special code or <span class="highlight">X</span> to disable. Edit this field very carefully because mistakes to formatting or field names can create problems in the game.</span></td></tr>
</table>
<input type="submit" name="submit" value="Submit" /> <input type="reset" name="reset" value="Reset" />
</form>
<b>Special Codes:</b><br />
Special codes are used in the two attribute fields to give the item properties. The first attribute field must contain a special code, but the second one may be left empty ("X") if you wish. Special codes are in the format <span class="highlight">attribute,value</span>. <span class="highlight">Attribute</span> can be any database field from the Users table - however, it is suggested that you only use the ones from the list below, otherwise things can get freaky. <span class="highlight">Value</span> may be any positive or negative whole number. For example, if you want a weapon to give an additional 50 max hit points, the special code would be <span class="highlight">maxhp,50</span>.<br /><br />
Suggested user fields for special codes:<br />
maxhp - max hit points<br />
maxmp - max magic points<br />
maxtp - max travel points<br />
goldbonus - gold bonus, in percent<br />
expbonus - experience bonus, in percent<br />
strength - strength (which also adds to attackpower)<br />
dexterity - dexterity (which also adds to defensepower)<br />
attackpower - total attack power<br />
defensepower - total defense power
END;
    
    $page = parsetemplate($page, $row);
    admindisplay($page, "Edit Drops");
    
}

function towns() {
    
    $query = doquery("SELECT id,name FROM {{table}} ORDER BY id", "towns");
    $page = "<b><u>Edit Towns</u></b><br />Click an town's name to edit it.<br /><br /><table width=\"50%\">\n";
    $count = 1;
    while ($row = mysqli_fetch_array($query)) {
        if ($count == 1) { $page .= "<tr><td width=\"8%\" style=\"background-color: #eeeeee;\">".$row["id"]."</td><td style=\"background-color: #eeeeee;\"><a href=\"admin.php?do=edittown:".$row["id"]."\">".$row["name"]."</a></td></tr>\n"; $count = 2; }
        else { $page .= "<tr><td width=\"8%\" style=\"background-color: #ffffff;\">".$row["id"]."</td><td style=\"background-color: #ffffff;\"><a href=\"admin.php?do=edittown:".$row["id"]."\">".$row["name"]."</a></td></tr>\n"; $count = 1; }
    }
    if (mysqli_num_rows($query) == 0) { $page .= "<tr><td width=\"8%\" style=\"background-color: #eeeeee;\">No towns found.</td></tr>\n"; }
    $page .= "</table>";
    admindisplay($page, "Edit Towns");
    
}

function edittown($id) {
    
    if (isset($_POST["submit"])) {
        
        extract($_POST);
        $errors = 0;
        $errorlist = "";
        if ($name == "") { $errors++; $errorlist .= "Name is required.<br />"; }
        if ($latitude == "") { $errors++; $errorlist .= "Latitude is required.<br />"; }
        if (!is_numeric($latitude)) { $errors++; $errorlist .= "Latitude must be a number.<br />"; }
        if ($longitude == "") { $errors++; $errorlist .= "Longitude is required.<br />"; }
        if (!is_numeric($longitude)) { $errors++; $errorlist .= "Longitude must be a number.<br />"; }
        if ($innprice == "") { $errors++; $errorlist .= "Inn Price is required.<br />"; }
        if (!is_numeric($innprice)) { $errors++; $errorlist .= "Inn Price must be a number.<br />"; }
        if ($mapprice == "") { $errors++; $errorlist .= "Map Price is required.<br />"; }
        if (!is_numeric($mapprice)) { $errors++; $errorlist .= "Map Price must be a number.<br />"; }

        if ($travelpoints == "") { $errors++; $errorlist .= "Travel Points is required.<br />"; }
        if (!is_numeric($travelpoints)) { $errors++; $errorlist .= "Travel Points must be a number.<br />"; }
        if ($itemslist == "") { $errors++; $errorlist .= "Items List is required.<br />"; }
        
        if ($errors == 0) { 
            $query = doquery("UPDATE {{table}} SET name='$name',latitude='$latitude',longitude='$longitude',innprice='$innprice',mapprice='$mapprice',travelpoints='$travelpoints',itemslist='$itemslist', kage='$kage' WHERE id='$id' LIMIT 1", "towns");
            admindisplay("Town updated.","Edit Towns");
        } else {
            admindisplay("<b>Errors:</b><br /><div style=\"color:red;\">$errorlist</div><br />Please go back and try again.", "Edit Towns");
        }        
        
    }   
        
    
    $query = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "towns");
    $row = mysqli_fetch_array($query);

$page = <<<END
<b><u>Edit Towns</u></b><br /><br />
<form action="admin.php?do=edittown:$id" method="post">
<table width="90%">
<tr><td width="20%">ID:</td><td>{{id}}</td></tr>
<tr><td width="20%">Name:</td><td><input type="text" name="name" size="30" maxlength="30" value="{{name}}" /></td></tr>
<tr><td width="20%">Nome do Kage:</td><td><input type="text" name="kage" size="30" maxlength="20" value="{{kage}}" /></td></tr>
<tr><td width="20%">Latitude:</td><td><input type="text" name="latitude" size="5" maxlength="10" value="{{latitude}}" /><br /><span class="small">Positive or negative integer.</span></td></tr>
<tr><td width="20%">Longitude:</td><td><input type="text" name="longitude" size="5" maxlength="10" value="{{longitude}}" /><br /><span class="small">Positive or negative integer.</span></td></tr>
<tr><td width="20%">Inn Price:</td><td><input type="text" name="innprice" size="5" maxlength="10" value="{{innprice}}" /> gold</td></tr>
<tr><td width="20%">Map Price:</td><td><input type="text" name="mapprice" size="5" maxlength="10" value="{{mapprice}}" /> gold<br /><span class="small">How much it costs to buy the map to this town.</span></td></tr>
<tr><td width="20%">Travel Points:</td><td><input type="text" name="travelpoints" size="5" maxlength="10" value="{{travelpoints}}" /><br /><span class="small">How many TP are consumed when travelling to this town.</span></td></tr>
<tr><td width="20%">Items List:</td><td><input type="text" name="itemslist" size="30" maxlength="200" value="{{itemslist}}" /><br /><span class="small">Comma-separated list of item ID numbers available for purchase at this town. (Example: <span class="highlight">1,2,3,6,9,10,13,20</span>)</span></td></tr>
</table>
<input type="submit" name="submit" value="Submit" /> <input type="reset" name="reset" value="Reset" />
</form>
END;
    
    $page = parsetemplate($page, $row);
    admindisplay($page, "Edit Towns");
    
}

function monsters() {
    
    global $controlrow;
    
    $statquery = doquery("SELECT * FROM {{table}} ORDER BY level DESC LIMIT 1", "monsters");
    $statrow = mysqli_fetch_array($statquery);
    
    $query = doquery("SELECT id,name FROM {{table}} ORDER BY id", "monsters");
    $page = "<b><u>Edit Monsters</u></b><br />";
    
    if (($controlrow["gamesize"]/5) != $statrow["level"]) {
        $page .= "<span class=\"highlight\">Note:</span> Your highest monster level does not match with your entered map size. Highest monster level should be ".($controlrow["gamesize"]/5).", yours is ".$statrow["level"].". Please fix this before opening the game to the public.<br /><br />";
    } else { $page .= "Monster level and map size match. No further actions are required for map compatibility.<br /><br />"; }
    
    $page .= "Click an monster's name to edit it.<br /><br /><table width=\"50%\">\n";
    $count = 1;
    while ($row = mysqli_fetch_array($query)) {
        if ($count == 1) { $page .= "<tr><td width=\"8%\" style=\"background-color: #eeeeee;\">".$row["id"]."</td><td style=\"background-color: #eeeeee;\"><a href=\"admin.php?do=editmonster:".$row["id"]."\">".$row["name"]."</a></td></tr>\n"; $count = 2; }
        else { $page .= "<tr><td width=\"8%\" style=\"background-color: #ffffff;\">".$row["id"]."</td><td style=\"background-color: #ffffff;\"><a href=\"admin.php?do=editmonster:".$row["id"]."\">".$row["name"]."</a></td></tr>\n"; $count = 1; }
    }
    if (mysqli_num_rows($query) == 0) { $page .= "<tr><td width=\"8%\" style=\"background-color: #eeeeee;\">No towns found.</td></tr>\n"; }
    $page .= "</table>";
    admindisplay($page, "Edit Monster");
    
}

function editmonster($id) {
    
    if (isset($_POST["submit"])) {
        
        extract($_POST);
        $errors = 0;
        $errorlist = "";
        if ($name == "") { $errors++; $errorlist .= "Name is required.<br />"; }
        if ($maxhp == "") { $errors++; $errorlist .= "Max HP is required.<br />"; }
        if (!is_numeric($maxhp)) { $errors++; $errorlist .= "Max HP must be a number.<br />"; }
        if ($maxdam == "") { $errors++; $errorlist .= "Max Damage is required.<br />"; }
        if (!is_numeric($maxdam)) { $errors++; $errorlist .= "Max Damage must be a number.<br />"; }
        if ($armor == "") { $errors++; $errorlist .= "Armor is required.<br />"; }
        if (!is_numeric($armor)) { $errors++; $errorlist .= "Armor must be a number.<br />"; }
        if ($level == "") { $errors++; $errorlist .= "Monster Level is required.<br />"; }
        if (!is_numeric($level)) { $errors++; $errorlist .= "Monster Level must be a number.<br />"; }
        if ($maxexp == "") { $errors++; $errorlist .= "Max Exp is required.<br />"; }
        if (!is_numeric($maxexp)) { $errors++; $errorlist .= "Max Exp must be a number.<br />"; }
        if ($maxgold == "") { $errors++; $errorlist .= "Max Gold is required.<br />"; }
        if (!is_numeric($maxgold)) { $errors++; $errorlist .= "Max Gold must be a number.<br />"; }
        
        if ($errors == 0) { 
            $query = doquery("UPDATE {{table}} SET name='$name',maxhp='$maxhp',maxdam='$maxdam',armor='$armor',level='$level',maxexp='$maxexp',maxgold='$maxgold',immune='$immune',elemento='$elemento' WHERE id='$id' LIMIT 1", "monsters");
            admindisplay("Monster updated.","Edit monsters");
        } else {
            admindisplay("<b>Errors:</b><br /><div style=\"color:red;\">$errorlist</div><br />Please go back and try again.", "Edit monsters");
        }        
        
    }   
        
    
    $query = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "monsters");
    $row = mysqli_fetch_array($query);
	
	if($row['elemento'] == "neutro"){$select1 = "selected";}
	elseif($row['elemento'] == "fogo"){$select2 = "selected";}
	elseif($row['elemento'] == "agua"){$select3 = "selected";}
	elseif($row['elemento'] == "terra"){$select4 = "selected";}
	elseif($row['elemento'] == "vento"){$select5 = "selected";}
	elseif($row['elemento'] == "raio"){$select6 = "selected";}

$page = <<<END
<b><u>Edit Monsters</u></b><br /><br />
<form action="admin.php?do=editmonster:$id" method="post">
<table width="90%">
<tr><td width="20%">ID:</td><td>{{id}}</td></tr>
<tr><td width="20%">Name:</td><td><input type="text" name="name" size="30" maxlength="30" value="{{name}}" /></td></tr>
<tr><td width="20%">Max Hit Points:</td><td><input type="text" name="maxhp" size="5" maxlength="10" value="{{maxhp}}" /></td></tr>
<tr><td width="20%">Max Damage:</td><td><input type="text" name="maxdam" size="5" maxlength="10" value="{{maxdam}}" /><br /><span class="small">Compares to player's attackpower.</span></td></tr>
<tr><td width="20%">Armor:</td><td><input type="text" name="armor" size="5" maxlength="10" value="{{armor}}" /><br /><span class="small">Compares to player's defensepower.</span></td></tr>
<tr><td width="20%">Monster Level:</td><td><input type="text" name="level" size="5" maxlength="10" value="{{level}}" /><br /><span class="small">Determines spawn location and item drops.</span></td></tr>
<tr><td width="20%">Max Experience:</td><td><input type="text" name="maxexp" size="5" maxlength="10" value="{{maxexp}}" /><br /><span class="small">Max experience gained from defeating monster.</span></td></tr>
<tr><td width="20%">Max Gold:</td><td><input type="text" name="maxgold" size="5" maxlength="10" value="{{maxgold}}" /><br /><span class="small">Max gold gained from defeating monster.</span></td></tr>
<tr><td width="20%">Immunity:</td><td><select name="immune"><option value="0" {{immune0select}}>None</option><option value="1" {{immune1select}}>Hurt Spells</option><option value="2" {{immune2select}}>Hurt & Sleep Spells</option></select><br /><span class="small">Some monsters may not be hurt by certain spells.</span></td></tr>
<tr><td width="20%">Elemento:</td><td><select name="elemento"><option $select1 value="neutro">Neutro</option><option $select2 value="fogo">Fogo</option><option $select3 value="agua">�gua</option><option $select4 value="terra">Terra</option><option $select5 value="vento">Vento</option><option $select6 value="raio">Rel�mpago</option></select><br /></td></tr>
</table>
<input type="submit" name="submit" value="Submit" /> <input type="reset" name="reset" value="Reset" />
</form>
END;
    
    if ($row["immune"] == 1) { $row["immune1select"] = "selected=\"selected\" "; } else { $row["immune1select"] = ""; }
    if ($row["immune"] == 2) { $row["immune2select"] = "selected=\"selected\" "; } else { $row["immune2select"] = ""; }
    if ($row["immune"] == 3) { $row["immune3select"] = "selected=\"selected\" "; } else { $row["immune3select"] = ""; }
    
    $page = parsetemplate($page, $row);
    admindisplay($page, "Edit Monsters");
    
}

function spells() {
    
    $query = doquery("SELECT id,name FROM {{table}} ORDER BY id", "spells");
    $page = "<b><u>Edit Spells</u></b><br />Click an spell's name to edit it.<br /><br /><table width=\"50%\">\n";
    $count = 1;
    while ($row = mysqli_fetch_array($query)) {
        if ($count == 1) { $page .= "<tr><td width=\"8%\" style=\"background-color: #eeeeee;\">".$row["id"]."</td><td style=\"background-color: #eeeeee;\"><a href=\"admin.php?do=editspell:".$row["id"]."\">".$row["name"]."</a></td></tr>\n"; $count = 2; }
        else { $page .= "<tr><td width=\"8%\" style=\"background-color: #ffffff;\">".$row["id"]."</td><td style=\"background-color: #ffffff;\"><a href=\"admin.php?do=editspell:".$row["id"]."\">".$row["name"]."</a></td></tr>\n"; $count = 1; }
    }
    if (mysqli_num_rows($query) == 0) { $page .= "<tr><td width=\"8%\" style=\"background-color: #eeeeee;\">No spells found.</td></tr>\n"; }
    $page .= "</table>";
    admindisplay($page, "Edit Spells");
    
}

function editspell($id) {
    
    if (isset($_POST["submit"])) {
        
        extract($_POST);
        $errors = 0;
        $errorlist = "";
        if ($name == "") { $errors++; $errorlist .= "Name is required.<br />"; }
        if ($mp == "") { $errors++; $errorlist .= "MP is required.<br />"; }
        if (!is_numeric($mp)) { $errors++; $errorlist .= "MP must be a number.<br />"; }
        if ($attribute == "") { $errors++; $errorlist .= "Attribute is required.<br />"; }
        if (!is_numeric($attribute)) { $errors++; $errorlist .= "Attribute must be a number.<br />"; }
        
        if ($errors == 0) { 
            $query = doquery("UPDATE {{table}} SET name='$name', elemento='$elemento',mp='$mp',attribute='$attribute',type='$type' WHERE id='$id' LIMIT 1", "spells");
            admindisplay("Spell updated.","Edit Spells");
        } else {
            admindisplay("<b>Errors:</b><br /><div style=\"color:red;\">$errorlist</div><br />Please go back and try again.", "Edit Spells");
        }        
        
    }   
        
    
    $query = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "spells");
    $row = mysqli_fetch_array($query);
	
	if($row['elemento'] == "neutro"){$select1 = "selected";}
	elseif($row['elemento'] == "fogo"){$select2 = "selected";}
	elseif($row['elemento'] == "agua"){$select3 = "selected";}
	elseif($row['elemento'] == "terra"){$select4 = "selected";}
	elseif($row['elemento'] == "vento"){$select5 = "selected";}
	elseif($row['elemento'] == "raio"){$select6 = "selected";}

$page = <<<END
<b><u>Edit Spells</u></b><br /><br />
<form action="admin.php?do=editspell:$id" method="post">
<table width="90%">
<tr><td width="20%">ID:</td><td>{{id}}</td></tr>
<tr><td width="20%">Name:</td><td><input type="text" name="name" size="30" maxlength="30" value="{{name}}" /></td></tr>
<tr><td width="20%">Magic Points:</td><td><input type="text" name="mp" size="5" maxlength="10" value="{{mp}}" /><br /><span class="small">MP required to cast spell.</span></td></tr>
<tr><td width="20%">Attribute:</td><td><input type="text" name="attribute" size="5" maxlength="10" value="{{attribute}}" /><br /><span class="small">Numeric value of the spell's effect. Ties with type, below.</span></td></tr>
<tr><td width="20%">Type:</td><td><select name="type"><option value="1" {{type1select}}>Heal</option><option value="2" {{type2select}}>Hurt</option><option value="3" {{type3select}}>Sleep</option><option value="4" {{type4select}}>Uber Attack</option><option value="5" {{type5select}}>Uber Defense</option></select><br /><span class="small">- Heal gives player back [attribute] hit points.<br />- Hurt deals [attribute] damage to monster.<br />- Sleep keeps monster from attacking ([attribute] is monster's chance out of 15 to stay asleep each turn).<br />- Uber Attack increases total attack damage by [attribute] percent.<br />- Uber Defense increases total defense from attack by [attribute] percent.</span></td></tr>
<tr><td width="20%">Elemento:</td><td><select name="elemento"><option $select1 value="neutro">Neutro</option><option $select2 value="fogo">Fogo</option><option $select3 value="agua">�gua</option><option $select4 value="terra">Terra</option><option $select5 value="vento">Vento</option><option $select6 value="raio">Rel�mpago</option></select><br /></td></tr>
</table>
<input type="submit" name="submit" value="Submit" /> <input type="reset" name="reset" value="Reset" />
</form>
END;

    if ($row["type"] == 1) { $row["type1select"] = "selected=\"selected\" "; } else { $row["type1select"] = ""; }
    if ($row["type"] == 2) { $row["type2select"] = "selected=\"selected\" "; } else { $row["type2select"] = ""; }
    if ($row["type"] == 3) { $row["type3select"] = "selected=\"selected\" "; } else { $row["type3select"] = ""; }
    if ($row["type"] == 4) { $row["type4select"] = "selected=\"selected\" "; } else { $row["type4select"] = ""; }
    if ($row["type"] == 5) { $row["type5select"] = "selected=\"selected\" "; } else { $row["type5select"] = ""; }
    
    $page = parsetemplate($page, $row);
    admindisplay($page, "Edit Spells");
    
}

function levels() {

    $query = doquery("SELECT id FROM {{table}} ORDER BY id DESC LIMIT 1", "levels");
    $row = mysqli_fetch_array($query);
    
    $options = "";
    for($i=2; $i<$row["id"]; $i++) {
        $options .= "<option value=\"$i\">$i</option>\n";
    }
    
$page = <<<END
<b><u>Edit Levels</u></b><br />Select a level number from the dropdown box to edit it.<br /><br />
<form action="admin.php?do=editlevel" method="post">
<select name="level">
$options
</select> 
<input type="submit" name="go" value="Submit" />
</form>
END;

    admindisplay($page, "Edit Levels");
    
}

function editlevel() {

    if (!isset($_POST["level"])) { admindisplay("No level to edit.", "Edit Levels"); die(); }
    $id = $_POST["level"];
    
    if (isset($_POST["submit"])) {
        
        extract($_POST);
        $errors = 0;
        $errorlist = "";
        if ($_POST["one_exp"] == "") { $errors++; $errorlist .= "Class 1 Experience is required.<br />"; }
        if ($_POST["one_hp"] == "") { $errors++; $errorlist .= "Class 1 HP is required.<br />"; }
        if ($_POST["one_mp"] == "") { $errors++; $errorlist .= "Class 1 MP is required.<br />"; }
        if ($_POST["one_tp"] == "") { $errors++; $errorlist .= "Class 1 TP is required.<br />"; }
        if ($_POST["one_strength"] == "") { $errors++; $errorlist .= "Class 1 Strength is required.<br />"; }
        if ($_POST["one_dexterity"] == "") { $errors++; $errorlist .= "Class 1 Dexterity is required.<br />"; }
        if ($_POST["one_spells"] == "") { $errors++; $errorlist .= "Class 1 Spells is required.<br />"; }
        if (!is_numeric($_POST["one_exp"])) { $errors++; $errorlist .= "Class 1 Experience must be a number.<br />"; }
        if (!is_numeric($_POST["one_hp"])) { $errors++; $errorlist .= "Class 1 HP must be a number.<br />"; }
        if (!is_numeric($_POST["one_mp"])) { $errors++; $errorlist .= "Class 1 MP must be a number.<br />"; }
        if (!is_numeric($_POST["one_tp"])) { $errors++; $errorlist .= "Class 1 TP must be a number.<br />"; }
        if (!is_numeric($_POST["one_strength"])) { $errors++; $errorlist .= "Class 1 Strength must be a number.<br />"; }
        if (!is_numeric($_POST["one_dexterity"])) { $errors++; $errorlist .= "Class 1 Dexterity must be a number.<br />"; }
        if (!is_numeric($_POST["one_spells"])) { $errors++; $errorlist .= "Class 1 Spells must be a number.<br />"; }

        if ($_POST["two_exp"] == "") { $errors++; $errorlist .= "Class 2 Experience is required.<br />"; }
        if ($_POST["two_hp"] == "") { $errors++; $errorlist .= "Class 2 HP is required.<br />"; }
        if ($_POST["two_mp"] == "") { $errors++; $errorlist .= "Class 2 MP is required.<br />"; }
        if ($_POST["two_tp"] == "") { $errors++; $errorlist .= "Class 2 TP is required.<br />"; }
        if ($_POST["two_strength"] == "") { $errors++; $errorlist .= "Class 2 Strength is required.<br />"; }
        if ($_POST["two_dexterity"] == "") { $errors++; $errorlist .= "Class 2 Dexterity is required.<br />"; }
        if ($_POST["two_spells"] == "") { $errors++; $errorlist .= "Class 2 Spells is required.<br />"; }
        if (!is_numeric($_POST["two_exp"])) { $errors++; $errorlist .= "Class 2 Experience must be a number.<br />"; }
        if (!is_numeric($_POST["two_hp"])) { $errors++; $errorlist .= "Class 2 HP must be a number.<br />"; }
        if (!is_numeric($_POST["two_mp"])) { $errors++; $errorlist .= "Class 2 MP must be a number.<br />"; }
        if (!is_numeric($_POST["two_tp"])) { $errors++; $errorlist .= "Class 2 TP must be a number.<br />"; }
        if (!is_numeric($_POST["two_strength"])) { $errors++; $errorlist .= "Class 2 Strength must be a number.<br />"; }
        if (!is_numeric($_POST["two_dexterity"])) { $errors++; $errorlist .= "Class 2 Dexterity must be a number.<br />"; }
        if (!is_numeric($_POST["two_spells"])) { $errors++; $errorlist .= "Class 2 Spells must be a number.<br />"; }
                
        if ($_POST["three_exp"] == "") { $errors++; $errorlist .= "Class 3 Experience is required.<br />"; }
        if ($_POST["three_hp"] == "") { $errors++; $errorlist .= "Class 3 HP is required.<br />"; }
        if ($_POST["three_mp"] == "") { $errors++; $errorlist .= "Class 3 MP is required.<br />"; }
        if ($_POST["three_tp"] == "") { $errors++; $errorlist .= "Class 3 TP is required.<br />"; }
        if ($_POST["three_strength"] == "") { $errors++; $errorlist .= "Class 3 Strength is required.<br />"; }
        if ($_POST["three_dexterity"] == "") { $errors++; $errorlist .= "Class 3 Dexterity is required.<br />"; }
        if ($_POST["three_spells"] == "") { $errors++; $errorlist .= "Class 3 Spells is required.<br />"; }
        if (!is_numeric($_POST["three_exp"])) { $errors++; $errorlist .= "Class 3 Experience must be a number.<br />"; }
        if (!is_numeric($_POST["three_hp"])) { $errors++; $errorlist .= "Class 3 HP must be a number.<br />"; }
        if (!is_numeric($_POST["three_mp"])) { $errors++; $errorlist .= "Class 3 MP must be a number.<br />"; }
        if (!is_numeric($_POST["three_tp"])) { $errors++; $errorlist .= "Class 3 TP must be a number.<br />"; }
        if (!is_numeric($_POST["three_strength"])) { $errors++; $errorlist .= "Class 3 Strength must be a number.<br />"; }
        if (!is_numeric($_POST["three_dexterity"])) { $errors++; $errorlist .= "Class 3 Dexterity must be a number.<br />"; }
        if (!is_numeric($_POST["three_spells"])) { $errors++; $errorlist .= "Class 3 Spells must be a number.<br />"; }

        if ($errors == 0) { 
$updatequery = <<<END
UPDATE {{table}} SET
1_exp='$one_exp', 1_hp='$one_hp', 1_mp='$one_mp', 1_tp='$one_tp', 1_strength='$one_strength', 1_dexterity='$one_dexterity', 1_spells='$one_spells',
2_exp='$two_exp', 2_hp='$two_hp', 2_mp='$two_mp', 2_tp='$two_tp', 2_strength='$two_strength', 2_dexterity='$two_dexterity', 2_spells='$two_spells',
3_exp='$three_exp', 3_hp='$three_hp', 3_mp='$three_mp', 3_tp='$three_tp', 3_strength='$three_strength', 3_dexterity='$three_dexterity', 3_spells='$three_spells'
WHERE id='$id' LIMIT 1
END;
			$query = doquery($updatequery, "levels");
            admindisplay("Level updated.","Edit Levels");
        } else {
            admindisplay("<b>Errors:</b><br /><div style=\"color:red;\">$errorlist</div><br />Please go back and try again.", "Edit Spells");
        }        
        
    }   
        
    
    $query = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "levels");
    $row = mysqli_fetch_array($query);
    global $controlrow;
    $class1name = $controlrow["class1name"];
    $class2name = $controlrow["class2name"];
    $class3name = $controlrow["class3name"];

$page = <<<END
<b><u>Edit Levels</u></b><br /><br />
Experience values for each level should be the cumulative total amount of experience up to this point. All other values should be only the new amount to add this level.<br /><br />
<form action="admin.php?do=editlevel" method="post">
<input type="hidden" name="level" value="$id" />
<table width="90%">
<tr><td width="20%">ID:</td><td>{{id}}</td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">$class1name Experience:</td><td><input type="text" name="one_exp" size="10" maxlength="8" value="{{1_exp}}" /></td></tr>
<tr><td width="20%">$class1name HP:</td><td><input type="text" name="one_hp" size="5" maxlength="5" value="{{1_hp}}" /></td></tr>
<tr><td width="20%">$class1name MP:</td><td><input type="text" name="one_mp" size="5" maxlength="5" value="{{1_mp}}" /></td></tr>
<tr><td width="20%">$class1name TP:</td><td><input type="text" name="one_tp" size="5" maxlength="5" value="{{1_tp}}" /></td></tr>
<tr><td width="20%">$class1name Strength:</td><td><input type="text" name="one_strength" size="5" maxlength="5" value="{{1_strength}}" /></td></tr>
<tr><td width="20%">$class1name Dexterity:</td><td><input type="text" name="one_dexterity" size="5" maxlength="5" value="{{1_dexterity}}" /></td></tr>
<tr><td width="20%">$class1name Spells:</td><td><input type="text" name="one_spells" size="5" maxlength="3" value="{{1_spells}}" /></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">$class2name Experience:</td><td><input type="text" name="two_exp" size="10" maxlength="8" value="{{2_exp}}" /></td></tr>
<tr><td width="20%">$class2name HP:</td><td><input type="text" name="two_hp" size="5" maxlength="5" value="{{2_hp}}" /></td></tr>
<tr><td width="20%">$class2name MP:</td><td><input type="text" name="two_mp" size="5" maxlength="5" value="{{2_mp}}" /></td></tr>
<tr><td width="20%">$class2name TP:</td><td><input type="text" name="two_tp" size="5" maxlength="5" value="{{2_tp}}" /></td></tr>
<tr><td width="20%">$class2name Strength:</td><td><input type="text" name="two_strength" size="5" maxlength="5" value="{{2_strength}}" /></td></tr>
<tr><td width="20%">$class2name Dexterity:</td><td><input type="text" name="two_dexterity" size="5" maxlength="5" value="{{2_dexterity}}" /></td></tr>
<tr><td width="20%">$class2name Spells:</td><td><input type="text" name="two_spells" size="5" maxlength="3" value="{{2_spells}}" /></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">$class3name Experience:</td><td><input type="text" name="three_exp" size="10" maxlength="8" value="{{3_exp}}" /></td></tr>
<tr><td width="20%">$class3name HP:</td><td><input type="text" name="three_hp" size="5" maxlength="5" value="{{3_hp}}" /></td></tr>
<tr><td width="20%">$class3name MP:</td><td><input type="text" name="three_mp" size="5" maxlength="5" value="{{3_mp}}" /></td></tr>
<tr><td width="20%">$class3name TP:</td><td><input type="text" name="three_tp" size="5" maxlength="5" value="{{3_tp}}" /></td></tr>
<tr><td width="20%">$class3name Strength:</td><td><input type="text" name="three_strength" size="5" maxlength="5" value="{{3_strength}}" /></td></tr>
<tr><td width="20%">$class3name Dexterity:</td><td><input type="text" name="three_dexterity" size="5" maxlength="5" value="{{3_dexterity}}" /></td></tr>
<tr><td width="20%">$class3name Spells:</td><td><input type="text" name="three_spells" size="5" maxlength="3" value="{{3_spells}}" /></td></tr>
</table>
<input type="submit" name="submit" value="Submit" /> <input type="reset" name="reset" value="Reset" />
</form>
END;
    
    $page = parsetemplate($page, $row);
    admindisplay($page, "Edit Levels");
    
}

function users() {
    
    $query = doquery("SELECT id,charname FROM {{table}} ORDER BY id", "users");
    $page = "<b><u>Edit Users</u></b><br />Click a username to edit the account.<br /><br /><table width=\"50%\">\n";
    $count = 1;
    while ($row = mysqli_fetch_array($query)) {
        if ($count == 1) { $page .= "<tr><td width=\"8%\" style=\"background-color: #eeeeee;\">".$row["id"]."</td><td style=\"background-color: #eeeeee;\"><a href=\"admin.php?do=edituser:".$row["id"]."\">".$row["charname"]."</a></td></tr>\n"; $count = 2; }
        else { $page .= "<tr><td width=\"8%\" style=\"background-color: #ffffff;\">".$row["id"]."</td><td style=\"background-color: #ffffff;\"><a href=\"admin.php?do=edituser:".$row["id"]."\">".$row["charname"]."</a></td></tr>\n"; $count = 1; }
    }
    if (mysqli_num_rows($query) == 0) { $page .= "<tr><td width=\"8%\" style=\"background-color: #eeeeee;\">No spells found.</td></tr>\n"; }
    $page .= "</table>";
    admindisplay($page, "Edit Users");

}

function edituser($id) {
    
    if (isset($_POST["submit"])) {
        
        extract($_POST);
        $errors = 0;
        $errorlist = "";
        if ($email == "") { $errors++; $errorlist .= "Email is required.<br />"; }
        if ($verify == "") { $errors++; $errorlist .= "Verify is required.<br />"; }
        if ($charname == "") { $errors++; $errorlist .= "Character Name is required.<br />"; }
        if ($authlevel == "") { $errors++; $errorlist .= "Auth Level is required.<br />"; }
        if ($latitude == "") { $errors++; $errorlist .= "Latitude is required.<br />"; }
        if ($longitude == "") { $errors++; $errorlist .= "Longitude is required.<br />"; }
        if ($difficulty == "") { $errors++; $errorlist .= "Difficulty is required.<br />"; }
        if ($charclass == "") { $errors++; $errorlist .= "Character Class is required.<br />"; }
        if ($currentaction == "") { $errors++; $errorlist .= "Current Action is required.<br />"; }
        if ($currentfight == "") { $errors++; $errorlist .= "Current Fight is required.<br />"; }
        
        if ($currentmonster == "") { $errors++; $errorlist .= "Current Monster is required.<br />"; }
        if ($currentmonsterhp == "") { $errors++; $errorlist .= "Current Monster HP is required.<br />"; }
        if ($currentmonstersleep == "") { $errors++; $errorlist .= "Current Monster Sleep is required.<br />"; }
        if ($currentmonsterimmune == "") { $errors++; $errorlist .= "Current Monster Immune is required.<br />"; }
        if ($currentuberdamage == "") { $errors++; $errorlist .= "Current Uber Damage is required.<br />"; }
        if ($currentuberdefense == "") { $errors++; $errorlist .= "Current Uber Defense is required.<br />"; }
        if ($currenthp == "") { $errors++; $errorlist .= "Current HP is required.<br />"; }
        if ($currentmp == "") { $errors++; $errorlist .= "Current MP is required.<br />"; }
        if ($currenttp == "") { $errors++; $errorlist .= "Current TP is required.<br />"; }
        if ($maxhp == "") { $errors++; $errorlist .= "Max HP is required.<br />"; }

        if ($maxmp == "") { $errors++; $errorlist .= "Max MP is required.<br />"; }
        if ($maxtp == "") { $errors++; $errorlist .= "Max TP is required.<br />"; }
        if ($level == "") { $errors++; $errorlist .= "Level is required.<br />"; }
        if ($gold == "") { $errors++; $errorlist .= "Gold is required.<br />"; }
        if ($experience == "") { $errors++; $errorlist .= "Experience is required.<br />"; }
        if ($goldbonus == "") { $errors++; $errorlist .= "Gold Bonus is required.<br />"; }
        if ($expbonus == "") { $errors++; $errorlist .= "Experience Bonus is required.<br />"; }
        if ($strength == "") { $errors++; $errorlist .= "Strength is required.<br />"; }
        if ($dexterity == "") { $errors++; $errorlist .= "Dexterity is required.<br />"; }
        if ($attackpower == "") { $errors++; $errorlist .= "Attack Power is required.<br />"; }

        if ($defensepower == "") { $errors++; $errorlist .= "Defense Power is required.<br />"; }
        if ($weaponid == "") { $errors++; $errorlist .= "Weapon ID is required.<br />"; }
        if ($armorid == "") { $errors++; $errorlist .= "Armor ID is required.<br />"; }
        if ($shieldid == "") { $errors++; $errorlist .= "Shield ID is required.<br />"; }
        if ($slot1id == "") { $errors++; $errorlist .= "Slot 1 ID is required.<br />"; }
        if ($slot2id == "") { $errors++; $errorlist .= "Slot 2 ID is required.<br />"; }
        if ($slot3id == "") { $errors++; $errorlist .= "Slot 3 ID is required.<br />"; }
        if ($weaponname == "") { $errors++; $errorlist .= "Weapon Name is required.<br />"; }
        if ($armorname == "") { $errors++; $errorlist .= "Armor Name is required.<br />"; }
        if ($shieldname == "") { $errors++; $errorlist .= "Shield Name is required.<br />"; }

        if ($slot1name == "") { $errors++; $errorlist .= "Slot 1 Name is required.<br />"; }
        if ($slot2name == "") { $errors++; $errorlist .= "Slot 2 Name is required.<br />"; }
        if ($slot3name == "") { $errors++; $errorlist .= "Slot 3 Name is required.<br />"; }
        if ($dropcode == "") { $errors++; $errorlist .= "Drop Code is required.<br />"; }
        if ($spells == "") { $errors++; $errorlist .= "Spells is required.<br />"; }
        if ($towns == "") { $errors++; $errorlist .= "Towns is required.<br />"; }
        
        if (!is_numeric($authlevel)) { $errors++; $errorlist .= "Auth Level must be a number.<br />"; }
        if (!is_numeric($latitude)) { $errors++; $errorlist .= "Latitude must be a number.<br />"; }
        if (!is_numeric($longitude)) { $errors++; $errorlist .= "Longitude must be a number.<br />"; }
        if (!is_numeric($difficulty)) { $errors++; $errorlist .= "Difficulty must be a number.<br />"; }
        if (!is_numeric($charclass)) { $errors++; $errorlist .= "Character Class must be a number.<br />"; }
        if (!is_numeric($currentfight)) { $errors++; $errorlist .= "Current Fight must be a number.<br />"; }
        if (!is_numeric($currentmonster)) { $errors++; $errorlist .= "Current Monster must be a number.<br />"; }
        if (!is_numeric($currentmonsterhp)) { $errors++; $errorlist .= "Current Monster HP must be a number.<br />"; }
        if (!is_numeric($currentmonstersleep)) { $errors++; $errorlist .= "Current Monster Sleep must be a number.<br />"; }
        
        if (!is_numeric($currentmonsterimmune)) { $errors++; $errorlist .= "Current Monster Immune must be a number.<br />"; }
        if (!is_numeric($currentuberdamage)) { $errors++; $errorlist .= "Current Uber Damage must be a number.<br />"; }
        if (!is_numeric($currentuberdefense)) { $errors++; $errorlist .= "Current Uber Defense must be a number.<br />"; }
        if (!is_numeric($currenthp)) { $errors++; $errorlist .= "Current HP must be a number.<br />"; }
        if (!is_numeric($currentmp)) { $errors++; $errorlist .= "Current MP must be a number.<br />"; }
        if (!is_numeric($currenttp)) { $errors++; $errorlist .= "Current TP must be a number.<br />"; }
        if (!is_numeric($maxhp)) { $errors++; $errorlist .= "Max HP must be a number.<br />"; }
        if (!is_numeric($maxmp)) { $errors++; $errorlist .= "Max MP must be a number.<br />"; }
        if (!is_numeric($maxtp)) { $errors++; $errorlist .= "Max TP must be a number.<br />"; }
        if (!is_numeric($level)) { $errors++; $errorlist .= "Level must be a number.<br />"; }
        
        if (!is_numeric($gold)) { $errors++; $errorlist .= "Gold must be a number.<br />"; }
        if (!is_numeric($experience)) { $errors++; $errorlist .= "Experience must be a number.<br />"; }
        if (!is_numeric($goldbonus)) { $errors++; $errorlist .= "Gold Bonus must be a number.<br />"; }
        if (!is_numeric($expbonus)) { $errors++; $errorlist .= "Experience Bonus must be a number.<br />"; }
        if (!is_numeric($strength)) { $errors++; $errorlist .= "Strength must be a number.<br />"; }
        if (!is_numeric($dexterity)) { $errors++; $errorlist .= "Dexterity must be a number.<br />"; }
        if (!is_numeric($attackpower)) { $errors++; $errorlist .= "Attack Power must be a number.<br />"; }
        if (!is_numeric($defensepower)) { $errors++; $errorlist .= "Defense Power must be a number.<br />"; }
        if (!is_numeric($weaponid)) { $errors++; $errorlist .= "Weapon ID must be a number.<br />"; }
        if (!is_numeric($armorid)) { $errors++; $errorlist .= "Armor ID must be a number.<br />"; }
        
        if (!is_numeric($shieldid)) { $errors++; $errorlist .= "Shield ID must be a number.<br />"; }
        if (!is_numeric($slot1id)) { $errors++; $errorlist .= "Slot 1 ID  must be a number.<br />"; }
        if (!is_numeric($slot2id)) { $errors++; $errorlist .= "Slot 2 ID must be a number.<br />"; }
        if (!is_numeric($slot3id)) { $errors++; $errorlist .= "Slot 3 ID must be a number.<br />"; }
        if (!is_numeric($dropcode)) { $errors++; $errorlist .= "Drop Code must be a number.<br />"; }
        
        if ($errors == 0) { 
$updatequery = <<<END
UPDATE {{table}} SET
email="$email", verify="$verify", charname="$charname", authlevel="$authlevel", latitude="$latitude",
longitude="$longitude", difficulty="$difficulty", charclass="$charclass", currentaction="$currentaction", currentfight="$currentfight",
currentmonster="$currentmonster", currentmonsterhp="$currentmonsterhp", currentmonstersleep="$currentmonstersleep", currentmonsterimmune="$currentmonsterimmune", currentuberdamage="$currentuberdamage",
currentuberdefense="$currentuberdefense", currenthp="$currenthp", currentmp="$currentmp", currenttp="$currenttp", maxhp="$maxhp",
maxmp="$maxmp", maxtp="$maxtp", level="$level", gold="$gold", experience="$experience",
goldbonus="$goldbonus", expbonus="$expbonus", strength="$strength", dexterity="$dexterity", attackpower="$attackpower",
defensepower="$defensepower", weaponid="$weaponid", armorid="$armorid", shieldid="$shieldid", slot1id="$slot1id",
slot2id="$slot2id", slot3id="$slot3id", weaponname="$weaponname", armorname="$armorname", shieldname="$shieldname",
slot1name="$slot1name", slot2name="$slot2name", slot3name="$slot3name", dropcode="$dropcode", spells="$spells",
towns="$towns", avatar="$avatar", agilidade="$agilidade", sorte="$sorte", inteligencia="$inteligencia", determinacao="$determinacao", precisao="$precisao", graduacao="$graduacao", pontoatributos="$pontoatributos", missao="$missao", senjutsutimer="$senjutsutimer", bancogeral="$bancogeral", bp1="$bp1", bp2="$bp2", bp3="$bp3", bp4="$bp4" WHERE id="$id" LIMIT 1
END;
			$query = doquery($updatequery, "users");
            admindisplay("User updated.","Edit Users");
        } else {
            admindisplay("<b>Errors:</b><br /><div style=\"color:red;\">$errorlist</div><br />Please go back and try again.", "Edit Users");
        }        
        
    }   
        
    $query = doquery("SELECT * FROM {{table}} WHERE id='$id' LIMIT 1", "users");
    $row = mysqli_fetch_array($query);
    global $controlrow;
    $diff1name = $controlrow["diff1name"];
    $diff2name = $controlrow["diff2name"];
    $diff3name = $controlrow["diff3name"];
    $class1name = $controlrow["class1name"];
    $class2name = $controlrow["class2name"];
    $class3name = $controlrow["class3name"];

$page = <<<END
<b><u>Edit Users</u></b><br /><br />
<form action="admin.php?do=edituser:$id" method="post">
<table width="90%">
<tr><td width="20%">ID:</td><td>{{id}}</td></tr>
<tr><td width="20%">Username:</td><td>{{username}}</td></tr>
<tr><td width="20%">Email:</td><td><input type="text" name="email" size="30" maxlength="100" value="{{email}}" /></td></tr>
<tr><td width="20%">Verify:</td><td><input type="text" name="verify" size="30" maxlength="8" value="{{verify}}" /></td></tr>
<tr><td width="20%">Character Name:</td><td><input type="text" name="charname" size="30" maxlength="30" value="{{charname}}" /></td></tr>
<tr><td width="20%">Register Date:</td><td>{{regdate}}</td></tr>
<tr><td width="20%">Last Online:</td><td>{{onlinetime}}</td></tr>
<tr><td width="20%">Auth Level:</td><td><select name="authlevel"><option value="0" {{auth0select}}>User</option><option value="1" {{auth1select}}>Admin</option><option value="2" {{auth2select}}>Blocked</option></select><br /><span class="small">Set to "Blocked" to temporarily (or permanently) ban a user.</span></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">Latitude:</td><td><input type="text" name="latitude" size="5" maxlength="6" value="{{latitude}}" /></td></tr>
<tr><td width="20%">Longitude:</td><td><input type="text" name="longitude" size="5" maxlength="6" value="{{longitude}}" /></td></tr>
<tr><td width="20%">Difficulty:</td><td><select name="difficulty"><option value="1" {{diff1select}}>$diff1name</option><option value="2" {{diff2select}}>$diff2name</option><option value="3" {{diff3select}}>$diff3name</option></select></td></tr>
<tr><td width="20%">Character Class:</td><td><select name="charclass"><option value="1" {{class1select}}>$class1name</option><option value="2" {{class2select}}>$class2name</option><option value="3" {{class3select}}>$class3name</option></select></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">Current Action:</td><td><input type="text" name="currentaction" size="30" maxlength="30" value="{{currentaction}}" /></td></tr>
<tr><td width="20%">Current Fight:</td><td><input type="text" name="currentfight" size="5" maxlength="4" value="{{currentfight}}" /></td></tr>
<tr><td width="20%">Current Monster:</td><td><input type="text" name="currentmonster" size="5" maxlength="6" value="{{currentmonster}}" /></td></tr>
<tr><td width="20%">Current Monster HP:</td><td><input type="text" name="currentmonsterhp" size="5" maxlength="6" value="{{currentmonsterhp}}" /></td></tr>
<tr><td width="20%">Current Monster Sleep:</td><td><input type="text" name="currentmonsterimmune" size="5" maxlength="3" value="{{currentmonsterimmune}}" /></td></tr>
<tr><td width="20%">Current Monster Immune:</td><td><input type="text" name="currentmonstersleep" size="5" maxlength="3" value="{{currentmonstersleep}}" /></td></tr>
<tr><td width="20%">Current Uber Damage:</td><td><input type="text" name="currentuberdamage" size="5" maxlength="3" value="{{currentuberdamage}}" /></td></tr>
<tr><td width="20%">Current Uber Defense:</td><td><input type="text" name="currentuberdefense" size="5" maxlength="3" value="{{currentuberdefense}}" /></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">Current HP:</td><td><input type="text" name="currenthp" size="5" maxlength="6" value="{{currenthp}}" /></td></tr>
<tr><td width="20%">Current MP:</td><td><input type="text" name="currentmp" size="5" maxlength="6" value="{{currentmp}}" /></td></tr>
<tr><td width="20%">Current TP:</td><td><input type="text" name="currenttp" size="5" maxlength="6" value="{{currenttp}}" /></td></tr>
<tr><td width="20%">Max HP:</td><td><input type="text" name="maxhp" size="5" maxlength="6" value="{{maxhp}}" /></td></tr>
<tr><td width="20%">Max MP:</td><td><input type="text" name="maxmp" size="5" maxlength="6" value="{{maxmp}}" /></td></tr>
<tr><td width="20%">Max TP:</td><td><input type="text" name="maxtp" size="5" maxlength="6" value="{{maxtp}}" /></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">Level:</td><td><input type="text" name="level" size="5" maxlength="5" value="{{level}}" /></td></tr>
<tr><td width="20%">Gold:</td><td><input type="text" name="gold" size="10" maxlength="8" value="{{gold}}" /></td></tr>
<tr><td width="20%">Experience:</td><td><input type="text" name="experience" size="10" maxlength="8" value="{{experience}}" /></td></tr>
<tr><td width="20%">Gold Bonus:</td><td><input type="text" name="goldbonus" size="5" maxlength="5" value="{{goldbonus}}" /></td></tr>
<tr><td width="20%">Experience Bonus:</td><td><input type="text" name="expbonus" size="5" maxlength="5" value="{{expbonus}}" /></td></tr>
<tr><td width="20%">Strength:</td><td><input type="text" name="strength" size="5" maxlength="5" value="{{strength}}" /></td></tr>
<tr><td width="20%">Dexterity:</td><td><input type="text" name="dexterity" size="5" maxlength="5" value="{{dexterity}}" /></td></tr>
<tr><td width="20%">Attack Power:</td><td><input type="text" name="attackpower" size="5" maxlength="5" value="{{attackpower}}" /></td></tr>
<tr><td width="20%">Defense Power:</td><td><input type="text" name="defensepower" size="5" maxlength="5" value="{{defensepower}}" /></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">Weapon ID:</td><td><input type="text" name="weaponid" size="5" maxlength="5" value="{{weaponid}}" /></td></tr>
<tr><td width="20%">Armor ID:</td><td><input type="text" name="armorid" size="5" maxlength="5" value="{{armorid}}" /></td></tr>
<tr><td width="20%">Shield ID:</td><td><input type="text" name="shieldid" size="5" maxlength="5" value="{{shieldid}}" /></td></tr>
<tr><td width="20%">Slot 1 ID:</td><td><input type="text" name="slot1id" size="5" maxlength="5" value="{{slot1id}}" /></td></tr>
<tr><td width="20%">Slot 2 ID:</td><td><input type="text" name="slot2id" size="5" maxlength="5" value="{{slot2id}}" /></td></tr>
<tr><td width="20%">Slot 3 ID:</td><td><input type="text" name="slot3id" size="5" maxlength="5" value="{{slot3id}}" /></td></tr>
<tr><td width="20%">Weapon Name:</td><td><input type="text" name="weaponname" size="30" maxlength="30" value="{{weaponname}}" /></td></tr>
<tr><td width="20%">Armor Name:</td><td><input type="text" name="armorname" size="30" maxlength="30" value="{{armorname}}" /></td></tr>
<tr><td width="20%">Shield Name:</td><td><input type="text" name="shieldname" size="30" maxlength="30" value="{{shieldname}}" /></td></tr>
<tr><td width="20%">Slot 1 Name:</td><td><input type="text" name="slot1name" size="30" maxlength="30" value="{{slot1name}}" /></td></tr>
<tr><td width="20%">Slot 2 Name:</td><td><input type="text" name="slot2name" size="30" maxlength="30" value="{{slot2name}}" /></td></tr>
<tr><td width="20%">Slot 3 Name:</td><td><input type="text" name="slot3name" size="30" maxlength="30" value="{{slot3name}}" /></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">Drop Code:</td><td><input type="text" name="dropcode" size="5" maxlength="8" value="{{dropcode}}" /></td></tr>
<tr><td width="20%">Spells:</td><td><input type="text" name="spells" size="50" maxlength="50" value="{{spells}}" /></td></tr>
<tr><td width="20%">Towns:</td><td><input type="text" name="towns" size="50" maxlength="50" value="{{towns}}" /></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>
<tr><td width="20%">Avatar (N�mero):</td><td><input type="text" name="avatar" size="50" maxlength="50" value="{{avatar}}" /></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">Agilidade:</td><td><input type="text" name="agilidade" size="5" maxlength="50" value="{{agilidade}}" /></td></tr>
<tr><td width="20%">Sorte:</td><td><input type="text" name="sorte" size="5" maxlength="50" value="{{sorte}}" /></td></tr>
<tr><td width="20%">Intelig�ncia:</td><td><input type="text" name="inteligencia" size="5" maxlength="50" value="{{inteligencia}}" /></td></tr>
<tr><td width="20%">Precis�o:</td><td><input type="text" name="precisao" size="5" maxlength="50" value="{{precisao}}" /></td></tr>
<tr><td width="20%">Determina��o:</td><td><input type="text" name="determinacao" size="5" maxlength="50" value="{{determinacao}}" /></td></tr>
<tr><td width="20%">Ponto de Atributos para Distribuir:</td><td><input type="text" name="pontoatributos" size="5" maxlength="50" value="{{pontoatributos}}" /></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">Miss�o que o Jogador Est� (N�mero):</td><td><input type="text" name="missao" size="5" maxlength="50" value="{{missao}}" /></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">Gradua��o:</td><td><input type="text" name="graduacao" size="30" maxlength="50" value="{{graduacao}}" /></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">Senjutsu Tempo Atual:</td><td><input type="text" name="senjutsutimer" size="30" maxlength="50" value="{{senjutsutimer}}" /><br><span class="small">Formato do Senjutsu: (None = padr�o) (dd/mes/ano, hora/minutos/segundos, quantos minutos � passar).</span></td></tr>

<tr><td colspan="2" style="background-color:#cccccc;">&nbsp;</td></tr>

<tr><td width="20%">Banco Geral:</td><td><input type="text" name="bancogeral" size="50" maxlength="2000" value="{{bancogeral}}" /></td></tr>

<tr><td width="20%">Bp Slot 1:</td><td><input type="text" name="bp1" size="50" maxlength="50" value="{{bp1}}" /></td></tr>

<tr><td width="20%">Bp Slot 2:</td><td><input type="text" name="bp2" size="50" maxlength="50" value="{{bp2}}" /></td></tr>

<tr><td width="20%">Bp Slot 3:</td><td><input type="text" name="bp3" size="50" maxlength="50" value="{{bp3}}" /></td></tr>

<tr><td width="20%">Bp Slot 4:</td><td><input type="text" name="bp4" size="50" maxlength="50" value="{{bp4}}" /></td></tr>

</table>
<input type="submit" name="submit" value="Submit" /> <input type="reset" name="reset" value="Reset" />
</form>
END;

    if ($row["authlevel"] == 0) { $row["auth0select"] = "selected=\"selected\" "; } else { $row["auth0select"] = ""; }
    if ($row["authlevel"] == 1) { $row["auth1select"] = "selected=\"selected\" "; } else { $row["auth1select"] = ""; }
    if ($row["authlevel"] == 2) { $row["auth2select"] = "selected=\"selected\" "; } else { $row["auth2select"] = ""; }
    if ($row["charclass"] == 1) { $row["class1select"] = "selected=\"selected\" "; } else { $row["class1select"] = ""; }
    if ($row["charclass"] == 2) { $row["class2select"] = "selected=\"selected\" "; } else { $row["class2select"] = ""; }
    if ($row["charclass"] == 3) { $row["class3select"] = "selected=\"selected\" "; } else { $row["class3select"] = ""; }
    if ($row["difficulty"] == 1) { $row["diff1select"] = "selected=\"selected\" "; } else { $row["diff1select"] = ""; }
    if ($row["difficulty"] == 2) { $row["diff2select"] = "selected=\"selected\" "; } else { $row["diff2select"] = ""; }
    if ($row["difficulty"] == 3) { $row["diff3select"] = "selected=\"selected\" "; } else { $row["diff3select"] = ""; }
    
    $page = parsetemplate($page, $row);
    admindisplay($page, "Edit Users");
    
}

function addnews() {
    
    if (isset($_POST["submit"])) {
        
        extract($_POST);
        $errors = 0;
        $errorlist = "";
        if ($content == "") { $errors++; $errorlist .= "Content is required.<br />"; }
        
        if ($errors == 0) { 
            $query = doquery("INSERT INTO {{table}} SET id='',postdate=NOW(),content='$content'", "news");
            admindisplay("News post added.","Add News");
        } else {
            admindisplay("<b>Errors:</b><br /><div style=\"color:red;\">$errorlist</div><br />Please go back and try again.", "Add News");
        }        
        
    }   
        
$page = <<<END
<b><u>Add A News Post</u></b><br /><br />
<form action="admin.php?do=news" method="post">
Type your post below and then click Submit to add it.<br />
<textarea name="content" rows="5" cols="50"></textarea><br />
<input type="submit" name="submit" value="Submit" /> <input type="reset" name="reset" value="Reset" />
</form>
END;
    
    admindisplay($page, "Add News");
    
}
    
?>