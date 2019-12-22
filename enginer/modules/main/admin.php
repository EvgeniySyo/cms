<?php

if(isset($_POST['delete']))
{
	$fp = fopen(DATA_PATH."/modules/".$_GET['name_tamlates']."/config.php","w");
	flock($fp,LOCK_EX);
	fwrite($fp,"<?php\n\$id_pages=\"0\";\n?>");
	flock($fp,LOCK_UN);
	$id_pages = 0;
	CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> изменил страницу по умолчанию в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'.pl"><b>'.$TitleModule.'</b></a>');
	echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
}

if(isset($_POST['set']) && is_numeric($_POST['id']))
{
	$fp = fopen(DATA_PATH."/modules/".$_GET['name_tamlates']."/config.php","w");
	flock($fp,LOCK_EX);
	fwrite($fp,"<?php\n\$id_pages=\"".$_POST['id']."\";\n?>");
	flock($fp,LOCK_UN);
	$id_pages = $_POST['id'];
	echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
}

if($id_pages !=  0 && is_numeric($id_pages))
{
	$SelectPage = Simple_DbApi::select_db("mc","*","id",$id_pages,"","","","");
	if(!empty($SelectPage))
	{
		$ns = current($SelectPage);
		$contents1 = strip_tags($ns['content']);
		$contents_1001 = mb_substr($contents1,0,100);
		echo CMS::AdminModuleSection(3,"{id},{name},{content}",$ns['id']."<><>".$ns['name']."<><>".$contents_1001);
	}
}
$pages = '';
$SelectListPage = Simple_DbApi::select_db("mc","*","","","name",1,"","");
if(!empty($SelectListPage))
{
	foreach ($SelectListPage as $i => $np)
	{
		$contents = strip_tags($np['content']);
		$contents_100 = mb_substr($contents,0,100);
		$pages .= CMS::AdminModuleSection(5,"{id},{name},{content}",$np['id']."<><>".$np['name']."<><>".$contents_100);
	}
	echo CMS::AdminModuleSection(4,"{pages}",$pages);
}

echo CMS::AdminModuleSection(2,"","");

?>