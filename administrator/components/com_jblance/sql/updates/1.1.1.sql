CREATE TABLE IF NOT EXISTS `#__jblance_forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `date_post` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `message` text NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

ALTER TABLE `#__jblance_notify` ADD (`notifyNewForumMessage` tinyint(1) NOT NULL DEFAULT '1');