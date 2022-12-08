<?php
include "aup.php";

apply_input_params(array('topic'));

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

$query = "DELETE FROM phaos_help WHERE id = '$topic'";
$result = mysql_query($query) or die ("Error in query: $query. " .
mysql_error());

session_destroy();
?>
</body>
</html>
