CREATE TABLE IF NOT EXISTS `#__jblance_portfolio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `id_category` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `finish_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `picture` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
