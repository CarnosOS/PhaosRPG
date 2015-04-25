<?php
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Create Character</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">

<script language="javascript">
function verify() {
if(document.form.name.value == "") {
	alert("You must enter a name.");
} else {
	document.form.submit();
}
}
</script>

<?php
$result = mysql_query ("SELECT * FROM phaos_characters WHERE username = '$username'");
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
		echo $skills;
		echo "skill-values:".$skills["fight"]."','".$skills["defence"]."','".$skills["weaponless"]."','".$skills["lockpick"]."','".$skills["traps"];
		// end Skill Adjustments!
		$attribute_check = $strength+$dexterity+$wisdom+$constitution;
		
		if($attribute_check == "24" AND $name != "") {
			
			$hit_points = $constitution*6;
			
			if($race == "Lizardfolk") {$homeland = rand(1,3);}
			if($race == "Gnome") {$homeland = rand(4,6);}
			if($race == "Orc") {$homeland = rand(7,9);}
			if($race == "Vampire") {$homeland = rand(10,12);}
			if($race == "Dwarf") {$homeland = rand(13,15);}
			if($race == "Undead") {$homeland = rand(16,18);}
			if($race == "Human") {$homeland = rand(19,21);}
			if($race == "Elf") {$homeland = rand(22,24);}
			
			$query = "INSERT INTO phaos_characters
			(location,image_path,username,name,age,strength,dexterity,wisdom,constitution,hit_points,race,class,sex,gold,fight,defence,weaponless,lockpick,traps) 
			VALUES
			('$homeland','$image_path','$username','$name','$age','$strength','$dexterity','$wisdom','$constitution','$hit_points','$race','$class','$sex','50','".$skills["fight"]."','".$skills["defence"]."','".$skills["weaponless"]."','".$skills["lockpick"]."','".$skills["traps"]."')";
			$req = mysql_query($query);
			if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;} 
			$char_created = "yes";
		}
	}
}
?>

</head>
<body> 

<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
<tr>
	<td align=center valign=middle  height=\"100%\" width=\"100%\">
	<table border=0 cellspacing=1 cellpadding=0>
	<tr>
		<td align=center valign=top>
		<table width=600 border=1 cellspacing=0 cellpadding=3 align=center>
		
		<form method="post" name="form" action="create_character.php?username=<?php echo $username; ?>&">
		<tr style=background:#006600;> 
      <td colspan="2"> 
        <div align="center"><b>Create a new character for user <?php echo $username; ?></b></div>
      </td>
    </tr>
		<tr>
		<td align=center colspan=2>
		<?php
		if($duplicate == "yes") {
			?>
			<big><b>This user all ready has a character.  You must delete there current character before you may create another.</b></big>
			<p>
			<form><input type='button' onClick="parent.location='admin_users_Create_New_Character.php'" value='OK'></form>
			<?php
		} else {
			
			if($char_created == "yes") {
			//DELETE CHARACETERS WITH BLANK NAMES
				$query = "DELETE FROM phaos_characters WHERE name = ''";
				$result = mysql_query($query) or die ("Error in query: $query. " .
				mysql_error());
				?>
				<big><b>New character was created successfully.</b></big><br>
				<form><input type='button' onClick="parent.location='admin_users_Create_New_Character.php'" value='OK'></form>
				<?php
			} else {
				?>
				<big><b>CREATE A NEW CHARACTER</b></big>
				</td>
				</tr>
				<tr>
				<td align=right valign=top>
				Name:
				<br>
				(Please DO NOT use names that would be out of context.)
				</td>
				<td align=left valign=top>
				<input type=text name="name" size="20" maxlength="20">
				</td>
				</tr>
				<tr>
				<td align=right>
				Age:
				</td>
				<td align=left>
				<input type=text name="age" size="4" maxlength="4">
				</td>
				</tr>
				<tr>
				<td align=right>
				Sex:
				</td>
				<td align=left>
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
				</select>
				</td>
				</tr>
				<tr>
				<td align=right>
				Race:
				</td>
				<td align=left>
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
				</select>
				</td>
				</tr>
				<tr>
				<td align=right>
				Class:
				</td>
				<td align=left>
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
				</select>
				</td>
				</tr>
				<tr>
				<td align=center colspan=2>
				Select Character Image
				<br>
				<table border=0 cellpadding=0 cellspacing=0>
				<tr>
				<td align=center>
				<img src="../images/icons/characters/character_1.gif">
				<br>
				<input CHECKED type="radio" name="image_path" value="images/icons/characters/character_1.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_2.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_2.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_3.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_3.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_4.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_4.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_5.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_5.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_6.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_6.gif">
				</td>
				</tr>
				<tr>
				<td align=center>
				<img src="../images/icons/characters/character_7.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_7.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_8.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_8.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_9.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_9.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_10.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_10.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_11.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_11.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_12.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_12.gif">
				</td>
				</tr>
				<tr>
				<td align=center>
				<img src="../images/icons/characters/character_13.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_13.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_14.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_14.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_15.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_15.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_16.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_16.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_17.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_17.gif">
				</td>
				<td align=center>
				<img src="../images/icons/characters/character_18.gif">
				<br>
				<input type="radio" name="image_path" value="images/icons/characters/character_18.gif">
				</td>
				</tr>
				</table>
				</td>
				</tr>
				<tr>
				<td align=center colspan=2>
				<br>
				<br>
				<input type="hidden" name="create_char" value="yes">
				<input type="button" onClick="verify();" value="Create Character">
				<form><input type='button' onClick="parent.location='admin_users_Create_New_Character.php'" value='Back'></form>
				<?php
			}
		}
		?>
		</td>
		</tr>
		</form>
	</table>

	</td>
	</tr>
</table>

</td>
</tr>
</table>

</body>
</html>
