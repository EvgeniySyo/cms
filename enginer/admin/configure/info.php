<?php

$TemplatesSection = CMS::SectionFile('Info');

include(DATA_PATH."system.php");

$Os = explode(" ",php_uname());
$Os = $Os[0];
$phpversion = phpversion();
if(extension_loaded('gd')) $GB = CMS::SectionAdmin($TemplatesSection,5,"","");
else $GB = CMS::SectionAdmin($TemplatesSection,4,"","");
$TimeServer = date("H:i:s");
$NameServer = $_SERVER['SERVER_NAME'];
$IpServer = $_SERVER['SERVER_ADDR'];
$SoftServer = $_SERVER['SERVER_SOFTWARE'];
$Protocol = $_SERVER['SERVER_PROTOCOL'];
$PortServer = $_SERVER['SERVER_PORT'];
$MaxTime = ini_get('max_execution_time');
if(ini_get('safe_mode') == 1 || strtolower(ini_get('safe_mode')) == 'on') $SafeMode = CMS::SectionAdmin($TemplatesSection,5,"","");
else $SafeMode = CMS::SectionAdmin($TemplatesSection,4,"","");
if(ini_get('magic_quotes_gpc') == 1 || strtolower(ini_get('magic_quotes_gpc')) == 'on') $MagicQuo = CMS::SectionAdmin($TemplatesSection,5,"","");
else $MagicQuo = CMS::SectionAdmin($TemplatesSection,4,"","");
if(ini_get('register_globals')==1) $RegGlobal = CMS::SectionAdmin($TemplatesSection,5,"","");
else $RegGlobal = CMS::SectionAdmin($TemplatesSection,4,"","");

echo CMS::SectionAdmin($TemplatesSection,3,"%VERSION%,%VERSIONPHP%,%OS%,%GD%,%TIME%,%NAMESERVER%,%IP%,%SOFT%,%PRO%,%PORT%,%EXE%,%SAFE%,%MAG%,%REG%,%CMS%","".$lisense."<><>".$phpversion."<><>".$Os."<><>".$GB."<><>".$TimeServer."<><>".$NameServer."<><>".$IpServer."<><>".$SoftServer."<><>".$Protocol."<><>".$PortServer."<><>".$MaxTime."<><>".$SafeMode."<><>".$MagicQuo."<><>".$RegGlobal."<><>".$enginering."");

echo CMS::SectionAdmin($TemplatesSection,2,"","");
?>