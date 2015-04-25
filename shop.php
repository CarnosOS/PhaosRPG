<?php
include "header.php";
include_once "class_character.php";

$refresh = 0; //determine if the SideBar has to be refreshed

$character = new character($PHP_PHAOS_CHARID);
shop_valid($character->location, $shop_id);

$current_time = time();

if(@$item_id) {	// if you've previously selected an item to purchase
	$result = mysql_query ("SELECT * FROM phaos_shop_inventory WHERE shop_id='$shop_id' AND item_id='$item_id'");
	// $result = mysql_query ("SELECT * FROM phaos_misc_items WHERE id = '$item'");
	$inv_row = mysql_fetch_array($result);
	// $price = $inv_row["sell"];

	$number= intval($_REQUEST['number']);
	while($number-->0){
		// is your pack already too full?
		if ($character->invent_count() >= $character->max_inventory) {
			print ("<big><b><font color=red>$lang_shop[inv_full]</font></b></big>
				<br> <br>
				<a href='town.php'>$lang_shop[return]</a>");
    		exit;
    	}

    	// is the item still in stock?
    	if (--$inv_row["quantity"] >= 0) {
    		// do you have enough gold to buy the item?
    		if ($character->pay($inv_row["sell"]) ) {	// reduce player gold if they have enough
    			// give gold to owner
				$result=mysql_query("SELECT * FROM phaos_buildings WHERE shop_id='$shop_id' ");
				$shop_row=mysql_fetch_array($result);
				$owner = new character($shop_row["owner_id"]);
				$owner->gold += $inv_row["sell"];
				$result=mysql_query("UPDATE phaos_characters
					SET gold='".$owner->gold."'
					WHERE id='".$owner->id."' ") or die ( mysql_error() );

				// remove item from store inventory
				$result=mysql_query("UPDATE phaos_shop_inventory
					SET quantity='$inv_row[quantity]'
					WHERE shop_id='$shop_id' AND item_id='$item_id' ") or die ( mysql_error() );
				// add item to player inventory
				$character->add_item($item_id,$inv_row["type"]);     // $character->add_item($item,"misc_items");
				$refresh=1;
    		} else {
				$sorry = $lang_shop["sorry"];
				break;
			}
    	} else {
			$sorry = $lang_shop["no_left"];
			break;
		}
	}
	// $refresh=1;

}

if ($refresh){
	echo " <script language=\"JavaScript\">
		<!--
		javascript:parent.side_bar.location.reload();
		//-->
			</script>";
	$refresh=0; //be sure to reset refresh-Status
}

?>

<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
<tr>
<td align=center valign=top>

<table border=0 cellspacing=5 cellpadding=0 width="100%">
	<tr>
		<td align=center colspan=2>
			<!--<font size=5 color=white>-->
			<?php
			$result = mysql_query ("SELECT * FROM phaos_buildings WHERE shop_id='$shop_id' ");
			$row = mysql_fetch_array($result);
			$result = mysql_query ("SELECT name FROM phaos_characters WHERE id='$row[owner_id]' ");
			if ($row = mysql_fetch_array($result)) {
        		//	print("$row[name]'s");
			}
			?>
			<!--</font> <br>-->
			<img src="lang/<?php echo $lang ?>_images/item_shop.png">
		</td>
	</tr>
<?php
echo "<table align=center>
	<tr><td> <b>$lang_shop[inv]:</b></br>
	<tr><td align='center'> $lang_shop[cap]" .$character->max_inventory ." ".$lang_shop[items]."</td></tr>
	<tr><td align='center'> $lang_shop[item]".$character->invent_count()." ".$lang_shop[items]."</td></tr>
	</table></td></tr>";

if(@$sorry) {
	print ("<tr>
	<td align=center colspan=2>
	<big><b><font color=red>$sorry</font></b></big>
	</td>
	</tr>");
}

?>
<tr>
<td align=center width="50%">
<br>
<br>
<b>ITEMS</b>
</td>
</tr>
<tr>
<td align=center valign=top width="50%">
<?php 
if ($result=mysql_query ("SELECT * FROM phaos_shop_inventory WHERE shop_id='$shop_id' ") ){
	print ("<table border=0 cellpadding=2 align='center' valign='top'>
		<tr>");
	$line = 0;
	while ( $inv_row = mysql_fetch_array($result) ) {
		$result2 = mysql_query ("SELECT * FROM phaos_$inv_row[type] WHERE id='$inv_row[item_id]' ") or die( mysql_error() );
		$item_row = mysql_fetch_array($result2);
		// FIXME: these vars will change with each different type of item
		$id = $item_row["id"];
		print ("<td align=center><hr><img src='$item_row[image_path]'><br>
			$item_row[name]<br>
			$inv_row[quantity] $lang_shop[stock]<br>
			$inv_row[sell] $lang_shop[gp] $lang_shop[each]<br>
			<form action='shop.php' method='post'>
				<input type='hidden' name='shop_id' value='$inv_row[shop_id]'>
				<input type='hidden' name='item_id' value='$inv_row[item_id]'>
				<input type='hidden' name='number' value='1'>
				<input type='submit' value='$lang_shop[purc]'>
			</form>
			<form action='shop.php' method='post'>
				<input type='hidden' name='shop_id' value='$inv_row[shop_id]'>
				<input type='hidden' name='item_id' value='$inv_row[item_id]'>
				<input type='hidden' name='number' value='$inv_row[quantity]'>
				<input type='submit' value='$lang_shop[purcall]'>
			</form>");
		$line ++;
		if($line==2){
			echo "</td></tr><tr>";
			$line = 0;
		}else{ 
			echo "</td>";
		}
	}
} else {
	die ( mysql_error() );
}

?>


</td>
</tr>
</table>

</td>
</tr>
</table>

<?php include "footer.php"; ?>
