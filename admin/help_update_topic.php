<?php
include "aup.php";
session_start();
if(isset($topic)){$_SESSION['topic'] = $topic;}
?>
<html>

<head>
<title>WoP Admin Panel- Update Help Topic</title>
<link rel=stylesheet type="text/css" href="../styles/phaos.css">
<meta http-equiv="refresh"content="0;URL=admin_help_Modify_Topic.php?topic=<?php print $topic; ?>">
</head>

<body bgcolor="#000000" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF">
<?php
$connection = mysql_connect("$mysql_server","$mysql_user","$mysql_password") or die ("Unable to connect to MySQL server.");
$db = mysql_select_db("$mysql_database") or die ("Unable to select requested database.");

$ntitle = addslashes($ntitle);
$nfile = addslashes($nfile);
$nbody = addslashes($nbody);

$query = ("UPDATE phaos_help SET title = '$ntitle',
		       file = '$nfile',
		       body = '$nbody'
		       WHERE id = '$topic'");
$req = mysql_query($query);
if (!$req)
{ echo "<B>Error ".mysql_errno()." :</B> ".mysql_error()."";
exit; } 

session_destroy();
?>
</body>
</html>
