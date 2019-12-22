<?php
CMS::CoreComponent('json');



if($_GET['page']=='shop')
{
	$module = 'shop';
	$thiscat='';
	$thiscat='';
	if(is_numeric($_GET['cat']) && $_GET['cat']>0) $thiscat=$_GET['cat'];
	//if(!file_exists(DATA_PATH."cache/block/".md5($BlockName)))
	//{
		$block .= CMS::Section_Block_List(3,'','');
		$list='';
		$selectednum='';
		$SelectCatalog = Simple_DbApi::select_db($module.'_cat','*','parent','0','name',1,'','');
		if(!empty($SelectCatalog))
		{
			foreach ($SelectCatalog as $i => $nc)
			{
				if($nc['id'] != $thiscat) $list.= CMS::Section_Block_List(2,'{url},{name}',URL_SITE.$module.'/'.$nc['id'].'/<><>'.$nc['name']);
				else
				{
					$list.= CMS::Section_Block_List('2-1','{url},{name}',URL_SITE.$module.'/'.$nc['id'].'/<><>'.$nc['name']);
					$selectednum = CMS::Section_Block_List('2-2','{num}',$i);
				}
			}
			$list .= $selectednum;
			$block .= CMS::Section_Block_List(1,'{list},{slideto}',$list.'<><>'.$selectednum);
			CMS::BlockInsertCacheData(md5($BlockName),$block);
		}
	//}
	//else $block .= file_get_contents(DATA_PATH."cache/block/".md5($BlockName));
}




?>