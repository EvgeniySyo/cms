<?php

include("includes/js.php");
$TemplatesSection = CMS::SectionFile('SecurityError');

if(isset($_POST['del']) && is_numeric($_POST['id']))
{
	Simple_DbApi::delete_db("admin_acsess","id",$_POST['id']);
	echo CMS::SectionAdmin($TemplatesSection,7,"","");
}

echo CMS::SectionAdmin($TemplatesSection,1,"","");

$SelectFalseEnter = Simple_DbApi::select_db("admin_acsess","*","","","","","","");
if(!empty($SelectFalseEnter))
{
	echo CMS::SectionAdmin($TemplatesSection,4,"","");
	foreach ($SelectFalseEnter as $i => $nb)
	{
		echo CMS::SectionAdmin($TemplatesSection,6,"%IP%,%DATA%,%ID%","".$nb['ip']."<><>".$nb['date']."<><>".$nb['id']."");
	}
	echo CMS::SectionAdmin($TemplatesSection,5,"","");
}
else echo CMS::SectionAdmin($TemplatesSection,3,"","");

echo CMS::SectionAdmin($TemplatesSection,2,"","");

?>