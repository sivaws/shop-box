<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	07 June 2012
 * @file name	:	helpers/integration/joombri/profile.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

class JoombriProfileJoombri extends JoombriProfile {
	public function __construct() {
		$this->priority = 25;
	}

	public function getUserListURL($action='', $xhtml = true){
		
		return JRoute::_("index.php?option=com_jblance&view=user&layout=userlist", $xhtml);
	}

	public function getProfileURL($userid, $task='', $xhtml = true){
		if($userid == 0) return false;
		$my = JFactory::getUser();
		$id = ($my->id != $userid) ? "&id={$userid}" : '';
		return JRoute::_("index.php?option=com_jblance&view=user&layout=viewprofile{$id}", $xhtml);
	}

	public function getProfileView($PopUserCount=0) {
		/* $_db =JFactory::getDBO ();
		$_config = JblanceHelper::getConfig ();

		$queryName = $_config->username ? "username" : "name";
		if (!$PopUserCount) $PopUserCount = $_config->popusercount;
		$query = "SELECT u.uhits AS hits, u.userid AS user_id, j.id, j.{$queryName} AS user FROM #__joombri_user AS u
					INNER JOIN #__users AS j ON j.id = u.userid
					WHERE u.uhits>'0' AND j.block=0 ORDER BY u.uhits DESC";
		$_db->setQuery ( $query, 0, $PopUserCount );
		$topJoombriProfileView = $_db->loadObjectList ();
		JoombriError::checkDatabaseError();

		return $topJoombriProfileView; */
	}
	
	public function getEditURL(){
		return JRoute::_('index.php?option=com_jblance&view=user&layout=editprofile');
	}

	public function showProfile($userid, &$msg_params) {}
}
