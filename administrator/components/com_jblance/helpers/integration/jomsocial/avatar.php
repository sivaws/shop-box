<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	07 June 2012
 * @file name	:	helpers/integration/jomsocial/avatar.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

class JoombriAvatarJomSocial extends JoombriAvatar {
	protected $integration = null;

	public function __construct() {
		$this->integration = JoombriIntegration::getInstance ('jomsocial');
		if (! $this->integration || ! $this->integration->isLoaded())
			return;
		$this->priority = 50;
	}

	public function load($userlist){
		//FB::log($userlist, 'Preload JomSocial Userlist');
		if (method_exists('CFactory', 'loadUsers')) CFactory::loadUsers($userlist);
	}

	public function getEditURL(){
		return CRoute::_('index.php?option=com_community&view=profile&task=uploadAvatar');
	}

	protected function _getURL($userid){
		// Get CUser object
		$user = CFactory::getUser($userid);
		/* if ($sizex<=90)	$avatar = $user->getThumbAvatar();
		else $avatar = $user->getAvatar(); */
		$avatar = $user->getAvatar();
		return $avatar;
	}
}
