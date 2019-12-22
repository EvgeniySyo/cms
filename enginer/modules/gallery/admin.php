<?php

$maxPhoto = 10;
$MaxNumbers = 10;
$uploaddir = 'images/' . $_GET['name_tamlates'] . '/upload/';
CMS::CoreComponent('page');
$TitleModule = CMS::TitleComponent($_GET['name_tamlates']);

$dir = 'images/' . $_GET['name_tamlates'] . '/upload/'; // Директория с файлами
$files = scandir($dir);
$time = time(); // Текущее время
$life_file = 100; // Время жизни файла в секундах
$time = $time - $life_file;

foreach ($files as $file) {
    if ($file != "." && $file != "..") {
        $file = $dir . $file;
        $filemtime = filemtime($file); // Время создания или модификации файла

        // Удаляем, если нужно
        if ($filemtime <= $time) {
            unlink($file);
        }
    }
}

echo CMS::AdminModuleSection(0, "", "");

if ($_GET['page'] < 1 || $_GET['page'] > 6) {
    if (isset($_POST['save'])) {

        if ($_POST['OnAlbum'] == 1)
            $GalleryAlbum = 1;
        else
            $GalleryAlbum = 2;
        if (!is_numeric($_POST['fotoOnPage']) || $_POST['fotoOnPage'] < 1)
            $MaxImageOnPage = 10;
        else
            $MaxImageOnPage = $_POST['fotoOnPage'];
        if (!is_numeric($_POST['ws']) || $_POST['ws'] < 1)
            $MinWidth = 200;
        else
            $MinWidth = $_POST['ws'];
        if (!is_numeric($_POST['wh']) || $_POST['wh'] < 1)
            $MinHeight = 200;
        else
            $MinHeight = $_POST['wh'];
        if (!is_numeric($_POST['wb']) || $_POST['wb'] < 1)
            $BigWidth = 400;
        else
            $BigWidth = $_POST['wb'];
        if (!is_numeric($_POST['hb']) || $_POST['hb'] < 1)
            $BigHeight = 400;
        else
            $BigHeight = $_POST['hb'];
        if ($_POST['OnAlbumPreview'] == 1)
            $PreviewImageAlbum = 1;
        else
            $PreviewImageAlbum = 2;

        $file = file(DATA_PATH . "modules/" . $_GET['name_tamlates'] . "/config.php");
        $fp = fopen(DATA_PATH . "modules/" . $_GET['name_tamlates'] . "/config.php", "w");
        flock($fp, LOCK_EX);
        for ($i = 0; $i < count($file); $i++) {
            if ($i == 1)
                fwrite($fp, "\$GalleryAlbum = " . $GalleryAlbum . ";\n");
            elseif ($i == 2)
                fwrite($fp, "\$MaxImageOnPage = " . $MaxImageOnPage . ";\n");
            elseif ($i == 3)
                fwrite($fp, "\$MinWidth = " . $MinWidth . ";\n");
            elseif ($i == 4)
                fwrite($fp, "\$MinHeight = " . $MinHeight . ";\n");
            elseif ($i == 5)
                fwrite($fp, "\$BigWidth = " . $BigWidth . ";\n");
            elseif ($i == 6)
                fwrite($fp, "\$BigHeight = " . $BigHeight . ";\n");
            elseif ($i == 7)
                fwrite($fp, "\$PreviewImageAlbum = " . $PreviewImageAlbum . ";\n");
            else
                fwrite($fp, $file[$i]);
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        echo CMS::AlertWindow('Успешно', 'Данные обновлены', 1, 0);
        CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> изменил настройки в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '.pl"><b>' . $TitleModule . '</b></a>');


    }
    $album = $album1 = $PAlbum = $PAlbum1 = '';
    if ($GalleryAlbum == 1)
        $album = "selected";
    else
        $album1 = "selected";
    if ($PreviewImageAlbum == 1)
        $PAlbum = "selected";
    else
        $PAlbum1 = "selected";

    echo CMS::AdminModuleSection(1, "{NameModule},{select1},{select2},{foto},{select3},{select4},{ws},{hs},{wb},{hb}", $_GET['name_tamlates'] . "<><>" . $album . "<><>" . $album1 . "<><>" . $MaxImageOnPage . "<><>" . $PAlbum . "<><>" . $PAlbum1 . "<><>" . $MinWidth . "<><>" . $MinHeight . "<><>" . $BigWidth . "<><>" . $BigHeight);
}

$style1 = $style2 = $style3 = $style4 = $style5 = $style6 = $list1 = $list2 = $list3 = $list4 = $list5 = $list6 = '';
if ($_GET['page'] == 1) {
    $list1 = "&#8595;";
    $list2 = "&#8594;";
    $list3 = "&#8594;";
    $style1 = "style=\"background:#b7ddf2;\"";
}
if ($_GET['page'] == 2) {
    $list1 = "&#8592;";
    $list2 = "&#8595;";
    $list3 = "&#8594;";
    $style2 = "style=\"background:#b7ddf2;\"";
}
if ($_GET['page'] == 3) {
    $list1 = "&#8592;";
    $list2 = "&#8592;";
    $list3 = "&#8595;";
    $style3 = "style=\"background:#b7ddf2;\"";
}

if ($_GET['page'] == 4) {
    $list4 = "&#8595;";
    $list5 = "&#8594;";
    $list6 = "&#8594;";
    $style4 = "style=\"background:#b7ddf2;\"";
}
if ($_GET['page'] == 5) {
    $list4 = "&#8592;";
    $list5 = "&#8595;";
    $list6 = "&#8594;";
    $style5 = "style=\"background:#b7ddf2;\"";
}
if ($_GET['page'] == 6) {
    $list4 = "&#8592;";
    $list5 = "&#8592;";
    $list6 = "&#8595;";
    $style6 = "style=\"background:#b7ddf2;\"";
}

if ($_GET['page'] == 1 || $_GET['page'] == 2 || $_GET['page'] == 3)
    echo CMS::AdminModuleSection(3, "{NameModule},{style-1},{style-2},{style-3},{list-1},{list-2},{list-3}", $_GET['name_tamlates'] . "<><>" . $style1 . "<><>" . $style2 . "<><>" . $style3 . "<><>" . $list1 . "<><>" . $list2 . "<><>" . $list3);
if ($_GET['page'] == 4 || $_GET['page'] == 5 || $_GET['page'] == 6)
    echo CMS::AdminModuleSection(16, "{NameModule},{style-1},{style-2},{style-3},{list-1},{list-2},{list-3}", $_GET['name_tamlates'] . "<><>" . $style4 . "<><>" . $style5 . "<><>" . $style6 . "<><>" . $list4 . "<><>" . $list5 . "<><>" . $list6);

### ADD ALBUM
if ($_GET['page'] == 1) {
    if (isset($_POST['add'])) {

        $name = strip_tags($_POST['nameCat']);
        $desc = strip_tags($_POST['desc']);
        if (strlen($name) > 0) {
            $selectmax = CMS::$db->query("select * from `" . _PREFIXDB_ . $_GET['name_tamlates'] . "_cat` order by `order` desc limit 1");
            if (!empty($selectmax)) {
                $nc = current($selectmax);
                $order = $nc['order'] + 1;
            } else $order = 1;

            if ($_POST['preload_img']) {
                $_POST['preload_img'] = strip_tags(trim($_POST['preload_img']));
                $file = "images/gallery/upload/" . $_POST['preload_img'];
                if (file_exists($file)) {
                    $file_name = $_POST['preload_img'];

                    $idImg = Simple_DbApi::auto_increment('gallery_cat');
                    $path = "images/gallery/album/" . $idImg . "." . simple_imagetest_format($file_name, 2);
                    copy($file, $path);
                    simple_image_save($MinWidth, $MinHeight, $path, $path, simple_imagetest_format($file_name, 2), 1);

                    Simple_DbApi::insert_db($_GET['name_tamlates'] . "_cat", "id_c,name_c,type,description,order", "<><>" . $name . "<><>" . simple_imagetest_format($file_name, 2) . "<><>" . $desc . "<><>" . $order);
                    if (isset($_SESSION['img_delete']))//cleanup
                    {
                        if (file_exists($uploaddir . $_SESSION['img_delete'])) unlink($uploaddir . $_SESSION['img_delete']);    //cleanup
                        unset($_SESSION['img_delete']);
                    }
                    if (isset($_SESSION['img_name'])) unset($_SESSION['img_name']);
                    echo CMS::AlertWindow('Успешно', 'Альбом создан', 1, 0);
                    CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> добавил альбом <b>' . $name . '</b> в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-1.pl"><b>Добавить альбом</b></a>');
                } else echo CMS::AlertWindow('Ошибка', 'Нет файла' . '--------' . $file, 3, 0);
            } else {
                Simple_DbApi::insert_db($_GET['name_tamlates'] . "_cat", "id_c,name_c,type,description,order", "<><>" . $name . "<><>" . "<><>" . $desc . "<><>" . $order);
                CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> добавил альбом <b>' . $name . '</b> в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-1.pl"><b>Добавить альбом</b></a>');
                echo CMS::AlertWindow('Успешно', 'Альбом создан', 1, 0);
            }
        } else echo CMS::AlertWindow('Ошибка', 'Не указанно название альбома', 3, 0);
    }

    //unset($_SESSION['img_name']);
    //$dir='images/'.$_GET['name_tamlates'].'/upload/';
    //if(is_dir($dir))
    //{
    //	if($handle = opendir($dir))
    //	{
    //		while( ($file = readdir($handle)) !== false ) if($file != "." && $file != "..") unlink($dir.$file);
    //		closedir($handle);
    //	}
    //}

    echo CMS::AdminModuleSection(4, "{modname}", $_GET['name_tamlates']);
}
### EDIT ALBUM
$List = '';
if ($_GET['page'] == 2) {
    if (isset($_POST['edit']) && is_numeric($_POST['id'])) {
        $name = strip_tags($_POST['nameCat']);
        $desc = strip_tags($_POST['desc']);
        if (strlen($name) > 0) {
            if (!empty($_POST['preload_img'])) {
                $_POST['preload_img'] = strip_tags(trim($_POST['preload_img']));
                $file = "images/gallery/upload/" . $_POST['preload_img'];
                if (file_exists($file)) {
                    $file_name = $_POST['preload_img'];

                    $path = "images/gallery/album/" . $_POST['id'] . "." . simple_imagetest_format($file_name, 2);
                    copy($file, $path);
                    simple_image_save($MinWidth, $MinHeight, $path, $path, simple_imagetest_format($file_name, 2), 1);
                    Simple_DbApi::update_db("gallery_cat", "name_c,type,description", $name . "<><>" . simple_imagetest_format($file_name, 2) . "<><>" . $desc, "id_c", $_POST['id']);
                    if (isset($_SESSION['img_delete'])) if (file_exists($uploaddir . $_SESSION['img_delete'])) unlink($uploaddir . $_SESSION['img_delete']);    //cleanup
                    unset($_SESSION['img_delete']);
                    if (isset($_SESSION['img_name'])) unset($_SESSION['img_name']);
                    echo CMS::AlertWindow('Успешно', 'Данные обновлены', 1, 0);

                    CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> внес изменения в альбом <b>' . $name . '</b> в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-2.pl"><b>Редактировать альбомы</b></a>');

                } else echo CMS::AlertWindow('Ошибка', 'Нет файла' . '--------' . $file, 3, 0);
            } else {
                if (!empty($_POST['delete']) && $_POST['delete'] == "on") {
                    $Select = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "id_c", $_POST['id'], "", "", "", "");
                    $nd = current($Select);
                    if (file_exists("images/gallery/album/" . $nd['id_c'] . "." . $nd['type'])) unlink("images/gallery/album/" . $nd['id_c'] . "." . $nd['type']);
                    Simple_DbApi::update_db("gallery_cat", "type", "", "id_c", $_POST['id']);
                    if (isset($_SESSION['img_delete'])) if (file_exists($uploaddir . $_SESSION['img_delete'])) unlink($uploaddir . $_SESSION['img_delete']);    //cleanup
                    unset($_SESSION['img_delete']);
                    if (isset($_SESSION['img_name'])) unset($_SESSION['img_name']);
                }
                Simple_DbApi::update_db("gallery_cat", "name_c,description", $name . "<><>" . $desc, "id_c", $_POST['id']);
                echo CMS::AlertWindow('Успешно', 'Данные обновлены', 1, 0);
                CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> внес изменения в альбом <b>' . $name . '</b> в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-2.pl"><b>Редактировать альбомы</b></a>');
            }
        } else echo CMS::AlertWindow('Ошибка', 'Не указанно название альбома', 3, 0);
    }


    echo CMS::AdminModuleSection(8, "", "");

    $na = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "", "", "order", 1, "", "");
    if (!empty($na)) {
        $numalbum = count($na);
        for ($i = 0; $i < $numalbum; $i++) {
            $rand = rand(1000, 9999);

            if ($numalbum == 1) {
                $toup = "";
                $todown = "";
            } else if ($i == 0) {
                $toup = "";
                $todown = CMS::AdminModuleSection('36-2', '{idalbumthis},{idalbumchange}', $na[$i]['id_c'] . '<><>' . $na[$i + 1]['id_c']);
            } else if ($i > 0 && $i != ($numalbum - 1)) {
                $toup = CMS::AdminModuleSection('36-1', '{idalbumthis},{idalbumchange}', $na[$i]['id_c'] . '<><>' . $na[$i - 1]['id_c']);
                $todown = CMS::AdminModuleSection('36-2', '{idalbumthis},{idalbumchange}', $na[$i]['id_c'] . '<><>' . $na[$i + 1]['id_c']);
            } else {
                $toup = CMS::AdminModuleSection('36-1', '{idalbumthis},{idalbumchange}', $na[$i]['id_c'] . '<><>' . $na[$i - 1]['id_c']);
                $todown = "";
            }

            if (empty($na[$i]['type']) || !file_exists("images/gallery/album/" . $na[$i]['id_c'] . "." . $na[$i]['type']))
                $image = "../images/notamplates.png";
            else
                $image = "../images/gallery/album/" . $na[$i]['id_c'] . "." . $na[$i]['type'] . "?" . $rand;
            $alert_window = CMS::AlertWindow('Редактирование альбома', CMS::AdminModuleSection('11-1', '{name},{image},{id},{desc},{modname}', $na[$i]['name_c'] . '<><>' . $image . '<><>' . $na[$i]['id_c'] . '<><>' . $na[$i]['description'] . '<><>' . $_GET['name_tamlates']), 4, $na[$i]['id_c']);
            $List .= CMS::AdminModuleSection(11, "{id},{name},{image},{window},{toupalbum},{todownalbum}", $na[$i]['id_c'] . "<><>" . $na[$i]['name_c'] . "<><>" . $image . "<><>" . $alert_window . "<><>" . $toup . "<><>" . $todown);
        }

        echo CMS::AdminModuleSection(10, "{list}", $List);
    }

    echo CMS::AdminModuleSection(9, "", "");
}
# DELETE ALBUM
if ($_GET['page'] == 3) {

    if (isset($_POST['delete']) && is_numeric($_POST['id'])) {
        $SelectID = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "id_c", $_POST['id'], "", "", "", "");
        if (!empty($SelectID)) {
            $nn = current($SelectID);
            CMS::$db->query("update `" . _PREFIXDB_ . $_GET['name_tamlates'] . "_cat` set `order`=(`order`-1) where `order`>'" . $nn['order'] . "'");//
            $SelectCatImage = Simple_DbApi::select_db($_GET['name_tamlates'], "*", "id_cat", $_POST['id'], "", "", "", "");
            foreach ($SelectCatImage as $s => $nd) {
                if (file_exists("images/gallery/small/" . $nd['id'] . "." . $nd['type']))
                    unlink("images/gallery/small/" . $nd['id'] . "." . $nd['type']);
                if (file_exists("images/gallery/big/" . $nd['id'] . "." . $nd['type']))
                    unlink("images/gallery/big/" . $nd['id'] . "." . $nd['type']);
                if (file_exists("images/gallery/full/" . $nd['id'] . "." . $nd['type']))
                    unlink("images/gallery/full/" . $nd['id'] . "." . $nd['type']);
            }

            if (file_exists("images/gallery/album/" . $nn['id_c'] . "." . $nn['type']))
                unlink("images/gallery/album/" . $nn['id_c'] . "." . $nn['type']);
            Simple_DbApi::delete_db($_GET['name_tamlates'] . "_cat", "id_c", $_POST['id']);
            Simple_DbApi::delete_db($_GET['name_tamlates'], "id_cat", $_POST['id']);

            CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> удалил альбом <b>' . $nn['name_c'] . '</b> в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-3.pl"><b>Удаление альбомов</b></a>');

            echo CMS::AlertWindow('Успешно', 'Альбом удален', 1, 0);
        }
    }

    echo CMS::AdminModuleSection(12, "", "");
    $SelectAlbum = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "", "", "order", 1, "", "");
    if (!empty($SelectAlbum)) {
        foreach ($SelectAlbum as $i => $na) {
            $List .= CMS::AdminModuleSection(14, "{id},{name},{window}", $na['id_c'] . "<><>" . $na['name_c'] . "<><>" . CMS::AlertWindow('Внимание', CMS::AdminModuleSection('14-1', '{name},{id}', $na['name_c'] . '<><>' . $na['id_c']), 2, $na['id_c']));
        }

        echo CMS::AdminModuleSection("10-1", "{list}", $List);
    }
    echo CMS::AdminModuleSection(13, "", "");
}
### ADD IMAGE
if ($_GET['page'] == 4) {
    $idAlbum = '';
    if (isset($_POST['add'])) {
        $name = strip_tags($_POST['name']);
        if (!is_numeric($_POST['idAlbum']))
            $idAlbum = 0;
        else
            $idAlbum = $_POST['idAlbum'];
        if ($_POST['preload_img']) {
            $_POST['preload_img'] = strip_tags(trim($_POST['preload_img']));
            $file = "images/gallery/upload/" . $_POST['preload_img'];
            if (file_exists($file)) {
                $file_name = $_POST['preload_img'];
                $desc = strip_tags($_POST['desc']);

                $selectmax = CMS::$db->query("select * from `" . _PREFIXDB_ . $_GET['name_tamlates'] . "` where `id_cat`='" . $idAlbum . "' order by `order` desc limit 1");
                if (!empty($selectmax)) {
                    $nc = current($selectmax);
                    $order = $nc['order'] + 1;
                } else $order = 1;

                $IdFoto = Simple_DbApi::auto_increment($_GET['name_tamlates']);
                $path = "images/gallery/full/" . $IdFoto . "." . simple_imagetest_format($file_name, 2);
                $path1 = "images/gallery/big/" . $IdFoto . "." . simple_imagetest_format($file_name, 2);
                $path2 = "images/gallery/small/" . $IdFoto . "." . simple_imagetest_format($file_name, 2);
                copy($file, $path);
                copy($file, $path1);
                copy($file, $path2);
                simple_image_save($BigWidth, $BigHeight, $path1, $path1, simple_imagetest_format($file_name, 2), 1);
                simple_image_save($MinWidth, $MinHeight, $path2, $path2, simple_imagetest_format($file_name, 2), 1);
                Simple_DbApi::insert_db($_GET['name_tamlates'], "id,id_cat,type,name,desc,order", "<><>" . $idAlbum . "<><>" . simple_imagetest_format($file_name, 2) . "<><>" . $name . "<><>" . $desc . "<><>" . $order);
                if (isset($_SESSION['img_delete'])) {
                    if (file_exists($uploaddir . $_SESSION['img_delete'])) unlink($uploaddir . $_SESSION['img_delete']);    //cleanup
                    unset($_SESSION['img_delete']);
                }
                if (isset($_SESSION['img_name'])) unset($_SESSION['img_name']);
                CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> добавил изображение в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-4.pl"><b>Добавить изображение</b></a>');
                echo CMS::AlertWindow('Успешно', 'Изображение добавлено', 1, 0);
            } else echo CMS::AlertWindow('Ошибка', 'Нет файла' . '--------' . $file, 3, 0);
        } else echo CMS::AlertWindow('Ошибка', 'Не выбрано изображение', 3, 0);
    }

    $SelectList = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "", "", "order", 1, "", "");
    if (!empty($SelectList)) {
        foreach ($SelectList as $i => $na) {
            if ($na['id_c'] == $idAlbum) $list .= CMS::AdminModuleSection(28, "{id},{name},{opt}", $na['id_c'] . "<><>" . $na['name_c'] . "<><>" . 'selected="selected"');
            else $list .= CMS::AdminModuleSection(28, "{id},{name},{opt}", $na['id_c'] . "<><>" . $na['name_c'] . "<><>" . '');
        }
    }


    echo CMS::AdminModuleSection(17, "{option},{modname}", $list . "<><>" . $_GET['name_tamlates']);
}
### EDIT IMAGE
if ($_GET['page'] == 5) {
    if (!isset($_GET['id_cat']) || !is_numeric($_GET['id_cat'])) {
        $SelectCat = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "", "", "order", 1, "", "");
        if (!empty($SelectCat)) {
            foreach ($SelectCat as $i => $nc) {
                $li .= CMS::AdminModuleSection(23, "{nameT},{id},{name}", $_GET['name_tamlates'] . "<><>" . $nc['id_c'] . "<><>" . $nc['name_c']);
            }
        }

        echo CMS::AdminModuleSection(22, "{li},{name}", $li . "<><>" . $_GET['name_tamlates']);
    } else {
        if (isset($_POST['edit']) && is_numeric($_POST['id'])) {
            $name = strip_tags($_POST['name']);
            $desc = strip_tags($_POST['desc']);

            if (!is_numeric($_POST['idAlbum']))
                $albumId = 0;
            else
                $albumId = $_POST['idAlbum'];
            if (isset($_POST['preload_img'])) {
                $_POST['preload_img'] = strip_tags(trim($_POST['preload_img']));
                $file = strip_tags("images/gallery/upload/" . $_POST['preload_img']);
                $file_name = $_POST['preload_img'];

                if (file_exists($file)) {
                    $SelectImg = Simple_DbApi::select_db($_GET['name_tamlates'], "*", "id", $_POST['id'], "", "", "", "");
                    $ns = current($SelectImg);
                    if ($ns['id_cat'] != $albumId) {
                        CMS::$db->query("update `" . _PREFIXDB_ . $_GET['name_tamlates'] . "` set `order`=(`order`-1) where `id_cat`='" . $ns['id_cat'] . "' and `order`>'" . $ns['order'] . "'");//
                        $selectmax = CMS::$db->query("select * from `" . _PREFIXDB_ . $_GET['name_tamlates'] . "` where `id_cat`='" . $albumId . "' order by `order` desc limit 1");
                        if (!empty($selectmax)) {
                            $nc = current($selectmax);
                            $order = $nc['order'] + 1;
                        } else $order = 1;
                    } else $order = $ns['order'];

                    if (file_exists("images/gallery/small/" . $ns['id'] . "." . $ns['type']))
                        unlink("images/gallery/small/" . $ns['id'] . "." . $ns['type']);
                    if (file_exists("images/gallery/big/" . $ns['id'] . "." . $ns['type']))
                        unlink("images/gallery/big/" . $ns['id'] . "." . $ns['type']);
                    if (file_exists("images/gallery/full/" . $ns['id'] . "." . $ns['type']))
                        unlink("images/gallery/full/" . $ns['id'] . "." . $ns['type']);

                    $path = "images/gallery/full/" . $ns['id'] . "." . simple_imagetest_format($file_name, 2);
                    $path1 = "images/gallery/big/" . $ns['id'] . "." . simple_imagetest_format($file_name, 2);
                    $path2 = "images/gallery/small/" . $ns['id'] . "." . simple_imagetest_format($file_name, 2);

                    copy($file, $path);
                    copy($file, $path1);
                    copy($file, $path2);
                    simple_image_save($BigWidth, $BigHeight, $path1, $path1, simple_imagetest_format($file_name, 2), 1);
                    simple_image_save($MinWidth, $MinHeight, $path2, $path2, simple_imagetest_format($file_name, 2), 1);
                    Simple_DbApi::update_db("gallery", "id_cat,type,name,desc,order", $albumId . "<><>" . simple_imagetest_format($file_name, 2) . "<><>" . $name . "<><>" . $desc . "<><>" . $order, "id", $_POST['id']);
                    if (isset($_SESSION['img_delete'])) if (file_exists($uploaddir . $_SESSION['img_delete'])) unlink($uploaddir . $_SESSION['img_delete']);    //cleanup
                    unset($_SESSION['img_delete']);
                    if (isset($_SESSION['img_name'])) unset($_SESSION['img_name']);
                    echo CMS::AlertWindow('Успешно', 'Данные обновлены', 1, 0);
                    CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> внес изменения в изображение в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-5-' . $_GET['id_cat'] . '.pl"><b>Редактирование изображений</b></a>');
                } else echo CMS::AlertWindow('Ошибка', 'Нет файла' . '--------' . $file, 3, 0);
            } else {
                $SelectImg = Simple_DbApi::select_db($_GET['name_tamlates'], "*", "id", $_POST['id'], "", "", "", "");
                $ns = current($SelectImg);
                if ($ns['id_cat'] != $albumId) {
                    CMS::$db->query("update `" . _PREFIXDB_ . $_GET['name_tamlates'] . "` set `order`=(`order`-1) where `id_cat`='" . $ns['id_cat'] . "' and `order`>'" . $ns['order'] . "'");//
                    $selectmax = CMS::$db->query("select * from `" . _PREFIXDB_ . $_GET['name_tamlates'] . "` where `id_cat`='" . $albumId . "' order by `order` desc limit 1");
                    if (!empty($selectmax)) {
                        $nc = current($selectmax);
                        $order = $nc['order'] + 1;
                    } else $order = 1;
                } else $order = $ns['order'];

                Simple_DbApi::update_db("gallery", "id_cat,name,desc,order", $albumId . "<><>" . $name . "<><>" . $desc . "<><>" . $order, "id", $_POST['id']);
                echo CMS::AlertWindow('Успешно', 'Данные обновлены', 1, 0);
                CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> внес изменения в изображение в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-5-' . $_GET['id_cat'] . '.pl"><b>Редактирование изображений</b></a>');
            }
        }
        $CountImage = Simple_DbApi::CountTable("gallery", "id_cat", $_GET['id_cat']);
        $maxPage = intval(($CountImage - 1) / $maxPhoto) + 1;

        $SelectA = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "id_c", $_GET['id_cat'], "", "", "", "");
        if (!empty($SelectA) || $_GET['id_cat'] == 0) {
            $nn = current($SelectA);

            if (!isset($_GET['id_news']) || !is_numeric($_GET['id_news']))
                $onP = 0;
            else
                $onP = $_GET['id_news'];

            $limit = abs($onP * $maxPhoto);

            if ($onP > 0) {
                $newlimit = $limit - 1;
            } else $newlimit = $limit;
            if ($onP < ($maxPage - 1)) {
                $newmaxPhoto = $maxPhoto + 1;
                if ($onP > 0) $newmaxPhoto++;
            } else {
                $newmaxPhoto = $maxPhoto;
                if ($onP > 0) $newmaxPhoto++;
            }

            $SelectImg = Simple_DbApi::select_db($_GET['name_tamlates'], "*", "id_cat", $_GET['id_cat'], "order", 1, $newlimit, $newmaxPhoto);
            $imgcount = count($SelectImg);

            for ($i = 0; $i < $imgcount; $i++) {
                if ($onP > 0) $ni[$i - 1] = array_shift($SelectImg);
                else $ni[$i] = array_shift($SelectImg);
            }
            $imgcount = $imgcount - ($newmaxPhoto - $maxPhoto);
            for ($i = 0; $i < $imgcount; $i++) {
                if ($_GET['id_cat'] == 0) $opt = "selected";
                else $opt = "";

                if ($imgcount == 1) {
                    $toup = "";
                    $todown = "";
                } else if ($i == 0) {
                    if ($onP > 0) $toup = CMS::AdminModuleSection('36-3', '{idimagethis},{idimagechange},{imagepage}', $ni[$i]['id'] . '<><>' . $ni[$i - 1]['id'] . '<><>' . $onP);
                    else $toup = "";
                    $todown = CMS::AdminModuleSection('36-4', '{idimagethis},{idimagechange},{imagepage}', $ni[$i]['id'] . '<><>' . $ni[$i + 1]['id'] . '<><>' . $onP);
                } else if ($i > 0 && $i != ($imgcount - 1)) {
                    $toup = CMS::AdminModuleSection('36-3', '{idimagethis},{idimagechange},{imagepage}', $ni[$i]['id'] . '<><>' . $ni[$i - 1]['id'] . '<><>' . $onP);
                    $todown = CMS::AdminModuleSection('36-4', '{idimagethis},{idimagechange},{imagepage}', $ni[$i]['id'] . '<><>' . $ni[$i + 1]['id'] . '<><>' . $onP);
                } else {
                    $toup = CMS::AdminModuleSection('36-3', '{idimagethis},{idimagechange},{imagepage}', $ni[$i]['id'] . '<><>' . $ni[$i - 1]['id'] . '<><>' . $onP);
                    if ($onP < ($maxPage - 1)) $todown = CMS::AdminModuleSection('36-4', '{idimagethis},{idimagechange},{imagepage}', $ni[$i]['id'] . '<><>' . $ni[$i + 1]['id'] . '<><>' . $onP);
                    else $todown = "";
                }

                $SelectCatList = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "", "", "order", 1, "", "");
                foreach ($SelectCatList as $j => $ng) {
                    if ($_GET['id_cat'] == $ng['id_c'])
                        $option .= CMS::AdminModuleSection(28, "{id},{opt},{name}", $ng['id_c'] . "<><>selected<><>" . $ng['name_c']);
                    else
                        $option .= CMS::AdminModuleSection(28, "{id},{opt},{name}", $ng['id_c'] . "<><><><>" . $ng['name_c']);
                }

                $rand = rand(1000, 9999);

                if (empty($ni[$i]['type']) || !file_exists("images/gallery/small/" . $ni[$i]['id'] . "." . $ni[$i]['type']))
                    $image = "../images/notamplates.png";
                else
                    $image = "../images/gallery/small/" . $ni[$i]['id'] . "." . $ni[$i]['type'] . "?" . $rand;

                $list .= CMS::AdminModuleSection(27, "{id},{name},{desc},{option},{opt},{image},{window},{toupimage},{todownimage}", $ni[$i]['id'] . "<><>" . $ni[$i]['name'] . "<><>" . $ni[$i]['desc'] . "<><>" . $option . "<><>" . $opt . "<><>" . $image . "<><>" . CMS::AlertWindow('Редактирование изображения', CMS::AdminModuleSection('27-1', '{id},{name},{desc},{option},{opt},{image},{modname}', $ni[$i]['id'] . "<><>" . $ni[$i]['name'] . "<><>" . $ni[$i]['desc'] . "<><>" . $option . "<><>" . $opt . "<><>" . $image . "<><>" . $_GET['name_tamlates']), 4, $ni[$i]['id']) . "<><>" . $toup . "<><>" . $todown);
                unset($option);
            }

            echo CMS::AdminModuleSection(24, "{list},{name},{id}", $list . "<><>" . $nn['name_c'] . '<><>' . $_GET['id_cat']);

            if ($CountImage > $maxPhoto) {

                $ListP = Simple_Page::NumberList($maxPage, $_GET['id_news'], CMS::AdminModuleSection('26-2', '{nameT},{id}', $_GET['name_tamlates'] . '<><>' . $_GET['id_cat']), CMS::AdminModuleSection('26-3', '{nameT},{id}', $_GET['name_tamlates'] . '<><>' . $_GET['id_cat']), CMS::AdminModuleSection(25, '', ''), CMS::AdminModuleSection(26, "{nameT},{id}", $_GET['name_tamlates'] . "<><>" . $_GET['id_cat']), $MaxNumbers);

                echo CMS::AdminModuleSection('26-1', "{list}", $ListP);
            }

        }

    }

}
### DELETE IMAGE
if ($_GET['page'] == 6) {
    if (!isset($_GET['id_cat']) || !is_numeric($_GET['id_cat'])) {
        $SelectCat = Simple_DbApi::select_db("gallery_cat", "*", "", "", "order", 1, "", "");
        foreach ($SelectCat as $i => $nc) {
            $li .= CMS::AdminModuleSection(30, "{nameT},{id},{name}", $_GET['name_tamlates'] . "<><>" . $nc['id_c'] . "<><>" . $nc['name_c']);
        }

        echo CMS::AdminModuleSection(29, "{li},{name}", $li . "<><>" . $_GET['name_tamlates']);

    } else {
        if (isset($_POST['delete']) && is_numeric($_POST['id'])) {

            $SelectImId = Simple_DbApi::select_db($_GET['name_tamlates'], "*", "id", $_POST['id'], "", "", "", "");
            if (!empty($SelectImId)) {
                $ndi = current($SelectImId);
                if (file_exists("images/gallery/full/" . $ndi['id'] . "." . $ndi['type'])) unlink("images/gallery/full/" . $ndi['id'] . "." . $ndi['type']);
                if (file_exists("images/gallery/small/" . $ndi['id'] . "." . $ndi['type'])) unlink("images/gallery/small/" . $ndi['id'] . "." . $ndi['type']);
                if (file_exists("images/gallery/big/" . $ndi['id'] . "." . $ndi['type'])) unlink("images/gallery/big/" . $ndi['id'] . "." . $ndi['type']);
                CMS::$db->query("update `" . _PREFIXDB_ . $_GET['name_tamlates'] . "` set `order`=(`order`-1) where `id_cat`='" . $ndi['id_cat'] . "' and `order`>'" . $ndi['order'] . "'");//
                Simple_DbApi::delete_db($_GET['name_tamlates'], "id", $_POST['id']);
                echo CMS::AlertWindow('Успешно', 'Изображение удалено', 1, 0);
                CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> удалил изображение в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-6-' . $_GET['id_cat'] . '.pl"><b>Удаление изображений</b></a>');
            }

        }

        $SelectA = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "id_c", $_GET['id_cat'], "", "", "", "");
        if (!empty($SelectA) || $_GET['id_cat'] == 0) {
            $nn = current($SelectA);

            if (!isset($_GET['id_news']) || !is_numeric($_GET['id_news']))
                $onP = 0;
            else
                $onP = $_GET['id_news'];

            $limit = abs($onP * $maxPhoto);

            $SelectImg = Simple_DbApi::select_db($_GET['name_tamlates'], "*", "id_cat", $_GET['id_cat'], "id", 2, $limit, $maxPhoto);
            foreach ($SelectImg as $i => $ni) {
                $rand = rand(1000, 9999);

                if (empty($ni['type']) || !file_exists("images/gallery/small/" . $ni['id'] . "." . $ni['type']))
                    $image = "../images/notamplates.png";
                else
                    $image = "../images/gallery/small/" . $ni['id'] . "." . $ni['type'] . "?" . $rand;

                $list .= CMS::AdminModuleSection(33, "{id},{name},{image},{window}", $ni['id'] . "<><>" . $ni['name'] . "<><>" . $image . "<><>" . CMS::AlertWindow('Удаление изображения', CMS::AdminModuleSection('33-1', '{name},{id}', $ni['name'] . '<><>' . $ni['id']), 2, $ni['id']));
            }

            echo CMS::AdminModuleSection(34, "{list},{name}", $list . "<><>" . $nn['name_c']);

            $CountImage = Simple_DbApi::CountTable("gallery", "id_cat", $_GET['id_cat']);

            if ($CountImage > $maxPhoto) {
                $maxPage = intval(($CountImage - 1) / $maxPhoto) + 1;
                $ListP = Simple_Page::NumberList($maxPage, $_GET['id_news'], CMS::AdminModuleSection('32-2', '{nameT},{id}', $_GET['name_tamlates'] . '<><>' . $_GET['id_cat']), CMS::AdminModuleSection('32-3', '{nameT},{id}', $_GET['name_tamlates'] . '<><>' . $_GET['id_cat']), CMS::AdminModuleSection(31, '', ''), CMS::AdminModuleSection(32, "{nameT},{id}", $_GET['name_tamlates'] . "<><>" . $_GET['id_cat']), $MaxNumbers);
                echo CMS::AdminModuleSection('32-1', "{list}", $ListP);
            }
        }
    }
}
?>