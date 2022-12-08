<?php
include "aup.php";
include_once "class_quest_generator.php";
include_once "class_quest.php";

$character = new character($PHP_PHAOS_CHARID);

$quest = intval($_POST['quest']);
$abandon = isset($_POST['abandon']) ? 'Y' : 'N';

if ($abandon === 'Y' && $quest > 0) {
  mysql_query("DELETE FROM phaos_questhunters "
    . "WHERE charid='".$character->id."' "
    . "AND questid='".$quest."' "
    . "AND complete='0'");
}

$quest_generator = new quest_generator();
$quest_generator->delete_finished_quests();
$rows = array();
$quests = array();

$sql = "SELECT "
  . "  qh.questid, qh.starttime, qh.monstkill, qh.visitdone, qh.complete, "
  . "  l.name as location_name, n.name as npc_name, n.image_path "
  . "FROM phaos_questhunters qh "
  . "INNER JOIN phaos_npcs n ON n.quest = qh.questid "
  . "INNER JOIN phaos_locations l ON l.id = n.location "
  . "WHERE qh.charid = '".$character->id."' AND qh.complete = 0 "
  . "ORDER BY qh.starttime ASC";

$result = mysql_query($sql);
while (($row = mysql_fetch_array($result))) {
  $rows[] = $row;
  $quests[] = new quest($row['questid']);
}

include "header.php";
?>

<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
  <tr>
    <td align="center" valign="top">

      <table border="0" cellspacing="0" cellpadding="2" width="100%">
        <tr>
          <td align="center" colspan="5">
            <h2><?php echo $lang_quest['quests']; ?></h2>
          </td>
        </tr>
        <tr>
          <td colspan="5">
            <h4><?php echo $lang_quest['your_quests']; ?> :</h4>
          </td>
        </tr>

        <tr>
          <td bgcolor="#003300"><?php echo $lang_quest['quest']; ?></td>
          <td bgcolor="#003300" align="center"><?php echo $lang_quest['from']; ?></td>
          <td bgcolor="#003300" align="center"><?php echo $lang_quest['time_left']; ?></td>
          <td bgcolor="#003300" align="center"><?php echo $lang_quest['status']; ?></td>
          <td bgcolor="#003300" align="center"><?php echo $lang_quest['abandon']; ?></td>
        </tr>
<?php
$quest_count = count($quests);

if ($quest_count === 0) {
?>
        <tr>
          <td colspan="5">
            <b><?php echo $lang_quest['no_quests']; ?></b>
          </td>
        </tr>
<?php
}

for ($i = 0; $i < $quest_count; $i++) {
  $q = $quests[$i];
  $row = $rows[$i];

  $title = $q->title;
  $status = $q->get_status($character);

?>
        <tr>
          <td>
            <b><?php echo $title; ?></b>
            <br />
            <br />
            <?php echo $q->print_reward($character); ?>
          </td>
          <td align="center">
            <img src="<?php echo $row['image_path']; ?>">
            <br />
            <?php echo $row['npc_name']; ?>
            <br />
            <?php echo $row['location_name']; ?>
          </td>
          <td align="center">
            <?php
              $now = time();
              $left = $q->endtime - $now;
              if ($left >= 3600) {
                echo floor($left / 3600) . ' ' . $lang_quest['hours_left'];
              } else {
                if ($left < 0) {
                  $left = 0;
                }
                echo floor($left / 60) . ' ' . $lang_quest['minutes_left'];
              }
            ?>
          </td>
          <td align="center">
            <?php
              if ($status === 2) {
                echo $lang_quest['done'];
              } else {
                if ($q->monstercollectq) {
                  echo $row['monstkill'] .' / '. $q->monstercollectq;
                } else {
                  echo $lang_quest['in_progress'];
                }
              }
            ?>
          </td>
          <td align="center">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <input type="hidden" name="quest" value="<?php echo $q->questid; ?>">
              <input type="submit" name="abandon" value="<?php echo $lang_quest['abandon']; ?>">
            </form>
          </td>
        </tr>
        <tr>
          <td colspan="5">
            <hr />
          </td>
        </tr>

<?php

}

?>

<tr>
  <td>

<?php
include "footer.php";
