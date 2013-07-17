<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	20 March 2012
 * @file name	:	views/admconfig/tmpl/editcategory.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Edit category (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHTML::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
?>
<script type="text/javascript">
<!--
	Joomla.submitbutton = function(task){
		if (task == 'admconfig.cancelcategory' || document.formvalidator.isValid(document.id('editcategory-form'))) {
			Joomla.submitform(task, document.getElementById('editcategory-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'));?>');
		}
	}
//-->
</script>

<form action="index.php" method="post" id="editcategory-form" name="adminForm" class="form-validate">
	<div class="col width-60">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_DETAILS'); ?></legend>
	
			<table class="admintable">
				<tr>
					<td class="key"><label for="category"><?php echo JText::_('COM_JBLANCE_CATEGORY'); ?>:</label>
					</td>
					<td>
						<input class="inputbox required" type="text" name="category" id="category" size="60" maxlength="255" value="<?php echo $this->row->category; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><label for="parent"><?php echo JText::_('COM_JBLANCE_PARENT_ITEM'); ?>:</label>
					</td>
					<td>
						<?php 
						$select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
						$attribs = "class='inputbox' size='20'";
						$categtree = $select->getSelectCategoryTree('parent', $this->row->parent, 'COM_JBLANCE_ROOT_CATEGORY', $attribs, '');
						echo $categtree;
						?>
					</td>
				</tr>
		    </table>
		</fieldset>
	</div>
	
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="savecategory" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
    <?php echo JHTML::_('form.token'); ?>
</form>