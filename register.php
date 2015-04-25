<?php
include "config.php";
include_once 'include_lang.php';
?>
<html>
<head>

<link href="styles/phaos.css" rel="stylesheet" type="text/css">

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function verify() {
var themessage = "<?php echo $lang_reg["msg1"]; ?> ";
if (document.form.first_name.value=="") {
themessage = themessage + " - <?php echo $lang_reg["fsnm"]; ?>";
}
if (document.form.last_name.value=="") {
themessage = themessage + " - <?php echo $lang_reg["lsnm"]; ?>";
}
if (document.form.email_address.value=="") {
themessage = themessage + " - <?php echo $lang_reg["mail"]; ?>";
}
if (document.form.username.value=="") {
themessage = themessage + " - <?php echo $lang_reg["user"]; ?>";
}
if (document.form.password_1.value=="") {
themessage = themessage + " - <?php echo $lang_reg["pass1"]; ?>";
}
if (document.form.password_2.value=="") {
themessage = themessage + " - <?php echo $lang_reg["pass2"]; ?>";
}
if (document.form.password_2.value != document.form.password_1.value) {
themessage = themessage + " - <?php echo $lang_reg["err1"]; ?>";
}
if (themessage == "<?php echo $lang_reg["msg1"]; ?> ") {
//document.form.submit();
    return true;
}
else {
alert(themessage);
return false;
   }
}
//  End -->
</script>

</head>
<body>

<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
<tr>
<td align=center valign=middle>

<table border=0 cellspacing=5 cellpadding=0>
<form method="post" name="form" action="register_complete.php" onSubmit="return verify();" >
<tr>
<td align=center colspan=2>
<img src="lang/<?php echo $lang ?>_images/register.png">
</td>
</tr>
<tr>
<td align=right>
<?php echo $lang_reg["fsnm"]; ?>:
</td>
<td align=left>
<input type=text name="first_name" size=20 maxlength=25>
</td>
</tr>
<tr>
<td align=right>
<?php echo $lang_reg["lsnm"]; ?>:
</td>
<td align=left>
<input type=text name="last_name" size=20 maxlength=25>
</td>
</tr>
<tr>
<td align=right>
<?php echo $lang_reg["mail"]; ?>:
</td>
<td align=left>
<input type=text name="email_address" size=20 maxlength=50>
</td>
</tr>
<tr>
<td align=right>
<?php echo $lang_reg["user"]; ?>:
</td>
<td align=left>
<input type=text name="username" size=20 maxlength=20>
</td>
</tr>
<tr>
<td align=right>
<?php echo $lang_reg["pass1"]; ?>:
</td>
<td align=left>
<input type=password name="password_1" size=20 maxlength=20>
</td>
</tr>
<tr>
<td align=right>
<?php echo $lang_reg["pass2"]; ?>:
</td>
<td align=left>
<input type=password name="password_2" size=20 maxlength=20>
</td>
</tr>
<tr>
<td align=center valign=middle colspan=2>
<br>
<input type="submit" value="<?php echo $lang_reg["sub"]; ?>">
<p>
<a href="index.php"><?php echo $lang_reg["back"]; ?></a>
</td>
</tr>
</form>
</table>

</td>
</tr>
</table>

</body>
</html>
