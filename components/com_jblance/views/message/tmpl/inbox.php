<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	30 May 2012
 * @file name	:	views/message/tmpl/inbox.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Inbox of Private Messages (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 
 $config 		= JblanceHelper::getConfig();
 $dformat 		= $config->dateFormat;
 $showUsername 	= $config->showUsername;
 
 $nameOrUsername = ($showUsername) ? 'username' : 'name';
 $link_compose = JRoute::_('index.php?option=com_jblance&view=message&layout=compose');
?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userForm">
	<div class="pull-right"><a href="<?php echo $link_compose; ?>" class="btn btn-primary"><span><?php echo JText::_('COM_JBLANCE_COMPOSE'); ?></span></a></div>
	<div class="sp10">&nbsp;</div>
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PRIVATE_MESSAGES'); ?></div>
	<?php 
	echo JHtml::_('tabs.start', 'panel-tabs', array('useCookie'=>'0'));
	$newTitle = ($this->newInMsg > 0) ? ' (<b>'.JText::sprintf('COM_JBLANCE_COUNT_NEW', $this->newInMsg).'</b>)' : '';
	echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_RECEIVED').$newTitle, 'received'); ?>
	<table class="table table-bordered table-hover table-striped table-condensed">
		<thead>
			<tr>
				<th><?php echo JText::_('COM_JBLANCE_FROM'); ?></th>	
				<th><?php echo JText::_('COM_JBLANCE_SUBJECT'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_DATE'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
			</tr>			
		</thead>
		<tbody>
		
		<?php
		if(count($this->in_msgs) == 0){		//Called if there are no messages -> Shows a text that spreads over the whole table
			?>
			<tr><td colspan='4' align="center"><?php echo JText::_("COM_JBLANCE_INBOX_EMPTY"); ?></td></tr>
		<?php
		}
		$k = 0;
		for ($i=0, $x=count($this->in_msgs); $i < $x; $i++){
			$in_msg = $this->in_msgs[$i];
			$userFrom = JFactory::getUser($in_msg->idFrom);
			$link_read = JRoute::_('index.php?option=com_jblance&view=message&layout=read&id='.$in_msg->id);
			
			$newMsg = JblanceHelper::countUnreadMsg($in_msg->id);
		?>
			<tr id="jbl_feed_item_<?php echo $in_msg->id; ?>">
		  		<td><a href="<?php echo $link_read; ?>"><?php echo $userFrom->$nameOrUsername; ?></a></td>
				<td><a href="<?php echo $link_read; ?>"><?php echo $in_msg->subject; ?> <?php echo ($newMsg > 0) ? '(<b>'.JText::sprintf('COM_JBLANCE_COUNT_NEW', $newMsg).'</b>)' : ''; ?></a></td>
				<td nowrap="nowrap"><?php echo JHTML::_('date', $in_msg->date_sent, $dformat, true);?></td>
				<td>
					<span id="feed_hide_<?php echo $in_msg->id; ?>" class="help-inline">
						<a class="btn btn-mini" onclick="processMessage('<?php echo $in_msg->id; ?>', 'message.processmessage');" title="<?php echo JText::_('COM_JBLANCE_REMOVE'); ?>" href="javascript:void(0);"><i class="icon-remove"></i></a>
					</span>
				</td>
			</tr>
		<?php 
			$k = 1 - $k;
		}
		?>
		</tbody>
	</table>
	<?php  
	$newTitle = ($this->newOutMsg > 0) ? ' (<b>'.JText::sprintf('COM_JBLANCE_COUNT_NEW', $this->newOutMsg).'</b>)' : '';
	echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_SENT').$newTitle, 'sent'); ?>
	<table class="table table-bordered table-hover table-striped table-condensed">
		<thead>
			<tr>
				<th><?php echo JText::_('COM_JBLANCE_TO'); ?></th>	
				<th><?php echo JText::_('COM_JBLANCE_SUBJECT'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_DATE'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
			</tr>			
		</thead>
		<tbody>
		<?php
		if(count( $this->out_msgs ) == 0){		//Called if there are no messages -> Shows a text that spreads over the whole table
			?>
			<tr><td colspan='4' align="center"><?php echo JText::_("COM_JBLANCE_INBOX_EMPTY"); ?></td></tr>
		<?php
		}
		$k = 0;
		for ($i=0, $x=count($this->out_msgs); $i < $x; $i++){
			$out_msg = $this->out_msgs[$i];
			$userTo = JFactory::getUser($out_msg->idTo);
			$link_read = JRoute::_('index.php?option=com_jblance&view=message&layout=read&id='.$out_msg->id);
			
			$newMsg = JblanceHelper::countUnreadMsg($out_msg->id);
		?>
			<tr id="jbl_feed_item_<?php echo $out_msg->id; ?>">
		  		<td><a href="<?php echo $link_read; ?>"><?php echo $userTo->$nameOrUsername; ?></a></td>
				<td><a href="<?php echo $link_read; ?>"><?php echo $out_msg->subject; ?> <?php echo ($newMsg > 0) ? '(<b>'.JText::sprintf('COM_JBLANCE_COUNT_NEW', $newMsg).'</b>)' : ''; ?></a></td>
				<td nowrap="nowrap"><?php echo JHTML::_('date', $out_msg->date_sent, $dformat, true);?></td>
				<td>
					<span id="feed_hide_<?php echo $out_msg->id; ?>" class="help-inline">
						<a class="btn btn-mini" onclick="processMessage('<?php echo $out_msg->id; ?>', 'message.processmessage');" title="<?php echo JText::_('COM_JBLANCE_REMOVE'); ?>" href="javascript:void(0);"><i class="icon-remove"></i></a>
					</span>
				</td>
			</tr>
		<?php 
			$k = 1 - $k;
		}
		?>
		</tbody>
	</table>
	<?php echo JHtml::_('tabs.end'); ?>
</form>