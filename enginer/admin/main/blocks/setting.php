<?php

$TemplatesSection = CMS::SectionFile('settingblock');


### UPDATE INFORMATION ###

if (isset($_GET['pos'])) {

    if ($_GET['pos'] == "left" || $_GET['pos'] == "right" || $_GET['pos'] == "center" || $_GET['pos'] == "down") {
        if ($_GET['posision'] == "down") {
            CMS::$db->query("UPDATE `block` SET `ves` = '" . ($_GET['f'] - 1) . "' WHERE `ves` = '" . $_GET['f'] . "' AND `posision` = '" . $_GET['pos'] . "'");
            CMS::$db->query("UPDATE `block` SET `ves` = '" . $_GET['f'] . "' WHERE `name_block` = '" . $_GET['nameblock'] . "' AND `posision` = '" . $_GET['pos'] . "'");
            echo CMS::AlertWindow('Успешно', 'Данные обновлены', 1, 0);
            CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> внес изменения. Раздел <a target="_blank" href="main-blocks-setting.pl"><b>Настройка блоков</b></a>');
            CMS::DesctroyCacheBlock();
        }

        if ($_GET['posision'] == "up") {

            CMS::$db->query("UPDATE `block` SET `ves` = '" . ($_GET['f'] + 1) . "' WHERE `ves` = '" . $_GET['f'] . "' AND `posision` = '" . $_GET['pos'] . "'");
            CMS::$db->query("UPDATE `block` SET `ves` = '" . $_GET['f'] . "' WHERE `name_block` = '" . $_GET['nameblock'] . "' AND `posision` = '" . $_GET['pos'] . "'");
            echo CMS::AlertWindow('Успешно', 'Данные обновлены', 1, 0);
            CMS::DesctroyCacheBlock();
            CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> внес изменения. Раздел <a target="_blank" href="main-blocks-setting.pl"><b>Настройка блоков</b></a>');
        }
    }

}

if (isset($_POST['save'])) {
    $countBlock = count($_POST['nameblock']);

    if ($countBlock > 0) {
        for ($t = 0; $t < $countBlock; $t++) {
            $systemNameBlock = $_POST['nameblock'][$t];
            $Title = strip_tags($_POST['title'][$t]);
            if ($_POST['q'][$t] == 1) $St = "on";
            else $St = "off";

            if ($_POST['show'][$t] == "on") $Show2 = "on";
            else $Show2 = "off";

            if ($_POST['pos'][$t] == 1) $PosBlock = "left";
            if ($_POST['pos'][$t] == 2) $PosBlock = "right";
            if ($_POST['pos'][$t] == 3) $PosBlock = "center";
            if ($_POST['pos'][$t] == 4) $PosBlock = "down";

            Simple_DbApi::update_db("block", "posision,status,titul,titul_on", "" . $PosBlock . "<><>" . $St . "<><>" . $Title . "<><>" . $Show2 . "", "name_block", $systemNameBlock);

        }
        CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> внес изменения. Раздел <a target="_blank" href="main-blocks-setting.pl"><b>Настройка блоков</b></a>');

        echo CMS::AlertWindow('Успешно', 'Данные обновлены', 1, 0);
    }
}

### END ###


echo CMS::SectionAdmin($TemplatesSection, 1, "", "");

### LEFT ###

$SelectBlockLeft = Simple_DbApi::select_db("block", "*", "posision,install", "left<><>yes", "ves", 1, "", "");
if (!empty($SelectBlockLeft)) {
    $countBlockLeft  = count($SelectBlockLeft);
    $Position = "left";
    echo CMS::SectionAdmin($TemplatesSection, 3, "", "");

    foreach ($SelectBlockLeft as $i => $nl) {
        if (!isset($PosB)) $PosB = 1;
        else $PosB = $PosB + 1;
        if (file_exists("block/" . $nl['name_block'] . "/title.php")) include("block/" . $nl['name_block'] . "/title.php");
        if (empty($BlockName)) $BlockName = "no name";

        if ($nl['titul_on'] == "on") $ch = "checked=\"checked\"";
        else $ch = "";

        $check1 = "";
        $check2 = "";

        if ($nl['status'] == "on") $check1 = "checked=\"checked\"";
        else $check2 = "checked=\"checked\"";

        if (($countBlockLeft - 1) != $i) {
            if ($Up == false) $UpDown = CMS::SectionAdmin($TemplatesSection, 13, "%ID%", $nl['name_block']);
            else $UpDown = CMS::SectionAdmin($TemplatesSection, 14, "%N%,%N1%,LAST,%ID%", "" . ($PosB + 1) . "<><>" . ($PosB - 1) . "<><>" . $NameLastBlock . "<><>" . $nl['name_block'] . "");
        } else $UpDown = CMS::SectionAdmin($TemplatesSection, 18, "%N%,%N1%,LAST,%ID%", "" . ($PosB + 1) . "<><>" . ($PosB - 1) . "<><>" . $NameLastBlock . "<><>" . $nl['name_block'] . "");

        $Pos = CMS::SectionAdmin($TemplatesSection, 9, "{i},{s1},{s2},{s3},{s4}", $i . "<><>selected<><><><><><>");

        if ($i == 0) $goF = CMS::SectionAdmin($TemplatesSection, 11, "{name},{num},{pos}", $nl['name_block'] . "<><>" . ($i + 2) . "<><>" . $Position);
        else {
            if (($i + 1) == $countBlockLeft) $goF = CMS::SectionAdmin($TemplatesSection, 12, "{name},{num},{pos}", $nl['name_block'] . "<><>" . ($i) . "<><>" . $Position);
            else $goF = CMS::SectionAdmin($TemplatesSection, 13, "{name},{num},{num1},{pos}", $nl['name_block'] . "<><>" . ($i + 2) . "<><>" . $i . "<><>" . $Position);
        }

        $NameLastBlock = $nl['name_block'];

        if (file_exists('templates/admin/ico/block/' . $NameLastBlock . '.png')) $image = 'templates/admin/ico/block/' . $NameLastBlock . '.png';
        else $image = 'templates/admin/ico/admin/blockdevice.png';

        $ListBlock .= CMS::SectionAdmin($TemplatesSection, 8, "{name},{url},{i},{title},{check},{NameBlock},{pos},{check1},{check2},{goF},{image}", $BlockName . "<><>main-blocks-modules_acss-blo-" . $nl['name_block'] . ".pl<><>" . $i . "<><>" . $nl['titul'] . "<><>" . $ch . "<><>" . $nl['name_block'] . "<><>" . $Pos . "<><>" . $check1 . "<><>" . $check2 . "<><>" . $goF . "<><>" . $image);
        $Up = true;

        Simple_DbApi::update_db("block", "ves", $PosB, "name_block", $nl['name_block']);
        unset($BlockName);
    }
    unset($PosB);

    echo CMS::SectionAdmin($TemplatesSection, 7, "{list}", $ListBlock);

    unset($ListBlock);

    //echo CMS::SectionAdmin($TemplatesSection,4,"","");
}

### RIGHT ###

$SelectBlockRight = Simple_DbApi::select_db("block", "*", "posision,install", "right<><>yes", "ves", 1, "", "");
if (!empty($SelectBlockRight)) {
    $countBlockRight = count($SelectBlockRight);
    $Position = "right";
    echo CMS::SectionAdmin($TemplatesSection, 4, "", "");

    foreach ($SelectBlockRight as $i => $nl) {
        if (!isset($PosB)) $PosB = 1;
        else $PosB = $PosB + 1;
        if (file_exists("block/" . $nl['name_block'] . "/title.php")) include("block/" . $nl['name_block'] . "/title.php");
        if (empty($BlockName)) $BlockName = "no name";

        if ($nl['titul_on'] == "on") $ch = "checked=\"checked\"";
        else $ch = "";

        $check1 = "";
        $check2 = "";

        if ($nl['status'] == "on") $check1 = "checked=\"checked\"";
        else $check2 = "checked=\"checked\"";

        if (($countBlockRight - 1) != $i) {
            if ($Up == false) $UpDown = CMS::SectionAdmin($TemplatesSection, 13, "%ID%", $nl['name_block']);
            else $UpDown = CMS::SectionAdmin($TemplatesSection, 14, "%N%,%N1%,LAST,%ID%", "" . ($PosB + 1) . "<><>" . ($PosB - 1) . "<><>" . $NameLastBlock . "<><>" . $nl['name_block'] . "");
        } else $UpDown = CMS::SectionAdmin($TemplatesSection, 18, "%N%,%N1%,LAST,%ID%", "" . ($PosB + 1) . "<><>" . ($PosB - 1) . "<><>" . $NameLastBlock . "<><>" . $nl['name_block'] . "");

        $Pos = CMS::SectionAdmin($TemplatesSection, 9, "{i},{s1},{s2},{s3},{s4}", $i . "<><><><>selected<><><><>");

        if ($i == 0) $goF = CMS::SectionAdmin($TemplatesSection, 11, "{name},{num},{pos}", $nl['name_block'] . "<><>" . ($i + 2) . "<><>" . $Position);
        else {
            if (($i + 1) == $countBlockRight) $goF = CMS::SectionAdmin($TemplatesSection, 12, "{name},{num},{pos}", $nl['name_block'] . "<><>" . ($i) . "<><>" . $Position);
            else $goF = CMS::SectionAdmin($TemplatesSection, 13, "{name},{num},{num1},{pos}", $nl['name_block'] . "<><>" . ($i + 2) . "<><>" . $i . "<><>" . $Position);
        }

        $NameLastBlock = $nl['name_block'];

        if (file_exists('templates/admin/ico/block/' . $NameLastBlock . '.png')) $image = 'templates/admin/ico/block/' . $NameLastBlock . '.png';
        else $image = 'templates/admin/ico/admin/blockdevice.png';

        $ListBlock .= CMS::SectionAdmin($TemplatesSection, 8, "{name},{url},{i},{title},{check},{NameBlock},{pos},{check1},{check2},{goF},{image}", $BlockName . "<><>main-blocks-modules_acss-blo-" . $nl['name_block'] . ".pl<><>" . $i . "<><>" . $nl['titul'] . "<><>" . $ch . "<><>" . $nl['name_block'] . "<><>" . $Pos . "<><>" . $check1 . "<><>" . $check2 . "<><>" . $goF . "<><>" . $image);
        $Up = true;
        Simple_DbApi::update_db("block", "ves", $PosB, "name_block", $nl['name_block']);
        unset($BlockName);
    }
    unset($PosB);

    echo CMS::SectionAdmin($TemplatesSection, 7, "{list}", $ListBlock);

    unset($ListBlock);
}

### TOP ###

$SelectBlockcenter = Simple_DbApi::select_db("block", "*", "posision,install", "center<><>yes", "ves", 1, "", "");
if (!empty($SelectBlockcenter)) {
    $countBlockcenter = count($SelectBlockcenter);
    $Position = "center";
    echo CMS::SectionAdmin($TemplatesSection, 5, "", "");

    foreach ($SelectBlockcenter as $i => $nl) {
        if (!isset($PosB)) $PosB = 1;
        else $PosB = $PosB + 1;
        if (file_exists("block/" . $nl['name_block'] . "/title.php")) include("block/" . $nl['name_block'] . "/title.php");
        if (empty($BlockName)) $BlockName = "no name";

        if ($nl['titul_on'] == "on") $ch = "checked=\"checked\"";
        else $ch = "";

        $check1 = "";
        $check2 = "";

        if ($nl['status'] == "on") $check1 = "checked=\"checked\"";
        else $check2 = "checked=\"checked\"";

        if (($countBlockcenter - 1) != $i) {
            if ($Up == false) $UpDown = CMS::SectionAdmin($TemplatesSection, 13, "%ID%", $nl['name_block']);
            else $UpDown = CMS::SectionAdmin($TemplatesSection, 14, "%N%,%N1%,LAST,%ID%", "" . ($PosB + 1) . "<><>" . ($PosB - 1) . "<><>" . $NameLastBlock . "<><>" . $nl['name_block'] . "");
        } else $UpDown = CMS::SectionAdmin($TemplatesSection, 18, "%N%,%N1%,LAST,%ID%", "" . ($PosB + 1) . "<><>" . ($PosB - 1) . "<><>" . $NameLastBlock . "<><>" . $nl['name_block'] . "");

        $Pos = CMS::SectionAdmin($TemplatesSection, 9, "{i},{s1},{s2},{s3},{s4}", $i . "<><><><><><>selected<><>");

        if ($i == 0) $goF = CMS::SectionAdmin($TemplatesSection, 11, "{name},{num},{pos}", $nl['name_block'] . "<><>" . ($i + 2) . "<><>" . $Position);
        else {
            if (($i + 1) == $countBlockcenter) $goF = CMS::SectionAdmin($TemplatesSection, 12, "{name},{num},{pos}", $nl['name_block'] . "<><>" . ($i) . "<><>" . $Position);
            else $goF = CMS::SectionAdmin($TemplatesSection, 13, "{name},{num},{num1},{pos}", $nl['name_block'] . "<><>" . ($i + 2) . "<><>" . $i . "<><>" . $Position);
        }

        $NameLastBlock = $nl['name_block'];

        if (file_exists('templates/admin/ico/block/' . $NameLastBlock . '.png')) $image = 'templates/admin/ico/block/' . $NameLastBlock . '.png';
        else $image = 'templates/admin/ico/admin/blockdevice.png';

        $ListBlock .= CMS::SectionAdmin($TemplatesSection, 8, "{name},{url},{i},{title},{check},{NameBlock},{pos},{check1},{check2},{goF},{image}", $BlockName . "<><>main-blocks-modules_acss-blo-" . $nl['name_block'] . ".pl<><>" . $i . "<><>" . $nl['titul'] . "<><>" . $ch . "<><>" . $nl['name_block'] . "<><>" . $Pos . "<><>" . $check1 . "<><>" . $check2 . "<><>" . $goF . "<><>" . $image);
        $Up = true;
        Simple_DbApi::update_db("block", "ves", $PosB, "name_block", $nl['name_block']);
        unset($BlockName);
    }
    unset($PosB);

    echo CMS::SectionAdmin($TemplatesSection, 7, "{list}", $ListBlock);

    unset($ListBlock);
}

### DOWN ###

$SelectBlockDown = Simple_DbApi::select_db("block", "*", "posision,install", "down<><>yes", "ves", 1, "", "");
if (!empty($SelectBlockDown)) {
    echo CMS::SectionAdmin($TemplatesSection, 6, "", "");
    $Position = "down";
    $countBlockDown = count($SelectBlockDown);
    foreach ($SelectBlockDown as $i => $nl)
 	{
        if (!isset($PosB)) $PosB = 1;
        else $PosB = $PosB + 1;
        if (file_exists("block/" . $nl['name_block'] . "/title.php")) include("block/" . $nl['name_block'] . "/title.php");
        if (empty($BlockName)) $BlockName = "no name";

        if ($nl['titul_on'] == "on") $ch = "checked=\"checked\"";
        else $ch = "";

        $check1 = "";
        $check2 = "";

        if ($nl['status'] == "on") $check1 = "checked=\"checked\"";
        else $check2 = "checked=\"checked\"";

        if (($countBlockDown - 1) != $i) {
            if ($Up == false) $UpDown = CMS::SectionAdmin($TemplatesSection, 13, "%ID%", $nl['name_block']);
            else $UpDown = CMS::SectionAdmin($TemplatesSection, 14, "%N%,%N1%,LAST,%ID%", "" . ($PosB + 1) . "<><>" . ($PosB - 1) . "<><>" . $NameLastBlock . "<><>" . $nl['name_block'] . "");
        } else $UpDown = CMS::SectionAdmin($TemplatesSection, 18, "%N%,%N1%,LAST,%ID%", "" . ($PosB + 1) . "<><>" . ($PosB - 1) . "<><>" . $NameLastBlock . "<><>" . $nl['name_block'] . "");

        $Pos = CMS::SectionAdmin($TemplatesSection, 9, "{i},{s1},{s2},{s3},{s4}", $i . "<><><><><><><><>selected");

        if ($i == 0) $goF = CMS::SectionAdmin($TemplatesSection, 11, "{name},{num},{pos}", $nl['name_block'] . "<><>" . ($i + 2) . "<><>" . $Position);
        else {
            if (($i + 1) == $countBlockDown) $goF = CMS::SectionAdmin($TemplatesSection, 12, "{name},{num},{pos}", $nl['name_block'] . "<><>" . ($i) . "<><>" . $Position);
            else $goF = CMS::SectionAdmin($TemplatesSection, 13, "{name},{num},{num1},{pos}", $nl['name_block'] . "<><>" . ($i + 2) . "<><>" . $i . "<><>" . $Position);
        }

        $NameLastBlock = $nl['name_block'];

        if (file_exists('templates/admin/ico/block/' . $NameLastBlock . '.png')) $image = 'templates/admin/ico/block/' . $NameLastBlock . '.png';
        else $image = 'templates/admin/ico/admin/blockdevice.png';

        $ListBlock .= CMS::SectionAdmin($TemplatesSection, 8, "{name},{url},{i},{title},{check},{NameBlock},{pos},{check1},{check2},{goF},{image}", $BlockName . "<><>main-blocks-modules_acss-blo-" . $nl['name_block'] . ".pl<><>" . $i . "<><>" . $nl['titul'] . "<><>" . $ch . "<><>" . $nl['name_block'] . "<><>" . $Pos . "<><>" . $check1 . "<><>" . $check2 . "<><>" . $goF . "<><>" . $image);
        $Up = true;
        Simple_DbApi::update_db("block", "ves", $PosB, "name_block", $nl['name_block']);
        unset($BlockName);
    }
 	unset($PosB);

 	echo CMS::SectionAdmin($TemplatesSection, 7, "{list}", $ListBlock);

 	unset($ListBlock);
 }

echo CMS::SectionAdmin($TemplatesSection, 2, "", "");

?>