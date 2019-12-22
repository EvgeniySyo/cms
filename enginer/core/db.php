<?php
class Simple_DbApi
{

	public static function select_db($table,$listros = '*',$list1 = '',$list2 = '',$id = '',$format = '',$limitS = '',$limitE = '')
	{
        $list_format = "";
	    if (!empty($list1))
		{
			$row = explode(",",$list1);
			$data = explode("<><>",$list2);
			if(count($row) != count($data)) Simple_Theme_Work::Theme_Error(SIMPLE_FORMAT." select_db");
			for($i=0;$i<count($row);$i++)
			{
				if($i == 0) $list_format .= " WHERE `".$row[$i]."`='".$data[$i]."'";
				else $list_format .=  " AND `".$row[$i]."`='".$data[$i]."'";
			}
		}
		if(!empty($id))
		{
			if($format == 1) $f = "ORDER BY `".$id."` ASC";
			else $f = "ORDER BY `".$id."` DESC";
		}
		else
		{
			if($format == 3) $f = "ORDER BY RAND()";
			else $f = "";
		}
		if(!empty($limitE)) $limit = "LIMIT ".(int)$limitS.",".(int)$limitE."";
		else $limit = "";
		if(empty($listros)) $listros = '*';
		$select = self::query("SELECT ".$listros." FROM `"._PREFIXDB_.$table."` $list_format ".$f." ".$limit." ");
		return $select;
	}

	public static function SelectDbSearch($table,$listros,$id,$format,$limitS,$limitE,$NameText,$TextSearch,$list1,$list2)
	{
		if(!empty($id))
		{
			if($format == 1) $f = "ORDER BY `".$id."` ASC";
			else $f = "ORDER BY `".$id."` DESC";
		}
		else
		{
			if($format == 3) $f = "ORDER BY RAND()";
			else $f = "";
		}
		if(empty($list1)) $list_format = "";
		else
		{
			$row = explode(",",$list1);
			$data = explode("<><>",$list2);
			if(count($row) != count($data)) Simple_Theme_Work::Theme_Error(SIMPLE_FORMAT." select_db");
			for($i=0;$i<count($row);$i++)
			{
				if($i == 0) $list_format .= " AND `".$row[$i]."`='".$data[$i]."'";
				else $list_format .=  " AND `".$row[$i]."`='".$data[$i]."'";
			}
		}
		if(!empty($limitE)) $limit = "LIMIT ".(int)$limitS.",".(int)$limitE."";
		else $limit = "";
		if(!empty($NameText)) $Like = "WHERE `".$NameText."` LIKE '%".$TextSearch."%'";
		else $Like = "";
		$select = self::query("SELECT ".$listros." FROM `"._PREFIXDB_.$table."` $Like $list_format ".$f." ".$limit." ");
		if(!$select) Simple_Theme_Work::Theme_Error(SIMPLE_SELECT_DB."SELECT ".$listros." FROM `"._PREFIXDB_.$table."` $Like ".$f." ".$limit." ");
		return $select;
	}
	public static function insert_db($table,$list_r,$list_d)
	{
		$row = explode(",",$list_r);
        $rr = $dd = '';
		for($i=0;$i<count($row);$i++)
		{
			if($i != count($row)-1) $rr .= "`".$row[$i]."` ,";
			else $rr .= "`".$row[$i]."`";
		}
		$data = explode("<><>",$list_d);
		for ($n=0;$n<count($data);$n++)
		{
			if($n != count($data)-1) $dd .= "'".$data[$n]."' ,";
			else $dd .= "'".$data[$n]."'";
		}
		@$insert = self::query("INSERT INTO `"._PREFIXDB_.$table."` ( $rr ) VALUES ($dd);");
		if(!$insert) Simple_Theme_Work::Theme_Error(SIMPLE_SELECT_DB."INSERT INTO `"._PREFIXDB_.$table."` ( $rr ) VALUES ($dd);");
		if(BUFFERSITE == "on") CMS::ClearCacheDirectory();
	}
	public static function update_db($table,$list_r,$list_d,$name_id,$id)
	{
        $date_go = '';
	    $row = explode(",",$list_r);
		$data = explode("<><>",$list_d);
		for($i=0;$i<count($row);$i++)
		{
			if($i != count($row)-1) $date_go .= "`".$row[$i]."` = '".$data[$i]."', ";
			else $date_go .= "`".$row[$i]."` = '".$data[$i]."'";
		}
		@$update = self::query("UPDATE `"._PREFIXDB_.$table."` SET $date_go WHERE `$name_id` = '$id'");
		//if(!$update) Simple_Theme_Work::Theme_Error(SIMPLE_SELECT_DB."UPDATE `$table` SET $date_go WHERE `$name_id` = '$id'");
		if(BUFFERSITE == "on" && $_SESSION['acsses_admin'] > 100) CMS::ClearCacheDirectory();
	}
	public static function delete_db($table,$id_r,$id)
	{
		$delete = self::query("DELETE FROM `"._PREFIXDB_.$table."` WHERE `".$id_r."` = '".$id."'");
		//if(!$delete) Simple_Theme_Work::Theme_Error(SIMPLE_SELECT_DB."DELETE FROM `"._PREFIXDB_.$table."` WHERE `".$id_r."` = '".$id."'");
		if(BUFFERSITE == "on") CMS::ClearCacheDirectory();
	}
	public static function auto_increment($table)
	{
        $auto = self::query("SHOW TABLE STATUS FROM `"._DATABASE_."` LIKE '"._PREFIXDB_.$table."'");
        return $auto[0]['Auto_increment'];
	}
	public static function CountTable($table,$id = '',$list = '')
	{
		$row = explode(",",$id);
		$data = explode("<><>",$list);
		if(count($row) != count($data)) Simple_Theme_Work::Theme_Error(SIMPLE_FORMAT." CountTable");
        $list_format = '';
		if($id != "")
		{
			for($i=0;$i<count($row);$i++)
			{
				if($i == 0) $list_format .= " WHERE `".$row[$i]."`='".$data[$i]."'";
				else $list_format .=  " AND `".$row[$i]."`='".$data[$i]."'";
			}
		}
		if(!empty($list_format)) $CountTable = self::query("SELECT COUNT(*) as count FROM `"._PREFIXDB_.$table."` $list_format");
		else  $CountTable = self::query("SELECT COUNT(*) as count FROM `"._PREFIXDB_.$table."`");
		if(!$CountTable) Simple_Theme_Work::Theme_Error(SIMPLE_SELECT_DB." SELECT COUNT(*) as count FROM `"._PREFIXDB_.$table."` $list_format");
		return $CountTable[0]['count'];
	}
	public static function DbDistinct($table,$listros,$id,$format,$limitS,$limitE,$NameText,$TextSearch,$list1,$list2)
	{
		if(!empty($id))
		{
			if($format == 1) $f = "ORDER BY `".$id."` ASC";
			else $f = "ORDER BY `".$id."` DESC";
		}
		else
		{
			if($format == 3) $f = "ORDER BY RAND()";
			else $f = "";
		}
		if(empty($list1)) $list_format = "";
		else
		{
			$row = explode(",",$list1);
			$data = explode("<><>",$list2);
			if(count($row) != count($data)) Simple_Theme_Work::Theme_Error(SIMPLE_FORMAT." DbDistinct");
			for($i=0;$i<count($row);$i++)
			{
				if($i == 0) $list_format .= " WHERE `".$row[$i]."`='".$data[$i]."'";
				else $list_format .=  " AND `".$row[$i]."`='".$data[$i]."'";
			}
		}
		if(!empty($limitE)) $limit = "LIMIT ".(int)$limitS.",".(int)$limitE."";
		else $limit = "";
		if(empty($NameText)) $Like = "";
		else
		{
			$row1 = explode(",",$NameText);
			$data1 = explode("<><>",$TextSearch);
			if(count($row1) != count($data1)) Simple_Theme_Work::Theme_Error(SIMPLE_FORMAT." DbDistinct");
			for($i=0;$i<count($row1);$i++)
			{
				if($i == 0) {
					if(empty($list_format)) $Like .= " WHERE `".$row1[$i]."` LIKE '%".$data1[$i]."%'";
					else $Like .= " AND `".$row1[$i]."` LIKE '%".$data1[$i]."%'";
				}
				else $Like .=  " OR `".$row1[$i]."` LIKE '%".$data1[$i]."%'";
			}
		}
		@$select = self::query("SELECT DISTINCT ".$listros." FROM `"._PREFIXDB_.$table."` $list_format $Like  ".$f." ".$limit." ");
		if(!$select) Simple_Theme_Work::Theme_Error(SIMPLE_SELECT_DB."SELECT DISTINCT ".$listros." FROM `"._PREFIXDB_.$table."` $Like $list_format ".$f." ".$limit." ");
		return $select;
	}
	public static function query_db($sql)
	{
		$query = self::query($sql);
		if(!$query) Simple_Theme_Work::Theme_Error(SIMPLE_SELECT_DB.$sql);
		return $query;
	}
	public static function auto_increment1($table)
	{
		$auto_increment = self::query("SHOW TABLE STATUS FROM `"._DATABASE1_."` LIKE '"._PREFIXDB_.$table."'")['Auto_increment'];
		return $auto_increment;
	}

    public static function query($query)
    {
        $ret = CMS::$db->query($query);
        if(!isset($GLOBALS['workdb'])) $GLOBALS['workdb'] = 1;
        else $GLOBALS['workdb'] = 1 + $GLOBALS['workdb'];
        //if(!$ret) Simple_Theme_Work::Theme_Error(SIMPLE_SELECT_DB.$query);
        return $ret;
    }
}
?>