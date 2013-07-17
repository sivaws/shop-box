<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 March 2012
 * @file name	:	views/project/tmpl/placebid.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows details of the project (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.tooltip');
 JHTML::_('behavior.modal', 'a.jb-modal');
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 $doc->addScript("components/com_jblance/js/upclick-min.js");
 
 $project = $this->project;
 $bid = $this->bid;
 
 $config = JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $currencycode = $config->currencyCode;
 
 $user = JFactory::getUser();
 $finance = JblanceHelper::get('helper.finance');		// create an instance of the class FinanceHelper
 ?>
 <script type="text/javascript">
<!--
function validateForm(f){
	var valid = document.formvalidator.isValid(f);
	var budgetmin = parseFloat('<?php echo $project->budgetmin; ?>');
	var budgetmax = parseFloat('<?php echo $project->budgetmax; ?>');
	var bidamount = $('amount').get('value');

	if(valid == true){
		//check if agreement is selected
		if($('is_nda_signed') && !$('is_nda_signed').checked){
			alert('<?php echo JText::_('COM_JBLANCE_PLEASE_REVIEW_AGREE_NDA', true); ?>');
			return false;
		}
		//check the amount is within the budget range
		if(bidamount < budgetmin || bidamount > budgetmax){
			alert('<?php echo JText::sprintf('COM_JBLANCE_PLEASE_ENTER_AMOUNT_BETWEEN_BUDGET_RANGE', JblanceHelper::formatCurrency($project->budgetmin), JblanceHelper::formatCurrency($project->budgetmax)); ?>');
			return false;
		}
		
		f.check.value='<?php echo JSession::getFormToken(); ?>';//send token
    }
    else {
    	var msg = '<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY', true); ?>';
    	if($('amount').hasClass('invalid') || $('delivery').hasClass('invalid')){
	    	msg = msg+'\n\n* '+'<?php echo JText::_('COM_JBLANCE_PLEASE_ENTER_AMOUNT_IN_NUMERIC_ONLY', true); ?>';
	    }
		alert(msg);
		return false;
    }
	return true;
}
function togglePrivateMsg() {
	if($('sendpm').checked){
		$('messagediv').setStyle('display', 'block');
		$('message').addClass('required');
	}
	else {
		$('messagediv').setStyle('display', 'none');
		$('message').removeClass('required');
	}
}
window.addEvent('domready', function(){
	if($('uploadmessage')){
		attachFile('uploadmessage', 'message.attachfile');
	}
});
//-->
</script>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userFormBid" id="userFormBid" class="form-validate form-horizontal" onsubmit="return validateForm(this);" enctype="multipart/form-data">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_BID_ON_PROJECT').' : '.$project->project_title; ?></div>
	<?php 
	$lastSubscr = $finance->getLastSubscription($user->id);
	if($lastSubscr->bids_allowed > 0) :
	?>
	<div class="bid_project_left pull-right">
	    <div><span class="font26"><?php echo $lastSubscr->bids_left; ?></span>/<span><?php echo $lastSubscr->bids_allowed; ?></span></div>
	    <div><?php echo JText::_('COM_JBLANCE_BIDS_LEFT'); ?></div>
	</div>
	<?php endif; ?>
	<div class="control-group">
		<label class="control-label" for="amount"><?php echo JText::_('COM_JBLANCE_YOUR_BID_FOR_PROJECT'); ?>:</label>
		<div class="controls">
			<div class="input-prepend input-append">
				<span class="add-on"><?php echo $currencysym; ?></span>
				<input type="text" name="amount" id="amount" class="input-small required validate-numeric" value="<?php echo $bid->amount; ?>" />
				<span class="add-on"><?php echo $currencycode; ?></span>
			</div>
			<span class="help-inline">
				<?php echo JText::_('COM_JBLANCE_BUDGET_RANGE'); ?> : <?php echo JblanceHelper::formatCurrency($project->budgetmin, true, false, 0); ?> - <?php echo JblanceHelper::formatCurrency($project->budgetmax, true, false, 0).' '.$currencycode; ?>
			</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="delivery"><?php echo JText::_('COM_JBLANCE_DAYS_DELIVER_PROJECT'); ?>:</label>
		<div class="controls">
			<input type="text" name="delivery" id="delivery" class="input-small required validate-numeric" value="<?php echo $bid->delivery; ?>" />&nbsp;<?php echo JText::_('COM_JBLANCE_BID_DAYS'); ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="details"><?php echo JText::_('COM_JBLANCE_BID_DETAILS'); ?>: </label>
		<div class="controls">
			<textarea name="details" id="details" rows="5" class="input-xlarge required"><?php echo $bid->details; ?></textarea>
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<!-- hide the outbid notification for sealed projects -->
			<?php if(!$this->project->is_sealed) : ?>
			<label class="checkbox">
				<input type="checkbox" name="outbid" id="outbid" value="1" <?php echo ($bid->outbid == 1) ? 'checked' : ''; ?> />
 				<?php echo JText::_('COM_JBLANCE_NOTIFY_OUT_BIDS'); ?>
			</label>
			<?php endif; ?>
			
			<!-- show the PM only for the first time placing bid -->
			<?php if($bid->id == 0) : ?>
			<label class="checkbox">
				<input type="checkbox" name="sendpm" id="sendpm" value="1" onclick="javascript:togglePrivateMsg();" />
 				<?php echo JText::_('COM_JBLANCE_SEND_PM_TO_PUBLISHER'); ?>
			</label>
			<div id="messagediv" style="display: none;">
				<textarea name="message" id="message" rows="5" class="input-xlarge"></textarea>
				<div id="ajax-container-uploadmessage"></div>
				<div id="file-attached-uploadmessage"></div>
				<input type="button" id="uploadmessage" value="<?php echo JText::_('COM_JBLANCE_ATTACH_FILE'); ?>" class="btn">
				<?php 
				$tipmsg = JText::_('COM_JBLANCE_ATTACH_FILE').'::'.JText::_('COM_JBLANCE_ALLOWED_FILE_TYPES').' : '.$config->projectFileText.'<br>'.JText::_('COM_JBLANCE_MAXIMUM_FILE_SIZE').' : '.$config->projectMaxsize.' kB';
				?>
				<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
				<input type="hidden" name="subject" value="<?php echo $project->project_title;?>" />
			</div>
			<?php endif; ?>
		</div>
	</div>
	
	<!-- show the agreement form is it is NDA project and not signed -->
	<?php if($this->project->is_nda && !($bid->is_nda_signed)) : ?>
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_SIGN_NDA'); ?></div>
	<div class="well well-small jbbox-gradient">
		<?php 
		$ndaFile  =  JURI::root().'components/com_jblance/images/nda.txt';
		echo JText::sprintf('COM_JBLANCE_NDA_BID_MUST_AGREE', $ndaFile); ?>
		<div class="sp20">&nbsp;</div>
		<label class="checkbox"><input type="checkbox" name="is_nda_signed" id="is_nda_signed" value="1" /><?php echo JText::_('COM_JBLANCE_NDA_FREELANCER_AGREE_TO_NDA'); ?></label>
	</div>
	<?php elseif($this->project->is_nda && $bid->is_nda_signed) : ?>
		<div class="jbbox-info"><?php echo JText::_('COM_JBLANCE_NDA_ALREADY_SIGNED_AGREEMENT');?>
		<!-- Show attachment if found -->
		<?php
		if(!empty($bid->attachment)) : ?>
			<div style="display: inline;">
			<?php echo LinkHelper::getDownloadLink('nda', $bid->id, 'project.download'); ?>
			</div>
		<?php	
		endif;
		?>
		</div>
	<?php endif; ?>
	
	<div class="clearfix"></div>
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SAVE'); ?>" class="btn btn-primary" /> 
		<input type="button" value="<?php echo JText::_('COM_JBLANCE_CANCEL'); ?>" onclick="javascript:history.back();" class="btn btn-primary" />
	</div>
	
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="project.savebid" />		
	<input type="hidden" name="id" value="<?php echo $bid->id;?>" />
	<input type="hidden" name="project_id" value="<?php echo $project->id;?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>