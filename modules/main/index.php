<?php
$content = '';
if(CMS::TestCacheFile('start') != 1)
{
	$page = Simple_DbApi::select_db("mc","*","id",$id_pages,"","","","")[0];
    $page['content'] = str_replace("\\","",$page['content']);
	$content .= $page['content'];
	$title_go = $page['name'];

	$metaTitle = !empty($page['title']) ? $page['title'] : $title_go;
	$metaKey = $page['keyword'];
	$metaDesc = $page['desc'];

	CMS::CreatContentCacheFile($title_go,$content,$metaTitle,$metaKey,$metaDesc,'start');
}
else
{
	$ReadCache = CMS::ReadCacheModule('start');
	$title_go = CMS::TitleCache($ReadCache);
	$x = str_replace("\\","",CMS::ContentCache($ReadCache));
	$content .= $x;

	$metaTitle = !empty($nt['title']) ? $nt['title'] : $title_go;
	$metaKey = $nt['keyword'];
	$metaDesc = $nt['desc'];

	$metaTitle = CMS::MetaTitleCache($ReadCache);
	$metaKey = CMS::MetaKeyCache($ReadCache);
	$metaDesc = CMS::MetaDescCache($ReadCache);
}
?>