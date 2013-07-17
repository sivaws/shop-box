<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	views/guest/tmpl/register.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	User Groups (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.modal', 'a.jb-modal');
 JHTML::_('behavior.tooltip');

 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js"); 

 //JblanceHelper::getTooltip();

 $app = JFactory::getApplication();
 $user	= JFactory::getUser();
 $model = $this->getModel();
 $config = JblanceHelper::getConfig();
 $taxpercent = $config->taxPercent;
 $taxname = $config->taxName;

 $session = JFactory::getSession();
 $ugid = $session->get('ugid', 0, 'register');
 $planChosen = $session->get('planChosen', 0, 'register');
 $planId = $session->get('planid', 0, 'register');
 $skipPlan = $session->get('skipPlan', 0, 'register');

 $jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
 
 if(empty($planId)){	//this is to check if the user has selected plan and entered this page
	$link = JRoute::_('index.php?option=com_jblance&view=guest&layout=showfront', false);
	$app->redirect($link);
 }
 
 $step = $app->input->get('step', 0, 'int');
 JText::script('COM_JBLANCE_AVAILABLE');
?>
<script type="text/javascript">
<!--
	function validateForm(f){
		if (document.formvalidator.isValid(f)) {
			f.check.value='<?php echo JSession::getFormToken(); ?>';//send token
	    }
	    else {
		    var msg = '<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY', true); ?>';
	    	if($('password2').hasClass('invalid')){
		    	msg = msg+'\n\n* '+'<?php echo JText::_('COM_JBLANCE_VERIFY_PASSWORD_INVALID', true); ?>';
		    }
			alert(msg);
			return false;
	    }
		return true;
	}
	window.addEvent('domready', function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); 
		});
	});
//-->
</script>
<?php 
if($step)
	echo JblanceHelper::getProgressBar($step); 
?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="regNewUser" class="form-horizontal form-validate" onsubmit="return validateForm(this);" enctype="multipart/form-data">
<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_ACCOUNT_INFO'); ?></div>
<?php echo JText::_('COM_JBLANCE_FIELDS_COMPULSORY'); ?>
	
	<?php if(!$skipPlan) { ?>
	<fieldset>
	<legend><?php echo JText::_('COM_JBLANCE_MEMBERSHIP_CHOSEN'); ?></legend>
		<div class="control-group">
			<label class="control-label nopadding"><?php echo JText::_('COM_JBLANCE_PLAN_NAME'); ?>: </label>
			<div class="controls">
				<?php $sub_id = $planChosen['plan_id'];
				echo $planChosen['planname'.$sub_id]; ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label nopadding"><?php echo JText::_('COM_JBLANCE_PLAN_DURATION'); ?>: </label>
			<div class="controls">
				<?php echo $planChosen['planperiod'.$sub_id]; ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label nopadding"><?php echo JText::_('COM_JBLANCE_BONUS_FUND'); ?>: </label>
			<div class="controls">
				<?php echo JblanceHelper::formatCurrency($planChosen['plancredit'.$sub_id]); ?>
			</div>
		</div>
		<?php 
			$totalamt = $planChosen['price'.$sub_id];
			if($totalamt > 0) :
			?>
		<div class="control-group">
			<label class="control-label nopadding"><?php echo JText::_('COM_JBLANCE_PAY_MODE'); ?>: </label>
			<div class="controls">
				<?php echo JblanceHelper::getGwayName($planChosen['gateway']); ?>
			</div>
		</div>
			<?php endif; ?>
		<div class="control-group">
			<label class="control-label nopadding"><?php echo JText::_('COM_JBLANCE_TOTAL_AMOUNT'); ?>: </label>
			<div class="controls">
				<?php
				$totalamt = $planChosen['price'.$sub_id];
				if($taxpercent > 0){
					$taxamt = $totalamt * ($taxpercent/100);
					$totalamt = $taxamt + $totalamt;
				}
				echo JblanceHelper::formatCurrency($totalamt);
				if($taxpercent > 0 && $totalamt > 0){
					echo ' ('.JblanceHelper::formatCurrency($planChosen['price'.$sub_id]).' + '.JblanceHelper::formatCurrency($taxamt).')';
				}
				?>
			</div>
		</div>
	</fieldset>
	<?php } ?>
	
	<fieldset>
	<legend><?php echo JText::_('COM_JBLANCE_USER_INFORMATION'); ?></legend>
		<div class="control-group">
			<label class="control-label" for="name"><?php echo JText::_('COM_JBLANCE_NAME'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input class="inputbox required" type="text" name="name" id="name" size="40" value="" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="username"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input type="text" name="username" id="username" class="inputbox hasTip required" onchange="checkAvailable(this);" title="<?php echo JText::_('COM_JBLANCE_TT_USERNAME'); ?>"> 
				<div id="status_username" class="dis-inl-blk"></div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="email"><?php echo JText::_('COM_JBLANCE_EMAIL'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input type="text" name="email" id="email" class="inputbox hasTip required validate-email" onchange="checkAvailable(this);" title="<?php echo JText::_('COM_JBLANCE_TT_EMAIL'); ?>">
				<div id="status_email" class="dis-inl-blk"></div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password"><?php echo JText::_('COM_JBLANCE_PASSWORD'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input type="password" name="password" id="password" class="inputbox hasTip required validate-password" title="<?php echo JText::_('COM_JBLANCE_TT_PASSWORD'); ?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password2"><?php echo JText::_('COM_JBLANCE_CONFIRM_PASSWORD'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
					<input type="password" size="40" maxlength="100" name="password2" id="password2" class="inputbox hasTip required validate-passverify" title="<?php echo JText::_('COM_JBLANCE_TT_REPASSWORD'); ?>">
			</div>
		</div>
	</fieldset>
	
	<?php
	$termid = $config->termArticleId;
	$link = JRoute::_("index.php?option=com_content&view=article&id=".$termid.'&tmpl=component');
	?>
	<p><?php echo JText::sprintf('COM_JBLANCE_BY_CLICKING_YOU_AGREE', $link); ?></p>
	
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_( 'COM_JBLANCE_I_ACCEPT_CREATE_MY_ACCOUNT' ); ?>" class="btn btn-primary" />
	</div>
		
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="guest.grabuseraccountinfo" />
	<?php echo JHTML::_('form.token'); ?>
</form>