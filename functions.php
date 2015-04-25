<?php
function movenpc() {
	// select an NPC/creature to move - let's pick the one that hasn't moved in the longest time
	$result = mysql_query ("SELECT id FROM phaos_characters WHERE username='phaos_npc' ORDER BY stamina_time LIMIT 1");
	if ($row = mysql_fetch_array($result)) {
		$npc = new character($row["id"]);

        defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][]= " trying to relocate $npc->name($npc->id) at $npc->location with stamina_time=$npc->stamina_time";

		// let NPC heal HP and stamina
		if ($npc->hit_points < $npc->max_hp) {
			$npc->hit_points += (int)($npc->max_hp / 5);	// heal 20%
			if ($npc->hit_points > $npc->max_hp) { $npc->hit_points = $npc->max_hp; }
		}
		if ($npc->stamina_points < $npc->max_stamina) {
            $npc->stamina_points += 3;     // 3 at a time -- perhaps this should be calc another way
            if ($npc->stamina_points > $npc->max_stamina) { $npc->stamina_points = $npc->max_stamina; }
        }

		// reset stamina and regen time
		$npc->stamina_time	= time()+1000;	// FIXME: using 1000 as temp amount
		$npc->regen_time	= time()+1000;	// FIXME: using 1000 as temp amount

		if ($npc->location != 0) {
            $npc->relocate( (int)rand(1,8) );
        }

		if ($npc->location == 0) {
            $condition_pass = $npc->sql_may_pass();
		    // how did he get here??  -- let's put him at a random location
            defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][]= " trying to relocate $npc->name($npc->id) at $npc->location with stamina_time=$npc->stamina_time";

			$result=mysql_query("SELECT `id` FROM phaos_locations WHERE $condition_pass ORDER BY RAND() LIMIT 1") or die(mysql_error());
			list($newloc) = mysql_fetch_array($result);
			$npc->place($newloc);
		}

		// now we can update the DB
        $query= "UPDATE phaos_characters SET
			hit_points	=".$npc->hit_points.",
			stamina		=".$npc->stamina_points.",
			stamina_time	=".$npc->stamina_time.",
			regen_time	=".$npc->regen_time."
			WHERE id	='".$npc->id."'";
		$result = mysql_query($query);
		if (!$result) {echo "$query:<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}

	} else {	// didn't set $row from sql query
		die("why could we not select a creature to move?");
	}
}



function updateshops() {
	/*	update NPC shops with new items
	//
	*/

	if (rand(1,8)<2) {	// shops replenish too fast - slow them down
		// for now let's just pick a random inventory item and increase it by 1 (up to max)
		// FIXME: cant get RAND to work with UPDATE !?!?
		// $result=mysql_query("UPDATE phaos_shop_inventory SET quantity=quantity+1 WHERE quantity<max   ORDER BY RAND()   LIMIT 1");

		// this code will do it randomly, but in two steps.  MySQL 4.x is needed for UPDATE with RAND
		$result=mysql_query("SELECT shop_id,type,item_id FROM phaos_shop_inventory WHERE quantity<max  ORDER BY RAND() LIMIT 1;");
		if ($row = mysql_fetch_array($result)) {
			$result=mysql_query("UPDATE phaos_shop_inventory
				SET quantity=quantity+1
				WHERE shop_id='$row[shop_id]' AND type='$row[type]' AND item_id='$row[item_id]' ");
		}
	}

}


?>
