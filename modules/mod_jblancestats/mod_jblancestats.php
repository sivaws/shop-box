<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	25 June 2012
 * @file name	:	modules/mod_jblancestats/mod_jblancestats.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).'/helper.php');
$total_projects 	= ModJblanceStatsHelper::getTotalProjects();
$active_projects 	= ModJblanceStatsHelper::getActiveProjects();
$total_users 		= ModJblanceStatsHelper::getTotalUsers();
require(JModuleHelper::getLayoutPath('mod_jblancestats'));
?>
