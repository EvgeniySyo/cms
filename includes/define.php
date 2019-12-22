<?php
$UrlSite = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$UrlSite = substr($UrlSite,0,(strlen($UrlSite)-9));
include("includes/path.php");

if(!file_exists($data_path) || !is_dir($data_path))
{
    $data_path = $_SERVER['DOCUMENT_ROOT'].'/enginer/';
    if(file_exists($data_path) || is_dir($data_path))
    {
        $fp = fopen('path.php','w');
        fwrite($fp,"<?php\n\$data_path = \"".$data_path."\";\n?>");
        fclose($fp);
    }
}

define("copyright","Simple CMS by Drakon4ik");
define('INCLUDES','includes');
define('DATA_PATH',$data_path);
define('BLOCK','block');
define('PLAGIN','plagin');
define('MODULES','modules');
define('TEMPLATES','templates');
define('LANGUAGES','languages');
require("includes/CMS.class.php");
require("includes/erorr.php");
require(DATA_PATH."/config.php");
require(INCLUDES."/lib.php");
require(DATA_PATH."/system.php");
define("LANDSITE",$languages);
require(LANGUAGES."/".LANDSITE.".php");
require(LANGUAGES."/system".LANDSITE.".php");
######################## define system #######################
define("_SERVEDB_",$db["server"]); # server db
define("_PORTDB_",$db["port"]); # port db
define("_LOGINDB_",$db["login"]); # login db
define("_PASSDB_",$db["password"]); # password db
define("_DATABASE_",$db["data_base"]); # database db
define("_PREFIXDB_",$db["prefix"]); # prefix db
define("TITLESITE",$html["title"]); # title site
define("KEYWORDSITE",$html["keywords"]); # keywords
define("ENCODE",$html["charset"]); # encode
define("DEFMODUL",$default_module); # start module
if(!isset($_GET['page'])) $_GET['page'] = DEFMODUL;
else $_GET['page'] = $_GET['page'];
$simple_theme = OtherTemplates($html["templates"]);
define("TSITE",$simple_theme); # templates site
define("BUFFERSITE",$buffer_date); # buffering in site
define("TIMINGSITE",$timing); # work time site
define("STATSITE",$statistic); # statistic site
define("LISTERRSITE",$error); # error php in site
define("CLOSEDSITE",$closed_site); # closed site
define("URLSITE",$url_site); # url full
define("USERSINSITE",$users_on_site); # user on site
define("LOOKING",$look_users_down); # ???
define("PATH_TO_THEME",TEMPLATES."/".TSITE."/");
define("URL_SITE_THEME","http://".$UrlSite.TEMPLATES."/".TSITE."/");
define("URL_SITE","http://".$UrlSite);
define("SIMPLE_NOT_FILE","Файл не найден:");
define("SIMPLE_DESC",$html["description"]);
######################## end ################################
CMS::CoreComponent('js');
CMS::CoreComponent('db');
?>