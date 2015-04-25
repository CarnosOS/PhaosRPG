<?php
include 'header.php';

$result = mysql_query ("SELECT * FROM phaos_characters WHERE id = '$PHP_PHAOS_CHARID'");
if ($row = mysql_fetch_array($result)) { $char_loc = $row["location"]; }
$result = mysql_query ("SELECT * FROM phaos_buildings WHERE location = '$char_loc'");
if ($row = mysql_fetch_array($result)) { $shop_id = $row["shop_id"]; }

shop_valid($char_loc, $shop_id); // make sure this requested shop is at the players location

$sql = mysql_query("SELECT gold,bankgold FROM phaos_characters WHERE id='$PHP_PHAOS_CHARID'");
$row = mysql_fetch_assoc($sql);

if(isset($_POST['submit'])) {
	// process bank here
	switch($_POST['R1']) {
		case "deposit":
		if ($_POST['amount'] <= 0) {
			echo"<center>You must enter an amount to deposit!</center>";
		} elseif ($_POST['amount'] > $row['gold']) {
			echo"<center>You dont have that much gold on hand!</center>";
		} else {
			$newgold = $row['gold'] - $_POST['amount'];
			$newbank = $row['bankgold'] + $_POST['amount'];
			mysql_query("UPDATE phaos_characters SET gold='$newgold', bankgold='$newbank' WHERE id='$PHP_PHAOS_CHARID'");
			echo"<center>You deposited ".number_format($_POST['amount'])." gold into your account.</center>";
			$refresh = 1;
		}
		break;
		case "withdraw":
		if ($_POST['amount'] <= 0) {
			echo"<center>You must enter an amount to withdraw!</center>";
		} elseif ($_POST['amount'] > $row['bankgold']) {
			echo"<center>You dont have that much gold in the bank!</center>";
		} else {
			$newgold = $row['gold'] + $_POST['amount'];
			$newbank = $row['bankgold'] - $_POST['amount'];
			mysql_query("UPDATE phaos_characters SET gold='$newgold', bankgold='$newbank' WHERE id='$PHP_PHAOS_CHARID'");
			echo"<center>You withdrew ".number_format($_POST['amount'])." gold from your account.</center>";
			$refresh = 1;
		}
		break;
	}
	if ($refresh){
		$sql = mysql_query("SELECT bankgold FROM phaos_characters WHERE id='$PHP_PHAOS_CHARID'");
		$row = mysql_fetch_assoc($sql);
		echo "<script language=\"JavaScript\">
			<!--
			javascript:parent.side_bar.location.reload();
			//-->
			</script>";
	}
	$refresh = 0; //be sure to reset refresh-Status
}

echo '<div align=center><form method="POST" action="bank.php">
	<center><table border="0" cellpadding="3" cellspacing="0" width="35%">
	<tr><td width="100%" colspan="2">&nbsp;</td></tr><tr><td width="100%" colspan="2">
	<p align="center"><b><big>Bank</big></b><br></td></tr><tr><td width="100%" colspan="2">
	<p align="center">Gold in Bank: '.number_format($row['bankgold']).'</td></tr><tr><td width="50%"><p align="center">
	<input type="radio" value="deposit" checked name="R1">Deposit</td><td width="50%"><p align="center">
	<input type="radio" value="withdraw" name="R1">Withdraw</td></tr><tr><td width="100%" colspan="2">
	<p align="center"><input type="text" name="amount" size="20"></td></tr><tr><td width="100%" colspan="2">
	<p align="center"><input type="submit" value="Submit" name="submit"></td></tr></table></center></form>
	</div>';

include "footer.php";
?>
