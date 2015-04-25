<?php
require_once "class_character.php";
// Modified to heal during non-acitvity

$current_time = time();
$clear = 0; // ensure post data has to be sent!
	    	   // use $clear=1 if $_POST Data has been used

//getting Character Data (one Time at all!)
$character=new character($PHP_PHAOS_CHARID);

//if(!$character->name == "N/A") {	
	$character->auto_heal();
	$character->auto_stamina();
	$character->auto_reputation();
	
	//echo "<br>:".$character->time_since_regen;	
	
	// start Drink Potion Code
	$drink_potion = @$_POST["drink_potion"];
	if($drink_potion) {
		$clear = 1;
		$character->drink_potion();
	}
	// end Drink Poption Code

	// start Deploying Points to Stats
	$str_add = @$_POST['strength'];
	$dex_add = @$_POST['dexterity'];
	$wis_add = @$_POST['wisdom'];
	$con_add = @$_POST['constitution'];
	
	if ($str_add == "add" AND $character->stat_points > 0) {
		if(!$character->level_up("strength")) {
			echo "an error has occured";
			exit();
		}
		$clear = 1;
	}

	if($dex_add == "add" AND $character->stat_points > 0) {
		if(!$character->level_up("dexterity")) {
			echo "an error has occured";
			exit();			
		}
		$clear = 1;
	}
	
	if($wis_add == "add" AND $character->stat_points > 0) {
		if(!$character->level_up("wisdom")) {
			echo "an error has occured";
			exit();
		}
		$clear = 1;
	}
	
	if($con_add == "add" AND $character->stat_points > 0) {
		if (!$character->level_up("constitution")) {
			echo "an error has occured";
			exit();
		}
		$clear = 1;
	}
	// end Deploying Points to Stats

	//$overtake=($character->level()+1);
	//$next_lev = ($overtake);

	if($character->level() < 1000) {
		$needed_xp = mysql_query("SELECT * FROM phaos_level_chart WHERE level = '".($character->level()+1)."'");
		if ($row = mysql_fetch_array($needed_xp)) {
			$char_next_lev_xp = $row["xp_needed"];
		}
	
		if($character->xp > $char_next_lev_xp) {
			$query = ("UPDATE phaos_characters SET stat_points = '".(1+$character->stat_points)."', level = '".(1+$character->level())."' WHERE username = '$PHP_PHAOS_USER'");
			$req = mysql_query($query);
			if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}
			$clear = 1;
		}
	}
//}

if (($clear == 1) and !($character->name == $lang_na)) { // if $_POST data has been sent and used, clear $_POST!
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=clear_side_post.php\">";
	$clear = 0;
}

$stamina_color= ($character->fight_reduction()>0.99 ? '#FFFFFF':( ($character->fight_reduction()>0.66? '#00FF00' : ($character->fight_reduction()<0.34?'#FF0000':'#FFFF00'))));
?>
<br><p align="center"><?php
$result2 = mysql_query("select * from phaos_mail where UserTo='$PHP_PHAOS_USER' AND status='unread'");
$result = mysql_num_rows($result2);
if ($result > 0) {
// changed by dragzone---
	echo "<a href=\"message.php?action=inbox\">[ ".$result." ] ".$lang_mssg["un_co"]."</a><img src=\"images/unread.gif\" boreder=0><br>";
//-----------------------
}
else {
	echo "[ 0 ] ".$lang_mssg["un_co"]."<img src=\"images/read.gif\" boreder=0><br>";
	}
?></p>
<p>
<table border=0 cellspacing=0 cellpadding=0 width="100%">
<tr>
<td align=center valign=middle colspan=2>
<?php echo $lang_name; ?>:
<?php /* ### getclan_sig ### */ getclan_sig($character->name);?> <big><b><?php echo $character->name; ?></b></big>
	<p>
	<big><b><?php echo $lang_side["stats"]; ?></b></big>
	<br>
	<br>
</td>
</tr>
<tr>
<td align=right valign=middle>
	<b><?php echo $lang_added["sid_hp"]; ?></b> <img src="images/icons/hp.gif" align="middle" alt="<?php echo $lang_hp; ?>"> &nbsp
</td>
<td align=left valign=middle>
	<b><?php print $character->hit_points; ?>/<?php print $character->max_hp; ?></b>
	</td>
	</tr>
<tr>
<form method=post action=side_bar.php>
<td align=right valign=middle>
	<b><?php echo $lang_cons; ?></b> <img src="images/icons/constitution.gif" align="middle" alt="<?php echo $lang_cons; ?>"> &nbsp
</td>
<td align=left valign=middle>
	<b><?php print $character->constitution; ?></b>
	<?php
	if($character->stat_points > 0) {
	print ("<button type=\"submit\" style=\"border:none;width:10;\">+</button>");
	}
	?>
	<input type="hidden" name="constitution" value="add">
</td>
</form>
</tr>
<tr>
<td align=right valign=middle>
	<b><?php echo $lang_str; ?><b> <img src="images/icons/strength.gif" align="middle" alt="<?php echo $lang_str; ?>"> &nbsp
</td>
<form method=post action=side_bar.php>
<td align=left valign=middle>
	<b><?php print $character->strength; ?></b>
	<?php
	if($character->stat_points > 0) {
	print ("<button type=\"submit\" style=\"border:none;width:10;\">+</button>");
	}
	?>
	<input type="hidden" name="strength" value="add">
</td>
</form>
</tr>
<tr>
<td align=right valign=middle>
	<b><?php echo $lang_added["sid_dex"] ?></b> <img src="images/icons/dexterity.gif" align="middle" alt="<?php echo $lang_dex; ?>"> &nbsp
</td>
<form method=post action=side_bar.php>
<td align=left valign=middle>
	<b><?php print $character->dexterity; ?></b>
	<?php
	if($character->stat_points > 0) {
	print ("<button type=\"submit\" style=\"border:none;width:10;\">+</button>");
	}
	?>
	<input type="hidden" name="dexterity" value="add">
</td>
</form>
</tr>
<tr>
<td align=right valign=middle>
	<b><?php echo $lang_wis; ?></b> <img src="images/icons/wisdom.gif" align="middle" alt="<?php echo $lang_wis; ?>"> &nbsp
</td>
<form method=post action=side_bar.php>
<td align=left valign=middle>
	<b><?php print $character->wisdom; ?></b>
	<?php
	if($character->stat_points > 0) {
	print ("<button type=\"submit\" style=\"border:none;width:10;\">+</button>");
	}
	?>
	<input type="hidden" name="wisdom" value="add">
</td>
</form>
</tr>
<tr>
<td align=right valign=middle>
	<b><?php echo $lang_tot_ac; ?></b> <img src="images/icons/wins.gif" align="middle" alt="<?php echo $lang_tot_ac; ?>"> &nbsp
</td>
<td align=left valign=middle>
	<b><?php print $character->ac(); ?></b>
</td>
</tr>
	<tr>
	<td align=right valign=middle>
	<b><?php echo $lang_gold; ?></b> <img src="images/icons/gold.gif" align="middle" alt="<?php echo $lang_gold; ?>"> &nbsp
</td>
<td align=left valign=middle>
	<b><?php print $character->gold; ?></b>
</td>
</tr>

<tr>
<td>
<p>&nbsp</p>
<hr>
<p>&nbsp</p>
</td>
<td>
<hr>
</td>
</tr>
<tr>
	<td align=right valign=middle>
	<b><?php echo $lang_level; ?></b> &nbsp
</td>
<td align=left valign=middle>
	<b><?php print $character->level; ?></b>
</td>
</tr>
<tr>
<td>
<p>&nbsp</p>
</td>
</tr>
<tr>
<td align=right valign=middle>
	<b><?php echo $lang_expe; ?>:</b> &nbsp
</td>
<td align=left valign=middle>
	<b><?php print $character->xp; ?>/<br><?php print $char_next_lev_xp; ?></b>
</td>
</tr>
<tr>
<td>
<p>&nbsp</p>
</td>
</tr>
<tr>
<td align=right valign=middle>
	<b><?php echo "$lang_stamina:" ?> &nbsp</b>
</td>
<td>
	<b style="color:<?php echo $stamina_color; ?>"><?php print $character->stamina_points."/".$character->max_stamina;?></b>
</td>
</tr>
<tr>
<td align=right valign=middle>
	<br><b><?php echo $lang_avpo; ?>:</b> &nbsp
</td>
<td align=left valign=middle>
	<br><b>&nbsp;<?php print $character->stat_points; ?></b>
</td>
</tr>
<tr>
<td>
<p>&nbsp</p>
<hr>
<p>&nbsp</p>
</td>
<td>
<hr>
</td>
</tr>
<tr>
<td align=middle valign=middle colspan=2>
	<b><?php echo $lang_shop["inv"]; ?>:</b>
</td>
</tr>
<tr>
<td align=center valign=middle colspan=2>
	<b><?php print $character->invent_count()."/".$character->max_inventory; ?> Item(s)</b>
</td>
</tr>
<tr>
<tr>
<td>
<p>&nbsp</p>
</td>
</tr>
<td align=center valign=middle colspan=2 style="padding:4px;">
<form method=post action="<?php print $PHP_SELFl; ?>" style="margin:0px;">
	<input type="hidden" name="drink_potion" value="Y">
	<!-- <button style="border:none;" type=submit><?php echo $lang_heal; ?></button> -->
	<button type=submit><?php echo $lang_added["ad_drink-first-potion"]; ?></button>
</form>
</td>
</tr>
</table>
<br>

<div align=center>

<?php echo $lang_tu; ?>:
<br>
<?php 
$reg_use = "SELECT * FROM phaos_users";
$numresults = mysql_query($reg_use);
$registered_users = mysql_num_rows($numresults);
print $registered_users; 

$res = mysql_query("SELECT * FROM phaos_characters where username='phaos_npc'");
$totmobs = mysql_num_rows($res);
print "<br>($totmobs)"; 
?>
<p>
<b><?php echo $lang_mus; ?>:</b>
<br>
<a href="music/index.php?play_music=YES" target="_blank"><?php echo $lang_plays; ?></a>
