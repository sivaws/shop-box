<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 March 2012
 * @file name	:	views/project/tmpl/pickuser.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Pick user from the bidders (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/mooboomodal.js");
 $doc->addScript("components/com_jblance/js/jbmodal.js");

 $model 		= $this->getModel();
 $user 			= JFactory::getUser();
 $config 		= JblanceHelper::getConfig();
 
 $currencycode 	= $config->currencyCode;
 $dformat 		= $config->dateFormat;
 $checkFund 	= $config->checkfundPickuser;
 $showUsername 	= $config->showUsername;
 
 $nameOrUsername = ($showUsername) ? 'username' : 'name';
 
 $curr_balance = JblanceHelper::getTotalFund($user->id);
 
 $link_deposit  = JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);
 
 JText::script('COM_JBLANCE_CLOSE');
 JText::script('COM_JBLANCE_YES');
?>
<script>
<!--
	function checkBalance(){

		if(!$$('input[name=assigned_userid]:checked')[0]){
			alert('<?php echo JText::_('COM_JBLANCE_PLEASE_PICK_AN_USER_FROM_THE_LIST', true); ?>');
			return false;
		}
		
		var checkFund = parseInt('<?php echo $checkFund; ?>');

		if(checkFund){
			var balance = parseFloat('<?php echo $curr_balance; ?>');
			var assigned = $$('input[name=assigned_userid]:checked')[0].get('value');
			var bidamt = $('bidamount_'+assigned).get('value');

			if(balance < bidamt){
				modalConfirm('<?php echo JText::_('COM_JBLANCE_INSUFFICIENT_FUND'); ?>', '<?php echo JText::_('COM_JBLANCE_INSUFFICIENT_BALANCE_PICK_USER'); ?>', '<?php echo $link_deposit; ?>');
				return false;
			}
		}
		return true;	
	}
//-->
</script>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userForm">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PICK_USER').' : '.$this->project->project_title; ?></div>
	<p class="font14 jb-alignright"><b><?php echo JText::_('COM_JBLANCE_CURRENT_BALANCE'); ?> : <?php echo JblanceHelper::formatCurrency($curr_balance); ?></b></p>
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th><?php echo JText::_('COM_JBLANCE_PICK'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_FREELANCERS'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_BIDS').' ('.$currencycode.')'; ?></th>
				<th><?php echo JText::_('COM_JBLANCE_DELIVERY_DAYS'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_TIME_OF_BID'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_RATING'); ?></th>	
				<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$k = 0;
			for($i=0, $n=count($this->rows); $i < $n; $i++){
				$row = $this->rows[$i];
			?>
			<tr>
				<td rowspan="2" class="jb-aligncenter">
					<?php if($row->status == '') : ?>
					<input type="radio" name="assigned_userid" id="assigned_userid_<?php echo $row->id; ?>" value="<?php echo $row->user_id; ?>"/>
					<?php endif; ?>
				</td>
				<td>
					<?php echo LinkHelper::GetProfileLink(intval($row->user_id), $row->$nameOrUsername); ?>
				</td>
				<td>
					<?php echo JblanceHelper::formatCurrency($row->amount, true, false, 0); ?>
					<input type="hidden" id="bidamount_<?php echo $row->user_id; ?>" value="<?php echo  $row->amount; ?>" />
				</td>
				<td>
					<?php echo $row->delivery; ?>
				</td> 
				<td>
					<?php echo JHTML::_('date', $row->bid_date, $dformat); ?>
				</td>
				<td>
					<?php
					$rate = JblanceHelper::getAvarageRate($row->user_id);
					?>
				</td>
				<td><?php echo JText::_($row->status); ?></td>
			</tr>
			<tr>
				<td colspan="5" class=""><b><?php echo JText::_('COM_JBLANCE_MESSAGE'); ?></b> : <br /><em><?php echo ($row->details) ? $row->details : JText::_('COM_JBLANCE_DETAILS_NOT_PROVIDED'); ?></em></td>
				<td class="jb-aligncenter">
					<!-- Show attachment if found -->
					<?php
					if(!empty($row->attachment)) : ?>
						<div style="display: inline;">
						<?php echo LinkHelper::getDownloadLink('nda', $row->id, 'project.download'); ?>
						</div>
					<?php	
					endif;
					?>
				</td>
			</tr>
			<?php 
			$k = 1 - $k;
			} ?>
		</tbody>
	</table>
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_PICK_USER'); ?>" class="btn btn-primary" onclick="return checkBalance();" />
	</div>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="project.savepickuser" />	
	<input type="hidden" name="id" value="<?php echo $row->project_id; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>