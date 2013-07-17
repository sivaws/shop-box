<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	19 March 2012
 * @file name	:	views/admproject/tmpl/showsubscr.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of subscribers (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.modal');
 
 $config 		= JblanceHelper::getConfig();
 $currencysym 	= $config->currencySymbol;
 $dformat 		= $config->dateFormat;
 $tableClass 	= JblanceHelper::getTableClassName();
 ?>
 
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

	<table>
		<tr>
			<td width="20%">
				<?php echo JText::_('COM_JBLANCE_INVOICE_NO'); ?>:
				<input type="text" name="sinv_num" id="sinv_num" size="20" maxlength="50" value="<?php echo htmlspecialchars($this->lists['sinv_num']);?>" />
			</td>
			<td width="15%">
				<?php echo JText::_('COM_JBLANCE_USERID'); ?>:
				<input type="text" name="suser_id" id="suser_id" size="5" maxlength="8" value="<?php echo htmlspecialchars($this->lists['suser_id']);?>" />
			</td>
			<td width=65%>
				<?php echo JText::_('COM_JBLANCE_SUBSCR_ID'); ?>:
				<input type="text" name="ssubscr_id" id="ssubscr_id" size="5" maxlength="8" value="<?php echo htmlspecialchars($this->lists['ssubscr_id']);?>" />
				<button onclick="this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button onclick="document.getElementById('suser_id').value='';
								 document.getElementById('ssubscr_id').value='';
								 document.getElementById('sinv_num').value='';
								 this.form.getElementById('subscr_status').value='';
								 this.form.getElementById('subscr_plan').value='0';
								 this.form.getElementById('ug_id').value='';
								 this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
				</button>
			</td>
			<td nowrap="nowrap" align="right">
				<?php echo $this->lists['ug_id']; ?>
				<?php echo $this->lists['subscr_plan']; ?>
				<?php echo $this->lists['subscr_status']; ?>
			</td>
		</tr>
	</table>

    <table class="<?php echo $tableClass; ?>">
		<thead>
		    <tr>
			    <th width="10"><?php echo JText::_('#'); ?></th>
			    <th width="10"><input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" /></th>
			    <th align="left" width="20%"><?php echo JText::_('COM_JBLANCE_PLAN_NAME'); ?></th>
			    <th align="left" width="30%"><?php echo JText::_('COM_JBLANCE_SUBSCR_NAME'); ?></th>
			    <th width="10%"><?php echo JText::_('COM_JBLANCE_GATEWAY'); ?></th>
			    <th width="5%" nowrap="nowrap"><?php echo JText::_('COM_JBLANCE_DAYS_LEFT'); ?></th>
			    <th width="10%"><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
			   <!-- <Th width="1%">Limit</Th>
			    <Th width="1%">Count</Th>-->
			    <th width="10"><?php echo JText::_('COM_JBLANCE_START'); ?></th>
			    <th width="10"><?php echo JText::_('COM_JBLANCE_END'); ?></th>
			    <th width="10%"><?php echo JText::_('COM_JBLANCE_PRICE').' ('.$currencysym.')'; ?></th>
			    <th width="10%" align="left"><?php echo JText::_('COM_JBLANCE_INVOICE_NO'); ?></th>
				<th width="10%">
					<?php echo JHTML::_( 'grid.sort', 'COM_JBLANCE_SUBSCR_ID', 'u.id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
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
	        $uurl = 'index.php?option=com_users&task=user.edit&id='.$row->uid;
	        $over = '';
			$link_edit	= JRoute::_('index.php?option=com_jblance&view=admproject&layout=editsubscr&cid[]='.$row->id);
			$link_invoice	= JRoute::_('index.php?option=com_jblance&view=admproject&layout=invoice&id='.$row->id.'&tmpl=component&print=1&type=plan');
	    ?>
	        <tr class="<?php echo "row$k"; ?>">
		       	<td><?php echo $this->pageNav->getRowOffset($i); ?></td>
		        <td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
		        <td>[<?php echo $row->sid ?>] <a href="<?php echo $link_edit; ?>"><?php echo $row->name ?></a></td>
		        <td>[<?php echo $row->uid ?>] <a<?php echo $over ?>  href="<?php echo $uurl; ?>"><?php echo $row->uname ?></a> (<?php echo JHTML::_("email.cloak", $row->email);?> )</td>
		        <td><?php echo JblanceHelper::getGwayName($row->gateway); ?></td>
		        <td align="right"><?php echo $row->days ?></td>
		        <td align="center"><?php echo JblanceHelper::getPaymentStatus($row->approved); ?></td>
		        <!-- <TD align="center"><?php echo intval($row->access_count) ?></TD>-->
		        <td nowrap="nowrap"><?php echo $row->date_approval != "0000-00-00 00:00:00" ? JHTML::_('date', $row->date_approval, $dformat, true) : "&nbsp;"; ?></td>
		        <td nowrap="nowrap"><?php echo $row->date_expire != "0000-00-00 00:00:00" ? JHTML::_('date', $row->date_expire, $dformat, true) : "&nbsp;"; ?></td>
		        <td align="right"><?php echo JblanceHelper::formatCurrency($row->price, false); ?></td>
				<td><a rel="{handler: 'iframe', size: {x: 650, y: 450}}" href="<?php echo $link_invoice; ?>" class="modal"><?php echo $row->invoiceNo; ?></a></td>
				<td><?php echo $row->id; ?></td>
	        </tr>
	    <?php
	      $k = 1 - $k;
	    }
	    ?>
		</tbody>	
    </table>

	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="view" value="admproject" />
	<input type="hidden" name="layout" value="showsubscr" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

