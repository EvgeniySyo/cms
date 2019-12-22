<?php

CMS::CoreComponent('map');

if (!isset($_GET['cat']) || !is_numeric($_GET['cat'])) {
    $cats = Simple_DbApi::select_db($_GET['page'], "*", "type,parent", "cat<><>0", "name", 1, "", "");
    foreach ($cats as $cat) {
        $catalog .= CMS::section_list(5, "%URL%,%LI%", "" . $cat['id'] . "<><>" . $cat['name'] . "");
    }

    if (file_exists(DATA_PATH . '/modules/' . $_GET['page'] . '/main')) {
        $ReadMainPage = file_get_contents(DATA_PATH . '/modules/' . $_GET['page'] . '/main');
        $ListPageCat = CMS::section_list(1, "%LI%", $catalog);
        $ReadMainPage = str_replace('{main}', $ListPageCat, $ReadMainPage);
        $content .= $ReadMainPage;
    } else $content .= CMS::section_list(1, "%LI%", $catalog);

} else {

    ### TEST CACHE ###
    if (CMS::TestCacheFile($_GET['cat']) != 11) {
        $page = Simple_DbApi::select_db($_GET['page'], "*", "id", $_GET['cat'], "", "", "", "");
        if (empty($page)) header("Location:/error/");
        $page = $page[0];
        if ($page['status'] == 1) header("Location:/error/");

        $title_go = $page['name'];
        $content = '';
        $metaTitle = !empty($page['title']) ? $page['title'] : $title_go;
        $metaKey = $page['keyword'];
        $metaDesc = $page['desc'];

        if ($page['type'] != "cat") {

            $GLOBALS['parent'] = $page['parent'];

            # PATH LIST #
            if ($path == 1 && $page['path'] == 1) {
                $murl = Simple_Module_Map::list_cat($_GET['cat'], 0);
                $list_url = Simple_Module_Map::work_m($murl);
                uksort($list_url, "cmp");
                $content .= CMS::section_list(4, "", "");
                foreach ($list_url as $path1) {
                    list($text, $url) = explode("<><>", $path1, 2);
                    $content .= CMS::section_list(6, "%URL%,%TEXT%", "" . $url . "<><>" . $text . "");
                }
                $content .= "<p>&nbsp;</p>";
            }

            # END #

            //$nt['content'] = html_entity_decode($nt['content']);
            $page['content'] = str_replace("\\", "", $page['content']);

            if ($fansybox == 1) {

                $page['content'] = preg_replace('!href=\"(.*?).jpg\"!i', "href=\"\\1.jpg\" class=\"gallery\" rel=\"gallery\"", $page['content']);
                $page['content'] = preg_replace('!href=\"(.*?).gif\"!i', "href=\"\\1.gif\" class=\"gallery\" rel=\"gallery\"", $page['content']);
                $page['content'] = preg_replace('!href=\"(.*?).png\"!i', "href=\"\\1.png\" class=\"gallery\" rel=\"gallery\"", $page['content']);
                $page['content'] = preg_replace('!href=\"(.*?).jpeg\"!i', "href=\"\\1.jpeg\" class=\"gallery\" rel=\"gallery\"", $page['content']);

            }

            if ($textfansy == 1) $page['content'] = preg_replace('!href=\"(.*?).txt\"!si', "href=\"\\1.txt\" class=\"gallery\" rel=\"gallery\"", $page['content']);

            $content .= CMS::section_list(3, "%TEXT%", $page['content']);
            CMS::CreatContentCacheFile($title_go, $content, $metaTitle, $metaKey, $metaDesc, $_GET['cat']);

        }
        if ($page['type'] == "cat") {
            $title_go = $page['name'];

            # PATH LIST #

            if ($path == 1 && $page['path'] == 1) {
                $murl = Simple_Module_Map::list_cat($_GET['cat'], 0);
                $list_url = Simple_Module_Map::work_m($murl);
                uksort($list_url, "cmp");
                $content .= CMS::section_list(4, "", "");
                foreach ($list_url as $path1) {
                    list($text, $url) = explode("<><>", $path1, 2);
                    $content .= CMS::section_list(6, "%URL%,%TEXT%", "" . $url . "<><>" . $text . "");
                }
                $content .= "<p>&nbsp;</p>";
            }

            # END #

            $select_list = Simple_DbApi::select_db($_GET['page'], "*", "parent", $page['id'], "name", 1, "", "");
            $cat_list = $page_list = '';
            foreach ($select_list as $t => $nk)
			{
                if ($nk['type'] == "cat") $cat_list .= CMS::section_list(8, "%URL%,%TEXT%", "" . $nk['id'] . "<><>" . $nk['name'] . "");
                else $page_list .= CMS::section_list(9, "%URL%,%TEXT%", "" . $nk['id'] . "<><>" . $nk['name'] . "");
            }
			
			//$nt['content'] = html_entity_decode($nt['content']);
            $page['content'] = str_replace("\\", "", $page['content']);

			### PRINT INFO ###

			if (!empty($page['content'])) {
                $list = CMS::section_list(7, "%CAT%,%PAGE%", "" . $cat_list . "<><>" . $page_list . "");
                $page['content'] = str_replace('{main}', $list, $page['content']);
                $content .= $page['content'];
            } else $content .= CMS::section_list(7, "%CAT%,%PAGE%", "" . $cat_list . "<><>" . $page_list . "");

			### END ###

			if ($page['status'] != 1) CMS::CreatContentCacheFile($title_go, $content, $metaTitle, $metaKey, $metaDesc, $_GET['cat']);
		}

    } else {
        $ReadCache = CMS::ReadCacheModule($_GET['cat']);
        $title_go = CMS::TitleCache($ReadCache);

        $content .= CMS::ContentCache($ReadCache);

        $metaTitle = CMS::MetaTitleCache($ReadCache);
        $metaKey = CMS::MetaKeyCache($ReadCache);
        $metaDesc = CMS::MetaDescCache($ReadCache);
    }

}
?>