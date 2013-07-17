<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 March 2012
 * @file name	:	views/admconfig/tmpl/editpaymode.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Edit the Payment Gateway(jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.tooltip');

 $model = $this->getModel();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task){
		if (task == 'admconfig.cancelpaymode' || document.formvalidator.isValid(document.id('editpaymode-form'))) {
			Joomla.submitform(task, document.getElementById('editpaymode-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'));?>');
		}
	}
</script>
<form action="index.php" name="adminForm" id="editpaymode-form" method="POST" enctype="multipart/form-data" class="form-validate">
<?php
	// Iterate through the fields and display them.
	if($this->form){ ?>
	<div class="col width-100">
	    <?php
	    // Iterate through the normal form fieldsets and display each one.
	    //foreach ($this->form->getFieldsets() as $fieldsets => $fieldset):
	    ?>
	    <fieldset class="adminform">
	       <legend><?php echo $this->paymode->gateway_name; ?></legend>
	        <ul class="adminformlist">
			<?php
			// Iterate through the fields and display them.
			foreach($this->form->getFieldset('settings') as $field):
			    // If the field is hidden, only use the input.
			    if ($field->hidden):
			        echo $field->input;
			    else:
			    ?>
			    <li>
			        <?php echo $field->label; ?>
			        <?php echo $field->input ?>
			   </li>
			    <?php
			    endif;
			endforeach;
			?>
			</ul>
	    </fieldset>
	    <?php
	    //endforeach;
	    ?>
	</div>
	<?php 
	}
	?>
	
	<!-- Identify if withdrawal is allowed -->
	<?php
	$isWithdrawEnabled = false;
	foreach($this->form->getFieldset('withdraw') as $field){
		$isWithdrawEnabled = true;
	}
	?>
	
	<div class="col width-100">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_DEPOSITS_WITHDRAWALS'); ?></legend>
			<table class="admintable">
				<?php if($isWithdrawEnabled) : ?>
				<tr>
					<td class="key">
						<label for="project_title"><?php echo JText::_('COM_JBLANCE_ENABLE_WITHDRAW'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">					
						<?php $enable_withdraw = $select->YesNoBool('withdraw', $this->paymode->withdraw);
						echo  $enable_withdraw; ?>
						</fieldset>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_ENABLE_WITHDRAW_EXAMPLE'); ?>	
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td class="key">
						<label for="project_title"><?php echo JText::_('COM_JBLANCE_WITHDRAW_FEE'); ?><span class="redfont">*</span>:</label>
					</td>
					<td>						
						<input type="text" class="inputbox required" name="withdrawFee" id="withdrawFee" size="8" value="<?php echo $this->paymode->withdrawFee;?>">
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_WITHDRAW_FEE_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="project_title"><?php echo JText::_('COM_JBLANCE_DEPOSIT_FEE_FIXED'); ?><span class="redfont">*</span>:</label>
					</td>
					<td>						
						<input type="text" class="inputbox required" name="depositfeeFixed" id="depositfeeFixed" size="8" value="<?php echo $this->paymode->depositfeeFixed;?>">
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_DEPOSIT_FEE_FIXED_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="project_title"><?php echo JText::_('COM_JBLANCE_DEPOSIT_FEE_PERCENT'); ?><span class="redfont">*</span>:</label>
					</td>
					<td>						
						<input type="text" class="inputbox required" name="depositfeePerc" id="depositfeePerc" size="8" value="<?php echo $this->paymode->depositfeePerc;?>">
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_DEPOSIT_FEE_PERCENT_EXAMPLE'); ?>	
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="gateway" value="<?php echo $this->paymode->gwcode; ?>" />
	<input type="hidden" name="id" value="<?php echo $this->paymode->id; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
	