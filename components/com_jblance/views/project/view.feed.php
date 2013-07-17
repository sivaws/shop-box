<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 June 2012
 * @file name	:	views/project/view.feed.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JblanceViewProject extends JViewLegacy {
	function display($tpl = null){
		// parameters
		$app		= JFactory::getApplication();
		$db			= JFactory::getDbo();
		$document	= JFactory::getDocument();
		$params		= $app->getParams();
		$feedEmail	= (@$app->getCfg('feed_email')) ? $app->getCfg('feed_email') : 'author';
		$siteEmail	= $app->getCfg('mailfrom');
		$document->link = JRoute::_('index.php?option=com_jblance&view=project&layout=listproject');
		$now  = JFactory::getDate();
		
		$config = JblanceHelper::getConfig();
		$limit = (int) $config->rssLimit;

		// Get some data from the model
		$app->input->set('limit', $app->getCfg('feed_limit'));
		$rows		= $this->get('Items');
		
		$query = "SELECT p.*,(TO_DAYS(p.start_date) - TO_DAYS(NOW())) AS daydiff FROM #__jblance_project p ".
 				 "WHERE p.status=".$db->quote('COM_JBLANCE_OPEN')." AND p.approved=1 AND '$now' > p.start_date ".
 				 "ORDER BY p.is_featured DESC, p.id DESC ".
				 "LIMIT $limit";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		foreach ($rows as $row){
			// strip html from feed item title
			$title = $this->escape($row->project_title);
			$title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');

			// url link to article
			$link = JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&id='.$row->id);

			// strip html from feed item description text
			$description	= strip_tags($row->description);
			
			//get the publisher info
			$publisherInfo = JFactory::getUser($row->publisher_userid);
			$author			= $publisherInfo->username;

			// load individual item creator class
			$item = new JFeedItem();
			$item->title		= $title;
			$item->link			= $link;
			$item->description	= $description;
			$item->date			= $row->start_date;
			$item->category 	= JblanceHelper::getCategoryNames($row->id_category);
			$item->author		= $author;
			if ($feedEmail == 'site') {
				$item->authorEmail = $siteEmail;
			}
			else {
				$item->authorEmail = $row->author_email;
			}
			// loads item info into rss array
			$document->addItem($item);
		}
	}
}
?>
