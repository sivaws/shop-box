<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	views/admproject/tmpl/showproject.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Projects (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.tooltip');
 
 $model = $this->getModel();
 $config = JblanceHelper::getConfig();
 $dformat = $config->dateFormat;
 $tableClass = JblanceHelper::getTableClassName();
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
		<td width="100%">
			<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
			<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_status').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php echo $this->lists['status'];?>
		</td>
		</tr>
	</table>

	<table class="<?php echo $tableClass; ?>">
		<thead>
			<tr>
				<th width="10"><?php echo '#'; ?>
				</th>
				<th width="10"><input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="60%" align="left"><?php echo JHTML::_('grid.sort', 'COM_JBLANCE_PROJECT_TITLE', 'p.project_title', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" align="left"><?php echo JText::_('COM_JBLANCE_STATUS'); ?>
				</th>
				<th width="5%" align="left"><?php echo JText::_('COM_JBLANCE_BIDS'); ?>
				</th>
				<th width="10%" align="left"><?php echo JHTML::_('grid.sort', 'COM_JBLANCE_CREATED_DATE', 'p.create_date', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="10%" align="left"><?php echo JHTML::_('grid.sort', 'COM_JBLANCE_START_DATE', 'p.start_date', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" align="left"><?php echo JText::_('COM_JBLANCE_EXPIRE_DAYS'); ?>
				</th>
				<th width="5%" align="left"><?php echo JHTML::_('grid.sort', 'COM_JBLANCE_APPROVED', 'p.approved', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" align="left"><?php echo JHTML::_('grid.sort', 'COM_JBLANCE_PROJECTID', 'p.id', $this->lists['order_Dir'], $this->lists['order']); ?>
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
				$link_edit	= JRoute::_('index.php?option=com_jblance&view=admproject&layout=editproject&cid[]='. $row->id);
				$bidsCount = $model->countBids($row->id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pageNav->getRowOffset($i); ?>
				</td>
				<td>
					<?php echo JHtml::_('grid.id', $i, $row->id); ?>
				</td>
				<td>
					<a href="<?php echo $link_edit;?>"><?php echo $row->project_title; ?></a>
					<div class="fr">
			  			<?php 
			  			if($row->is_featured) : 
			  				echo JHtml::_('image','components/com_jblance/images/featured.png', 'Featured', array('width'=>'20', 'title'=>JText::_('COM_JBLANCE_FEATURED_PROJECT')));
			  			endif;
			  			if($row->is_urgent) : 
			  				echo JHtml::_('image','components/com_jblance/images/urgent.png', 'Urgent', array('width'=>'20', 'title'=>JText::_('COM_JBLANCE_URGENT_PROJECT')));
			  			endif;
			  			if($row->is_private) : 
			  				echo JHtml::_('image','components/com_jblance/images/private.png', 'Private', array('width'=>'20', 'title'=>JText::_('COM_JBLANCE_PRIVATE_PROJECT')));
			  			endif; 
			  			if($row->is_sealed) : 
			  				echo JHtml::_('image','components/com_jblance/images/sealed.png', 'Sealed', array('width'=>'20', 'title'=>JText::_('COM_JBLANCE_SEALED_PROJECT')));
			  			endif; 
			  			if($row->is_nda) : 
			  				echo JHtml::_('image','components/com_jblance/images/nda.png', 'NDA', array('width'=>'20', 'title'=>JText::_('COM_JBLANCE_NDA_PROJECT')));
			  			endif; 
			  			?>
		  			</div>
				</td>
				<td class="center">
					<?php echo JText::_($row->status); ?>
				</td>
				<td class="center">
					<?php echo $bidsCount;?>
				</td>
				<td class="center">
					<?php
					echo $row->create_date != "0000-00-00 00:00:00" ?  JHTML::_('date', $row->create_date, $dformat, false) : JText::_('COM_JBLANCE_NEVER'); ?>
				</td>
				<td class="center">
					<?php
					echo $row->start_date != "0000-00-00 00:00:00" ?  JHTML::_('date', $row->start_date, $dformat, false) : JText::_('COM_JBLANCE_NEVER'); ?>
				</td>
				<td class="center">
					<?php
					echo $row->expires; ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('grid.boolean', $i, $row->approved, 'admproject.approveproject', null); ?>
				</td>
				<td><?php echo $row->id;?>
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
	<input type="hidden" name="layout" value="showproject" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>