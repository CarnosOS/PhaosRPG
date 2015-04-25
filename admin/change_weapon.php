<?php
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Change Weapon</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if($changeme=="yes")
{
	mysql_query("UPDATE phaos_weapons SET name='$name', min_damage='$min_damage', max_damage='$max_damage', buy_price='$buy_price', sell_price='$sell_price', image_path='$image_path' WHERE id='$id'");
	echo "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr>
	  <td align=center valign=middle height=\"100%\" width=\"100%\">
		  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<b>Weapon has been changed!</b>
		  </td>
		  </tr>
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<br><form><input type='button' onClick=\"parent.location='admin_edit_Weapons.php'\" value='OK'></form>
		  </td>
		  </tr>
		  </table>
		</td>
		</tr>
		</table>";
}
else
{
	$self=mysql_query("SELECT * FROM phaos_weapons WHERE id=$weapon");
	while ($row = mysql_fetch_array($self)) {
	$id = $row["id"];
	$name = $row["name"];
	$min_damage = $row["min_damage"];
	$max_damage = $row["max_damage"];
	$buy_price = $row["buy_price"];
	$sell_price = $row["sell_price"];
	$image_path = $row["image_path"];
	}
?>
<form action="change_weapon.php?changeme=yes" method=post>
<input type="hidden" name="id" value="<?php echo $id; ?>">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align=center valign=middle height="100%" width="100%">
  <table width="600" border="1" cellspacing="0" cellpadding="3">
  <tr style=background:#006600;>
    <td colspan="2">
      <div align="center"><b>Edit Weapon</b></div>
    </td>
  </tr>
  <tr>
    <td width="50%"><b><font color="#FFFFFF">Name</font></b></td>
    <td width="50%">
      <input type="text" name="name" value="<?php echo $name; ?>">
    </td>
  </tr>
  <tr>
    <td width="50%"><b><font color="#FFFFFF">Min Damage</font></b></td>
    <td width="50%">
      <input type="text" name="min_damage" value="<?php echo $min_damage; ?>">
    </td>
  </tr>
  <tr>
  <tr>
    <td width="50%"><b><font color="#FFFFFF">Max Damage</font></b></td>
    <td width="50%">
      <input type="text" name="max_damage" value="<?php echo $max_damage; ?>">
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
    <td width="50%"><b><font color="#FFFFFF">Picture</font></b></td>
    <td width="50%">
    Active Picture &nbsp;&nbsp;&nbsp;<img src="../<?php echo $image_path; ?>"><br>   

<br>
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

  <tr> 
    <td colspan="2"> 
      <div align="center"> 
        <input type="submit" name="Submit" value="Change">
        <input type='button' onClick="parent.location='admin_edit_Weapons.php'" value='Back to list'>
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
