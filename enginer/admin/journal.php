<?php
$TemplatesSection = CMS::SectionFile('journal');

CMS::CoreComponent('page');
$maxHistory = 20;
$MaxNumbers = 20;

$ThisPage = is_numeric($_GET['config']) ? $_GET['config']:0;
$limit = abs($ThisPage*$maxHistory);

$DateList = array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
echo CMS::SectionAdmin($TemplatesSection,1,"","");

$count = Simple_DbApi::CountTable('history');

if($count > 0)
{
	$SelectHistory = Simple_DbApi::select_db("history","*","","","id",2,$limit,$maxHistory);
	if (!empty($SelectHistory)) {
        foreach ($SelectHistory as $i => $nh)
        {
            list($date,$time) = explode(" ", $nh['date'], 3);
            list($year, $month, $day) = explode("-", $date, 3);
            $DateFormat = $day." ".$DateList[($month - 1)]." ".$year;
            $DateFormat1 = $day.".".$month.".".$year;
            $IpAdress = !empty($nh['ip']) ? $nh['ip']:'<b>неопределен</b>';
            $event = str_replace("\\\"","\"",$nh['text']);
            $list .= CMS::SectionAdmin($TemplatesSection,5,"{text},{date},{ip}",$event.'<><>'.$DateFormat.' '.$time.'<><>'.$IpAdress);
        }
    }

	$PageJournal = '';
	if($count > $maxHistory)
	{
		$maxPage = intval(($count - 1) / $maxHistory) + 1;
		$pages = Simple_Page::NumberList(
		$maxPage,
		$ThisPage,
		CMS::SectionAdmin($TemplatesSection,34, '{url}', 'journal-{n}.pl'),
		CMS::SectionAdmin($TemplatesSection,35, '{url}', 'journal-{n}.pl'),
		CMS::SectionAdmin($TemplatesSection,37,'',''),
		CMS::SectionAdmin($TemplatesSection,36, '{url}', 'journal-{n}.pl'),
		$MaxNumbers
		);
		$PageJournal = CMS::SectionAdmin($TemplatesSection,33,"{page}",$pages);
	}

	echo CMS::SectionAdmin($TemplatesSection,4,"{list},{page}",$list.'<><>'.$PageJournal);
}
else echo CMS::SectionAdmin($TemplatesSection,3,"","");




echo CMS::SectionAdmin($TemplatesSection,2,"","");

?>