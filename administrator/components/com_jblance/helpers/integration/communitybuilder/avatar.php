<?php
/**
 * @version $Id: avatar.php 4336 2011-01-31 06:05:12Z severdia $
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

class JoombriAvatarCommunityBuilder extends JoombriAvatar
{
	protected $integration = null;

	public function __construct() {
		$this->integration = JoombriIntegration::getInstance ('communitybuilder');
		if (! $this->integration || ! $this->integration->isLoaded())
			return;
		$this->priority = 50;
	}

	public function load($userlist) {
		if (method_exists('CBuser','advanceNoticeOfUsersNeeded')) {
			CBuser::advanceNoticeOfUsersNeeded($userlist);
		}
	}

	public function getEditURL()
	{
		return cbSef( 'index.php?option=com_comprofiler&task=userAvatar' . getCBprofileItemid() );
	}

	protected function _getURL($userid)
	{
		global $_CB_framework;
		$app = JFactory::getApplication ();

		if ( $app->getClientId() == 0 ) $cbclient_id = 1;
		if ( $app->getClientId() == 1 ) $cbclient_id = 2;
		$_CB_framework->cbset( '_ui',  $cbclient_id );
		// Get CUser object
		$cbUser = null;
		if($userid){
			$cbUser = CBuser::getInstance($userid);
		}
		if ( $cbUser === null ) {
			//if ($sizex<=90) return selectTemplate() . 'images/avatar/tnnophoto_n.png';
			return selectTemplate() . 'images/avatar/nophoto_n.png';
		}
		//if ($sizex<=90) return $cbUser->getField( 'avatar' , null, 'csv' );
		return $cbUser->getField( 'avatar' , null, 'csv', 'none', 'list' );
	}
}
