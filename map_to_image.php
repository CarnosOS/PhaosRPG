<?php

include "config.php";

$imgsizew = 52;
$imgsizeh = 52;
$border = 0;
$begin_id = $_POST[begin_id];
$end_id = $_POST[end_id];


echo "<html><head>
<title>$SITETITLE</title>
</head><body text='#FFFF00' bgcolor='#000000'>";

$result = mysql_query ("SELECT * FROM phaos_locations WHERE above_left = '0' AND above = '0' AND leftside = '0' AND id >= '$begin_id' AND id < '$end_id'");
if ($row = mysql_fetch_array($result)) {
$start_id = $row["id"];
$image = $row["image_path"];}

$max_width_x = "SELECT * FROM phaos_locations WHERE above = '0' AND id >= '$begin_id' AND id < '$end_id'";
$numresults = mysql_query($max_width_x);
$max_width = mysql_num_rows($numresults);
// echo $max_width."<br>";

$max_height_y = "SELECT * FROM phaos_locations WHERE leftside = '0' AND id >= '$begin_id' AND id < '$end_id'";
$numresults = mysql_query($max_height_y);
$max_height = mysql_num_rows($numresults);
// echo $max_height."<br>";

// COMMENTED OUT SO PEOPLE DON'T WASTE MY BANDWIDTH GENERATING IMAGES -Zeke
// IF YOU UNCOMMENT THIS THERE IS A BUTTON TO TURN THE MAP INTO A SINGLE IMAGE
//echo "
//<form method='POST' action='show-mapimage.php'>
//    <input type='hidden' name='max_width' value='".$max_width."'>
//	<input type='hidden' name='imgsizew' value='".$imgsizew."'>
//    <input type='hidden' name='max_height' value='".$max_height."'>
//    <input type='hidden' name='imgsizeh' value='".$imgsizeh."'>
//	<input type='hidden' name='begin_id' value='$begin_id'>
//	<input type='hidden' name='end_id' value='$end_id'>
//	<p><input type='submit' value='Show Image' name='B1'></p>
//</form>"

echo "<table border='1' cellspacing='1' width='".$max_width*$imgsizew."' height='".$max_height*$imgsizeh."' bordercolorlight='#000000' bordercolordark='#000000'>
		<tr>";

		echo "<td align='left' valign='top'>";

        $N_below = 0;
        for ($y= 1; $y <= $max_height; $y++) {
        	for ($x= 1; $x <= $max_width; $x++) {
        		$S_result = mysql_query("SELECT * FROM phaos_locations WHERE id  = '".$start_id."'");
            	if ($S_row = mysql_fetch_array($S_result)) {
            		$S_id = $S_row["id"];
	                $S_image = $S_row["image_path"];
    	            $S_above_left = $S_row["above_left"];
        	        $S_above = $S_row["above"];
            	    $S_above_right = $S_row["above_right"];
                	$S_left = $S_row["leftside"];
	                $S_right = $S_row["rightside"];
    	            $S_below_left = $S_row["below_left"];
        	        $S_below = $S_row["below"];
            	    $S_below_right = $S_row["below_right"];}

		        	$start_id=$S_right;

	          	echo "<img border='".$border."' src='".$S_image."' width='".$imgsizew."' height='".$imgsizeh."'>";
  		      	if($x == $max_width){echo "<br>";$N_below == 0;$start_id=$N_start_id;}
    		  	if($x == 1 and $N_below == 0){$N_start_id=$S_below;$N_below == 1;}
        	}
        }

        echo "</td>";

	echo "</tr>
</table>";

echo "</body></html>";

?>
