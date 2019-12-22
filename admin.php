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
include('includes/sf.php');
if(Simple_Search_Text("admin.php",$_SERVER['REQUEST_URI']) && !isset($_GET['ajax']) && !isset($_GET['ajax1'])) header("location: /error/");
session_start();
define("ADMIN","admin");

include("includes/define.php");
CMS::init();
include(LANGUAGES."/".$languages.".php");
$alert = new Simple_Theme_Work();
CMS::test_ip_ban();
CMS::$error->Test_File_Insite(DATA_PATH."/config.php",SIMPLE_NOT_FILE." config.php");
CMS::$error->Test_File_Insite(DATA_PATH."/system.php",SIMPLE_NOT_FILE." system.php");
CMS::$error->Test_File_Insite(INCLUDES."/lib.php",SIMPLE_NOT_FILE." lib.php");
CMS::$error->Test_File_Insite(LANGUAGES."/".LANDSITE.".php",SIMPLE_NOT_FILE." ".LANGUAGES."/".LANDSITE.".php");
if(Simple_Search_Text('MSIE 6',$_SERVER['HTTP_USER_AGENT'])) $alert->Theme_Error('WARNING: Internet Explorer 6 not supported');
if(file_exists(INCLUDES."/ApiUser.php")) include(INCLUDES."/ApiUser.php");
if(isset($_GET['exit']))
{
    CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> вышел из ситемы управления');
	unset($_SESSION["login_admin"]);
	unset($_SESSION["pass_admin"]);
	unset($_SESSION["acsses_admin"]);
}
CMS::ErrorEnter();
if(isset($_SESSION["acsses_admin"]) && $_SESSION["acsses_admin"] > 100) include(ADMIN."/index.php");
else CMS::TestAuth();
?>