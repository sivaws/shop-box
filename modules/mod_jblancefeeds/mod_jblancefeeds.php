<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 August, 2012
 * @file name	:	modules/mod_jblancefeeds/mod_jblancefeeds.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).'/helper.php');
$total_row  = intval($params->get('total_row', 5));
$show_type  = $params->get('show_type', 'feed');
$show_unread  = $params->get('show_unread', 0);

if($show_type == 'feed'){
	$rows = ModJblanceFeedsHelper::getFeeds($total_row, $show_unread);
}
else if($show_type == 'message'){
	$rows = ModJblanceFeedsHelper::getMessages($total_row, $show_unread);
}

require(JModuleHelper::getLayoutPath('mod_jblancefeeds'));
?>