<?php

$TemplatesSection = CMS::SectionFile('main');

$SelectComp = Simple_DbApi::select_db('modules', '*', 'install', 'yes', '', '', '', '');
$componet = $block = '';
$find = 0;
if (!empty($SelectComp)) {
    foreach ($SelectComp as $i => $nm)
	{
        if (file_exists('modules/' . $nm['name'] . '/index.php') && file_exists(DATA_PATH . 'modules/' . $nm['name'] . '/admin.php')) {
            if (file_exists('modules/' . $nm['name'] . '/title.php')) include('modules/' . $nm['name'] . '/title.php');
            if (file_exists('modules/' . $nm['name'] . '/info')) include('modules/' . $nm['name'] . '/info');
            if (!empty($module_title)) $nameModule = $module_title;
            else $nameModule = $nm['name'];
            $find = 1;
            if (file_exists('templates/admin/ico/module/' . $nm['name'] . '.png')) $image = 'module/' . $nm['name'] . '.png';
            else $image = 'admin/blockdevice.png';

            $componet .= CMS::SectionAdmin($TemplatesSection, 4, "{name},{image},{url},{title}", $nameModule . "<><>" . $image . "<><>main-modules-setting-mod-" . $nm['name'] . ".pl<><>" . $info);
        }
        unset($nameModule);
        unset($image);
    }
}

$componetIn = '';
if ($find != 0) $componetIn = CMS::SectionAdmin($TemplatesSection, 2, '{list}', $componet);

$SelectBlock = Simple_DbApi::select_db('block', '*', 'install', 'yes', '', '', '', '');
if (!empty($SelectBlock)) {
    $find = 0;
    foreach ($SelectBlock as $j => $nb) {
        if (file_exists('block/' . $nb['name_block'] . '/index.php') && file_exists(DATA_PATH . 'block/' . $nb['name_block'] . '/admin.php')) {
            if (file_exists('block/' . $nb['name_block'] . '/title.php')) include('block/' . $nb['name_block'] . '/title.php');
            if (!empty($BlockName)) $name = $BlockName;
            else $name = $nb['name_block'];
            $find = 1;
            if (file_exists('templates/admin/ico/block/' . $nb['name_block'] . '.png')) $image = 'block/' . $nb['name_block'] . '.png';
            else $image = 'admin/blockdevice.png';

            $block .= CMS::SectionAdmin($TemplatesSection, 4, "{name},{image},{url},{title}", $name . "<><>" . $image . "<><>block-" . $nb['name_block'] . ".pl<><>" . $name);
        }
    }
}

$blockIn = '';
if ($find != 0) $blockIn = CMS::SectionAdmin($TemplatesSection, 3, '{list}', $block);

echo CMS::SectionAdmin($TemplatesSection, 1, "{component},{block}", $componetIn . "<><>" . $blockIn);


?>