<?php
include_once "../config.php";

$admin_auth = false; // Assume admin is not authenticated

$row = null;
if ( (@$PHP_ADMIN_USER) && ((@$PHP_ADMIN_PW)||(@$PHP_ADMIN_MD5PW) )) {

    if(@$PHP_ADMIN_MD5PW){
       $query = "SELECT * FROM phaos_admin WHERE admin_user = '$PHP_ADMIN_USER' AND admin_pass = '$PHP_ADMIN_MD5PW'";
	   $result = mysql_query($query);
	   $row = mysql_fetch_array($result);
    }

    if(!@$row){
       $PHP_ADMIN_MD5PW = md5(@$PHP_ADMIN_PW);
       $query = "SELECT * FROM phaos_admin WHERE admin_user = '$PHP_ADMIN_USER' AND admin_pass = '$PHP_ADMIN_MD5PW'";
	   $result = mysql_query($query);
	   $row = mysql_fetch_array($result);
    }

    if ( $row ) {
        // A matching row was found - the admin is authenticated.
        $admin_auth = true;

        setcookie("PHP_ADMIN_USER",$PHP_ADMIN_USER,time()+1728000); // ( REMEMBERS USER NAME FOR 20 DAYS )
        setcookie("PHP_ADMIN_MD5PW",$PHP_ADMIN_MD5PW,time()+172800); // ( REMEMBERS USER PASSWORD FOR 2 DAYS )
    }

    setcookie("PHP_ADMIN_PW","",time()-3600); // remove old cookie
}


if ( !$admin_auth ) {
echo "<html>
<head>
<title>WoP Administration Panel</title>
<link rel=stylesheet type=text/css href=../styles/phaos.css>
</head>
<body bottommargin=\"0\" rightmargin=\"0\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">
<div align=center>
<br>
<br>
<form method=\"post\" action=\"".basename($_SERVER['PHP_SELF'])."\">
<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" width=\"435\" height=\"315\" background=\"../images/scroll.png\">
<tr>
<td colspan=\"2\" align=center>
<img src=\"../images/login_logo.gif\">
<p>
<table cellspacing=0 cellpadding=0 border=0>
<tr><td colspan=2 align=center><font color=#000000><strong>ADMIN LOGIN</strong></font></td></tr>
<tr>
<td align=right><font color=#000000><strong>Admin Name: &nbsp</strong></font></td>
<td><input name=\"PHP_ADMIN_USER\" type=\"text\"></td>
</tr>
<tr>
<td align=right><font color=#000000><strong>Password: &nbsp</strong></font></td>
<td><input name=\"PHP_ADMIN_PW\" type=\"password\"></td>
</tr>
</table>
<br>
<input type=\"submit\" value=\"Login\">
</td>
</tr>
</table>
</form>
<p>
<small>
Copyright 2004-".date("Y",time())."
<br><a href=\"http://worldofphaos.zekewalker.com/\" target=\"_blank\">Click Here for License Agreement details.</a>
</body>
</html>";
exit;
}
?>
