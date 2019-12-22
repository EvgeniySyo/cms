<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!file_exists("includes/lock.pro"))
{
	header('Location: /install/index.php');
	exit ;
}

require("includes/define.php");
$simple_db_work = new Simple_DbApi();

session_start();
unset($data_path);



if(LISTERRSITE == "on")error_reporting(0);
CMS::init();
require('includes/sf.php');
if(Simple_Search_Text("index.php", $_SERVER['REQUEST_URI'])) header("location: /error/");
AliasHandler($_SERVER['REQUEST_URI']);
CMS::$error->Test_File_Insite(DATA_PATH . "/config.php", SIMPLE_NOT_FILE . " config.php");
CMS::$error -> Test_File_Insite(DATA_PATH . "/system.php", SIMPLE_NOT_FILE . " system.php");
CMS::$error -> Test_File_Insite(INCLUDES . "/lib.php", SIMPLE_NOT_FILE . " lib.php");
CMS::$error -> Test_File_Insite(LANGUAGES . "/" . LANDSITE . ".php", SIMPLE_NOT_FILE . " " . LANGUAGES . "/" . LANDSITE . ".php");


$simple_js_lib = new simple_lib_js();
CMS::closed_site();

$time_generate_page = CMS::timing_site_start();
if(empty($_GET['page'])) $_GET['page'] = '';
if(empty($_GET['cat'])) $_GET['cat'] = '';
if(!isset($_SESSION["login_user"])) $_SESSION["status_user"] = 0;
if($_GET['page'] != 'spare' && $_GET['cat'] != 's')
{
	$_SESSION['SIMPLE_SPARE_SORT_TYPE'] = 1;
	$_SESSION['SIMPLE_SPARE_SEARCH_TYPE'] = 1;
	$_SESSION['SIMPLE_SPARE_TIME_DELIVERY'] = 0;
}
$_SESSION["SIMPLE_SEARCH_NUMBER"] = $_GET['cat'] == 's' ? $_GET['in'] : '';
if(!isset($_SESSION['COUNT_PRODUCT'])) $_SESSION['COUNT_PRODUCT'] = 0;
if(!isset($_SESSION['SIMPLE_COUNT_MONEY'])) $_SESSION['SIMPLE_COUNT_MONEY'] = 0;

CMS::test_ip_ban();
CMS::CoreComponent('stat');
$simple_stat = new simple_statistic();
if(file_exists(TEMPLATES . "/" . TSITE . "/start.php"))
{
	if(!isset($_GET['page']) || $_GET['page'] == DEFMODUL) $TemplateStart = "start.php";
	else
	{
		if($_GET['page'] == "private" && file_exists(TEMPLATES . "/" . TSITE . "/private.php")) $TemplateStart = "private.php";
		else $TemplateStart = "index.php";
	}
}
else $TemplateStart = "index.php";
if(!file_exists(PATH_TO_THEME . $TemplateStart))$TemplateStart = "index.php";
CMS::simple_module($_GET['page']);
if (empty($page)) {
    $page = '';
}
$left = CMS::format_block("left", $page);
$right = CMS::format_block("right", $page);
$center = CMS::format_block("center", $page);
$down = CMS::format_block("down", $page);
$title = CMS::$metaTitle;
$title = strip_tags($title);
$module = CMS::module_content();
if(BUFFERSITE == "on")ob_start();
include('includes/mail.php');
$Pro100KeyWords = strlen(CMS::$metaKey) > 0 ? CMS::$metaKey : $Pro100KeyWords = KEYWORDSITE;
$Pro100Description = !empty(CMS::$metaDesc) ? CMS::$metaDesc : SIMPLE_DESC;
if(file_exists(TEMPLATES . "/" . TSITE . "/sector")) CMS::$SectorFile = CMS::ReadSectorFileTheme();
include (TEMPLATES . "/" . TSITE . "/" . $TemplateStart);
if(BUFFERSITE == "on") ob_end_flush();
$time_generate_page_end = CMS::timing_end_site($time_generate_page);
CMS::db_times();
CMS::bottom_text($time_generate_page_end);
?>