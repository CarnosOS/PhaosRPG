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
	<a href="quests.php"><?php echo $lang_menu["quests"]; ?></a>
</td>
<td align=center width="15%">
	<?php
	echo "<a href='prefs.php?username=$PHP_PHAOS_USER'>Prefs</a>";
	?>
	<br>
	<a href="logout.php"><?php echo $lang_menu["logo"]; ?></a>
</td>
</tr>
</table>
