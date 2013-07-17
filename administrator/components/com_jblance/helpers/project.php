<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	helpers/select.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class ProjectHelper {
	function getProjectDetails($project_id){
		$db = JFactory::getDBO();
		$query = "SELECT p.* FROM #__jblance_project p".
				 " WHERE p.id=".$db->quote($project_id);
		$db->setQuery($query);
		$project = $db->loadObject();
		return $project;
	}
	
	function averageBidAmt($id){
		$db = JFactory::getDBO();
		$query = "SELECT AVG(amount) FROM #__jblance_bid WHERE project_id = $id";
		$db->setQuery($query);
		$avg = $db->loadResult();
		if($avg)
			return $avg;
		else
			return 0;
	}
	
	/**
	 * This function calculates the project commission fee for the given user and bid amount from amt & perc, whichever is higher
	 * 
	 * @param integer $user_id
	 * @param float $bid_amount
	 * @param string $user_type
	 * 
	 * @return float $project_fee calculated project commission
	 */
	function calculateProjectFee($user_id, $bid_amount, $user_type){
		
		$lastPlan = JblanceHelper::whichPlan($user_id);		//get the current active plan of the publisher/buyer
		
		if($user_type == 'freelancer'){
			$projFeeAmt = $lastPlan->flFeeAmtPerProject;
			$projFeePer = $lastPlan->flFeePercentPerProject;
		}
		elseif($user_type == 'buyer'){
			$projFeeAmt = $lastPlan->buyFeeAmtPerProject;
			$projFeePer = $lastPlan->buyFeePercentPerProject;
		}
		
		//calculate the project fee from freelancer, from amt & perc, whichever is higher
		$fee_per = round((($projFeePer /100) * $bid_amount), 2);
		if($fee_per >= $projFeeAmt)
			$project_fee = $fee_per;
		else
			$project_fee = $projFeeAmt;
		
		return $project_fee;
	}
	
	/**
	 * Check if the user has bid for the project.
	 * @param int $project_id
	 * @param int $user_id
	 * @return boolean
	 */
	function hasBid($project_id, $user_id){
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__jblance_bid b WHERE b.user_id=$user_id AND b.project_id=$project_id";
		$db->setQuery($query);
		$bid = $db->loadObject();
		if(count($bid))
			return 1;
		else
			return 0;
	}
}