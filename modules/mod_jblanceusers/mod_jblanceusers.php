<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 April 2012
 * @file name	:	modules/mod_jblanceusers/mod_jblanceusers.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once(dirname(__FILE__).'/helper.php');

$total_row  	= intval($params->get('total_row', 5));
$ug_id	  		= $params->get('ug_id', '');
$show_usertype  = $params->get('show_usertype', 0);

$rows 			= ModJblanceUserHelper::getRecentUsers($total_row, $ug_id, $show_usertype);
require(JModuleHelper::getLayoutPath('mod_jblanceusers'));
?>