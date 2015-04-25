<?php 
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Check Specials</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
  <tr> 
    <td colspan="2"> 
      <div align="center"><b>Check Specials</b></div>
    </td>
  </tr>
  <tr> 
    <td><font color="#FFFFFF">
	<?php
	$self=mysql_query("SELECT * FROM phaos_locations where not special=0");
echo "The following have specials on them:<br>[id] - [Name] - [Special]<br>";
$count=mysql_num_rows($self);
while ($row = mysql_fetch_array($self)) {
$id = $row["id"];
$desc = $row["name"];
$special=$row["special"];

echo "<b>$id - $desc - $special</b><br>";
} 
echo "<br>There are a total of $count specials on the surface map.<br>";
echo "<br><br><center><a href=\"add_specials.php\">[Add five specials]</a>  -  ";
	?><a href="index.php">[Back]</a></center>
	</font></td>
  </tr>
</table>
</body>
</html>
