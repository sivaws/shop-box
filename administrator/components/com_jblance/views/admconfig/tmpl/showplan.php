<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 March 2012
 * @file name	:	views/admconfig/tmpl/showplan.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Plans(jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 $config = JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $tableClass = JblanceHelper::getTableClassName();
 ?>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<table>
		<tr>
			<td nowrap="nowrap" align="right">
				<?php echo $this->lists['ug_id']; ?>
			</td>
		</tr>
	</table>
	<table class="<?php echo $tableClass; ?>">
		<thead>
			<tr>
			<th width="10"><?php echo '#'; ?></th>
			<th width="10"><input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" /></th>
			<th width="80" colspan="2"><?php echo JText::_('COM_JBLANCE_DURATION'); ?></th>
			<th align="left"><?php echo JText::_('COM_JBLANCE_PLAN_NAME'); ?></th>
			<th align="left"><?php echo JText::_('COM_JBLANCE_BONUS'); ?></th>
			<!-- <th align="left"><?php echo JText::_('COM_JBLANCE_PRICE_P_CREDIT'); ?></th> -->
			<th width="8%" nowrap="nowrap"><?php echo JText::_('JGRID_HEADING_ORDERING'); ?><?php echo JHTML::_('grid.order',  $this->rows, 'filesave.png', 'admconfig.saveorder'); ?></th>
			<th width="5%" nowrap="nowrap"><?php echo JText::_('JPUBLISHED'); ?></th>
			<th width="20"><?php echo JText::_('COM_JBLANCE_SUBSCRIBERS'); ?></th>
			<th width="50"><?php echo JText::_('COM_JBLANCE_PRICE').' ('.$currencysym.')'; ?></th>
			<th width="10"><?php echo JText::_('COM_JBLANCE_USER_GROUP'); ?></th>
			<th width="10"><?php echo JText::_('COM_JBLANCE_DEFAULT'); ?></th>
			<th width="10"><?php echo JText::_('JGRID_HEADING_ID'); ?></th>
			</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="15">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
		$k = 0;
		for ($i=0, $n=count($this->rows); $i < $n; $i++){
		$row = $this->rows[$i];
		$link_edit	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=editplan&cid[]='.$row->id);
		$published = JHTML::_('jgrid.published', $row->published, $i, 'admconfig.');
	?>
	    <tr class="<?php echo "row$k"; ?>">
		    <td><?php echo $this->pageNav->getRowOffset($i); ?></td>
		    <td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
		    <td align="right"><?php echo $row->days ?> </td>
		    <td> <?php echo ucfirst($row->days_type) ?></td>
		    <td><a href="<?php echo $link_edit; ?>"><?php echo $row->name ?></a></td>
			<td><?php echo $row->bonusFund; ?></td>
			<!-- <td><?php echo $row->creditPrice; ?></td> -->
		    <td class="order">
					<span><?php echo $this->pageNav->orderUpIcon( $i, true, 'admconfig.orderup', 'JLIB_HTML_MOVE_UP' , true); ?></span>
					<span><?php echo $this->pageNav->orderDownIcon( $i, $n, true, 'admconfig.orderdown', 'JLIB_HTML_MOVE_UP', true ); ?></span>
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
				</td>
			
		    <td align="center"><?php echo $published; ?></td>
		    <td align="right"><?php echo $row->subscr ?></td>
		    <td align="right"><?php echo $row->price ?></td>
		    <td><?php echo $row->groupName ?></td>
		    <td class="center">
				<?php if ($row->default_plan):?>
					<img src="components/com_jblance/images/default.png" align="middle" border="0" alt="" />
				<?php else :?>
					<a href="<?php echo JRoute::_('index.php?option=com_jblance&task=admconfig.setplandefault&cid[]='.$row->id.'&ug_id='.$row->ug_id.'&'.JSession::getFormToken().'=1');?>">
						<img src="components/com_jblance/images/default-not.png" align="middle" border="0" alt="" />
					</a>
				<?php endif;?>
			</td>
		    <td><?php echo $row->id ?></td>
	    </tr>
	<?php
	$k = 1 - $k;
	}
	?>
	</tbody>
	</table>
	<div class="jbadmin-welcome">
		<h3><?php echo JText::_('COM_JBLANCE_PLAN_TIPS');?></h3>
		<p><?php echo JText::_('COM_JBLANCE_PLAN_TIPS_DESC');?></p>
	</div>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="view" value="admconfig" />
	<input type="hidden" name="layout" value="showplan" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="ctype" value="plan" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>
    
  