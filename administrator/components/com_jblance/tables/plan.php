<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 March 2012
 * @file name	:	tables/plan.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
	
class TablePlan extends JTable {
	var $id = null;
	var $name = null;
	var $days = null;
	var $days_type = null;
	var $price = null;
	var $discount = null;
	var $published = null;
	var $invisible = null;
	var $ordering = null;
	var $time_limit = null;
	var $alert_admin = null;
	var $adwords = null;
	var $bonusFund = null;
	var $description = null;
	var $finish_msg = null;
	var $params = null;
	var $ug_id = null;
	var $default_plan = null;
	var $user_featured = null;
	var $lifetime = null;
			
	function __construct(&$db){
		parent::__construct('#__jblance_plan', 'id', $db);
	}
}
?>