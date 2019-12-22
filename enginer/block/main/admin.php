<?php

CMS::CoreComponent('FunctionMainBlock');
$TitleModule = CMS::TitleComponent($_GET['config'],'block');
### ADD
if(isset($_POST['add']))
{
	$name = strip_tags($_POST['name']);
	$url = strip_tags($_POST['url']);
	$url_list = strip_tags($_POST['urlList']);
	if(!is_numeric($_POST['cat']) || $_POST['cat'] < 0) $cat = 0;
	else $cat = $_POST['cat'];

	if(isset($_POST['moduleList']))
	{
		for ($i=0;$i<count($_POST['moduleList']);$i++)
		{
			if(strlen($_POST['moduleList'][$i]) > 0)
			{
				if($i != (count($_POST['moduleList'])-1)) $module .=  $_POST['moduleList'][$i]."::";
				else $module .=  $_POST['moduleList'][$i];
			}
		}
	}

	if(strlen($name) > 0)
	{
		$CountId = Simple_DbApi::CountTable('block_'.$_GET['config'],'id_cat',$cat);
		Simple_DbApi::insert_db('block_'.$_GET['config'],'id,id_cat,name,url,module,select_url,order','<><>'.$cat.'<><>'.$name.'<><>'.$url.'<><>'.$module.'<><>'.$url_list.'<><>'.($CountId+1));
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> добавил пункт <b>'.$name.'</b> в блоке <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="block-'.$_GET['config'].'.pl"><b>'.$TitleModule.'</b></a>');
		echo CMS::AlertWindow('Успешно','Успешно добавлено',1,0);
		WriteCache();
	}
	else echo CMS::AlertWindow('Ошибка','Не заполнено название',2,0);

}
### DELETE
if(isset($_POST['delete']) && is_numeric($_POST['id']))
{
	$listId = listID($_POST['id']);
	$formatID = IDarray($listId);

	if(!empty($formatID))
	{
		foreach ($formatID as $full)
		{
			Simple_DbApi::delete_db('block_'.$_GET['config'],'id',$full);
		}
	}

	$SelectListCat = Simple_DbApi::select_db('block_'.$_GET['config'],'*','id',$_POST['id'],'','','','');
	if(!empty($SelectListCat))
	{
		$ni = current($SelectListCat);
		Simple_DbApi::query_db("UPDATE `"._PREFIXDB_.'block_'.$_GET['config']."` SET `order`=(`order`-1) WHERE `id_cat`='".$ni['id_cat']."'  AND `order`>'".$ni['order']."'");
	}

	Simple_DbApi::delete_db('block_'.$_GET['config'],'id',$_POST['id']);
	CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> удалил пункт <b>'.$ni['name'].'</b> в блоке <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="block-'.$_GET['config'].'.pl"><b>'.$TitleModule.'</b></a>');
	echo CMS::AlertWindow('Успешно','Успешно удалено',1,0);
	WriteCache();

}
### EDIT
if(isset($_POST['edit']) && is_numeric($_POST['id']))
{
	if(!is_numeric($_POST['cat'])) $cat = 0;
	else $cat = $_POST['cat'];

	$name = strip_tags($_POST['name']);
	$url = strip_tags($_POST['url']);
	$url_list = strip_tags($_POST['urlList']);

	if(isset($_POST['moduleList']))
	{
		for ($i=0;$i<count($_POST['moduleList']);$i++)
		{
			if(strlen($_POST['moduleList'][$i]) > 0)
			{
				if($i != (count($_POST['moduleList'])-1)) $module .=  $_POST['moduleList'][$i]."::";
				else $module .=  $_POST['moduleList'][$i];
			}
		}
	}

	if(strlen($name) > 0)
	{
		if($cat == 0)
		{
			Simple_DbApi::update_db('block_'.$_GET['config'],'name,url,module,select_url',$name.'<><>'.$url.'<><>'.$module.'<><>'.$url_list,'id',$_POST['id']);
			echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
			WriteCache();
		}
		else
		{

			$SelectIDcat = Simple_DbApi::select_db('block_'.$_GET['config'],'id_cat','id',$cat,'','','','');
			$nn = current($SelectIDcat);
			$SelectIDcat1 = Simple_DbApi::select_db('block_'.$_GET['config'],'id_cat','id',$_POST['id'],'','','','');
			$nn1 = current($SelectIDcat1);
			$id_cat = $nn['id_cat'];
			$id_cat1 = $nn1['id_cat'];

			Simple_DbApi::update_db('block_'.$_GET['config'],'id,id_cat','0<><>'.$id_cat1,'id',$cat);
			Simple_DbApi::update_db('block_'.$_GET['config'],'id,id_cat,name,url,module,select_url',$cat.'<><>'.$id_cat.'<><>'.$name.'<><>'.$url.'<><>'.$module.'<><>'.$url_list,'id',$_POST['id']);
			Simple_DbApi::update_db('block_'.$_GET['config'],'id',$_POST['id'],'id',0);

			echo CMS::AlertWindow('Успешно','Данные обновлены',1,0);
			WriteCache();
		}
	}
	else echo CMS::AlertWindow('Ошибка','Не заполнено название',2,0);

}

if(!file_exists(DATA_PATH.'block/'.$_GET['config'].'/cache')) WriteCache();

echo CMS::SectionBlock(1,'','');
echo CMS::SectionBlock(5,'{li}',ReadCacheMain());

$SelectComp = Simple_DbApi::select_db('modules','*','install','yes','','','','');
$componet = '';
if(!empty($SelectComp))
{
	foreach ($SelectComp as $i => $nm)
	{
		if(file_exists('modules/'.$nm['name'].'/index.php'))
		{
			if(file_exists('modules/'.$nm['name'].'/title.php')) include('modules/'.$nm['name'].'/title.php');
			if(!empty($module_title)) $nameModule = $module_title;
			else $nameModule = $nm['name'];
			$componet .= CMS::SectionBlock(4,"{name},{module}",$nameModule."<><>".$nm['name']."");
		}
		unset($nameModule);
	}
}


echo CMS::SectionBlock(3,'{module},{option}',$componet.'<><>'.ReadCacheOption());

echo CMS::SectionBlock(2,'','');

?>