<?php

include_once "items.php";
include_once "shop_functions.php";
include_once "class_character.php";

$current_time = time();

$basicsquery= "SELECT phaos_shop_basics.*, phaos_buildings.name, phaos_buildings.location FROM phaos_buildings LEFT JOIN phaos_shop_basics USING(shop_id) WHERE location = '".$character->location."' AND phaos_shop_basics.shop_id='$shop_id'";
$shop_basics= fetch_first($basicsquery,__FILE__,__LINE__);

@$shop_basics['shop_id'] or die("shop($shop_id), $shop_basics[name] at($shop_basics[location]) has no shop_basics entry");

//FIXME:

// auto-generate the location the shop stores its items at
if(!$shop_basics['item_location_id']){
    $new_item_location_id= nextLocationIdFromRange('item_storage_location',__FILE__,__LINE__);
    $storage_name= "Store of Shop at $shop_basics[location]";
    $query= "insert into phaos_locations (id,name,special) values ($new_item_location_id,'$storage_name',1)";
    $req = mysql_query($query);
    if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__,$query); exit;}
    //check
    $new_item_location_id= fetch_value("select id from phaos_locations where name='$storage_name' LIMIT 1",__FILE__,__LINE__);
    if($new_item_location_id){
        $query= "update phaos_shop_basics set item_location_id=$new_item_location_id where shop_id='$shop_id'";
        $req = mysql_query($query);
        if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__,$query); exit;}
        //rerun query
        $shop_basics= fetch_first($basicsquery,__FILE__,__LINE__);
    }else{
        die("Failed allocate a location for $storage_name");
    }
}

defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][] = "shop($shop_id) storing items at $shop_basics[item_location_id]";

$current_time= time();

if($shop_basics['restock_time']<=0){
    $restock= 99;
}else{
    $time_since_restock = $current_time-$shop_basics['restock_time'];
    if($time_since_restock >= $shop_basics['restock_time_delta']) {
        if($shop_basics['restock_time_delta']<=0){
            $restock= 99;
        }else{
            $restock= ceil($time_since_restock/$shop_basics['restock_time_delta']);
        }
    }else{
         $restock= 0;
    }
}

//HACK
//defined('DEBUG') and DEBUG and $restock=2;

defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][] = "restock= $restock";

if($restock>0){
    //set update time
	$query = ("UPDATE phaos_shop_basics SET restock_time = $current_time WHERE shop_id = '$shop_id'");
	$req = mysql_query($query);
    if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__,$query); exit;}

    //remove one item
    $item= fetch_random_item_for_location($shop_basics['item_location_id']);
    if($item && $item['id'] && $item['type']!='gold'){
        $item['number']= 1;
        $item['number']= item_pickup($shop_basics['item_location_id'],$item);
        $restock+= $item['number'];
    }

    //take inventory
    $items= fetch_items_for_location($shop_basics['item_location_id']);
    foreach($items as $item){
        @$inventory[$item['type']]+= $item['number'];
    }

    //add items
    $tries= 40; //FIXME: replace the completely random selection of refill candidates by choosing only those with a deficit
    while($restock>0 && $tries-->0){
        $shop_refill= fetch_first("SELECT * FROM phaos_shop_refill WHERE shop_id='$shop_id' order by rand()*item_count_min DESC LIMIT 1",__FILE__,__LINE__);
        if(!$shop_refill){
            break;//stop loop
        }
        if(@$inventory[$shop_refill['item_type']]>= $shop_refill['item_count_min']) {
            continue;//next try
        }
        defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][] = "passed($shop_refill[item_type]):(".(@$inventory[$shop_refill['item_type']]).">=".$shop_refill['item_count_min'].")";

        $minvalue= $shop_refill['item_value_min'];
        $maxvalue= (int)( $minvalue*$shop_refill['item_value_growth']*powrand($shop_refill['item_value_growth'],$shop_refill['item_value_growth_probability'],23) );
        $item= random_item($minvalue,$maxvalue,$shop_refill['item_type'],$shop_refill['item_name_like']);
        defined('DEBUG') and DEBUG and $GLOBALS['debugmsgs'][] = "$item[type]($item[id]) from between values ($minvalue,$maxvalue)";
        if($item) {
            item_drop($shop_basics['item_location_id'],$item);
            @$inventory[$item['type']]+= $item['number'];
            $restock--;
        }
    }

}

//buying section

do_buy($character,$shop_basics);

if($sorrys) {
   	$sorry = $lang_shop["sorry"];
}
?>


