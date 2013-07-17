<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 July 2012
 * @file name	:	views/admproject/tmpl/showreporting.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Show Reportings (jblance)
 */
defined('_JEXEC') or die('Restricted access');

$config = JblanceHelper::getConfig();
$dformat = $config->dateFormat;
$tableClass = JblanceHelper::getTableClassName();
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table class="<?php echo $tableClass; ?>">
		<thead>
			<tr>
				<th width="10">
					<?php echo '#'; ?>
				</th>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th>
					<?php echo JText::_('COM_JBLANCE_ITEM_LINK');?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_JBLANCE_VIEW_REPORTS'); ?>
				</th>
				<th width="5%">
					<?php echo JText::_('COM_JBLANCE_STATUS'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_JBLANCE_ACTIONS'); ?>
				</th>
				<th width="5%">
					<?php echo JText::_('COM_JBLANCE_COUNT'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_JBLANCE_REPORTED_ON'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="11"><?php echo $this->pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
			$k = 0;
			for($i=0, $n=count($this->rows); $i < $n; $i++){
				$row = $this->rows[$i];
					
				$link_viewreport	= JRoute::_('index.php?option=com_jblance&view=admproject&layout=detailreporting&cid[]='. $row->id);
				$link_reportaction	= JRoute::_('index.php?option=com_jblance&task=admproject.reportaction&cid[]='. $row->id.'&'.JSession::getFormToken().'=1');
				?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pageNav->getRowOffset($i); ?>
				</td>
				<td>
					<?php echo JHtml::_('grid.id', $i, $row->id); ?>
				</td>
				<td>
					<a href="<?php echo $row->link;?>" target="_blank"><?php echo $row->link; ?></a>
				</td>
				<td class="center">
					<a href="<?php echo $link_viewreport; ?>"><?php echo JText::_('COM_JBLANCE_REPORTS'); ?></a>
				</td>
				<td class="center">
					<?php
					if($row->status == 1){
						echo JText::_('COM_JBLANCE_PROCESSED');
					}
					elseif($row->status == 2){
						echo JText::_('COM_JBLANCE_IGNORED');
					}
					else {
						echo JText::_('COM_JBLANCE_PENDING');
					}
					?>
				</td>
				<td class="center">
					<a href="<?php echo $link_reportaction; ?>"><?php echo $row->label; ?></a>
				</td>
				<td class="center">
					<?php echo $row->reporter; ?>
				</td>
				<td>
					<?php echo JHTML::_('date', $row->date_created, $dformat, false); ?>
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
	<input type="hidden" name="layout" value="showreporting" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
