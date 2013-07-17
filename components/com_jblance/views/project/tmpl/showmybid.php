<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 March 2012
 * @file name	:	views/project/tmpl/showmybid.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	List of projects posted by the user (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.tooltip');
 JHTML::_('behavior.modal', 'a.jb-modal');
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/mooboomodal.js");
 $doc->addScript("components/com_jblance/js/jbmodal.js");
 
 $model 				= $this->getModel();
 $user					= JFactory::getUser();
 $config 				= JblanceHelper::getConfig();
 $projhelp 				= JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 
 $currencysym 			= $config->currencySymbol;
 $currencycode 			= $config->currencyCode;
 $enableEscrowPayment 	= $config->enableEscrowPayment;
 $checkFund 			= $config->checkfundAcceptoffer;
 
 $curr_balance 			= JblanceHelper::getTotalFund($user->id);
 
 JText::script('COM_JBLANCE_CLOSE');
 JText::script('COM_JBLANCE_YES');
 
 $link_deposit  = JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);
?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userForm">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_MY_BIDS'); ?></div>
	<div id="no-more-tables">
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th><?php echo JText::_('COM_JBLANCE_PROJECT_NAME'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_BIDS')." ($currencysym)"; ?></th>
				<th><?php echo JText::_('COM_JBLANCE_BID_STATUS'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
				<?php if($enableEscrowPayment) { ?><th><?php echo JText::_('COM_JBLANCE_PAYMENT_STATUS'); ?></th><?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php
			$k = 0;
			for($i=0, $n=count($this->rows); $i < $n; $i++){
				$row 			  = $this->rows[$i];
				$link_accept_bid  = JRoute::_('index.php?option=com_jblance&task=project.acceptbid&id='.$row->id.'&'.JSession::getFormToken().'=1');
				$link_deny_bid	  = JRoute::_('index.php?option=com_jblance&task=project.denybid&id='.$row->id.'&'.JSession::getFormToken().'=1');
				$link_retract_bid = JRoute::_('index.php?option=com_jblance&task=project.retractbid&id='.$row->id.'&'.JSession::getFormToken().'=1');
				$link_edit_bid    = JRoute::_('index.php?option=com_jblance&view=project&layout=placebid&id='.$row->proj_id);
				$link_proj_detail = JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&id='.$row->proj_id);
				$link_invoice 	  = JRoute::_('index.php?option=com_jblance&view=membership&layout=invoice&id='.$row->proj_id.'&tmpl=component&print=1&type=project&usertype=freelancer');
				
				$projectFee = $projhelp->calculateProjectFee($user->id, $row->amount, 'freelancer');
			?>
			<tr>
				<td data-title="<?php echo JText::_('COM_JBLANCE_PROJECT_NAME'); ?>">
					<a href="<?php echo $link_proj_detail;?>"> <?php echo $row->project_title; ?></a>
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
					<?php echo JText::_($row->proj_status); ?>
				</td>
				<td class="text-right" data-title="<?php echo JText::_('COM_JBLANCE_BIDS'); ?>">
					<?php echo JblanceHelper::formatCurrency($row->amount, false, false, 0); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_BID_STATUS'); ?>">
					<?php echo JText::_($row->status); ?>
				</td>
				<td data-title="<?php echo JText::_('COM_JBLANCE_ACTION'); ?>">&nbsp;
				<?php
				if($row->assigned_userid == $user->id){
					if($row->status == ''){ ?>
						<!-- check if the user has enough fund and check fund is enabled to accept the bid -->
						<?php echo JText::_('COM_JBLANCE_BID_WON'); ?> - 
						<?php 
						if($checkFund && ($curr_balance < $projectFee)){ 
							$insuffMsg = JText::sprintf('COM_JBLANCE_INSUFFICIENT_BALANCE_TO_ACCEPT_THIS_OFFER', JblanceHelper::formatCurrency($curr_balance), JblanceHelper::formatCurrency($projectFee));
						?>
						<a href="javascript:void(0);" onclick="javascript:modalConfirm('<?php echo JText::_('COM_JBLANCE_INSUFFICIENT_FUND'); ?>', '<?php echo $insuffMsg; ?>', '<?php echo $link_deposit; ?>');"><?php echo JText::_('COM_JBLANCE_ACCEPT'); ?></a>
						<?php 
						} else { ?>
						<a href="javascript:void(0);" onclick="javascript:modalConfirm('<?php echo JText::_('COM_JBLANCE_ACCEPT'); ?>', '<?php echo JText::sprintf('COM_JBLANCE_CONFIRM_ACCEPT_BID', JblanceHelper::formatCurrency($projectFee)); ?>', '<?php echo $link_accept_bid; ?>');" ><?php echo JText::_('COM_JBLANCE_ACCEPT'); ?></a>
						<?php } ?> <!-- end of check fund -->
						 / 
						<a href="javascript:void(0);" onclick="javascript:modalConfirm('<?php echo JText::_('COM_JBLANCE_DENY'); ?>', '<?php echo JText::_('COM_JBLANCE_CONFIRM_DENY_BID'); ?>', '<?php echo $link_deny_bid; ?>');" ><?php echo JText::_('COM_JBLANCE_DENY'); ?></a>
				<?php	
					}
					elseif($row->status == 'COM_JBLANCE_ACCEPTED'){
						//get id rate first
						$rate =  $model->getRate($row->project_id, $row->publisher_userid);
						if($rate->quality_clarity == 0){
							$link_rate = JRoute::_('index.php?option=com_jblance&view=project&layout=rateuser&id='.$rate->id); ?>
							<a href="<?php echo $link_rate; ?>"><?php echo JText::_('COM_JBLANCE_RATE_BUYER'); ?></a>
				<?php			
						}
					}
				}
				else { ?>
					<a href="javascript:void(0);" onclick="javascript:modalConfirm('<?php echo JText::_('COM_JBLANCE_RETRACT_BID'); ?>', '<?php echo JText::_('COM_JBLANCE_CONFIRM_RETRACT_BID'); ?>', '<?php echo $link_retract_bid; ?>');" ><?php echo JText::_('COM_JBLANCE_RETRACT_BID'); ?></a> / 
					<a href="<?php echo $link_edit_bid; ?>"><?php echo JText::_('COM_JBLANCE_EDIT_BID'); ?></a>
				<?php
				}
				?>
				<!-- show the print invoice if the commission is > 0 and status is accepted -->
				<?php if(($row->lancer_commission > 0) && ($row->status == 'COM_JBLANCE_ACCEPTED')){ ?>
					<div class="fr">
						<a rel="{handler: 'iframe', size: {x: 650, y: 500}}" href="<?php echo $link_invoice; ?>" class="jb-modal"><img src="components/com_jblance/images/print.png" title="<?php echo JText::_('COM_JBLANCE_PRINT_INVOICE'); ?>" width="18" alt="Print"/></a>
					</div>
				<?php } ?>
				</td>
				<?php if($enableEscrowPayment) { ?>
				<td class="text-center" data-title="<?php echo JText::_('COM_JBLANCE_PAYMENT_STATUS'); ?>">
					<?php 
					if($row->status == 'COM_JBLANCE_ACCEPTED'){
						$perc = ($row->paid_amt/$row->amount)*100;
						echo round($perc, 2).'%';
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
			} ?>
		</tbody>
	</table>
	</div>
</form>