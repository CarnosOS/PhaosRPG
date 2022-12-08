<?php
include "aup.php";

/*
you create a ship.php page with this code, you insert a shop called ship where you want your port so when you explore you can see the it.
In location you should add (Port) to the end of the name so you know where other ports are
*/

$character=new character($PHP_PHAOS_CHARID);

// make sure requested shop is at same location as character
if (!($shop_id = shop_valid($character->location, 'ship.php'))) {
        include "header.php";
	echo $lang_markt["no_sell"].'</body></html>' ;
	exit;
}

// travel within current region only
$location_min = intval(floor($character->location / 10000) * 10000);
$location_max = $location_min + 10000;

function travel ($chid, $gol, $chloc, $locid) {
        global $location_min;
        global $location_max;

        // Make sure character is traveling from one stable to an another
        $res=mysql_query("SELECT count(*) FROM phaos_buildings WHERE (name='Stable' OR name='Ship Travel') "
                . "AND (location = '$locid' OR location = '$chloc') "
                . "AND (location > $location_min AND location < $location_max)");
        list($count) = mysql_fetch_array($res);

	if ($count == 2 && $gol >= 20) {
		$gol = ($gol-20);
		$res =("UPDATE phaos_characters set location='$locid', gold='$gol' WHERE id = '$chid'");
		$req = mysql_query($res);
		if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}
		header ('Location: travel.php');
	}
}

if(@$_POST['travel']) {
	$id=$_POST['travel'];
	travel($character->id, $character->gold, $character->location, $id);
}

include "header.php";

?>
<table width="550" border="1" cellspacing="0" cellpadding="3" align="center">
<tr style=background:#006600;>
<td colspan="2">
<div align="center"><b>Available Ports</b></div>
</td>
</tr>
<tr>
<td colspan=2 align=center>
<form><input type='button' onClick="location='travel.php'" value='Back to Map'></form>
</td>
</tr>
<tr>
<td><table><tr><td>Destination</td><td>&nbsp;&nbsp;</td><td>Cost</td><td>&nbsp;&nbsp;</td></tr>
<?php



$self1=mysql_query("SELECT location FROM phaos_buildings WHERE (name='Stable' OR name='Ship Travel') AND location > $location_min AND location < $location_max");
while ($row1 = mysql_fetch_array($self1)) {
	$loc = $row1["location"];
	$self=mysql_query("SELECT id,name FROM `phaos_locations` WHERE `id`=$loc");
	while ($row = mysql_fetch_array($self)) {
		$id = $row["id"];
		$name = $row["name"];
		if ($id != $character->location) {
			echo "<tr><td><form action=\"ship.php\" method=\"post\">
				<input type=\"hidden\" name=\"travel\" value=\"$id\">
				<input type=\"submit\" style=\"border:none;text-align:left;\" value=\"".$name."\">
				</form></td><td>&nbsp;&nbsp;</td><td>";
			// to do: different prices
			echo "Gold 20";
			echo "</td><td>&nbsp;&nbsp;</td></tr>";
		}
	}
}
?>
</table>
</td>
</tr>
</table>
<?php include "footer.php"; ?>
