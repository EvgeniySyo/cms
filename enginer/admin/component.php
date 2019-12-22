<?php

$TemplatesSection = CMS::SectionFile('component');

$SelectComp = Simple_DbApi::select_db('modules','*','install','yes','','','','');
$componet = '';
if(!empty($SelectComp))
{
    foreach ($SelectComp as $nm) {
        if(file_exists('modules/'.$nm['name'].'/index.php') && file_exists(DATA_PATH.'modules/'.$nm['name'].'/admin.php'))
        {
            if(file_exists('modules/'.$nm['name'].'/title.php')) include('modules/'.$nm['name'].'/title.php');
            if(file_exists('modules/'.$nm['name'].'/info')) include('modules/'.$nm['name'].'/info');
            if(!empty($module_title)) $nameModule = $module_title;
            else $nameModule = $nm['name'];

            if(file_exists('templates/admin/ico/module/'.$nm['name'].'.png')) $image = 'module/'.$nm['name'].'.png';
            else $image = 'admin/blockdevice.png';

            $componet .= CMS::SectionAdmin($TemplatesSection,2,"{name},{image},{url},{title}",$nameModule."<><>".$image."<><>main-modules-setting-mod-".$nm['name'].".pl<><>".$info);
        }
        unset($nameModule);
        unset($image);
    }
}


echo CMS::SectionAdmin($TemplatesSection,1,'{list}',$componet);
?>