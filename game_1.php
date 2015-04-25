<?php
include "header.php";

include_once "class_character.php";

$character = new character($PHP_PHAOS_CHARID);

$gold_o = $character->gold;
$stamina_o = $character->stamina_points;
$dexterity_o = $character->dexterity;

if(!isset($jackpot)){
    $jackpot = 1;
}

if($gold_o <= "0"){
    echo "<br><br>
    <table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
      <tr>
        <td width='100%'>
        <p align='center'><b><font color='#FF0000'>".$lang_game1["not_en__go"].".</font></b></p>
        <p align='center'><font color='#FF0000'><b>
        <a href='inn.php'>".$lang_clan["back"]."</a></b></font></td>
      </tr>
    </table><br><br>";exit;}
if($stamina_o <= "0"){
    echo "<br><br>
    <table class='utktable' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
      <tr>
        <td width='100%'>
        <p align='center'><b><font color='#FF0000'>".$lang_game1["2_tired"].".</font></b></p>
        <p align='center'><font color='#FF0000'><b>
        <a href='inn.php'>".$lang_clan["back"]."</a></b></font></td>
      </tr>
    </table><br><br>";exit;}

if($jackpot > $gold_o){
$jackpot = "";
$notenoughgold = $lang_game1["not_en__go"];}

$rollgo= "";

function xrand($lo,$hi,$skill){
    global $stamina_o;
    global $dexterity_o;
    global $gold_o;
    $x= rand($lo,$hi);
    if( (1+$skill)*$dexterity_o*83>$gold_o-$stamina_o){
        $y= rand($lo,$hi);
        if($y>$x){
            $x= $y;
        }
    }
    return $x;
}

if($jackpot < "1"){$jackpot = "";}
if($jackpot > "100"){$jackpot = "100";}
$roller = $lang_game1["rollll"];
if( (@$_REQUEST['roll']) && $jackpot > 0) {
    $rollgo = "yes";
    srand ((double)microtime()*1000000);
    $dice_opp_1 = rand(1,6);
    $dice_opp_2 = rand(1,6);
    $dice_opp_3 = rand(1,6);
    $dice_opp_4 = rand(1,6);
    $dice_opp_5 = rand(1,6);
    $dice_opp_all = $dice_opp_1+$dice_opp_2+$dice_opp_3+$dice_opp_4+$dice_opp_5;
    $dice_user_1 = rand(1,6);
    $dice_user_2 = xrand(1,6,$character->lockpick);//we use this skill here, since 0.90 doesn't use this skill anywhere else
    $dice_user_3 = $character->race=='Gnome'? xrand(1,6,2) : rand(1,6);
    $dice_user_4 = xrand(1,6,$character->traps);//we use this skill here, since 0.90 doesn't use this skill anywhere else
    $dice_user_5 = rand(1,6);
    $dice_user_all = $dice_user_1+$dice_user_2+$dice_user_3+$dice_user_4+$dice_user_5;
    	// $stamina_reduce = $stamina_o-10;
    	$stamina_reduce = $stamina_o-1;
    	mysql_query("UPDATE phaos_characters SET stamina = '$stamina_reduce' WHERE username = '$PHP_PHAOS_USER'");
}else{
    $rollgo = "no";
    $dice_user_1 = 1; $dice_user_2 = 1; $dice_user_3 = 1; $dice_user_4 = 1; $dice_user_5 = 1;
    $dice_user_all = 6;
    $dice_opp_1 = 1; $dice_opp_2 = 1; $dice_opp_3 = 1; $dice_opp_4 = 1; $dice_opp_5 = 1;
    $dice_opp_all= 6;
}

echo "<div align='center'>
    <center>
    <img src='./lang/".$lang."_images/inn.png'>
    <br><br>
    <table border='1' cellpadding='0' cellspacing='3' background='./images/body_background.jpg' width='70%'>
  <tr>
    <td width='100%' bgcolor='#008000'>";
echo "<form method='post' action='game_1.php'>
  <div align='center'>
    <center>
    <table border='1' cellpadding='0' cellspacing='0' id='AutoNumber2' width='100%'>
      <tr>
        <td width='100%' bgcolor='#008000' colspan='6' align='center'>".$lang_game1["host_roll"]."
        </td>
      </tr>
      <tr>
        <td width='100%' bgcolor='#008000' colspan='6' align='center'>
            $dice_opp_all
        </td>
      </tr>
      <tr>
        <td width='20%' bgcolor='#008000' align='center'>
        <img border='0' src='./images/games/000$dice_opp_1.gif' width='32' height='32'></td>
        <td width='20%' bgcolor='#008000' align='center'>
        <img border='0' src='./images/games/000$dice_opp_2.gif' width='32' height='32'></td>
        <td width='20%' bgcolor='#008000' align='center' colspan='2'>
        <img border='0' src='./images/games/000$dice_opp_3.gif' width='32' height='32'></td>
        <td width='20%' bgcolor='#008000' align='center'>
        <img border='0' src='./images/games/000$dice_opp_4.gif' width='32' height='32'></td>
        <td width='20%' bgcolor='#008000' align='center'>
        <img border='0' src='./images/games/000$dice_opp_5.gif' width='32' height='32'></td>
      </tr>
      <tr>
        <td width='20%' bgcolor='#008000' align='center'>$dice_opp_1</td>
        <td width='20%' bgcolor='#008000' align='center'>$dice_opp_2</td>
        <td width='20%' bgcolor='#008000' colspan='2' align='center'>$dice_opp_3</td>
        <td width='20%' bgcolor='#008000' align='center'>$dice_opp_4</td>
        <td width='20%' bgcolor='#008000' align='center'>$dice_opp_5</td>
      </tr>
      <tr>
        <td width='100%' bgcolor='#008000' colspan='6' align='center'>".$lang_game1["ur_roll"]."</td>
      </tr>
      <tr>
         <td width='100%' bgcolor='#008000' colspan='6' align='center'>
            $dice_user_all
         </td>
      </tr>
      <tr>
        <td width='50%' bgcolor='#008000' colspan='3' align='center'>
            ".$lang_added["ad_u-bet"]."
        </td>
        <td width='50%' bgcolor='#008000' colspan='3' align='center'>
        <input type='text' name='jackpot' size='5' value='$jackpot'> ".$lang_gold."</td>
      </tr>
      <tr>
        <td width='20%' bgcolor='#008000' align='center'>
        <img border='0' src='./images/games/000$dice_user_1.gif' width='32' height='32'></td>
        <td width='20%' bgcolor='#008000' align='center'>
        <img border='0' src='./images/games/000$dice_user_2.gif' width='32' height='32'></td>
        <td width='20%' bgcolor='#008000' align='center' colspan='2'>
        <img border='0' src='./images/games/000$dice_user_3.gif' width='32' height='32'></td>
        <td width='20%' bgcolor='#008000' align='center'>
        <img border='0' src='./images/games/000$dice_user_4.gif' width='32' height='32'></td>
        <td width='20%' bgcolor='#008000' align='center'>
        <img border='0' src='./images/games/000$dice_user_5.gif' width='32' height='32'></td>
      </tr>
      <tr>
        <td width='20%' bgcolor='#008000' align='center'>$dice_user_1</td>
        <td width='20%' bgcolor='#008000' align='center'>$dice_user_2</td>
        <td width='20%' bgcolor='#008000' colspan='2' align='center'>$dice_user_3</td>
        <td width='20%' bgcolor='#008000' align='center'>$dice_user_4</td>
        <td width='20%' bgcolor='#008000' align='center'>$dice_user_5</td>
      </tr>";


      if($rollgo=='yes' ){
            if( $dice_user_all > $dice_opp_all) {
                //win
              $wingold = $jackpot;

              $result_1 = mysql_query("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
                            if ($row = mysql_fetch_array($result_1)) {
                            $gold_o = $row["gold"];}

                            $gold_o = $gold_o + $wingold;

                        mysql_query("UPDATE phaos_characters SET gold = '$gold_o' WHERE username = '$PHP_PHAOS_USER'");

                        echo " <script language=\"JavaScript\">
                                                <!--
                                                javascript:parent.side_bar.location.reload();
                                                //-->
                                                </script>";
              echo "<tr>
                 <td width='100%' bgcolor='#00a000' colspan='6' align='center'>
                    <font color='#000000' size='4'><b>".$lang_game1["u_won"]."!!</b></font>
                 </td>
              </tr>";
          } if( $dice_user_all < $dice_opp_all) {
                //lose
                $result_1 = mysql_query("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
                        if ($row = mysql_fetch_array($result_1)) {
                        $gold_o = $row["gold"];}

                        $gold_o = $gold_o - $jackpot;

                    mysql_query("UPDATE phaos_characters SET gold = '$gold_o' WHERE username = '$PHP_PHAOS_USER'");

                    echo " <script language=\"JavaScript\">
                                            <!--
                                            javascript:parent.side_bar.location.reload();
                                            //-->
                                            </script>";
                echo "<tr>
                      <td width='100%' bgcolor='#a00000' colspan='6' align='center'>
                       <font color='#000000' size='4'><b>".$lang_game1["u_lost"].".</b></fot>
                      </td>
                      </tr>";
          }
          //Added by dragzone---
          if( $dice_user_all == $dice_opp_all) {
                echo "<tr>
                      <td width='100%' bgcolor='#FF7F2A' colspan='6' align='center'>
                       <font color='#000000' size='4'><b>".$lang_added["ad_g2-drew"].".</b></fot>
                      </td>
                      </tr>";
           }
           //-------------------
          
      } else {
            //pass
            echo "<tr>
                  <td width='100%' bgcolor='#008000' colspan='6' align='center'>
                    <font color='#000000' size='4'><b>...</b></font>
                  </td>
                  </tr>";
      }
  echo "</table>
    </center>
  </div>
  <p align='center'><input type='submit' value='".$lang_game1["rollll"]."' name='roll'></p>
</form>";

echo "</td>
  </tr>
</table>";

echo "<br><div align=center><a href='inn.php'> ".$lang_clan["back"]." </a></td></div>";

include "footer.php";
?>
