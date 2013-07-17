<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	19 March 2012
 * @file name	:	tables/transaction.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
	
class TableTransaction extends JTable {
	
	var $id = null;
	var $date_trans = null;
	var $transaction = null;
	var $fund_plus = null;
	var $fund_minus = null;
	var $user_id = null;
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct('#__jblance_transaction', 'id', $db);
	}
}
?>