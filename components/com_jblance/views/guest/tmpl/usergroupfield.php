<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	views/guest/tmpl/usergroupfield.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	User Groups (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.tooltip');
 
 JblanceHelper::getMultiSelect('id_category', JText::_('COM_JBLANCE_SEARCH_SKILLS'));

 $app = JFactory::getApplication();
 $user= JFactory::getUser();
 $model = $this->getModel();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper

 //set the chosen plan in the session
 $session = JFactory::getSession();
 $ugid = $session->get('ugid', 0, 'register');
 $accountInfo 	= $session->get('userInfo', null, 'register');
 
 $jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
 $userInfo = $jbuser->getUserGroupInfo(null, $ugid);
 
 $config = JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $currencycod = $config->currencyCode;
 
 //if the user is already registered, accoutnInfo will be empty.
 if(empty($accountInfo)){
 	$accountInfo['username'] = $user->username;
 	$accountInfo['name'] = $user->name;
 }
 
 $step = $app->input->get('step', 0, 'int');
 JText::script('COM_JBLANCE_CLOSE');
?>
<script type="text/javascript">
<!--
	function validateForm(f){
		if (document.formvalidator.isValid(f)) {
			f.check.value='<?php echo JSession::getFormToken(); ?>';//send token
	    }
	    else {
		    var msg = '<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY', true); ?>';
			alert(msg);
			return false;
	    }
		return true;
	}
//-->
</script>
<?php 
if($step)
	echo JblanceHelper::getProgressBar($step); 
?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userGroup" class="form-validate form-horizontal" onsubmit="return validateForm(this);">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PROFILE_INFO'); ?></div>
	<fieldset>
		<legend><?php echo JText::_('COM_JBLANCE_USER_INFORMATION'); ?></legend>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?>:</label>
			<div class="controls">
				<?php echo $accountInfo['username']; ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('COM_JBLANCE_NAME'); ?>:</label>
			<div class="controls">
				<?php echo $accountInfo['name']; ?>
			</div>
		</div>
		<!-- Company Name should be visible only to users who can post job -->
		<?php if($userInfo->allowPostProjects) : ?>
		<div class="control-group">
			<label class="control-label" for="biz_name"><?php echo JText::_('COM_JBLANCE_BUSINESS_NAME'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input class="inputbox required" type="text" name="biz_name" id="biz_name" value="" />
			</div>
		</div>
		<?php endif; ?>
		<!-- Skills and hourly rate should be visible only to users who can work/bid -->
		<?php if($userInfo->allowBidProjects) : ?>
		<div class="control-group">
			<label class="control-label" for="rate"><?php echo JText::_('COM_JBLANCE_HOURLY_RATE'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<div class="input-prepend input-append">
					<span class="add-on"><?php echo $currencysym; ?></span>
					<input class="input-mini required" type="text" name="rate" id="rate" value="" />
					<span class="add-on"><?php echo $currencycod.' / '.JText::_('COM_JBLANCE_HOUR'); ?></span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="id_category"><?php echo JText::_('COM_JBLANCE_SKILLS'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<?php 
				$attribs = 'class="inputbox required" size="20" multiple ';
				$categtree = $select->getSelectCategoryTree('id_category[]', 0, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '', true);
				echo $categtree; ?>
			</div>
		</div>
		<?php endif; ?>
	</fieldset>
	
	<!-- Show the following profile fields only for JoomBri Profile -->
	<?php 
	$joombriProfile = false;
	$profileInteg = JblanceHelper::getProfile();
	$profileUrl = $profileInteg->getEditURL();
	if($profileInteg instanceof JoombriProfileJoombri){
		$joombriProfile = true;
	}
	
	if($joombriProfile){
		$fields = JblanceHelper::get('helper.fields');		// create an instance of the class fieldsHelper
		
		$parents = array();$children = array();
		//isolate parent and childr
		foreach($this->fields as $ct){
			if($ct->parent == 0)
				$parents[] = $ct;
			else
				$children[] = $ct;
		}
		
		if(count($parents)){
			foreach($parents as $pt){ ?>
		<fieldset>
			<legend><?php echo JText::_($pt->field_title); ?></legend>
			<?php
			foreach($children as $ct){
				if($ct->parent == $pt->id){ ?>
				<div class="control-group">
					<?php
					$labelsuffix = '';
					if($ct->field_type == 'Checkbox') $labelsuffix = '[]'; //added to validate checkbox
					?>
					<label class="control-label" for="custom_field_<?php echo $ct->id.$labelsuffix; ?>"><?php echo JText::_($ct->field_title); ?> <span class="redfont"><?php echo ($ct->required)? '*' : ''; ?></span>:</label>
					<div class="controls controls-row">
						<?php $fields->getFieldHTML($ct); ?>
					</div>
				</div>
			<?php
				}
			} ?>
		</fieldset>
			<?php
			}
		}
	}	//end of $joombriProfile 'if'
	?>
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SAVE'); ?>" class="btn btn-primary" />
	</div>
	<input type="hidden" name="option" value="com_jblance">
	<input type="hidden" name="task" value="guest.saveusernew">
	<?php echo JHTML::_('form.token'); ?>
</form>	
