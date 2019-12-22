<?php
function normalizeSimpleXML($obj) {
	$data = $obj;
	$result='';
	if (is_object($data)) {
		$data = get_object_vars($data);
	}
	if (is_array($data)) {
		foreach ($data as $key => $value) {
			$res = null;
			normalizeSimpleXML($value, $res);
			if (($key == '@attributes') && ($key)) {
				$result = $res;
			} else {
				$result[$key] = $res;
			}
		}
	} else {
		$result = $data;
	}
	return $result;
}

function clearxml($fname1,$fname2)
{
	$tmp=array();
	$origin=file($fname1);
	//preg_match_all('!<Worksheet(.*?)>(.*?)</Worksheet>!si', $origin, $tmp);
	//print_r($tmp);
	$countstr=count($origin);
	$fsave=0;
	$end=0;
	$last='';
	for($i=0;$i<$countstr;$i++)
	{
		if(!$fsave)
		{
			if(strstr($origin[$i],'<Worksheet ')) 
			{
				$fsave=1;
				
			}
		}
		
		if($fsave) $last.=$origin[$i];
		
		if($fsave) if(strstr($origin[$i],'</Worksheet>')) {$fsave=0;$end=1;}
		if($end) break;
	}
	$last=str_replace(array('ss:','x:','o:','html:',':ss',':x',':o',':html'),array('','','','','','','',''),$last);
	//echo $last;
	$fp = fopen($fname2,"w");
	flock($fp,LOCK_EX);
	fwrite($fp,"<?xml version=\"1.0\"?>".$last);
	flock($fp,LOCK_UN);
	fclose($fp);//*/
}

function clearshop()
{
	//delete all
	#delete images	
	$selectimgs=CMS::$db->query("select * from `shop_image`");
	foreach ($selectimgs as $k => $di)
	{
		$file1='images/shop/product/'.$di['id'].'.'.$di['type'];
		$file2='images/shop/product/small_'.$di['id'].'.'.$di['type'];
		if(file_exists($file1)) unlink($file1);
		if(file_exists($file2)) unlink($file2);
	}
	$clearimgs=CMS::$db->query("TRUNCATE TABLE `shop_image`");
	#delete files
	$selectfiles=CMS::$db->query("select * from `shop_file`");
	foreach ($selectfiles as $k => $df)
	{
		$file1='images/shop/product/'.$df['id'].'.'.$df['type'];
		if(file_exists($file1)) unlink($file1);
	}
	$clearfiles=CMS::$db->query("TRUNCATE TABLE `shop_file`");
	#delete goods
	$cleargoods=CMS::$db->query("TRUNCATE TABLE `shop`");
	#delete cats
	$selectcats=CMS::$db->query("select * from `shop_cat`");
	foreach ($selectcats as $k => $dc)
	{
		$file1='images/shop/category/'.$dc['id'].'.'.$dc['type'];
		if(file_exists($file1)) unlink($file1);
	}
	$clearcats=CMS::$db->query("TRUNCATE TABLE `shop_cat`");
	#delete search
	Simple_DbApi::delete_db('search','module','shop');
}

function normalizeshop()
{
	//delete all
	echo '<br>Goods:<br><br>';
	#fix goods
	$selectgoods=CMS::$db->query("select * from `shop`");
	foreach ($selectgoods as $k => $di)
	{
		//$di['name']
		Simple_DbApi::update_db('shop','name',trim($di['name']),'id',$di['id']);
		echo trim($di['name']).'<br>';
	}
	echo '<br>Cats:<br><br>';
	#fix cats
	$selectcats=CMS::$db->query("select * from `shop_cat`");
	foreach ($selectcats as $k => $dc)
	{
		Simple_DbApi::update_db('shop_cat','name',mb_strtoupper(trim($dc['name'])),'id',$dc['id']);
		echo mb_strtoupper(trim($dc['name'])).'<br>';
	}
}
/*
$path - имя файла включая путь
$_GET['name_tamlates'] - название модуля


*/

ini_set("max_execution_time", 0);
set_time_limit(0);
//normalizeshop(); 



if(isset($_POST['go']) || isset($_POST['gogo']))
{
	$path='_upload/upload.xml';
	$clearfile='_upload/clear.xml';
	if(isset($_POST['go']))
	{
		$tmpfile='_upload/upload'.date('Y-m-d_h-i-s').'.xml';
		echo $_FILES['file']['tmp_name'];
		if($_FILES['file']['tmp_name'])
		{
			echo "файл загружен";
			$file = $_FILES['file']['tmp_name'];
			copy($_FILES['file']['tmp_name'],$path);
			copy($path,$tmpfile);
		}
		else {echo "файл не загружен";exit();}
	}
	//clearshop();
	clearxml($path,$clearfile);
	//$file=
	//echo file_get_contents($clearfile);
	$xml = simplexml_load_file($clearfile);
	$urls=$xml->xpath("/Worksheet/Table/Row");
	$idcat=0;
	$catsadded=$goodsadded=$goodsupdated=0;
	echo '<pre>';
	//
	$desc=$href=$name=$num='';
	
	foreach($urls as $key)
	{
		if($key->Cell[0]) $num=$key->Cell[0]->Data; else $num='';
		if($key->Cell[1]) 
		{
			$name=trim($key->Cell[1]->Data);
			$href='';
			foreach($key->Cell[1]->attributes() as $a=>$b) if($a=='HRef') $href=$key->Cell[1]->attributes()->HRef;
		}else $name='';
		if($key->Cell[2]) $price=$key->Cell[2]->Data; else $price='';
		
		
		echo '<p>'.'num='.$num.'---'.'name='.$name.'---'.'price='.$price.'</p>';
		
		if($num=='' && $price=='' && $name!='')
		{
			//echo '<h2>'.$name.'</h2>';
			$name=mb_strtoupper($name);
			$testcat=CMS::$db->query("select * from `shop_cat` where `name` like '".$name."' ");
			if(!empty($testcat))
			{
				$dc=current($testcat);
				$idcat=$dc['id'];
			}
			else
			{
				$idcat=Simple_DbApi::auto_increment($_GET['name_tamlates'].'_cat');
				Simple_DbApi::insert_db($_GET['name_tamlates'].'_cat','id,name,desc_cat,status,type,parent,order','<><>'.$name.'<><>'.''.'<><>1<><>'.''.'<><>'.'0'.'<><>'.$idcat);
				$catsadded++;
			}
		}
		else if($num!='' && $price!='' && $name!='' && $idcat!=0)
		{
			//echo '<p>'.$num.'---'.$name.'---'.$price.'</p>';
			$desc='';
			if($key->Cell[5]) $desc=$key->Cell[5]->Data;
			
			$idshop=Simple_DbApi::auto_increment($_GET['name_tamlates']);
			$name = htmlspecialchars($name);
			$code = $num;
			
			$money = $price;
			$category = is_numeric($idcat) ? $idcat : 0;
			$shop_status = 1;
			
			$testshop=CMS::$db->query("select * from `shop` where `name` like '".$name."' ");
			if(!empty($testshop))
			{
				$ds=current($testshop);
				$idshop=$ds['id'];
				if($desc!='') Simple_DbApi::update_db($_GET['name_tamlates'],'desc,money',$desc.'<><>'.$money,'id',$ds['id']);
				else Simple_DbApi::update_db($_GET['name_tamlates'],'money',$money,'id',$ds['id']);
				
				if($href!='')
				{
					$testimg=CMS::$db->query("select * from `shop_image` where `id_shop` = '".$idshop."' ");
					if(!empty($testimg))
					{
						$img=file_get_contents($href);
						if($img!=false && !strstr($img,'<html'))
						{
							$idimg=Simple_DbApi::auto_increment($_GET['name_tamlates'].'_image');
							$fileext=simple_imagetest_format($href, 2);
							$file="images/".$_GET['name_tamlates']."/product/".$idimg.'.'.$fileext;
							$fp = fopen($file,"w");
							flock($fp,LOCK_EX);
							fwrite($fp,$img);
							flock($fp,LOCK_UN);
							fclose($fp);
							$filesmall="images/".$_GET['name_tamlates']."/product/small_".$idimg.'.'.$fileext;
							copy($file,$filesmall);
							simple_image_save($ProductSmallWidth, $ProductSmallHeight, $filesmall, $filesmall, $fileext, 1);
							simple_image_save($ProductWidth, $ProductHeight, $file, $file, $fileext, 1);
							Simple_DbApi::insert_db($_GET['name_tamlates'].'_image','id,id_shop,type,status','<><>'.$idshop.'<><>'.simple_imagetest_format($href, 2).'<><>0');
						}
					}
				}
				$goodsupdated++;
			}
			else
			{
				$CountShop = Simple_DbApi::CountTable($_GET['name_tamlates'],'id_cat',$category);
				
				Simple_DbApi::insert_db($_GET['name_tamlates'],'id,id_cat,name,desc,money,status,date,order,code','<><>'.$category.'<><>'.$name.'<><>'.$desc.'<><>'.$money.'<><>1<><>'.date('Y-m-d H:i:s').'<><>'.($CountShop+1).'<><>'.$code);
				
				if($href!='')
				{
					$img=file_get_contents($href);
					if($img!=false && !strstr($img,'<html'))
					{
						$idimg=Simple_DbApi::auto_increment($_GET['name_tamlates'].'_image');
						$fileext=simple_imagetest_format($href, 2);
						$file="images/".$_GET['name_tamlates']."/product/".$idimg.'.'.$fileext;
						$fp = fopen($file,"w");
						flock($fp,LOCK_EX);
						fwrite($fp,$img);
						flock($fp,LOCK_UN);
						fclose($fp);
						$filesmall="images/".$_GET['name_tamlates']."/product/small_".$idimg.'.'.$fileext;
						copy($file,$filesmall);
						simple_image_save($ProductSmallWidth, $ProductSmallHeight, $filesmall, $filesmall, $fileext, 1);
						simple_image_save($ProductWidth, $ProductHeight, $file, $file, $fileext, 1);
						Simple_DbApi::insert_db($_GET['name_tamlates'].'_image','id,id_shop,type,status','<><>'.$idshop.'<><>'.simple_imagetest_format($href, 2).'<><>0');
					}
				}
				$goodsadded++;
			}
			
		}
		//echo '{'.$key->Cell[1]->attributes()->HRef.'}<br/>';
		//$tmp=normalizeSimpleXML($key->Cell);
		//print_r($tmp);
		//print_r($key::__toString());
	}
	

	echo $updatecount.' == '.$numerickeyname; 
	echo $tmpvalues.'<br/>>>>>>>>>>>'.$mincode.'<br>Добавлено новых категорий:'.$catsadded.'<br>Добавлено новых товаров:'.$goodsadded.'<br>Обновлено товаров:'.$goodsupdated;
	echo '</pre>';
}

/*
<form action="#" method="post">
	<input type="submit" name="gogo" value="выполнить выгрузку из прошлого файла"/>
</form>
<p>&nbsp;</p>
<form action="#" method="post" enctype="multipart/form-data">
	<input type="file" name="file"/>
	<input type="submit" name="go" value="выполнить выгрузку из файла">
</form>

*/

?>

