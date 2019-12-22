<?php
$cat = $s = $Numbers = $s = $s1 = $t = $t1 = $t2 = '';
CMS::CoreComponent('module');
$TitleModule = CMS::TitleComponent($_GET['config'],'block');
if(Simple_Module::TestInstall('news') == true)
{
	
	if(isset($_POST['save']))
	{
		if(!is_numeric($_POST['news'])) $LastNewsView = 3;
		else $LastNewsView = $_POST['news'];
		
		if($_POST['type'] < 0 && $_POST['type'] > 2) $Type = 0;
		else $Type = $_POST['type'];
		
		if($_POST['image'] == 0) $ImageNews = 1;
		else $ImageNews = 0;
		
		if(!is_numeric($_POST['text'])) $Numbers = 100;
		else $Numbers = $_POST['text'];
		
		if(!is_numeric($_POST['cat'])) $IdCat = 0;
		else $IdCat = $_POST['cat'];
		
		$file = file(DATA_PATH.'block/'.$_GET['config'].'/config.php');
		$fp = fopen(DATA_PATH.'block/'.$_GET['config'].'/config.php','w');
		flock($fp,LOCK_EX);
		for ($j=0;$j<count($file);$j++)
		{
			if($j == 1) fwrite($fp,"\$LastNewsView=".$LastNewsView.";\n");
			else if($j == 2) fwrite($fp,"\$IdCat=".$IdCat.";\n");
			else if($j == 3) fwrite($fp,"\$Type=".$Type.";\n");
			else if($j == 4) fwrite($fp,"\$ImageNews=".$ImageNews.";\n");
			else if($j == 5) fwrite($fp,"\$Numbers=".$Numbers.";\n");
			else fwrite($fp,$file[$j]);
		}
		flock($fp,LOCK_UN);
		fclose($fp);
		
		if(file_exists(DATA_PATH.'cache/block/'.md5($_GET['config']))) unlink(DATA_PATH.'cache/block/'.md5($_GET['config']));
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> изменил настройки в блоке <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="block-'.$_GET['config'].'.pl"><b>'.$TitleModule.'</b></a>');
		echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
		
	}
	
	$SelectCatNews = Simple_DbApi::select_db('news_cat','*','','','names',1,'','');
	if(!empty($SelectCatNews)) {
        foreach ($SelectCatNews as $i => $nn)
        {
            if($IdCat == $nn['ids']) $cat .= CMS::SectionBlock(2,'{id},{name},{s}',$nn['ids'].'<><>'.$nn['names'].'<><>selected');
            else $cat .= CMS::SectionBlock(2,'{id},{name},{s}',$nn['ids'].'<><>'.$nn['names'].'<><>');
        }
    }

	if($ImageNews == 1) $s = 'selected';
	else $s1 = 'selected';
	
	if($Type == 0) $t = 'selected';
	else if($Type == 1) $t1 = 'selected';
	else $t2 = 'selected';
	
	echo CMS::SectionBlock(1,'{num},{option},{n},{s},{s1},{t},{t1},{t2}',$LastNewsView.'<><>'.$cat.'<><>'.$Numbers.'<><>'.$s.'<><>'.$s1.'<><>'.$t.'<><>'.$t1.'<><>'.$t2);
}
else echo CMS::SectionBlock(3,'','');
?>