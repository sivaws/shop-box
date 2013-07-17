<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	18 February 2013
 * @file name	:	helpers/finance.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class FinanceHelper {
	
	//get the last plan's subscr details
	public static function getLastSubscription($userid = null){
		$db  = JFactory::getDBO();
	
		$query = "SELECT MAX(id) FROM #__jblance_plan_subscr WHERE approved=1 AND user_id=".$db->quote($userid);
		$db->setQuery($query);
		$id_max = $db->loadResult();
	
		$query = "SELECT * FROM #__jblance_plan_subscr WHERE id=".$db->quote($id_max);
		$db->setQuery($query);
		$last_subscr = $db->loadObject();
	
		return $last_subscr;
	}
	
	//update Project Left column
	function updateProjectLeft($userid){
		$db  = JFactory::getDBO();
		
		$last_subscr = $this->getLastSubscription($userid);
		if($last_subscr->projects_allowed > 0){
			$query = "UPDATE #__jblance_plan_subscr SET projects_left=projects_left-1 WHERE id=".$db->quote($last_subscr->id);
			$db->setQuery($query);
			if(!$db->execute())
				throw new Exception();
		}
	}
	
	//update bids Left column
	function updateBidsLeft($userid){
		$db  = JFactory::getDBO();
		
		$last_subscr = $this->getLastSubscription($userid);
		if($last_subscr->bids_allowed > 0){
			$query = "UPDATE #__jblance_plan_subscr SET bids_left=bids_left-1 WHERE id=".$db->quote($last_subscr->id);
			$db->setQuery($query);
			if(!$db->execute())
				throw new Exception();
		}
	}
}