<html>
<head>
<?php
include "aup.php";
?>
<title>WoP Admin Panel - Whos Online</title>
<link rel="stylesheet" type="text/css" href="../styles/phaos.css">
</head>
<body>
<table border="0" align="center">
<tr>
	<td align="center" bgcolor="#000000">
		<img src="../lang/en_images/whos_online.png"><br>
		<small>This page takes a while to load.</small>
	</td>
</tr>
<tr>   
<td align="center">
	<?php
	$current_time = time();
	
	$active_min = $current_time-300;
	$active_max = $current_time+300;
	
	$result = mysql_query("SELECT * FROM phaos_characters WHERE regen_time >= '$active_min' AND regen_time <= '$active_max' AND username != 'phaos_npc' AND username != 'phaos_npc_arena' ORDER by name ASC");
	
	$html='';
	if (mysql_num_rows($result) != 0) {
		while ($row = mysql_fetch_assoc($result)) {
			echo  '<font color="#009900">|</font><a href="player_info.php?player_name='. $row['username'] . '" target="_blank">' . $row['name'] .  '</a>';
		}
	} else {
		$html = "<font color=#009900>|No one is currently Online!</font>";
	}
	echo '<font color="#009900">|</font>';
	?>
</td>
</tr>
</table>
<p>
<center><form><input type='button' onClick="parent.location='index.php'" value='Back to Admin Panel'></form></center>
</body>
</html>
