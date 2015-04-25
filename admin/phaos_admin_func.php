<style>
.admin_item_list {float:left;text-align:center;margin-top:4px;}
.admin_Item_list_wrapper {width:100%;padding:1px;margin:1px;}
</style>


<?php
function do_image_list($folder) { 
     
     $handle=opendir("../".$folder."/");  
     while ($file = readdir($handle)) { 
     if ($file == "." || $file == "..") { } else { 
				print "<div class='admin_item_list'><img src='../".$folder.$file."' /><br />\n"; 
				print "<input CHECKED type='radio' name='image_path' value='".$folder.$file."' >";
				print "</div>\n";				
				} 
       
     } 
     closedir($handle); 

} 

?>
