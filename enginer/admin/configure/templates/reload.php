<?php

include("includes/js.php");

$TemplatesSection = CMS::SectionFile('SelectTheme');

if (isset($_POST['add'])) {
    if (file_exists("templates/" . $_POST['id'] . "/index.php")) {
        $file__templates = file(DATA_PATH . "config.php");
        $fp_templates = fopen(DATA_PATH . "config.php", "w");
        flock($fp_templates, LOCK_EX);
        for ($f = 0; $f < count($file__templates); $f++) {
            if ($f != 10) fwrite($fp_templates, $file__templates[$f]);
            else fwrite($fp_templates, "\$html[\"templates\"]=\"" . $_POST['id'] . "\";\n");
        }
        flock($fp_templates, LOCK_UN);
        fclose($fp_templates);

        CMS::DestroyCacheAlls("cache");

        echo CMS::SectionAdmin($TemplatesSection, 10, "", "");
    }
}

include(DATA_PATH . "config.php");

echo CMS::SectionAdmin($TemplatesSection, 1, "", "");

echo CMS::SectionAdmin($TemplatesSection, 3, "", "");

$SelectListTheme = Simple_DbApi::select_db("templates", "*", "", "", "name", 1, "", "");

if (!empty($SelectListTheme)) {
    foreach ($SelectListTheme as $i => $nt) {
        if ($nt['name'] != "admin") {
            if (file_exists("templates/" . $nt['name'] . "/img.png")) $Img = "templates/" . $nt['name'] . "/img.png";
            else $Img = "images/notamplates.png";

            if (!file_exists("templates/" . $nt['name'] . "/index.php")) Simple_DbApi::delete_db("templates", "name", $nt['name']);


            if ($html["templates"] == $nt['name']) $StatusTheme = CMS::SectionAdmin($TemplatesSection, 7, "", "");
            else $StatusTheme = CMS::SectionAdmin($TemplatesSection, 6, "%ID%", $nt['name']);

            if (file_exists("templates/" . $nt["name"] . "/info")) include("templates/" . $nt["name"] . "/info");
            if (empty($date_templates)) $date_templates = CMS::SectionAdmin($TemplatesSection, 9, "", "");
            if (empty($autor_templates)) $autor_templates = CMS::SectionAdmin($TemplatesSection, 9, "", "");
            if (empty($info_templates)) $info_templates = CMS::SectionAdmin($TemplatesSection, 9, "", "");
            if (empty($email_templates)) $email_templates = CMS::SectionAdmin($TemplatesSection, 9, "", "");

            $InfoTheme = CMS::SectionAdmin($TemplatesSection, 8, "%DATA%,%AUTOR%,%MAIL%,%INFO%", "" . $date_templates . "<><>" . $autor_templates . "<><>" . $email_templates . "<><>" . $info_templates . "");

            echo CMS::SectionAdmin($TemplatesSection, 5, "%IMG%,%NAME%,%STATUS%,%INFO%", "" . $Img . "<><>" . $nt['name'] . "<><>" . $StatusTheme . "<><>" . $InfoTheme . "");
        }
    }
}


echo CMS::SectionAdmin($TemplatesSection, 4, "", "");

echo CMS::SectionAdmin($TemplatesSection, 2, "", "");

?>