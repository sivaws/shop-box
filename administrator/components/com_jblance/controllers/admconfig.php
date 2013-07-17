<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	14 March 2012
 * @file name	:	controllers/admconfig.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');
include_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/jblance.php');	//include this helper file to make the class accessible in all other PHP files
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');	//include the tables path in order to use JTable in this controller file

/**
 * Showuser list controller class.
 */
class JblanceControllerAdmconfig extends JControllerAdmin {

	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct(){
		parent::__construct();
	
		// Register Extra tasks
		//$this->registerTask('add', 'edit');
		//following extra tasks has bee registered because they point to the default core functions instead of our own function , kind of override ;)
		$this->registerTask('orderup', 'orderup');
		$this->registerTask('orderdown', 'orderdown');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
	}
	
	public function publish(){
		$app  = JFactory::getApplication();
		$ctype = $app->input->get('ctype', '', 'string');
		$msg = JText::_('COM_JBLANCE_PUBLISHED_SUCCESSFULLY');
		if($ctype == 'usergroup'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
			$this->jbPubUnpub(1, '#__jblance_usergroup', $link, $msg);
		}
		elseif($ctype == 'plan'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
			$this->jbPubUnpub(1, '#__jblance_plan', $link, $msg);
		}
		elseif($ctype == 'paymode'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
			$this->jbPubUnpub(1, '#__jblance_paymode', $link, $msg);
		}
		elseif($ctype == 'customfield'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
			$this->jbPubUnpub(1, '#__jblance_custom_field', $link, $msg);
		}
		elseif($ctype == 'category'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
			$this->jbPubUnpub(1, '#__jblance_category', $link, $msg);
		}
		elseif($ctype == 'budget'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
			$this->jbPubUnpub(1, '#__jblance_budget', $link, $msg);
		}
	}
	
	public function unpublish(){
		$app  = JFactory::getApplication();
		$ctype = $app->input->get('ctype', '', 'string');
		$msg = JText::_('COM_JBLANCE_UNPUBLISHED_SUCCESSFULLY');
		if($ctype == 'usergroup'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
			$this->jbPubUnpub(0, '#__jblance_usergroup', $link, $msg);
		}
		elseif($ctype == 'plan'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
			$this->jbPubUnpub(0, '#__jblance_plan', $link, $msg);
		}
		elseif($ctype == 'paymode'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
			$this->jbPubUnpub(0, '#__jblance_paymode', $link, $msg);
		}
		elseif($ctype == 'customfield'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
			$this->jbPubUnpub(0, '#__jblance_custom_field', $link, $msg);
		}
		elseif($ctype == 'category'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
			$this->jbPubUnpub(0, '#__jblance_category', $link, $msg);
		}
		elseif($ctype == 'budget'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
			$this->jbPubUnpub(0, '#__jblance_budget', $link, $msg);
		}
	}
	

	public function orderup(){
		$app 		= JFactory::getApplication();
		$ctype 		= $app->input->get('ctype', '', 'string');
		$fieldfor 	= $app->input->get('fieldfor', 0, 'int');
		$cid 		= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
	
		if($ctype == 'usergroup'){
			$row = JTable::getInstance('usergroup', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
		}
		elseif($ctype == 'plan'){
			$row = JTable::getInstance('plan', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
		}
		elseif($ctype == 'paymode'){
			$row = JTable::getInstance('paymode', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
		}
		elseif( $ctype == 'customfield'){
			$row = JTable::getInstance('custom', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
		}
		elseif($ctype == 'category'){
			$row = JTable::getInstance('category', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
		}
		elseif($ctype == 'budget'){
			$row = JTable::getInstance('budget', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
		}
		
		$row->load($cid[0]);
		if($ctype == 'fieldorder' || $ctype == 'customfield'){
			$row->move(-1, 'field_for='.$fieldfor.' AND parent = '.(int) $row->parent);
		}
		elseif($ctype == 'category'){
			$row->move(-1, 'parent = '.(int) $row->parent);
		}
		else {
			$row->move(-1);
		}
		$app->redirect($link);
	}
	
	public function orderdown(){
		$app 		= JFactory::getApplication();
		$ctype 		= $app->input->get('ctype', '', 'string');
		$fieldfor	= $app->input->get('fieldfor', 0, 'int');
		$cid 		= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
		
		if($ctype == 'usergroup'){
			$row = JTable::getInstance('usergroup', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
		}
		elseif( $ctype == 'plan'){
			$row = JTable::getInstance('plan', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
		}
		elseif( $ctype == 'paymode'){
			$row = JTable::getInstance('paymode', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
		}
		elseif( $ctype == 'customfield'){
			$row = JTable::getInstance('custom', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
		}
		elseif($ctype == 'category'){
			$row = JTable::getInstance('category', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
		}
		elseif($ctype == 'budget'){
			$row = JTable::getInstance('budget', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
		}
		
		$row->load($cid[0]);
		if($ctype == 'fieldorder' || $ctype == 'customfield'){
			$row->move( 1, 'field_for='.$fieldfor.' AND parent = '.(int)$row->parent);
		}
		elseif($ctype == 'category'){
			$row->move(1, 'parent = '.(int)$row->parent);
		}
		else {
			$row->move(1);
		}
		$app->redirect($link);
	}
	
	public function saveorder(){
		$app 		= JFactory::getApplication();
		$ctype 		= $app->input->get('ctype', '', 'string');
		$fieldfor	= $app->input->get('fieldfor', 0, 'int');
		$cid 		= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
	
		if($ctype == 'usergroup'){
			$row = JTable::getInstance('usergroup', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
		}
		elseif( $ctype == 'plan'){
			$row = JTable::getInstance('plan', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
		}
		elseif( $ctype == 'paymode'){
			$row = JTable::getInstance('paymode', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
		}
		elseif( $ctype == 'customfield'){
			$row = JTable::getInstance('custom', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
		}
		elseif($ctype == 'category'){
			$row = JTable::getInstance('category', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
		}
		elseif($ctype == 'budget'){
			$row = JTable::getInstance('budget', 'Table');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
		}
	
		$total		= count($cid);
		$groupings	= array();
		$order		= $app->input->get('order', array(0), 'array');
		JArrayHelper::toInteger($order, array(0));
	
		// update ordering values
		for( $i=0; $i < $total; $i++ ){
			$row->load((int)$cid[$i]);
			// track parents
			$groupings[] = $row->parent;
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError(500, $db->getErrorMsg());
				}
			}
		}
	
		if($ctype == 'category'){
			// execute updateOrder for each parent group
			$groupings = array_unique($groupings);
			foreach ($groupings as $group){
				$row->reorder('parent = '.(int)$group.' AND published >=0');
			}
		}
		elseif($ctype == 'fieldorder' || $ctype == 'customfield'){
			// execute updateOrder for each parent group
			$groupings = array_unique($groupings);
			foreach ($groupings as $group){
				$row->reorder('field_for='.$fieldfor.' AND parent = '.(int)$group.' AND published >=0');
			}
		}
		else {
			$row->reorder();
		}
	
		$msg = JText::_('COM_JBLANCE_NEW_ORDERING_SAVED');
		$app->redirect( $link, $msg );
	}
	
	public function required(){
		$app  = JFactory::getApplication();
		$ctype = $app->input->get('ctype', '', 'string');
		$msg = JText::_('COM_JBLANCE_FIELD_SET_REQUIRED');
		if( $ctype == 'fieldorder' ){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=fieldorder';
			$this->jbReqUnrequired(1, '#__jblance_fieldorder', $link, $msg);
		}
		elseif($ctype == 'customfield'){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
			$this->jbReqUnrequired(1, '#__jblance_custom_field', $link, $msg);
		}
	}
	
	public function unrequired(){
		$app  = JFactory::getApplication();
		$ctype = $app->input->get('ctype', '', 'string');
		$msg = JText::_('COM_JBLANCE_FIELD_SET_UNREQUIRED');
		if( $ctype == 'fieldorder' ){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=fieldorder';
			$this->jbReqUnrequired(0, '#__jblance_fieldorder', $link, $msg);
		}
		if( $ctype == 'customfield' ){
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
			$this->jbReqUnrequired(0, '#__jblance_custom_field', $link, $msg);
		}
	}
	
	/**
	 ================================================================================================================
	 SECTION : Configuration:Config - save, cancel
	 ================================================================================================================
	 */
	function saveConfig(){
	
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app = JFactory::getApplication();
		$db		= JFactory::getDBO();
		$row	= JTable::getInstance('config', 'Table');
		$post   = JRequest::get('post');
		$params	= $app->input->get('params', null, 'array');
	
		// Build parameter string
		$registry = new JRegistry();
		$registry->loadArray($params);
		$row->params = $registry->toString();
		unset($post['params']);
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$msg	= JText::_('COM_JBLANCE_COMPONENT_SETTINGS_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=config';
		$app->redirect($link, $msg);
	}
	
	function cancelConfig(){
		$app = JFactory::getApplication();
		$msg ='';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=configpanel';
		$app->redirect($link, $msg);
	}	
	
	/**
	 ================================================================================================================
	 SECTION : Configuration: User Group - new, remove, save, cancel
	 ================================================================================================================
	 */
	function newUserGroup(){
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editusergroup');
		$this->display();
	}
	
	function removeUserGroup(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$row	= JTable::getInstance('usergroup', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
		$delCount = 0;
	
		if(count($cid)){
			$count_ketemu = 0;
			for($i=0; $i<count($cid); $i++){
				$curr_bid = $cid[$i];
	
				$query ="SELECT COUNT(*) FROM #__jblance_plan WHERE ug_id=$curr_bid";
				$db->setQuery($query);
				$find_1 = $db->loadResult();
	
				$query ="SELECT COUNT(*) FROM #__jblance_user WHERE ug_id=$curr_bid";
				$db->setQuery($query);
				$find_2 = $db->loadResult();
	
				if($find_1 > 0 || $find_2 > 0){
					$ketemu = 1;
				}
				if($find_1 == 0 && $find_2 == 0){
					$row->delete($curr_bid);
					$delCount++;
				}
				if($ketemu > 0){
					$count_ketemu++;
				}
			}
			if($count_ketemu > 0){
				$app->enqueueMessage(JText::sprintf('COM_JBLANCE_CANNOT_DELETE_DATA_DUE_TO_TABLE_LINKING', JText::_('COM_JBLANCE_USER_GROUP')), 'error');
			}
		}
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_USER_GROUP_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
		$app->redirect($link, $msg);
	}
	
	function saveUserGroup(){
	
		// Check for request forgeries
		JSession::checkToken() or jexit( 'Invalid Token' );
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$row	= JTable::getInstance('usergroup', 'Table');
		$post   = JRequest::get('post');
		$id		= $app->input->get('id' , 0 , 'int');
		$fields	= $app->input->get('fields', '', 'array');
		$tmpParents	= $app->input->get('parents', '', 'array');
		$isNew	= ($id == 0) ? true : false;
	
		$post['description']  = JRequest::getVar('description', '', 'POST', 'string', JREQUEST_ALLOWRAW);
	
		//set the Joomla user group
		$joomla_ug_id 	= $app->input->get('joomla_ug_id', '', 'array');
		if(count($joomla_ug_id) > 0 && !(count($joomla_ug_id) == 1 && empty($joomla_ug_id[0]))){
			$ugroup_id = implode(',', $joomla_ug_id);
		}
		elseif($joomla_ug_id[0] == 0){
			$ugroup_id = 2;	//default is registered
		}
		
		$post['joomla_ug_id'] = $ugroup_id;
		
		$params	= $app->input->get('params', null, 'array');
	
		// Build parameter string
		$registry = new JRegistry();
		$registry->loadArray($params);
		$row->params = $registry->toString();
		unset($post['params']);
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		// Since it would be very tedious to check if previous fields were enabled or disabled.
		// We delete all existing mapping and remap it again to ensure data integrity.
		if(!$isNew && !empty($fields)){
			$row->deleteChilds();
		}
	
		if(!empty($fields)){
			$parents = array();
	
			// We need to unique the parents first.
			foreach($fields as $id){
				$customFields	= JTable::getInstance('custom', 'Table');
				$customFields->load($id);
	
				// Need to only
				$parent	= $customFields->getCurrentParentId();
	
				if(in_array($parent, $tmpParents)){
					$parents[]	= $parent;
				}
			}
			$parents	= array_unique($parents);
	
			$fields		= array_merge($fields, $parents);
	
			foreach($fields as $id){
				$field				= JTable::getInstance('UsergroupField' , 'Table');
				$field->parent		= $row->id;
				$field->field_id	= $id;
	
				$field->store();
			}
		}
		
		// Enque message to warn that the newly created user group should have default plan
		if($isNew){
			$link_plan = 'index.php?option=com_jblance&view=admconfig&layout=showplan';
			$app->enqueueMessage(JText::sprintf('COM_JBLANCE_WARNING_TO_CREATE_DEFAULT_PLAN_FOR_USERGROUP', $link_plan), 'warning');
		}
	
		$msg	= JText::_('COM_JBLANCE_USER_GROUP_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
		$app->redirect($link, $msg);
	}
	
	function cancelUserGroup(){
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showusergroup';
		$app->redirect($link, $msg);
	}
	
	/**
	 ================================================================================================================
	 SECTION : Configuration:Plan - new, remove, save, cancel, show, setplandefault
	 ================================================================================================================
	 */
	function newPlan(){
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editplan');
		$this->display();
	}
	
	function removePlan(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$row	= JTable::getInstance('plan', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
		$ketemu = 0;
		$find_1 = $find_2 = 0;
		$delCount = 0;
	
		if(count($cid)){
			$count_ketemu = 0;
			for($i=0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
	
				$query = "SELECT COUNT(*) FROM  #__jblance_plan_subscr ".
						 "WHERE plan_id = $curr_bid";
				$db->setQuery($query);
				$find_1 = $db->loadResult();
	
				$row->load($curr_bid);
				/* if($row->default_plan){
					$find_2 = 1;		//default plan cannot be deleted.
					$app->enqueueMessage(JText::sprintf('COM_JBLANCE_PLAN_DEFAULT_CANNOT_BE_DELETED', $row->id), 'error');
				} */
	
				if($find_1 > 0 || $find_2 > 0){
					$ketemu = 1;
				}
				if($find_1 == 0 && $find_2 == 0){
					$row->delete($curr_bid);
					$delCount++;
				}
				if($ketemu > 0){
					$count_ketemu++;
				}
			}
			if($count_ketemu > 0){
				$app->enqueueMessage(JText::sprintf('COM_JBLANCE_CANNOT_DELETE_DATA_DUE_TO_TABLE_LINKING', JText::_('COM_JBLANCE_PLAN')), 'error');
			}
		}
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_PLAN_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
		$app->redirect($link, $msg);
	}
	
	function savePlan(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$row	= JTable::getInstance('plan', 'Table');
		$post   = JRequest::get('post');
		$post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$params	= $app->input->get('params', null, 'array');
	
		// Build parameter string
		$registry = new JRegistry();
		$registry->loadArray($params);
		$row->params = $registry->toString();
		unset($post['params']);
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$msg	= JText::_('COM_JBLANCE_PLAN_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
		$app->redirect($link, $msg);
	}
	
	function cancelPlan(){
		$app = JFactory::getApplication();
		$msg ='';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
		$app->redirect( $link,$msg );
	}
	
	function showPlan(){
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('layout', 'showplan');
		$this->display();
	}
	
	function setPlanDefault(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit('Invalid Token');
	
		// Initialise variables.
		$app  	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$ug_id 	= $app->input->get('ug_id', 0, 'int');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
	
		//set all the plans to undefault for the user group
		$query = "UPDATE #__jblance_plan SET default_plan = 0 WHERE ug_id =".$db->quote($ug_id);
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
	
		//now set the particular plan to be default
		$query = "UPDATE #__jblance_plan SET default_plan = 1 WHERE id = $cid[0] AND ug_id =".$db->quote($ug_id);
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
	
		$msg	= JText::_('COM_JBLANCE_PLAN_SET_DEFAULT_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showplan';
		$this->setRedirect($link, $msg);
	}
	
/**
 ================================================================================================================
 SECTION : Configuration:Payment Gateways - save, cancel
 ================================================================================================================
 */
	function savePaymode(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app  	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$post   = JRequest::get('post');
		$row	= JTable::getInstance('paymode', 'Table');
		$gateway = $app->input->get('gateway', '', 'string');
		$id		= $app->input->get('id', 0, 'int');
		$params	= $app->input->get('params', null, 'array');
		
		$registry = new JRegistry();
		$registry->loadArray($params);
		$row->params = $registry->toString();
		unset($post['params']);
		
		// save the changes
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
		$row->checkin();
	
		$msg	= JText::_('COM_JBLANCE_PAYMENT_GATEWAY_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
		$this->setRedirect($link, $msg);
	}
	
	function cancelPaymode(){
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
		$this->setRedirect($link, $msg);
	}

/**
 ================================================================================================================
 SECTION : Custom Fields - newcustomgroup, newCustomField, remove, save, cancel
 ================================================================================================================
 */
	function newCustomGroup(){
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editcustomfield');
		JRequest :: setVar('type', 'group');
		$this->display();
	}
	
	function newCustomField(){
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editcustomfield');
		$this->display();
	}
	
	function removeCustomField(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app = JFactory::getApplication();
		$db  = JFactory::getDBO();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger( $cid );
	
		$cids = implode(',', $cid);
		$query = 'DELETE FROM #__jblance_custom_field WHERE id IN ('.$cids.')';
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
		// Remove custom field values too.
		$query = 'DELETE FROM #__jblance_custom_field_value WHERE fieldid IN ('.$cids.')';
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
	
		$msg	= JText::_('COM_JBLANCE_CUSTOM_FIELD_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
		$app->redirect($link, $msg);
	}
	
	function saveCustomField(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$row = JTable::getInstance('custom', 'Table');
		$post = JRequest::get('post');
		$required = (!empty($post['required']))? $post['required'] : 0;
		$published = (!empty($post['published']))? $post['published'] : 0;
		$parent = ($post['type'] == 'group')? 0 : $post['parent'];
	
		$row->required = $required;
		$row->published = $published;
		$row->parent = $parent;
	
		if($post['field_type'] == 'Select' && $post['value_type'] == 'database')
			$post['value'] = $post['databaseValues'];
		else
			$post['value'] = $post['customValues'];
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$msg	= JText::_('COM_JBLANCE_CUSTOM_FIELD_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
		$app->redirect($link, $msg);
	}
	
	function cancelCustomField(){
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcustomfield';
		$app->redirect($link, $msg);
	}
	
/**
 ================================================================================================================
 SECTION : Configuration:Email Templates - save
 ================================================================================================================
 */
	function saveEmailTemplate(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$tempfor = $app->input->get('templatefor', 'subscr-pending', 'string');
		$row	= JTable::getInstance('emailtemp', 'Table');
		$post   = JRequest::get('post');
		$post['body'] = JRequest::getVar('body', '', 'POST', 'string', JREQUEST_ALLOWRAW);
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$msg	= JText::_('COM_JBLANCE_EMAIL_TEMPLATE_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=emailtemplate&tempfor='.$tempfor;
		$this->setRedirect($link, $msg);
	}

/**
 ================================================================================================================
 SECTION : Configuration:Category - new, remove, save, cancel
 ================================================================================================================
 */
	function newCategory(){
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editcategory');
		$this->display();
	}
	
	function removeCategory(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app 		= JFactory::getApplication();
		$db  		= JFactory::getDBO();
		$delCount 	= 0;
		$cid 		= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
	
		$cids = implode(',', $cid);
		$query = 'DELETE FROM #__jblance_category WHERE id IN ('.$cids.')';
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
		$delCount = $db->getAffectedRows();
		
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_CATEGORY_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
		$app->redirect($link, $msg);
	}
	
	function saveCategory(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app = JFactory::getApplication();
		$db		= JFactory::getDBO();
		$row	= JTable::getInstance('category', 'Table');
		$post   = JRequest::get('post');
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$msg	= JText::_('COM_JBLANCE_CATEGORY_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
		$app->redirect($link, $msg);
	}
	
	function cancelCategory(){
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showcategory';
		$app->redirect($link, $msg);
	}
	
/**
 ================================================================================================================
 SECTION : Configuration:Budget - new, remove, save, cancel
 ================================================================================================================
 */
	
	function newBudget(){
		JRequest :: setVar('view', 'admconfig');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editbudget');
		$this->display();
	}
	
	function removeBudget(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app = JFactory::getApplication();
		$db  = JFactory::getDBO();
		$delCount = 0;
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
	
		$cids = implode(',', $cid);
		$query = 'DELETE FROM #__jblance_budget WHERE id IN ('.$cids.')';
		$db->setQuery($query);
		if(!$db->execute()){
			JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
		}
		$delCount = $db->getAffectedRows();
	
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_BUDGET_RANGE_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
		$app->redirect($link, $msg);
	}
	
	function saveBudget(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$row	= JTable::getInstance('budget', 'Table');
		$post   = JRequest::get('post');
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$msg	= JText::_('COM_JBLANCE_BUDGET_RANGE_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
		$app->redirect($link, $msg);
	}
	
	function cancelBudget(){
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=showbudget';
		$app->redirect($link, $msg);
	}
	/* Misc Functions */
	
	//5.Publish / Unpublish row data
	function jbPubUnpub($publish, $tbl, $link, $msg){
	
		// Check for request forgeries
		JSession::checkToken() or jexit( 'Invalid Token' );
	
		// Initialize variables
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$cid 	= $app->input->get('cid', array(), 'array');
		$task = JFactory::getApplication()->input->get('task');
		$n = count($cid);
	
		JArrayHelper::toInteger($cid);
		$cids = implode(',', $cid);
		$query = 'UPDATE '.$tbl.' SET published = '.(int)$publish.' WHERE id IN ('.$cids.')';//echo $query;exit;
		$db->setQuery($query);
	
		if (!$db->execute()) {
			return JError::raiseWarning(500, $db->getError());
		}
		$app->redirect($link, $msg);
	}
	
	//6.Require / Unrequire fields
	function jbReqUnrequired($required, $tbl, $link, $msg){
		$app = JFactory::getApplication();
		// Check for request forgeries
		JSession::checkToken() or jexit( 'Invalid Token' );
	
		// Initialize variables
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$cid 	= $app->input->get('cid', array(), 'array');
	
		JArrayHelper::toInteger($cid);
		$cids = implode(',', $cid);
		$query = 'UPDATE '.$tbl.' SET required = '.(int)$required.' WHERE id IN ('.$cids.')';
		$db->setQuery($query);
	
		if(!$db->execute()){
			return JError::raiseWarning(500, $db->getError());
		}
		$app->redirect($link, $msg);
	}
	
	function runSql(){
		
		$db = JFactory::getDBO();
		$post   = JRequest::get('POST');
		$result = 0;
		
		if($post['actiontype'] == 1){ // first time
			if($_FILES['runsql']['size'] > 0){
				$file_name = $_FILES['runsql']['name']; // file name
				$file_tmp = $_FILES['runsql']['tmp_name']; // actual location
				
				$ext = JFile::getExt($file_name);
				if(!empty($file_tmp)){
					if($ext != "sql")
						$result = 2; //file type mismatch
				}
				
				$theData = JFile::read($file_tmp);
				
				$db->setQuery($theData);
				if($db->queryBatch())
					$result = 1;
			}
		}
		
		if($result == 1){
			$msg = JText::_('COM_JBLANCE_SQL_EXECUTED_SUCCESSFULLY');
		}
		elseif($result == 2){ // file mismatch
			$msg = JText::_('COM_JBLANCE_ONLY_SQL_ALLOWED');
		}
		else {
			$msg = JText::_('COM_JBLANCE_OPERATIION_UNSUCCESSFUL');
		}
		
		$link = 'index.php?option=com_jblance&view=admconfig&layout=showpaymode';
		$this->setRedirect($link, $msg);
		
	}
	
	function optimise(){
		$app  		 = JFactory::getApplication();
		$db		 	 = JFactory::getDBO();
		$user_ids 	 = $app->input->get('userIds', '', 'string');
		$project_ids = $app->input->get('projectIds', '', 'string');
	
		if(empty($user_ids) && empty($project_ids)){
			$msg	= JText::_('COM_JBLANCE_NO_OPERATION_EXECUTED');
			$link	= 'index.php?option=com_jblance&view=admconfig&layout=optimise';
			$app->redirect($link, $msg);
		}
		else {
			// delete from user table
			$query = "DELETE FROM #__jblance_user WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' users deleted from JoomBri users table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from project table
			$query = "DELETE FROM #__jblance_project WHERE id IN (".$project_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' projects deleted from JoomBri project table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from bid table
			$query = "DELETE FROM #__jblance_bid WHERE user_id IN (".$user_ids.") OR project_id IN (".$project_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from bid table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from custom field value table
			$query = "DELETE FROM #__jblance_custom_field_value WHERE userid IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from custom field value table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from deposit table
			$query = "DELETE FROM #__jblance_deposit WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from deposit table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from escrow table
			$query = "DELETE FROM #__jblance_escrow WHERE from_id IN (".$user_ids.") OR to_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from escrow table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from feeds table
			$query = "DELETE FROM #__jblance_feed WHERE actor IN (".$user_ids.") OR target IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from feeds table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from feeds hide table
			$query = "DELETE FROM #__jblance_feed_hide WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from feeds hide table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from forum table
			$query = "DELETE FROM #__jblance_forum WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from forum table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from message table
			$query = "DELETE FROM #__jblance_message WHERE idFrom IN (".$user_ids.") OR idTo IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from message table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from notify table
			$query = "DELETE FROM #__jblance_notify WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from notify table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from plan subscr table
			$query = "DELETE FROM #__jblance_plan_subscr WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from plan subscr table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from portfolio table
			$query = "DELETE FROM #__jblance_portfolio WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from portfolio table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from project file table
			$query = "DELETE FROM #__jblance_project_file WHERE project_id IN (".$project_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from project file table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from rating table
			$query = "DELETE FROM #__jblance_rating WHERE actor IN (".$user_ids.") OR target IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from rating table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from report table
			$query = "DELETE FROM #__jblance_report WHERE (`method` like 'project%' AND params IN ($project_ids)) OR (`method` like 'profile%' AND params IN ($user_ids))";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from report table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from reporter table
			$query = "DELETE FROM #__jblance_report_reporter WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from reporter table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from transaction table
			$query = "DELETE FROM #__jblance_transaction WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from transaction table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
	
			// delete from withdraw table
			$query = "DELETE FROM #__jblance_withdraw WHERE user_id IN (".$user_ids.")";
			$db->setQuery($query);
			$db->execute();
			$num_rows = $db->getAffectedRows();
			if($num_rows > 0){
				$msg = $num_rows.' entries deleted from withdraw table';
				$app->enqueueMessage($msg, 'notice');
				$result[] = $msg;
			}
		}
	
		$msg	= JText::_('COM_JBLANCE_OPERATION_COMPLETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admconfig&layout=optimise';
		$app->redirect($link, $msg);
	}
	
	function display($cachable = false, $urlparams = false){
		$document = JFactory :: getDocument();
		$viewName = JRequest :: getVar('view', 'admconfig');
		$layoutName = JRequest :: getVar('layout', 'configpanel');
		$viewType = $document->getType();
		$model = $this->getModel('admconfig', 'JblanceModel');
		$view = $this->getView($viewName, $viewType);
		if (!JError :: isError($model)){
			$view->setModel($model, true);
		}
		$view->setLayout($layoutName);
		$view->display();
	}
}
