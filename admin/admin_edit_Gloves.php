<?php
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Edit Gloves</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
  <tr style=background:#006600;> 
    <td colspan="2"> 
      <div align="center"><b>Edit Gloves</b></div>
    </td>
  </tr>
  <tr>
  	<td colspan=2 align=center>
  			<form><input type='button' onClick="parent.location='index.php'" value='Back to Admin Panel'></form>
  	</td>
  </tr>
  <tr> 
    <td><table><tr><td>ID - Name</td><td>AC</td><td>&nbsp;&nbsp;</td><td>Buy Price</td><td>&nbsp;&nbsp;</td><td>Sell Price</td></tr>
	<?php
	$self=mysql_query("SELECT * FROM phaos_gloves");
	while ($row = mysql_fetch_array($self)) {
		$id = $row["id"];
		$desc = $row["name"];
        $ac = $row['armor_class'];
        $buy = $row['buy_price'];
        $sell = $row['sell_price'];
		
  echo "<tr><td><a href='change_gloves.php?gloves=$id'><b>$id - $desc</b></a></td><td>$ac</td><td>&nbsp;&nbsp;</td><td>".number_format($buy)."</td><td>&nbsp;&nbsp;</td><td>".number_format($sell)."</td></tr>";
	} 
	?>
	</table></td>
  </tr>
</table>
</body>
</html>
