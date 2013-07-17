<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	04 March 2013
 * @file name	:	modules/mod_jblancebalance/helper.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 // no direct access
 defined('_JEXEC') or die('Restricted access');
 JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
 include_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/jblance.php');	//include this helper file to make the class accessible in all other PHP files

class ModJblanceBalanceHelper {	
	public static function getTotalFund(){
		$user = JFactory::getUser();
		$total_fund = JblanceHelper::getTotalFund($user->id);

		return $total_fund;
	}
}
?>