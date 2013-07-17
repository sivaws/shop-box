<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	views/project/tmpl/showmyproject.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	List of projects posted by the user (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.modal', 'a.jb-modal');
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/mooboomodal.js");
 $doc->addScript("components/com_jblance/js/jbmodal.js");

 $model				  = $this->getModel();
 $config 			  = JblanceHelper::getConfig();
 $enableEscrowPayment = $config->enableEscrowPayment;
 $showUsername 		  = $config->showUsername;
 $nameOrUsername 	  = ($showUsername) ? 'username' : 'name';
 
 JText::script('COM_JBLANCE_CLOSE');
 JText::script('COM_JBLANCE_YES');
?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userForm">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_MY_PROJECTS'); ?></div>
	<div id="no-more-tables">
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th><?php echo JText::_('#'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_PROJECT_NAME'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_BIDS'); ?></th>	
				<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
				<?php if($enableEscrowPayment) { ?><th><?php echo JText::_('COM_JBLANCE_PAYMENT_STATUS'); ?></th><?php } ?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="6">
					<div class="pagination">
						<?php echo $this->pageNav->getListFooter(); ?>
					</div>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
		$k = 0;
		$n=count($this->rows);
		for ($i=0;  $i < $n; $i++) {
			$row = $this->rows[$i];
	
			$link_proj_detail = JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&id='.$row->id);
			$link_edit 		  = JRoute::_('index.php?option=com_jblance&view=project&layout=editproject&id='.$row->id);	
			$link_pick_user	  = JRoute::_('index.php?option=com_jblance&view=project&layout=pickuser&id='.$row->id);
			$link_transfer	  = JRoute::_('index.php?option=com_jblance&view=membership&layout=escrow');
			$link_del  		  = JRoute::_('index.php?option=com_jblance&task=project.removeproject&id='.$row->id.'&'.JSession::getFormToken().'=1');
			$link_reopen_proj = JRoute::_('index.php?option=com_jblance&task=project.reopenproject&id='.$row->id.'&'.JSession::getFormToken().'=1');
			$bidsCount 		  = $model->countBids($row->id);
			$bidInfo 		  = $model->getBidInfo($row->id, $row->assigned_userid);
			$link_invoice 	  = JRoute::_('index.php?option=com_jblance&view=membership&layout=invoice&id='.$row->id.'&tmpl=component&print=1&type=project&usertype=buyer');
			?>
			<tr>
				<td data-title="<?php echo JText::_('#'); ?>">
					<?php echo $this->pageNav->getRowOffset($i); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_PROJECT_NAME'); ?>">
					<a href="<?php echo $link_proj_detail; ?>"><?php echo $row->project_title;?></a>
					<?php 
					if($row->approved == 0)
						echo '<small>('.JText::_('COM_JBLANCE_PENDING_APPROVAL').')</small>';
					?>
					<div class="fr">
			  			<?php if($row->is_featured) : ?>
			  			<img src="components/com_jblance/images/featured.png" alt="Featured" width="16" class="" title="<?php echo JText::_('COM_JBLANCE_FEATURED_PROJECT'); ?>" />
			  			<?php endif; ?>
			  			<?php if($row->is_urgent) : ?>
			  			<img src="components/com_jblance/images/urgent.png" alt="Urgent" width="16" class="" title="<?php echo JText::_('COM_JBLANCE_URGENT_PROJECT'); ?>" />
			  			<?php endif; ?>
			  			<?php if($row->is_private) : ?>
			  			<img src="components/com_jblance/images/private.png" alt="Private" width="16" class="" title="<?php echo JText::_('COM_JBLANCE_PRIVATE_PROJECT'); ?>" />
			  			<?php endif; ?>
			  			<?php if($row->is_sealed) : ?>
			  			<img src="components/com_jblance/images/sealed.png" alt="Sealed" width="16" class="" title="<?php echo JText::_('COM_JBLANCE_SEALED_PROJECT'); ?>" />
			  			<?php endif; ?>
			  			<?php if($row->is_nda) : ?>
			  			<img src="components/com_jblance/images/nda.png" alt="nda" width="16" class="" title="<?php echo JText::_('COM_JBLANCE_NDA_PROJECT'); ?>" />
			  			<?php endif; ?>
		  			</div>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_STATUS'); ?>">
					<?php echo JText::_($row->status);?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_BIDS'); ?>">
					<?php echo $bidsCount;?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_ACTION'); ?>">
					<?php 
					if($row->status == 'COM_JBLANCE_OPEN'){ ?>
						<a href="<?php echo $link_edit; ?>"><?php echo JText::_('COM_JBLANCE_EDIT'); ?></a> /
						<a href="javascript:void(0);" onclick="javascript:modalConfirm('<?php echo JText::_('COM_JBLANCE_DELETE', true); ?>', '<?php echo JText::_('COM_JBLANCE_CONFIRM_DELETE_PROJECT', true); ?>', '<?php echo $link_del; ?>');" ><?php echo JText::_('COM_JBLANCE_DELETE'); ?></a>
					<?php 
						if($bidsCount > 0){ ?> 
							/ <a href="<?php echo $link_pick_user;?>"><?php echo JText::_('COM_JBLANCE_PICK_USER'); ?></a>
					<?php 
						} ?>
					<?php 
					}
					elseif($row->status == 'COM_JBLANCE_CLOSED'){
						//get Rate
						$rate = $model->getRate($row->id, $row->assigned_userid);
						
						if($rate->quality_clarity == 0){
							$link_rate = JRoute::_('index.php?option=com_jblance&view=project&layout=rateuser&id='.$rate->id); ?>
						<a href="<?php echo $link_rate;?>"><?php echo JText::_('COM_JBLANCE_RATE_FREELANCER'); ?></a>
					<?php
						}
					}
					elseif($row->status == 'COM_JBLANCE_FROZEN'){
						//bid status check
						$detail_chosen = JFactory::getUser($row->assigned_userid);
						
						if($bidInfo->status == 'COM_JBLANCE_DENIED'){
							echo JText::_('COM_JBLANCE_STATUS_DENIED_BY').' - '.$detail_chosen->$nameOrUsername.'<br/>';
						?>
							<a href="<?php echo $link_pick_user; ?>"><?php echo JText::_('COM_JBLANCE_PICK_USER'); ?></a>&nbsp;|&nbsp;
							<a href="<?php echo $link_reopen_proj; ?>"><?php echo JText::_('COM_JBLANCE_REOPEN'); ?></a>

						<?php
						}
						elseif($bidInfo->status == ''){
							echo JText::_('COM_JBLANCE_STATUS_WAITING'); ?>
							<br /><a href="<?php echo $link_reopen_proj; ?>"><?php echo JText::_('COM_JBLANCE_REOPEN'); ?></a>
						<?php
						}
					}?>
					<?php if(($row->lancer_commission > 0)  && ($row->status == 'COM_JBLANCE_CLOSED')){ ?>
					<div class="fr">
						<a rel="{handler: 'iframe', size: {x: 650, y: 500}}" href="<?php echo $link_invoice; ?>" class="jb-modal"><img src="components/com_jblance/images/print.png" title="<?php echo JText::_('COM_JBLANCE_PRINT_INVOICE'); ?>" width="18" alt="Print"/></a>
						<?php
						if(!empty($bidInfo->attachment)) : ?>
							<div style="display: inline;">
							<?php echo LinkHelper::getDownloadLink('nda', $bidInfo->bidid, 'project.download'); ?>
							</div>
						<?php	
						endif;
						?>
					</div>
				<?php } ?>
				</td>
				<?php if($enableEscrowPayment) { ?>
				<td class="text-center" data-title="<?php echo JText::_('COM_JBLANCE_PAYMENT_STATUS'); ?>">
					<?php 
					if($row->status == 'COM_JBLANCE_CLOSED'){ 
						$perc = ($row->paid_amt/$bidInfo->bidamount)*100;
						echo round($perc, 2).'%';
						if($perc < 100){
					?>
					<a href="<?php echo $link_transfer; ?>"><?php echo JText::_('COM_JBLANCE_PAY_NOW'); ?></a>
					<?php
						}
					}
					else {
						echo JText::_('COM_JBLANCE_NA');
					}
					?>
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
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="" />	
</form>