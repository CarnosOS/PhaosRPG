<?php
include "aup.php";

$character = new character($PHP_PHAOS_CHARID);

// make sure the requested shop is where the player is
if (!($shop_id = shop_valid($character->location, 'inn.php'))) {
        include "header.php";
	echo $lang_markt["no_sell"].'</body></html>';
	exit;
}

$gold_o = intval($character->gold);
$stamina_o = intval($character->stamina_points);

if($gold_o <= "0")
   {
    include "header.php";
    echo "<br><br>
    <table border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
      <tr>
        <td width='100%'>
        <p align='center'><b><font color='#FF0000'>".$lang_game1["not_en__go"].".</font></b></p>
        <p align='center'><font color='#FF0000'><b>
        <a href='inn.php'>".$lang_clan["back"]."</a></b></font></td>
      </tr>
    </table><br><br>";
   exit;
   }
if($stamina_o <= "0")
   {
    include "header.php";
    echo "<br><br>
    <table border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='98%'>
      <tr>
        <td width='100%'>
        <p align='center'><b><font color='#FF0000'>".$lang_game1["2_tired"].".</font></b></p>
        <p align='center'><font color='#FF0000'><b>
        <a href='inn.php'>".$lang_clan["back"]."</a></b></font></td>
      </tr>
    </table><br><br>";
   exit;
   }
   
//end stamina and gold check


//reset npc_score
$npc_score = isset($_GET['npcscore']) ? intval($_GET['npcscore']) : 0;
$guess = isset($_GET['guess']) ? intval($_GET['guess']) : 0;
$show_results = false;

$num_to_choice = array(
    1 => "Scroll",
    2 => "Dagger",
    3 => "Stone"
);

if ($guess >= 1 && $guess <= 3) {
  $player_choice = $num_to_choice[$guess];
  $npc_choice = $num_to_choice[rand(1,3)];
  $game_result = 'draw';
  $show_results = true;

  if (($player_choice === 'Scroll' AND $npc_choice === 'Dagger')
    OR ($player_choice === 'Dagger' AND $npc_choice === 'Stone')
    OR ($player_choice === 'Stone' AND $npc_choice === 'Scroll')) {
    $npc_score += 1;
    $newgold = $gold_o - 1;
    $game_result = 'lost';
  }

  if (($player_choice === 'Dagger' AND $npc_choice === 'Scroll')
    OR ($player_choice === 'Stone' AND $npc_choice === 'Dagger')
    OR ($player_choice === 'Scroll' AND $npc_choice === 'Stone')) {
    $newgold = $gold_o + 1;
    $game_result = 'win';
  }

  if ($game_result !== 'draw') {
    mysql_query("UPDATE phaos_characters SET gold = '$newgold' WHERE username = '$PHP_PHAOS_USER'");
  }
}

//--------------------

include "header.php";

if ($show_results) {
  $gambler_image = 'images/icons/npcs/npc_1.gif';
  $gambler_name = $lang_added["ad_g2-gambler"];

  if ($game_result == "win")
  {
          $text1 = $PHP_PHAOS_USER." ".$lang_added["ad_chose"]." ".$player_choice;
          $text2 = $gambler_name." ".$lang_added["ad_chose"]." ".$npc_choice;
          $text3 = $lang_added["ad_g2-won"];
  }
  if ($game_result == "lost")
  {
          $text1 = $PHP_PHAOS_USER." ".$lang_added["ad_chose"]." ".$player_choice;
          $text2 = $gambler_name." ".$lang_added["ad_chose"]." ".$npc_choice;
          $text3 = $lang_added["ad_g2-lost"];
  }
  if ($game_result == "draw")
  {
          $text1 = $PHP_PHAOS_USER." ".$lang_added["ad_chose"]." ".$player_choice;
          $text2 = $gambler_name." ".$lang_added["ad_chose"]." ".$npc_choice;
          $text3 = $lang_added["ad_g2-drew"];
  }

  echo '<table cellspacing="2" cellpadding="2" border="1" width="100%">
  <tr>
  <td colspan="2" align="center"><h2>'
    .$lang_added["ad_stone"].', '
    .$lang_added["ad_scroll"].', '
    .$lang_added["ad_dagger"].'-'
    .$lang_added["ad_game"]
    .'</h2></td>
  </tr><tr align="center">
  <td width="50%">
          '.$character->name.'<br>
          <img src="'.$character->image.'"><br>
          '.$text1.'<br>
          '.$character->gold.'<br>
  </td>
  <td width="50%">
          '.$gambler_name.'<br>
          <img src="'.$gambler_image.'"><br>
          '.$text2.'<br>
          '.$npc_score.'<br>
  </td>
  </tr>
  <tr style="background:#004400;">
  <td colspan="2" align="center">
          <h1>'.$text3.'</h1>
  </td>
  </tr>
  </table>';
}


echo "</p>
<p align='center'>".$lang_added["ad_please-select"].": <a href='".$_SERVER[PHP_SELF]."?guess=1&npcscore=$npc_score'>".$lang_added["ad_scroll"]."</a>, <a href='".$_SERVER[PHP_SELF]."?guess=2&npcscore=$npc_score'>".$lang_added["ad_dagger"]."</a> or <a href='".$_SERVER[PHP_SELF]."?guess=3&npcscore=$npc_score'>".$lang_added["ad_stone"]."</a>?
<br>
</p>";

echo "<br><div align=center><a href='inn.php'> ".$lang_clan["back"]." </a></td></div>";

echo '</body>
</html>';
