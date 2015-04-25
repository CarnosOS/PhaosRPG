<?php include "header.php"; ?>
<br>
<table width="600" border="1" cellspacing="0" cellpadding="3" align="center">
	<tr style=background:#006600;>
		<td align='center'>
			<b><?php echo $lang_added["ad_all-users"]; ?></b>
		</td>
	</tr>
	<tr>
		<td align='center'>
			<?php
			$self=mysql_query("SELECT * FROM phaos_users ORDER BY username ASC");
			while ($row = mysql_fetch_array($self)) {
				$id = $row["id"];
				$username = $row["username"];
				echo "<a href=\"player_info.php?player_name=$username\" target=\"_blank\"><b>$username</b></a><br>";
			}
			?>
		</td>
	</tr>
</table>
<?php include "footer.php"; ?>
