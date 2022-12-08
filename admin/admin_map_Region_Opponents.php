<?php
include("../config.php");
include("aup.php");

$region = isset($_GET['region']) ? intval($_GET['region']) : 0;

if ($region !== 0 && isset($_GET['updateme'])) {
  $opponent = intval($_POST['opponent']);
  $probability = floatval($_POST['probability']);
  $min_level = intval($_POST['min_level']);
  $max_level = intval($_POST['max_level']);
  if ($probability === 0.0) {
    mysql_query("DELETE FROM phaos_region_opponents WHERE opponent='$opponent'");
  } else {
    $self = mysql_query("SELECT * FROM phaos_region_opponents WHERE region='$region' AND opponent='$opponent'");
    if (mysql_num_rows($self) === 1) {
      mysql_query("UPDATE phaos_region_opponents SET probability='$probability', min_level='$min_level', max_level='$max_level' WHERE region='$region' AND opponent='$opponent'");
    } else {
      mysql_query("INSERT INTO phaos_region_opponents VALUES ('$region', '$opponent', '$probability', '$min_level', '$max_level')");
    }
  }
}
?>
<html>
  <head>
    <title>WoP Admin Panel - Edit Region Opponents</title>
    <link href="../styles/phaos.css" rel="stylesheet" type="text/css">
    <style>
      input[type="text"] { width: 80px; }
    </style>
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
          <form><input type='button' onClick="parent.location = 'index.php'" value='Back to Admin Panel'></form>
        </td>
      </tr>

      <tr>
        <td>
          <form action="admin_map_Region_Opponents.php" method="get">
            <table width="100%">
              <tr>
                <td width="33%"><b><font color="#FFFFFF">Region</font></b></td>
                <td width="33%"><b><font color="#FFFFFF">
                    <select name="region" value="<?php echo $region; ?>">
                      <option value="0"<?php echo $region === 0 ? ' selected' : ''; ?>>Select Region</option>
                      <?php
                      $result = mysql_query("SELECT * FROM phaos_regions ORDER BY location ASC");
                      while ($row = mysql_fetch_array($result)) {
                        $region_id = intval($row["id"]);
                        $region_name = $row["name"];
                        $selected = $region === $region_id ? ' selected' : '';
                        print ("<option value=\"$region_id\"$selected>$region_name</option>");
                      }
                      ?>
                    </select>
                    </font></b>
                </td>
                <td width="33%">
                  <input type="submit" name="Submit" value="Select">
                </td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
      <?php
      if ($region !== 0) {
        ?>
        <tr> 
          <td>

            <table>
              <tr>
                <td width="12%">ID</td>
                <td width="40%">Name</td>
                <td width="12%">Probability</td>
                <td width="12%">Min Level</td>
                <td width="12%">Max Level</td>
                <td width="12%"></td>
              </tr>

              <?php
              $form_id = 0;
              $self = mysql_query(
                      "SELECT phaos_opponents.id, phaos_opponents.name, phaos_region_opponents.probability, phaos_region_opponents.min_level, phaos_region_opponents.max_level "
                      . "FROM phaos_opponents "
                      . "LEFT OUTER JOIN phaos_region_opponents ON phaos_opponents.id = phaos_region_opponents.opponent AND phaos_region_opponents.region='" . $region . "' "
                      . "WHERE phaos_opponents.location = 0 "
                      . "ORDER BY phaos_region_opponents.probability DESC, phaos_opponents.id ASC");
              while ($row = mysql_fetch_array($self)) {
                $form_id += 1;
                $color = $row['probability'] > 0 ? '#ffffff' : '#808080';
                ?>
                <tr>
                  <td>
                    <form id="form_<?php echo $form_id; ?>" action="admin_map_Region_Opponents.php?region=<?php echo $region; ?>&updateme=true" method="post">
                      <input type="hidden" name="opponent" value="<?php echo $row['id']; ?>">
                    </form>
                    <?php echo $row['id']; ?>
                  </td>
                  <td><font color="<?php echo $color; ?>"><?php echo $row['name']; ?></font></td>
                  <td><input type="text" name="probability" form="form_<?php echo $form_id; ?>" value="<?php echo $row['probability']; ?>"></td>
                  <td><input type="text" name="min_level" form="form_<?php echo $form_id; ?>" value="<?php echo $row['min_level']; ?>"></td>
                  <td><input type="text" name="max_level" form="form_<?php echo $form_id; ?>" value="<?php echo $row['max_level']; ?>"></td>
                  <td><input form="form_<?php echo $form_id; ?>" type="submit" name="Submit" value="Update"></td>
                </tr>
                <?php
              }
              ?>
            </table>
          </td>
        </tr>
        <?php
      }
      ?>
    </table>
  </body>
</html>
