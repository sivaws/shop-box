<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	controllers/guest.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.controller');

class JblanceControllerGuest extends JControllerLegacy {
	
	function __construct(){
		parent :: __construct();
	}
/**
 ==================================================================================================================
 SECTION : AJAX Requests
 1.checkUser - Check Username & Email (ajax)
 2.processFeed - Hide feed/activity
 3.searchSuggest - Search suggestion for University
 4.listempaddressdata - Load address data
 5.Dashboard Decide
 ==================================================================================================================
 */	
	//1.Check Username & Email (ajax)
	function checkUser(){
		$app  = JFactory::getApplication();
		$db 	  = JFactory::getDBO();
		$inputstr = $app->input->get('inputstr', '', 'string');
		$name 	  = $app->input->get('name', '', 'string');
	
		if($name == 'username'){
			$sql 	  = "SELECT COUNT(*) FROM #__users WHERE username='$inputstr'";
			$msg = 'COM_JBLANCE_USERNAME_EXISTS';
		}
		elseif($name == 'email'){
			$sql 	  = "SELECT COUNT(*) FROM #__users WHERE email='$inputstr'";
			$msg = 'COM_JBLANCE_EMAIL_EXISTS';
		}
	
		$db->setQuery($sql);
		if($db->loadResult()){
			echo JText::sprintf($msg, $inputstr);
		}
		else {
			echo 'OK';
		}
		exit;
	}
	
/**
 ==================================================================================================================
 SECTION : Registration & Login
 ==================================================================================================================
 */
	//1. grabUsergroupInfo
	function grabUsergroupInfo(){
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$ugid 	= $app->input->get('ugid', 0, 'int');
	
		$session = JFactory::getSession();
		$session->set('ugid', $ugid, 'register');
	
		$freeMode = JblanceHelper::isFreeMode($ugid);
	
		if($freeMode){
			// if the user is not registered, direct him to registration page else to profile page.
			if($user->id == 0)
				$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=register&step=3', false);
			else
				$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=usergroupfield', false);
	
			/* $app->redirect($return);
			return; */
		}
		else {
			// check for skipping of plan selection for this usergroup. If skipped, set the default plan for the usergroup
			$userHelper = JblanceHelper::get('helper.user');
			$ugroup = $userHelper->getUserGroupInfo(null, $ugid);
			
			if($ugroup->skipPlan){
				
				$query = "SELECT id FROM #__jblance_plan WHERE default_plan=1 AND ug_id=".$db->quote($ugid);
				$db->setQuery($query);
				$defaultPlanId = $db->loadResult();
				
				if(empty($defaultPlanId)){
					$app->enqueueMessage(JText::_('COM_JBLANCE_NO_DEFAULT_PLAN_FOR_THE_USERGROUP', 'error'));
					$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=showfront', false);
				}
				else {
					$session->set('planid', $defaultPlanId, 'register');
					$session->set('gateway', 'banktransfer', 'register');
					$session->set('skipPlan', 1, 'register');
					// if the user is not registered, direct him to registration page else to profile page.
					if($user->id == 0)
						$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=register&step=2', false);
					else 
						$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=usergroupfield&step=2', false);
				}
			}
			else {
				$return	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd&step=2', false);
			}
		}
		$app->redirect($return);
		return;
	}
	
	function grabPlanInfo(){
		$app 	= JFactory::getApplication();
		//$db		= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$post   = JRequest::get('post');
	
		$session = JFactory::getSession();
		$session->set('planid', $post['plan_id'], 'register');
		$session->set('gateway', $post['gateway'], 'register');
		$session->set('planChosen', $post, 'register');
	
		// if the user is not registered, direct him to registration page else to profile page.
		if($user->id == 0){
			$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=register&step=3', false);
		}
		else {
			$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=usergroupfield&step=3', false);
		}
		/* else {
			//skip the user group field layout in case the profile integration is not JoomBri
			$profileInteg = JblanceHelper::getProfile();
			if(!($profileInteg instanceof JoombriProfileJoombri)){
				//$url = $profileInteg->getEditURL();
				//if ($url) $app->redirect($url);
				//echo 'came here';exit;
				$return = JRoute::_('index.php?option=com_jblance&task=guest.saveusernew&'.JSession::getFormToken().'=1', false);
			}
			else {
				$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=usergroupfield', false);
			}
		} */
	
		$app->redirect($return);
		return;
	}
	
	function grabUserAccountInfo(){
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$post = JRequest::get('post');
		
		$session->set('userInfo', $post, 'register');
		
		//find step 3 or 4; step 4 = normal registration; step 3 = skip plan
		$skipPlan 	= $session->get('skipPlan', 0, 'register');
		$step = ($skipPlan) ? 'step=3' : 'step=4';
		
		$link = JRoute::_('index.php?option=com_jblance&view=guest&layout=usergroupfield&'.$step, false);
		$this->setRedirect($link);
		return false;
	}
	
	//1.Save new Employer
	function saveUserNew(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit('Invalid Token');
	
		$app = JFactory::getApplication();
		$now = JFactory::getDate();
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
	
		//get the user info from the session
		$session 	= JFactory::getSession();
		$userInfo 	= $session->get('userInfo', null, 'register');
		$ugid 		= $session->get('ugid', null, 'register');
		$gateway 	= $session->get('gateway', '', 'register');
		$skipPlan 	= $session->get('skipPlan', 0, 'register');
		//$session->clear('id', 'upgsubscr');
	
		$user = JFactory::getUser();
		$post = JRequest::get('POST');
		
		//get the Joombri user group information
		$usergroup	= JTable::getInstance('usergroup', 'Table');
		$usergroup->load($ugid);
		$jbrequireApproval = $usergroup->approval;
		$joomlaUserGroup = $usergroup->joomla_ug_id;
		$defaultUserGroup = explode(',', $joomlaUserGroup);
	
		//if the user is already registered and setting his profile to be JoomBri, then ignore the steps below.
		if($user->id == 0){
	
			// Get required system objects
			$usern = clone(JFactory::getUser());
	
			// If user registration is not allowed, show 403 not authorized.
			$usersConfig = JComponentHelper::getParams('com_users');
			if($usersConfig->get('allowUserRegistration') == '0'){
				JError::raiseError(403, JText::_('COM_JBLANCE_ACCESS_FORBIDDEN'));
				return;
			}
	
			// Bind the post array to the user object
			if(!$usern->bind($userInfo, 'usertype')){
				JError::raiseError(500, $usern->getError());
			}
	
			// Set some initial user values
			$usern->set('id', 0);
			$usern->set('usertype', 'deprecated');
			$usern->set('groups', $defaultUserGroup);
			$usern->set('registerDate', $now->toSql());
			
			$jAdminApproval = ($usersConfig->get('useractivation') == '2') ? 1 : 0;	//require Joomla Admin approval
			
			$requireApproval = $jbrequireApproval | $jAdminApproval;	//approval is required either JoomBri or Joomla require approval
			
			if($requireApproval)
				$usern->set('block', '1');
	
			// If user activation is turned on, we need to set the activation information
			$useractivation = $usersConfig->get('useractivation');
			if(($useractivation == 1 || $useractivation == 2) && !$requireApproval){
				jimport('joomla.user.helper');
				$usern->set('activation', JApplication::getHash(JUserHelper::genRandomPassword()));
				$usern->set('block', '1');
			}
	
			// If there was an error with registration, set the message and display form
			if(!$usern->save()){
				$msg = JText::_($usern->getError());
				$link = JRoute::_('index.php?option=com_jblance&view=guest&layout=register');
				$this->setRedirect($link, $msg);
				return false;
			}
			$userid = $usern->id;
		}
		else {
			$userid = $user->id;
			$user->set('groups', $defaultUserGroup);
			$user->save();
		}
	
		// Initialize variables
		$db		= JFactory::getDBO();
		$row	= JTable::getInstance('jbuser', 'Table');
		$row->user_id = $userid;
		$row->ug_id = $ugid;
		//$row->biz_name = isset($userInfo['biz_name']) ? $userInfo['biz_name'] : '';
		
		$id_category 	= $app->input->get('id_category', '', 'array');
		if(count($id_category) > 0 && !(count($id_category) == 1 && empty($id_category[0]))){
			$proj_categ = implode(',', $id_category);
		}
		elseif($id_category[0] == 0){
			$proj_categ = 0;
		}
		$post['id_category'] = $proj_categ;
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$fields = JblanceHelper::get('helper.fields');		// create an instance of the class fieldsHelper
		$fields->saveFieldValues('profile', $row->user_id, $post);
		
		//insert the user to notify table
		$obj = new stdClass();
		$obj->user_id = $userid;
		$db->insertObject('#__jblance_notify', $obj);
	
		// Send registration confirmation mail only to new registered user
		if($user->id == 0){
			$password = $userInfo['password2'];
			$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password); //Disallow control chars in the email
			
			$jbmail->sendRegistrationMail($usern, $password);
	
			if($requireApproval){
				$msg = JText::_('COM_JBLANCE_ACCOUNT_HAS_BEEN_CREATED_NEED_ADMIN_APPROVAL');
			}
			else {
				if($useractivation){
					$msg = JText::_('COM_JBLANCE_ACCOUNT_HAS_BEEN_CREATED_NEED_ACTIVATION');
				}
				else {
					$msg = JText::_('COM_JBLANCE_ACCOUNT_HAS_BEEN_CREATED_PLEASE_LOGIN');
				}
			}
		}
		else {
			$msg = JText::_('COM_JBLANCE_YOUR_PROFILE_HAS_BEEN_SUCCESSFULLY_CREATED');
		}
	
		$freeMode = JblanceHelper::isFreeMode($ugid);
		if(!$freeMode){
			include_once(JPATH_COMPONENT.'/controllers/membership.php');
			$subscrRow = JblanceControllerMembership::addSubscription($userid);	//add user to the subscription Table
			$subscrid = $subscrRow->id;	//this returnid is the subscr id from plan_subscr table
			
			$session->set('id', $subscrid, 'upgsubscr');

			if($gateway == 'banktransfer'){
				//send alert to admin and user
				$jbmail->alertAdminSubscr($subscrid, $userid);
				$jbmail->alertUserSubscr($subscrid, $userid);
			}
			// if plan selection is skipped, redirect him to the home page
			if($skipPlan || ($subscrRow->price == 0)){
				$link = JRoute::_('index.php?option=com_jblance&view=guest&layout=showfront', false);
			}
			else {
				$app->enqueueMessage(JText::_('COM_JBLANCE_PROCEED_PAYMENT_AFTER_REGISTRATION'));
				$link = JRoute::_('index.php?option=com_jblance&view=membership&layout=check_out&type=plan', false);
			}
		}
		else {
			$link = JRoute::_('index.php?option=com_jblance', false);
		}
	
		//clear the session variable of 'register'
		$session->clear('ugid', 'register');
		$session->clear('planid', 'register');
		$session->clear('gateway', 'register');
		$session->clear('userInfo', 'register');
		$session->clear('skipPlan', 'register');
	
		$this->setRedirect($link, $msg);
	}
	
	/* Misc Functions */
	
}