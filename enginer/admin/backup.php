<?php

if(isset($_POST['backup']))
{
	$ShowTables = Simple_DbApi::query_db('SHOW TABLES');
	$fp = fopen(DATA_PATH.'backup/'.date('Y-n-j-H-i-s').'.sql','a');
	while ($table = array_shift($ShowTables))
	{
		if($fp)
		{
			if($table[0] != _PREFIXDB_.'history')
			{
				$query = "TRUNCATE TABLE `".$table[0]."`;\n";
				fwrite ($fp, $query);
				$rows = Simple_DbApi::query_db('SELECT * FROM `'.$table[0].'`');
				while ($row = array_shift($rows))
				{
					$query = "";
					foreach ($row as $field)
					{
						if (is_null($field)) $field = "NULL";
						else $field = "'".$field."'";
						if ($query == "")$query = $field;
						else $query = $query.', '.$field;
					}
					$query = "INSERT INTO `".$table[0]."` VALUES (".$query.");\n";
					fwrite ($fp, $query);
				}
			}
		}
	}
	fclose ($fp);
	echo CMS::AlertWindow('Успешно','Бэкап создан',1,0);
	CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> создал бекап базы данных. Раздел <a target="_blank" href="backup.pl"><b>Бэкап базы данных</b></a>');

}

if(isset($_POST['delete']) && isset($_POST['f']))
{
	if(file_exists(DATA_PATH.'backup/'.$_POST['f'].'.sql'))
	{
		unlink(DATA_PATH.'backup/'.$_POST['f'].'.sql');
		echo CMS::AlertWindow('Успешно','Бэкап удален',1,0);

		list($year,$month,$day,$hour,$minute,$second) = explode('-',$_POST['f'],6);

		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> удалил бекап базы данных. Дата бэкапа: <b>'.$day.'.'.$month.'.'.$year.' '.$hour.':'.$minute.':'.$second.'</b>. Раздел <a target="_blank" href="backup.pl"><b>Бэкап базы данных</b></a>');
	}
	else echo CMS::AlertWindow('Ошибка','Данного файла не существует',3,0);
}

if(isset($_POST['restor']) && isset($_POST['f']))
{
	if(file_exists(DATA_PATH.'backup/'.$_POST['f'].'.sql'))
	{
		if(file_exists(DATA_PATH.'modules/mc/cacheList')) unlink(DATA_PATH.'modules/mc/cacheList');
		$ReadFile = file(DATA_PATH.'backup/'.$_POST['f'].'.sql');
		for ($j=0;$j<count($ReadFile);$j++)
		{
			$result = Simple_DbApi::query_db($ReadFile[$j]);
		}
		echo CMS::AlertWindow('Успешно','Данные успешно восстановлены',1,0);
		list($year,$month,$day,$hour,$minute,$second) = explode('-',$_POST['f'],6);

		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> произвел восстановленние данных. Дата бэкапа: <b>'.$day.'.'.$month.'.'.$year.' '.$hour.':'.$minute.':'.$second.'</b>. Раздел <a target="_blank" href="backup.pl"><b>Бэкап базы данных</b></a>');

	}
	else echo CMS::AlertWindow('Ошибка','Данного файла не существует',3,0);
}

$TemplatesSection = CMS::SectionFile('backup');
echo CMS::SectionAdmin($TemplatesSection,1,'','');

### LIST BACK UP ###
$find = 0;
$dirBackup = scandir(DATA_PATH.'backup');
rsort($dirBackup);

for ($i=0;$i<count($dirBackup);$i++)
{
	$id = $i+1;
	if(simple_imagetest_format(DATA_PATH.'backup/'.$dirBackup[$i],2) == 'sql')
	{
		$file = pathinfo(DATA_PATH.'backup/'.$dirBackup[$i]);
		list($year,$month,$day,$hour,$minute,$second) = explode('-',$file['filename'],6);
		if(is_numeric($year) && is_numeric($month) && is_numeric($day) && is_numeric($hour) && is_numeric($minute) && is_numeric($second))
		{
			$find = 1;
			$size = filesize(DATA_PATH.'backup/'.$dirBackup[$i]);
			$size = $size/1024; #KB
			$size = $size/1024; #MB
			$size = round($size,2);

			$list .= CMS::SectionAdmin($TemplatesSection,4,'{date},{size},{f},{id},{window},{window1}',$day.'.'.$month.'.'.$year.' '.$hour.':'.$minute.':'.$second.'<><>'.$size.'<><>'.$file['filename'].'<><>'.$id.'<><>'.CMS::AlertWindow('Внимание',CMS::SectionAdmin($TemplatesSection,5,'{date},{f},{id}',$day.'.'.$month.'.'.$year.' '.$hour.':'.$minute.':'.$second.'<><>'.$file['filename'].'<><>'.$id),2,$id).'<><>'.CMS::AlertWindow('Внимание',CMS::SectionAdmin($TemplatesSection,6,'{date},{f},{id}',$day.'.'.$month.'.'.$year.' '.$hour.':'.$minute.':'.$second.'<><>'.$file['filename'].'<><>'.'-'.$id),2,'-'.$id));
		}
	}
}

if($find == 1) $listBackup = CMS::SectionAdmin($TemplatesSection,3,'{list}',$list);

echo CMS::SectionAdmin($TemplatesSection,2,'{table}',$listBackup);

?>