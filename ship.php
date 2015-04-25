<?php include "header.php"; ?>
<!--
you create a ship.php page with this code, you insert a shop called ship where you want your port so when you explore you can see the it.
In location you should add (Port) to the end of the name so you know where other ports are
-->
<?php

include_once "class_character.php";

$character=new character($PHP_PHAOS_CHARID);

function travel ($chid, $gol, $locid) {
	if ($gol >= 20) {
		$gol = ($gol-20);
		$res =("UPDATE phaos_characters set location='$locid', gold='$gol' WHERE id = '$chid'");
		$req = mysql_query($res);
		if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}
		header ('Location: travel.php');
		?>
		<script language=javascript>
		self.location="town.php"
		</script>
		<?php
	}
}

if(@$_POST['travel']) {
	$id=$_POST['travel'];
	travel($character->id, $character->gold, $id);
}
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
$self1=mysql_query("SELECT location FROM phaos_buildings WHERE name='Stable' OR name='Ship Travel'");
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
