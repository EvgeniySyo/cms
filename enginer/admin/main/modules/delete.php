<?php

$TemplatesSection = CMS::SectionFile('remove');

function DeleteComponentFiles($dir) {
	if ($objs = glob($dir."/*")) {
		foreach($objs as $obj) {
			is_dir($obj) ? DeleteComponentFiles($obj) : unlink($obj);
		}
	}
	@rmdir($dir);
}

if(isset($_POST['name']) && isset($_POST['type']))
{
	if($_POST['type'] == 'module')
	{
		if(file_exists("modules/".$_POST['name']."/index.php"))
		{
			Simple_DbApi::delete_db('modules','name',$_POST['name']);
			//Simple_DbApi::query_db("DROP TABLE ".$_POST['name']."");
			//DeleteComponentFiles('modules/'.$_POST['name']);
			//DeleteComponentFiles(DATA_PATH.'modules/'.$_POST['name']);
			//DeleteComponentFiles('images/'.$_POST['name']);
			/*
			$dirTemplates = scandir('templates');
			for ($j=2;$j<count($dirTemplates);$j++)
			{
			if($dirTemplates[$j] != 'admin')
			{
			DeleteComponentFiles('templates/'.$dirTemplates[$j].'/modules/'.$_POST['name']);
			}
			}
			*/
			if(file_exists("modules/".$_POST['name']."/title.php")) include("modules/".$_POST['name']."/title.php");
			if(empty($module_title)) $module_title = $_POST['name'];
			
			CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> удалил модуль <b>'.$module_title.'</b>. Раздел <a target="_blank" href="main-modules-delete.pl"><b>Удаление модулей и блоков</b></a>');
			unset($module_title);
			echo CMS::AlertWindow('Успешно','Модуль удален',1,0);
		}
	}

	if($_POST['type'] == 'block')
	{
		if(file_exists("block/".$_POST['name']."/index.php"))
		{
			Simple_DbApi::delete_db('block','name_block',$_POST['name']);
			/*
			DeleteComponentFiles('block/'.$_POST['name']);
			DeleteComponentFiles(DATA_PATH.'block/'.$_POST['name']);
			$dirTemplates = scandir('templates');
			for ($j=2;$j<count($dirTemplates);$j++)
			{
			if($dirTemplates[$j] != 'admin')
			{
			DeleteComponentFiles('templates/'.$dirTemplates[$j].'/block/'.$_POST['name']);
			}
			}
			*/
			if(file_exists("block/".$_POST['name']."/title.php")) include("block/".$_POST['name']."/title.php");
			if(empty($BlockName)) $BlockName = $_POST['name'];
			CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> удалил блок <b>'.$BlockName.'</b>. Раздел <a target="_blank" href="main-modules-delete.pl"><b>Удаление модулей и блоков</b></a>');	
			echo CMS::AlertWindow('Успешно','Блок удален',1,0);
			//Simple_DbApi::delete_db($_POST['name']);
			//Simple_DbApi::query_db("DROP TABLE ".$_POST['name']."");
		}
	}

}

echo CMS::SectionAdmin($TemplatesSection,1,"","");

// MODULES
$SelectListModule = Simple_DbApi::select_db("modules","*","install","yes","","","","");
if(!empty($SelectListModule))
{
	foreach ($SelectListModule as $i => $nm)
	{
		$module_title = '';
		if(file_exists("modules/".$nm['name']."/title.php")) include("modules/".$nm['name']."/title.php");
		if(empty($module_title)) $module_title = "no name";

		$image = file_exists('templates/admin/ico/module/'.$nm['name'].'.png') ? '../templates/admin/ico/module/'.$nm['name'].'.png':'../templates/admin/ico/admin/blockdevice.png';
		$window = CMS::AlertWindow('Внимание!',CMS::SectionAdmin($TemplatesSection,7,'{name}',$nm['name']),2,'module-'.$nm['name']);
		$list .= CMS::SectionAdmin($TemplatesSection,5,'{name},{img},{window},{id}',$module_title.'<><>'.$image.'<><>'.$window.'<><>module-'.$nm['name']);
	}
	$text = CMS::SectionAdmin($TemplatesSection,4,'{list},{img}',$list.'<><>'.$image);
}
else $text = CMS::SectionAdmin($TemplatesSection,3,"","");
echo CMS::SectionAdmin($TemplatesSection,6,"{text}",$text);

// BLOCKS
unset($list);
$SelectListBlock = Simple_DbApi::select_db("block","*","install","yes","","","","");
if(!empty($SelectListBlock) > 0)
{
	foreach ($SelectListBlock as $i => $nb)
	{
		if(file_exists("block/".$nb['name_block']."/index.php"))
		{
			if(file_exists("block/".$nb['name_block']."/title.php")) include("block/".$nb['name_block']."/title.php");
			if(empty($BlockName)) $BlockName = "no name";

			$image = file_exists('templates/admin/ico/block/'.$nb['name_block'].'.png') ? '../templates/admin/ico/block/'.$nb['name_block'].'.png' : '../templates/admin/ico/admin/blockdevice.png';
			$window = CMS::AlertWindow('Внимание!',CMS::SectionAdmin($TemplatesSection,12,'{name}',$nb['name_block']),2,'block-'.$nb['name_block']);
			$list .= CMS::SectionAdmin($TemplatesSection,13,'{name},{img},{window},{id}',$BlockName.'<><>'.$image.'<><>'.$window.'<><>block-'.$nb['name_block']);
		}
	}
	$text = CMS::SectionAdmin($TemplatesSection,10,'{list}',$list);
}
else $text = CMS::SectionAdmin($TemplatesSection,9,"","");
echo CMS::SectionAdmin($TemplatesSection,8,"{text}",$text);


echo CMS::SectionAdmin($TemplatesSection,2,"","");

?>