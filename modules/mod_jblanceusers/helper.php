<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 April 2012
 * @file name	:	modules/mod_jblanceusers/helper.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */

 // no direct access
 defined('_JEXEC') or die('Restricted access');
 JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
 include_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/jblance.php');	//include this helper file to make the class accessible in all other PHP files
 include_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/link.php');	//include this helper file to make the class accessible in all other PHP files

class ModJblanceUserHelper {	
	public static function getRecentUsers($total_row, $ug_id, $show_usertype) {
		$db	 = JFactory::getDBO();
		$having = $ratesubQuery = '';
		$where = array();
		
		if(!empty($ug_id))
			$where[] = 'ju.ug_id IN ('.$ug_id.')';
		
		$where[] = "u.block=0";
		
		$where = (count($where) ? ' WHERE (' . implode( ') AND (', $where ) . ')' : '');
		
		if($show_usertype == 1){
			$ratesubQuery = ",(SELECT AVG((quality_clarity+communicate+expertise_payment+professional+hire_work_again)/5) FROM #__jblance_rating  
				 			 WHERE target=ju.user_id AND quality_clarity <> 0) AS rating";
			$having = ' HAVING rating > 4 '; //only top-rated users having rating above 4
		}
		
		$query = "SELECT u.name,u.username,ju.* {$ratesubQuery}
				 FROM #__jblance_user ju ".
				 "INNER JOIN #__users u ON u.id=ju.user_id ".
				  $where." ".$having.
				 "ORDER BY ju.id DESC ".
				 "LIMIT 0,". $total_row;//echo $query;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		return $rows;
	}//end function	
}//end of class

?>