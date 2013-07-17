<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	04 March 2013
 * @file name	:	modules/mod_jblancebalance/mod_jblancebalance.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).'/helper.php');
$total_fund 	= ModJblanceBalanceHelper::getTotalFund();
require(JModuleHelper::getLayoutPath('mod_jblancebalance'));
?>
