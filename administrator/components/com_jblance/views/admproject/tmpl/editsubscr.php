<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	19 March 2012
 * @file name	:	views/admproject/tmpl/editsubscr.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Edit subscription details (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 
 $model = $this->getModel();
 $config = JblanceHelper::getConfig();
?>
<script type="text/javascript">
<!--
	Joomla.submitbutton = function(task){
		if (task == 'admproject.cancelsubscr' || document.formvalidator.isValid(document.id('editsubscr-form'))) {
			Joomla.submitform(task, document.getElementById('editsubscr-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'));?>');
		}
	}
//-->
</script>
<form action="index.php" method="post" id="editsubscr-form" name="adminForm" class="form-validate">
	<fieldset class="adminform">
	<legend><?php echo JText::_('COM_JBLANCE_USER_INFORMATION'); ?></legend>
	<table class="admintable">
		<tr>
		  <td class="key"><?php echo JText::_('COM_JBLANCE_USER_GROUP'); ?>:</td>
		  <td>
			<?php echo $this->lists['ug_id']; ?>
		  </td>
		</tr>
		<tr>
		  <td class="key"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?>:</td>
		  <td><?php echo  $this->users; ?></td>
		</tr>
	</table>
	</fieldset>
	
	<fieldset class="adminform">
	<legend><?php echo JText::_('COM_JBLANCE_SUBSCR_INFO'); ?></legend>
	<table class="admintable">
		<tr>
		  <td class="key"><?php echo JText::_('COM_JBLANCE_SUBSCR_ID'); ?>:</td>
		  <td><input type="text" size="8" value="<?php echo $this->row->id; ?>" disabled="disabled"/></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_('COM_JBLANCE_INVOICE_NO'); ?>:</td>
			<td><?php echo $this->row->invoiceNo; ?></td>
		</tr>
		<tr>
		  <td class="key"><?php echo JText::_('COM_JBLANCE_PLAN_NAME'); ?>:</td>
		  <td><?php echo  $this->plans; ?></td>
		</tr>
		<tr>
		  <td class="key"><?php echo JText::_('COM_JBLANCE_STATUS'); ?>:</td>
		  <td><?php echo $this->lists['status']; ?>
		  </td>
		</tr>
		<?php if($this->row->date_approval != '0000-00-00 00:00:00' && $this->row->id > 0){?>
			<tr>
			  <td class="key"><?php echo JText::_('COM_JBLANCE_STARTS_ON'); ?>:</td>
			  <td><?php 
			  	$approvalDate = $this->row->date_approval != "0000-00-00 00:00:00" ?  JHTML::_('date', $this->row->date_approval, 'Y-m-d H:i:s') :  "";
			  	echo JHTML::_('calendar', $approvalDate, 'date_approval', 'date_approval', '%Y-%m-%d %H:%M:%S'); ?></td>
			</tr>
			<tr>
			  <td class="key"><?php echo JText::_('COM_JBLANCE_EXPIRES_ON'); ?>:</td>
			  <td><?php 
			  	$expireDate = $this->row->date_expire != "0000-00-00 00:00:00" ?  JHTML::_('date', $this->row->date_expire, 'Y-m-d H:i:s') :  "";
			  	echo JHTML::_('calendar', $expireDate, 'date_expire', 'date_expire', '%Y-%m-%d %H:%M:%S'); ?></td>
			</tr>
		<?php } ?>
	</table>
	</fieldset>
	
	<?php if($this->row->date_approval != '0000-00-00 00:00:00' && $this->row->id > 0){?>
	<fieldset class="adminform">
	<legend><?php echo JText::_('COM_JBLANCE_PAYMENT_INFO'); ?></legend>
	<table class="admintable">
		<tr>
		  <td class="key"><?php echo JText::_('COM_JBLANCE_TAX'); ?>:</td>
		  <td><input type="text" size="5" name="tax_percent" value="<?php echo $this->row->tax_percent; ?>" /> %</td>
		</tr>
		<tr>
		  <td class="key"><?php echo JText::_('COM_JBLANCE_TOTAL_AMOUNT'); ?> (<?php echo $config->currencySymbol; ?>):</td>
		  <td><input type="text" size="5" name="price" value="<?php echo JblanceHelper::formatCurrency($this->row->price, false); ?>" /></td>
		</tr>
		<tr>
		  <td class="key"><?php echo JText::_('COM_JBLANCE_FUND_ADDED'); ?>:</td>
		  <td><input type="text" size="5" name="credit" value="<?php echo $this->row->fund; ?>" /></td>
		</tr>
		<tr>
		  <td class="key"><?php echo JText::_('COM_JBLANCE_PAYMENT_GATEWAY'); ?>:</td>
		  <td><?php echo JblanceHelper::getGwayName($this->row->gateway); ?></td>
		</tr>
		<tr>
		  <td class="key"><?php echo JText::_('COM_JBLANCE_BID_LIMITS'); ?>:</td>
		  <td><input type="text" size="5" name="bids_left" value="<?php echo $this->row->bids_left; ?>" /> / <input type="text" size="5" name="bids_allowed" value="<?php echo $this->row->bids_allowed; ?>" /></td>
		</tr>
		<tr>
		  <td class="key"><?php echo JText::_('COM_JBLANCE_PROJECT_LIMITS'); ?>:</td>
		  <td><input type="text" size="5" name="projects_left" value="<?php echo $this->row->projects_left; ?>" /> / <input type="text" size="5" name="projects_allowed" value="<?php echo $this->row->projects_allowed; ?>" /></td>
		</tr>
		
	</table>
	</fieldset>
	<?php } ?>
	
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="view" value="admproject" />
	<input type="hidden" name="layout" value="editsubscr" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>">
	<input type="hidden" name="gateway" value="<?php echo ($this->row->gateway ? $this->row->gateway : 'byadmin' );?>" />
	<!--<input type="hidden" name="gateway_id" value="<?php echo ($this->row->gateway_id ? $this->row->gateway_id : time() );?>" />-->
	<input type="hidden" name="hidemainmenu" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

