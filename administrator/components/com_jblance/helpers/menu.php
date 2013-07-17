<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	22 March 2012
 * @file name	:	helpers/menu.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Helper class for generating menus (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class menuHelper {

	public function getJBMenuItems(){
		
		$db	=   JFactory::getDBO();
		$menus	=   array();

		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$result 		= $menu->getItems('menutype', 'joombri');
		
		foreach($result as $i => $row){
			//get top main links on toolbar

			//add Itemid if not our components
			//if(strpos($row->link, 'com_jblance') == false){
				$row->link .="&Itemid=".$row->id;
			//}
                        
			if($row->parent_id == 1){
				$obj				= new stdClass();
				$obj->item			= $row;
				$obj->item->script	= false;
				$obj->childs		= null;

				$menus[$row->id]	= $obj;
			}
		}
		
		// Retrieve child menus from the original result.
		// Since we reduce the number of sql queries, we need to use php to split the menu's out
		// accordingly.
		foreach($result as $i => $row){
			if($row->parent_id != 1 && isset($menus[$row->parent_id])){
				if(!is_array($menus[$row->parent_id]->childs)){
					$menus[$row->parent_id]->childs = array();
				}
				$menus[$row->parent_id]->childs[] = $row;
			}
		}
		return $menus;
	}
	
	function processJBMenuItems($menus){
		$user = JFactory::getUser();
		foreach($menus as $keyi=>$menu){
			if(!empty($menu->childs)){
				$count = count($menu->childs);
				$flag = 0;
				foreach($menu->childs as $keyj=>$child){
					$uri = JFactory::getURI($child->link);
					$layout = $uri->getVar('layout');
					$denied = JblanceHelper::deniedLayouts($user->id);
					if(in_array($layout, $denied)){
						unset($menu->childs[$keyj]);
						$flag++;
					}
				}
				//remove the parent menu item if all the subitems are denied. This is helpful in case of "Free Mode" condition
				if($count == $flag){
					unset($menus[$keyi]);
				}
			}
		}
		return $menus;
	}
	
 	function getActiveLink(){
		$url		= 'index.php?';
		$segments	= $_GET;
		$option = $view = $layout = '';
		$q = array();
		
		if(isset($_GET['option']))
			$q[] = 'option='.$_GET['option'];
		
		if(isset($_GET['view']))
			$q[] = 'view='.$_GET['view'];
		
		if(isset($_GET['layout']))
			$q[] = 'layout='.$_GET['layout'];
		
		$query = implode($q, '&');
		
		$url = 'index.php?'.$query;
		
		return $url;
	}
	
 	function getActiveId($link){
		$db		= JFactory::getDBO();
		
		$query	= 'SELECT `id`,parent_id FROM #__menu WHERE menutype ='.$db->Quote('joombri').' '.
				  'AND published=1 AND link LIKE '.$db->Quote('%'.$link.'%');
		$db->setQuery($query);//echo $query;
		$result	= $db->loadObject();
		
		if(!$result){
			return 0;
		}
		return ($result->parent_id == 0 || $result->parent_id == 1) ? $result->id : $result->parent_id;
	}
}
?>