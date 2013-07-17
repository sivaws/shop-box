<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	07 June 2012
 * @file name	:	helpers/integration/avatar.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

jbimport ('integration.integration');

abstract class JoombriAvatar {
	public $priority = 0;
	public $avatarSizes = null;

	protected static $instance = false;

	abstract public function __construct();

	static public function getInstance($integration = null) {
		if (self::$instance === false) {
			$config = JblanceHelper::getConfig();
			if(!$integration)
				$integration = $config->integrationAvatar;
			self::$instance = JoombriIntegration::initialize('avatar', $integration);
		}
		return self::$instance;
	}

	public function load($userlist) {}

	abstract public function getEditURL();
	abstract protected function _getURL($userid);

	public function getLink($userid, $att = ''){
		$imgurl = $this->_getURL($userid);
		if(!$imgurl) return;
		
		$link = "<img src=\"$imgurl\" $att alt=\"img\">";
		
		return $link;
	}
}
