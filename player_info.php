<?php
include "config.php";
include "class_character.php"; # add include
include_once 'include_lang.php';
?>

<html>
<head>
<title><?php echo "$SITETITLE"; ?></title>
<link rel=stylesheet type=text/css href=styles/phaos.css>

</head>
<body>

<?php # add find player ?>
<form enctype="multipart/form-data" method="post" action="">
<input type="text" name="player_name" size="20" maxlength="20">
<input type="submit" value="Find">
</form>

<?php
$login_result = mysql_query ("SELECT * FROM phaos_users WHERE username = '$player_name'");
if ($row = mysql_fetch_array($login_result)) { $char_last_login = $row["login_dat"]; }
	
	
if($rep_update == "yes") {
	$result = mysql_query ("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
	if ($row = mysql_fetch_array($result)) {
		$rep_points = $row["rep_points"];
	}
	
$result = mysql_query ("SELECT * FROM phaos_characters WHERE username = '$player_name'");
if ($row = mysql_fetch_array($result)) {
		$char_rep_helpfull = $row["rep_helpfull"];
		$char_rep_generious = $row["rep_generious"];
		$char_rep_combat = $row["rep_combat"];
	}
	
	if(isset($rate_helpfull) && $rep_points > 0) {
		print ("<center><big>".$lang_play["dis_1"]."$player_name</big></center>");
		if($rate_helpfull == "up") {
			$char_rep_helpfull=$char_rep_helpfull+1;
		} else {
			$char_rep_helpfull=$char_rep_helpfull-1;
		}
		$rep_points=$rep_points-1;
	}
	if(isset($rate_generious) && $rep_points > 0) {
		print ("<center><big>".$lang_play["dist_2"]."$player_name</big></center>");
		if($rate_generious == "up") {
			$char_rep_generious=$char_rep_generious+1;
		} else {
			$char_rep_generious=$char_rep_generious-1;
		}
		$rep_points=$rep_points-1;
	}
	if(isset($rate_combat) && $rep_points > 0) {
		print ("<center><big>".$lang_play["dist_3"]."$player_name</big></center>");
		if($rate_combat == "up") {
			$char_rep_combat=$char_rep_combat+1;
		} else {
			$char_rep_combat=$char_rep_combat-1;
		}
		$rep_points=$rep_points-1;
	}
	
	mysql_query ("UPDATE phaos_characters SET rep_helpfull='$char_rep_helpfull', rep_generious='$char_rep_generious', rep_combat='$char_rep_combat' WHERE username = '$player_name'");
	mysql_query ("UPDATE phaos_characters SET rep_points='$rep_points' WHERE username = '$PHP_PHAOS_USER'");
}

$result = mysql_query ("SELECT * FROM phaos_characters WHERE username = '$player_name' OR name = '$char_name'");
if ($row = mysql_fetch_array($result)) {
	$player_name = $row["username"];
	$char_name = $row["name"];
	$char_age = $row["age"];
	$char_race = $row["race"];
	$char_sex = $row["sex"];
	$char_class = $row["class"];
	$char_image = $row["image_path"];
	$char_str = $row["strength"];
	$char_dex = $row["dexterity"];
	$char_wis = $row["wisdom"];
	$char_con = $row["constitution"];
	$char_xp = $row["xp"];
	$char_level = $row["level"];
	$char_rep_helpfull = $row["rep_helpfull"];
	$char_rep_generious = $row["rep_generious"];
	$char_rep_combat = $row["rep_combat"];
	
	$result = mysql_query ("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
	if ($row = mysql_fetch_array($result)) {
		$rep_points = $row["rep_points"];
	}
} else {
	$char_name = $lang_na;
	$char_age = $lang_na;
	$char_race = $lang_na;
	$char_sex = $lang_na;
	$char_class = $lang_na;
	$char_image = $lang_na;
	$char_str = $lang_na;
	$char_dex = $lang_na;
	$char_wis = $lang_na;
	$char_con = $lang_na;
	$char_xp = $lang_na;
	$char_level = $lang_na;
	$char_rep_helpfull = $lang_na;
	$char_rep_combat =  $lang_na;
}
?>

<table border=0 cellspacing=5 cellpadding=0 width="100%" height="100%">
<tr>
<td align=center valign=middle>
<big><b><?php echo $lang_play["info"]; ?></b></big>
<table border=0 cellspacing=3 cellpadding=0>
<tr>
<td rowspan=19 valign=top align=right>
<img src="<?php print $char_image; ?>"> &nbsp
</td>
<?php //Added by dragzone--- ?>
<tr>
<td align=right>
<b><?php echo $lang_reg["user"] ?>: &nbsp</b>
</td>
<td>
<b><?php print $player_name; ?> &nbsp</b>
</td>
</tr>
<?php //-------------------- ?>
<tr>
<td align=right>
<b><?php echo $lang_name; ?>: &nbsp</b>
</td>
<td>
<?php /* ### getclan_sig ### */ getclan_sig($char_name);?> <b><?php print $char_name; ?> &nbsp</b>
</td>
</tr>
<tr>
<td align=right>
<b><?php echo $lang_age; ?>: &nbsp</b>
</td>
<td>
<b><?php print $char_age; ?> &nbsp</b>
</td>
</tr>
<tr>
<td align=right>
<b><?php echo $lang_sex; ?>: &nbsp</b>
</td>
<td>
<b><?php print $char_sex; ?> &nbsp</b>
</td>
</tr>
<tr>
<td align=right>
<b><?php echo $lang_race; ?>: &nbsp</b>
</td>
<td>
<b><?php print $char_race; ?> &nbsp</b>
</td>
</tr>
<tr>
<td align=right>
<b><?php echo $lang_class; ?>: &nbsp</b>
</td>
<td>
<b><?php print $char_class; ?> &nbsp</b>
</td>
</tr>
<tr>
<td align=right>
<b><?php echo $lang_str; ?>: &nbsp</b>
</td>
<td>
<b><?php print $char_str; ?> &nbsp</b>
</td>
</tr>
<tr>
<td align=right>
<b><?php echo $lang_dex; ?>: &nbsp</b>
</td>
<td>
<b><?php print $char_dex; ?> &nbsp</b>
</td>
</tr>
<tr>
<td align=right>
<b><?php echo $lang_wis; ?>: &nbsp</b>
</td>
<td>
<b><?php print $char_wis; ?> &nbsp</b>
</td>
</tr>
<tr>
<td align=right>
<b><?php echo $lang_cons; ?>: &nbsp</b>
</td>
<td>
<b><?php print $char_con; ?> &nbsp</b>
</td>
</tr>
<?php # add player level info ?>
<tr>
<td align=right>
<b><?php echo $lang_level; ?>: &nbsp</b>
</td>
<td>
<b><?php print $char_level; ?> &nbsp</b>
</td>
</tr>
<tr>
<td align=right>
<b><?php echo $lang_expe; ?>: &nbsp</b>
</td>
<td>
<b><?php print $char_xp; ?> &nbsp</b>
</td>
</tr>
<?php //Added by dragzone--- ?>
<tr>
<td align=right>
<b><?php echo $lang_added["last_login"]; ?>: &nbsp</b>
</td>
<td>
<b><?php if(substr($char_last_login,0,10) == '0000-00-00') {echo 'n/a';} else {echo substr($char_last_login,0,10);} ?> &nbsp</b>
</td>
</tr>
<?php //-------------------- ?>
<tr>
<td align=center colspan=2><br><br><big><b><?php echo $lang_char["rep"]; ?></b></big></td>
</tr>
<td>
<?php
if($rep_points > 0 && $player_name!=$PHP_PHAOS_USER) {
?>
</td>
</tr>
<tr>
<td align=center colspan=3><?php echo $lang_char["have_rep"]; ?><br></td>
</tr>
<tr>
<td>&nbsp</td>
<td>&nbsp</td>
<td style="background:#004400;" align=center colspan=2></b><?php echo $lang_play["rate"]; ?>&nbsp</b></td>
</tr>
<tr>
<td align=right><?php echo $lang_char["help"]; ?>: &nbsp</td>
<td><?php print $char_rep_helpfull; ?> &nbsp</td>
<td align=center><a href="player_info.php?player_name=<?php print $player_name; ?>&rep_update=yes&rate_helpfull=up">+ &nbsp</a></td>
<td align=center><a href="player_info.php?player_name=<?php print $player_name; ?>&rep_update=yes&rate_helpfull=down">- &nbsp</a></td>
</tr>
<tr>
<td></td>
<td align=right><?php echo $lang_char["gen"]; ?>: &nbsp</td>
<td><?php print $char_rep_generious; ?> &nbsp</td>
<td align=center><a href="player_info.php?player_name=<?php print $player_name; ?>&rep_update=yes&rate_generious=up">+ &nbsp</a></td>
<td align=center><a href="player_info.php?player_name=<?php print $player_name; ?>&rep_update=yes&rate_generious=down">- &nbsp</a></td>
</tr>
<tr>
<td></td>
<td align=right><?php echo $lang_char["com"]; ?>: &nbsp</td>
<td><?php print $char_rep_combat; ?> &nbsp</td>
<td align=center><a href="player_info.php?player_name=<?php print $player_name; ?>&rep_update=yes&&rate_combat=up">+ &nbsp</a></td>
<td align=center><a href="player_info.php?player_name=<?php print $player_name; ?>&rep_update=yes&rate_combat=down">- &nbsp</a></td>
</tr>
<tr>
<td></td>
<td align=right><hr><?php echo $lang_char["total"]; ?>:&nbsp<hr></td>
<td><hr><?php print $char_rep_helpfull+$char_rep_generious+$char_rep_combat; ?>&nbsp<hr></td>
</tr>
<?php
} else {
?>
<tr>
<td align=right><?php echo $lang_char["help"]; ?>: &nbsp</td>
<td><?php print $char_rep_helpfull; ?> &nbsp</td>
</tr>
<tr>
<td align=right><?php echo $lang_char["gen"]; ?>: &nbsp</td>
<td><?php print $char_rep_generious; ?> &nbsp</td>
</tr>
<tr>
<td align=right><?php echo $lang_char["com"]; ?>: &nbsp</td>
<td><?php print $char_rep_combat; ?> &nbsp</td>
</tr>
<tr>
<td></td>
<td align=right><hr><?php echo $lang_char["total"]; ?>:&nbsp<hr></td>
<td><hr><?php print $char_rep_helpfull+$char_rep_generious+$char_rep_combat; ?>&nbsp<hr></td>
</tr>
<?php
}
?>
<br>
<tr>
<td></td>
<td align=center colspan=2>
<br>
<?php if($player_name!=$PHP_PHAOS_USER) { ?>
<a href="message.php?action=compose&to=<?php echo $player_name; ?> "><?php echo $lang_added["ad_user-msg"]; ?></a>
<?php } ?>
<br>
<br>
<button type="button" onclick="window.close();"><?php echo $lang_play["close"]; ?></button>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
