<?php
include "header.php"; 
include_once 'class_character.php';

$character=new character($PHP_PHAOS_CHARID);

shop_valid($character->location, $shop_id); // make sure the requested shop is where the player is

$reload= false;

if($character->gold >= 30 && @$_REQUEST['spend_night']) {
	$character->stamina_points += $character->max_stamina;
	$character->hit_points += (int)( $character->max_hp*0.75 );
	$character->gold -= 30;
	$reload= true;
}

if($character->gold >= 3 && @$_REQUEST['have_drink']) {
	$character->hit_points += $character->race=='Dwarf'?5:3;
	$character->stamina_points += ($character->race=='Human'?21:13);
	$character->gold -= 3;
	$reload= true;
}

if($reload) {
	if($character->hit_points > $character->max_hp) {$character->hit_points = $character->max_hp;}
	if($character->stamina_points > $character->max_stamina) {$character->stamina_points = $character->max_stamina;}

	//do updates for all actions
	$query = ("UPDATE phaos_characters
				SET hit_points = $character->hit_points, stamina = $character->stamina_points, gold = $character->gold
				WHERE id = '$character->id'");
	$req = mysql_query($query);
	if (!$req) {
		showError(__FILE__,__LINE__,__FUNCTION__,$query);
		exit;
	}
	refsidebar();
}
?>

<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
<tr>
<td align=center valign=top>

<table border=0 cellspacing=5 cellpadding=0 width="100%">
<tr>
<td align=center colspan=2>
<img src="lang/<?php echo $lang ?>_images/inn.png">
</td>
</tr>
<tr>
<td align=center colspan=2>
<table border=0 cellspacing=0 cellpadding=5 width="100%">
<tr>
<td align=left>
<b>Available Actions:</b>
</td>
</tr>
<tr>
<form action='inn.php' method='post'> 
<td align=left>
<input type='hidden' name='spend_night' value='yes'> 
<input type='hidden' name='shop_id' value='<?php echo $shop_id; ?>'> 
<button type='submit' style="width:250px;"><?php echo $lang_inn["spnd_night"]; ?></button>
</form>
</td>
</tr>
<tr>
<form action='inn.php' method='post'> 
<td align=left>
<input type='hidden' name='have_drink' value='yes'> 
<input type='hidden' name='shop_id' value='<?php echo $shop_id; ?>'> 
<button type='submit' style="width:250px;"><?php echo $lang_inn["hav_drnk"]; ?></button> 
</form>
</td>
</tr>
<tr>
<form action='game_1.php' method='post'>
<td align=left>
<button type='submit' style="width:250px;"><?php echo $lang_inn["ply_dic"]; ?></button>
</td>
</form>
</tr>
<tr>
<form action='game_2.php' method='post'>
<td align=left>
<button type='submit' style="width:250px;"><?php echo $lang_inn["ply_rps"]; ?></button>
</td>
</form>
</tr>
<tr>
<td align=left>
<br><br>
<b>Others in the inn:</b>
<p>
<?php

$npc_id = @$_POST['npc_id'];
$rumors_yn = @$_POST['rumors'];
$quests_yn = @$_POST['quests'];

if(!$npc_id) {
	// SELECT NPC TO TALK TO
	$result = mysql_query ("SELECT * FROM phaos_npcs WHERE location = '$$character->location'");
	if ($row = mysql_fetch_array($result)) {
		do {
			$npc_name = $row["name"];
			$npc_image = $row["image_path"];
			$id_npc = $row["id"];

			print ("<div align=center><form action=\"inn.php\" method=\"post\">");
			print ("<input type=\"hidden\" name=\"npc_id\" value=\"$id_npc\">");
			print ("<button type=\"submit\"><div align=\"center\">");
			if($npc_image != "") {print ("<img src=\"$npc_image\"><br>");}
			print ("$npc_name</div>");
			print ("</button><br>");
			print ("</form></div>");
		} while($row = mysql_fetch_array($result));
	} else {print ($lang_inn['inn_empty']);}
} else {
	// NPC CONVERSATION OPTIONS
	$result = mysql_query ("SELECT * FROM phaos_npcs WHERE id = '$npc_id'");
	if ($row = mysql_fetch_array($result)) {
		$npc_name = $row["name"];
		$npc_image = $row["image_path"];
		$id_npc = $row["id"];
		$rumors = $row["rumors"];
		$quest = $row["quest"];

		print ("<div align=center><button type=\"button\"><div align=\"center\">");
		if($npc_image != "") {print ("<img src=\"$npc_image\"><br>");}
		print ("$npc_name</div>");
		print ("</button></div>");

		print ("<form action=\"inn.php\" method=\"post\">");
		print ("<button type=\"submit\" style=\"border:none;text-align:left;\">".$lang_inn["heard_rumor"]);
		print ("</button>");
		print ("<input type=\"hidden\" name=\"rumors\" value=\"yes\">");
		print ("<input type=\"hidden\" name=\"npc_id\" value=\"$id_npc\">");
		print ("<input type=\"hidden\" name=\"$shop_id\" value=\"$$shop_id\">");
		print ("</form>");

		print ("<form action=\"inn.php\" method=\"post\">");
		print ("<button type=\"submit\" style=\"border:none;text-align:left;\">".$lang_inn["look_stg"]);
		print ("</button>");
		print ("<input type=\"hidden\" name=\"quests\" value=\"yes\">");
		print ("<input type=\"hidden\" name=\"npc_id\" value=\"$id_npc\">");
		print ("<input type=\"hidden\" name=\"$shop_id\" value=\"$$shop_id\">");
		print ("</form>");

		print ("<form action=\"inn.php\" method=\"post\">");
		print ("<button type=\"submit\" style=\"border:none;text-align:left;\">".$lang_inn["gdbye"]);
		print ("</button>");
		print ("<input type=\"hidden\" name=\"npc_id\" value=\"\">");
		print ("<input type='hidden' name='$shop_id' value='$shop_id'>");
		print ("</form>");
	}
}

print ("<p><hr>");

if($rumors_yn) {
	if($rumors == "") {print ("<big><b>".$lang_inn["sorry_no"]."</b></big>");} else {print ("<big><b>$rumors</b></big>");}
}
if($quests_yn) {
	if($quest == "0") {print ("<big><b>".$lang_inn["sorry_no"].".</b></big>");
} else {
	if (candoquest($character->id, $quest)==-1) {
		print ("<big><b>".$lang_inn["sorry_no_bus"]."</b></big>");
	}
	if (candoquest($character->id, $quest)==-2) {
		print ("<big><b>".$lang_inn["u2weak"]."</b></big>");
	}
	if (candoquest($character->id, $quest)==-99) {
		print ("<big><b>".$lang_inn["wai_2solv"]."</b></big>");
	}
	if (candoquest($character->id, $quest)==-3) {
		print ("<big><b>".$lang_inn["2many_war"].".</b></big>");
	}
	if (candoquest($character->id, $quest)==1) {
		addquest( $quest);
		print ("<big><b>".getquest($quest)."</b></big>\n");                        }
	}
}
?>
</td>
</tr>
</table>

</td>
</tr>
</table>

<br>
<br>
<a href="town.php"><?php print $lang_inn["return"]; ?></a>
</td>
</tr>
</table>

<?php include "footer.php"; ?>
