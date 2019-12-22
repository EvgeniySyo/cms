<?php
###############################################################################################
################################## simple CMS LIBRARY v. 1.0 WE ###############################
function cmp ($a, $b)
{
	return strcmp($a["q"], $b["q"]);
}
function utf2win($str)
{
	for ($i = 144; $i <= 191; $i++)
	$cp1251[chr(208).chr($i)] = chr(192 + ($i - 144));
	for ($i = 128; $i <= 143; $i++)
	$cp1251[chr(209).chr($i)] = chr(240 + ($i - 128));
	$cp1251[chr(208).chr(129)] = 'Ё';
	$cp1251[chr(209).chr(145)] = 'ё';
	$cp1251[chr(208).chr(129)] = 'Ё';
	$cp1251[chr(209).chr(145)] = 'ё';
	$tmp = "";
	for ($i = 1, $len = strlen($str); $i < $len; $i++)
	{
		$c1 = $str[$i - 1];
		$c2 = $str[$i];

		if ($c1 == chr(208) || $c1 == chr(209))
		{
			$c = $cp1251[$c1.$c2];
			$tmp .= (isset($c) ? $c : "?");
			$i++;
		}
		else
		$tmp .= $c1;
	}
	return $tmp;
}

function simple_image_save($w,$h,$path,$name,$types,$parametr)
{
	$types = strtolower($types);
	$file_name = $name;
	$smallimage = $path;
	$ratio = $w/$h;
	$size_img = getimagesize($file_name);
	$src_ratio = $size_img[0]/$size_img[1];
	if($parametr == 1) 
	{
		if($size_img[0] > $w || $size_img[1] > $h)
		{
			if($ratio<$src_ratio) $h = $w/$src_ratio; else $w = $h*$src_ratio;
		}
		else
		{
			$w = $size_img[0];
			$h = $size_img[1];
		}
	}
	$dest_img = imagecreatetruecolor($w,$h);
	if($types == "png")
	{
		$src_img = imagecreatefrompng($file_name);
		imagesavealpha($dest_img, true);
		$png=imagecolorallocatealpha($dest_img,0,0,0,127);
		imagefill($dest_img, 0, 0, $png);
	}
	if($types == "jpg" || $types == "jpeg") $src_img = imagecreatefromjpeg($file_name);
	if($types == "gif")
	{
		$src_img = imagecreatefromgif($file_name);
		$gif = imagecolorat($dest_img,0,0);
		imagecolortransparent($dest_img,$gif);
	}
	if(!imagecopyresampled($dest_img,$src_img,0,0,0,0,$w,$h,$size_img[0],$size_img[1])) return false;
	if($types == "png") imagepng($dest_img,$smallimage);
	if($types == "jpg" || $types == "jpeg") imagejpeg($dest_img,$smallimage,100);
	if($types == "gif") imagegif($dest_img,$smallimage);
	imagedestroy($dest_img);
	imagedestroy($src_img);
}
function simple_imagetest_format($path,$parametr)
{
	$ttt = true;
	$testing = pathinfo($path);
	if($parametr == 1)
	{
		if($testing["extension"] == "jpg" || $testing["extension"] == "png" || $testing["extension"] == "gif" || $testing["extension"] == "jpeg" || $testing["extension"] == "JPEG" || $testing["extension"] == "GIF" || $testing["extension"] == "JPG" || $testing["extension"] == "GIF") $ttt = true;
		else $ttt = false;
	}
	else $ttt = strtolower($testing["extension"]);
	return $ttt;
}

function detect_utf ($s){
	$s=urlencode ($s); // в некоторых случаях — лишняя операция (закоментируйте)
	$res='0';
	$j=strlen ($s);

	$s2=strtoupper ($s);
	$s2=str_replace ("%D0",'',$s2);
	$s2=str_replace ("%D1",'',$s2);
	$k=strlen ($s2);

	$m=1;
	if ($k>0){
		$m=$j/$k;
		if (($m>1.2)&&($m<2.2)){ $res='1'; }
	}
	return $res;
}

function OtherTemplates($default)
{
		$theme = $default;
		if($_SERVER['REQUEST_URI'] != '/')
	    {
		     $UrlSite = $_SERVER['REQUEST_URI'];
		     list($site,$PagesSite) = explode('/',$_SERVER['REQUEST_URI'],2);
	    }
		else $PagesSite = 'index.html';
		
		$PagesSite = $PagesSite[0] != '/' ? '/'.$PagesSite : $PagesSite;
		$PagesSite = $PagesSite;	
		$PagesSite = str_replace('/', '%', $PagesSite);
		
		if(file_exists(DATA_PATH.'templates/page-'.$PagesSite.'.oth'))
		{
				$theme = file_get_contents(DATA_PATH.'templates/page-'.$PagesSite.'.oth');
				$theme = trim($theme);
				if(empty($theme)) $theme = $default;
		}
		if(file_exists(DATA_PATH.'templates/module-'.$_GET['page'].'.oth'))
		{
				$theme = file_get_contents(DATA_PATH.'templates/module-'.$_GET['page'].'.oth');
				$theme = trim($theme);
				if(empty($theme)) $theme = $default;
		}		
		#module $_GET['page']
		return $theme;
}

?>