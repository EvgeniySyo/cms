<?php

$addhref='';


if($_GET['page']!='main')
{
	$class='navmain1';
	if($_GET['page']=='mc')
	{
		$select = Simple_DbApi::select_db($_GET['page'],"*","id",$_GET['cat'],"","","","");
		if(!empty($select))
		{
			$nt = current($select);
			if($nt['parent']!=0)
			{
				$id=$nt['parent'];
				while($id!=0)
				{
					$selectcat = Simple_DbApi::select_db($_GET['page'],"*","id",$id,"","","","");
					$dcat = current($selectcat);
					$href='/'.$_GET['page'].'/'.$dcat['id'].'/';
					$testalias = AliasFromUrl($href);
					if($testalias!='') $href='/'.$testalias;
					$addhref .= CMS::Section_Block_List(2,'{href},{name}',$href.'<><>'.$dcat['name']);
					$id=$dcat['parent'];
				}
			}
			$name=$nt['name'];
			$href='/'.$_GET['page'].'/'.$_GET['cat'].'/';
			$testalias = AliasFromUrl($href);
			if($testalias!='') $href='/'.$testalias;
			$addhref .= CMS::Section_Block_List(2,'{href},{name}',$href.'<><>'.$name);
		}
	}
	else if($_GET['page']=='shop' && is_numeric($_GET['cat']))
	{
		$select = Simple_DbApi::select_db($_GET['page'].'_cat',"*","id",$_GET['cat'],"","","","");
		if(!empty($select))
		{
			$nt = current($select);
			if($nt['parent']!=0)
			{
				$id=$nt['parent'];
				while($id!=0)
				{
					$selectcat = Simple_DbApi::select_db($_GET['page'].'_cat',"*","id",$id,"","","","");
					$dcat=current($selectcat);
					$href='/'.$_GET['page'].'/'.$dcat['id'].'/';
					$testalias=AliasFromUrl($href);
					if($testalias!='') $href='/'.$testalias;
					$addhref .= CMS::Section_Block_List(2,'{href},{name}',$href.'<><>'.$dcat['name']);
					$id=$dcat['parent'];
				}
			}
			$name=$nt['name'];
			$href='/'.$_GET['page'].'/'.$_GET['cat'].'/';
			$testalias=AliasFromUrl($href);
			if($testalias!='') $href='/'.$testalias;
			if(!isset($_GET['pages_all']))
			{$addhref .= CMS::Section_Block_List(2,'{href},{name}',$href.'<><>'.$name);}
			else
			{
				$addhref .= CMS::Section_Block_List(3,'{href},{name},{class}',$href.'<><>'.$name.'<><>'.$class);
				$select = Simple_DbApi::select_db($_GET['page'],"*","id",$_GET['pages_all'],"","","","");
				if(!empty($select))
				{
					$nt = current($select);
					$name=$nt['name'];
					$href='/'.$_GET['page'].'/'.$_GET['cat'].'/in/'.$_GET['pages_all'].'/';
					$addhref .= CMS::Section_Block_List(2,'{href},{name}',$href.'<><>'.$name);
				}
			}
		}
	}
	else
	{
		if(file_exists("modules/".$_GET['page']."/title.php")) include("modules/".$_GET['page']."/title.php");
		$name=$module_title;
		$href='/'.$_GET['page'].'/';
		$addhref .= CMS::Section_Block_List(2,'{href},{name}',$href.'<><>'.$name);
	}
	$block .= CMS::Section_Block_List(1,'{addhref},{class}',$addhref.'<><>'.$class);
	
}
	


?>