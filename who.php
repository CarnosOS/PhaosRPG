<?php include "header.php"; ?>
<table border="0" align="center">
<tr>
<td align="center" bgcolor="#000000">
<img src="lang/<?php echo $lang ?>_images/whos_online.png"><br>
<small><?php echo $lang_who["msg"]; ?></small>
</td>
</tr><tr>   
<td align="center">
<?php echo who_is_online(); ?>
</td>
</tr>
</table>
<?php include "footer.php"; ?>
