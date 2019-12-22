<?php

$TemplatesSection = CMS::SectionFile('listmodule');

if(isset($_POST['save']))
{
	$ListMod = $_POST['check'];
	if($_POST['chechAll'] != "on") sort($ListMod);
	for ($j=0;$j<count($ListMod);$j++) $List .= $ListMod[$j]." ";
	$List = trim($List);
	
	if(empty($List) || isset($_POST['chechAll'])) Simple_DbApi::update_db("block","on_module","","name_block",$_GET["name_tamlates"]);
	else  Simple_DbApi::update_db("block","on_module",$List,"name_block",$_GET["name_tamlates"]);
	echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
	CMS::DesctroyCacheBlock();
}

$SelectListModuleInBlock = Simple_DbApi::select_db("block","*","name_block",$_GET["name_tamlates"],"","",0,1);
$nb = current($SelectListModuleInBlock);
$ListModule = $nb['on_module'];
$ListArrayModule = explode(" ",$ListModule);

if(file_exists("block/".$nb['name_block']."/title.php")) include("block/".$nb['name_block']."/title.php");
if(empty($BlockName)) $BlockName = "no name";
echo CMS::SectionAdmin($TemplatesSection,1,"{name}",$BlockName);

echo CMS::SectionAdmin($TemplatesSection,3,"","");

$SelectListModule = Simple_DbApi::select_db("modules","*","","","name",1,"","");
if (!empty($SelectListModule)) {
    foreach ($SelectListModule as $i => $nm)
    {
        $FindInModule = false;
        if(file_exists("modules/".$nm['name']."/title.php")) include("modules/".$nm['name']."/title.php");
        if(empty($module_title)) $module_title = "no name";

        for ($n=0;$n<count($ListArrayModule);$n++) if($nm['name'] == $ListArrayModule[$n]) $FindInModule = true;
        if($FindInModule == true) $CheckModule = "checked=\"checked\"";
        else $CheckModule = "";

        echo CMS::SectionAdmin($TemplatesSection,5,"%N%,%ID%,%NAME%,%CH%","".$i."<><>".$nm['name']."<><>".$module_title."<><>".$CheckModule."");
    }
}

if(empty($nb['on_module'])) $CheckAll = "checked=\"checked\"";
else $CheckAll = "";
echo CMS::SectionAdmin($TemplatesSection,4,"%CH%",$CheckAll);


echo CMS::SectionAdmin($TemplatesSection,2,"","");

?>