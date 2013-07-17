<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	02 April 2012
 * @file name	:	views/membership/tmpl/escrow.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Escrow Payment Form (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 
 $user = JFactory::getUser();
 $config = JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 
 $totalFund = JblanceHelper::getTotalFund($user->id);
?>
<script type="text/javascript">
<!--
	function validateForm(f){
		var valid = document.formvalidator.isValid(f);
		
		if(valid == true){
			f.check.value='<?php echo JSession::getFormToken(); ?>';//send token
	    }
	    else {
			var msg = '<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY', true); ?>';
	    	if($('amount').hasClass('invalid')){
		    	msg = msg+'\n\n* '+'<?php echo JText::_('COM_JBLANCE_PLEASE_ENTER_AMOUNT_IN_NUMERIC_ONLY', true); ?>';
		    }
			alert(msg);
			return false;
	    }
		return true;
	}

	function updateReason(){

		if($('full_payment_option').checked || $('partial_payment_option').checked){
			$('projectBox').setStyle('display', 'table-row');
			$('project_id').addClass('required');
			
		}
		else if($('other_reason_option').checked){
			//$('recipient').set('readonly', false);
         	//$('amount').set('readonly', false);
			$('projectBox').setStyle('display', 'none');
			$('project_id').removeClass('required').removeProperty('required').set('value', '');
		}
	}
//-->
</script>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userFormProject" id="userFormProject" class="form-validate form-horizontal" onsubmit="return validateForm(this);">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_ESCROW_PAYMENT'); ?></div>
	<div class="row">
		<div class="span6">
			<strong><?php echo JText::_('COM_JBLANCE_PLEASE_SELECT_ONE_OF_THE_FOLLOWING'); ?>:</strong>
			<p>
				<label class="radio">
					<input type="radio" name="reason" id="full_payment_option" value="full_payment" checked onclick="updateReason();"><?php echo JText::_('COM_JBLANCE_FULL_FINAL_PAYMENT_FOR_COMPLETED_PROJECT'); ?>
				</label>
				<label class="radio">
					<input type="radio" name="reason" id="partial_payment_option" value="partial_payment" onclick="updateReason();"> <?php echo JText::_('COM_JBLANCE_PARTIAL_PAYMENT_FOR_PROJECT'); ?>
				</label>
				<label class="radio">
					<input type="radio" name="reason" id="other_reason_option" value="other" onclick="updateReason();"> <?php echo JText::_('COM_JBLANCE_OTHER_REASON'); ?>
				</label>
			</p>
		</div>
	</div>
	<div class="lineseparator"></div>
	
	<div class="control-group" id="projectBox">
		<label class="control-label" for="project_id"><?php echo JText::_('COM_JBLANCE_PROJECT'); ?> :</label>
		<div class="controls">
			<?php echo $this->lists; ?>
			<input type="hidden" name="proj_balance" id="proj_balance" value="" />
			<strong><span id="proj_balance_div" class="help-inline"></span></strong>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="recipient"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?> :</label>
		<div class="controls">
			<input type="text" name="recipient" id="recipient" value="" class="inputbox required" onchange="checkUsername(this);" />
			<span id="status_recipient" class="help-inline"></span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="amount"><?php echo JText::_('COM_JBLANCE_AMOUNT'); ?> :</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on"><?php echo $currencysym; ?></span>
				<input type="text" name="amount" id="amount" class="input-small required validate-numeric" />
			</div>
			<span class="help-inline">
				<em>(<?php echo JText::_('COM_JBLANCE_YOUR_BALANCE').' : '.JblanceHelper::formatCurrency($totalFund); ?>)</em>
			</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="note"><?php echo JText::_('COM_JBLANCE_NOTES'); ?> :</label>
		<div class="controls">
			<input type="text" name="note" id="note" class="input-xlarge" />
		</div>
	</div>
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_TRANSFER')?>" class="btn btn-primary" />
	</div>
	
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="membership.saveescrow" />
	<?php echo JHTML::_('form.token'); ?>
</form>