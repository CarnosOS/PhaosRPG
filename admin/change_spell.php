<?php
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Change Spell</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if($changeme=="yes")
{
	IF ($damage_mess) $damage_mess = 1;
	mysql_query("UPDATE phaos_spells_items SET name='$name', min_damage='$min_damage', max_damage='$max_damage', buy_price='$buy_price', sell_price='$sell_price', image_path='$image_path', req_skill='$req_skill', damage_mess='$damage_mess' WHERE id='$id'");
	echo "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr>
	  <td align=center valign=middle height=\"100%\" width=\"100%\">
		  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<b>Spell has been changed!</b>
		  </td>
		  </tr>
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<form><input type='button' onClick=\"parent.location='admin_edit_Spells.php'\" value='OK'></form>
		  </td>
		  </tr>
		  </table>
		</td>
		</tr>
		</table>";
}
else
{
	$self=mysql_query("SELECT * FROM phaos_spells_items WHERE id=$spell");
	while ($row = mysql_fetch_array($self)) {
	$id = $row["id"];
	$name = $row["name"];
	$min_damage = $row["min_damage"];
	$max_damage = $row["max_damage"];
	$buy_price = $row["buy_price"];
	$sell_price = $row["sell_price"];
	$image_path = $row["image_path"];
	$req_skill = $row["req_skill"];
	$damage_mess = $row["damage_mess"];
	}
?>
<form action="change_spell.php?changeme=yes" method=post>
<input type="hidden" name="id" value="<?php echo $id; ?>">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align=center valign=middle height="100%" width="100%">
  <table width="600" border="1" cellspacing="0" cellpadding="3">
  <tr style=background:#006600;>
    <td colspan="2">
      <div align="center"><b>Edit Spell</b></div>
    </td>
  </tr>
  <tr>
    <td width="50%"><b><font color="#FFFFFF">Name</font></b></td>
    <td width="50%"> 
      <input type="text" name="name" value="<?php echo $name; ?>">
    </td>
  </tr>
  <tr>
    <td width="50%"><b><font color="#FFFFFF">Minimum Damage</font></b></td>
    <td width="50%"> 
      <input type="text" name="min_damage" value="<?php echo $min_damage; ?>">
    </td>
  </tr>
  <tr>
    <td width="50%"><b><font color="#FFFFFF">Maximum Damage</font></b></td>
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
    <td width="50%"><b><font color="#FFFFFF">Image</font></b></td>
    <td width="50%"> Active Picture &nbsp;&nbsp;&nbsp;<img src="../<?php echo $image_path; ?>"><br><br>

<table border=0 cellpadding=0 cellspacing=0>
  <tr>
   <td align=center>
   <img src="../images/icons/spells/conflageration.gif">
   <input <?php if ($image_path == "images/icons/spells/conflageration.gif") print "CHECKED"?> type="radio" name="image_path" value="images/icons/spells/conflageration.gif">
   </td>
   <td align=center>
   <img src="../images/icons/spells/energy_blast.gif">
   <input <?php if ($image_path == "images/icons/spells/energy_blast.gif") print "CHECKED"?> type="radio" name="image_path" value="images/icons/spells/energy_blast.gif">
   </td>
   <td align=center>
   <img src="../images/icons/spells/fireball.gif">
   <input <?php if ($image_path == "images/icons/spells/fireball.gif") print "CHECKED"?> type="radio" name="image_path" value="images/icons/spells/fireball.gif">
   </td>
   <td align=center>
   <img src="../images/icons/spells/lightning_bolt.gif">
   <input <?php if ($image_path == "images/icons/spells/lightning_bolt.gif") print "CHECKED"?> type="radio" name="image_path" value="images/icons/spells/lightning_bolt.gif">
   </td>
 </tr>
 <tr>
   <td align=center>
   <img src="../images/icons/spells/magic_missle.gif">
   <input <?php if ($image_path == "images/icons/spells/magic_missle.gif") print "CHECKED"?> type="radio" name="image_path" value="images/icons/spells/magic_missle.gif">
   </td>
   <td align=center>
   <img src="../images/icons/spells/meteor.gif">
   <input <?php if ($image_path == "images/icons/spells/meteor.gif") print "CHECKED"?> type="radio" name="image_path" value="images/icons/spells/meteor.gif">
   </td>
   <td align=center>
   <img src="../images/icons/spells/shocking_touch.gif">
   <input <?php if ($image_path == "images/icons/spells/shocking_touch.gif") print "CHECKED"?> type="radio" name="image_path" value="images/icons/spells/shocking_touch.gif">
   </td>
   <td align=center>
   </td>
 </tr>
</table> 
    </td>
  </tr>
  <tr> 
    <td width="50%"><b><font color="#FFFFFF">Required Skill</font></b></td>
    <td width="50%"> 
      <input type="text" name="req_skill" value="<?php echo $req_skill; ?>">
    </td>
  </tr>
   <tr> 
    <td width="50%"><b><font color="#FFFFFF">Mass Damage</font></b></td>
    <td width="50%">
      <input <?php if ($damage_mess == 1) print "CHECKED"?> type="checkbox" name="damage_mess">
    </td>
  </tr>
  <tr> 
    <td colspan="2"> 
      <div align="center"> 
        <input type="submit" name="Submit" value="Change">
        <input type='button' onClick="parent.location='admin_edit_Spells.php'" value='Back to list'>
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
