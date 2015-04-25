<?php
include "header.php";
include_once "class_character.php";

$character = new character($PHP_PHAOS_CHARID);

$npc_score = $_GET['npcscore'];
$guess = $_GET['guess'];
$gold_o = $character->gold;
$stamina_o = $character->stamina_points;
	
if($gold_o <= "0")
   {
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



echo '<html>
<head>
</head>

<body>';

//reset npc_score
if (!$npc_score){	$npc_score='0'; }
	
$npc_score = HTMLSpecialChars($npc_score);
$guess = HTMLSpecialChars($guess);
//play with the numbers to ensure inconsistant returns
$rand_num = rand(1,3);

//Added by dragzone---
$ad_array = array("won"=>$lang_added["ad_g2-won"], "lost"=>$lang_added["ad_g2-lost"], "drew"=>$lang_added["ad_g2-drew"], "gambler"=>$lang_added["ad_g2-gambler"], "chose"=>$lang_added["ad_chose"], "stone"=>$lang_added["ad_stone"], "scroll"=>$lang_added["ad_scroll"], "dagger"=>$lang_added["ad_dagger"], "game"=>$lang_added["ad_game"]);
//--------------------

if ($guess == 1)
{
	$player_choice = "Scroll";
	if ($rand_num == 1)
	{
		//mysql_query("UPDATE phaos_characters SET stamina = '$stamina_reduce' WHERE username = '$PHP_PHAOS_USER'");
		$npc_choice = "Scroll";
		display_results($npc_score,$player_choice,$npc_choice,drew,$ad_array);
	}
	if ($rand_num == 2)
	{
		$npc_score = $npc_score + 1;
		//mysql_query("UPDATE phaos_characters SET stamina = '$stamina_reduce' WHERE username = '$PHP_PHAOS_USER'");
		$npc_choice = "Dagger";
		display_results($npc_score,$player_choice,$npc_choice,lost,$ad_array);
	}
	if ($rand_num == 3)
	{
		//mysql_query("UPDATE phaos_characters SET stamina = '$stamina_reduce' WHERE username = '$PHP_PHAOS_USER'");
		$npc_choice = "Stone";
		display_results($npc_score,$player_choice,$npc_choice,won,$ad_array);
	}
}

if ($guess == 2)
{
	$player_choice = "Dagger";
	if ($rand_num == 1)
	{
		//mysql_query("UPDATE phaos_characters SET stamina = '$stamina_reduce' WHERE username = '$PHP_PHAOS_USER'");
		$npc_choice = "Scroll";
		display_results($npc_score,$player_choice,$npc_choice,won,$ad_array);
	}
	if ($rand_num == 2)
	{
		//mysql_query("UPDATE phaos_characters SET stamina = '$stamina_reduce' WHERE username = '$PHP_PHAOS_USER'");
		$npc_choice = "Dagger";
		display_results($npc_score,$player_choice,$npc_choice,drew,$ad_array);
	}
	if ($rand_num == 3)
	{
		$npc_score = $npc_score + 1;
		//mysql_query("UPDATE phaos_characters SET stamina = '$stamina_reduce' WHERE username = '$PHP_PHAOS_USER'");
		$npc_choice = "Stone";
		display_results($npc_score,$player_choice,$npc_choice,lost,$ad_array);
	}
}

//$stamina_reduce = $stamina_o-5;//stamina cost if used

if ($guess == 3)
{
	$player_choice = "Stone";
	
	if ($rand_num == 1)
	{
		$npc_score = $npc_score + 1;
		//mysql_query("UPDATE phaos_characters SET stamina = '$stamina_reduce' WHERE username = '$PHP_PHAOS_USER'");
		$npc_choice = "Scroll";
		display_results($npc_score,$player_choice,$npc_choice,lost,$ad_array);
	}
	
	if ($rand_num == 2)
	{
		//mysql_query("UPDATE phaos_characters SET stamina = '$stamina_reduce' WHERE username = '$PHP_PHAOS_USER'");
		$npc_choice = "Dagger";
		display_results($npc_score,$player_choice,$npc_choice,won,$ad_array);
	}

	if ($rand_num == 3)
	{
		//mysql_query("UPDATE phaos_characters SET stamina = '$stamina_reduce' WHERE username = '$PHP_PHAOS_USER'");
		$npc_choice = "Stone";
		display_results($npc_score,$player_choice,$npc_choice,drew,$ad_array);
  }
}

echo "</p>
<p align='center'>".$lang_added["ad_please-select"].": <a href='".$_SERVER[PHP_SELF]."?guess=1&npcscore=$npc_score'>".$lang_added["ad_scroll"]."</a>, <a href='".$_SERVER[PHP_SELF]."?guess=2&npcscore=$npc_score'>".$lang_added["ad_dagger"]."</a> or <a href='".$_SERVER[PHP_SELF]."?guess=3&npcscore=$npc_score'>".$lang_added["ad_stone"]."</a>?
<br>
</p>";

echo "<br><div align=center><a href='inn.php'> ".$lang_clan["back"]." </a></td></div>";

echo '</body>
</html>';


function display_results($npc_score,$player_choice,$npc_choice,$game_result,$ad_array)
{
	global $character, $PHP_PHAOS_CHARID, $PHP_PHAOS_USER;
	
	//Added by dragzone---
	if ($player_choice == "Scroll") { $player_choice = $ad_array["scroll"]; }
	if ($player_choice == "Dagger") { $player_choice = $ad_array["dagger"]; }
	if ($player_choice == "Stone") { $player_choice = $ad_array["stone"]; }
	if ($npc_choice == "Scroll") { $npc_choice = $ad_array["scroll"]; }
	if ($npc_choice == "Dagger") { $npc_choice = $ad_array["dagger"]; }
	if ($npc_choice == "Stone") { $npc_choice = $ad_array["stone"]; }
	//-------------------
	
	$gambler_image = 'images/icons/npcs/npc_1.gif';
	$gambler_name = $ad_array["gambler"];
	
	//DEBUG
	/*
	echo "Your CHARID: ".$PHP_PHAOS_CHARID." <br>";
	echo "Your GOLD: ".$character->gold." <br>";
	echo "Your STAMINA: ".$character->stamina_points." <br>";
	*/
	
	//We adjust the gold readout. Done singely so it's easy to understand
	$play_cost = '0'; //enter amount to play the game
	$remain = $character->gold - $play_cost; //players gold minus the cost to play a game
	$upgold = $remain + 1; //get the remaining gold and add the required win amount
	$dngold = $remain - 1; //get the remaining gold and subtract the required loss amount
	
	//ok now from the top stuff we get the text and lay it out
	//also we we will update the gold at this point.
	if ($game_result == "won")
	{
		$text1 = $PHP_PHAOS_USER." ".$ad_array["chose"]." ".$player_choice;
		$text2 = $gambler_name." ".$ad_array["chose"]." ".$npc_choice;
		$text3 = $ad_array["won"];
		mysql_query("UPDATE phaos_characters SET gold = '$upgold' WHERE username = '$PHP_PHAOS_USER'");
	}
	if ($game_result == "lost")
	{
		$text1 = $PHP_PHAOS_USER." ".$ad_array["chose"]." ".$player_choice;
		$text2 = $gambler_name." ".$ad_array["chose"]." ".$npc_choice;
		$text3 = $ad_array["lost"];
		mysql_query("UPDATE phaos_characters SET gold = '$dngold' WHERE username = '$PHP_PHAOS_USER'");
	}
	if ($game_result == "drew")
	{
		$text1 = $PHP_PHAOS_USER." ".$ad_array["chose"]." ".$player_choice;
		$text2 = $gambler_name." ".$ad_array["chose"]." ".$npc_choice;
		$text3 = $ad_array["drew"];
		//no update required unless we want to pay to play
	}
	echo '<table cellspacing="2" cellpadding="2" border="1" width="100%">
	<tr>
	<td colspan="2" align="center"><h2>'.$ad_array["stone"].', '.$ad_array["scroll"].', '.$ad_array["dagger"].'-'.$ad_array["game"].'</h2></td>
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

	return;
}
include "footer.php";
?> 
