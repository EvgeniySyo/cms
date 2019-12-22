<?php

class simple_lib_js
{
	public function InsertJs()
	{
		$FileJs = file(DATA_PATH."/jslib");
        $start = $JsFormat = '';
		for ($i=0;$i<count($FileJs);$i++)
		{
            $status = 0;
		    if(!empty($FileJs[$i]))
			{
				if(file_exists('js_lib/'.trim($FileJs[$i]).'/lib.php')) include('js_lib/'.trim($FileJs[$i]).'/lib.php');
				if($status == 1) $start .= $Js;
				else $JsFormat .= $Js;
			}
			unset($Js);
			unset($status);
		}
		$LibFormat = $start.$JsFormat;
		return $LibFormat;
	}
}

?>