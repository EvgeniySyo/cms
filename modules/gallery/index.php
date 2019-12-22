<?php
CMS::CoreComponent('page');
$MaxNumbers = 10;

//$content .= CMS::section_list(31);

if ($GalleryAlbum == 1) {
    if (!isset($_GET['cat']) || !is_numeric($_GET['cat'])) {
        #album
        $title_go = "Галерея";
        $content .= CMS::section_list(19, '', '');
        $selectCat = Simple_DbApi::select_db($_GET['page'] . "_cat", "*", "", "", "order", 1, "", "");
        if (!empty($selectCat)) {
            foreach ($selectCat as $i => $nc) {
                if (file_exists("images/gallery/album/" . $nc['id_c'] . "." . $nc['type'])) $image = URL_SITE . "images/gallery/album/" . $nc['id_c'] . "." . $nc['type'];
                else $image = URL_SITE . "images/notamplates.png";

                if ($PreviewImageAlbum != 1) $li .= CMS::section_list(2, "{url},{name}", URL_SITE . $_GET['page'] . "/" . $nc['id_c'] . "/<><>" . $nc['name_c']);
                else $content .= CMS::section_list(3, "{url},{name},{img}", URL_SITE . $_GET['page'] . "/" . $nc['id_c'] . "/<><>" . $nc['name_c'] . "<><>" . $image);
            }
            if ($PreviewImageAlbum != 1) $content .= CMS::section_list(1, "{li}", $li);
        }
        $content .= CMS::section_list(21, "", "");
    } else {
        $SelectNameId = Simple_DbApi::select_db($_GET['page'] . "_cat", "*", "id_c", $_GET['cat'], "", "", "", "");
        $nx = current($SelectNameId);
        $title_go = $nx['name_c'];
        $content .= CMS::section_list(23, "{desc}", $nx['description']);

        if (!isset($_GET['pages_all']) || !is_numeric($_GET['pages_all'])) {

            $content .= CMS::section_list(19, '', '');
            if (!is_numeric($_GET['in']) || !isset($_GET['in'])) $pageGo = 0;
            else $pageGo = $_GET['in'];

            $limit = abs($pageGo * $MaxImageOnPage);

            $CountImage = Simple_DbApi::CountTable($_GET['page'], "id_cat", $_GET['cat']);

            $SelectImg = Simple_DbApi::select_db($_GET['page'], "*", "id_cat", $_GET['cat'], "order", 1, $limit, $MaxImageOnPage);

            if (!empty($SelectImg)) {

                foreach ($SelectImg as $i => $ni) {
                    if (file_exists("images/gallery/small/" . $ni['id'] . "." . $ni['type'])) {
                        $image = URL_SITE . "images/gallery/small/" . $ni['id'] . "." . $ni['type'];
                        $image1 = URL_SITE . "images/gallery/full/" . $ni['id'] . "." . $ni['type'];
                    } else $image = URL_SITE . "images/notamplates.png";
                    $content .= CMS::section_list(20, "{url},{name},{img},{h},{img1}", URL_SITE . "" . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $pageGo . "/" . ($pageGo * $MaxImageOnPage + $i) . "/<><>" . $ni['name'] . "<><>" . $image . "<><>" . $MinHeight . "<><>" . $image1);
                }

                if ($CountImage > $MaxImageOnPage) {
                    $ThisPage = intval(($CountImage - 1) / $MaxImageOnPage) + 1;
                    $Pages = Simple_Page::NumberList($ThisPage, $_GET['in'], CMS::section_list(8, "{url}", URL_SITE . $_GET['page'] . "/" . $_GET['cat'] . "/in/{n}/"), CMS::section_list(5, "{url}", "/" . $_GET['page'] . "/" . $_GET['cat'] . "/in/{n}/"), CMS::section_list(7, '', ''), CMS::section_list(6, "{url}", "/" . $_GET['page'] . "/" . $_GET['cat'] . "/in/{n}/"), $MaxNumbers);
                    $content .= CMS::section_list(4, "{list}", $Pages);
                }
            }
            $content .= CMS::section_list(21, "", "");
        } else {
            $CountImage = Simple_DbApi::CountTable($_GET['page'], "id_cat", $_GET['cat']);
            if ($_GET['pages_all'] < 0 || $_GET['pages_all'] > ($CountImage - 1)) $_GET['pages_all'] = 0;

            $SelectThisImage = Simple_DbApi::select_db($_GET['page'], "*", "id_cat", $_GET['cat'], "order", 1, $_GET['pages_all'], 1);
            if (!empty($SelectThisImage)) {
                $ni = current($SelectThisImage);
                if (file_exists("images/gallery/small/" . $ni['id'] . "." . $ni['type'])) $image = URL_SITE . "images/gallery/small/" . $ni['id'] . "." . $ni['type'];
                else $image = URL_SITE . "images/notamplates.png";
                //$title_go = $ni['name'];

                if ($_GET['pages_all'] != 0) $ImageB = CMS::section_list(14, "{url}", URL_SITE . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $_GET['in'] . "/" . ($_GET['pages_all'] - 1) . "/");
                if (($CountImage - 1) != $_GET['pages_all']) $ImageN = CMS::section_list(15, "{url}", URL_SITE . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $_GET['in'] . "/" . ($_GET['pages_all'] + 1) . "/");

                $content .= CMS::section_list(9, "{url},{name},{desc},{img},{back},{b},{n}", URL_SITE . "images/gallery/full/" . $ni['id'] . "." . $ni['type'] . "<><>" . $ni['name'] . "<><>" . $ni['desc'] . "<><>" . $image . "<><>/" . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $_GET['in'] . "/" . "<><>" . $ImageB . "<><>" . $ImageN);

                if ($_GET['pages_all'] != 0) $ImageB = CMS::section_list(14, "{url}", URL_SITE . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $_GET['in'] . "/" . ($_GET['pages_all'] - 1) . "/");
                if (($CountImage - 1) != $_GET['pages_all']) $ImageN = CMS::section_list(15, "{url}", URL_SITE . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $_GET['in'] . "/" . ($_GET['pages_all'] + 1) . "/");

                //$content .= CMS::section_list(13,"{back},{next}",$ImageB."<><>".$ImageN);

                $nnn = $CountImage - 1;

                ####################

                if ($_GET['pages_all'] != 0 && $nnn != $_GET['pages_all']) {
                    $SelectP = Simple_DbApi::select_db($_GET['page'], "*", "id_cat", $_GET['cat'], "order", 1, ($_GET['pages_all'] - 1), 3);
                    if (!empty($SelectP)) {
                        foreach ($SelectP as $j => $nm) {
                            if (file_exists("images/gallery/small/" . $nm['id'] . "." . $nm['type'])) $image = URL_SITE . "images/gallery/small/" . $nm['id'] . "." . $nm['type'];
                            else $image = URL_SITE . "images/notamplates.png";
                            if ($j == 0) $ImageP .= CMS::section_list(17, "{url},{name},{img}", "/" . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $_GET['in'] . "/" . ($_GET['pages_all'] - 1) . "/<><>" . $nm['name'] . "<><>" . $image);
                            if ($j == 1) $ImageP .= CMS::section_list(16, "{{name},{img}", $nm['name'] . "<><>" . $image);
                            if ($j == 2) $ImageP .= CMS::section_list(17, "{url},{name},{img}", "/" . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $_GET['in'] . "/" . ($_GET['pages_all'] + 1) . "/<><>" . $nm['name'] . "<><>" . $image);
                        }
                    }


                    $content .= CMS::section_list(18, "{img}", $ImageP);
                } else {
                    if ($_GET['pages_all'] == 0) {
                        $SelectP = Simple_DbApi::select_db($_GET['page'], "*", "id_cat", $_GET['cat'], "order", 1, 0, 3);
                        if (!empty($SelectP)) {
                            foreach ($SelectP as $j => $nm) {
                                if (file_exists("images/gallery/small/" . $nm['id'] . "." . $nm['type'])) $image = URL_SITE . "images/gallery/small/" . $nm['id'] . "." . $nm['type'];
                                else $image = URL_SITE . "images/notamplates.png";
                                if ($j == 0) $ImageP .= CMS::section_list(16, "{{name},{img}", $nm['name'] . "<><>" . $image);
                                if ($j == 1) $ImageP .= CMS::section_list(17, "{url},{name},{img}", "/" . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $_GET['in'] . "/" . $j . "/<><>" . $nm['name'] . "<><>" . $image);
                                if ($j == 2) $ImageP .= CMS::section_list(17, "{url},{name},{img}", "/" . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $_GET['in'] . "/" . $j . "/<><>" . $nm['name'] . "<><>" . $image);
                            }
                        }

                        $content .= CMS::section_list(18, "{img}", $ImageP);
                    }
                    if ($nnn == $_GET['pages_all'] && $nnn != 0) {
                        if ($CountImage > 2) {
                            $SelectP = Simple_DbApi::select_db($_GET['page'], "*", "id_cat", $_GET['cat'], "order", 1, ($_GET['pages_all'] - 2), 3);
                            if (!empty($SelectP)) {
                                foreach ($SelectP as $j => $nm) {
                                    if (file_exists("images/gallery/small/" . $nm['id'] . "." . $nm['type'])) $image = URL_SITE . "images/gallery/small/" . $nm['id'] . "." . $nm['type'];
                                    else $image = URL_SITE . "images/notamplates.png";
                                    if ($j == 0) $ImageP .= CMS::section_list(17, "{url},{name},{img}", "/" . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $_GET['in'] . "/" . ($_GET['pages_all'] - 2) . "/<><>" . $nm['name'] . "<><>" . $image);
                                    if ($j == 1) $ImageP .= CMS::section_list(17, "{url},{name},{img}", "/" . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $_GET['in'] . "/" . ($_GET['pages_all'] - 1) . "/<><>" . $nm['name'] . "<><>" . $image);
                                    if ($j == 2) $ImageP .= CMS::section_list(16, "{{name},{img}", $nm['name'] . "<><>" . $image);
                                }
                            }


                            $content .= CMS::section_list(18, "{img}", $ImageP);

                        } else {
                            $SelectP = Simple_DbApi::select_db($_GET['page'], "*", "id_cat", $_GET['cat'], "order", 1, 0, 2);
                            if (!empty($SelectP)) {
                                foreach ($SelectP as $j => $nm) {
                                    if (file_exists("images/gallery/small/" . $nm['id'] . "." . $nm['type'])) $image = URL_SITE . "images/gallery/small/" . $nm['id'] . "." . $nm['type'];
                                    else $image = URL_SITE . "images/notamplates.png";
                                    if ($j == 0) $ImageP .= CMS::section_list(17, "{url},{name},{img}", "/" . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . $_GET['in'] . "/" . ($_GET['pages_all'] - 1) . "/<><>" . $nm['name'] . "<><>" . $image);
                                    if ($j == 1) $ImageP .= CMS::section_list(16, "{{name},{img}", $nm['name'] . "<><>" . $image);
                                }
                            }

                            $content .= CMS::section_list(18, "{img}", $ImageP);
                        }
                    }
                }

                ####################
            } else header("Location: /error/");
        }
    }
} else {
    if (!isset($_GET['in']) || !is_numeric($_GET['in'])) {

        if (!isset($_GET['cat']) || !is_numeric($_GET['cat'])) $pageGo = 0;
        else $pageGo = $_GET['cat'];

        $limit = abs($pageGo * $MaxImageOnPage);

        $CountImage = Simple_DbApi::CountTable($_GET['page'], "", "");

        $SelectImg = Simple_DbApi::select_db($_GET['page'], "*", "", "", "order", 1, $limit, $MaxImageOnPage);
        if (!empty($SelectImg)) {
            foreach ($SelectImg as $i => $ni) {
                if (file_exists("images/gallery/small/" . $ni['id'] . "." . $ni['type'])) $image = URL_SITE . "images/gallery/small/" . $ni['id'] . "." . $ni['type'];
                else $image = "/images/notamplates.png";
                $content .= CMS::section_list(3, "{url},{name},{img},{h}", URL_SITE . $_GET['page'] . "/" . $pageGo . "/in/" . ($pageGo * $MaxImageOnPage + $i) . "/<><>" . $ni['name'] . "<><>" . $image . "<><>" . $MinHeight);
            }

            if ($CountImage > $MaxImageOnPage) {
                $ThisPage = intval(($CountImage - 1) / $MaxImageOnPage) + 1;
                $Pages = Simple_Page::NumberList($ThisPage, $pageGo, CMS::section_list(8, "{url}", URL_SITE . $_GET['page'] . "/{n}/"), CMS::section_list(5, "{url}", "/" . $_GET['page'] . "/{n}/"), CMS::section_list(7, '', ''), CMS::section_list(6, "{url}", "/" . $_GET['page'] . "/{n}/"), $MaxNumbers);
                $content .= CMS::section_list(4, "{list}", $Pages);
            }

        }

    } else {
        $CountImage = Simple_DbApi::CountTable($_GET['page'], "", "");
        if ($_GET['in'] < 0 || $_GET['in'] > ($CountImage - 1)) $_GET['in'] = 0;
        $SelectThisImage = Simple_DbApi::select_db($_GET['page'], "*", "", "", "order", 1, $_GET['in'], 1);
        if (!empty($SelectThisImage)) {

            $ni = current($SelectThisImage);
            if (file_exists("images/gallery/small/" . $ni['id'] . "." . $ni['type'])) $image = URL_SITE . "images/gallery/small/" . $ni['id'] . "." . $ni['type'];
            else $image = URL_SITE . "images/notamplates.png";
            $title_go = $ni['name'];
            $content .= CMS::section_list(9, "{url},{name},{desc},{img},{back}", URL_SITE . "images/gallery/full/" . $ni['id'] . "." . $ni['type'] . "<><>" . $ni['name'] . "<><>" . $ni['desc'] . "<><>" . $image . "<><>" . URL_SITE . $_GET['page'] . "/" . $_GET['cat'] . "/");

            if ($_GET['in'] != 0) $ImageB = CMS::section_list(14, "{url}", URL_SITE . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . ($_GET['in'] - 1) . "/");
            if (($CountImage - 1) != $_GET['in']) $ImageN = CMS::section_list(15, "{url}", URL_SITE . $_GET['page'] . "/" . $_GET['cat'] . "/in/" . ($_GET['in'] + 1) . "/");

            $content .= CMS::section_list(13, "{back},{next}", $ImageB . "<><>" . $ImageN);


        } else header("Location: /error/");
    }

}

?>