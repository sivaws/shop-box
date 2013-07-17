<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	06 August 2012
 * @file name	:	views/membership/tmpl/invoice.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Generate and Print invoice (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $app = JFactory::getApplication();

 $config 			= JblanceHelper::getConfig();
 $dformat 			= $config->dateFormat;
 $invoicedetails 	= $config->invoiceDetails;
 $tax_name	 		= $config->taxName;
 
 $type 	= $app->input->get('type', '', 'string');
?>
	<button type="button" class="btn" onclick="window.print();"><i class="icon-print"></i> <?php echo JText::_('COM_JBLANCE_PRINT'); ?></button>
	<div class="sp10">&nbsp;</div>
	<table style="width: 100%;">
		<tr>
			<?php if($type == 'project') { ?>
			<td style="background: #ccc; padding: 20px"><strong><?php echo JText::_('COM_JBLANCE_PROJECTID'); ?></strong>: <?php echo $this->row->id; ?></td>
			<?php } else { ?>
			<td style="background: #ccc; padding: 20px"><strong><?php echo JText::_('COM_JBLANCE_INVOICE_NO'); ?></strong>: <?php echo $this->row->invoiceNo; ?></td>
			<?php }?>
			<td style="background: #ccc; padding: 20px"><strong><?php echo JText::_('COM_JBLANCE_INVOICE_DATE'); ?></strong>: <?php echo JHTML::_('date', $this->row->invoiceDate, $dformat, true); ?></td>
		</tr>
		<tr>
			<td valign="top" style="padding: 20px">
				<strong><?php echo JText::_('COM_JBLANCE_INVOICE_TO'); ?>:</strong><br />
				<?php echo !empty($this->row->biz_name) ? $this->row->biz_name.'<br/>' : ''; ?>
				<?php echo $this->row->name; ?><br />
				<?php echo JText::_('COM_JBLANCE_EMAIL'); ?>: <?php echo $this->row->email; ?>
			</td>
			<td valign="top" style="padding: 20px">
				<strong><?php echo JText::_('COM_JBLANCE_PROVIDED_BY'); ?>:<br /></strong>
				<strong><?php echo $app->getCfg('sitename');?></strong> <br />
				<?php echo JURI::base(); ?><br/>
				<?php echo $invoicedetails; ?>
			</td>
		</tr>
		<?php if($type != 'project') { ?>
		<tr>
			<td style="background: #ccc; padding: 10px" colspan="2"><strong><?php echo JText::_('COM_JBLANCE_PAY_MODE'); ?>:</strong> <?php echo JblanceHelper::getGwayName($this->row->gateway); ?></td>
		</tr>
		<?php } ?>
	</table>
	<table style="width: 100%;">
		<tr>
			<th align="left"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?></th>
			<th align="left"><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
			<th align="right"><?php echo JText::_('COM_JBLANCE_AMOUNT'); ?></th>
		</tr>
		<!-- section for deposit fund -->
		<?php if($type == 'deposit'): ?>
		<tr>
			<td><?php echo JText::_('COM_JBLANCE_DEPOSIT_FUNDS'); ?></td>
			<td><?php echo JblanceHelper::getPaymentStatus($this->row->approved); ?></td>
			<td style="text-align:right;"><?php echo JblanceHelper::formatCurrency($this->row->amount); ?></td>
		</tr>
			<?php 
			$subtotalName = JText::_('COM_JBLANCE_DEPOSIT_FEE').' (+)';
			$subtotalAmt = $this->row->total - $this->row->amount;
			$total = $this->row->total;
			?>
		<!-- section for deposit fund END-->
		
		<!-- section for withdraw fund -->
		<?php elseif($type == 'withdraw'): ?>
		<tr>
			<td><?php echo JText::_('COM_JBLANCE_WITHDRAW_FUNDS'); ?></td>
			<td><?php echo JblanceHelper::getPaymentStatus($this->row->approved); ?></td>
			<td style="text-align:right;"><?php echo JblanceHelper::formatCurrency($this->row->amount); ?></td>
		</tr>
			<?php 
			$subtotalName = JText::_('COM_JBLANCE_WITHDRAWAL_FEE').' (-)';
			$subtotalAmt = $this->row->withdrawFee;
			$total = $this->row->finalAmount;
			?>
		<!-- section for withdraw fund END-->
		
		<!-- section for subscription -->
		<?php elseif($type == 'plan'): ?>
		<tr>
			<td><?php echo JText::sprintf('COM_JBLANCE_PURCHASE_OF', $this->row->planname); ?></td>
			<td><?php echo JblanceHelper::getPaymentStatus($this->row->approved); ?></td>
			<td style="text-align:right;"><?php echo JblanceHelper::formatCurrency($this->row->price); ?></td>
		</tr>
			<?php 
			$subtotalName = $tax_name.' '.$this->row->tax_percent.' %'.' (+)';
			$subtotalAmt = ($this->row->tax_percent/100)*$this->row->price;
			$total = $subtotalAmt + $this->row->price;
			?>
		<!-- section for subscription END-->
		
		<!-- section for project -->
		<?php elseif($type == 'project'): ?>
		<tr>
			<td><?php echo JText::sprintf('COM_JBLANCE_PROJECT_COMMISSION_FOR_PROJECT_NAME', '<b>'.$this->row->project_title.'</b>'); ?></td>
			<td><?php echo JblanceHelper::getPaymentStatus(1); ?></td>
			<td style="text-align:right;"><?php echo JblanceHelper::formatCurrency($this->row->commission_amount); ?></td>
		</tr>
			<?php 
			$subtotalName = JText::_('COM_JBLANCE_SUBTOTAL');
			$subtotalAmt = $this->row->commission_amount;
			$total = $this->row->commission_amount;
			?>
		<!-- section for project END-->
		<?php endif; ?>
		<tr>
			<td colspan="2" align="right"><?php echo $subtotalName; ?>:</td>
			<td colspan="1" align="right">
				<?php echo JblanceHelper::formatCurrency($subtotalAmt); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right"> </td>
			<td colspan="1" align="right"><hr></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><?php echo JText::_('COM_JBLANCE_TOTAL'); ?> :</td>
			<td colspan="1" align="right">
				<?php echo '<b>'.JblanceHelper::formatCurrency($total, true, true).'</b>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_JBLANCE_WE_THANK_YOU_FOR_YOUR_BUSINESS'); ?></td>
		</tr>
	</table>