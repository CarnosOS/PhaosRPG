<?php

include_once "class_character.php";

class npc_generator {

  var $population;

  /**
   * constructor
   */
  function npc_generator() {
    $this->population = null;
  }

  private function get_population() {
    if ($this->population === null) {
      $res = mysql_query(
              "SELECT r.id, r.location, r.map_width, r.map_height, (count(c.id)/r.space) as value "
              . "FROM phaos_regions r LEFT OUTER JOIN phaos_characters c ON c.region = r.id "
              . "GROUP BY r.id, r.location, r.map_width, r.map_height "
              . "ORDER BY value ASC, r.id ASC"
              ) or die(mysql_error());
      $this->population = array();
      while ($row = mysql_fetch_array($res)) {
        $this->population[] = $row;
      }
    }
    return $this->population;
  }

  private function get_random_empty_location($region) {
    $where_name = "(name LIKE 'Wilderness' OR name LIKE 'Woodlands' OR name LIKE 'Rune Gate%' OR name LIKE 'Dungeon')";
    $where_pass = "(buildings='n' AND pass='y')";
    $where_region = "(id >= '" . $region['location'] . "' AND id < '" . ($region['location'] + $region['map_width'] * $region['map_height']) . "')";
    $where = "$where_name AND $where_pass AND $where_region";

    $tries = 10;
    while ($tries-- > 0) {
      $res = null;
      $sql = "SELECT id FROM phaos_locations WHERE $where ORDER BY RAND() LIMIT 1";
      $res = mysql_query($sql) or die(mysql_error());
      list($locationid) = mysql_fetch_array($res);

      //check whether location is crowded
      $res = mysql_query("SELECT count(*) FROM phaos_characters WHERE location='$locationid' AND username='phaos_npc'") or die(mysql_error());
      list($count) = mysql_fetch_array($res);
      if ($count > 2) {
        defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][] = " location $locationid is <b>crowded</b>, not placing here ($count npcs)";
        //trying to fix
        $res = mysql_query("SELECT id FROM phaos_characters WHERE location='$locationid' AND username='phaos_npc'") or die(mysql_error());
        while (list($id) = mysql_fetch_array($res)) {
          $crowd = new character($id);
          $crowd->relocate((int) rand(1, 8));
        }
      } else {
        break; //stop while loop
      }
    }
    return $locationid;
  }

  private function get_random_opponent($region) {
    $rows = array();
    $probability_sum = 0;
    $res = mysql_query("SELECT * FROM phaos_region_opponents WHERE region='" . $region['id'] . "'");
    while ($row = mysql_fetch_array($res)) {
      $row['probability'] = floor(100 * $row['probability']);
      $probability_sum += $row['probability'];
      $rows[] = $row;
    }

    if ($probability_sum === 0) {
      return null;
    }

    $opponent = null;
    $rand = mt_rand(0, $probability_sum);
    foreach ($rows as $row) {
      $opponent = $row;
      $rand -= $row['probability'];
      if ($rand <= 0) {
        break;
      }
    }

    return $opponent;
  }

  /**
   * generate new NPC/monster and add to database
   * @return 1 when npm generated otherwise 0
   */
  public function generate() {
    $population = $this->get_population();
    if (count($population) === 0) {
      return 0;
    }
    $region = $population[0]; // the region with the lowest amount of monsters
    $locationid = $this->get_random_empty_location($region);
    $opponent = $this->get_random_opponent($region);

    if ($opponent === null) {
      return 0;
    }

    $opponentid = $opponent['opponent'];
    $level = $opponent['min_level'];
    if ($opponent['min_level'] < $opponent['max_level']) {
      //create 50% min_level characters, and not more than 37,5% characters with level>3
      $level += rand(0, 1) * rand(0, $opponent['max_level'] - $opponent['min_level']);
    }

    $res = mysql_query("SELECT * FROM phaos_opponents WHERE id='$opponentid'") or die(mysql_error());
    if (($blueprint = mysql_fetch_array($res))) {
      $npc = new np_character_from_blueprint($blueprint, $level);
      $npc->region = $region['id'];
      $npc->place($locationid);
      DEBUG and $_SESSION['disp_msg'][] = "**DEBUG: $npc->name($npc->level) generated at location id $locationid";
      return 1;
    }

    return 0;
  }

}
