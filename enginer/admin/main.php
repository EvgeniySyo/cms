<?php
if(!isset($_GET['config'])) $conf = "main";
else $conf = $_GET['config'];
if(!file_exists(DATA_PATH."/admin/main/".$conf.".php")) include("main/main.php");
else include("main/".$conf.".php");
?>