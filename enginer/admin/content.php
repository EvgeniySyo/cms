<?php
if(!isset($_GET['admin'])) $admin = "main";
else $admin = $_GET['admin'];
if(!isset($_GET['ajax']) || !isset($_GET['ajax1']))
{ 
	$_SESSION['SIMPLE_MODULE'] = !empty($_GET['name_tamlates']) ? $_GET['name_tamlates'] : '';
	$_SESSION['SIMPLE_MAIN'] = !empty($_GET['admin']) ? $_GET['admin'] : '';
	$_SESSION['SIMPLE_BLOCK'] = !empty($_GET['config']) ? $_GET['config'] : '';
}
CMS::Admin_Page($admin);
?>