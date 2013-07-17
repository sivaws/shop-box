<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	28 July 2012
 * @file name	:	views/admconfig/tmpl/editbudget.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Edit budget range (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHTML::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 ?>
 <script type="text/javascript">
 <!--
	 Joomla.submitbutton = function(task){
		 if (task == 'admconfig.cancelbudget' || document.formvalidator.isValid(document.id('editbudget-form'))) {
		 	Joomla.submitform(task, document.getElementById('editbudget-form'));
		 }
		 else {
		 	alert('<?php echo $this->escape(JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'));?>');
		 }
	 }
 //-->
 </script>
 <form action="index.php" method="post" id="editbudget-form" name="adminForm" class="form-validate">
	<div class="col width-60">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_DETAILS'); ?></legend>
	
			<table class="admintable">
				<tr>
					<td class="key"><label for="title"><?php echo JText::_('COM_JBLANCE_TITLE'); ?>:</label>
					</td>
					<td>
						<input class="inputbox required" type="text" name="title" id="title" size="60" maxlength="255" value="<?php echo $this->row->title; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><label for="budgetmin"><?php echo JText::_('COM_JBLANCE_MINIMUM_BUDGET'); ?>:</label>
					</td>
					<td>
						<input class="inputbox required" type="text" name="budgetmin" id="budgetmin" size="15" maxlength="25" value="<?php echo number_format($this->row->budgetmin, 0, '.', ''); ?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><label for="budgetmax"><?php echo JText::_('COM_JBLANCE_MAXIMUM_BUDGET'); ?>:</label>
					</td>
					<td>
						<input class="inputbox required" type="text" name="budgetmax" id="budgetmax" size="15" maxlength="25" value="<?php echo number_format($this->row->budgetmax, 0, '.', ''); ?>" />
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