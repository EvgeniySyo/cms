<?PHP
$on_pages_button = 10;
include("includes/js.php");
$TemplatesSection = CMS::SectionFile('stat');
echo CMS::SectionAdmin($TemplatesSection,1,"","");
Simple_DbApi::connect_db();
echo CMS::SectionAdmin($TemplatesSection,2,"","");
if(!isset($_POST['id_date']))
{
	if(isset($_POST['id_del']))
	{
		simple_delete_db("statistics","date",$_POST['id_del']);
		simple_delete_db("hits","date",$_POST['id_del']);
		echo CMS::SectionAdmin($TemplatesSection,3,"","");
	}
	echo CMS::SectionAdmin($TemplatesSection,4,"","");
	if(!isset($_POST['ppp']) || !is_numeric($_POST['ppp'])) $_POST['ppp'] = 0;
	$limits_g = abs($_POST['ppp'])*$on_pages_button;
	$mysql["select_st"] = Simple_DbApi::select_db("hits","*","","","id",2,$limits_g,$on_pages_button);
	if (!empty($mysql["select_st"])) {
        foreach ($mysql["select_st"] as $t => $ns)
        {
            echo CMS::SectionAdmin($TemplatesSection,5,"%DATE%",$ns['date']);
        }
    }

	echo CMS::SectionAdmin($TemplatesSection,6,"","");
	$all_hits = simple_count_row("hits");
	if($all_hits > $on_pages_button)
	{
		for ($x=0;$x<$all_hits/$on_pages_button;$x++)
		{
			if($_POST['ppp'] != $x) echo CMS::SectionAdmin($TemplatesSection,7,"%X1%,%X%","".($x+1)."<><>".$x."");
			else echo CMS::SectionAdmin($TemplatesSection,8,"%X%",($x+1));
		}
		echo CMS::SectionAdmin($TemplatesSection,9,"","");
	}
}
else
{
	$select_hit = simple_select("statistics","date",$_POST['id_date'],"id",2,"","");
	$a = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0,14=>0,15=>0,16=>0,17=>0,18=>0,19=>0,20=>0,21=>0,22=>0,23=>0);
	if (!empty($select_hit)) {
        foreach ($select_hit as $t => $nf)
        {
            list($hour,$minute,$second) = explode(":",$nf['time'],3);
            if($hour == 0) $a[0] = $a[0] + 1;
            if($hour == 1) $a[1] = $a[1] + 1;
            if($hour == 2) $a[2] = $a[2] + 1;
            if($hour == 3) $a[3] = $a[3] + 1;
            if($hour == 4) $a[4] = $a[4] + 1;
            if($hour == 5) $a[5] = $a[5] + 1;
            if($hour == 6) $a[6] = $a[6] + 1;
            if($hour == 7) $a[7] = $a[7] + 1;
            if($hour == 8) $a[8] = $a[8] + 1;
            if($hour == 9) $a[9] = $a[9] + 1;
            if($hour == 10) $a[10] = $a[10] + 1;
            if($hour == 11) $a[11] = $a[11] + 1;
            if($hour == 12) $a[12] = $a[12] + 1;
            if($hour == 13) $a[13] = $a[13] + 1;
            if($hour == 14) $a[14] = $a[14] + 1;
            if($hour == 15) $a[15] = $a[15] + 1;
            if($hour == 16) $a[16] = $a[16] + 1;
            if($hour == 17) $a[17] = $a[17] + 1;
            if($hour == 18) $a[18] = $a[18] + 1;
            if($hour == 19) $a[19] = $a[19] + 1;
            if($hour == 20) $a[20] = $a[20] + 1;
            if($hour == 21) $a[21] = $a[21] + 1;
            if($hour == 22) $a[22] = $a[22] + 1;
            if($hour == 23) $a[23] = $a[23] + 1;
        }
    }

}
echo CMS::SectionAdmin($TemplatesSection,10,"","");
?>