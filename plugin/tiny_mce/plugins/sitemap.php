<?php
header('Content-Type: text/html; charset=utf-8');
if(!file_exists("../../../includes/lock.pro"))
{
	header('Location: /install/index.php');
	exit ;
}

class Simple_Error
{
	function Test_File_Insite($file,$text)
	{
		if(!file_exists($file)) Simple_Theme_Work::Theme_Error($text);
	}
}

class Simple_Theme_Work
{
	function Theme_Error($text)
	{
		$DirSite = $_SERVER['DOCUMENT_ROOT'].'/';
		include($DirSite."pro100/error.php");
		exit;
	}
}

$DirSite = $_SERVER['DOCUMENT_ROOT'].'/';
session_start();
$UrlSite = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$UrlSite = substr($UrlSite,0,(strlen($UrlSite)-9));
include($DirSite."includes/path.php");
define("copyright","Simple CMS");
define('INCLUDES','includes');
define('DATA_PATH',$data_path);
define('BLOCK','block');
define('PLAGIN','plagin');
define('MODULES','modules');
define('TEMPLATES','templates');
define('LANGUAGES','languages');
include($DirSite."includes/class.php");
include(DATA_PATH."/config.php");
include($DirSite.INCLUDES."/lib.php");
include(DATA_PATH."/system.php");
define("LANDSITE",$languages);
include($DirSite.LANGUAGES."/".LANDSITE.".php");
include($DirSite.LANGUAGES."/system".LANDSITE.".php");
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
define("LANDSITE",$languages); # site languages
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
if(LISTERRSITE == "on") error_reporting(0);
$simple_db_work = new Simple_DbApi();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Карта сайта</title>
<style type="text/css">
body{margin:8px;background:#F8F8F8;font-family:Arial;}
.mainTopP {
font-size: 22px;
border-bottom: 2px solid #23BDE4;
padding-top:0px;
}
input
{
width:70px;
border:1px #000 solid;
backgroind:none;
cursor:pointer;
margin-right:15px;
margin-top:15px;
}
h5{margin:0;padding-bottom:3px;font-size:14px;font-weight:normal;z-index:10;}
img{border:none;}
</style>
<script>
<!--
function fa()
{
	window.returnValue=false;
	window.close();
}
function tr()
{
	window.returnValue=linkk;
	window.close();
}

function opp(idd)
{
	if(document.getElementById(idd).style.display=="none")
	{
		document.getElementById('new').style.display="none";
		document.getElementById('del').style.display="none";
		document.getElementById('edit').style.display="none";
		document.getElementById(idd).style.display="block";
	}
	else
	{
		document.getElementById(idd).style.display="none";
	}
}
function OpenList(id)
{
	if(document.getElementById(id).style.display=="none")
	{
		document.getElementById(id).style.display="block";
	}
	else
	{
		document.getElementById(id).style.display="none";
	}
}
function opd(idd)
{
	if(document.getElementById('cat'+idd).style.display=="none")
	{
		document.getElementById('cat'+idd).style.display="block";
	}
	else
	{
		document.getElementById('cat'+idd).style.display="none";
	}
}
function foc(idd,ev)
{
	if(ev==1)
	{
		if(parseInt(document.getElementById('sel'+idd).style.zIndex)!==777)
		{
			document.getElementById('sel'+idd).style.backgroundColor="#c3e1f8";
		}
	}
	else
	{
		if(parseInt(document.getElementById('sel'+idd).style.zIndex)!==777)
		{
			document.getElementById('sel'+idd).style.backgroundColor="";
		}
	}
}

lastsel='0';
linkk='';
function sell(idd)
{
	document.getElementById('sel'+idd).style.backgroundColor="#7af";
	document.getElementById('sel'+idd).style.zIndex=777;
	linkk='/mc/'+idd+'/';
	if((idd!==lastsel)&&(lastsel!=='0'))
	{
		document.getElementById('sel'+lastsel).style.backgroundColor="";
		document.getElementById('sel'+lastsel).style.zIndex='';
	}
	lastsel=idd;
	return lastsel;
	return linkk;
}
function selp(idd)
{
	document.getElementById('sel'+idd).style.backgroundColor="#7af";
	document.getElementById('sel'+idd).style.zIndex=777;
	linkk='/'+idd+'/';
	if((idd!==lastsel)&&(lastsel!=='0'))
	{
		document.getElementById('sel'+lastsel).style.backgroundColor="";
		document.getElementById('sel'+lastsel).style.zIndex='';
	}
	lastsel=idd;
	return lastsel;
	return linkk;
}
function titt()
{
	if(document.getElementById('ch').selectedIndex==1)
	{
		document.getElementById('tit').value='Новая страница';
	}
	else
	{
		document.getElementById('tit').value='Новый раздел';
	}
}
var idna = new Array(1000000);
var idst = new Array(1000000);
moved=0;
function mov()
{
	alert('Выберите раздел и нажмите "Сохранить"');
	moved=1;
	return moved;
}
function linkk(idd)
{
	window.location.href="/mc/"+idd+"/";
}
-->
</script>
</head>
<?
function scan($idd,$t)
{

	$qq = Simple_DbApi::select_db('mc','*','parent',$idd,'id',1,'','');
	if(!empty($qq)) $inc=1;
	else $inc=0;

	$qq= Simple_DbApi::select_db('mc','*','id',$idd,'id',1,'','');
	$kat = current($qq);
       ?>
	   <script>
	   <!--
	   idna[<? echo $kat['id']; ?>]='<? echo $kat['name']; ?>';
	   idst[<? echo $kat['id']; ?>]='<? echo $kat['status']; ?>';
	   -->
	   </script>
	   <?
	   if($kat['type']=='cat')
	   {

	   	if($inc!==0)
	   	{
	   		echo '<h5><a href="#d" onclick=opd("'.$idd.'")><img src="../images/mc/plus.jpg" border=0></a>&nbsp;<img src="../images/mc/folder.png" align="absmiddle" onmouseover=foc("'.$kat['id'].'",1) onmouseout=foc("'.$kat['id'].'",0) onclick=sell("'.$kat['id'].'")>&nbsp;<span onmouseover=foc("'.$kat['id'].'",1) onmouseout=foc("'.$kat['id'].'",0) onclick=sell("'.$kat['id'].'") id="sel'.$kat['id'].'" style="cursor:default;">'.$kat['name'].'</span></h5>';
	   	}
	   	else
	   	{
	   		echo '<h5><img src="../images/mc/folder.png" style="margin:0 0 0 15px" align="absmiddle" onmouseover=foc("'.$kat['id'].'",1) onmouseout=foc("'.$kat['id'].'",0) onclick=sell("'.$kat['id'].'")>&nbsp;<span onmouseover=foc("'.$kat['id'].'",1) onmouseout=foc("'.$kat['id'].'",0) onclick=sell("'.$kat['id'].'") id="sel'.$kat['id'].'" style="cursor:default;">'.$kat['name'].'</span></h5>';
	   	}
	   	$qq=Simple_DbApi::select_db('mc','*','parent',$idd,'id','1','','');
	   	echo'<div style="display:none; padding:0 0 0 18px;" id="cat'.$idd.'">';
	   	while($kat = array_shift($qq))
	   	{
	    ?>
	   <script>
	   <!--
	   idna[<? echo $kat['id']; ?>]='<? echo $kat['name']; ?>';
	   idst[<? echo $kat['id']; ?>]='<? echo $kat['status']; ?>';
	   -->
	   </script>
	   <?
	   if($kat['type']=='page')
	   {
	   	echo'<h5><img src="../images/mc/page.png" align="absmiddle" onmouseover=foc("'.$kat['id'].'",1) onmouseout=foc("'.$kat['id'].'",0) onclick=sell("'.$kat['id'].'") style="margin:0 0 0 17px;">&nbsp;<span onmouseover=foc("'.$kat['id'].'",1) onmouseout=foc("'.$kat['id'].'",0) onclick=sell("'.$kat['id'].'") id="sel'.$kat['id'].'" style="cursor:default;">'.$kat['name'].'</span></h5>';
	   }
	   else
	   {
	   	echo scan($kat['id'],$t);
	   }
	   	}
	   	echo'</div>';
	   }
	   else if($kat['type']=='page')
	   {
	   	echo'<h5><img src="../images/mc/page.png" align="absmiddle" onmouseover=foc("'.$kat['id'].'",1) onmouseout=foc("'.$kat['id'].'",0) onclick=sell("'.$kat['id'].'") style="margin:0 0 0 17px;">&nbsp;<span onmouseover=foc("'.$kat['id'].'",1) onmouseout=foc("'.$kat['id'].'",0) onclick=sell("'.$kat['id'].'") id="sel'.$kat['id'].'" style="cursor:default;">'.$kat['name'].'</span></h5>';
	   }
}
?>
<body>
<p class="mainTopP">Карта сайта</p>
<?
CMS::CoreComponent('module');
if(Simple_Module::TestInstall('mc') == true)
{
	$qq=CMS::$db->query("SELECT * FROM `mc` WHERE `parent`='0' ORDER BY `type`,`order` ASC ");
	foreach ($qq as $kat) {
        echo scan($kat['id'],$t);
    }
}
$qq=Simple_DbApi::select_db('modules','*','install','yes','name',1,'','');
foreach ($qq as $kat) {
{
	if($kat['name'] != 'mc' && $kat['name'] != 'error' && $kat['name'] != 'news')
	{
		if(!empty($kat['title'])) $title = $kat['title'];
		else
		{
			include("../../../modules/".$kat['name']."/title.php");
			$title = $module_title;
		}
		echo '<h5><img src="../images/mc/folder.png" style="margin:0 0 0 15px" align="absmiddle" onmouseover=foc("'.$kat['name'].'",1) onmouseout=foc("'.$kat['name'].'",0) onclick=selp("'.$kat['name'].'")>&nbsp;<span onmouseover=foc("'.$kat['name'].'",1) onmouseout=foc("'.$kat['name'].'",0) onclick=selp("'.$kat['name'].'") id="sel'.$kat['name'].'" style="cursor:default;">'.$title.'</span></h5>';
	}
	else
	{
		if($kat['name'] == 'news')
		{
			$SelectCatNews = Simple_DbApi::select_db('news_cat','*','','','names',1,'','');
			if(empty($SelectCatNews))
			{
				if(!empty($kat['title'])) $title = $kat['title'];
				else
				{
					include("../../../modules/".$kat['name']."/title.php");
					$title = $module_title;
				}
				echo '<h5><img src="../images/mc/folder.png" style="margin:0 0 0 15px" align="absmiddle" onmouseover=foc("'.$kat['name'].'",1) onmouseout=foc("'.$kat['name'].'",0) onclick=selp("'.$kat['name'].'")>&nbsp;<span onmouseover=foc("'.$kat['name'].'",1) onmouseout=foc("'.$kat['name'].'",0) onclick=selp("'.$kat['name'].'") id="sel'.$kat['name'].'" style="cursor:default;">'.$title.'</span></h5>';
			}
			else 
			{
				
				if(!empty($kat['title'])) $title = $kat['title'];
				else
				{
					include("../../../modules/".$kat['name']."/title.php");
					$title = $module_title;
				}
				
				echo '<h5><a href="#" onclick=OpenList("news")><img src="../images/mc/plus.jpg"></a>&nbsp;<img src="../images/mc/folder.png" align="absmiddle" onmouseover=foc("'.$kat['name'].'",1) onmouseout=foc("'.$kat['name'].'",0) onclick=selp("'.$kat['name'].'")>&nbsp;<span onmouseover=foc("'.$kat['name'].'",1) onmouseout=foc("'.$kat['name'].'",0) onclick=selp("'.$kat['name'].'") id="sel'.$kat['name'].'" style="cursor:default;">'.$title.'</span></h5>';
				echo'<div style="display:none; padding:0 0 0 0px;" id="news">';
				foreach ($SelectCatNews as $i => $nn)
				{
					echo '<h5><img src="../images/mc/page.png" style="margin:0 0 0 15px" align="absmiddle" onmouseover=foc("'.$kat['name'].'/0/in/'.$nn['ids'].'",1) onmouseout=foc("'.$kat['name'].'/0/in/'.$nn['ids'].'",0) onclick=selp("'.$kat['name'].'/0/in/'.$nn['ids'].'")>&nbsp;<span onmouseover=foc("'.$kat['name'].'/0/in/'.$nn['ids'].'",1) onmouseout=foc("'.$kat['name'].'/0/in/'.$nn['ids'].'",0) onclick=selp("'.$kat['name'].'/0/in/'.$nn['ids'].'") id="sel'.$kat['name'].'/0/in/'.$nn['ids'].'" style="cursor:default;">'.$nn['names'].'</span></h5>';
				}
				echo '</div>';
			}
		}
	}
}
?>
<div align="center">
<input name="ok" type="button" value="Готово" onclick="tr()" /><input name="ok" type="button" value="Отмена" onclick="fa()" />
</div>
</body>
</html>