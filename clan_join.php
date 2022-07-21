<?php
include "aup.php";
include "header.php";

$character = new character($PHP_PHAOS_CHARID);

// make sure this requested shop is at the players location
if (!($shop_id = shop_valid($character->location, 'town_hall.php'))) {
	echo $lang_markt["no_sell"].'</body></html>' ;
	exit;
}

$clanmemberid = $character->id;
$clanmember = $character->name;

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


$result = mysql_query ("SELECT * FROM phaos_clan_in WHERE clanmemberid = '$clanmemberid'");
if (($row = mysql_fetch_array($result))) {
    $inclan = "yes";
}

$query = "SELECT c.clanname, c.clanleader, c.clanleaderid, c.clansig, c.clanslogan, count(i.clanmemberid) as clanmembers, c.clancreatedate, c.clanbanner, s.description "
        . "FROM phaos_clan_admin c "
        . "LEFT OUTER JOIN phaos_clan_in i ON c.clanname = i.clanname "
        . "LEFT OUTER JOIN phaos_clan_search s ON c.clanname = s.clanname AND s.clanmemberid = '$clanmemberid' "
        . "GROUP BY c.clanname, c.clanleader, c.clanleaderid, c.clansig, c.clanslogan, c.clancreatedate, c.clanbanner, s.description "
        . "ORDER BY clanmembers DESC, clanname ASC";
$result_0 = mysql_query ($query);
while ($row = mysql_fetch_array($result_0)) {
	$clanname = $row["clanname"];
	$clanleader = $row["clanleader"];
        $clanleaderid = $row["clanleaderid"];
	$clansig = $row["clansig"];
	$clanslogan = $row["clanslogan"];
	$clanmembers = $row["clanmembers"];
	$clancreatedate = $row["clancreatedate"];
	$clanbanner = $row["clanbanner"];
        $description = $row["description"];

	echo "<tr>
		<td width='20%' >$clanname</td>
                <td width='20%' ><font color='#666699'><b>$clansig$clanleader</b></font></td>
                <td width='20%' >$clanmembers</td>
                <td width='20%' >$clansig</td>";
	if($inclan == "yes"):
		if(file_exists($clanbanner)):
			echo "<td width='20%' ><p align='center'><img border='0' src=\"$clanbanner\" width='81' height='77'></td>";
		else:
			echo "<td width='20%' ><p align='center'>$clanname</td>";
		endif;
	else:
                if ($description !== null) {
                      echo "<td width='20%'><p align='center'>Request pending<br /><a href=\"clan_send.php?clanname_cancel=".urlencode($clanname)."\">Cancel</a></p></td>";
                } else {
                    if(file_exists($clanbanner)):
                            echo "<td width='20%' ><p align='center'><a href=\"clan_send.php?clanname_ask=".urlencode($clanname)."\"><img border='0' src=\"$clanbanner\" width='81' height='77'></a></td>";
                    else:
                            echo "<td width='20%' ><p align='center'><a href=\"clan_send.php?clanname_ask=".urlencode($clanname)."\">$clanname</a></td>";
                    endif;
                }
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
