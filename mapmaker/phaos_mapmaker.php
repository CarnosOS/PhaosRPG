<?php
session_start();

include "folder_func.php";

/*$_GET*/
if(!isset($sess_id)) {
// echo "wird gemacht OK.<br>";
$sess_id = session_id();
session_register("map_name");
session_register("map_mapx");
session_register("map_mapy");
session_register("map_fill");
session_register("map");
session_register("map_locname");
session_register("map_x");
session_register("map_mapxx");
session_register("map_mapyy");
session_register("tile_name");
session_register("menu");
session_register("in_phaos");
session_register("map_mapvolder");
session_register("map_ext");
session_register("image_volder");
session_register("maxtile");
session_register("tilesize");
session_register("tileformat");
session_register("tile_id_start");
session_register("tile_id_start_n");
session_register("tile_id_start_a");
session_register("buildings");
session_register("pass");
session_register("sqlall");
session_register("tileborder");
/*$_SESSION['map_name'];
$_SESSION['map_mapx'];
$_SESSION['map_mapy'];
$_SESSION['map_fill'];
$_SESSION['map'];
$_SESSION['map_x'];
$_SESSION['map_mapxx'];
$_SESSION['map_mapyy'];
$_SESSION['tile_name'];
$_SESSION['menu'];
$_SESSION['in_phaos'];
$_SESSION['map_mapvolder'];
$_SESSION['map_ext'];
$_SESSION['image_volder'];
$_SESSION['maxtile'];
$_SESSION['tilesize'];
$_SESSION['tileformat'];
$_SESSION['tile_id_start'];
$_SESSION['tile_id_start_n'];
$_SESSION['tile_id_start_a'];
$_SESSION['buildings'];*/
$_SESSION['newtilepicn'];
$_SESSION['tilex'];
$_SESSION['tiley'];
$_SESSION['newtilepic'];
$_SESSION['newtilepic_n'];
}

// echo strip_tags(SID);

if($menue == ""){

$in_phaos = "../";
$map_mapvolder = "maps/";
$map_ext = ".sql";
$image_volder = $in_phaos."images/land/";
$image_special_volder = $in_phaos."images/land/";

/*echo "xXx";*/
$menue = "1";
$map_name = $map_name_s;
$map_fill = $map_fill_s;
$map_mapx = $map_width_s-1;
$map_mapy = $map_height_s-1;
$tilesize = "22";
$tilesize_tile = "32";
$tileformat = ".png";
$map_tile = "";
$tile_id_start = $tile_id_start_s;
$tile_id_start_n = $tile_id_start;
// $tile_id_start_a = $tile_id_start_s;
// echo $tile_id_start_a;
$map = "";
$map_x = "";
$map_mapxx = "";
$map_mapyy = "";
$tilex = "0";
$tiley = "0";
$tileborder = "0";
// <Start Tile für Fülung für leere Map ------

// if($menue == "1"){
if($map_fill == "1") {$map_tile = "2" ;$tile_name = "2" ;$newtilepic = "2" ;}
if($map_fill == "2") {$map_tile = "49";$tile_name = "49";$newtilepic = "49";}
if($map_fill == "3") {$map_tile = "4" ;$tile_name = "4" ;$newtilepic = "4" ;}
if($map_fill == "4") {$map_tile = "1" ;$tile_name = "1" ;$newtilepic = "1" ;}
if($map_fill == "5") {$map_tile = "empty" ;$tile_name = "empty" ;$newtilepic = "empty" ;}
if($map_fill == "6") {$map_tile = "forrest_g" ;$tile_name = "forrest_g" ;$newtilepic = "forrest_g" ;}
if($map_fill == "99"){$map_tile = "0" ;$tile_name = "0" ;$newtilepic = "0" ;}
// }
// <End Fülung für leere Map ------
}

if($null == "Y"){$tile_id_start = $tile_id_start_n;}

if($menue == "1" and $map_name == ""){echo "Map Name muss angegeben werden";$menue = "";}

if($menue == "1" and $map_mapx+1 == ""){echo "Map Breite mus angegeben werden";$menue = "";}

if($menue == "1" and $map_mapx+1 < "10"){echo "Map Breite zu klein (min = 10 Max = 100)";$menue = "";}

if($menue == "1" and $map_mapx+1 > "100"){echo "Map Breite zu groß (min = 10 Max = 100)";$menue = "";}

if($menue == "1" and $map_mapy+1 == ""){echo "Map Höhe mus angegeben werden";$menue = "";}

if($menue == "1" and $map_mapy+1 < "10"){echo "Map Höhe zu klein (min = 10 Max = 100)";$menue = "";}

if($menue == "1" and $map_mapy+1 > "100"){echo "Map Höhe zu groß (min = 10 Max = 100)";$menue = "";}

if($menue == "1" and file_exists($map_mapvolder.$map_name.$map_ext)){
echo "Eine Map mit dem Namen '$map_name' existiert bereitz Wähle einen anderen Namen.
		<a href='index.php'>[ Back ]</a> :";$menue = "";}

// if($null == "Y"){$tile_id_start = "10";}

echo "<html><head>
<meta http-equiv='Content-Language' content='de'>
<meta http-equiv='Content-Type' content='text/html; charset=windows-1252'>
<title>Phaos Mapmaker v0.02</title>
</head><body link='#FFCC00' vlink='#FF9933' alink='#FF0000' text='#FFCC66' bgcolor='#000000'>";

if($menue == "4"){

/*echo "Menue 4:<br>";*/
	$newtilepicn = preg_replace("/".$tileformat."/", "", $newtilepic);
    $newtilepic_n = $newtilepicn;
	$map[$tiley][$tilex] = $newtilepicn;
    if($newtilepicn == "62"){echo "
<table border='1' cellspacing='1' width='100%'>
	<tr>
		<td>
		<form method='POST' action='phaos_mapmaker.php'>
			Wie soll dieser Ort Heißen ? :
			<input type='text' name='T1' size='20'>
			<input type='submit' value='OK' name='B1'>
		</form>
		</td>
	</tr>
</table>
";}
	$menue = "2";
}

/*echo "MapX>$map_mapx : MapY>$map_mapy : MapName>$map_name : Menu>$menue : MapFill>$map_fill : Maptile>$map_tile : Tile_name>$tile_name : newtilepic>$newtilepic : tilex>$tilex : tiley>$tiley<br>";*/

// <0 START> Hier Wird ----------
if($menue == "1"){
//echo "Menue 1:<br>";
    for ($y = 0; $y <= $map_mapy; $y++) {
        for ($x = 0; $x <= $map_mapx; $x++) {
        $map[$y][$x] = $map_tile;
		$map_locname[$y][$x] = "Blank";
		if ($map[$y][$x] == "2") {
		$map_locname[$y][$x] = "Wilderness";
		}
		if ($map_locname[$y][$x] == "Blank") {
		$map_locname[$y][$x] = "Wilderness";
		} 
        /*echo $map[$y][$x]."<br>";*/
        $menue = "2";
        }
    }
}
// <0 END> ----------

// <1 START> Hier Wird Di elehre Map angezeigt ----------
if($menue == "2"){
/*echo "Menue 2:<br>";*/
    /*$map_x = 100/$map_mapx;*/
$newtilepicn = preg_replace("/".$tileformat."/", "", $newtilepic);
echo "
<table border='1' cellspacing='2' width='100%' bgcolor='#FFCC00'>
    <tr>
        <td width='100%' colspan='2' bgcolor='#000000'>
        	Menü : <font color='#B89554'><b>Map Anzeige</b></font> |
         	Mapname : <font color='#B89554'><b>".$map_name."</b></font> |
            Map Width : <font color='#B89554'><b>".($map_mapx+1)."</b></font> |
            Map Height : <font color='#B89554'><b>".($map_mapy+1)."</b></font> |
            SQL ID Start : <font color='#B89554'><b>".$tile_id_start_a."</b></font></td>
        <td rowspan='2' bgcolor='#000000'>
             <table border='1' cellspacing='1'>
                <tr>
                    <td width='100%' bgcolor='#000000' align='center' nowrap>Current Tile</td>
                </tr>
                <tr>
                    <td width='100%' bgcolor='#000000' align='center'>
                        <img border='0' src='".$image_volder.$newtilepicn.$tileformat."' width='64' height='64'></td>
                </tr>
                <tr>
                    <td width='100%' bgcolor='#000000' align='center'>".$newtilepicn.$tileformat."</td>
                </tr>
             </table>
        </td>
    </tr>
    <tr>
        <td bgcolor='#000000'>
            <a href='index.php'>[ New Map ]</a> :
          	<a href='phaos_mapmaker.php?menue=5&tilex=$x&tiley=$y&newtilepic=$newtilepic'>[ Save Map ]</a> :
            <a href='phaos_mapmaker.php?#'>[ Clear Map ]</a> :
            <a href='phaos_mapmaker.php?menue=3&tilex=$x&tiley=$y&newtilepic=".$newtilepic."'>[ Get Tile ]</a> :
            <a href='phaos_mapmaker.php?menue=4&tilex=$x&tiley=$y&newtilepic=$newtilepic'>[ Map Refresh ]</a>";

            // <a href='phaos_mapmaker.php?menue=6&tilex=$x&tiley=$y&newtilepic=".$all_tile_pics[$i]."'>Special Tile</a> :
            // <a href='phaos_mapmaker.php?#'>Edit event</a> :

  echo "</td>
    </tr>
</table>

<br>
<div align='center'>
  <center>
<table border='1' cellspacing='4' cellpadding='0' bgcolor='#FFCC00'>
  <tr>
    <td width='100%' bgcolor='#000000'>
<table border='".$tileborder."' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#FFCC00'>";
    for ($y = 0; $y <= $map_mapy; $y++) {
    echo "<tr>";
        for ($x = 0; $x <= $map_mapx; $x++) {
            /*echo $image_volder.$map[$y][$x];*/
            echo "<td><a title='".$map[$y][$x]."' href='phaos_mapmaker.php?menue=4&tilex=".$x."&tiley=".$y."&newtilepic=".$newtilepic."'><img border='0' src='".$image_volder.$map[$y][$x].$tileformat."' width='".$tilesize."' height='".$tilesize."'></a></td>";
            }
    echo "</tr>";}
echo "</table>
    </td>
  </tr>
</table>
</center>
</div>";}
// <1 END> --------------------


// <3 START> Hier Werden alle zurverfügung stehenden Tiles in 32x32 angezeit ----------
if($menue == "3"){

echo "<table border='1' cellspacing='2' width='100%' bgcolor='#FFCC00'>
    <tr>
        <td width='100%' colspan='2' bgcolor='#000000'>Menü : <font color='#B89554'><b>Tile</b></font> | Info : <font color='#B89554'><b>Suche dir Das Tile aus Das du setzen Möchtest.</b></font></td>
        <td rowspan='2' bgcolor='#000000'>
             <table border='1' cellspacing='1' id='AutoNumber1'>
                <tr>
                    <td width='100%' bgcolor='#000000' align='center' nowrap>Aktuelles Tile</td>
                </tr>
                <tr>
                    <td width='100%' bgcolor='#000000' align='center'>
                        <img border='0' src='".$image_volder.$newtilepic."' width='64' height='64'></td>
                </tr>
                <tr>
                    <td width='100%' bgcolor='#000000' align='center'>
                        ".$newtilepic."</td>
                </tr>
             </table>
        </td>
    </tr>
    <tr>
        <td bgcolor='#000000'>
            <a href='index.php'>[ New Map ]</a> :
            <a href='phaos_mapmaker.php?menue=4&tilex=$x&tiley=$y&newtilepic=$newtilepic'>[ Map Refresh ]</a>";
          	// <a href='phaos_mapmaker.php?menue=5&tilex=$x&tiley=$y&newtilepic=$newtilepic'>Save Map</a> :
            // <a href='phaos_mapmaker.php?#'>Clear Map</a> :
            // <a href='phaos_mapmaker.php?menue=3&tilex=$x&tiley=$y&newtilepic=".$all_tile_pics[$i]."'>Get Tile</a> :
            // <a href='phaos_mapmaker.php?menue=6&tilex=$x&tiley=$y&newtilepic=".$all_tile_pics[$i]."'>Special Tile</a> :
            // <a href='phaos_mapmaker.php?#'>Edit event</a> :

       echo " </td>
    </tr>
</table>

<br>";
//$tilex = $tilex;
//$tiley = $tiley;
// echo "geht it<br>";
// do_image_list("$image_volder");

// $map_x = 100/$map_mapxx;

$i=0;
$handle=opendir($image_volder);

//while ($file=readdir($handle)){
//  if($file!=".." && $file!="."){
//  if(is_dir($folder."/".$file)){ }
//  else{
//$handle=opendir("./".$folder."/");
while ($file = readdir($handle)) {
if ($file == "." || $file == "..") { } else {

  $all_tile_pics[$i] = $file;
  //}

  }$i++;
}

closedir($handle);

$maxtile = $i-1;

$map_mapxx = 32;
$map_mapyy = 17;

$i = 1;
echo "<table border='".$tileborder."' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#FFCC00'>
<tr><td>";
for ($yy = 0; $yy <= $map_mapyy; $yy++) {

	for ($xx = 0; $xx <= $map_mapxx; $xx++) {$i++;$br++;

    	echo "<a title='".$all_tile_pics[$i]."' href='phaos_mapmaker.php?menue=4&tilex=$tilex&tiley=$tiley&newtilepic=".$all_tile_pics[$i]."'><img border='1' src='".$image_volder.$all_tile_pics[$i]."'></a>"; 
        if($br > $map_mapxx){echo "<br>";$br=0;}
        if($maxtile == $i){exit;}
	}
}
echo "</td></tr></table>";
}
// <3 END>--------------------

if($menue == "5"){
echo "
<table border='1' cellspacing='2' width='100%' bgcolor='#FFCC00'>
    <tr>
        <td width='100%' colspan='2' bgcolor='#000000'>Menü : <font color='#B89554'><b>Map Save</b></font> | Info : <font color='#B89554'><b>Map Speichern.</b></font></td>
        <td rowspan='2' bgcolor='#000000'>
             <table border='1' cellspacing='1' id='AutoNumber1'>
                <tr>
                    <td width='100%' bgcolor='#000000' align='center' nowrap>Aktuelles Tile</td>
                </tr>
                <tr>
                    <td width='100%' bgcolor='#000000' align='center'>
                        <img border='0' src='".$image_volder.$newtilepic."' width='64' height='64'></td>
                </tr>
                <tr>
                    <td width='100%' bgcolor='#000000' align='center'>
                        ".$newtilepic."</td>
                </tr>
             </table>
        </td>
    </tr>
    <tr>
        <td bgcolor='#000000'>
        	<a href='phaos_mapmaker.php?menue=4&tilex=$x&tiley=$y&newtilepic=$newtilepic&null=Y'>[ Back ]</a> :
            <a href='phaos_mapmaker.php?menue=10&tilex=$x&tiley=$y&newtilepic=$newtilepic&null=Y&save=Y'>[ Save Map ]</a>";
            //<a href='index.php'>New Map</a> :
          	// <a href='phaos_mapmaker.php?menue=5&tilex=$x&tiley=$y&newtilepic=$newtilepic'>Save Map</a> :
            // <a href='phaos_mapmaker.php?#'>Clear Map</a> :
            // <a href='phaos_mapmaker.php?menue=3&tilex=$x&tiley=$y&newtilepic=".$all_tile_pics[$i]."'>Get Tile</a> :
            // <a href='phaos_mapmaker.php?menue=6&tilex=$x&tiley=$y&newtilepic=".$all_tile_pics[$i]."'>Special Tile</a> :
            // <a href='phaos_mapmaker.php?#'>Edit event</a> :
            // <a href='phaos_mapmaker.php?menue=4&tilex=$x&tiley=$y&newtilepic=$newtilepic'>Map Refresh</a>
  echo "</td>
    </tr>
</table><br>
<table border='1' cellspacing='1' width='100%' id='AutoNumber1'>
  <tr>
    <td width='100%'>
    <form method='POST' action='--WEBBOT-SELF--'>
      <p align='center'>
      INSERT INTO `phaos_locations` VALUES (id, name, above_left, above, above_right, left, right, below_left, below, below_right, image_path, special, buildings, pass);
      <textarea style='font-size: 8pt; color: #FFFF00; background-color: #550000' rows='30' name='S1' cols='150'>";

	for ($y = 0; $y <= $map_mapy; $y++) {
    	for ($x = 0; $x <= $map_mapx; $x++) {
        	// echo $map[$y-1][$x-1]."\n";
            // echo $map[$y-1][$x]."\n";
            // echo $map[$y-1][$x+1]."\n";
            // echo $map[$y][$x-1]."\n";
            // echo $map[$y][$x]."\n";
            // echo $map[$y][$x+1]."\n";
            // echo $map[$y+1][$x-1]."\n";
            // echo $map[$y+1][$x]."\n";
            // echo $map[$y+1][$x+1]."\n";
            // echo "tile_id_start=".$tile_id_start." ".$map_mapx."\n";
            // echo $map[($y-1)-$map_mapx][($x-1)-$map_mapx]."\n";
	        // echo "X=".$x." Y=".$y." tile_id_start_n=".$tile_id_start_n." tile_id_start=".$tile_id_start."\n";

            $al = $map[$y-1][$x-1]; if($al == ""){$al = 0;}else{$al = $tile_id_start-($map_mapx+2);}
            $ab = $map[$y-1][$x]  ; if($ab == ""){$ab = 0;}else{$ab = $tile_id_start-($map_mapx+1);}
            $ar = $map[$y-1][$x+1]; if($ar == ""){$ar = 0;}else{$ar = $tile_id_start-($map_mapx);}
            $ls = $map[$y][$x-1]  ; if($ls == ""){$ls = 0;}else{$ls = $tile_id_start-1;}
            $mi = $map[$y][$x]    ; if($mi == ""){$mi = 0;}else{$mi = $tile_id_start;}
            $rs = $map[$y][$x+1]  ; if($rs == ""){$rs = 0;}else{$rs = $tile_id_start+1;}
            $bl = $map[$y+1][$x-1]; if($bl == ""){$bl = 0;}else{$bl = $tile_id_start+($map_mapx);}
            $be = $map[$y+1][$x]  ; if($be == ""){$be = 0;}else{$be = $tile_id_start+($map_mapx+1);}
            $br = $map[$y+1][$x+1]; if($br == ""){$br = 0;}else{$br = $tile_id_start+($map_mapx+2);}

			$pass = "y";
            $buildings = "n";
			if ($image_volder == "images/dungeon/") {$pass = "n";}
			if($map[$y][$x] == "floor"){$map_locname[$y][$x] = "Floor"; $pass = "y";}
			if($map[$y][$x] == "door1"){$map_locname[$y][$x] = "Door"; $pass = "y";}
			if($map[$y][$x] == "door2"){$map_locname[$y][$x] = "Door"; $pass = "y";}
			if($map[$y][$x] == "door3"){$map_locname[$y][$x] = "Door"; $pass = "y";}
			if($map[$y][$x] == "door4"){$map_locname[$y][$x] = "Door"; $pass = "y";}
			if($map[$y][$x] == "stair1"){$map_locname[$y][$x] = "Stairs"; $pass = "y";}
			if($map[$y][$x] == "stair2"){$map_locname[$y][$x] = "Stairs"; $pass = "y";}
			if($map[$y][$x] == "stair3"){$map_locname[$y][$x] = "Stairs"; $pass = "y";}
			if($map[$y][$x] == "stair4"){$map_locname[$y][$x] = "Stairs"; $pass = "y";}
            if($map[$y][$x] == 49){$map_locname[$y][$x] = "Water"; $pass = "n";}
            if($map[$y][$x] == 62){$buildings = "y"; $map_locname[$y][$x] = "House";}
            if($map[$y][$x] == 63){$buildings = "y"; $map_locname[$y][$x] = "House";}
            if($map[$y][$x] == 64){$buildings = "y"; $map_locname[$y][$x] = "House";}
            if($map[$y][$x] == 69){$buildings = "y"; $map_locname[$y][$x] = "Village";}
            if($map[$y][$x] == 70){$buildings = "y"; $map_locname[$y][$x] = "Village";}
            if($map[$y][$x] == 71){$buildings = "y"; $map_locname[$y][$x] = "Village";}
            if($map[$y][$x] == 72){$buildings = "y"; $map_locname[$y][$x] = "Town";}
            if($map[$y][$x] == 73){$buildings = "y"; $map_locname[$y][$x] = "Town";}
            if($map[$y][$x] == 74){$buildings = "y"; $map_locname[$y][$x] = "Town";}
            if($map[$y][$x] == 75){$buildings = "y"; $map_locname[$y][$x] = "Gate";}
            if($map[$y][$x] == 76){$buildings = "y"; $map_locname[$y][$x] = "Gate";}
            if($map[$y][$x] == 77){$buildings = "y"; $map_locname[$y][$x] = "Gate";}
            if($map[$y][$x] == 32){$buildings = "n"; $map_locname[$y][$x] = "Cave";}
            if($map[$y][$x] == 199){$buildings = "n"; $map_locname[$y][$x] = "Road";}
            if($map[$y][$x] == 200){$buildings = "n"; $map_locname[$y][$x] = "Road";}
            if($map[$y][$x] == 201){$buildings = "n"; $map_locname[$y][$x] = "Road";}
            if($map[$y][$x] == 202){$buildings = "n"; $map_locname[$y][$x] = "Road";}
            if($map[$y][$x] == 203){$buildings = "n"; $map_locname[$y][$x] = "Road";}
            if($map[$y][$x] == 204){$buildings = "n"; $map_locname[$y][$x] = "Road";}
            if($map[$y][$x] == 205){$buildings = "y"; $map_locname[$y][$x] = "Tower";}
            if($map[$y][$x] == 206){$buildings = "y"; $map_locname[$y][$x] = "Temple";}
            if($map[$y][$x] == 207){$buildings = "y"; $map_locname[$y][$x] = "Ship";}
            // echo $map[$y-$tile_id_start_n][$x-$tile_id_start_n]."";
            $tile_id=$tile_id_start;
            if($al != 0){$al_n=$al;}else{$al_n=$al;}
            if($ab != 0){$ab_n=$ab;}else{$ab_n=$ab;}
            if($ar != 0){$ar_n=$ar;}else{$ar_n=$ar;}
            if($ls != 0){$ls_n=$ls;}else{$ls_n=$ls;}
            if($mi != 0){$mi_n=$mi;}else{$mi_n=$mi;}
            if($rs != 0){$rs_n=$rs;}else{$rs_n=$rs;}
            if($bl != 0){$bl_n=$bl;}else{$bl_n=$bl;}
            if($be != 0){$be_n=$be;}else{$be_n=$be;}
            if($br != 0){$br_n=$br;}else{$br_n=$br;}
            // echo "".$tile_id." ".$al_n."\n";
    	    echo "INSERT INTO `phaos_locations` VALUES (".$tile_id.", '".$map_locname[$y][$x]."', '".$al_n."', '".$ab_n."', '".$ar_n."', '".$ls_n."', '".$rs_n."', '".$bl_n."', '".$be_n."', '".$br_n."', '".$image_volder.$map[$y][$x].".png', 0, '".$buildings."', '".$pass."', '');\n";
        	$tile_id_start ++;

        }
            // if($map[$y][$x] != "blanc"){$tile_id_start ++;}
    }

      echo "</textarea></p>
    </form>
    </td>
  </tr>
</table>";}

if($menue == "10"){
echo "
<table border='1' cellspacing='2' width='100%' bgcolor='#FFCC00'>
    <tr>
        <td width='100%' colspan='2' bgcolor='#000000'>Menü : <font color='#B89554'><b>Map Save</b></font> | Info : <font color='#B89554'><b>Map Saved.</b></font></td>
        <td rowspan='2' bgcolor='#000000'>
             <table border='1' cellspacing='1' id='AutoNumber1'>
                <tr>
                    <td width='100%' bgcolor='#000000' align='center' nowrap>Aktuelles Tile</td>
                </tr>
                <tr>
                    <td width='100%' bgcolor='#000000' align='center'>
                        <img border='0' src='".$image_volder.$newtilepic."' width='64' height='64'></td>
                </tr>
                <tr>
                    <td width='100%' bgcolor='#000000' align='center'>
                        ".$newtilepic."</td>
                </tr>
             </table>
        </td>
    </tr>
    <tr>
        <td bgcolor='#000000'>
        	<a href='phaos_mapmaker.php?menue=4&tilex=$x&tiley=$y&newtilepic=$newtilepic&null=Y'>[ Back ]</a> :";
            //<a href='index.php'>New Map</a> :
          	// <a href='phaos_mapmaker.php?menue=5&tilex=$x&tiley=$y&newtilepic=$newtilepic'>Save Map</a> :
            // <a href='phaos_mapmaker.php?#'>Clear Map</a> :
            // <a href='phaos_mapmaker.php?menue=3&tilex=$x&tiley=$y&newtilepic=".$all_tile_pics[$i]."'>Get Tile</a> :
            // <a href='phaos_mapmaker.php?menue=6&tilex=$x&tiley=$y&newtilepic=".$all_tile_pics[$i]."'>Special Tile</a> :
            // <a href='phaos_mapmaker.php?#'>Edit event</a> :
            // <a href='phaos_mapmaker.php?menue=4&tilex=$x&tiley=$y&newtilepic=$newtilepic'>Map Refresh</a>
  echo "</td>
    </tr>
</table><br>
<table border='1' cellspacing='1' width='100%' id='AutoNumber1'>
  <tr>
    <td width='100%'>
    <form method='POST' action='--WEBBOT-SELF--'>
      <p align='center'>
      INSERT INTO `phaos_locations` VALUES (id, name, above_left, above, above_right, left, right, below_left, below, below_right, image_path, special, buildings, pass);
      <textarea style='font-size: 8pt; color: #FFFF00; background-color: #550000' rows='30' name='S1' cols='150'>";

    $fpw = fopen($map_mapvolder.$map_name.$map_ext,"a");

	for ($y = 0; $y <= $map_mapy; $y++) {
    	for ($x = 0; $x <= $map_mapx; $x++) {
        	// echo $map[$y-1][$x-1]."\n";
            // echo $map[$y-1][$x]."\n";
            // echo $map[$y-1][$x+1]."\n";
            // echo $map[$y][$x-1]."\n";
            // echo $map[$y][$x]."\n";
            // echo $map[$y][$x+1]."\n";
            // echo $map[$y+1][$x-1]."\n";
            // echo $map[$y+1][$x]."\n";
            // echo $map[$y+1][$x+1]."\n";
            // echo "tile_id_start=".$tile_id_start." ".$map_mapx."\n";
            // echo $map[($y-1)-$map_mapx][($x-1)-$map_mapx]."\n";
	        // echo "X=".$x." Y=".$y." tile_id_start_n=".$tile_id_start_n." tile_id_start=".$tile_id_start."\n";

            $al = $map[$y-1][$x-1]; if($al == ""){$al = 0;}else{$al = $tile_id_start-($map_mapx+2);}
            $ab = $map[$y-1][$x]  ; if($ab == ""){$ab = 0;}else{$ab = $tile_id_start-($map_mapx+1);}
            $ar = $map[$y-1][$x+1]; if($ar == ""){$ar = 0;}else{$ar = $tile_id_start-($map_mapx);}
            $ls = $map[$y][$x-1]  ; if($ls == ""){$ls = 0;}else{$ls = $tile_id_start-1;}
            $mi = $map[$y][$x]    ; if($mi == ""){$mi = 0;}else{$mi = $tile_id_start;}
            $rs = $map[$y][$x+1]  ; if($rs == ""){$rs = 0;}else{$rs = $tile_id_start+1;}
            $bl = $map[$y+1][$x-1]; if($bl == ""){$bl = 0;}else{$bl = $tile_id_start+($map_mapx);}
            $be = $map[$y+1][$x]  ; if($be == ""){$be = 0;}else{$be = $tile_id_start+($map_mapx+1);}
            $br = $map[$y+1][$x+1]; if($br == ""){$br = 0;}else{$br = $tile_id_start+($map_mapx+2);}

			$pass = "y";
            $buildings = "n";
			if ($image_volder == "images/dungeon/") {$pass = "n";}
			if($map[$y][$x] == "floor"){$map_locname[$y][$x] = "Floor"; $pass = "y";}
			if($map[$y][$x] == "door1"){$map_locname[$y][$x] = "Door"; $pass = "y";}
			if($map[$y][$x] == "door2"){$map_locname[$y][$x] = "Door"; $pass = "y";}
			if($map[$y][$x] == "door3"){$map_locname[$y][$x] = "Door"; $pass = "y";}
			if($map[$y][$x] == "door4"){$map_locname[$y][$x] = "Door"; $pass = "y";}
			if($map[$y][$x] == "stair1"){$map_locname[$y][$x] = "Stairs"; $pass = "y";}
			if($map[$y][$x] == "stair2"){$map_locname[$y][$x] = "Stairs"; $pass = "y";}
			if($map[$y][$x] == "stair3"){$map_locname[$y][$x] = "Stairs"; $pass = "y";}
			if($map[$y][$x] == "stair4"){$map_locname[$y][$x] = "Stairs"; $pass = "y";}			
            if($map[$y][$x] == 49){$map_locname[$y][$x] = "Water"; $pass = "n";}
            if($map[$y][$x] == 62){$buildings = "y"; $map_locname[$y][$x] = "House";}
            if($map[$y][$x] == 63){$buildings = "y"; $map_locname[$y][$x] = "House";}
            if($map[$y][$x] == 64){$buildings = "y"; $map_locname[$y][$x] = "House";}
            if($map[$y][$x] == 69){$buildings = "y"; $map_locname[$y][$x] = "Village";}
            if($map[$y][$x] == 70){$buildings = "y"; $map_locname[$y][$x] = "Village";}
            if($map[$y][$x] == 71){$buildings = "y"; $map_locname[$y][$x] = "Village";}
            if($map[$y][$x] == 72){$buildings = "y"; $map_locname[$y][$x] = "Town";}
            if($map[$y][$x] == 73){$buildings = "y"; $map_locname[$y][$x] = "Town";}
            if($map[$y][$x] == 74){$buildings = "y"; $map_locname[$y][$x] = "Town";}
            if($map[$y][$x] == 75){$buildings = "y"; $map_locname[$y][$x] = "Gate";}
            if($map[$y][$x] == 76){$buildings = "y"; $map_locname[$y][$x] = "Gate";}
            if($map[$y][$x] == 77){$buildings = "y"; $map_locname[$y][$x] = "Gate";}

            // echo $map[$y-$tile_id_start_n][$x-$tile_id_start_n]."";
            $tile_id=$tile_id_start;
            if($al != 0){$al_n=$al;}else{$al_n=$al;}
            if($ab != 0){$ab_n=$ab;}else{$ab_n=$ab;}
            if($ar != 0){$ar_n=$ar;}else{$ar_n=$ar;}
            if($ls != 0){$ls_n=$ls;}else{$ls_n=$ls;}
            if($mi != 0){$mi_n=$mi;}else{$mi_n=$mi;}
            if($rs != 0){$rs_n=$rs;}else{$rs_n=$rs;}
            if($bl != 0){$bl_n=$bl;}else{$bl_n=$bl;}
            if($be != 0){$be_n=$be;}else{$be_n=$be;}
            if($br != 0){$br_n=$br;}else{$br_n=$br;}
            // echo "".$tile_id." ".$al_n."\n";
    	    echo "INSERT INTO `phaos_locations` VALUES (".$tile_id.", '".$map_locname[$y][$x]."', '".$al_n."', '".$ab_n."', '".$ar_n."', '".$ls_n."', '".$rs_n."', '".$bl_n."', '".$be_n."', '".$br_n."', '".$image_volder.$map[$y][$x].".png', 0, '".$buildings."', '".$pass."','');\n";
            $sqlall = "INSERT INTO `phaos_locations` VALUES (".$tile_id.", '".$map_locname[$y][$x]."', '".$al_n."', '".$ab_n."', '".$ar_n."', '".$ls_n."', '".$rs_n."', '".$bl_n."', '".$be_n."', '".$br_n."', '".$image_volder.$map[$y][$x].".png', 0, '".$buildings."', '".$pass."','');\n";
            fwrite($fpw,$sqlall);
            $tile_id_start ++;

        }
            // if($map[$y][$x] != "blanc"){$tile_id_start ++;}
    }

        fclose($fpw);

      echo "</textarea></p>
    </form>
    </td>
  </tr>
</table>";}

echo "</body></html>";

?>
