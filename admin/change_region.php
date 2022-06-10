<?php
include("../config.php");
include("aup.php");

function get_space_value($location, $width, $height) {
  $start = intval($location);
  $end = intval($location + $width * $height);
  if ($start > 0 && $end > $start) {
    $res = mysql_query("SELECT COUNT(*) FROM phaos_locations WHERE id >= '$start' AND id < '$end' AND buildings = 'n' AND pass = 'y'");
    list($space) = mysql_fetch_row($res);
    return $space;
  }
  return 0;
}

?>
<html>
  <head>
    <title>WoP Admin Panel - Change Region</title>
    <link href="../styles/phaos.css" rel="stylesheet" type="text/css">
  </head>
  <body>

    <?php
    if (@$_REQUEST['changeme']) {
      $home_page = $home_page ? 1 : 0;
      $space = get_space_value($location, $map_width, $map_height);
      mysql_query("UPDATE phaos_regions SET name='$name', location='$location', map_width='$map_width', map_height='$map_height', home_page='$home_page', space='$space' WHERE id='$id'");
      ?>
      <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align=center valign=middle height="100%" width="100%">
            <table border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td colspan="2" align=center>
                  <b>Region details have been changed!</b>
                </td>
              </tr>
              <tr>
                <td colspan="2" align=center>
                  <form><input type='button' onClick="parent.location = 'admin_map_Regions.php'" value='OK'></form>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <?php
    } if (@$_REQUEST['addme']) {
      $home_page = $home_page ? 1 : 0;
      $space = get_space_value($location, $map_width, $map_height);
      mysql_query("INSERT INTO phaos_regions (name, location, map_width, map_height, home_page, space) VALUES ('$name', '$location', '$map_width', '$map_height', '$home_page', '$space')");
      ?>
      <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align=center valign=middle height="100%" width="100%">
            <table border="0" cellspacing="1" cellpadding="0">
              <tr style="background:#006600;">
                <td colspan="2" align=center>
                  <b>New Region has been created!</b>
                </td>
              </tr>
              <tr>
                <td colspan="2" align=center>
                  <br><form><input type='button' onClick="parent.location = 'change_region.php'" value='Create more Regions'>
                    <input type='button' onClick="parent.location = 'index.php'" value='Back to Admin Panel'></form>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <?php
    } else if (@$_REQUEST['deleteme']) {
      $id = intval(@$_REQUEST['deleteme']);
      mysql_query("DELETE FROM phaos_regions WHERE id='$id'");
      ?>
      <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align=center valign=middle height="100%" width="100%">
            <table border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td colspan="2" align=center>
                  <b>The Region has been deleted!</b>
                </td>
              </tr>
              <tr>
                <td colspan="2" align=center>
                  <form><input type='button' onClick="parent.location = 'admin_map_Regions.php'" value='OK'></form>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <?php
    } else if ($region != 0) {
      $self = mysql_query("SELECT * FROM phaos_regions WHERE id=$region");
      while ($row = mysql_fetch_array($self)) {
        $id = $row["id"];
        $name = $row["name"];
        $location = $row["location"];
        $map_width = $row["map_width"];
        $map_height = $row["map_height"];
        $home_page = $row["home_page"];
      }
      ?>
      <form action="change_region.php?changeme=yes" method=post>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align=center valign=middle height="100%" width="100%">
              <table width="600" border="1" cellspacing="0" cellpadding="3">
                <tr style=background:#006600;>
                  <td colspan="2">
                    <div align="center"><b>Edit Regions</b></div>
                  </td>
                </tr>
                <tr> 
                  <td width="50%"><b><font color="#FFFFFF">Name</font></b></td>
                  <td width="50%"> <b><font color="#FFFFFF">
                      <input type="text" name="name" value="<?php echo $name; ?>">
                      </font></b></td>
                </tr>
                <tr>
                  <td width="50%"><b><font color="#FFFFFF">Map Location</font></b></td>
                  <td width="50%"> <b><font color="#FFFFFF">
                      <input type="text" name="location" value="<?php echo $location; ?>">
                      </font></b></td>
                </tr>
                <tr>
                  <td width="50%"><b><font color="#FFFFFF">Map Width</font></b></td>
                  <td width="50%"> <b><font color="#FFFFFF">
                      <input type="text" name="map_width" value="<?php echo $map_width; ?>">
                      </font></b></td>
                </tr>
                <tr> 
                  <td width="50%"><b><font color="#FFFFFF">Map Height</font></b></td>
                  <td width="50%"><b><font color="#FFFFFF">
                      <input type="text" name="map_height" value="<?php echo $map_height; ?>">
                      </font></b></td>
                </tr>
                <tr> 
                  <td width="50%"><b><font color="#FFFFFF">Show on Home page</font></b></td>
                  <td width="50%"><b><font color="#FFFFFF">
                      <input type="checkbox" name="home_page"<?php echo $home_page ? ' checked' : '' ?> value="1">
                      </font></b></td>
                </tr>
                <tr> 
                  <td colspan="2"> 
                    <div align="center"> 
                      <input type="submit" name="Submit" value="Change">
                      <input type='button' onClick="parent.location = 'change_region.php?deleteme=<?php echo $id; ?>'" value='Delete this region'>
                      <input type='button' onClick="parent.location = 'admin_map_Regions.php'" value='Back to list'>
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </form>
      <?php
    } else {
      ?>
      <form action="change_region.php?addme=yes" method=post>
        <table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
          <tr style=background:#006600;> 
            <td colspan="2"> 
              <div align="center"><b>Create a Region</b></div>
            </td>
          </tr>
          <tr>
            <td width="50%"><b><font color="#FFFFFF">Name</font></b></td>
            <td width="50%"> <b><font color="#FFFFFF">
                <input type="text" name="name">
                </font></b></td>
          </tr>
          <tr> 
            <td width="50%"><b><font color="#FFFFFF">Map Location</font></b></td>
            <td width="50%"> <b><font color="#FFFFFF">
                <input type="text" name="location">
                </font></b></td>
          </tr>
          <tr>
            <td width="50%"><b><font color="#FFFFFF">Map Width</font></b></td>
            <td width="50%"> <b><font color="#FFFFFF">
                <input type="text" name="map_width">
                </font></b></td>
          </tr>
          <tr>
            <td width="50%"><b><font color="#FFFFFF">Map Height</font></b></td>
            <td width="50%"> <b><font color="#FFFFFF">
                <input type="text" name="map_height">
                </font></b></td>
          </tr>
          <tr>
            <td width="50%"><b><font color="#FFFFFF">Show on Home page</font></b></td>
            <td width="50%"><b><font color="#FFFFFF">
                <input type="checkbox" name="home_page" value="1">
                </font></b></td>
          </tr>
          <tr>
            <td colspan="2">
              <div align="center">
                <input type="submit" name="Submit" value="Create">
                <input type='button' onClick="parent.location = 'admin_map_Regions.php'" value='Back to list'>
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
