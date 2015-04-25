<?php 
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Edit Opponents</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
  <tr style=background:#006600;> 
    <td colspan="2"> 
      <div align="center"><b>Edit Opponents</b></div>
    </td>
  </tr>
  <tr>
  	<td colspan=2 align=center>
  			<form><input type='button' onClick="parent.location='index.php'" value='Back to Admin Panel'></form>
  	</td>
  </tr>
  <tr> 
	<td><font color="#FFFFFF"><table><tr><td>ID</td><td>Name:</td><td>HP</td><td>Race</td><td>MinD</td><td>MaxD</td><td>AC</td><td>Exp</td><td>Gold</td></tr>
	<?php
	$self=mysql_query("SELECT * FROM phaos_opponents ORDER BY id ASC");
	while ($row = mysql_fetch_array($self)) {
		echo "<tr><td>".$row['id']."</td><td><a href='change_opp.php?opp={$row['id']}'>".$row['name']."</a></td><td>".$row['hit_points']."</td><td>".$row['race']."</td><td>".$row['min_damage']."</td><td>".$row['max_damage']."</td><td>".$row['AC']."</td><td>".$row['xp_given']."</td><td>".$row['gold_given']."</td></tr>";
	}
	?>
	</font></td>
  </tr>
</table>
</body>
</html>
