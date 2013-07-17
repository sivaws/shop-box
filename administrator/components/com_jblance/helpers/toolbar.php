<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	03 November 2012
 * @file name	:	helpers/toolbar.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class JbToolbarHelper {
//Dashboard
	public static function _DASHBOARD(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_JOOMBRI'), 'joombri');    
		
		$canDo = JblanceHelper::getActions();
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_jblance');
		}
	}
	
	public static function _DEFAULT(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_JOOMBRI'), 'joombri');    
		
		$canDo = JblanceHelper::getActions();
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_jblance');
		} 
	}
	
	//PROJECT
	public static function _SHOW_PROJECT(){
		JToolBarHelper::title( JText::_('COM_JBLANCE_TOOLBAR_PROJECT_MANAGER'), 'joombri');    
		JToolBarHelper::custom('admproject.removeproject', 'delete.png', 'delete_f2.png', 'JTOOLBAR_DELETE', true, false); 
		JToolBarHelper::custom('admproject.newproject', 'new.png', 'new_f2.png', 'JTOOLBAR_NEW', false, false);
		JToolBarHelper::custom('admproject.approveproject', 'apply.png', 'apply_f2.png','COM_JBLANCE_APPROVE', true, true);
	}
	
	public static function _EDIT_PROJECT(){
		$app	= JFactory::getApplication();
		$cid	= $app->input->get('cid', array(), 'array');
		$isNew	= (empty($cid)) ? true : false; //if it is new job, cid = 0
		$text	= ($isNew ? JText::_('JTOOLBAR_NEW') : JText::_('JTOOLBAR_EDIT'));
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_PROJECT_MANAGER') .': <small><small>[ '. $text .' ]</small></small>', 'joombri');
		JToolBarHelper::custom('admproject.saveproject', 'save.png', 'save_f2.png', 'JTOOLBAR_SAVE', false,  false);		
		if($isNew)
			JToolBarHelper::custom('admproject.cancelproject', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CANCEL', false, false);	
		else
			JToolBarHelper::custom('admproject.cancelproject', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CLOSE', false, false);
	}
	
	//USER
	public static function _SHOW_USER(){
		JToolBarHelper::title( JText::_('COM_JBLANCE_TOOLBAR_USER_MANAGER'), 'joombri');
	}
	
	public static function _EDIT_USER(){
		$app	= JFactory::getApplication();
		$cid	= $app->input->get('cid', array(), 'array');
		$isNew	= (empty($cid)) ? true : false; //if it is new job, cid = 0
		$text	= ($isNew ? JText::_('JTOOLBAR_NEW') : JText::_('JTOOLBAR_EDIT'));
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_USER_MANAGER') .': <small><small>['.$text.']</small></small>', 'joombri');
		JToolBarHelper::custom('admproject.saveuser', 'save.png', 'save_f2.png', 'JTOOLBAR_SAVE', false,  false);		
		if($isNew)
			JToolBarHelper::custom('admproject.canceluser', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CANCEL', false,  false);
		else
			JToolBarHelper::custom('admproject.canceluser', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CLOSE', false,  false);
	}
	
	//SUBSCRIPTION
	public static function _SHOW_SUBSCR(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_SUBSCRIPTIONS_MANAGER'), 'joombri');
		JToolBarHelper::custom('admconfig.showplan', 'edit.png', 'edit_f2.png', 'COM_JBLANCE_PLANS', false, false);
		JToolBarHelper::divider();
		JToolBarHelper::custom('admproject.newsubscr', 'new.png', 'new_f2.png', 'JTOOLBAR_NEW', false, false);
		JToolBarHelper::custom('admproject.removesubscr', 'delete.png', 'delete_f2.png', 'JTOOLBAR_DELETE', true, false);
		JToolBarHelper::custom('admproject.approvesubscr', 'apply.png', 'apply_f2.png','COM_JBLANCE_APPROVE', true, true);
	}
	
	public static function _EDIT_SUBSCR(){
		$app	= JFactory::getApplication();
		$cid	= $app->input->get('cid', array(), 'array');
		$isNew	= (empty($cid)) ? true : false; //if it is new job, cid = 0
		$text	= ($isNew ? JText::_('JTOOLBAR_NEW') : JText::_('JTOOLBAR_EDIT'));
		JToolBarHelper::title( JText::_('COM_JBLANCE_TOOLBAR_SUBSCRIPTIONS_MANAGER') .': <small><small>[ '. $text .' ]</small></small>', 'joombri');
		JToolBarHelper::custom('admproject.savesubscr', 'save.png', 'save_f2.png', 'JTOOLBAR_SAVE', false, false);
		if($isNew)
			JToolBarHelper::custom('admproject.cancelsubscr', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CANCEL', false, false);
		else
			JToolBarHelper::custom('admproject.cancelsubscr', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CLOSE', false, false);
	}
	
	//DEPOSIT
	public static function _SHOW_DEPOSIT(){
		JToolBarHelper::title( JText::_('COM_JBLANCE_TOOLBAR_FUNDS_DEPOSIT_MANAGER'), 'joombri');
		JToolBarHelper::custom( 'admproject.removedeposit', 'delete.png', 'delete_f2.png', 'JTOOLBAR_DELETE', true, false);
		JToolBarHelper::custom( 'admproject.approvedeposit', 'apply.png', 'apply_f2.png','COM_JBLANCE_APPROVE', true, true);
	}
	
	//WITHDRAW
	public static function _SHOW_WITHDRAW(){
		JToolBarHelper::title( JText::_('COM_JBLANCE_TOOLBAR_FUNDS_WITHDRAWAL_MANAGER'), 'joombri');
		JToolBarHelper::custom( 'admproject.removewithdraw', 'delete.png', 'delete_f2.png', 'JTOOLBAR_DELETE', true, false);
		JToolBarHelper::custom( 'admproject.approvewithdraw', 'apply.png', 'apply_f2.png','COM_JBLANCE_APPROVE', true, true);
	}
	
	//ESCROW
	public static function _SHOW_ESCROW(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_ESCROW_TRANSFER_MANAGER'), 'joombri');
	}
	
	//REPORTING
	public static function _SHOW_REPORTING(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_REPORTING_MANAGER'), 'joombri');
		JToolBarHelper::custom('admproject.removereporting', 'delete.png', 'delete_f2.png', 'JTOOLBAR_DELETE', true, false);
		JToolBarHelper::custom('admproject.purgereporting', 'purge.png', 'purge_f2.png', 'COM_JBLANCE_PURGE_PROCESSED', false, false);
	}
	
	//MESSAGE
	public static function _SHOW_MESSAGE(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_PRIVATE_MESSAGES_MANAGER'), 'joombri');
	}
	
	//CONFIGURATION : CONFIG PANEL
	public static function _CONFIG_PANEL(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_PANEL'), 'joombri');
	}
	//CONFIGURATION : COMPONENT SETTINGS
	public static function _CONFIG(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_COMPONENT_SETTINGS'), 'joombri');
		JToolBarHelper::custom('admconfig.saveconfig', 'save.png', 'save_f2.png', 'JTOOLBAR_APPLY', false, false);
		JToolBarHelper::custom('admconfig.cancelconfig', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CANCEL', false, false);
	}
	
	//CONFIGURATION : User Group
	public static function _SHOW_USERGROUP(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_USERGROUPS'), 'joombri');
		JToolBarHelper::custom('admconfig.removeusergroup', 'delete.png', 'delete_f2.png', 'JTOOLBAR_DELETE', true, true);
		JToolBarHelper::custom('admconfig.newusergroup', 'new.png', 'new_f2.png', 'JTOOLBAR_NEW', false, false);
	}
	
	public static function _EDIT_USERGROUP(){
		$app	= JFactory::getApplication();
		$cid	= $app->input->get('cid', array(), 'array');
		$isNew	= (empty($cid)) ? true : false; //if it is new job, cid = 0
		$text	= ($isNew ? JText::_('JTOOLBAR_NEW') : JText::_('JTOOLBAR_EDIT'));
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_USERGROUPS') .': <small><small>['.$text.']</small></small>', 'joombri');
		JToolBarHelper::custom('admconfig.saveusergroup', 'save.png', 'save_f2.png', 'JTOOLBAR_SAVE', false, false);
		if($isNew)
			JToolBarHelper::custom('admconfig.cancelusergroup', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CANCEL', false, false);
		else
			JToolBarHelper::custom('admconfig.cancelusergroup', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CLOSE', false, false);
	}
	
	//CONFIGURATION : SUBSCRIPTION PLANS FOR USERS
	public static function _SHOW_PLAN(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_SUBSCRIPTION_PLANS'), 'joombri');
		JToolBarHelper::custom('admproject.showsubscr', 'apply.png', 'apply_f2.png', 'COM_JBLANCE_SUBSCRIPTIONS', false, false);
		JToolBarHelper::divider();
		JToolBarHelper::custom('admconfig.removeplan', 'delete.png', 'delete_f2.png', 'JTOOLBAR_DELETE', true, true);
		JToolBarHelper::custom('admconfig.newplan', 'new.png', 'new_f2.png', 'JTOOLBAR_NEW', false, false);
	}
	
	public static function _EDIT_PLAN(){
		$app	= JFactory::getApplication();
		$cid	= $app->input->get('cid', array(), 'array');
		$isNew	= (empty($cid)) ? true : false; //if it is new job, cid = 0
		$text	= ($isNew ? JText::_('JTOOLBAR_NEW') : JText::_('JTOOLBAR_EDIT'));
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_SUBSCRIPTION_PLANS') .': <small><small>['.$text.']</small></small>', 'joombri');
		JToolBarHelper::custom('admconfig.saveplan', 'save.png', 'save_f2.png', 'JTOOLBAR_SAVE', false, false);
		if($isNew)
			JToolBarHelper::custom('admconfig.cancelplan', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CANCEL', false, false);
		else
			JToolBarHelper::custom('admconfig.cancelplan', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CLOSE', false, false);
	}
	
	//CONFIGURATION : PAYMENT MODES
	public static function _SHOW_PAYMODE(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_PAYMENT_GATEWAYS'), 'joombri');
		JToolBarHelper::publish('admconfig.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('admconfig.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	}
	
	public static function _EDIT_PAYMODE(){
		$app	= JFactory::getApplication();
		$cid	= $app->input->get('cid', array(), 'array');
		$isNew	= (empty($cid)) ? true : false; //if it is new job, cid = 0
		$text	= ($isNew ? JText::_('JTOOLBAR_NEW') : JText::_('JTOOLBAR_EDIT'));
		JToolBarHelper::title( JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_PAYMENT_GATEWAYS') .': <small><small>[ '. $text .' ]</small></small>', 'joombri');
		JToolBarHelper::custom('admconfig.savepaymode', 'save.png', 'save_f2.png', 'JTOOLBAR_SAVE', false,  false );
		if($isNew)
			JToolBarHelper::custom('admconfig.cancelpaymode', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CANCEL', false,  false);
		else
			JToolBarHelper::custom('admconfig.cancelpaymode', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CLOSE', false,  false);
	}
	
	//CONFIGURATION : CUSTOM FIELDS
	public static function _SHOW_CUSTOM_FIELD(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_CUSTOM_FIELDS'), 'joombri');
		JToolBarHelper::custom('admconfig.removecustomfield', 'delete.png', 'delete_f2.png', 'JTOOLBAR_DELETE', true, true);
		JToolBarHelper::custom('admconfig.newcustomfield', 'new.png', 'new_f2.png', 'COM_JBLANCE_NEW_FIELD', false, false);
		JToolBarHelper::custom('admconfig.newcustomgroup', 'new.png', 'new_f2.png', 'COM_JBLANCE_NEW_GROUP', false, false);
	}
	
	public static function _EDIT_CUSTOM_FIELD(){
		$app	= JFactory::getApplication();
		$cid	= $app->input->get('cid', array(), 'array');
		$isNew	= (empty($cid)) ? true : false; //if it is new job, cid = 0
		$text	= ($isNew ? JText::_('JTOOLBAR_NEW') : JText::_('JTOOLBAR_EDIT'));
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_CUSTOM_FIELDS') .': <small><small>['.$text.']</small></small>', 'joombri');
		JToolBarHelper::custom('admconfig.savecustomfield', 'save.png', 'save_f2.png', 'JTOOLBAR_SAVE', false, false);
		if($isNew)
			JToolBarHelper::custom('admconfig.cancelcustomfield', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CANCEL', false, false);
		else
			JToolBarHelper::custom('admconfig.cancelcustomfield', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CLOSE', false, false);
	}
	
	//CONFIGURATION : EMAIL TEMPLATES
	public static function _EMAIL_TEMPLATE(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_EMAIL_TEMPLATES'), 'joombri');
		JToolBarHelper::custom('admconfig.saveemailtemplate', 'save.png', 'save_f2.png', 'JTOOLBAR_APPLY', false, false);
	}
	
	//CONFIGURATION : CATEGORY
	public static function _SHOW_CATEGORY(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_CATEGORIES'), 'joombri');
		JToolBarHelper::custom('admconfig.removecategory', 'delete.png', 'delete_f2.png', 'JTOOLBAR_DELETE', true, true);
		JToolBarHelper::custom('admconfig.newcategory', 'new.png', 'new_f2.png', 'JTOOLBAR_NEW', false, false);
	}
	
	public static function _EDIT_CATEGORY(){
		$app	= JFactory::getApplication();
		$cid	= $app->input->get('cid', array(), 'array');
		$isNew	= (empty($cid)) ? true : false; //if it is new job, cid = 0
		$text	= ($isNew ? JText::_('JTOOLBAR_NEW') : JText::_('JTOOLBAR_EDIT'));
		JToolBarHelper::title( JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_CATEGORIES') .': <small><small>['.$text.']</small></small>', 'joombri');
		JToolBarHelper::custom('admconfig.savecategory', 'save.png', 'save_f2.png', 'JTOOLBAR_SAVE', false,  false);
		if($isNew)
			JToolBarHelper::custom('admconfig.cancelcategory', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CANCEL', false, false);
		else
			JToolBarHelper::custom('admconfig.cancelcategory', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CLOSE', false, false);
	}
	
	//CONFIGURATION : BUDGET
	public static function _SHOW_BUDGET(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_BUDGET_RANGE'), 'joombri');
		JToolBarHelper::custom('admconfig.removebudget', 'delete.png', 'delete_f2.png', 'JTOOLBAR_DELETE', true, true);
		JToolBarHelper::custom('admconfig.newbudget', 'new.png', 'new_f2.png', 'JTOOLBAR_NEW', false, false);
	}
	
	public static function _EDIT_BUDGET(){
		$app	= JFactory::getApplication();
		$cid	= $app->input->get('cid', array(), 'array');
		$isNew	= (empty($cid)) ? true : false; //if it is new job, cid = 0
		$text	= ($isNew ? JText::_('JTOOLBAR_NEW') : JText::_('JTOOLBAR_EDIT'));
		JToolBarHelper::title( JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_BUDGET_RANGE') .': <small><small>['.$text.']</small></small>', 'joombri');
		JToolBarHelper::custom('admconfig.savebudget', 'save.png', 'save_f2.png', 'JTOOLBAR_SAVE', false,  false);
		if($isNew)
			JToolBarHelper::custom('admconfig.cancelbudget', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CANCEL', false, false);
		else
			JToolBarHelper::custom('admconfig.cancelbudget', 'cancel.png', 'cancel_f2.png', 'JTOOLBAR_CLOSE', false, false);
	}
	
	//CONFIGURATION : OPTIMSE
	public static function _OPTIMSE(){
		JToolBarHelper::title(JText::_('COM_JBLANCE_TOOLBAR_CONFIGURATION_OPTIMISE_DB'), 'joombri');
		//JToolBarHelper::custom('admconfig.optimise', 'delete.png', 'delete_f2.png', 'JTOOLBAR_Optimise', false);
	}
}