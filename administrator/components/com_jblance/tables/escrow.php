<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	03 April 2012
 * @file name	:	tables/escrow.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class TableEscrow extends JTable {
	var $id = null;
	var $from_id = null;
	var $to_id = null;
	var $amount = null;
	var $project_id = null;
	var $date_transfer = null;
	var $date_accept = null;
	var $note = null;
	var $status = null;
	var $date_release = null;
	var $from_trans_id = null;
	var $to_trans_id = null;
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct('#__jblance_escrow', 'id', $db);
	}
}
?>