<?php
inlcude "header.php";
include_once "items.php";
include_once "class_character.php";
?>

<h1>Updating</h1>
<?php

$query = "REPLACE INTO phaos_shop_basics
	    SELECT shop_id, 'blacksmith', 0, 0, 3600
	    FROM  `phaos_buildings`
	    WHERE  TYPE  LIKE  'blacksmith.php'";

$req = mysql_query($query);
if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__,$query); exit;}

$query="update `phaos_buildings` set type= 'darksmith.php' where type like 'blacksmith.php'";

$req = mysql_query($query);
if (!$req) { showError(__FILE__,__LINE__,__FUNCTION__,$query); exit;}

jsChangeLocation("darksmith.php?shop_id=$shop_id");

include "footer.php";
?>
