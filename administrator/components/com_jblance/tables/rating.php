<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 March 2012
 * @file name	:	tables/rating.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class TableRating extends JTable {
	
	var $id = null;
	var $actor = null;
	var $target = null;
	var $project_id = null;
	var $rate_date = null;
	var $comments = null;
	var $rate_type = null;
	var $quality_clarity = null;
	var $communicate = null;
	var $expertise_payment = null;
	var $professional = null;
	var $hire_work_again = null;
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct('#__jblance_rating', 'id', $db);
	}
}
?>