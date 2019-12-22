<?php
class simple_statistic
{
	public function __construct()
	{
		include('includes/bot.php');
		if(STATSITE == "on" )
		{
            $TimeFormat = '';
		    $date_stat = date("j.n.Y");
			$date_time = date("H:i:s");
			$date_h = date("H")+1;
			$refer_on = getenv("HTTP_REFERER");
			$server_ip = $_SERVER['HTTP_HOST'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$agent = $_SERVER['HTTP_USER_AGENT'];
			$h = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$h[$date_h-1] = 1;

			$select_date = Simple_DbApi::CountTable('statistics','date,ip',$date_stat.'<><>'.$ip);
			$FindBot = false;
			foreach ($base_bot as $id=>$name)
			{
				if(Simple_Search_Text($name,$agent))
				{
					$FindBot = true;
					break;
				}
			}


			if($select_date == 0)
			{
				if($FindBot == true)
				{
					Simple_DbApi::insert_db("statistics_bot","date,ip,agent","".date('Y-m-d H:i:s')."<><>".$ip."<><>".$agent);
				}
				else
				{
					Simple_DbApi::insert_db("statistics","date,ip,came,agent,time","".$date_stat."<><>".$ip."<><>".$refer_on."<><>".$agent."<><>".$date_time."");

					if(!file_exists(DATA_PATH."stat_cache/".$date_stat))
					{
						if(!is_dir(DATA_PATH."stat_cache")) mkdir(DATA_PATH."stat_cache");
						$DateCache = fopen(DATA_PATH."stat_cache/".$date_stat,"w");
						flock($DateCache,LOCK_EX);
						for ($j=0;$j<24;$j++)
						{
							$TimeFormat .= $h[$j]."::";
						}
						fwrite($DateCache,$TimeFormat.(1));
						flock($DateCache,LOCK_UN);
						fclose($DateCache);

						Simple_DbApi::insert_db("hits","id,date,hits,h1,h2,h3,h4,h5,h6,h7,h8,h9,h10,h11,h12,h13,h14,h15,h16,h17,h18,h19,h20,h21,h22,h23,h24","<><>".$date_stat."<><>1<><>".(0+$h[0])."<><>".(0+$h[1])."<><>".(0+$h[2])."<><>".(0+$h[3])."<><>".(0+$h[4])."<><>".(0+$h[5])."<><>".(0+$h[6])."<><>".(0+$h[7])."<><>".(0+$h[8])."<><>".(0+$h[9])."<><>".(0+$h[10])."<><>".(0+$h[11])."<><>".(0+$h[12])."<><>".(0+$h[13])."<><>".(0+$h[14])."<><>".(0+$h[15])."<><>".(0+$h[16])."<><>".(0+$h[17])."<><>".(0+$h[18])."<><>".(0+$h[19])."<><>".(0+$h[20])."<><>".(0+$h[21])."<><>".(0+$h[22])."<><>".(0+$h[23])."");
						$GLOBALS['workdb'] = !isset($GLOBALS['workdb']) ? 1: $GLOBALS['workdb'] += 1;
					}
					else
					{
						$date_yes = false;
						$info = file(DATA_PATH."stat_cache/".$date_stat);
						$TimeFormats = explode("::",$info[0],26);
						$number = $TimeFormats[date("G")]+1;
						$DateCache = fopen(DATA_PATH."cache/date/".$date_stat,"w");
						flock($DateCache,LOCK_EX);
						for ($j=0;$j<24;$j++)
						{
							$hgo = "h".($j+1);
							if(date("G") == $j) $TimeFormat .= $number."::";
							else $TimeFormat .= $TimeFormats[$j]."::";
						}
						fwrite($DateCache,$TimeFormat.($TimeFormats[24]+1));
						flock($DateCache,LOCK_UN);
						fclose($DateCache);
						$FullHits = $TimeFormats[24]+1;

						if($FindBot == false)
						{
                            CMS::$db->query("UPDATE `hits` SET `hits` = '".$FullHits."', `h$date_h` = '".$number."' WHERE `date` = '".$date_stat."'");
							$GLOBALS['workdb'] = !isset($GLOBALS['workdb']) ? 1: $GLOBALS['workdb'] += 1;
						}
					}
				}
			}
			else
			{
				$date_yes = false;
				$info = file(DATA_PATH."stat_cache/".$date_stat);
				$TimeFormats = explode("::",$info[0],26);
				$number = $TimeFormats[date("G")]+1;
				$DateCache = fopen(DATA_PATH."stat_cache/".$date_stat,"w");
				flock($DateCache,LOCK_EX);
				for ($j=0;$j<24;$j++)
				{
					$hgo = "h".($j+1);
					if(date("G") == $j) $TimeFormat .= $number."::";
					else $TimeFormat .= $TimeFormats[$j]."::";
				}
				fwrite($DateCache,$TimeFormat.($TimeFormats[24]+1));
				flock($DateCache,LOCK_UN);
				fclose($DateCache);
				$FullHits = $TimeFormats[24]+1;
				if($FindBot == false)
				{
					CMS::$db->query("UPDATE `hits` SET `hits` = '".$FullHits."', `h$date_h` = '".$number."' WHERE `date` = '".$date_stat."'");
					$GLOBALS['workdb'] = !isset($GLOBALS['workdb']) ? 1: $GLOBALS['workdb'] += 1;
				}
			}
		}
	}
}
?>