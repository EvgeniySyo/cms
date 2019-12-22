<?php


$TemplatesSection = CMS::SectionFile('conf');

if(isset($_POST['enter']))
{
	if($_POST['buffer'] == "on") $buf = "on";
	else $buf = "off";
	if($_POST['stats'] == "on") $sta = "on";
	else $sta = "off";
	if($_POST['time'] == "on") $tim = "on";
	else $tim = "off";
	if($_POST['error'] == "on") $err = "on";
	else $err = "off";
	if($_POST['close'] == "on") $clo = "on";
	else $clo = "off";
	if($_POST['user_online'] == "on") $use = "on";
	else $use = "off";
	if($_POST['looks'] == "on") $look = "on";
	else $look = "off";
	$file_conf = file(DATA_PATH."/config.php");
	$fp = fopen(DATA_PATH."/config.php", "w");
	flock($fp,LOCK_EX);
	
	$titleSave = strip_tags($_POST["title"]);
	$keySave = strip_tags($_POST["key"]);
	$descSave = strip_tags($_POST['desc']);
	$titleSave = str_replace("\"","\\\"",$titleSave);
	$keySave = str_replace("\"","\\\"",$keySave);
	$descSave = str_replace("\"","\\\"",$descSave);
	
	for($i=0;$i<count($file_conf);$i++)
	{
		if($i == 7) fwrite($fp,"\$html[\"title\"] = \"".$titleSave."\";\n");
		elseif($i == 8) fwrite($fp,"\$html[\"keywords\"] = \"".$keySave."\";\n");
		elseif($i == 9) fwrite($fp,"\$html[\"charset\"] = \"UTF-8\";\n");
		elseif ($i == 15) fwrite($fp,"\$buffer_date = \"".strip_tags($buf)."\";\n");
		elseif ($i == 16) fwrite($fp,"\$timing = \"".strip_tags($tim)."\";\n");
		elseif ($i == 17) fwrite($fp,"\$statistic = \"".strip_tags($sta)."\";\n");
		elseif ($i == 18) fwrite($fp,"\$error = \"".strip_tags($err)."\";\n");
		elseif ($i == 19) fwrite($fp,"\$closed_site = \"".strip_tags($clo)."\";\n");
		elseif($i == 21) fwrite($fp,"\$users_on_site = \"".strip_tags($use)."\";\n");
		elseif($i == 22) fwrite($fp,"\$look_users_down = \"".strip_tags($look)."\";\n");
		elseif($i == 23) fwrite($fp,"\$html[\"description\"] = \"".$descSave."\";\n");
		else fwrite($fp,$file_conf[$i]);
	}
	flock($fp,LOCK_UN);
	fclose($fp);
	unset($file_conf);
	unset($fp);
	$fp = fopen(DATA_PATH."/closed_page.php", "w");
	flock($fp,LOCK_EX);
	$closed = strip_tags($_POST['closed_text']);
	$closed = str_replace("\"","\\\"",$closed);
	fwrite($fp,"<?\n\$closed_text = \"".$closed."\";\n?>");
	flock($fp,LOCK_UN);
	fclose($fp);
	echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
	CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> изменил параметры в разделе <a target="_blank" href="configure-conf.pl"><b>настройки сайта</b></a>');
	CMS::ClearCacheDirectory();
}

echo CMS::SectionAdmin($TemplatesSection,1,"","");
echo CMS::SectionAdmin($TemplatesSection,3,"","");

include(DATA_PATH."/config.php");

if($buffer_date == "on") $ch1 = "checked=\"checked\"";
else $ch2 = "checked=\"checked\"";
if($statistic == "on") $ch3 = "checked=\"checked\"";
else $ch4 = "checked=\"checked\"";
if($timing == "on") $ch5 = "checked=\"checked\"";
else $ch6 = "checked=\"checked\"";
if($error == "on") $ch7 = "checked=\"checked\"";
else $ch8 = "checked=\"checked\"";
if($closed_site == "on") $ch9 = "checked=\"checked\"";
else $ch10 = "checked=\"checked\"";
if($users_on_site == "on") $ch11 = "checked=\"checked\"";
else $ch12 = "checked=\"checked\"";
if($look_users_down == "on") $ch13 = "checked=\"checked\"";
else $ch14 = "checked=\"checked\"";

include_once(DATA_PATH."/closed_page.php");

echo CMS::SectionAdmin($TemplatesSection,5,"%TITLE%,%KEY%,%ENCODE%,%CH1%,%CH2%,%CH3%,%CH4%,%CH5%,%CH6%,%CH7%,%CH8%,%CH9%,%CH10%,%CH11%,%CH12,%CH13%,%CH14%,%TEXTCLOSE%,{desc}","".htmlspecialchars($html["title"])."<><>".htmlspecialchars($html["keywords"])."<><>".$html["charset"]."<><>".$ch1."<><>".$ch2."<><>".$ch3."<><>".$ch4."<><>".$ch5."<><>".$ch6."<><>".$ch7."<><>".$ch8."<><>".$ch11."<><>".$ch12."<><>".$ch13."<><>".$ch14."<><>".$ch9."<><>".$ch10."<><>".htmlspecialchars($closed_text)."<><>".htmlspecialchars($html["description"]));

echo CMS::SectionAdmin($TemplatesSection,4,"","");
echo CMS::SectionAdmin($TemplatesSection,2,"","");
?>