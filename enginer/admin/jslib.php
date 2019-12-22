<?php

$TemplatesSection = CMS::SectionFile('jslib');

$LibFolder = scandir('js_lib');

if(isset($_POST['save']))
{
	for ($h=2;$h<count($LibFolder);$h++) if($_POST['q'][$h] == 1) $WriteLib .= $LibFolder[$h]."\n";
	
	$fp = fopen(DATA_PATH.'jslib','w');
	flock($fp,LOCK_EX);
	fwrite($fp,$WriteLib);
	flock($fp,LOCK_UN);
	fclose($fp);
	
	echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
	CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> внес изменения. Раздел <a target="_blank" href="jslib.pl"><b>Js библиотеки</b></a>');
	
}

echo CMS::SectionAdmin($TemplatesSection,1,'','');

$LibFile = file(DATA_PATH.'jslib');
$countLib = count($LibFolder);

if($countLib>0)
{
	$countTrueLib = 0;
	for ($i=2;$i<$countLib;$i++)
	{
		if(file_exists('js_lib/'.$LibFolder[$i].'/lib.php'))
		{
			$ch1 = "checked=\"checked\"";
			$ch2 = "";
			for ($j=0;$j<count($LibFile);$j++)
			{
				if(!empty($LibFile[$j]))
				{
					if(trim($LibFile[$j]) == trim($LibFolder[$i]))
					{
						$ch1 = "";
						$ch2 = "checked=\"checked\"";
						break;
					}
				}
			}
			$countTrueLib = 1;
			$ListLib .= CMS::SectionAdmin($TemplatesSection,5,'{name},{id},{ch1},{ch2}',$LibFolder[$i].'<><>'.$i.'<><>'.$ch1.'<><>'.$ch2);
		}
	}


	if($countTrueLib>0)
	{
		echo CMS::SectionAdmin($TemplatesSection,3,'','');
		echo CMS::SectionAdmin($TemplatesSection,4,'{list}',$ListLib);
	}
}



echo CMS::SectionAdmin($TemplatesSection,2,'','');

?>