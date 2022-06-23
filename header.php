<?php
session_start();
include "aup.php";
include_once "class_character.php";
require("Sajax.php");

function add_chat_line($text) {
	global $PHP_PHAOS_USER;
	$text = htmlentities($text);
	$bb_replace =      array('[b]', '[B]', '[/b]', '[/B]', '[i]', '[I]', '[/i]', '[/I]', '[u]', '[U]', '[/u]', '[/U]');
	$bb_replacements = array('<strong>', '<strong>', '</em>', '</em>', '<i>', '<i>', '</i>', '</i>', '<u>', '<u>', '</u>', '</u>');
	$text = str_replace($bb_replace,$bb_replacements,$text);
	$result = mysql_query('SELECT location, name FROM phaos_characters WHERE username = \''.$PHP_PHAOS_USER.'\'');
	$row = mysql_fetch_assoc($result);
        $text = mysql_real_escape_string($text);
	mysql_query ('INSERT INTO phaos_shout (location, postname, postdate, posttext) 	VALUES (\''.$row['location'].'\', \''.$row['name'] . '\', \''.mktime().'\',\'' . $text . '\')');
}

// Added by dragzone---
    $sql_stamina = "SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'";
      $sql_rs_stamina = mysql_query($sql_stamina);
      if ($row = mysql_fetch_array($sql_rs_stamina)) {
         $stamina_points = $row["stamina"]; }
      if ($stamina_points < 0) {
       $stamina_points = 0;
       $query_stamina = ("UPDATE phaos_characters SET stamina = '$stamina_points' WHERE username = '$PHP_PHAOS_USER'");
       $req_stamina = mysql_query($query_stamina); }
//------------------------

function refresh() {
	$result = mysql_query ('SELECT * FROM phaos_characters WHERE username = \'' . $_COOKIE[PHP_PHAOS_USER] . '\'');
	if ($row = mysql_fetch_array($result)) {
		$char_location = $row["location"];
		$char_name = $row['name'];
	}
	$result = mysql_query ('SELECT * FROM phaos_shout WHERE location = \'' . $char_location .'\' OR destname=\''.$char_name.'\' OR destname = \'admin\' ORDER BY postdate DESC LIMIT 0, 10');
	while ($row = mysql_fetch_array($result)) {
		$color = '';
		if($row['destname'] == 'admin') {
			$color = "red";
		}
		if($row['destname'] == $char_name) {
			$color = "yellow";
		}
		if ($color == '') {
			$color = "white";
		}
		print '<hr><div><style> hr div{font-family: text-align: left;Segoe UI; color:'.$color.';}</style>' . $row['postname'] . ',' . date('Y/m/d H:i', $row['postdate']) . '<br><br> '.$row['posttext'] .' <br>';
	}

	$result = mysql_query ('SELECT * FROM phaos_shout');
	while ($row = mysql_fetch_array($result)) {
		$current_time = time();
		$time_check = $current_time-$row['postdate'];
		if($time_check > '86400') {
			$delete_extras = mysql_query ('DELETE FROM phaos_shout WHERE id = \''.$row['id'].'\' ');
		}
	}
}

$sajax_request_type = "GET";
sajax_init();
sajax_export("add_chat_line", "refresh");
sajax_handle_client_request();
?>
<!DOCTYPE HTML>
<html>
<head>

<script>
<?php sajax_show_javascript(); ?>
function refresh_chat(new_data) {
	document.getElementById("chat_right_side").innerHTML = new_data;
}

function refresh() {
	x_refresh(refresh_chat);
}

function add_chat_line_cb() {
	refresh();
}

function add_chat_line() {
	var chat_text;
	chat_text = document.chat_form.chat_text.value;
	x_add_chat_line(chat_text,add_chat_line_cb);
	document.chat_form.chat_text.value="";
}
</script>

<meta name="author" content="Zeke Walker">
<title><?php echo "$SITETITLE"; ?></title>
<link rel=stylesheet type="text/css" href="styles/phaos.css">

<link rel="shortcut icon" href="images/phaos.ico" >
<link rel="icon" href="images/phaos.ico" >
</head>
<body  onFocus="refresh();">

<!-- START HELP MENU CODE -->
<script src="help_ssm.js" type="text/javascript"></script>

<script language="JavaScript">
<!--
<?php
	$current_file = $_SERVER["SCRIPT_NAME"];
	$current_file_parts = explode('/', $current_file);
	$current_file = $current_file_parts[count($current_file_parts) - 1];
	
	$result = mysql_query ("SELECT id,title FROM phaos_help WHERE file = '$current_file' ORDER BY title ASC");
	if ($row = mysql_fetch_array($result)) {
		$num = 0;
	?>
		/*
		Configure menu styles below
		NOTE: To edit the link colors, go to the STYLE tags and edit the ssm2Items colors
		*/
		YOffset=50; // no quotes!!
		XOffset=0;
		staticYOffset=5; // no quotes!!
		slideSpeed=20 // no quotes!!
		waitTime=500; // no quotes!! this sets the time the menu stays out for after the mouse goes off it.
		menuBGColor="#000000";
		menuIsStatic="yes"; //this sets whether menu should stay static on the screen
		menuWidth=180; // Must be a multiple of 10! no quotes!!
		menuCols=2;
		hdrFontFamily="verdana";
		hdrFontSize="2";
		hdrFontColor="#000000";
		hdrBGColor="#EFEFEF";
		hdrAlign="left";
		hdrVAlign="center";
		hdrHeight="20";
		linkFontFamily="Verdana";
		linkFontSize="2";
		linkBGColor="green";
		linkOverBGColor="#000000";
		linkTarget="_top";
		linkAlign="Left";
		barBGColor="#FFFFFF";
		barFontFamily="Verdana";
		barFontSize="2";
		barFontColor="#000000";
		barVAlign="middle";
		barWidth=32; // no quotes!!
		barText="?"; // <IMG> tag supported. Put exact html for an image to show.

		// ssmItems[...]=[name, link, target, colspan, endrow?] - leave 'link' and 'target' blank to make a header
		ssmItems[0]=["Help Topics"] //create header
		<?php
		do {
			$num++;
			?>
			var num = "<?php print $num; ?>";
			ssmItems[num]=["<?php print $row[title]; ?>", "help.php?id=<?php print $row[id]; ?>", "_new"]
			<?php
		} while($row = mysql_fetch_array($result));
		?>
		buildMenu();
		<?php
	}
?>
//-->
</script>
<!-- END HELP MENU CODE -->
	
<table width=100%>
<tbody>
<tr>
<td align=center width=175 valign=top rowspan=2>
<img src="images/top_logo.png">
<?php include "side_bar.php"; ?>
</td>
<td align=center valign=top>
<?php include "menu.php"; ?>
</td>
<td align=center width=250 valign=top rowspan=2>
<form name="chat_form">
<textarea cols="20" rows="3" name="chat_text"></textarea>
<br>
<br>
<button type="button" onclick="add_chat_line();"><?php echo $lang_post["submit"]; ?></button>
</form>
<div id="chat_right_side"></div>
</td>
</tr>
<tr>
<td align=center valign=top>
<table cellspacing=0 cellpadding=5 border=0 width=90%>
<tr>
<td align=center valign=top>
