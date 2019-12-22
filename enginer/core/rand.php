<?php
class RandText
{
	private $max = 6;
	
	public function GoText($max)
	{
		$symbol = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','y','w','v','z','0','1','2','3','4','5','6','7','8','9');
		$count = count($symbol);
		$CountS = !empty($max) ? $max : $this->max;	
		for ($i=0;$i<$max;$i++)
		{
			$SymbolNumber = rand(0,$count);
			$text .= $symbol[$SymbolNumber];
		}
		
		return $text;
	}
}
?>