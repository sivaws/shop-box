<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	04 April 2012
 * @file name	:	views/admproject/tmpl/showsummary.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Show profit reports (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $tableClass  = JblanceHelper::getTableClassName();
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">	
	<table style="width:100%;">
		<tr>
			<td width="80%">&nbsp;</td>
			<td align="right">
				<?php echo JText::_('COM_JBLANCE_MONTH').':'.$this->lists['search_month']; ?>
			</td>
			<td>
				<?php echo JText::_('COM_JBLANCE_YEAR').':'.$this->lists['search_year']; ?>
			</td>
		</tr>
	</table>
	
	
	<div class="col width-30 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_PROFIT_FROM_DEPOSITS'); ?></legend>
			<table class="<?php echo $tableClass; ?>">
				<thead>
					<tr>
						<th>
							<?php echo JText::_('COM_JBLANCE_GATEWAY'); ?>
						</th>
						<th>
							<?php echo JText::_('COM_JBLANCE_PROFIT'); ?>
						</th>
					</tr>	
				</thead>
				<tbody>	
				<?php
				$total_d = 0;
				for($i=0, $n=count($this->deposits); $i < $n; $i++){
				$deposit = $this->deposits[$i];
				$total_d += ($deposit->profit);
				?>
				   <tr>
				   		<td><?php echo $deposit->gateway;?></td>
						<td align="right"><?php echo JblanceHelper::formatCurrency($deposit->profit); ?></td>	
				   </tr>
			   	
			<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td>
							<?php echo JText::_('COM_JBLANCE_TOTAL'); ?>
						</td>
						<td style="text-align: right">
							<strong><?php echo JblanceHelper::formatCurrency($total_d); ?></strong>
						</td>
					</tr>	
				</tfoot>	
			</table>			
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_PROFIT_FROM_WITHDRAWALS'); ?></legend>
			 <table class="<?php echo $tableClass; ?>">
			 	<thead>
					<tr>
						<th><?php echo JText::_('COM_JBLANCE_GATEWAY'); ?>
						</th>
						<th><?php echo JText::_('COM_JBLANCE_PROFIT'); ?>
						</th>
					</tr>
				</thead>
				<tbody>	
				<?php
				$total_w = 0;
				for($i=0, $n=count($this->withdraws); $i < $n; $i++){
				$withdraw = $this->withdraws[$i];
				$total_w += ($withdraw->profit);
				?>
				   <tr>
				   		<td><?php echo $withdraw->gateway; ?>
				   		</td>
						<td align="right"><?php echo JblanceHelper::formatCurrency($withdraw->profit); ?>
						</td>	
				   </tr>
			   	</tbody>	
			<?php } ?>
				<tfoot>
					<tr>
						<td><?php echo JText::_('COM_JBLANCE_TOTAL'); ?>
						</td>
						<td style="text-align: right">
							<strong><?php echo JblanceHelper::formatCurrency($total_w); ?></strong>
						</td>
					</tr>
				</tfoot>
			</table>			
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_PROFIT_FROM_SUBSCRIPTIONS'); ?></legend>
			 <table class="<?php echo $tableClass; ?>">
			 	<thead>
					<tr>
						<th><?php echo JText::_('COM_JBLANCE_GATEWAY'); ?>
						</th>
						<th><?php echo JText::_('COM_JBLANCE_PROFIT'); ?>
						</th>
					</tr>
				</thead>
				<tbody>	
				<?php
				$total_s = 0;
				for($i=0, $n=count($this->subscrs); $i < $n; $i++){
				$subscr = $this->subscrs[$i];
				$total_s += ($subscr->profit);
				?>
				   <tr>
				   		<td><?php echo $subscr->gateway; ?>
				   		</td>
						<td align="right"><?php echo JblanceHelper::formatCurrency($subscr->profit); ?>
						</td>	
				   </tr>
			   	</tbody>	
			<?php } ?>
				<tfoot>
					<tr>
						<td><?php echo JText::_('COM_JBLANCE_TOTAL'); ?>
						</td>
						<td style="text-align: right">
							<strong><?php echo JblanceHelper::formatCurrency($total_s); ?></strong>
						</td>
					</tr>
				</tfoot>
			</table>			
		</fieldset>
	</div>
	<div class="col width-30 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_PROFIT_SUMMARY'); ?></legend>
			<table class="<?php echo $tableClass; ?>">
				<thead>
					<tr>
						<th>
							<?php echo JText::_('COM_JBLANCE_ITEMS'); ?>
						</th>
						<th>
							<?php echo JText::_('COM_JBLANCE_PROFIT'); ?>
						</th>
					</tr>	
				</thead>
				<tbody>	
					<tr>
						<td><?php echo JText::_('COM_JBLANCE_PROFIT_FROM_PROJECTS'); ?>
						</td>
						<td align="right"><?php echo JblanceHelper::formatCurrency($this->project); ?>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_JBLANCE_PROFIT_FROM_DEPOSITS'); ?>
						</td>
						<td align="right"><?php echo JblanceHelper::formatCurrency($total_d); ?>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_JBLANCE_PROFIT_FROM_WITHDRAWALS'); ?>
						</td>
						<td align="right"><?php echo JblanceHelper::formatCurrency($total_w); ?>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_JBLANCE_PROFIT_FROM_SUBSCRIPTIONS'); ?>
						</td>
						<td align="right"><?php echo JblanceHelper::formatCurrency($total_s); ?>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td>
							<?php echo JText::_('COM_JBLANCE_TOTAL'); ?>
						</td>
						<td style="text-align: right">
							<strong><?php echo JblanceHelper::formatCurrency($this->project + $total_d + $total_w + $total_s); ?></strong>
						</td>
					</tr>	
				</tfoot>	
			</table>			
		</fieldset>
	</div>
	
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="view" value="admproject" />
	<input type="hidden" name="layout" value="showsummary" />
	<input type="hidden" name="task" value="" />
	</form>