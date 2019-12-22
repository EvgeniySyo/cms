<?php

if(isset($_GET['admin_page']))
{
	if(file_exists("templates/".$_GET['admin_page']."/index.php"))
	{
		$Templates = $_GET['admin_page'];
		$TemplatesSection = CMS::SectionFile('css');

		if(file_exists("templates/".$Templates."/img.png")) $Img = "../templates/".$Templates."/img.png";
		else $Img = "../images/notamplates.png";

		echo CMS::SectionAdmin($TemplatesSection,1,"{name},{img}",$Templates."<><>".$Img);

		$ListTheme = scandir("templates/".$Templates."/css/");
		for ($i=2;$i<count($ListTheme);$i++)
		{
			$typeTheme = pathinfo("templates/".$Templates."/css/".$ListTheme[$i]);
			//$fileType = $css = $typeTheme['filename'];

			if($typeTheme['extension'] == 'css')
			{
				$numberSection = $_GET['name_tamlates'] == $typeTheme['filename'] ? 4 : 3;
				echo CMS::SectionAdmin($TemplatesSection,$numberSection,"{name},{template},{file}",$ListTheme[$i]."<><>".$Templates."<><>".$typeTheme['filename']);
			}

		}

		echo CMS::SectionAdmin($TemplatesSection,2,"","");
		
		// FILE EDIT
		
		if(isset($_GET['name_tamlates']))
		{
			if(file_exists("templates/".$Templates."/css/".$_GET['name_tamlates'].".css"))
			{
				
				if(isset($_POST['update']))
				{
					$fp = fopen("templates/".$Templates."/css/".$_GET['name_tamlates'].".css","w");
					flock($fp,LOCK_EX);
					fwrite($fp,stripslashes($_POST['text']));
					flock($fp,LOCK_UN);
					fclose($fp);
					CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> внес изменения в css файл <b>'.$_GET['name_tamlates'].'.css</b>. Шаблон <b>'.$Templates.'</b>. Раздел <a target="_blank" href="templates-css-'.$Templates.'.pl"><b>Редактитрование css файла</b></a>');
					echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
				}
				
				$ReadFile = file_get_contents("templates/".$Templates."/css/".$_GET['name_tamlates'].".css");
				echo CMS::SectionAdmin($TemplatesSection,5,"{name},{text}",$_GET['name_tamlates'].'<><>'.$ReadFile);
			}
		}
	}
}

?>