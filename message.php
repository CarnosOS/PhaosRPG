<?php include "header.php"; ?>
<html>
<head>

<script>
function addText(elname, wrap1, wrap2) {
   if (document.selection) { // for IE
      var str = document.selection.createRange().text;
      document.forms['inputform'].elements[elname].focus();
      var sel = document.selection.createRange();
      sel.text = wrap1 + str + wrap2;
      return;
   } else if ((typeof document.forms['inputform'].elements[elname].selectionStart) != 'undefined') { // for Mozilla
      var txtarea = document.forms['inputform'].elements[elname];
      var selLength = txtarea.textLength;
      var selStart = txtarea.selectionStart;
      var selEnd = txtarea.selectionEnd;
      var oldScrollTop = txtarea.scrollTop;
      //if (selEnd == 1 || selEnd == 2)
      //selEnd = selLength;
      var s1 = (txtarea.value).substring(0,selStart);
      var s2 = (txtarea.value).substring(selStart, selEnd)
      var s3 = (txtarea.value).substring(selEnd, selLength);
      txtarea.value = s1 + wrap1 + s2 + wrap2 + s3;
      txtarea.selectionStart = s1.length;
      txtarea.selectionEnd = s1.length + s2.length + wrap1.length + wrap2.length;
      txtarea.scrollTop = oldScrollTop;
      txtarea.focus();
      return;
   } else {
      insertText(elname, wrap1 + wrap2);
   }
}

function insertText(elname, what) {
   if (document.forms['inputform'].elements[elname].createTextRange) {
      document.forms['inputform'].elements[elname].focus();
      document.selection.createRange().duplicate().text = what;
   } else if ((typeof document.forms['inputform'].elements[elname].selectionStart) != 'undefined') { // for Mozilla
      var tarea = document.forms['inputform'].elements[elname];
      var selEnd = tarea.selectionEnd;
      var txtLen = tarea.value.length;
      var txtbefore = tarea.value.substring(0,selEnd);
      var txtafter =  tarea.value.substring(selEnd, txtLen);
      var oldScrollTop = tarea.scrollTop;
      tarea.value = txtbefore + what + txtafter;
      tarea.selectionStart = txtbefore.length + what.length;
      tarea.selectionEnd = txtbefore.length + what.length;
      tarea.scrollTop = oldScrollTop;
      tarea.focus();
   } else {
      document.forms['inputform'].elements[elname].value += what;
      document.forms['inputform'].elements[elname].focus();
   }
}
</script>
<?php
include_once "class_character.php";
// added by dragzone---
if (!$to) { $to = $HTTP_GET_VARS['to']; }
//---------------------
$character=new character($PHP_PHAOS_CHARID);
//bbcode & smily functions could be moved to a seperate page such as global.php
//start
function displaysmileys($textarea)
{
   $smileys = array (
      ":)" => "smile.gif",
      ";)" => "wink.gif",
      ":|" => "frown.gif",
      ":(" => "sad.gif",
      ":o" => "shock.gif",
      ":p" => "pfft.gif",
      "B)" => "cool.gif",
      ":D" => "grin.gif",
      ":@" => "angry.gif"
   );
   foreach($smileys as $key=>$smiley) $smiles .= "<img src='images/smiley/$smiley' onClick=\"insertText('$textarea', '$key');\">\n";
   return $smiles;
}

function parsesmileys($message)
{
   $smiley = array(
      "/\:\)/si" => "<img src='images/smiley/smile.gif'>",
      "/\;\)/si" => "<img src='images/smiley/wink.gif'>",
      "/\:\(/si" => "<img src='images/smiley/sad.gif'>",
      "/\:\|/si" => "<img src='images/smiley/frown.gif'>",
      "/\:o/si" => "<img src='images/smiley/shock.gif'>",
      "/\:p/si" => "<img src='images/smiley/pfft.gif'>",
      "/b\)/si" => "<img src='images/smiley/cool.gif'>",
      "/\:d/si" => "<img src='images/smiley/grin.gif'>",
      "/\:@/si" => "<img src='images/smiley/angry.gif'>"
   );
   foreach($smiley as $key=>$smiley_img) $message = preg_replace($key, $smiley_img, $message);
   return $message;
}

function parseubb($message)
{
   $ubbs1[0] = '#\[b\](.*?)\[/b\]#si';
   $ubbs2[0] = '<b>\1</b>';
   $ubbs1[1] = '#\[i\](.*?)\[/i\]#si';
   $ubbs2[1] = '<i>\1</i>';
   $ubbs1[2] = '#\[u\](.*?)\[/u\]#si';
   $ubbs2[2] = '<u>\1</u>';
   $ubbs1[3] = '#\[center\](.*?)\[/center\]#si';
   $ubbs2[3] = '<center>\1</center>';
   $ubbs1[4] = '#\[url\]http://(.*?)\[/url\]#si';
   $ubbs2[4] = '<a href=\'http://\1\' target=\'_blank\'>http://\1</a>';
   $ubbs1[5] = '#\[url\](.*?)\[/url\]#si';
   $ubbs2[5] = '<a href=\'http://\1\' target=\'_blank\'>\1</a>';
   $ubbs1[6] = '#\[url=http://(.*?)\](.*?)\[/url\]#si';
   $ubbs2[6] = '<a href=\'http://\1\' target=\'_blank\'>\2</a>';
   $ubbs1[7] = '#\[url=(.*?)\](.*?)\[/url\]#si';
   $ubbs2[7] = '<a href=\'http://\1\' target=\'_blank\'>\2</a>';
   $ubbs1[8] = '#\[mail\](.*?)\[/mail\]#si';
   $ubbs2[8] = '<a href=\'mailto:\1\'>\1</a>';
   $ubbs1[9] = '#\[mail=(.*?)\](.*?)\[/mail\]#si';
   $ubbs2[9] = '<a href=\'mailto:\1\'>\2</a>';
   $ubbs1[10] = '#\[img\](.*?)\[/img\]#si';
   $ubbs2[10] = '<img src=\'\1\'>';
   $ubbs1[11] = '#\[small\](.*?)\[/small\]#si';
   $ubbs2[11] = '<span class=\'small\'>\1</span>';
   $ubbs1[12] = '#\[color=(.*?)\](.*?)\[/color\]#si';
   $ubbs2[12] = '<span style=\'color:\1\'>\2</span>';
   $ubbs1[13] = '#\[quote\](.*?)\[/quote\]#si';
   $ubbs2[13] = '<div class=\'quote\'>\1</div>';
   $ubbs1[14] = '#\[code\](.*?)\[/code\]#si';
   $ubbs2[14] = '<div class=\'quote\' style=\'width:400px;white-space:nowrap;overflow:auto\'><code style=\'white-space:nowrap\'>\1<br><br><br></code></div>';
   for ($i=0;$ubbs1[$i]!="";$i++) $message = preg_replace($ubbs1, $ubbs2, $message);
   
   // Prevent use of mallicious javascript
   $text1[0] = "#document#si"; $text2[0] = 'docu<i></i>ment';
   $text1[1] = "#expression#si"; $text2[1] = 'expres<i></i>sion';
   $text1[2] = "#onmouseover#si"; $text2[2] = 'onmouse<i></i>over';
   $text1[3] = "#onclick#si"; $text2[3] = 'on<i></i>click';
   $text1[4] = "#onmousedown#si"; $text2[4] = 'onmouse<i></i>down';
   $text1[5] = "#onmouseup#si"; $text2[5] = 'onmouse<i></i>up';
   $text1[6] = "#ondblclick#si"; $text2[6] = 'on<i></i>dblclick';
   $text1[7] = "#onmouseout#si"; $text2[7] = 'onmouse<i></i>out';
   $text1[8] = "#onmousemove#si"; $text2[8] = 'onmouse<i></i>move';
   $text1[9] = "#onload#si"; $text2[9] = 'on<i></i>load';
   $text1[10] = "#background:url#si"; $text2[10] = 'background<i></i>:url';
   for ($i=0;$text1[$i]!="";$i++) $message = preg_replace($text1, $text2, $message);
   
   return $message;
}
//end bbcode & smilys
?>

</head>
<body>
<?php
$result1=mysql_query("select * from phaos_users WHERE username='$PHP_PHAOS_USER'") or die (" ".$lang_mssg["cnat_touch_this"]);
$row100 = mysql_fetch_array($result1);
?>
<p align="center"><?php echo $lang_mssg["m_cent"]; ?> <?php echo $PHP_PHAOS_USER; ?> | <a href="<?php echo $_SERVER[PHP_SELF]; ?>?action=compose"><?php echo $lang_mssg["comp"]; ?></a> | <a href="<?php echo $_SERVER[PHP_SELF]; ?>?action=inbox"><?php echo $lang_mssg["inbox"]; ?></a> | </p>
<table cellpadding="1" cellspacing="1" height="300" width="450" align="center">
<tr><td align="center" valign=top>
<?php

// Line 173 translation-var added by dragzone
   if($action==compose)
   {
   echo '<form name="inputform" action="'.$_SERVER[PHP_SELF].'?action=compose2" method="post">
      <table>
      <tr>
      <td>'.$lang_mssg["subj"].':</td>
      <td><input type="text" name="subject" size="20" value="'.$subject.'"></td>
      </tr><tr>
      <td>'.$lang_mssg["222"].':</td>
      <td><input type="text" name="to" size="20" value="'.$to.'"><br><b>'; echo $lang_added["must_username"]; echo '</b></td>
      </tr><tr>
      <td>'.$lang_mssg["mssgi"].':</td>
      <td><textarea rows="16" cols="45" name="message"></textarea></td>
      </tr><tr>
     <td>';
     //addin the bbcode + smilys
           echo "
     <tr>
<td width='145' class='tbl2'>&nbsp;</td>
<td class='tbl2'>
<input type='button' value='b' onClick=\"addText('message', '[b]', '[/b]');\">
<input type='button' value='i' onClick=\"addText('message', '[i]', '[/i]');\">
<input type='button' value='u' onClick=\"addText('message', '[u]', '[/u]');\">
<input type='button' value='".$lang_bbcode["url"]."' onClick=\"addText('message', '[url]', '[/url]');\">
<input type='button' value='".$lang_bbcode["mail"]."' onClick=\"addText('message', '[mail]', '[/mail]');\">
<input type='button' value='".$lang_bbcode["img"]."' onClick=\"addText('message', '[img]', '[/img]');\">
<input type='button' value='".$lang_bbcode["center"]."' onClick=\"addText('message', '[center]', '[/center]');\">
<input type='button' value='".$lang_bbcode["small"]."' onClick=\"addText('message', '[small]', '[/small]');\">
<input type='button' value='".$lang_bbcode["code"]."' onClick=\"addText('message', '[code]', '[/code]');\">
<input type='button' value='".$lang_bbcode["quote"]."' onClick=\"addText('message', '[quote]', '[/quote]');\">
</td>
</tr>
<td width='145'>&nbsp;</td>
<td>
Colour Select :<select name='bbcolor' onChange=\"addText('message', '[color=' + this.options[this.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;\">
<option value=''>".$lang_bbcode["Default"]."</option>
<option value='maroon' style='color:maroon;'>".$lang_bbcode["Maroon"]."</option>
<option value='red' style='color:red;'>".$lang_bbcode["Red"]."</option>
<option value='orange' style='color:orange;'>".$lang_bbcode["Orange"]."</option>
<option value='brown' style='color:brown;'>".$lang_bbcode["Brown"]."</option>
<option value='yellow' style='color:yellow;'>".$lang_bbcode["Yellow"]."</option>
<option value='green' style='color:green;'>".$lang_bbcode["Green"]."</option>
<option value='lime' style='color:lime;'>".$lang_bbcode["Lime"]."</option>
<option value='olive' style='color:olive;'>".$lang_bbcode["Olive"]."</option>
<option value='cyan' style='color:cyan;'>".$lang_bbcode["Cyan"]."</option>
<option value='blue' style='color:blue;'>".$lang_bbcode["Blue"]."</option>
<option value='navy' style='color:navy;'>".$lang_bbcode["Navy Blue"]."</option>
<option value='purple' style='color:purple;'>".$lang_bbcode[""]."</option>
<option value='violet' style='color:violet;'>".$lang_bbcode["Violet"]."</option>
<option value='black' style='color:black;'>".$lang_bbcode["Black"]."</option>
<option value='gray' style='color:gray;'>".$lang_bbcode["Gray"]."</option>
<option value='silver' style='color:silver;'>".$lang_bbcode["Silver"]."</option>
<option value='white' style='color:white;'>".$lang_bbcode["White"]."</option>
</select>
</td>
</tr>
<tr>
<td width='145'>&nbsp;</td>
<td>
".displaysmileys("message")."
</td>
</tr> ";
     //end
     echo '<tr>
      <td><button type="submit">'.$lang_mssg["send_mssg"].'</button></td>
      </tr>
      </table>
      </form>';
   }

   
if($action==compose2)
   {
      $subject or die($lang_mssg["_blank1"]);
      $message or die($lang_mssg["_blank2"]);
      $to or die($lang_mssg["_blank3"]);
      $date = date(YmdHis);
     if ($to == $PHP_PHAOS_USER)
     {
     // Changed by dragzone---
     echo $lang_added["ad_msg-yourself"];
     //---------------------
     }
     else
     {
      $create = "INSERT INTO phaos_mail (UserTo, UserFrom, Subject, Message, SentDate, status)
      VALUES ('$to','$PHP_PHAOS_USER','$subject','$message','$date','unread')";
      $create2 = mysql_query($create) or die($lang_mssg["snd22"]." $to!");
      echo $lang_mssg["snd_2"].' '.$to;
     }
   
   }

if($action==inbox)
{
$result=mysql_query("select * from phaos_mail where UserTo='$PHP_PHAOS_USER' ORDER BY SentDate DESC") or die (" ".$lang_mssg["cnat_touch_this"]);
if (mysql_num_rows($result) > 0){
echo '<table cellpadding="2" cellspacing="1" width="100%" valign="top">';
   while ($row=mysql_fetch_array($result))
   {
      // Error status-image-not-found on line 266 ($row[status]) solved by dragzone
      echo '<tr>
      <td width="30"><img src="images/'.$row[STATUS].'.gif" border="0">'.$lang_mssg["ma_il"].':</td>
      <td><a href="'.$_SERVER[PHP_SELF].'?action=veiw&mail_id='.$row[mail_id].'">'.$row[Subject].'</a></td>
     <td width=50><a href="'.$_SERVER[PHP_SELF].'?action=delete&SentDate='.$row[SentDate].'&id='.$row[mail_id].'"><center>'.$lang_mssg["delt"].'</a>'.'</td>
      </tr>';
      //-------------------------------------------------
   }
echo '</table>';
}
else {
   echo "<br><p align=\"center\"><b>".$lang_mssg["no_msg_l"]."</p><b>";
   }
}

//in the view i have removed viewing in the textbox to allow correct output of the codes now in use. This should have no effect on the overall standard display abilitys since i have also made use of nl2br to get the returns etc. (at least i think it will work :)

   if($action==veiw)
   {
   $result=mysql_query("select * from phaos_mail where UserTo='$PHP_PHAOS_USER' and mail_id=$mail_id") or die (" ".$lang_mssg["cnat_touch_this"]);
   $row=mysql_fetch_array($result);
   //parse the smilys & bbcode
   $message = $row['Message'];
   $message = parsesmileys($message);
   $message = parseubb($message);
   $message = nl2br($message);//make returns
   //end parse
      if($row[UserTo] == $PHP_PHAOS_USER)
      {
       
      }
      else
      {
      echo '<b>'.$lang_mssg["not_ur_ma"].'</b>';
      exit;
      }
   $query="UPDATE phaos_mail SET status='read' WHERE UserTo='$PHP_PHAOS_USER' AND mail_id='$row[mail_id]'";
   $query or die($lang_mssg["err_resu"]);
   echo "<table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"428\" height=\"201\">
  <tr>
    <td width=\"424\" colspan=\"2\" height=\"19\"><b>".$lang_mssg["frm"]." </b> : ".$row[UserFrom]."</td>
  </tr>
  <tr>
    <td width=\"424\" colspan=\"2\" height=\"19\"><b>".$lang_mssg["subj"]."</b>: ".$row[Subject]."</td>
  </tr>
  <tr>
    <td width=\"99\" height=\"179\" valign=\"top\" rowspan=\"3\"><b>".$lang_mssg["mssgi"]." :</b></td>
    <td width=\"325\" height=\"141\"> 
      ".$message."
    </td>
  </tr>
  <tr>
    <td width=\"325\" height=\"19\">&nbsp;</td>
  </tr>
  <tr>
    <td width=\"204\" height=\"19\" align=\"center\"><a href=\"".$_SERVER[PHP_SELF]."?action=compose&to=".$row[UserFrom]."&subject=RE:".$row[Subject]."\">".$lang_mssg["repply"]."</a> &nbsp; - &nbsp; <a href=\"".$_SERVER[PHP_SELF]."?action=delete&SentDate=".$row["SentDate"]."&id=".$row[mail_id]."\">".$lang_mssg["delt"]."</a></td>
  </tr>
</table>";
   $rs = mysql_query("UPDATE phaos_mail SET status='read' WHERE mail_id='$mail_id'");
   }



   if($action==delete)
   {
   $query = mysql_query("DELETE FROM phaos_mail WHERE mail_id='$id' AND SentDate='$SentDate' LIMIT 1");
      if($query)
      {
         echo $lang_mssg["msg_dellt"];
      }
      else
      {
         echo $lang_mssg["mssg_wasn"];
      }
   }
?>

</body>
</html>
<?php include "footer.php"; ?>
