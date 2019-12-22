<?php

if($_GET['config'] != 'css' && $_GET['config'] != 'addition' && $_GET['config'] != 'edittheme')
{

	$TemplatesSection = CMS::SectionFile('templates');
	if(isset($_POST['NameTemplates']))
	{
		if(file_exists("templates/".$_POST['NameTemplates']."/index.php"))
		{
			Simple_DbApi::insert_db("templates","name",$_POST['NameTemplates']);
			CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> установил шаблон <b>'.$_POST['NameTemplates'].'</b>. Раздел <a target="_blank" href="templates.pl"><b>Шаблоны</b></a>');
		}
		
	}

	if(!isset($_POST['editT']))
	{

		if(isset($_POST['id']))
		{

			if(file_exists("templates/".$_POST['id']."/index.php"))
			{

				$file__templates = file(DATA_PATH."config.php");
				$fp_templates = fopen(DATA_PATH."config.php", "w");
				flock($fp_templates,LOCK_EX);
				for($f=0;$f<count($file__templates);$f++)
				{
					if($f != 10) fwrite($fp_templates,$file__templates[$f]);
					else fwrite($fp_templates,"\$html[\"templates\"]=\"".$_POST['id']."\";\n");
				}
				flock($fp_templates,LOCK_UN);
				fclose($fp_templates);
				CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> установил шаблон по умолчанию <b>'.$_POST['id'].'</b>. Раздел <a target="_blank" href="templates.pl"><b>Шаблоны</b></a>');
				CMS::ClearCacheDirectory();
			}
		}

		if(isset($_POST['template']))
		{
			CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> удалил шаблон <b>'.$_POST['id'].'</b>. Раздел <a target="_blank" href="templates.pl"><b>Шаблоны</b></a>');
			Simple_DbApi::delete_db("templates","name",$_POST['template']);
		}

		include(DATA_PATH."config.php");

		$SelectListTheme = Simple_DbApi::select_db("templates","*","","","name",1,"","");

		if (!empty($SelectListTheme)) {
            foreach ($SelectListTheme as $i => $nt)
            {
                if($nt['name'] != "admin")
                {
                    if(file_exists("templates/".$nt['name']."/img.png")) $Img = "../templates/".$nt['name']."/img.png";
                    else $Img = "../images/notamplates.png";



                    if($html["templates"] == $nt['name']) $SelectTemplate = CMS::SectionAdmin($TemplatesSection,3,"","");
                    else $SelectTemplate = CMS::SectionAdmin($TemplatesSection,4,"%ID%",$nt['name']);

                    if(file_exists("templates/".$nt["name"]."/info")) include("templates/".$nt["name"]."/info");

                    $FormatTemplates .= CMS::SectionAdmin($TemplatesSection,2,"%IMG%,%NAME%,%DATE%,%AUTOR%,%MAIL%,%INFO%,%SELECT%",$Img."<><>".$nt['name']."<><>".$date_templates."<><>".$autor_templates."<><>".$email_templates."<><>".$info_templates."<><>".$SelectTemplate);

                }

            }
        }


		echo CMS::SectionAdmin($TemplatesSection,1,"%INFO%",$FormatTemplates);

		# INSTALL #

		$DirList = scandir("templates/");

		$SelectListTheme = Simple_DbApi::select_db("templates","*","","","","","","");

		$LiStInstallTheme = [];
        if (!empty($SelectListTheme)) {
            foreach ($SelectListTheme as $n => $nm1) {
                if ($nm1['name'] != "admin") $LiStInstallTheme[$n] = $nm1['name'];
            }
        }

		sort($LiStInstallTheme);

		$ListFind = 0;

		for ($i=2;$i<count($DirList);$i++)
		{
			$FindMod = false;
			for($j=0;$j<count($LiStInstallTheme);$j++) if(is_dir("templates/".$DirList[$i]) && $LiStInstallTheme[$j] == $DirList[$i] && $DirList[$i] != "admin") $FindMod = true;
			if($FindMod == false)
			{
				if(file_exists("templates/".$DirList[$i]."/index.php") && $DirList[$i] != "admin")
				{
					if(file_exists("templates/".$DirList[$i]."/img.png")) $Img = "../templates/".$DirList[$i]."/img.png";
					if(empty($Img)) $Img = "../images/notamplates.png";
					$FormatTEmplaet .= CMS::SectionAdmin($TemplatesSection,11,"%IMG%,%NAME%","".$Img."<><>".$DirList[$i]."");
					$ListFind = $ListFind + 1;

				}
			}
		}

		# END #

		
		if($ListFind > 0)
		{
			echo CMS::SectionAdmin($TemplatesSection,8,"","");
			echo CMS::SectionAdmin($TemplatesSection,10,"%TABLE%",$FormatTEmplaet);
		}
		
		echo CMS::SectionAdmin($TemplatesSection,12,'','');

	}

	if(isset($_POST['editT']) && file_exists("templates/".$_POST['template']."/index.php"))
	{
		if(file_exists("templates/".$_POST['template']."/img.png")) $Img = "../templates/".$_POST['template']."/img.png";
		else $Img = "../images/notamplates.png";

		if(isset($_POST['save']))
		{
			for ($m=0;$m<count($_POST['name']);$m++)
			{
				$NameSave = htmlspecialchars(trim($_POST['name'][$m]));
				$NameSave = str_replace("\\","",$NameSave);
				$SectorFormat .= "[section::".$_POST['IdSector'][$m]."]".trim($_POST['value'][$m])."::".$NameSave."[/section::".$_POST['IdSector'][$m]."]\n";
			}
			$fp = fopen("templates/".$_POST['template']."/sector","w");
			flock($fp,LOCK_EX);
			fwrite($fp,$SectorFormat);
			flock($fp,LOCK_UN);
			fclose($fp);
			unset($SectorFormat);
			echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
			CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> внес изменения в редактируемые области шаблона <b>'.$_POST['template'].'</b>. Раздел <a target="_blank" href="templates.pl"><b>Шаблоны</b></a>');
		}


		# SECTOR #

		if(file_exists("templates/".$_POST['template']."/sector"))
		{
			//chmod("templates/".$_POST['template']."/sector", 666);
			$ReadSectorFile = file("templates/".$_POST['template']."/sector");

			for ($s=0;$s<count($ReadSectorFile);$s++) $DataSector .= $ReadSectorFile[$s];

			preg_match_all("!\[section::(.*?)\](.*?)\[/section::(.*?)\]!si",$DataSector,$match);


			for ($g=0;$g<count($match[0]);$g++)
			{
				preg_match("!\[section::(.*?)\]!si",$match[0][$g],$SectionId);
				preg_match("!\[section::(.*?)\](.*?)\[/section::(.*?)\]!si",$match[0][$g],$SectorData);
				$SectionIdThis = $SectionId[1];
				$SectorDataThis = $SectorData[2];
				list($NameSector,$Data) = explode("::",$SectorDataThis,2);
				$TTT .= CMS::SectionAdmin($TemplatesSection,7,"%ID%,%NAMES%,%NAME%,%T%","".$SectionIdThis."<><>".$NameSector."<><>".$Data."<><>".$g."");
			}

			$Sectors = CMS::SectionAdmin($TemplatesSection,6,"%TTT%,%NAME%",$TTT."<><>".$_POST['template']);
		}

		# END #

		echo CMS::SectionAdmin($TemplatesSection,5,"%IMG%,%NAME%,%TABLE%",$Img."<><>".$_POST['template']."<><>".$Sectors);

	}

}
else
{
	if($_GET['config'] == 'css') include('css.php');
	if($_GET['config'] == 'addition') include('addition.php');
	if($_GET['config'] == 'edittheme') include('edittheme.php');
}

?>