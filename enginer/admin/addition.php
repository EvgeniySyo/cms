<?php

if(isset($_GET['admin_page']))
{
	if(file_exists("templates/".$_GET['admin_page']."/index.php"))
	{
		$TemplatesSection = CMS::SectionFile('addition');
		$Templates = $_GET['admin_page'];

		if(file_exists("templates/".$Templates."/img.png")) $Img = "../templates/".$Templates."/img.png";
		else $Img = "../images/notamplates.png";

		echo CMS::SectionAdmin($TemplatesSection,1,"{name},{img}",$Templates."<><>".$Img);

		if($_GET['name_tamlates'] == 1) echo CMS::SectionAdmin($TemplatesSection,4,'{template},{component},{name}',$Templates.'<><>1<><>модули');
		else echo CMS::SectionAdmin($TemplatesSection,3,'{template},{component},{name}',$Templates.'<><>1<><>модули');
		if($_GET['name_tamlates'] == 2) echo CMS::SectionAdmin($TemplatesSection,4,'{template},{component},{name}',$Templates.'<><>2<><>блоки');
		else echo CMS::SectionAdmin($TemplatesSection,3,'{template},{component},{name}',$Templates.'<><>2<><>блоки');

		### MODULE LIST ###
		if($_GET['name_tamlates'] == 1)
		{
			echo CMS::SectionAdmin($TemplatesSection,5,'','');

			$Select = Simple_DbApi::select_db('modules','*','install','yes','','','','');
			if(!empty($Select))
			{
				foreach ($Select as $i => $nm)
				{
					if(file_exists('modules/'.$nm['name'].'/title.php'))
					{
						include('modules/'.$nm['name'].'/title.php');
						if(strlen($module_title) > 0) $nameModule = $module_title;
						else $nameModule = $nm['name'];
					}
					else $nameModule = $nm['name'];

					if(file_exists('templates/'.$Templates.'/modules/'.$nm['name'].'/section'))
					{
						if($_GET['page'] == trim($nm['name'])) echo CMS::SectionAdmin($TemplatesSection,9,'{template},{component},{name},{namemodule}',$Templates.'<><>1<><>'.$nameModule.'<><>'.$nm['name']);
						else echo CMS::SectionAdmin($TemplatesSection,8,'{template},{component},{name},{namemodule}',$Templates.'<><>1<><>'.$nameModule.'<><>'.$nm['name']);
					}

				}

				### UPDATE ###
				if(isset($_GET['page']))
				{

					if(file_exists('templates/'.$Templates.'/modules/'.$_GET['page'].'/section'))
					{
						//chmod('templates/'.$Templates.'/modules/'.$_GET['page'].'/section', 666);
						if(isset($_POST['saveModsection']))
						{
							for ($m=0;$m<count($_POST['element']);$m++)
							{
								$clearElement = strip_tags($_POST['element'][$m]);
								$sectionElement = $_POST['css'][$m];
								$sectionElement = str_replace("\\","",$sectionElement);
								if(strlen($clearElement) > 0 && strlen($sectionElement) > 0) $SaveFormatCss .= "[section::".$clearElement."]\n".$sectionElement."[/section::".$clearElement."]\n";
								unset($clearElement);
								unset($sectionElement);
							}

							$SaveFormatCss = trim($SaveFormatCss);
							if(!empty($SaveFormatCss))
							{
								$fp = fopen('templates/'.$Templates.'/modules/'.$_GET['page'].'/section','w');
								flock($fp,LOCK_EX);
								fwrite($fp,$SaveFormatCss);
								flock($fp,LOCK_UN);
								fclose($fp);
								if(BUFFERSITE == "on") CMS::ClearCacheDirectory();
								echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
								
								include('modules/'.$_GET['page'].'/title.php');
								if(strlen($module_title) > 0) $nameModuleSelect = $module_title;
								else $nameModuleSelect = $_GET['page'];
								
								CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> внес изменения в section. Модуль: <b>'.$nameModuleSelect.'</b>. Шаблон <b>'.$Templates.'</b>. Раздел <a target="_blank" href="templates-addition-'.$Templates.'-mod-1-'.$_GET['page'].'.pl#down1"><b>Компоненты и блоки</b></a>');
							}

						}

						$SelectMod = Simple_DbApi::select_db('modules','*','name',$_GET['page'],'','','','');
						if(!empty($SelectMod))
						{
							$nn = current($SelectMod);
							if(file_exists('modules/'.$nn['name'].'/title.php'))
							{
								include('modules/'.$nn['name'].'/title.php');
								if(strlen($module_title) > 0) $nameModuleSelect = $module_title;
								else $nameModuleSelect = $nn['name'];
							}
							else $nameModuleSelect = $nn['name'];

							echo CMS::SectionAdmin($TemplatesSection,10,'{name}',$nameModuleSelect);

							$ReadSection = file_get_contents('templates/'.$Templates.'/modules/'.$_GET['page'].'/section');

							preg_match_all("!\[section::(.*?)\](.*?)\[/section::(.*?)\]!s",$ReadSection,$match);


							for ($j=0;$j<count($match[1]);$j++)
							{
								if(strlen($match[2][$j]) > 0) $listSection .= CMS::SectionAdmin($TemplatesSection,12,'{id},{name},{section}',$j.'<><>'.$match[1][$j].'<><>'.htmlspecialchars($match[2][$j]));
							}
							echo CMS::SectionAdmin($TemplatesSection,11,'{list}',$listSection);

							echo CMS::SectionAdmin($TemplatesSection,6,'','');
						}
					}
				}

			}

			echo CMS::SectionAdmin($TemplatesSection,6,'','');
		}
		### BLOCK LIST ###
		if($_GET['name_tamlates'] == 2)
		{
			echo CMS::SectionAdmin($TemplatesSection,7,'','');
			$Select = Simple_DbApi::select_db('block','*','install','yes','','','','');
			if(!empty($Select))
			{
				foreach ($Select as $i => $nm)
				{
					if(file_exists('block/'.$nm['name_block'].'/title.php'))
					{
						include('block/'.$nm['name_block'].'/title.php');
						if(strlen($BlockName) > 0) $nameBlock = $BlockName;
						else $nameBlock = $nm['name_block'];
					}
					else $nameBlock = $nm['name_block'];

					if(file_exists('templates/'.$Templates.'/block/'.$nm['name_block'].'/section'))
					{
						if($_GET['page'] == trim($nm['name_block'])) echo CMS::SectionAdmin($TemplatesSection,9,'{template},{component},{name},{namemodule}',$Templates.'<><>2<><>'.$nameBlock.'<><>'.$nm['name_block']);
						else echo CMS::SectionAdmin($TemplatesSection,8,'{template},{component},{name},{namemodule}',$Templates.'<><>2<><>'.$nameBlock.'<><>'.$nm['name_block']);
					}

				}

				### UPDATE ###
				if(isset($_GET['page']))
				{
					if(file_exists('templates/'.$Templates.'/block/'.$_GET['page'].'/section'))
					{
						//chmod('templates/'.$Templates.'/block/'.$_GET['page'].'/section', 666);
						if(isset($_POST['saveModsection']))
						{
							for ($m=0;$m<count($_POST['element']);$m++)
							{
								$clearElement = strip_tags($_POST['element'][$m]);
								$sectionElement = $_POST['css'][$m];
								$sectionElement = str_replace("\\","",$sectionElement);
								if(strlen($clearElement) > 0 && strlen($sectionElement) > 0) $SaveFormatCss .= "[section::".$clearElement."]\n".$sectionElement."[/section::".$clearElement."]\n";
								unset($clearElement);
								unset($sectionElement);
							}

							$SaveFormatCss = trim($SaveFormatCss);
							if(!empty($SaveFormatCss))
							{
								$fp = fopen('templates/'.$Templates.'/block/'.$_GET['page'].'/section','w');
								flock($fp,LOCK_EX);
								fwrite($fp,$SaveFormatCss);
								flock($fp,LOCK_UN);
								fclose($fp);
								if(BUFFERSITE == "on") CMS::ClearCacheDirectory();
								
								include('block/'.$_GET['page'].'/title.php');
								if(strlen($BlockName) > 0) $nameModuleSelect = $BlockName;
								else $nameModuleSelect = $_GET['page'];
								
								CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> внес изменения в <b>section</b>. Блок: <b>'.$nameModuleSelect.'</b>. Шаблон <b>'.$Templates.'</b>. Раздел <a target="_blank" href="templates-addition-'.$Templates.'-mod-2-'.$_GET['page'].'.pl#down1"><b>Компоненты и блоки</b></a>');
								
								echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
							}

						}

						$SelectMod = Simple_DbApi::select_db('block','*','name_block',$_GET['page'],'','','','');
						if(!empty($SelectMod))
						{
							$nn = current($SelectMod);
							if(file_exists('block/'.$nn['name_block'].'/title.php'))
							{
								include('block/'.$nn['name_block'].'/title.php');
								if(strlen($BlockName) > 0) $nameModuleSelect = $BlockName;
								else $nameModuleSelect = $nn['name_block'];
								
							}
							else $nameModuleSelect = $nn['name_block'];

							echo CMS::SectionAdmin($TemplatesSection,14,'{name}',$nameModuleSelect);

							$ReadSection = file_get_contents('templates/'.$Templates.'/block/'.$_GET['page'].'/section');

							preg_match_all("!\[section::(.*?)\](.*?)\[/section::(.*?)\]!s",$ReadSection,$match);


							for ($j=0;$j<count($match[1]);$j++)
							{
								if(strlen($match[2][$j]) > 0) $listSection .= CMS::SectionAdmin($TemplatesSection,12,'{id},{name},{section}',$j.'<><>'.$match[1][$j].'<><>'.htmlspecialchars($match[2][$j]));
							}
							echo CMS::SectionAdmin($TemplatesSection,11,'{list}',$listSection);

							echo CMS::SectionAdmin($TemplatesSection,6,'','');
						}

					}
				}

			}

			echo CMS::SectionAdmin($TemplatesSection,6,'','');
		}

	}

	echo CMS::SectionAdmin($TemplatesSection,2,'','');

}

?>