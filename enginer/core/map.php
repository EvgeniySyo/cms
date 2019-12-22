<?php

class Simple_Module_Map
{
	public function list_cat($id,$num)
	{
		$select = Simple_DbApi::select_db($_GET['page'],"*","id",$id,"","","","");
		$nn = current($select);
		$text = $nn['name']."<><>".$nn['id']."::";
		$ggg = $nn['parent'];
		$num = $num+1;
		if(!empty($ggg)) $text .= Simple_Module_Map::list_cat($ggg,$num);
		return $text;
	}
	
	public function work_m($list)
	{
		$text = array();
		$go = explode("::",$list);
		for ($i=0;$i<count($go);$i++) if(!empty($go[$i])) $text[$i] = $go[$i];
		return $text;
	}
}

?>