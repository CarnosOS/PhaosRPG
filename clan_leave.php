<?php
include "header.php";

$result_3 = mysql_query("SELECT * FROM phaos_clan_admin WHERE clanname = '$clanname'");
if ($row = mysql_fetch_array($result_3)) {
	$clanname = $row["clanname"];
	$clanleader = $row["clanleader"];
	$clanleader_1 = $row["clanleader_1"];
	$clanbanner = $row["clanbanner"];
	#    $clansig = $row["clansig"];
	$clan_sig = $row["clan_sig"];
	$clanlocation = $row["clanlocation"];
	$clanslogan = $row["clanslogan"];
	$clancashbox = $row["clancashbox"];
	$clanmembers = $row["clanmembers"];
	$clancreatedate = $row["clancreatedate"];
	$clanrank_1 = $row["clanrank_1"];
	$clanrank_2 = $row["clanrank_2"];
	$clanrank_3 = $row["clanrank_3"];
	$clanrank_4 = $row["clanrank_4"];
	$clanrank_5 = $row["clanrank_5"];
	$clanrank_6 = $row["clanrank_6"];
	$clanrank_7 = $row["clanrank_7"];
	$clanrank_8 = $row["clanrank_8"];
	$clanrank_9 = $row["clanrank_9"];
	$clanrank_10 = $row["clanrank_10"];
}

echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber1' height='103'>
	<tr>
	<td width='100%' height='100%' align='center'>
	<img src='lang/".$lang."_images/clan_home.png'><br>
	</td>
	</tr>";

if($quitting == "yes") {
	$v_error = "yes";

	echo "<br><br>
		<table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
		<tr>
		<td width='100%'>
		<p align='center'><b><font color='#FF0000'><img src=images/guild_sign/$clan_sig alt=$clanname>$clan_user_name ".$lang_guild4["plz_del_wa"].".</font></b></p>
		<p align='center'><font color='#FF0000'><b>
		<a href='town_hall.php'>".$lang_clan["town_ret"]."</a></b></font></td>
		</tr>
		</table><br><br>";

	$query_3 = "DELETE FROM phaos_clan_in WHERE clanmember LIKE '$clan_user_name'";
	$result = mysql_query($query_3) or die ("Error in query: $query_3. " . mysql_error());

	$clanmembers --;

	mysql_query("UPDATE phaos_clan_admin SET clanmembers='$clanmembers' WHERE clanname='$clanname'");

	$array_2 = "";
	$oldname = str_replace($array_2,$clan_user_name);

	mysql_query("UPDATE phaos_characters SET name='$oldname' WHERE name='$clan_user_name'");

	if($clanleader_1 == $clan_user_name) {
		mysql_query("UPDATE phaos_clan_admin SET clanleader_1='' WHERE clanname='$clanname'");
	}
}

if($v_error == "") {
	echo "<tr>
		<td align='center' valign='top' height='63'>
		<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%'>
		<tr>
		<td width='100%' bgcolor='#003300' align='center'>&nbsp;</td>
		</tr>
		<tr>
		<td width='100%' align='center' valign='top'>
		<br>
		<form method='post' action='clan_leave.php?clanname=$clanname&clan_user_name=$clan_user_name&clanmembers=$clanmembers'>
		<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' width='95%' id='AutoNumber3'>
		<tr>
		<td width='100%' bgcolor='#003300' align='center'>
		<img src=images/guild_sign/$clan_sig alt=$clanname><b><font color='#FF0000'>$clan_user_name</font> ".$lang_guild4["sure2le"]."</b>
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
?>
