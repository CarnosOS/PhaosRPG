<?php
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Change Boots</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if(@$_REQUEST['changeme'])
{
	mysql_query("UPDATE phaos_boots SET name='$name', armor_class='$armor_class', buy_price='$buy_price', sell_price='$sell_price', image_path='$image_path' WHERE id='$id'");
	echo "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr>
	  <td align=center valign=middle height=\"100%\" width=\"100%\">
		  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<b>Boots have been changed!</b>
		  </td>
		  </tr>
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<form><input type='button' onClick=\"parent.location='admin_edit_Boots.php'\" value='OK'></form>
		  </td>
		  </tr>
		  </table>
		</td>
		</tr>
		</table>";
}
else
{
	$self=mysql_query("SELECT * FROM phaos_boots WHERE id=$boots");
	while ($row = mysql_fetch_array($self)) {
	$id = $row["id"];
	$name = $row["name"];
	$armor_class = $row["armor_class"];
	$buy_price = $row["buy_price"];
	$sell_price = $row["sell_price"];
	$image_path = $row["image_path"];
	}
?>
<form action="change_boots.php?changeme=yes" method=post>
<input type="hidden" name="id" value="<?php echo $id; ?>">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align=center valign=middle height="100%" width="100%">
  <table width="600" border="1" cellspacing="0" cellpadding="3">
  <tr style=background:#006600;>
    <td colspan="2">
      <div align="center"><b>Edit Boots</b></div>
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Name</font></b></td>
    <td width="50%"> 
      <input type="text" name="name" value="<?php echo $name; ?>">
    </td>
  </tr>
  <tr>
    <td width="50%"><b><font color="#FFFFFF">Armor Class</font></b></td>
    <td width="50%"> 
      <input type="text" name="armor_class" value="<?php echo $armor_class; ?>">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Buy Price</font></b></td>
    <td width="50%"> 
      <input type="text" name="buy_price" value="<?php echo $buy_price; ?>">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Sell Price</font></b></td>
    <td width="50%"> 
      <input type="text" name="sell_price" value="<?php echo $sell_price; ?>">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Image</font></b></td>
    <td width="50%"> Active Picture &nbsp;&nbsp;&nbsp;<img src="../<?php echo $image_path; ?>"><br><br>
    
<table border=0 cellpadding=0 cellspacing=0>
  <tr>
   <td align=center>
   <img src="../images/icons/boots/chain_boots.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/boots/chain_boots.gif">
   </td>
   <td align=center>
   <img src="../images/icons/boots/cloth_boots.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/boots/cloth_boots.gif">
   </td>
   <td align=center>
   <img src="../images/icons/boots/full_plate_boots.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/boots/full_plate_boots.gif">
   </td>
   <td align=center>
   <img src="../images/icons/boots/half_plate_boots.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/boots/half_plate_boots.gif">
   </td>
 </tr>
 <tr>
   <td align=center>
   <img src="../images/icons/boots/leather_boots.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/boots/leather_boots.gif">
   </td>
   <td align=center>
   <img src="../images/icons/boots/padded_boots.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/boots/padded_boots.gif">
   </td>
   <td align=center>
   <img src="../images/icons/boots/scale_boots.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/boots/scale_boots.gif">
   </td>
   <td align=center>
   <img src="../images/icons/boots/studded_leather_boots.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/boots/studded_leather_boots.gif">
   </td>
 </tr>
</table> 
    </td>
  </tr>
  <tr> 
    <td colspan="2"> 
      <div align="center"> 
        <input type="submit" name="Submit" value="Change">
        <input type='button' onClick="parent.location='admin_edit_Boots.php'" value='Back to list'>
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
