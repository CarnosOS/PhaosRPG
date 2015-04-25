<?php
include "config.php";
include_once 'include_lang.php';

// BEGIN BLANK COOKIE VARS ( SETS COOKIE VARS TO BLANK )

setcookie("PHP_PHAOS_PW",$PHP_PHAOS_PW,time()-172800);
setcookie("PHP_PHAOS_MD5PW",$PHP_PHAOS_PW,time()-172800);
unset ($PHP_PHAOS_USER, $PHP_PHAOS_PW, $PHP_PHAOS_MD5PW, $PHP_PHAOS_CHARID, $PHP_PHAOS_CHAR);

// END BLANK COOKIE VARS
?>

<html>
<head>
<title>Phaos - 3E Productions</title>
<link rel=stylesheet type=text/css href=styles/phaos.css>
<meta http-equiv="refresh" content="0;URL=index.php">
</head>
<body bottommargin="0" rightmargin="0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align=center valign=middle height="100%" width="100%">
<table border="0" cellspacing="1" cellpadding="0">
<tr>
<td colspan="2" align=center>
<img src="images/top_logo.png">
</td>
</tr>
<tr>
<td colspan="2" align=center>
<?php echo $lang_aup["no_long"]; ?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
