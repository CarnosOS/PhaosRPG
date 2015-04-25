<?php
include "header.php";

echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber1' height='103'>
	<tr>
	<td width='100%' height='100%' align='center'>
	<img src='lang/".$lang."_images/clan_join.png'><br>
	</td>
	</tr>
	<tr>
	<td align='center' valign='top' height='63'>
	<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2'>
	<tr>
	<td width='100%' bgcolor='#003300' align='center'>&nbsp;</td>
	</tr>
	<tr>
	<td width='100%' align='center' valign='top'>
	".$lang_guild4["lk_fr_gu"].".<br>
	".$lang_guild4["lk_fr_gu2"].".
	<br><hr color='#FFFFFF' width='98%'><br>
	<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' width='95%' id='AutoNumber4'>
	<tr>
	<td width='20%' bgcolor='#003300'>".$lang_guild["gname"]." : </td>
	<td width='20%' bgcolor='#003300'>".$lang_guild["master"]." :</td>
	<td width='20%' bgcolor='#003300'>".$lang_guild3["gu_mes"]." :</td>
	<td width='20%' bgcolor='#003300'>".$lang_guild["sig"]." :</td>
	<td width='20%' bgcolor='#003300'><p align='center'>".$lang_guild4["gu_res"]." :</td>
	</tr>";

$result_b = mysql_query("SELECT name FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
if ($row = mysql_fetch_array($result_b)) {
	$name = $row["name"];
}

$result = mysql_query ("SELECT clanmember FROM phaos_clan_in ORDER BY clanmember");
while ($row = mysql_fetch_array($result)) {
	$clanmember = $row["clanmember"];

	if($name == $clanmember) {
		$inclan = "yes";
	}
}

$result_a = mysql_query ("SELECT charname FROM phaos_clan_search ORDER BY charname");
while ($row = mysql_fetch_array($result_a)) {
	$charname = $row["charname"];

	if($name == $charname) {
		$inclan = "yes";
	}
}

$result_0 = mysql_query ("SELECT * FROM phaos_clan_admin ORDER BY clanname");
while ($row = mysql_fetch_array($result_0)) {
	$clanname = $row["clanname"];
	$clanleader = $row["clanleader"];
	#    $clansig = $row["clansig"];
	$clan_sig = $row["clan_sig"];
	$clanslogan = $row["clanslogan"];
	$clanmembers = $row["clanmembers"];
	$clancreatedate = $row["clancreatedate"];
	$clanbanner = $row["clanbanner"];

	$result_1 = mysql_query("SELECT * FROM phaos_users WHERE username = '$clanleader'");
	if ($row = mysql_fetch_array($result_1)) {
		$usermail = $row["email_address"];
	}

	echo "<tr>
		<td width='20%' >$clanname</td>
		<td width='20%' ><img src=images/guild_sign/$clan_sig alt=$clanname><font color='#666699'><b>$clanleader</b></font></td>
		<td width='20%' >$clanmembers</td>
		<td width='20%' ><img src=images/guild_sign/$clan_sig alt=$clanname></td>";
	if($inclan == "yes"):
		if(file_exists($clanbanner)):
			echo "<td width='20%' ><p align='center'>$clanname</td>";
		else:
			echo "<td width='20%' ><p align='center'>$clanname</td>";
		endif;
	else:
		if(file_exists($clanbanner)):
			echo "<td width='20%' ><p align='center'><a href='clan_send.php?clanname_ask=".$clanname."'><img border='0' src='$clanbanner' width='81' height='77'></a></td>";
		else:
			echo "<td width='20%' ><p align='center'><a href='clan_send.php?clanname_ask=".$clanname."'>$clanname</a></td>";
		endif;
	endif;
	echo "</tr>
		<tr>
		<td width='100%' colspan='5'>".$lang_guild4["ur_slog"]." : $clanslogan<br>".$lang_guild4["2b_pres"]." : $clancreatedate</td>
		</tr>
		<tr>
		<td width='100%' colspan='5'><hr color='#FFFFFF' width='98%'>
		</td>
		</tr>";
}

echo "</table><br>
	</td>
	</tr>
	<tr>
	<td width='100%' bgcolor='#003300'><a href='town_hall.php'>".$lang_clan["back"]."</a></td>
	</tr>
	</table>
	</td>
	</tr>
	<tr>
	<td width='100%' colspan='3' height='19'>&nbsp;</td>
	</tr>
	</table>";
include "footer.php";
?>
