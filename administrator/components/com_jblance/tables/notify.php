<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	11 September 2012
 * @file name	:	tables/notify.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
defined('_JEXEC') or die( 'Restricted access' );

class TableNotify extends JTable {
	var $id = null;
	var $user_id = null;
	var $frequency = null;
	var $notifyNewProject = null;
	var $notifyBidWon = null;
	var $notifyNewMessage = null;
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct( '#__jblance_notify', 'id', $db);
	}
}
?>