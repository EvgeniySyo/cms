<?php
CMS::$db->query("
CREATE TABLE `"._PREFIXDB_.$nameInstallMod."_cat` (
  `id_c` int(100) NOT NULL auto_increment,
  `name_c` varchar(255) NOT NULL,
  `type` varchar(4) NOT NULL,
  `description` TEXT NOT NULL,
  `order` int(100) NOT NULL,
  PRIMARY KEY  (`id_c`)
) ENGINE=MyISAM;
");
CMS::$db->query("
CREATE TABLE `"._PREFIXDB_.$nameInstallMod."` (
  `id` int(100) NOT NULL auto_increment,
  `id_cat` int(100) NOT NULL,
  `type` varchar(3) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `order` int(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;
");



?>