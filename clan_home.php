<?php
include "header.php";

/*OPTIMIZE TABLE 'phaos_characters'*/

function get_member_rank($clanname, $clanmember) {
  $res = mysql_query("SELECT * FROM phaos_clan_in WHERE clanmember='$clanmember'");
  if (($row = mysql_fetch_array($res))) {
      $character_clan = $row['clanname'];
      if ($character_clan === $clanname) {
          return $row['clanrank'];
      }
  }
  return 0;
}

echo"<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber1' height='103'>
	<tr>
	<td width='100%' height='100%' align='center'>
	<img src='lang/".$lang."_images/clan_home.png'><br></td>
	</tr>
	<tr>
	<td align='center' valign='top' height='63'>";


$character = new character($PHP_PHAOS_CHARID);

// make sure this requested shop is at the players location
if (!($shop_id = shop_valid($character->location, 'town_hall.php'))) {
	echo $lang_markt["no_sell"].'</table></body></html>' ;
	exit;
}

$date_h = date('m.d.Y - H:i:s');
$messages = array();
$back = 'clan_home.php';
$error = false;

$clanmember_id = $character->id;
$clanmember = $character->name;
$gold = $character->gold;
$clanname = '';

$result_1 = mysql_query("SELECT * FROM phaos_clan_in WHERE clanmember = '$clanmember'");
if ($row = mysql_fetch_array($result_1)) {
        $clanname = $row["clanname"];
	$clanindate = $row["clanindate"];
	$givegold = $row["givegold"];
	$clanrank = intval($row["clanrank"]);

        $result_2 = mysql_query("SELECT * FROM phaos_clan_admin WHERE clanname = '$clanname'");
        if ($row = mysql_fetch_array($result_2)) {
                $clanname = $row["clanname"];
                $clanleader = $row["clanleader"];
                $clanleaderid = $row["clanleaderid"];
                $clanbanner = $row["clanbanner"];
                $clansig = $row["clansig"];
                $clanlocation = $row["clanlocation"];
                $clanslogan = $row["clanslogan"];
                $clancashbox = $row["clancashbox"];
                $clancreatedate = $row["clancreatedate"];

                $guildrank_n = array();
                $guildrank_n[1] = $row["clanrank_1"];
                $guildrank_n[2] = $row["clanrank_2"];
                $guildrank_n[3] = $row["clanrank_3"];
                $guildrank_n[4] = $row["clanrank_4"];
                $guildrank_n[5] = $row["clanrank_5"];
                $guildrank_n[6] = $row["clanrank_6"];
                $guildrank_n[7] = $row["clanrank_7"];
                $guildrank_n[8] = $row["clanrank_8"];
                $guildrank_n[9] = $row["clanrank_9"];
                $guildrank_n[10] = $row["clanrank_10"];
        }

        $clanleader_1 = '';
        $clanleader_1_id = null;
        $result_3 = mysql_query("SELECT clanmember, clanmemberid FROM phaos_clan_in WHERE clanname='$clanname' AND clanrank='98'");
        if ($row = mysql_fetch_array($result_3)) {
              $clanleader_1 = $row["clanmember"];
              $clanleader_1_id = $row["clanmemberid"];
        }
}

if($clanname === '') {
        $messages[] = $lang_guild3["not_in"];
        $back = 'town_hall.php';
        $error = true;
}

if ($clanrank === 99) {
  $clan_user_edit = "yes";
  $clan_delete = "yes";
} else if($clanrank === 98):
  $clan_user_edit = "yes";
  $clan_delete = "no";
else:
  $clan_user_edit = "no";
  $clan_delete = "no";
endif;

if($error === false && $clan_user_edit === "yes" && $adjustment == "Update" ) {
	if($newlogo !== $clanbanner):
		mysql_query("UPDATE phaos_clan_admin SET clanbanner='$newlogo' WHERE clanname='$clanname'");
                $messages[] = $lang_guild3["logo_chg"]. " \"$newlogo\"";
	endif;

	if($newguildhelp !== $clanleader_1):
                if ($newguildhelp === "") {
                  mysql_query("UPDATE phaos_clan_in SET clanrank='1' WHERE clanrank='98' AND clanname='$clanname'");
                  $messages[] = $lang_guild3["new_ass"] . " " . $lang_guild3["none"];
                } else {
                  $member_rank = get_member_rank($clanname, $newguildhelp);
                  if ($member_rank === 0 || $member_rank >= 98) {
                      $messages[] = $lang_guild3["invalid_member"];
                      $error = true;
                  } else {
                      $res = mysql_query("SELECT * FROM phaos_characters WHERE name = '$newguildhelp'");
                      $row = mysql_fetch_array($res);
                      $clanleader_1_id = $row['id'];

                      mysql_query("UPDATE phaos_clan_in SET clanrank='1' WHERE clanrank='98' AND clanname='$clanname'");
                      mysql_query("UPDATE phaos_clan_in SET clanrank='98' WHERE clanmember='$newguildhelp' AND clanname='$clanname'");
                      $messages[] = $lang_guild3["new_ass"] . " " . $newguildhelp;
                  }
                }
	endif;
	
	if($error === false && $newrank !== ""):
                $gonewrank = intval($gonewrank);
                $member_rank = get_member_rank($clanname, $newrank);
                if ($member_rank === 0 || $member_rank >= 99 || $gonewrank >= 98) {
                    $messages[] = $lang_guild3["invalid_member"];
                    $error = true;
                } else {
                
                    $check_cl_1 = false;
                    if ($gonewrank === 0) {
                      mysql_query("DELETE FROM phaos_clan_in WHERE clanmember='$newrank'");

                      $check_cl_1 = true;
                    } else if ($gonewrank > 0 && $gonewrank < 98) {
                      mysql_query("UPDATE phaos_clan_in SET clanrank='$gonewrank' WHERE clanmember='$newrank' AND clanname='$clanname'");
                      $check_cl_1 = true;
                    }

                    if ($check_cl_1 && $newrank === $clanleader_1) {
                      mysql_query("UPDATE phaos_clan_admin SET clanleader_1=NULL WHERE clanname='$clanname'");
                    }
                
                    $messages[] = $lang_guild3["rank_new"]. " " .$guildrank_n[$gonewrank];
                }
	endif;

	if($error === false && $goldtomember !== "") {
                $goldto_n = intval($goldto_n);
                $member_rank = get_member_rank($clanname, $goldtomember);
                if ($member_rank === 0) {
                    $messages[] = $lang_guild3["invalid_member"];
                    $error = true;
                } else if ($clancashbox < $goldto_n) {
                    $messages[] = $lang_guild3["not_en_go"] . " " . $goldto_n;
                    $error = true;
                } else {
                    $result_1 = mysql_query("SELECT * FROM phaos_characters WHERE name = '$goldtomember'");
                    if ($row = mysql_fetch_array($result_1)) {
                            $gold_o = $row["gold"];
                    }

                    $gold_o = $gold_o + $goldto_n;
                    $clancashbox = $clancashbox - $goldto_n;
                    mysql_query("UPDATE phaos_clan_admin SET clancashbox='$clancashbox' WHERE clanname='$clanname'");
                    mysql_query("UPDATE phaos_characters SET gold='$gold_o' WHERE name='$goldtomember'");
                    $messages[] = $goldto_n . " " . $lang_guild3["giv_gold"] . " " . $goldtomember;
                }
      }
}

// Gold to Guild ----------------------------------------------------------------------------------------------------->
if($error === false && $n_gold == "Deposit Gold" && $givegold_n != "0") {
    $givegold_n = intval($givegold_n);
    /*echo "$gibgold <> $gold <> $name <> $clanname <> $clankasse";*/
    if ($givegold_n <= 0 || $givegold_n > $gold) {
      $message[] = $lang_guild3["not_hav"];
      $error = true;
    } else {
      $clancashbox = $clancashbox + $givegold_n;
      $gold = $gold - $givegold_n;
      $givegold = $givegold + $givegold_n;
      /*echo "AFTER: clankasse> $clankasse : gold> $gold : givegold> $givegold : gibgold> $gibgold";*/

      mysql_query("UPDATE phaos_clan_admin SET clancashbox='$clancashbox' WHERE clanname='$clanname'");
      mysql_query("UPDATE phaos_characters SET gold='$gold' WHERE name='$clanmember'");
      mysql_query("UPDATE phaos_clan_in SET givegold='$givegold' WHERE clanmember='$clanmember'");
      $message[] = $clanmember .$lang_guild3["plz_wait"]." $givegold_n ".$lang_guild3["gold_tr"];
    }
}

// ------------------------------------------------------------------------------------------------------------------->
if($error === false && $clan_user_edit === "yes" && $newnames == "Update") {
	$ferror = "yes";
        
        $ranks = array(1 => $T1, $T2, $T3, $T4, $T5, $T6, $T7, $T8, $T9, $T10);
        for ($i = 1; $i <= 10; $i++) {
          if ($ranks[$i] === '') {
            $message[] = $lang_guild3["em_$i"];
            $error = true;
          }
        }
        
        if ($error === false) {
          mysql_query("UPDATE phaos_clan_admin SET "
                  . "clanrank_1='$T1',"
                  . "clanrank_2='$T2',"
                  . "clanrank_3='$T3',"
                  . "clanrank_4='$T4',"
                  . "clanrank_5='$T5',"
                  . "clanrank_6='$T6',"
                  . "clanrank_7='$T7',"
                  . "clanrank_8='$T8',"
                  . "clanrank_9='$T9',"
                  . "clanrank_10='$T10'"
                  . " WHERE clanname='$clanname'");
          $message[] = $lang_guild3["plz_new"];
        }
}

	// ------------------------------------------------------------------------------------------------------------------->
	if($error === false && $charname_n != "" and $toaccept == "yes") { # add new char to clan
		echo "<b><font color='#FF0000'> > $charname_n < ".$lang_guild3["acc_new"]."...</font></b><br>";

                $res = mysql_query("SELECT * FROM phaos_characters WHERE name = '$charname_n'");
                $row = mysql_fetch_array($res);
                if ($row === false) {
                  $message = $lang_guild3["invalid_member"];
                  $error = true;
                } else {
                  $clanmember_id = $row['id'];

                  $res = mysql_query ("SELECT * FROM phaos_clan_in WHERE clanmemberid = '$clanmember_id'");
                  if ($row !== false) { // check if already in a clan
                      $req = mysql_query(
                          "INSERT INTO phaos_clan_in (`clanname`,`clanmember`,`clanmemberid`,`clanindate`,`givegold`,`clanrank`)
                          VALUES ('$clanname','$charname_n','$clanmember_id','$date_h','0','1')");
                      if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}

                      $result = mysql_query("DELETE FROM phaos_clan_search WHERE clanmember = '$charname_n'");

                      $message[] = $charname_n.$lang_guild3["acc_2"];
                  }
                }
	}

	// ------------------------------------------------------------------------------------------------------------------->
	if($charname_n != "" and $toaccept == "no") { # no accept new char
                $message[] = $lang_guild3["user_rej"];
		mysql_query("DELETE FROM phaos_clan_search WHERE clanmember = '$charname_n'");
	}
        
        if (count($messages) > 0) {
          echo "<br><br>
                  <table border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
                  <tr>
                  <td width='100%'>";
          
          foreach ($messages as $message) {
              echo "<p align='center'><b><font color='#FF0000'>".$message. "</font></b></p>\n";
          }
          
          echo "<p align='center'><font color='#FF0000'><b>
                  <a href='$back'>".$lang_clan["back"]."</a></b></font></td>
                  </tr>
                  </table><br><br>";
        } else

	// ------------------------------------------------------------------------------------------------------------------->
	{
                $clan_members = array();
                $result = mysql_query ("SELECT c.id, c.name, i.clanrank "
                        . "FROM `phaos_clan_in` i INNER JOIN phaos_characters c ON i.clanmemberid = c.id "
                        . "WHERE `clanname` = '$clanname'");
                while ($row = mysql_fetch_array($result)) {
                  $clan_members[] = $row;
                }
                $clanmembers = count($clan_members);

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

		if($clan_delete == "yes") {
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
                }
		if($clan_user_edit == "yes") {
			// ------------------------------------------------------------------------------------------------------------------->
			echo "<form method='post' action='clan_home.php'>
				<table border='0' cellpadding='2' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='95%'>
				<tr>
				<td width='100%' colspan='2' bgcolor='#003300' align='center'><b>Options</b></td>
				</tr>
				<tr>
				<td width='33%' bgcolor='#141414'>".$lang_guild3["giv_gold2"]." :</td>
				<td width='33%' bgcolor='#141414'><select size='1' name='goldtomember'>
				<option value=\"\" selected>".$lang_guild3["none"]."</option>";
			foreach ($clan_members as $row) {
				$clanmember_id = $row["id"];
                                $clanmember_name = $row["name"];
				echo "<option value=\"$clanmember_name\">$clanmember_name</option>";
			}
			echo "</select> ... <input type='text' name='goldto_n' size='10'></td></tr>";
			//  ----->>>
			echo "<tr>
				<td width='33%' bgcolor='#141414'>".$lang_guild3["ass_a_gu"]." :</td>
				<td width='33%' bgcolor='#141414'>
				<select size='1' name='newrank'>
				<option selected value=\"\">".$lang_guild3["none"]."</option>";
			foreach ($clan_members as $row) {
				$clanmember_id = $row["id"];
                                $clanmember_name = $row["name"];
                                $clanmember_rank = intval($row['clanrank']);
				if($clanmember_rank < 98) {
					echo "<option value=\"$clanmember_name\">$clanmember_name</option>";
				}
			}
			echo "</select> ... <select size='1' name='gonewrank'>
				<option selected value=\"0\">".$lang_guild3["none"]."</option>";
			$result_3 = mysql_query("SELECT * FROM phaos_clan_admin WHERE clanname = '$clanname'");
			if ($row = mysql_fetch_array($result_3)) {
				$clanname = $row["clanname"];
				$clanleader = $row["clanleader"];
				$clanleaderid = $row["$clanleaderid"];
				$clanbanner = $row["clanbanner"];
				$clansig = $row["clansig"];
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
				<td width='50%' bgcolor='#141414'><input type='text' name='newlogo' size='40' maxlength='100' value=\"$clanbanner\"></td>
				</tr>
				<tr>
				<td width='50%' bgcolor='#141414'>".$lang_guild3["gu_ass"]." :</td>
				<td width='50%' bgcolor='#141414'>
				<select size='1' name='newguildhelp'>
				<option value=\"\" ".($clanleader_1 === '' ? 'selected' : '').">".$lang_guild3["none"]."</option>";
			foreach ($clan_members as $row) {
				$clanmember_id = $row["id"];
                                $clanmember_name = $row["name"];
                                $clanmember_rank = intval($row['clanrank']);
				if($clanmember_rank < 99) {
                                        $selected = $clanmember_rank === 98 ? ' selected' : '';
					echo "<option value=\"$clanmember_name\"$selected>$clanmember_name</option>";
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

			$result = mysql_query ("SELECT c.id, c.name, s.description "
                          . "FROM `phaos_clan_search` s INNER JOIN `phaos_characters` c ON s.clanmemberid = c.id "
                          . "WHERE s.`clanname` = '$clanname'");
			if ($row = mysql_fetch_array($result)) {
				do {
                                        $charname_id = $row["id"];
					$charname = $row["name"];
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
			<td width='80%' height='19' bgcolor='#282828'>$clansig</td>
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

		$result = mysql_query ("SELECT * FROM `phaos_clan_in` WHERE 1 AND `clanname` = '$clanname'");
		while ($row = mysql_fetch_array($result)) {
			$clanmember = $row["clanmember"];
			$clanindate = $row["clanindate"];
			$givegold = $row["givegold"];
			$clanrank = intval($row["clanrank"]);

		if($clanrank >= 1 && $clanrank <= 10):
			$n_clanrank = $guildrank_n[$clanrank];
		elseif($clanrank === 98):
			$n_clanrank = "Guild Assistant";
		elseif($clanrank === 99):
			$n_clanrank = "Guild Master";
		endif;

		echo "<tr>
			<td width='25%' bgcolor='#141414'>$clansig$clanmember</td>
			<td width='25%' bgcolor='#141414'>$n_clanrank</td>
			<td width='25%' bgcolor='#141414'>$givegold</td>
			<td width='25%' bgcolor='#141414'>$clanindate</td>
			</tr>";
	}

	echo "</table><br>";

	// ------------------------------------------------------------------------------------------------------------------->
	$result = mysql_query ("SELECT * FROM `phaos_characters` WHERE 1 AND `username` = '$PHP_PHAOS_USER'");
	if ($row = mysql_fetch_array($result)) {
		$name = $row["name"];
		$gold = $row["gold"];
	}

	$result_a = mysql_query ("SELECT * FROM `phaos_clan_in` WHERE 1 AND `clanmember` = '$name'");
	if ($row = mysql_fetch_array($result_a)) {
		$clanrank = intval($row["clanrank"]);
	}

        if($clanrank >= 1 && $clanrank <= 10):
                $n_clanrank = $guildrank_n[1];
        elseif($clanrank === 98):
                $n_clanrank = "Guild Assistant";
        elseif($clanrank === 99):
                $n_clanrank = "Guild Master";
        endif;

	echo "<form method='post' action='clan_home.php'>
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
		<td width='20%' bgcolor='#141414'>$clansig$name</td>
		<td width='20%' bgcolor='#141414'>$n_clanrank</td>
		<td width='20%' bgcolor='#141414'>$gold</td>
		<td width='20%' bgcolor='#141414'><p align='center'><input type='text' name='givegold_n' size='7'></td>";
	if($clanrank === 99):
		echo "<td width='20%' bgcolor='#141414'><p align='center'> &nbsp </td>";
	else:
		echo "<td width='20%' bgcolor='#141414'><p align='center'><a href='clan_leave.php?clanname=$clanname&clan_user_name=$clan_user_name'>&gt; ".$lang_guild3["liv"]." &lt;</a></td>";
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
