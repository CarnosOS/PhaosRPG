<?php
include 'header.php';

echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber1' height='103'>
	<tr>
	<td width='100%' height='100%' align='center'>
	<img src='lang/".$lang."_images/clan_create.png'><br>
	</td>
	</tr>
	<tr>
	<td align='center' valign='top' height='63'>";

// --------------------------------------------------------------------------------------------------------------------
$result_0 = mysql_query ("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
if ($row = mysql_fetch_array($result_0)) {
	$level = $row["level"];
}
if($level <= 9) {
	echo "<table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber1'>
		<tr>
		<td width='100%'>
		<center><b><font color='#FF0000'>".$lang_clan["cannot"]."</font></b><br>
		<b><a href='town_hall.php'>".$lang_clan["back"]."</a></b></center>
		</td>
		</tr>
		</table>";
	$totalerror = "yes";
}

$result = mysql_query ("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
if ($row = mysql_fetch_array($result)) {
	$char_name = $row["name"];
	$char_location = $row["location"];
}

$result_1 = mysql_query ("SELECT * FROM phaos_clan_admin WHERE clanleader = '$char_name'");
if ($row = mysql_fetch_array($result_1)) {
	echo "<table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber1'>
		<tr>
		<td width='100%'>
		<center><b><font color='#FF0000'>".$lang_clan["alread"].".</font></b><br>
		<b><a href='town_hall.php'>".$lang_clan["back"]."</a></b></center>
		</td>
		</tr>
		</table>";
	$totalerror = "yes";
}

if($totalerror == "") {
	$result = mysql_query ("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
	if ($row = mysql_fetch_array($result)) {
		$char_name = $row["name"];
		$char_location = $row["location"];}
		$clanrank_1 = $lang_clan["rank_1"];
		$clanrank_2 = $lang_clan["rank_2"];
		$clanrank_3 = $lang_clan["rank_3"];
		$clanrank_4 = $lang_clan["rank_4"];
		$clanrank_5 = $lang_clan["rank_5"];
		$clanrank_6 = $lang_clan["rank_6"];
		$clanrank_7 = $lang_clan["rank_7"];
		$clanrank_8 = $lang_clan["rank_8"];
		$clanrank_9 = $lang_clan["rank_9"];
		$clanrank_10 = $lang_clan["rank_10"];

		$noerror = "yes";
		/*echo "<font color='#FF0000'>totalerror - 5 - $clanname - $creategilde</font><br>";*/
		if($clanname == "" and $createguild == $lang_clan["create"]) {
			$error = "<center><font color='#FF0000'><sup>*</sup></font><font color='#FF0000'><b>".$lang_clan["plz_1"]." ...</b></font></center><br>";
			$createguild = "";
			$noerror = "no";
		}

		if($clansig == "" and $createguild == $lang_clan["create"]) {
			$error = "<center><font color='#FF0000'><sup>*</sup></font><font color='#FF0000'><b>".$lang_clan["plz_2"]." ...</b></font></center><br>";
			$createguild = "";
			$noerror = "no";
		}

		if($clanslogan == "" and $createguild == $lang_clan["create"]) {
			$error = "<center><font color='#FF0000'><sup>*</sup></font><font color='#FF0000'><b>".$lang_clan["plz_3"]." ...</b></font></center><br>";
			$createguild = "";
			$noerror = "no";
		}

		if($createguild == $lang_clan["create"] and $noerror == "yes") {
			echo "<center><font color='#FF0000'>".$lang_clan["plz_4"]." ...</font></center><br>";

			$clanindate = date("m.d.Y");
			$date_h = date('m.d.Y - H:i:s');
			$clangold = "0";
			$creategilde = "no";

			$result = mysql_query ("SELECT * FROM phaos_clan_admin WHERE clanname = '$clanname'");
			if ($row = mysql_fetch_array($result)) {
				$duplicate = "YES";
			}

			if($duplicate != "YES" AND $clanname != "") {
				$query = "INSERT INTO phaos_clan_admin
				(clanname,clanleader,clanleader_1,clanbanner,clansig,clanlocation,clanslogan,clancashbox,clanmembers,clancreatedate,clanrank_1,clanrank_2,clanrank_3,clanrank_4,clanrank_5,clanrank_6,clanrank_7,clanrank_8,clanrank_9,clanrank_10)
				VALUES
				('$clanname','$clansig$clanleader','$clanleader_1','$clanbanner','$clansig','$char_location','$clanslogan','$clangold','1','$clanindate','$clanrank_1','$clanrank_2','$clanrank_3','$clanrank_4','$clanrank_5','$clanrank_6','$clanrank_7','$clanrank_8','$clanrank_9','$clanrank_10')";
				$req = mysql_query($query);
				if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}

				print ("<font color='#FF0000'>".$lang_clan["g_ok"]." ...</font><p><a href='town_hall.php'>".$lang_clan["town_ret"]."</a>");
			} else {
				print ("<font color='#FF0000'><big>".$lang_clan["err1"]."...</font></big><a href='town_hall.php'>".$lang_clan["town_ret"]."</a><p><a href=\"clan_create.php\">".$lang_clan["again"]."</a>");
			}

			$query = "INSERT INTO phaos_clan_in
			(clanname,clanmember,clanindate,givegold,clanrank)
			VALUES
			('$clanname','$clansig$clanleader','$date_h','0','99')";
			$req = mysql_query($query);
			if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}

			/*echo "$clansig$clanoberhaupt";*/
			mysql_query("UPDATE phaos_characters SET name='$clansig$clanleader' WHERE name='$clanleader'");
		}

		$result_y = mysql_query ("SELECT * FROM `phaos_characters` WHERE 1 AND `username` LIKE '$PHP_PHAOS_USER'");
		if ($row = mysql_fetch_array($result_y)) {
			$clanmember_n = $row["name"];
		}

		if($creategilde == ""):
			echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2'>
				<tr>
				<td width='100%' bgcolor='#003300' align='center'>
				".$lang_guild["data"]."
				</td>
				</tr>
				<tr>
				<td width='100%' align='center' valign='top'>
				".$lang_guild["inf"].".<br>
				<sup><font color='#FF0000'>**</font></sup>".$lang_guild["hint"].".<br>
				".$lang_guild["marked"]."<br>
				<br><hr color='#FFFFFF' width='98%'><br>
				$error<br>
				<form method='post' action='clan_create.php'>
				<font color='#FF0000'><sup>*</sup></font>".$lang_guild["gname"]."       : <input type='text' name='clanname' size='30'><br><br>
				<font color='#FF0000'><sup>*</sup></font>".$lang_guild["master"]."    : <input type='text' name='clanleader' size='30' value='$clanmember_n'><br><br>
				".$lang_guild["assist"]." : <input type='text' name='clanleader_1' size='30'><br><br>
				".$lang_guild["banner"]."    : <input type='text' name='clanbanner' size='40'><br><br>
				<font color='#FF0000'><sup>*</sup></font>".$lang_guild["sig"]."       : <input type='text' name='clansig' size='6'><br><br>
				<font color='#FF0000'><sup>*</sup></font>".$lang_guild["slogan"]."    : <input type='text' name='clanslogan' size='40' maxlength='100'><br><br>
				<input class='buttont' type='submit' value='".$lang_clan["create"]."' name='createguild'>
				<input class='buttont' type='reset' value='".$lang_clan["reset"]."' name='B2'><br><br>
				</form>
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
				</tr>";
		else:
			echo "";
			endif;
}
echo "</table>";
include "footer.php";
?>
