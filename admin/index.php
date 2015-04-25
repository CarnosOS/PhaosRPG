<?php
include_once("../config.php");
include "aup.php";
?>
<html>
<head>
<title>WoP Administration Panel</title>
<link href="../styles/phaos.css" rel="stylesheet" type="text/css">
</head>
<body>
<table border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="5" align="center">
  <tr>
    <td align="center"><b><font size="6">WoP Administration Panel</font></b></td>
	</tr>
<?php
	// This script will search for files with the name "admin_<section>_<function>.php
	// To add an admin-file, name the function file admin_<section>_<function>.php
	// To give the function a .gif icon, name the function icon exactly the same as the
	// function file but, instead of .php use .gif and place in the phaos/admin/images
	// folder. Make sure you use the same upper and lower case letters and spellings.
	// eg. function admin_create_Weapon.php has icon admin_create_Weapon.gif

function printit( $element ){
	print( $element );
}

function buildit( $section ){
	$title = eregi_replace("_"," ",$section);
	$title = strtoupper($title);
	echo "<tr style=background:#006600;>
    		<td align='center'><b>$title</b></td>
				</tr>
				<tr>
				<td>
				<table width='100%' border='0' cellspacing='5' cellpadding='5' align='center'>
					<tr>";
	$dir=opendir(".");
	$i=0;
	while ($entry=readdir($dir)) {
    if (eregi("admin_".$section."_.*",$entry)){
			//take out admin_<section>_ and .php from sring
			$picture_name=eregi_replace(".php",".gif",$entry);
			$display_name=eregi_replace("admin_".$section."_","",$entry);
			$display_name=eregi_replace(".php","",$display_name);
			$display_name=eregi_replace("_"," ",$display_name);
			$output[$i]='<td align="center"><a href="'.$entry.'"><img src="images/'.$picture_name.'" width="32" height="32" alt="'.$display_name.'"><br>'.$display_name.'</a></td>';
			$i++;
		}
  }

  $outArr=asort($output);
	array_walk($output,'printit');
	echo "</tr>
				</table>
				</td>
				</tr>";
}


// ----------------
// BUILD THE SCREEN
// ----------------
// If you want another section in the Admin Panel then all
// you have to do is place the name of the section between
// the peranthesis () of buildit();
// eg. If you have created a 'map' section and you want to
//     call the section 'MAPS' with the file names in the format of
//       admin_maps_<function>.php
//     then you would use:
// buildit(Maps);

buildit('Create');
buildit('Edit');
buildit('Map');
buildit('Users');
buildit('Help');
buildit('Options');

echo "</table>
			</body>
			</html>";
?>
