<?
include "../aup.php";
?>
<html>
<head>
<script>
function changeScreenSize(w,h) {
	window.resizeTo(w,h)
}
</script>
<meta name="author" content="Zeke Walker">
<title><? echo "$SITETITLE"; ?></title>
<link rel=stylesheet type="text/css" href="../styles/phaos.css">
<link rel="shortcut icon" href="../images/phaos.ico" type="image/x-icon">
<link rel="icon" href="../images/phaos.ico" type="image/x-icon">
<?
if($play_music == "") {$play_music = 'NO';}
if($play_music == "YES"){
        if($song_select == "") {$song_select = rand(1,4);}
        if($song_select == $old_song) {$song_select = rand(1,4);}
        if($song_select == $old_song) {$song_select = rand(1,4);}
        if($song_select == $old_song) {$song_select = rand(1,4);}
        if($song_select == $old_song) {$song_select = rand(1,4);}
        if($song_select == 1) {
                ?>
		<embed SRC="homeland_farmland.mid" hidden="true" LOOP="true">
                <?
        } elseif($song_select == 2) {
                ?>
		<embed SRC="under_the_bards_tree.mid" hidden="true" LOOP="true">
                <?
        } elseif($song_select == 3) {
                ?>
		<embed SRC="stranger_on_a_hill.mid" hidden="true" LOOP="true">
                <?
        } elseif($song_select == 4) {
                ?>
		<embed SRC="the_town_of_witchwoode.mid" hidden="true" LOOP="true">
                <?
        }
}
?>
<meta http-equiv="REFRESH" content="90;URL=index.php?play_music=YES&old_song=<? print $song_select; ?>">
</head>
<body onload="changeScreenSize(300,400);starttime();">
<div align="center">
<img src="../images/top_logo.png">
<p>
<big><b>Music Player</b></big>
<p>
<hr>
<p>
<a href="index.php?play_music=YES&old_song=<? print $song_select; ?>">Play New Song</a>
<p>
<hr>
<p>
<a href="javascript:window.close();">Close Music Player</a>
</div>
</body>
</html>
