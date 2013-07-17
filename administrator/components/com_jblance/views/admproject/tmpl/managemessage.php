<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	13 May 2013
 * @file name	:	views/admproject/tmpl/managemessage.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Manage Private Messages (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.framework', true);
 
 $doc = JFactory::getDocument();
 $doc->addScript(JURI::root()."components/com_jblance/js/utility.js");
  
 $config = JblanceHelper::getConfig();
 $dformat = $config->dateFormat;
 $tableClass = JblanceHelper::getTableClassName();
 ?>
<div class="width-50 fltlft">
	<form action="index.php" method="post" name="adminForm" id="adminForm">
		<table>
			<tr>
				<td width="100%">
					<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
					<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" onchange="document.adminForm.submit();" />
					<button onclick="this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
					<button onclick="document.getElementById('search').value=''; this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
				</td>
			</tr>
		</table>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_TITLE_PRIVATE_MESSAGES'); ?></legend>
			<table class="<?php echo $tableClass; ?>">
			<thead>
				<tr>
					<th width="10"><?php echo '#'; ?>
					<th><?php echo JText::_('COM_JBLANCE_FROM'); ?></th>	
					<th><?php echo JText::_('COM_JBLANCE_TO'); ?></th>	
					<th><?php echo JText::_('COM_JBLANCE_SUBJECT'); ?></th>
					<th><?php echo JText::_('COM_JBLANCE_DATE'); ?></th>
					<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
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
			if(count($this->rows) == 0){		//Called if there are no messages -> Shows a text that spreads over the whole table
				?>
				<tr><td colspan='6' align="center"><?php echo JText::_("COM_JBLANCE_INBOX_EMPTY"); ?></td></tr>
			<?php
			}
			$k = 0;
			for ($i=0, $x=count($this->rows); $i < $x; $i++){
				$row = $this->rows[$i];
				$userFrom = JFactory::getUser($row->idFrom);
				$userTo = JFactory::getUser($row->idTo);
				$link_read = JRoute::_('index.php?option=com_jblance&view=admproject&layout=managemessage&cid[]='.$row->id);
				
				$newMsg = JblanceHelper::countUnreadMsg($row->id);
			?>
				<tr id="jbl_feed_item_<?php echo $row->id; ?>" class="<?php echo "row$k"; ?>">
					<td><?php echo $this->pageNav->getRowOffset($i); ?></td>
			  		<td><a href="<?php echo $link_read; ?>"><?php echo $userFrom->username; ?></a></td>
			  		<td><a href="<?php echo $link_read; ?>"><?php echo $userTo->username; ?></a></td>
					<td><a href="<?php echo $link_read; ?>"><?php echo $row->subject; ?> <?php echo ($newMsg > 0) ? '(<b>'.JText::sprintf('COM_JBLANCE_COUNT_NEW', $newMsg).'</b>)' : ''; ?></a></td>
					<td nowrap><?php echo JHTML::_('date', $row->date_sent, $dformat, true);?></td>
					<td>
						<a id="feed_hide_<?php echo $row->id; ?>" class="remFeed" onclick="processMessage('<?php echo $row->id; ?>', 'admproject.processmessage');" href="javascript:void(0);"><?php echo JText::_('COM_JBLANCE_REMOVE'); ?></a>
					</td>
				</tr>
			<?php 
				$k = 1 - $k;
			}
			?>
			</tbody>
			</table>
		</fieldset>
		
		<input type="hidden" name="option" value="com_jblance" />
		<input type="hidden" name="view" value="admproject" />
		<input type="hidden" name="layout" value="managemessage" />
		<input type="hidden" name="task" value="" />
	</form>
</div>	
<!-- Message thread section -->
<?php if(!empty($this->threads)) { ?>
<div class="width-50 fltrt">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_JBLANCE_SUBJECT').': '.$this->threads[0]->subject; ?></legend>
		<table class="<?php echo $tableClass; ?>">
		<thead>
			<tr>
				<th><?php echo JText::_('COM_JBLANCE_FROM'); ?></th>	
				<th><?php echo JText::_('COM_JBLANCE_MESSAGE'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_DATE'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $x=count($this->threads); $i < $x; $i++){
			$thread = $this->threads[$i];
			$userFrom = JFactory::getUser($thread->idFrom);
		?>
			<tr id="jbl_feed_item_<?php echo $thread->id; ?>" class="<?php echo "row$k"; ?>">
		  		<td><?php echo $userFrom->username; ?></td>
		  		<td>
		  			<?php echo $thread->message; ?>
		  			<div class="feed_date small ">
					<!-- Show attachment if found -->
					<?php
					if(!empty($thread->attachment)) : ?>
						<div style="display: inline;">
							<img src="<?php echo JURI::root();?>components/com_jblance/images/attachment.png" />
							<?php echo LinkHelper::getDownloadLink('message', $thread->id, 'admproject.download'); ?>
						</div>
					<?php	
					endif;
					?>
					</div>
		  		</td>
				<td nowrap><?php echo JHTML::_('date', $thread->date_sent, $dformat, true); ?></td>
				<td>
					<a id="feed_hide_<?php echo $thread->id; ?>" class="remFeed" onclick="processMessage('<?php echo $thread->id; ?>', 'admproject.processmessage');" href="javascript:void(0);"><?php echo JText::_('COM_JBLANCE_REMOVE'); ?></a>
					
				</td>
			</tr>
		<?php 
			$k = 1 - $k;
		}
		?>
		</tbody>
		</table>
	</fieldset>
</div>
<?php } ?>
