<?php

include_once "class_quest.php";

class quest_generator {

  var $rumors = array(
    "There is a blacksmith who lives alone in the mountains of Gilanthia who crafts weapons out of dragon bones.",
    "Dragons live in the mountains of Gilanthia.  Some of the beasts are taken from their caves while they're still young and raised in captivity to fight in the arena at Kelvy.",
    "The blacksmith in Gornath only carries leather goods.  So if you're running low on gold it is a good place for armor.",
    "You hear that the local Lord Tom Hoppenstance is making a pilgrimage to the local temple to ask the gods to bless his new sword, the one he got from the last dragon he killed.",
    "The woods of Tel-Khaliid are haunted by the ghost of an old hag who takes care of her lost goats.",
    "The Lord Johnson from the City of Snowholde is a werewolf. People says he has been cursed by a powerful wizard.",
    "There is an underground passage below the Lake of Tel-Khaliid. You can use it to travel to the island.",
    "I heard that there is a tomb in Lanus. It is a huge maze with deadly monsters. Only few managed to explore it.",
    "There is an elf village deep in the woods of Tel-Khaliid. There is no road and it's easy to get lost in the forest.",
    "The great mage Flageric has slaid the wyrm dragon, the most dangerous species.",
  );

  var $races = array("Human", "Elf", "Gnome", "Vampire", "Dwarf", "Orc", "Lizardfolk");

  var $nm1 = array("", "", "", "", "b", "c", "d", "g", "j", "k", "l", "m", "n", "ph", "r", "t", "v", "w", "z");
  var $nm2 = array("a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "ia", "oe", "io", "y");
  var $nm3 = array("c", "dr", "gr", "l", "ld", "lm", "ln", "m", "md", "mn", "n", "nd", "r", "rl", "rd", "s", "ss", "th", "thm", "z");
  var $nm4 = array("", "", "", "", "", "b", "c", "d", "k", "l", "ln", "lm", "n", "r", "s");
  var $nm5 = array("", "", "", "", "", "ch", "h", "j", "l", "m", "n", "ph", "r", "rh", "s", "sh", "w", "z");
  var $nm6 = array("a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "y");
  var $nm7 = array("dr", "l", "ll", "lm", "ln", "ld", "m", "mn", "n", "nm", "nn", "nr", "mr", "lgr", "n", "r", "rl", "rn", "rm", "rsh", "s", "sh", "ss", "th");
  var $nm8 = array("", "", "", "", "", "l", "n", "r", "s", "sh");

  private function generate_male_name() {
    $rnd = mt_rand(1, count($this->nm1));
    $rnd2 = mt_rand(1, count($this->nm2));
    $rnd3 = mt_rand(1, count($this->nm3));
    $rnd4 = mt_rand(1, count($this->nm2));
    $rnd5 = mt_rand(1, count($this->nm4));
    $rnd6 = mt_rand(1, count($this->nm3));
    $rnd7 = mt_rand(1, count($this->nm2));
    if (mt_rand(0, 1) === 0) {
      return $this->nm1[$rnd] . $this->nm2[$rnd2] . $this->nm3[$rnd3] . $this->nm2[$rnd4] . $this->nm4[$rnd5];
    }
    return $this->nm1[$rnd] . $this->nm2[$rnd2] . $this->nm3[$rnd3] . $this->nm2[$rnd4] . $this->nm3[$rnd6] . $this->nm2[$rnd7] . $this->nm4[$rnd5];
  }

  private function generate_female_name() {
    $rnd = mt_rand(1, count($this->nm5));
    $rnd2 = mt_rand(1, count($this->nm6));
    $rnd3 = mt_rand(1, count($this->nm7));
    $rnd4 = mt_rand(1, count($this->nm6));
    $rnd5 = mt_rand(1, count($this->nm8));
    $rnd6 = mt_rand(1, count($this->nm7));
    $rnd7 = mt_rand(1, count($this->nm6));
    if (mt_rand(0, 1) === 0) {
      return $this->nm5[$rnd] . $this->nm6[$rnd2] . $this->nm7[$rnd3] . $this->nm6[$rnd4] . $this->nm8[$rnd5];
    }
    return $this->nm5[$rnd] . $this->nm6[$rnd2] . $this->nm7[$rnd3] . $this->nm6[$rnd4] . $this->nm7[$rnd6] . $this->nm6[$rnd7] . $this->nm8[$rnd5];
  }

  private function get_random_item() {
      $item_types = array('weapon', 'potion', 'gloves', 'boots', 'shield', 'helm', 'armor');
      $item_type = $item_types[mt_rand(0, count($item_types) - 1)];
      $item_type_to_table = array(
          'weapon' => 'phaos_weapons',
          'potion' => 'phaos_potion',
          'armor' => 'phaos_armor',
          'boots' => 'phaos_boots',
          'gloves' => 'phaos_gloves',
          'shield' => 'phaos_shields',
          'helm' => 'phaos_helmets'
      );

      $res = mysql_query("SELECT id, name, image_path FROM `".$item_type_to_table[$item_type]."` ORDER BY RAND() LIMIT 1") or die(mysql_error());
      $item = mysql_fetch_array($res);
      $item['item_type'] = $item_type;
      return $item;
  }

  private function get_random_inn() {
    $sql = "SELECT b.location, count(n.id) as npcs "
      . "FROM phaos_buildings b "
      . "LEFT OUTER JOIN phaos_npcs n ON n.location = b.location "
      . "WHERE b.name = 'Inn' "
      . "GROUP BY b.location, b.name "
      . "ORDER BY npcs, RAND() LIMIT 1";
    $res = mysql_query($sql) or die(mysql_error());
    return mysql_fetch_array($res);
  }

  private function generate_quest($inn) {
    $quest = new quest(0);

    $type = mt_rand(1, 10);

    // kill monsters (50% chance)
    if ($type <= 5) {
      // get random monster
      $res = mysql_query("SELECT * FROM phaos_opponents WHERE location = 0 ORDER BY RAND() LIMIT 1") or die(mysql_error());
      $monster = mysql_fetch_array($res);

      $quest->monstercollectid = $monster['id'];
      $quest->monstercollectq = 5 * mt_rand(1, 5);

      $quest->title = "Defeat ". $quest->monstercollectq ." monsters called ${monster['name']}.";
      $quest->narrate = "Hero, I need you to defeat ". $quest->monstercollectq ." monsters called ${monster['name']}.";
      $quest->tracemsg = "As for now you have killed {count} monsters out of ". $quest->monstercollectq .". Come back to me, when your job is done.";
      $quest->completemsg = "Good job hero. Here is your reward.";

    // collect an item (20% chance)
    } else if ($type <= 7) {
      // get random item
      $item = $this->get_random_item();
      $item_type = $item['item_type'];

      $quest->haveitemid = intval($item['id']);
      $quest->haveitemtype = $item_type;

      $quest->title = "Find the item called ${item['name']}.";
      $quest->narrate = "Hero, I am looking for the item called ${item['name']}. If you bring it to me, you will be rewarded.";
      $quest->tracemsg = "You are still looking for the item. Come back, when you find it.";
      $quest->completemsg = "Good job hero. Here is your reward.";

    // Visit location (30% chance)
    } else if ($type <= 10) {
      // get random location
      $res = mysql_query("SELECT * FROM phaos_locations WHERE buildings = 'Y' AND id != '". $inn['location'] ."' ORDER BY RAND() LIMIT 1") or die(mysql_error());
      $location = mysql_fetch_array($res);

      $quest->visitlocationid = $location['id'];

      $quest->title = "Deliver message to the ${location['name']}.";
      $quest->narrate = "Hero, I have an urgent message that needs to be delivered to the ${location['name']}.";
      $quest->tracemsg = "My message is still not delivered. Come back when the job is done.";
      $quest->completemsg = "Good job hero. Here is your reward.";
    }

    // generate random reward
    $quest->rewardexp = 100 * mt_rand(1, 10);

    $has_item_reward = mt_rand(1, 10);
    if ($has_item_reward <= 2) {
      $item = $this->get_random_item();
      $quest->rewarditemid = intval($item['id']);
      $quest->rewarditemtype = $item['item_type'];
    } else {
      $quest->rewardgold = 100 * mt_rand(1, 20);
    }

    // every quest will last for one week
    $day = 24 * 60 * 60;
    $quest->endtime = time() + 7 * $day + mt_rand(1, $day);

    // time for completing the quest is 2 days
    $quest->maxtime = 2 * $day;
    return $quest;
  }

  public function generate() {
    $target_quest_count = 100; // about 2 quest per inn
    $quest_count = $this->get_active_quests_count();
    if ($quest_count >= $target_quest_count) {
      return;
    }

    // get random inn
    $inn = $this->get_random_inn();

    // random rumor
    $rumors = $this->rumors[mt_rand(0, count($this->rumors) - 1)];

    $is_male = 1; // male only for now
    $name = ucfirst($is_male === 1 ? $this->generate_male_name() : $this->generate_female_name());

    $race = $this->races[mt_rand(0, count($this->races) - 1)];

    $image_number = $race === 'Vampire' ? 6 : mt_rand(1, 5);
    $image_path = "images/icons/npcs/npc_$image_number.gif";

    // quest with random parameters
    $quest = $this->generate_quest($inn);

    // INSERT
    $quest->insert();

    mysql_query("INSERT INTO phaos_npcs (name, race, image_path, location, rumors, quest) "
            . "VALUES ("
            . "'" . mysql_real_escape_string($name) ."',"
            . "'" . mysql_real_escape_string($race) ."',"
            . "'" . mysql_real_escape_string($image_path) ."',"
            . "'" . $inn['location'] ."',"
            . "'" . mysql_real_escape_string($rumors) ."',"
            . "'" . $quest->questid ."'"
            . ")");
  }

  public function delete_finished_quests() {
    $now = time();
    mysql_query("DELETE FROM phaos_quests WHERE endtime < '$now'");
  }

  private function get_active_quests_count() {
    $result = mysql_query("select count(questid) from phaos_quests");
    list($count) = mysql_fetch_array($result);
    return $count;
  }

  public function collect_monster($character, $monster) {
    if ($monster->user !== 'phaos_npc') {
      return;
    }
    $result = mysql_query("SELECT id FROM phaos_opponents WHERE name='".$monster->name."'");
    if (($row = mysql_fetch_array($result))) {
      $monster_id = $row['id'];
      $sql =
        "UPDATE phaos_questhunters "
        . "SET monstkill = monstkill + 1 "
        . "WHERE charid='". $character->id ."' "
        . "AND questid IN ("
        . "  SELECT questid FROM phaos_quests WHERE monstercollectid = '".$monster_id."'"
        . ")";
      mysql_query($sql);
    }
  }

  public function visit_location($character, $location_id) {
    $sql =
      "UPDATE phaos_questhunters "
      . "SET visitdone = 1 "
      . "WHERE charid='". $character->id ."' "
      . "AND questid IN ("
      . "  SELECT questid FROM phaos_quests WHERE visitlocationid = '".$location_id."'"
      . ")";
    mysql_query($sql);
  }

}
