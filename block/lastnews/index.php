<?php
if(!file_exists(DATA_PATH."cache/block/".md5($BlockName)))
{
	$DateList = array("Января","Февраля","Марта","Апреля","Мая","Июня","Июля","Августа","Сентября","Октября","Ноября","Декабря");

	function SubStrWord($string, $length, $symbols)
	{
		$string = trim($string);
		$string = str_replace("\n", "<br>", $string);
		$words = explode(' ', $string);
		$str = '';

		foreach ($words as $value):
			if ((strlen($str) + strlen($value)) > $length):
				$str .= $symbols;
				break;
			endif;
			$str .= ' '.$value;
		endforeach;

		//$str .= $symbols;
		return $str;
	}

	CMS::CoreComponent('module');
	if(Simple_Module::TestInstall('news') == true && isset($_GET['ppp']))
	{
		include(DATA_PATH."modules/news/config.php");

		//echo $PageBreak."---".$TimePublic;
		if($TimePublic)
		{
			//$datenum = date("Y-m-j");
			$SelectionOption1="WHERE ( `dateend` ='0000-00-00' OR ((`dateend` > CURDATE() AND `date` < CURDATE())
			OR ( ( `dateend`=CURDATE() AND `timeend`>CURTIME() AND `date`=CURDATE() AND `time`<CURTIME() ) OR (`dateend` > CURDATE() AND `date`=CURDATE() AND `time`<CURTIME()) OR (`date` < CURDATE() AND `dateend`=CURDATE() AND `timeend`>CURTIME()) ))  ) ";
			$SelectionOption2=" AND ( `dateend` ='0000-00-00' OR ((`dateend` > CURDATE() AND `date` < CURDATE())
			OR ( ( `dateend`=CURDATE() AND `timeend`>CURTIME() AND `date`=CURDATE() AND `time`<CURTIME() ) OR (`dateend` > CURDATE() AND `date`=CURDATE() AND `time`<CURTIME()) OR (`date` < CURDATE() AND `dateend`=CURDATE() AND `timeend`>CURTIME()) ))  ) ";
		}

		if($IdCat == 0) $cat = '';
		else $cat = $IdCat;

		if(is_numeric($cat)) $SelectNews = Simple_DbApi::query_db("SELECT * FROM `"._PREFIXDB_."news` WHERE `id_cat` = 1  ".$SelectionOption2."ORDER BY `date` DESC, `time` DESC LIMIT 0, $LastNewsView");
		else $SelectNews = Simple_DbApi::query_db("SELECT * FROM `"._PREFIXDB_."news` ".$SelectionOption1."ORDER BY `date` DESC, `time` DESC LIMIT 0, $LastNewsView");

		if (!empty($SelectNews)) {
            foreach ($SelectNews as $i => $nn)
            {
                $IdThisNews = $i+1;
                list($year,$month,$day) = explode("-",$nn['date'],3);
                $DateFormat = $day." ".$DateList[($month-1)]." ".$year;
                $DateFormat1 = $day."/".$month."/".$year;
                $DateFormat2 = $nn['time'];

                if(!is_numeric($cat)) $url = URL_SITE.'news/0/in/0/0/'.$nn['id'].'/';
                else $url = URL_SITE.'news/0/in/'.$nn['id_cat'].'/0/'.$nn['id'].'/';

                $image = '';
                if($ImageNews == 1)
                {
                    if ($nn['type']!='')
                    {
                        $imgPath = 'images/news/small/'.$nn['id'].'.'.$nn['type'];
                        if(file_exists($imgPath)) $image = CMS::Section_Block_List(28, "{url},{image}", $url."<><>".$imgPath);
                    }
                    else
                    {
                        $nn['full'] = CMS::ClearContent($nn['full']);
                        preg_match("!src=\"(.*?)\"!si",$nn['full'],$match);
                        if(!empty($match[1])) $image = CMS::Section_Block_List(30,'{url},{image}',$url.'<><>'.$match[1]);
                        //	else $image = CMS::Section_Block_List(28, "{url},{image}", $url."<><>".URL_SITE_THEME.'/images/news.jpg');
                    }
                }

                $text = strip_tags($nn['small']);
                $text = SubStrWord($text,$Numbers,'...');

                if($Type == 0)
                {
                    $info .= CMS::Section_Block_List(1,'{url},{name},{date},{date1},{date2},{idthisnews},{image},{text}',$url.'<><>'.$nn['name'].'<><>'.$DateFormat.'<><>'.$DateFormat1.'<><>'.$DateFormat2.'<><>'.$IdThisNews.'<><>'.$image.'<><>'.$text);
                }
                else if($Type == 1 || $Type == 2)
                {
                    if($Type == 1) $info .= CMS::Section_Block_List(2,'{url},{date},{date1},{date2},{text},{image},{name},{idthisnews}',$url.'<><>'.$DateFormat.'<><>'.$DateFormat1.'<><>'.$DateFormat2.'<><>'.$text.'<><>'.$image.'<><>'.$nn['name'].'<><>'.$IdThisNews);
                    else $info .= CMS::Section_Block_List(3,'{url},{date},{date1},{date2},{text},{image},{name},{idthisnews}',$url.'<><>'.$DateFormat.'<><>'.$DateFormat1.'<><>'.$DateFormat2.'<><>'.$text.'<><>'.$image.'<><>'.$nn['name'].'<><>'.$IdThisNews);
                }
            }
        }


		$infoEnter = CMS::Section_Block_List(5,'{text}',$info);
		$block .= $infoEnter;
		CMS::BlockInsertCacheData(md5($BlockName),$infoEnter);
	}
}
else $block .= file_get_contents(DATA_PATH."cache/block/".md5($BlockName));
?>