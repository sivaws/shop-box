<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 March 2012
 * @file name	:	tables/bid.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class TableBid extends JTable {
	var $id = null;
	var $user_id = null;
	var $project_id = null;
	var $amount = null;
	var $delivery = null;
	var $bid_date = null;
	var $details = null;
	var $outbid = null;
	var $status = null;
	var $milestone_perc = null;
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct('#__jblance_bid', 'id', $db);
	}
}
?>