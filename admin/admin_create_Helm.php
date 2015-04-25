<?php 
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Create Helm</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if($addme=="yes")
{
	mysql_query("INSERT INTO phaos_helmets (name, armor_class, buy_price, sell_price, image_path) VALUES ('$name','$armor_class', '$buy_price', '$sell_price', '$image_path')");
	echo "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr>
	  <td align=center valign=middle height=\"100%\" width=\"100%\">
		  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<b>New Armor has been created!</b>
		  </td>
		  </tr>
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<br><form><input type='button' onClick=\"parent.location='admin_create_Helm.php'\" value='Create more Helmets'>
		  	<input type='button' onClick=\"parent.location='index.php'\" value='Back to Admin Panel'></form>
		  </td>
		  </tr>
		  </table>
		</td>
		</tr>
		</table>";
}
else
{
?>
<form action="admin_create_Helm.php?addme=yes" method=post>
<table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
  <tr style=background:#006600;> 
    <td colspan="2"> 
      <div align="center"><b>Create a Helmet</b></div>
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Name</font></b></td>
    <td width="50%"> 
      <input type="text" name="name">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Armor Class</font></b></td>
    <td width="50%"> 
      <input type="text" name="armor_class">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Buy Price</font></b></td>
    <td width="50%"> 
      <input type="text" name="buy_price">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Sell Price</font></b></td>
    <td width="50%"> 
      <input type="text" name="sell_price">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Image</font></b></td>
    <td width="50%"> 
<table border=0 cellpadding=0 cellspacing=0>
  <tr>
   <td align=center>
   <img src="../images/icons/helms/chain_helm.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/helms/chain_helm.gif">
   </td>
   <td align=center>
   <img src="../images/icons/helms/cloth_helm.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/helms/cloth_helm.gif">
   </td>
   <td align=center>
   <img src="../images/icons/helms/full_plate_helm.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/helms/full_plate_helm.gif">
   </td>
   <td align=center>
   <img src="../images/icons/helms/half_plate_helm.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/helms/half_plate_helm.gif">
   </td>
 </tr>
 <tr>
   <td align=center>
   <img src="../images/icons/helms/leather_helm.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/helms/leather_helm.gif">
   </td>
   <td align=center>
   <img src="../images/icons/helms/padded_helm.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/helms/padded_helm.gif">
   </td>
   <td align=center>
   <img src="../images/icons/helms/scale_helm.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/helms/scale_helm.gif">
   </td>
   <td align=center>
   <img src="../images/icons/helms/studded_leather_helm.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/helms/studded_leather_helm.gif">
   </td>
 </tr>
</table> 
    </td>
  </tr>
  <tr> 
    <td colspan="2"> 
      <div align="center"> 
        <input type="submit" name="Submit" value="Create">
        <input type='button' onClick="parent.location='index.php'" value='Back to Admin Panel'>
      </div>
    </td>
  </tr>
</table>
</form>
<?php
}
?>
</body>
</html>
