<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 March 2012
 * @file name	:	views/project/tmpl/rateuser.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Rate user (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');

 $model 		= $this->getModel();
 $config		= JblanceHelper::getConfig();
 $showUsername 	= $config->showUsername;
 $nameOrUsername = ($showUsername) ? 'username' : 'name';
 
 //logic to determine if rating is being done for buyer/freelancer. Because the rating form is different for both user.
 if($this->project->publisher_userid == $this->rate->target){
 	$rate_type = 'COM_JBLANCE_BUYER';	//we are rating the buyer
 }
 elseif($this->project->assigned_userid == $this->rate->target){
 	$rate_type = 'COM_JBLANCE_FREELANCER';	//we are rating the freelancer
 }
?>
<script type="text/javascript">
<!--
function validateForm(f){
	var valid = document.formvalidator.isValid(f);
	
	if(valid == true){
		f.check.value='<?php echo JSession::getFormToken(); ?>';//send token
    }
    else {
		alert('<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY', true); ?>');
		return false;
    }
	return true;
}
//-->
</script>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userFormProject" id="userFormProject" class="form-validate form-horizontal" onsubmit="return validateForm(this);">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_RATE_USER'); ?></div>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('COM_JBLANCE_PROJECT_NAME'); ?>: </label>
		<div class="controls font16">
			<?php echo $this->project->project_title; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('COM_JBLANCE_NAME'); ?>: </label>
		<div class="controls">
			<?php
			$target_user = JFactory::getUser($this->rate->target);
			echo $target_user->$nameOrUsername.' ('.JText::_($rate_type).')'; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="quality_clarity"><?php echo ($rate_type == 'COM_JBLANCE_BUYER') ? JText::_('COM_JBLANCE_CLARITY_SPECIFICATION') : JText::_('COM_JBLANCE_QUALITY_OF_WORK'); ?>: </label>
		<div class="controls">
			<?php $rating = $model->getSelectRating('quality_clarity', '');
			echo $rating; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="communicate"><?php echo JText::_('COM_JBLANCE_COMMUNICATION'); ?>: </label>
		<div class="controls">
			<?php $rating = $model->getSelectRating('communicate', '');
			echo $rating; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="expertise_payment"><?php echo ($rate_type == 'COM_JBLANCE_BUYER') ? JText::_('COM_JBLANCE_PAYMENT_PROMPTNESS') : JText::_('COM_JBLANCE_EXPERTISE'); ?>: </label>
		<div class="controls">
			<?php $rating = $model->getSelectRating('expertise_payment', '');
			echo $rating; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="professional"><?php echo JText::_('COM_JBLANCE_PROFESSIONALISM'); ?>: </label>
		<div class="controls">
			<?php $rating = $model->getSelectRating('professional', '');
			echo $rating; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="hire_work_again"><?php echo ($rate_type == 'COM_JBLANCE_BUYER') ? JText::_('COM_JBLANCE_WORK_AGAIN') : JText::_('COM_JBLANCE_HIRE_AGAIN'); ?>: </label>
		<div class="controls">
			<?php $rating = $model->getSelectRating('hire_work_again', '');
			echo $rating; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="comments"><?php echo JText::_('COM_JBLANCE_COMMENTS'); ?>: </label>
		<div class="controls">
			<textarea name="comments" rows="5" class="input-xlarge"></textarea>
		</div>
	</div>
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SUBMIT'); ?>" class="btn btn-primary" />
	</div>
	
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="project.saverateuser" />	
	<input type="hidden" name="id" value="<?php echo $this->rate->id ; ?>" />
	<input type="hidden" name="rate_type" value="<?php echo $rate_type; ?>" />
	<?php echo JHTML::_('form.token'); ?>
	</form>