<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	04 April 2012
 * @file name	:	tables/withdraw.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
	
class TableWithdraw extends JTable {
	
	var $id = null;
	var $invoiceNo = null;
	var $user_id = null;
	var $gateway = null;
	var $amount = null;
	var $withdrawFee = null;
	var $finalAmount = null;
	var $approved = null;
	var $date_approval = null;
	var $date_withdraw = null;
	var $params = null;
	var $trans_id = null;
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct('#__jblance_withdraw', 'id', $db);
	}
}
?>