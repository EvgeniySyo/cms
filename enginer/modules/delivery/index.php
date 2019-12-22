<?



$content .= CMS::section_list(0,"","");

$content .= CMS::section_list(1,"%ALL%",$all);

$select_cities = Simple_DbApi::select_db("stores_cities","*","","","city",1,"","");
$citycount=count($select_cities);
$r_cities=array();
$r_citiesreg=array();
if (!empty($select_cities)) {
	foreach ($select_cities as $i => $tmp_cities)
	{
		$r_cities[$tmp_cities['id']]=$tmp_cities['city'];
		$r_citiesreg[$tmp_cities['id']]=$tmp_cities['region'];
	}
}

asort($r_cities);


foreach($r_cities as $index=>$value)
{
	$sql = "SELECT * FROM `stores` WHERE `city`='".$index."' ORDER BY `stores`.`id` ASC";
	$select_stores = CMS::$db->query($sql);
	$store_list="";
	if (!empty($select_stores)) {
		foreach ($select_stores as $j => $r_stores)
		{
			$select_img = Simple_DbApi::select_db("stores_img","*","store_id,type",$r_stores['id'].'<><>'.'scheme',"","","","");
			if(!empty($select_img)) {$dsi=current($select_img);$scheme=$dsi['id'].'.'.$dsi['ext'];}
			else $scheme='';
			//	$phone = isset($phonesArray[$r_stores['id']]) ? $phonesArray[$r_stores['id']] : '';
			$floor = $r_stores['floor'];
			$coords = $r_stores['coords'] !='' ? CMS::section_list(4,"%ID_STORE%",$r_stores['id']) : '';
			$coords .= $scheme !='' ? CMS::section_list(6,"%LINK%",$scheme) : '';
			$coords .= $r_stores['tclink'] !='' ? CMS::section_list(7,"%LINK%",$r_stores['tclink']) : '';
			if(isset($_GET['cat'])) if($r_stores['id']==$_GET['cat'])
			{
				$thismap = $r_stores['coords'] !='' ? CMS::section_list(4,"%ID_STORE%",$r_stores['id']) : '';
				$thisscheme = $scheme !='' ? CMS::section_list(6,"%LINK%",$scheme) : '';
				$thistc = $r_stores['tclink'] !='' ? CMS::section_list(7,"%LINK%",$r_stores['tclink']) : '';
			}

			$info = $r_stores['info'] !='' ? CMS::section_list(8,"{info},{phone}",$r_stores['info']."<><>".$r_stores['phone']) : '';
			if($floor!='' || $info!='' || $coords!='')$phone = CMS::section_list(5,"%FLOOR%,%INFO%,%COORDS%",$floor."<><>".$info."<><>".$coords);

			$store_list .= '<a href="/stores/'.$r_stores['id'].'/">'.$r_stores['address']."</a>".$phone."<br/>\n";
		}
		if ($r_citiesreg[$index]!='') {
			$content .=CMS::section_list(2,"%CITY%,%REGION%,%STORES%,%STORE_ID%",$r_cities[$index]."<><>, ".$r_citiesreg[$index]."<><>".$store_list."<><>".$r_stores);
		}
		else {
			$content .=CMS::section_list(2,"%CITY%,%REGION%,%STORES%,%STORE_ID%",$r_cities[$index]."<><>".""."<><>".$store_list."<><>".$r_stores);
		}
	}

}

if(!isset($_GET['cat'])) $content .=CMS::section_list(10,"","");
else
{
	$selectstore=Simple_DbApi::select_db("stores","*","id",$_GET['cat'],"","","","");
	if(!empty($selectstore))
	{
		$ds=current($selectstore);
		$selectimgs=Simple_DbApi::select_db("stores_img","*","store_id,type",$_GET['cat'].'<><>'.'',"type","","","");
		$imgmain='';
		$imgsub='';
		$slider='';
		if(!empty($selectimgs))
		{
			foreach ($selectimgs as $i => $dimg)
			{
				if($i==0) $class='class="selected"';
				else $class='';
				$img='/images/stores/'.$dimg['id'].'.'.$dimg['ext'];
				$imgmain.= CMS::section_list('9-1',"{id},{img}",$dimg['id'].'<><>'.$img);
				$imgsub.= CMS::section_list('9-2',"{id},{img},{class}",$dimg['id'].'<><>'.$img.'<><>'.$class);
			}
			if($imgmain!='')
			{
				$slider=CMS::section_list(9,"{imgmain},{imgsub}",$imgmain.'<><>'.$imgsub);
			}
			
			$content.= CMS::section_list('9-3',"{slider},{address},{phone},{grafic},{desc},{map},{scheme},{tc}",$slider.'<><>'.$r_cities[$ds['city']].', '.$ds['address'].'<><>'.$ds['phone'].'<><>'.$ds['info'].'<><>'.$ds['desc'].'<><>'.$thismap.'<><>'.$thisscheme.'<><>'.$thistc);
		}
		
		$content .= CMS::section_list('10-1',"","");
	}
	else $content .=CMS::section_list(10,"","");
}


?>

