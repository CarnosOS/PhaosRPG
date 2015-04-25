<?php
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Change Admin Details</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if(@$_REQUEST['changeme'])
{
    if($admin_pass===$admin_pass_2){
        $PHP_ADMIN_MD5PW= md5($admin_pass);
    	$result= mysql_query("UPDATE phaos_admin SET admin_user='$admin_user', admin_pass='$PHP_ADMIN_MD5PW' WHERE admin_user = '$PHP_ADMIN_USER'");
        if($result){
            setcookie("PHP_ADMIN_USER",$admin_user,time()+1728000); // ( REMEMBERS USER NAME FOR 200 DAYS )
            setcookie("PHP_ADMIN_MD5PW",$PHP_ADMIN_MD5PW,time()+172800); // ( REMEMBERS USER PASSWORD FOR 60 DAYS )
        }

    	echo "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
    	  <tr>
    	  <td align=center valign=middle height=\"100%\" width=\"100%\">
    		  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
    		  <tr>
    		  <td colspan=\"2\" align=center>
    		  	<b>Admin account details have been changed!</b>
    		  </td>
    		  </tr>
    		  <tr>
    		  <td colspan=\"2\" align=center>
    		  	<input type='button' onClick=\"parent.location='index.php'\" value='Back to Admin Panel'>
    		  </td>
    		  </tr>
    		  </table>
    		</td>
    		</tr>
    		</table>";
            exit;
    }else{
        ?><p align="center"><b>Password does not match repetition, please try again!</b></p><?php
    }

    setcookie("PHP_ADMIN_PW","",time()-3600); // remove cookie used in version 0.88
}

	$self=mysql_query("SELECT * FROM phaos_admin WHERE id=1");
	while ($row = mysql_fetch_array($self)) {
	$id = $row["id"];
	$admin_user = $row["admin_user"];
	}
?>
<form action="admin_options_Change_Admin_Details.php?changeme=yes" method=post>
<table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
  <tr style=background:#006600;>
    <td colspan="2">
      <div align="center"><b>Change Admin Account Details</b></div>
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Admin User</font></b></td>
    <td width="50%"> 
      <input type="text" name="admin_user" value="<?php echo $admin_user; ?>">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Change admin password to:</font></b></td>
    <td width="50%">
      <input type="password" name="admin_pass" value="">
    </td>
  </tr>
  <tr>
    <td width="50%"><b><font color="#FFFFFF">Repeat new password:</font></b></td>
    <td width="50%">
      <input type="password" name="admin_pass_2" value="">
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <div align="center">
        <input type="submit" name="Submit" value="Change">
        <input type='button' onClick="parent.location='index.php'" value='Back to Admin Panel'>
      </div>
    </td>
  </tr>
</table>
</form>

</body>
</html>
