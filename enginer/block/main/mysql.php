<?php

CMS::$db->query("CREATE TABLE `"._PREFIXDB_."block_".$nameBlock."` (
  `id` int(100) NOT NULL auto_increment,
  `id_cat` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `module` text NOT NULL,
  `select_url` text NOT NULL,
  `order` int(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;");

?>