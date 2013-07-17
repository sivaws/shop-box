<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	helpers/select.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

class SelectHelper {
	
	function getSelectCategoryTree($var, $default, $title, $attribs, $event, $group = false){
		$db	= JFactory::getDBO();
	
		//if attribs is empty, then set a default one.
		if(empty($attribs))
			$attribs = "class='inputbox' size='10'";
	
		$query = 'SELECT * FROM #__jblance_category WHERE parent=0 AND published=1 ORDER BY ordering';
		$db->setQuery($query);
		$categs = $db->loadObjectList();
	
		$types[] = JHTML::_('select.option', '', '-- '.JText::_($title).' --');
	
		if(!$group){
			foreach($categs as $categ) {
				$indent = '';
				$types[] = JHTML::_('select.option', $categ->id, $categ->category);
			
				$subs = $this->getSubcategories($categ->id, $indent, '', 0);
				foreach($subs as $sub) {
					$types[] = JHTML::_('select.option', $sub->id, $sub->category);
				}
			}
		}
		else {
			foreach($categs as $categ) {
				$indent = '';
				$types[] = JHtml::_('select.optgroup', $categ->category);	// Start group:
			
				$subs = $this->getSubcategories($categ->id, $indent, '', 0);
				foreach($subs as $sub) {
					$types[] = JHTML::_('select.option', $sub->id, $sub->category);
				}
				$types[] = JHtml::_('select.optgroup', $categ->category);	// Finish group:
			}
		}
		
		$lists 	= JHTML::_('select.genericlist', $types, $var, "$attribs $event", 'value', 'text', $default);
		return $lists;
	}
	
	// list subcats as tree
	function getSubcategories($parent, $indent, $init, $type = 1){
		$db =JFactory::getDBO();
		
		if($init)
			$tree = $init;
		else
			$tree = array();
	
		$db->setQuery("SELECT * FROM #__jblance_category WHERE parent =".$parent." ORDER BY ordering");
		$rows = $db->loadObjectList();
		
		foreach($rows as $v){
			if($type){
				$pre 	= '<span class="gi">|&mdash;</span>';
				$spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			else {
				$pre 	= '- ';
				$spacer = '.  ';
			}
			$v->category = $indent.$pre.$v->category;
			$tree[] = $v;
			$tree = $this->getSubcategories($v->id, $indent.$spacer, $tree, $type);
		}
		return $tree;
	}
	
	function getSelectPrivacy($var, $default, $visible){
		
		if($visible == 'all'){
			$put[] = JHTML::_('select.option',  0, JText::_('COM_JBLANCE_PUBLIC'));
			$put[] = JHTML::_('select.option',  10, JText::_('COM_JBLANCE_SITE_MEMBERS'));
			$put[] = JHTML::_('select.option',  20, JText::_('COM_JBLANCE_ONLY_ME'));
			$privacy = JHTML::_('select.genericlist', $put, $var, 'class=input-medium', 'value', 'text', $default);
		}
		elseif($visible == 'personal'){
			$privacy = '<input type="hidden" id="'.$var.'" name="'.$var.'" value="20" /><small><span class="label label-info">'.JText::_('COM_JBLANCE_ONLY_ME').'</span>';
		}
		
		return $privacy;
	}
	
	//1.YesNo - boolean
	function YesNoBool($name, $value = 1){
		$yesno = JHTML::_('select.booleanlist', $name, 'class="inputbox"', $value);
		return $yesno;
	}
	
	//20.getSelectUserGroups
	function getSelectUserGroups($var, $default, $title, $attribs, $event){
		$db	= JFactory::getDBO();
	
		//if attribs is empty, then set a default one.
		if(empty($attribs))
			$attribs = 'class="inputbox" size="1"';
	
		$query = 'SELECT id AS value, name AS text FROM `#__jblance_usergroup` WHERE published=1 ORDER BY ordering';
		$db->setQuery($query);
		$groups = $db->loadObjectList();
	
		$types[] = JHTML::_('select.option', '', '- '.JText::_($title).' -');
		foreach($groups as $item){
			$types[] = JHTML::_('select.option', $item->value, JText::_($item->text));
		}
	
		$lists 	= JHTML::_('select.genericlist', $types, $var, "$attribs $event", 'value', 'text', $default);
		return $lists;
	}
	
	//14.getSearchPhrase
	function getRadioSearchPhrase($var, $default){
	
		$searchphrases 	 = array();
		$searchphrases[] = JHTML::_('select.option', 'any', JText::_('COM_JBLANCE_ANY_WORDS'));
		$searchphrases[] = JHTML::_('select.option', 'all', JText::_('COM_JBLANCE_ALL_WORDS'));
		$searchphrases[] = JHTML::_('select.option', 'exact', JText::_('COM_JBLANCE_EXACT_PHRASE'));
		$lists = JHTML::_('select.radiolist',  $searchphrases, $var, '', 'value', 'text', $default);
	
		return $lists;
	}
	
	//16.getCheckJobCategory
	function getCheckCategory($default){
		$app = JFactory::getApplication();
		$db	= JFactory::getDBO();
		$doc = JFactory::getDocument();
	
		$query = 'SELECT * FROM #__jblance_category WHERE parent=0 AND published=1 ORDER BY ordering';
		$db->setQuery($query);
		$categs = $db->loadObjectList();
	
		$html = "<div class='project_search_category'>";
		foreach($categs as $categ){
			$indent = '';
			$checked = (in_array($categ->id, $default)) ? 'checked' : '';
			$html .= "\n\t<label class='checkbox boldfont'><input type='checkbox' onclick=\"checkUncheck(this, 'cat')\" class='cat-parent-$categ->parent' alt='$categ->id' id='category_$categ->id' name='id_categ[]' value='$categ->id' $checked>&nbsp;$categ->category</label>";
			
			$subs = $this->getSubcategories($categ->id, $indent, '', 0);
			
			foreach($subs as $sub) {
				$html .= "\n\t<label class='checkbox'><input type='checkbox' onclick=\"checkUncheck(this, 'cat')\" class='cat-parent-$sub->parent' alt='$sub->id' id='category_$sub->id' name='id_categ[]' value='$sub->id' $checked>&nbsp;$sub->category</label>";
				
			}
		}
		$html .="</div>";
	
		return $html;
	}
	
	function getSelectProjectStatus($var, $default, $title, $attribs, $event){
		//if attribs is empty, then set a default one.
		if(empty($attribs))
			$attribs = "class='inputbox' size='1'";
		
		$types[] = JHTML::_('select.option', '', JText::_($title));
		$types[] = JHTML::_('select.option', 'COM_JBLANCE_OPEN', JText::_('COM_JBLANCE_OPEN'));
		$types[] = JHTML::_('select.option', 'COM_JBLANCE_FROZEN', JText::_('COM_JBLANCE_FROZEN'));
		$types[] = JHTML::_('select.option', 'COM_JBLANCE_CLOSED', JText::_('COM_JBLANCE_CLOSED'));
	
		$lists 	 = JHTML::_('select.genericlist', $types, $var, "$attribs $event", 'value', 'text', $default);
		return $lists;
	}

	function getSelectBudgetRange($var, $default, $title, $attribs, $event){
		$db	= JFactory::getDBO();
		
		//if attribs is empty, then set a default one.
		if(empty($attribs))
			$attribs = "class='inputbox' size='1'";
		
		$query = "SELECT id, CONCAT_WS('-', budgetmin, budgetmax) AS value, title, budgetmin, budgetmax FROM `#__jblance_budget` WHERE published=1 ORDER BY ordering";
		$db->setQuery($query);
		$budgets = $db->loadObjectList();
		
		$types[] = JHTML::_('select.option', '', '- '.JText::_($title).' -');
		foreach($budgets as $item){
			$types[] = JHTML::_('select.option', $item->value, sprintf("%s (%s - %s)", $item->title, JblanceHelper::formatCurrency($item->budgetmin, true, false, 0), JblanceHelper::formatCurrency($item->budgetmax, true, false, 0)));
		}
		
		$lists 	= JHTML::_('select.genericlist', $types, $var, "$attribs $event", 'value', 'text', $default);
		return $lists;
	}

}