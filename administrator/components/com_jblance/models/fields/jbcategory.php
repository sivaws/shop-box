<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	02 February 2013
 * @file name	:	models/admconfig.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.form.formfield');
 require_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/jblance.php');
 
class JFormFieldJbCategory extends JFormField {
 
	protected $type = 'JbCategory';
 
	// getLabel() left out
 
	public function getInput() {
		
		$select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
		$attribs = "class='inputbox' size='15'";
		$categtree = $select->getSelectCategoryTree($this->name, $this->value, 'COM_JBLANCE_ALL_CATEGORIES', $attribs, '', true);
		return $categtree;
	}
}