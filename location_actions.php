<?php
include_once "items.php";
include_once "class_character.php";

function pickup_actions(&$character){
    if($character->stamina_points<=0){
        return 0;
   }

    $location= $character->location;
    $ids= @$_REQUEST['pickup_id'];

    if(!$ids || !is_array($ids) || count($ids)==0){
        return 0;
    }
    $types= $_REQUEST['pickup_type'];
    $numbers= $_REQUEST['pickup_number'];

    $pickedup= 0;
    foreach($ids as $key=>$id){
        $type= $types[$key];
        $number= $numbers[$key];
        $item= array('id'=>$id,'type'=>$type,'number'=>$number);
        $item['number']= item_pickup($location,$item);
        $pickedup+= $character->pickup_item($item);
    }

    $character->stamina_points-= $pickedup*3;
    $character->update_stamina();

    return $pickedup;
}

function drop_actions(&$character){
    $location= $character->location;
    $ids= @$_REQUEST['drop_id'];
    if(!$ids || !is_array($ids) || count($ids)==0){
        return 0;
    }
    $types= $_REQUEST['drop_type'];
    $numbers= $_REQUEST['drop_number'];

    $dropped= 0;
    foreach($ids as $key=>$id){
        $type= $types[$key];
        $number= $numbers[$key];
        $item= array('id'=>$id,'type'=>$type,'number'=>$number);
        item_drop($location,$item);
        $dropped+= $character->drop_item($item);
    }

    $character->stamina_points-= $dropped;
    $character->update_stamina();
    return $dropped;
}


?>
