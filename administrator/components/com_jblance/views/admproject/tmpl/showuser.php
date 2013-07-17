<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	13 March 2012
 * @file name	:	views/admproject/tmpl/showuser.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Users (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.tooltip');
 
 $tableClass = JblanceHelper::getTableClassName();
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>:
				<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button onclick="document.getElementById('search').value='';document.getElementById('ug_id').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</td>
			<td nowrap="nowrap" align="right">
				<?php echo $this->lists['ug_id']; ?>
			</td>
		</tr>
	</table>
			
	<table class="<?php echo $tableClass; ?>">
		<thead>
			<tr>
				<th width="10">
					<?php echo '#'; ?>
				</th>
				<th width="10" >
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="30%" align="left">
					<?php echo JHTML::_( 'grid.sort', 'COM_JBLANCE_NAME', 'u.name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="12%" align="left">
					<?php echo JHTML::_( 'grid.sort', 'COM_JBLANCE_USERNAME', 'u.username', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="2%">
				<?php echo JHTML::_('grid.sort', JText::_('COM_JBLANCE_APPROVED'), 'u.block', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th width="18%" align="left">
					<?php echo JHTML::_( 'grid.sort', 'JGLOBAL_EMAIL', 'u.email', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="30%" align="left">
					<?php echo JHTML::_( 'grid.sort', 'COM_JBLANCE_BUSINESS_NAME', 'ju.biz_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" align="right">
					<?php echo JText::_('COM_JBLANCE_BALANCE'); ?>
				</th>
				<th width="10" align="left">
					<?php echo JHTML::_( 'grid.sort', 'COM_JBLANCE_USERID', 'u.id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>				
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count($this->rows); $i < $n; $i++) {
			$row = $this->rows[$i];
			$uurl = 'index.php?option=com_users&task=user.edit&id='.$row->id;
			
			$link_edit	= JRoute::_( 'index.php?option=com_jblance&view=admproject&layout=edituser&cid[]='.$row->id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pageNav->getRowOffset($i); ?>
				</td>
				<td>
					<?php echo JHtml::_('grid.id', $i, $row->id); ?>
				</td>
				<td>
					<a href="<?php echo $link_edit;?>"><?php echo $row->name; ?></a>			
				</td>
				<td>
					<?php echo $row->username; ?>
				</td>
				<td class="center">
					<?php echo JHTML::_('grid.boolean', $i, !$row->block, 'admproject.unblock', 'admproject.block'); ?>
				</td>
				<td>
					<?php echo JHtml::_("email.cloak", $row->email);?>
				</td>
				<td>
					<?php echo ($row->biz_name) ? $row->biz_name : '- <i>'.JText::_('COM_JBLANCE_NOT_APPLICABLE').'</i> -'; ?>
				</td>
				<td style="text-align: right;">
					<?php echo JblanceHelper::formatCurrency($row->total_fund, false); ?>
				</td>
				<td>
					<a href="<?php echo $uurl; ?>" title="<?php echo JText::_('COM_JBLANCE_EDIT_USER_ACCOUNT'); ?>"><?php echo $row->id;?></a>
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
	<input type="hidden" name="layout" value="showuser" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>