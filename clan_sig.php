<?php include "header.php"; ?>
Please select Guild Sig.<br>
* Do not press oftentimes, frequent pressure will result in an error.<br>

<table border="0" width="100%">
<tr>
<td>
<?php
// get a list of images
$list = '';
$dh = opendir('images/guild_sign/');
while($file = readdir($dh)) {
	if($file == '.' || $file == '..') {
		continue;
	}
	?>
	<img src=images/guild_sign/<?php echo $file; ?> value="<?php echo $file; ?>" alt="<?php echo $file; ?>" onmouseover="this.style.cursor='hand';" onClick="opener.form1.clan_sig.value=value; window.close();">
	<?php
}
?>
</td>
</tr>
</table>
<?php include "footer.php"; ?>
