<?php
include "aup.php";

apply_input_params(array(
  'topic', 'ntitle', 'nfile', 'nbody'
));

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
