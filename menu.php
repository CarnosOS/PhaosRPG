<table border=1 bordercolor="#003300" cellpadding=0 cellspacing=0 width="90%">
<tr>
<td align=center width="15%">
	<a href="index.php"><?php echo $lang_menu["home"]; ?></a>
	<br>
	<a href="character.php"><?php echo $lang_menu["char"]; ?></a>
</td>
<td align=center width="15%">
	<a href="travel.php"><?php echo $lang_menu["trav"]; ?></a>
	<br>
	<a href="town.php"><?php echo $lang_menu["expl"]; ?></a>
</td>
<td align=center width="15%">
	<a href="message.php"><?php echo $lang_menu["msg_"]; ?></a>
	<br>
	<?php
	echo "<a href='prefs.php?username=$PHP_PHAOS_USER'>Prefs</a>";
	?>
</td>
<td align=center width="15%">
	<a href="logout.php"><?php echo $lang_menu["logo"].'<br>('.$_COOKIE[PHP_PHAOS_USER].')'; ?></a>
</td>
</tr>
</table>
