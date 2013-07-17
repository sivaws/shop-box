<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	13 March 2012
 * @file name	:	tables/jbuser.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
	
class TableJbuser extends JTable {
	var $id = null;
	var $user_id = null;
	var $biz_name = null;
	var $status = null;
	var $status_access = null;
	var $posted_on = null;
	var $picture = null;
	var $thumb = null;
	var $invite = null;
	var $params = null;
	var $latitude = null;
	var $longitude = null;
	var $ug_id = null;
	var $search_email = null;
	var $notify = null;
	var $suspend = null;
	var $suspend_reason = null;
	var $featured = null;
	var $rate = null;
	var $id_category = null;
	var $featured_expire = null;
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct('#__jblance_user', 'id', $db);
	}
}
?>