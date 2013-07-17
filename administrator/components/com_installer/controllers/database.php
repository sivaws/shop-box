<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_installer
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Installer Database Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_installer
 * @since       2.5
 */
class InstallerControllerDatabase extends JControllerLegacy
{
	/**
	 * Tries to fix missing database updates
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function fix()
	{
		$model = $this->getModel('database');
		$model->fix();
		$this->setRedirect(JRoute::_('index.php?option=com_installer&view=database', false));
	}
}
