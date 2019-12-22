<?php

include("includes/js.php");
$TemplatesSection = CMS::SectionFile('InstallTheme');

if (isset($_POST['add'])) {
    if (file_exists("templates/" . $_POST['mod'] . "/index.php")) {
        Simple_DbApi::insert_db("templates", "name", $_POST['mod']);
        echo CMS::SectionAdmin($TemplatesSection, 7, "", "");
    }
}

echo CMS::SectionAdmin($TemplatesSection, 1, "", "");

$DirList = scandir("templates/");

$SelectListTheme = Simple_DbApi::select_db("templates", "*", "", "", "", "", "", "");

$LiStInstallTheme = array();

if (!empty($SelectListTheme)) {
    foreach ($SelectListTheme as $n => $nm1) {
        if ($nm1['name'] != "admin") $LiStInstallTheme[$n] = $nm1['name'];
    }
}

sort($LiStInstallTheme);

$ListFind = 0;

echo CMS::SectionAdmin($TemplatesSection, 4, "", "");
echo CMS::SectionAdmin($TemplatesSection, 3, "", "");

for ($i = 2; $i < count($DirList); $i++) {
    $FindMod = false;
    for ($j = 0; $j < count($LiStInstallTheme); $j++) if (is_dir("templates/" . $DirList[$i]) && $LiStInstallTheme[$j] == $DirList[$i] && $DirList[$i] != "admin") $FindMod = true;
    if ($FindMod == false) {
        if (file_exists("templates/" . $DirList[$i] . "/index.php") && $DirList[$i] != "admin") {
            if (file_exists("templates/" . $DirList[$i] . "/img.png")) $Img = "../templates/" . $DirList[$i] . "/img.png";
            if (empty($Img)) $Img = "../images/notamplates.png";
            echo CMS::SectionAdmin($TemplatesSection, 6, "%IMG%,%NAME%,%ID%", "" . $Img . "<><>" . $DirList[$i] . "<><>" . $DirList[$i] . "");
            $ListFind = $ListFind + 1;
        }
    }
}

echo CMS::SectionAdmin($TemplatesSection, 5, "", "");

if ($ListFind == 0 && !isset($_POST['add'])) echo CMS::SectionAdmin($TemplatesSection, 8, "", "");

echo CMS::SectionAdmin($TemplatesSection, 2, "", "");

?>