<?php 
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Create Weapon</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if($addme=="yes")
{
	mysql_query("INSERT INTO phaos_weapons (name, min_damage, max_damage, buy_price, sell_price, image_path) VALUES ('$name','$min_damage', '$max_damage', '$buy_price', '$sell_price', '$image_path')");
	echo "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr style=background:#006600;>
	  <td align=center valign=middle height=\"100%\" width=\"100%\">
		  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<b>New Weapon has been created!</b>
		  </td>
		  </tr>
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<br><form><input type='button' onClick=\"parent.location='admin_create_Weapon.php'\" value='Create more Weapons'>
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
<form action="admin_create_Weapon.php?addme=yes" method=post>
<table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
  <tr style=background:#006600;> 
    <td colspan="2"> 
      <div align="center"><b>Add Weapon</b></div>
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Name</font></b></td>
    <td width="50%"> 
      <input type="text" name="name">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">min damage</font></b></td>
    <td width="50%"> 
      <input type="text" name="min_damage">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">max damage</font></b></td>
    <td width="50%"> 
      <input type="text" name="max_damage">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">buy price</font></b></td>
    <td width="50%"> 
      <input type="text" name="buy_price">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">sell price</font></b></td>
    <td width="50%"> 
      <input type="text" name="sell_price">
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Picture</font></b></td>
    <td width="50%"> 
<table border=0 cellpadding=0 cellspacing=0>
  <tr>
   <td align=center>
   <img src="../images/icons/weapons/bastardsword.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/bastardsword.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/broadsword.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/broadsword.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/claymore.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/claymore.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/greatsword.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/greatsword.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/longsword.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/longsword.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/shortsword.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/shortsword.gif">
   </td>
 </tr>
 <tr>
   <td align=center>
   <img src="../images/icons/weapons/scimitar.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/scimitar.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/battleaxe.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/battleaxe.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/greataxe.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/greataxe.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/halberd.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/halberd.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/handaxe.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/handaxe.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/warhammer.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/warhammer.gif">
   </td>
 </tr>
 <tr>
   <td align=center>
   <img src="../images/icons/weapons/club.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/club.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/morningstar.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/morningstar.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/spear.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/spear.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/staff.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/staff.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/lightmace.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/lightmace.gif">
   </td>
   <td align=center>
   <img src="../images/icons/weapons/dagger.gif">
   <input CHECKED type="radio" name="image_path" value="images/icons/weapons/dagger.gif">
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
