ALTER TABLE `#__jblance_custom_field` ADD (`visible` VARCHAR(100) DEFAULT 'all' NOT NULL);
ALTER TABLE `#__jblance_custom_field` ADD (`field_code` VARCHAR(255) NOT NULL);
ALTER TABLE `#__jblance_custom_field` ADD (`params` TEXT NOT NULL);