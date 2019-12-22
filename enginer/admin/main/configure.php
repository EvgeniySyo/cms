<?php
if(!isset($_GET['config'])) $conf = "main";
else $conf = $_GET['config'];

if(!file_exists("configure/".$conf.".php")) include("configure/main.php");
else include("configure/".$conf.".php");

echo $_GET['config'];
?>