<?php

// ---------------------------------------------------- //
// - Programmer : Thunder Doom                        - //
// - Email      : thunder_doom@gmc.de                 - //
// - Web        : http://www.utkingdom.com            - //
// - Sript      : Map Maker.php                       - //
// - Languange  : Deutsch                             - //
// - Version    : v1.0.0000                           - //
// - Date       : 16.09.2004                          - //
// - Info       :                                     - //
// -            :                                     - //
// ---------------------------------------------------- //

echo "
<html>

<head>
<meta http-equiv='Content-Language' content='de'>
<meta http-equiv='Content-Type' content='text/html; charset=windows-1252'>
<title>..:: Phaos Mapmaker v1.0 ::..</title>
</head>

<body link='#FFCC00' vlink='#FF9933' alink='#FF0000' text='#FFCC66' bgcolor='#000000'>";

echo "
<div align='center'>
	<table width='50%' border='2' cellspacing='1' bordercolorlight='#FF0000' bordercolordark='#800000' bordercolor='#800000' bgcolor='#000000' style='border-collapse: collapse'>
		<tr>
			<td>
				<form method=post action=phaos_mapmaker.php>
			  		Map Name: <input type='text' name='map_name_s' size='20' value='bobo'><br><br>

                    Map Width: <input type='text' name='map_width_s' size='4' value='15'><br>
                    Map Height: <input type='text' name='map_height_s' size='4' value='15'><br><br>

                    SQL Start ID: <input type='text' name='tile_id_start_s' size='7' value='1000'><br><br>

					Fill Map with ?<br>
  					Land   :<input type='radio' name='map_fill_s' value='1' checked ><br>
  					Water :<input type='radio' name='map_fill_s' value='2'><br>
  					Snow :<input type='radio' name='map_fill_s' value='3'><br>
  					Desert  :<input type='radio' name='map_fill_s' value='4'><br>
                    Dungeon :<input type='radio' name='map_fill_s' value='5'><br>
                    Forrest :<input type='radio' name='map_fill_s' value='6'><br>
  					nothing :<input type='radio' name='map_fill_s' value='99'><br>
  					<center><input type='submit' value='Edit Map' name='B1'></center>
				</form>
			</td>
		</tr>
	</table>
</div>
";

echo "</body></html>";

?>