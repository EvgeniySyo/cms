<?php

CMS::CoreComponent('FunctionMainBlock');

if(is_numeric($_GET['ajax1']) && isset($_GET['type']))
{
	$select = Simple_DbApi::select_db('block_'.$_GET['config'],'*','id',$_GET['ajax1'],'','',0,1);
	if(!empty($select))
	{
		$nt = current($select);
		$IdCat = $nt['id_cat'];
		$orderElement = $nt['order'];

		if($_GET['type'] == 'top')
		{
			if($orderElement > 1)
			{
				$SelectId = Simple_DbApi::select_db('block_'.$_GET['config'],'*','id_cat,order',$IdCat.'<><>'.($orderElement-1),'order',1,0,1);
				$nf = current($SelectId);
				Simple_DbApi::update_db('block_'.$_GET['config'],'order',$nf['order'],'id',$nt['id']);
				Simple_DbApi::update_db('block_'.$_GET['config'],'order',$nt['order'],'id',$nf['id']);
			}
		}
		if($_GET['type'] == 'bottom')
		{
			$count = Simple_DbApi::CountTable('block_'.$_GET['config'],'id_cat',$IdCat);
			if($orderElement < $count)
			{
				$SelectId = Simple_DbApi::select_db('block_'.$_GET['config'],'*','id_cat,order',$IdCat.'<><>'.($orderElement+1),'order',1,0,1);
				$nf = current($SelectId);
				Simple_DbApi::update_db('block_'.$_GET['config'],'order',$nf['order'],'id',$nt['id']);
				Simple_DbApi::update_db('block_'.$_GET['config'],'order',$nt['order'],'id',$nf['id']);
			}
		}
	}
	WriteCache();
	echo ReadCacheMain();
}

?>