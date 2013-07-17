<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	07 June 2012
 * @file name	:	helpers/integration/profile.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

jbimport('integration.integration');

abstract class JoombriProfile {
	public $priority = 0;

	protected static $instance = false;

	abstract public function __construct();

	static public function getInstance($integration = null){
		if(self::$instance === false){
			$config = JblanceHelper::getConfig();
			if(!$integration)
				$integration = $config->integrationProfile;
			self::$instance = JoomBriIntegration::initialize('profile', $integration);
		}
		return self::$instance;
	}

	public function open() {}
	public function close() {}
	public function trigger($event, &$params) {}

	abstract public function getUserListURL($action = '', $xhtml = true);
	abstract public function getProfileURL($user, $task = '', $xhtml = true);
	abstract public function showProfile($userid, &$msg_params);
	abstract public function getEditURL();
	public function getProfileView($PopUserCount=0) {}
}
