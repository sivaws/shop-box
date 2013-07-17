<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	25 June 2012
 * @file name	:	modules/mod_jblancestats/tmpl/default.php
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

 $document = JFactory::getDocument();
 $document->addStyleSheet("components/com_jblance/css/style.css");
 
 $sh_users 	= $params->get('total_users', 1);
 $sh_active 	= $params->get('active_projects', 1);
 $sh_total 	= $params->get('total_projects', 1);
?>
<div class="form-horizontal">
	<?php if($sh_users) : ?>
	<div class="control-group">
		<label class="control-label nopadding"><?php echo JText::_('MOD_JBLANCE_LABEL_TOTAL_USERS'); ?>: </label>
		<div class="controls">
			<?php echo $total_users; ?>
		</div>
	</div>
	<?php endif; ?>
	<?php if($sh_active) : ?>
	<div class="control-group">
		<label class="control-label nopadding"><?php echo JText::_('MOD_JBLANCE_LABEL_TOTAL_OPEN_PROJECTS'); ?>: </label>
		<div class="controls">
			<?php echo $active_projects; ?>
		</div>
	</div>
	<?php endif; ?>
	<?php if($sh_total) : ?>
	<div class="control-group">
		<label class="control-label nopadding"><?php echo JText::_('MOD_JBLANCE_LABEL_TOTAL_PROJECTS'); ?>: </label>
		<div class="controls">
			<?php echo $total_projects; ?>
		</div>
	</div>
	<?php endif; ?>
</div>