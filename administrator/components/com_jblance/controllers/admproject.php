<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	controllers/admproject.php
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
class JblanceControllerAdmproject extends JControllerAdmin {

	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct(){
		parent::__construct();
	
		// Register Extra tasks
		/* $this->registerTask('add', 'edit');
		
		$this->registerTask('approveproject', 'approveproject'); */
		
		
		$this->registerTask('block', 'changeBlock');
		$this->registerTask('unblock', 'changeBlock');
	}
	
	/* function edit(){
		$app  	= JFactory::getApplication();
		$layout = $app->input->get('layout', '', 'string');
		$app->input->set('view', 'admproject');
		$app->input->set('hidemainmenu', 1);
		
		if($layout == 'showproject')	$app->input->set('layout', 'editproject');

		$this->display();
	}
	
	function remove(){
		$layout = $app->input->get('layout');
		if($layout == 'showproject') $this->removeProject();
	} */
/**
 ================================================================================================================
 SECTION : Project - new, remove, save, cancel, approve
 ================================================================================================================
 */
	function newProject(){
		JRequest :: setVar('view', 'admproject');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editproject');
		$this->display();
	}
	
	//4.Remove Resume
	function removeProject(){
		
		// Check for request forgeries
		JSession::checkToken() or jexit( 'Invalid Token' );
		
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$row 	= JTable::getInstance('project', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		
		if(count($cid)){
			for($i = 0; $i < count($cid); $i++){
				if(!$row->delete($cid[$i])){
					JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
				}
				// Remove the bids for this project
				$query = 'DELETE FROM #__jblance_bid WHERE project_id = '.$db->quote($cid[$i]);
				$db->setQuery($query);
				if(!$db->execute()){
					JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
				}
			}
		}
		$msg	= JText::_('COM_JBLANCE_PROJECTS_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showproject';
		$app->redirect($link, $msg);
	}
	
	function saveProject(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app  = JFactory::getApplication();
		$db	  = JFactory::getDBO();
		$row  = JTable::getInstance('project', 'Table');
		$id	  = $app->input->get('id' , 0 , 'int');
		$post = JRequest::get('post');
		$post['description'] = JRequest::getVar('description', '', 'POST', 'string', JREQUEST_ALLOWRAW);
		$budgetRange = $app->input->get('budgetrange', '', 'string');
		$isNew	= ($id == 0) ? true : false;
		
		if($isNew){
			$now = JFactory::getDate();
			$post['create_date'] = $now->toSql();
		}
		
		$id_category 	= $app->input->get('id_category', '', 'array'); 
		if(count($id_category) > 0 && !(count($id_category) == 1 && empty($id_category[0]))){
			$proj_categ = implode(',', $id_category);
		}
		elseif($id_category[0] == 0){
			$proj_categ = 0;
		}
		
		$post['id_category'] = $proj_categ;
		
		$budgetRange = explode('-', $budgetRange);
		$post['budgetmin'] = $budgetRange[0];
		$post['budgetmax'] = $budgetRange[1];
	 
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
		
		//save the custom field value for project
		$fields = JblanceHelper::get('helper.fields');		// create an instance of the class fieldsHelper
		$fields->saveFieldValues('project', $row->id, $post);
		
		JBMediaHelper::uploadFile($post, $row);		//remove and upload files
	
		$msg	= JText::_('COM_JBLANCE_PROJECT_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showproject';
		$app->redirect($link, $msg);
	}
	
	function cancelProject(){
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showproject';
		$app->redirect($link, $msg);
	}
	
	function approveProject(){
	
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$user	= JFactory::getUser();
		$cid 	= $app->input->get('cid', array(), 'array');
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		JArrayHelper::toInteger($cid, array(0));
		$delCount = 0;
	
		if(count($cid)){
			$count_ketemu = 0;
			for($i = 0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
				$row	= JTable::getInstance('project', 'Table');
				$row->load($curr_bid);
	
				//checking first
				if(!$row->approved){
	
					$row->approved = 1;
	
					if(!$row->check())
						JError::raiseError(500, $row->getError());
					
					if(!$row->store())
						JError::raiseError(500, $row->getError());
					
					$row->checkin();
					$delCount++;
					
					//send project approved email to the publisher
					$jbmail->sendPublisherProjectApproved($row->id);
					
					//send new project notification to all users
					$jbmail->sendNewProjectNotification($row->id);
				}
			}
		}
		$msg = $delCount.' '.JText::_('COM_JBLANCE_PROJECTS_APPROVED_SUCCESSFULLY');
		$link = 'index.php?option=com_jblance&view=admproject&layout=showproject';
		$app->redirect($link, $msg);
	}
	
/**
 ================================================================================================================
 SECTION : User - save, cancel
 ================================================================================================================
 */	
	function saveUser(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app 		= JFactory::getApplication();
		$db			= JFactory::getDBO();
		$row		= JTable::getInstance('jbuser', 'Table');
		$post  		= JRequest::get('post');
		$id			= $app->input->get('id', 0, 'int');
		$fund 	 	= $app->input->get('fund', null, 'float');
		$desc_fund 	= $app->input->get('desc_fund', null, 'string');
		$type_fund 	= $app->input->get('type_fund', null, 'string');
		$user_id 	= $app->input->get('user_id', 0, 'int');
		$is_new		= false;
		
		$config 	= JblanceHelper::getConfig();
		$currsymb 	= $config->currencySymbol;
	
		if($id > 0){	//existing user in JoomBri user table
			$row->load($id);
			$user_id = $row->user_id;
		}
		else {	// new user
			$is_new		= true;
			$row->user_id = $user_id;
		}
		
		$id_category 	= $app->input->get('id_category', '', 'array');
		if(count($id_category) > 0 && !(count($id_category) == 1 && empty($id_category[0]))){
			$proj_categ = implode(',', $id_category);
		}
		elseif($id_category[0] == 0){
			$proj_categ = 0;
		}
		$post['id_category'] = $proj_categ;
	
		/* //set the company name to empty if the user has JoomBri profile and not allowed to post job.
		$hasJBProfile = JblanceHelper::hasJBProfile($user_id);	//check if the user has JoomBri profile
		if($hasJBProfile){
			$jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
			$userInfo = $jbuser->getUserGroupInfo(null, $post['ug_id']);
			if(!$userInfo->allowPostProjects)
				$post['biz_name'] = '';
		} */
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		if(!empty($fund)){
			//update the transaction table
			if($type_fund == 'p'){
				JblanceHelper::updateTransaction($user_id, $desc_fund, $fund, 1);
				$msg_fund = JText::sprintf('COM_JBLANCE_USER_CREDITED_WITH_CURRENCY', $currsymb, $fund);
				$app->enqueueMessage($msg_fund);
			}
			elseif($type_fund == 'm'){
				JblanceHelper::updateTransaction($user_id, $desc_fund, $fund, -1);
				$msg_fund = JText::sprintf('COM_JBLANCE_USER_DEBITED_WITH_CURRENCY', $currsymb, $fund);
				$app->enqueueMessage($msg_fund);
			}
		}
	
		$fields = JblanceHelper::get('helper.fields');		// create an instance of the class fieldsHelper
		$fields->saveFieldValues('profile', $row->user_id, $post);
		
		//insert the user to notify table, if new user
		if($is_new){
			$obj = new stdClass();
			$obj->user_id = $user_id;
			$db->insertObject('#__jblance_notify', $obj);
		}
	
		$name = $post['name'];
		$query = "UPDATE #__users SET name='$name' WHERE id=".$db->quote($user_id);
		$db->setQuery($query);
		$db->execute();
	
		$msg	= JText::_('COM_JBLANCE_USER_SAVED_SUCCESSFULLY').' - '.JText::_('COM_JBLANCE_USERID').' : '.$user_id;
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showuser';
		$app->redirect($link, $msg);
	}
	
	function cancelUser(){
		$app = JFactory::getApplication();
		$msg = '';
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showuser';
		$app->redirect($link, $msg);
	}
	
	public function changeBlock(){
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app 	= JFactory::getApplication();
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$cid 	= $app->input->get('cid', array(), 'array');
		$values	= array('block' => 1, 'unblock' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

		// Get the model of user component.
		include_once(JPATH_ADMINISTRATOR.'/components/com_users/models/user.php');
		$model =  new UsersModelUser();

		if(count($cid)){
			for($i=0; $i<count($cid); $i++){
				$curr_bid = $cid[$i];
				$userid = $curr_bid;
				if(!$model->block($curr_bid, $value)){
					JError::raiseWarning(500, $model->getError());
				}
				//send user approved email
				if($value == 0)
					$jbmail->sendUserAccountApproved($userid);
			}
		}
		
		if($value == 1){
			$msg = count($cid).' '.JText::_('COM_JBLANCE_USERS_BLOCKED_SUCCESSFULLY');
		}
		elseif($value == 0){
			$msg = count($cid).' '.JText::_('COM_JBLANCE_USERS_APPROVED_SUCCESSFULLY');
		}
		
		$link = 'index.php?option=com_jblance&view=admproject&layout=showuser';
		$app->redirect($link, $msg);
	}
	
	/**
	 ================================================================================================================
	 SECTION : Subscription - new, remove, save, cancel, approve, show
	 ================================================================================================================
	 */
	function newSubscr(){
		JRequest :: setVar('view', 'admproject');
		JRequest :: setVar('hidemainmenu', 1);
		JRequest :: setVar('layout', 'editsubscr');
		$this->display();
	}
	
	function removeSubscr(){
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
	
		if(count($cid)){
			for($i = 0; $i < count($cid); $i++){
				$db->setQuery("DELETE FROM #__jblance_plan_subscr WHERE id=".$cid[$i]);
				if (!$db->execute()){
					JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
				}
			}
		}
		$msg  = JText::_('COM_JBLANCE_SUBSCRIPTIONS_DELETED_SUCCESSFULLY');
		$link = 'index.php?option=com_jblance&view=admproject&layout=showsubscr';
		$app->redirect($link, $msg);
	}
	
	function saveSubscr(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
	
		// Initialize variables
		$app = JFactory::getApplication();
		$db		= JFactory::getDBO();
		$row	= JTable::getInstance('plansubscr', 'Table');
	
		$config = JblanceHelper::getConfig();
		$invoiceFormatPlan = $config->invoiceFormatPlan;
	
		$post   = JRequest::get('post');
		$id = $post['id'];
	
		if($id > 0){	//existing subscription
			$row->load($id);
			$isNew = false;
		}
		else {	// new user
			$isNew = true;
		}
	
		if(!$row->bind($post)){
			JError::raiseError(500, $row->getError());
		}
		// pre-save checks
		if(!$row->check()){
			JError::raiseError(500, $row->getError());
		}
		if($isNew){
			//calculate the price
			$query = 'SELECT id, days, days_type, price, discount, bonusFund, name FROM #__jblance_plan WHERE id ='.$row->plan_id;
			$db->setQuery($query);
			$plan = $db->loadObject();
	
			if($plan->discount){
				$query = 'SELECT COUNT(*) AS total FROM #__jblance_plan_subscr WHERE plan_id ='.$row->plan_id.' AND user_id='.$row->user_id;
				$db->setQuery($query);
				$total = $db->loadResult();
				if($total > 0){
					$plan->price = $plan->price - (($plan->price / 100) * $plan->discount);
				}
			}
			$now = JFactory::getDate();
			$row->price = $plan->price;
			$row->fund = $plan->bonusFund;
			$row->date_buy = $now->toSql();
			$row->tax_percent = $config->taxPercent;
	
			if (!$row->store()){
				JError::raiseError(500, $row->getError());
			}
			//update the invoice no
			$year = date("Y");
			$time = time();
			//replace the tags
			$tags = array("[ID]", "[USERID]", "[YYYY]", "[TIME]");
			$tagsValues = array("$row->id", "$row->user_id", "$year", "$time");
			$invoiceNo = str_replace($tags, $tagsValues, $invoiceFormatPlan);
			$row->invoiceNo = $invoiceNo;
		}
		
		// save the changes
		if(!$row->store()) {
			JError::raiseError(500, $row->getError());
		}
		$row->checkin();

		//approve the subscription if new or approve manully by admin.
		if($post['approved'] == 1){
			if($isNew || $row->date_approval == '0000-00-00 00:00:00'){
				JRequest :: setVar('cid', $row->id);
				$this->approveSubscr();
			}
		}
	
		$msg	= JText::_('COM_JBLANCE_SUBSCRIPTION_SAVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showsubscr';
		$app->redirect($link, $msg);
	}
	
	function cancelSubscr(){
		$app = JFactory::getApplication();
		$msg = '';
		$link = 'index.php?option=com_jblance&view=admproject&layout=showsubscr';
		$app->redirect($link, $msg);
	}	
	
	function approveSubscr(){
	
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$user	= JFactory::getUser();
		$cid 	= $app->input->get('cid', array(), 'array');
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		JArrayHelper::toInteger($cid, array(0));
	
		if(count($cid)){
			$count_ketemu = 0;
			for($i = 0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
				$row	= JTable::getInstance('plansubscr', 'Table');
				$row->load($curr_bid);
	
				//get the plan details
				$query = "SELECT * FROM #__jblance_plan WHERE id = ".$row->plan_id;
				$db->setQuery($query);
				$plan = $db->loadObject();
	
				//checking first
				if($row->trans_id == 0){
	
					$desc_trans = JText::_('COM_JBLANCE_BUY_SUBSCR').' - '.$plan->name;
					$trans = JblanceHelper::updateTransaction($row->user_id, $desc_trans, $plan->bonusFund, 1);
	
					//update subscription approval
					$now = JFactory::getDate();
					$date_approve = $now->toSql();
					$now->modify("+$plan->days $plan->days_type");
					$date_expires = $now->toSql();
	
					$row->date_approval = $date_approve;
					$row->date_expire = $date_expires;
					$row->approved = 1;
					$row->gateway_id = time();
					$row->trans_id = $trans->id;
					$row->access_count = 1;
					
					//set the project/bid limit details
					$planParams = new JRegistry;
					$planParams->loadString($plan->params);
					
					$row->bids_allowed		= $planParams->get('flBidCount');
					$row->bids_left 		= $planParams->get('flBidCount');
					$row->projects_allowed	= $planParams->get('buyProjectCount');
					$row->projects_left		= $planParams->get('buyProjectCount');
	
					// pre-save checks
					if(!$row->check()){
						JError::raiseError(500, $row->getError());
					}
					// save the changes
					if(!$row->store()){
						JError::raiseError(500, $row->getError());
					}
					$row->checkin();
					$jbmail->sendSubscrApprovedEmail($row->id, $row->user_id);
				}
			}
		}
		$msg = JText::_('COM_JBLANCE_SUBSCRIPTIONS_APPROVED_SUCCESSFULLY');
		$link = 'index.php?option=com_jblance&view=admproject&layout=showsubscr';
		$app->redirect($link, $msg);
	}
	
	function showSubscr(){
		JRequest :: setVar('view', 'admproject');
		JRequest :: setVar('layout', 'showsubscr');
		$this->display();
	}
/**
 ================================================================================================================
 SECTION : Funds Deposit - approve, remove
 ================================================================================================================
 */
	function approveDeposit(){
	
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		$delCount = 0;
	
		if (count($cid)) {
			$count_ketemu = 0;
			for($i = 0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
				$row	= JTable::getInstance('deposit', 'Table');
				$row->load($curr_bid);
	
				//checking first
				if($row->trans_id == 0){
					$desc_credit = JText::sprintf('COM_JBLANCE_DEPOSIT_FUNDS');
					$trans = JblanceHelper::updateTransaction($row->user_id, $desc_credit, $row->amount, 1);
	
					$now = JFactory::getDate();
	
					//update deposit approval
					$row->date_approval = $now->toSql();
					$row->approved = 1;
					$row->trans_id = $trans->id;
	
					if(!$row->check())
						JError::raiseError(500, $row->getError());
					
					if(!$row->store())
						JError::raiseError(500, $row->getError());
					
					$row->checkin();
					$delCount++;
					
					//send approved deposit fund to depositor
					$jbmail->sendUserDepositFundApproved($row->id);
				}
				
			}
		}
	
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_FUND_DEPOSIT_APPROVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showdeposit';
		$app->redirect($link, $msg);
	}
	
	function removeDeposit(){
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		$delCount = 0;
	
		if(count($cid)){
			for($i = 0; $i < count($cid); $i++){
				$db->setQuery("DELETE FROM #__jblance_deposit WHERE id=".$cid[$i]);
				$delCount++;
				if(!$db->execute()){
					JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
				}
			}
		}
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_FUND_DEPOSIT_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showdeposit';
		$app->redirect($link, $msg);
	}	
/**
 ================================================================================================================
 SECTION : Funds Withdraw - approve, remove
 ================================================================================================================
 */
	function approveWithdraw(){
	
		$app  = JFactory::getApplication();
		$db	  = JFactory::getDBO();
		$user = JFactory::getUser();
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		$delCount = 0;
	
		if (count($cid)) {
			$count_ketemu = 0;
			for($i = 0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
				$row = JTable::getInstance('withdraw', 'Table');
				$row->load($curr_bid);
	
				//checking first
				if($row->trans_id == 0){
					$desc_trans = JText::sprintf('COM_JBLANCE_WITHDRAW_FUNDS');
					$trans = JblanceHelper::updateTransaction($row->user_id, $desc_trans, $row->amount, -1);
	
					$now = JFactory::getDate();
	
					//update withdraw approval
					$row->date_approval = $now->toSql();
					$row->approved = 1;
					$row->trans_id = $trans->id;
	
					if(!$row->check())
						JError::raiseError(500, $row->getError());
					
					if(!$row->store())
						JError::raiseError(500, $row->getError());
					
					$row->checkin();
					
					$delCount++;
					
					//send approved withdraw request to requestor
					$jbmail->sendWithdrawRequestApproved($row->id);
				}
			}
		}
	
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_FUND_WITHDRAWAL_APPROVED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showwithdraw';
		$app->redirect($link, $msg);
	}
	
	function removeWithdraw(){
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		$delCount = 0;
	
		if(count($cid)){
			for($i = 0; $i < count($cid); $i++){
				$db->setQuery("DELETE FROM #__jblance_withdraw WHERE id=".$cid[$i]);
				$delCount++;
				if(!$db->execute()){
					JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
				}
			}
		}
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_FUND_WITHDRAWAL_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showwithdraw';
		$app->redirect($link, $msg);
	}
	
/**
 ================================================================================================================
 SECTION : Escrow - release, cancel
 ================================================================================================================
 */
	function releaseEscrow(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit('Invalid Token');
		
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$id 	= $app->input->get('id', 0, 'int');
		
		$escrow = JTable::getInstance('escrow', 'Table');
		$escrow->load($id);
		
		$now = JFactory::getDate();
		$escrow->date_release = $now->toSql();
		$escrow->status = 'COM_JBLANCE_RELEASED';
		
		if(!$escrow->check())
			JError::raiseError(500, $escrow->getError());
		
		if(!$escrow->store())
			JError::raiseError(500, $escrow->getError());
		
		$escrow->checkin();
		
		//send escrow pymt released to the reciever
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$jbmail->sendEscrowPaymentReleased($escrow->id);
		
		/* //Trigger the plugin event to feed the activity - buyer pick freelancer
		JPluginHelper::importPlugin('system', 'jblancefeeds');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onBuyerReleaseEscrow', array($escrow->from_id, $escrow->to_id, $escrow->project_id)); */
		
		$msg = JText::_('COM_JBLANCE_ESCROW_PAYMENT_RELEASED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showescrow';
		$app->redirect($link, $msg);
	}
	
	function cancelEscrow(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit('Invalid Token');
	
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$id 	= $app->input->get('id', 0, 'int');
	
		$escrow = JTable::getInstance('escrow', 'Table');
		$escrow->load($id);
	
		//set the status to cancelled
		$escrow->status = 'COM_JBLANCE_CANCELLED';
	
		// get the transaction id and delete it
		$trans_id = $escrow->from_trans_id;
		$trans	= JTable::getInstance('transaction', 'Table');
		$trans->delete($trans_id);
	
		$escrow->from_trans_id = 0;
	
		if(!$escrow->check())
			JError::raiseError(500, $escrow->getError());
	
		if(!$escrow->store())
			JError::raiseError(500, $escrow->getError());
	
		$escrow->checkin();
	
	
		//Trigger the plugin event to feed the activity - buyer pick freelancer
		//JPluginHelper::importPlugin('system', 'jblancefeeds');
		//$dispatcher = JDispatcher::getInstance();
		//$dispatcher->trigger('onBuyerReleaseEscrow', array($escrow->from_id, $escrow->to_id, $escrow->project_id));
	
		$msg = JText::_('COM_JBLANCE_ESCROW_PAYMENT_CANCELLED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showescrow';
		$app->redirect($link, $msg);
	}
/**
 ================================================================================================================
 SECTION : Reporting - action, remove, purge
 ================================================================================================================
 */
	function reportAction(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit('Invalid Token');
		
		// Initialise variables.
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid);
		
		$report = JTable::getInstance('report', 'Table');
		$report->load($cid[0]);
		
		$reportHelper = JblanceHelper::get('helper.report');		// create an instance of the class ReportHelper
		
		$method		= explode(',', $report->method);
		$args		= $report->params;
		
		$result = $reportHelper->$method[1]($args);
		
		//set the status to 'processed'
		$report->status = 1;
		$report->store();
		
		$msg	= $result['action'];
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showreporting';
		$app->redirect($link, $msg);
	}
	
	function removeReporting(){
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$report = JTable::getInstance('report', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		$delCount = 0;
		
		if(count($cid)){
			for($i = 0; $i < count($cid); $i++){
				$curr_bid = $cid[$i];
				
				//remove from report table
				$report->load($curr_bid);
				$report->delete($curr_bid);
				
				//remove from reporter table
				$db->setQuery("DELETE FROM #__jblance_report_reporter WHERE report_id=".$curr_bid);
				if(!$db->execute()){
					JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
				}
				$delCount++;
			}
		}
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_REPORTING_DELETED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showreporting';
		$app->redirect($link, $msg);
	}
	
	function purgeReporting(){
		$app = JFactory::getApplication();
		$db	= JFactory::getDBO();
		$delCount = 0;
		
		$query = 'SELECT * FROM #__jblance_report WHERE status=1';
		$db->setQuery($query);
		$reports = $db->loadObjectList();
		
		for($i = 0; $i < count($reports); $i++){
			$report = $reports[$i];
			
			//remove from report table
			$db->setQuery("DELETE FROM #__jblance_report WHERE id=".$report->id);
			if(!$db->execute()){
				JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
			}
			
			//remove from reporter table
			$db->setQuery("DELETE FROM #__jblance_report_reporter WHERE report_id=".$report->id);
			if(!$db->execute()){
				JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
			}
			$delCount++;
		}
		$msg	= $delCount.' '.JText::_('COM_JBLANCE_REPORTING_PURGED_SUCCESSFULLY');
		$link	= 'index.php?option=com_jblance&view=admproject&layout=showreporting';
		$app->redirect($link, $msg);
		
	}
/**
 ================================================================================================================
 SECTION : Private Messages - process
 ================================================================================================================
 */	
	//2.Hide/remove Message
	function processMessage(){
		JblanceHelper::processMessage();
	}
	
	//download file
	function download(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit('Invalid Token');
	
		JBMediaHelper::downloadFile();
	}
	
	/* Misc Functions */
	
	function uploadPicture(){
		JBMediaHelper::uploadPictureMedia();
	}
	
	function removePicture(){
		JBMediaHelper::removePictureMedia();
	}
	
	function cropPicture(){
		JBMediaHelper::cropPictureMedia();
	}
	
	function display($cachable = false, $urlparams = false){
		$document = JFactory :: getDocument();
		$viewName = JRequest :: getVar('view', 'admproject');
		$layoutName = JRequest :: getVar('layout', 'dashboard');
		$viewType = $document->getType();
		$model = $this->getModel('admproject', 'JblanceModel');
		$view = $this->getView($viewName, $viewType);
		if (!JError :: isError($model)){
			$view->setModel($model, true);
		}
		$view->setLayout($layoutName);
		$view->display();
	}
	
	function removeForum(){
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$forumid= $app->input->get('forumid', '', 'int');
		
		$query = "DELETE FROM #__jblance_forum WHERE id=".$db->quote($forumid);
		$db->setQuery($query);
		$db->execute();
		
		echo 'OK';
		exit;
	}
}