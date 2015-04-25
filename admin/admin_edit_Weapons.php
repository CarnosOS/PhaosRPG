<?php
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Edit Weapons</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
  <tr style=background:#006600;> 
    <td colspan="2"> 
      <div align="center"><b>Edit Weapons</b></div>
    </td>
  </tr>
  <tr>
  	<td colspan=2 align=center>
  			<form><input type='button' onClick="parent.location='index.php'" value='Back to Admin Panel'></form>
  	</td>
  </tr>
  <tr> 
    <td><table><tr><td>ID - Name</td><td>Min Dam</td><td>&nbsp;&nbsp;</td><td>Max Dam</td><td>&nbsp;&nbsp;</td><td>Buy Price</td><td>&nbsp;&nbsp;</td><td>Sell Price</td><td>&nbsp;&nbsp;</td></tr>
	<?php
	$self=mysql_query("SELECT * FROM phaos_weapons");
    while ($row = mysql_fetch_array($self)) {
        $id = $row["id"];
        $name = $row["name"];
        $mindamage = $row['min_damage'];
        $maxdamage = $row['max_damage'];
        $buy = $row['buy_price'];
        $sell = $row['sell_price'];
        echo "<tr><td><a href='change_weapon.php?weapon=$id'><b>$id - $name</b></a><td>$mindamage</td><td>&nbsp;&nbsp;</td><td>$maxdamage</td><td>&nbsp;&nbsp;</td></td><td>".number_format($buy)."</td><td>&nbsp;&nbsp;</td><td>".number_format($sell)."</td><td>&nbsp;&nbsp;</td></tr>";
    }
	?>
	</table></td>
  </tr>
</table>
</body>
</html>
