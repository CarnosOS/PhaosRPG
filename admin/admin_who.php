<html>
<head>
<?php
include "../config.php";
?>
<title>WoP Admin Panel - Whos Online</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>

<tr>
  <td align=center> <div align="center" bgcolor="#000000">
	<img src="../lang/<?php echo $lang ?>_images/whos_online.png">
</td> 
<div align="center"><small>This page may take a while to load, please wait.</small> </div>
    <p align="center"> 
      <?php
$current_time = time();

$active_min = $current_time-300;
$active_max = $current_time+300;

$active_check = mysql_query ("SELECT * FROM phaos_characters WHERE regen_time >= '$active_min' AND regen_time <= '$active_max' AND username != 'phaos_npc' AND username != 'phaos_npc_arena' ORDER by name ASC");

if ($row = mysql_fetch_array($active_check)) {$active = "yes";}
$player_name = $row[username];

if($active == "yes") {
print ("<font color=#009900>|</font><a href=\"../player_info.php?player_name=$player_name\" target=\"_blank\">$char_name</a>");
}

$active = "";
} while ($row = mysql_fetch_array($result));
}
?>
<font color=#009900>|</font>
</td>
</tr>
</table>

</td>
</tr>
</table>
<center><b><a href=index.php>[Back]</a></b></center>
</body>
</html>
