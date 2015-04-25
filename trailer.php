<?php

/*
 * (c) 2005 peter.schaefer@gmail.com
 * This file is supposed to do final actions, like appending debug output to the page
 */

global $lang;

if(@$GLOBALS['debugmsgs']){
    echo "<hr>\n";
    defined('DEBUG') and DEBUG and $character and $GLOBALS['debugmsgs'][]= "Location: $character->location";
    print_msgs($GLOBALS['debugmsgs'],'',"<br>\n");
}

?>


