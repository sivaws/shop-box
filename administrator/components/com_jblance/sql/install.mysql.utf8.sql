CREATE TABLE IF NOT EXISTS `#__jblance_bid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `project_id` int(11) NOT NULL DEFAULT '0',
  `amount` float NOT NULL,
  `delivery` int(11) NOT NULL DEFAULT '0',
  `bid_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `details` mediumtext NOT NULL,
  `outbid` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL,
  `milestone_perc` int(11) NOT NULL DEFAULT '0',
  `attachment` varchar(255) DEFAULT NULL,
  `is_nda_signed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ibid` (`project_id`,`user_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `budgetmin` float NOT NULL DEFAULT '0',
  `budgetmax` float NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(80) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `parent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `params` longtext,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_custom_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_title` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `show_type` enum('left-to-right','top-to-bottom') NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `lang_code` varchar(20) NOT NULL DEFAULT 'COM_JBLANCE_',
  `tips` varchar(100) DEFAULT NULL,
  `ordering` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL,
  `registration` tinyint(1) NOT NULL DEFAULT '1',
  `gdesc` varchar(500) DEFAULT NULL,
  `field_type` varchar(50) DEFAULT 'group',
  `field_for` varchar(50) NOT NULL DEFAULT 'profile',
  `value_type` varchar(50) NOT NULL DEFAULT 'custom',
  `searchPage` tinyint(1) NOT NULL DEFAULT '0',
  `visible` varchar(100) NOT NULL DEFAULT 'all',
  `field_code` varchar(255) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_custom_field_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldid` int(11) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `projectid` int(11) NOT NULL DEFAULT '0',
  `value` varchar(512) NOT NULL,
  `access` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceNo` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `amount` float DEFAULT NULL,
  `feeFixed` float DEFAULT NULL,
  `feePerc` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `approved` int(1) NOT NULL DEFAULT '0',
  `date_deposit` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_approval` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gateway` varchar(20) NOT NULL,
  `trans_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_emailtemplate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `templatefor` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_escrow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL DEFAULT '0',
  `to_id` int(11) NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT '0',
  `project_id` int(11) DEFAULT '0',
  `date_transfer` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_accept` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `note` text,
  `date_release` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(50) NOT NULL,
  `from_trans_id` int(11) NOT NULL DEFAULT '0',
  `to_trans_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actor` int(11) NOT NULL DEFAULT '0',
  `target` int(11) NOT NULL DEFAULT '0',
  `project_id` int(11) NOT NULL DEFAULT '0',
  `title` text,
  `content` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` tinyint(3) NOT NULL,
  `params` text NOT NULL,
  `points` int(4) NOT NULL DEFAULT '1',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_feed_hide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `date_post` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `message` text NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

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
  `attachment` VARCHAR(255),
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `frequency` varchar(32) NOT NULL DEFAULT 'instantly',
  `notifyNewProject` tinyint(1) NOT NULL DEFAULT '1',
  `notifyBidWon` tinyint(1) NOT NULL DEFAULT '1',
  `notifyNewMessage` tinyint(1) NOT NULL DEFAULT '1',
  `notifyBidNewAcceptDeny` tinyint(1) NOT NULL DEFAULT '1',
  `notifyNewForumMessage` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_paymode` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gateway_name` varchar(100) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `shortcode` varchar(5) NOT NULL,
  `gwcode` varchar(20) DEFAULT NULL,
  `withdraw` tinyint(1) DEFAULT '0',
  `withdrawFee` float DEFAULT '0',
  `withdrawDesc` varchar(500) DEFAULT NULL,
  `depositfeeFixed` float DEFAULT '0',
  `depositfeePerc` float DEFAULT '0',
  `params` longtext,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(155) NOT NULL,
  `days` int(10) NOT NULL DEFAULT '0',
  `days_type` set('days','weeks','months','years') NOT NULL DEFAULT 'days',
  `price` float NOT NULL DEFAULT '0',
  `discount` float NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `invisible` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` tinyint(3) NOT NULL DEFAULT '0',
  `time_limit` int(10) NOT NULL DEFAULT '0',
  `alert_admin` tinyint(1) NOT NULL DEFAULT '1',
  `adwords` mediumtext NOT NULL,
  `bonusFund` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `finish_msg` varchar(155) NOT NULL,
  `params` longtext NOT NULL,
  `ug_id` int(11) NOT NULL DEFAULT '1',
  `default_plan` tinyint(4) DEFAULT '0',
  `user_featured` tinyint(1) NOT NULL DEFAULT '0',
  `lifetime` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_plan_subscr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `plan_id` int(10) NOT NULL DEFAULT '0',
  `approved` int(1) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `tax_percent` float NOT NULL DEFAULT '0',
  `access_count` int(10) NOT NULL DEFAULT '0',
  `gateway` varchar(45) NOT NULL DEFAULT 'undefined',
  `gateway_id` varchar(200) NOT NULL,
  `trans_id` int(11) NOT NULL DEFAULT '0',
  `fund` int(11) NOT NULL DEFAULT '0',
  `date_buy` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_approval` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `invoiceNo` varchar(100) DEFAULT NULL,
  `lifetime` tinyint(1) NOT NULL DEFAULT '0',
  `bids_allowed` int(11) NOT NULL DEFAULT '0',
  `bids_left` int(11) NOT NULL DEFAULT '0',
  `projects_allowed` int(11) NOT NULL DEFAULT '0',
  `projects_left` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

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

CREATE TABLE IF NOT EXISTS `#__jblance_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_title` varchar(255) NOT NULL,
  `id_category` varchar(50) NOT NULL,
  `start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expires` int(11) NOT NULL DEFAULT '0',
  `assigned_userid` int(11) NOT NULL DEFAULT '0',
  `publisher_userid` int(11) NOT NULL DEFAULT '0',
  `status` varchar(32) NOT NULL,
  `budgetmin` float NOT NULL DEFAULT '0',
  `budgetmax` float NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `profit` float NOT NULL DEFAULT '0',
  `paid_amt` float NOT NULL DEFAULT '0',
  `paid_status` varchar(32) NOT NULL,
  `approved` int(1) NOT NULL DEFAULT '1',
  `profit_additional` float NOT NULL DEFAULT '0',
  `is_urgent` tinyint(1) NOT NULL DEFAULT '0',
  `is_private` tinyint(1) NOT NULL DEFAULT '0',
  `is_sealed` tinyint(1) NOT NULL DEFAULT '0',
  `is_nda` tinyint(1) NOT NULL DEFAULT '0',
  `metakey` text,
  `metadesc` text,
  `buyer_commission` float NOT NULL DEFAULT '0',
  `lancer_commission` float NOT NULL DEFAULT '0',
  `accept_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_project_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL DEFAULT '0',
  `file_name` varchar(255) NOT NULL,
  `show_name` varchar(255) NOT NULL,
  `hash` varchar(32) DEFAULT NULL,
  `is_nda_file` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actor` int(11) NOT NULL DEFAULT '0',
  `target` int(11) NOT NULL DEFAULT '0',
  `project_id` int(11) NOT NULL DEFAULT '0',
  `rate_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comments` text NOT NULL,
  `rate_type` varchar(32) NOT NULL,
  `quality_clarity` int(11) NOT NULL DEFAULT '0',
  `communicate` int(11) NOT NULL DEFAULT '0',
  `expertise_payment` int(11) NOT NULL DEFAULT '0',
  `professional` int(11) NOT NULL DEFAULT '0',
  `hire_work_again` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniquestring` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `date_created` datetime NOT NULL,
  `label` varchar(100) NOT NULL,
  `method` varchar(100) NOT NULL,
  `params` varchar(100) NOT NULL,
  `defaultaction` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_report_reporter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_trans` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `transaction` varchar(500) NOT NULL,
  `fund_plus` float NOT NULL DEFAULT '0',
  `fund_minus` float NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `biz_name` varchar(100) NOT NULL,
  `status` text NOT NULL,
  `status_access` int(11) NOT NULL DEFAULT '0',
  `posted_on` datetime NOT NULL,
  `picture` varchar(50) NOT NULL,
  `thumb` text NOT NULL,
  `invite` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `latitude` float NOT NULL DEFAULT '255',
  `longitude` float NOT NULL DEFAULT '255',
  `ug_id` int(11) NOT NULL DEFAULT '0',
  `search_email` tinyint(1) NOT NULL DEFAULT '1',
  `notify` tinyint(1) NOT NULL DEFAULT '1',
  `suspend` tinyint(1) NOT NULL DEFAULT '0',
  `suspend_reason` varchar(100) NOT NULL,
  `featured` tinyint(1) DEFAULT '0',
  `rate` float DEFAULT NULL,
  `id_category` varchar(50) NOT NULL,
  `featured_expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_usergroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `approval` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `create_group` tinyint(1) DEFAULT '1',
  `ordering` int(11) NOT NULL,
  `params` longtext NOT NULL,
  `freeMode` tinyint(1) NOT NULL DEFAULT '0',
  `joomla_ug_id` VARCHAR(50) DEFAULT '2' NOT NULL,
  `skipPlan` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_usergroup_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jblance_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceNo` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `gateway` varchar(32) NOT NULL,
  `amount` float NOT NULL,
  `withdrawFee` float NOT NULL,
  `finalAmount` float NOT NULL,
  `approved` int(1) NOT NULL DEFAULT '0',
  `date_approval` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_withdraw` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` longtext,
  `trans_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;