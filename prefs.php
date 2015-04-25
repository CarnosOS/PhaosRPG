<?php
include "header.php";

if (@$_REQUEST['saved']) {
  setcookie('lang',$lang,time()+17280000); // ( REMEMBERS LANGUAGE FOR 200 DAYS )
//Added by dragzone---
  if($pw != "") {
  $pw=$HTTP_POST_VARS['pw'];
  $pw2=$HTTP_POST_VARS['pw2'];
  if ($pw != $pw2) { echo "<meta http-equiv=\"refresh\" content=\"3; URL=index.php\"><p>&nbsp;</p><p>&nbsp;</p><p align=\"center\"><b>Your password doesn't match!</b></p>"; exit;}
  $vpw=md5($pw);
  mysql_query("UPDATE phaos_users SET lang='$language', grid_size='$map_grid_size', grid_status='$map_grid_status', password='$vpw' WHERE username='$username'") or die("nope");
  } else {
  mysql_query("UPDATE phaos_users SET lang='$language', grid_size='$map_grid_size', grid_status='$map_grid_status' WHERE username='$username'") or die("nope"); }
//--------------------
}

if (@$_REQUEST['saved']) {
	?>
	<meta http-equiv="refresh" content="3; URL=logout.php">
	<table border="0" cellspacing="1" cellpadding="0" width="435" height="315">
	<tr>
		<td colspan="2" align=center>
			<table border="0" cellspacing="1" cellpadding="0">
		  <tr>
		  <td colspan="2" align=center>
		  	<b><?php echo $lang_added["ad_pref_pref-saved"]; ?></b>
		  </td>
		  </tr>
		  <tr>
		  <td colspan="2" align=center>
       <?php echo $lang_added["ad_pref_new-pref"]; ?>
		  </td>
		  </tr>
		  <tr>
		  <td colspan="2" align=center>
		  	<?php echo $lang_added["ad_pref_lang"]." ".$language ?>
		  </td>
		  </tr>
		  <tr>
		  <td colspan="2" align=center>
		  	<?php echo $lang_added["ad_pref_map-g-size"]." ".$map_grid_size; ?>
		  </td>
		  </tr>
		  <tr>
		  <td colspan="2" align=center>
		  	<?php echo $lang_added["ad_pref_map-g-size"]." ".$map_grid_status?"On":"Off"; ?>
		  </td>
		  </tr>
    <tr>
		  <td colspan="2" align=center>
        <br><b><?php echo $lang_added["ad_pref_auto-logout"]; ?></b>
		  </td>
		  </tr>
		  </table>
		</td>
	</tr>
	</table>
<?php
} else {
	$result=mysql_query("SELECT * FROM phaos_users WHERE username='$username'");
	while ($row = mysql_fetch_array($result)) {
		// $id = $row['id'];
		$language = $row['lang'];
		$map_grid_size = $row['grid_size'];
		$map_grid_status = $row['grid_status'];
	}
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?saved=yes&username=<?php echo $username ?>">
	<table cellspacing=0 cellpadding=0 border=1>
	<tr><td colspan=2 align=center><font color=#FFFFFF><strong><u><?php echo $lang_added["ad_pref_panel"]; ?></u></strong></font></td></tr>
	<tr>
		<td align=right><font color=#FFFFFF><strong><?php echo $lang_added["ad_pref_username"]; ?> &nbsp</strong></font></td>
		<td><?php echo $username ?></td>
	</tr>
	<tr>
		<td align=right><font color=#FFFFFF><strong><?php echo $lang_added["ad_pref_new-pw"]; ?> &nbsp</strong></font></td>
		<td><input type="password" name="pw" size="10"></td>
	</tr>
	<tr>
		<td align=right><font color=#FFFFFF><strong><?php echo $lang_added["ad_pref_conf-pw"]; ?> &nbsp</strong></font></td>
		<td><input type="password" name="pw2" size="10"></td>
	</tr>
	<tr>
	<td align=right><font color=#FFFFFF><strong><?php echo $lang_added["ad_pref_lang"]; ?> &nbsp</strong></font></td>
	<td>
    <?php
    //FIXME: get this array automatically somehow
    $languages= array(
            array('lang'=>'en','language'=>'English'),
            array('lang'=>'fr','language'=>'Francais'),
            array('lang'=>'de','language'=>'Deutsch'),
            array('lang'=>'tr','language'=>'Turkish'),
            array('lang'=>'bg','language'=>'Bulgarian')
            );

    htmlSelect('language',$languages,$language,'lang','language');
    ?>
	</td>
	</tr>
	<tr>
	<td align=right><font color=#FFFFFF><strong><?php echo $lang_added["ad_pref_map-g-size"]; ?> &nbsp</strong></font></td>
	<td>
		<select name="map_grid_size">
		<?php
		If ($map_grid_size == 52) {
			print ("<option value='52' selected >52</option>");
		} else {
			print ("<option value='52'>52</option>");
		}
		if ($map_grid_size == 78) {
			print ("<option value='78' selected >78</option>");
		} else {
			print ("<option value='78'>78</option>");
		}
		if ($map_grid_size == 104) {
			print ("<option value='104' selected >104</option>");
		} else {
			print ("<option value='104'>104</option>");
		}
		?>
	</td>
	</tr>
	<tr>
	<td align=right><font color=#FFFFFF><strong><?php echo $lang_added["ad_pref_map-g-stat"]; ?> &nbsp</strong></font></td>
	<td>
		<?php
		if ($map_grid_status == "1") {
			print ("<input checked type='radio' name='map_grid_status' value='1'>Active");
		} else {
			print ("<input type='radio' name='map_grid_status' value='1'>Active");
		}
		if ($map_grid_status == "0") {
			print ("<input checked type='radio' name='map_grid_status' value='0'>Nonactive");
		} else {
			print ("<input type='radio' name='map_grid_status' value='0'>Nonactive");
		}
		?>
	</td>
	</tr>
	</table>
	<br>
	<input type="submit" value="<?php echo $lang_added["ad_pref_safe"]; ?>">
	</form>
<?php
}
include "footer.php";
?>
