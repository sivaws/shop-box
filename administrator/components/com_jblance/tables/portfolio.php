<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 November 2012
 * @file name	:	tables/portfolio.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class TablePortfolio extends JTable {
	var $id = null;
	var $title = null;
	var $id_category = null;
	var $user_id = null;
	var $link = null;
	var $description = null;
	var $start_date = null;
	var $finish_date = null;
	var $picture = null;
	var $attachment = null;
	var $published = null;
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct( '#__jblance_portfolio', 'id', $db);
	}
}
?>