<?php

/* part Copyright 2005 peter.schaefer@gmail.com */

include_once "class_character.php";

/*
 * This file contains functions related to shop creation and maintenance
 */


/* execute buy action part of request */
function do_buy(&$character, $shop_basics){
    global $bought, $sorrys, $refresh;

    $ids= @$_REQUEST['buy_id'];
    $types= @$_REQUEST['buy_type'];
    $numbers= @$_REQUEST['buy_number'];

    $bought= 0;
    $sorrys= 0;
    if( $ids && is_array($ids) && $types && is_array($types) ){
        foreach($ids as $key=>$id) {
            $number= @$numbers[$key];
            if(!$number){
                $number=1;
            }
            $item = array('id'=>$id,'type'=>$types[$key],'number'=>$number);
            $info= fetch_item_additional_info($item,&$character);
        	if($info['buy_price']>0 && $character->pay($info['buy_price'])) {
                $item['number']= item_pickup($shop_basics['item_location_id'],$item);
                $bought+= $character->pickup_item($item);
        	} else {
                $sorrys+= $number;
            }
        	$refresh=1;
         }
    }
}

function insert_shop_refill($shop_id, $item_type, $item_value_min, $item_value_growth, $item_value_growth_probability,$item_count_min){
    $query="replace into phaos_shop_refill
          ( shop_id, item_type, item_value_min, item_value_growth, item_value_growth_probability, item_count_min)
    values(  '$shop_id', '$item_type', '$item_value_min', '$item_value_growth', '$item_value_growth_probability','$item_count_min')";

    $req = mysql_query($query);
    if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__,$query); exit;}
}

?>
