ALTER TABLE `#__jblance_message` ADD (attachment VARCHAR(255));

ALTER TABLE `#__jblance_usergroup` ADD (joomla_ug_id VARCHAR(50) DEFAULT '2' NOT NULL);

CREATE TABLE IF NOT EXISTS `#__jblance_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `budgetmin` float NOT NULL DEFAULT '0',
  `budgetmax` float NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;