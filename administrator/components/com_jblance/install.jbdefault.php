<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	24 April 2012
 * @file name	:	install.jbdefault.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Table default values (jblance)
 */
defined('_JEXEC') or die('Restricted access');

function addMenus(){
	// Need to update the menu's component id if this is a reinstall
	$html = '';
	if(mainMenuExist()){
		$html .= '<div style="width:150px; float:left;">'.JText::_('COM_JBLANCE_INSTALLATION_UPDATE_MENU_ITEMS').'</div>';
		if(!updateMenuItems()){
		?>
			<p style="font-weight: 700; color: red;">System encountered an error while trying to update the existing menu items. You will need to update the existing menu structure manually.</p>
		<?php
		}
		else {
			//nothing
		}
	}
	else {
		$html .= '<div style="width:150px; float:left;">'.JText::_('COM_JBLANCE_INSTALLATION_CREATE_MENU_ITEMS').'</div>';
		if(!addMainMenuItems()){
		?>
		<p style="font-weight: 700; color: red;">System encountered an error while trying to create a menu item. You will need to create your menu item manually.</p>
		<?php
		}
		else {
			//nothing
		}
	}

	// JoomBri menu types
	if(!menuTypesExist()){
		$html .= '<div style="width:150px; float:left;">'.JText::_('COM_JBLANCE_INSTALLATION_CREATE_TOOLBAR_MENU_ITEM').'</div>';
		if(!addDefaultMenuTypes()){
		?>
		<p style="font-weight: 700; color: red;">System encountered an error while trying to create a menu type item. You will need to create your toolbar menu type item manually.</p>
		<?php
		}
		else {
			//nothing
		}
	}

	// JoomBri menu items
	if(!joombriMenuItemsExist()){
		$html .= '<div style="width:150px; float:left;">'.JText::_('COM_JBLANCE_INSTALLATION_CREATE_TOOLBAR_MENU_ITEM').'</div>';
		if(!addDefaultToolbarMenus()){
		?>
		<p style="font-weight: 700; color: red;">System encountered an error while trying to create a menu type item. You will need to create your toolbar menu type item manually.</p>
		<?php
		}
		else {
			//nothing
		}
	}
}

function mainMenuExist(){
	$db		= JFactory::getDBO();

	$query	= 'SELECT COUNT(*) FROM #__menu'.
			' WHERE link LIKE '.$db->quote('%option=com_jblance%').
			' AND menutype != '.$db->quote('main').' AND menutype = '.$db->quote('mainmenu');
	$db->setQuery($query);
	$needUpdate	= ($db->loadResult() >= 1) ? true : false;

	return $needUpdate;
}

function updateMenuItems(){
	// Get new component id.
	$component		= JComponentHelper::getComponent('com_jblance');
	$component_id	= 0;
	if(is_object($component) && isset($component->id)){
		$component_id 	= $component->id;
	}

	if ($component_id > 0){
		// Update the existing menu items.
		$db 	= JFactory::getDBO();

		$query 	= 'UPDATE #__menu SET '.$db->quoteName('component_id').'='.$db->quote($component_id).
				  ' WHERE link LIKE '.$db->quote('%option=com_jblance%');
		$db->setQuery($query);
		$db->execute();

		if($db->getErrorNum()){
			return false;
		}
	}
	return true;
}

//This function add the menu items in the Main Menu
function addMainMenuItems(){
	$db		= JFactory::getDBO();

	// Get new component id.
	$component		= JComponentHelper::getComponent('com_jblance');
	$component_id	= 0;
	if(is_object($component) && isset($component->id)){
		$component_id = $component->id;
	}

	$column_name	= 'title';
	$column_cid		= 'component_id';

	/* Get the default menu type
	 2 Joomla bugs occur in /Administrator mode
	Bug 1: JFactory::getApplication('site') failed. It always return id = 'administrator'.
	Bug 2: JMenu::getDefault('*') failed. JAdministrator::getLanguageFilter() doesn't exist.
	If these 2 bugs are fixed, we can call the following syntax:
	$defaultMenuType	= JFactory::getApplication('sites')->getMenu()->getDefault()->menutype; */
	jimport('joomla.application.application');
	$defaultMenuType	= JApplication::getInstance('site')->getMenu()->getDefault('workaround_joomla_bug')->menutype;

	/* $query 	= 'SELECT ordering FROM #__menu ORDER BY ordering DESC LIMIT 1';
	$db->setQuery($query);
	$order 	= $db->loadResult() + 1; */

	// Update the existing menu items.
	$row = JTable::getInstance('menu', 'JTable');

	$row->menutype = $defaultMenuType;
	$row->$column_name = 'JoomBri';
	$row->alias = 'JoomBri';
	$row->link = 'index.php?option=com_jblance&view=guest&layout=showfront';
	$row->type = 'component';
	$row->published = '1';
	$row->$column_cid = $component_id;
	//$row->ordering = $order;
	$row->id = null; //new item

	if (!false){
		$row->language	= '*';
	}

	$row->check();

	if(!$row->store()){
		// $row->getError()
		return false;
	}

	//for version 1.6 only. The parent_id is not updated correctly via JTable
	if (!false){
		$query = 'UPDATE #__menu SET `parent_id` = 1, `level` = 1'.
				' WHERE `id` = '.$db->quote($row->id) ;
		$db->setQuery($query);
		$db->execute();
		if($db->getErrorNum()){
			return false;
		}
	}

	/*if (!addDefaultToolbarMenus()){
	 return false;
	}

	// update memu items with component id
	if (!updateMenuItems()){
	return false;
	}*/

	return true;
}

function joombriMenuItemsExist(){
	$db		= JFactory::getDBO();

	$query	= 'SELECT COUNT(*) FROM #__menu'.
			' WHERE link LIKE '.$db->quote('%option=com_jblance%').
			' AND menutype = '.$db->quote('joombri');
	$db->setQuery($query);
	$needUpdate	= ($db->loadResult() >= 1) ? true : false;

	return $needUpdate;
}

function addDefaultToolbarMenus(){
	$db		= JFactory::getDBO();
	$filename	= JPATH_ROOT.'/administrator/components/com_jblance/toolbar.xml';
	$menu_name = 'title';
	$menu_parent = 'parent_id';
	$menu_level = 'level';
	
	$xml 	  = simplexml_load_file($filename);

	$items	= $xml->children()->items;

	$i	= 1;
	foreach($items->children() as $item){
		$obj		= new stdClass();

		// Retrieve the menu name
		$element	= (string) $item->{'name'};
		$obj->title = !empty($element) ? $element : '';

		// Retrieve the menu alias
		$element	= (string) $item->{'alias'};
		$obj->alias	= !empty($element) ? $element : '';

		// Retrieve the menu link
		$element	= (string) $item->{'link'};
		$obj->link	= !empty($element) ? $element : '';

		// Retrieve the menu type
		$element	= (string) $item->{'type'};
		$obj->type	= !empty($element) ? $element : '';

		$obj->menutype	= 'joombri';
		$obj->published	= 1;
		$obj->parent_id	= 1;
		$obj->level	= 1;
		//$obj->ordering	= $i;
		$obj->access = 1;
		$obj->path = $obj->alias;

		//$childs	= $item->getElementByPath('childs');
		$childs		= $item->children()->childs;
		
		if(!false){
			$obj->language	= '*';

			//J1.6: menu item ordering follow lft and rgt
			$query 	= 'SELECT rgt FROM #__menu'.
					' ORDER BY rgt DESC LIMIT 1';
			$db->setQuery($query);
			$obj->lft 	= $db->loadResult() + 1;
			$totalchild = $childs ? count($childs->children()) : 0;
			$obj->rgt	= $obj->lft + $totalchild * 2 + 1;
		}

		$db->insertObject('#__menu', $obj);
		if ($db->getErrorNum()) {
			return false;
		}
		$parentId		= $db->insertid();

		if($childs){
			$x	= 1;

			foreach($childs->children() as $child){
				$childObj		= new stdClass();

				// Retrieve the menu name
				$element	= (string) $child->{'name'};
				$childObj->title	= !empty($element) ? $element : '';

				// Retrieve the menu alias
				$element	= (string) $child->{'alias'};
				$childObj->alias	= !empty($element) ? $element : '';

				// Retrieve the menu link
				$element	= (string) $child->{'link'};
				$childObj->link	= !empty($element) ? $element : '';

				$childObj->menutype	= 'joombri';
				$childObj->type		= 'component';
				$childObj->published	= 1;
				$childObj->parent_id	= $parentId;
				$childObj->level	= 1 + 1;
				//$childObj->ordering	= $x;
				$childObj->access = 1;
				$childObj->path = $obj->alias.'/'.$childObj->alias;

				if (!false){
					$childObj->language	= '*';
					//J1.6: menu item ordering follow lft and rgt
					$childObj->lft 	= $obj->lft + ($x - 1)* 2 + 1;
					$childObj->rgt	= $childObj->lft + 1;
				}

				$db->insertObject('#__menu' , $childObj);
				if ($db->getErrorNum()){
					return false;
				}

				$x++;
			}
		}
		$i++;
	}
	// update memu items with component id
	if (!updateMenuItems()){
		return false;
	}

	return true;
}

function menuTypesExist(){
	$db		= JFactory::getDBO();

	$query	= 'SELECT COUNT(*) FROM #__menu_types'.
			' WHERE menutype='. $db->quote('joombri').' AND title='.$db->quote('JoomBri Menu');
	$db->setQuery($query);
	$needUpdate	= ($db->loadResult() >= 1) ? true : false;

	return $needUpdate;
}

function addDefaultMenuTypes(){
	$db		= JFactory::getDBO();
	$query	= 'INSERT INTO #__menu_types (menutype, title, description) VALUES'.
			' ('.$db->quote('joombri').','.$db->quote('JoomBri Menu').','.$db->quote('JoomBri Menu Items').')';
	$db->setQuery($query);
	$db->execute();
	if ($db->getErrorNum()){
		return false;
	}
	return true;
}

function defaultBudgetRange(){
	$db = JFactory::getDBO();
	$query = "SELECT COUNT(*) FROM #__jblance_budget";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_budget` VALUES
				('1', 'Simple Project', '30', '250', '1', '1'),
				('2', 'Very Small Project', '250', '750', '2', '1'),
				('3', 'Small Project', '750', '1500', '3', '1'),
				('4', 'Medium Project', '1500', '3000', '4', '1'),
				('5', 'Large Project', '3000', '5000', '5', '1'),
				('6', 'Larger Project', '5000', '10000', '6', '1');
				";
		$db->setQuery($query);
		$db->execute();
	}
}

function defaultCategory(){
	$db = JFactory::getDBO();
	$query = "SELECT COUNT(*) FROM #__jblance_category";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_category` VALUES 
				('1', 'Websites, IT & Software', '1', '1', '0'),
				('2', '.Net', '1', '1', '1'),
				('3', 'AJAX', '2', '1', '1'),
				('4', 'ASP', '3', '1', '1'),
				('5', 'C++ Programming', '4', '1', '1'),
				('6', 'CMS', '5', '1', '1'),
				('7', 'Computer Graphics', '6', '1', '1'),
				('8', 'Java/J2EE', '7', '1', '1'),
				('9', 'Joomla', '8', '1', '1'),
				('10', 'SQL', '10', '1', '1'),
				('11', 'Perl', '9', '1', '1'),
				('12', 'Visual Basic', '11', '1', '1'),
				('13', 'Wordpress', '12', '1', '1'),
				('14', 'YouTube', '13', '1', '1'),
				('15', 'Design, Media & Architecture', '2', '1', '0'),
				('16', '3D Animation', '1', '1', '15'),
				('17', '3ds Max', '2', '1', '15'),
				('18', 'Animation', '3', '1', '15'),
				('19', 'Brochure Design ', '4', '1', '15'),
				('20', 'Building Architecture', '5', '1', '15'),
				('21', 'CSS', '6', '1', '15'),
				('22', 'Flash', '7', '1', '15'),
				('23', 'Graphic Design', '8', '1', '15'),
				('24', 'Logo Design', '9', '1', '15'),
				('25', 'Photoshop', '10', '1', '15'),
				('26', 'PSD to HTML', '11', '1', '15'),
				('27', 'Engineering & Science', '3', '1', '0'),
				('28', 'Algorithm', '1', '1', '27'),
				('29', 'AutoCAD', '2', '1', '27'),
				('30', 'CAD/CAM', '3', '1', '27'),
				('31', 'Electrical Engineering', '4', '1', '27'),
				('32', 'Manufacturing Design', '5', '1', '27'),
				('33', 'Sales & Marketing', '4', '1', '0'),
				('34', 'Advertising', '1', '1', '33'),
				('35', 'Affiliate Marketing', '2', '1', '33'),
				('36', 'Branding', '3', '1', '33'),
				('37', 'Bulk Marketing', '4', '1', '33'),
				('38', 'Classifieds Posting ', '5', '1', '33'),
				('39', 'Internet Marketing', '6', '1', '33'),
				('40', 'Market Research', '7', '1', '33'),
				('41', 'Business & Human Resources', '5', '1', '0'),
				('42', 'Accounting', '1', '1', '41'),
				('43', 'Business Analysis', '2', '1', '41'),
				('44', 'Business Plans', '3', '1', '41'),
				('45', 'Human Resources', '4', '1', '41'),
				('46', 'Legal Research', '5', '1', '41'),
				('47', 'Project Management', '6', '1', '41'),
				('48', 'Public Relations', '7', '1', '41'),
				('49', 'Recruitment', '8', '1', '41');
				";
		$db->setQuery($query);
		$db->execute();
	}
}

function defaultConfigs(){
	$db		= JFactory::getDBO();
	$query = "SELECT COUNT(*) FROM #__jblance_config";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_config` VALUES ('1', '{\"welcomeTitle\":\"Welcome to JoomBri!\",\"currencySymbol\":\"$\",\"currencyCode\":\"USD\",\"dateFormat\":\"d-m-Y\",\"termArticleId\":\"1\",\"rssLimit\":\"10\",\"showRss\":\"1\",\"showJoombriCredit\":\"0\",\"reviewProjects\":\"0\",\"enableAddThis\":\"1\",\"addThisPubid\":\"\",\"showFeedsDashboard\":\"1\",\"feedLimitDashboard\":\"10\",\"showUsername\":\"1\",\"enableReporting\":\"1\",\"maxReport\":\"25\",\"enableGuestReporting\":\"0\",\"reportCategory\":\"Posting contact information;Fake project\\/information posted;Obscenities or harassing behaviour;Other\",\"mailFromName\":\"Freelance Support\",\"mailFromAddress\":\"support@mysite.com\",\"invoiceDetails\":\"#123, My Address,<br\\/>City,<br\\/>Country<br\\/>My Tax No:123456\",\"invoiceFormatDeposit\":\"FD-[YYYY]-[ID]\",\"invoiceFormatPlan\":\"MP-[YYYY]-[ID]\",\"invoiceFormatWithdraw\":\"WF-[YYYY]-[ID]\",\"fundDepositMin\":\"10\",\"withdrawMin\":\"25\",\"taxName\":\"VAT\",\"taxPercent\":\"12.36\",\"checkfundPickuser\":\"1\",\"checkfundAcceptoffer\":\"0\",\"enableEscrowPayment\":\"1\",\"enableWithdrawFund\":\"1\",\"projectFileType\":\"image\\/jpeg,image\\/gif,image\\/png,image\\/x-png,application\\/msword,application\\/excel,application\\/pdf,text\\/plain,application\\/x-z,application\\/octet-stream,application\\/vnd.ms-excel\",\"projectFileText\":\"jpg, gif, word, excel, pdf, zip\",\"projectMaxsize\":\"500\",\"projectMaxfileCount\":\"3\",\"imgFileType\":\"image\\/jpeg,image\\/pjpeg,image\\/gif,image\\/png,image\\/x-png,image\\/bmp,image\\/ico\",\"imgFileText\":\"jpg, gif, png, bmp, ico\",\"imgWidth\":\"300\",\"imgHeight\":\"200\",\"imgMaxsize\":\"200\",\"integrationProfile\":\"joombri\",\"integrationAvatar\":\"joombri\",\"fbApikey\":\"\",\"fbAppsecret\":\"\"}');
				";
		$db->setQuery($query);
		$db->execute();
	}
}

function defaultCustomFields(){
	$db = JFactory::getDBO();
	$query = "SELECT COUNT(*) FROM #__jblance_custom_field";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_custom_field` VALUES 
				('1', 'Basic Information', '', 'inputbox', 'left-to-right', '0', 'COM_JBLANCE_', '', '1', '0', '1', '1', '', 'group', 'profile', 'custom', '0', 'all', '', ''),
				('2', 'Gender', 'Male;\r\nFemale', '', 'left-to-right', '1', 'COM_JBLANCE_', '', '2', '0', '1', '1', null, 'Radio', 'profile', 'custom', '0', 'all', '', ''),
				('3', 'About Me', '', '', 'left-to-right', '1', 'COM_JBLANCE_', '', '1', '0', '1', '1', null, 'Textarea', 'profile', 'custom', '0', 'all', '', ''),
				('4', 'Contact Information', '', '', 'left-to-right', '0', 'COM_JBLANCE_', null, '2', '0', '1', '1', '', 'group', 'profile', 'custom', '0', 'all', '', ''),
				('5', 'Address', '', '', 'left-to-right', '4', 'COM_JBLANCE_', '', '1', '0', '1', '1', null, 'Textarea', 'profile', 'custom', '0', 'personal', '', ''),
				('6', 'Location', '', '', 'left-to-right', '4', 'COM_JBLANCE_', '', '2', '0', '1', '1', null, 'Textbox', 'profile', 'custom', '0', 'personal', '', ''),
				('7', 'Postal Code', '', '', 'left-to-right', '4', 'COM_JBLANCE_', '', '3', '0', '1', '1', null, 'Textbox', 'profile', 'custom', '0', 'personal', '', ''),
				('8', 'Phone', '', '', 'left-to-right', '4', 'COM_JBLANCE_', '', '4', '0', '1', '1', null, 'Textbox', 'profile', 'custom', '0', 'personal', '', '');
				";
		$db->setQuery($query);
		$db->execute();
	}
}

function defaultEmailTemplates(){
	$db = JFactory::getDBO();
	$query = "SELECT COUNT(*) FROM #__jblance_emailtemplate";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_emailtemplate` VALUES 
				('1', 'subscr-pending', 'Subscription Pending', 'Subscription for [NAME] at [SITENAME] - Pending', '<p>Dear [NAME],</p><p>You have been subscribed to <strong>[PLANNAME]</strong> at [SITENAME].</p><p>But your subscription has not been approved automatically. Please, contact the admin at [ADMINEMAIL] for payment details.</p><p><br />Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('2', 'subscr-approved-auto', 'Subscription Auto Approved', 'Subscription for [NAME] at [SITENAME] - Approved', '<p>Dear [NAME],</p><p>You have been subscribed to <strong>[PLANNAME]</strong> at [SITENAME].</p><p>You may now login to [SITEURL] and enjoy the benefits. You may also email to [ADMINEMAIL]</p><p><br />Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('3', 'subscr-details', 'Subscription Details', 'Subscription for [NAME] at [SITENAME]', '<p>Dear Admin,</p><p>A new user has created a subscription at [SITENAME].</p><p>Further details below:</p><p>Name.........: [NAME]<br /> Email.........: [USEREMAIL]<br /> Username...: [USERNAME]<br /> Subscr.-ID...: [SUBSCRID]<br /> Plan Name..: [PLANNAME]<br /> Gateway......: [GATEWAY]<br /> Status.........: [PLANSTATUS]</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('4', 'subscr-approved-admin', 'Subscription Admin Approved', 'Your Subscription has been approved at [SITENAME]', '<p>Dear [NAME],</p><p>Your subscription for <strong>[PLANNAME]</strong> has been approved by admin at [SITENAME].</p><p>You may now login to [SITEURL] and start using it right away.</p><p><br />Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('5', 'newuser-login', 'New User Login', 'Account details for [NAME] at [SITENAME]', '<p>Dear [NAME],</p><p>Thank you for registering at [SITENAME].</p><p>You may now log in to [SITEURL] using the username and password you registered with.</p><p><p><br />Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p></p>'),
				('6', 'newuser-activate', 'New User Activate', 'Account details for [NAME] at [SITENAME]', '<p>Dear [NAME],</p><p>Thank you for registering at [SITENAME].</p><p>Your account is created and must be activated before you can use it.</p><p>To activate the account click on the following link or copy-paste it in your browser:</p><p>[ACTLINK]</p><p>After activation you may login to [SITEURL] using the following username and password:</p><p>Username: [USERNAME]<br />Password: [PASSWORD]</p><p><br />Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('7', 'newuser-details', 'New User Details', 'Account details for [NAME] at [SITENAME]', '<p>Dear Admin,</p>\r\n<p>A new user has registered at [SITENAME].</p>\r\n<p>This e-mail contains the user details:</p>\r\n<p>Name: [NAME]<br />E-mail: [USEREMAIL]<br />Username: [USERNAME]<br />Usertype: [USERTYPE]<br />Status: [STATUS]</p>\r\n<p><strong><span style=\"color: #ff0000;\">Note:</span></strong> If the status is <strong>Pending</strong>, you need to login to [ADMINURL] and approve the user.</p>\r\n<p>Thank You.</p>\r\n<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('8', 'proj-new-notify', 'New Project Notification', 'New Project on [SITENAME]', '<p>Dear User,</p><p>New project posted in  [SITENAME].</p><p>Project Details:</p><p>Project Title: <strong>[PROJECTNAME]</strong><br />Skills Required: [CATEGORYNAME]<br />Budget: [CURRENCYSYM][BUDGETMIN] - [CURRENCYSYM][BUDGETMAX] [CURRENCYCODE]<br />Starts On: [STARTDATE]<br />Expires: [EXPIRE] Days</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('9', 'proj-newbid-notify', 'New Bid Notification', 'New Bid for Project - [PROJECTNAME]', '<p>Dear [PUBLISHERNAME],</p><p>There is a new bid for the project posted by you at [SITENAME] of which details are given below.</p><p><span style=\"text-decoration: underline;\">Project Details:</span></p><p>Project Title: <strong>[PROJECTNAME]</strong><br />Budget: [CURRENCYSYM][BUDGETMIN] - [CURRENCYSYM][BUDGETMAX] [CURRENCYCODE]<br />Starts On: [STARTDATE]<br />Expires: [EXPIRE] Days</p><p><span style=\"text-decoration: underline;\">Bid Details:</span></p><p>Bidder Name: [BIDDERNAME]<br />Bidder Username: [BIDDERUSERNAME]<br />Bid Amount: [CURRENCYSYM][BIDAMOUNT]<br />Deliver in: [DELIVERY] Days</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('10', 'proj-lowbid-notify', 'Low Bid Notification', 'Lower Bid for Project - [PROJECTNAME]', '<p>Dear User,</p><p>Someone has bid lower that your bid at [SITENAME] of which details are given below.</p><p><span style=\"text-decoration: underline;\">Project Details:</span></p><p>Project Title: <strong>[PROJECTNAME]</strong><br />Budget: [CURRENCYSYM][BUDGETMIN] - [CURRENCYSYM][BUDGETMAX] [CURRENCYCODE]<br />Starts On: [STARTDATE]<br />Expires: [EXPIRE] Days</p><p><span style=\"text-decoration: underline;\">Bid Details:</span></p><p>Bidder Username: [BIDDERUSERNAME]<br />Bid Amount: [CURRENCYSYM][BIDAMOUNT]<br />Deliver in: [DELIVERY] Days</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('11', 'proj-bidwon-notify', 'Bid Won Notification', 'You have won bid for Project - [PROJECTNAME]', '<p>Dear [BIDDERNAME],</p><p>Congratulations!</p><p>You have been picked for the project <strong>[PROJECTNAME]</strong> at [SITENAME] of which details are given below.</p><p><span style=\"text-decoration: underline;\">Details:</span></p><p>Project Title: <strong>[PROJECTNAME]</strong><br />Bid Amount: [CURRENCYSYM][BIDAMOUNT] [CURRENCYCODE]<br />Delivery: [DELIVERY] Days</p><p>Please login to [SITEURL] and browse to Dashboard-&gt;My Bids to Accept/Reject the offer.</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('12', 'proj-denied-notify', 'Project Denied Notification', 'Your offer is denied for - [PROJECTNAME]', '<p>Dear [PUBLISHERNAME],</p><p>Unfortunately the user ( [BIDDERUSERNAME] ) you picked for the project <strong>[PROJECTNAME]</strong> at [SITENAME] has denied your offer.</p><p>Please login to [SITEURL] and browse to Dashboard-&gt;My Projects and pick a different user for the project.</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('13', 'proj-accept-notify', 'Project Accepted Notification', 'Your offer is accepted for - [PROJECTNAME]', '<p>Dear [PUBLISHERNAME],</p><p>Congratulations!</p><p>The user ( [BIDDERUSERNAME] ) you picked for the project <strong>[PROJECTNAME]</strong> at [SITENAME] has accepted your offer and the project is awarded to him.</p><p>Please login to [SITEURL] and browse to Dashboard-&gt;My Projects and pay the user once the project is completed.</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('14', 'fin-witdrw-request', 'Withdraw Fund Request', 'Request for fund withdrawal', '<p>Dear Admin,</p><p>You have received a request for fund withdrawal. Here are further details:</p><p>Name: [NAME]<br />Username: [USERNAME]<br />Invoice No: [INVOICENO]<br />Gateway: [GATEWAY]</p><p>Please log in to [ADMINURL] and go to <span style=\"color: #0000ff;\">JoomBri Dashboard -&gt; Withdrawals</span> to approve or delete the request.</p><p>Thank You.</p>'),
				('15', 'fin-witdrw-approved', 'Withdraw Request Approved', 'Your withdraw fund request approved at [SITENAME]', '<p>Dear [NAME],</p><p>Your request for fund withdrawal for the amount [CURRENCYSYM] [AMOUNT] (<strong>Invoice No. [INVOICENO]</strong>) has been approved at [SITENAME] by admin.</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('16', 'fin-escrow-released', 'Escrow Payment Released', 'Escrow payment released from [SENDERUSERNAME]', '<p>Dear [RECEIVEUSERNAME],</p><p>Escrow payment received from [SENDERUSERNAME].</p><p><span style=\"text-decoration: underline;\">Details:</span></p><p>Release Date: [RELEASEDATE]<br />Amount: [CURRENCYSYM] [AMOUNT]<br />Project: [PROJECTNAME]<br />Note: [NOTE]</p><p>Please login to [SITEURL] and browse to <span style=\"color: #0000ff;\">Dashboard -&gt; Manage Payments -&gt; Incoming Escrow Payments</span> to Accept the payment.</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('17', 'fin-escrow-accepted', 'Escrow Payment Accepted', 'Escrow payment accepted by [RECEIVEUSERNAME]', '<p>Dear [SENDERUSERNAME],</p><p>Escrow payment you sent to [RECEIVEUSERNAME] has accepted it.</p><p><span style=\"text-decoration: underline;\">Details:</span></p><p>Release Date: [RELEASEDATE]<br />Amount: [CURRENCYSYM] [AMOUNT]<br />Project: [PROJECTNAME]<br />Note: [NOTE]</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('18', 'fin-deposit-alert', 'Deposit Fund Alert', 'A user has deposited fund - [STATUS]', '<p>Dear Admin,</p><p>A user ([USERNAME]) has deposited fund into his account. Here are further details:</p><p>Name: [NAME]<br />Username: [USERNAME]<br />Amount: [CURRENCYSYM] [AMOUNT]<br />Invoice No: [INVOICENO]<br />Gateway: [GATEWAY]<br />Status: [STATUS]</p><p>If the status is pending/not approved, please log in to [ADMINURL] and go to <span style=\"color: #0000ff;\">JoomBri Dashboard -&gt; Deposits</span> to approve or delete the payment.</p><p>Thank You.</p>'),
				('19', 'fin-deposit-approved', 'Deposit Fund Approved', 'Your deposit fund request approved at [SITENAME]', '<p>Dear [NAME],</p><p>Your request for fund deposit for the amount [CURRENCYSYM] [AMOUNT] (<strong>Invoice No. [INVOICENO]</strong>) has been approved at [SITENAME] by admin.</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('20', 'pm-new-notify', 'New PM Notification', 'You have received a message via [SITENAME]', '<p>Dear [RECIPIENT_USERNAME],</p><p>You have received a message from [SENDER_USERNAME].</p><p>Subject: <strong>[MSG_SUBJECT]</strong><br />Message: [MSG_BODY]</p><p>Please login to [SITEURL], browse to Dashboard-&gt;Private Messages to read your message.</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('21', 'proj-pending-approval', 'Project Pending Approval', 'Project : [PROJECTNAME] - pending for approval', '<p>Dear Admin,</p>\r\n<p>There is a new project <strong>[PROJECTNAME]</strong> posted by [PUBLISHERUSERNAME] at [SITENAME] which is pending for approval from you.</p>\r\n<p>Please log in to [ADMINURL] and go to <span style=\"color: #0000ff;\">JoomBri Dashboard -&gt; Projects</span> to approve or delete the request.</p>\r\n<p>Thank You.</p>'),
				('22', 'proj-approved', 'Project Approved', 'Your project : [PROJECTNAME] - Approved', '<p>Dear [PUBLISHERNAME],</p>\r\n<p>Your project <strong>[PROJECTNAME]</strong> has been approved and it is now live at [SITENAME].</p>\r\n<p>See it live at [PROJECTURL]</p>\r\n<p>Thank You.</p>'),
				('23', 'newuser-pending-approval', 'New User Pending Approval', 'Account details for [NAME] at [SITENAME] - Pending', '<p>Dear [NAME],</p>\r\n<p>Thank you for registering at [SITENAME].</p>\r\n<p>Your account is created and it is still pending moderation from the site. Until the site moderators approves your account, you will not be able to login. Once your account is approved, you will receive another notification email from us.</p>\r\n<p>Thank You.</p>\r\n<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('24', 'newuser-account-approved', 'New User Account Approved', 'Your Account is Approved on [SITENAME]', '<p>Dear [NAME],</p>\r\n<p>Your account has been approved and you may now login to the site at [SITEURL]</p>\r\n<p>This e-mail contains the your registration details:</p>\r\n<p>Name: [NAME]<br />E-mail: [EMAIL]<br />Username: [USERNAME]</p>\r\n<p>Thank You.</p>\r\n<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('25', 'report-default-action', 'Reporting Default Action', 'Reporting: Default action taken on [SITENAME]', '<p>Dear Admin,</p>\r\n<p>A default Reporting action has been executed automatically. The details are given below.</p>\r\n<p>Report Item: [TYPE]<br />Total Reports: [COUNT]<br />Action Taken: [ACTION]<br />Link: [ITEMLINK]</p>\r\n<p>Thank You.</p>\r\n<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('26', 'proj-newforum-notify', 'New Forum Message Notification', 'New Forum Message - [PROJECTNAME]', '<p>Dear User,</p>\r\n<p>A new message has been posted on [SITENAME] under public clarification board of the project [PROJECTNAME].</p>\r\n<p>Posted By: [POSTERUSERNAME]<br />Project Link: [PROJECTURL]<br />Subject: <strong>[PROJECTNAME]</strong><br />Message: [FORUMMESSAGE]</p>\r\n<p>Thank You.</p>\r\n<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('27', 'newuser-facebook-signin', 'New User Facebook Sign in', 'Account details for [NAME] at [SITENAME]', '<p>Dear [NAME],</p>\r\n<p>You have successfully signed in using your Facebook account.</p>\r\n<p>You may now log in to [SITEURL] with the details are given below.</p>\r\n<p>Username: [USERNAME]<br />Password: [PASSWORD]</p>\r\n<p>Thank You.</p>\r\n<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>');
			";
		$db->setQuery($query);
		$db->execute();
	}
}

function defaultPaymentModes(){
	$db = JFactory::getDBO();
	$query = "SELECT COUNT(*) FROM #__jblance_paymode";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_paymode` VALUES 
				('1', 'Bank Transfer', '1', '1', 'BT', 'banktransfer', '1', '2', 'Withdraw funds to your Bank account.', '15', '0', '{\"btAccnum\":\"000123456789\",\"btBankname\":\"My Bank Name\",\"btAccHoldername\":\"My Name\",\"btIBAN\":\"\",\"btSWIFT\":\"\",\"btNotifyEmail\":\"notify@mysite.com\",\"btNotifyFaxno\":\"+1 2345678910\"}'),
				('2', 'PayPal', '1', '2', 'PP', 'paypal', '1', '2', 'Withdraw funds to your PayPal account.', '0.3', '2.9', '{\"paypalEmail\":\"paypal@mysite.com\",\"ppCurrency\":\"USD\",\"ppTestmode\":\"0\"}'),
				('3', 'Moneybookers', '1', '3', 'MB', 'moneybookers', '1', '2', 'Withdraw funds to your Moneybookers account.', '0.3', '2.9', '{\"mbPaymentEmail\":\"moneybookers@mysite.com\",\"mbMerchantID\":\"\",\"mbSecret\":\"\",\"mbCurrency\":\"USD\"}'),
				('4', 'Authorize.Net', '1', '4', 'AN', 'authorizenet', '0', '0', 'Withdraw funds to your Authorize.Net account.', '0.3', '2.9', '{\"authAPILogin\":\"\",\"authTransKey\":\"\",\"authCurrency\":\"USD\",\"authTestmode\":\"0\"}'),
				('5', '2Checkout', '1', '5', '2CO', 'twocheckout', '0', '0', 'Withdraw funds to your 2Checkout account.', '0.3', '2.9', '{\"twocoVendorID\":\"\",\"twocoSecretword\":\"\",\"twocoTestmode\":\"0\"}');
				";
		$db->setQuery($query);
		$db->execute();
	}
}

function defaultPlans(){
	$db = JFactory::getDBO();
	$query = "SELECT COUNT(*) FROM #__jblance_plan";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_plan` VALUES 
				('1', 'Starter', '15', 'days', '0', '0', '1', '0', '1', '1', '1', '', '5', '', 'Thank you subscribing to this plan.', '{\"portfolioCount\":\"3\",\"buyFeeAmtPerProject\":\"0\",\"buyFeePercentPerProject\":\"0\",\"buyChargePerProject\":\"0\",\"buyFeePerFeaturedProject\":\"0\",\"buyFeePerUrgentProject\":\"0\",\"buyFeePerPrivateProject\":\"0\",\"buyFeePerSealedProject\":\"0\",\"buyFeePerNDAProject\":\"0\",\"buyProjectCount\":\"0\",\"flFeeAmtPerProject\":\"5\",\"flFeePercentPerProject\":\"10\",\"flChargePerBid\":\"5\",\"flBidCount\":\"30\"}', '1', '1', '0', '0'),
				('2', 'Professional', '3', 'months', '25', '5', '1', '0', '2', '0', '1', '', '10', '', 'Thank you subscribing to this plan.', '{\"portfolioCount\":\"5\",\"buyFeeAmtPerProject\":\"0\",\"buyFeePercentPerProject\":\"0\",\"buyChargePerProject\":\"0\",\"buyFeePerFeaturedProject\":\"0\",\"buyFeePerUrgentProject\":\"0\",\"buyFeePerPrivateProject\":\"0\",\"buyFeePerSealedProject\":\"0\",\"buyFeePerNDAProject\":\"0\",\"buyProjectCount\":\"0\",\"flFeeAmtPerProject\":\"3\",\"flFeePercentPerProject\":\"3\",\"flChargePerBid\":\"3\",\"flBidCount\":\"0\"}', '1', '0', '0', '0'),
				('3', 'Basic', '15', 'days', '0', '0', '1', '0', '3', '1', '1', '', '5', '', 'Thank you subscribing to this plan.', '{\"portfolioCount\":\"3\",\"buyFeeAmtPerProject\":\"5\",\"buyFeePercentPerProject\":\"5\",\"buyChargePerProject\":\"5\",\"buyFeePerFeaturedProject\":\"25\",\"buyFeePerUrgentProject\":\"5\",\"buyFeePerPrivateProject\":\"5\",\"buyFeePerSealedProject\":\"5\",\"buyFeePerNDAProject\":\"15\",\"buyProjectCount\":\"30\",\"flFeeAmtPerProject\":\"0\",\"flFeePercentPerProject\":\"0\",\"flChargePerBid\":\"0\",\"flBidCount\":\"0\"}', '2', '1', '0', '0'),
				('4', 'Premium', '3', 'months', '50', '10', '1', '0', '4', '5', '1', '', '25', '', 'Thank you subscribing to this plan.', '{\"portfolioCount\":\"5\",\"buyFeeAmtPerProject\":\"3\",\"buyFeePercentPerProject\":\"3\",\"buyChargePerProject\":\"3\",\"buyFeePerFeaturedProject\":\"15\",\"buyFeePerUrgentProject\":\"5\",\"buyFeePerPrivateProject\":\"5\",\"buyFeePerSealedProject\":\"5\",\"buyFeePerNDAProject\":\"10\",\"buyProjectCount\":\"0\",\"flFeeAmtPerProject\":\"0\",\"flFeePercentPerProject\":\"0\",\"flChargePerBid\":\"0\",\"flBidCount\":\"0\"}', '2', '0', '0', '0');
				";
		$db->setQuery($query);
		$db->execute();
	}
}

function defaultUserGroups(){
	$db = JFactory::getDBO();
	$query = "SELECT COUNT(*) FROM #__jblance_usergroup";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_usergroup` VALUES 
				('1', 'Freelancer', '<p>Freelancers enjoy the following features:</p>\r\n<h3><strong>Features:</strong></h3>\r\n<ul>\r\n<li>Simple registration.</li>\r\n<li>Search and apply for Projects in just a few clicks.</li>\r\n<li>Get notified when a new project is posted.</li>\r\n</ul>', '0', '1', '0', '1', '{\"allowBidProjects\":\"1\",\"allowPostProjects\":\"0\",\"searchUserGroupIds\":\"0\"}', '0', '2', '0'),
				('2', 'Buyer', '<p>Try Freelance Solutions from our site. We enable you to,</p>\r\n<h3><strong>Features:</strong></h3>\r\n<ul>\r\n<li>Post projects for free in easy steps and start receiving bids.</li>\r\n<li>Pay Freelancers when satisfied.</li>\r\n<li>Get notified immediately when there is a new bid.</li>\r\n</ul>', '0', '1', '0', '3', '{\"allowBidProjects\":\"0\",\"allowPostProjects\":\"1\",\"searchUserGroupIds\":\"1\"}', '0', '2', '0');
				";
		$db->setQuery($query);
		$db->execute();
	}
}

function defaultUserGroupFields(){
	$db = JFactory::getDBO();
	$query = "SELECT COUNT(*) FROM #__jblance_usergroup_field";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_usergroup_field` VALUES 
				('1', '1', '3'),
				('2', '1', '2'),
				('3', '1', '5'),
				('4', '1', '6'),
				('5', '1', '7'),
				('6', '1', '8'),
				('7', '1', '1'),
				('8', '1', '4'),
				('9', '2', '3'),
				('10', '2', '2'),
				('11', '2', '5'),
				('12', '2', '6'),
				('13', '2', '7'),
				('14', '2', '8'),
				('15', '2', '1'),
				('16', '2', '4');
		";
		$db->setQuery($query);
		$db->execute();
	}
}

// update section
//1.0.0 to 1.0.1
function upgrade100_101(){
	//Add the PM entry to email template
	$db = JFactory::getDBO();
	$query = "SELECT id FROM #__jblance_emailtemplate WHERE id=20";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
		INSERT INTO `#__jblance_emailtemplate` VALUES ('20', 'pm-new-notify', 'New PM Notification', 'You have received a message via [SITENAME]', '<p>Dear [RECIPIENT_USERNAME],</p><p>You have received a message from [SENDER_USERNAME].</p><p>Subject: <strong>[MSG_SUBJECT]</strong><br />Message: [MSG_BODY]</p><p>Please login to [SITEURL], browse to Dashboard-&gt;Private Messages to read your message.</p><p>Thank You.</p><p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>');
		";
		$db->setQuery($query);
		$db->execute();
	}
}
	
//1.0.1 to 1.0.2
function upgrade101_102(){
	JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
	$config= JTable::getInstance('config', 'Table');
	$config->load(1);
	
	// add integration params to config table.
	$registry = new JRegistry;
	$registry->loadString($config->params);
	if(!$registry->get('integrationProfile')){
		$string = '{"integrationProfile":"auto","integrationAvatar":"auto"}';
		$registry->loadString($string);
	
		$config->params = $registry->toString();
	
		if(!$config->check())
			JError::raiseError(500, $config->getError());
	
		if(!$config->store())
			JError::raiseError(500, $config->getError());
	
		if(!$config->checkin())
			JError::raiseError(500, $config->getError());
	}
}

//1.0.2 to 1.0.3
function upgrade102_103(){
	
	JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
	
	//add private, featured, urgent, and sealed fees to the plan table.
	$db = JFactory::getDBO();
	$planTbl= JTable::getInstance('plan', 'Table');
	$query = "SELECT * FROM #__jblance_plan";
	$db->setQuery($query);
	$plans = $db->loadObjectList();

	foreach($plans as $plan){
		
		$planTbl->load($plan->id);
		$registry = new JRegistry;
		$registry->loadString($plan->params);
	
		if(!$registry->get('buyFeePerUrgentProject')){
			$string = '{"buyFeePerUrgentProject":"5","buyFeePerPrivateProject":"5","buyFeePerSealedProject":"5"}';
			$registry->loadString($string);
		
			$planTbl->params = $registry->toString();
		
			if(!$planTbl->check())
				JError::raiseError(500, $planTbl->getError());
		
			if(!$planTbl->store())
				JError::raiseError(500, $planTbl->getError());
		
			if(!$planTbl->checkin())
				JError::raiseError(500, $planTbl->getError());
		}
	}
	
	// add rss, reviewproject params to config table.
	$config= JTable::getInstance('config', 'Table');
	$config->load(1);
	
	$registry = new JRegistry;
	$registry->loadString($config->params);
	if(!$registry->get('reviewProjects')){
		$string = '{"reviewProjects":"0","rssLimit":"10","showRss":"1"}';
		$registry->loadString($string);
	
		$config->params = $registry->toString();
	
		if(!$config->check())
			JError::raiseError(500, $config->getError());
	
		if(!$config->store())
			JError::raiseError(500, $config->getError());
	
		if(!$config->checkin())
			JError::raiseError(500, $config->getError());
	}
	
	//Add the project pending/approve entry to email template
	$query = "SELECT id FROM #__jblance_emailtemplate WHERE id=21";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_emailtemplate` VALUES 
				('21', 'proj-pending-approval', 'Project Pending Approval', 'Project : [PROJECTNAME] - pending for approval', '<p>Dear Admin,</p>\r\n<p>There is a new project <strong>[PROJECTNAME]</strong> posted by [PUBLISHERUSERNAME] at [SITENAME] which is pending for approval from you.</p>\r\n<p>Please log in to [ADMINURL] and go to <span style=\"color: #0000ff;\">JoomBri Dashboard -&gt; Projects</span> to approve or delete the request.</p>\r\n<p>Thank You.</p>'),
				('22', 'proj-approved', 'Project Approved', 'Your project : [PROJECTNAME] - Approved', '<p>Dear [PUBLISHERNAME],</p>\r\n<p>Your project <strong>[PROJECTNAME]</strong> has been approved and it is now live at [SITENAME].</p>\r\n<p>See it live at [PROJECTURL]</p>\r\n<p>Thank You.</p>');
				";
		$db->setQuery($query);
		$db->execute();
	}
}

//1.0.3 to 1.0.4
function upgrade103_104(){
	JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
	$db = JFactory::getDBO();
	
	// Add the user pending/approve & reporting default action entries to email template
	$query = "SELECT id FROM #__jblance_emailtemplate WHERE id=23";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_emailtemplate` VALUES 
				('23', 'newuser-pending-approval', 'New User Pending Approval', 'Account details for [NAME] at [SITENAME] - Pending', '<p>Dear [NAME],</p>\r\n<p>Thank you for registering at [SITENAME].</p>\r\n<p>Your account is created and it is still pending moderation from the site. Until the site moderators approves your account, you will not be able to login. Once your account is approved, you will receive another notification email from us.</p>\r\n<p>Thank You.</p>\r\n<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('24', 'newuser-account-approved', 'New User Account Approved', 'Your Account is Approved on [SITENAME]', '<p>Dear [NAME],</p>\r\n<p>Your account has been approved and you may now login to the site at [SITEURL]</p>\r\n<p>This e-mail contains the your registration details:</p>\r\n<p>Name: [NAME]<br />E-mail: [EMAIL]<br />Username: [USERNAME]</p>\r\n<p>Thank You.</p>\r\n<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>'),
				('25', 'report-default-action', 'Reporting Default Action', 'Reporting: Default action taken on [SITENAME]', '<p>Dear Admin,</p>\r\n<p>A default Reporting action has been executed automatically. The details are given below.</p>\r\n<p>Report Item: [TYPE]<br />Total Reports: [COUNT]<br />Action Taken: [ACTION]<br />Link: [ITEMLINK]</p>\r\n<p>Thank You.</p>\r\n<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>');
				";
		$db->setQuery($query);
		$db->execute();
	}
	
	// Add feeds, social and reporting params to config table.
	$config= JTable::getInstance('config', 'Table');
	$config->load(1);
	
	$registry = new JRegistry;
	$registry->loadString($config->params);
	if(!$registry->get('enableAddThis')){
		$string = '{"enableAddThis":"1","addThisPubid":"","showFeedsDashboard":"1","feedLimitDashboard":"10","enableReporting":"1","maxReport":"25","enableGuestReporting":"0","reportCategory":"Posting contact information;Fake project\/information posted;Obscenities or harassing behaviour;Other"}';
		$registry->loadString($string);
	
		$config->params = $registry->toString();
	
		if(!$config->check())
			JError::raiseError(500, $config->getError());
	
		if(!$config->store())
			JError::raiseError(500, $config->getError());
	
		if(!$config->checkin())
			JError::raiseError(500, $config->getError());
	}
}

function upgrade104_105(){
	$db = JFactory::getDBO();
	$query = "SELECT COUNT(*) FROM #__jblance_budget";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_budget` VALUES
				('1', 'Simple Project', '30', '250', '1', '1'),
				('2', 'Very Small Project', '250', '750', '2', '1'),
				('3', 'Small Project', '750', '1500', '3', '1'),
				('4', 'Medium Project', '1500', '3000', '4', '1'),
				('5', 'Large Project', '3000', '5000', '5', '1'),
				('6', 'Larger Project', '5000', '10000', '6', '1');
				";
		$db->setQuery($query);
		$db->execute();
	}
}

function upgrade105_106(){
	$db = JFactory::getDBO();
	
	// Add MB entries to pay mode
	$query = "SELECT id FROM #__jblance_paymode WHERE id=3";
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_paymode` VALUES 
				('3', 'Moneybookers', '1', '3', 'MB', 'moneybookers', '0', '0', 'Withdraw funds to your Moneybookers account.', '0.3', '2.9', '{\"mbPaymentEmail\":\"moneybookers@mysite.com\",\"mbMerchantID\":\"\",\"mbSecret\":\"\",\"mbCurrency\":\"USD\"}'),
				('4', 'Authorize.Net', '1', '4', 'AN', 'authorizenet', '0', '0', 'Withdraw funds to your Authorize.Net account.', '0.3', '2.9', '{\"authAPILogin\":\"\",\"authTransKey\":\"\",\"authCurrency\":\"USD\",\"authTestmode\":\"0\"}'),
				('5', '2Checkout', '1', '5', '2CO', 'twocheckout', '0', '0', 'Withdraw funds to your 2Checkout account.', '0.3', '2.9', '{\"twocoVendorID\":\"\",\"twocoSecretword\":\"\",\"twocoTestmode\":\"0\"}');
				";
		$db->setQuery($query);
		$db->execute();
	}
}

function upgrade106_107(){
	$db = JFactory::getDBO();
	
	//add the existing users to notification table.
	$query = "SELECT user_id FROM #__jblance_user";
	$db->setQuery($query);
	$users = $db->loadColumn();
	
	$query = "SELECT user_id FROM #__jblance_notify";
	$db->setQuery($query);
	$notify = $db->loadColumn();
	
	foreach($users as $user){
		if(!in_array($user, $notify)){
			$obj = new stdClass();
			$obj->user_id = $user;
			$db->insertObject('#__jblance_notify', $obj);
		}
	}
}

function upgrade108_109(){
	JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
	
	//add NDA fee to the plan table.
	$db = JFactory::getDBO();
	$planTbl= JTable::getInstance('plan', 'Table');
	$query = "SELECT * FROM #__jblance_plan";
	$db->setQuery($query);
	$plans = $db->loadObjectList();
	
	foreach($plans as $plan){
	
		$planTbl->load($plan->id);
		$registry = new JRegistry;
		$registry->loadString($plan->params);
	
		if(!$registry->get('buyFeePerNDAProject')){
			$string = '{"buyFeePerNDAProject":"10"}';
			$registry->loadString($string);
	
			$planTbl->params = $registry->toString();
	
			if(!$planTbl->check())
				JError::raiseError(500, $planTbl->getError());
	
			if(!$planTbl->store())
				JError::raiseError(500, $planTbl->getError());
	
			if(!$planTbl->checkin())
				JError::raiseError(500, $planTbl->getError());
		}
	}
}

function upgrade11B1_11B2(){
	JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
	$config= JTable::getInstance('config', 'Table');
	$config->load(1);
	
	// add payment enable/disable params to config table.
	$registry = new JRegistry;
	$registry->loadString($config->params);
	if(!$registry->get('enableEscrowPayment')){
		$string = '{"enableEscrowPayment":"1","enableWithdrawFund":"1"}';
		$registry->loadString($string);
	
		$config->params = $registry->toString();
	
		if(!$config->check())
			JError::raiseError(500, $config->getError());
	
		if(!$config->store())
			JError::raiseError(500, $config->getError());
	
		if(!$config->checkin())
			JError::raiseError(500, $config->getError());
	}
}

function upgrade11B2_110(){
	JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
	
	//add charge per project/bid and portfolio to the plan table.
	$db = JFactory::getDBO();
	$planTbl= JTable::getInstance('plan', 'Table');
	$query = "SELECT * FROM #__jblance_plan";
	$db->setQuery($query);
	$plans = $db->loadObjectList();
	
	foreach($plans as $plan){
	
		$planTbl->load($plan->id);
		$registry = new JRegistry;
		$registry->loadString($plan->params);
	
		if(!$registry->get('portfolioCount')){
			$string = '{"portfolioCount":"3","buyChargePerProject":"0","flChargePerBid":"0"}';
			$registry->loadString($string);
	
			$planTbl->params = $registry->toString();
	
			if(!$planTbl->check())
				JError::raiseError(500, $planTbl->getError());
	
			if(!$planTbl->store())
				JError::raiseError(500, $planTbl->getError());
	
			if(!$planTbl->checkin())
				JError::raiseError(500, $planTbl->getError());
		}
	}
}

function upgrade110_111(){
	$db = JFactory::getDBO();
	//Add the new forum notifcation entry to email template
	$query = "SELECT id FROM #__jblance_emailtemplate WHERE templatefor=".$db->quote('proj-newforum-notify');
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_emailtemplate` (templatefor, title, subject, body) VALUES ('proj-newforum-notify', 'New Forum Message Notification', 'New Forum Message - [PROJECTNAME]', '<p>Dear User,</p>\r\n<p>A new message has been posted on [SITENAME] under public clarification board of the project [PROJECTNAME].</p>\r\n<p>Posted By: [POSTERUSERNAME]<br />Project Link: [PROJECTURL]<br />Subject: <strong>[PROJECTNAME]</strong><br />Message: [FORUMMESSAGE]</p>\r\n<p>Thank You.</p>\r\n<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>');
				";
		$db->setQuery($query);
		$db->execute();
	}
}

function upgrade111_112(){
	JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
	$config= JTable::getInstance('config', 'Table');
	$config->load(1);
	
	// add username/name params to config table.
	$registry = new JRegistry;
	$registry->loadString($config->params);
	if(!$registry->get('showUsername')){
		$string = '{"showUsername":"1","imgFileText":"jpg, gif, png, bmp, ico"}';
		$registry->loadString($string);
	
		$config->params = $registry->toString();
	
		if(!$config->check())
			JError::raiseError(500, $config->getError());
	
		if(!$config->store())
			JError::raiseError(500, $config->getError());
	
		if(!$config->checkin())
			JError::raiseError(500, $config->getError());
	}
}

function upgrade114_115(){
	JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
	
	//add private, featured, urgent, and sealed fees to the plan table.
	$db = JFactory::getDBO();
	$planTbl= JTable::getInstance('plan', 'Table');
	$query = "SELECT * FROM #__jblance_plan";
	$db->setQuery($query);
	$plans = $db->loadObjectList();

	foreach($plans as $plan){
		
		$planTbl->load($plan->id);
		$registry = new JRegistry;
		$registry->loadString($plan->params);
	
		if(!$registry->get('buyProjectCount')){
			$string = '{"buyProjectCount":"0","flBidCount":"0"}';
			$registry->loadString($string);
		
			$planTbl->params = $registry->toString();
		
			if(!$planTbl->check())
				JError::raiseError(500, $planTbl->getError());
		
			if(!$planTbl->store())
				JError::raiseError(500, $planTbl->getError());
		
			if(!$planTbl->checkin())
				JError::raiseError(500, $planTbl->getError());
		}
	}
}

function upgrade116_117(){
	
	// Uninstall Feeds in System plugins
	$db = JFactory::getDbo();
	$query = "SELECT extension_id FROM #__extensions WHERE type='plugin' AND folder='system' AND element='jblancefeeds'";
	$db->setQuery($query);
	$id = $db->loadResult();
	if($id){
		$installer = new JInstaller;
		$result = $installer->uninstall('plugin', $id);
	}
}

function upgrade117_118(){

	// copying values from `valuetext` to `value` column
	$db = JFactory::getDbo();
	$fields = $db->getTableColumns("#__jblance_custom_field_value");

	//check if `valuetext' column exists in custom field value table
	if(array_key_exists("valuetext", $fields)){
		// get all the records from the table
		$query = "SELECT * FROM #__jblance_custom_field_value";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		foreach($rows as $row){
			// if the `valuetext` column has some data, copy it to `value` column
			if(!empty($row->valuetext)){
				$query = "UPDATE #__jblance_custom_field_value SET `value`=`valuetext` WHERE id=".$db->quote($row->id);
				$db->setQuery($query);
				$db->execute();
			}
		}

		// After copying the column, remove it
		$query = "ALTER TABLE `#__jblance_custom_field_value` DROP COLUMN `valuetext`;";
		$db->setQuery($query);
		$db->execute();
	}
	
	// add Facebook Connect settings
	JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
	$config= JTable::getInstance('config', 'Table');
	$config->load(1);
	
	// add username/name params to config table.
	$registry = new JRegistry;
	$registry->loadString($config->params);
	if(!$registry->get('fbApikey')){
		$string = '{"fbApikey":"","fbAppsecret":"","checkfundPickuser":"1","checkfundAcceptoffer":"0"}';
		$registry->loadString($string);
	
		$config->params = $registry->toString();
	
		if(!$config->check())
			JError::raiseError(500, $config->getError());
	
		if(!$config->store())
			JError::raiseError(500, $config->getError());
	
		if(!$config->checkin())
			JError::raiseError(500, $config->getError());
	}
	
	//Add the new user Facebook sign-in entry to email template
	$query = "SELECT id FROM #__jblance_emailtemplate WHERE templatefor=".$db->quote('newuser-facebook-signin');
	$db->setQuery($query);
	if(!$db->loadResult()){
		$query = "
				INSERT INTO `#__jblance_emailtemplate` (templatefor, title, subject, body) VALUES ('newuser-facebook-signin', 'New User Facebook Sign in', 'Account details for [NAME] at [SITENAME]', '<p>Dear [NAME],</p>\r\n<p>You have successfully signed in using your Facebook account.</p>\r\n<p>You may now log in to [SITEURL] with the details are given below.</p>\r\n<p>Username: [USERNAME]<br />Password: [PASSWORD]</p>\r\n<p>Thank You.</p>\r\n<p>Please do not respond to this message as it is automatically generated and is for information purposes only.</p>');
				";
		$db->setQuery($query);
		$db->execute();
	}
}