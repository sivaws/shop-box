<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	28 March 2012
 * @file name	:	views/project/tmpl/searchproject.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Search projects (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.tooltip');
 
 $app  	= JFactory::getApplication();
 $model = $this->getModel();
 $now 		 	= JFactory::getDate();
 $projHelper = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 $userHelper = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
 
 $keyword	  = $app->input->get('keyword', '', 'string');
 $phrase	  = $app->input->get('phrase', 'any', 'string');
 $id_categ	  = $app->input->get('id_categ', array(), 'array');
 $min_budget  = $app->input->get('min_bud', '', 'string');
 $max_budget  = $app->input->get('max_bud', '', 'string');
 $status	  = $app->input->get('status', '', 'string');
 
 $config = JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $currencycode = $config->currencyCode;
 $dformat = $config->dateFormat;
 $action = JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject');
 
?>
<script type="text/javascript">
<!--
	function checkUncheck(obj, type){
		$$('.'+type+'-parent-'+obj.alt).each(function(el){
			el.set('checked', obj.checked);
			checkUncheck(el, type);
		});
	}
//-->
</script>
<form action="<?php echo $action; ?>" method="get" name="userFormJob" enctype="multipart/form-data">
	<div class="sp10">&nbsp;</div>
	<div class="row-fluid">
		<div class="span12 text-center">
			<div class="input-append">
				<?php $tipMsg = JText::_('COM_JBLANCE_KEYWORDS').'::'.JText::_('COM_JBLANCE_SEARCH_KEYWORD_TIPS'); ?>
				<input type="text" name="keyword" id="keyword" value="<?php echo $keyword; ?>" class="input-xxlarge hasTip" title="<?php echo $tipMsg; ?>"/>
				<button class="btn" type="submit"><?php echo JText::_('COM_JBLANCE_SEARCH'); ?></button>
				<div class="sp10">&nbsp;</div>
				<label class="radio inline">
					<?php $list_phrase = $select->getRadioSearchPhrase('phrase', $phrase);	   					   		
				 	echo $list_phrase; ?>
				 </label>
			</div>
		</div>
	</div>
	<div class="lineseparator"></div>
	
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
      			<!--Sidebar content-->
      			<div class="control-group">
					<label class="control-label" for="status"><?php echo JText::_('COM_JBLANCE_PROJECT_STATUS'); ?>: </label>
					<div class="controls">
						<?php 
						$attribs = "class='input-small' size='1'";
						$list_status = $select->getSelectProjectStatus('status', $status, 'COM_JBLANCE_ANY', $attribs, '');	   					   		
						echo $list_status; ?>
					</div>
				</div>
				<div class="lineseparator"></div>
      			
      			<div class="control-group">
					<label class="control-label" for="id_categ"><?php echo JText::_('COM_JBLANCE_CATEGORIES'); ?>: </label>
					<div class="controls">
						<?php $list_categ = $select->getCheckCategory($id_categ);	   					   		
					 	echo $list_categ; ?>
					</div>
				</div>
      			<div class="lineseparator"></div>
				
				<div class="control-group">
					<label class="control-label" for="min_bud"><?php echo JText::_('COM_JBLANCE_MINIMUM_BUDGET'); ?>: </label>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" name="min_bud" id="min_bud" size="10" value="<?php echo $min_budget; ?>" class="input-small" />
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="max_bud"><?php echo JText::_('COM_JBLANCE_MAXIMUM_BUDGET'); ?>: </label>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" name="max_bud" id="max_bud" size="10" value="<?php echo $max_budget; ?>" class="input-small"/>
						</div>
					</div>
				</div>
				<div class="lineseparator"></div>
				
				<div class="form-actions">
					<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SEARCH'); ?>" class="btn btn-primary" />
				</div>
			</div>
			<div class="span9">
      		<!--Body content-->
      			<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_SEARCH_RESULTS'); ?></div>
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
			</div>
		</div>
	</div>
	
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="view" value="project" />
	<input type="hidden" name="layout" value="searchproject" />
	<input type="hidden" name="task" value="" />
</form>