<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	models/project.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.component.model');
 
 class JblanceModelProject extends JModelLegacy {
 	
 	function getEditProject(){
 		
 		$app  = JFactory::getApplication();
 		$db   = JFactory::getDBO();
 		$user = JFactory::getUser();
 		$id   = $app->input->get('id', 0, 'int');
 		$finance = JblanceHelper::get('helper.finance');		// create an instance of the class FinanceHelper
 		
 		//check if the user's plan has expired or not approved. If so, do not allow him to post new project
 		$planStatus = JblanceHelper::planStatus($user->id);
 		if(($id == 0) && ($planStatus == 1 || $planStatus == 2)){
 			$msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_DO_OPERATION_NO_ACTIVE_SUBSCRIPTION');
 			$link	= JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
 			$app->redirect($link, $msg, 'error');
 			return false;
 		}
 		
 		//check if the user has enough fund to post new projects. This should be checked for new projects only
 		$plan = JblanceHelper::whichPlan($user->id);
 		$chargePerProject = $plan->buyChargePerProject;
 		
 		if( ($chargePerProject > 0) && ($id == 0) ){
 			$totalFund = JblanceHelper::getTotalFund($user->id);
 			if($totalFund < $chargePerProject){
 				$msg = JText::sprintf('COM_JBLANCE_BALANCE_INSUFFICIENT_TO_POST_PROJECT', JblanceHelper::formatCurrency($chargePerProject));
 				$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);
 				$app->redirect($link, $msg, 'error');
 				return false;
 			}
 		}
 		
 		//check if the user has any project limit. If any and exceeds, then disallow him
 		$lastSubscr = $finance->getLastSubscription($user->id);
 		if(($id == 0) && ($lastSubscr->projects_allowed > 0 && $lastSubscr->projects_left == 0)){
 			$msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_POST_PROJECT_LIMIT_EXCEEDED');
 			$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd', false);
 			$app->redirect($link, $msg, 'error');
 			return false;
 		}
 		
 		$row = JTable::getInstance('project', 'Table');
 		$row->load($id);
 		
 		$query = 'SELECT * FROM #__jblance_project_file WHERE project_id='.$id;
 		$db->setQuery($query);
 		$projfiles = $db->loadObjectList();
 		
 		$query = "SELECT * FROM #__jblance_custom_field ".
 				 "WHERE published=1 AND field_for=".$db->quote('project')." ".
 				 "ORDER BY ordering";
 		$db->setQuery($query);
 		$fields = $db->loadObjectList();
 		
 		$return[0] = $row;
 		$return[1] = $projfiles;
 		$return[2] = $fields;
 		return $return;
 	}
 	
 	function getShowMyProject(){
 		$app  = JFactory::getApplication();
 		$db	  = JFactory::getDBO();
 		$user = JFactory::getUser();
 		
 		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
 		$limitstart	= $app->input->get('limitstart', 0, 'int');
 		
 		$query = 'SELECT * FROM #__jblance_project p WHERE p.publisher_userid='.$user->id.' ORDER BY p.id DESC';
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
 	
 	function getListProject(){
 		$app  = JFactory::getApplication();
 		$db	  = JFactory::getDBO();
 		$user = JFactory::getUser();
 		$now  = JFactory::getDate();
 		$where = array();
 		
 		// Load the parameters.
 		$params = $app->getParams();
 		$param_status = $params->get('param_status', 'all');
 		$param_upgrade = $params->get('param_upgrade', 'all');
 		$param_categid = $params->get('id_categ', '');
 		
 		if($param_status == 'open')
 			$where[] = "p.status=".$db->quote('COM_JBLANCE_OPEN');
 		elseif($param_status == 'frozen')
 			$where[] = "p.status=".$db->quote('COM_JBLANCE_FROZEN');
 		elseif($param_status == 'closed')
 			$where[] = "p.status=".$db->quote('COM_JBLANCE_CLOSED');
 		
 		if($param_upgrade == 'featured')
 			$where[] = "p.is_featured=1";
 		elseif($param_upgrade == 'urgent')
 			$where[] = "p.is_urgent=1";
 		elseif($param_upgrade == 'private')
 			$where[] = "p.is_private=1";
 		elseif($param_upgrade == 'sealed')
 			$where[] = "p.is_sealed=1";
 		
 		if(!empty($param_categid))
 			$where[] = 'FIND_IN_SET('.$param_categid.', p.id_category)';
 		
 		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
 		$limitstart	= $app->input->get('limitstart', 0, 'int');
 		
 		$where[] = "p.approved=1";
 		$where[] = "'$now' > p.start_date";
 		
 		$where = (count($where) ? ' WHERE (' . implode( ') AND (', $where ) . ')' : '');
 		
 		$query = "SELECT p.*,(TO_DAYS(p.start_date) - TO_DAYS(NOW())) AS daydiff FROM #__jblance_project p ".
 				 $where." ".
 				 "ORDER BY p.is_featured DESC, p.id DESC";//echo $query;
 		$db->setQuery($query);
 		$db->execute();
 		$total = $db->getNumRows();
 		
 		jimport('joomla.html.pagination');
 		$pageNav = new JPagination($total, $limitstart, $limit);
 		
 		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
 		$rows = $db->loadObjectList();
 		
 		$return[0] = $rows;
 		$return[1] = $pageNav;
 		$return[2] = $params;
 		return $return;
 	}
 	
 	function getDetailProject(){
 		$app  = JFactory::getApplication();
 		$db	  = JFactory::getDBO();
 		$user = JFactory::getUser();
 		$id   = $app->input->get('id', 0, 'int');
 		
 		$row = JTable::getInstance('project', 'Table');
 		$row->load($id);
 		
 		//redirect the project to login page if the project is a `private` project and user is not logged in
 		if($row->is_private && $user->guest){
 			$url 	= JFactory::getURI()->toString();
 			$msg = JText::_('COM_JBLANCE_PRIVATE_PROJECT_LOGGED_IN_TO_SEE_DESCRIPTION');
 			$link_login  = JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($url), false);
 			$app->redirect($link_login, $msg);
 		}
 		
 		//redirect the user to dashboard if the project is not approved.
 		if(!$row->approved){
 			$msg = JText::_('COM_JBLANCE_PROJECT_PENDING_APPROVAL_FROM_ADMIN');
 			$link_dash  = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
 			$app->redirect($link_dash, $msg, 'error');
 		}
 		
 		//get project files
 		$query = 'SELECT * FROM #__jblance_project_file WHERE project_id='.$id;
 		$db->setQuery($query);
 		$projfiles = $db->loadObjectList();
 		
 		//if the project is sealed, get the particular bid row for the bidder.
 		$projHelper = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 		$hasBid = $projHelper->hasBid($row->id, $user->id);
 		
 		$bidderQuery = 'TRUE';
 		if($row->is_sealed && $hasBid){
 			$bidderQuery = " b.user_id=$user->id";
 		}
 		
 		//for nda projects, bid count should inlcude only signed bids
 		$ndaQuery = 'TRUE';
 		if($row->is_nda)
 			$ndaQuery = " b.is_nda_signed=1";
 		
 		//get bid info
 		$query ="SELECT b.*, u.username, u.name FROM #__jblance_bid b ".
 				"INNER JOIN #__users u ON b.user_id=u.id ".
 				"WHERE b.project_id =".$id." AND $bidderQuery AND $ndaQuery";//echo $query;
 		$db->setQuery($query);
 		$bids = $db->loadObjectList();
 		
 		$query = "SELECT * FROM #__jblance_custom_field ".
 				 "WHERE published=1 AND field_for=".$db->quote('project')." ".
 				 "ORDER BY ordering";
 		$db->setQuery($query);
 		$fields = $db->loadObjectList();
 		
 		//get the forum list
 		$query = "SELECT * FROM #__jblance_forum ".
 				 "WHERE project_id=$row->id ".
 				 "ORDER BY date_post ASC";
 		$db->setQuery($query);//echo $query;
 		$forums = $db->loadObjectList();
 		
 		$return[0] = $row;
 		$return[1] = $projfiles;
 		$return[2] = $bids;
 		$return[3] = $fields;
 		$return[4] = $forums;
 		return $return;
 	}
 	
 	function getPlaceBid(){
 		$app  = JFactory::getApplication();
 		$db	  = JFactory::getDBO();
 		$user = JFactory::getUser();
 		$id   = $app->input->get('id', 0, 'int');	//id is the "project id"
 		$finance = JblanceHelper::get('helper.finance');		// create an instance of the class FinanceHelper
 		
 		$project = JTable::getInstance('project', 'Table');
 		$project->load($id);
 		
 		// Project author is allowed to bid on his own project
 		if($project->publisher_userid == $user->id){
 			$msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_BID_ON_YOUR_OWN_PROJECT');
 			$link	= JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
 			$app->redirect($link, $msg, 'error');
 			return false;
 		}
 		
 		//project in Frozen/Closed should not be allowed to bid
 		if($project->status != 'COM_JBLANCE_OPEN'){
 			$link = JRoute::_('index.php?option=com_jblance&view=project&layout=listproject', false);
 			$app->redirect($link);
 			return;
 		}
 		
 		//get the bid id
 		$query = "SELECT id FROM #__jblance_bid WHERE project_id=".$id." AND user_id=".$user->id;
 		$db->setQuery($query);
 		$bid_id = $db->loadResult();
 		
 		$bid = JTable::getInstance('bid', 'Table');
 		$bid->load($bid_id);
 		
 		//check if the user's plan is expired or not approved. If so, do not allow him to bid new on project
 		$planStatus = JblanceHelper::planStatus($user->id);
 		if( empty($bid_id) && ($planStatus == 1 || $planStatus == 2) ){
 			$msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_DO_OPERATION_NO_ACTIVE_SUBSCRIPTION');
 			$link	= JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
 			$app->redirect($link, $msg, 'error');
 			return false;
 		}
 		
 		//check if the user has enough fund to bid new on projects. This should be checked for new bids only
 		$plan = JblanceHelper::whichPlan($user->id);
 		$chargePerBid = $plan->flChargePerBid;
 		
 		if( ($chargePerBid > 0) && (empty($bid_id)) ){	// bid_id will be empty for new bids
 			$totalFund = JblanceHelper::getTotalFund($user->id);
 			if($totalFund < $chargePerBid){
 				$msg = JText::sprintf('COM_JBLANCE_BALANCE_INSUFFICIENT_TO_BID_PROJECT', JblanceHelper::formatCurrency($chargePerBid));
 				$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);
 				$app->redirect($link, $msg, 'error');
 				return false;
 			}
 		}
 		
 		//check if the user has any bid limit. If any and exceeds, then disallow him
 		$lastSubscr = $finance->getLastSubscription($user->id);
 		if(empty($bid_id) && ($lastSubscr->bids_allowed > 0 && $lastSubscr->bids_left == 0)){
 			$msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_BID_PROJECT_LIMIT_EXCEEDED');
 			$link	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd', false);
 			$app->redirect($link, $msg, 'error');
 			return false;
 		}
 		
 		$return[0] = $project;
 		$return[1] = $bid;
 		return $return;
 	}
 	
 	function getShowMyBid(){
 		$db	  = JFactory::getDBO();
 		$user = JFactory::getUser();
 		
 		$query = "SELECT b.*,p.id proj_id,p.project_title,p.status proj_status,p.assigned_userid,p.publisher_userid,".
 				 "p.paid_amt,p.lancer_commission,p.is_featured,p.is_urgent,p.is_private,p.is_sealed,p.is_nda FROM #__jblance_bid b ".
 				 "LEFT JOIN #__jblance_project p ON b.project_id=p.id ".
 				 "WHERE user_id =".$user->id;//echo $query;
 		$db->setQuery($query);
 		$rows = $db->loadObjectList();
 		
 		$return[0] = $rows;
 		return $return;
 	}
 	
 	function getPickUser(){
 		
 		$app  = JFactory::getApplication();
 		$db   = JFactory::getDBO();
 		$id   = $app->input->get('id', 0, 'int');	//proj id
 		
 		$project = JTable::getInstance('project', 'Table');
 		$project->load($id);
 		
 		$query ="SELECT b.*,u.username,u.name,p.project_title FROM #__jblance_bid b ".
 	 			"LEFT JOIN #__jblance_project p ON b.project_id=p.id ".
 				"INNER JOIN #__users u ON b.user_id=u.id ".
 				//"WHERE b.project_id =".$id." AND b.status =''";
 				"WHERE b.project_id =".$id." AND TRUE";
 		$db->setQuery($query);
 		$rows = $db->loadObjectList();
 		
 		$return[0] = $rows;
 		$return[1] = $project;
 		return $return;
 	}
 	
 	function getRateUser(){
 		$app  = JFactory::getApplication();
 		$db   = JFactory::getDBO();
 		$id   = $app->input->get('id', 0, 'int');	//rate id
 		
		$rate = JTable::getInstance('rating', 'Table');
		$rate->load($id);
		
		//get info project
		$project = JTable::getInstance('project', 'Table');
		$project->load($rate->project_id);
 		
 		$return[0] = $rate;
 		$return[1] = $project;
 		return $return;
 	}
 	
 	//7.Search Project
 	function getSearchProject(){
 	
 		// Initialize variables
 		$app  = JFactory::getApplication();
 		$user = JFactory::getUser();
 		$db   = JFactory::getDBO();
 		$now  = JFactory::getDate();
 	
		$keyword	= $app->input->get('keyword', '', 'string');
		$phrase	  	= $app->input->get('phrase', 'any', 'string');
		$id_categ	= $app->input->get('id_categ', array(), 'array');
		$min_budget	= $app->input->get('min_bud', '', 'string');
		$max_budget = $app->input->get('max_bud', '', 'string');
		$status		= $app->input->get('status', '', 'string');
 	
 		$keyword = preg_replace("/\s*,\s*/", ",", $keyword); //remove the spaces before and after the commas(,)
 		switch ($phrase) {
 			case 'exact':
 				$text		= $db->quote('%'.$db->escape($keyword, true).'%', false);
 				$wheres2 	= array();
 				$wheres2[] 	= 'p.project_title LIKE '.$text;
 				$wheres2[] 	= 'ju.biz_name LIKE '.$text;
 				$wheres2[] 	= 'cv.value LIKE '.$text;
 				$wheres2[] 	= 'p.description LIKE '.$text;
 				$queryStrings[] = '(' . implode( ') OR (', $wheres2 ) . ')';
 				break;
 	
 			case 'all':
 			case 'any':
 			default:
 				$words = explode(',', $keyword);
 				$wheres = array();
 				foreach ($words as $word) {
 					$word		= $db->quote('%'.$db->escape($word, true).'%', false);
 					$wheres2 	= array();
 					$wheres2[] 	= 'p.project_title LIKE '.$word;
 					$wheres2[] 	= 'ju.biz_name LIKE '.$word;
 					$wheres2[] 	= 'cv.value LIKE '.$word;
 					$wheres2[] 	= 'p.description LIKE '.$word;
 					$wheres[] 	= implode(' OR ', $wheres2);
 				}
 				$queryStrings[] = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
 				break;
 		}
 	
 		if(count($id_categ) > 0 && !(count($id_categ) == 1 && empty($id_categ[0]))){
 			if(is_array($id_categ)){
 				$miniquery = array();
 				foreach($id_categ as $cat){
 					$miniquery[] = "FIND_IN_SET($cat, p.id_category)";
 				}
 				$querytemp = '('.implode(' OR ', $miniquery).')';
 			}
 			$queryStrings[] = $querytemp;
 		}
 		if($min_budget > 0){
 			$queryStrings[] = "p.budgetmin >= ".$db->quote($min_budget);
 		}
 		if($max_budget > 0){
 			$queryStrings[] = "p.budgetmax <= ".$db->quote($max_budget);
 		}
 		if($status != ''){
 			$queryStrings[] = "p.status=".$db->quote($status);
 		}
 		
 		$queryStrings[] = "p.approved=1";
 		$queryStrings[] = "'$now' > p.start_date ";
 		
 		$where =  implode (' AND ', $queryStrings);
 	
 		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
 		$limitstart	= $app->input->get('limitstart', 0, 'int');
 	
 		$query ="SELECT DISTINCT p.*,(TO_DAYS(p.start_date) - TO_DAYS(NOW())) AS daydiff FROM #__jblance_project p".
 				" LEFT JOIN #__jblance_user ju ON p.publisher_userid = ju.user_id".
 				" LEFT JOIN #__jblance_custom_field_value cv ON cv.projectid=p.id".
 				" WHERE ".$where.
 				" ORDER BY p.id DESC";
 		$db->setQuery($query);//echo $query;
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
 	
 	/* Misc Functions */
 	function countBids($id){
 		$db = JFactory::getDBO();
 		$row = JTable::getInstance('project', 'Table');
 		$row->load($id);
 		
 		//for nda projects, bid count should include only signed bids
 		$ndaQuery = 'TRUE';
 		if($row->is_nda)
 			$ndaQuery = "is_nda_signed=1";
 		
 		$query = "SELECT COUNT(*) FROM #__jblance_bid WHERE project_id = $id AND $ndaQuery";
 		$db->setQuery($query);
 		$total = $db->loadResult();
 		return $total;
 	}
 	
 	function getRate($pid, $userid){
 		$db = JFactory::getDBO();
 		$query = "SELECT id,quality_clarity FROM #__jblance_rating WHERE project_id = ".$pid." AND target =".$userid;
 		$db->setQuery($query);
 		$rate = $db->loadObject();
 		return $rate;
 	}
 	
 	function getBidInfo($pid, $userid){
 		$db = JFactory::getDBO();
 		$query = "SELECT id AS bidid, amount AS bidamount, status, attachment FROM #__jblance_bid WHERE project_id = ".$pid." AND user_id =".$userid;
 		$db->setQuery($query);
 		$bidInfo = $db->loadObject();
 		return $bidInfo;
 	}
 	
 	function getSelectRating($var, $default){
 		$put[] = JHTML::_('select.option',  '', '- '.JText::_('COM_JBLANCE_PLEASE_SELECT').' -');
 		$put[] = JHTML::_('select.option',  1, '1 --- '.JText::_('COM_JBLANCE_VERY_POOR'));
 		$put[] = JHTML::_('select.option',  2, '2');
 		$put[] = JHTML::_('select.option',  3, '3 --- '.JText::_('COM_JBLANCE_ACCEPTABLE'));
 		$put[] = JHTML::_('select.option',  4, '4');
 		$put[] = JHTML::_('select.option',  5, '5 --- '.JText::_('COM_JBLANCE_EXCELLENT'));
 		$rating = JHTML::_('select.genericlist', $put, $var, "class='required'", 'value', 'text', $default);
 		return $rating;
 	}
 	
 	
 }