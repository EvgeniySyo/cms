<?php
# EDIT MAIN CONTENT #
$TitleModule = CMS::TitleComponent($_GET['name_tamlates']);
if(isset($_POST['main']))
{
	$TextMain = htmlspecialchars($_POST['FCKeditor']);
	$TextMain = html_entity_decode($TextMain);
	$TextMain = str_replace("\\","",$TextMain);
	
		$fp = fopen(DATA_PATH.'/modules/'.$_GET['name_tamlates'].'/main','w');
		flock($fp,LOCK_EX);
		fwrite($fp,$TextMain);
		flock($fp,LOCK_UN);
		fclose($fp);
		echo CMS::AlertWindow(SIMPLE_MC_TRUE,SIMPLE_MC_DATA_TRUE,1,0);
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> изменил текст главного раздела в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'.pl"><b>'.$TitleModule.'</b></a>');
	
}
# EDIT CAT CONTENT #
if(isset($_POST['EditCatContent']) && is_numeric($_POST['id']))
{
	$TestCat = Simple_DbApi::select_db($_GET['name_tamlates'],'*','type,id','cat<><>'.$_POST['id'],'','','','');
	if(count($TestCat) == 1)
	{
		Simple_DbApi::update_db($_GET['name_tamlates'],'content',$_POST['FCKeditor'],'id',$_POST['id']);
		echo CMS::AlertWindow(SIMPLE_MC_TRUE,SIMPLE_MC_DATA_TRUE,1,0);
	}
}

if(isset($_POST['save_data']))
{
	if($_POST['path'] == 1) $path = 1;
	else $path = 2;
	if($_POST['fansybox'] == 1) $fansybox = 1;
	else $fansybox = 2;
	if($_POST['text'] == 1) $textfansy = 1;
	else $textfansy = 2;

	$fp = fopen(DATA_PATH.'/modules/'.$_GET['name_tamlates'].'/config.php','w');
	flock($fp,LOCK_EX);
	fwrite($fp,"<?php\n\$path=".$path.";\n\$fansybox=".$fansybox.";\n\$textfansy=".$textfansy.";\n?>");
	flock($fp,LOCK_UN);
	fclose($fp);

	if(BUFFERSITE == "on") CMS::ClearCacheDirectory();

	CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> изменил настройки в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'.pl"><b>'.$TitleModule.'</b></a>');
	echo CMS::AlertWindow(SIMPLE_MC_TRUE,SIMPLE_MC_DATA_TRUE,1,0);

}

function  TreeMC($full,$id){
	if(is_array($full) && isset($full[$id]))
	{
		if(!isset($GLOBALS['SIMPLE_MC']))
		{
			$main = CMS::AdminModuleSection(11,"{name},{url}",SIMPLE_MC_MAIN."<><>main-modules-setting-mod-".$_GET['name_tamlates']."-0.pl");
			$GLOBALS['SIMPLE_MC'] = 1;
		}
		$text = CMS::AdminModuleSection(16,'{main}',$main);
		foreach($full[$id] as $list){
			$text .= CMS::AdminModuleSection(18,'{url},{name}','main-modules-setting-mod-'.$_GET['name_tamlates'].'-'.$list['id'].'.pl<><>'.$list['name']);
			$text.=  TreeMC($full,$list['id']);
			$text .= CMS::AdminModuleSection(19,'','');
		}
		$text .=  CMS::AdminModuleSection(17,'','');
	}
	else return null;
	return $text;
}

function CategoryList($id,$go,$id_category)
{
    $content = '';
    $selectIdCat = Simple_DbApi::select_db($_GET['name_tamlates'],'id,type,name,parent','type,parent','cat<><>'.$id.'','name',1,'','');
	if(!empty($selectIdCat))
	{
		foreach ($selectIdCat as $i => $nt)
		{
			$s = $nt['id'] == $id_category ? 'selected' : '';
			$content .= CMS::AdminModuleSection('8-2','{s},{id},{go},{name}',$s.'<><>'.$nt['id'].'<><>&nbsp;&nbsp;'.$go.'<><>'.$nt['name']);
			$content .= CategoryList($nt['id'],'&nbsp;&nbsp;&nbsp;&nbsp;'.$go,$id_category);
		}
	}
	return $content;
}

if(!isset($_GET['page']) || !is_numeric($_GET['page'])) $id = 0;
else $id = $_GET['page'];
$list = $pagesList = '';

### ADD NEW CATEGORY

if(isset($_POST['add']))
{
	$NameFolder = strip_tags($_POST['nameCat']);
	$PathToCat = $_POST['PathToCat'] == 1 ? 1 : 2;
	@unlink(DATA_PATH.'modules/'.$_GET['name_tamlates'].'/cacheList');
	if(strlen($NameFolder) > 0)
	{
		$selectmax=CMS::$db->query("select * from `"._PREFIXDB_."mc` where `parent`='".$id."' and `type`='cat' order by `order` desc limit 1");
		if(!empty($selectmax))
		{
			$nc = current($selectmax);
			$order=$nc['order']+1;
		}
		else $order=1;
		Simple_DbApi::insert_db($_GET['name_tamlates'],"id,type,name,content,parent,search,keyword,path,order","<><>cat<><>".$NameFolder."<><><><>".$id."<><><><><><>".$PathToCat."<><>".$order);
		
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> добавил раздел <b>'.$NameFolder.'</b> в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'.pl"><b>'.$TitleModule.'</b></a>');
		
		echo CMS::AlertWindow(SIMPLE_MC_TRUE,SIMPLE_MC_CAT_TRUE,1,0);
	}
	else echo CMS::AlertWindow(SIMPLE_MC_ERROR,SIMPLE_MC_NAME_NULL,3,0);

}
/* template for alias mc/{id} page/cat */
### ADD NEW PAGE

if(isset($_POST['addPage']))
{
	$NamePage = strip_tags($_POST['namePage']);
	$textAdd = $_POST['FCKeditor'];
	
	$IdPage = Simple_DbApi::auto_increment($_GET['name_tamlates']);
	
	$keywords = strip_tags($_POST['SIMPLE_META_KEYWORD']);
	$titlePage = strip_tags($_POST['SIMPLE_META_TITLE']);
	$descPage = strip_tags($_POST['SIMPLE_META_DESC']);
	$alias = strip_tags($_POST['SIMPLE_ALIAS']);
	$aliasvarnames='page/cat';
	$aliasvarvalues='mc/'.$IdPage;
	
	$search = strip_tags($textAdd);
	if($_POST['pathInPa'] == 1) $PathTo = 1;
	else $PathTo = 2;

	if(strlen($NamePage) > 0 && !CMS::TestAliasExists($alias,$aliasvarnames,$aliasvarvalues))
	{
		CMS::AliasAdd($alias,$aliasvarnames,$aliasvarvalues);
		#order
		$selectmax=CMS::$db->query("select * from `"._PREFIXDB_."mc` where `parent`='".$id."' and `type`='page' order by `order` desc limit 1");
		if(!empty($selectmax))
		{
			$nc = current($selectmax);
			$order=$nc['order']+1;
		}
		else $order=1;
		Simple_DbApi::insert_db($_GET['name_tamlates'],"id,type,name,content,parent,search,keyword,path,desc,title,order","<><>page<><>".$NamePage."<><>".$textAdd."<><>".$id."<><>".$search."<><>".$keywords."<><>".$PathTo."<><>".$descPage."<><>".$titlePage."<><>".$order);
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> добавил страницу <b>'.$NamePage.'</b> в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'.pl"><b>'.$TitleModule.'</b></a>');
		CMS::InsertSearchText($IdPage,'/'.$textAdd,$_GET['name_tamlates']."/".$IdPage."/",$NamePage);
		unset($textAdd);
		echo CMS::AlertWindow(SIMPLE_MC_TRUE,SIMPLE_MC_ADDPAGE_TRUE,1,0);
		$NamePage=$textAdd=$keywords=$titlePage=$descPage=$alias='';
	}
	else echo CMS::AlertWindow(SIMPLE_MC_ERROR,SIMPLE_MC_NAMEPAGE_NULL,3,0);
}

### UPDATE INFO PAGE

if(isset($_POST['idPage']) && isset($_POST['edit']))
{
	$SelectThis=Simple_DbApi::select_db($_GET['name_tamlates'],"*","type,id","page<><>".$_POST['idPage']."","","","","");
	$DataThis=current($SelectThis);
	$NamePage = strip_tags($_POST['namePage']);
	$textAdd = $_POST['FCKeditor'];
	$search = strip_tags($textAdd);
	
	$keywords = !empty($_POST['SIMPLE_META_KEYWORD']) ? strip_tags($_POST['SIMPLE_META_KEYWORD']) : '';
	$titlePage = !empty($_POST['SIMPLE_META_TITLE']) ? strip_tags($_POST['SIMPLE_META_TITLE']) : '';
	$descPage = !empty($_POST['SIMPLE_META_DESC']) ? strip_tags($_POST['SIMPLE_META_DESC']) : '';
	$alias = !empty($_POST['SIMPLE_ALIAS']) ? strip_tags($_POST['SIMPLE_ALIAS']) : '';
	$aliasvarnames='page/cat';
	$aliasvarvalues='mc/'.$DataThis['id'];
	
	
	$IdParent = !empty($_POST['PageCcategory']) && is_numeric($_POST['PageCcategory']) ? $_POST['PageCcategory'] : 0;
	#order
	if($IdParent!=$id)
	{
		$selectmax = CMS::$db->query("select * from `"._PREFIXDB_."mc` where `parent`='".$IdParent."' and `type`='page' order by `order` desc limit 1");
		if(!empty($selectmax))
		{
			$nc = current($selectmax);
			$order=$nc['order']+1;
		}
		else $order=1;
		$setcategory=1;
	}
	else
	{
		$setcategory=0;
	}
	
	if($_POST['pathInPa'] == 1) $PathTo = 1;
	else $PathTo = 2;

	if(strlen($NamePage) > 0 && !CMS::TestAliasExists($alias,$aliasvarnames,$aliasvarvalues))
	{
		CMS::UpdateSearchText($_POST['idPage'],$search,$NamePage,"/".$_GET['name_tamlates']."/".$_POST['idPage']."/");
		CMS::AliasAdd($alias,$aliasvarnames,$aliasvarvalues);
		if($setcategory)
		{
            CMS::$db->query("update `"._PREFIXDB_."mc` set `order`=(`order`-1) where `parent`='".$DataThis['parent']."' and `type`='page' and `order`>'".$DataThis['order']."'");
			Simple_DbApi::update_db($_GET['name_tamlates'],"name,content,search,keyword,path,desc,title,parent,order",$NamePage."<><>".$textAdd."<><>".$search."<><>".$keywords."<><>".$PathTo."<><>".$descPage."<><>".$titlePage."<><>".$IdParent."<><>".$order,"id",$_POST['idPage']);
		}
		else
		{
			Simple_DbApi::update_db($_GET['name_tamlates'],"name,content,search,keyword,path,desc,title,parent",$NamePage."<><>".$textAdd."<><>".$search."<><>".$keywords."<><>".$PathTo."<><>".$descPage."<><>".$titlePage."<><>".$IdParent,"id",$_POST['idPage']);
			CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> внес изменения в страницу <b>'.$NamePage.'</b> в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'-'.$_GET['page'].'-'.$_GET['id_cat'].'-'.$_GET['id_news'].'.pl"><b>'.$TitleModule.'</b></a>');
		}
		
		unset($textAdd);
		echo CMS::AlertWindow(SIMPLE_MC_TRUE,SIMPLE_MC_DATA_TRUE,1,0);
	}
	else echo CMS::AlertWindow(SIMPLE_MC_ERROR,SIMPLE_MC_NAMEPAGE_NULL,3,0);
}

### DELETE PAGE

if(isset($_POST['deletePage']) && is_numeric($_POST['idCat']))
{
	$SelectThis=Simple_DbApi::select_db($_GET['name_tamlates'],"*","type,id","page<><>".$_POST['idCat']."","","","","");
	$DataThis=current($SelectThis);
    CMS::$db->query("update `"._PREFIXDB_."mc` set `order`=(`order`-1) where `parent`='".$DataThis['parent']."'  and `type`='page' and `order`>'".$DataThis['order']."'");//
	Simple_DbApi::delete_db($_GET['name_tamlates'],"id",$_POST['idCat']);
	CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> удалил страницу <b>'.$DataThis['name'].'</b> в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'.pl"><b>'.$TitleModule.'</b></a>');
	CMS::DeleteSearch($_POST['idCat']);
	echo CMS::AlertWindow(SIMPLE_MC_TRUE,SIMPLE_MC_PAGE_DELETE_TRUE,1,0);
}

### UPDATE CATEGORY INFO

if(isset($_POST['edit']) && !empty($_POST['idCat']) && is_numeric($_POST['idCat']))
{
	$NameFolder = strip_tags($_POST['nameCat']);
	if($_POST['PathToCat'] == 1) $UpdatePath = 1;
	else $UpdatePath = 2;
	if(strlen($NameFolder) > 0)
	{
		Simple_DbApi::update_db($_GET['name_tamlates'],"name,path",$NameFolder."<><>".$UpdatePath,"id",$_POST['idCat']);
		echo CMS::AlertWindow(SIMPLE_MC_TRUE,SIMPLE_MC_DATA_TRUE,1,0);
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> внес изменения в категорию <b>'.$NameFolder.'</b> в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'-'.$_GET['page'].'-'.$_GET['id_cat'].'-'.$_GET['id_news'].'.pl"><b>'.$TitleModule.'</b></a>');
		@unlink(DATA_PATH.'modules/'.$_GET['name_tamlates'].'/cacheList');
	}
	else echo CMS::AlertWindow(SIMPLE_MC_ERROR,SIMPLE_MC_NAME_NULL,3,0);
}

### DELETE CATAGORY

if(isset($_POST['delete']) && is_numeric($_POST['idCat']))
{
	$count = Simple_DbApi::CountTable($_GET['name_tamlates'],"parent",$_POST['idCat']);
	if($count > 0) echo CMS::AlertWindow(SIMPLE_MC_ERROR,SIMPLE_MC_CAT_NOTNULL,3,0);
	else
	{
		$SelectThis=Simple_DbApi::select_db($_GET['name_tamlates'],"*","type,id","cat<><>".$_POST['idCat']."","","","","");
		$DataThis=current($SelectThis);
        CMS::$db->query("update `"._PREFIXDB_."mc` set `order`=(`order`-1) where `parent`='".$DataThis['parent']."'  and `type`='cat' and `order`>'".$DataThis['order']."'");//
		
		Simple_DbApi::delete_db($_GET['name_tamlates'],"id",$_POST['idCat']);
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> удалил категорию <b>'.$DataThis['name'].'</b> в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'.pl"><b>'.$TitleModule.'</b></a>');
		echo CMS::AlertWindow(SIMPLE_MC_TRUE,SIMPLE_MC_CAT_DELETE_TRUE,1,0);
		@unlink(DATA_PATH.'modules/'.$_GET['name_tamlates'].'/cacheList');
	}
}

$Url = "main-modules-setting-mod-".$_GET['name_tamlates']."-".$id."-1.pl";
$Url1 = "main-modules-setting-mod-".$_GET['name_tamlates']."-".$id.".pl";

echo CMS::AdminModuleSection(1,"","");

if(empty($_GET['id_cat']) || ($_GET['id_cat'] != 1 && $_GET['id_cat'] != 2))
{

	if($id == 0) $SelectCategory = Simple_DbApi::select_db($_GET['name_tamlates'],"*","type,parent","cat<><>0","order",1,"","");
	else $SelectCategory = Simple_DbApi::select_db($_GET['name_tamlates'],"*","type,parent","cat<><>".$id."","order",1,"","");

	if($id == 0) $SelectPage = Simple_DbApi::select_db($_GET['name_tamlates'],"*","type,parent","page<><>0","order",1,"","");
	else $SelectPage = Simple_DbApi::select_db($_GET['name_tamlates'],"*","type,parent","page<><>".$id."","order",1,"","");
    $scount=count($SelectCategory);
	if (!empty($SelectCategory)) {
        foreach ($SelectCategory as $i => $nc)
        {
            if($scount==1)
            {
                $toup = "";
                $todown = "";
            }
            else if($i==0)
            {
                $toup = "";
                $todown = CMS::AdminModuleSection('7-5','{idcatthis},{idcatchange}',$SelectCategory[$i]['id'].'<><>'.$SelectCategory[$i+1]['id']);
            }
            else if($i>0 && $i!=($scount-1))
            {
                $toup = CMS::AdminModuleSection('7-4','{idcatthis},{idcatchange}',$SelectCategory[$i]['id'].'<><>'.$SelectCategory[$i-1]['id']);
                $todown = CMS::AdminModuleSection('7-5','{idcatthis},{idcatchange}',$SelectCategory[$i]['id'].'<><>'.$SelectCategory[$i+1]['id']);
            }
            else
            {
                $toup = CMS::AdminModuleSection('7-4','{idcatthis},{idcatchange}',$SelectCategory[$i]['id'].'<><>'.$SelectCategory[$i-1]['id']);
                $todown = "";
            }

            $PathToEditCat1 = '';
            $PathToEditCat2 = '';

            if($SelectCategory[$i]['path'] == 1) $PathToEditCat1 = 'selected';
            else $PathToEditCat2 = 'selected';

            $list .= CMS::AdminModuleSection(5,"{name},{id},{urls},{s1},{s2},{window},{window1},{positionup},{positiondown}",$SelectCategory[$i]['name']."<><>".$SelectCategory[$i]['id']."<><>main-modules-setting-mod-".$_GET['name_tamlates']."-".$SelectCategory[$i]['id'].".pl<><>".$PathToEditCat1."<><>".$PathToEditCat2."<><>".CMS::AlertWindow('Удаление раздела раздела',CMS::AdminModuleSection('5-1','{id},{name}',$SelectCategory[$i]['id'].'<><>'.$SelectCategory[$i]['name']),2,'-'.$SelectCategory[$i]['id'])."<><>".CMS::AlertWindow('Редактирование раздела раздела',CMS::AdminModuleSection('5-2','{id},{name},{s1},{s2},{url},{url-site}',$SelectCategory[$i]['id'].'<><>'.$SelectCategory[$i]['name'].'<><>'.$PathToEditCat1.'<><>'.$PathToEditCat2.'<><>main-modules-setting-mod-'.$_GET['name_tamlates'].'-'.$id.'-2-'.$SelectCategory[$i]['id'].'.pl<><>'.URL_SITE.$_GET['name_tamlates'].'/'.$SelectCategory[$i]['id'].'/'),4,$SelectCategory[$i]['id'])."<><>".$toup."<><>".$todown);

        }
    }


	$folder = CMS::AdminModuleSection(4,"{list}",$list);
	
	$scount=count($SelectPage);
	if(!empty($SelectPage)) {
        foreach ($SelectPage as $n => $np)
        {
            if($scount==1)
            {
                $toup = "";
                $todown = "";
            }
            else if($n==0)
            {
                $toup = "";
                $todown = CMS::AdminModuleSection('7-3','{idpagethis},{idpagechange}',$SelectPage[$n]['id'].'<><>'.$SelectPage[$n+1]['id']);
            }
            else if($n>0 && $n!=($scount-1))
            {
                $toup = CMS::AdminModuleSection('7-2','{idpagethis},{idpagechange}',$SelectPage[$n]['id'].'<><>'.$SelectPage[$n-1]['id']);
                $todown = CMS::AdminModuleSection('7-3','{idpagethis},{idpagechange}',$SelectPage[$n]['id'].'<><>'.$SelectPage[$n+1]['id']);
            }
            else
            {
                $toup = CMS::AdminModuleSection('7-2','{idpagethis},{idpagechange}',$SelectPage[$n]['id'].'<><>'.$SelectPage[$n-1]['id']);
                $todown = "";
            }
            $statusPage = $SelectPage[$n]['status'] == 0 ? CMS::AdminModuleSection('5-3','{id}',$SelectPage[$n]['id']):CMS::AdminModuleSection('5-4','{id}',$SelectPage[$n]['id']);
            $pagesList .= CMS::AdminModuleSection(7,"{name},{id},{url},{window},{status},{positionup},{positiondown}",$SelectPage[$n]['name']."<><>".$SelectPage[$n]['id']."<><>main-modules-setting-mod-".$_GET['name_tamlates']."-".$id."-2-".$SelectPage[$n]['id'].".pl<><>".CMS::AlertWindow('Удаление страницы',CMS::AdminModuleSection('7-1','{id},{name}',$SelectPage[$n]['id'].'<><>'.$SelectPage[$n]['name']),2,$SelectPage[$n]['id']).'<><>'.$statusPage.'<><>'.$toup.'<><>'.$todown);
        }
    }

	$folder .= CMS::AdminModuleSection("4-1","{list}",$pagesList);

	### MAP ###

	if(!file_exists(DATA_PATH.'/modules/'.$_GET['name_tamlates'].'/cacheList') || filesize(DATA_PATH.'/modules/'.$_GET['name_tamlates'].'/cacheList') < 10)
	{
		$SelectMap = Simple_DbApi::select_db($_GET['name_tamlates'],"id,name,parent","type","cat","order",1,"","");
		$full = array();
		while($list =  array_shift($SelectMap)) $full[$list['parent']][] =  $list;
		$maps = TreeMC($full,0);
		$CacheMap = $maps;
		###########
		$f = fopen(DATA_PATH.'/modules/'.$_GET['name_tamlates'].'/cacheList','w');
		flock($f,LOCK_EX);
		fwrite($f,$CacheMap);
		flock($f,LOCK_UN);
		fclose($f);
		
		if($id == 0) $maps = preg_replace('!<a href=\"(.*?)mc-0.pl\"(.*?)>(.*?)</a>!si',"<span style=\"background:#23bde4;padding:1px;\">\\3</span>",$maps);
		else $maps = preg_replace("!<a href=\"(.*?)mc-$id.pl\"(.*?)>(.*?)</a>!i","<span style=\"background:#23bde4;padding:1px;\">\\3</span>",$maps);
	}
	else
	{

		$f = file_get_contents(DATA_PATH.'/modules/'.$_GET['name_tamlates'].'/cacheList');

		if($id == 0) $maps = preg_replace('!<a href=\"(.*?)mc-0.pl\"(.*?)>(.*?)</a>!si',"<span style=\"background:#23bde4;padding:1px;\">\\3</span>",$f);
		else $maps = preg_replace("!<a href=\"(.*?)mc-$id.pl\"(.*?)>(.*?)</a>!i","<span style=\"background:#23bde4;padding:1px;\">\\3</span>",$f);

	}
	### END ###

	echo CMS::AdminModuleSection(3,"{url},{folder},{map},{window}",$Url."<><>".$folder."<><>".$maps."<><>".CMS::AlertWindow('Создание раздела',CMS::AdminModuleSection('3-1','',''),4,'alex'));
}
else
{
	if($_GET['id_cat'] == 1)
	{
		$editor =  CMS::CKeditor($textAdd,'','');
		echo CMS::AdminModuleSection(6,"{url},{editor},{seo},{category-page},{name}",$Url1."<><>".$editor."<><>".CMS::SeoForm($keywords,$titlePage,$descPage,$alias)."<><>"."<><>".$NamePage);
	}
	else
	{
		if(is_numeric($_GET['id_news']))
		{
			$selectPage = Simple_DbApi::select_db($_GET['name_tamlates'],"*","id",$_GET['id_news'],"","","","");
			if(!empty($selectPage))
			{
				$ne = current($selectPage);
				$PathToSection1 = '';
				$PathToSection2 = '';
				if($ne['path'] == 2) $PathToSection2 = 'selected';
				else $PathToSection1 = 'selected';
				
				$NameTex = $ne['type'] == 'cat' ? 'категории' : 'страницы';
				if($ne['type'] == 'cat') @unlink(DATA_PATH.'modules/'.$_GET['name_tamlates'].'/cacheList');
				
				$ne['content'] = str_replace("\\","",$ne['content']); 
				
				$editor =  CMS::CKeditor($ne['content'],'','');		
				$seo = CMS::SeoForm($ne['title'],$ne['keyword'],$ne['desc'],CMS::AliasSelect('page/cat','mc/'.$ne['id']));
				
				if($ne['type'] == 'page') $categoryList = CMS::AdminModuleSection('8-1','{option}',CategoryList(0,'',$ne['parent']));
				$pageCategory = $ne['type'] == 'page' ? $categoryList : '';		
				
				echo CMS::AdminModuleSection(8,"{url},{editor},{name},{id},{key},{s1},{s2},{seo},{nameText},{category-page}",$Url1."<><>".$editor."<><>".$ne['name']."<><>".$ne['id']."<><>".$ne['keyword']."<><>".$PathToSection1."<><>".$PathToSection2."<><>".$seo."<><>".$NameTex."<><>".$pageCategory);
			}
			else echo SIMPLE_NOT_PAGE;
		}
		else echo SIMPLE_NOT_PAGE;
	}

}

$select1 = '';
$select2 = '';
$select3 = '';
$select4 = '';
$select5 = '';
$select6 = '';

if($fansybox == 1) $select1 = 'selected';
else $select2 = 'selected';
if($path == 1) $select3 = 'selected';
else $select4 = 'selected';
if($textfansy == 1) $select5 = 'selected';
else $select6 = 'selected';

if(CMS::TestOnJsLib('jquery') != 1) $JsLib = CMS::AdminModuleSection(15,'','');
else
{
	if(CMS::TestOnJsLib('fancybox') != 1) $JsLib = CMS::AdminModuleSection(14,'','');
	else $JsLib = '';
}

if(!isset($_GET['id_cat']))
{
	if(file_exists(DATA_PATH.'/modules/'.$_GET['name_tamlates'].'/main')) $ReadMainFile = file_get_contents(DATA_PATH.'/modules/'.$_GET['name_tamlates'].'/main');
	$edit1 = CMS::CKeditor($ReadMainFile,'200','');
	$window = CMS::AlertWindow('style="width:800px;margin-left:-400px;"',CMS::AdminModuleSection(20,'{editor}',$edit1),5,-1);
	echo CMS::AdminModuleSection(2,'{p1},{p2},{p3},{p4},{p5},{p6},{js},{window}',$select1.'<><>'.$select2.'<><>'.$select3.'<><>'.$select4.'<><>'.$select5.'<><>'.$select6.'<><>'.$JsLib.'<><>'.$window);
}


?>