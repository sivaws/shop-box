<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 July 2012
 * @file name	:	helpers/report.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class ReportHelper {
	
	public function createReport(){
		
		$app  	= JFactory::getApplication();
		$now 	= JFactory::getDate();
		$user	= JFactory::getUser();
		$db 	= JFactory::getDBO();
		$post 	= JRequest::get('post');
		$link 	= $app->input->get('link', '', 'string');
		$reportitemId = $app->input->get('reportitemid', 0, 'int');
		$reportType   = $app->input->get('report', '', 'string');
		$row 	= JTable::getInstance('report', 'Table');
		$ip		= JRequest::getVar('REMOTE_ADDR', '', 'SERVER');
		
		$uniqueString = md5($reportType.$reportitemId);
		
		// Test if the report already exists as we do not want to keep re-creating new reports
		$id	= $row->getId($uniqueString);
		
		//check if the user has already reported this item - for guest user id will be 0 - check for ip address too
		//if($user->id > 0){
			$query = "SELECT * FROM #__jblance_report_reporter WHERE report_id=".$db->quote($id)." AND user_id=".$user->id.' AND ip='.$db->quote($ip);
			$db->setQuery($query);
			$hasReported = $db->loadObject();
		//}
		
		if($hasReported)
			return false;
		
		if(!$id){
			//get action for the report type
			$action = $this->getAction($reportType, $reportitemId);
			
			$row->uniquestring 	= $uniqueString;
			$row->link 			= base64_decode($link);
			$row->date_created 	= $now->toSql();
			$row->label 		= $action->label;
			$row->method 		= $action->method;
			$row->params		= $action->params;
			$row->defaultAction	= $action->defaultAction;
		
			if(!$row->store())
				JError::raiseError(500, $row->getError());
		}
		else {
			$row->load($id);
		}
		
		// Add a new reporter item
		
		if(!$row->addReporter($row->id, $user->id, $post['category'], $post['message'], $now->toSql(), $ip)){
			// Error while trying to add a new reporter.
			return false;
		}
		
		//execute default action when the max report count is reached
		$config = JblanceHelper::getConfig();
		$maxReport = $config->maxReport;
		
		$count = $row->getReportersCount();
		
		if($count >= $maxReport && $maxReport > 0){
			if($this->executeDefaultAction($row)){
				$row->status = 1;
				$row->store();
			}
		}
		return true;
	}
	
	function getAction($reportType, $reportitemId){
		
		if($reportType == 'profile'){
			$action					= new stdClass();
			$action->label			= JText::_('COM_JBLANCE_BLOCK_USER');
			$action->method			= 'profile,blockProfile';
			$action->params			= $reportitemId;
			$action->defaultAction	= true;
			return $action;
		}
		if($reportType == 'project'){
			$action					= new stdClass();
			$action->label			= JText::_('COM_JBLANCE_UNPUBLISH_PROJECT');
			$action->method			= 'project,unpublishProject';
			$action->params			= $reportitemId;
			$action->defaultAction	= true;
			return $action;
		}
	}
	
	function executeDefaultAction($report){
		
		$method		= explode(',', $report->method);
		$args		= $report->params;
		
		$result = $this->$method[1]($args);
		
		//send notification to system admins about this action.
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		$jbmail->sendReportingDefaultAction($report, $result);
		
		return true;
	}
	
	function blockProfile($userid){
		$return = array();
		$user = JFactory::getUser($userid);
		$user->set('block', 1);
		$user->save();
		
		$return['type'] = JText::_('COM_JBLANCE_PROFILE');
		$return['action'] = JText::_('COM_JBLANCE_USER_ACCOUNT_BANNED');
		return $return;
	}
	
	function unpublishProject($projectid){
		$return = array();
		
		$project 	= JTable::getInstance('project', 'Table');
		$project->load($projectid);
		
		$project->approved = 0;
		if(!$project->store())
			JError::raiseError(500, $project->getError());
		
		$return['type'] = JText::_('COM_JBLANCE_PROJECT');
		$return['action'] = JText::_('COM_JBLANCE_PROJECT_UNPUBLISHED');
		return $return;
	}
}