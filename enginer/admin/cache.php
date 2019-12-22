<?php

include("includes/js.php");

function ScanCachePro($p,$s)
{
	$scan = scandir($p);
	for ($i=2;$i<count($scan);$i++)
	{
		if(is_file($p."/".$scan[$i])) $size = (filesize($p."/".$scan[$i]))+$size+$s;
		else $size = ScanCachePro($p."/".$scan[$i],$size)+$size;
	}
	
	return $size;
}

if(isset($_POST['cacheA'])) CMS::DestroyCacheAlls("cache");
if(isset($_POST['cacheM'])) CMS::DestroyCacheAlls("cache/module");
if(isset($_POST['cacheB'])) CMS::DestroyCacheAlls("cache/block");

$TemplatesSection = CMS::SectionFile('Cache');

echo CMS::SectionAdmin($TemplatesSection,1,"","");

$AllsCache = round(ScanCachePro(DATA_PATH."cache/",0)/1024);
$ModuleCache = round(ScanCachePro(DATA_PATH."cache/module/",0)/1024);
$BlockCache = round(ScanCachePro(DATA_PATH."cache/block/",0)/1024);

$pr = $AllsCache/100;

$WidthM = @floor($ModuleCache/$pr);
$WidthB = @floor($BlockCache/$pr);




echo CMS::SectionAdmin($TemplatesSection,3,"%N%,%N1%,%N2%,%W%,%W1%,%W3%,%W4%",$AllsCache."<><>".$ModuleCache."<><>".$BlockCache."<><>".($WidthM+15)."<><>".($WidthB+15)."<><>".$WidthM."<><>".$WidthB."");



echo CMS::SectionAdmin($TemplatesSection,2,"","");

?>