<?php
include "aup.php";
session_start();
if(isset($topic)){$_SESSION['topic'] = $topic;} 
?>
<html>
<head>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function verify() {
	var themessage = "You are required to complete the following fields: ";
	if (document.form.ntitle.value=="") {
		themessage = themessage + " - Subject";
	}
	if (document.form.nfile.value=="") {
		themessage = themessage + " - File Name";
	}
	if (document.form.nbody.value=="") {
		themessage = themessage + " - Body";
	}
	if (themessage == "You are required to complete the following fields: ") {
		document.form.submit();
	} else {
		alert(themessage);
		return false;
	}
}
//  End -->
</script>
<title>WoP Admin Panel - Modify Help Topic</title>
<link rel=stylesheet type="text/css" href="../styles/phaos.css">
</head>

<body bgcolor="#000000" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF">
<br><div align=center>
<hr>
<img src="../images/top_logo.png">
<br>
<font size="4">Help System Admin</font>
<br>
<input type='button' onClick="parent.location='index.php'" value='Back to Admin Panel'>
<hr>
<?php
$connection = mysql_connect("$mysql_server","$mysql_user","$mysql_password") or die ("Unable to connect to MySQL server.");
$db = mysql_select_db("$mysql_database") or die ("Unable to select requested database.");
?>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<form action="help_lookup_topic.php">
<td valign="middle" align="center">
	<select name=hlpfield>
		<option value="title">Subject</option>
		<option value="file">File Name</option>
	</select>
	<input type="text" name="hlpvalue" size="10" title="You MUST select a field from the drop down box, but you may leave this box blank.">
</td>
<td valign=bottom>
	<button type=submit class=search_button>Search</button>
</td></form>
<form action="help_delete_topic.php" onSubmit="return confirm('Are you sure you want to delete this topic?')">
<td valign=bottom>
	<input type=hidden name=topic value="<?php print $topic; ?>">
	<button type=submit class=delete_button>Delete From Database</button>
</td></form>
</tr>
</table>
<p>
<?php
if($topic == "") {session_destroy();exit;}

$result = mysql_query ("SELECT * FROM phaos_help WHERE id = '$topic'");
if ($row = mysql_fetch_array($result)) {
	$title = stripslashes($row[title]);
	$file = stripslashes($row[file]);
	$body = stripslashes($row[body]);
} else {print "You do not have access to this record.";exit;}
?>
<table border="0" cellspacing="0" cellpadding="0" class="default">
<form name=form method=post action="help_update_topic.php">
<tr class=trhead>
	<td align=center colspan=4><b>Topic Information</b></td>
</tr>
<tr>
	<td align="right">Subject:<font color=red><b>*</b></font>&nbsp;</td>
	<td align="left" colspan=3><input size="50" maxlength="100" name="ntitle" value="<?php print htmlspecialchars($title); ?>" type="text"></td>
</tr>
<tr>
	<td align="right">File Name:<font color=red><b>*</b></font>&nbsp;</td>
	<td align="left" colspan=3><input size="50" maxlength="100" name="nfile" value="<?php print htmlspecialchars($file); ?>" type="text"></td>
</tr>
<tr>
	<td align="right">Body:<font color=red><b>*</b></font>&nbsp;</td>
	<td align="left" colspan=3><textarea name="nbody" rows="4" cols="80" WRAP><?php print $body; ?></textarea></td>
</tr>
</table>
<br>
<input type="hidden" name="topic" value="<?php print $topic; ?>">
<button type=button onclick="verify();" class=save_button>Save Changes</button>
</form>
<br>
<br>
<br>
</div>
<?php session_destroy(); ?>
</body>
</html>
