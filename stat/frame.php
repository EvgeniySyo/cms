<?php
###TEST###
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
session_start();
if(!isset($_SESSION["login_admin"])) exit();
function Simple_Search_Text($FullText,$text)
{
	$text = str_replace('/','\/',$text);
	if(preg_match('/'.$text.'/i',$FullText)) return true;
	else return false;
}
$UrlSite = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$UrlSite = substr($UrlSite,0,(strlen($UrlSite)-9));
$DateTest = $_GET['id_date'];
list($t1,$t2,$t3) = explode(".",$DateTest,3);
if(!is_numeric($t1) || $t1 > 31 || $t1 < 1) exit;
if(!is_numeric($t2) || $t2 > 12 || $t2 < 1) exit;
if(!is_numeric($t3)) exit;
include("../includes/path.php");
define("DATA_PATH",$data_path);
include(DATA_PATH."config.php");
define("_SERVEDB_",$db["server"]); // server db
define("_PORTDB_",$db["port"]); // port db
define("_LOGINDB_",$db["login"]); // login db
define("_PASSDB_",$db["password"]); // password db
define("_DATABASE_",$db["data_base"]); // database db
define("_PREFIXDB_",$db["prefix"]); // prefix db
include("../includes/class.php");
CMS::CoreComponent('erorr');
CMS::CoreComponent('js');
CMS::CoreComponent('db');
$Db = new Simple_DbApi();
$Db->connect_db();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<title>Статистика</title> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /> 
<link type="text/css" rel="stylesheet" href="../templates/admin/js_admin/frame.css"/> 
<link type="text/css" rel="stylesheet" href="../templates/admin/js_admin/visualize.jQuery.css"/>
<style type="text/css">
.mainTopP {
font-size: 22px;
border-bottom: 2px #23BDE4 solid;
}
a { color:blue;}
.tablelist th {
background: #687EAB;
border-bottom: 2px solid white;
height: 40px;
font-weight: normal;
text-transform: normal;
text-align: left;
padding-left: 5px;
color: white;
}
</style>
 <!--[if IE]>
  <script type="text/javascript" src=".../templates/admin/js_admin/excanvas.compiled.js"></script>
 <![endif]-->
 <script type="text/javascript" src="../templates/admin/js_admin/jqmin.js"></script>
<script type="text/javascript" src="../templates/admin/js_admin/visualize.jQuery.js"></script>

<script type="text/javascript">
$(function(){
	$('#tablestat').visualize({type: 'area'});
});
</script>
</head>
<body>
<?php
include('../includes/bot.php');
list($d,$m,$y) = explode('.',$_GET['id_date'],3);
$d = $d < 10 ? '0'.$d:$d;
$m = $m < 10 ? '0'.$m:$m;
$select_hit = $Db->select_db("statistics","*","date",$_GET['id_date'],"id",2,"","");
$select_bot = $Db->query_db("SELECT * FROM `"._PREFIXDB_."statistics_bot` WHERE `date` LIKE '%".$y."-".$m."-".$d."%' GROUP BY agent");
if (!empty($select_bot)) {
    foreach ($select_bot as $b => $nb)
    {
        for ($i=0;$i<count($base_bot);$i++)
        {
            if(Simple_Search_Text($nb['agent'],$base_bot[$i]))
            {
                $BotNAme .= "<b>".$base_bot[$i]."</b> ";
            }
        }
    }
}

$select_hits = $Db->select_db("hits","*","date",$_GET['id_date'],"","","","");
$notauth = 0;
if(!empty($select_hits))
{
    $hhh = current($select_hits);
    $a = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0,14=>0,15=>0,16=>0,17=>0,18=>0,19=>0,20=>0,21=>0,22=>0,23=>0);
	$user = 0;
	$go_in_site = 0;
	foreach ($select_hit as $t => $nf)
	{
		list($hour,$minute,$second) = explode(":",$nf['time'],3);

		if($hour == 0) {$a[0] = $a[0] + 1;$a1[0] = $hhh['h1']-1;}
		if($hour == 1) {$a[1] = $a[1] + 1;$a1[1] = $hhh['h2']-1;}
		if($hour == 2) {$a[2] = $a[2] + 1;$a1[2] = $hhh['h3']-1;}
		if($hour == 3) {$a[3] = $a[3] + 1;$a1[3] = $hhh['h4']-1;}
		if($hour == 4) {$a[4] = $a[4] + 1;$a1[4] = $hhh['h5']-1;}
		if($hour == 5) {$a[5] = $a[5] + 1;$a1[5] = $hhh['h6']-1;}
		if($hour == 6) {$a[6] = $a[6] + 1;$a1[6] = $hhh['h7']-1;}
		if($hour == 7) {$a[7] = $a[7] + 1;$a1[7] = $hhh['h8']-1;}
		if($hour == 8) {$a[8] = $a[8] + 1;$a1[8] = $hhh['h9']-1;}
		if($hour == 9) {$a[9] = $a[9] + 1;$a1[9] = $hhh['h10']-1;}
		if($hour == 10) {$a[10] = $a[10] + 1;$a1[10] = $hhh['h11']-1;}
		if($hour == 11) {$a[11] = $a[11] + 1;$a1[11] = $hhh['h12']-1;}
		if($hour == 12) {$a[12] = $a[12] + 1;$a1[12] = $hhh['h13']-1;}
		if($hour == 13) {$a[13] = $a[13] + 1;$a1[13] = $hhh['h14']-1;}
		if($hour == 14) {$a[14] = $a[14] + 1;$a1[14] = $hhh['h15']-1;}
		if($hour == 15) {$a[15] = $a[15] + 1;$a1[15] = $hhh['h16']-1;}
		if($hour == 16) {$a[16] = $a[16] + 1;$a1[16] = $hhh['h17']-1;}
		if($hour == 17) {$a[17] = $a[17] + 1;$a1[17] = $hhh['h18']-1;}
		if($hour == 18) {$a[18] = $a[18] + 1;$a1[18] = $hhh['h19']-1;}
		if($hour == 19) {$a[19] = $a[19] + 1;$a1[19] = $hhh['h20']-1;}
		if($hour == 20) {$a[20] = $a[20] + 1;$a1[20] = $hhh['h21']-1;}
		if($hour == 21) {$a[21] = $a[21] + 1;$a1[21] = $hhh['h22']-1;}
		if($hour == 22) {$a[22] = $a[22] + 1;$a1[22] = $hhh['h23']-1;}
		if($hour == 23) {$a[23] = $a[23] + 1;$a1[23] = $hhh['h24']-1;}

		if(!empty($nf['agent']))
		{
			if(Simple_Search_Text($nf['agent'],"windows")) if(!Simple_Search_Text($os,"windows")) $os .= "Windows ";
			if(Simple_Search_Text($nf['agent'],"linux")) if(!Simple_Search_Text($os,"linux")) $os .= "Linux ";
			if(Simple_Search_Text($nf['agent'],"Android")) if(!Simple_Search_Text($os,"Android")) $os .= "Android ";

			if(!empty($nf['came']))
			{
				$DecodingUrl = urldecode($nf['came']);
				$search = false;
				preg_match("#([\w\-\.]+\.[\w]{2,4})(/.*)?$#", $nf['came'], $url_clear);
				if(Simple_Search_Text($url_clear[1],'yandex.') && $url_clear[1]  != 'yaca.yandex.ru')
				{
					$Cames = $DecodingUrl;
					list($s1,$s2) = explode("text=",$Cames,2);
					list($s3,$s4) = explode("&",$s2,2);
					$s3 = mb_substr($s3,0,70);
					$Urls = "<a target=\"_blank\" href=\"".$nf['came']."\">".$s3."</a>";
					$search = true;
				}
				elseif (Simple_Search_Text($url_clear[1],'google.'))
				{
					$Cames = $DecodingUrl;

					list($s1,$s2) = explode("&url=",$Cames,2);
					list($s3,$s4) = explode("&",$s2,2);
					$s3 = substr($s3,0,70);

					$Urls = "<a target=\"_blank\" href=\"".$nf['came']."\">".$s3."</a>";
					$search = true;
				}
				elseif (Simple_Search_Text($url_clear[1],'nigma.'))
				{
					$Cames = $DecodingUrl;
					list($s1,$s2) = explode("&s=",$Cames,2);

					list($s3,$s4) = explode("&",$s2,2);
					$s3 = mb_substr($s3,0,70);
					$Urls = "<a target=\"_blank\" href=\"".$nf['came']."\">".$s3."</a>";
					$search = true;
				}
				elseif (Simple_Search_Text($url_clear[1],'rambler.'))
				{
					$Cames = $DecodingUrl;
					list($s1,$s2) = explode("&query=",$Cames,2);
					list($s3,$s4) = explode("&",$s2,2);
					$s3 = substr($s3,0,70);
					$Urls = "<a target=\"_blank\" href=\"".$nf['came']."\">".$s3."</a>";
					$search = true;
				}
				else if(Simple_Search_Text($url_clear[1],"search.qip.ru"))
				{
					$Cames = $DecodingUrl;
					list($s1,$s2) = explode("?query=",$Cames,2);
					list($s3,$s4) = explode("&",$s2,2);
					$s3 = substr($s3,0,70);
					$Urls = "<a target=\"_blank\" href=\"".$nf['came']."\">".$s3."</a>";
					$search = true;
				}
				else if(Simple_Search_Text($url_clear[1],"mail."))
				{
					$Cames = $DecodingUrl;
					list($s1,$s2) = explode("q=",$Cames,2);
					list($s3,$s4) = explode("&",$s2,2);
					$s3 = mb_substr($s3,0,70);
					$Urls = "<a target=\"_blank\" href=\"".$nf['came']."\">".$s3."</a>";
					$search = true;
				}
				else
				{
					$urlSub = mb_substr($nf['came'],0,70);
					$Urls = "<a target=\"_blank\" href=\"".$nf['came']."\">".urldecode($urlSub)."</a>";
				}
				if($search == false)
				{
					if(!Simple_Search_Text($UrlSite,$url_clear[1]))
					{
						$list_url .= "<tr><th>".$url_clear[1]."</td><th style=\"text-align:left;\">".$Urls."</td><th>".$nf['time']."</th></tr>";
					}
				}
				else
				{
					$list_url_search .= "<tr><th>".$url_clear[1]."</td><th style=\"text-align:left;\">".$Urls."</td><th>".$nf['time']."</th></tr>";
				}
			}
			$user++;
		}
		else $notauth++;
	}

?>
<table id="tablestat" style="width:600px;">
	<caption>Статистика за <b><?=$_GET['id_date']?></b></caption>
	<thead>
		<tr>
			<td></td>
			<th>0</th>
			<th>1</th>
			<th>2</th>
			<th>3</th>
			<th>4</th>
			<th>5</th>
			<th>6</th>
			<th>7</th>
			<th>8</th>
			<th>9</th>
			<th>10</th>
			<th>11</th>
			<th>12</th>
			<th>13</th>
			<th>14</th>
			<th>15</th>
			<th>16</th>
			<th>17</th>
			<th>18</th>
			<th>19</th>
			<th>20</th>
			<th>21</th>
			<th>22</th>
			<th>23</th>
		</tr>
	</thead>
	<tbody>
	<tr>
			<th>Количество просмотров(часы)</th>
			<td><?=$hhh['h1']; ?></td>
			<td><?=$hhh['h2']; ?></td>
			<td><?=$hhh['h3']; ?></td>
			<td><?=$hhh['h4']; ?></td>
			<td><?=$hhh['h5']; ?></td>
			<td><?=$hhh['h6']; ?></td>
			<td><?=$hhh['h7']; ?></td>
			<td><?=$hhh['h8']; ?></td>
			<td><?=$hhh['h9']; ?></td>
			<td><?=$hhh['h10']; ?></td>
			<td><?=$hhh['h11']; ?></td>
			<td><?=$hhh['h12']; ?></td>
			<td><?=$hhh['h13']; ?></td>
			<td><?=$hhh['h14']; ?></td>
			<td><?=$hhh['h15']; ?></td>
			<td><?=$hhh['h16']; ?></td>
			<td><?=$hhh['h17']; ?></td>
			<td><?=$hhh['h18']; ?></td>
			<td><?=$hhh['h19']; ?></td>
			<td><?=$hhh['h20']; ?></td>
			<td><?=$hhh['h21']; ?></td>
			<td><?=$hhh['h22']; ?></td>
			<td><?=$hhh['h23']; ?></td>
			<td><?=$hhh['h24']; ?></td>
		</tr>
		<tr>
			<th>Количество пользователей(часы)</th>
			<td><?=$a[0]; ?></td>
			<td><?=$a[1]; ?></td>
			<td><?=$a[2]; ?></td>
			<td><?=$a[3]; ?></td>
			<td><?=$a[4]; ?></td>
			<td><?=$a[5]; ?></td>
			<td><?=$a[6]; ?></td>
			<td><?=$a[7]; ?></td>
			<td><?=$a[8]; ?></td>
			<td><?=$a[9]; ?></td>
			<td><?=$a[10]; ?></td>
			<td><?=$a[11]; ?></td>
			<td><?=$a[12]; ?></td>
			<td><?=$a[13]; ?></td>
			<td><?=$a[14]; ?></td>
			<td><?=$a[15]; ?></td>
			<td><?=$a[16]; ?></td>
			<td><?=$a[17]; ?></td>
			<td><?=$a[18]; ?></td>
			<td><?=$a[19]; ?></td>
			<td><?=$a[20]; ?></td>
			<td><?=$a[21]; ?></td>
			<td><?=$a[22]; ?></td>
			<td><?=$a[23]; ?></td>
		</tr>
	</tbody>
</table>
<br />
<br />
<table style="width:650px;">
<tr>
<th>Всего пользователей:</th><th><?=$user; ?></th>
</tr>
<tr>
<th>Не определено:</th><th><?=$notauth; ?></th>
</tr>
<tr>
<th>Всего хитов:</th><th><?=$hhh['hits']; ?></th>
</tr>
<tr>
<th>Ботов посетило:</th><th><?=$bot; ?></th>
</tr>
</table>
<br />
<? if(!empty($BotNAme)) {?>
<p class="mainTopP">Список посетивших ботов:</p>
<? echo $BotNAme; }?>

<?php if(isset($list_url_search)) { ?>
<p class="mainTopP">Переходы с поисковых систем:</p>
<table width="100%">
<tr>
<th style="width:150px">Поиисковая система:</th><th style="text-align:left;">Запрос:</th><th>Время:</th>
</tr>
<?=$list_url_search?>
</table>
<?php } ?>
<?php if(isset($list_url)) { ?>
<p class="mainTopP">Переходы с сайтов:</p>
<table width="100%">
<tr>
<th style="width:150px">Сайт:</th><th style="text-align:left;">URL перехода:</th><th>Время:</th>
</tr>
<?=$list_url?>
</table>
<?php } ?>
<p class="mainTopP">Операционные системы:</p>
<b><?=$os?></b>
<?php
}
else echo "Статистика за данное число отсутствует";
?>
</body>
</html>