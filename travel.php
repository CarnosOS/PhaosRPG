<?php
session_start();
if(@$_SESSION['opponent_id']) {
	header ('Location: combat.php?opp_type=npc');
	exit;
}
include "header.php";
include_once "functions.php";
include_once "class_character.php";

beginTiming();

## Variables
$params = array();
$DEBUG	= 0;	// 0 means turn off debugging;   1 means turn on debugging

// population control
$where= "username='phaos_npc' and race <> 'Dwarf'";
$result = mysql_query("select count(id) from phaos_characters where $where");
list($count) = mysql_fetch_row($result);

//number of
$lowerlimit= 3000;

$upperlimit= $lowerlimit+200;
if ($count > $upperlimit ) {
	$delta= 3+(int)(($count-$upperlimit)/100);
	$result = mysql_query("select id,location,username,name,race from phaos_characters where $where order by rand() LIMIT $delta");
	while( $row = mysql_fetch_assoc($result) ){
		$mob = new character($row['id']);
		$mob->kill_characterid();
	}
}

//@$_COOKIE['_speed'] is just a HACK to speed up testing on my PC, which is very slow

if ($count < $lowerlimit ) {
	$n = ceil(sqrt($lowerlimit-$count)*0.20*(@$_COOKIE['_speed']?0.5:1.0));
	$n>6 and $n= 6;
	for($i=0;$i<$n;++$i) {
		npcgen();
	}
}

if(@$_COOKIE['_timing']) { echo "time end pop control=".endTiming()."<br>\n"; };



// move some NPC first

$npctomov= (@$_COOKIE['_speed'])?3:9;

for($i=0;$i<$npctomov;$i++){
    movenpc();
}

if(@$_COOKIE['_timing']) { echo "time end pop movement=".endTiming()."<br>\n"; };

updateshops();

if(@$_COOKIE['_timing']) { echo "time end shop updates=".endTiming()."<br>\n"; };

// CHARACTER INFORMATION
$character= new character($PHP_PHAOS_CHARID);

// Make sure character is strong enough to travel
if ($character->hit_points<="0") {
	$destination = "";
} elseif ($character->stamina_points<="0") {
	$destination = "";
} else {
    //FIXME: this allows an instant gate travel hack, uhm, I mean, spell
	if (is_numeric(@$_POST['destination']) and $_POST['destination'] > 0) {
		$destination = $_POST['destination'];
	} else {
		$destination = "";
	}
}

if(@$_COOKIE['_timing']) { echo "time 1=".endTiming(); };

if($destination != "")
   {
      //new stamina reduction formula:
      $inv_count=$character->invent_count();
      $degrade=($inv_count-($character->constitution+$character->strength*4));
      if ($inv_count>$character->max_inventory){$degrade=$degrade*2;}
      if ($degrade<0) {$degrade=1;}
      //end stamina reduction update table:

      $character->reduce_stamina($degrade);
      $result = mysql_query('SELECT `above_left`, `above`, `above_right`, `leftside`, `rightside`, `below_left`, `below`, `below_right` FROM  phaos_locations WHERE id = \'' . $character->location . '\'');
      $row = mysql_fetch_assoc($result);
      foreach ($row as $item)
         {
            //FIXME: uses untrusted input by the user
            if ($item == $destination OR @$_POST['rune_gate'] == "yes" OR @$_POST['explorable'] == "yes")
               {
                  $query = ("UPDATE phaos_characters SET location = '$destination', stamina=".$character->stamina_points." WHERE id = '$PHP_PHAOS_CHARID'");
                  $req = mysql_query($query);
                  if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;}
                  $result = mysql_query ("SELECT * FROM phaos_locations WHERE id = '$destination'");
                  $character->location=$destination;
                  if ($row = mysql_fetch_array($result)) {$location_name = $row["name"];}
               }
          }
   }

// define mob separators for php and escaped for javascript
$info_eol= "\r";
$js_info_eol= "\\r";

if($character->name == "") {
     $message =  ("<font size=4><b>".$lang_area["must_create_a_char"]."</b></font><p>".$lang_area["create_a_char"]);
} else {
    $message= '';

	if  ($character->hit_points <= "0") {
		$message =  $lang_trav["zero_hp"]."<br>";
	}
	if  ($character->stamina_points <= "0") {
		$message .= $lang_trav["zero_st"]."<br>";
	}
	if  ($destination == "") {
		$message .= "<b>".$lang_trav["dest"]."</b>";
		draw_html($message);
	}
	if  ($destination != "") {
		$list = whos_here($character->location,'phaos_npc');
		if (count($list)) {
			$result = mysql_query("SELECT buildings,special FROM phaos_locations WHERE id = '".$character->location."'");
			list($buildings,$special) = mysql_fetch_array($result);
			if ($buildings == "n" AND $special == 0) {
				?>
				<script language="javascript">
				window.location = "combat.php?opp_type=roammonst";
				</script>
				<?php
//				header ("Location: combat.php?opp_type=roammonst");
				exit;
			}
		}
	    draw_html(@$message);
	}
}

session_destroy();

##---Functions--##
function draw_html($message = '')
	{
		global $character;
		global $params;
		global $DEBUG;
		global $lang_area;
        global $js_info_eol;

        if ($DEBUG >= 1) { $message.= "<p>** DEBUG - Location: ".$character->location."<br>"; }
		//if ($DEBUG >= 1) { $message.= "<p>** DEBUG - whos_here: ".print_r($list,true); }
		// FFIXME:  had to change this to NONE.css or link/input squares have css background !
?>
<script language="JavaScript" type="text/JavaScript"><!--
function displayInfo(info){
    var infoDiv= document.getElementById("info");
    var re = /<?php echo $js_info_eol; ?>/g;
    info = info.replace(re,"<br>");
    infoDiv.innerHTML= info;
}
//-->
</script>
<?php
			print '<table border="0" cellspacing="0" cellpadding="0" width="100%">
				  <tr>
					 <td align="center" valign="top">
						<table border="0" cellspacing="0" cellpadding="0">
						   <tr>
							  <td align="center" colspan="2">
								'.$message . '<br>';
                                    //print '<div style="z-Index:30;position:absolute;top:20px;left:20px;">';
                                    // build and print map
                                    list($out_loc,$marker_loc) = data_collect();
                                    draw_all($out_loc);
                                    //print '</div>'."\n";
                                    //print '<div style="z-Index:40;position:absolute;top:20px;left:20px;">';
                                    //draw_all($marker_loc);
                                    //print '</div>'."\n";

			print			  '<br></td>
						   </tr><tr>
						   <td>';
        if(!isset($params['name'])){
            $params['name']='';
        }
		if ($params["buildings"] == "y" AND $params["one_building"] > "1")
			{
				echo "<a href='town.php'>
					<img src='images/icons/enter.gif' alt='$lang_area[enter]' border=0 align=left> $lang_area[enter]<br> $params[name]</a><br>";
			}
		if ($params["buildings"] == "y" AND $params["one_building"] == "1")
			{
				echo "<a href='town.php'>
					<img src='images/icons/enter.gif' alt='$lang_area[enter]' border=0 align=left> $lang_area[enter]<br> $params[name]</a><br>";
			}
		if ($params["explore"] != "")
			{
		echo "<table border=0 cellspacing=0 cellpadding=0>";
		      echo "<form action='travel.php' method='post'>
		      	    <tr>
			    <td align=left>
			    <input type='hidden' name='explorable' value='yes'>
			    <input type='hidden' name='destination' value='$params[explore]'>
			    <button style='background:#000000;border:none;color:#FFFFFF;' type=submit><img src='images/icons/enter.gif' alt='$lang_area[enter]' border=0 align=left>$lang_area[enter]</button>
			    </td>
			    </tr>
			    </form>";
		 echo "</table>";
			}
		if ($params["special_id"] > 0){
			  echo "<a href=\"area.php\">";
			  echo "<img src=\"images/icons/invest.gif\" alt=\"".$lang_trav["invest"]."\" border=\"0\"></a>";
		 }
		if (@$params['rune_gate'] == "yes"){
		echo "<table border=0 cellspacing=0 cellpadding=0>
			<tr><td align=left><b>Gate Travel:</b></td></tr>";

		      $result = mysql_query ("SELECT * FROM phaos_locations WHERE name LIKE 'Rune Gate%' AND id != '$character->location' ORDER BY name ASC");
		      while ($row = mysql_fetch_array($result)) {
		      echo "<form action='travel.php' method='post'>
		        <tr>
			    <td align=left>
			    <input type='hidden' name='destination' value='$row[id]'>
			    <input type='hidden' name='rune_gate' value='yes'>
			    <input type='submit' style='text-align:left;background:#000000;color:#FFFFFF;border:none;' value='$row[name]'>
			    </td>
			    </tr>
			    </form>";
		      }
		 echo "</table>";
		 }
if(@$_COOKIE['_timing']) { echo "time F=".endTiming(); };

		print '
								  </td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
        ';
        ?><center><div id="info" style="overflow:auto;height:180px;width: 420px;z-Index:20;"></div><?php include "trailer.php";?></center><?php
	}

function draw_all($out_loc) {
	// locations of sight from center(25) starting north, going clockwise
	$locs = array(
		23 , 24 ,  2 ,  4 ,  5,
		22 , 21 ,  1 ,  3 ,  6,
		20 , 19 , 25 ,  7 ,  8,
		18 , 15 , 13 ,  9 , 10,
		17 , 16 , 14 , 12 , 11
	);

	$x=1;
	echo "<table border=0 cellpadding=0 cellspacing=1 style='background:#FFFFFF;'><tr>";
	foreach ( $locs as $block) {
		echo $out_loc[$block]['html'];
        $x++ == 5 && $block!=11 and print '</tr><tr>' and $x=1;
	}
	echo '</tr></table>';
}

function draw_square($link = false, $picture, $id='', $ch_img='images/clear.gif', $locname='', $mobs= array('text'=>''), $markers= array(), $dir='') {
	if($mobs && $mobs['text']){
	    if($mobs['char'][0]['isnpc']){
	         $ch_img= "images/mobs/map_mob.gif";
        }else{
             $ch_img= $mobs['char'][0]['image_path'];
        }
     }else{
        $mobs= array('text'=>'');
     }
    (!$ch_img && count($markers)>0 ) and $ch_img = 'images/'.$markers[0];
	$ch_img or $ch_img="images/clear.gif";

    DEBUG or $dir='';

    if($link){
        $image= '<input style="border:none;background:none;" type=image src="'.$ch_img.'" title="'.$locname.' ('.$id.")$dir\n".$mobs['text'].'" onMouseOver="displayInfo(this.title);" onMouseOut="displayInfo(\'\');" name="destination_button" value="'.$id.'">';
    }else{
        if($picture){
            $image= '<img src="'.$ch_img.'" alt="'.$mobs['text'].'" title="'.$locname.' ('.$id.")$dir\n".$mobs['text'].'" onMouseOver="displayInfo(this.title);" onMouseOut="displayInfo(\'\');" >';
        }else{
            $image= '<img src="images/land/49.png" alt="seas" title="Water">';
            $picture= "images/land/49.png";
        }
    }

	if ($link) {
		$s = '<form action="travel.php" method="post"><td align=center valign=middle style="background:url('.$picture.')"><input type="hidden" name="destination" value="'.$id.'">'.$image.'</td></form>';
	} else {
		$s = '<td width=52 height=52 align=center valign=middle style="background:url('.$picture.');">'.$image.'</td>';
	}
	return $s;
}

function data_collect() {
    global $character;
    global $params;

    $fchance= $character->finding();

if(@$_COOKIE['_timing']) { echo "time begin DC=".endTiming(); };


      $result = mysql_query ("SELECT * FROM phaos_locations WHERE id = '".$character->location."'");
      if ($row = mysql_fetch_array($result)) {
            $out_loc[25]['id'] = $character->location;
            $character_locname = $row['name'];

            $markers= array();
            $ground_items= fetch_items_for_location($character->location, $fchance );
            if(count($ground_items)>0){
                $markers[] = 'icons/gold.gif';
            }

            $out_loc[25]['html'] = draw_square(false, $row["image_path"], '', $character->image , $row["name"], array('text'=>''), $markers, 25);
            $params['name'] = $row['name'];
    	    if(strstr($row['name'],"Rune Gate")) {$params['rune_gate'] = "yes";}
            $params['special_id'] = $row["special"];
    	    $params['buildings'] = $row['buildings'];
    	    $params['explore'] = $row['explore'];

      	    $build_check = mysql_query ("SELECT type FROM phaos_buildings WHERE location = '".$character->location."'");
    	    $numrows = mysql_num_rows($build_check);
    	    $params['one_building'] = $numrows;
    	    if ($bui = mysql_fetch_array($build_check)) {
        		$params['building_type'] = $bui['type'];
    	    } else {
        		$params['building_type'] = "";
    	    }

            $out_loc[ 1]['id'] = $row["above"];		$out_loc[ 1]['link'] = true; $out_loc[ 1]['block']=0;
            $out_loc[ 3]['id'] = $row["above_right"];	$out_loc[ 3]['link'] = true; $out_loc[ 3]['block']=0;
            $out_loc[ 7]['id'] = $row["rightside"];	$out_loc[ 7]['link'] = true; $out_loc[ 7]['block']=0;
            $out_loc[ 9]['id'] = $row["below_right"];	$out_loc[ 9]['link'] = true; $out_loc[ 9]['block']=0;
            $out_loc[13]['id'] = $row["below"];		$out_loc[13]['link'] = true; $out_loc[13]['block']=0;
            $out_loc[15]['id'] = $row["below_left"];	$out_loc[15]['link'] = true; $out_loc[15]['block']=0;
            $out_loc[19]['id'] = $row["leftside"];	$out_loc[19]['link'] = true; $out_loc[19]['block']=0;
            $out_loc[21]['id'] = $row["above_left"];	$out_loc[21]['link'] = true; $out_loc[21]['block']=0;

        // We try to collect the data from mysql in one go to speed up things
        $set= "('".$out_loc[3]['id']."','".$out_loc[9]['id']."','".$out_loc[15]['id']."','".$out_loc[21]['id']."')";
		$data_locations = fetch_all("SELECT * FROM phaos_locations WHERE id IN ".$set);
        foreach($data_locations as $data_location){
            $cache_row[$data_location['id']]= $data_location;
        }

		// We do some of these twice because they might not be accessible from one angle
		if ($out_loc[3]['id']) {
			//$result = mysql_query ("SELECT * FROM phaos_locations WHERE id = ".$out_loc[3]['id']);
			//$row = mysql_fetch_array($result);
            $row= @$cache_row[$out_loc[3]['id']];
			$out_loc[ 2]['id'] = $row["above_left"];	$out_loc[ 2]['block']=0;
			$out_loc[ 4]['id'] = $row["above"];		$out_loc[ 4]['block']=0;
			$out_loc[ 5]['id'] = $row["above_right"];	$out_loc[ 5]['block']=0;
			$out_loc[ 6]['id'] = $row["rightside"];		$out_loc[ 6]['block']=0;
			$out_loc[ 8]['id'] = $row["below_right"];	$out_loc[ 8]['block']=0;
		}

		if ($out_loc[9]['id']) {
			//$result = mysql_query ("SELECT * FROM phaos_locations WHERE id = ".$out_loc[9]['id']);
			//$row = mysql_fetch_array($result);
            $row= @$cache_row[$out_loc[9]['id']];
			$out_loc[ 8]['id'] = $row["above_right"];	$out_loc[ 8]['block']=0;
			$out_loc[10]['id'] = $row["rightside"];		$out_loc[10]['block']=0;
			$out_loc[11]['id'] = $row["below_right"];	$out_loc[11]['block']=0;
			$out_loc[12]['id'] = $row["below"];		$out_loc[12]['block']=0;
			$out_loc[14]['id'] = $row["below_left"];	$out_loc[14]['block']=0;
		}

		if ($out_loc[15]['id']) {
			//$result = mysql_query ("SELECT * FROM phaos_locations WHERE id = ".$out_loc[15]['id']);
			//$row = mysql_fetch_array($result);
            $row= @$cache_row[$out_loc[15]['id']];
			$out_loc[14]['id'] = $row["below_right"];	$out_loc[14]['block']=0;
			$out_loc[16]['id'] = $row["below"];		$out_loc[16]['block']=0;
			$out_loc[17]['id'] = $row["below_left"];	$out_loc[17]['block']=0;
			$out_loc[18]['id'] = $row["leftside"];		$out_loc[18]['block']=0;
			$out_loc[20]['id'] = $row["above_left"];	$out_loc[20]['block']=0;
		}

		if ($out_loc[21]['id']) {
			//$result = mysql_query ("SELECT * FROM phaos_locations WHERE id = ".$out_loc[21]['id']);
			//$row = mysql_fetch_array($result);
            $row= @$cache_row[$out_loc[21]['id']];
			$out_loc[20]['id'] = $row["below_left"];	$out_loc[20]['block']=0;
			$out_loc[22]['id'] = $row["leftside"];		$out_loc[22]['block']=0;
			$out_loc[23]['id'] = $row["above_left"];	$out_loc[23]['block']=0;
			$out_loc[24]['id'] = $row["above"];		$out_loc[24]['block']=0;
			$out_loc[ 2]['id'] = $row["above_right"];	$out_loc[ 2]['block']=0;
		}
         }

		// some views might be blocked
	if ($out_loc[ 1]['block']) {
		$out_loc[24]['block']=1;
		$out_loc[ 2]['block']=1;
		$out_loc[ 4]['block']=1;
	}
	if ($out_loc[ 3]['block']) {
		$out_loc[ 4]['block']=1;
		$out_loc[ 5]['block']=1;
		$out_loc[ 6]['block']=1;
	}
	if ($out_loc[ 7]['block']) {
		$out_loc[ 6]['block']=1;
		$out_loc[ 8]['block']=1;
		$out_loc[10]['block']=1;
	}
	if ($out_loc[ 9]['block']) {
		$out_loc[10]['block']=1;
		$out_loc[11]['block']=1;
		$out_loc[12]['block']=1;
	}
	if ($out_loc[13]['block']) {
		$out_loc[12]['block']=1;
		$out_loc[14]['block']=1;
		$out_loc[16]['block']=1;
	}
	if ($out_loc[15]['block']) {
		$out_loc[16]['block']=1;
		$out_loc[17]['block']=1;
		$out_loc[18]['block']=1;
	}
	if ($out_loc[19]['block']) {
		$out_loc[18]['block']=1;
		$out_loc[20]['block']=1;
		$out_loc[22]['block']=1;
	}
	if ($out_loc[21]['block']) {
		$out_loc[22]['block']=1;
		$out_loc[23]['block']=1;
		$out_loc[24]['block']=1;
	}

    $marker_loc = array();

      $close_locs= array(1,3,7,9,13,15,19,21,25);

      $locs = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24);
      foreach ( $locs as $i) {
         if (@$out_loc[$i]['html'] == '') {
    		if (@$out_loc[$i]['block'] == 0 and @$out_loc[$i]['id'] != 0) {
    			$mobs="";
    		  	$mobs = getmobs($out_loc[$i]['id']);
              	$result = mysql_query('SELECT name,image_path,pass FROM phaos_locations WHERE id= \''. $out_loc[$i]['id'].'\'');
              	$row = mysql_fetch_assoc($result);

                $markers= array();
                if(in_array($i,$close_locs) || $character->finding()>=100 ){
                    $ground_items= fetch_items_for_location($out_loc[$i]['id'], $fchance );
                    if(count($ground_items)>0){
                        $markers[] = 'icons/gold.gif';
                    }
                }

    			if($row['pass'] == 'n') {
                    $out_loc[$i]['html'] = draw_square(false, $row['image_path'], $out_loc[$i]['id'],'',$row['name'],$mobs, $markers, $i);
    			} else {
//                    $out_loc[$i]['html'] = draw_square(false, $row['image_path'], $out_loc[$i]['id'],'',$row['name'],$mobs, $markers, $i);
                    $out_loc[$i]['html'] = draw_square(@$out_loc[$i]['link'], $row['image_path'], $out_loc[$i]['id'],'',$row['name'],$mobs, $markers, $i);
    			}

    		} else {
                //HACK: is a hack because it will break if a location is inside but is not named like %Dungeon%
                if( stristr($character_locname,'Dungeon')!== false){
                    $out_loc[$i]['html'] = draw_square(false, "images/land/195.png", 0,'',"Dungeon");
                }else{
                    $out_loc[$i]['html'] = draw_square(false, "images/land/49.png", 0,'',"Water");
                }
    		}


         }
      }

if(@$_COOKIE['_timing']) { echo "<br>time end DC=".endTiming(); };

      return array($out_loc,$marker_loc);
}

//@see also: whos_here() in global.php
function getmobs ($loc) {
    global $info_eol;
	// return monsters that are at this location
	// REALLY long monster names cause problems with mouse-overs
	$mobs['char']= array();
    $mobs['text']= '';

    $res = mysql_query ("SELECT name,race,level,image_path, (username = 'phaos_npc') as isnpc FROM phaos_characters WHERE location = $loc AND username = 'phaos_npc' order by isnpc DESC ");
    if (!$res) {showError(__FILE__,__LINE__,__FUNCTION__); exit;}

    $i=0;
    while ($row = mysql_fetch_array($res)) {
        $mobs['char'][$i++]= $row;
        $mobs['text'].= $info_eol."$row[name]\n$row[race]\nLevel $row[level]\n";
	}
	return $mobs;
}

include "footer.php";
?>
