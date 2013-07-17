<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 July 2012
 * @file name	:	tables/report.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class TableReport extends JTable {
	
	var $id = null;
	var $uniquestring = null;
	var $link = null;
	var $status = null;
	var $date_created = null;
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct('#__jblance_report', 'id', $db);
	}
	
	public function getId($uniqueString){
		$db		= $this->getDBO();
	
		$query	= 'SELECT id FROM #__jblance_report WHERE uniquestring='.$db->quote($uniqueString);
		$db->setQuery($query);
		$reportID = $db->loadResult();
	
		if(!$reportID)
			return false;
	
		return $reportID;
	}
	
	/**
	 * Adds a reporter and the text that is reported
	 *
	 * @param	$reportId	The parent's id
	 * @param	$authorId	The reporter's id
	 * @param	$message	The text that have been submitted by reporter.
	 * @param	$created	Datetime representation value.
	 * @param	$ip			The reporter's ip address
	 */
	public function addReporter($reportId, $authorId, $category, $message, $created, $ip){
		$db					= $this->getDBO();
		$data				= new stdClass();
	
		$data->report_id	= $reportId;
		$data->category		= $category;
		$data->message		= $message;
		$data->user_id		= $authorId;
		$data->date_created	= $created;
		$data->ip			= $ip;
		
		return $db->insertObject('#__jblance_report_reporter', $data, 'id');
	}
	
	public function getReportersCount(){
		$db		= $this->getDBO();
	
		$query	= 'SELECT COUNT(*) FROM #__jblance_report_reporter WHERE report_id='.$db->quote($this->id);
		$db->setQuery($query);
		return $db->loadResult();
	}
}
?>