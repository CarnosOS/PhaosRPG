<?php
include "aup.php";
session_start();
?>
<html>

<head>
<title>WoP Admin Panel - Insert Help Topic</title>
<link rel=stylesheet type="text/css" href="../styles/phaos.css">
<meta http-equiv="refresh"content="0;URL=admin_help_Add_Topic.php">
</head>

<body bgcolor="#000000" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF">
<?php
$connection = mysql_connect("$mysql_server","$mysql_user","$mysql_password") or die ("Unable to connect to MySQL server.");
$db = mysql_select_db("$mysql_database") or die ("Unable to select requested database.");

$title = addslashes($title);
$file = addslashes($file);
$body = addslashes($body);

$query = "INSERT INTO phaos_help
(title,file,body) 
VALUES
('$title','$file','$body')";
$req = mysql_query($query);
if (!$req)
{ echo "<B>Error ".mysql_errno()." :</B> ".mysql_error()."";
exit; } 

session_destroy();
?>
</body>
</html>
