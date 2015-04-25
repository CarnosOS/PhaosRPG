<?php

/* Thanks to all who contribute(d) to this */

/* 
 * This files codes a blacksmith in a style that can be used to code any shop
 * this blacksmith can sell other stuff too
 */

include "header.php";
include_once "items.php";
include_once "shop_functions.php";
include_once "class_character.php";

$refresh = 0; //determine if the SideBar has to be refreshed

$character = new character($PHP_PHAOS_CHARID);
// shop_valid($character->location, 'blacksmith');
shop_valid($character->location, $shop_id);

// auto-generate refills if the shop does not exist yet
$refills = fetch_value("SELECT count(*) FROM phaos_shop_refill WHERE shop_id='$shop_id'",__FILE__,__LINE__);

if ( !$refills ){
    //blacksmith refills v1.0
    //insert_shop_refill($shop_id, 'armor', $item_value_min, $item_value_growth, $item_value_growth_probability, $item_count_min);
    insert_shop_refill($shop_id, 'weapon', rand(1,400), 1.5, 0.83, 5);
    insert_shop_refill($shop_id, 'armor', rand(1,3000), 1.5, 0.70, 1);
    insert_shop_refill($shop_id, 'boots', rand(1,500), 2.0, 0.70, 1);
    insert_shop_refill($shop_id, 'gloves', rand(1,500), 2.0, 0.5, 1);
    insert_shop_refill($shop_id, 'helm', rand(1,700), 2.0, 0.5, 1);
    insert_shop_refill($shop_id, 'shield', rand(1,160), 1.5, 0.5, 1);
}

//generic processing for a shop
include_once "shop_include.php";

?>

<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
<tr>
<td align=center valign=top>

<table border=0 cellspacing=5 cellpadding=0 width="100%">
<tr>
<td align=center colspan=2>
<img src="lang/<?php echo $lang; ?>_images/blacksmith.png">
</td>
</tr>
<?php

echo "<tr><td colspan=2><b>".$lang_shop["inv"]." :</b></br>";
echo "<table width='60%' align='center'>
			<tr><td align='center'> ".$lang_shop["cap"]." ".$character->max_inventory." ".$lang_shop["items"]."</td></tr>"
			."<tr><td align='center'> ".$lang_shop["item"]."  ".$character->invent_count()." ".$lang_shop["items"]."</td></tr>";
echo "</table></td></tr>";

if (@$sorry) {
    print ("<tr>
    	<td align=center colspan=2>
    	<big><b><font color=red>$sorry</font></b></big>
    	</td>
    	</tr>");
}

if ($character->invent_count() > $character->max_inventory){
    print ("<tr>
    	<td align=center colspan=2>
    	<big><b><font color=red>".$lang_shop["inv_full"]."</font></b></big>
    	</td>
    	</tr>");
    print ("<tr>
    	<td align=center colspan=2>
    	<br>
    	<br>
    	<a href=\"town.php\">".$lang_shop["return"]."</a>
    	</td>
    	</tr>");
    exit;
}


?>
<tr>
<td align=center width="50%">
<br>
<br>
<b><?php echo $lang_shop["wep"]; ?></b>
</td>
<td align=center width="50%">
<br>
<br>
<b><?php echo $lang_shop["armor"]; ?></b>
</td>
</tr>
<tr>
<td align=center valign=top width="50%">
<?php

$items = fetch_items_for_location($shop_basics['item_location_id']);

if (is_array($items) && count($items)>0){
    foreach($items as $item){
        if($item['type'] == 'weapon') {
            $info = fetch_item_additional_info($item,$character);
            if ($info) {
                print ("<form action=\"$_SERVER[PHP_SELF]\" method=\"post\">
                	<hr><img src=\"$info[image_path]\"><br>");
                print ("<input type=\"hidden\" name=\"buy_id[]\" value=\"$item[id]\">
                	<input type=\"hidden\" name=\"buy_type[]\" value=\"$item[type]\">
                	<input type=\"hidden\" name=\"buy_number[]\" value=\"1\">
                	<input type=\"hidden\" name=\"shop_id\" value=\"$shop_id\">
                	  $info[description]<br>
                	  ".$lang_shop["dam"]." $info[min_damage]-$info[max_damage]<br>
                	  <font color=$info[skill_need]>".$lang_shop["req"]." $info[skill_req] ".$lang_att."</font><br>
                	  $info[buy_price] ".$lang_shop["gp"]."<br>
                	<input type=submit value=".$lang_shop["purc"].">");
                print ("</form>");
            }
        }
    }
}else{
    ?><h2><?php echo $lang_shop["sold_out"]; ?></h2><?php
}

?>
</td>
<td align=center valign=top width="50%">
<?php
if(is_array($items) && count($items)>0){
    foreach($items as $item){
        $info = fetch_item_additional_info($item,$character);
        if(in_array($item['type'],$armorItems)) {//FIXME-TODO: this only tested for armor ATM
            if ($info) {
                print ("<form action=\"$_SERVER[PHP_SELF]\" method=\"post\">
                	<hr><img src=\"$info[image_path]\"><br>");
                print ("<input type=\"hidden\" name=\"buy_id[]\" value=\"$item[id]\">
                	<input type=\"hidden\" name=\"buy_type[]\" value=\"$item[type]\">
                	<input type=\"hidden\" name=\"buy_number[]\" value=\"1\">
                	<input type=\"hidden\" name=\"shop_id\" value=\"$shop_id\">
                	  $info[description]<br>
                	  ".$lang_shop["ac"]." $info[armor_class]<br>
                	  <font color=$info[skill_need]>".$lang_shop["req"]." $info[skill_req] ".$lang_def."</font><br>
                	  $info[buy_price] ".$lang_shop["gp"]."<br>
                	<input type=submit value=".$lang_shop["purc"].">");
                print ("</form>");
            }
        }else if( $item['type'] == 'spell_items') {
                //UNTESTED, gives bad formatting
                echo "<form action=\"$_SERVER[PHP_SELF]\" method=\"post\"><hr><img src=\"$info[image_path]\"><br>";
                print ("<input type=\"hidden\" name=\"buy_id[]\" value=\"$item[id]\">
                	<input type=\"hidden\" name=\"buy_type[]\" value=\"$item[type]\">
                	<input type=\"hidden\" name=\"shop_id\" value=\"$shop_id\">");
                echo $info['description']."<br>";
                echo $lang_shop["dam"];
                echo "&nbsp;";
                echo $info['min_damage'];
                echo "-";
                echo $info['max_damage'];
                echo "&nbsp";
                echo "($info[damage_mess])";
                echo "<br>";
                echo "<font color=".$info['skill_need'].">".$lang_shop["req"]." ".$info['skill_req']." ".$lang_wis."</font><br>";
                echo $info['buy_price'];
                echo $lang_shop["gp"];
                echo "<br>".$lang_shop["qu"]." ";
                echo " <input type=\"text\" name=\"buy_number[]\" value=1 size=4>/$item[number]<input type=\"submit\" value=\"".$lang_shop["purc"]."\">";
                echo "</form>";
            }else if( $item['type'] == 'potion') {
                //UNTESTED, gives bad formatting
        		print ("<table border=0 width=\"100%\"><tr><td colspan=\"5\"><hr></td></tr><tr>
        			<td><img src='$info[image_path]'></td><td>$item[number] $info[description]</td>
        			<td>$info[sell_price] $lang_shop[gp]</td>
        			<td>	<form action='$_SERVER[PHP_SELF]' method='post'>
        				<input type='hidden' name='shop_id' value='$shop_id'>
        				<input type='hidden' name='buy_id[]' value='$item[id]'>
        				<input type='hidden' name='buy_type[]' value='$item[type]'>
        				<input type='hidden' name='buy_number[]' value='1'>
        				<input type='submit' value='$lang_shop[purc]'>
        				</form>
        			</td>
        			<td>	<form action='shop.php' method='post'>
        				<input type='hidden' name='shop_id' value='$shop_id'>
        				<input type='hidden' name='buy_id[]' value='$item[id]'>
        				<input type='hidden' name='buy_type[]' value='$item[type]'>
        				<input type='hidden' name='buy_number[]' value='$item[number]'>
        				<input type='submit' value='$lang_shop[purcall]'>
        				</form>
        			</td>
        			</tr></table>");
        }else{
              // gold ^^ ?
        }
    }
}else{
    ?><h2><?php echo $lang_shop["sold_out"]; ?></h2><?php
}


?>
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
