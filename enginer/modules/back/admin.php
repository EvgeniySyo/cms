<?php
#save
$s = $s1 = '';
$TitleModule = CMS::TitleComponent($_GET['name_tamlates']);
if(isset($_POST['save']))
{

	### VALID INFO ###
	$mail = strip_tags($_POST['mail']);
	$subject = strip_tags($_POST['subj']);
	if($_POST['cap'] == 1) $capcha = 1;
	else $capcha = 2;
	### END ###

	$file = file(DATA_PATH."modules/".$_GET['name_tamlates']."/config.php");
	$fp = fopen(DATA_PATH."modules/".$_GET['name_tamlates']."/config.php","w");
	flock($fp,LOCK_EX);
	for ($i=0;$i<count($file);$i++)
	{
		if($i == 1) fwrite($fp,"\$mail = '".$mail."';\n");
		else if($i == 2) fwrite($fp,"\$subject = '".$subject."';\n");
		else if($i == 3) fwrite($fp,"\$capcha = ".$capcha.";\n");
		else fwrite($fp,$file[$i]);
	}
	flock($fp,LOCK_UN);
	fclose($fp);

	CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> изменил настройки в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'.pl"><b>'.$TitleModule.'</b></a>');
	
	echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);

}

echo CMS::AdminModuleSection(0, "","");
if ($_GET['page'] == 'main') 
{
	if($capcha == 1) $s = 'selected';
	else $s1 = 'selected';
	echo CMS::AdminModuleSection(1, "%MOD%,%MAIL%,%subj%,{s},{s1}", $_GET['name_tamlates']."<><>".$mail."<><>".$subject."<><>".$s."<><>".$s1);	
}
else
{
	if ($_GET['page']=='1') echo CMS::AdminModuleSection(2, "%MOD%", $_GET['name_tamlates']);
 	if ($_GET['page']=='2') 
 	{
 		if (isset($_POST['edit'])) 
 		{
 			file_put_contents(DATA_PATH."modules/".$_GET['name_tamlates']."/before.txt",$_POST['FCKeditor']);
 			echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
 			CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> изменил текст до формы обратной связи. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'-2.pl"><b>Редактирование текста до формы обратной связи</b></a>');
 		}
 		$before = file_get_contents(DATA_PATH."modules/".$_GET['name_tamlates']."/before.txt");

 		echo CMS::AdminModuleSection(3, "%TEXT%,%MOD%", "до<><>".$_GET['name_tamlates']);
		echo CMS::CKeditor($before,'','');
		echo CMS::AdminModuleSection(4, "", "");
	}

 	if ($_GET['page']=='3') 
 	{
 		if (isset($_POST['edit'])) 
 		{
 			file_put_contents(DATA_PATH."modules/".$_GET['name_tamlates']."/after.txt",$_POST['FCKeditor']);
 			echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
 			CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> изменил текст после формы обратной связи. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'-3.pl"><b>Редактирование текста после формы обратной связи</b></a>');
 		}
 		$after = file_get_contents(DATA_PATH."modules/".$_GET['name_tamlates']."/after.txt");
 		
 		echo CMS::AdminModuleSection(3, "%TEXT%,%MOD%", "после<><>".$_GET['name_tamlates']);
		echo CMS::CKeditor($after,'','');
		echo CMS::AdminModuleSection(4, "", "");
	}
}


?>