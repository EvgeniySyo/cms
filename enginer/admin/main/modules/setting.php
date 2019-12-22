<?php

if(!isset($_GET['name_tamlates']))
{

	$TemplatesSection = CMS::SectionFile('settingmodule');

	if(isset($_POST['save']))
	{
		$ModulesList = count($_POST['name']);
		for ($t=0;$t<$ModulesList;$t++)
		{
			$title[$t] = strip_tags($_POST['title'][$t]);
			if($_POST['q'][$t] == 1) $StatusMod[$t] = "on";
			else $StatusMod[$t] = "off";

			Simple_DbApi::update_db("modules","status,title","".$StatusMod[$t]."<><>".$title[$t]."","name",$_POST['name'][$t]);

		}

		$file_mod = file(DATA_PATH."/config.php");
		$fp_mod = fopen(DATA_PATH."/config.php", "w");
		flock($fp_mod,LOCK_EX);
		for($x=0;$x<count($file_mod);$x++)
		{
			if($x == 12) fwrite($fp_mod,"\$default_module = \"".strip_tags($_POST['default'])."\";\n");
			else fwrite($fp_mod,$file_mod[$x]);
		}
		flock($fp_mod,LOCK_UN);
		fclose($fp_mod);
		
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> внес изменения. Раздел <a target="_blank" href="main-modules-setting.pl"><b>Настройка модулей</b></a>');
		echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
		
	}

	echo CMS::SectionAdmin($TemplatesSection,1,"","");

	$SelectModuleTitle = Simple_DbApi::select_db("modules","*","install","yes","name",1,"","");

	if(!empty($SelectModuleTitle))
	{
		foreach ($SelectModuleTitle as $i => $nm) {
			if(file_exists("modules/".$nm['name']."/info")) include("modules/".$nm['name']."/info");
			if(empty($names_module)) $names_module = SIMPLE_ADMIN_NOT_DATA;

			$chech1 = "";
			$chech2 = "";
			$chech3 = "";

			if($nm['status'] == "on") $chech1 = "checked=\"checked\"";
			else $chech2 = "checked=\"checked\"";


			include(DATA_PATH."config.php");
			if($nm['name'] == $default_module)
			{
				$text = CMS::SectionAdmin($TemplatesSection,5,"","");
				$chech3 = "checked=\"checked\"";
			}
			else $text = CMS::SectionAdmin($TemplatesSection,6,"","");

			if(file_exists('templates/admin/ico/module/'.$nm['name'].'.png')) $image = 'templates/admin/ico/module/'.$nm['name'].'.png';
			else $image = 'templates/admin/ico/admin/blockdevice.png';

			$list .= CMS::SectionAdmin($TemplatesSection,4,"{name},{i},{check1},{check2},{text},{title},{check3},{n},{image},{url},{url-in}",$names_module."<><>".$i."<><>".$chech1."<><>".$chech2."<><>".$text."<><>".$nm['title']."<><>".$chech3."<><>".$nm['name']."<><>".$image."<><>".URL_SITE.$nm['name']."/<><>/".$nm['name']."/");
		}

		echo CMS::SectionAdmin($TemplatesSection,3,"{list}",$list);
	}

	echo CMS::SectionAdmin($TemplatesSection,2,"","");

}
else
{

	if(file_exists("modules/".$_GET["name_tamlates"]."/languages/".LANDSITE.".php")) include("modules/".$_GET["name_tamlates"]."/languages/".LANDSITE.".php");
	if(file_exists(DATA_PATH."/modules/".$_GET["name_tamlates"]."/config.php")) include(DATA_PATH."/modules/".$_GET["name_tamlates"]."/config.php");
	$TitleModule = CMS::TitleComponent($_GET['name_tamlates']);
	$GLOBALS['ModSection'] = CMS::AdminModuleFileSection();
	if(!isset($_GET['ajax'])) include(DATA_PATH."/modules/".$_GET["name_tamlates"]."/admin.php");
	else 
	{
		if(file_exists(DATA_PATH."/modules/".$_GET["name_tamlates"]."/ajax.php")) include(DATA_PATH."/modules/".$_GET["name_tamlates"]."/ajax.php");
	}
}

?>	