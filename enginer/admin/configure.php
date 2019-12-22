<?php
if(!isset($_GET['config'])) $conf = "main";
else $conf = $_GET['config'];
if(!file_exists(DATA_PATH."/admin/configure/".$conf.".php")) include("configure/main.php");
else include("configure/".$conf.".php");
?>