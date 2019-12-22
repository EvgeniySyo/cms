<?php

$TemplatesSection = CMS::SectionFile('other');

if(isset($_POST['id']) && is_numeric($_POST['id']))
{
	$dir = scandir(DATA_PATH . 'templates');
	for($t = 2; $t < count($dir); $t++)
	{
		if($_POST['id'] == $t)
		{
			if(file_exists(DATA_PATH . 'templates/' . $dir[$t]))
			{
				$DeleteTheme = file_get_contents(DATA_PATH . 'templates/' . $dir[$t]);
				unlink(DATA_PATH . 'templates/' . $dir[$t]);
			}
			break;
		}
	}
	echo CMS::AlertWindow('Успешно', 'Успешно удалено', 1, 0);

	CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> удалил индивидуальный шаблон <b>'.$DeleteTheme.'</b>. Раздел <a target="_blank" href="other.pl"><b>Индивидуальные шаблоны</b></a>');

}

function creatOtherTheme($name, $theme)
{
	$fp = fopen(DATA_PATH . 'templates/' . $name . '.oth', 'w');
	flock($fp, LOCK_EX);
	fwrite($fp, $theme);
	flock($fp, LOCK_UN);
	fclose($fp);
}

if(isset($_POST['addUse']))
{
	$namePage = $_POST['pageTheme'];

	$namePage = str_replace('/', '%', $namePage);

	$moduleSelect = $_POST['moduleTheme'];
	$selectTheme = $_POST['Theme'];
	if(file_exists('templates/' . $selectTheme . '/index.php'))
	{
		if(!empty($namePage))
		{
			creatOtherTheme('page-' . $namePage, $selectTheme);
			CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> добавил индивидуальный шаблон <b>'.$selectTheme.'</b>. Раздел <a target="_blank" href="other.pl"><b>Индивидуальные шаблоны</b></a>');
		}
		else
		{
			if(file_exists('modules/' . $moduleSelect . '/index.php'))
			{
				creatOtherTheme('module-' . $moduleSelect, $selectTheme);
				CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> добавил индивидуальный шаблон <b>'.$selectTheme.'</b>. Раздел <a target="_blank" href="other.pl"><b>Индивидуальные шаблоны</b></a>');
			}
			else echo CMS::AlertWindow('Ошибка', 'Не выбран модуль', 3, 0);
		}
	}
	else echo CMS::AlertWindow('Ошибка', 'Не выбран шаблон', 3, 0);
}

echo CMS::SectionAdmin($TemplatesSection, 1, '', '');

$dir = scandir(DATA_PATH . 'templates');

$findTemplateUse = 0;
$moduleThemeFind = 0;
$pageThemeFind = 0;

for($i = 2; $i < count($dir); $i++)
{
	$fileIn = pathinfo(DATA_PATH . 'templates/' . $dir[$i]);
	if($fileIn['extension'] == 'oth')
	{
		list($Ftype, $Fname) = explode('-', $fileIn['filename'], 2);
		if($Ftype == 'module')
		{
			$moduleThemeFind = 1;
			$findTemplateUse += 1;
			$nameReadTheme = file_get_contents(DATA_PATH . 'templates/' . $dir[$i]);
			$nameReadTheme = trim($nameReadTheme);
			if(file_exists("templates/" . $nameReadTheme . "/img.png"))
			$Img = "../templates/" . $nameReadTheme . "/img.png";
			else
			$Img = "../images/notamplates.png";

			if(file_exists('templates/admin/ico/module/' . $Fname . '.png'))
			$imageM = '../templates/admin/ico/module/' . $Fname . '.png';
			else
			$imageM = '../templates/admin/ico/admin/blockdevice.png';

			if(file_exists('modules/' . $Fname . '/title.php'))
			include ('modules/' . $Fname . '/title.php');

			$wind = CMS::AlertWindow('Внимание!', CMS::SectionAdmin($TemplatesSection, 11, '{id}', $i), 2, $i);

			$moduleT .= CMS::SectionAdmin($TemplatesSection, 9, "{ico},{name},{img-template},{name-t},{id},{wind}", $imageM . "<><>" . $module_title . "<><>" . $Img . "<><>" . $nameReadTheme . '<><>' . $i . '<><>' . $wind);

		}
		if($Ftype == 'page')
		{
			$pageThemeFind = 1;
			$findTemplateUse += 1;
			$namePages = str_replace('%', '/', $Fname);
			$nameReadTheme = file_get_contents(DATA_PATH . 'templates/' . $dir[$i]);
			$nameReadTheme = trim($nameReadTheme);
			if(file_exists("templates/" . $nameReadTheme . "/img.png"))
			$Img = "../templates/" . $nameReadTheme . "/img.png";
			else
			$Img = "../images/notamplates.png";

			$wind = CMS::AlertWindow('Внимание!', CMS::SectionAdmin($TemplatesSection, 11, '{id}', $i), 2, $i);

			$pageT .= CMS::SectionAdmin($TemplatesSection, 10, "{name},{img-template},{name-t},{id},{wind}", $namePages . "<><>" . $Img . "<><>" . $nameReadTheme . '<><>' . $i . '<><>' . $wind);

		}

	}

}

# List module
$SelectComp = Simple_DbApi::select_db('modules', '*', 'install', 'yes', '', '', '', '');
if(!empty($SelectComp))
{
	foreach ($SelectComp as $i => $nm)
	{
		if(file_exists('modules/' . $nm['name'] . '/index.php'))
		{
			if(file_exists('modules/' . $nm['name'] . '/title.php'))
			include ('modules/' . $nm['name'] . '/title.php');
			if(!empty($module_title))
			$nameModule = $module_title;
			else
			$nameModule = $nm['name'];
			$componet .= CMS::SectionAdmin($TemplatesSection, 4, "{name},{id}", $nameModule . "<><>" . $nm['name'] . "");
		}
		unset($nameModule);
	}
}

#list templates
$SelectListTheme = Simple_DbApi::select_db("templates", "*", "", "", "", "", "", "");
if (!empty($SelectListTheme)) {
    foreach ($SelectListTheme as $n => $nm1)
    {
        if($nm1['name'] != "admin")
        {
            if(file_exists('templates/' . $nm1['name'] . '/index.php'))
            {
                $theme .= CMS::SectionAdmin($TemplatesSection, 4, "{name},{id}", $nm1['name'] . "<><>" . $nm1['name'] . "");
            }
        }
    }
}


echo CMS::SectionAdmin($TemplatesSection, 3, '{option},{theme}', $componet . '<><>' . $theme);

if($findTemplateUse > 0)
{
	if($moduleThemeFind == 1)
	{
		echo CMS::SectionAdmin($TemplatesSection, 6, '', '');
		echo CMS::SectionAdmin($TemplatesSection, 7, '{list}', $moduleT);
	}

	if($pageThemeFind == 1)
	{
		echo CMS::SectionAdmin($TemplatesSection, 5, '', '');
		echo CMS::SectionAdmin($TemplatesSection, 8, '{list}', $pageT);
	}
}

echo CMS::SectionAdmin($TemplatesSection, 2, '', '');
?>