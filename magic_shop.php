<?php
include "header.php";
include_once "class_character.php";

$refresh=0; //determine if the SideBar has to be refreshed

$character=new character($PHP_PHAOS_CHARID);
shop_valid($character->location, $shop_id);  // make sure requested shop is at same location as character

$current_time = time();

if(@$_REQUEST['spell_items']) {

   $result = mysql_query ("SELECT * FROM phaos_spells_items WHERE id = '$spell_items'");
   if ($row = mysql_fetch_array($result)) {
      $price = $row["buy_price"];
	  $req = $row["req_skill"];
   }
   if($character->wisdom*2 < $req){//latest change: characters may buy spells up to twice their wisdom, even if it means they fumble.
       $sorry = $lang_shop["sorry_w"];
   }else if($price*$number_purchased <= $character->gold) {
      if ($number_purchased>20){$number_purchased=20;}
      if($character->pay($price*$number_purchased)) {
        if ($number_purchased < 0) {
        	$character->pay(-$price*$number_purchased);
        	$number_purchased =0;
        	$sorry = $lang_shop["negative"];
        }

        $i= 0;
         while ($i<$number_purchased){
            $i++;
            $character->add_item($spell_items,"spell_items");
         }
      } else {
            $sorry = $lang_shop["sorry"];
      }
      $refresh=1;
   } else {$sorry = $lang_shop["sorry"];}
   $refresh=1;

}

if ($refresh){
   echo " <script language=\"JavaScript\">
               <!--
               javascript:parent.side_bar.location.reload();
               //-->
               </script>";
}
$refresh=0; //be sure to reset refresh-Status

?>

<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%"> 
<tr> 
<td align=center valign=top> 

<table border=0 cellspacing=5 cellpadding=0 width="100%"> 
<tr> 
<td align=center colspan=2> 
<img src="lang/<?php echo $lang ?>_images/magic_shop.png"> 
</td> 
</tr> 
<?php 
echo "<tr><td colspan='2'> <b>".$lang_shop["inv"].":</b></br>";
echo "<table width='60%' align='center'>
			<tr><td align='center'> ".$lang_shop["cap"]." ".$character->max_inventory." ".$lang_shop["items"]."</td></tr>"
			."<tr><td align='center'> ".$lang_shop["item"]." ".$character->invent_count()." ".$lang_shop["items"]."</td></tr>";
echo "</table></td></tr>";
if(@$sorry) {
print ("<tr> 
   <td align=center colspan=2> 
   <big><b><font color=red>$sorry</font></b></big> 
   </td> 
   </tr>"); 
} 

if ($character->invent_count()>$character->max_inventory){ 
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
exit;} 

?> 
<tr> 
<td align=center colspan=2> 
<br> 
<br> 
<b><?php echo $lang_shop["mgc_books"]; ?></b> 
</td> 
</tr> 
<tr> 
<?php 
$line = 0;
$result = mysql_query ("SELECT * FROM phaos_spells_items"); 
if ($row = mysql_fetch_array($result)) { 
   do { 
      echo "<td align='center' valign='top'> ";
      $id = $row["id"];
      $description = $row["name"]; 
      $min_damage = $row["min_damage"];
      $max_damage = $row["max_damage"];
      $buy_price = $row["buy_price"];
      $image_path = $row["image_path"];
      $skill_req = $row["req_skill"];
	  $damage_mess = $row["damage_mess"];
	  if($damage_mess == 0){ 
		  $damage_mess = $lang_shop["mgc_eff1"];
		  } 
	  else {
		  $damage_mess = $lang_shop["mgc_eff2"];
		  } 
       if($skill_req > $character->wisdom) {$skill_need = "red";} else {$skill_need = "#FFFFFF";}

      echo "<form action=\"magic_shop.php\" method=\"post\"><hr><img src=\"$image_path\"><br>";
      echo "<input type=\"hidden\" name=\"spell_items\" value=\"$id\">";
      echo "<input type='hidden' name='shop_id' value='$shop_id'>";
      echo $description."<br>";
      echo $lang_shop["dam"];
      echo "&nbsp;";
		echo $min_damage;
		echo "-";
		echo $max_damage;
		echo "&nbsp";
		echo "($damage_mess)";
		echo "<br>";
		echo "<font color=".$skill_need.">".$lang_shop["req"]." ".$skill_req." ".$lang_wis."</font><br>";
		echo $buy_price;
		echo $lang_shop["gp"];
		echo "<br>".$lang_shop["qu"]." ";
		echo " <input type=\"text\" name=\"number_purchased\" value=1 size=4><input type=\"submit\" value=\"".$lang_shop["purc"]."\">";
      print ("</form>");
    
		$line ++;
		if($line==2){
			echo "</td></tr><tr>";
			$line = 0;
		}else{ 
			echo "</td>";
		}
   } while ($row = mysql_fetch_array($result)); 
} 

?> 
</tr>
</table> 

</td> 
</tr> 
</table> 
<?php include "footer.php"; ?>
