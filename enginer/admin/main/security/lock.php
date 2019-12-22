<?php

//include("includes/js.php");
$TemplatesSection = CMS::SectionFile('SecurityLock');

if(isset($_POST['add']))
{
	$TextAlert = strip_tags($_POST['alert']);
	if(empty($TextAlert)) echo CMS::SectionAdmin($TemplatesSection,10,"","");
	else
	{
		$IpBanNew = $_POST['ip'];
		list($num1,$num2,$num3,$num4) = explode(".",$IpBanNew,4);
		if(!is_numeric($num1) || !is_numeric($num2) || !is_numeric($num3) || !is_numeric($num4))  echo CMS::SectionAdmin($TemplatesSection,11,"","");
		else
		{
			$SelectTestIp = Simple_DbApi::select_db("ban_ip","ip","ip",$IpBanNew,"","","","");
			if(count($SelectTestIp) > 0) echo CMS::SectionAdmin($TemplatesSection,12,"","");
			else
			{
				Simple_DbApi::insert_db("ban_ip","ip,alert,date","".$IpBanNew."<><>".$TextAlert."<><>".date("j.n.Y-H:i:s")."");
				echo CMS::SectionAdmin($TemplatesSection,13,"","");
			}
		}
	}
}

if(isset($_POST['delete']) && is_numeric($_POST['id']))
{
	Simple_DbApi::delete_db("ban_ip","id",$_POST['id']);
	echo CMS::SectionAdmin($TemplatesSection,14,"","");
}

if(!isset($_POST['edit']) || !is_numeric($_POST['id']))
{
	echo CMS::SectionAdmin($TemplatesSection,1,"","");
	$SelectBanList = Simple_DbApi::select_db("ban_ip","*","","","","","","");
	if(!empty($SelectBanList))
	{
		echo CMS::SectionAdmin($TemplatesSection,3,"","");

		foreach ($SelectBanList as $i => $nb)
		{
			echo CMS::SectionAdmin($TemplatesSection,9,"%IP%,%DATA%,%TEXT%,%ID%","".$nb['ip']."<><>".$nb['date']."<><>".$nb['alert']."<><>".$nb['id']."");
		}
		echo CMS::SectionAdmin($TemplatesSection,4,"","");
	}
	else CMS::SectionAdmin($TemplatesSection,7,"","");
	echo CMS::SectionAdmin($TemplatesSection,2,"","");

	echo CMS::SectionAdmin($TemplatesSection,5,"","");
	echo CMS::SectionAdmin($TemplatesSection,8,"%IP%",strip_tags($_POST['ips']));
	echo CMS::SectionAdmin($TemplatesSection,6,"","");
}
else
{
	echo CMS::SectionAdmin($TemplatesSection,15,"","");

	if(isset($_POST['go']))
	{
		$TextAlert = strip_tags($_POST['alert']);
		if(empty($TextAlert)) echo CMS::SectionAdmin($TemplatesSection,10,"","");
		else
		{
			Simple_DbApi::update_db("ban_ip","alert",$TextAlert,"id",$_POST['id']);
			echo CMS::SectionAdmin($TemplatesSection,19,"","");
		}
	}

	echo CMS::SectionAdmin($TemplatesSection,18,"","");

	$SelectBanEdit = Simple_DbApi::select_db("ban_ip","*","id",$_POST['id'],"","","","");
	if(!empty($SelectBanEdit))
	{
		$ne = current($SelectBanEdit);
		echo CMS::SectionAdmin($TemplatesSection,17,"%IP%,%ALERT%,%ID%","".$ne['ip']."<><>".$ne['alert']."<><>".$ne['id']."");
	}

	echo CMS::SectionAdmin($TemplatesSection,16,"","");
}
?>