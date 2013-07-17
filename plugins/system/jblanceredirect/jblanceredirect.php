<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	11 June 2012
 * @file name	:	plugins/system/jblanceredirect/jblanceredirect.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 // no direct access
 defined('_JEXEC') or die('Restricted access');
 jimport('joomla.plugin.plugin');

class plgSystemJblanceRedirect extends JPlugin {
	function plgSystemJblanceRedirect(&$subject, $config){
		parent::__construct($subject, $config);
	}

	function onAfterRoute(){
		$app =JFactory::getApplication();

		$option = $app->input->get('option');
		$task   = $app->input->get('task');
		$view   = $app->input->get('view');
		$layout = $app->input->get('layout');
		$user	= JFactory::getUser();
		$item	= $app->getMenu()->getItems('link', 'index.php?option=com_jblance&view=guest&layout=showfront', true);
		$itemid = isset($item->id) ? '&Itemid='.$item->id : '';

		// get plugin info
		$redirectTo = $this->params->get('redirect_to', 1);
		
		if($redirectTo == 1){
			if($option=='com_users' && ($task == 'register' || $view == 'registration')){
				$link = JRoute::_('index.php?option=com_jblance&view=guest&layout=showfront&Itemid='.$itemid, false);
				$app->redirect($link);
				return;
			}
		}
		if($redirectTo == 2){
			if($user->guest){
				if( ($option=='com_users' && ($task == 'register' || $view == 'registration')) || ($option=='com_jblance' && ($view == 'guest' && $layout == 'showfront')) ){
					$link = JRoute::_('index.php?option=com_community&view=register', false);
					$app->redirect($link);
					return;
				}
			}
		}
		if($redirectTo == 3){
			if($user->guest){
				if( ($option=='com_users' && ($task == 'register' || $view == 'registration')) || ($option=='com_jblance' && ($view == 'guest' && $layout == 'showfront')) ){
					$link = JRoute::_('index.php?option=com_comprofiler&task=registers', false);
					$app->redirect($link);
					return;
				}
			}
		}
	}
}
