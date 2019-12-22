<?php

CMS::CoreComponent('news');

$MaxNewsOnPage = 10;
$MaxListNumber = 10;
$TitleModule = CMS::TitleComponent($_GET['name_tamlates']);
$DateList = array("Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь");

function dateFormat($number)
{
	if($number < 10) $n = '0'.$number;
	else $n = $number;
	return $n;
}

if(isset($_POST['save']))
{
	### VALID INFO ###
	if(!is_numeric($_POST['NewsOnPasge']) || $_POST['NewsOnPasge'] < 0) $NewsOnPasge = 10;
	else $NewsOnPasge = $_POST['NewsOnPasge'];
	if(!is_numeric($_POST['CommOnPasge']) || $_POST['CommOnPasge'] < 0) $CommOnPasge = 10;
	else $CommOnPasge = $_POST['CommOnPasge'];
	if($_POST['comment'] == 1) $comment = 1;
	else $comment = 2;
	if($_POST['title'] == 1) $TitleNews = 1;
	else $TitleNews = 2;
	if($_POST['title1'] == 1) $TitleNewsCat = 1;
	else $TitleNewsCat = 2;
	if($_POST['LastNews'] == 1) $LastNews = 1;
	else $LastNews = 2;
	$_POST['pagebreak']?$PageBreak=1:$PageBreak=0;
	$_POST['timepublic']?$TimePublic=1:$TimePublic=0;
	if(!is_numeric($_POST['widthsmall'])) $SmallImageWidth = 150;
	else $SmallImageWidth = $_POST['widthsmall'];
	if(!is_numeric($_POST['heightsmall'])) $SmallImageHeight = 150;
	else $SmallImageHeight = $_POST['heightsmall'];
	$mailsend=strip_tags(str_replace("'",'',$_POST['mailsend']));
	### END ###

	$file = file(DATA_PATH."modules/".$_GET['name_tamlates']."/config.php");
	$fp = fopen(DATA_PATH."modules/".$_GET['name_tamlates']."/config.php","w");
	flock($fp,LOCK_EX);
	for ($i=0;$i<count($file);$i++)
	{
		if($i == 1) fwrite($fp,"\$NewsOnPasge = ".$NewsOnPasge.";\n");
		else if($i == 2) fwrite($fp,"\$CommOnPasge = ".$CommOnPasge.";\n");
		elseif ($i == 3) fwrite($fp,"\$comment = ".$comment.";\n");
		elseif ($i == 4) fwrite($fp,"\$LastNews = ".$LastNews.";\n");
		elseif ($i == 5) fwrite($fp,"\$TitleNews = ".$TitleNews.";\n");
		elseif ($i == 6) fwrite($fp,"\$TitleNewsCat = ".$TitleNewsCat.";\n");
		elseif ($i == 7) fwrite($fp,"\$PageBreak = ".$PageBreak.";\n");
		elseif ($i == 8) fwrite($fp,"\$TimePublic = ".$TimePublic.";\n");
		else if($i == 9) fwrite($fp,"\$SmallImageWidth=".$SmallImageWidth.";\n");
		else if($i == 10) fwrite($fp,"\$SmallImageHeight=".$SmallImageHeight.";\n");
		else if($i == 11) fwrite($fp,"\$mailsend='".$mailsend."';\n");
		else fwrite($fp,$file[$i]);
	}
	flock($fp,LOCK_UN);
	fclose($fp);

	echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
	CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> изменил настройки в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'.pl"><b>'.$TitleModule.'</b></a>');
}

if(!is_numeric($_GET['page']))
{
	$select1 = $select2 = $select3 = $select4 = $select5 = $select6 = $select7 = $select8 = $select9 = $select10 = $select11 = $select12 = "";

	if($comment == 1) $select1 = "selected";
	else $select2 = "selected";
	if($LastNews == 1) $select3 = "selected";
	else $select4 = "selected";
	if($TitleNews == 1) $select5 = 'selected';
	else $select6 = 'selected';
	if($TitleNewsCat == 1) $select7 = 'selected';
	else $select8 = 'selected';
	$PageBreak?$select9 = 'selected':$select10 = 'selected';
	$TimePublic?$select11 = 'selected':$select12 = 'selected';

	echo CMS::AdminModuleSection(1,"{newspage},{commentpage},{select-1},{select-2},{select-3},{select-4},{NameModule},{select-5},{select-6},{select-7},{select-8},{select-9},{select-10},{select-11},{select-12},{widthsmall},{heightsmall},{mailsend}",$NewsOnPasge."<><>".$CommOnPasge."<><>".$select1."<><>".$select2."<><>".$select3."<><>".$select4."<><>".$_GET['name_tamlates']."<><>".$select5."<><>".$select6."<><>".$select7."<><>".$select8."<><>".$select9."<><>".$select10."<><>".$select11."<><>".$select12.'<><>'.$SmallImageWidth.'<><>'.$SmallImageHeight."<><>".$mailsend);
}
else
{
	if($_GET['page'] == 7 || $_GET['page'] == 8 || $_GET['page'] == 9 || $_GET['page'] == 4 || $_GET['page'] == 5 || $_GET['page'] == 6 || $_GET['page'] == 10)
	{
		$style1 = "";
		$style2 = "";
		$style3 = "";
		$list1 = "";
		$list2 = "";
		$list3 = "";

		if($_GET['page'] == 7)
		{
			$list1 = "&#8595;";
			$list2 = "&#8594;";
			$list3 = "&#8594;";
			$style1 = "style=\"background:#b7ddf2;\"";
		}
		if($_GET['page'] == 8 || $_GET['page'] == 4 || $_GET['page'] == 5)
		{
			$list1 = "&#8592;";
			$list2 = "&#8595;";
			$list3 = "&#8594;";
			$style2= "style=\"background:#b7ddf2;\"";
		}
		if($_GET['page'] == 9 || $_GET['page'] == 6 || $_GET['page'] == 10)
		{
			$list1 = "&#8592;";
			$list2 = "&#8592;";
			$list3 = "&#8595;";
			$style3 = "style=\"background:#b7ddf2;\"";
		}

		echo CMS::AdminModuleSection(2,"{NameModule},{style-1},{style-2},{style-3},{list-1},{list-2},{list-3}",$_GET['name_tamlates']."<><>".$style1."<><>".$style2."<><>".$style3."<><>".$list1."<><>".$list2."<><>".$list3);
	}
	if($_GET['page'] == 1 || $_GET['page'] == 2 || $_GET['page'] == 3)
	{
		$style1 = "";
		$style2 = "";
		$style3 = "";
		$list1 = "";
		$list2 = "";
		$list3 = "";

		if($_GET['page'] == 1)
		{
			$list1 = "&#8595;";
			$list2 = "&#8594;";
			$list3 = "&#8594;";
			$style1 = "style=\"background:#b7ddf2;\"";
		}
		if($_GET['page'] == 2)
		{
			$list1 = "&#8592;";
			$list2 = "&#8595;";
			$list3 = "&#8594;";
			$style2= "style=\"background:#b7ddf2;\"";
		}
		if($_GET['page'] == 3)
		{
			$list1 = "&#8592;";
			$list2 = "&#8592;";
			$list3 = "&#8595;";
			$style3 = "style=\"background:#b7ddf2;\"";
		}

		echo CMS::AdminModuleSection(4,"{NameModule},{style-1},{style-2},{style-3},{list-1},{list-2},{list-3}",$_GET['name_tamlates']."<><>".$style1."<><>".$style2."<><>".$style3."<><>".$list1."<><>".$list2."<><>".$list3);
	}

	if($_GET['page'] == 1) include("AddCat.php");
	if($_GET['page'] == 2) include("editCat.php");
	if($_GET['page'] == 3) include("deleteCat.php");

	if($_GET['page'] == 7) include("AddNews.php");
	if($_GET['page'] == 8 || $_GET['page'] == 4 || $_GET['page'] == 5) include("editNews.php");
	if($_GET['page'] == 9 || $_GET['page'] == 6 || $_GET['page'] == 10) include("deleteNews.php");
}
?>