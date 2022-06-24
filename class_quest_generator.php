<?php

include_once "class_quest.php";

class quest_generator {

  var $rumors = array(
    "You hear that the local Lord Tom Hoppenstance is making a pilgrimage to the local temple to ask the gods to bless his new sword, the one he got from the last dragon he killed.",
    "Rumors are going around about a lost treasure hidden in the local catacombs.",
    "The local woods are haunted by the ghost of an old hag who takes care of her lost goats.",
    "People are complaining about the smell coming from the local tannery.",
    "A local merchant brought back a strange seed from his last trip to the city. He planted it and is now growing a giant beanstalk that smells like rotting meat.",
    "A man has been chasing his wife with a frying pan.",
    "A man was seen walking down the local road with a chicken on his head singing about how he was the king of the world.",
    "The local mayor has been seen in the streets with a big feather sticking out of his backside.",
    "A man was seen in the streets dressed in a barrel and asking for spare change for a good cause.",
    "A half orc was seen running around the streets with a pig on his head.",
    "The local Lord John of the manor has been seen walking around with a two headed chicken on his shoulder.",
    "The local butcher has been selling tainted chicken.",
    "A man has been walking around in the streets talking to a piece of cloth that looks like a man’s face",
    "A man was seen walking around the streets, warning about an upcoming zombie invasion",
    "The local fortune teller says that the King is going to be killed by a man dressed in green",
    "The local Lord Gregor was seen walking around town in his underwear, singing about how he is the king of the world",
    "A man has been seen walking around the streets with a box on his head",
    "A woman has been walking the streets yelling that the meat she bought from the local butcher is tainted",
    "The local bard was seen walking around in the streets and telling the townsfolk that the king has been turned into a frog",
    "The local Lord Gregor was seen walking around town in his underwear, singing about how he is the king of the world",
    "A man has been seen walking near the town walls yelling that the town will be invaded as soon as the wall is breached",
    "The local Lord Gregor has been seen walking around town complaining about the price of ale",
    "The local Lord Johnson has been seen walking around town with a black eye and a limp",
    "A drunk man was seen walking around town saying how he saw a dragon tear off the roof of a building",
    "Two old ladies are selling dolls in the market. They say that each doll can speak one secret word about its owner",
    "Three dragons are coming to town to eat everyone",
    "A man was seen walking around town yelling that someone killed his dog",
    "There is a wizard in the tavern. If you listen to him talk for more than an hour, he’ll make you forget everything you heard him say",
    "Two elderly men are responsible for the price of ale going up. They’re selling it to the barkeep for a higher price",
    "The local Lord Johnson is a werewolf. He goes out on a full moon and kills chickens",
    "Two men are selling a type of meat in the market. They will not say what the meat is, but they say it is very good",
    "A man was seen walking around town yelling that someone stole his gold",
    "Three men were seen walking around town yelling that they found gold in the swamp",
    "The local Lord Johnson has been seen walking around town with a new mace that he got from the dwarven smiths",
    "A man was seen walking around town yelling that he saw a ghost",
    "A man was seen walking around town yelling that he saw a demon",
    "A man was seen walking around town yelling that he saw a dragon",
    "A man was seen walking around town yelling that he saw a dragon eating a horse",
    "The local Lord Gregor has been seen walking around town with a big smile on his face",
    "The local Lord Gregor has been seen walking around town with a black eye",
    "The local Lord Gregor has been seen walking around town with a limp",
    "Two men are selling metal bars in the market. They say they are made from dragons",
    "The king is actually an ogre",
    "A man was seen walking around town yelling that he saw a dragon flying towards the town",
    "The local Lord Gregor was seen walking around town wearing a sash that says “Kiss Me, I’m A Lord”",
    "A man was seen walking around town yelling that he saw a bear walking on two legs",
    "The local Lord Johnson is a vampire. He can be seen walking around town with a walking stick",
    "A man was seen walking around town yelling that he saw a dragon flying towards the town",
    "The local Lord Gregor is a werewolf. He can be seen walking around town in the moonlight",
    "A man was seen walking around town yelling that he saw a dragon walk into the gates of the town",
    "A man was seen walking around town yelling that he saw a dragon fly over the town",
    "A man was seen walking around town yelling that he saw a dragon flying towards the town",
    "The local Lord Gregor is a vampire. He can be seen walking around town in the moonlight",
    "Rumors about a dragon attacking ships and towns on the coast",
    "You hear a persistent rumor about several escaped prisoners that are heading northwest",
    "A dragon has been seen flying overhead every day for the past week",
    "A dragon has been seen flying overhead every day for the past month",
    "When the new moon comes again, all the magic users will die",
    "There’s a new witch in town. She’s just as good as the old witch that was killed",
    "The new witch is better than the old witch",
    "There’s a new witch in town",
    "A witch was killed last night",
    "A new dragon has been seen flying along the east coast for the past week",
    "The dragon seen flying east is a new hatchling",
    "A new dragon has been seen flying east. It’s a huge, red dragon",
    "The dragon flying east is a red dragon",
    "A new dragon has been seen flying along the east coast",
    "A new dragon has been seen flying along the east coast for the past month",
    "A new dragon has been seen flying along the east coast for the past week",
    "A new dragon has been seen flying along the east coast for the past day",
    "The dragon flying east has been seen for the past week. It’s a big, red dragon",
    "Two wyverns are responsible for all the missing sheep around town. The wyverns are the size of horses",
    "Two wyverns are responsible for all the missing livestock around town",
    "The Lord Gregor was seen walking around town last night. Rumor has it that he was seen sucking the blood of a villager",
    "When the full moon rises, Zan the Wizard will make an appearance and attack the town",
    "An old woman was seen walking through town at 3:00 a.m. this morning. This morning, the Lord Gregor was found dead in his castle",
    "Last night, everyone in town dreamed of a dragon attacking the town",
    "You’ve been hearing rumors about an old man in the hills to the north. When approached, he disappears",
    "You’ve been hearing rumors about an old woman in the hills to the south. She is a member of the Cult of Zan",
    "The local blacksmith has been missing for days",
    "Jenny the innkeeper is remarrying old Lord Gregor",
    "Lord Gregor is remarrying Jenny the innkeeper",
    "Lord Gregor is remarrying Jenny the innkeeper because Lord Gregor is in love with his sister",
    "Local shepherds have reported that their sheep are being killed by wolves",
    "The local cleric has been missing for days",
    "Rumors are flying that the king was killed by his brother",
    "Rumors are flying that Lord Gregor was killed by his brother",
    "Rumors are flying that Jenny the innkeeper was killed by her husband",
    "Rumors are flying that the local cleric was killed by his brother",
    "Rumors about a dragon to the north",
    "Rumors about the old woman in the hills say that she is actually a powerful wizard",
    "You hear stories about a dragon to the south. He is actually a powerful wizard in disguise",
    "Stories about a strange wizard in the hills to the east",
    "The king has just appointed a new cleric",
    "Jenny and Lord Gregor have just remarried",
    "Lord Gregor and Jenny have just divorced",
    "The local bishop is said to have been killed by a powerful wizard",
    "You hear that the old woman in the hills was a member of a cult that worships a dragon",
    "Every Wednesday afternoon, the local blacksmith goes into the hills to pray to his god. He returns at dusk",
    "One of the local shepherds has been found dead in the hills."
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
          'potion' => 'phaos_potions',
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

      $quest->title = "Defeat ". $quest->monstercollectq ." monsters called ${monster['name']}";
      $quest->narrate = "Hero, I need you to defeat ". $quest->monstercollectq ." monsters called ${monster['name']}";
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
      $quest->tracemsg = "As I can see, you are still looking for the item. Come back, when you find it.";
      $quest->completemsg = "Good job hero. Here is your reward.";

    // Visit location (30% chance)
    } else if ($type <= 10) {
      // get random location
      $res = mysql_query("SELECT * FROM phaos_locations WHERE buildings = 'Y' AND id != '". $inn['location'] ."' ORDER BY RAND() LIMIT 1") or die(mysql_error());
      $location = mysql_fetch_array($res);

      $quest->visitlocationid = $location['id'];

      $quest->title = "Deliver message to the ${location['name']}.";
      $quest->narrate = "Hero, I have an urgent message that needs to be delivered to the ${location['name']}.";
      $quest->tracemsg = "I assume my message is still not delivered. Come back when the job is done.";
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
    $quest->endtime = time() + 7 * $day;

    // time for completing the quest is 2 days
    $quest->maxtime = 2 * $day;
    return $quest;
  }

  public function generate() {
    // get random inn
    $res = mysql_query("SELECT * FROM phaos_buildings WHERE name = 'Inn' AND shop_id = 22 ORDER BY RAND() LIMIT 1") or die(mysql_error());
    $inn = mysql_fetch_array($res);

    // random rumor
    $rumors = $this->rumors[mt_rand(1, count($this->rumors))];

    $is_male = 1; // male only for now
    $name = ucfirst($is_male === 1 ? $this->generate_male_name() : $this->generate_female_name());

    $race = $this->races[mt_rand(1, count($this->races))];

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

  public function get_active_quests_count() {
    $result = mysql_query("select count(questid) from phaos_quests");
    list($count) = mysql_fetch_array($result);
    return $count;
  }

}
