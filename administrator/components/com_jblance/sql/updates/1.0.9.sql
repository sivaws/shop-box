ALTER TABLE `#__jblance_project` ADD (`is_nda` tinyint(1) NOT NULL DEFAULT '0');

ALTER TABLE `#__jblance_project_file` ADD (`is_nda_file` tinyint(1) NOT NULL DEFAULT '0');

ALTER TABLE `#__jblance_bid` ADD (`attachment` varchar(255) DEFAULT NULL);
ALTER TABLE `#__jblance_bid` ADD (`is_nda_signed` tinyint(1) NOT NULL DEFAULT '0');
