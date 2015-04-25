<?php
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Delete User</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if($deleteme=="yes")
{
	mysql_query("DELETE FROM phaos_char_inventory WHERE username='$username'");
	mysql_query("DELETE FROM phaos_characters WHERE username='$username'");
	mysql_query("DELETE FROM phaos_users WHERE username='$username'");
	echo "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr>
	  <td align=center valign=middle height=\"100%\" width=\"100%\">
		  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<b>User '$username' and all there Character details have been deleted!</b>
		  </td>
		  </tr>
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<form><input type='button' onClick=\"parent.location='admin_users_Edit_Users.php'\" value='OK'></form>
		  </td>
		  </tr>
		  </table>
		 </td>
		 </tr>
		 </table>";
}
else
{
	$self=mysql_query("SELECT * FROM phaos_characters WHERE username='$username'");
	while ($row = mysql_fetch_array($self))
	{
	$username = $row["username"];
	$name = $row["name"];
	}
?>
<form action="delete_user.php?deleteme=yes&username=<?php echo $username; ?>" method=post>
  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align=center valign=middle height="100%" width="100%">
  <table width="600" border="1" cellspacing="0" cellpadding="3">
    <tr style=background:#006600;> 
      <td colspan="2" align=center valign=middle height=\"100%\" width=\"100%\"> 
        <div align="center"><b>Delete user '<?php echo $username; ?>' and there character '<?php echo $username; ?>'</b></div>
      </td>
    </tr>
    <tr> 
      <td align=center valign=middle height=\"100%\" width=\"100%\">
			  <table border="0" cellspacing="1" cellpadding="0">
				  <tr>
					  <td colspan="2" align=center>
					  	<b>ARE YOU SURE YOU WANT TO DELETE USER '<?php echo $username; ?>'<br>
					  	AND ALL CHARACTER DETAILS FOR	'<?php echo $name; ?>'?</b
					  </td>
					</tr>
					<tr>
					<td colspan="2" align=center>
						<input type="submit" name="submit" value="YES">
						<input type="button" onClick="parent.location='change_user.php?user=<?php echo $userid; ?>'" value="NO">
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
