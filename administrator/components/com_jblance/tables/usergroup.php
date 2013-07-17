<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	tables/usergroup.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
	
class TableUsergroup extends JTable {
	var $id = null;
	var $name = null;
	var $description = null;
	var $approval = null;
	var $published = null;
	var $create_group = null;
	var $ordering = null;
	var $params = null;
	var $freeMode = null;
	var $joomla_ug_id = null;
	var $skipPlan = null;
	
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct('#__jblance_usergroup', 'id', $db);
	}
	
	public function deleteChilds(){
		$db		= JFactory::getDBO();
		$query	= 'DELETE FROM '.$db->quoteName('#__jblance_usergroup_field').' WHERE '.$db->quoteName( 'parent' ).'='.$db->Quote( $this->id );
		$db->setQuery( $query );
		$db->execute();
		
		return true;
	}
	
	/**
	 * Retrieve a multiprofile mapping for a given multi profile.
	 * 
	 * @return	Object	An object of #__community_multiprofiles_fields	 	 
	 **/
	public function getChild($fieldId , $multiprofileId){		
		$db		= JFactory::getDBO();
		$query	= 'SELECT * FROM '.$db->quoteName('#__jblance_usergroup_field')
				. ' WHERE '.$db->quoteName( 'field_id' ).'='.$db->Quote( $fieldId )
				. ' AND '.$db->quoteName( 'parent' ).'='.$db->Quote( $multiprofileId );
		$db->setQuery( $query );
		
		$result	= $db->loadObject();

		return $result;
	}
	
	public function isChild($fieldId){
		if($this->id == 0)
			return false;

		if( $this->getChild($fieldId, $this->id)){
			return true;
		}

		return false;
	}
	
}
?>