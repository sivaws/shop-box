<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	30 May 2012
 * @file name	:	models/message.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.component.model');
 
 class JblanceModelMessage extends JModelLegacy {
 	
 	function getInbox(){
		$db 	= JFactory::getDBO();
		$user = JFactory::getUser();
		
		$query = "SELECT * FROM #__jblance_message". 
				 " WHERE idTo=".$user->id." AND parent=0 AND deleted=0".
				 " ORDER BY date_sent DESC";
		$db->setQuery($query);
		$in_msgs = $db->loadObjectList();
		
		//count total received new messages
		$newInMsg = 0;
		foreach($in_msgs as $in_msg){
			$newInMsg += JblanceHelper::countUnreadMsg($in_msg->id);
		}
		
		$query = "SELECT * FROM #__jblance_message". 
				 " WHERE idFrom=".$user->id." AND parent=0 AND deleted=0".
				 " ORDER BY date_sent DESC";
		$db->setQuery($query);
		$out_msgs = $db->loadObjectList();
		
		//count total sent new messages
		$newOutMsg = 0;
		foreach($out_msgs as $out_msg){
			$newOutMsg += JblanceHelper::countUnreadMsg($out_msg->id);
		}
		
		$return[0] = $in_msgs;
		$return[1] = $out_msgs;
		$return[2] = $newInMsg;
		$return[3] = $newOutMsg;
		return $return;
	}
	
	function getMessageRead(){
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$id 	= $app->input->get('id', 0, 'int');
		$user	= JFactory::getUser();
		$app 	= JFactory::getApplication();
		
		$query = 'SELECT * FROM #__jblance_message WHERE id='.$id.' AND deleted=0';//echo $query;
		$db->setQuery($query);
		$parent = $db->loadObject();
		
		//check if the parent message is deleted.
		if(empty($parent)){
			$msg	= JText::_('COM_JBLANCE_THIS_MESSAGE_IS_DELETED');
			$link	= JRoute::_('index.php?option=com_jblance&view=message&layout=inbox');
			$app->redirect($link, $msg, 'error');
			return false;
		}
			
		$query = 'SELECT * FROM #__jblance_message WHERE id='.$id.' OR parent='.$id.' AND deleted=0 ORDER BY id';//echo $query;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		//update the status of the messages to be read
		$query = "UPDATE #__jblance_message SET is_read=1 WHERE idTo=$user->id AND (id=$id OR parent=$id)";
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError(500, $db->getError());
		}
		
		$return[0] = $parent;
		$return[1] = $rows;
		return $return;
		
	}
	
	function getSelectReportCategory(){
		$config = JblanceHelper::getConfig();
		$categories = $config->reportCategory;
		$values = explode(";", $categories);
		
		$put[] = JHTML::_('select.option', '', '- '.JText::_('COM_JBLANCE_PLEASE_SELECT').' -');
		foreach($values as $value){
			if($value){
				$put[] = JHTML::_('select.option', $value, JText::_($value), 'value', 'text');
			}
		}
		
		$lists 	= JHTML::_('select.genericlist', $put, 'category', "class='inputbox required' size='1'", 'value', 'text', '');
		return $lists;
	}
 	
 }