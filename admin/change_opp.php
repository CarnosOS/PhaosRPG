<?php
include("../config.php");
include("aup.php");
?>
<html>
<head>
<title>WoP Admin Panel - Change Opponent</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if(@$_REQUEST['changeme'])
{
	mysql_query("UPDATE phaos_opponents SET name='$name', hit_points='$hit_points', race='$race', class='$class', min_damage='$min_damage', max_damage='$max_damage', AC='$AC', xp_given='$xp_given', gold_given='$gold_given', image_path='$image', location='$location' WHERE id='$id'");
	echo "<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	  <tr>
	  <td align=center valign=middle height=\"100%\" width=\"100%\">
		  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<b>Opponent details have been changed!</b>
		  </td>
		  </tr>
		  <tr>
		  <td colspan=\"2\" align=center>
		  	<form><input type='button' onClick=\"parent.location='admin_edit_Opponents.php'\" value='OK'></form>
		  </td>
		  </tr>
		  </table>
		</td>
		</tr>
		</table>";
}
else
{
	$self=mysql_query("SELECT * FROM phaos_opponents WHERE id=$opp");
	while ($row = mysql_fetch_array($self)) {
	$id = $row["id"];
	$name = $row["name"];
	$hit_points = $row["hit_points"];
	$race = $row["race"];
	$class = $row["class"];
	$min_damage = $row["min_damage"];
	$max_damage = $row["max_damage"];
	$AC = $row["AC"];
	$xp_given = $row["xp_given"];
	$gold_given = $row["gold_given"];
	$image = $row["image_path"];
	$location_id = $row["location"];
	if($location_id == "0") {
	$location_name = "All";
	} else {
	$result = mysql_query ("SELECT * FROM phaos_locations WHERE id = '$location_id'");
	if ($row = mysql_fetch_array($result)) {$location_name = $row["name"];}
	}
	}
?>
<form action="change_opp.php?changeme=yes" method=post>
<input type="hidden" name="id" value="<?php echo $id; ?>">
  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align=center valign=middle height="100%" width="100%">
  <table width="600" border="1" cellspacing="0" cellpadding="3">
    <tr style=background:#006600;>
      <td colspan="2">
        <div align="center"><b>Edit Opponents</b></div>
      </td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Name</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="name" value="<?php echo $name; ?>">
        </font></b></td>
    </tr>
    <tr>
      <td width="50%"><b><font color="#FFFFFF">Hit Points</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="hit_points" value="<?php echo $hit_points; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Creature Type</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <input type="text" name="race" value="<?php echo $race; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Class</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <input type="text" name="class" value="<?php echo $class; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Min Damage</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="min_damage" value="<?php echo $min_damage; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Max Damage</font></b></td>
      <td width="50%"> <b><font color="#FFFFFF">
        <input type="text" name="max_damage" value="<?php echo $max_damage; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">AC</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <input type="text" name="AC" value="<?php echo $AC; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">XP Given</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <input type="text" name="xp_given" value="<?php echo $xp_given; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Gold Given</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <input type="text" name="gold_given" value="<?php echo $gold_given; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Image Path</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <input type="text" name="image" value="<?php echo $image; ?>">
        </font></b></td>
    </tr>
    <tr> 
      <td width="50%"><b><font color="#FFFFFF">Location</font></b></td>
      <td width="50%"><b><font color="#FFFFFF">
        <select name="location">
	  <?php
	  print ("<option value=\"$location_id\">$location_name</option>");
	  ?>
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
          <input type="submit" name="Submit" value="Change">
          <input type='button' onClick="parent.location='admin_edit_Opponents.php'" value='Back to list'>
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
