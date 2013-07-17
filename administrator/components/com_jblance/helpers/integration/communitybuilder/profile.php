<?php
/**
 * @version $Id: profile.php 4336 2011-01-31 06:05:12Z severdia $
 * Kunena Component
 * @package Kunena
 *
 * @Copyright (C) 2008 - 2011 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 *
 **/
//
// Dont allow direct linking
defined( '_JEXEC' ) or die('');

class JoombriProfileCommunityBuilder extends JoombriProfile
{
	protected $integration = null;

	public function __construct() {
		$this->integration = JoombriIntegration::getInstance ('communitybuilder');
		if (! $this->integration || ! $this->integration->isLoaded())
			return;
		$this->priority = 50;
	}

	public function open()
	{
		$this->integration->open();
	}

	public function close()
	{
		$this->integration->close();
	}

/* TODO: do we need this anymore:
	public function getForumTabURL()
	{
		return cbSef( 'index.php?option=com_comprofiler&amp;tab=getForumTab' . getCBprofileItemid() );
	}
*/

	public function getUserListURL($action='', $xhtml = true){	
		return cbSef('index.php?option=com_comprofiler&amp;task=usersList', $xhtml);
	}

	public function getProfileURL($userid, $task='', $xhtml = true){
		if ($userid == 0) return false;
		// Get CUser object
		$cbUser = CBuser::getInstance($userid);
		if($cbUser === null) return false;
		return cbSef( 'index.php?option=com_comprofiler&task=userProfile&user='.$userid.getCBprofileItemid(), $xhtml);
	}

	public function showProfile($user, &$msg_params){
		global $_PLUGINS;

		$kunenaConfig = KunenaFactory::getConfig();
		$user = KunenaFactory::getUser($user);
		$_PLUGINS->loadPluginGroup('user');
		return implode( '', $_PLUGINS->trigger( 'forumSideProfile', array( 'kunena', null, $user->userid,
			array( 'config'=> &$kunenaConfig, 'userprofile'=> &$user, 'msg_params'=>&$msg_params) ) ) );
	}

	public function trigger($event, &$params)
	{
		return $this->integration->trigger($event, $params);
	}

	public function getProfileView($PopUserCount=0) {
		$_db =JFactory::getDBO ();
		$_config = KunenaFactory::getConfig ();

		$queryName = $_config->username ? "username" : "name";
		if (!$PopUserCount) $PopUserCount = $_config->popusercount;
		$query = "SELECT c.hits AS hits, u.id AS user_id, u.{$queryName} AS user FROM #__comprofiler AS c
					INNER JOIN #__users AS u ON u.id = c.user_id
					WHERE c.hits>'0' ORDER BY c.hits DESC";
		$_db->setQuery ( $query, 0, $PopUserCount );
		$topCBProfileView = $_db->loadObjectList ();
		KunenaError::checkDatabaseError();

		return $topCBProfileView;
	}
	
	public function getEditURL(){
		return cbSef('index.php?option=com_comprofiler&task=userDetails'.getCBprofileItemid());
	}
}
