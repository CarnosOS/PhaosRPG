<?php
// Enter your language (see the avaliables in the /lang directory):

$lang = "en"; // default to "en" for English -- but later select lang from users table

if(@$_COOKIE['lang']) {
	$lang= $_COOKIE['lang'];
}

// Enter your MySQL settings and $SITETITLE in this file
@include 'config_settings.php';

//removing 1st class security risk
if(file_exists('phaos.cfg')){
	unlink('phaos.cfg');
}

$connection = mysql_connect("$mysql_server","$mysql_user","$mysql_password") or die ("Unable to connect to MySQL server.");
$db = mysql_select_db("$mysql_database") or die ("Unable to select requested database.");

foreach($_POST as $key=>$value) {
	// IF POST VARIABLE NOT BLANK
	if (isset($_POST[$key])) {
		if(get_magic_quotes_gpc()) {
			$_POST[$key] = stripslashes($_POST[$key]);
		}
		// ESCAPE CHARACTERS
		$_POST[$key] = trim(htmlspecialchars(htmlentities(mysql_real_escape_string($_POST[$key]), ENT_QUOTES)));
	}
}

foreach($_GET as $key=>$value) {
	// IF GET VARIABLE NOT BLANK
	if (isset($_GET[$key])) {
		if(get_magic_quotes_gpc()) {
			$_GET[$key] = stripslashes($_GET[$key]);
		}
		// ESCAPE CHARACTERS
		$_GET[$key] = trim(htmlspecialchars(htmlentities(mysql_real_escape_string($_GET[$key]), ENT_QUOTES)));
	}
}

//Sanity check
$query = "SELECT 1 FROM phaos_characters LIMIT 1";
$result = mysql_query($query);
if (!mysql_fetch_array($result)) {
	die('Missing tables in the database - please import the structure and the data.');
}

// INITIAL SETUP
define('DEBUG',intval(@$_COOKIE['_debug']));
if(DEBUG){
	error_reporting(E_ALL);
} else {
	error_reporting(E_ERROR | E_PARSE);
}

$PHP_PHAOS_USER = @$_COOKIE["PHP_PHAOS_USER"];
$PHP_PHAOS_PW = @$_COOKIE["PHP_PHAOS_PW"];// for compatibility with old accounts
$PHP_PHAOS_MD5PW = @$_COOKIE["PHP_PHAOS_MD5PW"];

$PHP_ADMIN_USER = @$_COOKIE["PHP_ADMIN_USER"];
$PHP_ADMIN_PW = @$_COOKIE["PHP_ADMIN_PW"];// for compatibility with old accounts
$PHP_ADMIN_MD5PW = @$_COOKIE["PHP_ADMIN_MD5PW"];

// FIXME: security hole
foreach($_GET as $key=>$value) {
	$$key = get_magic_quotes_gpc() ? $value : addslashes($value);
}
foreach($_POST as $key=>$value) {
	$$key = get_magic_quotes_gpc() ? $value : addslashes($value);
}

// Additional Security Check
unset($PHP_PHAOS_CHARID);
unset($PHP_PHAOS_CHAR);

$auth = false;
if(@$PHP_PHAOS_USER && ((@$PHP_PHAOS_MD5PW)||(@$PHP_PHAOS_PW)) ) {
	if(@$PHP_PHAOS_MD5PW){
		$query = "SELECT * FROM phaos_users WHERE username = '$PHP_PHAOS_USER' AND password = '$PHP_PHAOS_MD5PW'";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
	}

	if(!@$row){
		$PHP_PHAOS_MD5PW= md5(@$PHP_PHAOS_PW);
		$query = "SELECT * FROM phaos_users WHERE username = '$PHP_PHAOS_USER' AND password = '$PHP_PHAOS_MD5PW'";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
	}

	if ($row) {
		$auth = true;
		$lang = $row['lang'];
		$result = mysql_query("SELECT * FROM phaos_characters WHERE username = '$PHP_PHAOS_USER'");
		if ($row = mysql_fetch_array($result)) {
			$PHP_PHAOS_CHARID	= $row['id'];
			$PHP_PHAOS_CHAR		= $row['name'];
		} else {
			$PHP_PHAOS_CHARID=0;
		}

		if(defined('AUTH')){
			setcookie("PHP_PHAOS_USER",$PHP_PHAOS_USER,time()+17280000); // ( REMEMBERS USER NAME FOR 200 DAYS )		
			setcookie("PHP_PHAOS_MD5PW",$PHP_PHAOS_MD5PW,time()+172800); // ( REMEMBERS USER PASSWORD FOR 2 DAYS )
			setcookie('lang',$lang,time()+17280000); // ( REMEMBERS LANGUAGE FOR 200 DAYS )
			setcookie("PHP_PHAOS_PW",0,time()-3600); // remove cookie used in version 0.88
			if($_GET[play_music] == "YES") {
			        $play_music = $_GET[play_music];
			        setcookie("play_music",$play_music,time()+17280000);
			} elseif($_GET[play_music] == "NO") {
			        $play_music = $_GET[play_music];
			        setcookie("play_music",$play_music,time()+17280000);
			} elseif($_GET[play_music] == "") {
			        $play_music = $_COOKIE[play_music];
		        	setcookie("play_music",$play_music,time()+17280000);
			}
		}
	} else {
		please_register(true);
	}
} else {
	please_register();
}

function please_register($badpass=false){
	if($badpass){
		?><p style="background:black"><p><div align="center"><?php
		?><hr width="10%"><font size=+1 color=red>Bad User Name or Password</font></p><hr width="10%"><?php
		?><p>If you do not already have a character, please Register first!<br><?php
		?></div><?php
	}

	if(!defined('AUTH')){
		//unset these values just in case someone decides to remove the 'exit'
		unset($_COOKIE["PHP_PHAOS_USER"]);
		unset($GLOBALS['PHP_PHAOS_USER']);
		unset($GLOBALS['PHP_PHAOS_CHAR']);
		unset($GLOBALS['PHP_PHAOS_CHARID']);
		// Commented out because admin login wasn't working....please fix
		// exit;
	}
}

