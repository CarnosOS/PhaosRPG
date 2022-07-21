<?php
include "aup.php";

apply_input_params(array(
  'title', 'file', 'body'
));

?>
<html>

<head>
<title>WoP Admin Panel - Insert Help Topic</title>
<link rel=stylesheet type="text/css" href="../styles/phaos.css">
<meta http-equiv="refresh"content="0;URL=admin_help_Add_Topic.php">
</head>

<body bgcolor="#000000" link="#FFFFFF" alink="#FFFFFF" vlink="#FFFFFF" text="#FFFFFF">
<?php

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
