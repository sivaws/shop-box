ALTER TABLE `#__jblance_project` ADD (profit_additional FLOAT DEFAULT '0' NOT NULL);
ALTER TABLE `#__jblance_project` ADD (is_urgent TINYINT(1) DEFAULT '0' NOT NULL);
ALTER TABLE `#__jblance_project` ADD (is_private TINYINT(1) DEFAULT '0' NOT NULL);
ALTER TABLE `#__jblance_project` ADD (is_sealed TINYINT(1) DEFAULT '0' NOT NULL);