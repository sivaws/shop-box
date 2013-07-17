<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	29 March 2012
 * @file name	:	modules/mod_jblancesearch/helper.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */

 // no direct access
 defined('_JEXEC') or die('Restricted access');
 include_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/jblance.php');	//include this helper file to make the class accessible in all other PHP files
 JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');

class ModJblanceSearchHelper {	

	public static function getListJobCateg(){
		$select = JblanceHelper::get('helper.select');		// create an instance of the class selectHelper
		$attribs = "class='span2' size='1'";
		$lists = $select->getSelectCategoryTree('id_categ', 0, 'MOD_JBLANCE_SEARCH_ALL', $attribs, '');
		return $lists;
	}
	
	public static function getSelectProjectStatus(){
		$types[] = JHTML::_('select.option', '', JText::_('MOD_JBLANCE_SEARCH_ALL'));
		$types[] = JHTML::_('select.option', 'COM_JBLANCE_OPEN', JText::_('MOD_JBLANCE_OPEN'));
		$types[] = JHTML::_('select.option', 'COM_JBLANCE_FROZEN', JText::_('MOD_JBLANCE_FROZEN'));
		$types[] = JHTML::_('select.option', 'COM_JBLANCE_CLOSED', JText::_('MOD_JBLANCE_CLOSED'));
	
		$lists 	 = JHTML::_('select.genericlist', $types, 'status', 'class="span2" size="1"', 'value', 'text', '');
	
		return $lists;
	}
	
}

?>