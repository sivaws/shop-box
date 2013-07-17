<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	13 March 2012
 * @file name	:	helpers/user.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class UserHelper {
	
	protected static $_online = null;
	
	public function __construct($identifier = 0) {
		$this->_app = JFactory::getApplication ();
		$this->_session_timeout = time() - $this->_app->getCfg ( 'lifetime', 15 ) * 60;
	}
	
	public static function getUser($userid){
		$db = JFactory::getDBO();
		$query = "SELECT ju.*, u.name, u.username, u.email FROM #__jblance_user ju".
				 " LEFT JOIN #__users u ON u.id=ju.user_id". 
				 " WHERE ju.user_id=".$userid;
		$db->setQuery($query);
		$user = $db->loadObject();
		return $user;
	}
	
	public function getUserGroupInfo($userid = null, $groupid = null){
		$db = JFactory::getDBO();
		
		// get the group info by user id
		if(!empty($userid))
			$query = "SELECT ug.id,ug.name,ug.approval,ug.params FROM #__jblance_user u
					  LEFT JOIN #__jblance_usergroup ug ON u.ug_id = ug.id
					  WHERE u.user_id = ".$db->quote($userid)." AND ug.published=1";
		
		// get the group info by group id
		if(!empty($groupid))
			$query = "SELECT ug.id,ug.name,ug.approval,ug.params,ug.skipPlan FROM #__jblance_usergroup ug
					  WHERE ug.id = ".$db->quote($groupid)." AND ug.published=1";
		
		$db->setQuery($query);
		$userGroup = $db->loadObject();
		
		//convert the params to object
		$registry = new JRegistry;
		$registry->loadString($userGroup->params);
		/* if(version_compare(JVERSION, '1.6.0', 'ge')){
			$registry->loadString($userGroup->params);
		}
		else {
			$registry->loadINI($userGroup->params);
		} */
		$params = $registry->toObject();
		
		//bind the $params object to $plan and make one object
		foreach ($params as $k => $v) {
			$userGroup->$k = $v;
		}
		return $userGroup;
	}
	
	public function getPlanInfo($planId){
		$db = JFactory::getDBO();
		
		// get the group info by user id
		$query = "SELECT p.id, p.ug_id, p.params FROM #__jblance_plan p ".
				 "WHERE p.id = ".$db->quote($planId);
		$db->setQuery($query);
		$plan = $db->loadObject();
		
		//convert the params to object
		$registry = new JRegistry;
		$registry->loadString($plan->params);
		$params = $registry->toObject();
		
		//bind the $params object to $plan and make one object
		foreach($params as $k => $v){
			$plan->$k = $v;
		}
		return $plan;
	}
	
	 public function getBillingAddress($userid){
		
		//get the billing fields
 		$config = JblanceHelper::getConfig();
		$fields = JblanceHelper::get('helper.fields');	// create an instance of fieldsHelper class
		
		$obj				= new stdClass();
		$obj->invAddress	= $fields->getFieldValue($config->invAddress, $userid);
		$obj->invLocation	= $fields->getFieldValue($config->invLocation, $userid);
		$obj->invZip		= $fields->getFieldValue($config->invZip, $userid);
		$obj->invPhone		= $fields->getFieldValue($config->invPhone, $userid);
		//$obj->invOtherInfo1	= $fields->getFieldValue($config->invOtherInfo1, $userid);
		//$obj->invOtherInfo2	= $fields->getFieldValue($config->invOtherInfo2, $userid);
	
		return $obj;
	}
	
	 public function getSearchUserLayout($userid){
		
		//get the billing fields
 		$config = JblanceHelper::getConfig();
		$fields = JblanceHelper::get('helper.fields');	// create an instance of fieldsHelper class
		
		$obj			  = new stdClass();
		$obj->position	  = $fields->getFieldValue($config->searchResPosition, $userid);
		$obj->degreeLevel = $fields->getFieldValue($config->searchResDegLevel, $userid);
	
		return $obj;
	}
	
	public function isOnline($userid){
		$online = false;
		$onlineList = self::getOnlineUsers();
		
		if(self::$_online === null){
			self::getOnlineUsers();
		}
		$online = isset($onlineList [$userid]) ? ($onlineList [$userid]->time > $this->_session_timeout) : false;
		
		return $online ? true : false;
	}
	
	public static function getOnlineUsers(){
		if(self::$_online === null) {
			$db = JFactory::getDBO();
			$query = "SELECT s.userid, s.time
					  FROM #__session AS s
					  INNER JOIN #__jblance_user AS u ON u.user_id=s.userid
					  WHERE s.client_id=0 AND s.userid>0
					  GROUP BY s.userid
					  ORDER BY s.time DESC";
			$db->setQuery($query);
			self::$_online = $db->loadObjectList('userid');
		}
		return self::$_online;
	}
		
}

?>