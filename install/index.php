<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');
define("INSTALL_PRO100", "yes");
session_start();
define('DATA_PATH', $_SERVER['DOCUMENT_ROOT'] . '/enginer/');
require("../includes/CMS.class.php");
$warning = '';
if (!file_exists("includes/lock.pro")) {
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
            "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Установка</title>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="js_admin/j.js"></script>
        <script type="text/javascript" src="js_admin/m.js"></script>
        <script type="text/javascript" src="js_admin/s.js"></script>
        <script type="text/javascript" src="js_admin/w.js"></script>
        <link rel="stylesheet" type="text/css" media="all" href="js_admin/jscroll.css"/>
        <script type="text/javascript">
            $(function () {
                $('.scroll-pane').jScrollPane({showArrows: true, scrollbarWidth: 19, dragMaxHeight: 43});
            });
        </script>
    </head>
    <body>
    <?php
    if (!isset($_GET['step']) || $_GET['step'] < 1 || $_GET['step'] > 5) include("step1.php");
    else {
        if ($_GET['step'] == 2 || $_GET['step'] == 3 || $_GET['step'] == 4 || $_GET['step'] == 5) {
            switch ($_GET['step']) {
                case 2:
                    if (isset($_POST['back'])) include("step1.php");
                    if (isset($_POST['step'])) {
                        $stepGO = false;
                        $nameSite = strip_tags($_POST['nameSite']);
                        $keywords = strip_tags($_POST['keySite']);
                        $_SESSION['NameSite'] = $nameSite;
                        $_SESSION['keywords'] = $keywords;
                        $pathToengi = $_POST['path'];
                        if (!isset($_SESSION['enginerPATH'])) $PathTo = $_SERVER['DOCUMENT_ROOT'] . '/enginer/';
                        else $PathTo = $_SESSION['enginerPATH'];
                        if ($pathToengi[(strlen($pathToengi) - 1)] != '/') $pathToengi = $pathToengi . '/';
                        if (file_exists($pathToengi . 'system.php')) {
                            $stepGO = true;
                            $_SESSION['enginerPATH'] = $pathToengi;

                        } else {
                            $warning = '<div class="warning">не верно указан путь до папки enginer</div>';
                        }

                        if ($stepGO == true) include('step3.php');
                        else include("step2.php");
                    }
                    break;
                case 3:
                    if (isset($_POST['back'])) {
                        if (!isset($_SESSION['enginerPATH'])) $PathTo = $_SERVER['DOCUMENT_ROOT'] . '/enginer/';
                        else $PathTo = $_SESSION['enginerPATH'];
                        include("step2.php");
                    }
                    if (isset($_POST['step'])) {
                        $connect = true;
                        $Server = $_POST['serverDB'];
                        $port = $_POST['portDB'];
                        $loginDB = $_POST['loginDB'];
                        $passDB = $_POST['passDB'];
                        $db = $_POST['db'];
                        $prefix = $_POST['prefix'];
                        CMS::init_db('mypdo://' . $loginDB . ':' . $passDB . '@' . $Server . '/' . $db . '?enc=utf8mb4&persist=true'); //= mysql_connect($Server.':'.$port,$loginDB,$passDB);

                        $_SESSION['serverDB'] = $Server;
                        $_SESSION['port'] = $port;
                        $_SESSION['Login'] = $loginDB;
                        $_SESSION['passDB'] = $passDB;
                        $_SESSION['DB'] = $db;
                        $_SESSION['prefix'] = $prefix;
                        include("step4.php");
                    }
                    break;
                case 4:
                    if (isset($_POST['back'])) include("step3.php");
                    if (isset($_POST['step'])) {
                        $login = $_POST['login'];
                        $pass = $_POST['pass'];
                        $passReplay = $_POST['replaypass'];
                        $mail = $_POST['mail'];
                        $install = true;
                        if (!preg_match("/[a-zA-Z0-9]/i", $login)) {
                            $install = false;
                            $warning .= '<div class="warning">только латинские символы и цифры в логине</div>';
                        }
                        if (strlen($pass) < 6) {
                            $install = false;
                            $warning .= '<div class="warning">слишком короткий пароль (не менее 6 символов)</div>';
                        }
                        if ($pass != $passReplay) {
                            $install = false;
                            $warning .= '<div class="warning">пароль и повторный пароль не совпадают</div>';
                        }
                        if (!preg_match("/(([[:alnum:]]+(-|_)?[[:alnum:]]+)\.?)+@([[:alnum:]]+(-|_)?[[:alnum:]]+\.)+[[:alnum:]]/i", $mail)) {
                            $install = false;
                            $warning .= '<div class="warning">неверный e-mail</div>';
                        }

                        if ($install == false) include("step4.php");
                        else {
                            $date = date("j.m.Y-G:i:s");
                            CMS::init_db('mypdo://' . $_SESSION['Login'] . ':' . $_SESSION['passDB'] . '@' . $_SESSION['serverDB'] . '/' . $_SESSION['DB'] . '?enc=utf8mb4&persist=true');
                            //mysql_connect($_SESSION['serverDB'].':'.$_SESSION['port'],$_SESSION['Login'],$_SESSION['passDB']);
                            //mysql_select_db($_SESSION['DB']);

                            CMS::$db->query("DROP TABLE IF EXISTS `" . $_SESSION['prefix'] . "accounts`");
                            CMS::$db->query("DROP TABLE IF EXISTS `" . $_SESSION['prefix'] . "ban_ip`");
                            CMS::$db->query("DROP TABLE IF EXISTS `" . $_SESSION['prefix'] . "block`");
                            CMS::$db->query("DROP TABLE IF EXISTS `" . $_SESSION['prefix'] . "hits`");
                            CMS::$db->query("DROP TABLE IF EXISTS `" . $_SESSION['prefix'] . "modules`");
                            CMS::$db->query("DROP TABLE IF EXISTS `" . $_SESSION['prefix'] . "search`");
                            CMS::$db->query("DROP TABLE IF EXISTS `" . $_SESSION['prefix'] . "secutity`");
                            CMS::$db->query("DROP TABLE IF EXISTS `" . $_SESSION['prefix'] . "statistics`");
                            CMS::$db->query("DROP TABLE IF EXISTS `" . $_SESSION['prefix'] . "templates`");


                            /* SQL */

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "accounts` (
  `user_id` int(100) NOT NULL auto_increment,
  `login` varchar(15) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `mail` varchar(30) NOT NULL default '',
  `acsess_level` varchar(100) NOT NULL default '',
  `ip_user` varchar(15) NOT NULL default '',
  `date` varchar(20) NOT NULL default '',
  `avatar` varchar(70) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `family` varchar(30) NOT NULL default '',
  `otch` varchar(30) NOT NULL default '',
  `icq` varchar(10) NOT NULL default '',
  `url` varchar(50) NOT NULL default '',
  `interest` text NOT NULL,
  `fal` varchar(4) NOT NULL default '',
  `birth` varchar(10) NOT NULL default '',
  `from` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`user_id`,`login`)
) ENGINE=MyISAM ;
");

                            CMS::$db->query("INSERT INTO `" . $_SESSION['prefix'] . "accounts` VALUES ('', '" . $login . "', '" . md5(md5($pass)) . "', '" . $mail . "', '200', '" . $_SERVER['HTTP_HOST'] . "', '" . $date . "', '', '', '', '', '', '', '', '', '', '');
						");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "admin_acsess` (
  `id` int(100) NOT NULL auto_increment,
  `ip` varchar(20) NOT NULL default '',
  `date` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;
");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "ban_ip` (
  `id` int(100) NOT NULL auto_increment,
  `ip` varchar(15) NOT NULL default '',
  `alert` text NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY  (`id`,`ip`)
) ENGINE=MyISAM;
");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "block` (
  `name_block` varchar(30) NOT NULL default '',
  `posision` varchar(15) NOT NULL default '',
  `ves` int(100) default NULL,
  `status` char(3) NOT NULL default '',
  `titul` varchar(45) NOT NULL default '',
  `on_module` text NOT NULL,
  `for_block` char(2) NOT NULL default '',
  `install` char(3) NOT NULL default '',
  `titul_on` char(3) NOT NULL default '',
  PRIMARY KEY  (`name_block`)
) ENGINE=MyISAM;
");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "hits` (
  `id` int(100) NOT NULL auto_increment,
  `date` varchar(10) NOT NULL default '',
  `hits` int(100) NOT NULL default '0',
  `h1` int(100) default NULL,
  `h2` int(100) default NULL,
  `h3` int(100) default NULL,
  `h4` int(100) default NULL,
  `h5` int(100) default NULL,
  `h6` int(100) default NULL,
  `h7` int(100) default NULL,
  `h8` int(100) default NULL,
  `h9` int(100) default NULL,
  `h10` int(100) default NULL,
  `h11` int(100) default NULL,
  `h12` int(100) default NULL,
  `h13` int(100) default NULL,
  `h14` int(100) default NULL,
  `h15` int(100) default NULL,
  `h16` int(100) default NULL,
  `h17` int(100) default NULL,
  `h18` int(100) default NULL,
  `h19` int(100) default NULL,
  `h20` int(100) default NULL,
  `h21` int(100) default NULL,
  `h22` int(100) default NULL,
  `h23` int(100) default NULL,
  `h24` int(100) default NULL,
  PRIMARY KEY  (`id`,`date`)
) ENGINE=MyISAM;
");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "log` (
  `id` int(100) NOT NULL auto_increment,
  `ip` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;
");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "modules` (
  `name` varchar(15) NOT NULL default '',
  `status` char(3) NOT NULL default '',
  `acsess` char(3) NOT NULL default '',
  `install` char(3) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM;
");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "search` (
  `id` int(100) NOT NULL auto_increment,
  `module` varchar(255) NOT NULL,
  `page` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `id_page` int(100) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `text` (`text`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM;
");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "secutity` (
  `id` int(100) NOT NULL default '0',
  `date` varchar(20) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  `zapros` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;
");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "statistics` (
  `id` int(100) NOT NULL auto_increment,
  `date` varchar(10) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  `came` text NOT NULL,
  `agent` text NOT NULL,
  `time` varchar(8) NOT NULL,
  PRIMARY KEY  (`id`,`date`)
) ENGINE=MyISAM;
");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "statistics_bot` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `ip` varchar(50) NOT NULL,
  `agent` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;
");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "templates` (
  `name` varchar(30) NOT NULL default '',
  `status` char(3) NOT NULL default '',
  PRIMARY KEY  (`name`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM;
");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "history` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;
");

                            CMS::$db->query("
CREATE TABLE IF NOT EXISTS `" . $_SESSION['prefix'] . "aliases` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `varnames` text NOT NULL,
  `varvalues` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;
");

                            $dirTheme = scandir('../templates');
                            $DefaultTemplate = '';
                            for ($z = 2; $z < count($dirTheme); $z++) {
                                if ($dirTheme[$z] != 'admin') {
                                    $DefaultTemplate = $dirTheme[$z];
                                    break;
                                }
                            }
                            $DefaultTemplate = !empty($DefaultTemplate) ? $DefaultTemplate : 'default';

                            CMS::$db->query("
						INSERT INTO `" . $_SESSION['prefix'] . "templates` (`name` ,`status`) VALUES ('$DefaultTemplate', '');
						");
                            /* */
                            //chmod('../includes/', 777);
                            //chmod($_SESSION['enginerPATH'].'config.php', 666);
                            //chmod('../includes/path.php', 666);
                            $fp = fopen($_SESSION['enginerPATH'] . 'config.php', 'w');
                            flock($fp, LOCK_EX);
                            fwrite($fp,
                                "<?php
\$db[\"server\"] = \"" . str_replace("\"", "\\\"", $_SESSION['serverDB']) . "\";
\$db[\"port\"] = \"" . str_replace("\"", "\\\"", $_SESSION['port']) . "\";
\$db[\"login\"] = \"" . str_replace("\"", "\\\"", $_SESSION['Login']) . "\";
\$db[\"password\"] = \"" . str_replace("\"", "\\\"", $_SESSION['passDB']) . "\";
\$db[\"data_base\"] = \"" . str_replace("\"", "\\\"", $_SESSION['DB']) . "\";
\$db[\"prefix\"] = \"" . str_replace("\"", "\\\"", $_SESSION['prefix']) . "\";
\$html[\"title\"] = \"" . str_replace("\"", "\\\"", $_SESSION['NameSite']) . "\";
\$html[\"keywords\"] = \"" . str_replace("\"", "\\\"", $_SESSION['keywords']) . "\";
\$html[\"charset\"] = \"windows-1251\";
\$html[\"templates\"]=\"" . str_replace("\"", "\\\"", $DefaultTemplate) . "\";
\$languages = \"ru\";
\$default_module = \"main\";
\$admin_data = \"admin\";
\$date_create = \"" . date("j.m.Y") . "\";
\$buffer_date = \"on\";
\$timing = \"off\";
\$statistic = \"on\";
\$error = \"off\";
\$closed_site = \"off\";
\$url_site = \"\";
\$users_on_site = \"off\";
\$look_users_down = \"on\";
\$html[\"description\"] = \"\";
?>");
                            flock($fp, LOCK_UN);
                            fclose($fp);
                            $fp = fopen('../includes/path.php', 'w');
                            fwrite($fp, "<?php\n\$data_path = \"" . str_replace("\"", "\\\"", $_SESSION['enginerPATH']) . "\";\n?>");
                            fclose($fp);
                            //chmod('../includes/lock.pro', 666);
                            $fp = fopen('../includes/lock.pro', 'w');
                            fwrite($fp, 'Simple CMS');
                            fclose($fp);
                            unset($_SESSION);
                            include('step5.php');
                        }
                    }
                    break;
            }
        } else {
            if ($_GET['step'] == 1 && $_POST['yes'] == 'on') {
                if (!isset($_SESSION['enginerPATH'])) $PathTo = $_SERVER['DOCUMENT_ROOT'] . '/enginer/';
                else $PathTo = $_SESSION['enginerPATH'];
                include("step2.php");
            } else include("step1.php");
        }
    }

    ?>
    </div>
    <div class="clear"></div>
    <div class="line"></div>
    </div>
    </body>
    </html>
<?php } ?>