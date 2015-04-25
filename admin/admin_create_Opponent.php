<?php 
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Create Opponent</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if($addme=="yes")
{
	mysql_query("INSERT INTO phaos_opponents (name, hit_points, race, class, min_damage, max_damage, AC, xp_given, gold_given, image_path, location) VALUES ('$name', '$hit_points', '$race', '$class', '$min_damage', '$max_damage', '$AC', '$xp_given', '$gold_given', '$image_path', '$location')");
	echo "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr style=background:#006600;>
	  <td align=center valign=middle height=\"100%\" width=\"100%\">
		  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<b>New Opponent has been created!</b>
		  </td>
		  </tr>
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<br><form><input type='button' onClick=\"parent.location='admin_create_Opponent.php'\" value='Create more Opponents'>
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
<form action="admin_create_Opponent.php?addme=yes" method=post>
  <table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
    <tr style=background:#006600;> 
      <td colspan="2"> 
        <div align="center"><b>Create an Opponent</b></div>
      </td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Name</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="name">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Hit Points</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="hit_points">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Creature Type</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
         <input type="text" name="race">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Class</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
         <input type="text" name="class">
        </select>
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Min Damage</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="min_damage">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Max Damage</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="max_damage">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">AC</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <input type="text" name="AC">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">XP Given</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <input type="text" name="xp_given">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Gold Given</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <input type="text" name="gold_given">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Image Path</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <input type="text" name="image_path" value="images/monster/forest_troll.gif">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Location</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <select name="location">
	  <option value="">All</option>
	  <?php
	  $result = mysql_query ("SELECT * FROM phaos_locations ORDER BY name ASC");
	  if ($row = mysql_fetch_array($result)) {
	  do {
	  $id_num = $row["id"];
	  $location_name = $row["name"];
	  print ("<option value=\"$id_num\">$location_name</option>");
	  } while ($row = mysql_fetch_array($result));
	  }
	  ?>
	  </select>
        </font></b></td>
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
