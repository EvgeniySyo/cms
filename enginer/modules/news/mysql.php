<?phpCMS::$db->query("CREATE TABLE IF NOT EXISTS `"._PREFIXDB_.$nameInstallMod."` (  `id` int(100) NOT NULL auto_increment,  `id_cat` int(100) NOT NULL,  `name` varchar(250) NOT NULL,  `search` text NOT NULL,  `small` text NOT NULL,  `full` text NOT NULL,  `date` date NOT NULL,  `type` varchar(3) NOT NULL,  `read` int(100) NOT NULL,  `time` time NOT NULL,  `comment` int(15) NOT NULL,  `countcomments` int(100) NOT NULL,  `dateend` date NOT NULL,  `timeend` time NOT NULL,  `status` INT( 3 ) NOT NULL,  PRIMARY KEY  (`id`),  FULLTEXT KEY `search` (`search`),  FULLTEXT KEY `small` (`small`),  FULLTEXT KEY `name` (`name`)) ENGINE=MyISAM;");CMS::$db->query("CREATE TABLE IF NOT EXISTS `"._PREFIXDB_.$nameInstallMod."_comment` (  `id` int(100) NOT NULL auto_increment,  `id_cat` int(100) NOT NULL,  `IdNews` int(100) NOT NULL,  `name` varchar(150) NOT NULL,  `text` text NOT NULL,  `date` date NOT NULL,  `dateh` time NOT NULL,  `ip` varchar(15) NOT NULL,  PRIMARY KEY  (`id`)) ENGINE=MyISAM;");CMS::$db->query("CREATE TABLE IF NOT EXISTS `"._PREFIXDB_.$nameInstallMod."_cat` (  `ids` int(100) NOT NULL auto_increment,  `names` varchar(250) NOT NULL,  `cat_title` varchar(255) NOT NULL,  `cat_key` varchar(255) NOT NULL,  `cat_dec` varchar(255) NOT NULL,  PRIMARY KEY  (`ids`),  KEY `name` (`names`)) ENGINE=MyISAM;");?>