<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 August, 2012
 * @file name	:	modules/mod_jblancefeeds/helper.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */

 defined('_JEXEC') or die('Restricted access');
 JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
 include_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/jblance.php');	//include this helper file to make the class accessible in all other PHP files
 include_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/link.php');

class ModJblanceFeedsHelper {	
	public static function getFeeds($total_row, $show_unread) {
		if($show_unread)
			$rows = JblanceHelper::getFeeds($total_row, 'notify');
		else
			$rows = JblanceHelper::getFeeds($total_row);
		return $rows;
	}
	
	public static function getMessages($total_row, $show_unread) {
		$db	  = JFactory::getDBO();
		$user = JFactory::getUser();
		$isread = ' true ';
		
		if($show_unread)
			$isread = ' is_read=0 ';
		
		$query = "SELECT * FROM #__jblance_message ". 
				 //"WHERE (idTo=".$user->id." OR idFrom=".$user->id.") AND deleted=0 ".
				 "WHERE idTo=".$user->id." AND deleted=0 AND $isread".
				 "ORDER BY date_sent DESC ".
		 		 "LIMIT 0,".$total_row;//echo $query;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
}

?>