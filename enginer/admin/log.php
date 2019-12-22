<?php

$TemplatesSection = CMS::SectionFile('log');

echo CMS::SectionAdmin($TemplatesSection,1,'','');

$SelectLastLog = Simple_DbApi::select_db('log','*','','','id',2,0,100);
if(!empty($SelectLastLog))
{
	foreach ($SelectLastLog as $i => $nl)
	{
		list($year,$month,$day) = explode('-',$nl['date'],3);
		$list .= CMS::SectionAdmin($TemplatesSection,4,'{ip},{date},{time}',$nl['ip'].'<><>'.$day.'.'.$month.'.'.$year.'<><>'.$nl['time']);
	}
	echo CMS::SectionAdmin($TemplatesSection,3,'{list}',$list);
}

echo CMS::SectionAdmin($TemplatesSection,2,'','');

?>