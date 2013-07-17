CREATE TABLE IF NOT EXISTS `#__jblance_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idFrom` int(11) NOT NULL,
  `idTo` int(11) NOT NULL,
  `date_sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `subject` varchar(250) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `project_id` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;