<html>
<head>

<?php
include "config.php";
include_once 'include_lang.php';

apply_input_params(array(
  'first_name', 'last_name', 'username', 'email_address', 'password_1'
));

?>

<link href="styles/phaos.css" rel="stylesheet" type="text/css">

</head>
<body> 

<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
<tr>
<td align=center valign=middle>

<table border=0 cellspacing=5 cellpadding=0>
<tr>
<td align=center>
<img src="lang/<?php echo $lang ?>_images/register.png">
</td>
</tr>
<tr>
<td align=center>
<?php

$full_name = checkHtmlEntities($first_name." ".$last_name);
$username = checkHtmlEntities($username);
$email_address = checkHtmlEntities($email_address);
$password_1 = checkHtmlEntities($password_1);
$password = md5($password_1);

$result = mysql_query ("SELECT * FROM phaos_users WHERE username = '$username'");
$duplicate = mysql_fetch_array($result);

if(!@$duplicate AND $username != "" AND $password_1 != "" AND $email_address != "" AND $full_name != "") {
$query = "INSERT INTO phaos_users
(username,password,email_address,full_name)
VALUES
('$username','$password','$email_address','$full_name')";
$req = mysql_query($query);
if (!$req) {echo "<B>Error ".mysql_errno()." :</B> ".mysql_error().""; exit;} 

print ($lang_reg["thx"]."<p><a href=\"index.php\">".$lang_aup["login"]."</a>");

} else {print ("<big>".$lang_reg["not"]."</big><p>".$lang_reg["not2"]."<p><a href=\"index.php\">".$lang_aup["login"]."</a><p><a href=\"register.php\">".$lang_reg["try"]."</a>");}
?>
</td>
</tr>
</table>

</td>
</tr>
</table>

</body>
</html>
