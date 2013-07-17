<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 March 2012
 * @file name	:	views/admconfig/tmpl/showcategory.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Categories (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
	
 $app  	 = JFactory::getApplication();
 $jbtask = $app->input->get('jbtask', '', 'string');
 $ename  = $app->input->get('ename', '', 'string');
 $elId   = $app->input->get('elId', '', 'int');	//get the number to attach to the id of the input to update after selecting a menu item
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
			<th width="98%" align="left">
				<?php echo JText::_('COM_JBLANCE_CATEGORY'); ?>
			</th>
			 <th width="8%" nowrap="nowrap">
 				<?php echo JText::_('JGRID_HEADING_ORDERING'); ?>
				<?php echo JHTML::_('grid.order', $this->rows, 'filesave.png', 'admconfig.saveorder'); ?>
 			</th>
 			<th width="5%" nowrap="nowrap">
 				<?php echo JText::_('JPUBLISHED'); ?>
 			</th>
 			<th width="5%" nowrap="nowrap">
 				<?php echo JText::_('JGRID_HEADING_ID'); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="6">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for($i=0, $n=count($this->rows); $i < $n; $i++){
		$row = $this->rows[$i];

		$link_edit	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=editcategory&cid[]='. $row->id);
		$published = JHTML::_('jgrid.published', $row->published, $i, 'admconfig.');
		
		//The following code has been added for module browing to get id
		$onclick = '';
		if($jbtask == 'getid'){
	 		$onclick = "window.parent.jSelectItemid('$ename', '$row->id', $elId)";
		}
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pageNav->getRowOffset($i); ?>
			</td>
			<td>
				<?php echo JHtml::_('grid.id', $i, $row->id); ?>
			</td>
			<td>
				<?php 
				$class = '';
				if($row->parent == 0)
					$class = "style='font-weight: bold;'";
				?>
				<a href="<?php echo $link_edit?>" onclick="<?php echo $onclick; ?>" <?php echo $class; ?>><?php echo $row->category; ?></a>					
			</td>										
			<td class="order">
 				<span><?php echo $this->pageNav->orderUpIcon($i, true, 'admconfig.orderup', 'JLIB_HTML_MOVE_UP', true); ?></span>
				<span><?php echo $this->pageNav->orderDownIcon($i, $n, true, 'admconfig.orderdown', 'JLIB_HTML_MOVE_DOWN', true ); ?></span>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
 			</td>
 			<td align="center">
 				<?php echo $published; ?>
 			</td>
 			<td>
 				<?php echo $row->id; ?>
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
	<input type="hidden" name="layout" value="showcategory" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctype" value="category" />
	<input type="hidden" name="boxchecked" value="0" />
	
	<input type="hidden" name="jbtask" value="<?php echo $jbtask; ?>" />
	<input type="hidden" name="ename" value="<?php echo $ename; ?>" />
	<input type="hidden" name="elId" value="<?php echo $elId; ?>" />
	<?php if($jbtask == 'getid'){?>
	<input type="hidden" name="tmpl" value="<?php echo $app->input->get('tmpl', '', 'string'); ?>" />
	<?php }?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>