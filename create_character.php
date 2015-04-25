<?php
include "header.php";
$DEBUG = 0; // show debugging messages: 0=off, 1=on


	function GetAppearanceList($folder)
	{
		$sList = "";
		$handle = opendir($folder);
		$file = readdir($handle);
		while($file)
		{
			if(is_file($folder.$file)) {
				$sList .= "<option value=\"".$folder.$file."\">".$file."</option>\n";
			}
			$file = readdir($handle);
		}
		closedir($handle);
		return $sList;
	}


?>

<script type="text/javascript">
function verify()
{
	if(document.form.name.value == "")
	{
		window.alert("<?php echo $lang_crt["u_must"]; ?>");
		return false;
	}
	else {
		return true;
	}
}


	function LoadAppearance(sPreviewID)
	{
		var oAppearance = window.document.getElementById("image_path");
		var sAppearanceImg = oAppearance.options[oAppearance.selectedIndex].value;

		window.document.getElementById(sPreviewID).src = sAppearanceImg;
	}

</script>

<?php
$result = mysql_query ("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
if ($row = mysql_fetch_array($result)) {
$duplicate = "yes";
}

if($create_char == "yes") {
if($duplicate != "yes") {

$races_lookup = mysql_query("SELECT * FROM phaos_races WHERE name = '$race'");
if ($row = mysql_fetch_array($races_lookup)) {
$race_name = $row["name"];
$strength = $row["str"];
$dexterity = $row["dex"];
$wisdom = $row["wis"];
$constitution = $row["con"];
}
// skill adjustments:
//echo "Class:".$class;
$skillquery=mysql_query("select * from phaos_classes where name='$class'");
//echo "query:".$skillquery;
$skills=mysql_fetch_array($skillquery);
$DEBUG and print("SKILLS:$skills:");
$DEBUG and print("$lang_crt[skll_vars]: $skills[fight], $skills[defence], $skills[weaponless], $skills[lockpick], $skills[traps]");
// end Skill Adjustments!
$attribute_check = $strength+$dexterity+$wisdom+$constitution;

if($attribute_check == "24" AND $name != "") {

$hit_points = $constitution*6;

// set homeland - start location
// if     ($race == "Lizardfolk")	{$startloc = rand(3001,3225);}
// elseif ($race == "Gnome")	{$startloc = rand(5001,5225);}
// elseif ($race == "Orc")		{$startloc = rand(1001,1225);}
// elseif ($race == "Vampire")	{$startloc = rand(2001,2225);}
// elseif ($race == "Dwarf")	{$startloc = rand(7001,7225);}
// elseif ($race == "Undead")	{$startloc = rand(4001,4225);}
// elseif ($race == "Human")	{$startloc = rand(8001,8225);}
// elseif ($race == "Elf")		{$startloc = rand(6001,6225);}
// else				{$startloc = rand(1,225);}
$startloc = rand(1,225);	// incase no race is given??  How does that happen?
// These specific locations are the best starting cities for each race
$race == "Orc"		AND $startloc = 1037;
$race == "Vampire"	AND $startloc = 2067;
$race == "Lizardfolk"	AND $startloc = 3001;
$race == "Undead"	AND $startloc = 4072;
$race == "Gnome"	AND $startloc =	5170;
$race == "Elf"		AND $startloc = 6173;
$race == "Dwarf"	AND $startloc = 7179;
$race == "Human"	AND $startloc = 8052;

$query = "INSERT INTO phaos_characters
(location,image_path,username,name,age,strength,dexterity,wisdom,constitution,hit_points,race,class,sex,gold,fight,defence,weaponless,lockpick,traps) 
VALUES
('$startloc','$image_path','$PHP_PHAOS_USER','$name','$age','$strength','$dexterity','$wisdom','$constitution','$hit_points','$race','$class','$sex','150','".$skills["fight"]."','".$skills["defence"]."','".$skills["weaponless"]."','".$skills["lockpick"]."','".$skills["traps"]."')";
$req = mysql_query($query);
if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;} 
?>
<script type="text/javascript">
<!--
javascript:parent.side_bar.location.reload();
//-->
</script>
<?php
$char_created = "yes";
}
}
}
?>

<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
<tr>
<td align="center" valign="top">

<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center" valign="top">

<table border="0" cellspacing="5" cellpadding="0">
<form action="create_character.php" method="post" name="form" id="form" onsubmit="return verify();" >
<tr>
<td align="center" colspan="2">
<?php
if($duplicate == "yes") {
?>
<big><b><?php echo $lang_crt["alr_hav_char"]; ?>.</b></big>
<p>
<a href="character.php"><?php echo $lang_crt["ret_char_pg"]; ?></a>
<?php
} else {

if($char_created == "yes") {
//DELETE CHARACETERS WITH BLANK NAMES
$query = "DELETE FROM phaos_characters WHERE name = ''";
$result = mysql_query($query) or die ("Error in query: $query. ".mysql_error());
?>
<big><b><?php echo $lang_crt["char_succ_crt"]; ?></b></big>
<?php
} else {
?>
<big><b><?php echo $lang_crt["crt_a__char"]; ?></b></big></p></td>
</tr>
<tr>
<td align="right" valign="top">
<?php echo $lang_name; ?>:
<br />
<?php echo $lang_crt["plz_not_use"]; ?></td>
<td align="left" valign="top">
	<input type="text" name="name" size="20" maxlength="20" /></td>
</tr>
<tr>
<td align="right">
<?php echo $lang_age; ?>:</td>
<td align="left">
	<input type="text" name="age" size="4" maxlength="4" /></td>
</tr>
<tr>
<td align="right">
<?php echo $lang_sex; ?>:</td>
<td align="left">
<select name="sex">
<?php
$sex = mysql_query("SELECT * FROM phaos_sex ORDER BY sex ASC");
if ($row = mysql_fetch_array($sex)) {
	do {
		$sex_name = $row["sex"];
		print ("<option value=\"$sex_name\">$sex_name</option>");
	} while ($row = mysql_fetch_array($sex));
}
?>
</select></td>
</tr>
<tr>
<td align="right">
Race:</td>
<td align="left">
<select name="race">
<?php
$races = mysql_query("SELECT * FROM phaos_races ORDER BY name ASC");
if ($row = mysql_fetch_array($races)) {
	do {
		$race_name = $row["name"];
		print ("<option value=\"$race_name\">$race_name</option>");
	} while ($row = mysql_fetch_array($races));
}
?>
</select></td>
</tr>
<tr>
<td align="right">
Class:</td>
<td align="left">
<select name="class">
<?php
$classes = mysql_query("SELECT * FROM phaos_classes ORDER BY name ASC");
if ($row = mysql_fetch_array($classes)) {
	do {
		$class_name = $row["name"];
		print ("<option value=\"$class_name\">$class_name</option>");
	} while ($row = mysql_fetch_array($classes));
}
?>
</select></td>
</tr>
<tr>
<td align="right">
<?php echo $lang_crt["sel_char_img"]; ?>:</td>
<td align="left">
<select name="image_path" id="image_path" onchange="javascript: LoadAppearance('current_appearance');">
<?php echo GetAppearanceList("images/icons/characters/"); ?>
</select>

</td>
</tr>

<tr>
<td align="center" colspan="2">
<?php echo $lang_crt["sel_char_img"]; ?>
<br />
<img id="current_appearance" name="current_appearance" src="images/icons/characters/character_1.gif" />
</td>
</tr>
<tr>
<td align="center" colspan="2">
<br />
<br />
<input type="hidden" name="create_char" value="yes" />
<input type="submit" value="<?php echo $lang_crt["crt_char"]; ?>" />
<?php
}
}
?></td>
</tr>
</form>
</table></td>
</tr>
</table></td>
</tr>
</table>
<?php include "footer.php"; ?>
