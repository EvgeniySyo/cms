<?php

class ModuleNews
{
	public static function SelectTextSmall($text)
	{
		if(!CMS::TestData("<!-- pagebreak -->", $text, 2))
		{
			$smallText = $text;
		}
		else
		{
			list($a,$b) = explode('<!-- pagebreak -->',$text,2);
			$smallText = $a;
		}
		return $smallText;
	}
}

?>