<?php
include 'aup.php';

$result = mysql_query ("SELECT * FROM phaos_characters WHERE id = '$PHP_PHAOS_CHARID'");
if ($row = mysql_fetch_array($result)) { $char_loc = $row["location"]; }

$char_loc = $row['location'];
$gold = $row['gold'];
$bankgold = $row['bankgold'];

// make sure this requested shop is at the players location
if (!($shop_id = shop_valid($char_loc, 'bank.php'))) {
        include 'header.php';
	echo $lang_markt["no_sell"].'</body></html>' ;
	exit;
}

$sql = mysql_query("SELECT gold,bankgold FROM phaos_characters WHERE id='$PHP_PHAOS_CHARID'");
$row = mysql_fetch_assoc($sql);

$message = '';

if(isset($_POST['submit'])) {
	// process bank here
	switch($_POST['R1']) {
		case "deposit":
		if ($_POST['amount'] <= 0) {
			$message = "<center>You must enter an amount to deposit!</center>";
		} elseif ($_POST['amount'] > $gold) {
			$message = "<center>You dont have that much gold on hand!</center>";
		} else {
			$gold -= $_POST['amount'];
			$bankgold += $_POST['amount'];
			mysql_query("UPDATE phaos_characters SET gold='".$gold."', bankgold='".$bankgold."' WHERE id='$PHP_PHAOS_CHARID'");
			$message = "<center>You deposited ".number_format($_POST['amount'])." gold into your account.</center>";
		}
		break;
		case "withdraw":
		if ($_POST['amount'] <= 0) {
			$message = "<center>You must enter an amount to withdraw!</center>";
		} elseif ($_POST['amount'] > $bankgold) {
			$message = "<center>You dont have that much gold in the bank!</center>";
		} else {
			$gold += $_POST['amount'];
			$bankgold -= $_POST['amount'];
			mysql_query("UPDATE phaos_characters SET gold='".$gold."', bankgold='".$bankgold."' WHERE id='$PHP_PHAOS_CHARID'");
			$message = "<center>You withdrew ".number_format($_POST['amount'])." gold from your account.</center>";
		}
		break;
	}

}


include 'header.php';

echo $message;

?>

<div align=center>
  <form method="POST" action="bank.php">
    <center>
      <table border="0" cellpadding="3" cellspacing="0" width="35%">
        <tr>
          <td width="100%" colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td width="100%" colspan="2">
            <p align="center"><b><big>Bank</big></b><br></p>
          </td>
        </tr>
        <tr>
          <td width="100%" colspan="2">
            <p align="center">
              Gold in Bank: <?php echo number_format($bankgold); ?>
            </p>
          </td>
        </tr>
        <tr>
          <td width="50%">
            <p align="center">
              <input type="radio" value="deposit" checked name="R1">Deposit
            </p>
          </td><td width="50%">
            <p align="center">
              <input type="radio" value="withdraw" name="R1">Withdraw
            </p>
          </td>
        </tr>
        <tr>
          <td width="100%" colspan="2">
            <p align="center"><input type="text" name="amount" size="20"></p>
          </td>
        </tr>
        <tr>
          <td width="100%" colspan="2">
            <p align="center"><input type="submit" value="Submit" name="submit"></p>
          </td>
        </tr>
      </table>
    </center>
  </form>
</div>

<?php
include "footer.php";
