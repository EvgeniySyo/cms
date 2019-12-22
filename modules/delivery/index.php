<?

include(DATA_PATH . "modules/" . $_GET['page'] . "/towns.php");

if (isset($_GET['cat'])) {
    if (isset($deltownsname[$_GET['cat']])) {
        if (isset($_GET['id'])) $id = $_GET['id'];
        else $id = 0;
        $title_go = $deltownsname[$_GET['cat']];
        $selectdel = Simple_DbApi::select_db($_GET['page'], "*", "id_town", $_GET['cat'], "", "", "", "");
        if (!empty($selectdel)) {
            $firstid = '';
            foreach ($selectdel as $i => $dd) {
                if ($dd['type'] == 1) $addition = CMS::section_list('7-4', '', '');
                else $addition = '';
                if ($dd['phone'] != '') $phone = CMS::section_list('7-1', '{phone}', $dd['phone']);
                if ($dd['worktime'] != '') $worktime = CMS::section_list('7-2', '{worktime}', $dd['worktime']);
                if ($dd['proezd'] != '') $proezd = CMS::section_list('7-3', '{proezd}', $dd['proezd']);
                if (($i == 0 && $id == 0) || $id == $dd['id']) {
                    $storelist .= CMS::section_list(7, "{id},{address},{phone},{worktime},{proezd},{town},{first},{addition}", $dd['id'] . '<><>' . $dd['address'] . '<><>' . $phone . '<><>' . $worktime . '<><>' . $proezd . '<><>' . $deltownsname[$_GET['cat']] . '<><>' . ' delselected' . '<><>' . $addition);
                    $firstid = $dd['id'];
                } else $storelist .= CMS::section_list(7, "{id},{address},{phone},{worktime},{proezd},{town},{first},{addition}", $dd['id'] . '<><>' . $dd['address'] . '<><>' . $phone . '<><>' . $worktime . '<><>' . $proezd . '<><>' . $deltownsname[$_GET['cat']] . '<><>' . '' . '<><>' . $addition);
            }
            $slider = '';
            $imgmain = '';
            $imgsub = '';
            $selectimg = Simple_DbApi::select_db($_GET['page'] . '_img', "*", "store_id", $firstid, "", "", "", "");
            if (!empty($selectimg)) {
                foreach ($selectimg as $i => $dimg) {
                    if ($i == 0) $class = 'class="selected"';
                    else $class = '';
                    $img = '/images/delivery/' . $dimg['id'] . '.' . $dimg['ext'];
                    $imgmain .= CMS::section_list('9-1', "{id},{img}", $dimg['id'] . '<><>' . $img);
                    $imgsub .= CMS::section_list('9-2', "{id},{img},{class}", $dimg['id'] . '<><>' . $img . '<><>' . $class);
                }
            }
            $slider = CMS::section_list(9, "{imgmain},{imgsub}", $imgmain . '<><>' . $imgsub);
            $content .= CMS::section_list(8, "{storelist},{slider}", $storelist . '<><>' . $slider);
            $content .= CMS::section_list(0, "", "");
        } else $content .= CMS::section_list('error2', "", "");
    }
} else {
    $selectdelivery = Simple_DbApi::select_db($_GET['page'], "*", "", "", "", "", "", "");
    if (!empty($selectdelivery)) {
        $coords = '';
        $type = '';
        foreach ($selectdelivery as $i => $dd) {
            if ($dd['type'] == 1) $type = 'post';
            else $type = 'store';
            $tmp = explode(',', $dd['coords']);
            $coords .= CMS::section_list('2-1', "{coord1},{coord2},{id},{town},{address},{type},{id_town}", trim($tmp[0]) . '<><>' . trim($tmp[1]) . '<><>' . $dd['id'] . '<><>' . $deltownsname[$dd['id_town']] . '<><>' . $dd['address'] . '<><>' . $type . '<><>' . $dd['id_town']);
        }
        $captowns = '';
        $towns = array();
        $tindex = 0;
        $tcount = count($deltowns);
        $prevletter = '';
        foreach ($deltowns as $index => $arr) {
            $href = '/' . $_GET['page'] . '/' . $arr['id'] . '/';
            $name = $arr['name'];
            $letter = mb_substr($arr['name'], 0, 1);
            if ($arr['capital'] == 1) {
                $captowns .= CMS::section_list(4, "{href},{name}", $href . '<><>' . $name);
            } else {
                if ($index < $tcount / 4) $tindex = 0;
                else if ($index >= $tcount / 4 && $index <= $tcount / 2) $tindex = 1;
                else if ($index >= $tcount / 2 && $index <= (3 * $tcount) / 4) $tindex = 2;
                else $tindex = 3;
                if ($letter != $prevletter) {
                    $towns[$tindex] .= CMS::section_list(6, "{letter}", $letter);
                    $prevletter = $letter;
                }
                $towns[$tindex] .= CMS::section_list(5, "{href},{name}", $href . '<><>' . $name);
            }
        }
        $alllist = CMS::section_list(3, "{itemscap},{items},{items1},{items2},{items3}", $captowns . '<><>' . $towns[0] . '<><>' . $towns[1] . '<><>' . $towns[2] . '<><>' . $towns[3]);
        $text = file_get_contents(DATA_PATH . "modules/" . $_GET['page'] . "/text.txt");
        $map = CMS::section_list(2, "{coords}", trim($coords, ','));
        $content .= CMS::section_list(1, "{map},{text},{list}", $map . '<><>' . $text . '<><>' . $alllist);
    } else $content .= CMS::section_list('error', "", "");
}


?>