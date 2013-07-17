<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 March 2012
 * @file name	:	views/project/tmpl/listproject.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of projects (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

 $model 		= $this->getModel();
 $now 		 	= JFactory::getDate();
 $config 		= JblanceHelper::getConfig();
 $currencycode 	= $config->currencyCode;
 $dformat 		= $config->dateFormat;
 $showUsername 	= $config->showUsername;
 
 $nameOrUsername = ($showUsername) ? 'username' : 'name';

 $action	= JRoute::_('index.php?option=com_jblance&view=project&layout=listproject');
 $link_search	= JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject');
 
 $projHelper = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 $userHelper = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
?>
<form action="<?php echo $action; ?>" method="post" name="userForm">
	<a href="<?php echo $link_search; ?>" class="pull-right btn btn-primary"><?php echo JText::_('COM_JBLANCE_SEARCH_PROJECTS'); ?></a>
	<div class="sp10">&nbsp;</div>
	<div class="jbl_h3title"><?php echo $this->escape($this->params->get('page_heading', JText::_('COM_JBLANCE_LIST_OF_PROJECTS'))); ?></div>
	<?php
	for ($i=0, $x=count($this->rows); $i < $x; $i++){
		$row = $this->rows[$i];
		$buyer = $userHelper->getUser($row->publisher_userid);
		$daydiff = $row->daydiff;
		
		if($daydiff == -1){
			$startdate = JText::_('COM_JBLANCE_YESTERDAY');
		}
		elseif($daydiff == 0){
			$startdate = JText::_('COM_JBLANCE_TODAY');
		}
		else {
			$startdate =  JHTML::_('date', $row->start_date, $dformat, true);
		}
		
		// calculate expire date and check if expired
		$expiredate = JFactory::getDate($row->start_date);
		$expiredate->modify("+$row->expires days");
		$isExpired = ($now > $expiredate) ? true : false;
		
		/* if($isExpired)
			$statusLabel = 'label';
		else */if($row->status == 'COM_JBLANCE_OPEN')
			$statusLabel = 'label label-success';
		elseif($row->status == 'COM_JBLANCE_FROZEN')
			$statusLabel = 'label label-warning';
		elseif($row->status == 'COM_JBLANCE_CLOSED')
			$statusLabel = 'label label-important';
		
		$link_proj_detail	= JRoute::_( 'index.php?option=com_jblance&view=project&layout=detailproject&id='.$row->id);
		$bidsCount = $model->countBids($row->id);
		
		//calculate average bid
		$avg = $projHelper->averageBidAmt($row->id);
		$avg = round($avg, 0);
	?>
	<div class="row-fluid">
		<div class="span1">
		<?php
		$attrib = 'width=56 height=56 class="img-polaroid"';
		$avatar = JblanceHelper::getThumbnail($row->publisher_userid, $attrib);
		echo !empty($avatar) ? LinkHelper::GetProfileLink($row->publisher_userid, $avatar) : '&nbsp;' ?>
		</div>
		<div class="span6">
			<h3 class="media-heading">
				<a href="<?php echo $link_proj_detail; ?>"><?php echo $row->project_title; ?></a>
			</h3>
			<div class="font14">
				<strong><?php echo JText::_('COM_JBLANCE_POSTED_BY'); ?></strong> : <?php echo LinkHelper::GetProfileLink($row->publisher_userid, $buyer->biz_name); ?>
			</div>
			<div class="font14">
				<strong><?php echo JText::_('COM_JBLANCE_SKILLS_REQUIRED'); ?></strong> : <?php echo JblanceHelper::getCategoryNames($row->id_category); ?>
			</div>
			<ul class="promotions">
				<?php if($row->is_featured) : ?>
				<li data-promotion="featured"><?php echo JText::_('COM_JBLANCE_FEATURED'); ?></li>
				<?php endif; ?>
				<?php if($row->is_private) : ?>
	  			<li data-promotion="private"><?php echo JText::_('COM_JBLANCE_PRIVATE'); ?></li>
	  			<?php endif; ?>
				<?php if($row->is_urgent) : ?>
	  			<li data-promotion="urgent"><?php echo JText::_('COM_JBLANCE_URGENT'); ?></li>
	  			<?php endif; ?>
	  			<?php if($row->is_sealed) : ?>
				<li data-promotion="sealed"><?php echo JText::_('COM_JBLANCE_SEALED'); ?></li>
				<?php endif; ?>
				<?php if($row->is_nda) : ?>
				<li data-promotion="nda"><?php echo JText::_('COM_JBLANCE_NDA'); ?></li>
				<?php endif; ?>
			</ul>
		</div>
		<div class="span3">
			<div>
				<i class="icon-tags"></i> <?php echo JText::_('COM_JBLANCE_BIDS'); ?> : 
				<?php if($row->is_sealed) : ?>
	        		<span class="label label-info"><?php echo JText::_('COM_JBLANCE_SEALED'); ?></span>
	  			<?php else : ?>
	  			<span class="badge badge-info"><?php echo $bidsCount; ?></span>
	  			<?php endif; ?>
			</div>
			<div>
				<i class="icon-flag"></i> <?php echo JText::_('COM_JBLANCE_STATUS'); ?> : 
				<span class="<?php echo $statusLabel; ?>">
					<?php //if(!$isExpired) : ?>
					<?php echo JText::_($row->status); ?>
					<?php //else : ?>
					<?php //echo JText::_('COM_JBLANCE_EXPIRED'); ?>
					<?php //endif; ?>
				</span>
			</div>
		</div>
		<div class="span2">
			<div class="bid_project_left text-center">
				<div><?php echo JText::_('COM_JBLANCE_AVG_BID'); ?></div>
				<?php if($row->is_sealed) : ?>
	        	<span class="label label-important"><?php echo JText::_('COM_JBLANCE_SEALED'); ?></span>
	  			<?php else : ?>
	  			<span class="font16 boldfont"><?php echo JblanceHelper::formatCurrency($avg, true, false, 0); ?></span>
	  			<?php endif; ?>
  			</div>
		</div>
	</div>
	<div class="lineseparator"></div>
	<?php 
	}
	?>
	<div class="pagination">
	<?php echo $this->pageNav->getListFooter(); ?>
	</div>
	<?php 
	$link_rss = JRoute::_('index.php?option=com_jblance&view=project&format=feed');
	$rssvisible = (!$config->showRss) ? 'style=display:none' : '';
	?>
	<div class="jbrss" <?php echo $rssvisible; ?>>
		<div id="showrss" class="fr">
			<a href="<?php echo $link_rss; ?>" target="_blank">
				<img src="components/com_jblance/images/rss.png" alt="RSS" title="<?php echo JText::_('COM_JBLANCE_RSS_IMG_ALT'); ?>">
			</a>
		</div>
	</div>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="" />	
</form>