<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	tables/project.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
defined('_JEXEC') or die( 'Restricted access' );

class TableProject extends JTable {
	var $id = null;
	var $project_title = null;
	var $id_category = null;
	var $start_date = null;
	var $create_date = null;
	var $expires = null;
	var $assigned_userid = null;
	var $publisher_userid = null;
	var $status = null;
	var $budgetmin = null;
	var $budgetmax = null;
	var $description = null;
	var $is_featured = null;
	var $profit = null;
	var $paid_amt = null;
	var $paid_status = null;
	var $approved = null;
	var $is_urgent = null;
	var $is_private = null;
	var $is_sealed = null;
	var $metakey = null;
	var $metadesc = null;
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct( '#__jblance_project', 'id', $db);
	}
}
?>