<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	02 April 2012
 * @file name	:	views/admproject/tmpl/showdeposit.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Show Funds deposit transactions (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.modal');
 JHtml::_('behavior.multiselect');
 
 $config = JblanceHelper::getConfig();
 $dformat = $config->dateFormat;
 $currencysym = $config->currencySymbol;
 $tableClass = JblanceHelper::getTableClassName();
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<table>
		<tr>
			<td width="20%">
				<?php echo JText::_('COM_JBLANCE_INVOICE_NO'); ?>:
				<input type="text" name="cinv_num" id="cinv_num" size="20" maxlength="50" value="<?php echo htmlspecialchars($this->lists['cinv_num']);?>" />
			</td>
			<td width="15%">
				<?php echo JText::_('COM_JBLANCE_USERID'); ?>:
				<input type="text" name="cuser_id" id="cuser_id" size="5" maxlength="8" value="<?php echo htmlspecialchars($this->lists['cuser_id']);?>" />
			</td>
			<td width="65%">
				<?php echo JText::_('COM_JBLANCE_DEPOSIT_ID'); ?>:
				<input type="text" name="ccredit_id" id="ccredit_id" size="5" maxlength="8" value="<?php echo htmlspecialchars($this->lists['ccredit_id']);?>" />
				<button onclick="this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button onclick="document.getElementById('cinv_num').value='';
								document.getElementById('cuser_id').value='';
								document.getElementById('ccredit_id').value='';
								this.form.getElementById('cgateways').value='';
								this.form.getElementById('cstatus').value='';
								this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
				</button>
			</td>
			<td nowrap="nowrap" align="right">
				<?php echo $this->lists['cgateways']; ?>
				<?php echo $this->lists['cstatus']; ?>
			</td>
		</tr>
	</table>
	
	<table class="<?php echo $tableClass; ?>">
	<thead>
		<tr>
			<th width="10">
				<?php echo JText::_('#'); ?>
			</th>
			<th width="10">
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
			</th>
			<th width="10%" align="left">
				<?php echo JText::_('COM_JBLANCE_DEPOSIT_DATE'); ?>
			</th>
			<th width="30%" align="left">
				<?php echo JText::_('COM_JBLANCE_NAME'); ?>
			</th>
			<th width="10%" align="left">
				<?php echo JText::_('COM_JBLANCE_GATEWAY'); ?>
			</th>
			<th width="8%" align="left">
				<?php echo JText::_('COM_JBLANCE_STATUS'); ?>
			</th>
			<th width="10%" align="left">
				<?php echo JText::_('COM_JBLANCE_APPROVED'); ?>
			</th>
			<th width="8%" align="left">
				<?php echo JText::_('COM_JBLANCE_AMOUNT').' ('.$currencysym.')'; ?>
			</th>
			<th width="8%" align="left">
				<?php echo JText::_('COM_JBLANCE_TOTAL_AMOUNT').' ('.$currencysym.')'; ?>
			</th>
			<th width="10%" align="left">
				<?php echo JText::_('COM_JBLANCE_INVOICE_NO'); ?>
			</th>
			<th width="10" align="left">
				<?php echo JText::_('JGRID_HEADING_ID'); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="13">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for($i=0, $n=count($this->rows); $i < $n; $i++){
		$row = $this->rows[$i];
		$link_invoice	= JRoute::_('index.php?option=com_jblance&view=admproject&layout=invoice&id='.$row->id.'&tmpl=component&print=1&type=deposit');
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pageNav->getRowOffset($i); ?>
			</td>
			<td>
				<?php echo JHtml::_('grid.id', $i, $row->id); ?>
			</td>
			<td class="center">
				<?php echo JHTML::_('date', $row->date_deposit, $dformat); ?>
			</td>
			<td>
				<?php echo $row->name; ?>				
			</td>
			<td>
				<?php echo JblanceHelper::getGwayName($row->gateway); ?>
			</td>
			<td class="center">
				<?php echo JblanceHelper::getPaymentStatus($row->approved); ?>
			</td>
			<td class="center">
				<?php echo ($row->date_approval != "0000-00-00 00:00:00" ?  JHTML::_('date', $row->date_approval, $dformat) :  "-"); ?>
			</td>
			<td align="right">
				<?php echo JblanceHelper::formatCurrency($row->amount, false); ?>
			</td>
			<td align="right">
				<?php echo JblanceHelper::formatCurrency($row->total, false); ?>
			</td>
			<td class="center">
				<a rel="{handler: 'iframe', size: {x: 650, y: 450}}" href="<?php echo $link_invoice; ?>" class="modal"><?php echo $row->invoiceNo; ?></a>
			</td>
			<td align="right">
				<?php echo $row->id;?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>

	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="view" value="admproject" />
	<input type="hidden" name="layout" value="showdeposit" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>