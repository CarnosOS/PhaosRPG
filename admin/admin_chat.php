<?php
include "../config.php";
include "aup.php";
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../styles/phaos.css" >
</head>
<body leftmargin="0" topmargin="0">
<div align="center">
	<form action="admin_chat.php" method="post">
		<textarea cols="20" rows="3" name="usertext"></textarea>
		<br>
		<?php destination(); ?>
		<input type="submit" value="Post Message">
	</form>
</div>
<div align="left">
<?php
if (isset($_POST['usertext']) and $_POST['usertext'] != "")
	{
		write_shout_post();
	}
$result = mysql_query ('SELECT * FROM phaos_shout ORDER BY postdate DESC LIMIT 0, 10');

while ($row = mysql_fetch_array($result)) 
	{
		$color = '';
		if($row['destname'] == 'admin')
			{
				$color = "red";
			}
		if($row['postname'] == 'admin' and $row['destname'] != 'admin')
			{
				$color = "yellow";
			}
		if ($color == '')
			{
				$color = "white";
			}
		print '<hr><div align="left"><font color="'.$color.'">' . $row['postname'] . ',' . date('Y/m/d H:i', $row['postdate']) . '<br><br> '.$row['posttext'] .' <br></font>';
	}
?>
</div>
</body> 
</html>
<?php
### --- Functions --- ###
function write_shout_post()
	{
		global $PHP_ADMIN_USER;
		$text = strip_tags($_POST['usertext']);
##		$bb_replace =      array('[b]', '[B]', '[/b]', '[/B]', '[i]', '[I]', '[/i]', '[/I]', '[u]', '[U]', '[/u]', '[/U]');
##		$bb_replacements = array('<b>', '<b>', '</b>', '</b>', '<i>', '<i>', '</i>', '</i>', '<u>', '<u>', '</u>', '</u>');
##		$text = str_replace($bb_replace,$bb_replacements,$text);
		mysql_query ('INSERT INTO phaos_shout (location, postname, postdate, posttext, destname) 	VALUES (\'0\', \''.$PHP_ADMIN_USER . '\', \''.mktime().'\',\'' . $text . '\', \''.$_POST['destname'].'\')');	
		echo mysql_error();
	}
function destination()
	{
		print '<select name="destname">
				<option value="admin">To everybody</option>';
		$current_time = time();

		$active_min = $current_time-300;
		$active_max = $current_time+300;

		$result = mysql_query('SELECT * FROM phaos_characters WHERE regen_time >= \'' . $active_min . '\' AND regen_time <= \'' . $active_max . '\' ORDER by name ASC');
		if (mysql_num_rows($result) != 0)
		   {
			  while ($row = mysql_fetch_assoc($result))
				{
					print '<option value="'.$row['name'].'">Private to '.$row['name'].'</option>';
				}
		   }
		print '</select>';
	}
?>
