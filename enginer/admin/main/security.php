<?php
if(!isset($_GET['admin_page'])) $AdminPage = "start";
if($AdminPage == "start")
{
	include("includes/js.php");
	$TemplatesSection = CMS::SectionFile('Security');
	
	echo CMS::SectionAdmin($TemplatesSection,1,"","");
	echo CMS::SectionAdmin($TemplatesSection,3,"","");
	echo CMS::SectionAdmin($TemplatesSection,2,"","");
}
else 
{
	$AdminPage = $_GET['admin_page'];
	if(!file_exists(DATA_PATH."/admin/main/security/".$AdminPage.".php")) include(DATA_PATH."/admin/main/security.php");
	else include(DATA_PATH."/admin/main/security/".$AdminPage.".php");
}
?>