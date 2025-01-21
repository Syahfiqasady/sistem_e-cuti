<?
// error_reporting(~E_DEPRECATED & ~E_ERROR & ~E_NOTICE & ~E_WARNING);
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
session_start();
$db = mysqli_connect("localhost", "db_name", "db_pwd*", "db_username") or die("Cannot connect to database.");
mysqli_set_charset($db, 'utf8');
$session_pfx = "db_session_pfx";


$site_url = "site_url";
$base_url = "base_url";

$mail_sender = "noreply@site_url";
$mail_recipient = "admin@site_url";
$site_email = "info@site_url";
$site_name = "site_name";
$site_phone = "";
$site_address = "";



//form parameters
function hsc($s)
{
	return htmlspecialchars($s, ENT_QUOTES);
}
function frmp($s)
{
	if (isset($_POST[$s])) return hsc($_POST[$s]);
	else return "";
}
function frmg($s)
{
	if (isset($_GET[$s])) return hsc($_GET[$s]);
	else return "";
}
function frm($s)
{
	if (frmp($s) != "") return frmp($s);
	else if (frmg($s) != "") return frmg($s);
	else return "";
}
function frmr($s)
{
	if (isset($_POST[$s]) && $_POST[$s] != "") return $_POST[$s];
	else return "";
}

function img($s)
{
	return str_replace('../', '', $s);
}

//mysql shortcuts
function mq($s)
{
	global $db;
	return mysqli_query($db, $s);
}
function mfa($s)
{
	return mysqli_fetch_array($s);
}
function mnr($s)
{
	return mysqli_num_rows($s);
}

//session handling
function set($s, $v)
{
	global $session_pfx;
	$_SESSION[$session_pfx . $s] = $v;
}
function get($s)
{
	global $session_pfx;
	if (isset($_SESSION[$session_pfx . $s])) return $_SESSION[$session_pfx . $s];
	else return "";
}

//cookie handling
function setc($s, $v)
{
	global $session_pfx;
	setcookie($session_pfx . $s, $v, (time() + 60 * 60 * 24 * 365));
}
function getc($s)
{
	global $session_pfx;
	if (isset($_COOKIE[$session_pfx . $s])) return $_COOKIE[$session_pfx . $s];
	else return "";
}

//time formating
function now()
{
	return date('Y-m-d H:i:s');
}
function sdf($t)
{
	return date('d M Y', strtotime($t));
}
function sdtf($t)
{
	return date('d-M-Y, h:i a', strtotime($t));
}
function sdfnm($t)
{
	return date('d-m-Y', strtotime($t));
}
function stf($t)
{
	return date('h:i a', strtotime($t));
}
function sdfd($t)
{
	return date('d', strtotime($t));
}
function sdfm($t)
{
	return date('m', strtotime($t));
}
function sdfy($t)
{
	return date('Y', strtotime($t));
}

//date manipulation - only accept / return Y-m-d format. e.g. moddate('2008-01-01','+3 day')
function monthnm($t)
{
	return date("F", mktime(0, 0, 0, sdfm($t), 10));
}
function moddate($s, $v)
{
	return date('Y-m-d', strtotime($v, strtotime($s)));
}

//number formating
function dfi($s)
{
	return number_format($s, 0);
}
function dfd($s)
{
	return number_format($s, 2);
}


$this_file = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], "/") + 1);
$this_url = $site_url . '/' . $_SERVER['REQUEST_URI'];
$this_page = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
// pr($_SERVER);



$setup = mfa(mq("SELECT * FROM setup WHERE id =1"));

$leave_type = array();
$rxy = mq("SELECT * FROM leave_type");
while ($rx=mfa($rxy)){
    $leave_type[$rx['id']] = $rx['name'];
}

$status = array();
$status = ['Baru', 'Disetujui', 'Ditolak'];
$current_year = date("Y");


 
