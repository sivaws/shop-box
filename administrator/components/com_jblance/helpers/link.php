<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	07 June 2012
 * @file name	:	helpers/link.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Helper Class for sending Emails (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class LinkHelper {
	
	// Basic universal href link
	public static function GetHrefLink($link, $name, $title = '', $rel = 'nofollow', $class = '', $anker = '', $attr = '') {
		return '<a ' . ($class ? 'class="' . $class . '" ' : '') . 'href="' . $link . ($anker ? ('#' . $anker) : '') . '" title="' . $title . '"' . ($rel ? ' rel="' . $rel . '"' : '') . ($attr ? ' ' . $attr : '') . '>' . $name . '</a>';
	}
	
	public static function GetProfileLink($userid, $name = null, $title ='', $rel = 'nofollow', $class = '') {
		if(!$name){
			$profile = JFactory::getUser($userid);
			$name = htmlspecialchars($profile->name, ENT_COMPAT, 'UTF-8');
		}
		if($userid > 0){
			$link = self::GetProfileURL($userid);
			if(!empty ($link))
				return self::GetHrefLink($link, $name, $title, $rel, $class);
		}
		return "<span class=\"{$class}\">{$name}</span>";
	}
	
	public static function GetProfileURL($userid, $xhtml = true) {
		$profile = JblanceHelper::getProfile();
		return $profile->getProfileURL($userid, '', $xhtml);
	}
	
	public static function getDownloadLink($type, $id, $task=''){
		$fileInfo = JBMediaHelper::getFileInfo($type, $id);
	
		$filePath = $fileInfo['filePath'];
		$fileUrl = $fileInfo['fileUrl'];
		$showName = $fileInfo['showName'];
		
		$directDownloadLink = false;
	
		if(!$directDownloadLink){
			if($type == 'nda'){
				$showName = '<img src="components/com_jblance/images/nda.png" width="20px" title="'.JText::_('COM_JBLANCE_NDA_SIGNED').'"/>';
			}
			$link = JRoute::_('index.php?option=com_jblance&task='.$task.'&type='.$type.'&id='.$id.'&'.JSession::getFormToken().'=1');
		}
		else {
			if($type == 'nda'){
				$showName = '<img src="components/com_jblance/images/nda.png" width="20px" title="'.JText::_('COM_JBLANCE_NDA_SIGNED').'"/>';
			}
			$link = $fileUrl;
		}
	
		return self::GetHrefLink($link, $showName, $title ='', $rel = 'nofollow', $class = '', '','target=_blank');
	}
	
	function getProjectLink($project_id, $name = ''){
		if(empty($name)){
			$projhelp 	 = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
			$proj_detail = $projhelp->getProjectDetails($project_id);
			$name = $proj_detail->project_title;
		}
		//$link = JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&id='.$project_id);
		$link = 'index.php?option=com_jblance&view=project&layout=detailproject&id='.$project_id;
		return self::GetHrefLink($link, $name);
	}
}