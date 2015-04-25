<?php
include "header.php";

if($begin == "") {
	/*echo $clanname_ask;*/
	$clan_name = $clanname_ask;
	$begin = "yes";
}

echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber1' height='103'>
	<tr>
	<td width='100%' height='100%' align='center'>
	<img border='0' src='lang/".$lang."_images/clan_ask.png' width='341' height='50'></td>
	</tr>";

if($Text1 == "" and $questionsend == $lang_guild4["send_me"]) {
	echo "<br><table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' width='95%' id='AutoNumber3'>
		<tr>
		<td width='100%'>
		<p align='center'><font color='#FF0000'>".$lang_guild4["plz_fill"].".</font></p>
		<p align='center'><a href='clan_send.php'>".$lang_guild4["stl_tm_try"]."</a></td>
		</tr>
		</table>";
	$error = "yes";
}

if($Text1 > "" and $questionsend == $lang_guild4["send_me"]) {
	echo "<br><table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' width='95%' id='AutoNumber3'>
		<tr>
		<td width='100%'>
		<center><font color='#FF0000'>".$lang_guild4["plz_send"].".</font></center><br>
		<!-- <p align='center'><a href='town_hall.php'>to Town Hall</a> -->";

	$result_1 = mysql_query ("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
	if ($row = mysql_fetch_array($result_1)) {
		$chname = $row["name"];
	}

	$result_2 = mysql_query ("SELECT * FROM phaos_clan_search WHERE charname = '$chname'");
	if ($row = mysql_fetch_array($result_2)) {
		$duplicate = "YES";
	}

	if($duplicate != "YES" AND $chname != "") {
		echo $clan_name;
		$query = "INSERT INTO phaos_clan_search
		(clanname,charname,description)
		VALUES
		('$nclanname','$chname','$Text1')";
		$req = mysql_query($query);
		if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}

		print ("<center><font color='#FF0000'>".$lang_guild4["has_sent"]."...</font><p><br><a href='town_hall.php'>".$lang_clan["town_ret"]."</a></center>");
	} else {
		print ("<center><font color='#FF0000'><big>".$lang_guild4["not_sent"]."</font></big><br><a href='town_hall.php'>".$lang_clan["town_ret"]."</a><br><a href=\"clan_join.php\">".$lang_guild4["tr_agi"]."</a><br>");
	}
	$error = "no";
}
echo "</td>
	</tr>
	</table>";

if($error == "") {
	echo "<tr>
		<td align='center' valign='top' height='63'>
		<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2'>
		<tr>
		<td width='100%' bgcolor='#003300' align='center'>&nbsp;</td>
		</tr>
		<tr>
		<td width='100%' align='center' valign='top'>
		".$lang_guild4["cl_ask"]." >> $clanname_ask << <br>
		".$lang_guild4["cl_aft_rep"].".";
	echo "<br><hr color='#FFFFFF' width='98%'><br>
		<form method='post' action='clan_send.php'>
		The Guild's Name : <input type='text' name='nclanname' size='30' maxlength='30' value='$clanname_ask'><br>";

	$result_c = mysql_query ("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
	if ($row = mysql_fetch_array($result_c)) {
		$chname = $row["name"];
	}
	echo $lang_guild4["ur_messss"]." : <input type='text' name='Text1' size='40' maxlength='100' value='".$lang_guild4["ur_txxxx"]."'><br>
		<font color='#FF0000'><sup>*</sup></font><font style='font-size: 9pt'>".$lang_guild4["cn_ent_mes"].".</font>
		<br><br>
		<input class='buttont' type='submit' value='".$lang_guild4["send_me"]."' name='questionsend'>
		<input class='buttont' type='reset' value='".$lang_clan["reset"]."' name='B2'>
		<br><br>
		</form>";
	echo "</td>
		</tr>
		<tr>
		<td width='100%' bgcolor='#003300'><a href='town_hall.php' target='content'>".$lang_clan["back"]."</a></td>
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
