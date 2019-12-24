<?php

/**
 * Основной класс, инициализирующий приложение
 * Drakon4ik (c)
 */

function init_db()
{
    // Инициализируем класс базы данных
    $db = DbSimple_Generic::connect(CMS::$config['db']);
    if (!empty(CMS::$config['debug']['enable']))
        $db->setLogger("Debug::DBLogger", "db");
    return $db;
}

class CMS
{
    static $mod_con, $go_title, $SectorFile, $metaTitle, $metaKey, $metaDesc;
    static $db; // database connector
    static $dbApi; // database api
    static $error; //error handler
    static $config = []; //config

    /* Admin part */

    static $date = 0;
    static $ip = 0;
    static $copyright;

    /* constants */

    public static function init() //__construct
    {
        /* db connection */
        require DATA_PATH . '/core/DBSimple/Generic.php';
        self::$config['db'] = 'mypdo://' . _LOGINDB_ . ':' . _PASSDB_ . '@' . (!empty(_SERVEDB_) ? _SERVEDB_ : 'localhost') . (!empty(_PORTDB_) ? ':' . _PORTDB_ : '') . '/' . _DATABASE_ . '?enc=utf8mb4&persist=true'; //mypdo://login:password@server/database?enc=utf8mb4&persist=true
        self::$db = init_db();

        self::$error = new Simple_Error();

        if (!empty(self::$db->error)) {
            Simple_Theme_Work::Theme_Error(SIMPLE_ER_CONNECT_DB);
        }

        self::$date = date("j.n.Y.H.i.s");
        self::$ip = $_SERVER['REMOTE_ADDR'];
        self::$copyright = "";
    }

    public static function init_db($mypdo_str) //__construct
    {
        /* db connection */
        require DATA_PATH . '/core/DBSimple/Generic.php';
        self::$config['db'] = $mypdo_str; //mypdo://login:password@server/database?enc=utf8mb4&persist=true
        self::$db = init_db();

        if (!empty(self::$db->error)) {
            Simple_Theme_Work::Theme_Error(SIMPLE_ER_CONNECT_DB);
        }
    }

    static function closed_site()
    {
        if (CLOSEDSITE == "on" && !isset($_SESSION["login_admin"])) {
            self::$error->Test_File_Insite(DATA_PATH . "/closed_page.php", SIMPLE_NOT_FILE . " closed_page.php");
            self::$error->Test_File_Insite("pro100/closed.php", SIMPLE_NOT_FILE . " closed.php");
            include(DATA_PATH . "/closed_page.php");
            include("pro100/closed.php");
            exit;
        }
    }

    static function timing_site_start()
    {
        if (TIMINGSITE == "on") {
            $start_time = microtime(true);
            return $start_time;
        }
    }

    static function timing_end_site($time)
    {
        if (TIMINGSITE == "on") {
            $all_time = microtime(true) - $time;
            $all_time = substr($all_time, 0, 4);
            return $all_time;
        }
    }

    static function users_on_site()
    {
        if (USERSINSITE == "on") {
            self::$error->Test_File_Insite(DATA_PATH . "/online.why", SIMPLE_NOT_FILE . " online.why");
            $ip = $_SERVER['REMOTE_ADDR'];
            session_set_cookie_params('0');
            $id = session_id();
            $CurrentTime = time();
            $LastTime = time() + 480;
            $base = DATA_PATH . "/online.why";
            $file = file($base);
            $save_user = true;
            if (empty($_SESSION["login_user"])) $logins_stat = "guest";
            else $logins_stat = $_SESSION["login_user"];
            if (empty($_SESSION["acess_user"])) $ac = -24;
            else $ac = $_SESSION["acess_user"];
            for ($i = 0; $i < count($file); $i++) if (Simple_Search_Text($id, $file[$i])) $save_user = false;
            if ($save_user == true) {
                $fp = fopen(DATA_PATH . "/online.why", "a+");
                flock($fp, LOCK_EX);
                fwrite($fp, trim($id) . "|" . $ac . "|" . $LastTime . "|" . $logins_stat . "|" . $ip . "\n");
                flock($fp, LOCK_UN);
                fclose($fp);
            }
            unset($fp);
            $fp1 = file($base);
            $fp = fopen($base, "w+");
            flock($fp, LOCK_EX);
            for ($x = 0; $x < count($fp1); $x++) {
                list($ids, $acsess, $time, $login, $ips) = explode("|", $fp1[$x], 5);
                if ($time > $CurrentTime) {
                    if ($ids == $id && $logins_stat != $login) fwrite($fp, $id . "|" . $ac . "|" . $LastTime . "|" . $logins_stat . "|" . $ip . "\n");
                    else fwrite($fp, $fp1[$x]);
                }
            }
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    static function all_user_insite()
    {
        self::$error->Test_File_Insite(DATA_PATH . "/online.why", SIMPLE_NOT_FILE . " online.why");
        $u = file(DATA_PATH . "/online.why");
        $all = count($u);
        return $all;
    }

    static function bottom_text($time)
    {
        $text = "<div style=\"margin-top:0px; width:100%;clear:both;text-align:center; font-family: Tahoma;font-size: 10pt;color:#d2d2d3;font-weight:500;\">";
        if (USERSINSITE == "on" && LOOKING == "on") $text .= SIMPLE_USERON . " " . self::all_user_insite() . " <br />";
        if (TIMINGSITE == "on") $text .= SIMPLE_GENERATE_PAGE . $time;
        $text .= "</div></body></html>";
        echo $text;
    }

    static function read_file_in_theme()
    {
        if (file_exists(TEMPLATES . "/" . TSITE . "/modules/" . $_GET['page'] . "/section")) {
            $text = '';
            $file = file(TEMPLATES . "/" . TSITE . "/modules/" . $_GET['page'] . "/section");
            for ($i = 0; $i < count($file); $i++) $text .= $file[$i];
            return $text;
        }
    }

    static function section_list($number, $on = '', $in = '')
    {
        if (!empty($GLOBALS['section'])) {
            $section = $GLOBALS['section'];
            preg_match("!\[section::" . $number . "\](.*?)\[/section::" . $number . "\]!si", $section, $match);
            $section = $match[1];
            $array[1] = explode(",", $on);
            $array[2] = explode("<><>", $in);
            if (count($array[1]) != count($array[2])) Simple_Theme_Work::Theme_Error(SIMPLE_FORMAT . " section_list");
            for ($n = 0; $n < count($array[1]); $n++) $section = str_replace($array[1][$n], $array[2][$n], $section);
            if (strlen($section) < 1) Simple_Theme_Work::Theme_Error(SIMPLE_ADMIN_SECTION . $number);
        }
        return $section;
    }

    static function js_insert_file($name)
    {
        if (file_exists("js/" . $name)) {
            $format = "<script src=\"js/" . $name . "\" type=\"text/javascript\"></script>";
            return $format;
        }
    }

    static function js_insert_code($code)
    {
        $code_js = "<script type=\"text/javascript\">" . $code . "</script>";
        return $code_js;
    }

    static function test_ip_ban()
    {
        $ip_user = $_SERVER['REMOTE_ADDR'];
        if (file_exists(DATA_PATH . "/ipban/" . $ip_user)) {
            $alert = file_get_contents(DATA_PATH . "/ipban/" . $ip_user);
            Simple_Theme_Work::Theme_Error($alert);
        }
    }

    static function db_times()
    {
        echo "<!-- db:" . $GLOBALS['workdb'] . "-->";
    }

    static function module_content()
    {
        if (file_exists(PATH_TO_THEME . "modules/module")) {
            $modules = file_get_contents(PATH_TO_THEME . "modules/module");
            if (empty($GLOBALS['SIMPLE_TITLE_MODULE'])) preg_match("!\[module::title\](.*?)\[/module::title\]!si", $modules, $title_module);
            preg_match("!\[module::content\](.*?)\[/module::content\]!si", $modules, $content_module);
            $title_module[1] = str_replace("%TEXT_TITLE%", self::$go_title, $title_module[1]);
            $content_module[1] = str_replace("%TEXT_MODULE%", self::$mod_con, $content_module[1]);
            $text_work = $title_module[1] . $content_module[1];
            $text_work = str_replace('{PATH_TO_THEME}', URL_SITE_THEME, $text_work);
            $text_work = str_replace('{URL_SITE}', URL_SITE, $text_work);
            $text_work = str_replace('{PAGE}', $_GET['page'], $text_work);
            return $text_work;
        } else Simple_Theme_Work::Theme_Error(SIMPLE_NOT_FILE . " " . PATH_TO_THEME . "module");
    }

    static function format_block($possision, $page)
    {
        $block = $go_content = $FormatCache = '';
        if (!file_exists(DATA_PATH . "cache/block/" . $possision)) {
            $select = Simple_DbApi::select_db("block", "*", "status,posision", "on<><>" . $possision . "", "ves", 1, "", "");
            foreach ($select as $i => $nf) {
                $in_module = false;
                if (empty($nf['on_module'])) $in_module = true;
                else {
                    $ListMod = explode(" ", $nf['on_module']);
                    for ($m = 0; $m < count($ListMod); $m++) if ($_GET['page'] == $ListMod[$m]) $in_module = true;
                }

                if ($in_module == true) {

                    if ($_SESSION["status_user"] == 3) $status_z = 50;
                    else {
                        if ($nf['for_block'] == 4) $status_z = 50;
                        else {
                            $status_u = $_SESSION["status_user"];
                            $status_z = 0;
                        }
                    }
                    if ($status_u == $nf['for_block'] || $status_z == 50) {
                        if (file_exists(BLOCK . "/" . $nf['name_block'] . "/index.php")) {
                            self::$error->Test_File_Insite(BLOCK . "/" . $nf['name_block'] . "/index.php", SIMPLE_NOT_FILE . " " . BLOCK . "/" . $nf['name_block'] . "/index.php");
                            $GLOBALS['section_block'] = self::Section_Block($nf['name_block']);
                            $block .= self::BlockContent($nf['name_block']);
                            if (file_exists(BLOCK . "/" . $nf['name_block'] . "/title.php")) include(BLOCK . "/" . $nf['name_block'] . "/title.php");
                            if (!empty($nf['titul'])) $title = $nf['titul'];
                            else $title = $BlockName;
                            if ($nf['titul_on'] == "on") $yes_titul = true;
                            else $yes_titul = false;
                            $block_name = $title;
                            $block = str_replace('{TitleBlock}', $block_name, $block);
                            self::$error->Test_File_Insite(PATH_TO_THEME . "/block/" . $possision . ".php", SIMPLE_NOT_FILE . " " . PATH_TO_THEME . "/block/" . $possision . ".php");
                            include(PATH_TO_THEME . "/block/" . $possision . ".php");
                            if ($in_module == true) $go_content .= $block_l;
                            unset($block_x);
                            unset($block);
                            unset($block_l);
                        }
                    }
                    $FormatCache .= $nf['on_module'] . "::" . $nf['for_block'] . "::" . $nf['name_block'] . "::" . $block_name . "::" . $nf['titul_on'] . "\n";
                }
            }
            self::BlockInsertCacheData($possision, $FormatCache);
        } else {
            $file = file(DATA_PATH . "cache/block/" . $possision);
            for ($i = 0; $i < count($file); $i++) {
                $in_module = false;
                list($OnModule, $ForBlock, $NameBlock, $titul, $TitleOn) = explode("::", $file[$i], 5);
                if (!empty($NameBlock)) {
                    if (empty($OnModule)) $in_module = true;
                    else {
                        $ListMod = explode(" ", $OnModule);
                        for ($m = 0; $m < count($ListMod); $m++) if ($_GET['page'] == $ListMod[$m]) $in_module = true;
                    }
                    if ($in_module == true) {
                        if ($_SESSION["status_user"] == 3) $status_z = 50;
                        else {
                            if ($ForBlock == 4) $status_z = 50;
                            else {
                                $status_u = $_SESSION["status_user"];
                                $status_z = 0;
                            }
                        }
                        if ($status_u == $ForBlock || $status_z == 50) {
                            if (file_exists(BLOCK . "/" . $NameBlock . "/index.php")) {
                                self::$error->Test_File_Insite(BLOCK . "/" . $NameBlock . "/index.php", SIMPLE_NOT_FILE . " " . BLOCK . "/" . $NameBlock . "/index.php");
                                $GLOBALS['section_block'] = self::Section_Block($NameBlock);
                                $block .= self::BlockContent($NameBlock);
                                if (trim($TitleOn) == "on") $yes_titul = true;
                                else $yes_titul = false;
                                $block_name = $titul;
                                $block = str_replace('{TitleBlock}', $block_name, $block);
                                self::$error->Test_File_Insite(PATH_TO_THEME . "/block/" . $possision . ".php", SIMPLE_NOT_FILE . " " . PATH_TO_THEME . "/block/" . $possision . ".php");
                                include(PATH_TO_THEME . "/block/" . $possision . ".php");
                                $go_content .= $block_l;
                                unset($block_x);
                                unset($block);
                                unset($block_l);
                            }
                        }
                    }
                }
            }
        }

        return $go_content;
    }

    public static function BlockContent($name)
    {
        $BlockName = $name;
        self::$error->Test_File_Insite(BLOCK . "/" . $BlockName . "/index.php", SIMPLE_NOT_FILE . " " . BLOCK . "/" . $BlockName . "/index.php");
        if (file_exists(DATA_PATH . "block/" . $BlockName . "/config.php")) include(DATA_PATH . "block/" . $BlockName . "/config.php");
        include(BLOCK . "/" . $BlockName . "/index.php");
        $block = str_replace('{PATH_TO_THEME}', URL_SITE_THEME, $block);
        $block = str_replace('{URL_SITE}', URL_SITE, $block);
        return $block;
    }

    static function insert_css_file($file)
    {
        self::$error->Test_File_Insite(PATH_TO_THEME . "css/" . $file, SIMPLE_NOT_FILE . " " . PATH_TO_THEME . "css/" . $file);
        $css = "<link href=\"" . URL_SITE_THEME . "css/" . $file . "\" rel=\"stylesheet\" type=\"text/css\" />";
        return $css;
    }

    static function simple_module($page)
    {
        $content = $metaKey = $metaDesc = '';
        if (file_exists("modules/" . $page . "/index.php")) {
            if (CMS::TestFileOtherCache('mod/' . $page) != 1) {
                $select_find_module = Simple_DbApi::select_db("modules", "*", "name,install,status", "" . $page . "<><>yes<><>on", "", "", "", "");
                if (!empty($select_find_module)) {
                    $nmpro100modules = current($select_find_module);

                    if (isset($acsess_mod) && $_SESSION["status_user"] != $acsess_mod && $acsess_mod != 4 && $_SESSION["status_user"] != 3) header("Location:/error/");
                    else $page = $page;
                    if (file_exists(MODULES . "/" . $page . "/" . LANGUAGES . "/" . LANDSITE . ".php")) include(MODULES . "/" . $page . "/" . LANGUAGES . "/" . LANDSITE . ".php");
                    if (file_exists(DATA_PATH . "/modules/" . $page . "/config.php")) include(DATA_PATH . "/modules/" . $page . "/config.php");
                    if (file_exists(DATA_PATH . "/modules/" . $page . "/seo.php")) include(DATA_PATH . "/modules/" . $page . "/seo.php");
                    $GLOBALS['section'] = self::read_file_in_theme();
                    include("modules/" . $page . "/index.php");
                    self::$mod_con = !empty($content) ? $content : '';
                    if (!isset($title_go)) {
                        if (empty($nmpro100modules['title'])) {
                            if (file_exists(MODULES . "/" . $page . "/title.php")) {
                                include(MODULES . "/" . $page . "/title.php");
                                $title_go = $module_title;
                            } else $title_go = "No name";
                        } else $title_go = $nmpro100modules['title'];
                    }
                    self::$go_title = $title_go;
                    self::$metaTitle = !empty($metaTitle) ? $metaTitle : $title_go;
                    self::$metaKey = !empty($metaKey) ? $metaKey : self::$metaKey;
                    self::$metaDesc = !empty($metaDesc) ? $metaDesc : self::$metaDesc;

                    if ($_GET['page'] != "error") CMS::CreatFileCache($page, 'mod', $title_go);
                } else {
                    $_GET['page'] = "error";
                    self::$error->Test_File_Insite("modules/error/index.php", SIMPLE_NOT_FILE . " modules/error/index.php");
                    if (file_exists(DATA_PATH . "/modules/" . $_GET['page'] . "/seo.php")) include(DATA_PATH . "/modules/" . $_GET['page'] . "/seo.php");
                    include("modules/error/index.php");
                    self::$mod_con = $content;
                    if (file_exists(MODULES . "/error/title.php")) {
                        include(MODULES . "/error/title.php");
                        $title_go = $module_title;
                    } else $title_go = "No name";
                    self::$go_title = $title_go;

                    self::$metaTitle = !empty($metaTitle) ? $metaTitle : $title_go;
                    self::$metaKey = $metaKey;
                    self::$metaDesc = $metaDesc;
                }
            } else {
                if (file_exists(MODULES . "/" . $page . "/" . LANGUAGES . "/" . LANDSITE . ".php")) include(MODULES . "/" . $page . "/" . LANGUAGES . "/" . LANDSITE . ".php");
                if (file_exists(DATA_PATH . "/modules/" . $page . "/config.php")) include(DATA_PATH . "/modules/" . $page . "/config.php");
                $GLOBALS['section'] = self::read_file_in_theme();
                if (file_exists(DATA_PATH . "/modules/" . $page . "/seo.php")) include(DATA_PATH . "/modules/" . $page . "/seo.php");
                include("modules/" . $page . "/index.php");
                self::$mod_con = $content;
                if (!isset($title_go)) $title_go = CMS::ReadOtherFileCache('mod/' . $page);
                self::$go_title = $title_go;
                self::$metaTitle = !empty($metaTitle) ? $metaTitle : $title_go;
                self::$metaKey = $metaKey;
                self::$metaDesc = $metaDesc;
            }
        } else {
            $_GET['page'] = "error";
            self::$error->Test_File_Insite("modules/error/index.php", SIMPLE_NOT_FILE . " modules/error/index.php");
            if (file_exists(DATA_PATH . "/modules/" . $_GET['page'] . "/seo.php")) include(DATA_PATH . "/modules/" . $_GET['page'] . "/seo.php");
            include("modules/error/index.php");
            self::$mod_con = $content;
            if (file_exists(MODULES . "/error/title.php")) {
                include(MODULES . "/error/title.php");
                $title_go = $module_title;
            } else $title_go = "No name";
            self::$go_title = $title_go;
            self::$metaTitle = !empty($metaTitle) ? $metaTitle : $title_go;
            self::$metaKey = $metaKey;
            self::$metaDesc = $metaDesc;
        }
    }

    static function Section_Block($file)
    {
        if (file_exists(PATH_TO_THEME . "block/" . $file . "/section")) $s = file_get_contents(PATH_TO_THEME . "block/" . $file . "/section");
        return $s;
    }

    static function Section_Block_List($number, $on, $in)
    {
        if (!empty($GLOBALS['section_block'])) {
            $section = $GLOBALS['section_block'];
            preg_match("!\[section::" . $number . "\](.*?)\[/section::" . $number . "\]!si", $section, $match);
            $section = $match[1];
            $array[1] = explode(",", $on);
            $array[2] = explode("<><>", $in);
            if (count($array[1]) != count($array[2])) Simple_Theme_Work::Theme_Error(SIMPLE_FORMAT . " Section_Block_List");
            for ($n = 0; $n < count($array[1]); $n++) $section = str_replace($array[1][$n], $array[2][$n], $section);
            if (strlen($section) < 1) Simple_Theme_Work::Theme_Error(SIMPLE_ADMIN_SECTION . $number);
        }
        return $section;
    }

    public static function ReadSectorFileTheme()
    {
        self::$error->Test_File_Insite(PATH_TO_THEME . "sector", SIMPLE_NOT_FILE . " " . PATH_TO_THEME . "sector");
        $sector = file_get_contents(PATH_TO_THEME . "sector");
        return $sector;
    }

    public static function SectorTheme($number, $on, $in)
    {
        if (!empty(self::$SectorFile)) {
            $section = self::$SectorFile;
            preg_match("!\[section::" . $number . "\](.*?)\[/section::" . $number . "\]!si", $section, $match);
            $section = $match[1];
            $array[1] = explode(",", $on);
            $array[2] = explode("<><>", $in);
            if (count($array[1]) != count($array[2])) Simple_Theme_Work::Theme_Error(SIMPLE_FORMAT . " Section_Block_List");
            for ($n = 0; $n < count($array[1]); $n++) $section = str_replace($array[1][$n], $array[2][$n], $section);
        }
        list($NameTheme, $NameText) = explode("::", $section, 2);
        return $NameText;
    }

    public static function ReadCacheModule($id)
    {
        $Cache = @file_get_contents(DATA_PATH . "cache/module/" . $_GET['page'] . "/" . md5($id));
        $Cache = $Cache;
        return $Cache;
    }

    public static function BlockInsertCacheData($possision, $Format)
    {
        if (BUFFERSITE == 'on') {
            $fp = fopen(DATA_PATH . "cache/block/" . $possision, "w");
            flock($fp, LOCK_EX);
            fwrite($fp, $Format);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    public static function TitleCache($text)
    {
        list($title, $text, $metaTitle, $metaKey, $metaDesc) = explode("::", $text, 5);
        return $title;
    }

    public static function ContentCache($text)
    {
        list($title, $text, $metaTitle, $metaKey, $metaDesc) = explode("::", $text, 5);
        return $text;
    }

    public static function MetaTitleCache($text)
    {
        list($title, $text, $metaTitle, $metaKey, $metaDesc) = explode("::", $text, 5);
        return strip_tags($metaTitle);
    }

    public static function MetaKeyCache($text)
    {
        list($title, $text, $metaTitle, $metaKey, $metaDesc) = explode("::", $text, 5);
        return strip_tags($metaKey);
    }

    public static function MetaDescCache($text)
    {
        list($title, $text, $metaTitle, $metaKey, $metaDesc) = explode("::", $text, 5);
        return strip_tags($metaDesc);
    }

    public static function TestCacheFile($id)
    {
        if (file_exists(DATA_PATH . "cache/module/" . $_GET['page'] . "/" . md5($id))) $Cache = 1;
        else $Cache = 2;
        return $Cache;
    }

    public static function CreatContentCacheFile($title, $text, $metaTitle, $metaKey, $metaDesc, $id)
    {
        if (BUFFERSITE == 'on') {
            if (!is_dir(DATA_PATH . "cache/module")) mkdir(DATA_PATH . "cache/module");
            if (!is_dir(DATA_PATH . "cache/block")) mkdir(DATA_PATH . "cache/block");
            $text = $title . "::" . $text . "::" . $metaTitle . "::" . $metaKey . "::" . $metaDesc;
            if (!is_dir(DATA_PATH . "cache/module/" . $_GET['page'])) mkdir(DATA_PATH . "cache/module/" . $_GET['page']);
            $fp = fopen(DATA_PATH . "cache/module/" . $_GET['page'] . "/" . md5($id), "w");
            flock($fp, LOCK_EX);
            fwrite($fp, $text);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    public static function CreatFileCache($FileName, $Dir, $text)
    {
        if (BUFFERSITE == 'on') {
            if (!is_dir(DATA_PATH . "cache/" . $Dir)) mkdir(DATA_PATH . "cache/" . $Dir);
            $fp = fopen(DATA_PATH . "cache/" . $Dir . "/" . $FileName, "w");
            flock($fp, LOCK_EX);
            fwrite($fp, $text);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    public static function TestFileOtherCache($file)
    {
        if (file_exists(DATA_PATH . "cache/" . $file)) $find = 1;
        else $find = 2;
        return $find;
    }

    public static function ReadOtherFileCache($file)
    {
        if (file_exists(DATA_PATH . "cache/" . $file)) $text = file_get_contents(DATA_PATH . "cache/" . $file);
        return $text;
    }

    public static function ClearCacheDirectory()
    {
        $PathBlock = DATA_PATH . "cache/block/";
        $PathModule = DATA_PATH . "cache/module/";
        $PathModule1 = DATA_PATH . "cache/mod/";
        $PathData = DATA_PATH . "cache/date/";
        CMS::DeleteFull($PathBlock);
        CMS::DeleteFull($PathModule);
        CMS::DeleteFull($PathModule1);
        CMS::DeleteFull($PathData);
    }

    public static function DeleteFull($dir)
    {
        if (!$dd = opendir($dir)) return false;
        while (false !== ($obj = readdir($dd))) {
            if ($obj == '.' || $obj == '..') continue;
            if (!@unlink($dir . '/' . $obj)) CMS::DeleteFull($dir . '/' . $obj);
        }
        closedir($dd);
        if ($dir != DATA_PATH . "cache/block/" && $dir != DATA_PATH . "cache/module/" && $dir != DATA_PATH . "cache/mod/" && $dir != DATA_PATH . "cache/date/") @rmdir($dir);
    }

    public static function DateName($id)
    {
        $DateList = array("Январь", "Февраль", "Март", "Апрель", "Май", "�?юнь", "�?юль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
        $date = $DateList[$id];
        return $date;
    }

    public static function TestOnJsLib($name)
    {
        $file = file(DATA_PATH . 'jslib');
        $find = 2;
        for ($i = 0; $i < count($file); $i++) {
            if (trim($name) == trim($file[$i])) {
                $find = 1;
                break;
            }
        }
        return $find;
    }

    public static function CoreComponent($obgect)
    {
        if (empty($GLOBALS['obj'][$obgect])) {
            if (file_exists(DATA_PATH . 'core/' . $obgect . '.php')) {
                $GLOBALS['obj'][$obgect] = 1;
                include(DATA_PATH . 'core/' . $obgect . '.php');
            }
        }
    }

    public static function TestData($valid, $text, $status = 1)
    {
        if ($status == 2) {
            $status = preg_match('/(' . $valid . ')/i', $text) ? true : false;
        } else {
            $status = preg_match("/^[" . $valid . "]+$/", $text) ? true : false;
        }
        return $status;
    }

    public static function TestValidMail($mail)
    {
        if (preg_match("/(([[:alnum:]]+(-|_)?[[:alnum:]]+)\.?)+@([[:alnum:]]+(-|_)?[[:alnum:]]+\.)+[[:alnum:]]/i", $mail)) $valid = true;
        else $valid = false;
        return $valid;
    }

    public static function ClearContent($text)
    {
        $content = str_replace("\\", "", $text);
        return $content;
    }

    public static function AdminPanelUser()
    {
        $html = '';
        if (isset($_SESSION["login_admin"]) && isset($_SESSION["pass_admin"])) {
            $html .= '<link href="' . URL_SITE . 'templates/admin/css/front.css" rel="stylesheet" type="text/css" />';
            if (file_exists(DATA_PATH . 'modules/' . $_GET['page'] . '/pro100.php')) {
                include(DATA_PATH . 'modules/' . $_GET['page'] . '/pro100.php');

                if (!empty($AdminType[0])) {
                    preg_match_all("/{(.*?)}/is", $AdminType[0], $match);
                    for ($i = 0; $i < count($match[1]); $i++) {
                        $AdminType[0] = str_replace('{' . $match[1][$i] . '}', $_GET[$match[1][$i]], $AdminType[0]);
                    }
                    $url = 'admin_panel/' . $AdminType[0] . '.pl';
                    $list .= '<li><a target="_blank" href="' . URL_SITE . $url . '">Добавить</a></li>';
                }

                if (!empty($AdminType[1])) {
                    preg_match_all("/{(.*?)}/is", $AdminType[1], $match);
                    for ($i = 0; $i < count($match[1]); $i++) {
                        $AdminType[1] = str_replace('{' . $match[1][$i] . '}', $_GET[$match[1][$i]], $AdminType[1]);
                    }
                    $url = 'admin_panel/' . $AdminType[1] . '.pl';
                    $list .= '<li><a target="_blank" href="' . URL_SITE . $url . '">Редактировать</a></li>';
                }
                if (!empty($AdminType[2])) {
                    preg_match_all("/{(.*?)}/is", $AdminType[1], $match);
                    for ($i = 0; $i < count($match[1]); $i++) {
                        $AdminType[2] = str_replace('{' . $match[1][$i] . '}', $_GET[$match[1][$i]], $AdminType[2]);
                    }
                    $url = 'admin_panel/' . $AdminType[2] . '.pl';
                    $list .= '<li><a target="_blank" href="' . URL_SITE . $url . '">Удалить</a></li>';
                }

            } else $list = '<li><a target="_blank" href="' . URL_SITE . 'admin_panel/main-modules-setting-mod-' . $_GET['page'] . '.pl">Редактировать</a></li>';
            $html .= '
		<div id="panel-pro100">
<ul>
<li><a target="_blank" href="' . URL_SITE . 'admin_panel/admin.pl">Панель администратора</a></li>

</ul>
<div id="exit-pro100"><a href="' . URL_SITE . 'admin_panel/admin.pl?exit" title="">выход</a></div>
</div>
		';
        }
        return $html;
    }

    /* Admin part */
    public static function Admin_Page($admin)
    {
        if (!empty($admin)) {
            if (strlen($admin) < 15) {
                if (!file_exists(DATA_PATH . "/admin/" . $admin . ".php")) $admin = "main";
                Simple_Error::Test_File_Insite(DATA_PATH . "/admin/" . $admin . ".php", SIMPLE_NOT_FILE . " ", DATA_PATH . "/admin/" . $admin . ".php");
                include(DATA_PATH . "/admin/" . $admin . ".php");
            } else unset($admin);
        } else {
            $admin = "main";
            include(DATA_PATH . "/admin/" . $admin . ".php");
        }
    }

    public static function ErrorEnter()
    {
        if (!empty($_SESSION['e_i']) && $_SESSION['e_i'] > 3) {
            Simple_DbApi::insert_db("admin_acsess", "ip,date", "" . $this->ip . "<><>" . $this->date . "");
            Simple_Theme_Work::Theme_Error(SIMPLE_ADMIN_ERR_IN);
        }
    }

    public static function TestAuth()
    {
        if (!isset($_POST['go'])) {
            $rand = rand(100000, 999999);
            $text_capcha = $rand;
            $_SESSION['captcha'] = $rand;
            Simple_Error::Test_File_Insite(TEMPLATES . "/admin/enter.php", SIMPLE_NOT_FILE . " " . TEMPLATES . "/admin/enter.php");
            include(TEMPLATES . "/admin/enter.php");
            print copyright;
        } else {
            Simple_Error::Test_File_Insite(ADMIN . "/index.php", SIMPLE_NOT_FILE . " " . ADMIN . "/index.php");
            include(ADMIN . "/index.php");
        }
    }

    public static function TestAdminAcount()
    {
        $SelectUser = Simple_DbApi::select_db('accounts', 'login,password,acsess_level', 'login', $_SESSION["login_admin"], '', '', '', '');
        $na = current($SelectUser);
        if ($na['login'] == $_SESSION["login_admin"] && $na['password'] == $_SESSION["pass_admin"] && $na['acsess_level'] > 100) $Test = true;
        else $Test = false;
        return $Test;
    }

    public static function SectionFile($file)
    {
        if (file_exists(DATA_PATH . "/admin/section/" . $file)) {
            $list = file_get_contents(DATA_PATH . "/admin/section/" . $file);
            return $list;
        }
    }

    public static function SectionAdmin($list, $number, $on, $in)
    {
        if (!empty($list)) {
            preg_match("!\[section::" . $number . "\](.*?)\[/section::" . $number . "\]!si", $list, $fmatch);
            $listing = $fmatch[1];
            $array[1] = explode(",", $on);
            $array[2] = explode("<><>", $in);
            if (count($array[1]) != count($array[2])) Simple_Theme_Work::Theme_Error(SIMPLE_FORMAT . " SectionAdmin");
            for ($n = 0; $n < count($array[1]); $n++) $listing = str_replace($array[1][$n], $array[2][$n], $listing);
            return $listing;
        }
    }

    public static function AdminModuleFileSection()
    {
        $list = "";
        if (!isset($_GET['ajax'])) {
            if (file_exists(DATA_PATH . "modules/" . $_GET['name_tamlates'] . "/section")) {
                $list = file_get_contents(DATA_PATH . "modules/" . $_GET['name_tamlates'] . "/section");
                return $list;
            }
        } else {
            if (file_exists(DATA_PATH . "modules/" . $_GET['name_tamlates'] . "/ajax")) {
                $list = file_get_contents(DATA_PATH . "modules/" . $_GET['name_tamlates'] . "/ajax");
                return $list;
            }
        }
        return $list;
    }

    public static function AdminModuleSection($number, $on = '', $in = '')
    {
        if (!empty($GLOBALS['ModSection'])) {
            $section = $GLOBALS['ModSection'];
            preg_match("!\[section::" . $number . "\](.*?)\[/section::" . $number . "\]!si", $section, $match);
            $section = $match[1];
            $array[1] = explode(",", $on);
            $array[2] = explode("<><>", $in);
            if (count($array[1]) != count($array[2])) Simple_Theme_Work::Theme_Error(SIMPLE_FORMAT . " AdminModuleSection");
            for ($n = 0; $n < count($array[1]); $n++) $section = str_replace($array[1][$n], $array[2][$n], $section);
            if (strlen($section) < 1) Simple_Theme_Work::Theme_Error(SIMPLE_ADMIN_SECTION . $number);
            return $section;
        }
    }

    public static function SectionBlockFile()
    {
        $list = "";
        if (!isset($_GET['ajax1'])) {
            if (file_exists(DATA_PATH . "block/" . $_GET['config'] . "/section")) $list = file_get_contents(DATA_PATH . "block/" . $_GET['config'] . "/section");
        } else {
            if (file_exists(DATA_PATH . "block/" . $_GET['config'] . "/ajax")) $list = file_get_contents(DATA_PATH . "block/" . $_GET['config'] . "/ajax");
        }
        return $list;
    }

    public static function SectionBlock($number, $on, $in)
    {
        if (!empty($GLOBALS['FileSectionBlock'])) {
            $section = $GLOBALS['FileSectionBlock'];
            preg_match("!\[section::" . $number . "\](.*?)\[/section::" . $number . "\]!si", $section, $match);
            $section = $match[1];
            $array[1] = explode(",", $on);
            $array[2] = explode("<><>", $in);
            if (count($array[1]) != count($array[2])) Simple_Theme_Work::Theme_Error(SIMPLE_FORMAT . " SectionBlock");
            for ($n = 0; $n < count($array[1]); $n++) $section = str_replace($array[1][$n], $array[2][$n], $section);
            return $section;
        }
    }

    public static function DestroyCacheModule($id)
    {
        if (file_exists(DATA_PATH . "cache/module/" . $_GET['name_tamlates'] . "/" . md5($id)))
            @unlink(DATA_PATH . "cache/module/" . $_GET['name_tamlates'] . "/" . md5($id));
    }

    public static function DestroyAllCacheModule()
    {
        if (is_dir(DATA_PATH . "cache/module/" . $_GET['name_tamlates'] . "")) {
            $scan = scandir(DATA_PATH . "cache/module/" . $_GET['name_tamlates'] . "/");
            for ($i = 0; $i < count($scan); $i++)
                if (is_file(DATA_PATH . "cache/module/" . $_GET['name_tamlates'] . "/" . $scan[$i]))
                    @unlink(DATA_PATH . "cache/module/" . $_GET['name_tamlates'] . "/" . $scan[$i]);
        }
    }

    public static function DestroyAllCacheBlockS()
    {
        if (is_dir(DATA_PATH . "cache/block/")) {
            $scan = scandir(DATA_PATH . "cache/block/");
            for ($i = 0; $i < count($scan); $i++)
                if (is_file(DATA_PATH . "cache/block/" . $scan[$i]))
                    @unlink(DATA_PATH . "cache/block/" . $scan[$i]);
        }
    }

    public static function DestroyCacheAlls($path)
    {
        $scan = @scandir(DATA_PATH . $path);
        for ($i = 2; $i < count($scan); $i++) {
            if ($scan[$i] != ".htaccess" && $scan[$i] != "." && $scan[$i] != "..") {
                AdminPanel::DestroyCacheAlls($path . "/" . $scan[$i]);
                @unlink(DATA_PATH . $path . "/" . $scan[$i]);
            }
        }
    }

    public static function DesctroyCacheBlock()
    {
        $scan = scandir(DATA_PATH . "cache/block/");
        for ($i = 0; $i < count($scan); $i++)
            if (is_file(DATA_PATH . "cache/block/" . $scan[$i]))
                @unlink(DATA_PATH . "cache/block/" . $scan[$i]);
    }

    public static function MainIstallCom()
    {
        $TemplatesSection = AdminPanel::SectionFile('FormatMainTop');
        $List = '';
        $SelectInstallModule = Simple_DbApi::select_db("modules", "*", "install", "yes", "name", 1, "", "");
        if (!empty($SelectInstallModule)) {
            foreach ($SelectInstallModule as $s => $nmod) {
                if (file_exists(DATA_PATH . "/modules/" . $nmod['name'] . "/admin.php")) {
                    if (file_exists("modules/" . $nmod['name'] . "/title.php")) {
                        include("modules/" . $nmod['name'] . "/title.php");
                        $text = $module_title;
                    } else $text = "no name";
                    $List .= AdminPanel::SectionAdmin($TemplatesSection, 2, "%URL%,%NAME%", "main-modules-setting-mod-" . $nmod['name'] . ".pl<><>" . $text . "");
                    unset($text);
                    unset($module_title);
                }
            }
        }

        $FormatMain = AdminPanel::SectionAdmin($TemplatesSection, 1, "%LI%,%ID%", $List . "<><>7");
        return $FormatMain;
    }

    public static function InsertSearchText($IdPage, $Text, $Page, $Name)
    {
        $TextSearch = strip_tags($Text);
        $TextSearch = str_replace("  ", "", $TextSearch);
        $TextSearch = str_replace("&nbsp;", " ", $TextSearch);
        $TextSearch = trim($TextSearch);
        $TextSearch = str_replace("\n", " ", $TextSearch);
        Simple_DbApi::insert_db("search", "id,module,page,name,text,id_page", "<><>" . $_GET['name_tamlates'] . "<><>" . $Page . "<><>" . $Name . "<><>" . $TextSearch . "<><>" . $IdPage);
    }

    public static function InsertAltSearchText($IdPage, $Text, $Page, $Name, $module)
    {
        $TextSearch = strip_tags($Text);
        $TextSearch = str_replace("  ", "", $TextSearch);
        $TextSearch = str_replace("&nbsp;", " ", $TextSearch);
        $TextSearch = trim($TextSearch);
        $TextSearch = str_replace("\n", " ", $TextSearch);
        Simple_DbApi::insert_db("search", "id,module,page,name,text,id_page", "<><>" . $module . "<><>" . $Page . "<><>" . $Name . "<><>" . $TextSearch . "<><>" . $IdPage);
    }

    public static function UpdateSearchPage($Id, $Page)
    {
        self::$db->query("UPDATE `" . _PREFIXDB_ . "search` SET  `page` =  '" . $Page . "' WHERE `module` = '" . $_GET['name_tamlates'] . "' AND `id_page` = '" . $Id . "' LIMIT 1 ;");
    }

    public static function UpdateSearchText($Id, $Text, $Name, $page)
    {
        $TextSearch = strip_tags($Text);
        $TextSearch = str_replace("   ", "", $TextSearch);
        $TextSearch = str_replace("&nbsp;", " ", $TextSearch);
        $TextSearch = trim($TextSearch);
        $TextSearch = str_replace("\n", " ", $TextSearch);
        $Count = Simple_DbApi::CountTable("search", "module,id_page", $_GET['name_tamlates'] . "<><>" . $Id);
        if ($Count > 0) {
            self::$db->query("UPDATE `" . _PREFIXDB_ . "search` SET  `text` =  '" . $TextSearch . "',`name` =  '" . strip_tags($Name) . "', page = '" . strip_tags($page) . "' WHERE `module` = '" . $_GET['name_tamlates'] . "' AND `id_page` = '" . $Id . "' LIMIT 1 ;");
        } else
            Simple_DbApi::insert_db("search", "id,module,page,name,text,id_page", "<><>" . $_GET['name_tamlates'] . "<><>" . $page . "<><>" . $Name . "<><>" . $TextSearch . "<><>" . $Id);
    }

    public static function DeleteSearch($Id)
    {
        self::$db->query("DELETE FROM `" . _PREFIXDB_ . "search` WHERE `id_page` = '" . $Id . "' AND `module` = '" . $_GET['name_tamlates'] . "'");
    }

    # END #

    ### ALERT

    public static function AlertWindow($title, $html, $type, $id)
    {
        switch ($type) {
            case 1 :
                if (file_exists('templates/admin/alert/success'))
                    $window = file_get_contents('templates/admin/alert/success');
                break;
            case 2 :
                if (file_exists('templates/admin/alert/warning'))
                    $window = file_get_contents('templates/admin/alert/warning');
                break;
            case 3 :
                if (file_exists('templates/admin/alert/error'))
                    $window = file_get_contents('templates/admin/alert/error');
                break;
            case 4 :
                if (file_exists('templates/admin/alert/promt'))
                    $window = file_get_contents('templates/admin/alert/promt');
                break;
            case 5 :
                if (file_exists('templates/admin/alert/promt'))
                    $window = file_get_contents('templates/admin/alert/edit');
                break;
        }
        if (!empty($window)) {
            $window = str_replace('{title}', $title, $window);
            $window = str_replace('{text}', $html, $window);
            $window = str_replace('{id}', $id, $window);
            if (empty($id))
                $window = str_replace('style="display:none;"', '', $window);
        }
        return $window;
    }

    public static function PathToModule()
    {
        $path = DATA_PATH . '/modules/' . $_GET['name_tamlates'] . '/';
        return $path;
    }

    public static function PathToBlock()
    {
        $path = DATA_PATH . 'block/' . $_GET['config'] . '/';
        return $path;
    }

    public static function CKeditor($content, $height, $name)
    {
        $html = '';
        if (file_exists('plugin/tiny_mce/tiny_mce.js')) {
            if (empty($height))
                $height = 30;
            if (empty($name)) $name = 'FCKeditor';
            $html .= '
			
			<script type="text/javascript" src="' . URL_SITE . 'plugin/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		language: "ru",
		relative_urls : false,
        convert_urls : false,
		file_browser_callback : "elFinderBrowser",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "' . URL_SITE_THEME . 'css/style.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : \'Bold text\', inline : \'b\'},
			{title : \'Red text\', inline : \'span\', styles : {color : \'#ff0000\'}},
			{title : \'Red header\', block : \'h1\', styles : {color : \'#ff0000\'}},
			{title : \'Example 1\', inline : \'span\', classes : \'example1\'},
			{title : \'Example 2\', inline : \'span\', classes : \'example2\'},
			{title : \'Table styles\'},
			{title : \'Table row 1\', selector : \'tr\', classes : \'tablerow1\'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
	
	function elFinderBrowser (field_name, url, type, win) {
  var elfinder_url = \'' . URL_SITE . 'plugin/elfinder/elfinder.html\';    // use an absolute path!
  tinyMCE.activeEditor.windowManager.open({
    file: elfinder_url,
    title: \'elFinder 2.0\',
    width: 900,  
    height: 450,
    resizable: \'yes\',
    inline: \'yes\',    // This parameter only has an effect if you use the inlinepopups plugin!
    popup_css: false, // Disable TinyMCE\'s default popup CSS
    close_previous: \'no\'
  }, {
    window: win,
    input: field_name
  });
  return false;
}
	
</script>';
            $html .= '<textarea cols="80" id="editor1' . $name . '" name="' . $name . '" rows="' . $height . '" style="width:100%">' . $content . '</textarea>';
        } else {
            $html .= '<textarea cols="80" id="editor1" name="' . $name . '" rows="10" style="width:100%; height: ' . $height . 'px">' . $content . '</stextarea>';
        }

        return $html;
    }

    public static function CKeditorAjax($text, $name)
    {
        $code = '<script type="text/javascript" src="/plugin/AjexFileManager/ajex.js"></script>';
        if (empty($name)) $name = 'FCKeditor';
        include("plugin/ckeditor/ckeditor.php");
        $CKEditor = new CKEditor();
        $CKEditor->basePath = '/plugin/ckeditor/';
        $CKEditor->returnOutput = true;
        $CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
        $code .= $CKEditor->editor($name, $text);
        $html = '<script type="text/javascript">
			    AjexFileManager.init({returnTo: \'ckeditor\',  editor: editorPro100, path: \'' . URL_SITE . 'plugin/AjexFileManager/\'});
			    </script>';
        $code = str_replace('CKEDITOR.replace', 'var editorPro100 = CKEDITOR.replace', $code);
        return $code . $html;

    }

    public static function CreatFolder($path)
    {
        if (!is_dir($path)) {
            mkdir($path);
        }
    }

    public static function CreatFile($path)
    {
        if (!file_exists($path)) {
            $fp = fopen($path, 'w');
            fclose($fp);
        }
    }

    public static function SeoForm($title, $key, $desc, $alias = '')
    {
        if (file_exists(DATA_PATH . 'admin/section/seoform')) {
            $ReadFile = file_get_contents(DATA_PATH . 'admin/section/seoform');
            $ReadFile = str_replace('{title}', $title, $ReadFile);
            $ReadFile = str_replace('{key}', $key, $ReadFile);
            $ReadFile = str_replace('{desc}', $desc, $ReadFile);
            $ReadFile = str_replace('{alias}', $alias, $ReadFile);
            return $ReadFile;
        }
    }

    public static function TestAliasExists($alias, $aliasvarnames, $aliasvarvalues)
    {
        $errorname = 0;
        $errorall = 0;
        if ($alias != '') {
            //name testing
            $selectalias = self::$db->query("select * from `aliases` where `name`='" . $alias . "' ");
            if (!empty($selectalias)) $errorname = 1;
        }
        if ($alias != '') {
            //name testing
            $selectalias = self::$db->query("select * from `aliases` where `name`='" . $alias . "' and `varnames`='" . $aliasvarnames . "' and `varvalues`='" . $aliasvarvalues . "' ");
            if (!empty($selectalias)) $errorall = 1;
        }
        if ($errorname == 0 || ($errorname == 1 && $errorall == 1)) return false;
        else return true;
    }

    public static function AliasAdd($alias, $aliasvarnames, $aliasvarvalues)
    {
        if (!self::TestAliasExists($alias, $aliasvarnames, $aliasvarvalues)) {
            $selectalias = self::$db->query("select * from `aliases` where `varnames`='" . $aliasvarnames . "' and `varvalues`='" . $aliasvarvalues . "' ");
            if (!empty($selectalias)) self::$db->query("update `aliases` set `name`='" . $alias . "' where `varnames`='" . $aliasvarnames . "' and `varvalues`='" . $aliasvarvalues . "' ");
            else self::$db->query("insert into `aliases` (id,name,varnames,varvalues) values ('','" . $alias . "','" . $aliasvarnames . "','" . $aliasvarvalues . "') ");
            return true;
        } else return false;
    }

    public static function AliasSelect($aliasvarnames, $aliasvarvalues)
    {
        $alias = '';
        $selectalias = self::$db->query("select * from `aliases` where `varnames`='" . $aliasvarnames . "' and `varvalues`='" . $aliasvarvalues . "' ");
        if (!empty($selectalias)) {
            $da = current($selectalias);
            $alias = $da['name'];
        }
        return $alias;
    }

    public static function SeoFormBase()
    {
        if (file_exists(DATA_PATH . 'admin/section/seoformbase')) {
            $ReadFile = file_get_contents(DATA_PATH . 'admin/section/seoformbase');

            if (isset($_POST['SIMPLE_SAVE'])) {
                $metaTitle = strip_tags($_POST['SIMPLE_META_TITLE']);
                $metaKey = strip_tags($_POST['SIMPLE_META_KEYWORD']);
                $metaDesc = strip_tags($_POST['SIMPLE_META_DESC']);

                $fp = fopen(DATA_PATH . 'modules/' . $_GET['name_tamlates'] . '/seo.php', 'w');
                flock($fp, LOCK_EX);
                fwrite($fp, "<?php\n\$metaTitle=\"" . $metaTitle . "\";\n\$metaKey=\"" . $metaKey . "\";\n\$metaDesc=\"" . $metaDesc . "\";\n?>");
                flock($fp, LOCK_UN);
                fclose($fp);
                echo AdminPanel::AlertWindow('Успешно', 'Данные обновлены', 1, 0);
            }

            if (file_exists(DATA_PATH . 'modules/' . $_GET['name_tamlates'] . '/seo.php')) include(DATA_PATH . 'modules/' . $_GET['name_tamlates'] . '/seo.php');
            else {
                $fp = fopen(DATA_PATH . 'modules/' . $_GET['name_tamlates'] . '/seo.php', 'w');
                flock($fp, LOCK_EX);
                fwrite($fp, "<?php\n\$metaTitle=\"" . $metaTitle . "\";\n\$metaKey=\"" . $metaKey . "\";\n\$metaDesc=\"" . $metaDesc . "\";\n?>");
                flock($fp, LOCK_UN);
                fclose($fp);
                include(DATA_PATH . 'modules/' . $_GET['name_tamlates'] . '/seo.php');
            }

            $ReadFile = str_replace('{title}', $metaTitle, $ReadFile);
            $ReadFile = str_replace('{key}', $metaKey, $ReadFile);
            $ReadFile = str_replace('{desc}', $metaDesc, $ReadFile);
            return $ReadFile;
        }
    }

    public static function UserEvents($event)
    {
        Simple_DbApi::insert_db('history', 'id,text,date,ip', '<><>' . $event . '<><>' . date('Y-m-d H:i:s') . '<><>' . $_SERVER['REMOTE_ADDR']);
    }

    public static function TitleComponent($name, $type = 'module')
    {
        if ($type == 'module') {
            if (file_exists("modules/" . $name . "/title.php")) include("modules/" . $name . "/title.php");
            if (empty($module_title)) $module_title = $name;
            $title = $module_title;
        } else {
            if (file_exists("block/" . $name . "/title.php")) include("block/" . $name . "/title.php");
            if (empty($BlockName)) $BlockName = $name;
            $title = $BlockName;
        }
        return $title;
    }


}

?>