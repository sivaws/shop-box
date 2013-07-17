<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 March 2012
 * @file name	:	views/admconfig/tmpl/showpaymode.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Payment Gateways(jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 $tableClass = JblanceHelper::getTableClassName();
?>
<form action="index.php" method="post" id="adminForm" name="adminForm">
	
	<table class="<?php echo $tableClass; ?>">
		<thead>
			<tr>
				<th width="10">
					<?php echo JText::_('#'); ?>
				</th>
				<th width="10" >
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th align="left">
					<?php echo JText::_('COM_JBLANCE_TITLE'); ?>
				</th>
				<th width="8%" nowrap="nowrap">
					<?php echo JText::_('JGRID_HEADING_ORDERING'); ?><?php echo JHTML::_('grid.order',  $this->rows, 'filesave.png', 'admconfig.saveorder'); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JText::_('JPUBLISHED'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
					<?php echo $this->pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count($this->rows); $i < $n; $i++) {
			$row = $this->rows[$i];
			$edit_paymode = JRoute::_('index.php?option=com_jblance&view=admconfig&layout=editpaymode&cid[]='.$row->id);
			$published = JHTML::_('jgrid.published', $row->published, $i, 'admconfig.');
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pageNav->getRowOffset($i); ?>
				</td>
				<td>
					<?php echo JHtml::_('grid.id', $i, $row->id); ?>
				</td>
				<td>
					<a href="<?php echo $edit_paymode?>"><?php echo $row->gateway_name; ?></a>					
				</td>										
				<td class="order">
					<span><?php echo $this->pageNav->orderUpIcon( $i, true, 'admconfig.orderup', 'JLIB_HTML_MOVE_UP' , true); ?></span>
					<span><?php echo $this->pageNav->orderDownIcon( $i, $n, true, 'admconfig.orderdown', 'JLIB_HTML_MOVE_UP', true ); ?></span>
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
	<input type="hidden" name="layout" value="showpaymode" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctype" value="paymode" />
	<input type="hidden" name="fieldfor" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php 
$user = JFactory::getUser();
$isSuperAdmin = false;
if(isset($user->groups[8]))
	$isSuperAdmin = true;
?>
<?php if($isSuperAdmin) : ?>
<form action="index.php" method="post" name="adminRunSqlForm" enctype="multipart/form-data">
	<div class="jbadmin-welcome">
		<table class="adminform" border="0">
			<thead>
				<tr valign="middle" style="align:center;">
					<th>
						<?php echo JText::_('COM_JBLANCE_RUN_SQL'); ?>
					</th>
				</tr>
				<tr>
					<td><?php echo JText::_('COM_JBLANCE_RUN_SQL_MESSAGE'); ?>
					</td>
				</tr>
			</thead>
			
			<tr valign="top" >
				<td>
					<?php echo JText::_('COM_JBLANCE_FILE'); ?><font color="red">*</font>:&nbsp;&nbsp;<input type="file" class="inputbox" name="runsql" id="runsql" size="20" maxlength="30"/>
					<input class="button" type="button" name="submit_app" value="<?php echo JText::_('COM_JBLANCE_RUN_SQL'); ?>" onclick="this.form.submit()"/>
					
				</td>
			</tr>
			<input type="hidden" name="actiontype" value="1" />
		</table>
	</div>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="admconfig.runsql" />
</form>
<?php endif; ?>