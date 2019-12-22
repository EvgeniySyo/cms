<?php

$TemplatesSection = CMS::SectionFile('installmodule');
$installNewModule = 2;

if(isset($_POST['add']))
{
	if(file_exists("modules/".$_POST['mod']."/index.php"))
	{
		$nameInstallMod = $_POST['mod'];
		if(file_exists(DATA_PATH."/modules/".$_POST['mod']."/mysql.php")) include(DATA_PATH."/modules/".$_POST['mod']."/mysql.php");
		if(file_exists(DATA_PATH."/modules/".$_POST['mod']."/system.php")) include(DATA_PATH."/modules/".$_POST['mod']."/system.php");
		Simple_DbApi::insert_db("modules","name,status,acsess,install","".strip_tags($_POST['mod'])."<><>on<><>-10<><>yes");
		$installNewModule = 1;

		if(file_exists("modules/".$_POST['mod']."/title.php")) include("modules/".$_POST['mod']."/title.php");
		if(empty($module_title)) $module_title = $_POST['mod'];
		
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> установил модуль <b>'.$module_title.'</b>. Раздел <a target="_blank" href="main-modules-install.pl"><b>Установка модулей</b></a>');
		unset($module_title);
	}
}

echo CMS::SectionAdmin($TemplatesSection,1,"","");
echo CMS::SectionAdmin($TemplatesSection,3,"","");

$DirList = scandir("modules/");

$SelectListModule = Simple_DbApi::select_db("modules","*","install","yes","","","","");
echo CMS::SectionAdmin($TemplatesSection,4,"","");

$LiStInstallMod = array();

foreach ($SelectListModule as $nm1) {
    $LiStInstallMod[] = $nm1['name'];
}

$ListFind = 0;

for ($i=2;$i<count($DirList);$i++)
{
	$FindMod = false;
	for($j=0;$j<count($LiStInstallMod);$j++) if(is_dir("modules/".$DirList[$i]) && $LiStInstallMod[$j] == $DirList[$i]) $FindMod = true;
	if($FindMod == false)
	{
		if(file_exists("modules/".$DirList[$i]."/index.php"))
		{
			$module_title = '';
			if(file_exists("modules/".$DirList[$i]."/title.php")) include("modules/".$DirList[$i]."/title.php");
			if(empty($module_title)) $module_title = "no name";

			if(file_exists('templates/admin/ico/module/'.$DirList[$i].'.png')) $image = 'templates/admin/ico/module/'.$DirList[$i].'.png';
			else $image = 'templates/admin/ico/admin/blockdevice.png';

			echo CMS::SectionAdmin($TemplatesSection,6,"%NAME%,%ID%,{img}","".$module_title."<><>".$DirList[$i]."<><>".$image);
			$ListFind =+ 1;
		}
	}
}

echo CMS::SectionAdmin($TemplatesSection,5,"","");

if($ListFind == 0 && !isset($_POST['add'])) echo CMS::SectionAdmin($TemplatesSection,8,"","");

if($installNewModule == 1) echo CMS::AlertWindow('Успешно','Модуль установлен',1,0);


echo CMS::SectionAdmin($TemplatesSection,2,"","");
?>