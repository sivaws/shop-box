<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 April 2012
 * @file name	:	modules/mod_jblancetags/mod_jblancetags.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */

	defined('_JEXEC') or die('Restricted access');
	global $joomlusModCount,$joomlushelper_instance;
	$set_Itemid	= intval($params->get('set_itemid', 0));
	$doc = JFactory::getDocument();
	//set number of times a this module is called. Stops javascript confilcts.
	$joomlusModCount += 1;
	//get all module params conveiently in one array
	$paramsArry = $params->toArray();
	//check instance of helper class, basic singleton
	if (!is_object($joomlushelper_instance)) {
        // does not currently exist, so create it
        require_once (dirname(__FILE__).'/helper.php');
		//$mainframe->addCustomHeadTag("<!--[if IE]></base><![endif]-->");
		$doc->addCustomTag("<!--[if IE]></base><![endif]-->");
    }
    $joomlushelper_instance = new joomulusHelper($paramsArry);
	$tagwords = $joomlushelper_instance->joomulus_tagwords_sd($set_Itemid);
	$altdiv = $joomlushelper_instance->joomulus_altdiv_sd($tagwords);
	$flashvars = $joomlushelper_instance->joomulus_flashvars_sd($tagwords);
	
	echo $joomlushelper_instance->joomulus_createflashcode($tagwords, $altdiv, $flashvars);

?>





