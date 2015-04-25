<?php
include "header.php";
include_once "class_character.php"; # add include

$char_loc = 0;
$result = mysql_query ("SELECT * FROM phaos_characters WHERE id = '$PHP_PHAOS_CHARID'");
if ($row = mysql_fetch_array($result)) { $char_loc = $row["location"]; }
$shop_id = 0;
$result = mysql_query ("SELECT * FROM phaos_buildings WHERE location = '$char_loc'");
if ($row = mysql_fetch_array($result)) { $shop_id = $row["shop_id"]; }

// make sure this requested shop is at the players location
if (!shop_valid($char_loc, $shop_id)){
	echo $lang_markt["no_sell"].'</body></html>' ;
	exit;
}

// we use the character to determine opponent level
$character = new character($PHP_PHAOS_CHARID);

if ($character->stamina_points <= 0 || $character->hit_points <= 0) {
	echo $lang_comb["stam_noo"].'</body></html>' ;
	exit;
}
?>

<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
<tr>
<td align=center valign=top>
	
	<table border=0 cellspacing=5 cellpadding=0>
	<tr>
		<td align=center colspan=2><img src="lang/<?php echo $lang ?>_images/arena.png"></td>
	</tr>
	<tr>
	<td align=center colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width="100%">
	<tr>
	<td align=center valign=middle colspan=3 width="300">
		<big><b><?php echo $lang_arena["other"]; ?></b></big>
		<br>
		<br>
	</td>
	</tr>
	<tr>
		<td align=center><b>Name</b></td>
		<td align=center width="33%"><b>Level</b></td>
		<td align=center><b> &nbsp </b></td>
	</tr>
	<?php
	if(!@$num_limit){
		$num_limit = 0;
	}
	$number = 0;
	
	//FIXME: arena locations should be cleane from npcs: 
	//update phaos_characters set username='phaos_npc_arena' where username='phaos_merchant'
	$where = "location = '$char_loc' AND username LIKE 'phaos_npc_arena' AND xp <> 0";
	
	$query = "SELECT count(*) FROM phaos_characters WHERE $where";
	$result = mysql_query ($query);
	
	$min_arena = 9;
	@list($present) = mysql_fetch_row($result);
	$missing = $min_arena-$present;
	
	if($missing<1){
		$missing = rand(0,1)*rand(0,1);
	}
	
	$message = "";
	
	while( $missing>0 && $char_loc){
		$query = "SELECT * FROM phaos_opponents WHERE location='".$char_loc."' ORDER BY RAND() LIMIT 1";
		$result = mysql_query ($query);
		$blueprint = mysql_fetch_array($result);
		if(!$blueprint){
			$query = "SELECT * FROM phaos_opponents WHERE location='0' ORDER BY RAND() LIMIT 1";
			$result = mysql_query ($query);
			$blueprint = mysql_fetch_array($result);
			$message = "Please ask the arena administrator to hire some special opponents.";
		}
		if($blueprint){
			$maxlevel = 1+(int)((rand(1,5)*rand(1,5)+4)/9)+rand(0,$character->level);
			$level = rand(1,$maxlevel);
			$npc = new np_character_from_blueprint($blueprint,$level,'phaos_npc_arena');
			$npc->place($char_loc);
			--$missing;
		} else {
			$message= "No opponents found.";
			break;
		}
	}
	
	$query = "SELECT * FROM phaos_characters WHERE $where ORDER BY xp DESC LIMIT $num_limit,10";
	$result = mysql_query($query);
	$NA = false;
	if ($row = mysql_fetch_array($result)) {
		do {
			$number = $number+1;
			$opponent_id = $row["id"];
			$leader_name = $row["name"];
			$player_name = $row["username"];
			$leader_level = number_format($row["level"],0);
			if($number == "1" OR $number == "3" OR $number == "5" OR $number == "7" OR $number == "9") {$rbgc = "#004400";} else {$rbgc = "#000000";}
		
			//FIXME: leader_level should not be part of the GET request
			print ("<tr style=\"background:$rbgc;\"><td align=left>");
			/* ### getclan_sig ### */ getclan_sig($leader_name);
			print ("<a href=\"player_info.php?player_name=$player_name\" target=\"_blank\"><font color=#FFFFFF><b>$leader_name</b></font></a>
				</td>
				<td align=center>
				<b>$leader_level</b>
				</td>
				<td align=left>
				<input type='button' onClick=\"parent.location='combat.php?opp_type=char&oppchar=$player_name&charfrom=arena&opponent_id=$opponent_id&opponent_level=$leader_level'\" value='$lang_arena[fight]'>
				</td>
			</tr>");
		} while ($row = mysql_fetch_array($result));
	}
	if($number != "10") {
		do {
			$number = $number+1;
			if($number == "1" OR $number == "3" OR $number == "5" OR $number == "7" OR $number == "9") {$rbgc = "#004400";} else {$rbgc = "#000000";}
			print ("<tr style=\"background:$rbgc;\">
				<td align=left>
				<b>N/A</b>
				</td>
				<td align=center>
				<b>N/A</b>
				</td>
				<td align=center>
				<b>N/A</b>
				</td>
			</tr>");
			$NA = true;
		} while ($number < 10);
	}
	$next_ten = $num_limit+10;
	$prev_ten = $num_limit-10;
	?>
	</table>
	<?php
	if ($num_limit != 0) {
		print ("<input type='button' onClick=\"parent.location='arena.php?num_limit=$prev_ten'\" value='$lang_arena[prev]'> &nbsp | &nbsp");
	}
	
	if (!$NA) {
		print ("<input type='button' onClick=\"parent.location='arena.php?num_limit=$next_ten'\" value='$lang_arena[next]'>");
	}
	?>
	<br><?php echo $message; ?>
	<br>
	
	</td>
	</tr>
	<tr>
	<td align=center>
		<?php print ("<input type='button' onClick=\"parent.location='combat.php?opp_type=npc&loc=arena'\" value='$lang_arena[f_monster]'>"); ?>
	</td>
	</tr>
	</table>

</td>
</tr>
</table>

<?php include "footer.php"; ?>
