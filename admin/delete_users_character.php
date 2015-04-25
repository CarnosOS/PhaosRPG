<?php
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Delete Users Character</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if($deleteme=="yes")
{
	mysql_query("DELETE FROM phaos_characters WHERE username='$username'");
	mysql_query("DELETE FROM phaos_char_inventory WHERE username='$username'");
	
	echo "<table width=\"100%\" hight=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\">
		<tr>
		<td align=center valign=middle  height=\"100%\" width=\"100%\">
		<table width=\"600\" border=\"1\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\">
	  <tr style=background:#006600;>
	  <td colspan=\"2\" align=center valign=middle height=\"100%\" width=\"100%\"> 
    	<div align=\"center\"><b>Deleted character for user $username</b></div>
    </td>
    </tr>
    <tr> 
    <td align=center valign=middle height=\"100%\" width=\"100%\">
			<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
			<tr>
			<td colspan=\"2\" align=center>
		  	<b>Users Character for user '$username' has been deleted!</b>
		  </td>
		  </tr>
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<br><form><input type='button' onClick=\"parent.location='admin_users_Edit_Users_Character.php'\" value='OK'></form>
		  </td>
		  </tr>
		  </table>
		 </td>
		 </tr>
		 </table>
		 </td>
		 </tr>
		 </table>";
}
else
{
	$self=mysql_query("SELECT * FROM phaos_characters WHERE id='$character'");
	while ($row = mysql_fetch_array($self)) {
		$id = $row["id"];
		$username = $row["username"];
		$name = $row["name"];
	}
?>
<form action="delete_users_character.php?deleteme=yes&username=<?php echo $username; ?>" method=post>
  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align=center valign=middle height="100%" width="100%">
  <table width="600" border="1" cellspacing="0" cellpadding="3">
    <tr style=background:#006600;> 
      <td colspan="2" align=center valign=middle height=\"100%\" width=\"100%\"> 
        <div align="center"><b>Delete character <?php echo $name; ?> for user <?php echo $username; ?></b></div>
      </td>
    </tr>
    <tr> 
      <td align=center valign=middle height=\"100%\" width=\"100%\">
			  <table border="0" cellspacing="1" cellpadding="0">
				  <tr>
					  <td colspan="2" align=center>
					  	<b>ARE YOU SURE YOU WANT TO DELETE CHARACTER<br>
					  	'<?php echo $name; ?>' FOR USER '<?php echo $username; ?>'?</b
					  </td>
					</tr>
					<tr>
					<td colspan="2" align=center>
						<input type="submit" name="submit" value="YES">
						<input type="button" onClick="parent.location='change_character.php?character=<?php echo $character; ?>'" value="NO">
					</td>
				  </tr>
			  </table>
		 	</td>
		</tr>
  </table>
  </td>
  </tr>
  </table>
</form>
<?php
}
?>
</body>
</html>
