<?php

if(!isset($_GET['config']))
{
	$TemplatesSection = CMS::SectionFile('block');
    $list = '';
	$SelectListBlock = Simple_DbApi::select_db("block","*","install","yes","","","","");
	if(!empty($SelectListBlock))
	{
		foreach ($SelectListBlock as $i => $nb)
		{
			if(file_exists(DATA_PATH."block/".$nb['name_block']."/admin.php"))
			{
				if(file_exists("block/".$nb['name_block']."/title.php")) include("block/".$nb['name_block']."/title.php");
				if(empty($BlockName)) $BlockName = $nb['name_block'];
				if(file_exists('templates/admin/ico/block/'.$nb['name_block'].'.png')) $image = 'block/'.$nb['name_block'].'.png';
				else $image = 'admin/blockdevice.png';
				$list .= CMS::SectionAdmin($TemplatesSection,2,'{url},{image},{name}','block-'.$nb['name_block'].'.pl<><>'.$image.'<><>'.$BlockName);
			}
		}
	}

	echo CMS::SectionAdmin($TemplatesSection,1,"{list}",$list);

}
else
{
	if(!file_exists(DATA_PATH."block/".$_GET['config']."/admin.php")) header("Location: block.pl");
	if(file_exists(DATA_PATH."block/".$_GET['config']."/config.php")) include(DATA_PATH."block/".$_GET['config']."/config.php");
	if(file_exists("block/".$_GET['config']."/languages/".LANDSITE.".php")) include("block/".$_GET['config']."/languages/".LANDSITE.".php");
	$GLOBALS['FileSectionBlock'] = CMS::SectionBlockFile();
	$TitleModule = CMS::TitleComponent($_GET['config'],'block');
	if(!isset($_GET['ajax1'])) include(DATA_PATH."block/".$_GET['config']."/admin.php");
	else 
	{
		if(file_exists(DATA_PATH."/block/".$_GET['config']."/ajax.php")) include(DATA_PATH."block/".$_GET['config']."/ajax.php");
	}
}
?>