<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	29 March 2012
 * @file name	:	modules/mod_jblancelatest/mod_jblancelatest.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).'/helper.php');
$total_row  	= intval($params->get('total_row', 5));
$limit_title    = intval($params->get('limit_project_title', 50));
$show_categ 	= intval($params->get('show_categ', 0));
$show_bid		= intval($params->get('show_bid', 0));
$show_avgbid	= intval($params->get('show_avgbid', 0));
$show_startdate	= intval($params->get('show_startdate', 0));
$show_enddate	= intval($params->get('show_enddate', 0));
$show_budget	= intval($params->get('show_budget', 0));
$show_publisher	= intval($params->get('show_publisher', 0));
$rows 			= ModJblanceLatestHelper::getLatestProjects($total_row, $limit_title);
require(JModuleHelper::getLayoutPath('mod_jblancelatest'));
?>