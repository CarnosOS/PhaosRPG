<?php
/* map integer to sql location table field*/
$npc_dir_map = array(
    1=>"above_left",
    2=>"above",
    3=>"above_right",
    4=>"leftside",
    5=>"rightside",
    6=>"below_left",
    7=>"below",
    8=>"below_right"
);

/*
 *  get random elements from an associative array
 *  @from www.php.net
 *  shuffle() does not maintain key associations. This is how to shuffle an associative array without losing key associations:
 */
function array_rand_assoc_array($shuffle_me,$num_req,$byreference= true) {
   $randomized_keys = array_rand($shuffle_me, $num_req);
   foreach($randomized_keys as $current_key) {
       if($byreference){
           $shuffled_me[$current_key] = &$shuffle_me[$current_key];
       }else{
           $shuffled_me[$current_key] = $shuffle_me[$current_key];
       }
   }
   return $shuffled_me;
} 

function candoquest($char, $quest)
	{
	      global $PHP_PHAOS_USER;
	      $cando=1;
	      $sql="SELECT * FROM phaos_characters WHERE id=$char LIMIT 1;";
	      $res=mysql_query($sql);
	      $charrow=@mysql_fetch_array($res);
	      $sql="SELECT * FROM phaos_quests WHERE questid=$quest LIMIT 1;";
	      $res=mysql_query($sql);
	      $questrow=@mysql_fetch_array($res);
	      if ($questrow["restnum"]==0) {$cando=-3;}
	      if ($questrow["reqexp"]>$charrow["xp"]) {$cando=-2;}
	      if ($questrow["complete"]==0) {$cando=-1;}
	      $sql="SELECT * FROM phaos_questhunters WHERE charid=$char AND questid=$quest LIMIT 1;";
	      $res=mysql_query($sql);
	      if ($sent4hunt=mysql_fetch_array($res)) {$cando=-99;}
	      return $cando;
	}

function who_is_online($location = '') {
	global $PHP_PHAOS_USER;
	global $lang_glo;
	
	if ($location != '') { $loc = 'location = ' . $location . ' AND '; }
	$current_time = time();
	
	$active_min = $current_time-300;
	$active_max = $current_time+300;
	
	$result = mysql_query("SELECT * FROM phaos_characters WHERE $loc regen_time >= '$active_min' AND regen_time <= '$active_max' AND username != 'phaos_npc' AND username != 'phaos_npc_arena' ORDER by name ASC");
	
	$html='';
	if (mysql_num_rows($result) != 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$html .=  '<font color="#009900">|</font><a href="player_info.php?player_name='. $row['username'] . '" target="_blank">' . $row['name'] .  '</a>';
		}
	} else {
		$html = "<font color=#009900>|</font>".$lang_glo["n_else"];
	}
	return $html . '<font color="#009900">|</font>';
}

function who_is_offline($location = '') {
	global $PHP_PHAOS_USER;
	if ($location != '') { $loc = 'location = ' . $location . ' AND '; }
	$current_time = time();

	$active_min = $current_time-300;
	$active_max = $current_time+300;
	
	$result = mysql_query("SELECT * FROM phaos_characters WHERE $loc regen_time < '$active_min' AND username != 'phaos_npc%' AND username != 'phaos_npc_arena' ORDER by name ASC");
	echo mysql_error();
	
	$html='';
	if (mysql_num_rows($result) != 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$html = '<font color="#009900">|</font><a href="player_info.php?player_name='. $row['username'] . '" target="_blank" style="color=#FFFFFF">' . $row['name'] .  '</a>';
		}
	}
	return $html . '<font color="#009900">|</font>';
}


// return the plural of a (monster)name
function namePlural($name,$number=1){
    return "".$name.($number>1?'s':'');
}

//create a list text from an array for use in SQL: .. where id IN $list ..
function makeList($ids) {
    $idlist= "(-1";
    foreach($ids as $id) {
        $iid= intval($id);
        if($iid || $id===0 || $id==="0"){
            $idlist.= ",$iid";
        }
    }
    $idlist.= ')';
    return $idlist;
}

//
// project an list of associative arrays onto a list containing just one field
//
function project($list,$fieldname){
    if(!is_array($list)){
        if($list){
            die("bad list in project($list,'$fieldname')");
        }
        return;
    }
    $single= array();
    foreach($list as $key=>$array){
        $single[$key]= $array[$fieldname];
    }
    return $single;
}

/*
 * show a database access error
 */
function showError($FILE=__FILE__,$LINE=__LINE__,$FUNCTION=__FUNCTION__,$sql=""){
    echo "<B> $FILE #$LINE $FUNCTION(): Error ".mysql_errno()." :</B> ".mysql_error().($sql?" <br> SQL:$sql":"");
    flush();
}

// fetch all results for an SQL query into an associative array
function fetch_all($query,$FILE=__FILE__,$LINE=__LINE__,$FUNCTION=__FUNCTION__){
	$result = mysql_query($query);
    if (!$result) {
        showError($FILE,$LINE,$FUNCTION,$query);
        return array();
    }

    $list= array();
	if (mysql_num_rows($result) != 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$list[] =  $row;
		}
	}

    return $list;
}

// fetch one result for an SQL query into an associative array
function fetch_first($query,$FILE=__FILE__,$LINE=__LINE__,$FUNCTION=__FUNCTION__){
    $limit=preg_match("/LIMIT (\d*,){0,1}\d*$/",$query)?"":" LIMIT 1";

	$result = mysql_query($query.$limit);
    if (!$result) {
        showError($FILE,$LINE,$FUNCTION,$query);
        return array();
    }

    $list= array();
	if (mysql_num_rows($result) != 0) {
		$row = mysql_fetch_assoc($result);
        return $row;
	}

    return null;
}

// fetch one value for an SQL query, such as a count(*)
function fetch_value($query,$FILE=__FILE__,$LINE=__LINE__,$FUNCTION=__FUNCTION__){
    $limit=preg_match("/LIMIT (\d*,){0,1}\d*$/",$query)?"":" LIMIT 1";

	$result = mysql_query($query.$limit);
    if (!$result) {
        showError($FILE,$LINE,$FUNCTION,$query);
        return array();
    }

    $list= array();
	if (mysql_num_rows($result) != 0) {
		$row = mysql_fetch_row($result);
        return @$row[0];
	}

    return null;
}



//
// args: map location or array of locations
// returns: list of npc/monster id numbers at that location (not including active player)
// the characterrole argument can be used to specify a subset of characters
//FIXME: there should be a separate field in the database for characterrole, since right now it abuses the username
function whos_here($locationids,$characterrole='phaos_npc') {
	global $PHP_PHAOS_CHARID;
    if(is_array($locationids)) {
        $locationset= makeList($locationids);
        $locwhere= "location IN $locationset";
    }else{
        $locationid= intval($locationids);
        $locwhere= "location=$locationid";
    }

    $order= 'ORDER by wisdom ASC, gold/(strength+dexterity+hit_points) ASC';
    $query = "SELECT id FROM phaos_characters WHERE $locwhere AND username LIKE '$characterrole' $order";
    $list= project( fetch_all($query), 'id');

	return $list;
}


// check to see if this map location has a specific shop
function shop_valid($location, $shop_id) {  // was $type
    $location= intval($location);
    $shop_id= intval($shop_id);
	$result = mysql_query ("SELECT * FROM phaos_buildings WHERE location = '$location' AND shop_id='$shop_id' ");  // was $type.php
	// if (mysql_num_rows($result) == 0) {exit;}
	if(! list($shop_id,$shop_location,$shop_name,$shop_type,$shop_ownerid,$shop_capacity) = mysql_fetch_array($result)) {
		return false;
	}
    return true;
}

// speed up selecting random rows by preselecting a subset for select by rand()
// this will only work fairly if the ids are distributed about evenly
// very low and very high ids will be selected less often
// this function is redundant for databases that support selecting a random row quickly
//FIXME: use this function more
function speedup_random_row($id,$table,$where) {
    $cia= fetch_first("select count($id) as c, min($id) as i, max($id) as a from $table where $where",__FILE__,__LINE__,__FUNCTION__);
    $count= $cia['c'];
    $min= $cia['i'];
    $max= $cia['a'];
    if($count>0){
        $range= $max-$min;
        $m= rand($min,$max);
        $root= ceil( sqrt($range) );
        $a= $m - $root;
        $b= $m + $root;
        $whereid= " $a<=$id and $id<=$b ";
        $gotsome= fetch_value("select $id from $table where $where and $whereid",__FILE__,__LINE__,__FUNCTION__);
        if($gotsome){
            return $whereid;
        }else{//play it safe
            return " 1=1 ";
        }
    }else{
        return " 1=1 ";
    }
}

/*
 * check whether a string is a valid item type
 */

//these item type names are used as fields in the database
$validEquipFieldItems= array("armor","weapon","boots","shield","helm","gloves");

//these are valid items in the inventory
$validInvItems= array_merge($validEquipFieldItems,array("potion","spell_items"));

//these items are valid random drops
$validDropItems= array_merge($validInvItems,array("gold"));

//this is a fucking mess to need this trick at all
$tableForField= array(
    "armor"=>"armor",
    "weapon"=>"weapons",
    "boots"=>"boots",
    "shield"=>"shields",
    "helm"=>"helmets",
    "gloves"=>"gloves",
    "potion"=>"potion",
    "spell_items"=>"spells_items",
    "gold"=>"gold"
);

foreach ($tableForField as $field=>$table){
    $fieldForTable[$table]= $field;
}


function isEqItemType($item_type){
    global $validEquipFieldItems;
    return in_array($item_type,$validEquipFieldItems);
}

function isItemType($item_type){
    global $validInvItems;
    return in_array($item_type,$validInvItems);
}

function jsChangeLocation($link){
    if(@DEBUG){
        ?>
        <a href="<?php echo htmlSpecialChars($link); ?>" style="background:red"><?php echo htmlSpecialChars($link); ?></a>
        <?php
    }else{
        ?>
        <script language="JavaScript">
        <!--
        self.location="<?php echo $link; ?>";
        //-->
        </script>
        <?php
    }
}

function smsTime(){
    $start = microtime();
    $start = explode(" ", $start);
    $start = (float)$start[1] + (float)$start[0];
    return $start;
}

function beginTiming(){
    global $timestart;
    $timestart= smsTime();
}

function endTiming(){
    global $timestart;
    return smsTime()-$timestart;
}

//print an array of error message
function print_msgs($msgs,$left='<tr><td align=center>',$right='</td></tr>'){
    if(is_array(@$msgs)){
        foreach ($msgs as $i => $value) {
            echo $left.$value.$right;
        }
    }
}

/*
 * This multiplies rand() such that there will be few, but some high numbers
 * FIXME: fit this into one formula solving
 * p(x<=exp(n*ln($growth)))=exp(n*ln($probability))
 * TODO: write testing function
 */
function powrand($growth,$probability,$n){
/*  left here for your entertainment the fun magic structural constant 1/137 */
    $powar= 1.0;
    $power= 1.0;
    while($n-->0 && rand(1,137)<= 137*$probability){
        $powar= $power;
        $power*= $growth;
        //defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][] = "powrand($growth,$probability,$n) = ($powar,$power)";
    }
    $fudge= rand(0,136)/137;
    return rand( (int)(137.0*($powar+$fudge)), (int)(137.0*($power+$fudge)) )/137.0;
}

/* Display a hidden field, but recurse if the field is an array */
function hiddenFields($name,$value){
    if(is_array($value)){
        foreach($value as $k=>$v){
            hiddenFields($name.'['.$k.']',$v);
        }//foreach
    }else{
        ?><input type="hidden" name="<?php echo $name; ?>" value="<?php echo htmlspecialchars($value); ?>"><?php
    }
}

function htmlSelect($name,$options,$selected,$vk=0,$ok=1,$size="",$null="",$defaulttext="------"){
?>
<select name="<?php echo $name; ?>" <?php echo $size?"size=\"$size\"":""; ?> onChange="if(this.value!='<?php echo $null; ?>')this.form.submit();">
<option value="<?php echo $null; ?>"><?php echo $defaulttext; ?></option>
<?php
foreach($options as $array){
    $value= $array[$vk];
    $text= $array[$ok];
    ?>
<option value="<?php echo $value; ?>" <?php echo ($selected==$value)?"SELECTED":""; ?> ><?php echo $text; ?></option>
    <?php
}
?>
</select>
<?php
}

/*
 * select id from 90001-100000
 * for use as
 *  arena_fighting_location
 *  item_storage_location
 * FIXME: this is not thread-safe at all without table locking
 * when you add more ranges, you can split the old ranges
 */
function nextLocationIdFromRange($location_type,$file=__FILE__,$line=__LINE__){
    switch($location_type){
        case 'arena_fighting_location':
            $min=  90001;
            $max=  95001;
            break;
        case 'item_storage_location':
            $min=  95001;
            $max= 100001;
            break;
        default:
            die("Invalid location range($location_type:$min-$max) requested in call to ".__FUNCTION__."() by ".$file."#".$line);
    }
    $query= "select max(id) from phaos_locations where id>=$min and id<$max";
    $id= fetch_value($query);
    if( !$id ){
        //die("No id found(bad sql?) in location range($location_type) requested in call to ".__FUNCTION__."() by ".$file."#".$line);
        $query= "insert into phaos_locations
         (id, name, image_path, special, buildings, pass, explore)
         values
         ($min, 'BEGIN location range($location_type:$min-$max)','images/special.gif',1,0,1,0)
          ";
        $req = mysql_query($query);
        if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__); exit;}

        $id= $min;
    }
    ++$id;
    if( $id>=$max ){
            die("No ids left in location range($location_type:$min-$max) requested in call to ".__FUNCTION__."() by ".$file."#".$line."<br> SQL:".$query);
    }
    defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][] = "issued id $id as $location_type";
    return $id;
}


/* Display a hidden field, but recurse if the field is an array */
function urlFields($name,$value){
    if(is_array($value)){
        $rval= '';
        foreach($value as $k=>$v){
           $rval .= urlFields($name.'['.$k.']',$v);
        }//foreach
        return $rval;
    }else{
        return htmlentities($name).'='.rawurlencode($value).'&';
    }
}

/* creates a nice looking form button */
function actionButton($label,$action,$fields,$target='self',$method='POST'){
    ?><form action="<?php echo $action; ?>" method="<?php echo $method; ?>"><?php
    foreach($fields as $name=>$value){
        hiddenFields($name,$value);
    }
    ?>
	<input type="submit" value="<?php echo $label; ?>">
</form>
<?php
}

/* format image for use in table cells */
function makeImg($image_path){
    if(@$image_path){
        return "<img src=\"".$image_path."\">";
    }else{
        return "&nbsp;";
    }
}

?>
