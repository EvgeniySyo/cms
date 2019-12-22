<?php
CMS::$db->query("CREATE TABLE `"._PREFIXDB_.$nameInstallMod."` (
  `id` bigint(20) NOT NULL auto_increment,
  `type` varchar(20) NOT NULL default '',
  `name` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `parent` varchar(100) NOT NULL default '',
  `search` text NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `path` int(2) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` int(3) NOT NULL,
  `order` int(100) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `search` (`search`)
) ENGINE=MyISAM;")
?>