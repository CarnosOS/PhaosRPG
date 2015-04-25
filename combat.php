<?php
include "header.php";

function endfight(){
	$disp_msg= $_SESSION['disp_msg'];
	$charfrom= @$_SESSION['charfrom'];

	session_unset();
	session_destroy();

	flush();

	if(@$_SESSION['charfrom'] == "arena"){
		return "arena.php";
	} else if($charfrom == "dungeon"){
		return "travel_dungeon.php?finish";
	} else {
		return "travel.php";
	}
}

function sayOpponents(){
	global $oppcharacter;
	$more=  (@$_SESSION['num_of_opps']>1)? ' and '.($_SESSION['num_of_opps']-1).' more foes':"";
	return "$oppcharacter->name Level $oppcharacter->level $more";
}


/* part Copyright 2005 peter.schaefer@gmail.com */

session_start();

// INITIAL SETUP

include_once "class_character.php";

/*
 * return the root of the damage
 * FIXME: charcters attacks, magic, and the opponents table have to be fixed to use the same system
 * the damage inflicted by character is completely out of proportion compared to damage min/max of npcs
 */
function root_damage($damage) {
	//TEMPORARY FIX FOR NEW PLAYERS...
	if($character->level < 10) {
		return $damage;
	} else {
		$exp= 0.49;//0.50
		return (int)(pow($damage,$exp)+rand(0,99)*0.01);
	}
}

/*
 * return a random damage between min and max
 * Adding this kind of randomness should make combat more exciting, while keeping damage in the lower range most of the time.
 * FIXME: This function should somehow make root_damage superfluous, but that requires making npcs more player like
 */
function roll_damage($mindamage, $maxdamage) {
	$delta= $maxdamage-$mindamage;
	if($delta < 1){
		$delta= 1;
	}

	$damage = floor($mindamage+exp(rand(0,floor(137.0*log($delta)))/137.0)+rand(0,99)*0.01);
	DEBUG AND $_SESSION['disp_msg'][] = "DEBUG: damage between $mindamage-$maxdamage = $damage";
	return $damage;
}

$character = new character($PHP_PHAOS_CHARID);

$_SESSION['disp_msg'] = array();
if (isset($_GET['charfrom'])) {
	$_SESSION['charfrom'] = $_GET['charfrom'];
}

//FIXME: way to easy to hack
$_SESSION['fightbonus']= isset($_GET['bonus'])? $_GET['bonus']: 1;
if($_SESSION['fightbonus'] > 4){
	$_SESSION['fightbonus']= 4;
}

function setcombatlocation($combatlocation){
	$_SESSION['combatlocation']= $combatlocation;
	$result = mysql_query("select name from phaos_locations where id=$_SESSION[combatlocation] LIMIT 1");
	@list($_SESSION['locationname']) = mysql_fetch_row($result);
}

DEBUG AND $_SESSION['disp_msg'][] = "DEBUG: character hp= $character->hit_points";

// OPPONENT INFORMATION
if(@$_SESSION['charfrom'] != "arena") {
	DEBUG AND $_SESSION['disp_msg'][] = "DEBUG: normal combat";
	setcombatlocation($character->location);
	$list= whos_here($_SESSION['combatlocation'],'phaos_npc');
} else {
	//WARNING: usernames have limited length, so don't use too long names for npc character types

	$list= array();
	if(@$_SESSION['combatlocation']){
 		$list= whos_here($_SESSION['combatlocation'],'phaos_arena_fighting');
		DEBUG AND $_SESSION['disp_msg'][] = "DEBUG: Trying to locate previous opponents, found ".count($list);
	} else {
		//place new monsters in arena
		// this code has to be moved/adapted into the arena.php, I guess, since otherwise the arena cannot properly set the level of the monster
		DEBUG AND $_SESSION['disp_msg'][] = "DEBUG: arena initial setup";

		//Set opponents level
		$opponent_level= intval(@$_GET['opponent_level']);
		if($opponent_level > 0){
			$_SESSION['opp_level'] = $opponent_level;
		} else {
			$_SESSION['opp_level'] = (int)rand((int)($character->level/5),($character->level));
		}
		$other_opp_level= $_SESSION['opp_level'];

		// Set number of opponents
		$_SESSION['num_of_opps'] = rand(1, ceil(sqrt($opponent_level)) );

		$opponent_id= intval(@$_GET['opponent_id']);
		DEBUG AND $_SESSION['disp_msg'][] = "DEBUG: choosing opponent $opponent_id, lvl $_SESSION[opp_level] , $_SESSION[num_of_opps] foes";

		//find or create an arena location
		//FIXME
		//find out whether there is a city or special here, and use the name
		$result = mysql_query("select name from phaos_locations where id=$character->location LIMIT 1");
		@list($locationname) = mysql_fetch_row($result);

		$arenaat= isset($locationname)? $locationname : "Unknown ".rand(0,99);
		$arenaname= "Arena ".rand(1,3)." at $arenaat";

		//check whether this arena exists already
		$result = mysql_query("select id from phaos_locations where name='$arenaname' order by id DESC");
		@list($arenalocation) = mysql_fetch_row($result);
		if(!@$arenalocation) {
			DEBUG and $_SESSION['disp_msg'][] = "DEBUG: adding new arena ";
			$arenalocation= nextLocationIdFromRange('arena_fighting_location',__FILE__,__LINE__);
			//insert if not exists
			$query= "INSERT INTO phaos_locations
			(id, name, image_path, special, buildings, pass, explore)
	                VALUES
			($arenalocation, '$arenaname','images/arena.gif',1,0,1,0)";
			$req = mysql_query($query);
			if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__); exit;}

			$result = mysql_query("select id from phaos_locations where name='$arenaname' order by id DESC");
			@list($arenalocation) = mysql_fetch_row($result);

			//make arena point to itself so that monsters don't wander off
			$query= "UPDATE phaos_locations
			SET
			`above_left`= $arenalocation,
			`above`= $arenalocation,
			`above_right`= $arenalocation,
			`leftside`= $arenalocation,
			`rightside`= $arenalocation,
			`below_left`= $arenalocation,
			`below`= $arenalocation,
			`below_right`= $arenalocation
			WHERE id= $arenalocation";
			$req = mysql_query($query);
			if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__); exit;}
		}

		(@$arenalocation)or die('Must have a special location for arena');

		$_SESSION['arenalocation']= $arenalocation;
		setcombatlocation($_SESSION['arenalocation']);

		$opplocation= $character->location;

		//empty arena of old combatants
		//FIXME: check whether arena is in use
		$query= "UPDATE phaos_characters
		SET location= '$opplocation',
		username= 'phaos_npc_arena'
		WHERE location= '$arenalocation'";
		$req = mysql_query($query);
		if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__); exit;}

		$oppsneeded= $_SESSION['num_of_opps'];

		if(@$opponent_id) {
			//try to find requested opponent
			$query = "SELECT id FROM phaos_characters WHERE $opplocation AND id=$opponent_id AND username LIKE 'phaos_%_arena'";
			$result = mysql_query($query);
			//place requested opponent
			if( mysql_num_rows($result)>0 ) {
				$oppcharacter= new character($opponent_id);
				$oppcharacter->user= 'phaos_arena_fighting';
				$oppcharacter->place($arenalocation);
				$opponent_id= $oppcharacter->id;//paranoia
				$list[]= $opponent_id;
				$_SESSION['opponent_id'] = $opponent_id;
				--$oppsneeded;
				if(--$other_opp_level<1) {
					$other_opp_level= 1;
				}
			}
		}

		//place new monsters on map
		$query = "SELECT * FROM phaos_opponents WHERE location='$opplocation' ORDER BY RAND() LIMIT $oppsneeded";
		$blueprints= fetch_all($query);
		foreach($blueprints as $blueprint) {
			$npc= new np_character_from_blueprint($blueprint,$other_opp_level,'phaos_arena_fighting');
			$npc->place($arenalocation);
			$list[]= $npc->id;
			DEBUG AND $_SESSION['disp_msg'][] = @"DEBUG: placing $npc->name $npc->level $npc->username";
		}
		//$list= whos_here($_SESSION['combatlocation'],'phaos_arena_fighting');
	} // set up arena
}

$_SESSION['num_of_opps'] = count($list);
DEBUG AND $_SESSION['disp_msg'][] = "DEBUG: There are ".count($list)." opponents here.";

//TODO: for spells
//$opponentList= makeList($list);

$oppcharacter= null;
if (!count($list)) {
	$comb_act = 'endfight';
	DEBUG and print_msgs($_SESSION['disp_msg'],'','<br>');
	$link= endfight();
	jsChangeLocation($link);
	$skip_actions = true;
} else {
	$skip_actions = false;

	if(!@$_SESSION['opponent_id']){
		$_SESSION['opponent_id']= $list[0];
	}

	//load opponents
	$opponents= array();
	foreach($list as $id) {
		$opponents[$id]= new character($id);
	}

	if(@$_SESSION['opponent_id']){
		DEBUG AND $_SESSION['disp_msg'][] = "DEBUG: Using old opponent";
		$oppcharacter = &$opponents[$_SESSION['opponent_id']];
	}
	if(!@$oppcharacter || $oppcharacter->hit_points<=0 ) {
		$_SESSION['opponent_id'] = $list[0];
		$oppcharacter = &$opponents[$_SESSION['opponent_id']];
		DEBUG AND $_SESSION['disp_msg'][] = "DEBUG: Using new opponent";
	}
}

if(DEBUG){
	$_SESSION['disp_msg'][] = "DEBUG: location: ".@$character->location;
	$_SESSION['disp_msg'][] = "DEBUG: charfrom: ".@$_SESSION['charfrom'];
	$_SESSION['disp_msg'][] = "DEBUG: combat location: ".@$_SESSION['combatlocation'];
	$_SESSION['disp_msg'][] = "DEBUG: opponent id: ".@$oppcharacter->id;
	$_SESSION['disp_msg'][] = "DEBUG: opponent session id: ".@$_SESSION['opponent_id'];
	$_SESSION['disp_msg'][] = "DEBUG: LIST: ".print_r(@$list,true);
}

//$_SESSION['opponent_id']: the an opponent to attack
//$_SESSION['endcombat']: whether combat skips the monster attack, maybe if monster is dead

$_SESSION['endcombat'] = false;

if($skip_actions) {
	DEBUG AND $_SESSION['disp_msg'][] = "DEBUG: Skipping actions";
} else {
	// Do this if we have an opponent ID  (only time we shouldn't have one is when we FIRST enter combat)

	$combat_continue = 1;   //Combat continues from where it left off

	if(isset($healed)) {
		if($_SESSION['no_heal'] == 1) {
			$_SESSION['disp_msg'][] = $lang_comb["not_heal_this"];
		} else {
			$_SESSION['disp_msg'][] = $lang_comb["drink"].$_SESSION['heal_points']."";
			refsidebar();
		}
		unset($healed);
	}

	// COMBAT ACTIONS
	if(!isset($comb_act)){
		$comb_act= 'travel';
	}

	if($comb_act == 'flee'){
		// Flee Code
		$char_flee_roll = ($character->dexterity + $character->level + diceroll());
		$opp_flee_roll  = ($oppcharacter->dexterity + $oppcharacter->level + diceroll());

		DEBUG AND $_SESSION['disp_msg'][] = "DEBUG: Flee roll $char_flee_roll > $opp_flee_roll?";

    // <10 changed to <50 by dragzone
    if($char_flee_roll > $opp_flee_roll  OR  rand(1,100)<50 ) {
			//move character to random adjacent location
			for($i=0;$i<24;++$i) {
				if($character->relocate(rand(1,8))) {
					break;
				}
			}
			//Character Flees
			$link= endfight();
			jsChangeLocation($link);
		} else {
			jsChangeLocation("combat.php?comb_act=npc_attack&fleefail=1");
		}
	}

	// BEGIN ATTACK CODE
	if($comb_act == 'both_attack' OR $comb_act == 'char_attack' OR $comb_act == 'magic_attack') {

		//Check Stamina points
		if ($character->stamina_points <= 0) {
			$_SESSION['disp_msg'][] = $lang_comb["stam_noo"];
		} else {
			//CHARACTER ATTACKS

			// are we using up a scroll/magic ?
    			if($comb_act == 'magic_attack'){
    				$res=mysql_query("SELECT name,min_damage,max_damage,damage_mess,req_skill FROM phaos_spells_items WHERE id = $spellid");
    				list($name,$min_damage,$max_damage,$damage_mess,$req_skill) = mysql_fetch_array($res);
	
    				// Remove scroll from inventory
    				$sql = "DELETE FROM phaos_char_inventory WHERE id = '$invid'";
    				mysql_query($sql) or die ("Error in query: $query. " . mysql_error());

				if($character->wisdom + rand(1,$character->wisdom) < $req_skill) {
					$defenders = array();
					$_SESSION['disp_msg'][] = $lang_magic["spell_fumble"];
				} else {
					// set area effect
					$numdefenders=  $damage_mess* (1+(int)($character->wisdom/9+rand(0,99)*0.01));
					if($numdefenders>0) {
						if($numdefenders>=count($opponents)) {
							$defenders= &$opponents;
						} else {
							$defenders=  array_rand_assoc_array($opponents, $numdefenders);
						}
					}
					//always attack the one the character is engaged with
					$defenders[$oppcharacter->id] = &$oppcharacter;
				}
			} else {
				$defenders= array( $oppcharacter->id => &$oppcharacter );
		}

		foreach(array_keys($defenders) as $defenderkey) {
			$defender= &$defenders[$defenderkey];
			if($defender->hit_points<=0) {
				if(DEBUG) {$_SESSION['disp_msg'][] = "**DEBUG: defender[$defenderkey]=".print_r($defender,true);}
			} else {
				// Determine char attack value
				$_SESSION['char_attack']= $character->attack_roll($comb_act);
				if(DEBUG>=1) {$_SESSION['disp_msg'][] = "**DEBUG: char_attack = ".$_SESSION['char_attack'];}
				// Determine opponent defense value
				$_SESSION['opp_defence']= $defender->defence_roll($comb_act);
//zeke
           		if(DEBUG>=1) {$_SESSION['disp_msg'][] = "**DEBUG: opp_def = ".$_SESSION['opp_defence'];}

        		//Check hit to opponent
                $damage_multiplier= $_SESSION['char_attack']-$_SESSION['opp_defence'];

////TEMPORARY FIX FOR NEW PLAYERS... ---Temporary Bugfix: If userlevel over 9, user doesn't hit opponets by dragzone
//if($character->level < 10) {
	$lucky_hit = rand(1,10);
//}

          		if($damage_multiplier<0 AND $lucky_hit < 3) { // Missed
        			$_SESSION['disp_msg'][] = $lang_comb["u_miss"];
        		}

        		if(!$damage_multiplier OR $lucky_hit == 3) { // Tie (missed)
        			$_SESSION['disp_msg'][] = $defender->name." ".$lang_comb["def_ur_att"];
        		}

          	    if(DEBUG>=1) {$_SESSION['disp_msg'][] = "**DEBUG: damage multiplier = ".$damage_multiplier;}
        		if( $damage_multiplier>0 OR $lucky_hit > 3) {	// Hit
        			// Do and show damage to Opponent
        			if ($comb_act == "magic_attack") {	// magic damage
        				$_SESSION['dmg_to_opp'] = roll_damage(root_damage($min_damage*$damage_multiplier),root_damage($max_damage*$damage_multiplier))+$character->wisdom;
        			} else {	// normal damage
                        //FIXME: this is odd, attack_min,max turn up the second time
        				$_SESSION['dmg_to_opp'] = roll_damage(root_damage($character->attack_min()*$damage_multiplier)-$defender->defense_min()/2,root_damage($character->attack_max()*$damage_multiplier)-$defender->defense_min());
                        if($character->race=='Vampire'){
               				$_SESSION['disp_msg'][] = "$lang_comb[suck_opp_blood]";
                            $character->stamina_points+= $_SESSION['dmg_to_opp'];
                        }
        			}
        			if(DEBUG>=1) {$_SESSION['disp_msg'][] = "**DEBUG: dmg_to_opp = ".$_SESSION['dmg_to_opp'];}
        			if($_SESSION['dmg_to_opp'] <= 0) {$_SESSION['dmg_to_opp'] = 1; }

        			$defender->hit_points   = $defender->hit_points   - $_SESSION['dmg_to_opp'];
       				$_SESSION['disp_msg'][] = "$lang_comb[att_hit_foor] $_SESSION[dmg_to_opp]";

            		//Update Opponent Hit Points in the DataBase
            		$sql = ("UPDATE phaos_characters SET hit_points = ".$defender->hit_points." WHERE id = ".$defender->id."");
            		$res = mysql_query($sql);
                    if(!$res){ showError(__FILE__,__LINE__,__FUNCTION__); exit; };

                    //update opponent character

        			// FIXME: when   ($damage_mess == 1)  this whole section needs to loop
        			if ($defender->hit_points <= 0) {	//An opponent has been killed
        				if($defender->hit_points < 0) { $defender->hit_points = 0; }
        				$_SESSION['disp_msg'][] = $lang_comb["kill_a"]." ".$defender->name;

                        if($defender->id == $_SESSION['opponent_id']){
                            $_SESSION['opponent_id']= 0;
            				unset($_SESSION['opponent_id']);	// clear current opp
                        }

            			$ret = $defender->kill_characterid();
                   		if(DEBUG>=1) {$_SESSION['disp_msg'][] = "**DEBUG: killing oppponent";}

        				// add monsters to replace dead one
        				for($i = 1; $i <= 2; $i++) {	//FIXME: 2 monsters for now, but this WILL over populate!
        					npcgen()  and  DEBUG and $_SESSION['disp_msg'][] = "**DEBUG: npcgen is success";
        				}

        				//Receive Gold
        				// Changed by dragzone---
        				if($character->level < 10) {$_SESSION['gold_rec'] = $_SESSION['fightbonus']*4* $defender->gold;} else {$_SESSION['gold_rec'] = $_SESSION['fightbonus']*2* $defender->gold;}
        				// ----------------------
        				$character->gold += $_SESSION['gold_rec'];
        				$_SESSION['disp_msg'][] = "$lang_fun[gai_gold] $_SESSION[gold_rec]";

        				//Receive Experience
        				// changed by dragzone---
        				if($character->level < 10) { $_SESSION['xp_rec'] = (int)($defender->max_hp+$character->wisdom); } else { $_SESSION['xp_rec'] = (int)($defender->max_hp/2+$character->wisdom); }
        				// ----------------------
        				$_SESSION['xp_rec'] *= $_SESSION['fightbonus'];
        				$character->xp += $_SESSION['xp_rec'];
        				$_SESSION['disp_msg'][] = "$lang_fun[gai_xp] $_SESSION[xp_rec]";

        				//Update Character rewards to the DataBase
        				$res=mysql_query("UPDATE phaos_characters SET gold=".$character->gold.", xp=".$character->xp." WHERE id='$PHP_PHAOS_CHARID'");
                		$res = mysql_query($sql);
                        if(!$res){ showError(__FILE__,__LINE__,__FUNCTION__); exit; };

                        $character->all_skillsup($comb_act,$lang_fun);

        				refsidebar();

        				$list=whos_here($character->location);

                        if($defender->id == $oppcharacter->id){
                            //the opponent we are engaged with has been killed
                            $_SESSION['endcombat']= true;
                        }
        			}//killed opponent
        		}//hit opponent
                }//living defender
            }//foreach defender
        }  //end "has enough stamina"

        $_SESSION['disp_msg'][] = "&nbsp";
        $_SESSION['disp_msg'][] = "&nbsp";

       $character->update_stamina();

   } // end all char attack code

   if( ($comb_act == 'both_attack' OR $comb_act == 'npc_attack' OR $comb_act == 'magic_attack') AND $_SESSION['endcombat'] == false) {

      //OPPONENT ATTACKS
      if(isset($npcfirstatt)) {
         $_SESSION['disp_msg'][] = $lang_comb["under_att"];
         unset($npcfirstatt);
      }

      if(isset($fleefail)) {
         $_SESSION['disp_msg'][] = $lang_comb["fail_flee"]." ".sayOpponents();
         unset($fleefail);
	DEBUG and $_SESSION['disp_msg'][] = "**DEBUG: Flee = Char: ".($character->dexterity+$character->level)."+(2-30)  /  MOB: ".($oppcharacter->dexterity+$oppcharacter->level)."+(2-30)";
      }


    // let each opponent attack
    foreach(array_keys($opponents) as $opponentskey) {
        $attackingcharacter= &$opponents[$opponentskey];
        if($attackingcharacter->hit_points<=0){
             if(DEBUG) {$_SESSION['disp_msg'][] = "**DEBUG: $attackingcharacter->name #$attackingcharacter->id is dead";}
            unset($opponents[$opponentskey]);
        }else{
         if(DEBUG>=1) {$_SESSION['disp_msg'][] = "**DEBUG: $attackingcharacter->name #$attackingcharacter->id, STR = $attackingcharacter->strength, fight = $attackingcharacter->fight";}

         $_SESSION['opp_attack'] = $attackingcharacter->attack_roll($comb_act);
         if(DEBUG>=1) {$_SESSION['disp_msg'][] = "**DEBUG: opp_attack = ".$_SESSION['opp_attack'];}

         //Set Characters defence
		 $_SESSION['char_def'] = $character->defence_roll($comb_act);
         if(DEBUG>=1) {$_SESSION['disp_msg'][] = "**DEBUG: char_def = ".$_SESSION['char_def'];}

         $damage_multiplier= $_SESSION['opp_attack']-$_SESSION['char_def'];

         if($damage_multiplier<0) {
         // Missed
            $_SESSION['disp_msg'][] = $attackingcharacter->name." misses you!";
         }

         //Check hit to Character
         if(!$damage_multiplier) {
         // Deadlock
            $_SESSION['disp_msg'][] = $lang_comb["def_en"]." ".$attackingcharacter->name."";
         }

 	     if(DEBUG>=1) {$_SESSION['disp_msg'][] = "**DEBUG: damage multiplier = ".$damage_multiplier;}
         if($damage_multiplier>0) {
            // Do and show damage to Character
            DEBUG AND $_SESSION['disp_msg'][] = "**DEBUG: char defense_min =  ".$character->defense_min();
			$_SESSION['dmg_to_char'] = roll_damage(root_damage($attackingcharacter->attack_min()*$damage_multiplier) - $character->defense_min()/2 ,root_damage($attackingcharacter->attack_max()*$damage_multiplier) - $character->defense_min() );
            DEBUG AND $_SESSION['disp_msg'][] = "**DEBUG: dmg_to_char = ".$_SESSION['dmg_to_char'];



////TEMPORARY FIX FOR NEW PLAYERS... ---Temporary Bugfix: If userlevel over 9, user looses every battle by dragzone
//if($character->level < 10) {
	$_SESSION['dmg_to_char'] = 0;
//}


            if($_SESSION['dmg_to_char'] <= 0) {$_SESSION['dmg_to_char'] = 1; }

            $character->hit_points = $character->hit_points - $_SESSION['dmg_to_char'];

            $_SESSION['disp_msg'][] = $attackingcharacter->name." ".$lang_comb["hit_for_u"]." ".$_SESSION['dmg_to_char'];

            if($character->hit_points <= 0) {
                $character->hit_points = 0;
                $_SESSION['disp_msg'][] = $attackingcharacter->name." ".$lang_comb["kill_u_man"];

        		//Let NPC Receive Gold
        		$attackingcharacter->gold += (int)($character->gold*.15);
        		$character->gold = (int)($character->gold*.85);  // FIXME: player should really lose ALL gold carrying... this is temp (should set to 0)

        		$attackingcharacter->xp += ((int)($character->max_hp/1)+$attackingcharacter->wisdom)*$_SESSION['fightbonus'];	//   (max_hp/1) for now (NPC's need to gain faster)
        		$character->xp -= (int)($character->xp/100);		// for now, player only looses 1% XP

        		// Update database with win/loss for player/NPC
        		$req=mysql_query("UPDATE phaos_characters SET gold = ".$character->gold.", xp = ".$character->xp." WHERE id = '".$character->id."'");
                if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__); exit;}

        		$req=mysql_query("UPDATE phaos_characters SET gold = ".$attackingcharacter->gold.", xp = ".$attackingcharacter->xp." WHERE id = '".$attackingcharacter->id."'");
                if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__); exit;}

                $attackingcharacter->all_skillsup("",$lang_fun_opp);

                unset($_SESSION['opponent_id']);

        		// when dead, go to Gornath (easy city of undead) to start over
        		if (! mysql_query("UPDATE phaos_characters SET location = 4072 WHERE id = '$PHP_PHAOS_CHARID'") ) {  showError(__FILE__,__LINE__,__FUNCTION__); die; }

                $break_loop= true;
            }

            //Update Character Hit Points in the DataBase
            $query = ("UPDATE phaos_characters SET hit_points = ".$character->hit_points." WHERE id = '$PHP_PHAOS_CHARID'");
            $req = mysql_query($query);
            if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__); exit;}

            //REFRESH SIDEBAR INFO
            $refsidebar= true;

           $attackingcharacter->update_stamina();

            if(@$break_loop){
                break;
            }
         }

        }//hit points>0
      }//end foreach opponent
   }

   $character->update_stamina();
   if( $character->stamina_points<0.25*$character->stamina_points ){
        $refsidebar= true;
   }

   // DRINK POTIONS
   if($comb_act == 'drink_potion') {
         $_SESSION['heal_points'] = $character->drink_potion2($invid);
         $_SESSION['no_heal'] = 0;
         $refsidebar= true;

       	// still in combat?
        if($_SESSION['endcombat'] == true) {	// "yes" means you drink and OPP attacks  --- set to true by dragzone
            jsChangeLocation("combat.php?comb_act=npc_attack&healed");
        } else {					// "no" means you drink, no OPP attack
            jsChangeLocation("combat.php?healed");
        }
   }

    if(@$refsidebar){
        refsidebar();
    }


}//skip actions

?>

<table width=400 border=1 cellspacing=0 cellpadding=5 align=center>
<tr style=background:#006600;>
<td align=center><big><?php echo sayOpponents(); ?></big></td>
</tr>
<?php
	// Opponent Health
	$color="#00FF00";
	$percent=$oppcharacter->hit_points/$oppcharacter->max_hp*100;
	$percent<85  AND $color="#D7D730";
        $percent<55  AND $color="#AA5500";
	$percent<30  AND $color="#FF0000";

    //FIXME: image width/height database needed
	print "
		<tr>
		  <td align=\"center\"><img src='".$oppcharacter->image."' height=160></td>
		</tr>
		<tr>
		  <td>".$lang_comb['opp_heea']." : ".$oppcharacter->hit_points." / ".$oppcharacter->max_hp."</td>
		</tr>
		<tr>
		  <td> <table border=0 cellspacing=0 align=left width=$percent%> <tr> <td BGCOLOR=$color>&nbsp;</td> </tr> </table> </td>
		</tr>
	";

	// Player Health
        $color="#00FF00";
        $percent=$character->hit_points/$character->max_hp*100;
        $percent<85  AND $color="#D7D730";
        $percent<55  AND $color="#AA5500";
        $percent<30  AND $color="#FF0000";

	print "
		<tr>
		  <td>".$lang_comb['ur_heea']." : ".$character->hit_points." / ".$character->max_hp."</td>
		</tr>
		<tr>
		  <td> <table border=0 cellspacing=0 align=left width=$percent%> <tr> <td BGCOLOR=$color>&nbsp;</td> </tr> </table> </td>
   		<tr>
   		</tr>
	";
?>
</td>
</tr>

<?php if(@$_SESSION['opponent_id']&& !@$_SESSION['endcombat']) {
	?>
	<tr>
	<td>
   	<table width="100%" border=0 cellspacing=0 cellpadding=3 align=left>
   	<tr>
   	<td align=center><input type='button' onClick='self.location="combat.php?comb_act=both_attack"'	value='<?php echo $lang_comb["_attt"]; ?>'></td>
   	<td align=center><input type='button' onClick='self.location="combat.php?comb_act=flee"' value='<?php echo $lang_comb["_flee"]; ?>'></td>
   	</tr>
   	</table>
	</td>
	</tr>


	<?php
	// Show available magic
	echo "<tr>
	<td>
   	<table width=\"100%\" border=0 cellspacing=0 cellpadding=3 align=left><tr>";
	// $result=mysql_query("SELECT id,item_id FROM phaos_char_inventory WHERE username = '".$character->user."' AND type='spell_items'");
	$result=mysql_query("SELECT id,item_id,count(item_id) FROM phaos_char_inventory WHERE username = '".$character->user."' AND type='spell_items' group by item_id ");
	if( mysql_num_rows($result) ){
		while(list($id,$item_id,$count) = mysql_fetch_array($result)){

			$result1= mysql_query("SELECT name,image_path,damage_mess FROM phaos_spells_items WHERE id=$item_id ");
			list($description,$image_path,$damage_mess) = mysql_fetch_array($result1);

     			if($damage_mess == 0){ $damage_mess = "effect single";} else {$damage_mess = "effect all";}
			echo "<td align=left><input type='image' src='$image_path' alt='$description--$damage_mess' title='$description--$damage_mess'  onClick='self.location=\"combat.php?comb_act=magic_attack&spellid=$item_id&invid=$id\"'><br>($count)</td>";
		}
	}else{
   		echo "<td align=center>".$lang_comb["no_mag"]."</td>";
	}
	echo "</tr></table>
	</td></tr>";
	?>


	<?php
	// Show available potions
	echo "<tr>
	<td>
   	<table width='100%' border=0 cellspacing=0 cellpadding=3 align=left><tr>";
	$result=mysql_query("SELECT id,item_id,count(item_id) FROM phaos_char_inventory WHERE username = '".$character->user."' AND type='potion' group by item_id ");
	if( mysql_num_rows($result) ){
		while(list($id,$item_id,$count) = mysql_fetch_array($result)){

			$result1= mysql_query("SELECT name,image_path FROM phaos_potion WHERE id=$item_id ");
			list($description,$image_path) = mysql_fetch_array($result1);

			echo "<td align=left><input type='image' src='$image_path' alt='$description' title='$description'  onClick='self.location=\"combat.php?comb_act=drink_potion&item_id=$item_id&invid=$id\"'><br>($count)</td>";
		}
	}else{
   		echo "<td align=center>".$lang_comb["no_pot"]."</td>";
	}
	echo "</tr></table>
	</td></tr>";
	?>



<?php } ?>


<tr>
<td>
   <table width="100%" weight="100%" border=0 cellspacing=0 cellpadding=0 align=center>
   <tr>
   <?php
   if(@$combat_continue){
      //echo "<td align=center><b>(Combat continues...)</b></td>";
      $combat_continue = 0;
   } else {
      $char_first_attack = $character->dexterity+diceroll();
      $opp_first_attack = $oppcharacter->dexterity+diceroll();

      if($char_first_attack <= $opp_first_attack){
          jsChangeLocation("combat.php?comb_act=npc_attack&npcfirstatt");
      } else {
         // You attack first, code
         echo "<td align=center>".$lang_comb["sight_enn"]."</td>";
      }
   }
   echo "</tr>";

   print_msgs(@$_SESSION['disp_msg']);

   unset($_SESSION['disp_msg']);
   ?>
   </tr>
   </table>
</td>
</tr>

<?php
//
// wandering mobs always check for new opponents, the arena does not.
//
if( defined('DEBUG') and DEBUG ){
    $GLOBALS['debugmsgs'][]= "\ncombatlocation=".@$_SESSION['combatlocation'];
    $GLOBALS['debugmsgs'][]= "\ncharfrom=".@$_SESSION['charfrom'];
    $GLOBALS['debugmsgs'][]= "\nopponent id=".@$_SESSION['opponent_id'];
    $GLOBALS['debugmsgs'][]= "\nend of combat detected=".@$_SESSION['endcombat'];
    $GLOBALS['debugmsgs'][]= "\n hp: me ".@$character->hit_points." vs opp ".@$oppcharacter->hit_points;
}

if($character->hit_points>0){
    if( !(@$_SESSION['opponent_id']&& !@$_SESSION['endcombat']) ){
        ?>
        <tr>
        <td align=center><input type='button' onClick='self.location="combat.php?comb_act=nextfight"' value='<?php echo $lang_comb["_conti"]; ?>'></td>
        </tr>
        <?php
    }
}else{//player died
    unset($_SESSION['opponent_id']);
    ?>
    <tr>
    <td align=center><input type='button' onClick='self.location="travel.php"' value='<?php echo $lang_comb["_conti"]; ?>'></td>
    </tr>
    <?php
}

?>
        <tr>
        <td align=center><?php echo @$_SESSION['locationname']; ?></td>
        </tr>
</table>
<p>
<br><br>


<?php
include "trailer.php";
include "footer.php";
?>
