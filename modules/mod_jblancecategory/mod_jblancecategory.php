<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	28 March 2012
 * @file name	:	modules/mod_jblancecategory/mod_jblancecategory.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( dirname(__FILE__).'/helper.php' );
$total_column 	  = intval($params->get('total_column', 1));
$show_count 	  = intval($params->get('show_count', 1));
$show_empty_count = intval($params->get('show_empty_count', 1));

$rows = ModJblanceCategoryHelper::getCategory($show_empty_count);

require(JModuleHelper::getLayoutPath('mod_jblancecategory'));

?>