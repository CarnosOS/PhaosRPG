<?php
//Line 390 - 397 changed by dragzone
include "header.php";
include_once "class_character.php";
include_once "items.php";

$refsidebar= false;

$character=new character($PHP_PHAOS_CHARID);

include_once "location_actions.php";
$dropped= drop_actions($character);

// if you've clicked to put something up for market sale
if(@$_POST['market_item'] == "yes") {
	$res=mysql_query("SELECT * FROM phaos_char_inventory WHERE id = '$_POST[char_inv_id]' AND username = '$_COOKIE[PHP_PHAOS_USER]'");
	if ($row = mysql_fetch_array($res)) {
		if(empty($sell_to)){$sell_to ="all";}
		if($_POST['asking_price']<=0){
			$sell_to= '';//stop selling
		}

		$sql=("UPDATE phaos_char_inventory SET asking_price = '$_POST[asking_price]',
								   sell_to = '$sell_to'
							     WHERE id = '$_POST[char_inv_id]'");
		mysql_query($sql) or die("<B>Error ".mysql_errno()." :</B> ".mysql_error()."");
	}
}

// clear out any bad database rows
mysql_query("DELETE FROM phaos_char_inventory WHERE item_id='' AND type='' ");

// find out if character is at a blacksmith, item shop, or magic shop
$res=mysql_query("SELECT * FROM phaos_buildings WHERE location = '".$character->location."' ");
$res OR die("<B>Error ".mysql_errno()." :</B> ".mysql_error()."");

$blacksmith_yn= false;
$item_yn= false;
$magicshop_yn= false;
while ($row=mysql_fetch_array($res)) {
	$blacksmith_yn	|= $row["name"] == "Blacksmith";
	$item_yn	|= $row["name"] == "Item Shop";
	$magicshop_yn	|= $row["name"] == "Magic Shop";
	//echo "$row[name] $blacksmith_yn,$item_yn,$magicshop_yn<br>";
}

$shopForItemtype= array();
function setShopForItemtype($its,$value){
	global $shopForItemtype;
	foreach($its as $it){
		$shopForItemtype[$it]= $value;
	}
}

setShopForItemtype( array( "armor","weapon","boots","shield","helm","gloves"), $blacksmith_yn);
setShopForItemtype( array( "potion" ), $item_yn);
setShopForItemtype( array( "spell_items"), $magicshop_yn);

// did you click to delete your character?
if(@$_POST['delete'] == "yes") {
	$character->kill_character();
	$refsidebar= true;
}

// SELL AN ITEM
if(@$sell_id == "Y") {
	$result = mysql_query ("SELECT * FROM phaos_char_inventory WHERE id = '$id'");
	if ($row = mysql_fetch_array($result)) {
		$ite_type = $row["type"];
	}

	if($ite_type == "potion") {
		if($item_yn) {
			$priceresult = mysql_query("SELECT * FROM phaos_potion WHERE id = '$item_id'");
			if ($row = mysql_fetch_array($priceresult)) {$sell_price = $row["sell_price"];}
		} else {$sell_price = 0;}
	} elseif ($ite_type == "weapon") {
		if($blacksmith_yn) {
			$priceresult = mysql_query("SELECT * FROM phaos_weapons WHERE id = '$item_id'");
			if ($row = mysql_fetch_array($priceresult)) {$sell_price = $row["sell_price"];}
		} else {$sell_price = 0;}
	} elseif ($ite_type == "armor") {
		if($blacksmith_yn) {
			$priceresult = mysql_query("SELECT * FROM phaos_armor WHERE id = '$item_id'");
			if ($row = mysql_fetch_array($priceresult)) {$sell_price = $row["sell_price"];}
		} else {$sell_price = 0;}
	} elseif ($ite_type == "boots") {
		if($blacksmith_yn) {
			$priceresult = mysql_query("SELECT * FROM phaos_boots WHERE id = '$item_id'");
			if ($row = mysql_fetch_array($priceresult)) {$sell_price = $row["sell_price"];}
		} else {$sell_price = 0;}
	} elseif ($ite_type == "gloves") {
		if($blacksmith_yn) {
			$priceresult = mysql_query("SELECT * FROM phaos_gloves WHERE id = '$item_id'");
			if ($row = mysql_fetch_array($priceresult)) {$sell_price = $row["sell_price"];}
		} else {$sell_price = 0;}
	} elseif ($ite_type == "helm") {
		if($blacksmith_yn) {
			$priceresult = mysql_query("SELECT * FROM phaos_helmets WHERE id = '$item_id'");
			if ($row = mysql_fetch_array($priceresult)) {$sell_price = $row["sell_price"];}
		} else {$sell_price = 0;}
	} elseif ($ite_type == "shield") {
		if($blacksmith_yn) {
			$priceresult = mysql_query("SELECT * FROM phaos_shields WHERE id = '$item_id'");
			if ($row = mysql_fetch_array($priceresult)) {$sell_price = $row["sell_price"];}
		} else {$sell_price = 0;}
	} elseif ($ite_type == "spell_items") {
		if($magicshop_yn) {
			$priceresult = mysql_query("SELECT * FROM phaos_spells_items WHERE id = '$item_id'");
			if ($row = mysql_fetch_array($priceresult)) {$sell_price = $row["sell_price"];}
		} else {$sell_price = 0;}
	}

	if($sell_price>0) {
		$sell_gold = $sell_price+$character->gold;
		$query = "UPDATE phaos_characters SET gold = '$sell_gold' WHERE username = '$_COOKIE[PHP_PHAOS_USER]'";
		$req = mysql_query($query);
		if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}

		$character->unequipt($item_type,$item_id);

		$query = "DELETE FROM phaos_char_inventory WHERE id = '$id'";
		$result = mysql_query($query) or die ("Error in query: $query. " .mysql_error());

		$sell_msg= $lang_char['soldtoshop'];
	} else {
		$sell_msg= $lang_char['noshop'];
	}
	$refsidebar= true;
}


// DRINK POTION
if($_GET[drink_potion] == "Y") {
	$result = mysql_query ("SELECT type FROM phaos_char_inventory WHERE id = '$_GET[id]'");
	if ($row = mysql_fetch_array($result)) {
		if ($row["type"] == "potion") {
			$character->drink_potion2($_GET[id]);
		}
	}
	$refsidebar= true;
}

// EQUIP AN ITEM
if(@$equip_id){
	if($equip_id == "Y") {
		if ($character->equipt($item_type,$item_id)){
			$refsidebar= true;
			//echo "equipping successfull";
		} else {
			//echo "not equipped";
		}
	}

	// UNEQUIP AN ITEM
	if($equip_id == "N") {
		if ($character->unequipt($item_type,$item_id)){
			$refsidebar = true;
			//echo "unequipping successfull";
		} else {
			//echo "not unequipped";
		}
	}
}

if($dropped>0) {
	$refsidebar= true;
	?><div align=center><?php
	echo $dropped." ".$lang_char['itemsdropped'];
	?></div><?php
}

if($refsidebar){
	refsidebar();
	$refsidebar= false;
}
?>

<table border=0 cellspacing=0 cellpadding=0 width="100%">
<tr>
<td align=center valign=top colspan=2>
<table border=0 cellspacing=5 cellpadding=0>
<tr>
<td align=center colspan=2>
<img src="lang/<?php echo $lang ?>_images/character.png">
</td>
</tr>
<?php
// MAKE SURE EQUIPPED ARMOR IS STILL IN INVENTORY
if ($numerrors=$character->checkequipment()){
	echo $numerrors." ".$lang_char["eq_dropped"];
}
// Take Care of Skill-Levels!!
if ($numerrors=$character->inv_skillmatch()){
	echo $numerrors." ".$lang_char["ins_skill"];
}

if(@$sell_msg){
	echo "<center>$sell_msg</center>";
}

if($character->name != "" AND $character->image != "") {
	?>
	<tr>
	<td align=center colspan=2>
	<big><b><?php echo $lang_char["info"]; ?></b></big>
	<br>
	<br>
	<table border=0 cellspacing=0 cellpadding=0 width="60%"><?php # change width="50%" -> width="60%"?>
	<tr>
	<td rowspan=4 valign=middle align=right>
	<img src="<?php print $character->image; ?>"> &nbsp
	</td>
	<td align=right>
	<b><?php echo $lang_name; ?>: &nbsp</b>
	</td>
	<td>
	<?php /* ### getclan_sig ### */ getclan_sig($character->name); ?> <b><?php print $character->name; ?> &nbsp</b>
	</td>
	</tr>
	<tr>
	<td align=right>
	<b><?php echo $lang_age; ?>: &nbsp</b>
	</td>
	<td>
	<b><?php print $character->age; ?> &nbsp</b>
	</td>
	</tr>
	<tr>
	<td align=right>
	<b><?php echo $lang_sex; ?>: &nbsp</b>
	</td>
	<td>
	<b><?php print $character->sex; ?> &nbsp</b>
	</td>
	</tr>
	<tr>
	<td align=right>
	<b><?php echo $lang_race; ?>: &nbsp</b>
	</td>
	<td>
	<b><?php print $character->race; ?> &nbsp</b>
	</td>
	</tr>
	<tr>
	<td></td>
	<td align=right>
	<b><?php echo $lang_class; ?>: &nbsp</b>
	</td>
	<td>
	<b><?php print $character->cclass; ?> &nbsp</b>
	</td>
	</tr>
	<tr>
	<td></td>
	<td align=right>
	<b><?php echo $lang_att; ?>: &nbsp</b>
	</td>
	<td>
	<b><?php print $character->fight; ?> &nbsp</b>
	</td>
	</tr>
	<tr>
	<td></td>
	<td align=right>
	<b><?php echo $lang_def; ?>: &nbsp</b>
	</td>
	<td>
	<b><?php print $character->defence; ?> &nbsp</b>
	</td>
	</tr>
	<tr>
	<td></td>
	<td align=right>
	<b><?php echo $lang_wplss; ?>: &nbsp</b>
	</td>
	<td>
	<b><?php print $character->weaponless; ?> &nbsp</b>
	</td>
	</tr>
	<tr>
	<td></td>
	<td align=right>
	<b><?php echo $lang_lckpk; ?>: &nbsp</b>
	</td>
	<td>
	<b><?php print $character->lockpick; ?> &nbsp</b>
	</td>
	</tr>
	<tr>
	<td></td>
	<td align=right>
	<b><?php echo $lang_traps; ?>: &nbsp</b>
	</td>
	<td>
	<b><?php print $character->traps; ?> &nbsp</b>
	</td>
	</tr>
	<tr><td>&nbsp</td></tr>
	<tr>
	<td align=center colspan=3>
	<b><big><?php echo $lang_added["ad_curr-eq"]; ?></big></b>
	<table align=center border=0 cellspacing=2 cellpadding=2>
	<tr>
	<td align=center>
	<img src='<?php print $character->get_eq_item_pic("weapons"); ?>' title='<?php echo $lang_char["eq_wep"]; ?>: <?php print $character->get_eq_item_name("weapon"); ?>'>
	</td>
	<td align=center>
	<img src='<?php print $character->get_eq_item_pic("helms"); ?>' title='<?php echo $lang_char["eq_helm"]; ?>: <?php print $character->get_eq_item_name("helm"); ?>'>
	</td>
	<td align=center>
	<img src='<?php print $character->get_eq_item_pic("shields"); ?>' title='<?php echo $lang_char["eq_shld"]; ?>: <?php print $character->get_eq_item_name("shield"); ?>'>
	</td>
	</tr>
	<tr>
	<td align=center></td>
	<td align=center>
	<img src='<?php print $character->get_eq_item_pic("armor"); ?>' title='<?php echo $lang_char["eq_armor"]; ?>: <?php print $character->get_eq_item_name("armor"); ?>'>
	</td>
	<td align=center>
	<img src='<?php print $character->get_eq_item_pic("gloves"); ?>' title='<?php echo $lang_char["eq_glvs"]; ?>: <?php print $character->get_eq_item_name("gloves"); ?>'>
	</td>
	</tr>
	<tr>
	<td align=center></td>
	<td align=center>
	<img src='<?php print $character->get_eq_item_pic("boots"); ?>' title='<?php echo $lang_char["eq_boot"]; ?>: <?php print $character->get_eq_item_name("boots"); ?>'>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	<br>
	<br>
	</td>
	</tr>
	<tr>
	<td align=center colspan=2>
	<big><b><?php echo $lang_char["rep"]; ?></b></big>
	<br>
	<table border=0 cellspacing=0 cellpadding=0>
	<tr>
	<td align=right><?php echo $lang_char["help"]; ?>: &nbsp</td>
	<td><?php print $character->rep_helpfull; ?> &nbsp</td>
	</tr>
	<tr>
	<td align=right><?php echo $lang_char["gen"]; ?>: &nbsp</td>
	<td><?php print $character->rep_generious; ?> &nbsp</td>
	</tr>
	<tr>
	<td align=right><?php echo $lang_char["com"]; ?>: &nbsp</td>
	<td><?php print $character->rep_combat; ?> &nbsp</td>
	</tr>
	<tr>
	<td align=right><hr><?php echo $lang_char["total"]; ?>: &nbsp</td>
	<td><hr><?php print ($character->rep_helpfull+$character->rep_generious+$character->rep_combat); ?> &nbsp</td>
	</tr>
	<tr><td align=right><hr>&nbsp</td><td><hr>&nbsp</td></tr>
	</table>
	<?php
	if($character->rep_points > 0) {
		?>
		<tr><td align=center><?php echo " ".$lang_char["hav_rep1"]." ".$character->rep_points." ".$lang_char["hav_rep2"]; ?></td></tr>
		<tr><td align=center><?php echo $lang_char["dist"]; ?></td></tr>
		<tr>
		<td align=center>
		<form method="post" action="player_info.php" target="_blank">
		<Input type='text' name='char_name'>
		<input type='submit'  value='<?php echo $lang_char["dis"]; ?>'>
		</form>
		</td>
		</tr>
		<?php
	}
	?>
	</td>
	</tr>
	<?php
}
?>
<tr><td>&nbsp</td></tr>
<tr><td>&nbsp</td></tr>
<tr>
<td align=center colspan=2>
<big><b><?php echo $lang_char["invent"]; ?></b></big>
<br>
<br><a name="inventory"><font color="#FFFFFF"><?php echo $lang_added["ad_sort"]; ?></font></a>
<br>
<a href="character.php"><?php echo $lang_added["ad_all"]; ?></a>
&nbsp;|&nbsp;<a href="character.php?act=weapon#inventory"><?php echo $lang_added["ad_weapons"]; ?></a>
&nbsp;|&nbsp;<a href="character.php?act=armor#inventory"><?php echo $lang_added["ad_armor"]; ?></a>
&nbsp;|&nbsp;<a href="character.php?act=boots#inventory"><?php echo $lang_added["ad_boots"]; ?></a>
&nbsp;|&nbsp;<a href="character.php?act=gloves#inventory"><?php echo $lang_added["ad_gloves"]; ?></a>
&nbsp;|&nbsp;<a href="character.php?act=helm#inventory"><?php echo $lang_added["ad_helms"]; ?></a>
&nbsp;|&nbsp;<a href="character.php?act=shield#inventory"><?php echo $lang_added["ad_shields"]; ?></a>
&nbsp;|&nbsp;<a href="character.php?act=potion#inventory"><?php echo $lang_added["ad_potions"]; ?></a>
&nbsp;|&nbsp;<a href="character.php?act=spell_items#inventory"><?php echo $lang_added["ad_spell-items"]; ?></a>
<table border=0 cellspacing=0 cellpadding=0>

<?php
$wheretype= "";

$item_type= @$_GET['act'];
if(isItemType($item_type)){
	$wheretype= " AND type = '$item_type' ";
}

//!PS: be careful with this code, it took some time to write

$items= array();
//$list_inventory = mysql_query("SELECT * FROM phaos_char_inventory WHERE username = '$_COOKIE[PHP_PHAOS_USER]' $wheretype ORDER BY item_id ASC");
$list_inventory = mysql_query("SELECT * FROM phaos_char_inventory WHERE username = '$_COOKIE[PHP_PHAOS_USER]' $wheretype ORDER BY type ASC, item_id ASC");

if($list_inventory) {
	while ($row = mysql_fetch_assoc($list_inventory)) {
		$items[]= $row;
	}
	$items[]= null;// add an extra empty row to trigger routput
}

if(count($items)>1) {
	?>
	<tr style="background:#004400;">
		<td align=center valign=top><b><?php echo $lang_char["amount"]; ?> &nbsp</b></td>
		<td>&nbsp</td>
		<td valign=top><b><?php echo $lang_char["desc"]; ?> &nbsp</b></td>
		<td align=center valign=top><b><?php echo $lang_char["eff"]; ?> &nbsp</b></td>
		<td align=center valign=top colspan=3><b>Action &nbsp</b></td>
	</tr>
	<?php
}
//end if show header

// begin output loop
$lastrow= null;
$output= null;
foreach($items as $row) {
	if($row) {
		$id = $row["id"];
		$equiped = $row["equiped"];
		$item_type = $row["type"];
		$item_id = $row["item_id"];
		$sell_to_name = $row["sell_to"];
		$ask_price = $row["asking_price"];

		if(!@$_GET['act'] || $_GET['act'] == $item_type || $_GET['act'] == $item_type.'s' || $_GET['act'].'s' == $item_type) {
			if($lastrow && $row['item_id'] == $lastrow['item_id'] && $row['equiped'] == $lastrow['equiped'] && $row['type'] == $lastrow['type'] && $row['sell_to'] == $lastrow['sell_to'] && $row['asking_price'] == $lastrow['asking_price']) {
				++$lastrow['itemcount'];
				$output= null;
			} else {
				$output= $lastrow;
				$lastrow= $row;
				$lastrow['itemcount']= 1;
			}
		} else {
			//ignore item
		}
	} else {
		$output= $lastrow;
	}
	if(!$output){
		//no output
		continue;
	} else {
		$id = $output["id"];
		$equiped = $output["equiped"];
		$item_type = $output["type"];
		$item_id = $output["item_id"];
		$sell_to_name = $output["sell_to"];
		$ask_price = $output["asking_price"];

		$info= fetch_item_additional_info(array('id'=>$item_id,'type'=>$item_type,'number'=>1),$character);

		$description = $info['description'];
		$sell_price = $info['sell_price'];
		$image_path= $info['image_path'];
		$skill_req= $info['skill_req'];
		$damage_mess= @$info['damage_mess'];
		$skill_need= $info['skill_need'];
		$effect= $info['effect'];
		$skill_type= $info['skill_type'];

		// start outputting a row
		print ("<tr>");
		print ("<td align=center valign=top><b>$output[itemcount]</b>x</td>");
		?><td align=center valign=top><?php echo makeImg($image_path); ?></td><?php
		print "<td>".ucwords($description)."&nbsp;<br>";
		if($item_type != "potion") {
			print ("<font color=$skill_need>".$lang_shop["req"].$skill_req." ".$skill_type."</font>");
		}
		print "</td><td align=center valign=top>$effect&nbsp;</td>";
		if($item_type == "potion") {
			print "<td valign=top><input type='button' onClick=\"parent.location='character.php?item_id=$item_id&id=$id&drink_potion=Y'\" value='$lang_char[drink]'></td>";
			//print "<td valign=top>&nbsp;<a href='character.php?item_id=$item_id&id=$id&drink_potion=Y'>".$lang_char["drink"]."</a>&nbsp;</td>";
		} elseif($item_type == "spell_items") {
			print "<td valign=top>&nbsp;</td>";
		} else {
			print "<td align=center valign=top>&nbsp;";
			if(!$character->equipped($item_type,$item_id)){
				print ("<input type='button' onClick=\"parent.location='character.php?item_id=$item_id&item_type=$item_type&id=$id&equip_id=Y'\" value='$lang_char[eq]'>");
			}
			if($character->equipped($item_type,$item_id)) {
				print ("<input type='button' onClick=\"parent.location='character.php?item_id=$item_id&item_type=$item_type&id=$id&equip_id=N'\" value='$lang_char[uneq]'>");
			}
			print "</td>";
		}
		if($item_type == "weapon" OR $item_type == "armor" OR $item_type == "boots" OR $item_type == "potion" OR $item_type == "gloves" OR $item_type == "helm" OR $item_type == "shield" OR $item_type == "spell_items") {
			print "<td align=center valign=top>";
			if($shopForItemtype[$item_type]) {
				print "<input type='button' title=\"$lang_char[sell_pr] $sell_price gold\" onClick=\"parent.location='character.php?item_id=$item_id&item_type=$item_type&id=$id&sell_id=Y'\" value='$lang_char[sell]'";
			} else {
				print "&nbsp;";
			}
			?> </td><td align=center valign=top><?php
			actionButton($lang_char['dropitem'],$_SERVER['PHP_SELF'],
				array(
					'drop_id[]'=> $item_id,
					'drop_type[]'=> $item_type,
					'drop_number[]'=> 1
				)
			);
			print "</td></tr>";
		}
		print ("<p>
	        	  <td align=center colspan=7 valign=top>
	        	  <form method=\"post\" action=\"character.php\">
	        	  <input type=\"hidden\" name=\"market_item\" value=\"yes\">
	        	  <input type=\"hidden\" name=\"char_inv_id\" value=\"$id\">
	        	  Sell To:<input type=\"text\" name=\"sell_to\" value=\"$sell_to_name\" size=10>
	        	  Price:<input type=\"text\" name=\"asking_price\" value=\"$ask_price\" size=10>
	        	  <input type=\"submit\" value=\"$lang_char[setsellprice]\">
	        	  </form>
	              </td>");
		?></tr><?php
		?><tr><td colspan="8"><hr width=50%></td></tr><?php
	}
	// end out put a row
}
// end loop

if(!$output) {
	print "<tr><td align=center colspan=4><b>".$lang_char["noitem"].($wheretype?" ($_GET[act])":'')."</b></td></tr>";
}
?>
</table>

</td>
</tr>
</table>

</td>
</tr>

<tr>
<form action="create_character.php">
<td align=center>
<br>
<br>
<input type="submit" value="<?php echo $lang_char["create"]; ?>">
</td>
</form>
<form method=post action="character.php" onSubmit="return confirm('<?php echo $lang_char["msg"]; ?>')">
<td align=center>
<br>
<br>
<input type="hidden" name="delete" value="yes">
<input type="submit" value="<?php echo $lang_char["delete"]; ?>">
</td>
</form>
</tr>
</table>

<?php include "footer.php"; ?>
