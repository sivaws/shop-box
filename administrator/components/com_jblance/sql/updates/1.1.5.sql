ALTER TABLE `#__jblance_plan_subscr` ADD (`bids_allowed` INT DEFAULT '0' NOT NULL);
ALTER TABLE `#__jblance_plan_subscr` ADD (`bids_left` INT DEFAULT '0' NOT NULL);
ALTER TABLE `#__jblance_plan_subscr` ADD (`projects_allowed` INT DEFAULT '0' NOT NULL);
ALTER TABLE `#__jblance_plan_subscr` ADD (`projects_left` INT DEFAULT '0' NOT NULL);