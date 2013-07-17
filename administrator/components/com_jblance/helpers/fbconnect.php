<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	11 May 2013
 * @file name	:	helpers/fbconnect.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

 jbimport('facebook.facebook');

class FbconnectHelper {
	
	public $facebook = null;
	
	/**
	 * 	Fields to map from Facebook and the values are the default field codes in JoomBri.
	 **/
	public function __construct(){
		
		$config = JblanceHelper::getConfig();
		$app_id = $config->fbApikey;
		$app_sec = $config->fbAppsecret;
		
		$this->facebook = new Facebook(array(
				'appId'  => $app_id,
				'secret' => $app_sec
		));
	}
	
	function initFbLogin(){
		$task = JRequest::getVar('qs_act');
		$code = JRequest::getVar('code','');
		
		$logout_return_url	= base64_encode(JURI::root().trim('index.php'));
		$password = JUserHelper::genRandomPassword(6);

		$fbuser = $this->facebook->getUser();
		$jUser = JFactory::getUser();
	
		/* check if logout action */
		if($task == 'logout' && $fbuser && !empty($jUser->id)) {
			self::logout();
		}
	
		if($fbuser){
			try {
				$user_profile = $this->facebook->api('/me');
			} catch (FacebookApiException $e) {
				error_log($e);
				$fbuser = null;
			}
		}
		
		// If facebook user state is loged-in then register or login into Joomla.
		if($fbuser){
			if(!($jUser && !$jUser->guest)){
				// if not loged in into Joomla system
				$user_info = array_merge((array)$user_profile, array('logoutUrl' => $this->facebook->getLogoutUrl(), 'loginUrl' => ''));
				$logoutUrl = $this->facebook->getLogoutUrl(array('next' => base64_decode($logout_return_url)));
				
				/* $session = JFactory::getSession();
				$session->set('fb_user_profile', $user_profile, 'register'); */
				
				if(!empty($code)){
					$app = JFactory::getApplication();
					$user_fields = new stdClass();
					$user_fields->id = NULL;
					$user_fields->name = $user_info['name'];
					$user_fields->username = $user_info['username'];
					$user_fields->email = $user_info['email'];
					$user_fields->password = $password;//.substr($this->facebook->getAccessToken(),0,20);
	
					self::login($user_fields);
				}
				else {
					$this->facebook->destroySession();
					$user_info = array('logoutUrl' => '', 'loginUrl' => $this->facebook->getLoginUrl(array('scope' => 'email')));
				}
			}
			else {
				// if loged in into Joomla system
				$user_info = array(
						'name' => $jUser->name,
						'username' => $jUser->username,
						'email' => $jUser->email,
						'logoutUrl' => $this->facebook->getLogoutUrl(array('next' => base64_decode($logout_return_url))),
						'loginUrl' => ''
				);
			}
		}
		else {
			// If facebook user state is not loged-in then show facebook login url.
			$loginUrl = $this->facebook->getLoginUrl(array('scope' => 'email'));
			$user_info = array('logoutUrl' => '', 'loginUrl' => $this->facebook->getLoginUrl(array('scope' => 'email')));
		}
		return $user_info;
	}
	
	function login(&$data) {
		$app = JFactory::getApplication();
		$db	 = JFactory::getDbo();
	
		$passwd 		= $data->password;
		$rand_add 		= JUserHelper::genRandomPassword(32);
		$pass_crypt 	= JUserHelper::getCryptedPassword($passwd, $rand_add);
		$data->password = $pass_crypt.':'.$rand_add;
		$data->groups 	= array('2' => 2);
	
		//Check username in #_users for exists
		$userId = self::getIdUsers($data->email);
	
		// if the user already exist
		if($userId && $userId > 0){
			$jUser = JFactory::getUser($userId);
			$oldPassword = $jUser->password;
			$jUser->set('email', $data->email);
			$jUser->set('password', $data->password);
	
			if(!$jUser->save()) 
				throw new Exception(JText::sprintf('COM_JBLANCE_FACEBOOK_ERROR_OCCURED_WHILE_SAVING_THE_USER', $jUser->getError()));
		}
		else {
			$jUser = clone(JFactory::getUser());
			$oldPassword = $data->password;
			foreach($data as $key => $value){
				$jUser->set($key, $value);
			}
			if(!$jUser->save()){
				$return = JRoute::_('index.php');
				$msg 	= JText::sprintf('COM_JBLANCE_FACEBOOK_ERROR_OCCURED_WHILE_SAVING_THE_USER', $jUser->getError());
				$app->redirect($return, $msg, 'error');
			}
			
			//send email for newly registered user
			self::sendAccountDetailsEmail($jUser, $passwd);
		}
		
		
		$hasJoomBriProfile = JblanceHelper::hasJBProfile($userId);
		if($hasJoomBriProfile)
			$signin_message = JText::_('COM_JBLANCE_SIGNED_IN_USING_FACEBOOK_SUCCESSFULLY');	// message for existing user
		else 
			$signin_message = JText::_('COM_JBLANCE_ACCOUNT_CREATED_SELECT_ROLE_TO_PROCEED');	// message for new registered user
	
		//$return = base64_decode(JRequest::getVar('return'));
		$return = JRoute::_('index.php?option=com_jblance');
	
		/* prepare for perform login */
		$options = array();
		$options['remember'] = false;
		$options['return'] = $return;
	
		$credentials = array();
		$credentials['username'] = $jUser->username;
		$credentials['password'] = $passwd;
	
		/* preform the login action */
		$error = $app->login($credentials, $options);
		if(!JError::isError($error)){
			if(!$return){
				$return	= 'index.php';
			}
			$app->enqueueMessage($signin_message);
		}
		else {
			if(!$return){
				$return	= 'index.php';
			}
			JError::raiseNotice('1', JText::_('CANNOT REGISTER OR LOGIN INTO JOOMLA'));
		}
		
		// Since FB uses new password every time to login, save the old password and update it back to the #__users table
		$query = "UPDATE #__users SET password='".$oldPassword."' WHERE id='".$jUser->id."';";
		$db->setQuery($query);
		$db->query();
		
		$app->redirect($return);
	
	}
	
	/**
	 * Check if the Facebook email exist in #__users table
	 * 
	 * @param string $email
	 * @return integer Return the user id
	 */
	function getIdUsers($email){
		$db = JFactory::getDBO();
		$query = 'SELECT id FROM #__users WHERE email='.$db->quote($email);
		$db->setQuery($query, 0, 1);
		return $db->loadResult();
	}
	
	function sendAccountDetailsEmail($usern, $password){
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$jbmail->sendRegistrationMail($usern, $password, true);
	}
}