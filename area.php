<html>
<head>

<?php
include "config.php";
include "class_character.php";
include_once 'include_lang.php';
$character=new character($PHP_PHAOS_CHARID);
?>

<link href="styles/phaos.css" rel="stylesheet" type="text/css">

</head>
<body>

<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
<tr>
<td align=center valign=top>

<table border=0 cellspacing=5 cellpadding=0 width="100%">
<tr>
<td align=center colspan=2>
<img src="lang/<?php echo $lang ?>_images/explore.png">
<br>
<?php
// Get all Character Data

//get location Data
$result = mysql_query ("SELECT * FROM phaos_locations WHERE id = '".$character->location."'");
if ($row = mysql_fetch_array($result)) {
	$location_name = $row["name"];
	$special_no=$row["special"];
}
//get Special Data
if ($special_no>0){
	$result = mysql_query ("SELECT * FROM phaos_specials WHERE id = '$special_no'");
	if ($row = mysql_fetch_array($result)) {
		$special_name=$row["name"];
		$special_text=$row["text"];
		$special_type=$row["encountertype"];
		$special_forced=$row["forced_encounter"];
		$special_bonus=(int)$row["bonus"];
		$special_target_name=$row["target_name"];
		$special_rnd=$row["type"];
	}
}
if($character->location == "") {
	echo ("<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"create_character.php\";',0);</script>\n");
} else {
	print ("<font size=4><b>$location_name</b></font>");
	?>
	<br>
	<br>
	</td>
	</tr>
	<tr><td>
	<?php
	// begin Special
	echo "<h3>".$special_name."<h3>";
	echo "<p>".$special_text."</p>";
	if ($special_forced=="y"){
	echo "<p>".$lang_area["special_event"]."</p>";
}

$instant_Fight=0;
if ($special_forced=="y") {
	$instant_Fight=1;
} else {
	$instant_Fight=0;
}
// if specialtype=1 then enemy-patroll (instant Fight)!)
if ($special_type==1){
	$op_countmin=2;
	$op_countmax=4;
}
// end encountertype=1 *****************************
// if encountertype=2 then enemy-Camp
if ($special_type==2){
	$op_countmin=3;
	$op_countmax=6;
}
// end encountertype=2 *****************************
// if encountertype=3 then link to Dungeon
// setting Dungeon to "Bonus" value.
if ($special_type==3){
	//$query="select * from phaos_locations_dungeon where is_an='$special_target_name' and area='$special_bonus'";
	$entrance=mysql_query("select * from phaos_locations_dungeon where is_an='$special_target_name' and area='".$special_rnd."'");
	//echo $query." - ".$entrance; //debug_ressource
	$target = mysql_fetch_array($entrance);
	//echo mysql_error();
	$target_id=$target["id"];
	//echo "Target-ID :".$target_id." - ".$target["id"];//debug target
	$query="update phaos_characters set dungeon_location='$target_id' where id='".$character->id."'";
	$result=mysql_query($query);
	echo mysql_error();
	echo "<a href=\"travel_dungeon.php\">".$lang_area["enter"]."</a>";
}
// Instant_Fight_Special

if (($instant_Fight==1)&&(!isset($finish))){
	echo "<a href=\"combat.php?opp_type=npc&loc=area&bonus=$special_bonus\"><h3>".$lang_area["f_them"]."</h3></a>";
} else if(isset($finish)) {
	?>
	<script language="JavaScript">
	<!--
	self.location="travel.php";
	//-->
	</script>
	<?php
}
?>
</table>

</td>
</tr>
<?php
}
// End Instant_Fight

// end special
?>
</tr>
<tr>
<td align=center>
<a href="market.php" target="content"><?php echo $lang_area["trade"]; ?></a>
</td>
</tr>
<tr>
<td align=left>
<br><br>
<b>Others in the area:</b><p>
<div align=center>
<small>Players currently logged in are white.</small>
</div>
<p>
<?php
$current_time = time();

$active_min = $current_time-300;
$active_max = $current_time+300;

$result = mysql_query ("SELECT * FROM phaos_characters WHERE location = '".$character->location."' ORDER BY name ASC");
if ($row = mysql_fetch_array($result)) {
	do {
		$char_name = $row["name"];
		$char_username = $row["username"];

		$active_check = mysql_query ("SELECT * FROM phaos_characters WHERE regen_time >= '$active_min' AND regen_time <= '$active_max' AND username = '$char_username'");
		if ($row = mysql_fetch_array($active_check)) {$active = "yes";}

		if($active == "yes") {
			print ("<font color=#009900>|</font> $char_name ");
		} else {
			print ("<font color=#009900>|</font> <font color=#009900>$char_name</font> ");
		}

		$active = "";
	} while ($row = mysql_fetch_array($result));
}
?>
<font color=#009900>|</font>
<?php
// Move Special to an other Location!
/*if ($special_rnd=="random") {
	$query1="select * from phaos_locations where special=0 order by rand() limit 1";
	$query="UPDATE phaos_locations set special=0 where id=".$character->location.";";
	mysql_query($query);
	$new_stuff=mysql_query($query1);
	//select an random event
	$query3="select * from phaos_specials where type='random' order by rand() limit 1";
	$res=mysql_query($query3);
	$targetid=mysql_fetch_array($new_stuff);
	$specialid=mysql_fetch_array($res);
	//echo "selected special_ID:".$specialid["id"];

	$query2="update phaos_locations set special='".$specialid["id"]."' where id=".$targetid["id"];
	//echo $query2;
	mysql_query($query2);
}*/
?>
</td>
</tr>
</table>

</td>
</tr>
</table>

</body>
</html>
