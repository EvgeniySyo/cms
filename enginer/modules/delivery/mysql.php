<?php

CMS::$db->query("
CREATE TABLE IF NOT EXISTS `"._PREFIXDB_.$nameInstallMod."` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `coords` varchar(255) NOT NULL,
  `type` int NOT NULL,
  `href` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
");


CMS::$db->query("
CREATE TABLE IF NOT EXISTS `"._PREFIXDB_.$nameInstallMod."_img` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `store_id` int(100) NOT NULL,
  `ext` varchar(10) NOT NULL,
  `type` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
");


?>