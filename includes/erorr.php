<?php
class Simple_Error
{
	static function Test_File_Insite($file,$text)
	{
		if(!file_exists($file)) Simple_Theme_Work::Theme_Error($text);
	}
}

class Simple_Theme_Work
{
    static function Theme_Error($text)
	{
		include("pro100/error.php");
		exit;
	}
}


?>