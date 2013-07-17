<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	14 March 2012
 * @file name	:	views/admconfig/tmpl/showusergroup.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Users Groups (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 $tableClass = JblanceHelper::getTableClassName();
?>
<form action="index.php" method="post" id="adminForm" name="adminForm">
	<table class="<?php echo $tableClass; ?>">
		<thead>
			<tr class="title">
				<th width="10">#</th>
				<th width="10">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="15%" style="text-align: left;">
					<?php echo JText::_('COM_JBLANCE_GROUP_TITLE');?>
				</th>
				<th style="text-align: left;">
					<?php echo JText::_('COM_JBLANCE_GROUP_DESC');?>
				</th>
				<th width="5%">
					<?php echo JText::_('COM_JBLANCE_TOTAL_USERS');?>
				</th>
				<th width="8%" nowrap="nowrap">
					<?php echo JText::_('JGRID_HEADING_ORDERING'); ?>
					<?php echo JHTML::_('grid.order',  $this->rows, 'filesave.png', 'admconfig.saveorder'); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JText::_('JPUBLISHED'); ?>
				</th>
			</tr>
		</thead>
		<?php $i = 0; ?>
		<?php
			if(empty($this->rows)){
		?>
		<tr>
			<td colspan="8" align="center"><?php echo JText::_('COM_JBLANCE_NO_USERGROUP_CREATED'); ?></td>
		</tr>
		<?php
			}
			else {
				foreach ($this->rows as $i => $row) {
					$row = $this->rows[$i];
					$published = JHTML::_('jgrid.published', $row->published, $i, 'admconfig.');
		?>
			<tr>
				<td>
					<?php echo $this->pageNav->getRowOffset($i); ?>
				</td>
				<td>
					<?php echo JHtml::_('grid.id', $i, $row->id); ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_jblance&view=admconfig&layout=editusergroup&cid[]='.$row->id); ?>">
						<?php echo $row->name; ?>
					</a>
				</td>
				<td>
					<?php echo $row->description; ?>
				</td>
				<td align="center">
					<?php echo $row->usercount;?>
				</td>
				<td class="order">
					<span><?php echo $this->pageNav->orderUpIcon($i, true, 'admconfig.orderup', 'JLIB_HTML_MOVE_UP', true); ?></span>
					<span><?php echo $this->pageNav->orderDownIcon($i, $this->pageNav->total, true, 'admconfig.orderdown', 'JLIB_HTML_MOVE_DOWN', true); ?></span>
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
				</td>
				<td align="center">
					<?php echo $published; ?>
				</td>
			</tr>
		<?php
				}
		?>
		<?php } ?>
		<tfoot>
		<tr>
			<td colspan="8">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
	</table>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctype" value="usergroup" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' );?>
</form>