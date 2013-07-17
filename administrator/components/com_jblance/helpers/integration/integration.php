<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	07 June 2012
 * @file name	:	helpers/integration/integration.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

jimport ( 'joomla.version' );

// Abstract base class for various 3rd party integration classes
abstract class JoomBriIntegration extends JObject {
	protected static $instances = array ();
	protected $loaded = false;

	static public function getInstance($integration) {
		if (! $integration)
			return false;
		if (! isset ( self::$instances [$integration] )) {
			$basedir = dirname ( __FILE__ );
			$file = "{$basedir}/{$integration}/integration.php";
			if (is_file ( $file )) {
				require_once ($file);
				$class = __CLASS__ . ucfirst ( $integration );
				self::$instances [$integration] = new $class ( );
			} else {
				self::$instances [$integration] = false;
			}
		}
		return self::$instances [$integration];
	}

	public function isLoaded() {
		return $this->loaded;
	}

	static public function initialize($name, $integration) {
		$object = self::_initialize($name, $integration);
		if (!$object) $object = self::_initialize($name, 'auto');
		return $object;
	}

	static protected function _initialize($name, $integration) {
		if (! $integration)
			$integration = 'none';
		if ($integration == 'auto')
			$integration = self::detectIntegration ( $name, true );
		else if ($integration == 'joomla')
			$integration = self::detectJoomla ();
		$basedir = dirname ( __FILE__ );
		$file = "{$basedir}/{$integration}/{$name}.php";
		if (is_file ( $file )) {
			require_once ($file);
			$class = 'Joombri' . ucfirst ( $name ) . ucfirst ( $integration );
			if (class_exists ( $class )) {
				$object = new $class ( );
				if ($object->priority)
					return $object;
			}
		}
	}

	static protected function detectJoomla() {
		$jversion = new JVersion ();
		if ($jversion->RELEASE == '1.5') {
			return 'joomla15';
		} else {
			return 'joomla16';
		}
	}

	static public function detectIntegration($name, $best = false){
		jimport ('joomla.filesystem.folder');
		$dir = dirname (__FILE__);
		$folders = JFolder::folders ($dir);
		$list = array ();
		foreach ($folders as $integration){
			$file = "$dir/$integration/$name.php";
			if (is_file ( $file )) {
				jbimport("integration.$name");
				$obj = self::_initialize($name, $integration);
				$priority = 0;
				if($obj)
					$priority = $obj->priority;
				$list [$integration] = $priority;
				unset ($obj);
			}
		}
		if($best){
			// Return best choice
			arsort ($list);
			reset ($list);
			return key ($list);
		}
		// Return associative list of all options
		return $list;
	}

	static public function getConfigOptions($name) {
		$config = JblanceHelper::getConfig ();
		$options = JoomBriIntegration::detectIntegration($name);
		$integration = 'integration'.ucfirst($name);
		/* if (isset($options['none'])) {
			 $none = $options['none'];
			 unset ($options['none']);
		} */

		$opt[] = JHTML::_('select.option', 'auto', JText::_('COM_JBLANCE_INTEGRATION_AUTO'));
		foreach ($options as $component=>$status) {
			if ($component == 'joomla15' || $component == 'joomla16') {
				if(!$status) continue;
				$component = 'joomla';
			}
			//echo $component;
			$opt[] = JHTML::_('select.option', $component, JText::_('COM_JBLANCE_INTEGRATION_'.strtoupper($component)), 'value', 'text', !$status);
		}
		/* if (isset($none)) {
			$opt[] = JHTML::_('select.option', 'none', JText::_('COM_JOOMBRI_INTEGRATION_NONE'), 'value', 'text', !$none);
		} */
		return JHTML::_('select.genericlist', $opt, "params[$integration]", 'class="inputbox" size="1"', 'value', 'text', $config->$integration);
	}

	// abstract function to be overriden in derived class
	public function load() {
	}
}