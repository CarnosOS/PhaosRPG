<?php
include "header.php";
include_once "class_character.php";

$refsidebar= false;

$character = new character($PHP_PHAOS_CHARID);

$char_loc= $character->location;

$location_name =  fetch_value ("SELECT name FROM phaos_locations WHERE id = '$char_loc'");

include_once "location_actions.php";
$pickedup= pickup_actions($character);

if($pickedup>0){
    $refsidebar= true;
    ?><div align="center"><?php
    echo $pickedup." ".$lang_char['itemspickedup'];
    ?></div><?php
}

if($refsidebar){
    refsidebar();
    $refsidebar= false;
}

?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td align="center" valign="top">
			<table border="0" cellspacing="5" cellpadding="0" width="100%">
				<tr>
					<td align="center" colspan="2">
						<img src="lang/<?php echo $lang ?>_images/explore.png">
<br>

<?php

if($char_loc == "") {
	echo ("<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"create_character.php\";',0);</script>\n");
} else {
			print ('<font size=4><b>'. $location_name . '</b></font>
					<br><br>
				</td>
			</tr>');
		$result = mysql_query('SELECT * FROM phaos_buildings WHERE location = \''.$char_loc.'\' ORDER by name ASC');
		while ($row = mysql_fetch_assoc($result))
			{
        //Added by dragzone---
        $name = $row[name];
        if ($name == "Arena") { $insname = "ad_arena"; }
        if ($name == "Blacksmith") { $insname = "ad_blacksmith"; }
        if ($name == "Magic Shop") { $insname = "ad_magic_sh"; }
        if ($name == "Item Shop") { $insname = "ad_itm_sh"; }
        if ($name == "Inn") { $insname = "ad_inn"; }
        if ($name == "Town Hall") { $insname = "ad_twn_hall"; }
        if ($name == "Bank") { $insname = "ad_bank"; }
        if ($name == "Stable") { $insname = "ad_stable"; }
        //--------------------
        print "<tr>
						<td align='center'>
							<a href='$row[type]?shop_id=$row[shop_id]'>$lang_added[$insname]</a>
						</td>
						</tr>";
			}
		}

?>

<tr>
	<td align="center">
		<a href="market.php"><?php echo $lang_area["trade"]; ?></a>
	</td>
</tr>

<?php
//------------- stuff on the ground -------------

auto_ground();//tick

//$fchance= $character->finding();
$fchance= 100; //always find at same place, so you can pick up items you dropped
$ground_items= fetch_items_for_location($char_loc, $fchance );

if(count($ground_items)>0){
    ?><tr><td><center><hr width="50%"><table><?php
    ?><tr><th colspan="4"><?php echo $lang_town["u_find"]; ?></th></tr><?php
    foreach($ground_items as $item){
        $info= fetch_item_additional_info($item,$character);
        $info['number']= $info['number']>1?($info['number']." "):"";
        ?><tr><?php
        ?><td><?php echo makeImg($info['image_path']); ?></td><?php
        ?><td style="color:<?php echo $info['skill_need']; ?>;"><?php echo $info['number'].$info['description']; ?></td><?php
        ?><td><?php
            actionButton($lang_town['pickup'],$_SERVER['PHP_SELF'],
                array(
                    'pickup_id[]'=> $item['id'],
                    'pickup_type[]'=> $item['type'],
                    'pickup_number[]'=> 1
                )
            );
        ?></td><?php
        ?><td><?php
            actionButton($lang_town['pickup_all'],$_SERVER['PHP_SELF'],
                array(
                    'pickup_id[]'=> $item['id'],
                    'pickup_type[]'=> $item['type'],
                    'pickup_number[]'=> $item['number']
                )
            );
        ?></td><?php
        ?></tr><?php
    }
    ?></table></center></td></tr><?php
}

?>

<tr>
	<td align="left">
		<br><br>
		<b><?php echo $lang_town["ot_on"]; ?></b><br>
<?php echo who_is_online($char_loc) ?>
		<br><br>
		<b><?php echo $lang_town["ot_of"]?></b><br>
<?php echo who_is_offline($char_loc) ?>
	</td>
</tr>
</table>

</td>
</tr>
</table>

<?php
include "trailer.php";

mysql_close();

include "footer.php";
?>
