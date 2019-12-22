<?php

function Simple_Search_Text($text, $FullText)
{
	$text = str_replace('/','\/',$text);
	if(preg_match('/'.$text.'/i',$FullText)) return true;
	else return false;
}

function AliasHandler($url)
{
	$alias=trim($_SERVER['REQUEST_URI'],'/');
	$selectalias = CMS::$db->query("select * from `aliases` where `name`='".$alias."' and `name`!='' ");
	if(!empty($selectalias))
	{
		$da=curent($selectalias);
		$names=explode('/',$da['varnames']);
		$values=explode('/',$da['varvalues']);
		foreach($names as $index=>$value){$_GET[$value]=$values[$index];}
	}
}

function AliasGetName($vars,$values)
{
	$ret='';
	$selectalias = CMS::$db->query("select * from `aliases` where `varnames`='".$vars."' and `varvalues`='".$values."' ");
	if (count($selectalias) >0 ) {
	    $ret=current($selectalias)['name'];
	}
	return $ret;
}

function AliasFromUrl($url)
{
    $testalias = $ret = '';
	$testurl='';
	if(strstr($url,'//:'))
	{
		$testurl=explode('//',$url);
		$testurl=explode('/',$testurl[1]);
		unset($testurl[0]);
	}
	else $testurl=explode('/',$url);
	$testvars=array();
	if(count($testurl)>=1) foreach($testurl as $value){if(!strstr($value,'//:') && $value!='')$testvars[]=$value;}
	if(count($testvars)==2){$testalias=AliasGetName('page/cat',$testvars[0].'/'.$testvars[1]);}
	if($testalias!='') $ret=$testalias;
	return $ret;
}

?>