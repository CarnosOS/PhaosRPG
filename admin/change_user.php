<?php
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Change User</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if($changeme=="yes") {
	if($password=="") {
		mysql_query("UPDATE phaos_users SET username='$username', email_address='$email_address', full_name='$full_name' WHERE id='$id'");
	}	else {
		mysql_query("UPDATE phaos_users SET username='$username', password=md5('$password'), email_address='$email_address', full_name='$full_name' WHERE id='$id'");
	}
	
	echo "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr>
	  <td align=center valign=middle height=\"100%\" width=\"100%\">
		  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<b>Users details for '$username' have been changed!</b>
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
} else {
	$self=mysql_query("SELECT * FROM phaos_users WHERE id=$user");
	while ($row = mysql_fetch_array($self)) {
	$id = $row["id"];
	$username = $row["username"];
	$email_address = $row["email_address"];
	$full_name = $row["full_name"];
}
?>
<form action="change_user.php?changeme=yes" method=post>
	<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align=center valign=middle height="100%" width="100%">
  <table width="600" border="1" cellspacing="0" cellpadding="3">
    <tr style=background:#006600;> 
      <td colspan="2"> 
        <div align="center"><b>Edit User <?php echo $username; ?></b></div>
      </td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">ID</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="id" value="<?php echo $id; ?>">
        </font></b></td></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">User Name</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="username" value="<?php echo $username; ?>">
        </font></b></td></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Password<br>(Edit to create a new password - will be MD5 encrypted)</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="password" value="<?php echo @$password; ?>">
        </font></b></td></td>
    </tr>
    <tr>
      <td width="50%"><b><font color="#FFFFFF">Email Address</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="email_address" value="<?php echo @$email_address; ?>">
        </font></b></td></td>
    </tr>
    <tr>
      <td width="50%"><b><font color="#FFFFFF">Full Name</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="full_name" value="<?php echo @$full_name; ?>">
        </font></b></td></td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <div align="center"> 
          <input type='submit' name="Submit" value="Change">
          <input type='button' onClick="parent.location='delete_user.php?username=<?php echo $username; ?>&userid=<?php echo $id; ?>'" value='Delete this user and there character'>
          <input type='button' onClick="parent.location='admin_users_Edit_Users.php'" value='Back to list'>
      	</div>
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
