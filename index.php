<?php include "header.php"; ?>
<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
<tr>
<td align=center valign=top>

<?php
$result = mysql_query ("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
if ($row = mysql_fetch_array($result)) {
	$char_loc = $row["location"];
}
$result = mysql_query ("SELECT * FROM phaos_locations WHERE id = '$char_loc'");
if ($row = mysql_fetch_array($result)) {
		$location_name = $row["name"];
}

// Added by dragzone---
$ts = date("Y-m-d H:i:s");
$login_result = mysql_query ("SELECT * FROM phaos_users WHERE username = '$usrname'");
if ($row = mysql_fetch_array($login_result)) {
    $vts = $row["login_dat"];
    if ($ts != $row["login_dat"]) {
      $change = "UPDATE phaos_users Set login_dat = '$ts' WHERE username = '$usrname'";
      $update = mysql_query($change);
}}
//---------------------

if($char_loc == "") {
	echo ("<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"create_character.php\";',0);</script>\n");
	exit;
}
?>

<table border=0 cellspacing=5 cellpadding=0>
<tr>
<td align=center width="33%">
<img src="images/torch.gif">
</td>
<td align=center width="34%">
<img src="lang/<?php echo $lang ?>_images/welcome.png">
</td>
<td align=center width="33%">
<img src="images/torch.gif">
</td>
</tr>
<tr>
<td align=center colspan=3>
<a href="http://www.worldofphaos.com" target="_blank"><b><?php echo $lang_home["need_help"]; ?></b></a>
<br>
<br>
<a href="who.php"><b><?php echo $lang_home["hos_online"]; ?></b></a>
<br>
<br>
<a href="all_users.php"><b><?php echo $lang_added["ad_all-users"]; ?></b></a>
<br>
<br>
<a href="credits.php"><b><?php echo $lang_home["creddy"]; ?></b></a>
</td>
</tr>
<tr>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<td align=center colspan=3>
<hr>
<b><?php echo $lang_home["sevap"]; ?></b>
<P>
<input type="hidden" name="cmd" value="_s-xclick">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
</td>
</form>
</tr>

<tr>
<td colspan=3 align=center>
<hr>
<table cellspacing=0 cellpadding=3 border=1>
<tr>
<td align=center colspan=2>
<b><?php print $lang_home['maps']; ?></b>
</td>
</tr>
<tr>
<form method="post" action="map_to_image.php" target="_blank">
<td align=center>
<input type="hidden" name="begin_id" value="0">
<input type="hidden" name="end_id" value="10000">
<input type="submit" value="<?php print $lang_home['map_of_1']; ?>" style="background:#000000;border:none;">
</td>
</form>
<form method="post" action="map_to_image.php" target="_blank">
<td align=center>
<input type="hidden" name="begin_id" value="10000">
<input type="hidden" name="end_id" value="20000">
<input type="submit" value="<?php print $lang_home['map_of_2']; ?>" style="background:#000000;border:none;">
</td>
</form>
</tr>
<tr>
<form method="post" action="map_to_image.php" target="_blank">
<td align=center>
<input type="hidden" name="begin_id" value="20000">
<input type="hidden" name="end_id" value="30000">
<input type="submit" value="<?php print $lang_home['map_of_3']; ?>" style="background:#000000;border:none;">
</td>
</form>
<form method="post" action="map_to_image.php" target="_blank">
<td align=center>
<input type="hidden" name="begin_id" value="30000">
<input type="hidden" name="end_id" value="40000">
<input type="submit" value="<?php print $lang_home['map_of_4']; ?>" style="background:#000000;border:none;">
</td>
</form>
</tr>
<tr>
<form method="post" action="map_to_image.php" target="_blank">
<td align=center>
<input type="hidden" name="begin_id" value="40000">
<input type="hidden" name="end_id" value="50000">
<input type="submit" value="<?php print $lang_home['map_of_5']; ?>" style="background:#000000;border:none;">
</td>
</form>
<form method="post" action="map_to_image.php" target="_blank">
<td align=center>
<input type="hidden" name="begin_id" value="50000">
<input type="hidden" name="end_id" value="60000">
<input type="submit" value="<?php print $lang_home['map_of_6']; ?>" style="background:#000000;border:none;">
</td>
</form>
</tr>
<tr>
<form method="post" action="map_to_image.php" target="_blank">
<td align=center>
<input type="hidden" name="begin_id" value="60001">
<input type="hidden" name="end_id" value="61681">
<input type="submit" value="<?php print $lang_home['map_of_7']; ?>" style="background:#000000;border:none;">
</form>
</td>
<form method="post" action="map_to_image.php" target="_blank">
<td align=center>
<input type="hidden" name="begin_id" value="70000">
<input type="hidden" name="end_id" value="80000">
<input type="submit" value="<?php print $lang_home['map_of_8']; ?>" style="background:#000000;border:none;">
</td>
</form>
</tr>
<tr>
<form method="post" action="map_to_image.php" target="_blank">
<td align=center colspan=2>
<input type="hidden" name="begin_id" value="80000">
<input type="hidden" name="end_id" value="90000">
<input type="submit" value="<?php print $lang_home['map_of_9']; ?>" style="background:#000000;border:none;">
</td>
</form>
</tr>
</table>

</td>
</tr>

<tr>
<td align=center colspan=3>
<hr>
<b><?php echo $lang_home["rela_sit"]; ?>:</b>
<p>
<a href="http://www.worldofphaos.com/" target="_blank">World of Phaos</a>
<p>
<a href="http://www.zekewalker.com/" target="_blank">Zeke Walker</a>
</td>
</tr>
</table>

</td>
</tr>
</table>
<?php include "footer.php"; ?>
