<?php
include "aup.php";
?>
<html>
<head>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function verify() {
	var themessage = "You are required to complete the following fields: ";
	if (document.form.title.value=="") {
		themessage = themessage + " - Subject";
	}
	if (document.form.body.value=="") {
		themessage = themessage + " - Body";
	}
	if (document.form.file.value=="") {
		themessage = themessage + " - File Name";
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
<title>WoP Admin Panel - Add Help Topic</title>
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
<table border="0" cellspacing="0" cellpadding="0" class="default">
<form name=form method=post action="help_insert_topic.php">
<tr class=trhead>
<td align=center colspan=4>
<b>Topic Information</b> 
</td>
</tr>
<tr>
<td align="right">
Subject:<font color=red><b>*</b></font>&nbsp;
</td>
<td align="left">
<input size="50" maxlength="100" name="title" type="text">
</td>
</tr>
<tr>
<td align="right">
File Name:<font color=red><b>*</b></font>&nbsp;
</td>
<td align="left">
<input size="50" maxlength="100" name="file" type="text">
</td>
</tr>
<tr>
<td align="right">
Body:<font color=red><b>*</b></font>&nbsp;
</td>
<td align="left">
<textarea name="body" rows="4" cols="80" WRAP></textarea>
</td>
</tr>
</table>
<br>
<button type=button onclick="verify();" class=add_record_button>Add Record</button>
</form>
<br>
<br>
<br>
</div>
</body>
</html>
