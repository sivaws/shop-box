<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 March 2012
 * @file name	:	views/admconfig/tmpl/showcustomfield.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Custom Fields (jblance)
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.multiselect');
$tableClass = JblanceHelper::getTableClassName();
?>
<form action="index.php" method="post" id="adminForm" name="adminForm">
	<table>
		<tr>
			<td width="100%" align="right">
				<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>:
				<button onclick="this.form.getElementById('filter_field_type').value='0';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php echo $this->lists['field_type'];?>
			</td>
		</tr>
	</table>	
	<table class="<?php echo $tableClass; ?>">
	<thead>
		<tr>
			<th width="10">
				<?php echo JText::_('#'); ?>
			</th>
			<th width="10" >
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
			</th>
			<th width="10%" nowrap="nowrap">
				<?php echo JText::_('COM_JBLANCE_FIELD_FOR'); ?>
			</th>
			<th align="left">
				<?php echo JText::_('COM_JBLANCE_TITLE'); ?>
			</th>
			<th width="8%" nowrap="nowrap">
				<?php echo JText::_('COM_JBLANCE_TYPE'); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JText::_('COM_JBLANCE_REQUIRED'); ?>
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
	<tfoot>
		<tr>
			<td colspan="8">
				<?php //echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count($this->rows); $i < $n; $i++) {
		$row = $this->rows[$i];
		$edit_field	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=editcustomfield&cid[]='.$row->id);
		$edit_group	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=editcustomfield&cid[]='.$row->id.'&type=group');
		$published = JHTML::_('jgrid.published', $row->published, $i, 'admconfig.');
		$required = required($row, $i);
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pageNav->getRowOffset($i); ?>
			</td>
			<td>
				<?php echo JHtml::_('grid.id', $i, $row->id); ?>
			</td>
			<td>
				<?php echo $row->field_for; ?>
			</td>
			<?php if($row->parent == 0): ?>
			<td align="center" colspan="3">
				<strong><a href="<?php echo $edit_group?>"><?php echo $row->field_title; ?></a></strong>				
			</td>
			<?php else: ?>
			<td>
				<a href="<?php echo $edit_field?>"><?php echo $row->field_title; ?></a>					
			</td>										
			<td>
				<?php echo $row->field_type; ?>
			</td>										
			<td align="center">
				<?php echo $required; ?>
			</td>	
			<?php endif; ?>									
												
			<td class="order">
				<span><?php echo $this->pageNav->orderUpIcon( $i, true, 'admconfig.orderup', 'JLIB_HTML_MOVE_UP', true); ?></span>
				<span><?php echo $this->pageNav->orderDownIcon( $i, $n, true, 'admconfig.orderdown', 'JLIB_HTML_MOVE_DOWN', true ); ?></span>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
			</td>
			<td align="center">
				<?php echo $published; ?>
			</td>										
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>

	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="view" value="admconfig" />
	<input type="hidden" name="layout" value="showcustomfield" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctype" value="customfield" />
	<input type="hidden" name="fieldfor" value="<?php echo $this->fieldfor; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<?php 
	function required(&$row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix=''){
		$img 	= $row->required ? $imgY : $imgX;
		$task 	= $row->required ? 'admconfig.unrequired' : 'admconfig.required';
		$alt 	= $row->required ? JText::_('COM_JBLANCE_REQUIRED') : JText::_('COM_JBLANCE_UNREQUIRED');
		$action = $row->required ? JText::_('COM_JBLANCE_ITEM_UNREQUIRED') : JText::_('COM_JBLANCE_ITEM_REQUIRED');

		$href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
		<img src="components/com_jblance/images/'. $img .'" border="0" alt="'. $alt .'" /></a>'
		;

		return $href;
	}
?>