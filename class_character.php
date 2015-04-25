<?php
include_once "items.php";

class character {
	//main variables
 	var $id;
	var $name;
	var $user;
	var $cclass; //instead of Class only!
	var $race;
	var $image;
	var $age;
	var $sex;
	var $location;
	var $location_data;
	//attribute vars
	var $strength;
	var $dexterity;
	var $wisdom;
	var $constitution;
	//changeable vars
	var $hit_points;
	var $stamina_points;
	var $level;
	var $xp;
	var $gold;
	var $stats_points;
	//equipment vars
	var $weapon;
	var $armor;
	var $gloves;
	var $helm;
	var $shield;
	var $boots;
	//vars to be calculated
	var $max_hp;
	var $next_level;
	var $available_points;
	var $max_stamina;
	var $stamina_degrade;
	var $max_rep;
	//other vars
	var $time_since_regen;
	var $stamina_time_since_regen;
	var $rep_time_since_regen;
	var $regen_time;
	var $rep_time;
	var $no_regen_hp;
	//Fighting_Vars
	var $weapon_min;
	var $weapon_max;
	var $weapon_name;
	var $armor_ac;
	var $boots_ac;
	var $gloves_ac;
	var $shield_ac;
	var $helm_ac;
	//trade & inventory
	var $max_inventory;
	// Skills
	var $fight;
	var $defence;
	var $weaponless;
	var $lockpick;
	var $traps;
	// Reputation
	var $rep_points;
	var $rep_helpfull;
	var $rep_generious;
	var $rep_combat;
	//end_of_vars


	function finding() {
		//TODO: finding could be a character skill
 		$finding= $this->wisdom;
 		$fchance= (int)(33.33+6.66*sqrt($finding));//DONT add random effects to modify fchance, this has to be deterministic
 		return $fchance;
 	}

 	function ac() {
 		return $this->armor_ac+$this->boots_ac+$this->gloves_ac+$this->helm_ac+$this->shield_ac;
	}

	function attack_skill_min() {
 		$skill = $this->weapon==0 ? $this->weaponless : $this->fight;
 		$skill+= $this->attack_bonus();
		return fairInt(pow($skill*$this->dexterity,0.66));
 	}

 	function attack_skill_max() {
 		$exp= 0.80;
 		if ($this->weapon==0) {
			return fairInt(pow(($this->strength+$this->dexterity)*$this->weaponless,$exp));
	 	} else {
			return fairInt(pow(($this->strength+$this->dexterity)*$this->fight,$exp));
	 	}
	}

	function defense_max() {
		return $this->defense_min()+fairInt(sqrt($this->dexterity*($this->defence+1)));
	}

	function defense_min() {
		return $this->ac()+$this->weaponless+$this->defense_bonus();
	}

	function defense_skill_max() {
		return $this->defense_min()+fairInt(sqrt($this->dexterity*($this->defence+1)));
	}

	function defense_skill_min() {
		return fairInt(sqrt($this->ac())+$this->weaponless+$this->defense_bonus());
	}

	function attack_min() {
		if ($this->weapon==0) {
			return $this->weaponless*$this->strength;
		} else {
			return $this->weapon_min*$this->strength;
		}
	}

	function attack_max() {
		if ($this->weapon==0) {
			$skill= $this->weaponless;
		} else {
			$skill= $this->weapon_max+$this->fight;
		}
		return fairInt( ($this->strength+$this->dexterity)*$skill );//TODO: change phaos_opponents and use sqrt(dex) here
	}

	function fight_reduction() {
		$factor= $this->stamina_points/$this->max_stamina;
		if($factor> 0.66) {
			return 1;
		} elseif($factor<0.33) {
			return 0.33;
		} else {
			return 0.33+($factor-0.33)*2;
		}
	}

	function attack_roll($comb_act,$test=false) {
		if(DEBUG) {
			if($comb_act == "magic_attack") {
				$_SESSION['disp_msg'][] = "**DEBUG: wisdom: ".$this->wisdom;
			} else {
 				$_SESSION['disp_msg'][] = "**DEBUG: strength: $this->strength";
				$_SESSION['disp_msg'][] = "**DEBUG: dexterity: $this->dexterity";
				$_SESSION['disp_msg'][] = "**DEBUG: fight: $this->fight";
				$_SESSION['disp_msg'][] = "**DEBUG: weapon name: $this->weapon_name";
				$_SESSION['disp_msg'][] = "**DEBUG: weapon: $this->weapon_max";
				$_SESSION['disp_msg'][] = "**DEBUG: weaponless: $this->weaponless";
				$_SESSION['disp_msg'][] = "**DEBUG: attack_skill_min: ".$this->attack_skill_min();
				$_SESSION['disp_msg'][] = "**DEBUG: attack_skill_max: ".$this->attack_skill_max();
				$_SESSION['disp_msg'][] = "**DEBUG: attack_min: ".$this->attack_min();
				$_SESSION['disp_msg'][] = "**DEBUG: attack_max: ".$this->attack_max();
			}
		}

		$rollthedice = diceroll();
		if(DEBUG>=2) {$_SESSION['disp_msg'][] = "**DEBUG: dice roll = $rollthedice";}
		if ($comb_act == "magic_attack") {
			$char_attack = round(($this->wisdom+$rollthedice)/2);
		} else {
			$fight_reduction= $this->fight_reduction();
			if(DEBUG>=1) {
				$_SESSION['disp_msg'][] = "**DEBUG: fight reduction = $fight_reduction";}
				$char_attack = round(($this->dexterity+rand(
                    (int)($this->attack_skill_min()*$fight_reduction),
                    (int)($this->attack_skill_max()*$fight_reduction)
                )
                +$rollthedice)/8);
		}
        if(!$test){
            if($this->stamina_points>0){
                --$this->stamina_points;
            }
        }
        return $char_attack;
    }

    function defence_roll($comb_act,$test=false){
		$rollthedice = diceroll();
		if(DEBUG>=2) {$_SESSION['disp_msg'][] = "**DEBUG: dice roll = $rollthedice";}
		if ($comb_act == "magic_attack") {
			$char_defence= (int)(($this->wisdom+$rollthedice)/2+rand(0,99)*0.01);
		} else {
           if(DEBUG>=2) {$_SESSION['disp_msg'][] = "**DEBUG: defense_min = ".($this->defense_min());}
            $char_def = rand($this->defense_skill_min(),$this->defense_skill_max());
			$char_defence=  (int)(($this->dexterity+$char_def+$rollthedice)/10 +rand(0,99)*0.01 );
		}
        if(!$test){
            if($this->stamina_points>0){
                --$this->stamina_points;
            }
        }
        return $char_defence;
    }

    //Update Character Stamina Points in the DataBase
    function update_stamina(){
        if($this->stamina_points>$this->max_stamina){
            $this->stamina_points= $this->max_stamina;
        }
        $sql = "UPDATE phaos_characters SET stamina = ".$this->stamina_points." WHERE id=$this->id";
        $res = mysql_query($sql);
        if(!$res){ showError(__FILE__,__LINE__,__FUNCTION__); exit; };
    }

    function attack_bonus() {
        if($this->location_data){
            if( 'Elf' == $this->race && stristr($this->location_data['name'],'Woodland') !== false ){
                return 1+fairInt($this->level*0.05);
            }
        }
    }

    function defense_bonus() {
        if($this->location_data){
            if( 'Elf' == $this->race && stristr($this->location_data['name'],'Woodland') !== false ){
                return 1+fairInt($this->level*0.1);
            }
        }
    }

	/**
    * constructor
	* @param character id
	*/
	function character($id){
		$result = mysql_query ("SELECT * FROM phaos_characters WHERE id = '$id'");

		if ($row = mysql_fetch_array($result)) {
			//define main vars
			$this->id=$row["id"];
			$this->name=$row["name"];
			$this->user=$row["username"];
			$this->cclass=$row["class"];
			$this->race=$row["race"];
			$this->sex=$row["sex"];
			$this->image=$row["image_path"];
			$this->age=$row["age"];
			$this->location=$row["location"];
			//define attribute vars
			$this->strength = $row["strength"];
			$this->dexterity = $row["dexterity"];
			$this->wisdom = $row["wisdom"];
			$this->constitution = $row["constitution"];
			//define changeable vars
			$this->hit_points = $row["hit_points"];
			$this->stamina_points=$row["stamina"];
			if($row['level'] == 0 OR $row['level'] == "") {
				$this->level = 1;
			} else {
				$this->level = (int)$row['level'];
			}
			$this->xp = (int)$row["xp"];
			$this->gold = $row["gold"];
			$this->stat_points = $row["stat_points"];
			//define equipment vars
			$this->weapon = $row["weapon"];
			$this->armor = $row["armor"];
			$this->boots = $row["boots"];
			$this->shield = $row["shield"];
			$this->gloves = $row["gloves"];
			$this->helm = $row["helm"];

            //FIX
            //at some point during development some characters had negative strength
            //this should not happen usually
            if($this->constitution<1){
                $this->constitution=1;
            }
            if($this->strength<1){
                $this->strength=1;
            }

			//calculated stuff
			$this->available_points = $this->strength+$this->dexterity+$this->wisdom+$this->constitution;
			$this->max_hp = $this->constitution*6;
			$this->max_stamina = ($this->constitution+$this->strength)*10;


			$this->max_rep = 7;
			//other stuff
			$this->regen_time = $row["regen_time"];
			$this->stamina_time = $row["stamina_time"];
			$this->rep_time = $row["rep_time"];
			$this->no_regen_hp = $row["hit_points"];
			//regeneration
			$actTime=time();
			$this->time_since_regen = $actTime-$this->regen_time;
			$this->stamina_time_since_regen = $actTime-$this->stamina_time;
			$this->rep_time_since_regen = $actTime-$this->rep_time;
			//skills
			$this->fight = $row["fight"];
			$this->defence = $row["defence"];
			$this->weaponless = $row["weaponless"];
			$this->lockpick = $row["lockpick"];
			$this->traps = $row["traps"];
			//reputation
			$this->rep_points = $row["rep_points"];
			$this->rep_helpfull = $row["rep_helpfull"];
			$this->rep_generious = $row["rep_generious"];
			$this->rep_combat = $row["rep_combat"];
			//weapon & fight Calculation
			//fill weapon:
			$result = mysql_query ("SELECT * FROM phaos_weapons WHERE id = '".$this->weapon."'");
			if ($row = mysql_fetch_array($result)) {
				$this->weapon_min = $row["min_damage"];
				$this->weapon_max = $row["max_damage"];
				$this->weapon_name = $row["name"];
			} else {
                $this->weapon = 0;
				$this->weapon_min = 0;
				$this->weapon_max = 1;
				$this->weapon_name = 'Bare Hands';
			}
			//fill armor
			$result = mysql_query ("SELECT * FROM phaos_armor WHERE id = '".$this->armor."'");
				if ($row = mysql_fetch_array($result)) {
					$this->armor_ac = $row["armor_class"];
				} else {
					$this->armor_ac = 0;
			}
			$result = mysql_query ("SELECT * FROM phaos_boots WHERE id = '".$this->boots."'");
				if ($row = mysql_fetch_array($result)) {
					$this->boots_ac = $row["armor_class"];
				} else {
					$this->boots_ac = 0;
			}
			$result = mysql_query ("SELECT * FROM phaos_gloves WHERE id = '".$this->gloves."'");
				if ($row = mysql_fetch_array($result)) {
					$this->gloves_ac = $row["armor_class"];
				} else {
					$this->gloves_ac = 0;
			}
			$result = mysql_query ("SELECT * FROM phaos_shields WHERE id = '".$this->shield."'");
			if ($row = mysql_fetch_array($result)) {
				$this->shield_ac = $row["armor_class"];
			} else {
				$this->shield_ac = 0;
			}
			$result = mysql_query ("SELECT * FROM phaos_helmets WHERE id = '".$this->helm."'");
			if ($row = mysql_fetch_array($result)) {
				$this->helm_ac = $row["armor_class"];
			} else {
				$this->helm_ac = 0;
			}
			$this->max_inventory=$this->strength*5;

		} else {
			global $lang_na;
			$this->name = $lang_na;
			$this->strength = $lang_na;
			$this->dexterity = $lang_na;
			$this->wisdom = $lang_na;
			$this->constitution = $lang_na;
			$this->hit_points = $lang_na;
			$this->max_hp = $lang_na;
			$this->weapon = $lang_na;
			$this->armor = $lang_na;
			$this->boots = $lang_na;
			$this->shield = $lang_na;
			$this->gloves = $lang_na;
			$this->helm = $lang_na;
			$this->level = $lang_na;
			$this->next_lev_xp = $lang_na;
			$this->xp = $lang_na;
			$this->gold = $lang_na;
			$this->available_points = $lang_na;
		}

    	if( !$this->image ) {
            if($this->user=='phaos_npc') {
                $this->image = "images/monster/forest_troll.gif";
            }else{
                $this->image = "images/icons/characters/character_1.gif";
            }
        }

        //get location to be able to have location modifiers
        $this->location_data= fetch_first("select * from phaos_locations where id='$this->location'");
        //FIXME: since characters now have location data, many places in the code don't need to fetch it.
	}



		function auto_heal()
		/**
		* heals the character, if he is bellow maxhealth (and an given time has been exceeded)
		*/
			{
				$result = @mysql_query('SELECT * FROM phaos_races WHERE name = \''.$this->race.'\'');
				$data = @mysql_fetch_assoc($result);
				if($this->time_since_regen >= $data['healing_time'] && $data['healing_rate'] > 0)
					{
						$char_regen_hp = $this->hit_points+(int)($this->time_since_regen/$data['healing_rate']);
						if ($this->hit_points < $this->max_hp){
							if($char_regen_hp > $this->max_hp){
								$char_regen_hp = $this->max_hp;
							}
							$query = ("UPDATE phaos_characters SET hit_points = '$char_regen_hp', regen_time = '".time()."'WHERE id = '".$this->id."'");
							$req = @mysql_query($query);
							$this->hit_points=$char_regen_hp;
						}else{
							$query = ("UPDATE phaos_characters SET regen_time = '".time()."'WHERE id = '".$this->id."'");
							$req = @mysql_query($query);
						}
					}
			}
		function auto_stamina ()
		/**
		* regens the character stamina, if he is below max_stamina (and an given time has been exceeded)
		*/
			{
				$result = mysql_query('SELECT * FROM phaos_races WHERE name = \''.$this->race.'\'');
				$data = mysql_fetch_assoc($result);
				if($this->stamina_time_since_regen >= $data['stamina_regen_time'] && $data['stamina_regen_rate'] > 0)
					{
                        //recover less during a fight, but leave the player a little opportunity to cheat in combat by waiting
                        $rate= @$_SESSION['opponent_id']?0.025:0.25;
                        $delta = fairInt(($this->stamina_time_since_regen/$data['stamina_regen_rate'])*(sqrt($this->constitution)*$rate+rand(0,99)*0.01));
						if ($delta>0 && $this->stamina_points<$this->max_stamina)
							{
    						    $char_regen = $this->stamina_points+ $delta;
                                //defined('DEBUG') and DEBUG and print "updating $this->name from $this->stamina_points to $char_regen, max $this->max_stamina<br>";
								if($char_regen > $this->max_stamina)
									{
										$char_regen = $this->max_stamina;
									}

								$query = ("UPDATE phaos_characters SET stamina = '$char_regen', stamina_time = '".time()."'WHERE id = '".$this->id."'");
								$req = mysql_query($query);
								$this->stamina_points=$char_regen;
							}
							else
								{
									$query = ("UPDATE phaos_characters SET stamina_time = '".time()."'WHERE id = '".$this->id."'");
									$req = mysql_query($query);
								}
					}
			}

	/**
	* regens the character reputation points to distribute, if a given time has been exceeded.
	*/
	function auto_reputation(){
		//There are 86400 seconds in 24 hours
		if($this->rep_time_since_regen >= 86400) {
			//Add 1 rep point every 24 hours
			$rep_regen = $this->rep_points+(INT)($this->rep_time_since_regen/86400);
			if ($this->rep_points < $this->max_rep){
				if($rep_regen > $this->max_rep) {$rep_regen = $this->max_rep;}
				$query = ("UPDATE phaos_characters SET rep_points = '$rep_regen', rep_time = '".time()."' WHERE id = '".$this->id."'");
				$req = mysql_query($query);
				$this->rep_points=$rep_regen;
				if (!$req) {showError(__FILE__,__LINE__,__FUNCTION__); exit;}

			}	else {
				//print("Rep points at max");
				$this->rep_points = $this->max_rep;
				$query = ("UPDATE phaos_characters SET rep_points='$this->rep_points', rep_time = '".time()."' WHERE id = '".$this->id."'");
				$req = mysql_query($query);
				if (!$req) {showError(__FILE__,__LINE__,__FUNCTION__); exit;}
			}
		}
	}

	/**
	* drinks the fist found potion in the inventory
	*/
	function drink_potion(){
		// FIXME: this just gets the first potion from inventory!!  Not a specific potion!
		$result = mysql_query ("SELECT * FROM phaos_char_inventory WHERE username = '".$this->user."' AND type = 'potion'");
		if ($row = mysql_fetch_array($result)) {
			$potion_id = $row["item_id"];
			$inv_id = $row["id"];
			$result = mysql_query ("SELECT * FROM phaos_potion WHERE id = '$potion_id'");
			if ($row = mysql_fetch_array($result)) {	// if it's a valid potion
				list($effect,$details) = split(' ',$row["effect"]);	// determine potion effect
				if ($effect == "heal") {
					$heal_amount=$details;
					$new_hp_amount = $this->hit_points + $heal_amount;
					if($new_hp_amount > $this->max_hp) {$new_hp_amount = $this->max_hp;}

					$query = ("UPDATE phaos_characters SET hit_points = $new_hp_amount WHERE id = '".$this->id."'");
					$req=mysql_query($query);
	    				if (!$req) {showError(__FILE__,__LINE__,__FUNCTION__); exit;}
					$query = "DELETE FROM phaos_char_inventory WHERE id = '$inv_id'";
					$result = mysql_query($query) or die ("Error in query: $query. " . mysql_error());
					$this->hit_points=$new_hp_amount;
				}
			}
		}
	}


	/**
	* drinks a given Potion of the inventory
	* @param $Inv_ID identifies the inventory-slot
	*/
	function drink_potion2($Inv_ID) {
		$charID = $this->id;
		$presult = mysql_query ("SELECT * FROM phaos_char_inventory WHERE id='$Inv_ID'");
		if ($pot = mysql_fetch_array($presult)) {
			$potion_id = $pot["item_id"];
			$inv_id = $pot["id"];
			$result = mysql_query ("SELECT * FROM phaos_potion WHERE id = '$potion_id'");
			if ($row = mysql_fetch_array($result)) {
				list($effect,$details) = split(' ',$row["effect"]);     // determine potion effect
				if ($effect == "heal" or $effect == "stamina") {
					$current =& $this->hit_points;		// pointer to hit_points value
					$max=$this->max_hp;
					$min=0;
					$dbfield="hit_points";
					$sayfield="hit points";
				}
				if ($effect == "stamina") {
					$current =& $this->stamina_points;	// pointer to stamina value
					$max=$this->max_stamina;
					$min=0;
					$dbfield="stamina";
					$sayfield="stamina";
				}
				if(@$dbfield) {
					$add=$details;
					if($current+$add > $max) {$add = $max-$current;}
					if($current+$add < $min) {$add = $min-$current;}
					$current += $add;	// updates EITHER $this->hit_points or $this->stamina depending on pointer

					$sql="UPDATE phaos_characters SET $dbfield = $current WHERE id = $charID";
					mysql_query($sql) OR die("<B>Error ".mysql_errno()." :</B> full Query: $sql Mysql-error:".mysql_error()."");
					$sql = "DELETE FROM phaos_char_inventory WHERE id = $inv_id";
					mysql_query($sql) OR die ("Error in query: $query. ".mysql_error());
					return "You gain $add $sayfield";
				} else {
					return "The potion has no known effects";
				}
			}
		}
	}


	/**
	* (string)$attribute -> Attribute collumn of the char that has to be increesed
	* @return Returns 0 on failure and 1 on all done successfully. (mainly SQL-errors!!)
	*/
	function level_up($attribute){
		$query = ("UPDATE phaos_characters SET stat_points = stat_points-1, $attribute = $attribute+1 WHERE id = '".$this->id."'");
		$req = mysql_query($query);
		if (!$req) {return(0);}
		else {return(1);}
	}

	/**
	* @return returns character Level
	*/
	function level(){
		return $this->level;
	}

	/**
	* @return returns character available_points
	*/
	function available_points(){
		return $this->available_points;
	}

	/**
    * kill a character
	* @return Returns 0 on failure and 1 on all done successfully. (mainly SQL-errors!!)
	*/
	function kill_character(){
		$query = "DELETE FROM phaos_characters WHERE username = '".$this->user."'";
		$result = mysql_query($query) or die ("Error in query: $query. " .
		mysql_error());

		$query = "DELETE FROM phaos_char_inventory WHERE username = '".$this->user."'";
		$result = mysql_query($query) or die ("Error in query: $query. " .
		mysql_error());
		return 1;
	}

    /**
    * @param (ID) ID character number to identify the desired character
    * @return Returns 0 on failure and 1 on all done successfully. (mainly SQL-errors!!)
    */
    function kill_characterid(){
            $query = "DELETE FROM phaos_characters WHERE id = '".$this->id."'";
            $result = mysql_query($query) or die ("Error in query: $query. " . mysql_error());

		// FIXME: DBS - this should be changed so items are dropped at location
            $query = "DELETE FROM phaos_char_inventory WHERE username = '".$this->user."'";
            $result = mysql_query($query) or die ("Error in query: $query. " . mysql_error());
            return 1;
    }

	/**
    * check whether an item is equipped
	* @param (string)$item_type - choose the item type as string
	* @param (string)$item_id - the ID of the includet object
	* @return boolean
	*/
	function equipped($item_type,$item_id){
		if ($item_type=="armor"){return $this->armor==$item_id;}
		if ($item_type=="weapon"){return $this->weapon==$item_id;}
		if ($item_type=="gloves"){return $this->gloves==$item_id;}
		if ($item_type=="helm"){return $this->helm==$item_id;}
		if ($item_type=="shield"){return $this->shield==$item_id;}
		if ($item_type=="boots"){return $this->boots==$item_id;}

		return false;
	}

	/**
    * equip an item
	* @param (string)$item_type - choose the item type as string
	* @param (string)$item_id - the ID of the includet object
	* @return Returns 0 on failure and 1 on all done successfully. (mainly SQL-errors!!)
	*/
	function equipt($item_type,$item_id){
		$query = ("UPDATE phaos_characters SET $item_type = '$item_id' WHERE id = '".$this->id."'");
		$req = mysql_query($query);
		if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__); return 0; exit;}
		if ($item_type=="armor"){$this->armor=$item_id;}
		if ($item_type=="weapon"){$this->weapon=$item_id;}
		if ($item_type=="gloves"){$this->gloves=$item_id;}
		if ($item_type=="helm"){$this->helm=$item_id;}
		if ($item_type=="shield"){$this->shield=$item_id;}
		if ($item_type=="boots"){$this->boots=$item_id;}

		return 1;
	}

	/**
	* @param (string)$item_type - choose the item type as string
	* @return Returns 0 on failure and 1 on all done successfully. (mainly SQL-errors!!)
	*/
	function unequipt($item_type){
		switch($item_type){
            case "armor":
                $this->armor='';
                break;
            case "weapon":
                $this->weapon='';
                break;
            case "gloves":
                $this->gloves='';
                break;
            case "helm":
                $this->helm='';
                break;
            case "shield":
                $this->shield='';
                break;
            case "boots":
                $this->boots='';
                break;
            default:
                return 0;
        }

		$query = ("UPDATE phaos_characters SET $item_type = '' WHERE id = '".$this->id."'");
		$req = mysql_query($query);
		if (!$req)  {showError(__FILE__,__LINE__,__FUNCTION__); return 0; exit;}
		return 1;
	}

	/**
    * Check whether the player still owns the items he has equipped
	* @param (string)$item_type - choose the item type as string
	* @param (string)$item_id   - choose the item id  as string
	* @return 1 on failure and 0 on all done successfully. (mainly SQL-errors!!)
	* system function to check the equipped items (do not use in public)
	*/
	function checkequipped($item_type,$item_id){
		if ($item_id=='' OR $item_id=='0' OR $item_id=='N/A'){ return(0); }

		$res=mysql_query("SELECT * FROM phaos_char_inventory WHERE username = '".$this->user."' AND item_id = '$item_id' AND type = '$item_type'");
		if ($row = mysql_fetch_array($res)) {
			return 0;
		} else {
            $this->unequipt($item_type);
			return 1;
		}
	}

	/**
	* @return the number of failures during check if "0" then all done correctly
	* Purpose: Usercalled function to check the equipped items
	*/
	function checkequipment(){
		$c1=$this->checkequipped("armor",$this->armor);
		$c1+=$this->checkequipped("weapon",$this->weapon);

		$c1+=$this->checkequipped("boots",$this->boots);
		$c1+=$this->checkequipped("shield",$this->shield);
		$c1+=$this->checkequipped("helm",$this->helm);
		$c1+=$this->checkequipped("gloves",$this->gloves);
		return $c1;
	}

	function get_eq_item_name($item_type){
		global $lang_na;
		if ($item_type=="armor"){$result = mysql_query ("SELECT * FROM phaos_armor WHERE id = '".$this->armor."'");}
		if ($item_type=="weapon"){$result = mysql_query ("SELECT * FROM phaos_weapons WHERE id = '".$this->weapon."'");}
		if ($item_type=="gloves"){$result = mysql_query ("SELECT * FROM phaos_gloves WHERE id = '".$this->gloves."'");}
		if ($item_type=="helm"){$result = mysql_query ("SELECT * FROM phaos_helmets WHERE id = '".$this->helm."'");}
		if ($item_type=="shield"){$result = mysql_query ("SELECT * FROM phaos_shields WHERE id = '".$this->shield."'");}
		if ($item_type=="boots"){$result = mysql_query ("SELECT * FROM phaos_boots WHERE id = '".$this->boots."'");}
		if ($row = mysql_fetch_array($result)) {
			return ($row["name"]);
		} else {return $lang_na;}

	}
	function get_eq_item_pic($item_type){
		global $lang_na;
		if ($item_type=="armor"){$result = mysql_query ("SELECT * FROM phaos_armor WHERE id = '".$this->armor."'");}
		if ($item_type=="weapons"){$result = mysql_query ("SELECT * FROM phaos_weapons WHERE id = '".$this->weapon."'");}
		if ($item_type=="gloves"){$result = mysql_query ("SELECT * FROM phaos_gloves WHERE id = '".$this->gloves."'");}
		if ($item_type=="helms"){$result = mysql_query ("SELECT * FROM phaos_helmets WHERE id = '".$this->helm."'");}
		if ($item_type=="shields"){$result = mysql_query ("SELECT * FROM phaos_shields WHERE id = '".$this->shield."'");}
		if ($item_type=="boots"){$result = mysql_query ("SELECT * FROM phaos_boots WHERE id = '".$this->boots."'");}
		if ($row = mysql_fetch_array($result)) {
			return ($row["image_path"]);
		} else {return 'images/icons/'.$item_type.'/na.gif';}

	}
	function reduce_stamina($ammount){
		$def=$this->stamina_points;
		$this->stamina_points=$def-$ammount;
				$query = ("UPDATE phaos_characters SET stamina = '".$this->stamina_points."'
							   WHERE id = '".$this->id."'");
				$req = mysql_query($query);
	}

	//shop functions of Character
	function pay($amount){
		// FIXME: this function should also take a payee character ID and add gold to them
		if($amount <= $this->gold) {
			//echo "paying:".$amount;
			$this->gold = $this->gold-$amount;
			$req = mysql_query("UPDATE phaos_characters SET gold = ".$this->gold." WHERE id = ".$this->id);
			if (!$req) {showError(__FILE__,__LINE__,__FUNCTION__); exit;}
			return 1;
		}	else {
			return 0;
		}
	}

	function invent_count(){
		$result 	 = mysql_query("SELECT * FROM phaos_char_inventory WHERE username = '".$this->user."'");
		$inv_count = mysql_num_rows($result);
		return $inv_count;
	}

	function add_item($item_id,$item_type){
		$query = "INSERT INTO phaos_char_inventory
				(username,item_id,type)
				VALUES
				('".$this->user."','$item_id','$item_type')";
		$req = mysql_query($query);
		if (!$req) {showError(__FILE__,__LINE__,__FUNCTION__); exit;}
		return 1;
	}

	function remove_item($item_id,$item_type){
		$query = "select id FROM phaos_char_inventory
				WHERE username='$this->user'
                   AND item_id='$item_id'
                   AND type='$item_type'";
        $id= fetch_value($query,__FILE__,__LINE__,__FUNCTION__);
        if($id){
    		$req = mysql_query("delete from phaos_char_inventory where id=$id");
    		if (!$req) {showError(__FILE__,__LINE__,__FUNCTION__); exit;}
    		return 1;
        }else{
            return 0;
        }
	}

    //
    // pickup one or more items from the ground - includes gold.
    // the actual change of the ground happens elsewhere
    //
    function pickup_item($item){
        $pickedup= 0;
        if($item['type']=="gold") {
            $req = mysql_query("update phaos_characters set gold=gold+$item[number] where id='$this->id'");
            if (!$req) {showError(__FILE__,__LINE__,__FUNCTION__); exit;}
            $pickedup+= 1;
        }else{
            for($i=0;$i<$item['number'];++$i){
                $pickedup+= $this->add_item($item['id'],$item['type']);                
            }
        }
        return $pickedup;
    }

    //
    // drop one or more items to the ground - includes gold.
    // the actual change of the ground happens elsewhere
    //
    function drop_item($item){
        $location= $this->location;
        $dropped= 0;
        if($item['type']=="gold") {
            $req = mysql_query("update phaos_characters set gold=gold-$item[number] where id='$this->id'");
            if (!$req) {showError(__FILE__,__LINE__,__FUNCTION__); exit;}
            $dropped+= $item['number'];
        }else{
            for($i=0;$i<$item['number'];++$i){
                $dropped+= $this->remove_item($item['id'],$item['type']);
            }
        }
        return $dropped;
    }

	// skill Raising and lowering
	function skillup($skillname){
        if($skillname == 'wisdom') {
    		$total=$this->fight+$this->defence+$this->weaponless+$this->lockpick+$this->traps+$this->wisdom;
            $wisdomoffset= 4+$this->level*2;
        }else{
    		$total=$this->fight+$this->defence+$this->weaponless+$this->lockpick+$this->traps;
            $wisdomoffset= 0; 
       }

        $chance= 10;
        if($this->race=='Gnome' && $skillname=='wisdom'){
            $total-= 1;
            $chance= 15;
        }

		if ($total<(10+$this->level+$wisdomoffset)){
			$rnd=rand(1,100); //normaly set to 100!
			if ($rnd<$chance){
				$query="update phaos_characters set $skillname=$skillname+1 where id='".$this->id."';";
				$exec=mysql_query($query);
				return(1);
			}
		}
		else{
			return(0);
		}
	}
		function skilldown(){
			$rnd=rand(1,100); //normaly set to 100!
			if ($rnd<5+($this->level/10)){
			$rnd2=rand(1,5);
			switch ($rnd2){
				case 1 : $skillname="fight";
									if ($this->fight<=1){ $exec=0;}
									else {$exec=1;}
									break;
				case 2 : $skillname="defence";
									if ($this->defence<=1){ $exec=0;}
									else {$exec=1;}
									break;
				case 3 : $skillname="weaponless";
									if ($this->weaponless<=1){ $exec=0;}
									else {$exec=1;}
									break;
				case 4 : $skillname="lockpick";
									if ($this->lockpick<=1){ $exec=0;}
									else {$exec=1;}
									break;
				case 5 : $skillname="trap";
									if ($this->trap<=1){ $exec=0;}
									else {$exec=1;}
									break;
				}
				if ($exec==1){
					$query="update phaos_characters set $skillname=$skillname-1 where id='".$this->id."';";
					$exec=mysql_query($query);
					echo mysql_error(); //debug row
					return(1);
				}
		}
		else{
			return(0);
		}
	}
	function inv_skillmatch(){
        $error= 0;
		 $wstr=$this->weapon_min+$this->weapon_max;
		 if ($wstr>($this->fight*3)+10){
	 			$this->unequipt("weapon");
				$error++;
		 }
		 if ($this->armor_ac>($this->defence*3)+10){
	 			$this->unequipt("armor");
				$error++;
		 }
		 if ($this->helm_ac>($this->defence)){
	 			$this->unequipt("helm");
				$error++;
		 }
		 if ($this->boots_ac>($this->defence)){
	 			$this->unequipt("boots");
				$error++;
		 }
		 if ($this->gloves_ac>($this->defence)){
	 			$this->unequipt("gloves");
				$error++;
		 }
		 if ($this->shield_ac>($this->defence)){
	 			$this->unequipt("shield");
				$error++;
		 }
		 return($error);
	}

    //place a character on the map
    // @optional $locationid the new location of the character
    // no argument = just save character information using the current location.
    function place($locationid=-1) {
        if($this->id>0) {
            $idkey= 'id,';
            $idvalue= "$this->id,";
            $auto_increment_id= false;
        }else{
            $idkey= "";
            $idvalue= "";
            //we use mysqls AUTO_INCREMENT feature
            $auto_increment_id= true;
        }
        if($locationid>=0){
            $this->location= $locationid;
        }
        $query = "REPLACE INTO phaos_characters
        (  $idkey location,image_path,username,name,age,strength,dexterity,wisdom,constitution,hit_points,race,class,sex,gold,fight,defence,weaponless,lockpick,traps
         , weapon,xp,level,armor,stat_points,boots,gloves,helm,shield,regen_time,stamina,stamina_time,rep_time,rep_points,rep_helpfull,rep_generious,rep_combat
        )
        VALUES
        (
           $idvalue '$this->location','$this->image','$this->user','$this->name','$this->age','$this->strength','$this->dexterity','$this->wisdom','$this->constitution','$this->hit_points','$this->race','$this->cclass','$this->sex',$this->gold
         , $this->fight,$this->defence,$this->weaponless,$this->lockpick,$this->traps
         , $this->weapon,$this->xp,$this->level,$this->armor,$this->stat_points,$this->boots,$this->gloves,$this->helm,$this->shield,$this->regen_time,$this->stamina_points,$this->stamina_time,$this->rep_time,$this->rep_points,$this->rep_helpfull,$this->rep_generious,$this->rep_combat
        )";

        $req = mysql_query($query);
        if (!$req) {showError(__FILE__,__LINE__,__FUNCTION__); exit;}

        if( $auto_increment_id ){
            $query= "select id from phaos_characters where location='$this->location' order by id desc LIMIT 1";
            $req = mysql_query($query);
            if (!$req) {showError(__FILE__,__LINE__,__FUNCTION__); exit;}
            $row= mysql_fetch_assoc($req);
            if($row) {
                $this->id= $row['id'];
            }
        }
        defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][]= " placed $this->name(id:$this->id, user:$this->user)";
    }

    function all_skillsup($action,$lang_fun) {
    	if ($action == "magic_attack") {
			$ret=$this->skillup("wisdom");
			if ($ret==1){ $_SESSION['disp_msg'][] = $lang_fun["gai_wis"]; }
		} else {
			if ($this->weapon==0){
			$ret=$this->skillup("weaponless");
			if ($ret==1){ $_SESSION['disp_msg'][] = $lang_fun["gai_wep"]; }
			} else {
    			$ret=$this->skillup("fight");
        		if ($ret==1){ $_SESSION['disp_msg'][]= $lang_fun["gai_att"]; }
    			$ret=$this->skillup("defence");
    			if ($ret==1){ $_SESSION['disp_msg'][]= $lang_fun["gai_def"]; }
			}
    	}
    }

    //FIXME: there should be a separate field in the database for characterrole, since right now it abuses the username
    function setRole($role){
        $this->username= $role;
    }

    function is_npc(){
        return strpos($this->user,'phaos_') === 0 ;
    }

    //TODO: ghosts, and flying creatures might be able to pass where others cannot
    function sql_may_pass() {

        //HACK
        //determine whether the npc is somewhere we he is not supposed to be and maybe walled in. Allow him to move to fix that.
        $condition_pass = "pass='y'";
		$result = mysql_query ("SELECT id, buildings, pass FROM phaos_locations WHERE id=$this->location AND $condition_pass");
        $position_ok = mysql_num_rows($result)>0;
        if(!$position_ok){
            return '1=1';
        }

        return $this->real_sql_may_pass();
    }

    //TODO: ghosts, and flying creatures might be able to pass where others cannot
    function real_sql_may_pass() {

        if( $this->is_npc() ) {
            return "( buildings='n' AND pass='y' )";
        }else{
            return "pass='y'";
        }
    }


    function relocate($direction){
        global $npc_dir_map;

        $condition_pass = $this->sql_may_pass();

		// move to new map location
        $npc_dir= $npc_dir_map[intval($direction)];

        $query= "SELECT `$npc_dir` FROM phaos_locations WHERE id=".$this->location;
        //defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][]= " relocate querying: ".$query;
		$result = mysql_query ($query);
		if ( (@list($newloc)=mysql_fetch_array($result)) && $newloc ) {
			// get and set the new location for the NPC

			$result = mysql_query ("SELECT id, buildings, pass FROM phaos_locations WHERE id=$newloc AND $condition_pass");
                if ($row = mysql_fetch_array($result)) {
                    /*OLD v0.89
    				if ($row["buildings"]=="n" && $row["pass"]=='y') {
                    */
    					$this->location = $newloc;
                		// now we can update the DB
                        $query= "UPDATE phaos_characters SET
                			location	=".$this->location."
                			WHERE id	='".$this->id."'";
                		$result = mysql_query($query);
                		if (!$result) {showError(__FILE__,__LINE__,__FUNCTION__); exit;}
                        defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][]= " moving $this->name($this->id) to $newloc";
                        return true;
                    /*OLD v0.89
    				}else{
                        defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][]= " moving $this->name($this->id) to $newloc was <b>blocked</b>";
                    }
                    */
    			} else {
                    defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][]= " moving $this->name($this->id) to $newloc was <b>blocked</b>";
    				//die("why could we not select data for the NPC's NEW location ($newloc/ID:".$row["id"].")?");
    			}
		}else{
            defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][]= " no adjacent square in direction $npc_dir at $this->location";
        }
        return false;
    }

}// end class character

//the blueprints suck atm, but I try to make the better of it
class np_character_from_blueprint extends character {
	function np_character_from_blueprint($blueprint,$level=1,$username='phaos_npc'){
        $this->level= intval($level);
        if($level<0){
            $level= 1;
            echo "<p>bad level input for npc!</p>";
        }

		//define main vars
		$this->id= -1;
		$this->name=$blueprint["name"];
		$this->user= $username;
		$this->cclass=$blueprint["class"];
		$this->race=$blueprint["race"];
		$this->sex= rand(0,1)?'Female':'Male';
		$this->image=$blueprint["image_path"];
		$this->age=$this->level*$this->level;
		$this->location= 0;
		//define attribute vars
		$this->strength = (int)($blueprint["min_damage"]+3*($this->level-1));
    	$this->dexterity = (int)($blueprint["max_damage"]-$blueprint["min_damage"]+2*$this->level+2);
        $this->wisdom = (int)($blueprint["xp_given"]/2+$this->level);
        //define changeable vars( well except constitution )
        $this->hit_points = $blueprint["hit_points"]+rand(0,$this->level*3);
        $this->constitution = (int)(($this->hit_points+10)/6);
        $this->stamina_points= ($this->constitution+$this->strength)*5;
        $this->level = $this->level;

        //This are the most significant changes from 0.90
        $ac_left= fairInt( $blueprint['AC']*sqrt($this->level*0.25) );
        $this->xp   = fairInt( $blueprint["xp_given"]*(0.50+sqrt($this->level*0.25)) );
        $this->gold = fairInt( $blueprint["gold_given"]*(0.50+sqrt($this->level*0.25)) );

        $this->stat_points = 0;

        //skills
        $this->fight = 4+$this->level;
        $this->defence = (int)($blueprint['AC']/4+$this->level-1);
        $this->lockpick = 1+ (int)($this->wisdom/4);
        $this->traps = 1 + (int)($this->wisdom/2);

        //define equipment vars
        $this->weapon = 0;
        $this->armor = 0;
        $this->boots = 0;
        $this->shield = 0;
        $this->gloves = 0;
        $this->helm = 0;

        //FIXME: we need natural armor to clothe e.g. dragons
        //FIXME: armor class needs to be spent more evenly among armor types
        if($ac_left>0) {
            $armors= fetch_all("select id, armor_class from phaos_armor where armor_class<=$ac_left order by armor_class DESC LIMIT 1");
            if(count($armors)>0){
                $this->armor= $armors[0]['id'];
                $this->armor_ac= $armors[0]['armor_class'];
                $ac_left -= $this->armor_ac;
            }
        }

        if($ac_left>0) {
            $boots= fetch_all("select id, armor_class from phaos_boots where armor_class<=$ac_left order by armor_class DESC LIMIT 1");
            if(count($boots)>0){
                $this->boots= $boots[0]['id'];
                $this->boots_ac= $boots[0]['armor_class'];
                $ac_left -= $this->boots_ac;
            }
        }

        //fill weapon:
        $blueprint['avg_damage']= (int)(($blueprint["min_damage"]+$blueprint["max_damage"])*0.5);
        $weapons= fetch_all("select * from phaos_weapons where min_damage<=$blueprint[min_damage] and $blueprint[avg_damage]<= 2*max_damage order by RAND() LIMIT 1");
        if(count($weapons)>0){
            $this->weapon= $weapons[0]['id'];
            $this->weapon_min = $weapons[0]['min_damage'];
            $this->weapon_max = $weapons[0]['max_damage'];
            $this->weapon_name = $weapons[0]['name'];
        }else{
            $this->weapon_min = 0;
            $this->weapon_max = 0;
            $this->weapon_name = "natural weapon";
        }
        $this->weaponless = $blueprint['avg_damage']+ 2*(int)($this->level);

        //calculated stuff
        $this->available_points = $this->strength+$this->dexterity+$this->wisdom+$this->constitution;
        $this->max_hp = $this->constitution*6;
        $this->max_stamina = ($this->constitution+$this->strength)*10;
        $this->max_rep = 7;

        if($this->hit_points>$this->max_hp){
            $this->max_hp= $this->hit_points;
        }
        if($this->stamina_points>$this->max_stamina){
            $this->max_stamina= $this->stamina_points;
        }

        //other stuff
        $actTime=time();
        $this->regen_time = $actTime;
        $this->stamina_time = $actTime;
        $this->rep_time = $actTime;
        $this->no_regen_hp = $blueprint["hit_points"];
        //regeneration
        $this->time_since_regen = $actTime-$this->regen_time;
        $this->stamina_time_since_regen = $actTime-$this->stamina_time;
        $this->rep_time_since_regen = $actTime-$this->rep_time;
        //reputation
        $this->rep_points = rand(0,$this->level-1);
        $this->rep_helpfull = rand(0,$this->level-1);
        $this->rep_generious = rand(0,$this->level-1);
        $this->rep_combat = rand(0,$this->level-1);
        //weapon & fight Calculation

        $this->max_inventory=$this->strength*5;

    	if( !$this->image ) {
            $this->image = "images/monster/forest_troll.gif";
        }
    }

}

/**
* @param: none
* return: none
* purpose: generate new NPC/monster and add to database
*/
function npcgen() {
	$res = mysql_query ("SELECT * FROM phaos_opponents WHERE location='0' ORDER BY RAND() LIMIT 1") or die(mysql_error());
	if($blueprint = mysql_fetch_array($res)) {
        //create 50% level 1 characters, and not more than 37,5% characters with level>3
        $level= 1+(int)(rand(0,1)*(pow(1+rand(0,10)*rand(0,10)*0.01,4)+rand(0,99)*0.01));
        $npc= new np_character_from_blueprint($blueprint, $level);

        $condition_passable= $npc->real_sql_may_pass();

        //TODO: add generator regions/locations feature to phaos

        $tries= 10;
        while($tries-->0){
            $res= null;
            //FIXME: this actually should depend on the area covered by dungeons
            //20050717
            //Wilderness    14277
            //Woodlands 	1891
            //Dungeon       675
    		if( !@$res && rand(0,99)<4 ) {
                $location= 'Rune Gate%';
                $sql = "SELECT id FROM phaos_locations WHERE (name LIKE 'Rune Gate%' OR name LIKE 'Dungeon') AND $condition_passable ORDER BY RAND() LIMIT 1";                  
                //defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][]= __FUNCTION__.": sql: $sql";
                $res = mysql_query ($sql) or die(mysql_error());
            }
            if( !@$res) {
                $location= 'Wilderness';
                $sql = "SELECT id FROM phaos_locations WHERE (name LIKE 'Wilderness' OR name LIKE 'Woodlands' OR name LIKE 'Rune Gate%' OR name LIKE 'Dungeon') AND $condition_passable ORDER BY RAND() LIMIT 1";
                //defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][]= __FUNCTION__.": sql: $sql";
                $res = mysql_query ($sql) or die(mysql_error());
            }
    		list($locationid) = mysql_fetch_array($res);

            //check whether location is crowded
            $res = mysql_query ("SELECT count(*) FROM phaos_characters WHERE location='$locationid' AND username='phaos_npc'") or die(mysql_error());
            list($count)= mysql_fetch_array($res);
            if($count>$level+1) {
                defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][]= " location $locationid is <b>crowded</b>, not placing here ($count npcs)";
                //trying to fix
                $res = mysql_query ("SELECT id FROM phaos_characters WHERE location='$locationid' AND username='phaos_npc'") or die(mysql_error());
                while(list($id)= mysql_fetch_array($res)){
                    $crowd= new character($id);
                    $crowd->relocate( (int)rand(1,8) );
                }
            }else{
                break;//stop while loop
            }
        }

	} else {
		die("cant find valid mob in DB: ".mysql_error());
	}
    $npc->place($locationid);
	DEBUG and  $_SESSION['disp_msg'][] = "**DEBUG: $npc->name($npc->level) generated at location $location $locationid";
	return 1;
}

function refsidebar() {
	//REFRESH SIDEBAR INFO
	?>
	<script language="JavaScript">
	<!--
	parent.side_bar.location.reload();
	//-->
	</script>
	<?php
}

function diceroll(){
	return rand(1,15)+rand(1,15);
	// odds, rolling 2 15-side dice
	//   2=0.4%      3=0.9%      4=1.3%      5=1.8%      6=2.2%      7=2.7%      8=3.1%      9=3.5%     10=4.0%     11=4.5%
	//  12=4.9%     13=5.4%     14=5.7%     15=6.2%     16=6.6%     17=6.1%     18=5.8%     19=5.4%     20=4.9%     21=4.4%
	//  22=4.0%     23=3.6%     24=3.1%     25=2.7%     26=2.2%     27=1.8%     28=1.3%     29=0.9%     30=0.4%
}

function fairInt($f){
    return (int)($f+rand(0,99)*0.01);
}

function getclan_sig($plname){
    $result = mysql_query ("SELECT * FROM phaos_clan_in WHERE clanmember = '$plname'");
    if ($row = mysql_fetch_array($result)) {
    	$mem_clan = $row["clanname"];
    } else {return false;}

    $result = mysql_query ("SELECT * FROM phaos_clan_admin WHERE clanname = '$mem_clan'");
    if ($row = mysql_fetch_array($result)) {
    	$clan_name = $row["clanname"];
    	$clan_sig = $row["clan_sig"];
    }
    if ($clan_sig !== "no" or $clan_sig !== ""){
    ?>
    <img src="images/guild_sign/<?php echo $clan_sig; ?>" alt="<?php echo $clan_name; ?>">
    <?php
    }
}// end function getclan_sig

?>
