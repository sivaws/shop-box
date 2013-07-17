<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	07 June 2012
 * @file name	:	helpers/integration/jomsocial/integration.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

class JoomBriIntegrationJomSocial extends JoomBriIntegration {
	public function __construct() {
		$path = JPATH_ROOT.'/components/com_community/libraries/core.php';
		if (!is_file ( $path )) return;
		include_once ($path);
		$this->loaded = 1;
	}

	public function enqueueErrors() {
		if (self::GetError ()) {
			$app = JFactory::getApplication ();
			$app->enqueueMessage ( COM_JOOMBRI_INTEGRATION_JOMSOCIAL_WARN_GENERAL, 'notice' );
			$app->enqueueMessage ( self::$errormsg, 'notice' );
			$app->enqueueMessage ( COM__JOOMBRI_INTEGRATION_JOMSOCIAL_WARN_HIDE, 'notice' );
		}
	}

	/**
	 * Triggers Jomsocial events
	 *
	 * Current events: profileIntegration=0/1, avatarIntegration=0/1
	 **/
	public function trigger($event, &$params) {
		$kconfig = JblanceHelper::getConfig ();
		$params ['config'] = $kconfig;
		// TODO: jomsocial trigger
	}
}
