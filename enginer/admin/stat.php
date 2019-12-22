<?php

$TemplatesSection = CMS::SectionFile('statistic');

if(isset($_POST['id_date'])) 
{
	Simple_DbApi::delete_db("statistics","date",$_POST['id_date']);
	Simple_DbApi::delete_db("hits","date",$_POST['id_date']);
}

# CALENDAR #

if(!isset($_GET['admin_page']) || $_GET['admin_page'] > 12 || $_GET['admin_page'] < 1) $Month = date('n');
else $Month = $_GET['admin_page'];

$ListMonth = array("Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь");

# TIME #

$td = "<td class=\"NoSelect\">";

if(!isset($_GET['config']) || !is_numeric($_GET['config']) || $_GET['config'] < 1 || $_GET['config'] > 31) $day = date ('j', time ());
else $day = $_GET['config'];

if(!isset($_GET['admin_page']) || !is_numeric($_GET['admin_page']) || $_GET['admin_page'] < 1 || $_GET['admin_page'] > 12) $month = date ('n', time ());
else $month = $_GET['admin_page'];

if(!isset($_GET['name_tamlates']) || !is_numeric($_GET['name_tamlates'])) $year = date ('Y', time ());
else $year = $_GET['name_tamlates'];

if($month == 1) $end_day = 31;
else if($month == 3) $end_day = 30;
else if($month == 2) 
{
	if(date('L') == 1) $end_day = 29;
	else $end_day = 28;
}
else if($month == 4) $end_day = 30;
else if($month == 5) $end_day = 31;
else if($month == 6) $end_day = 30;
else if($month == 7) $end_day = 31;
else if($month == 8) $end_day = 31;
else if($month == 9) $end_day = 30;
else if($month == 10) $end_day = 31;
else if($month == 11) $end_day = 30;
else if($month == 12) $end_day = 31;


$one_day = date ('w', mktime (1,0,0,$month,1,$year))-1;
if ($one_day=='-1'){ $one_day=6; }
$dass = $month. date ('d', time ());


for ( $i = 1; $i <= $end_day+$one_day; $i++)
{
	if ($x==0)$x=7;
	$x--;
	$a = $i- $one_day;
	if ($one_day> $i || $a<1)$fc .= "$td </td>";
	elseif ($one_day == $i) $fc .= "$td $a</td>";
	elseif ($i==6 || $i==13 || $i==20 || $i==27 or $i==34)
	{
		if ($i==$day+$one_day) $fc .= "<td class=\"selectDate\"> <a href=\"stat-".$a."-".$Month."-set-".$year.".pl\">$a</a> </td>";
		else $fc .= "$td <a href=\"stat-".$a."-".$Month."-set-".$year.".pl\">$a</a> </td>";
	}
	elseif ($i==7 || $i==14 || $i==21 || $i==28 or $i==35)
	{
		if ($i==$day+$one_day) $fc .= "<td class=\"selectDate\"> <a href=\"stat-".$a."-".$Month."-set-".$year.".pl\">$a</a> </td></tr><tr>";
		else $fc .= "$td <a href=\"stat-".$a."-".$Month."-set-".$year.".pl\">$a</a> </td></tr><tr>";
		
	}
	elseif ($i==$day+$one_day) $fc .= "<td class=\"selectDate\"><a href=\"stat-".$a."-".$Month."-set-".$year.".pl\">$a</a></td>";
	else $fc .= "$td <a href=\"stat-".$a."-".$Month."-set-".$year.".pl\">$a</a> </td>";
}

if ($x!=0) for ( $i = 0; $i < $x; $i++) $fc .= "$td </td>";


# END #

if($month == 1) $yearUrl = $year-1;
else $yearUrl = $year;

if($month == 12) $yearUrl1 = $year+1;
else $yearUrl1 = $year;

if($Month == 1) $url = "stat-".$day."-12-set-".$yearUrl.".pl";
else $url = "stat-".$day."-".($Month-1)."-set-".$yearUrl.".pl";

if($Month == 12) $url1 = "stat-".$day."-1-set-".$yearUrl1.".pl";
else $url1 = "stat-".$day."-".($Month+1)."-set-".$yearUrl1.".pl";

$TestDate = Simple_DbApi::select_db("statistics","*","date",$day.".".$Month.".".$year,"","","","");
if(count($TestDate) > 0) $FormatDelete = CMS::SectionAdmin($TemplatesSection,2,"%DATAS%",$day.".".$Month.".".$year);
else $FormatDelete = "";

echo CMS::SectionAdmin($TemplatesSection,1,"%DATA%,%M%,%Y%,%URL%,%URL1%,%DELETE%,%DATAS%",$fc."<><>".$ListMonth[$Month-1]."<><>".$year."<><>".$url."<><>".$url1."<><>".$FormatDelete."<><>".$day.".".$Month.".".$year);


# end #

?>