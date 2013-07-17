<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	controllers/membership.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.controller');

class JblanceControllerMembership extends JControllerLegacy {
	
	function __construct(){
		parent :: __construct();
	}
	
	//1.Check Username & Email (ajax)
	function checkUser(){
		$app  = JFactory::getApplication();
		$db 	  = JFactory::getDBO();
		$inputstr = $app->input->get('inputstr', '', 'string');
		$name 	  = $app->input->get('name', '', 'string');
		$response = array();
	
		if($name == 'recipient'){
			$sql 	  = "SELECT COUNT(*) FROM #__users WHERE username=".$db->quote($inputstr);
		}
	
		$db->setQuery($sql);
		if($db->loadResult()){
			$response['result'] = 'OK';
			$response['msg'] = JText::_('COM_JBLANCE_VALID_USERNAME');
		}
		else {
			$response['result'] = 'NO';
			$response['msg'] = JText::_('COM_JBLANCE_INVALID_USERNAME');
		}
		echo json_encode($response); exit;
	}
	
	function fillProjectInfo(){
		$app  		= JFactory::getApplication();
		$db 	  	= JFactory::getDBO();
		$projectId 	= $app->input->get('project_id', 0, 'int');
		$response 	= array();
		
		$query = "SELECT p.*,b.amount bidamount FROM #__jblance_project p ".
				 "INNER JOIN #__jblance_bid b ON p.id=b.project_id ".
				 "WHERE p.id=".$db->quote($projectId)." AND b.status='COM_JBLANCE_ACCEPTED'";
		$db->setQuery($query);
		$row = $db->loadObject();
		
		if($row){
			$bidderUsername = JFactory::getUser($row->assigned_userid)->username;
			$proj_balance = $row->bidamount - $row->paid_amt;
			$response['result'] = 'OK';
			$response['assignedto'] = $bidderUsername;
			$response['bidamount'] = $row->bidamount;
			$response['proj_balance_html'] = JText::_('COM_JBLANCE_PROJECT_BALANCE_IS').' '.JblanceHelper::formatCurrency($proj_balance);
			$response['proj_balance'] = $proj_balance;
		}
		echo json_encode($response); exit;
		
	}
	
	//12.Cancel Subscription
	function cancelSubscr(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit('Invalid Token');

		//Initialise variables
		$app  = JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$user	= JFactory::getUser();
		$userid = $user->id;
		$model 	= $this->getModel();
		$id 	= $app->input->get('id', 0, 'int');
	
		if(!$userid){
			$return	= JRoute::_('index.php?option=com_users&view=login');
			$this->setRedirect($return);
			return;
		}
	
		//authorize cancel subscription
		$query = "SELECT * FROM #__jblance_plan_subscr WHERE id =".$db->quote($id);
		$db->setQuery($query);
		$subscr = $db->loadObject();
	
		if($subscr->user_id != $userid){
			$msg = JText::_('COM_JBLANCE_NOT_AUTHORIZED_TO_CANCEL_SUBSCR');
		}
		elseif($subscr->approved == 1){
			$msg = JText::_('COM_JBLANCE_CANNOT_CANCEL_APPROVED_SUBSCR');
		}
		else {
			//cancel subscription
			$query = "UPDATE #__jblance_plan_subscr SET approved=2 WHERE id =".$db->quote($id);	//2 - cancelled
			$db->setQuery($query);
			if(!$db->execute()){
				JError::raiseError($db->getErrorNum(), $db->getErrorMsg());
			}
			$msg = JText::_('COM_JBLANCE_SUBSCR_CANCELLED_SUCCESSFULLY');
		}
	
		$link = JRoute::_('index.php?option=com_jblance&view=membership&layout=planhistory', false);
		$this->setRedirect($link, $msg);
	}
	
	//10.Upgrade Subscription
	function upgradeSubscription(){
	
		//Initialize variables
		$app  = JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$user	= JFactory::getUser();
		$userid = $user->id;
		$post   = JRequest::get('post');
	
		$query = "SELECT MAX(id) FROM #__jblance_plan_subscr WHERE user_id = $userid";
		$db->setQuery($query);
		$id_max = $db->loadResult();
	
		$query = "SELECT * FROM #__jblance_plan_subscr WHERE id = ".$db->quote($id_max);
		$db->setQuery($query);
		$last_subscr = $db->loadObject();
	
		if($id_max && $last_subscr->approved == 0){	//if he has some plan and not approved
			$msg = JText::_('COM_JBLANCE_PENDING_SUBSCR_CANCEL_FIRST');
			$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planhistory', false);
			$this->setRedirect($link, $msg);
			return false;
		}
	
		//redirect the user if the plan purchase limit is exceeded (this is server side checking for forgeries)
		$query = "SELECT p.time_limit, SUM(s.access_count) plan_count FROM #__jblance_plan p ".
				 "LEFT JOIN #__jblance_plan_subscr s ON s.plan_id = p.id ".
				 "WHERE s.approved=1 AND s.user_id = $user->id AND p.id = ".$post['plan_id']." ".
				 "GROUP BY p.id";
		$db->setQuery($query);
		$count = $db->loadObject();
	
		if($count->time_limit > 0){
			if($count->plan_count >= $count->time_limit){
				$msg = JText::sprintf('COM_JBLANCE_PLAN_PURCHASE_LIMIT_MESSAGE', $count->time_limit);
				$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd', false);
				$this->setRedirect($link, $msg, 'error');
				return false;
			}
		}
	
		$subscrRow = $this->addSubscription($userid);
		$subscrid = $subscrRow->id;	//this returnid is the subscr id from plan_subscr table
	
		$paymode = $post['gateway'];
		$session = JFactory::getSession();
		$session->set('id', $subscrid, 'upgsubscr');
		if($paymode == 'banktransfer'){
			//send alert to admin and user
			$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
			$jbmail->alertAdminSubscr($subscrid, $userid);
			$jbmail->alertUserSubscr($subscrid, $userid);
		}
	
		$link = JRoute::_('index.php?option=com_jblance&view=membership&layout=check_out&type=plan', false);
		$this->setRedirect($link, $msg);
	}
	
	//11.Add Subscription
	function addSubscription($userid){
		$user	= JFactory::getUser();
		if($user->guest)
			$userid = $userid;
		else
			$userid = $user->id;
	
		// Initialize variables
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$row	= JTable::getInstance('plansubscr', 'Table');
		$post   = JRequest::get('post');
		$config	= JblanceHelper::getConfig();
		
		$taxpercent 	   = $config->taxPercent;
		$invoiceFormatPlan = $config->invoiceFormatPlan;
	
		$session = JFactory::getSession();
		$planid = $session->get('planid', 0, 'register');
		$ugid = $session->get('ugid', 0, 'register');
		$gateway = $session->get('gateway', '', 'register');
	
		//check for session variable - it will be empty during the upgrade
		if(empty($planid) || empty($ugid) || empty($gateway)){
			$planid = $post['plan_id'];
			$jbuser =JblanceHelper::get('helper.user');
			$ugroup = $jbuser->getUserGroupInfo($userid, null);
			$ugid = $ugroup->id;
			$gateway = $post['gateway'];
		}
	
		if(!$row->bind($post)){
			JError::raiseError(500, $row->getError());
		}
		// pre-save checks
		if(!$row->check()){
			JError::raiseError(500, $row->getError());
		}
	
		$row->user_id = $userid;
		$row->plan_id = $planid;
		$row->approved = 0;
		$row->gateway = $gateway;
	
		//calculate the price
		$sql = "SELECT id, days, days_type, price, discount, bonusFund, name, params FROM #__jblance_plan WHERE id =".$db->quote($planid);
		$db->setQuery($sql);
		$plan = $db->loadObject();
	
		if($plan->discount){
			$sql = 'SELECT COUNT(*) AS total FROM #__jblance_plan_subscr WHERE plan_id ='.$db->quote($planid).' AND approved=1 AND user_id='.$db->quote($userid);
			$db->setQuery($sql);
			$total = $db->loadResult();
			if($total > 0){
				$plan->price = $plan->price - (($plan->price / 100) * $plan->discount);
			}
		}
		// auto approve if free on expiry by system or in case of free plan
		if($plan->price <= 0){
			$date = JFactory::getDate();
			$date_approve = $date->toSql();
			$date->modify("+$plan->days $plan->days_type");
			$date_expires = $date->toSql();
	
			$row->date_approval = $date_approve;
			$row->date_expire = $date_expires;
			$row->approved = 1;
			$row->access_count = 1;
			$row->gateway_id = time();
			$row->fund = $plan->bonusFund;
	
			if($row->fund > 0){
				// Update the transaction table
				$transDtl = JText::_('COM_JBLANCE_BUY_SUBSCR').' - '.$plan->name;
				$trans = JblanceHelper::updateTransaction($row->user_id, $transDtl, $plan->bonusFund, 1);
	
				$row->trans_id = $trans->id;
			}
		}
		else {
			$row->fund = $plan->bonusFund;
		}
	
		$row->date_buy = JFactory::getDate()->toSql();
		$row->price	= $plan->price;
		$row->tax_percent = $taxpercent;
		
		//set the project/bid limit details
		$planParams = new JRegistry;
		$planParams->loadString($plan->params);
		
		$row->bids_allowed		= $planParams->get('flBidCount');
		$row->bids_left 		= $planParams->get('flBidCount');
		$row->projects_allowed	= $planParams->get('buyProjectCount');
		$row->projects_left		= $planParams->get('buyProjectCount');
	
		// save the changes
		if (!$row->store()){
			JError::raiseError(500, $row->getError());
		}
		$year = date("Y");
		$time = time();
		//replace the tags
		$tags = array("[ID]", "[USERID]", "[YYYY]", "[TIME]");
		$tagsValues = array("$row->id", "$userid", "$year", "$time");
		$invoiceNo = str_replace($tags, $tagsValues, $invoiceFormatPlan);
		$row->invoiceNo = $invoiceNo;
	
		// save the changes after updating the invoice no
		if(!$row->store()){
			JError::raiseError(500, $row->getError());
		}
		$row->checkin();
		
		return $row;
	}
	
	function processPaymentMethod(){
		//clear the id set in the session after the checkout page
		$session = JFactory::getSession();
		$session->clear('id', 'upgsubscr');
	
		$app  = JFactory::getApplication();
		$id   		= $app->input->get('id', 0, 'int');
		$paymode 	= $app->input->get('paymode', '', 'string');
		$buy  		= $app->input->get('buy', '', 'string');	//either buy fund deposit or plan
		$price 		= $app->input->get('price', 0, 'float');
	
		//do not redirect to payment page in case of free products
		if(empty($price)){
			if($buy == 'plan')
				$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planhistory', false);
			elseif($buy == 'deposit')
				$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=transaction', false);
			$this->setRedirect($link);
			return false;
		}
	
		if($paymode == 'banktransfer'){ // Regular Bank Transfer
			if($buy == 'plan')
				$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=bank_transfer&id='.$id.'&type=plan', false);
			elseif($buy == 'deposit')
				$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=bank_transfer&id='.$id.'&type=deposit', false);
			$this->setRedirect($link);
		}
		else {
			$payconfig = JblanceHelper::getPaymodeInfo($paymode);	//get the payment config of the gateway
			$details = $this->getPaymentDetails($id, $buy);		//get the payment details of the cart/item
			require_once(JPATH_SITE.'/components/com_jblance/gateways/class.'.$paymode.'.php');
			
			$className = $paymode.'_class';
			if(class_exists($className)){ 
				$object = new $className($payconfig, $details);
				$functionName = $paymode.'Payment';
				$object->$functionName();						//call the payment function to submit the form
			}
		}
	}
	
	function returnAfterPayment(){
		$app  	  = JFactory::getApplication();
		
		$rawDataPost = JRequest::get('POST');
		$rawDataGet = JRequest::get('GET');
		$data = array_merge($rawDataGet, $rawDataPost);
		
		$gwayName = $app->input->get('gateway', '', 'string');
		$isValid = false;
		
		if(!empty($gwayName)){
			$payconfig = JblanceHelper::getPaymodeInfo($gwayName);	//get the payment config of the gateway
		
			require_once(JPATH_SITE.'/components/com_jblance/gateways/class.'.$gwayName.'.php');
		
			$className = $gwayName.'_class';
			if(class_exists($className)){
				$object = new $className($payconfig, null);
				$functionName = $gwayName.'Return';
				$result = $object->$functionName($data);						//call the payment function to submit the form
				
				$isValid = $result['success'];
				
				if($isValid){
					$invoice_num = $result['invoice_num'];
					
					// based on the invoice number, identify if it is deposit or plan
					if($invoice_num){
						$result = JblanceHelper::identifyDepositOrPlan($invoice_num);
						//if invoice number is not found
						if($result){
							if($result['type'] ==  'plan')
								$row = JblanceHelper::approveSubscription($result['id']);
							elseif($result['type'] ==  'deposit')
							$row = JblanceHelper::approveFundDeposit($result['id']);
					
							//set the result type and id for redirection
							$app->input->set('buy', $result['type']);
							$app->input->set('oid', $result['id']);
						}
						else {
							$isValid = false;
							$data['jblance_failure_reason'] = JText::_('COM_JBLANCE_INVOICE_NUMBER_NOT_FOUND_PLAN_DEPOSIT_RECORDS');
						}
					}
					else {
						$isValid = false;
						$data['jblance_failure_reason'] = JText::_('COM_JBLANCE_INVOICE_NUMBER_EMPTY_OR_INVALID');
					}
					
					$oid 	  = $app->input->get('oid', 0, 'int');
					$buy  	  = $app->input->get('buy', '', 'string');	//either buy deposit or plan
				}
				else {
					$data['jblance_failure_reason'] = JText::_('COM_JBLANCE_PAYMENT_VERIFICATION_FAILED');
				}
				
				if($isValid){
					$msg = JText::sprintf('COM_JBLANCE_PAYMENT_SUCCESSFUL', ucfirst($gwayName));
					$link = JRoute::_('index.php?option=com_jblance&view=membership&layout=thankpayment&oid='.$oid.'&buy='.$buy, false);
				}
				else {
					$msg = JText::_('COM_JBLANCE_PAYMENT_ERROR').' : '.$data['jblance_failure_reason'];
					$link = JRoute::_('index.php?option=com_jblance&view=membership&layout=thankpayment&type=cancel', false);
				}
		
				$this->setRedirect($link, $msg);
			}	//end of class name
		}	//end of gateway
	}
	
	function saveDepositFund(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		
		// Initialize variables
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$db		= JFactory::getDBO();
		$row	= JTable::getInstance('deposit', 'Table');
		$post   = JRequest::get('post');
		$amount = $app->input->get('amount', 0, 'float');
		$paymode = $app->input->get('gateway', '', 'string');
		
		$config = JblanceHelper::getConfig();
		$invoiceFormatDeposit = $config->invoiceFormatDeposit;
		
		//get the deposit fee details for the payment gateway
		$query = "SELECT * FROM #__jblance_paymode WHERE gwcode=".$db->quote($paymode);
		$db->setQuery($query);
		$gatwayInfo = $db->loadObject();
		$depositFeeFixed  = $gatwayInfo->depositfeeFixed;
		$depositFeePerc = $gatwayInfo->depositfeePerc;
		
		if(!$row->bind($post))
			JError::raiseError(500, $row->getError());

		if(!$row->check())
			JError::raiseError(500, $row->getError());

		$now = JFactory::getDate();
		$row->user_id 	= $user->id;
		$row->date_deposit  = $now->toSql();
		$row->feeFixed  = $depositFeeFixed;
		$row->feePerc   = $depositFeePerc;
		$total			= $row->amount + $depositFeeFixed + ($depositFeePerc*$row->amount)/100;
		$row->total 	= round($total, 2);
		
		// save the changes
		if(!$row->store()){
			JError::raiseError(500, $row->getError());
		}
		
		$year = date("Y");
		$time = time();
		//replace the tags
		$tags = array("[ID]", "[USERID]", "[YYYY]", "[TIME]");
		$tagsValues = array("$row->id", "$user->id", "$year", "$time");
		$invoiceNo = str_replace($tags, $tagsValues, $invoiceFormatDeposit);
		$row->invoiceNo = $invoiceNo;
		
		// save the changes after updating the invoice no
		if(!$row->store()){
			JError::raiseError(500, $row->getError());
		}
		$row->checkin();
		
		if($row->gateway == 'banktransfer'){
			//send deposit fund alert to admin
			$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
			$jbmail->sendAdminDepositFund($row->id);
		}
		
		$link = JRoute::_('index.php?option=com_jblance&view=membership&layout=check_out&id='.$row->id.'&type=deposit', false);
		$this->setRedirect($link, $msg);
	}
	
	function saveEscrow(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		
		// Initialize variables
		$app  = JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$user	= JFactory::getUser();
		$row	= JTable::getInstance('escrow', 'Table');
		$post   = JRequest::get('POST');
		$amount = $app->input->get('amount', 0, 'float');
		$balance = $app->input->get('proj_balance', 0, 'float');
		$recipient = $app->input->get('recipient', '', 'string');
		$recipientInfo = JFactory::getUser($recipient);		//get the recipient info from the recipient's username
		$project_id = $app->input->get('project_id', 0, 'int');
		$reason = $post['reason'];
		
		//check if the recipient info is valid/username exists
		if(empty($recipientInfo)){
			$msg = JText::_('COM_JBLANCE_INVALID_USERNAME');
			$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=escrow', false);
			$this->setRedirect($link, $msg, 'error');
			return false;
		}
		
		//check if the user has enough fund to transfer
		$totalFund = JblanceHelper::getTotalFund($user->id);
		if($totalFund < $amount){
			$msg = JText::_('COM_JBLANCE_BALANCE_INSUFFICIENT_TO_MAKE_PAYMENT');
			$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=escrow', false);
			$this->setRedirect($link, $msg);
			return false;
		}
		
		//redirect the user if he is trying to pay more than the bid amount
		if(($reason == 'full_payment' || $reason == 'partial_payment') && $project_id){
			if($amount > $balance){
				$msg = JText::_('COM_JBLANCE_PAYMENT_OVER_THE_PROJECT_BALANCE');
				$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=escrow', false);
				$this->setRedirect($link, $msg, 'error');
				return false;
			}
		}
		
		//save the escrow
		$post['from_id'] 		= $user->id;
		$post['to_id'] 			= $recipientInfo->id;
		$now 					= JFactory::getDate();
		$post['date_transfer'] 	= $now->toSql();
		
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
		
		if(($reason == 'full_payment' || $reason == 'partial_payment') && $project_id){
			$project	= JTable::getInstance('project', 'Table');
			$project->load($project_id);
			$transDtl_sender = JText::sprintf('COM_JBLANCE_ESCROW_PAYMENT_TO_FOR_PROJECT', $recipient, $project->project_title);
		}
		else {
			$transDtl_sender = JText::sprintf('COM_JBLANCE_ESCROW_PAYMENT_TO', $recipient);
		}
		
		//debit the sender
		$row_trans = JblanceHelper::updateTransaction($user->id, $transDtl_sender, $amount, -1);
		
		//save the tras id to the escrow table
		$row->from_trans_id = $row_trans->id;
		if(!$row->store()){
			JError::raiseError(500, $row->getError());
		}
		$row->checkin();
		
		$msg = JText::_('COM_JBLANCE_ESCROW_PAYMENT_COMPLETED_SUCCESSFULLY');
		$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=managepay', false);
		$this->setRedirect($link, $msg);
		return false;
	}
	
	//12. Release Escrow
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
		
		//Trigger the plugin event to feed the activity - buyer pick freelancer
		JPluginHelper::importPlugin('joombri');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onBuyerReleaseEscrow', array($escrow->from_id, $escrow->to_id, $escrow->project_id));
		
		$msg = JText::_('COM_JBLANCE_ESCROW_PAYMENT_RELEASED_SUCCESSFULLY');
		$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=managepay', false);
		$this->setRedirect($link, $msg);
		return false;
	}
	
	function acceptEscrow(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit('Invalid Token');
		
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$id 	= $app->input->get('id', 0, 'int');
		
		$escrow = JTable::getInstance('escrow', 'Table');
		$escrow->load($id);
		
		$now = JFactory::getDate();
		$escrow->date_accept = $now->toSql();
		$escrow->status = 'COM_JBLANCE_ACCEPTED';
		
		if(!$escrow->check())
			JError::raiseError(500, $escrow->getError());
		
		if(!$escrow->store())
			JError::raiseError(500, $escrow->getError());
		
		$escrow->checkin();
		
		$senderInfo = JFactory::getUser($escrow->from_id);		//get the sender info from the sender userid
		
		$project_id = $escrow->project_id;
		
		//update the paid_status in project table, if the project id is not null
		if(!empty($project_id)){
		
			//get the sum of amount already paid for the project
			$query  = "SELECT SUM(amount) AS amt FROM #__jblance_escrow WHERE project_id=$project_id AND status=".$db->quote('COM_JBLANCE_ACCEPTED');
			$db->setQuery($query);
			$sum = $db->loadResult();
			
			$project	= JTable::getInstance('project', 'Table');
			$project->load($project_id);
			
			$project->paid_amt = $sum;
			
			//get the bid amount of the project
			$query = "SELECT b.amount bidamount FROM #__jblance_project p ".
					 "INNER JOIN #__jblance_bid b ON p.id=b.project_id ".
					 "WHERE p.id=$project_id AND b.status='COM_JBLANCE_ACCEPTED'";
			$db->setQuery($query);
			$bidamount = $db->loadResult();
			
			if($sum == $bidamount){
				$project->paid_status = 'COM_JBLANCE_PYMT_COMPLETE';
			}
			elseif($sum < $bidamount){
				$project->paid_status = 'COM_JBLANCE_PYMT_PARTIAL';
			}
			// save the changes
			if(!$project->store())
				JError::raiseError(500, $resume->getError());
			
			$project->checkin();
			
			$transDtl_receiver = JText::sprintf('COM_JBLANCE_ESCROW_PAYMENT_FROM_FOR_PROJECT', $senderInfo->username, $project->project_title);
		}
		else {
			$transDtl_receiver = JText::sprintf('COM_JBLANCE_ESCROW_PAYMENT_FROM', $senderInfo->username);
		}
		
		//credit the recipient
		$row_trans = JblanceHelper::updateTransaction($escrow->to_id, $transDtl_receiver, $escrow->amount, 1);
		
		//update escrow table with the trans id
		$escrow->to_trans_id = $row_trans->id;
		if(!$escrow->store())
			JError::raiseError(500, $escrow->getError());
		
		$escrow->checkin();
		
		//send escrow pymt accepted to sender
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$jbmail->sendEscrowPaymentAccepted($escrow->id);
		
		//Trigger the plugin event to feed the activity - buyer pick freelancer
		JPluginHelper::importPlugin('joombri');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onFreelancerAcceptEscrow', array($escrow->to_id, $escrow->from_id, $escrow->project_id));
		
		$msg = JText::_('COM_JBLANCE_ESCROW_PAYMENT_ACCEPTED_SUCCESSFULLY');
		$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=managepay', false);
		$this->setRedirect($link, $msg);
		return false;
	}
	
	/* //12. Cancel Escrow payment by buyer before releasing
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
		//JPluginHelper::importPlugin('joombri');
		//$dispatcher = JDispatcher::getInstance();
		//$dispatcher->trigger('onBuyerReleaseEscrow', array($escrow->from_id, $escrow->to_id, $escrow->project_id));
	
		$msg = JText::_('COM_JBLANCE_ESCROW_PAYMENT_CANCELLED_SUCCESSFULLY');
		$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=managepay', false);
		$this->setRedirect($link, $msg);
		return false;
	} */
	
	function saveWithdrawFund(){
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		
		$app  	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$params	= $app->input->get('params', null, 'array');
		$post   = JRequest::get('POST');
		$now 	= JFactory::getDate();
		$row	= JTable::getInstance('withdraw', 'Table');
		
		$config = JblanceHelper::getConfig();
		$invoiceFormatWithdraw = $config->invoiceFormatWithdraw;
		
		//check if the user has enough fund to withdraw
		$totalFund = JblanceHelper::getTotalFund($user->id);
		if($totalFund < $post['amount']){
			$msg = JText::_('COM_JBLANCE_BALANCE_INSUFFICIENT_TO_MAKE_PAYMENT');
			$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=withdrawfund&step=2&gateway='.$post['gateway'], false);
			$this->setRedirect($link, $msg, 'error');
			return false;
		}
		
		$row->user_id = $user->id;
		$row->date_withdraw = $now->toSql();
		$row->finalAmount = $post['amount'] - $post['withdrawFee'];
		
		$registry = new JRegistry();
		$registry->loadArray($params);
		$row->params = $registry->toString();
		unset($post['params']);
		
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
		
		$year = date("Y");
		$time = time();
		//replace the tags
		$tags = array("[ID]", "[USERID]", "[YYYY]", "[TIME]");
		$tagsValues = array("$row->id", "$user->id", "$year", "$time");
		$invoiceNo = str_replace($tags, $tagsValues, $invoiceFormatWithdraw);
		$row->invoiceNo = $invoiceNo;
		
		// save the changes after updating the invoice no
		if(!$row->store()){
			JError::raiseError(500, $row->getError());
		}
		$row->checkin();
		
		//send withdraw request to admin
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$jbmail->sendWithdrawFundRequest($row->id);
		
		$msg = JText::_('COM_JBLANCE_WITHDRAWAL_REQUEST_SENT_SUCCESSFULLY');
		$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=managepay', false);
		$this->setRedirect($link, $msg);
		return false;
	}
	
	//get the payment details that need to be sent to each payment
	function getPaymentDetails($id, $buy){
		$db = JFactory::getDBO();
		$details = array();
		if($buy == 'deposit'){
			$row 	= JTable::getInstance('deposit', 'Table');
			$row->load($id);
	
			$amount = $row->total;
			$taxrate = 0;
			$orderid = $row->id;
			$itemname = JText::_('COM_JBLANCE_DEPOSIT_FUNDS');
			$item_num = $row->id;	// id of the fund deposit
			$invoiceNo = $row->invoiceNo;	// invoice number of the payment
		}
		elseif($buy == 'plan'){
			$row 	= JTable::getInstance('plansubscr', 'Table');
			$row->load($id);
	
			$query = "SELECT * FROM #__jblance_plan WHERE id = ".$row->plan_id;
			$db->setQuery($query);
			$plan = $db->loadObject();
	
			$amount = $row->price;
			$taxrate = $row->tax_percent;
			$orderid = $row->id;
			$itemname = JText::_('COM_JBLANCE_BUY_SUBSCR').' - '.$plan->name;
			$item_num = $row->id;	// subscr id of the plan purchased
			$invoiceNo = $row->invoiceNo;	// invoice number of the payment
		}
		$details['amount'] 	  = $amount;
		$details['taxrate']   = $taxrate;
		$details['orderid']   = $orderid;
		$details['itemname']  = $itemname;
		$details['item_num']  = $item_num;
		$details['invoiceNo'] = $invoiceNo;
		return $details;
	}
}