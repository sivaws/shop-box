<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	models/admproject.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

 jimport('joomla.application.component.model');
 
class JblanceModelAdmproject extends JModelLegacy {
 	function __construct(){
 		parent :: __construct();
 		//$user	= JFactory::getUser();
 	}
 	
 	//Admin Dashboard
 	function getDashboard(){
 		$app = JFactory::getApplication();
 		$db	= JFactory::getDBO();
 	
 		$query = "SELECT COUNT(*) FROM #__users";
 		$db->setQuery($query);
 		$users = $db->loadResult();
 	
 		$query = "SELECT COUNT(*) FROM #__jblance_user";
 		$db->setQuery($query);
 		$jbusers = $db->loadResult();
 	
 		$query = "SELECT COUNT(*) FROM #__jblance_project";
 		$db->setQuery( $query );
 		$projects = $db->loadResult();
 	
 		$return[0] = $users;
 		$return[1] = $jbusers;
 		$return[3] = $projects;
 		return $return;
 	}
 	
 	//2.Project - show
 	function getShowProject(){
 		$app = JFactory::getApplication();
		$db	= JFactory::getDBO();
		$select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
		
		$filter_order     = $app->getUserStateFromRequest('com_jblance_filter_order_sp', 'filter_order', 'p.project_title', 'cmd');
		$filter_order_Dir = $app->getUserStateFromRequest('com_jblance_filter_order_Dir_sp', 'filter_order_Dir', 'asc', 'word');
		$filter_status	  = $app->getUserStateFromRequest('com_jblance_filter_status', 'filter_status', '', 'string');
		$search			  = $app->getUserStateFromRequest('com_jblance_project_search', 'search', '', 'string');
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);
		
		$where = array();
		
		if(isset($search) && $search != ''){
			$searchEscaped = $db->quote( '%'.$db->escape( $search, true ).'%', false );
			$where[] = 'p.project_title LIKE '.$searchEscaped;
		}
		
		$this->setState('filter_order', $filter_order);
		$this->setState('filter_order_Dir', $filter_order_Dir);
		$orderby = $this->_buildContentOrderBy();
		$lists['order_Dir']	= $this->getState('filter_order_Dir');
		$lists['order']     = $this->getState('filter_order');
		$lists['status'] 	= $select->getSelectProjectStatus('filter_status', $filter_status, 'COM_JBLANCE_ALL_STATUS', '', 'onchange="document.adminForm.submit();"');//('filter_status', $filter_status, 0, 'onchange="document.adminForm.submit();"');
		$lists['search'] 	= $search;
		
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
		
		
		if($filter_status != ''){
			$where[] = "status =".$db->quote($filter_status);
		}
		
		$where = (count( $where) ? ' WHERE (' . implode( ') AND (', $where ) . ')' : '' );
		
		$query = "SELECT p.* FROM #__jblance_project p".
		         $where.
				 $orderby;
		$db->setQuery($query);
		$db->execute();//echo $query;
		$total = $db->getNumRows();
		
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );
		
		$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$rows = $db->loadObjectList();
		
		$return[0] = $rows;
		$return[1] = $pageNav;
		$return[2] = $lists;
		return $return;
 		}
 	
 	function getEditProject(){
 		
 		$app  	= JFactory::getApplication();
 		$db		= JFactory::getDBO();
 		$row 	= JTable::getInstance('project', 'Table');
 		$cid 	= $app->input->get('cid', array(), 'array');
 		JArrayHelper::toInteger($cid, array(0)); 		
 		$lists = array();
 		
 		$isNew = (empty($cid)) ? true : false;
 		
 		if(!$isNew)
 			$row->load($cid[0]);
 		
 		//make selection user
 		$query = "SELECT u.id AS value, u.username, name FROM #__jblance_user ju ".
				 "LEFT JOIN #__users u ON ju.user_id=u.id ".
				 "ORDER BY u.username";
 		$db->setQuery($query);
 		$users = $db->loadObjectList();
 		
 		$types = array();
 		$types[] = JHTML::_('select.option', '', '- '.JText::_('COM_JBLANCE_USERNAME_USERID_NAME').' -');
 		foreach($users as $item){
 			$types[] = JHTML::_('select.option', $item->value, sprintf("%s [%d] (%s)", $item->username, $item->value, $item->name));
 		}
 		
 		$lists['userlist'] 	= JHTML::_('select.genericlist', $types, 'publisher_userid', 'class="inputbox required" size="1"', 'value', 'text', $row->publisher_userid ? $row->publisher_userid : '0');
 		
 		//get project files and bid info
 		$projfiles = $bids = array();
 		if(!$isNew){
	 		$query = 'SELECT * FROM #__jblance_project_file WHERE project_id='.$cid[0];
	 		$db->setQuery($query);
	 		$projfiles = $db->loadObjectList();
	 		
	 		$query ="SELECT b.*, u.username FROM #__jblance_bid b ". 
	 				"INNER JOIN #__users u ON b.user_id=u.id ".
	 				"WHERE b.project_id =".$cid[0];
	 		$db->setQuery($query);
	 		$bids = $db->loadObjectList();
 		}
 		
 		$query = "SELECT * FROM #__jblance_custom_field ".
 				 "WHERE published=1 AND field_for=".$db->quote('project')." ".
 				 "ORDER BY ordering";
 		$db->setQuery($query);
 		$fields = $db->loadObjectList();
 		
 		//get the forum list
 		$query = "SELECT * FROM #__jblance_forum ".
 				 "WHERE project_id=".$db->quote($row->id)." ".
 				 "ORDER BY date_post ASC";
 		$db->setQuery($query);//echo $query;
 		$forums = $db->loadObjectList();
 		
 		$return[0] = $row;
 		$return[1] = $projfiles;
 		$return[2] = $bids;
 		$return[3] = $lists;
 		$return[4] = $fields;
 		$return[5] = $forums;
 		return $return;
 		
 	}
 	
 	//4.Employer - show
 	function getShowUser(){
 		$app = JFactory::getApplication();
 		$db	= JFactory::getDBO();
 		$select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 	
 		$filter_order     = $app->getUserStateFromRequest('com_jblance_filter_order_user', 'filter_order', 'u.id', 'cmd');
 		$filter_order_Dir = $app->getUserStateFromRequest('com_jblance_filter_order_Dir_user', 'filter_order_Dir', 'asc', 'word');
 		$search			  = $app->getUserStateFromRequest('com_jblance_user_search', 'search', '', 'string');
 		$ug_id	 		  = $app->getUserStateFromRequest('com_jblance_filter_user_ug_id', 'ug_id', '', 'int');
 		if (strpos($search, '"') !== false) {
 			$search = str_replace(array('=', '<'), '', $search);
 		}
 		$search = JString::strtolower($search);
 	
 		$this->setState('filter_order', $filter_order);
 		$this->setState('filter_order_Dir', $filter_order_Dir);
 		$orderby = $this->_buildContentOrderBy();
 		$lists['order_Dir'] =  $this->getState( 'filter_order_Dir' );
 		$lists['order']     =  $this->getState( 'filter_order' );
 		$lists['search'] 	= $search;
 		$lists['ug_id'] = $this->getSelectUserGroupsWithJoomla('ug_id', $ug_id, 'COM_JBLANCE_ALL_USERS', '', 'onchange="document.adminForm.submit();"');
 	
 		$where = array();
 	
 		if(isset($search) && $search != ''){
 			$searchEscaped = $db->quote( '%'.$db->escape( $search, true ).'%', false );
 			$where[] = 'u.name LIKE '.$searchEscaped.' OR u.username LIKE '.$searchEscaped.' OR ju.biz_name LIKE '.$searchEscaped;
 		}
 		if($ug_id == -1) $where[] = 'ju.ug_id IS NULL';
 		if($ug_id > 0) 	$where[] = 'ju.ug_id ='.$db->quote($ug_id);
 	
 		$where = (count( $where) ? ' WHERE (' . implode( ') AND (', $where ) . ')' : '' );
 	
 		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
 		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
 	
 		$query = "SELECT u.*,ju.biz_name FROM #__users u".
 				" LEFT JOIN #__jblance_user ju ON ju.user_id = u.id".
 				$where.
 				$orderby;//echo $query;
 		$db->setQuery($query);
 		$db->execute();
 		$total = $db->getNumRows();
 	
 		jimport('joomla.html.pagination');
 		$pageNav = new JPagination($total, $limitstart, $limit);
 	
 		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
 		$rows2 = $db->loadObjectList();
 	
 		$rows = null;
 		if(count($rows2)){
 			$i = 0;
 			foreach($rows2 as $row){
 				$rows[$i] = new stdClass();
 				$total_fund = JblanceHelper::getTotalFund($row->id);
 	
 				$rows[$i] = $row;
 				$rows[$i]->total_fund = $total_fund;
 				$i++;
 			}
 		}
 		$return[0] = $rows;
 		$return[1] = $pageNav;
 		$return[2] = $lists;
 	
 		return $return;
 	}
 	
 	//4.Employer - edit
 	function getEditUser(){
 		$app 	= JFactory::getApplication();
 		$db		= JFactory::getDBO();
 		$user 	= JFactory::getUser();
 		$cid 	= $app->input->get('cid', array(), 'array');
 		JArrayHelper::toInteger($cid, array(0));
 	
 		$post_ugid =  $app->input->get('ug_id', 0, 'int');
 	
 		/* //if it is new, cid[0] = 0
 		$isNew = ($cid[0] == 0)? true : false; */
 	
 		$hasJBProfile = JblanceHelper::hasJBProfile($cid[0]);	//check if the user has JoomBri profile
 	
 		$query = "SELECT u.*, ju.biz_name,ju.id AS jb_id,ju.user_id,ju.ug_id,ju.picture,ju.thumb,ju.rate,ju.id_category FROM #__users u".
 				" LEFT JOIN #__jblance_user ju ON ju.user_id = u.id".
 				" WHERE u.id=".$cid[0];
 		$db->setQuery($query);//echo $query;
 		$row = $db->loadObject();
 	
 		$disabled = '';
 	
 		//make selection user
 		$query = "SELECT id AS value, username, name FROM #__users ORDER BY username";
 		$db->setQuery($query);
 		$users = $db->loadObjectList();
 	
 		$types = array();
 		$types[] = JHTML::_('select.option', '', '- '.JText::_('COM_JBLANCE_USERNAME_USERID_NAME').' -' );
 		foreach($users as $item){
 			$types[] = JHTML::_('select.option', $item->value, sprintf("%s [%d] (%s)", $item->username, $item->value, $item->name) );
 		}
 	
 		/*if(!$isNew)
 		 $disabled ='disabled';*/
 	
 		$lists 	= JHTML::_('select.genericlist', $types, 'username', 'class="inputbox required" size="1" '.$disabled.'', 'value', 'text', /*$row->user_id ? $row->user_id : */$cid[0]);
 	
 		//get the list of user groups
 		$query = "SELECT id AS value, name FROM #__jblance_usergroup ORDER BY name";
 		$db->setQuery($query);
 		$groups = $db->loadObjectList();
 	
 		$types = array();
 		$types[] = JHTML::_('select.option',  '', '- '.JText::_('COM_JBLANCE_SELECT_USERGROUP').' -');
 		foreach($groups as $item){
 			$types[] = JHTML::_('select.option', $item->value, $item->name);
 		}
 	
 		if(!$hasJBProfile)
 			$event = 'onchange="document.adminForm.submit();"';
 		else
 			$event = '';
 	
 		$grpLists 	= JHTML::_('select.genericlist', $types, 'ug_id', "class='inputbox required' size='1' $disabled $event", 'value', 'text', $row->ug_id ? $row->ug_id : $post_ugid);
 	
 		//get the transaction list of the user
 		$query = "SELECT * FROM #__jblance_transaction ".
 				" WHERE user_id =".$db->quote($row->user_id)." ORDER BY date_trans DESC";
 		$db->setQuery($query);
 		$trans = $db->loadObjectList();
 	
 		if($hasJBProfile){
 			$jbuser = JblanceHelper::get('helper.user');
 			$ugroup = $jbuser->getUserGroupInfo($row->user_id, null);
 			$ugid = $ugroup->id;
 		}
 		else
 			$ugid = $post_ugid;
 	
 		$jbfields = JblanceHelper::get('helper.fields');		// create an instance of the class fieldsHelper
 		$fields   = $jbfields->getUserGroupTypeFields($ugid);
 	
 		$return[0] = $row;
 		$return[1] = $lists;
 		$return[2] = $grpLists;
 		$return[3] = $trans;
 		$return[4] = $fields;
 	
 		return $return;
 	}
 	
 	//6.Subscription - show
 	function getShowSubscr(){
 	
 		$app = JFactory::getApplication();
 		$db	= JFactory::getDBO();
 	
 		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
 		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
 	
 		$filter_order     = $app->getUserStateFromRequest('com_jblance_filter_order_subscr', 'filter_order', 's.id', 'cmd');
 		$filter_order_Dir = $app->getUserStateFromRequest('com_jblance_filter_order_Dir_subscr', 'filter_order_Dir', 'desc', 'word');
 		$subscr_status 	  = $app->getUserStateFromRequest('com_jblance_filter_subscr_status', 'subscr_status', '', 'string');
 		$subscr_plan 	  = $app->getUserStateFromRequest('com_jblance_filter_subscr_plan', 'subscr_plan', 0, 'string');
 		$ug_id	 		  = $app->getUserStateFromRequest('com_jblance_filter_ugroup_id', 'ug_id', '', 'int');
 		$inv_num 		  = $app->getUserStateFromRequest('com_jblance_sinv_num', 'sinv_num', '', 'string');
 		$user_id 		  = $app->getUserStateFromRequest('com_jblance_subscr_userid', 'suser_id', '', 'string');
 		$subscr_id 		  = $app->getUserStateFromRequest('com_jblance_subscr_subscrid', 'ssubscr_id', '', 'string');
 	
 		$this->setState('filter_order', $filter_order);
 		$this->setState('filter_order_Dir', $filter_order_Dir);
 		$orderby = $this->_buildContentOrderBy();
 		$lists['order_Dir'] =  $this->getState('filter_order_Dir');
 		$lists['order']     =  $this->getState('filter_order');
 		$lists['sinv_num'] = $inv_num;
 		$lists['suser_id'] = $user_id;
 		$lists['ssubscr_id'] = $subscr_id;
 		$lists['subscr_status'] = $this->getSelectSubscrStatus('subscr_status', $subscr_status, 0, 'onchange="document.adminForm.submit();"');
 		$lists['subscr_plan'] = $this->getSelectPlan('subscr_plan', $subscr_plan, 0, $ug_id, 'onchange="document.adminForm.submit();"');
 	
 		$select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 		$lists['ug_id'] = $select->getSelectUserGroups('ug_id', $ug_id, 'COM_JBLANCE_SELECT_USERGROUP', '', 'onchange="document.adminForm.submit();"');
 	
 		$where = array();
 		if($inv_num != '')	 $where[] = 's.invoiceNo ='.$db->quote($inv_num);
 		if($user_id > 0)	 $where[] = 's.user_id ='.$user_id;
 		if($subscr_id > 0) 	 $where[] = 's.id ='.$subscr_id;
 		if($subscr_plan > 0) $where[] = 's.plan_id ='.$subscr_plan;
 		if($ug_id != '') 	 $where[] = 'p.ug_id ='.$db->quote($ug_id);
 		if($subscr_status != ''){
 			if($subscr_status == 3)
 				$where[] = '(TO_DAYS(s.date_expire) - TO_DAYS(NOW())) < 0';
 			else
 				$where[] = 's.approved = '.$subscr_status;
 		}
 	
 		$where = (count( $where) ? ' WHERE (' . implode( ') AND (', $where ) . ')' : '' );
 	
 		$query = "SELECT s.*, u.name AS uname, u.email, u.id AS uid, p.name,(TO_DAYS(s.date_expire) - TO_DAYS(NOW())) AS days, p.id AS sid
 				  FROM #__jblance_plan_subscr AS s
 				  LEFT JOIN #__jblance_plan AS p ON p.id = s.plan_id
 				  LEFT JOIN #__users AS u ON u.id = s.user_id".
 				  $where.
 				  $orderby;
 		$db->setQuery($query);
 		$db->execute();
 		$total = $db->getNumRows();
 	
 		jimport('joomla.html.pagination');
 		$pageNav = new JPagination($total, $limitstart, $limit);
 	
 		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
 		$rows = $db->loadObjectList();
 	
 		$return[0] = $rows;
 		$return[1] = $pageNav;
 		$return[2] = $lists;
 	
 		return $return;
 	}
 	
 	//6.Subscription - edit
 	function getEditSubscr(){
 		$app 	= JFactory::getApplication();
 		$db		= JFactory::getDBO();
 		$row 	= JTable::getInstance('plansubscr', 'Table');
 		$select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 		$cid 	= $app->input->get('cid', array(), 'array');
 		JArrayHelper::toInteger($cid, array(0));
 	
 		$isNew = (empty($cid)) ? true : false;
 		if(!$isNew)
 			$row->load($cid[0]);
 	
 		$disabled = '';
 		if(!$isNew)
 			$disabled = 'disabled';
 	
 		$ug_id = $app->getUserStateFromRequest('com_jblance_filter_ugroup_id', 'ug_id', '', 'int');
 		$rowugid = $row->planJoin($row->id);
 		$def_ug_id = !empty($rowugid) ? $rowugid : $ug_id;
 		$attribs = "class='inputbox required' size='1' $disabled";
 		$lists['ug_id'] = $select->getSelectUserGroups('ug_id', $def_ug_id, 'COM_JBLANCE_SELECT_USERGROUP', $attribs, 'onchange="document.adminForm.submit();"');
 	
 		//get the subscribtion status
 		$types = array();
 		$types[] = JHTML::_('select.option', '', '- '.JText::_('COM_JBLANCE_SELECT_SUBSCR_STATUS').' -');
 		$types[] = JHTML::_('select.option', '0', JText::_('COM_JBLANCE_UNAPPROVED'));
 		$types[] = JHTML::_('select.option', '1', JText::_('COM_JBLANCE_APPROVED'));
 		$types[] = JHTML::_('select.option', '2', JText::_('COM_JBLANCE_CANCELLED'));
 		$lists['status'] = JHTML::_('select.genericlist', $types, 'approved', "class='inputbox required' size='1'", 'value', 'text', $row->approved);
 	
 		$user_where = $plan_where = 'true';
 		if(!empty($ug_id)){
 			$user_where = ' ju.ug_id = '.$ug_id;
 			$plan_where = ' p.ug_id = '.$ug_id;
 		}
 	
 		//make selection user
 		$query = "SELECT u.id AS value, u.username, u.name FROM #__jblance_user ju".
 				" LEFT JOIN #__users u ON ju.user_id = u.id WHERE ".$user_where.
 				" ORDER BY u.id";
 		$db->setQuery($query);
 		$user_rows = $db->loadObjectList();
 	
 		$types = array();
 		$types[] = JHTML::_('select.option', '', '- '.JText::_('COM_JBLANCE_USERNAME_USERID_NAME').' -' );
 		foreach($user_rows as $item){
 			$types[] = JHTML::_('select.option', $item->value, sprintf("[%d] %s (%s)", $item->value, $item->username, $item->name));
 		}
 		$users 	= JHTML::_('select.genericlist', $types, 'user_id', 'class="inputbox required" size="1" '.$disabled.'', 'value', 'text', $row->user_id ? $row->user_id : '0');
 	
 		//make plans selection list
 		$query = "SELECT * FROM #__jblance_plan p WHERE $plan_where AND p.published = 1 ORDER BY p.id ASC";
 		$db->setQuery($query);
 		$plan_rows = $db->loadObjectList();
 	
 		$types = array();
 		$types[] = JHTML::_('select.option', '', '- '.JText::_('COM_JBLANCE_SELECT_PLAN').' -');
 		foreach($plan_rows as $item){
 			$types[] = JHTML::_('select.option', $item->id, sprintf("[%d] %s", $item->id, $item->name));
 		}
 		$plans 	= JHTML::_('select.genericlist', $types, 'plan_id', 'class="inputbox required" size="1" '.$disabled.'', 'value', 'text', $row->plan_id ? $row->plan_id : '0');
 	
 		$return[0] = $row;
 		$return[1] = $users;
 		$return[2] = $plans;
 		$return[3] = $lists;
 		return $return;
 	}
 	
 	//3.Deposit - show
 	function getShowDeposit(){
 		$app = JFactory::getApplication();
 		$db	= JFactory::getDBO();
 	
 		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
 		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
 		$gateways 	= $app->getUserStateFromRequest('com_jblance.cgateways', 'cgateways', '', 'string');
 		$status 	= $app->getUserStateFromRequest('com_jblance.cstatus', 'cstatus', '', 'string');
 		$inv_num 	= $app->getUserStateFromRequest('com_jblance.cinv_num', 'cinv_num', '', 'string');
 		$user_id 	= $app->getUserStateFromRequest('com_jblance.cuser_id', 'cuser_id', '', 'string');
 		$credit_id 	= $app->getUserStateFromRequest('com_jblance.ccredit_id', 'ccredit_id', '', 'string');
 	
 		$lists['cinv_num'] = $inv_num;
 		$lists['cuser_id'] = $user_id;
 		$lists['ccredit_id'] = $credit_id;
 		$lists['cgateways'] = $this->getSelectPaymode('cgateways', $gateways, 'onchange="document.adminForm.submit();"');
 		$lists['cstatus'] = $this->getSelectDepositStatus('cstatus', $status, 'onchange="document.adminForm.submit();"');
 	
 		$where = array();
 		if($inv_num != '') 	$where[] = 'd.invoiceNo ='.$db->quote($inv_num);
 		if($user_id > 0) 	$where[] = 'd.user_id ='.$user_id;
 		if($credit_id > 0) 	$where[] = 'd.id ='.$credit_id;
 		if($gateways != '') $where[] = 'd.gateway ='.$db->quote($gateways);
 		if($status != '') 	$where[] = 'd.approved  = '.$db->quote($status);
 	
 		$where = (count( $where) ? ' WHERE (' . implode( ') AND (', $where ) . ')' : '' );
 	
 		$query = "SELECT d.*,u.name FROM #__jblance_deposit d".
 				" LEFT JOIN #__users u ON d.user_id = u.id".$where.
 				" ORDER BY d.date_deposit DESC";
 		$db->setQuery($query);
 		$db->execute();
 		$total = $db->getNumRows();
 	
 		jimport('joomla.html.pagination');
 		$pageNav = new JPagination($total, $limitstart, $limit);
 	
 		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
 		$rows = $db->loadObjectList();
 	
 		$return[0] = $rows;
 		$return[1] = $pageNav;
 		$return[2] = $lists;
 	
 		return $return;
 	}
 	
 	//3.Withdraw - show
 	function getShowWithdraw(){
 		$app = JFactory::getApplication();
 		$db	= JFactory::getDBO();
 		
 		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
 		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
 		$status 	= $app->getUserStateFromRequest('com_jblance.cstatus', 'cstatus', '', 'string');
 		$inv_num 	= $app->getUserStateFromRequest('com_jblance.cinv_num', 'cinv_num', '', 'string');
 		$user_id 	= $app->getUserStateFromRequest('com_jblance.cuser_id', 'cuser_id', '', 'string');
 		$credit_id 	= $app->getUserStateFromRequest('com_jblance.ccredit_id', 'ccredit_id', '', 'string');
 		
 		$lists['cinv_num'] = $inv_num;
 		$lists['cuser_id'] = $user_id;
 		$lists['ccredit_id'] = $credit_id;
 		$lists['cstatus'] = $this->getSelectDepositStatus('cstatus', $status, 'onchange="document.adminForm.submit();"');
 		
 		$where = array();
 		if($inv_num != '') 	$where[] = 'w.invoiceNo ='.$db->quote($inv_num);
 		if($user_id > 0) 	$where[] = 'w.user_id ='.$user_id;
 		if($credit_id > 0) 	$where[] = 'w.id ='.$credit_id;
 		if($status != '') 	$where[] = 'w.approved  = '.$db->quote($status);
 		
 		$where = (count( $where) ? ' WHERE (' . implode( ') AND (', $where ) . ')' : '' );
 		
 		$query = "SELECT w.*,u.name FROM #__jblance_withdraw w".
 				" LEFT JOIN #__users u ON w.user_id = u.id".$where.
 				" ORDER BY w.date_withdraw DESC";
 		$db->setQuery($query);
 		$db->execute();
 		$total = $db->getNumRows();
 		
 		jimport('joomla.html.pagination');
 		$pageNav = new JPagination($total, $limitstart, $limit);
 		
 		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
 		$rows = $db->loadObjectList();
 		
 		$return[0] = $rows;
 		$return[1] = $pageNav;
 		$return[2] = $lists;
 		
 		return $return;
 	}
 	
 	function getShowsEscrow(){
 		$app = JFactory::getApplication();
 		$db	= JFactory::getDBO();
 		
 		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
 		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
 		
 		$query = "SELECT e.*,s.name sender,r.name receiver,p.project_title FROM #__jblance_escrow e ".
				 "LEFT JOIN #__users s ON e.from_id = s.id ".
				 "LEFT JOIN #__users r ON e.to_id = r.id ".
				 "LEFT JOIN #__jblance_project p ON e.project_id=p.id ".
				 "ORDER BY e.date_transfer DESC";//echo $query;
 		$db->setQuery($query);
 		$db->execute();
 		$total = $db->getNumRows();
 		
 		jimport('joomla.html.pagination');
 		$pageNav = new JPagination($total, $limitstart, $limit);
 		
 		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
 		$rows = $db->loadObjectList();
 		
 		$return[0] = $rows;
 		$return[1] = $pageNav;
 		return $return;
 	}
 	
 	function getShowSummary(){
 		
 		$app = JFactory::getApplication();
 		$db	= JFactory::getDBO();
 		$event = 'onchange="document.adminForm.submit();"';
 		$curr_yr = (int)date("Y");
 		
 		$search_month 	= $app->getUserStateFromRequest('com_jblance.search_month', 'search_month', '0', 'string');
 		$search_year 	= $app->getUserStateFromRequest('com_jblance.search_year', 'search_year', $curr_yr, 'int');
 		
 		//get month options
 		$types = array();
 		$types[] = JHTML::_('select.option', '0', '- '.JText::_('JALL').' -');
 		$types[] = JHTML::_('select.option', '1', JText::_('JANUARY_SHORT'));
 		$types[] = JHTML::_('select.option', '2', JText::_('FEBRUARY_SHORT'));
 		$types[] = JHTML::_('select.option', '3', JText::_('MARCH_SHORT'));
 		$types[] = JHTML::_('select.option', '4', JText::_('APRIL_SHORT'));
 		$types[] = JHTML::_('select.option', '5', JText::_('MAY_SHORT'));
 		$types[] = JHTML::_('select.option', '6', JText::_('JUNE_SHORT'));
 		$types[] = JHTML::_('select.option', '7', JText::_('JULY_SHORT'));
 		$types[] = JHTML::_('select.option', '8', JText::_('AUGUST_SHORT'));
 		$types[] = JHTML::_('select.option', '9', JText::_('SEPTEMBER_SHORT'));
 		$types[] = JHTML::_('select.option', '10', JText::_('OCTOBER_SHORT'));
 		$types[] = JHTML::_('select.option', '11', JText::_('NOVEMBER_SHORT'));
 		$types[] = JHTML::_('select.option', '12', JText::_('DECEMBER_SHORT'));
 		$lists['search_month'] = JHTML::_('select.genericlist', $types, 'search_month', "class='inputbox' size='1' $event", 'value', 'text', $search_month);
 		
 		//get year options
 		$types = array();
 		$now = (int)date("Y");
 		for($z = $now; $z >= $now-10 ; $z--){
 			$types[] = JHTML::_('select.option', $z, $z);
 		}
 		$lists['search_year'] = JHTML::_('select.genericlist', $types, 'search_year', "class='inputbox' size='1' $event", 'value', 'text', $search_year);
 		
 		if($search_month > 0){
 			$where_d[] = "MONTH(date_deposit) = $search_month AND YEAR(date_deposit) = $search_year";
 			$where_w[] = "MONTH(date_withdraw) = $search_month AND YEAR(date_withdraw) = $search_year";
 			$where_p[] = "MONTH(create_date) = $search_month AND YEAR(create_date) = $search_year";
 			$where_s[] = "MONTH(date_buy) = $search_month AND YEAR(date_buy) = $search_year";
 		}
 		else {
 			$where_d[] = "YEAR(date_deposit) = $search_year";
 			$where_w[] = "YEAR(date_withdraw) = $search_year";
 			$where_p[] = "YEAR(create_date) = $search_year";
 			$where_s[] = "YEAR(date_buy) = $search_year";
 		}
 		
 		$where_d = (count( $where_d) ? ' (' . implode( ') AND (', $where_d ) . ')' : '' );
 		$where_w = (count( $where_w) ? ' (' . implode( ') AND (', $where_w ) . ')' : '' );
 		$where_p = (count( $where_p) ? ' (' . implode( ') AND (', $where_p ) . ')' : '' );
 		$where_s = (count( $where_s) ? ' (' . implode( ') AND (', $where_s ) . ')' : '' );
 		
 		$query = "SELECT gateway, SUM(feeFixed+feePerc) AS profit FROM #__jblance_deposit ".
 				 "WHERE approved=1 AND".$where_d.
 				 " GROUP BY gateway";
 		$db->setQuery($query);
 		$deposits = $db->loadObjectList();
 		
 		$query = "SELECT gateway, SUM(withdrawFee) AS profit FROM #__jblance_withdraw ".
 				 "WHERE approved=1 AND".$where_w.
 				 " GROUP BY gateway";
 		$db->setQuery($query);
 		$withdraws = $db->loadObjectList();
 		
 		$query = "SELECT SUM(profit) AS profit FROM #__jblance_project ".
 				 "WHERE status='COM_JBLANCE_CLOSED' AND".$where_p;
 		$db->setQuery($query);
 		$project = $db->loadResult();
 		
 		$query = "SELECT gateway, SUM(price) AS profit FROM #__jblance_plan_subscr ".
 				 "WHERE approved=1 AND".$where_s.
 		 		 " GROUP BY gateway";//echo $query;
 		$db->setQuery($query);
 		$subscrs = $db->loadObjectList();
 		
 		$return[0] = $deposits;
 		$return[1] = $withdraws;
 		$return[2] = $project;
 		$return[3] = $subscrs;
 		$return[4] = $lists;
 		
 		return $return;
 	}
 	
 	function getShowReporting(){
 		$app = JFactory::getApplication();
 		$db	= JFactory::getDBO();
 		
 		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
 		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
 		
 		$query = "SELECT r.*, (SELECT COUNT(*) FROM #__jblance_report_reporter rr WHERE r.id=rr.report_id) reporter FROM #__jblance_report r ORDER BY date_created DESC";//echo $query;
 		$db->setQuery($query);
 		$db->execute();
 		$total = $db->getNumRows();
 		
 		jimport('joomla.html.pagination');
 		$pageNav = new JPagination($total, $limitstart, $limit);
 		
 		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
 		$rows = $db->loadObjectList();
 		
 		$return[0] = $rows;
 		$return[1] = $pageNav;
 		return $return;
 	}
 	
 	function getDetailReporting(){
 		$app = JFactory::getApplication();
 		$db	= JFactory::getDBO();
 		$cid 	= $app->input->get('cid', array(), 'array');
 		JArrayHelper::toInteger($cid, array(0));
 		
 		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
 		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
 		
 		$query = "SELECT * FROM #__jblance_report_reporter rr WHERE rr.report_id=$cid[0] ORDER BY date_created DESC";//echo $query;
 		$db->setQuery($query);
 		$db->execute();
 		$total = $db->getNumRows();
 		
 		jimport('joomla.html.pagination');
 		$pageNav = new JPagination($total, $limitstart, $limit);
 		
 		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
 		$rows = $db->loadObjectList();
 		
 		$return[0] = $rows;
 		$return[1] = $pageNav;
 		return $return;
 	}
 	
 	function getManageMessage(){
 		$app = JFactory::getApplication();
 		$db	= JFactory::getDbo();
 		$cid 	= $app->input->get('cid', array(), 'array');
 		JArrayHelper::toInteger($cid, array(0));
 		
 		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
 		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
 		
 		$search		= $app->getUserStateFromRequest('com_jblance_message_search', 'search', '', 'string');
 		if(strpos($search, '"') !== false){
 			$search = str_replace(array('=', '<'), '', $search);
 		}
 		$search = JString::strtolower($search);
 		$lists['search'] = $search;
 		
 		$where = $whereSub = array();
 		
 		if(isset($search) && $search != ''){
 			$searchEscaped = $db->quote( '%'.$db->escape( $search, true ).'%', false );
 			$where[] = 'm.subject LIKE '.$searchEscaped.' OR m.message LIKE '.$searchEscaped;
 			$whereSub[] = 'm1.subject LIKE '.$searchEscaped.' OR m1.message LIKE '.$searchEscaped;
 		}
 		$where[] = 'm.parent=0';
 		$where[] = 'm.deleted=0';
 		$whereSub[] = 'm1.deleted=0';
 		
 		$where = (count($where) ? '  (' . implode( ') AND (', $where ) . ')' : '' );
 		$whereSub = (count($whereSub) ? ' WHERE (' . implode( ') AND (', $whereSub ) . ')' : '' );
 		
 		$query = "SELECT * FROM #__jblance_message m ".
				 "WHERE id IN (SELECT parent FROM #__jblance_message m1 $whereSub) OR ($where) ".
				 "ORDER BY m.date_sent DESC";//echo $query;
 		$db->setQuery($query);
 		$db->execute();
 		$total = $db->getNumRows();
 		
 		jimport('joomla.html.pagination');
 		$pageNav = new JPagination($total, $limitstart, $limit);
 		
 		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
 		$rows = $db->loadObjectList();
 		
 		//retrieve the particular message thread
 		$threads = null;
 		if(!empty($cid[0])){
 			$query = 'SELECT * FROM #__jblance_message WHERE (id='.$cid[0].' OR parent='.$cid[0].') AND deleted=0 ORDER BY id';//echo $query;
 			$db->setQuery($query);
 			$threads = $db->loadObjectList();
 		}
 		
 		$return[0] = $rows;
 		$return[1] = $pageNav;
 		$return[2] = $threads;
 		$return[3] = $lists;
 		return $return;
 	}
 	
 	function getInvoice(){
 		$app 	= JFactory::getApplication();
 		$user	= JFactory::getUser();
 		$id 	= $app->input->get('id', 0, 'int');
 		$type 	= $app->input->get('type', '', 'string');
 		$db 	= JFactory::getDBO();
 		
 		$lang = JFactory::getLanguage();
 		$lang->load('com_jblance', JPATH_SITE);
 	
 		if($type == 'plan'){
 			$query = "SELECT ps.*,ps.date_buy AS invoiceDate,p.name AS planname,u.email,u.name,ju.biz_name FROM #__jblance_plan_subscr ps ".
 					"LEFT JOIN #__users u ON ps.user_id=u.id ".
 					"LEFT JOIN #__jblance_plan p ON p.id=ps.plan_id ".
 					"LEFT JOIN #__jblance_user ju ON ju.user_id=ps.user_id ".
 					"WHERE ps.id=".$db->quote($id);//." AND ps.user_id=".$db->quote($user->id);
 		}
 		elseif($type == 'deposit'){
 			$query = "SELECT d.*,d.date_deposit AS invoiceDate,u.email,u.name,ju.biz_name FROM #__jblance_deposit d ".
 					"LEFT JOIN #__users u ON d.user_id=u.id ".
 					"LEFT JOIN #__jblance_user ju ON ju.user_id=d.user_id ".
 					"WHERE d.id=".$db->quote($id);//." AND d.user_id=".$db->quote($user->id);
 		}
 		elseif($type == 'withdraw'){
 			$query = "SELECT w.*,w.date_withdraw AS invoiceDate,u.email,u.name,ju.biz_name FROM #__jblance_withdraw w ".
 					"LEFT JOIN #__users u ON w.user_id=u.id ".
 					"LEFT JOIN #__jblance_user ju ON ju.user_id=w.user_id ".
 					"WHERE w.id=".$db->quote($id);//." AND w.user_id=".$db->quote($user->id);
 		}
 		/* elseif($type == 'project'){
 			$usertype 	= $app->input->get('usertype', '', 'string');
 			if($usertype == 'freelancer'){
 				$query = "SELECT p.*, p.accept_date AS invoiceDate,p.lancer_commission AS commission_amount,u.email,u.name,ju.biz_name FROM #__jblance_project p ".
 						"LEFT JOIN #__users u ON p.assigned_userid=u.id ".
 						"LEFT JOIN #__jblance_user ju ON ju.user_id=p.assigned_userid ".
 						"WHERE p.id=".$db->quote($id)." AND p.assigned_userid=".$db->quote($user->id);
 			}
 			elseif($usertype == 'buyer'){
 				$query = "SELECT p.*, p.accept_date AS invoiceDate,p.buyer_commission AS commission_amount,u.email,u.name,ju.biz_name FROM #__jblance_project p ".
 						"LEFT JOIN #__users u ON p.publisher_userid=u.id ".
 						"LEFT JOIN #__jblance_user ju ON ju.user_id=p.publisher_userid ".
 						"WHERE p.id=".$db->quote($id)." AND p.publisher_userid=".$db->quote($user->id);
 			}
 		} */
 	
 		$db->setQuery($query);//echo $query;
 		$row = $db->loadObject();
 	
 		$return[0] = $row;
 		//$return[1] = $billingAddress;
 		return $return;
 	}
 	
 	/* Misc Functions */
 	function _buildContentOrderBy(){
 		$app = JFactory::getApplication();
 	
 		$orderby = '';
 		$filter_order     = $this->getState('filter_order');
 		$filter_order_Dir = $this->getState('filter_order_Dir');
 	
 		/* Error handling is never a bad thing*/
 		if(!empty($filter_order) && !empty($filter_order_Dir) ){
 			$orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
 		}
 	
 		return $orderby;
 	}
 	
 	//20.getSelectUserGroups
 	function getSelectUserGroupsWithJoomla($var, $default, $title, $attribs, $event){
 		$db	= JFactory::getDBO();
 	
 		//if attribs is empty, then set a default one.
 		if(empty($attribs))
 			$attribs = 'class="inputbox" size="1"';
 	
 		$query = 'SELECT id AS value, name AS text FROM `#__jblance_usergroup` WHERE published=1 ORDER BY ordering';
 		$db->setQuery($query);
 		$groups = $db->loadObjectList();
 	
 		$types[] = JHTML::_('select.option', '', '- '.JText::_($title).' -');
 		$types[] = JHTML::_('select.option', '-1', JText::_('COM_JBLANCE_JOOMLA_USERS'));
 		foreach($groups as $item){
 			$types[] = JHTML::_('select.option', $item->value, JText::_($item->text));
 		}
 	
 		$lists 	= JHTML::_('select.genericlist', $types, $var, "$attribs $event", 'value', 'text', $default);
 		return $lists;
 	}
 	
 	//8.getSelectSubscrStatus
 	function getSelectSubscrStatus($var, $default, $disabled, $event){
 		$option = '';
 		if($disabled == 1)
 			$option = 'disabled';
 	
 		$types[] = JHTML::_('select.option', '', '- '.JText::_('COM_JBLANCE_SELECT_SUBSCR_STATUS').' -');
 		$types[] = JHTML::_('select.option', '0', JText::_('COM_JBLANCE_UNAPPROVED'));
 		$types[] = JHTML::_('select.option', '1', JText::_('COM_JBLANCE_APPROVED'));
 		$types[] = JHTML::_('select.option', '2', JText::_('COM_JBLANCE_CANCELLED'));
 		$types[] = JHTML::_('select.option', '3', JText::_('COM_JBLANCE_EXPIRED'));
 	
 		$lists 	 = JHTML::_('select.genericlist', $types, $var, "class=\"inputbox\" size=\"1\" $option $event", 'value', 'text', $default );
 	
 		return $lists;
 	}
 	
 	//19.getSelectPlan
 	function getSelectPlan($var, $default, $disabled, $ug_id, $event){
 		$db	= JFactory::getDBO();
 	
 		$option = '';
 		if($disabled == 1)
 			$option = 'disabled';
 	
 		$where = '';
 		if($ug_id)
 			$where = 'p.ug_id='.$ug_id.' AND';
 	
 		//make selection plans
 		$query = "SELECT id as value, name as text FROM #__jblance_plan p WHERE $where p.published = 1 ORDER BY p.ordering";
 		$db->setQuery($query);
 		$plans = $db->loadObjectList();
 	
 		$types[] = JHTML::_('select.option', '0', '- '.JText::_('COM_JBLANCE_SELECT_PLAN').' -');
 		foreach( $plans as $item ){
 			$types[] = JHTML::_('select.option', $item->value, $item->text);
 		}
 	
 		$lists 	= JHTML::_('select.genericlist', $types, $var, 'class="inputbox" size="1" '.$option.' '.$event.'', 'value', 'text', $default );
 		return $lists;
 	}
 	
 	//13.getSelectPaymode
	function getSelectPaymode($var, $default, $event){
		$db	= JFactory::getDBO(); 
		
		//make selection gateways
		$query = 'SELECT gwcode AS value, gateway_name AS text FROM #__jblance_paymode 
				  WHERE published=1
				  ORDER BY ordering';
		$db->setQuery($query);
		$gateways = $db->loadObjectList();
		
		$types[] = JHTML::_('select.option',  '', '- '.JText::_('COM_JBLANCE_ALL_GATEWAYS').' -');
		foreach($gateways as $item){
			$types[] = JHTML::_('select.option', $item->value, JText::_($item->text));
		}		
		$lists 	= JHTML::_('select.genericlist', $types, $var, 'class="inputbox" size="1" '.$event.'', 'value', 'text', $default);
		return $lists;
	}
	
	//8.getSelectDepositStatus
	function getSelectDepositStatus($var, $default, $event){
		$types[] = JHTML::_('select.option', '', '- '.JText::_('COM_JBLANCE_ALL_STATUS').' -');
		$types[] = JHTML::_('select.option', '1', JText::_('COM_JBLANCE_APPROVED'));
		$types[] = JHTML::_('select.option', '0', JText::_('COM_JBLANCE_UNAPPROVED'));
	
		$lists 	 = JHTML::_('select.genericlist', $types, $var, "class='inputbox' size='1' $event", 'value', 'text', $default);
	
		return $lists;
	}
	
 	function countBids($id){
 		$db = JFactory::getDBO();
 		$query = "SELECT COUNT(*) FROM #__jblance_bid WHERE project_id = $id";
 		$db->setQuery($query);
 		$total = $db->loadResult();
 		return $total;
 	}
 	
	function getWithdrawParams($params){
		// Convert the params field to an object.
		$registry = new JRegistry;
		$registry->loadString($params);
		$params = $registry->toObject();
		
		$html = '';
		
		foreach($params as $key=>$value){
			if($key == 'paypalEmail')
				$html .= JText::_('COM_JBLANCE_PAYPAL_ID').' : <strong>'.$value.'</strong><br>';
			if($key == 'btAccnum')
				$html .= JText::_('COM_JBLANCE_ACCOUNT_NUMBER').' : <strong>'.$value.'</strong><br>';
			if($key == 'btBankname')
				$html .= JText::_('COM_JBLANCE_BANK_NAME').' : <strong>'.$value.'</strong><br>';
			if($key == 'btAccHoldername')
				$html .= JText::_('COM_JBLANCE_ACCOUNT_HOLDER_NAME').' : <strong>'.$value.'</strong><br>';
			if($key == 'btIBAN' && !empty($value))
				$html .= JText::_('COM_JBLANCE_IBAN').' : <strong>'.$value.'</strong><br>';
			if($key == 'btSWIFT' && !empty($value))
				$html .= JText::_('COM_JBLANCE_SWIFT').' : <strong>'.$value.'</strong><br>';
			if($key == 'mbRecipientName')
				$html .= JText::_('COM_JBLANCE_RECIPIENT_NAME').' : <strong>'.$value.'</strong><br>';
			if($key == 'mbRecipientEmail')
				$html .= JText::_('COM_JBLANCE_RECIPIENT_EMAIL').' : <strong>'.$value.'</strong><br>';
		}
		return $html;
	}
}