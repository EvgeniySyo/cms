<?php
CMS::CoreComponent('page');
$maxProducts = 10;
$MaxNumbers = 10;
$TitleModule = CMS::TitleComponent($_GET['name_tamlates']);
include(DATA_PATH . "modules/" . $_GET['name_tamlates'] . "/towns.php");
//print_r($deltowns);

if (!isset($_GET['page'])) echo CMS::AdminModuleSection(1, '{NameModule}', $_GET['name_tamlates']);
if ($_GET['page'] >= 1 && $_GET['page'] <= 9) {
    $style1 = "";
    $style2 = "";
    $style3 = "";
    $list1 = "";
    $list2 = "";
    $list3 = "";
    if ($_GET['page'] == 1 || $_GET['page'] == 4 || $_GET['page'] == 7) {
        $list1 = "&#8595;";
        $list2 = "&#8594;";
        $list3 = "&#8594;";
        $style1 = "style=\"background:#b7ddf2;\"";
    }
    if ($_GET['page'] == 2 || $_GET['page'] == 5 || $_GET['page'] == 8) {
        $list1 = "&#8592;";
        $list2 = "&#8595;";
        $list3 = "&#8594;";
        $style2 = "style=\"background:#b7ddf2;\"";
    }
    if ($_GET['page'] == 3 || $_GET['page'] == 6 || $_GET['page'] == 9) {
        $list1 = "&#8592;";
        $list2 = "&#8592;";
        $list3 = "&#8595;";
        $style3 = "style=\"background:#b7ddf2;\"";
    }
}

if ($_GET['page'] == 1 || $_GET['page'] == 2 || $_GET['page'] == 3) $top = CMS::AdminModuleSection(5, "{NameModule},{style-1},{style-2},{style-3},{list-1},{list-2},{list-3}", $_GET['name_tamlates'] . "<><>" . $style1 . "<><>" . $style2 . "<><>" . $style3 . "<><>" . $list1 . "<><>" . $list2 . "<><>" . $list3);


function getExtension($filename)
{
    return end(explode(".", $filename));
}


switch ($_GET['page']) {
    case 1:
        // add delivery
        $cityid = '';
        echo CMS::AdminModuleSection(2);
        if (isset($_POST['add'])) {
            $name = strip_tags($_POST['name']);
            $desc = $_POST['desc'];
            $href = strip_tags($_POST['href']);
            $coords = strip_tags($_POST['coords']);

            $file1 = $_FILES['default']['tmp_name'];
            $file_name1 = $_FILES['default']['name'];

            $IdShop = Simple_DbApi::auto_increment($_GET['name_tamlates']);
            $ImageSave = false;
            if (!empty($name)) {

                if (!empty($file_name1)) {
                    if (simple_imagetest_format($file_name1, 1) == true) {
                        $idImg = Simple_DbApi::auto_increment($_GET['name_tamlates'] . '_img');
                        $pathDefault = "images/" . $_GET['name_tamlates'] . "/" . $idImg . "." . simple_imagetest_format($file_name1, 2);
                        $pathDefault1 = "images/" . $_GET['name_tamlates'] . "/preview_" . $idImg . "." . simple_imagetest_format($file_name1, 2);
                        copy($file1, $pathDefault);
                        move_uploaded_file($file1, $pathDefault1);
                        simple_image_save($ProductSmallWidth, $ProductSmallHeight, $pathDefault1, $pathDefault1, simple_imagetest_format($file_name1, 2), 1);
                        simple_image_save($ProductWidth, $ProductHeight, $pathDefault, $pathDefault, simple_imagetest_format($file_name1, 2), 1);
                        $ImageSave = true;
                        Simple_DbApi::insert_db($_GET['name_tamlates'] . '_img', 'id,store_id,ext,type', '<><>' . $IdShop . '<><>' . simple_imagetest_format($file_name1, 2) . '<><>scheme');
                    }
                } else $ImageSave = true;
                if ($ImageSave = true) {
                    //Adition Image
                    $CountAddition = count($_FILES['file']['name']);
                    if ($CountAddition > 0) {
                        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
                            $file = $_FILES['file']['tmp_name'][$i];
                            $file_name = $_FILES['file']['name'][$i];

                            if (!empty($file_name)) {
                                if (simple_imagetest_format($file_name, 1) == true) {
                                    $idImg = Simple_DbApi::auto_increment($_GET['name_tamlates'] . '_img');
                                    $path = "images/" . $_GET['name_tamlates'] . "/" . $idImg . "." . simple_imagetest_format($file_name, 2);
                                    $path1 = "images/" . $_GET['name_tamlates'] . "/preview_" . $idImg . "." . simple_imagetest_format($file_name, 2);
                                    copy($file, $path);
                                    move_uploaded_file($file, $path1);
                                    simple_image_save($ProductSmallWidth, $ProductSmallHeight, $path1, $path1, simple_imagetest_format($file_name, 2), 1);
                                    simple_image_save($ProductWidth, $ProductHeight, $path, $path, simple_imagetest_format($file_name, 2), 1);
                                    Simple_DbApi::insert_db($_GET['name_tamlates'] . '_img', 'id,store_id,ext,type', '<><>' . $IdShop . '<><>' . simple_imagetest_format($file_name, 2) . '<><>');
                                }
                            }
                        }
                    }


                    $CountShop = Simple_DbApi::CountTable($_GET['name_tamlates'], '', '');


                    Simple_DbApi::insert_db($_GET['name_tamlates'], 'id,name,desc,coords,href', '<><>' . $name . '<><>' . $desc . '<><>' . $coords . '<><>' . $href);


                    echo CMS::AlertWindow('Успешно', 'Пункт добавлен', 1, 0);
                    CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> добавил Пункт <b>' . $name . '</b> в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-1.pl"><b>Добавить Пункт</b></a>');
                } else echo CMS::AlertWindow('Ошибка', 'Неверный формат изображения', 3, 0);
            } else echo CMS::AlertWindow('Ошибка', 'Требуется указать название', 3, 0);
        }


        $editor = CMS::CKeditor('', '', 'desc');
        echo CMS::AdminModuleSection('add', '{top},{editor}', $top . '<><>' . $editor);
        echo CMS::AdminModuleSection(3);
        break;
    case 2:
        // edit shop
        echo CMS::AdminModuleSection(2);

        if (!is_numeric($_GET['id_cat'])) {
            $count = Simple_DbApi::CountTable($_GET['name_tamlates'], '', '');
            if ($count > 0) {

                $SelectProduct = Simple_DbApi::query_db(" SELECT * FROM `" . _PREFIXDB_ . $_GET['name_tamlates'] . "` ");
                if (!empty($SelectProduct)) {
                    foreach ($SelectProduct as $i => $np) {
                        $list .= CMS::AdminModuleSection(24, '{name},{desc},{coords},{id},{NameModule}', $np['name'] . '<><>' . $np['desc'] . '<><>' . $np['coords'] . '<><>' . $np['id'] . '<><>' . $_GET['name_tamlates']);
                    }

                    echo CMS::AdminModuleSection(23, '{list},{top}', $list . '<><>' . $top);
                } else echo CMS::AdminModuleSection(28, '{top}', $top);
            } else echo CMS::AdminModuleSection(28, '{top}', $top);
        } else {

            if (isset($_POST['edit']) && is_numeric($_POST['id'])) {
                $name = strip_tags($_POST['address']);
                $desc = $_POST['desc'];
                $href = strip_tags($_POST['href']);
                $coords = strip_tags($_POST['coords']);

                $file1 = $_FILES['default']['tmp_name'];
                $file_name1 = $_FILES['default']['name'];

                if (!empty($name)) {
                    if ($_POST['deleteDef'] == 'on') {
                        $SelectImage = Simple_DbApi::select_db($_GET['name_tamlates'] . '_img', '*', 'store_id,type', $_POST['id'] . '<><>scheme', '', '', '', '');
                        if (!empty($SelectImage)) {
                            $nd = current($SelectImage);
                            if (file_exists('images/' . $_GET['name_tamlates'] . '/' . $nd['id'] . '.' . $nd['ext'])) unlink('images/' . $_GET['name_tamlates'] . '/' . $nd['id'] . '.' . $nd['ext']);
                            if (file_exists('images/' . $_GET['name_tamlates'] . '/preview_' . $nd['id'] . '.' . $nd['ext'])) unlink('images/' . $_GET['name_tamlates'] . '/preview_' . $nd['id'] . '.' . $nd['ext']);
                            Simple_DbApi::delete_db($_GET['name_tamlates'] . '_img', 'id', $nd['id']);
                        }
                    }

                    if (count($_POST['delete']) > 0) {
                        foreach ($_POST['delete'] as $DelImage => $id) {
                            $SelectImage = Simple_DbApi::select_db($_GET['name_tamlates'] . '_img', '*', 'id', $DelImage, '', '', '', '');
                            if (!empty($SelectImage)) {
                                $nd = current($SelectImage);
                                if (file_exists('images/' . $_GET['name_tamlates'] . '/' . $nd['id'] . '.' . $nd['ext'])) unlink('images/' . $_GET['name_tamlates'] . '/' . $nd['id'] . '.' . $nd['ext']);
                                if (file_exists('images/' . $_GET['name_tamlates'] . '/preview_' . $nd['id'] . '.' . $nd['ext'])) unlink('images/' . $_GET['name_tamlates'] . '/preview_' . $nd['id'] . '.' . $nd['ext']);
                                Simple_DbApi::delete_db($_GET['name_tamlates'] . '_img', 'id', $nd['id']);
                            }
                        }
                    }

                    $ImageSave = false;
                    if (!empty($file_name1)) {
                        if (simple_imagetest_format($file_name1, 1) == true) {
                            $idImg = Simple_DbApi::auto_increment($_GET['name_tamlates'] . '_img');
                            $pathDefault = "images/" . $_GET['name_tamlates'] . "/" . $idImg . "." . simple_imagetest_format($file_name1, 2);
                            $pathDefault1 = "images/" . $_GET['name_tamlates'] . "/preview_" . $idImg . "." . simple_imagetest_format($file_name1, 2);
                            copy($file1, $pathDefault);
                            move_uploaded_file($file1, $pathDefault1);
                            simple_image_save($ProductSmallWidth, $ProductSmallHeight, $pathDefault1, $pathDefault1, simple_imagetest_format($file_name1, 2), 1);
                            simple_image_save($ProductWidth, $ProductHeight, $pathDefault, $pathDefault, simple_imagetest_format($file_name1, 2), 1);
                            $ImageSave = true;

                            $SelectImage = Simple_DbApi::select_db($_GET['name_tamlates'] . '_img', '*', 'store_id,type', $_POST['id'] . '<><>scheme', '', '', '', '');
                            if (!empty($SelectImage)) {
                                $nd = current($SelectImage);
                                if (file_exists('images/' . $_GET['name_tamlates'] . '/' . $nd['id'] . '.' . $nd['ext'])) unlink('images/' . $_GET['name_tamlates'] . '/' . $nd['id'] . '.' . $nd['ext']);
                                if (file_exists('images/' . $_GET['name_tamlates'] . '/preview_' . $nd['id'] . '.' . $nd['ext'])) unlink('images/' . $_GET['name_tamlates'] . '/preview_' . $nd['id'] . '.' . $nd['ext']);
                                Simple_DbApi::delete_db($_GET['name_tamlates'] . '_img', 'id', $nd['id']);
                            }

                            Simple_DbApi::insert_db($_GET['name_tamlates'] . '_img', 'id,store_id,ext,type', '<><>' . $_POST['id'] . '<><>' . simple_imagetest_format($file_name1, 2) . '<><>scheme');

                        }
                    } else $ImageSave = true;
                    if ($ImageSave = true) {
                        //Adition Image
                        $CountAddition = count($_FILES['file']['name']);
                        if ($CountAddition > 0) {
                            for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
                                $file = $_FILES['file']['tmp_name'][$i];
                                $file_name = $_FILES['file']['name'][$i];
                                if (!empty($file_name)) {
                                    if (simple_imagetest_format($file_name, 1) == true) {
                                        $idImg = Simple_DbApi::auto_increment($_GET['name_tamlates'] . '_img');
                                        $path = "images/" . $_GET['name_tamlates'] . "/" . $idImg . "." . simple_imagetest_format($file_name, 2);
                                        $path1 = "images/" . $_GET['name_tamlates'] . "/preview_" . $idImg . "." . simple_imagetest_format($file_name, 2);
                                        copy($file, $path);
                                        move_uploaded_file($file, $path1);
                                        simple_image_save($ProductSmallWidth, $ProductSmallHeight, $path1, $path1, simple_imagetest_format($file_name, 2), 1);
                                        simple_image_save($ProductWidth, $ProductHeight, $path, $path, simple_imagetest_format($file_name, 2), 1);
                                        Simple_DbApi::insert_db($_GET['name_tamlates'] . '_img', 'id,store_id,ext,type', '<><>' . $_POST['id'] . '<><>' . simple_imagetest_format($file_name, 2) . '<><>');
                                    }
                                }
                            }
                        }


                        Simple_DbApi::update_db($_GET['name_tamlates'], 'name,desc,coords,href', $name . '<><>' . $desc . '<><>' . $coords . '<><>' . $href, 'id', $_POST['id']);

                        CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> изменил информацию о товаре <b>' . $name . '</b> в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-5-' . $_GET['id_cat'] . '-' . $_GET['id_news'] . '-' . $_GET['id_st'] . '.pl"><b>Редактировать товары</b></a>');
                        echo CMS::AlertWindow('Успешно', 'Данные обновлены', 1, 0);
                    } else echo CMS::AlertWindow('Ошибка', 'Неверный формат изображения', 3, 0);
                } else echo CMS::AlertWindow('Ошибка', 'Требуется указать название', 3, 0);

            }

            $select = Simple_DbApi::query_db("SELECT * FROM `" . _PREFIXDB_ . $_GET['name_tamlates'] . "` WHERE `id` = " . $_GET['id_cat'] . " ");

            if (!empty($select)) {
                // info
                // 0 - id, 1 - id_cat, 2 - name, 3 - desc, 4 - money, 5 - status, 6 - response, 7 - date, 8 - order, 9 - country,
                // 10 - small, 11 - box , 12 - brand , 13 - structure, 14 - appli

                $np = current($select);

                $SelectImage = Simple_DbApi::select_db($_GET['name_tamlates'] . '_img', '*', 'store_id,type', $_GET['id_cat'] . '<><>' . '', 'type', '', '', '');
                if (!empty($SelectImage)) {
                    foreach ($SelectImage as $i => $ni) {
                        if ($ni['type'] == '1') {
                            $p = 'images/' . $_GET['name_tamlates'] . '/' . $ni['id'] . '.' . $ni['ext'];
                            $image = file_exists($p) ? CMS::AdminModuleSection(31, '{img}', $p) : '';
                        } else {
                            $p = 'images/' . $_GET['name_tamlates'] . '/' . $ni['id'] . '.' . $ni['ext'];
                            $img .= file_exists($p) ? CMS::AdminModuleSection(32, '{img},{id}', $p . '<><>' . $ni['id']) : '';
                        }
                    }
                }
                $editor = CMS::CKeditor($np['desc'], '', 'desc');
                echo CMS::AdminModuleSection('edit', '{top},{name},{editor},{coords},{href},{NameModule},{image},{i},{id},{editor}',
                    $top . '<><>' . $np['name'] . '<><>' . $editor . '<><>' . $np['coords'] . '<><>' . $np['href'] . '<><>' . $_GET['name_tamlates'] . '<><>' . $image . '<><>' . $img . '<><>' . $np['id'] . '<><>' . $editor);
            }
        }


        echo CMS::AdminModuleSection(3);
        break;
    case 3:
        // delete delivery
        echo CMS::AdminModuleSection(2);

        if (isset($_POST['delete']) && is_numeric($_POST['id'])) {
            $SelectType = Simple_DbApi::select_db($_GET['name_tamlates'], '*', 'id', $_POST['id'], '', '', '', '');
            if (!empty($SelectType)) {
                $nj = current($SelectType);
                $SelectImageDelete = Simple_DbApi::select_db($_GET['name_tamlates'] . "_img", '*', 'store_id', $_POST['id'], '', '', '', '');
                if (!empty($SelectImageDelete)) {
                    foreach ($SelectImageDelete as $i => $nd) {
                        if (file_exists('images/' . $_GET['name_tamlates'] . '/' . $nd['id'] . '.' . $nd['ext'])) unlink('images/' . $_GET['name_tamlates'] . '/' . $nd['id'] . '.' . $nd['ext']);
                        if (file_exists('images/' . $_GET['name_tamlates'] . '/preview_' . $nd['id'] . '.' . $nd['ext'])) unlink('images/' . $_GET['name_tamlates'] . '/preview_' . $nd['id'] . '.' . $nd['ext']);
                        Simple_DbApi::delete_db($_GET['name_tamlates'] . '_img', 'id', $nd['id']);
                    }
                }

                Simple_DbApi::delete_db($_GET['name_tamlates'], 'id', $_POST['id']);

                CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> удалил товар <b>' . $nj['name'] . '</b> в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-6-' . $_GET['id_cat'] . '.pl"><b>Удалить товары</b></a>');
                //Simple_DbApi::delete_db('top','id',$_POST['id']);

                echo CMS::AlertWindow('Успешно', 'Пункт удален', 1, 0);
            }
        }

        $Selectdel = Simple_DbApi::select_db($_GET['name_tamlates'], '*', '', '', '', '', '', '');
        if (!empty($Selectdel)) {
            $delstore = '';
            foreach ($Selectdel as $i => $dd) {
                $delstore .= CMS::AdminModuleSection('42-1', '{name},{desc},{coords},{id}', $dd['name'] . '<><>' . $dd['desc'] . '<><>' . $dd['coords'] . '<><>' . $dd['id']);
            }
            echo CMS::AdminModuleSection(42, '{delete}', $delstore);
        }

        echo CMS::AdminModuleSection(3);
        break;

    default:

        if (isset($_POST['save'])) {
            $ProductWidth = !is_numeric($_POST['ProWidth']) ? 500 : $_POST['ProWidth'];
            $ProductHeight = !is_numeric($_POST['ProHeight']) ? 500 : $_POST['ProHeight'];
            $ProductSmallWidth = !is_numeric($_POST['sProWidth']) ? 100 : $_POST['sProWidth'];
            $ProductSmallHeight = !is_numeric($_POST['sProHeight']) ? 100 : $_POST['sProHeight'];

            ### END ###

            $file = file(DATA_PATH . "modules/" . $_GET['name_tamlates'] . "/config.php");
            $fp = fopen(DATA_PATH . "modules/" . $_GET['name_tamlates'] . "/config.php", "w");
            flock($fp, LOCK_EX);
            for ($i = 0; $i < count($file); $i++) {
                if ($i == 1) fwrite($fp, "\$ProductWidth = " . $ProductWidth . ";\n");
                elseif ($i == 2) fwrite($fp, "\$ProductHeight = " . $ProductHeight . ";\n");
                elseif ($i == 3) fwrite($fp, "\$ProductSmallWidth = " . $ProductSmallWidth . ";\n");
                elseif ($i == 4) fwrite($fp, "\$ProductSmallHeight = " . $ProductSmallHeight . ";\n");
                else fwrite($fp, $file[$i]);
            }
            flock($fp, LOCK_UN);
            fclose($fp);

            echo CMS::AlertWindow('Успешно', 'Данные обновлены', 1, 0);
            CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> изменил настройки в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '.pl"><b>' . $TitleModule . '</b></a>');
        }
        if (isset($_POST['savetext'])) {
            $fp = fopen(DATA_PATH . "modules/" . $_GET['name_tamlates'] . "/text.txt", "w");
            flock($fp, LOCK_EX);
            fwrite($fp, $_POST['maintext']);
            flock($fp, LOCK_UN);
            fclose($fp);
        }


        $text = file_get_contents(DATA_PATH . "modules/" . $_GET['name_tamlates'] . "/text.txt");
        $editor = CMS::CKeditor($text, '', 'maintext');
        echo CMS::AdminModuleSection(1, '{NameModule},{ProWidth},{ProHeight},{sProWidth},{sProHeight},{editor}', $_GET['name_tamlates'] . '<><>' . $ProductWidth . '<><>' . $ProductHeight . '<><>' . $ProductSmallWidth . '<><>' . $ProductSmallHeight . '<><>' . $editor);


        break;
}

?>