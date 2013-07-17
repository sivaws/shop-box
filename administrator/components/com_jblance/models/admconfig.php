<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	14 March 2012
 * @file name	:	models/admconfig.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

 jimport('joomla.application.component.model');
 
class JblanceModelAdmconfig extends JModelLegacy {
	function __construct(){
		parent :: __construct();
		//$user	= JFactory::getUser();
	}
	
	function getConfig(){
	
		$row = JTable::getInstance('config', 'Table');
		$row->load(1);
	
		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadString($row->params);
		$params = $registry->toObject();
	
		$return[0] = $row;
		$return[1] = $params;
		return $return;
	}
	
	public function getShowUserGroup(){
	
		// Initialize variables
		$app = JFactory::getApplication();
		$db	 = JFactory::getDBO();
	
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
	
		// Get the total number of records for pagination
		$query	= 'SELECT COUNT(*) FROM #__jblance_usergroup';
		$db->setQuery($query);
		$total = $db->loadResult();
	
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
	
		$query	= "SELECT ug.*, (SELECT COUNT(*) FROM #__jblance_user u WHERE u.ug_id=ug.id) usercount FROM #__jblance_usergroup ug ".
				  "ORDER BY ordering";
		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows	= $db->loadObjectList();
	
		$return[0] = $rows;
		$return[1] = $pageNav;
		return $return;
	}
	
	//7.Salary Type - edit
	function getEditUserGroup(){
		$app  	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$row 	= JTable::getInstance('usergroup', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));

		$isNew = (empty($cid))? true : false;
		if(!$isNew)
			$row->load($cid[0]);
	
		$fields = $this->getFields();
	
		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadString($row->params);
		$params = $registry->toArray();
	
		$return[0] = $row;
		$return[1] = $fields;
		$return[2] = $params;
	
		return $return;
	}
	
	//2.Membership Plans - show
	function getShowPlan(){
		$app = JFactory::getApplication();
		$db	= JFactory::getDBO();
	
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
	
		$ug_id	 	= $app->getUserStateFromRequest('com_jblance_filter_plan_ug_id', 'ug_id', '', 'int');
		$select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
		$lists['ug_id'] = $select->getSelectUserGroups('ug_id', $ug_id, 'COM_JBLANCE_SELECT_USERGROUP', '', 'onchange="document.adminForm.submit();"');
	
		$where = array();
		if($ug_id != '') 	 $where[] = 'p.ug_id ='.$db->quote($ug_id);
		$where = (count($where) ? ' WHERE ('.implode( ') AND (', $where ) . ')' : '');
	
		$query = "SELECT COUNT(*) FROM #__jblance_plan";
		$db->setQuery($query);
		$total = $db->loadResult();
	
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
	
		$query = "	SELECT p.*, COUNT(s.id) as subscr, ug.name groupName FROM #__jblance_plan p
					LEFT JOIN #__jblance_plan_subscr AS s ON s.plan_id = p.id
					LEFT JOIN `#__jblance_usergroup` AS ug ON p.ug_id = ug.id
					$where
					GROUP BY p.id
					ORDER BY p.ordering ASC";
		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();
		
		//check for default plan for each user group
		$query = "SELECT id,name FROM #__jblance_usergroup WHERE published=1";
		$db->setQuery($query);
		$usergroups = $db->loadObjectList();
		
		foreach($usergroups as $usergroup){
			$query = "SELECT id FROM #__jblance_plan WHERE default_plan=1 AND ug_id=".$db->quote($usergroup->id);
			$db->setQuery($query);
			$defaultPlanId = $db->loadResult();
			
			if(empty($defaultPlanId)){
				$app->enqueueMessage(JText::sprintf('COM_JBLANCE_NO_DEFAULT_PLAN_FOR_THE_USERGROUP', $usergroup->name), 'error');
				//$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=showfront', false);
			}
		}
		
		
	
		$return[0] = $rows;
		$return[1] = $pageNav;
		$return[2] = $lists;
		return $return;
	}
	
	//2.Membership Plans - edit
	function getEditPlan(){
		$app  	= JFactory::getApplication();
		$row 	= JTable::getInstance('plan', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		
		$isNew = (empty($cid))? true : false;
		if(!$isNew)
			$row->load($cid[0]);
		
		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadString($row->params);
		$params = $registry->toArray();
	
		$return[0] = $row;
		$return[1] = $params;
	
		return $return;
	}
	
	//7a.Pay Modes - show
	function getShowPaymode(){
		$app = JFactory::getApplication();
		$db	= JFactory::getDBO();
		$post   = JRequest::get('post');
	
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
	
		$query = "SELECT COUNT(*) FROM #__jblance_paymode";
		$db->setQuery($query);
		$total = $db->loadResult();
	
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
	
		$query = "SELECT * FROM #__jblance_paymode ".
				 "ORDER BY ordering";
		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();
	
		$return[0] = $rows;
		$return[1] = $pageNav;
	
		return $return;
	}
	
	//7a.Pay Modes - edit
	function getEditPaymode(){
		$app  	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
	
		$paymode = JTable::getInstance('paymode', 'Table');
		$paymode->load($cid[0]);
		
		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadString($paymode->params);
		$params = $registry->toObject();
		
		$gwcode = $paymode->gwcode;
		// get the JForm object
		jimport('joomla.form.form');
		$pathToGatewayXML = JPATH_COMPONENT_SITE."/gateways/forms/$gwcode.xml";
		if(file_exists($pathToGatewayXML)){
			$form = JForm::getInstance($gwcode, $pathToGatewayXML, array('control' => 'params', 'load_data' => true));
			$form->bind($params);
		}
		else
			$form = null;
	
		$return[0] = $paymode;
		$return[1] = $params;
		$return[2] = $form;
		return $return;
	}
	
	//7.Custom Field - show
	function getShowCustomField(){
		$app = JFactory::getApplication();
		$db	= JFactory::getDBO();
		$post   = JRequest::get( 'post' );
	
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
		$filter_field_type = $app->getUserStateFromRequest('com_jblance.filter_cust_field_type', 'filter_field_type', 'profile', 'string');
	
		$where = '';
		if(!empty($filter_field_type))
			$where = " WHERE field_for = ".$db->quote($filter_field_type);
	
		$lists['field_type'] = $this->getSelectFieldtype('filter_field_type', $filter_field_type, 0, 'onchange="document.adminForm.submit();"');
	
		$query = "SELECT COUNT(*) FROM #__jblance_custom_field $where";
		$db->setQuery($query);
		$total = $db->loadResult();
	
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );
	
		$query = "SELECT * FROM #__jblance_custom_field
		$where
		ORDER BY ordering";
		$db->setQuery($query/*, $pageNav->limitstart, $pageNav->limit*/);
		$rows = $db->loadObjectList();
	
		$parents = $children = array();
		foreach($rows as $ct){
			if($ct->parent == 0)
				$parents[] = $ct;
			else
				$children[] = $ct;
		}
		$ordered = '';
		
		if(count($parents)){
			foreach($parents as $pt){
				$ordered[] = $pt;
				foreach($children as $ct){
					if($ct->parent == $pt->id){
						$ordered[]= $ct;
					}
				}
			}
			$rows = $ordered;
		}
		
		$return[0] = $rows;
		$return[1] = $pageNav;
		$return[2] = $lists;
		$return[3] = $filter_field_type;
		
		return $return;
	}
	
	//7.Custom Field - edit
	function getEditCustomField(){
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$row 	= JTable::getInstance('custom', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		
		$isNew = (empty($cid)) ? true : false;
		if(!$isNew)
			$row->load($cid[0]);
	
		$filter_field_type = $app->getUserStateFromRequest('com_jblance.filter_cust_field_type', 'field_for', 'profile', 'string');
		$lists['field_type'] = $this->getSelectFieldtype('field_for', $filter_field_type, 'profile', 'onchange="document.adminForm.submit();"');
		if($filter_field_type)
			$where = " field_for = ".$db->quote($filter_field_type);
	
		//make selection custom group
		$query = 'SELECT id AS value, field_title AS text FROM #__jblance_custom_field WHERE parent=0 AND'. $where.' ORDER BY ordering';
		$db->setQuery($query);
		$users = $db->loadObjectList();
	
		$types = array();
		foreach($users as $item){
			$types[] = JHTML::_('select.option', $item->value, JText::_($item->text));
		}
		$groups = JHTML::_('select.genericlist', $types, 'parent', 'class="inputbox required" size="8"', 'value', 'text', $row->parent);
	
		$return[0] = $row;
		$return[1] = $groups;
		$return[2] = $lists;
		return $return;
	}
	
	//Email Templates
	function getEmailTemplate(){
		$app  	 = JFactory::getApplication();
		$db 	 = JFactory :: getDBO();
		$tempFor = $app->input->get('tempfor', 'subscr-pending', 'string');
	
		$query = "SELECT * FROM #__jblance_emailtemplate WHERE templatefor = ".$db->Quote($tempFor);
		$db->setQuery($query);
		$template = $db->loadObject();
	
		return $template;
	}
	
	//13.Category - show
	function getShowCategory(){
		$app = JFactory::getApplication();
		$db	= JFactory::getDBO();
		$select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
		$post   = JRequest::get('post');
	
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
	
		$query = "SELECT COUNT(*) FROM #__jblance_category a";
		$db->setQuery($query);
		$total = $db->loadResult();
	
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
	
		$query = 'SELECT * FROM #__jblance_category WHERE parent=0 ORDER BY ordering';
		$db->setQuery($query);
		$categs = $db->loadObjectList();
	
		// subcategories view as tree
		$tree = array();
	
		foreach($categs as $v) {
			$indent = '';
			$tree[] = $v;
			$tree = $select->getSubcategories($v->id, $indent, $tree, 1);
		}
		$rows = array_slice($tree, $pageNav->limitstart, $pageNav->limit);
	
		$return[0] = $rows;
		$return[1] = $pageNav;
		return $return;
	}
	
	//13.Category - edit
	function getEditCategory(){
		$app  	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$row 	= JTable::getInstance('category', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		
		$isNew = (empty($cid)) ? true : false;
		if(!$isNew)
			$row->load($cid[0]);
	
		return $row;
	}
	
	function getShowBudget(){
		$app = JFactory::getApplication();
		$db	= JFactory::getDBO();
		$post   = JRequest::get('post');
		
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest('com_jblance.limitstart', 'limitstart', 0, 'int');
		
		$query = "SELECT COUNT(*) FROM #__jblance_budget b";
		$db->setQuery($query);
		$total = $db->loadResult();
		
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
		
		$query = 'SELECT * FROM #__jblance_budget ORDER BY ordering';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$return[0] = $rows;
		$return[1] = $pageNav;
		return $return;
	}
	
	function getEditBudget(){
		$app  	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$row 	= JTable::getInstance('budget', 'Table');
		$cid 	= $app->input->get('cid', array(), 'array');
		JArrayHelper::toInteger($cid, array(0));
		
		$isNew = (empty($cid)) ? true : false;
		if(!$isNew)
			$row->load($cid[0]);
	
		return $row;
	}
	
	function getOptimise(){
	
		// Initialize variables
		$app	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$result = array();
	
		//get list of user ids removed from Joomla user table
		$query = "SELECT user_id FROM #__jblance_user WHERE user_id NOT IN (SELECT id FROM #__users)";
		$db->setQuery($query);
		$db->execute();
		$num_rows = $db->getNumRows();
		$user_ids = implode(',',$db->loadColumn());
		if($num_rows > 0)
			$result[] = $num_rows.' users will be deleted from JoomBri users table';
		
		//if user id is empty, return null
		if(empty($user_ids))
			return null;
	
		//get list of project ids to be removed
		$query = "SELECT id FROM #__jblance_project WHERE assigned_userid IN (".$user_ids.") OR publisher_userid IN (".$user_ids.")";
		$db->setQuery($query);
		$db->execute();
		$num_rows = $db->getNumRows();
		$project_ids = $db->loadColumn();
		if(!empty($project_ids) && is_array($project_ids))
			$project_ids = implode(',', $project_ids);
		else 
			$project_ids = 0;
		if($num_rows > 0)
			$result[] = $num_rows.' projects will be deleted';
	
		// count entries from bid table
		$query = "SELECT COUNT(id) FROM #__jblance_bid WHERE user_id IN (".$user_ids.") OR project_id IN (".$project_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from bids table';
	
		// count entries from custom field value table
		$query = "SELECT COUNT(id) FROM #__jblance_custom_field_value WHERE userid IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from custom field value table';
	
		// count entries from deposit table
		$query = "SELECT COUNT(id) FROM #__jblance_deposit WHERE user_id IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from deposit table';
	
		// count entries from escrow table
		$query = "SELECT COUNT(id) FROM #__jblance_escrow WHERE from_id IN (".$user_ids.") OR to_id IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from escrow table';
	
		// count entries from feeds table
		$query = "SELECT COUNT(id) FROM #__jblance_feed WHERE actor IN (".$user_ids.") OR target IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from feeds table';
	
		// count entries from feeds hide table
		$query = "SELECT COUNT(id) FROM #__jblance_feed_hide WHERE user_id IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from feeds hide table';
	
		// count entries from forum table
		$query = "SELECT COUNT(id) FROM #__jblance_forum WHERE user_id IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from forum table';
	
		// count entries from message table
		$query = "SELECT COUNT(id) FROM #__jblance_message WHERE idFrom IN (".$user_ids.") OR idTo IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from message table';
	
		// count entries from notify table
		$query = "SELECT COUNT(id) FROM #__jblance_notify WHERE user_id IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from notify table';
	
		// count entries from plan subscr table
		$query = "SELECT COUNT(id) FROM #__jblance_plan_subscr WHERE user_id IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from plan subscr table';
	
		// count entries from portfolio table
		$query = "SELECT COUNT(id) FROM #__jblance_portfolio WHERE user_id IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from portfolio table';
	
		// count entries from project file table
		$query = "SELECT COUNT(id) FROM #__jblance_project_file WHERE project_id IN (".$project_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from project file table';
	
		// count entries from rating table
		$query = "SELECT COUNT(id) FROM #__jblance_rating WHERE actor IN (".$user_ids.") OR target IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from rating table';
	
		// count entries from report table
		$query = "SELECT COUNT(id) FROM #__jblance_report WHERE (`method` like 'project%' AND params IN ($project_ids)) OR (`method` like 'profile%' AND params IN ($user_ids))";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from report table';
	
		// count entries from reporter table
		$query = "SELECT COUNT(id) FROM #__jblance_report_reporter WHERE user_id IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from reporter table';
	
		// count entries from transaction table
		$query = "SELECT COUNT(id) FROM #__jblance_transaction WHERE user_id IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from transaction table';
	
		// count entries from withdraw table
		$query = "SELECT COUNT(id) FROM #__jblance_withdraw WHERE user_id IN (".$user_ids.")";
		$db->setQuery($query);
		$num_rows = $db->loadResult();
		if($num_rows > 0)
			$result[] = $num_rows.' entries will be deleted from withdraw table';
	
		$return[0] = $result;
		$return[1] = $user_ids;
		$return[2] = $project_ids;
	
		return $return;
	}
	
	/* Misc Functions */
	
	public function &getFields(){
		// Initialize variables
		$app	= JFactory::getApplication();
		$db		= JFactory::getDBO();
	
		$query	= "SELECT * FROM #__jblance_custom_field ".
				  "WHERE field_for=".$db->quote('profile')." ".
				  "ORDER BY ordering";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
	
		$parents = $children = array();
		foreach($rows as $ct){
			if($ct->parent == 0)
				$parents[] = $ct;
			else
				$children[] = $ct;
		}
		$ordered = '';
	
		if(count($parents)){
			foreach($parents as $pt){
				$ordered[] = $pt;
				foreach($children as $ct){
					if($ct->parent == $pt->id){
						$ordered[]= $ct;
					}
				}
			}
			$rows = $ordered;
		}
	
		return $rows;
	}
	
	//7.getSelectDuration
	function getSelectDuration($var, $default, $disabled, $event){
		$option = '';
		if($disabled == 1)
			$option = 'disabled';
	
		$types[] = JHTML::_('select.option', 'days', JText::_('COM_JBLANCE_DAYS'));
		$types[] = JHTML::_('select.option', 'weeks', JText::_('COM_JBLANCE_WEEKS'));
		$types[] = JHTML::_('select.option', 'months', JText::_('COM_JBLANCE_MONTHS'));
		$types[] = JHTML::_('select.option', 'years', JText::_('COM_JBLANCE_YEARS'));
	
		$lists = JHTML::_('select.genericlist', $types, $var, "class=\"inputbox\" size=\"1\" $option $event", 'value', 'text', $default);
		return $lists;
	}
	
	//20.getSelectFieldtype
	function getSelectFieldtype($var, $default, $disabled, $event){
		$option = '';
		if($disabled == 1)
			$option = 'disabled';
	
		$types[] = JHTML::_('select.option', 'profile', JText::_('COM_JBLANCE_PROFILE'));
		$types[] = JHTML::_('select.option', 'project', JText::_('COM_JBLANCE_PROJECT'));
	
		$lists 	 = JHTML::_('select.genericlist', $types, $var, "class='inputbox' size='1' $option $event", 'value', 'text', $default);
		return $lists;
	}
	
	function getSelectTheme($var, $default){
		$types[] = JHTML::_('select.option', 'styleGR.css', JText::_('COM_JBLANCE_GREY'));
		/* $types[] = JHTML::_('select.option', 'styleFB.css', JText::_('COM_JBLANCE_FACEBOOK_BLUE'));
		$types[] = JHTML::_('select.option', 'styleJS.css', JText::_('COM_JBLANCE_JOMSOCIAL_GREEN'));
		$types[] = JHTML::_('select.option', 'styleBO.css', JText::_('COM_JBLANCE_BLACK_ORANGE'));
		$types[] = JHTML::_('select.option', 'styleOR.css', JText::_('COM_JBLANCE_ORANGE'));
		$types[] = JHTML::_('select.option', 'styleCS1.css', JText::_('COM_JBLANCE_CUSTOM1'));
		$types[] = JHTML::_('select.option', 'styleCS2.css', JText::_('COM_JBLANCE_CUSTOM2')); */
	
		$lists 	 = JHTML::_('select.genericlist', $types, $var, 'class="inputbox" size="1"', 'value', 'text', $default);
	
		return $lists;
	}
	
	function getselectDateFormat($var, $default){
		$types[] = JHTML::_('select.option', 'd-m-Y', JText::_('dd-mm-yyyy'));
		$types[] = JHTML::_('select.option', 'm-d-Y', JText::_('mm-dd-yyyy'));
		$types[] = JHTML::_('select.option', 'Y-m-d', JText::_('yyyy-mm-dd'));
	
		$lists 	 = JHTML::_('select.genericlist', $types, $var, 'class="inputbox" size="1"', 'value', 'text', $default);
	
		return $lists;
	}
	
	//Get the Joomla user group title for non-super users
	function getJoomlaUserGroupTitles($id){
		$db = JFactory::getDBO();
		$query = "SELECT title FROM #__usergroups ug WHERE ug.id IN ($id)";
		$db->setQuery($query);
		$cats = $db->loadColumn();
		if($cats)
			return implode($cats, ", ");
		else
			return '';
	}
}