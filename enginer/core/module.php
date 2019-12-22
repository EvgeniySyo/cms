<?php

class Simple_Module
{
	public static function TestInstall($module)
	{
		$Select = Simple_DbApi::select_db('modules','name','name,install',$module.'<><>yes','','','','');
		if(!empty($Select)) $find = true;
		else $find = false;
		return $find;
	}
}

?>