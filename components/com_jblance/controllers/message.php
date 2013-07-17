<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	30 May 2012
 * @file name	:	controllers/message.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.controller');

class JblanceControllerMessage extends JControllerLegacy {
	
	function __construct(){
		parent :: __construct();
	}
	
	function sendMessage(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		
		$app  	= JFactory::getApplication();
		$now 	= JFactory::getDate();
		$user 	= JFactory::getUser();
		$post   = JRequest::get('post');
		$message = JTable::getInstance('message', 'Table');
		$project_id = $app->input->get('project_id', 0, 'int');
		$parentid = $app->input->get('parent', 0, 'int');
		
		$message->date_sent = $now->toSql();
		
		//save the file attachment `if` checked
		$chkAttach = $app->input->get('chk-uploadmessage', 0, 'int');
		$attachedFile = $app->input->get('attached-file-uploadmessage', '', 'string');
		
		if($chkAttach){
			$message->attachment = $attachedFile;
		}
		else {
			$attFile = explode(';', $attachedFile);
			$filename = $attFile[1];
			$delete = JBMESSAGE_PATH.'/'.$filename;
			if(JFile::exists($delete))
				unlink($delete);
		}
		
		if(!$message->save($post)){
			JError::raiseError(500, $message->getError());
		}
		
		//send PM notification email
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$jbmail->sendMessageNotification($post);
		
		$msg	= JText::_('COM_JBLANCE_MESSAGE_SENT_SUCCESSFULLY');
		$return	= JRoute::_('index.php?option=com_jblance&view=message&layout=read&id='.$parentid, false);
		$this->setRedirect($return, $msg);
	}
	
	function sendCompose(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		
		$app  = JFactory::getApplication();
		$now = JFactory::getDate();
		$user 	= JFactory::getUser();
		$post   = JRequest::get('post');
		$message = JTable::getInstance('message', 'Table');
		$recipient = $app->input->get('recipient', '', 'string');
		$recipientInfo = JFactory::getUser($recipient);		//get the recipient info from the recipient's username
		
		//check if the recipient info is valid/username exists
		if(empty($recipientInfo)){
			$msg = JText::_('COM_JBLANCE_INVALID_USERNAME');
			$link	= JRoute::_('index.php?option=com_jblance&view=message&layout=compose', false);
			$this->setRedirect($link, $msg, 'error');
			return false;
		}
		
		$message->date_sent = $now->toSql();
		$message->idFrom = $user->id;
		$message->idTo = $recipientInfo->id;
		
		//save the file attachment `if` checked
		$chkAttach = $app->input->get('chk-uploadmessage', 0, 'int');
		$attachedFile = $app->input->get('attached-file-uploadmessage', '', 'string');
		
		if($chkAttach){
			$message->attachment = $attachedFile;
		}
		else {
			$attFile = explode(';', $attachedFile);
			$filename = $attFile[1];
			$delete = JBMESSAGE_PATH.'/'.$filename;
			if(JFile::exists($delete))
				unlink($delete);
		}
		
		if(!$message->save($post)){
			JError::raiseError(500, $message->getError());
		}
		
		//send PM notification email
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$post['idFrom'] = $user->id;
		$post['idTo'] = $recipientInfo->id;
		$jbmail->sendMessageNotification($post);
		
		$msg	= JText::_('COM_JBLANCE_MESSAGE_SENT_SUCCESSFULLY');
		$return	= JRoute::_('index.php?option=com_jblance&view=message&layout=inbox', false);
		$this->setRedirect($return, $msg);
	}
	
	function saveReport(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		
		$app  	= JFactory::getApplication();
		$link 	= $app->input->get('link', '', 'string');
		
		$reportHelper = JblanceHelper::get('helper.report');		// create an instance of the class ReportHelper
		$result = $reportHelper->createReport();
		
		$msg = (!$result) ? JText::_('COM_JBLANCE_ALREADY_REPORTED') : JText::_('COM_JBLANCE_REPORTED_SUCCESSFULLY');
		//echo $msg;exit;
		$return	= base64_decode($link);
		$this->setRedirect($return, $msg);
	}
	
	
	//AJAX functions
	//2.Hide/remove Message
	function processMessage(){
		JblanceHelper::processMessage();
	}
	
	function getAutocompleteUsername() {
		$app = JFactory::getApplication();
		$db  = JFactory::getDBO();
		
		$search = $app->input->get('recipient', '', 'string');
		$result = array();
		
		// Some simple validation
		if (is_string($search) && strlen($search) > 2 && strlen($search) < 64){
			$query = "SELECT u.username FROM #__users u
					  WHERE u.username LIKE '%$search%' OR u.name LIKE '%$search%'";
			$db->setQuery($query);
			$rows = $db->loadObjectList();
		
			for($i = 0; $i < count($rows); $i++){
				$row = $rows[$i];
				$result[] = $row->username;
			}
		}
		
		// Finally the JSON, including the correct content-type
		header('Content-type: application/json');
		
		echo json_encode($result);
		exit;
		
	}
	
	function attachFile(){
		JBMediaHelper::messageAttachFile();
	}
	
	//download file
	function download(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit('Invalid Token');
	
		JBMediaHelper::downloadFile();
	}
}