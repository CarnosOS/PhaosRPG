<?php
include "aup.php";
?>
<html>

<head>

<title>World of Phaos Help</title>
<link rel=stylesheet type="text/css" href="styles/phaos.css">

<script>
function changeScreenSize(w,h) {
	window.resizeTo(w,h)
}
</script>
</head>

<body onload="changeScreenSize(450,500)" bgcolor=#000000>
<br><div align=center>
<hr>
<img src="images/top_logo.png">
<br>
<font size="4">Help</font>
<hr>
<?php
$connection = mysql_connect("$mysql_server","$mysql_user","$mysql_password") or die ("Unable to connect to MySQL server.");
$db = mysql_select_db("$mysql_database") or die ("Unable to select requested database.");

$result = mysql_query ("SELECT body,title FROM phaos_help WHERE id = '$_GET[id]'");
if ($row = mysql_fetch_array($result)) {
	$help_body = stripslashes(str_replace("\n", "<br>", $row[body]));
	$help_title = stripslashes($row[title]);
	?>	
	<table border="0" cellspacing="0" cellpadding="0" class="default" width="80%">
	<tr class=trhead>
	<td align=center><b><u><?php print $help_title; ?></u></b><br><br></td>
	</tr>
	<tr>
	<td align=left>
	<?php print $help_body; ?>
	</td>
	</tr>
	</table>
	<?php
}
?>
<br>
<br>
<br>
</div>
</body>
</html>
