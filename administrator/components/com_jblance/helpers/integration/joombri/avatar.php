<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	07 June 2012
 * @file name	:	helpers/integration/joombri/profile.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

class JoombriAvatarJoombri extends JoombriAvatar {
	public function __construct(){
		$this->priority = 25;
	}

	public function getEditURL(){
		return JRoute::_('index.php?option=com_jblance&view=user&layout=editpicture');
	}

	protected function _getURL($userid){
		//get the JoomBri picture
		$db = JFactory::getDBO();
		$query = "SELECT thumb FROM #__jblance_user WHERE user_id=".$db->quote($userid);
		$db->setQuery($query);
		$jbpic = $db->loadResult();
		
		$imgpath = JBPROFILE_PIC_PATH.'/'.$jbpic;
		$imgurl = JBPROFILE_PIC_URL.$jbpic.'?'.time();
		
		if(JFile::exists($imgpath)){
			return $imgurl;
		}
		
		elseif($userid){
			$imgurl = JURI::root().'components/com_jblance/images/nophoto_sm.png';
			return  $imgurl;
		}
	}
}
