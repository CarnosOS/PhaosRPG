<?php 
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Create New Character</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
  <tr style=background:#006600;> 
    <td colspan="2"> 
      <div align="center"><b>Create a New User Character</b></div>
    </td>
  </tr>
  <tr>
  	<td colspan="2">
  		<div align="center"><form><input type='button' onClick="parent.location='admin_users_Edit_Users.php'" value='Edit/Delete Users'><input type='button' onClick="parent.location='admin_users_Create_New_Character.php'" value='Create New Character'><input type='button' onClick="parent.location='admin_users_Edit_Users_Character.php'" value='Edit/Delete Character'><input type='button' onClick="parent.location='index.php'" value='Back to Admin Panel'></form></div>
  	</td>
  </tr>
  <tr> 
    <td><font color="#FFFFFF"><center>Click on a user to create a new character for them:</center><br>
		<?php
		$self=mysql_query("SELECT * FROM phaos_users ORDER BY username ASC");
		while ($row = mysql_fetch_array($self)) {
			$id = $row["id"];
			$username = $row["username"];
			
			echo "<a href='create_character.php?username=$username'><b>$username</b></a><br>";
		} 
		?>
		</font></td>
  </tr>
</table>
</body>
</html>
