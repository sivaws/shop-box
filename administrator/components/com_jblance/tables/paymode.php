<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 March 2012
 * @file name	:	tables/paymode.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
	
class TablePaymode extends JTable {
	var $id = null;
	var $gateway_name = null;
	var $published = null;
	var $ordering = null;
	var $shortcode = null;
	var $gwcode = null;
	var $withdraw = null;
	var $withdrawFee = null;
	var $withdrawDesc = null;
	var $depositfeeFixed = null;
	var $depositfeePerc = null;
	var $params = null;
		
	function __construct(&$db){
		parent::__construct('#__jblance_paymode', 'id', $db);
	}
}
?>