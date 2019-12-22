<?php
$uploaddir = 'images/' . $_GET['name_tamlates'] . '/upload/';
$filetypes = array('jpg', 'gif', 'png', 'JPG', 'GIF', 'PNG', 'jpeg', 'JPEG');
$maxPhoto = 10;
if (empty($_POST['key'])) {
    $_POST['key'] = '';
}
$List = '';
if ($_POST['key'] == 'add') {
    if (!isset($_SESSION['img_name'])) $_SESSION['img_name'] = date("Y-m-d") . "_" . date("H-i-s");
    else if (isset($_SESSION['img_delete'])) if (file_exists($uploaddir . $_SESSION['img_delete'])) unlink($uploaddir . $_SESSION['img_delete']);
    $ext = pathinfo($_FILES['uploadfile']['name'], PATHINFO_EXTENSION);
    $file_name = $_SESSION['img_name'] . "." . $ext;
    $file = $uploaddir . $_SESSION['img_name'] . "." . $ext;

    if (!in_array($ext, $filetypes)) echo "error";
    else {
        if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) {
            echo "success," . $file_name;
            $_SESSION['img_delete'] = $file_name;
        } else {
            echo "error";
        }
    }
}
if ($_POST['key'] == 'edit') {
    if (!isset($_SESSION['img_name'])) $_SESSION['img_name'] = date("Y-m-d") . "_" . date("H-i-s");
    else if (isset($_SESSION['img_delete'])) if (file_exists($uploaddir . $_SESSION['img_delete'])) unlink($uploaddir . $_SESSION['img_delete']);
    $ext = pathinfo($_FILES['uploadfile']['name'], PATHINFO_EXTENSION);
    $file_name = $_SESSION['img_name'] . "." . $ext;
    $file = $uploaddir . $_SESSION['img_name'] . "." . $ext;

    if (!in_array($ext, $filetypes)) echo "error";
    else {
        if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) {
            echo "success," . $file_name;
            $_SESSION['img_delete'] = $file_name;
        } else {
            echo "error";
        }
    }
}
if (!empty($_GET['cleanup']) && $_GET['cleanup'] == 1) {
    if (isset($_SESSION['img_delete'])) {
        if (file_exists($uploaddir . $_SESSION['img_delete'])) unlink($uploaddir . $_SESSION['img_delete']);
        unset($_SESSION['img_delete']);
        if (isset($_SESSION['img_name'])) unset($_SESSION['img_name']);
    }
}
#change order album
if (!empty($_GET['idalbumthis']) && !empty($_GET['idalbumchange']) && is_numeric($_GET['idalbumthis']) && is_numeric($_GET['idalbumchange'])) {
    $SelectThisId = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "id_c", $_GET['idalbumthis'] . "", "", "", "", "");
    if (!empty($SelectThisId)) $nThis = current($SelectThisId);
    $SelectChangeId = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "id_c", $_GET['idalbumchange'] . "", "", "", "", "");
    if (!empty($SelectChangeId)) $nChange = current($SelectChangeId);
    if ($nThis && $nChange) {
        $NewThisOrder = $nChange['order'];
        $NewChangeOrder = $nThis['order'];
        Simple_DbApi::update_db($_GET['name_tamlates'] . "_cat", "order", $NewThisOrder, "id_c", $_GET['idalbumthis']);
        Simple_DbApi::update_db($_GET['name_tamlates'] . "_cat", "order", $NewChangeOrder, "id_c", $_GET['idalbumchange']);


        $na = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "", "", "order", 1, "", "");
        $numalbum = count($na);
        $typeTr = '';
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
            $typeTr = $typeTr == 'odd' ? 'int' : 'odd';
            $List .= CMS::AdminModuleSection(11, "{id},{name},{image},{window},{toupalbum},{todownalbum},{td}", $na[$i]['id_c'] . "<><>" . $na[$i]['name_c'] . "<><>" . $image . "<><>" . $alert_window . "<><>" . $toup . "<><>" . $todown . '<><>' . $typeTr);
        }
        echo CMS::AdminModuleSection(10, "{list}", $List);
    } else echo "error!";
}
#change order image
if (!empty($_GET['idimagethis']) && !empty($_GET['idimagechange']) && is_numeric($_GET['idimagethis']) && is_numeric($_GET['idimagechange'])) {
    $maxPhoto = 10;
    $MaxNumbers = 10;
    $CountImage = Simple_DbApi::CountTable("gallery", "id_cat", $_GET['id_cat']);
    $maxPage = intval(($CountImage - 1) / $maxPhoto) + 1;

    $SelectThisId = Simple_DbApi::select_db($_GET['name_tamlates'], "*", "id", $_GET['idimagethis'] . "", "", "", "", "");
    if (!empty($SelectThisId)) $nThis = current($SelectThisId);
    $SelectChangeId = Simple_DbApi::select_db($_GET['name_tamlates'], "*", "id", $_GET['idimagechange'] . "", "", "", "", "");
    if (!empty($SelectChangeId)) $nChange = current($SelectChangeId);
    if ($nThis && $nChange) {
        $NewThisOrder = $nChange['order'];
        $NewChangeOrder = $nThis['order'];
        Simple_DbApi::update_db($_GET['name_tamlates'], "order", $NewThisOrder, "id", $_GET['idimagethis']);
        Simple_DbApi::update_db($_GET['name_tamlates'], "order", $NewChangeOrder, "id", $_GET['idimagechange']);


        $CountImage = Simple_DbApi::CountTable("gallery", "id_cat", $_GET['id_cat']);
        $maxPage = intval(($CountImage - 1) / $maxPhoto) + 1;

        $SelectA = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "id_c", $_GET['id_cat'], "", "", "", "");
        if (!empty($SelectA) || $_GET['id_cat'] == 0) {
            $nn = current($SelectA);

            if (!isset($_GET['imagepage']) || !is_numeric($_GET['imagepage']))
                $onP = 0;
            else
                $onP = $_GET['imagepage'];

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
                $typeTr = $typeTr == 'odd' ? 'int' : 'odd';

                $window = CMS::AlertWindow('Редактирование изображения', CMS::AdminModuleSection('27-1', '{id},{name},{desc},{option},{opt},{image},{modname}', $ni[$i]['id'] . "<><>" . $ni[$i]['name'] . "<><>" . $ni[$i]['desc'] . "<><>" . $option . "<><>" . $opt . "<><>" . $image . "<><>" . $_GET['name_tamlates']), 4, $ni[$i]['id']);

                $list .= CMS::AdminModuleSection(27, "{id},{name},{desc},{option},{opt},{image},{window},{toupimage},{todownimage},{td}", $ni[$i]['id'] . "<><>" . $ni[$i]['name'] . "<><>" . $ni[$i]['desc'] . "<><>" . $option . "<><>" . $opt . "<><>" . $image . "<><>" . $window . "<><>" . $toup . "<><>" . $todown . '<><>' . $typeTr);
                unset($option);
            }

            echo CMS::AdminModuleSection(24, "{list},{name}", $list . "<><>" . $nn['name_c']);
        }
    } else echo "error!";
}
?>