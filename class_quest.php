<?php
include_once "items.php";

class quest {
  var $questid;
  var $reqexp;
  var $title;
  var $narrate;
  var $tracemsg;
  var $completemsg;
  var $rewarditemid;
  var $rewarditemtype;
  var $rewardgold;
  var $rewardexp;
  var $monstercollectid;
  var $monstercollectq;
  var $haveitemtype;
  var $haveitemid;
  var $visitlocationid;
  var $maxtime;
  var $endtime;

  function quest($id) {
    $this->questid = intval($id);
    $this->reqexp = 0;
    $this->title = '';
    $this->narrate = '';
    $this->tracemsg = '';
    $this->completemsg = '';
    $this->rewarditemid = 0;
    $this->rewarditemtype = '';
    $this->rewardgold = 0;
    $this->rewardexp = 0;
    $this->monstercollectid = 0;
    $this->monstercollectq = 0;
    $this->haveitemtype = '';
    $this->haveitemid = 0;
    $this->visitlocationid = 0;
    $this->maxtime = 0;
    $this->endtime = 0;

    if ($id !== 0) {
      $sql = "SELECT * FROM phaos_quests WHERE questid='".$this->questid."'";
      $res = mysql_query($sql);
      $row = mysql_fetch_array($res);
      $this->reqexp = intval($row['reqexp']);
      $this->title = $row['title'];
      $this->narrate = $row['narrate'];
      $this->tracemsg = $row['tracemsg'];
      $this->completemsg = $row['completemsg'];
      $this->rewarditemid = intval($row['rewarditemid']);
      $this->rewarditemtype = $row['rewarditemtype'];
      $this->rewardgold = intval($row['rewardgold']);
      $this->rewardexp = intval($row['rewardexp']);
      $this->monstercollectid = intval($row['monstercollectid']);
      $this->monstercollectq = intval($row['monstercollectq']);
      $this->haveitemtype = $row['haveitemtype'];
      $this->haveitemid = intval($row['haveitemid']);
      $this->visitlocationid = intval($row['visitlocationid']);
      $this->maxtime = intval($row['maxtime']);
      $this->endtime = intval($row['endtime']);
    }
  }

  public function insert() {
    $query = ("INSERT INTO phaos_quests "
            . "(reqexp, title, narrate, tracemsg, completemsg, rewarditemid,"
            . "rewarditemtype, rewardgold, rewardexp, monstercollectid,"
            . "monstercollectq, haveitemtype, haveitemid, visitlocationid,"
            . "maxtime, endtime) VALUES ("
            . "'". $this->reqexp . "',"
            . "'". mysql_real_escape_string($this->title) . "',"
            . "'". mysql_real_escape_string($this->narrate) . "',"
            . "'". mysql_real_escape_string($this->tracemsg) . "',"
            . "'". mysql_real_escape_string($this->completemsg) . "',"
            . "'". $this->rewarditemid . "',"
            . "'". $this->rewarditemtype . "',"
            . "'". $this->rewardgold . "',"
            . "'". $this->rewardexp . "',"
            . "'". $this->monstercollectid . "',"
            . "'". $this->monstercollectq . "',"
            . "'". $this->haveitemtype . "',"
            . "'". $this->haveitemid . "',"
            . "'". $this->visitlocationid . "',"
            . "'". $this->maxtime . "',"
            . "'". $this->endtime . "'"
            . ")");
    mysql_query($query);
    $this->questid = mysql_insert_id();
  }

  public function can_do_quest($character) {
    $now = time();
    // no time left
    if ($now + $this->maxtime > $this->endtime) {
      return -3;
    }

    // to little experience
    if ($this->reqexp > $character->xp) {
      return -2;
    }

    return 1;
  }

  private function get_quest_hunter($character) {
    $sql = "SELECT * FROM phaos_questhunters WHERE charid='".$character->id."' AND questid='".$this->questid."' LIMIT 1";
    $res = mysql_query($sql);
    $row = mysql_fetch_array($res);
    return $row ? $row : false;
  }

  /**
   * Returns quest status
   * @param character $character
   * @return int
   *    0 => not started,
   *    1 => started not finished,
   *    2 => finished, reward not taken,
   *    3 => completed (reward received)
   */
  public function get_status($character) {
    $row = $this->get_quest_hunter($character);
    if ($row === false) {
      return 0;
    }

    if (intval($row['complete']) === 1) {
      return 3;
    }

    // not all monsters killed
    if ($this->monstercollectq > intval($row['monstkill'])) {
      return 1;
    }

    // location not visited
    if ($this->visitlocationid && intval($row['visitdone']) === 0) {
      return 1;
    }

    $item_type = $this->haveitemtype;
    $item_id = $this->haveitemid;

    if ($item_id !== 0 && $character->checkequipped($item_type, $item_id) === 1) {
      return 1;
    }

    return 2;
  }

  public function get_trace_message($character) {
    $tracemsg = $this->tracemsg;
    if (strstr($tracemsg, '{count}')) {
      if (($row = $this->get_quest_hunter($character))) {
        $tracemsg = str_replace('{count}', $row['monstkill'], $tracemsg);
      }
    }
    return $tracemsg;
  }

  public function print_item($character, $item_id, $item_type) {
    global $lang_char, $lang_shop;
    $info= fetch_item_additional_info(array('id'=>$item_id,'type'=>$item_type,'number'=>1), $character);

    $description = $info['description'];
    $sell_price = $info['sell_price'];
    $image_path= $info['image_path'];
    $skill_req= $info['skill_req'];
    $skill_need= $info['skill_need'];
    $effect= $info['effect'];
    $skill_type= $info['skill_type'];
?>
    <table>
      <tr>
        <td align="center" valign="top">
          <?php echo makeImg($image_path); ?>
        </td>
        <td>
          <b><?php echo ucwords($description); ?></b><br />
          <?php if($item_type != "potion"): ?>
            <font color="<?php echo $skill_need; ?>"><?php echo $lang_shop["req"].$skill_req." ".$skill_type; ?></font>
          <?php endif; ?><br />
          <?php echo $lang_char['sell_pr'] . " " . $sell_price; ?>
        </td>
        <td align="center" valign="top">
          <?php echo $effect; ?>
        </td>
      </tr>
    </table>
<?php
  }

  public function print_reward($character) {
    global $lang_shop, $lang_expe, $lang_quest;
    
    echo "<div>";
    echo "<b>". $lang_quest['reward'] .":</b>";
    echo "<table>";
    if ($this->rewardgold > 0) {
      echo "<tr><td>" . $this->rewardgold . ' ' . $lang_shop['gp'] . "</td></tr>";
    }
    if ($this->rewardexp > 0) {
      echo "<tr><td>" . $this->rewardexp . ' ' . $lang_expe . '</td></tr>';
    }
    if ($this->rewarditemid !== 0) {
        $item_id = $this->rewarditemid;
        $item_type = $this->rewarditemtype;
        echo "<tr><td>";
        $this->print_item($character, $item_id, $item_type);
        echo "</td></tr>";
    }
    echo "</table>";
  }

  public function accept($character) {
    $now = time();
    mysql_query("INSERT INTO phaos_questhunters (charid, questid, starttime) "
            . "VALUES ('".$character->id."', '".$this->questid."', '".$now."')");
  }

  public function complete(character $character) {
    mysql_query("UPDATE phaos_questhunters SET complete=1 "
            . "WHERE charid='".$character->id."' AND questid='".$this->questid."'");

    $set = array();
    if ($this->rewardgold > 0) {
      $newgold = $character->gold + $this->rewardgold;
      $set[] = "gold='$newgold'";
    }

    if ($this->rewardexp > 0) {
      $newxp = $character->xp + $this->rewardexp;
      $set[] = "xp='$newxp'";
    }

    if (count($set) > 0) {
      mysql_query("UPDATE phaos_characters SET ".join(',', $set)." WHERE id='".$character->id."'");
    }

    if ($this->rewarditemid) {
      $item_id = $this->rewarditemid;
      $item_type = $this->rewarditemtype;
      $character->add_item($item_id, $item_type);
    }
  }

}
