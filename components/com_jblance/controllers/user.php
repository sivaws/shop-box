<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	22 March 2012
 * @file name	:	controllers/user.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.controller');

class JblanceControllerUser extends JControllerLegacy {
	
	function __construct(){
		parent :: __construct();
	}
	
	//1.Save user Profile
	function saveProfile(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app  	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$db		= JFactory::getDBO();
		$id		= $app->input->get('id', 0, 'int');
		$post   = JRequest::get('POST');
		$jbuser	= JTable::getInstance('jbuser', 'Table');
		//$jbuser->load($id);
		
		$id_category 	= $app->input->get('id_category', '', 'array');
		if(count($id_category) > 0 && !(count($id_category) == 1 && empty($id_category[0]))){
			$proj_categ = implode(',', $id_category);
		}
		elseif($id_category[0] == 0){
			$proj_categ = 0;
		}
		$post['id_category'] = $proj_categ;
		
		if(!$jbuser->save($post)){
			JError::raiseError(500, $jbuser->getError());
		}
		
		//update the name
		$query = "UPDATE #__users SET name=".$db->quote($post['name'])." WHERE id=".$user->id;
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
	
		$fields = JblanceHelper::get('helper.fields');		// create an instance of the class fieldsHelper
		$fields->saveFieldValues('profile', $user->id, $post);
	
		//update the privacy post settings to the feed table.
		/*$query = "UPDATE #__jblance_feed f SET f.access=".$post['show_post']." WHERE f.actor=".$db->quote($user->id);
		$db->setQuery($query);
		$db->execute();*/
	
		/* //Trigger the plugin event to fedd the employer profile update to JoomBri Feed
		JPluginHelper::importPlugin('system');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onUserUpdateProfile', array($user->id)); */
	
		$link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
		$msg = JText::_('COM_JBLANCE_PROFILE_SAVED_SUCCESSFULLY');
		$this->setRedirect($link, $msg);
	}
	
	function savePortfolio(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		
		// Initialize variables
		$app  	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$row	= JTable::getInstance('portfolio', 'Table');
		$post   = $app->input->post->get('jform', array(), 'array');print_r($post);
		$id		= $app->input->get('id', 0, 'int');
		
		if($id > 0)
			$row->load($id);
		
		$post['id'] = $id;
		$post['user_id'] = $user->id;
		
		$id_category 	= $post['id_category'];
		if(count($id_category) > 0 && !(count($id_category) == 1 && empty($id_category[0]))){
			$proj_categ = implode(',', $id_category);
		}
		elseif($id_category[0] == 0){
			$proj_categ = 0;
		}
		$post['id_category'] = $proj_categ;
		
		//save the portfolio image file attachment `if` checked
		$chkAttach = $app->input->get('chk-portfoliopicture', 0, 'int');
		$attachedFile = $app->input->get('attached-file-portfoliopicture', '', 'string');
		
		if($chkAttach){
			$post['picture'] = $attachedFile;
			
			//delete if there is already attached
			$attFile = explode(';', $row->picture);
			$filename = $attFile[1];
			$delete = JBPORTFOLIO_PATH.'/'.$filename;
			if(JFile::exists($delete))
				unlink($delete);
		}
		else {
			$attFile = explode(';', $attachedFile);
			$filename = $attFile[1];
			$delete = JBPORTFOLIO_PATH.'/'.$filename;
			if(JFile::exists($delete))
				unlink($delete);
		}
		
		//save the portfolio file attachment `if` checked
		$chkAttach = $app->input->get('chk-portfolioattachment', 0, 'int');
		$attachedFile = $app->input->get('attached-file-portfolioattachment', '', 'string');
		
		if($chkAttach){
			$post['attachment'] = $attachedFile;
			
			//delete if there is already attached
			$attFile = explode(';', $row->attachment);
			$filename = $attFile[1];
			$delete = JBPORTFOLIO_PATH.'/'.$filename;
			if(JFile::exists($delete))
				unlink($delete);
		}
		else {
			$attFile = explode(';', $attachedFile);
			$filename = $attFile[1];
			$delete = JBPORTFOLIO_PATH.'/'.$filename;
			if(JFile::exists($delete))
				unlink($delete);
		}
		
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
		
		$msg	= JText::_('COM_JBLANCE_PORTFOLIO_SAVED_SUCCESSFULLY');
		$return	= JRoute::_('index.php?option=com_jblance&view=user&layout=editportfolio', false);
		$this->setRedirect($return, $msg);
	}
	
	//4.Delete Portfolio
	function deletePortfolio(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit('Invalid Token');
		
		//Initialise variables
		$app  	= JFactory::getApplication();
		$id 	= $app->input->get('id', 0, 'int');
		$row	= JTable::getInstance('portfolio', 'Table');
	
		$row->delete($id);
		
		$link	= JRoute::_('index.php?option=com_jblance&view=user&layout=editportfolio', false);
		$msg = JText::_('COM_JBLANCE_PORTFOLIO_DELETED_SUCCESSFULLY');
		$this->setRedirect($link, $msg);
	}
	
	//3.Upload Photo
	function uploadPicture(){
		JBMediaHelper::uploadPictureMedia();
	}
	
	function removePicture(){
		JBMediaHelper::removePictureMedia();
	}
	
	function cropPicture(){
		JBMediaHelper::cropPictureMedia();
	}
	
	function attachPortfolioFile(){
		JBMediaHelper::portfolioAttachFile();
	}
	
	//2.Hide/remove feeds
	function processFeed(){
		$app  		= JFactory::getApplication();
		$db 	  	= JFactory::getDBO();
		$userid 	= $app->input->get('userid', '', 'int');
		$activityid	= $app->input->get('activityid', '', 'int');
		$type 		= $app->input->get('type', '', 'string');
	
		if($type == 'remove')
			$query	= 'DELETE FROM #__jblance_feed WHERE id='.$activityid;
		elseif($type == 'hide')
			$query = "INSERT INTO #__jblance_feed_hide(`activity_id`,`user_id`) VALUES('$activityid', '$userid')";
		
		$db->setQuery($query);
		$db->execute();
		echo 'OK';
		exit;
	}
	
	//2.Set the feeds as read
	function setFeedRead(){
		$db 	  = JFactory::getDBO();
		$user = JFactory::getUser();
	
		$query = "UPDATE #__jblance_feed SET is_read=1 WHERE target=".$user->id;
		$db->setQuery($query);
		$db->execute();
		echo 'OK';
		exit;
	}
	
	function saveNotify(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		
		// Initialize variables
		$app  	= JFactory::getApplication();
		$user 	= JFactory::getUser();
		$row	= JTable::getInstance('notify', 'Table');
		$post   = JRequest::get('POST');
		
		$row->user_id = $user->id;
		
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
		
		$link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
		$msg = JText::_('COM_JBLANCE_EMAIL_NOTIFICATION_PREFERENCES_SAVED_SUCCESSFULLY');
		$this->setRedirect($link, $msg);
	}
	
	//download file
	function download(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit('Invalid Token');
		
		JBMediaHelper::downloadFile();
	}
	/* Misc Functions */
	
}