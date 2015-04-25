<?php 
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Add Specials</title>
<link rel="stylesheet" type="text/css" href="../styles/phaos.css">
</head>
<body>
<table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
  <tr style=background:#006600;> 
    <td colspan="2"> 
      <div align="center"><b>Check Specials</b></div>
    </td>
  </tr>
  <tr> 
    <td><font color="#FFFFFF">
	<?php
	for ($x=0; $x<=5; $x++){
	$query1="select * from phaos_locations";
	$randqry=mysql_query($query1);
	$rndmax=mysql_num_rows($randqry);
	$query3="select * from phaos_specials where type='random' order by rand() limit 1";
	$res=mysql_query($query3);
	$rand_id=rand(1,$rndmax);
	$specialid=mysql_fetch_array($res);
	echo "Selected special_ID:".$specialid["id"];
//
	$query2="update phaos_locations set special='".$specialid["id"]."' where id=$rand_id";
	mysql_query($query2);
if (!mysql_query($query2)){echo mysql_error();echo "an error has occured<br>";}
	else {echo " - Area $rand_id has been updated<br>";}
}
?>
	<br><br><center><form><input type='button' onClick="parent.location='admin_map_Check_Specials.php'" value='Return to overview menu'>
	<input type='button' onClick="parent.location='index.php'" value='Back to Admin Panel'></form></center>
	</font></td>
  </tr>
</table>
</body>
</html>
