<?php
include "header.php";

$clanmemberid = 0;
$clanrank = 0;
$clanname = '';
$result_b = mysql_query("SELECT id, name FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
if ($row = mysql_fetch_array($result_b)) {
        $clanmemberid = $row['id'];
	$clanmember = $row["name"];
}

$result = mysql_query ("SELECT clanname, clanrank FROM phaos_clan_in WHERE clanmemberid = '$clanmemberid'");
if (($row = mysql_fetch_array($result))) {
    $clanname = $row["clanname"];
    $clanrank = intval($row["clanrank"]);
}

// Deleting an another user from the same clan, only for guild leader or guild assistant.
if ($clan_user_name !== '' && $clanrank >= 98) {
  $result = mysql_query ("SELECT clanname, clanrank, clanmember, clanmemberid FROM phaos_clan_in WHERE clanmember = '$clan_user_name'");
  if (($row = mysql_fetch_array($result))) {
      $clan_user_rank = intval($row['clanrank']);
      if ($row["clanname"] === $clanname && $clan_user_rank < 99) {
        $clanmemberid = $row["clanmemberid"];
        $clanmember = intval($row["clanmember"]);
      }
  }
}

echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber1' height='103'>
	<tr>
	<td width='100%' height='100%' align='center'>
	<img src='lang/".$lang."_images/clan_home.png'><br>
	</td>
	</tr>";


if($clanname === '') {
  echo "<p align='center'><font color='#FF0000'><b>
          <a href=\"town_hall.php\">".$lang_guild3["not_in"]."</a></b></font></td>
          </tr>
          </table><br><br>";
} else if($quitting == "yes") {
	echo "<br><br>
		<table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
		<tr>
		<td width='100%'>
		<p align='center'><b><font color='#FF0000'>$clanmember ".$lang_guild4["plz_del_wa"].".</font></b></p>
		<p align='center'><font color='#FF0000'><b>
		<a href='town_hall.php'>".$lang_clan["town_ret"]."</a></b></font></td>
		</tr>
		</table><br><br>";

	$query_3 = "DELETE FROM phaos_clan_in WHERE clanmemberid = '$clanmemberid'";
	$result = mysql_query($query_3) or die ("Error in query: $query_3. " . mysql_error());
} else {
	echo "<tr>
		<td align='center' valign='top' height='63'>
		<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%'>
		<tr>
		<td width='100%' bgcolor='#003300' align='center'>&nbsp;</td>
		</tr>
		<tr>
		<td width='100%' align='center' valign='top'>
		<br>
		<form method='post' action=\"clan_leave.php?clan_user_name=$clanmember\">
		<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' width='95%' id='AutoNumber3'>
		<tr>
		<td width='100%' bgcolor='#003300' align='center'>
		<b><font color='#FF0000'>$clanmember</font> ".$lang_guild4["sure2le"]."</b>
		</td>
		</tr>
		<tr>
		<td width='100%' align='center'>
		<br>".$lang_guild4["wish2le"].".<br>
		<br>
		".$lang_o_yes." <input type='radio' value='yes' name='quitting'><br>
		".$lang_o_no." <input type='radio' value='no' checked name='quitting'>
		</td>
		</tr>
		</table>
		<p>
		<input class='buttont' type='submit' value='".$lang_guild2["confirm"]."' name='B1'></p>
		</form>
		</td>
		</tr>
		<tr>
		<td width='100%' bgcolor='#003300'>
		<a href='clan_home.php'>".$lang_clan["back"]."</a></td>
		</tr>
		</table>
		</td>
		</tr>
		<tr>
		<td width='100%' colspan='3' height='19'>&nbsp;</td>
		</tr>
		</table>";
}

include "footer.php";
