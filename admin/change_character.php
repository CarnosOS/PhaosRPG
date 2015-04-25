<?php
include("../config.php");
include("aup.php");

	function GetAppearanceList($folder, $current)
	{
		$sList = "";
		$handle = opendir($folder);
		$file = readdir($handle);
		while($file)
		{
			if(is_file($folder.$file)) {
				$sSelected = ($folder.$file == "../$current") ? " selected=\"selected\"" : "";
				$sList .= "<option value=\"".$folder.$file."\"$sSelected>".$file."</option>\n";
			}
			$file = readdir($handle);
		}
		closedir($handle);
		return $sList;
	}

?>
<html>
<head>
<title>WoP Admin Panel - Change Character</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">

<script type="text/javascript">

	function LoadAppearance(sPreviewID)
	{
		var oAppearance = window.document.getElementById("image_path");
		var sAppearanceImg = oAppearance.options[oAppearance.selectedIndex].value;

		window.document.getElementById(sPreviewID).src = sAppearanceImg;
	}

</script>
</head>
<body>
<?php
//if (isset($_POST['$changeme'])) { (***Commented out as this IF statement doesn't seem to work -ARADAN***)
if($changeme=="yes") {
	mysql_query("UPDATE phaos_characters SET location='$location', username='$username', name='$name', age='$age', sex='$sex', strength='$strength', dexterity='$dexterity', wisdom='$wisdom', constitution='$constitution', weapon='$weapon', hit_points='$hit_points', race='$race', class='$class', xp='$xp', level='$level', gold='$gold', armor='$armor', image_path='".str_replace("../", "", $image_path)."', stat_points='$stat_points', boots='$boots', gloves='$gloves', helm='$helm', shield='$shield', regen_time='$regen_time', dungeon_location='$dungeon_location', stamina='$stamina', stamina_time='$stamina_time', fight='$fight', defence='$defence', weaponless='$weaponless', lockpick='$lockpick', traps='$traps', rep_time='$rep_time', rep_points='$rep_points', rep_helpfull='$rep_helpfull', rep_generious='$rep_generious', rep_combat='$rep_combat' WHERE id='$id'");
	echo "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
		<tr>
	  	<td align=center valign=middle height=\"100%\" width=\"100%\">
			<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
		  	<tr>
		  	<td colspan=\"2\" align=center>
		  		<b>Character '$name' for user '$username' has been changed!</b>
		  	</td>
		  	</tr>
		  	<tr>
		  	<td colspan=\"2\" align=center>
		  		<form><input type='button' onClick=\"parent.location='admin_users_Edit_Users_Character.php'\" value='OK'></form>
		  	</td>
		  	</tr>
		  	</table>
		</td>
		</tr>
		</table>";
} else {
	$result = mysql_query("SELECT * FROM phaos_characters WHERE id=$character");
	while ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
		$username = $row["username"];
		$image_path = $row["image_path"];
		$name = $row["name"];
		$age = $row["age"];
		$sex = $row["sex"];
		$race = $row["race"];
		$class = $row["class"];
		
		$strength = $row["strength"];
		$dexterity = $row["dexterity"];
		$wisdom = $row["wisdom"];
		$constitution = $row["constitution"];
		
		$hit_points = $row["hit_points"];
		$stat_points = $row["stat_points"];
		$xp = $row["xp"];
		$level = $row["level"];
		$gold = $row["gold"];
		
		$rep_points = $row["rep_points"];
		$rep_helpfull = $row["rep_helpfull"];
		$rep_generious = $row["rep_generious"];
		$rep_combat = $row["rep_combat"];
		
		$weapon_id = $row["weapon"];
		$armor_id = $row["armor"];
		$boots_id = $row["boots"];
		$gloves_id = $row["gloves"];
		$helm_id = $row["helm"];
		$shield_id = $row["shield"];
		
		$regen_time = $row["regen_time"];
		$stamina = $row["stamina"];
		$stamina_time = $row["stamina_time"];
		$rep_time = $row["rep_time"];
		
		$fight = $row["fight"];
		$defence = $row["defence"];
		
		$weaponless = $row["weaponless"];
		$lockpick = $row["lockpick"];
		$traps = $row["traps"];
		
		$location_id = $row["location"];
		$dungeon_location_id = $row["dungeon_location"];
		
		
		if($sex == "") {
			$sex_sex = "Please select a sex type";
		} else {
			$result = mysql_query ("SELECT * FROM phaos_sex WHERE sex = '$sex'");
			if ($row = mysql_fetch_array($result)) {$sex_sex = $row["sex"];}
		}
		
		if($race == "") {
			$race_name = "Please select a race";
		} else {
			$result = mysql_query ("SELECT * FROM phaos_races WHERE name = '$race'");
			if ($row = mysql_fetch_array($result)) {$race_name = $row["name"];}
		}
		
		if($class == "") {
			$class_name = "Please select a class";
		} else {
			$result = mysql_query ("SELECT * FROM phaos_classes WHERE name = '$class'");
			if ($row = mysql_fetch_array($result)) {$class_name = $row["name"];}
		}
		
		if($weapon_id == "0") {
			$weapon_name = "None";
		} else {
			$result = mysql_query ("SELECT * FROM phaos_weapons WHERE id = '$weapon_id'");
			if ($row = mysql_fetch_array($result)) {$weapon_name = $row["name"];}
		}
		
		if($shield_id == "0") {
			$shield_name = "None";
		} else {
			$result = mysql_query ("SELECT * FROM phaos_shields WHERE id = '$shield_id'");
			if ($row = mysql_fetch_array($result)) {$shield_name = $row["name"];}
		}
		
		if($helm_id == "0") {
			$helm_name = "None";
		} else {
			$result = mysql_query ("SELECT * FROM phaos_helmets WHERE id = '$helm_id'");
			if ($row = mysql_fetch_array($result)) {$helm_name = $row["name"];}
		}
		
		if($gloves_id == "0") {
			$gloves_name = "None";
		} else {
			$result = mysql_query ("SELECT * FROM phaos_gloves WHERE id = '$gloves_id'");
			if ($row = mysql_fetch_array($result)) {$gloves_name = $row["name"];}
		}
		
		if($boots_id == "0") {
			$location_name = "None";
		} else {
			$result = mysql_query ("SELECT * FROM phaos_boots WHERE id = '$boots_id'");
			if ($row = mysql_fetch_array($result)) {$boots_name = $row["name"];}
		}
		
		if($location_id == "0") {
			$location_name = "Please select a location";
		} else {
			$result = mysql_query ("SELECT * FROM phaos_locations WHERE id = '$location_id'");
			if ($row = mysql_fetch_array($result)) {$location_name = $row["name"];}
		}
		
		if($dungeon_location_id == "0") {
			$dungeon_location_name = "None";
		} else {
			$result = mysql_query ("SELECT * FROM phaos_locations_dungeon WHERE id = '$dungeon_location_id'");
			if ($row = mysql_fetch_array($result)) {$dungeon_location_name = $row["name"];}
		}
		
	}
?>
<form action="change_character.php?changeme=yes" method="post">
<input type="hidden" name="id" value="<?php echo $id; ?>">
	<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align=center valign=middle height="100%" width="100%">
  	<table width="600" border="1" cellspacing="0" cellpadding="3">
   <tr style=background:#006600;>
   <td colspan="2">
		<div align="center"><b>Edit character for user <?php echo $username; ?></b></div>
	</td>
	</tr>
	<tr>
	<td colspan="2">
		<div align="center"><font color=red><b>NOTE: Please make sure you understand the values before you change them as altering the values incorrectly may cause problems with the users character.</b></font></div>
	</td>
	</tr>
	<tr>
	<td width="50%"><b><font color="#FFFFFF">User Name</font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="username" value="<?php echo $username; ?>">
		</font></b>
	</td>
	</tr>
	<tr>
	<td width="50%"><b><font color="#FFFFFF">Character Name</font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="name" value="<?php echo $name; ?>">
		</font></b>
	</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Age</font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="age" value="<?php echo $age; ?>">
		</font></b>
	</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Sex</font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<select name="sex">
		<?php
		$result = mysql_query ("SELECT * FROM phaos_sex ORDER BY id ASC");
		if ($row = mysql_fetch_array($result)) {
			do {
				$id_num = $row["id"];
				$sex_sex = $row["sex"];
				if ($sex == $sex_sex) {
					print ("<option value=\"$sex_sex\" selected >$sex_sex</option>");
				} else {
					print ("<option value=\"$sex_sex\">$sex_sex</option>");
				}
			} while ($row = mysql_fetch_array($result));
		}
		?>
		</select>
		</font></b>
	</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Race</font></b></td>
	<td width="50%"><b><font color="#FFFFFF">
		<select name="race">
		<?php
		$result = mysql_query ("SELECT * FROM phaos_races ORDER BY id ASC");
		if ($row = mysql_fetch_array($result)) {
			do {
				$id_num = $row["id"];
				$race_name = $row["name"];
				if ($race == $race_name) {
					print ("<option value=\"$race_name\" selected >$race_name</option>");
				} else {
					print ("<option value=\"$race_name\">$race_name</option>");
				}
			} while ($row = mysql_fetch_array($result));
		}
		?>
		</select>
		</font></b>
	</td>
   </tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Class</font></b></td>
	<td width="50%"><b><font color="#FFFFFF">
		<select name="class">
	  	<?php
		$result = mysql_query ("SELECT * FROM phaos_classes ORDER BY id ASC");
		if ($row = mysql_fetch_array($result)) {
			do {
				$id_num = $row["id"];
				$class_name = $row["name"];
				if ($class == $class_name) {
					print ("<option value=\"$class_name\" selected >$class_name</option>");
				} else {
					print ("<option value=\"$class_name\">$class_name</option>");
				}
			} while ($row = mysql_fetch_array($result));
		}
		?>
		</select>
		</font></b>
	</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Image</font></b></td>
	<td width="50%">
		<select name="image_path" id="image_path" onChange="javascript: LoadAppearance('current_appearance');">
			<?php echo GetAppearanceList("../images/icons/characters/", $image_path); ?>
		</select>
		<img id="current_appearance" name="current_appearance" src="../<?php echo $image_path; ?>" />
		
	</td>
	</tr>
	<tr>
	<td colspan="2"><center><b>ATTRIBUTES</b></center></td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Strength</font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="strength" value="<?php echo $strength; ?>">
		</font></b>
	</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Dexterity</font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="dexterity" value="<?php echo $dexterity; ?>">
		</font></b>
	</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Wisdom</font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="wisdom" value="<?php echo $wisdom; ?>">
		</font></b>
	</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Constitution</font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="constitution" value="<?php echo $constitution; ?>">
		</font></b>
	</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Stat Points to distribute</font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="stat_points" value="<?php echo $stat_points; ?>">
		</font></b>
	</td>
	</tr>
	<tr>
	<td colspan="2">&nbsp;</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Hit Points<br><i>(Maximum of <?php echo $constitution*6; ?>)<br>(Constitution * 6)</i></font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="hit_points" value="<?php echo $hit_points; ?>">
		</font></b>
	</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Experiance (XP)</font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="xp" value="<?php echo $xp; ?>">
		</font></b>
	</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Level</font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="level" value="<?php echo $level; ?>">
		</font></b>
	</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Stamina<br><i>(Maximum of <?php echo ($constitution+$strength)*5; ?>)<br>((Constitution + Strength) * 5)</i></font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="stamina" value="<?php echo $stamina; ?>">
		</font></b>
	</td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Gold</font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="gold" value="<?php echo $gold; ?>">
		</font></b>
	</td>
	</tr>
	<tr>
	<td colspan="2"><center><b>REPUTATION</b></center></td>
	</tr>
	<tr> 
	<td width="50%"><b><font color="#FFFFFF">Points to Distribute<br><i>(Maximum = 7)</i></font></b></td>
	<td width="50%"> <b><font color="#FFFFFF">
		<input type="text" name="rep_points" value="<?php echo $rep_points; ?>">
		</font></b>
	</td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Helpfullness</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="rep_helpfull" value="<?php echo $rep_helpfull; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Generious</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="rep_generious" value="<?php echo $rep_generious; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Combat Skills</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="rep_combat" value="<?php echo $rep_combat; ?>">
        </font></b></td>
    </tr>
    <tr>
    	<td colspan="2"><center><b>CURRENTLY EQUIPPED</b></center></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Weapon</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <select name="weapon">
        <option value="0">0. None</option>
	  		<?php

			  $result = mysql_query ("SELECT * FROM phaos_weapons ORDER BY id ASC");
			  if ($row = mysql_fetch_array($result)) {
				  do {
					  $id_num = $row["id"];
					  $weapon_name = $row["name"];
					  if ($weapon_id == $id_num) {
					  	print ("<option value=\"$id_num\" selected >$id_num. $weapon_name</option>");
					  } else {
					  	print ("<option value=\"$id_num\">$id_num. $weapon_name</option>");
					  }
				  } while ($row = mysql_fetch_array($result));
			  }
			  ?>
			  </select>
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Armor</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <select name="armor">
        <option value="0">0. None</option>
	  		<?php

			  $result = mysql_query ("SELECT * FROM phaos_armor ORDER BY id ASC");
			  if ($row = mysql_fetch_array($result)) {
				  do {
					  $id_num = $row["id"];
					  $armor_name = $row["name"];
					  if ($armor_id == $id_num) {
					  	print ("<option value=\"$id_num\" selected >$id_num. $armor_name</option>");
					  } else {
					  	print ("<option value=\"$id_num\">$id_num. $armor_name</option>");
					  }
				  } while ($row = mysql_fetch_array($result));
			  }
			  ?>
			  </select>
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Helm</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <select name="helm">
        <option value="0">0. None</option>
	  		<?php

			  $result = mysql_query ("SELECT * FROM phaos_helmets ORDER BY id ASC");
			  if ($row = mysql_fetch_array($result)) {
				  do {
					  $id_num = $row["id"];
					  $helm_name = $row["name"];
					  if ($helm_id == $id_num) {
					  	print ("<option value=\"$id_num\" selected >$id_num. $helm_name</option>");
					  } else {
					  	print ("<option value=\"$id_num\">$id_num. $helm_name</option>");
					  }
				  } while ($row = mysql_fetch_array($result));
			  }
			  ?>
			  </select>
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Gloves</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <select name="gloves">
        <option value="0">0. None</option>
	  		<?php

			  $result = mysql_query ("SELECT * FROM phaos_gloves ORDER BY id ASC");
			  if ($row = mysql_fetch_array($result)) {
				  do {
					  $id_num = $row["id"];
					  $gloves_name = $row["name"];
					  if ($gloves_id == $id_num) {
					  	print ("<option value=\"$id_num\" selected >$id_num. $gloves_name</option>");
					  } else {
					  	print ("<option value=\"$id_num\">$id_num. $gloves_name</option>");
					  }
				  } while ($row = mysql_fetch_array($result));
			  }
			  ?>
			  </select>
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Boots</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <select name="boots">
        <option value="0">0. None</option>
	  		<?php

			  $result = mysql_query ("SELECT * FROM phaos_boots ORDER BY id ASC");
			  if ($row = mysql_fetch_array($result)) {
				  do {
					  $id_num = $row["id"];
					  $boots_name = $row["name"];
					  if ($boots_id == $id_num) {
					  	print ("<option value=\"$id_num\" selected >$id_num. $boots_name</option>");
					  } else {
					  	print ("<option value=\"$id_num\">$id_num. $boots_name</option>");
					  }
				  } while ($row = mysql_fetch_array($result));
			  }
			  ?>
			  </select>
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Shield</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <select name="shield">
        <option value="0">0. None</option>
	  		<?php

			  $result = mysql_query ("SELECT * FROM phaos_shields ORDER BY id ASC");
			  if ($row = mysql_fetch_array($result)) {
				  do {
					  $id_num = $row["id"];
					  $shield_name = $row["name"];
					  if ($shield_id == $id_num) {
					  	print ("<option value=\"$id_num\" selected >$id_num. $shield_name</option>");
					  } else {
					  	print ("<option value=\"$id_num\">$id_num. $shield_name</option>");
					  }
				  } while ($row = mysql_fetch_array($result));
			  }
			  ?>
			  </select>
        </font></b></td>
    </tr>
    <tr>
    	<td colspan="2"><center><b>REGENERATION</b></center></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Regen Time</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="regen_time" value="<?php echo $regen_time; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Stamina Time</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="stamin_time" value="<?php echo $stamina_time; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Reputation Time</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="rep_time" value="<?php echo $rep_time; ?>">
        </font></b></td>
    </tr>
    <tr>
    	<td colspan="2"><center><b>SKILLS</b></center></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Fight</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="fight" value="<?php echo $fight; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Defence</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="defence" value="<?php echo $defence; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Weaponless</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="weaponless" value="<?php echo $weaponless; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Lock Pick</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="lockpick" value="<?php echo $lockpick; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Dissarm Traps</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="traps" value="<?php echo $traps; ?>">
        </font></b></td>
    </tr>
    <tr>
    	<td colspan="2"><center><b>LOCATIONS</b></center></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Location</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <select name="location">
			  <?php
			  $result = mysql_query ("SELECT * FROM phaos_locations ORDER BY id ASC");
			  if ($row = mysql_fetch_array($result)) {
				  do {
					  $id_num = $row["id"];
					  $location_name = $row["name"];
					  if ($location_id == $id_num) {
					  	print ("<option value=\"$id_num\" selected >$id_num. $location_name</option>");
					  } else {
					  	print ("<option value=\"$id_num\">$id_num. $location_name</option>");
					  }
				  } while ($row = mysql_fetch_array($result));
			  }
			  ?>
			  </select>
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Dungeon Location</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <select name="dungeon_location">
			  <option value="">0. None</option>
			  <?php
			  $result = mysql_query ("SELECT * FROM phaos_locations_dungeon ORDER BY id ASC");
			  if ($row = mysql_fetch_array($result)) {
				  do {
					  $id_num = $row["id"];
					  $dungeon_location_name = $row["name"];
					  if ($dungeon_location_id == $id_num) {
					  	print ("<option value=\"$id_num\" selected >$id_num. $dungeon_location_name</option>");
					  } else {
					  	print ("<option value=\"$id_num\">$id_num. $dungeon_location_name</option>");
					  }
				  } while ($row = mysql_fetch_array($result));
			  }
			  ?>
			  </select>
			  </font></b></td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <div align="center"> 
          <input type="submit" name="submit" value="Change"> 
          <input type="button" onClick="parent.location='delete_users_character.php?character=<?php echo $character; ?>'" value="Delete this Character"> 
          <input type="button" onClick="parent.location='admin_users_Edit_Users_Character.php'" value="Back to list">
      	</div>
      </td>
    </tr>
  </table>
  </td>
  </tr>
  </table>
</form>
<?php
}
?>
</body>
</html>
