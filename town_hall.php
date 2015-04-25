<?php include "header.php"; ?>

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber1" height="103">
  <tr>
    <td width="100%" height="100%" align="center">
    <img src="lang/<?php echo $lang ?>_images/town_hall.png"><br><br>
    <br><br></td>
  </tr>
  <tr>
    <td align="center" valign="top" height="63">
    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber2">
      <tr>
        <td width="100%" bgcolor="#003300" align="center">&nbsp;</td>
      </tr>
      <tr>
        <td width="100%" align="center"><?php echo $lang_town["wel"]; ?><br>
<?php echo $lang_town["gmsg"]; ?><br>
<?php echo $lang_town["gmsg2"]; ?>.<br><hr color="#FFFFFF" width="98%"><br>
        <a href="./clan_create.php"><?php echo $lang_town["create_guild"]; ?></a><br>
        <br>
        <a href="./clan_join.php"><?php echo $lang_town["look_guild"]; ?></a><br>
        <br><hr color="#FFFFFF" width="98%"><br>
        <a href="./clan_home.php"><?php echo $lang_town["go_guild"]; ?></a><br>
        <br><hr color="#FFFFFF" width="98%"><br>
        </td>
      </tr>
      <tr>
        <td width="100%" bgcolor="#003300"><a href="town.php"><?php echo $lang_shop["return"]; ?></a>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
<?php include "footer.php"; ?>
