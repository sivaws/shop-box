<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('MenusHelper', JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');

/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 */
abstract class MenusHtmlMenus
{
	/**
	 * @param   int $itemid	The menu item id
	 */
	public static function association($itemid)
	{
		// Get the associations
		$associations = MenusHelper::getAssociations($itemid);

		// Get the associated menu items
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('m.*')
			->select('mt.title as menu_title')
			->from('#__menu as m')
			->join('LEFT', '#__menu_types as mt ON mt.menutype=m.menutype')
			->where('m.id IN ('.implode(',', array_values($associations)).')')
			->join('LEFT', '#__languages as l ON m.language=l.lang_code')
			->select('l.image')
			->select('l.title as language_title');
		$db->setQuery($query);

		try
		{
			$items = $db->loadObjectList('id');
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
			return false;
		}

		// Construct html
		$text = array();
		foreach ($associations as $tag => $associated)
		{
			if ($associated != $itemid)
			{
				$text[] = JText::sprintf('COM_MENUS_TIP_ASSOCIATED_LANGUAGE', JHtml::_('image', 'mod_languages/' . $items[$associated]->image . '.gif', $items[$associated]->language_title, array('title' => $items[$associated]->language_title), true), $items[$associated]->title, $items[$associated]->menu_title);
			}
		}
		return JHtml::_('tooltip', implode('<br />', $text), JText::_('COM_MENUS_TIP_ASSOCIATION'), 'admin/icon-16-links.png');
	}

	/**
	 * Returns a published state on a grid
	 *
	 * @param   integer       $value			The state value.
	 * @param   integer       $i				The row index
	 * @param   boolean       $enabled			An optional setting for access control on the action.
	 * @param   string        $checkbox			An optional prefix for checkboxes.
	 *
	 * @return  string        The Html code
	 *
	 * @see JHtmlJGrid::state
	 *
	 * @since   1.7.1
	 */
	public static function state($value, $i, $enabled = true, $checkbox = 'cb')
	{
		$states	= array(
			9	=> array(
				'unpublish',
				'',
				'COM_MENUS_HTML_UNPUBLISH_HEADING',
				'',
				false,
				'publish',
				'publish'
			),
			8	=> array(
				'publish',
				'',
				'COM_MENUS_HTML_PUBLISH_HEADING',
				'',
				false,
				'unpublish',
				'unpublish'
			),
			7	=> array(
				'unpublish',
				'',
				'COM_MENUS_HTML_UNPUBLISH_SEPARATOR',
				'',
				false,
				'publish',
				'publish'
			),
			6	=> array(
				'publish',
				'',
				'COM_MENUS_HTML_PUBLISH_SEPARATOR',
				'',
				false,
				'unpublish',
				'unpublish'
			),
			5	=> array(
				'unpublish',
				'',
				'COM_MENUS_HTML_UNPUBLISH_ALIAS',
				'',
				false,
				'publish',
				'publish'
			),
			4	=> array(
				'publish',
				'',
				'COM_MENUS_HTML_PUBLISH_ALIAS',
				'',
				false,
				'unpublish',
				'unpublish'
			),
			3	=> array(
				'unpublish',
				'',
				'COM_MENUS_HTML_UNPUBLISH_URL',
				'',
				false,
				'publish',
				'publish'
			),
			2	=> array(
				'publish',
				'',
				'COM_MENUS_HTML_PUBLISH_URL',
				'',
				false,
				'unpublish',
				'unpublish'
			),
			1	=> array(
				'unpublish',
				'COM_MENUS_EXTENSION_PUBLISHED_ENABLED',
				'COM_MENUS_HTML_UNPUBLISH_ENABLED',
				'COM_MENUS_EXTENSION_PUBLISHED_ENABLED',
				true,
				'publish',
				'publish'
			),
			0	=> array(
				'publish',
				'COM_MENUS_EXTENSION_UNPUBLISHED_ENABLED',
				'COM_MENUS_HTML_PUBLISH_ENABLED',
				'COM_MENUS_EXTENSION_UNPUBLISHED_ENABLED',
				true,
				'unpublish',
				'unpublish'
			),
			-1	=> array(
				'unpublish',
				'COM_MENUS_EXTENSION_PUBLISHED_DISABLED',
				'COM_MENUS_HTML_UNPUBLISH_DISABLED',
				'COM_MENUS_EXTENSION_PUBLISHED_DISABLED',
				true,
				'warning',
				'warning'
			),
			-2	=> array(
				'publish',
				'COM_MENUS_EXTENSION_UNPUBLISHED_DISABLED',
				'COM_MENUS_HTML_PUBLISH_DISABLED',
				'COM_MENUS_EXTENSION_UNPUBLISHED_DISABLED',
				true,
				'unpublish',
				'unpublish'
			),
		);

		return JHtml::_('jgrid.state', $states, $value, $i, 'items.', $enabled, true, $checkbox);
	}
}
