<?php
$TitleModule = CMS::TitleComponent($_GET['name_tamlates']);
if(isset($_POST['FCKeditor']))
{
	//str_replace("\\","",$_POST['FCKeditor']);
	$path = DATA_PATH.'modules/'.$_GET['name_tamlates'].'/text';
	$infoSave = htmlspecialchars($_POST['FCKeditor']);
	$infoSave = html_entity_decode($infoSave);
	$infoSave = str_replace("\\","",$infoSave);
	$fp = fopen($path,"w");	
	flock($fp,LOCK_EX);
	fwrite($fp,$infoSave);
	flock($fp,LOCK_UN);
	fclose($fp);
	CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> внес изменения в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'.pl"><b>'.$TitleModule.'</b></a>');
	echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
}

echo CMS::AdminModuleSection(1,"","");

$path = DATA_PATH.'modules/'.$_GET['name_tamlates'].'/text';
if(file_exists($path))
{
	$te = file_get_contents($path);
	$editor = CMS::CKeditor($te,'','');
	echo CMS::AdminModuleSection(3,"{editor},{seo}",$editor."<><>".CMS::SeoFormBase());
}
else echo "Файл шаблон по данному модулю отсутствует!";

echo CMS::AdminModuleSection(2,"","");

?>