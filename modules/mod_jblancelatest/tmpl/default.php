<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	29 March 2012
 * @file name	:	modules/mod_jblancelatest/tmpl/default.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 // no direct access
 defined('_JEXEC') or die('Restricted access');
 
 $show_logo = intval($params->get('show_logo', 1));
 $set_Itemid	= intval($params->get('set_itemid', 0));
 $Itemid = ($set_Itemid > 0) ? '&Itemid='.$set_Itemid : '';

 $config 		= JblanceHelper::getConfig();
 $currencycod 	= $config->currencyCode;
 $dformat 		= $config->dateFormat;
 $showUsername 	= $config->showUsername;
 
 $nameOrUsername = ($showUsername) ? 'username' : 'name';

 $document = JFactory::getDocument();
 $document->addStyleSheet("components/com_jblance/css/style.css");
 $document->addStyleSheet("modules/mod_jblancecategory/css/style.css");
 
 if(version_compare(JVERSION, '3.0', '>')){
	JHtml::_('bootstrap.loadCss');
 }
 else {
	jbimport('moobootstrap');
	JHtml::_('moobootstrap.loadCss');
 }

 $link_listproject = JRoute::_('index.php?option=com_jblance&view=project&layout=listproject'.$Itemid); 

 $lang = JFactory::getLanguage();
 $lang->load('com_jblance', JPATH_SITE);
?>
<div id="no-more-tables">
<table class="table table-bordered table-hover table-striped">
	<thead>
		<tr>
			<th><?php echo JText::_('MOD_JBLANCE_PROJECT_NAME'); ?></th>
			<?php if($show_categ == 1){?><th><?php echo JText::_('MOD_JBLANCE_SKILLS_REQUIRED'); ?></th><?php } ?> 
			<?php if($show_bid == 1){?><th><?php echo JText::_('MOD_JBLANCE_BIDS'); ?></th><?php } ?>
			<?php if($show_avgbid == 1){?><th><?php echo JText::sprintf('MOD_JBLANCE_AVG_BIDS', $currencycod); ?></th><?php } ?>
			<?php if($show_startdate == 1){?><th><?php echo JText::_('MOD_JBLANCE_STARTED'); ?></th><?php } ?>
			<?php if($show_enddate ==1){?><th><?php echo JText::_('MOD_JBLANCE_ENDS'); ?></th><?php } ?>
			<?php if($show_budget == 1){?><th nowrap="nowrap"><?php echo JText::_('MOD_JBLANCE_BUDGET').' ('.$currencycod.')'; ?></th><?php } ?>
			<?php if($show_publisher == 1){?><th align="center"><?php echo JText::_('MOD_JBLANCE_PUBLISHER'); ?></th><?php } ?>
		</tr>
	</thead>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $x=count($rows); $i < $x; $i++){
		$row = $rows[$i];
		$buyer = JFactory::getUser($row->publisher_userid);
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
		
		$expiredate = JFactory::getDate($row->start_date);
		$expiredate->modify("+$row->expires days");
		
		$link_proj_detail	= JRoute::_( 'index.php?option=com_jblance&view=project&layout=detailproject&id='.$row->id.$Itemid); 
		?>
		<tr>
			<td data-title="<?php echo JText::_('MOD_JBLANCE_PROJECT_NAME'); ?>">
				<ul class="promotions" style="margin: -8px 0 0 -14px;">
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
	  			<a href="<?php echo $link_proj_detail; ?>"><strong><?php echo $row->project_title; ?></strong></a>
	  		</td>
			<?php if($show_categ == 1){?><td data-title="<?php echo JText::_('MOD_JBLANCE_SKILLS_REQUIRED'); ?>"><?php echo $row->categories; ?></td><?php } ?>
			<?php if($show_bid == 1){?>
			<td data-title="<?php echo JText::_('MOD_JBLANCE_BIDS'); ?>" class="text-center">
			<?php echo ($row->is_sealed) ? '-' : $row->bids; ?>
			</td>
			<?php } ?>
			<?php if($show_avgbid == 1){?>
			<td data-title="<?php echo JText::sprintf('MOD_JBLANCE_AVG_BIDS', $currencycod); ?>" class="text-right">
			<?php
			$projHelper = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
			$avg = $projHelper->averageBidAmt($row->id);
			$avg = round($avg, 0); ?>
			<?php if($row->is_sealed) : ?>
  				-
  			<?php else : ?>
  				<?php echo JblanceHelper::formatCurrency($avg, true, false, 0); ?>
  			<?php endif; ?>
			</td>
			<?php } ?>
			<?php if($show_startdate == 1){?><td  data-title="<?php echo JText::_('MOD_JBLANCE_STARTED'); ?>" nowrap="nowrap" class="text-center"><?php echo $startdate; ?></td><?php } ?>
			<?php if($show_enddate == 1){?><td  data-title="<?php echo JText::_('MOD_JBLANCE_ENDS'); ?>" class="text-center"><?php echo JblanceHelper::showRemainingDHM($expiredate, 'SHORT'); ?></td><?php } ?>
	 		<?php if($show_budget == 1){?><td  data-title="<?php echo JText::_('MOD_JBLANCE_BUDGET').' ('.$currencycod.')'; ?>" class="text-center"><?php echo JblanceHelper::formatCurrency($row->budgetmin, true, false, 0); ?> - <?php echo JblanceHelper::formatCurrency($row->budgetmax, true, false, 0); ?></td><?php } ?>
			<?php if($show_publisher == 1){?>
			<td  data-title="<?php echo JText::_('MOD_JBLANCE_PUBLISHER'); ?>">
				<?php
				$attrib = 'class="img-polaroid" style="width: 25px; height: 25px;"';
				echo JblanceHelper::getThumbnail($row->publisher_userid, $attrib); ?>
				<?php echo LinkHelper::GetProfileLink($row->publisher_userid, $buyer->$nameOrUsername); ?>
			 </td>
			 <?php } ?>
	  </tr>
		<?php 
		$k = 1 - $k;
	}
	?>
	</tbody>
</table>
</div>
<p class="text-center">
	<a href="<?php echo $link_listproject; ?>" class="btn btn-primary"><?php echo JText::_('MOD_JBLANCE_MORE_PROJECTS'); ?></a>
</p>
