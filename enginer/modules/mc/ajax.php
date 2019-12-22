<?php

if(is_numeric($_GET['id']))
{
	$select = Simple_DbApi::select_db($_GET['name_tamlates'],'*','id',$_GET['id'],'','','','');
	if(count($select) == 1)
	{
		$randTipe = rand(11111,99999);
		$nc = array_shift($select);
		if($nc['status'] == 0)
		{
			Simple_DbApi::update_db($_GET['name_tamlates'],'status',1,'id',$_GET['id']);
			echo CMS::AdminModuleSection(2,'{id},{rand}',$nc['id'].'<><>'.$randTipe);
		}
		else 
		{
			Simple_DbApi::update_db($_GET['name_tamlates'],'status',0,'id',$_GET['id']);
			echo CMS::AdminModuleSection(1,'{id},{rand}',$nc['id'].'<><>'.$randTipe);
		}
	}
}

?>
