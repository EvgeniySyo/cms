<?php
CMS::CoreComponent('FunctionMainBlock');
CMS::CoreComponent('page');

function SortArrayField($arr,$keyname)
{
	$tmp=array();
	foreach($arr as $index=>$value)
	{
		$tmp[$index]=$value[$keyname];
	}
	asort($tmp);
	$tmp1=array();
	foreach($tmp as $index=>$value)
	{
		$tmp1[$index]=$arr[$index];
	}
	return $tmp1;
}

function ListCategoryPortfolio($FullList,$id,$level)
{
	$level++;
	if(is_array($FullList) && isset($FullList[$id]))
	{
		$countFull = count($FullList[$id]);
		$i=1;
        $text = '';
		foreach ($FullList[$id] as $option)
		{
			if($i==1) $text .= CMS::Section_Block_List(10,'{class}','menu-level-'.$level);
			$class = '';
			if($i==1) $class.='menu-first';
			if($i==count($FullList[$id])) $class.=' menu-last';
			if(!empty($FullList[$option['id']])) $class.=' menu-havechild';
			//alias
			
			$testalias=AliasFromUrl($option['url']);
			if($testalias!='') $option['url']='/'.$testalias;
			if($_SERVER['REQUEST_URI'] == $option['url'] || $_GET['page'] == $option['module']) $class.=' menu-light';
			$class = trim($class);
			$text .= CMS::Section_Block_List(12,'{class}','class="'.$class.'"');
			
			$text .= CMS::Section_Block_List(15,'{url},{name}',$option['url'].'<><>'.$option['name']);
			$text .= ListCategoryPortfolio($FullList,$option['id'],$level);
			$text .= CMS::Section_Block_List(13,'','');
			if($i==count($FullList[$id]))  $text .= CMS::Section_Block_List(11,'','');
			$i++;
		}
	}
	else return null;
	return $text;
}
$option = $top = $block = '';
$Selectall = Simple_DbApi::select_db('block_main', "*", "", "", "order", "1", "", "");
if(!empty($Selectall))
{
	$Format = array();
	foreach ($Selectall as $Format1) {
        $Format[$Format1['id_cat']][] = $Format1;
    }
	if(count($Format[0])>1)
	{
		$Format[0]=SortArrayField($Format[0],'order');
	}
	
	$option = ListCategoryPortfolio($Format,0,0);
}

$block .= CMS::Section_Block_List(7,'{top},{li}',$top.'<><>'.$option);



//$block .= CMS::Section_Block_List(1,'{li}',ListMain($BlockName));

?>