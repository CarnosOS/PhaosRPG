<?php
include "aup.php";
session_start();
if(isset($topic)){$_SESSION['topic'] = $topic;} 
?>
<html>

<head>
<title>WoP Admin Panel - Delete Help Topic</title>
<link rel=stylesheet type="text/css" href="../styles/phaos.css">
<meta http-equiv="refresh"content="0;URL=admin_help_Modify_Topic.php">
</head>

<body bgcolor="#000000" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF">
<?php
$connection = mysql_connect("$mysql_server","$mysql_user","$mysql_password") or die ("Unable to connect to MySQL server.");
$db = mysql_select_db("$mysql_database") or die ("Unable to select requested database.");

$query = "DELETE FROM phaos_help WHERE id = '$topic'";
$result = mysql_query($query) or die ("Error in query: $query. " .
mysql_error());

session_destroy();
?>
</body>
</html>
