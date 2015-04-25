<?php
include "header.php";
/*OPTIMIZE TABLE 'phaos_characters'*/

echo"<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber1' height='103'>
	<tr>
	<td width='100%' height='100%' align='center'>
	<img src='lang/".$lang."_images/clan_home.png'><br></td>
	</tr>
	<tr>
	<td align='center' valign='top' height='63'>";

$date_h = date('m.d.Y - H:i:s');

$result_1a = mysql_query("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
if ($row = mysql_fetch_array($result_1a)) {
	$username_ab = $row["name"];
}

$result_1 = mysql_query("SELECT * FROM phaos_clan_admin WHERE clanleader = '$username_ab'");
if ($row = mysql_fetch_array($result_1)) {
	$clanname = $row["clanname"];
	$clanleader = $row["clanleader"];
	$clanleader_1 = $row["clanleader_1"];
	$clancashbox = $row["clancashbox"];
	$clanbanner = $row["clanbanner"];
	$clan_user_name = $clanleader;
}

$result_2 = mysql_query("SELECT * FROM phaos_clan_in WHERE clanmember = '$username_ab'");
if ($row = mysql_fetch_array($result_2)) {
	$clanname = $row["clanname"];
	$clanmember = $row["clanmember"];
	$clanindate = $row["clanindate"];
	$givegold = $row["givegold"];
	$clanrank = $row["clanrank"];
	$clan_user_name = $clanmember;
}

if($clanname == "") {
	$ferror = "yes";
	echo "<br><br>
		<table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
		<tr>
		<td width='100%'>
		<p align='center'><b><font color='#FF0000'>".$lang_guild3["not_in"].".</font></b></p>
		<p align='center'><font color='#FF0000'><b>
		<a href='town_hall.php'>".$lang_clan["back"]."</a></b></font></td>
		</tr>
		</table><br><br>";
}

if($clanrank == "99" or $clanrank == "98"):
	$clan_user_name = $username_ab;
	$clan_user_edit = "yes";
else:
	$result_2 = mysql_query("SELECT * FROM phaos_clan_in WHERE clanmember = '$username_ab'");
	if ($row = mysql_fetch_array($result_2)) {
		$clanname = $row["clanname"];}
		$clan_user_edit = "no";
endif;

$result_3 = mysql_query("SELECT * FROM phaos_clan_admin WHERE clanname = '$clanname'");
if ($row = mysql_fetch_array($result_3)) {
	$clanname = $row["clanname"];
	$clanleader = $row["clanleader"];
	$clanleader_1 = $row["clanleader_1"];
	$clanbanner = $row["clanbanner"];
	#    $clansig = $row["clansig"];
	$clan_sig = $row["clan_sig"]; # select clan_sig
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

$guildrank_n[1] = "$clanrank_1";
$guildrank_n[2] = "$clanrank_2";
$guildrank_n[3] = "$clanrank_3";
$guildrank_n[4] = "$clanrank_4";
$guildrank_n[5] = "$clanrank_5";
$guildrank_n[6] = "$clanrank_6";
$guildrank_n[7] = "$clanrank_7";
$guildrank_n[8] = "$clanrank_8";
$guildrank_n[9] = "$clanrank_9";
$guildrank_n[10] = "$clanrank_10";

if($adjustment == "Update" ) {
	if($newlogo == ""):
		echo " ";
	else:
		$weinerror = "yes";
		$ferror = "yes";
		echo "<br><br>
			<table border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
			<tr>
			<td width='100%'>
			<p align='center'><b><font color='#FF0000'>".$lang_guild3["logo_chg"]." $newlogo </font></b></p>
			<p align='center'><font color='#FF0000'><b>
			<a href='clan_home.php'>".$lang_clan["back"]."</a></b></font></td>
			</tr>
			</table><br><br>";
		mysql_query("UPDATE phaos_clan_admin SET clanbanner='$newlogo' WHERE clanname='$clanname'");
	endif;

	if($newguildhelp == $lang_guild3["none"]):
		echo " ";
	else:
		$weinerror = "yes";
		$ferror = "yes";
		echo "<br><br>
			<table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
			<tr>
			<td width='100%'>
			<p align='center'><b><font color='#FF0000'>".$lang_guild3["new_ass"]." $newguildhelp.</font></b></p>";
	
		$result_1 = mysql_query("SELECT * FROM phaos_clan_admin WHERE clanname = '$clanname'");
		if ($row = mysql_fetch_array($result_1)) {
			$clanleader_1 = $row["clanleader_1"];
		}
	
		/*echo "clanname>$clanname : clanleader_1>$clanleader_1<br>";*/
		if($clanleader_1 == ""):
			echo "<center>".$lang_guild3["new_ass"]." $newguildhelp</center><br>";
			mysql_query("UPDATE phaos_clan_admin SET clanleader_1='$newguildhelp' WHERE clanname='$clanname'");
			mysql_query("UPDATE phaos_clan_in SET clanrank='98' WHERE clanmember='$newguildhelp'");
		else:
			echo "<center>".$lang_guild3["new_ass"]." $newguildhelp.</center>";
			mysql_query("UPDATE phaos_clan_admin SET clanleader_1='$newguildhelp' WHERE clanname='$clanname'");
			mysql_query("UPDATE phaos_clan_in SET clanrank='1' WHERE clanmember='$clanleader_1'");
			mysql_query("UPDATE phaos_clan_in SET clanrank='98' WHERE clanmember='$newguildhelp'");
		endif;
	
		echo "<p align='center'><font color='#FF0000'><b><a href='clan_home.php'>".$lang_clan["back"]."</a></b></font></td>
			</tr>
			</table><br><br>";
		echo "<p align='center'><font color='#FF0000'><b><a href='clan_home.php'></a></b></font></td>
			</tr>
			</table><br><br>";
	endif;
	
	if($newrank == $lang_guild3["none"]):
		echo " ";
	else:
		$weinerror = "yes";
		$ferror = "yes";
		echo "<br><br>
			<table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
			<tr>
			<td width='100%'>
			<p align='center'><b><font color='#FF0000'>".$lang_guild3["new_name"]." $newrank ".$lang_guild3["ass_new"]." $guildrank_n[$gonewrank].</font></b></p>
			<p align='center'><font color='#FF0000'><b>
			<a href='clan_home.php'>".$lang_clan["back"]."</a></b></font></td>
			</tr>
			</table><br><br>";
			mysql_query("UPDATE phaos_clan_in SET clanrank='$gonewrank' WHERE clanmember='$newrank'");
	endif;

	if($goldtomember == $lang_guild3["none"]):
		echo " ";
	else:
		$weinerror = "yes";
		$ferror = "yes";
		if($clancashbox > $goldto_n):
			echo "<br><br>
				<table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
				<tr>
				<td width='100%'>
				<p align='center'><b><font color='#FF0000'>$goldto_n ".$lang_guild3["giv_gold"]." $goldtomember.</font></b></p>
				<p align='center'><font color='#FF0000'><b>
				<a href='clan_home.php'>".$lang_clan["back"]."</a></b></font></td>
				</tr>
				</table><br><br>";
	
			$result_1 = mysql_query("SELECT * FROM phaos_characters WHERE name = '$goldtomember'");
			if ($row = mysql_fetch_array($result_1)) {
				$gold_o = $row["gold"];
			}
			/*echo "BEFORE: $gold_o";*/
			$gold_o = $gold_o + $goldto_n;
			$clancashbox = $clancashbox - $goldto_n;
			/*echo "AFTER: $gold_o";*/
			mysql_query("UPDATE phaos_clan_admin SET clancashbox='$clancashbox' WHERE clanname='$clanname'");
			mysql_query("UPDATE phaos_characters SET gold='$gold_o' WHERE name='$goldtomember'");
		else:
			echo "<br><br>
				<table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
				<tr>
				<td width='100%'>
				<p align='center'><b><font color='#FF0000'>".$lang_guild3["not_en_go"]." $goldto_n .</font></b></p>
				<p align='center'><font color='#FF0000'><b>
				<a href='clan_home.php'>".$lang_clan["back"]."</a></b></font></td>
				</tr>
				</table><br><br>";
		endif;
	endif;
}

// Gold to Guild ----------------------------------------------------------------------------------------------------->
if($n_gold == "Deposit Gold" and $givegold_n > "") {
	$ferror = "yes";
	if ($givegold_n<0) {
		echo "not allowed to takeback cash!";
		exit;
	} else {    
		/*echo "$gibgold <> $gold <> $name <> $clanname <> $clankasse";*/
		if($givegold_n <= $gold) {
			echo "<br><br>
				<table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
				<tr>
				<td width='100%'>
				<p align='center'><b><font color='#FF0000'>$username_ab ".$lang_guild3["plz_wait"]." $givegold_n ".$lang_guild3["gold_tr"].".</font></b></p>
				<p align='center'><font color='#FF0000'><b>
				<a href='clan_home.php'>".$lang_clan["back"]."</a></b></font></td>
				</tr>
				</table><br><br>";

			/*echo "BEFORE: clankasse> $clankasse : gold> $gold : givegold> $givegold : gibgold> $gibgold<br>";*/
			$clancashbox = $clancashbox + $givegold_n;
			$gold = $gold - $givegold_n;
			$givegold = $givegold + $givegold_n;
			/*echo "AFTER: clankasse> $clankasse : gold> $gold : givegold> $givegold : gibgold> $gibgold";*/
	
			mysql_query("UPDATE phaos_clan_admin SET clancashbox='$clancashbox' WHERE clanname='$clanname'");
			mysql_query("UPDATE phaos_characters SET gold='$gold' WHERE name='$username_ab'");
			mysql_query("UPDATE phaos_clan_in SET givegold='$givegold' WHERE clanmember='$username_ab'");
		} else {
			echo "<br><br>
				<table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
				<tr>
				<td width='100%'>
				<p align='center'><b><font color='#FF0000'>".$lang_guild3["not_hav"].".</font></b></p>
				<p align='center'><font color='#FF0000'><b>
				<a href='clan_home.php'>".$lang_clan["back"]."</a></b></font></td>
				</tr>
				</table><br><br>";
		}
	}
}

// ------------------------------------------------------------------------------------------------------------------->
if($newnames == "Change") {
	$ferror = "yes";
	if($T1 == ""){echo $lang_guild3["em_1"];$grerror = "yes";$ferror = "";}
	if($T2 == ""){echo $lang_guild3["em_2"];$grerror = "yes";$ferror = "";}
	if($T3 == ""){echo $lang_guild3["em_3"];$grerror = "yes";$ferror = "";}
	if($T4 == ""){echo $lang_guild3["em_4"];$grerror = "yes";$ferror = "";}
	if($T5 == ""){echo $lang_guild3["em_5"];$grerror = "yes";$ferror = "";}
	if($T6 == ""){echo $lang_guild3["em_6"];$grerror = "yes";$ferror = "";}
	if($T7 == ""){echo $lang_guild3["em_7"];$grerror = "yes";$ferror = "";}
	if($T8 == ""){echo $lang_guild3["em_8"];$grerror = "yes";$ferror = "";}
	if($T9 == ""){echo $lang_guild3["em_9"];$grerror = "yes";$ferror = "";}
	if($T10 == ""){echo $lang_guild3["em_10"];$grerror = "yes";}
	if($grerror == "") {
		echo "<br><br>
			<table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
			<tr>
			<td width='100%'>
			<p align='center'><b><font color='#FF0000'>".$lang_guild3["plz_new"].".</font></b></p>
			<p align='center'><font color='#FF0000'><b>
			<a href='clan_home.php'>".$lang_clan["back"]."</a></b></font></td>
			</tr>
			</table><br><br>";
		echo "<font color='#FF0000'>$clanname</font>";
		mysql_query("UPDATE phaos_clan_admin SET clanrank_1='$T1',clanrank_2='$T2',clanrank_3='$T3',clanrank_4='$T4',clanrank_5='$T5',clanrank_6='$T6',clanrank_7='$T7',clanrank_8='$T8',clanrank_9='$T9',clanrank_10='$T10' WHERE clanname='$clanname'");}
	}

	// ------------------------------------------------------------------------------------------------------------------->
	if($charname_n > "" and $toaccept == "yes") { # add new char to clan
		echo "<b><font color='#FF0000'> > $charname_n < ".$lang_guild3["acc_new"]."...</font></b><br>";

		$result_a = mysql_query ("SELECT * FROM phaos_clan_in WHERE clanmember = '$charname_n'");
		if ($row = mysql_fetch_array($result_a)) {
			$duplicate = "YES";
		}

		if($duplicate != "YES" AND $charname_n != "") {
			$query = "INSERT INTO phaos_clan_in
			(clanname,clanmember,clanindate,givegold,clanrank)
			VALUES
			('$clanname','$charname_n','$date_h','0','1')"; # i delete $clansig for this string ($clansig$charname_n)
			$req = mysql_query($query);
			if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}
			print ("<font color='#FF0000'><img src=images/guild_sign/$clan_sig alt=$clanname>$charname_n ".$lang_guild3["acc_2"].".</font><p><a href='clan_home.php'>".$lang_guild3["2guild_hall"]."</a>");
		} else {
			print ("<font color='#FF0000'><big> $charname_n ".$lang_guild3["not_acc"]."</font></big><br><a href='town_hall.php'>".$lang_clan["town_ret"]."To Town Hall</a><p><a href=\"clan_home.php\">".$lang_guild3["2guild_hall"]."</a>");
		}

		$query_3 = "DELETE FROM phaos_clan_search WHERE charname LIKE '$charname_n'";
		$result = mysql_query($query_3) or die ("Error in query: $query_3. " .
		mysql_error());

		$clanmembers ++;
		/*echo "$charname_n <> $clanname";*/
		mysql_query("UPDATE phaos_clan_admin SET clanmembers='$clanmembers' WHERE clanname='$clanname'");
		mysql_query("UPDATE phaos_characters SET name='$charname_n' WHERE name='$charname_n'"); # i delete $clansig for this string ($clansig$charname_n)
	}

	// ------------------------------------------------------------------------------------------------------------------->
	if($charname_n > "" and $toaccept == "no") { # no accept new char
		echo "<b><font color='#FF0000'>".$lang_guild3["user_rej"].".</font></b><br>";

		$query_3 = "DELETE FROM phaos_clan_search WHERE charname LIKE '$charname_n'";
		$result = mysql_query($query_3) or die ("Error in query: $query_3. " . mysql_error());
	}
	// ------------------------------------------------------------------------------------------------------------------->
	if($ferror == "") {
		echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2'>
			<tr>
			<td width='100%' bgcolor='#003300' align='center'>&nbsp;</td>
			</tr>
			<tr>
			<td width='100%' align='center' valign='top'>
			".$lang_guild3["well_com"]."$clanname.<br>
			".$lang_guild3["can_view"].".<br>
			<hr color='#FFFFFF' width='98%'><br>";

		echo "<table class='utktable' border='2' cellpadding='0' cellspacing='0' style='border-collapse: collapse' width='98%' id='AutoNumber7' bordercolorlight='#FF0000' bordercolordark='#FF0000'>
			<tr>
			<td width='100%' align='center' valign='top'><b><font size='4'>".$lang_guild3["our_slog"]."</font></b></td>
			</tr>
			<tr>
			<td width='100%' align='center' valign='top'>$clanslogan</td>
			</tr>
			</table><br>
			<hr color='#FFFFFF' width='98%'><br>";

		if($clan_user_edit == "yes") {
			// ------------------------------------------------------------------------------------------------------------------->
			echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' width='95%' id='AutoNumber8'>
				<tr>
				<td width='100%' bgcolor='#003300' align='center' valign='top'>
				<b>".$lang_guild3["can_del"]." !!!</b>
				</td>
				</tr>
				<tr>
				<td width='100%' bgcolor='#141414' align='center'>
				<p align='center'><a href='clan_delete.php?clanname=$clanname'>&gt; ".$lang_guild3["del_gu"]." &lt;</a>
				</td>
				</tr>
				</table><br>";

			// ------------------------------------------------------------------------------------------------------------------->
			echo "<form method='post' action='clan_home.php'>
				<table border='0' cellpadding='2' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='95%'>
				<tr>
				<td width='100%' colspan='2' bgcolor='#003300' align='center'><b>Options</b></td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#141414'>".$lang_guild3["giv_gold2"]." :</td>
				<td width='33%' bgcolor='#141414'><select size='1' name='goldtomember'>
				<option selected value='".$lang_guild3["none"]."'>".$lang_guild3["none"]."</option>";
			$result = mysql_query ("SELECT * FROM `phaos_clan_in` WHERE 1 AND `clanname` LIKE '$clanname'");
			while ($row = mysql_fetch_array($result)) {
				$clanmember = $row["clanmember"];
				echo "<option value='$clanmember'>$clanmember</option>";
			}
			echo "</select> ... <input type='text' name='goldto_n' size='10'></td></tr>";
			//  ----->>>
			echo "<tr>
				<td width='33%' bgcolor='#141414'>".$lang_guild3["ass_a_gu"]." :</td>
				<td width='33%' bgcolor='#141414'>
				<select size='1' name='newrank'>
				<option selected value='".$lang_guild3["none"]."'>".$lang_guild3["none"]."</option>";
			$result = mysql_query ("SELECT * FROM `phaos_clan_in` WHERE 1 AND `clanname` LIKE '$clanname'");
			while ($row = mysql_fetch_array($result)) {
				$clanmember = $row["clanmember"];
				$clanrank = $row["clanrank"];
				if($clanrank < "98") {
					echo "<option value='$clanmember'>$clanmember</option>";
				}
			}
			echo "</select> ... <select size='1' name='gonewrank'>
				<option selected value='".$lang_guild3["none"]."'>".$lang_guild3["none"]."</option>";
			$result_3 = mysql_query("SELECT * FROM phaos_clan_admin WHERE clanname = '$clanname'");
			if ($row = mysql_fetch_array($result_3)) {
				$clanname = $row["clanname"];
				$clanleader = $row["clanleader"];
				$clanleader_1 = $row["clanleader_1"];
				$clanbanner = $row["clanbanner"];
				#            $clansig = $row["clansig"];
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
			echo "<option value='1'>$clanrank_1</option>
				<option value='2'>$clanrank_2</option>
				<option value='3'>$clanrank_3</option>
				<option value='4'>$clanrank_4</option>
				<option value='5'>$clanrank_5</option>
				<option value='6'>$clanrank_6</option>
				<option value='7'>$clanrank_7</option>
				<option value='8'>$clanrank_8</option>
				<option value='9'>$clanrank_9</option>
				<option value='10'>$clanrank_10</option>";
			echo "</select></td>
				</tr>
				<tr>
				<td width='50%' bgcolor='#141414'>".$lang_guild3["gu_logo"]." :</td>
				<td width='50%' bgcolor='#141414'><input type='text' name='newlogo' size='40' maxlength='100'></td>
				</tr>
				<tr>
				<td width='50%' bgcolor='#141414'>".$lang_guild3["gu_ass"]." :</td>
				<td width='50%' bgcolor='#141414'>
				<select size='1' name='newguildhelp'>
				<option selected value='".$lang_guild3["none"]."'>".$lang_guild3["none"]."</option>";
			$result = mysql_query ("SELECT * FROM `phaos_clan_in` WHERE 1 AND `clanname` LIKE '$clanname'");
			while ($row = mysql_fetch_array($result)) {
				$clanmember = $row["clanmember"];
				$clanrank = $row["clanrank"];
				if($clanrank < "98") {
					echo "<option value='$clanmember'>$clanmember</option>";
				}
			}
			echo "</select></td>
				</tr>
				</table>
				<p align='center'><input class='buttont' type='submit' value='".$lang_guild3["update"]."' name='adjustment'></p></form>";

			// ------------------------------------------------------------------------------------------------------------------->
			echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='95%' id='AutoNumber1'>
				<tr>
				<td width='15%' bgcolor='#003300'>".$lang_guild3["app_li"]." :</td>
				<td width='51%' bgcolor='#003300'>".$lang_guild3["txt_li"]." :</td>
				<td width='19%' bgcolor='#003300' align='center'><p>".$lang_guild3["acc"]."</td>
				<td width='15%' bgcolor='#003300' align='center' ><p>".$lang_guild3["rej"]."</td>
				</tr>";

			$result = mysql_query ("SELECT * FROM `phaos_clan_search` WHERE 1 AND `clanname` LIKE '$clanname'");
			if ($row = mysql_fetch_array($result)) {
				do {
					$charname = $row["charname"];
					$description = $row["description"];

					echo "<tr>
						<td width='15%' bgcolor='#141414'>$charname</td>
						<td width='51%' bgcolor='#141414'>$description</td>
						<td width='19%' bgcolor='#141414' align='center'><a href='clan_home.php?charname_n=$charname&toaccept=yes'>Accept</a></td>
						<td width='15%' bgcolor='#141414' align='center'><a href='clan_home.php?charname_n=$charname&toaccept=no'>Reject</a></td>
						</tr>";
				} while ($row = mysql_fetch_array($result));
			} else {
				echo "<tr>
					<td colspan=4 align=center bgcolor='#141414'>".$lang_guild3["no_app"].".</td>
					</tr>";
			}

			echo "</table><br>";

			// ------------------------------------------------------------------------------------------------------------------->
			echo "<form method='post' action='clan_home.php'>
				<table border='0' cellpadding='2' cellspacing='0' style='border-collapse: collapse' width='95%' id='AutoNumber10'>
				<tr>
				<td width='100%' colspan='3' bgcolor='#003300' align='center' valign='top'>
				<b>".$lang_guild3["cus_here"]." !!!</b>
				</td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#002300'>".$lang_guild3["rank_nr"].".:</td>
				<td width='33%' bgcolor='#002300'>".$lang_guild3["old_rank_na"]." :</td>
				<td width='34%' bgcolor='#002300'>".$lang_guild3["new_rank_na"]." :</td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#141414'>".$lang_guild3["gu_ra_na"]." 1 :</td>
				<td width='33%' bgcolor='#141414'>$clanrank_1</td>
				<td width='34%' bgcolor='#141414'><input type='text' name='T1' size='30' value='$clanrank_1'></td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#282828'>".$lang_guild3["gu_ra_na"]." 2 :</td>
				<td width='33%' bgcolor='#282828'>$clanrank_2</td>
				<td width='34%' bgcolor='#282828'><input type='text' name='T2' size='30' value='$clanrank_2'></td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#141414'>".$lang_guild3["gu_ra_na"]." 3 :</td>
				<td width='33%' bgcolor='#141414'>$clanrank_3</td>
				<td width='34%' bgcolor='#141414'><input type='text' name='T3' size='30' value='$clanrank_3'></td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#282828'>".$lang_guild3["gu_ra_na"]." 4 :</td>
				<td width='33%' bgcolor='#282828'>$clanrank_4</td>
				<td width='34%' bgcolor='#282828'><input type='text' name='T4' size='30' value='$clanrank_4'></td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#141414'>".$lang_guild3["gu_ra_na"]." 5 :</td>
				<td width='33%' bgcolor='#141414'>$clanrank_5</td>
				<td width='34%' bgcolor='#141414'><input type='text' name='T5' size='30' value='$clanrank_5'></td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#282828'>".$lang_guild3["gu_ra_na"]." 6 :</td>
				<td width='33%' bgcolor='#282828'>$clanrank_6</td>
				<td width='34%' bgcolor='#282828'><input type='text' name='T6' size='30' value='$clanrank_6'></td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#141414'>".$lang_guild3["gu_ra_na"]." 7 :</td>
				<td width='33%' bgcolor='#141414'>$clanrank_7</td>
				<td width='34%' bgcolor='#141414'><input type='text' name='T7' size='30' value='$clanrank_7'></td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#282828'>".$lang_guild3["gu_ra_na"]." 8 :</td>
				<td width='33%' bgcolor='#282828'>$clanrank_8</td>
				<td width='34%' bgcolor='#282828'><input type='text' name='T8' size='30' value='$clanrank_8'></td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#141414'>".$lang_guild3["gu_ra_na"]." 9 :</td>
				<td width='33%' bgcolor='#141414'>$clanrank_9</td>
				<td width='34%' bgcolor='#141414'><input type='text' name='T9' size='30' value='$clanrank_9'></td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#282828'>".$lang_guild3["gu_ra_na"]." 10 :</td>
				<td width='33%' bgcolor='#282828'>$clanrank_10</td>
				<td width='34%' bgcolor='#282828'><input type='text' name='T10' size='30' value='$clanrank_10'></td>
				</tr>
				</table><br>
				<input class='buttont' type='submit' value='".$lang_guild3["update"]."' name='newnames'>
				<input class='buttont' type='reset' value='".$lang_clan["reset"]."' name='B2'><br>
				</form>
				<br>";
		}

		// Guild Data ------------------------------------------------------------------------------------------------------>
		echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' width='95%' id='AutoNumber5' height='114'>
			<tr>
			<td width='100%' height='19' colspan='2' bgcolor='#003300' align='center' valign='top'>
			<b>".$lang_guild3["data_at"]." $date_h</b>
			</td>
			</tr>
			<tr>
			<td width='20%' height='19' bgcolor='#141414'>".$lang_guild["master"]." :</td>
			<td width='80%' height='19' bgcolor='#141414'>$clanleader</td>
			</tr>
			<tr>
			<td width='20%' height='19' bgcolor='#282828'>".$lang_guild["assist"]." :</td>
			<td width='80%' height='19' bgcolor='#282828'>$clanleader_1</td>
			</tr>
			<tr>
			<td width='20%' height='19' bgcolor='#141414'>".$lang_guild["gu_gold"]." :</td>
			<td width='80%' height='19' bgcolor='#141414'>$clancashbox</td>
			</tr>
			<tr>
			<td width='20%' height='19' bgcolor='#282828'>".$lang_guild["sig"]." :</td>
			<td width='80%' height='19' bgcolor='#282828'><img src=images/guild_sign/$clan_sig alt=$clanname></td>
			</tr>
			<tr>
			<td width='20%' height='19' bgcolor='#141414'>".$lang_guild["banner"]." :</td>";
		if($clanbanner == ""):
			echo "<td width='80%' height='19' bgcolor='#141414'> $clanbanner</td>";
		else:
			echo "<td width='80%' height='19' bgcolor='#141414'><img border='0' src='$clanbanner' width='213' height='122'></td>";
		endif;
		echo "</tr>
			<tr>
			<td width='20%' height='19' bgcolor='#282828'>".$lang_guild3["gu_me"]." :</td>
			<td width='80%' height='19' bgcolor='#282828'>$clanmembers</td>
			</tr>
			</table><br>";

		// ------------------------------------------------------------------------------------------------------------------->
		echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' width='95%' id='AutoNumber4'>
			<tr>
			<td width='100%' bgcolor='#003300' colspan='4' align='center' valign='top'>
			<b>".$lang_guild3["gu_mes"]." :</b>
			</td>
			</tr>
			<tr>
			<td width='25%' bgcolor='#002300'>".$lang_name." :</td>
			<td width='25%' bgcolor='#002300'>".$lang_guild3["guu_ra"]." :</td>
			<td width='25%' bgcolor='#002300'>".$lang_guild3["gld_depo"].":</td>
			<td width='25%' bgcolor='#002300'>".$lang_guild3["enlist_on"]." :</td>
			</tr>";

		$result = mysql_query ("SELECT * FROM `phaos_clan_in` WHERE 1 AND `clanname` LIKE '$clanname'");
		while ($row = mysql_fetch_array($result)) {
			$clanmember = $row["clanmember"];
			$clanindate = $row["clanindate"];
			$givegold = $row["givegold"];
			$clanrank = $row["clanrank"];

		if($clanrank == "1"):
			$n_clanrank = $clanrank_1;
		elseif($clanrank == "2"):
			$n_clanrank = $clanrank_2;
		elseif($clanrank == "3"):
			$n_clanrank = $clanrank_3;
		elseif($clanrank == "4"):
			$n_clanrank = $clanrank_4;
		elseif($clanrank == "5"):
			$n_clanrank = $clanrank_5;
		elseif($clanrank == "6"):
			$n_clanrank = $clanrank_6;
		elseif($clanrank == "7"):
			$n_clanrank = $clanrank_7;
		elseif($clanrank == "8"):
			$n_clanrank = $clanrank_8;
		elseif($clanrank == "9"):
			$n_clanrank = $clanrank_9;
		elseif($clanrank == "10"):
			$n_clanrank = $clanrank_10;
		elseif($clanrank == "98"):
			$n_clanrank = "Guild Assistant";
		elseif($clanrank == "99"):
			$n_clanrank = "Guild Master";
		endif;

		echo "<tr>
			<td width='25%' bgcolor='#141414'>$clanmember</td>
			<td width='25%' bgcolor='#141414'>$n_clanrank</td>
			<td width='25%' bgcolor='#141414'>$givegold</td>
			<td width='25%' bgcolor='#141414'>$clanindate</td>
			</tr>";
	}

	echo "</table><br>";

	// ------------------------------------------------------------------------------------------------------------------->
	$result = mysql_query ("SELECT * FROM `phaos_characters` WHERE 1 AND `username` LIKE '$PHP_PHAOS_USER'");
	if ($row = mysql_fetch_array($result)) {
		$name = $row["name"];
		$gold = $row["gold"];
	}

	$result_a = mysql_query ("SELECT * FROM `phaos_clan_in` WHERE 1 AND `clanmember` LIKE '$name'");
	if ($row = mysql_fetch_array($result_a)) {
		$clanrank = $row["clanrank"];
	}

	if($clanrank == "1"):
		$n_clanrank = $clanrank_1;
	elseif($clanrank == "2"):
		$n_clanrank = $clanrank_2;
	elseif($clanrank == "3"):
		$n_clanrank = $clanrank_3;
	elseif($clanrank == "4"):
		$n_clanrank = $clanrank_4;
	elseif($clanrank == "5"):
		$n_clanrank = $clanrank_5;
	elseif($clanrank == "6"):
		$n_clanrank = $clanrank_6;
	elseif($clanrank == "7"):
		$n_clanrank = $clanrank_7;
	elseif($clanrank == "8"):
		$n_clanrank = $clanrank_8;
	elseif($clanrank == "9"):
		$n_clanrank = $clanrank_9;
	elseif($clanrank == "10"):
		$n_clanrank = $clanrank_10;
	elseif($clanrank == "98"):
		$n_clanrank = "Guild Assistant";
	elseif($clanrank == "99"):
		$n_clanrank = "Guild Master";
	endif;

	echo "<form method='post' action='clan_home.php?gold=$gold&clancashbox=$clancashbox'>
		<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' width='95%' id='AutoNumber6'>
		<tr>
		<td width='102%' colspan='5' bgcolor='#003300' align='center' valign='top'>
		<b>".$lang_guild3["ur_inf"].":</b>
		</td>
		</tr>
		<tr>
		<td width='20%' bgcolor='#002300'>".$lang_name." :</td>
		<td width='20%' bgcolor='#002300'>".$lang_guild3["guu_ra"]." :</td>
		<td width='20%' bgcolor='#002300'>".$lang_gold." :</td>
		<td width='20%' bgcolor='#002300'><p align='center'>".$lang_guild3["dep_how_gld"]." :</td>
		<td width='20%' bgcolor='#002300'><p align='center'>".$lang_guild3["lft_gu_on"]." :</td>
		</tr>
		<tr>
		<td width='20%' bgcolor='#141414'>$name</td>
		<td width='20%' bgcolor='#141414'>$n_clanrank</td>
		<td width='20%' bgcolor='#141414'>$gold</td>
		<td width='20%' bgcolor='#141414'><p align='center'><input type='text' name='givegold_n' size='7'></td>";
	if($clanrank == "99"):
		echo "<td width='20%' bgcolor='#141414'><p align='center'> &nbsp </td>";
	else:
		echo "<td width='20%' bgcolor='#141414'><p align='center'><a href='clan_leave.php?clanname=$clanname&clan_user_name=$clan_user_name&clanmembers=$clanmembers'>&gt; ".$lang_guild3["liv"]." &lt;</a></td>";
	endif;

	echo "</tr>
		</table><br>
		<input class='buttont' type='submit' value='".$lang_guild3["dep_golds"]."' name='n_gold'>
		<input class='buttont' type='reset' value='".$lang_clan["reset"]."' name='B2'><br><br>
		</form>";

	echo "</td>
		</tr>
		<tr>
		<td width='100%' bgcolor='#003300'><a href='town_hall.php'>".$lang_clan["town_ret"]."</a></td>
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
