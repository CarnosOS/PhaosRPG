<?php
include("../config.php");
include("aup.php");
?>
<html>
  <head>
    <title>WoP Admin Panel - Edit Regions</title>
    <link href="../styles/phaos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
      <tr style=background:#006600;> 
        <td colspan="2"> 
          <div align="center"><b>Edit Regions</b></div>
        </td>
      </tr>
      <tr>
        <td colspan=2 align=center>
          <input type='button' onClick="parent.location = 'index.php'" value='Back to Admin Panel'>
          <input type='button' onClick="parent.location = 'change_region.php'" value='Create Region'>
        </td>
      </tr>
      <tr> 
        <td>
          <table>
            <tr><td>ID</td><td>Name:</td><td>Map Lacation</td><td>Map Width</td><td>Map Height</td><td>Home</td><td>Space</td></tr>
            <?php
            $self = mysql_query("SELECT * FROM phaos_regions ORDER BY location ASC");
            while ($row = mysql_fetch_array($self)) {
              echo "<tr><td>" . $row['id'] . "</td><td><a href='change_region.php?region={$row['id']}'>" . $row['name'] . "</a></td><td>" . $row['location'] . "</td><td>" . $row['map_width'] . "</td><td>" . $row['map_height'] . "</td><td>" . $row['home_page'] . "</td><td>" . $row['space'] . "</td></tr>";
            }
            ?>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
