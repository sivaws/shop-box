<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	04 March 2013
 * @file name	:	modules/mod_jblancebalance/tmpl/default.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 // no direct access
 defined('_JEXEC') or die('Restricted access');
 
 if(version_compare(JVERSION, '3.0', '>')){
	JHtml::_('bootstrap.loadCss');
 }
 else {
	jbimport('moobootstrap');
	JHtml::_('moobootstrap.loadCss');
 }

 $user = JFactory::getUser();
 $hasJBProfile = JblanceHelper::hasJBProfile($user->id);
?>
<?php if($hasJBProfile) : ?>
<ul class="inline">
	<li><?php echo JText::_('MOD_JBLANCE_CURRENT_BALANCE'); ?>:</li>
	<li><strong><?php echo JblanceHelper::formatCurrency($total_fund); ?></strong></li>
</ul>
<?php endif; ?>