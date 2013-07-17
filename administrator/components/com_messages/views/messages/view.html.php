<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.modal');

/**
 * View class for a list of messages.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 * @since       1.6
 */
class MessagesViewMessages extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= MessagesHelper::getActions();

		JToolbarHelper::title(JText::_('COM_MESSAGES_MANAGER_MESSAGES'), 'inbox.png');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('message.add');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::divider();
			JToolbarHelper::publish('messages.publish', 'COM_MESSAGES_TOOLBAR_MARK_AS_READ');
			JToolbarHelper::unpublish('messages.unpublish', 'COM_MESSAGES_TOOLBAR_MARK_AS_UNREAD');
		}

		if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::divider();
			JToolbarHelper::deleteList('', 'messages.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::divider();
			JToolbarHelper::trash('messages.trash');
		}

		//JToolbarHelper::addNew('module.add');
		JToolbarHelper::divider();
		$bar = JToolBar::getInstance('toolbar');
		JHtml::_('bootstrap.modal', 'collapseModal');
		$title = JText::_('COM_MESSAGES_TOOLBAR_MY_SETTINGS');
		$dhtml = "<a class=\"btn modal btn-small\" href=\"index.php?option=com_messages&amp;view=config&amp;tmpl=component\"
					rel=\"{handler:'iframe', size:{x:700,y:300}}\">
					<i class=\"icon-cog\" title=\"$title\"></i>$title</a>";
		$bar->appendButton('Custom', $dhtml, 'config');

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_messages');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COMPONENTS_MESSAGING_INBOX');
	}
}
