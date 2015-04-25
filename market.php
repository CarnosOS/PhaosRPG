<?php
include "header.php";
include_once 'items.php';
include_once 'class_character.php';

//FIXME: $character could be used more often
$character = new character($PHP_PHAOS_CHARID);

//FIXME: this code still accesses the phaos_character table by username
//FIXME: phaos_char_inventory uses username to store inventory

if($_POST['inventory_id'] != "" AND $_POST['owner_name'] != "".$PHP_PHAOS_USER."") {
	$inventory_id = $_POST['inventory_id'];
	$owner_name = $_POST['owner_name'];

	$result = mysql_query ("SELECT * FROM phaos_char_inventory WHERE id = ".$inventory_id." AND username = '".$owner_name."' AND sell_to != ''");
	if ($row = mysql_fetch_array($result)) {
		$asking_price = abs($row["asking_price"]);

		$result = mysql_query ("SELECT * FROM phaos_characters WHERE username = '".$owner_name."'");
		if ($sell_char = mysql_fetch_array($result)) {
			$seller_gold = $sell_char["gold"];
		}

		$result = mysql_query ("SELECT * FROM phaos_characters WHERE username = '".$PHP_PHAOS_USER."'");
		if ($buy_char = mysql_fetch_array($result)) {
			$buyer_gold = $buy_char["gold"];
		}

		if($buyer_gold >= $asking_price) {
			$new_buyer_gold = $buyer_gold-$asking_price;
			$new_seller_gold = $seller_gold+$asking_price;

			$query = ("UPDATE phaos_char_inventory SET username = '".$PHP_PHAOS_USER."',
                      		asking_price = '0',
                      		sell_to = ''
                      		WHERE id = ".$inventory_id."");
			$req = mysql_query($query);
			if (!$req) { echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}

			$query = ("UPDATE phaos_characters SET gold = ".$new_seller_gold." WHERE username = '".$owner_name."'");
			$req = mysql_query($query);
			if (!$req) { echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}

			$query = ("UPDATE phaos_characters SET gold = ".$new_buyer_gold." WHERE username = '".$PHP_PHAOS_USER."'");
			$req = mysql_query($query);
			if (!$req) { echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}

			?>
			<script language="JavaScript">
			<!--
			javascript:parent.side_bar.location.reload();
			//-->
			</script>
			<?php
		} else {
			$trade_result = $lang_markt["not_en_goo"];
		}

		if($trade_result == "") {$trade_result = $lang_markt["tr_compt"];}
	} else {
		$trade_result = $lang_markt["tr_not"];
	}
} else $trade_result = "";
?>

<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
<tr>
<td align=center valign=top>

<table border=0 cellspacing=5 cellpadding=0 width="100%">
<tr>
<td align=center colspan=2>
<img src="lang/<?php echo $lang ?>_images/market.png">
<br>

<br>
<br>
<?php

if($trade_result != "") {
	print ("<b>$trade_result</b><p>");
}

?>
<table cellspacing=0 cellpadding=0 border=0>
<?php
print ("<tr style='background:#006600;'>
     <td align=left><b>".$lang_markt["sllr"]."</b> &nbsp</td>
     <td align=left><b>".$lang_markt["desc"]."</b> &nbsp</td>
     <td align=left><b>".$lang_markt["ask_pr"]."</b> &nbsp</td>
     <td align-center>&nbsp</td>
     </tr>");



// FIXME: Need to update char_inventory table to give owner as userID, not name.. then use it to link to the char name... OR, just store both in this table :)
$res=mysql_query("SELECT * FROM phaos_char_inventory WHERE sell_to = 'all' OR sell_to = '$PHP_PHAOS_CHAR' ORDER BY id DESC");
if ($row = mysql_fetch_array($res)) {
	do {
		$owner_name = $row["username"]; // FIXME: this is bad.. we should use character name
		$inventory_id = $row["id"];
		$type = $row["type"];
		$item_id = $row["item_id"];
		$asking_price = abs($row["asking_price"]);
		$sell_to =$row["sell_to"];

        $item= fetch_item_additional_info(array('id'=>$item_id,'type'=>$type,'number'=>1), $character);
		if($sell_to =="all" || $sell_to =="".$PHP_PHAOS_CHAR."" ){
			print ("<tr>
     				<form method='post' action='market.php'>
     				<td align=left>$owner_name &nbsp</td>
     				<td align=left><font color=\"$item[skill_need]\">$item[description]</font> &nbsp</td>
     				<td align=left>$asking_price $lang_gold &nbsp</td>
     				<td align=center>
     				<input type='hidden' name='inventory_id' value='$inventory_id'>
     				<input type='hidden' name='owner_name' value='$owner_name'>
     				<button type='submit'>$lang_markt[buuy]</button>
     				</td>
     				</form>
     				</tr>");
     			$selling_something = "yes";
		}else{
			$selling_something = "no";
		}

	} while ($row = mysql_fetch_array($res));

}

if($selling_something != "yes") {
	print ("<tr>
     		<td align=center colspan=4>$lang_markt[no_sell]</td>
     		</tr>");
}
?>
</table>
</td>
</tr>
</table>

<br>
<br>
<a href="town.php"><?php echo $lang_markt["rree_area"]; ?></a>
</td>
</tr>
</table>
<?php include "footer.php"; ?>
