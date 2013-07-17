<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	20 April 2012
 * @file name	:	views/admproject/tmpl/showescrow.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Show Funds transfer between users (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.multiselect');
 
 $config = JblanceHelper::getConfig();
 $dformat = $config->dateFormat;
 $currencysym = $config->currencySymbol;
 $tableClass = JblanceHelper::getTableClassName();
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<table class="<?php echo $tableClass; ?>">
	<thead>
		<tr>
			<th width="10">
				<?php echo JText::_('#'); ?>
			</th>
			<th width="10" >
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
			</th>
			<th width="6%" align="left">
				<?php echo JText::_('COM_JBLANCE_TRANSFER_DATE'); ?>
			</th>
			<th width="6%" align="left">
				<?php echo JText::_('COM_JBLANCE_RELEASE_DATE'); ?>
			</th>
			<th width="6%" align="left">
				<?php echo JText::_('COM_JBLANCE_ACCEPT_DATE'); ?>
			</th>
			<th width="10%" align="left">
				<?php echo JText::_('COM_JBLANCE_FROM'); ?>
			</th>
			<th width="10%" align="left">
				<?php echo JText::_('COM_JBLANCE_TO'); ?>
			</th>
			<th width="22%" align="left">
				<?php echo JText::_('COM_JBLANCE_PROJECT'); ?>
			</th>
			<th width="5%" align="left">
				<?php echo JText::_('COM_JBLANCE_STATUS'); ?>
			</th>
			<th width="5%" align="left">
				<?php echo JText::_('COM_JBLANCE_AMOUNT').' ('.$currencysym.')'; ?>
			</th>
			<th width="20%">
				<?php echo JText::_('COM_JBLANCE_NOTE'); ?>
			</th>
			<th width="10%">
				<?php echo JText::_('COM_JBLANCE_ACTION'); ?>
			</th>
			<th width="10" align="left">
				<?php echo JText::_('JGRID_HEADING_ID'); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="14">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for($i=0, $n=count($this->rows); $i < $n; $i++){
		$row = $this->rows[$i];
		$link_release 	= JRoute::_('index.php?option=com_jblance&task=admproject.releaseescrow&id='.$row->id.'&'.JSession::getFormToken().'=1');
		$link_cancel 	= JRoute::_('index.php?option=com_jblance&task=admproject.cancelescrow&id='.$row->id.'&'.JSession::getFormToken().'=1');
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pageNav->getRowOffset($i); ?>
			</td>
			<td>
				<?php echo JHtml::_('grid.id', $i, $row->id); ?>
			</td>
			<td class="center" nowrap>
				<?php echo ($row->date_transfer != "0000-00-00 00:00:00" ?  JHTML::_('date', $row->date_transfer, $dformat) :  "-"); ?>
			</td>
			<td class="center" nowrap>
				<?php echo ($row->date_release != "0000-00-00 00:00:00" ?  JHTML::_('date', $row->date_release, $dformat) :  "-"); ?>
			</td>
			<td class="center" nowrap>
				<?php echo ($row->date_accept != "0000-00-00 00:00:00" ?  JHTML::_('date', $row->date_accept, $dformat) :  "-"); ?>
			</td>
			<td>
				[<?php echo $row->from_id ?>] <?php echo $row->sender; ?>				
			</td>
			<td>
				[<?php echo $row->to_id ?>] <?php echo $row->receiver; ?>				
			</td>
			<td align="center">
				<?php echo ($row->project_title) ? $row->project_title : JText::_('COM_JBLANCE_NA'); ?>
			</td>
			<td align="center">
				<?php echo JText::_($row->status); ?>
			</td>
			<!-- <td>
				<?php echo ($row->date_approval != "0000-00-00 00:00:00" ?  JHTML::_('date', $row->date_approval, $dformat) :  "Never"); ?>
			</td> -->
			<td align="right">
				<?php echo JblanceHelper::formatCurrency($row->amount, false); ?>
			</td>
			<td>
				<?php echo $row->note;?>
			</td>
			<td>
				<?php if($row->status == '') : ?>
				<a href="<?php echo  $link_release; ?>"><?php echo JText::_('COM_JBLANCE_RELEASE'); ?></a>/
				<a href="<?php echo  $link_cancel; ?>"><?php echo JText::_('COM_JBLANCE_CANCEL'); ?></a>
				<?php endif; ?>
			</td>
			<td>
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
	<input type="hidden" name="layout" value="showescrow" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>