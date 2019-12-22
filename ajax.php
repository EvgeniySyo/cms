<?php
header('Content-Type: text/html; charset=utf-8');
if(!file_exists("includes/lock.pro"))
{
	header('Location: /install/index.php');
	exit ;
}
session_start();
unset($data_path);
include("includes/define.php");
if(LISTERRSITE == "on") error_reporting(0);
$simple_error = new Simple_Error();
$CMS = new CMS();
$simple_db_work = new Simple_DbApi();
$simple_error->Test_File_Insite(DATA_PATH."/config.php",SIMPLE_NOT_FILE." config.php"); 
$simple_error->Test_File_Insite(DATA_PATH."/system.php",SIMPLE_NOT_FILE." system.php");
$simple_error->Test_File_Insite(INCLUDES."/lib.php",SIMPLE_NOT_FILE." lib.php");
$simple_error->Test_File_Insite(LANGUAGES."/".LANDSITE.".php",SIMPLE_NOT_FILE." ".LANGUAGES."/".LANDSITE.".php");
$CMS->closed_site();
if(file_exists(INCLUDES."/ApiUser.php")) include(INCLUDES."/ApiUser.php");
$time_generate_page = $CMS->timing_site_start();
if(!isset($_SESSION["login_user"])) $_SESSION["status_user"] = 0;
$simple_db_work->connect_db();
$CMS->test_ip_ban();
if(!isset($_GET['page'])) $_GET['page'] = DEFMODUL;
else $_GET['page'] = $_GET['page'];
$CMS->simple_module($_GET['page']);

/*
if(isset($_GET['i']))
{
    $d = 'images/shop/product/';
    $dir = scandir($d);
    
    for($i=2;$i<count($dir);$i++)
    {
        list($name,$type) = explode('.',$dir[$i],2);
        if($name > 0)
        {
            echo $name."\n";
        }
    }
    
    exit;
}
*/

$module = $CMS->module_content();
echo $module;


?>