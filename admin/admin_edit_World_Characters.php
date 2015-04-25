<?php
include("../config.php");
include("aup.php");
if($sort == "") {$sort = "name";}
?>
<html>
<head>
<title>WoP Admin Panel - Edit World Characters</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
  <tr style=background:#006600;> 
    <td colspan="2"> 
      <div align="center"><b>Edit World Characters</b></div>
    </td>
  </tr>
  <tr>
  	<td colspan=2 align=center>
  			<form><input type='button' onClick="parent.location='index.php'" value='Back to Admin Panel'></form>
  	</td>
  </tr>
  <tr> 
<td><font color="#FFFFFF"><table><tr><td><a href='<?php print $_SERVER[PHP_SELF]; ?>?sort=id'>ID</a></td><td><a href='<?php print $_SERVER[PHP_SELF]; ?>?sort=name'>Name:</a></td><td><a href='<?php print $_SERVER[PHP_SELF]; ?>?sort=race'>Race</a></td><td><a href='<?php print $_SERVER[PHP_SELF]; ?>?sort=hit_points'>HP</a></td><td><a href='<?php print $_SERVER[PHP_SELF]; ?>?sort=level'>Level</a></td><td><a href='<?php print $_SERVER[PHP_SELF]; ?>?sort=armor'>Armor</a></td><td><a href='<?php print $_SERVER[PHP_SELF]; ?>?sort=xp'>Exp</a></td><td><a href='<?php print $_SERVER[PHP_SELF]; ?>?sort=gold'>Gold</a></td><td><a href='<?php print $_SERVER[PHP_SELF]; ?>?sort=image_path'>Image Path</a></td><td><a href='<?php print $_SERVER[PHP_SELF]; ?>?sort=location'>Location</a></td></tr>
	<?php
	$self=mysql_query("SELECT * FROM phaos_characters ORDER BY $sort ASC");
	while ($row = mysql_fetch_array($self)) {
		echo "<tr><td>".$row['id']."</td><td><a href='change_wrldchr.php?wrldchr={$row['id']}'>".$row['name']."</a></td><td>".$row['race']."</td><td>".$row['hit_points']."</td><td>".$row['level']."</td><td>".$row['armor']."</td><td>".$row['xp']."</td><td>".$row['gold']."</td><td>".$row['image_path']."</td><td>".$row['location']."</td></tr>";
	}
	?>
	</font></td>
  </tr>
</table>
</body>
</html>
