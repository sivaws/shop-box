CREATE TABLE IF NOT EXISTS `#__jblance_notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `frequency` varchar(32) NOT NULL DEFAULT 'instantly',
  `notifyNewProject` tinyint(1) NOT NULL DEFAULT '1',
  `notifyBidWon` tinyint(1) NOT NULL DEFAULT '1',
  `notifyNewMessage` tinyint(1) NOT NULL DEFAULT '1',
  `notifyBidNewAcceptDeny` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;