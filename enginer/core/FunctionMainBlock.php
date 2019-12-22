<?php

function MainCache($go, $id)
{
    $content = '';
    if (empty($go) && empty($id)) {
        $SelectMain = Simple_DbApi::select_db('block_' . $_GET['config'], '*', 'id_cat', 0, 'order', 1, '', '');
        foreach ($SelectMain as $i => $nm) {
            $content .= $nm['id'] . '<><>' . $nm['name'] . '<><>' . $nm['url'] . '<><>' . $nm['module'] . '<><>' . $nm['select_url'] . '<><>' . $go . '<><>cat' . "\n";
            $content .= MainCache('', $nm['id']);
        }
    } else {
        if (is_numeric($id)) {
            $SelectID = Simple_DbApi::select_db('block_' . $_GET['config'], '*', 'id_cat', $id, 'order', 1, '', '');
            $go .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $go;
            if (!empty($SelectID)) {
                foreach ($SelectID as $i => $nm) {
                    if (Simple_DbApi::CountTable('block_' . $_GET['config'], 'id_cat', $nm['id']) > 0)
                        $type = 'cat';
                    else
                        $type = 'page';
                    $content .= $nm['id'] . '<><>' . $nm['name'] . '<><>' . $nm['url'] . '<><>' . $nm['module'] . '<><>' . $nm['select_url'] . '<><>' . $go . '<><>' . $type . "\n";
                    if ($type == 'cat')
                        $content .= MainCache($go, $nm['id']);
                }
            }
        }
    }

    return $content;
}

function ReadCacheOption()
{
    $content = '';
    if (file_exists(DATA_PATH . 'block/' . $_GET['config'] . '/cache')) {
        $file = file(DATA_PATH . 'block/' . $_GET['config'] . '/cache');
        for ($i = 0; $i < count($file); $i++) {
            if (!empty($file[$i])) {
                list($id, $name, $url, $module, $select_url, $go, $type) = explode('<><>', $file[$i], 7);
                $go = str_replace('<1>', '&nbsp;', $go);
                $content .= CMS::SectionBlock(7, '{name},{go},{id}', $name . '<><>' . $go . '<><>' . $id);
            }
        }
    }

    return $content;
}

function ReadCacheMain()
{
    $content = '';
    $componet = [];
    if (file_exists(DATA_PATH . 'block/' . $_GET['config'] . '/cache')) {
        $SelectComp = Simple_DbApi::select_db('modules', '*', 'install', 'yes', '', '', '', '');
        if (!empty($SelectComp)) {

            foreach ($SelectComp as $j => $nm) {
                if (file_exists('modules/' . $nm['name'] . '/index.php')) {
                    if (file_exists('modules/' . $nm['name'] . '/title.php'))
                        include('modules/' . $nm['name'] . '/title.php');
                    if (!empty($module_title))
                        $nameModule = $module_title;
                    else
                        $nameModule = $nm['name'];
                    $componet[$j] = !empty($componet[$j]) ? $componet[$j] + $nameModule . '::' . $nm['name'] : $nameModule . '::' . $nm['name'];
                }
                unset($nameModule);
            }
        }

        $file = file(DATA_PATH . 'block/' . $_GET['config'] . '/cache');

        for ($i = 0; $i < count($file); $i++) {
            $FullListModule = '';
            $typeTr = '';
            if (!empty($file[$i])) {
                list($id, $name, $url, $module, $select_url, $go, $type) = explode('<><>', $file[$i], 7);
                $go = str_replace('<1>', '&mdash;', $go);
                $listModule = explode('::', $module);
                if (!empty($componet)) {
                    foreach ($componet as $formatModule) {
                        list($titleM, $nameM) = explode('::', $formatModule, 2);
                        $find = 0;
                        for ($n = 0; $n < count($listModule); $n++) {
                            if ($nameM == $listModule[$n]) {
                                $FullListModule .= CMS::SectionBlock(10, '{check},{name},{module}', 'checked="checked"<><>' . $titleM . '<><>' . $nameM);
                                $find = 1;
                                break;
                            }
                        }
                        if ($find == 0)
                            $FullListModule .= CMS::SectionBlock(10, '{check},{name},{module}', '<><>' . $titleM . '<><>' . $nameM);
                    }
                }

                $top = $i != 0 ? CMS::SectionBlock(11, '{id}', $id) : '';
                $bottom = ($i + 1) != count($file) ? CMS::SectionBlock(12, '{id}', $id) : '';

                $typeTr = $typeTr == 'odd' ? 'int' : 'odd';

                $content .= CMS::SectionBlock(6, '{name},{go},{window},{window1},{id},{position},{t}', $name . '<><>' . $go . '<><>' . CMS::AlertWindow('Редактирование', CMS::SectionBlock(9, '{id},{name},{url},{url_list},{option},{module}', $id . '<><>' . $name . '<><>' . $url . '<><>' . $select_url . '<><>' . ReadCacheOption() . '<><>' . $FullListModule), '4', $id) . '<><>' . CMS::AlertWindow('Внимание', CMS::SectionBlock(8, '{id}', $id), 2, '-' . $id) . '<><>' . $id . '<><>' . $bottom . $top . '<><>' . $typeTr);
                unset($FullListModule);
            }
        }
    }
    return $content;
}

function WriteCache()
{
    $fp = fopen(DATA_PATH . 'block/' . $_GET['config'] . '/cache', 'w');
    flock($fp, LOCK_EX);
    fwrite($fp, MainCache('', ''));
    flock($fp, LOCK_UN);
    fclose($fp);
}

function listID($id)
{
    $listID = '';
    $SelectID = Simple_DbApi::select_db('block_' . $_GET['config'], "*", "id_cat", $id, "", "", "", "");
    if (!empty($SelectID)) {
        foreach ($SelectID as $i => $nn) {
            $listID .= $nn['id'] . '::';
            if (Simple_DbApi::CountTable('block_' . $_GET['config'], 'id_cat', $nn['id']) > 0)
                $listID .= listID($nn['id']);
        }
    }

    return $listID;
}

function IDarray($id)
{
    $array = explode('::', $id);
    for ($i = 0; $i < count($array); $i++)
        if (!empty($array[$i])) $list[$i] = $array[$i];
    return $list;
}

function ListMain($BlockName)
{
    if (!file_exists(DATA_PATH . 'block/' . $BlockName . '/cache')) WriteCache();

    $file = file(DATA_PATH . 'block/' . $BlockName . '/cache');
    $count = count($file);

    if (empty($file[($count - 1)])) $last = $count - 2;
    else $last = $count - 1;
    $listP = '';
    for ($i = 0; $i < count($file); $i++) {
        if (!empty($file[$i])) {
            list($id, $name, $url, $module, $select_url, $go, $type) = explode('<><>', $file[$i], 7);
            $find = 0;
            if (!empty($module)) {
                $listModule = explode('::', $module);
                for ($j = 0; $j < count($listModule); $j++) {
                    if ($_GET['page'] == trim($listModule[$j])) {
                        $find = 1;
                        break;
                    }
                }
            }

            if ($find == 0 && !empty($select_url)) {
                $UrlList = explode(',', $select_url);
                for ($n = 0; $n < count($UrlList); $n++) {
                    if (Simple_Search_Text($UrlList[$n], $_SERVER['REQUEST_URI'])) {
                        $find = 1;
                        break;
                    }
                }
            }

            if (trim($type) == 'cat') {

                if (!empty($listP)) {
                    $content = str_replace('{li}', $listP, $content);
                    $content = str_replace('[ul]', CMS::Section_Block_List(7, '', ''), $content);
                    $content = str_replace('[/ul]', CMS::Section_Block_List(8, '', ''), $content);
                } else {
                    $content = str_replace('{li}', $listP, $content);
                    $content = str_replace('[ul]', '', $content);
                    $content = str_replace('[/ul]', '', $content);
                }

                if ($last != $i) {
                    if ($find != 1)
                        $content .= CMS::Section_Block_List(2, '{url},{name}', $url . '<><>' . $name);
                    else
                        $content .= CMS::Section_Block_List(3, '{url},{name}', $url . '<><>' . $name);
                } else {
                    if ($find != 1)
                        $content .= CMS::Section_Block_List(9, '{url},{name}', $url . '<><>' . $name);
                    else
                        $content .= CMS::Section_Block_List(10, '{url},{name}', $url . '<><>' . $name);
                }

                unset($listP);
            } else {
                if ($find != 1)
                    $listP .= CMS::Section_Block_List(5, '{url},{name}', $url . '<><>' . $name);
                else
                    $listP .= CMS::Section_Block_List(6, '{url},{name}', $url . '<><>' . $name);
            }

            if ($last == $i && trim($type) == 'page')
                $content = str_replace('{li}', $listP, $content);
            if ($last == $i && trim($type) == 'cat') {
                $content = str_replace('{li}', '', $content);
                $content = str_replace('[ul]', '', $content);
                $content = str_replace('[/ul]', '', $content);
            }

        }
    }

    return $content;

    $content = str_replace('[ul]', CMS::Section_Block_List(7, '', ''), $content);
    $content = str_replace('[/ul]', CMS::Section_Block_List(8, '', ''), $content);

}

?>