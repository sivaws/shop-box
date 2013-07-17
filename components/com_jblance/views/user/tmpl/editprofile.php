<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	22 March 2012
 * @file name	:	views/user/tmpl/editprofile.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Edit profile (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.tooltip');

 JblanceHelper::getMultiSelect('id_category', JText::_('COM_JBLANCE_SEARCH_SKILLS'));
 
 $user= JFactory::getUser();
 $model = $this->getModel();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 
 $jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
 $userInfo = $jbuser->getUserGroupInfo($user->id, null);
 
 $config = JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;	
 $currencycod = $config->currencyCode;	
 
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
		    if($('rate').hasClass('invalid')){
		    	msg = msg+'\n\n* '+'<?php echo JText::_('COM_JBLANCE_PLEASE_ENTER_AMOUNT_IN_NUMERIC_ONLY', true); ?>';
		    }
			alert(msg);
			return false;
	    }
		return true;
	}
//-->
</script>
<?php include_once(JPATH_COMPONENT.'/views/profilemenu.php'); ?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userGroup" class="form-validate form-horizontal" onsubmit="return validateForm(this);">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_EDIT_PROFILE'); ?></div>
	<fieldset>
		<legend><?php echo JText::_('COM_JBLANCE_USER_INFORMATION'); ?></legend>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?>:</label>
			<div class="controls">
				<?php echo  $this->userInfo->username; ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="name"><?php echo JText::_('COM_JBLANCE_NAME'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input class="inputbox required" type="text" name="name" id="name" value="<?php echo $this->userInfo->name; ?>" />
			</div>
		</div>
		<!-- Company Name should be visible only to users who can post job -->
		<?php if($userInfo->allowPostProjects) : ?>
		<div class="control-group">
			<label class="control-label" for="biz_name"><?php echo JText::_('COM_JBLANCE_BUSINESS_NAME'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input class="inputbox required" type="text" name="biz_name" id="biz_name" value="<?php echo $this->userInfo->biz_name; ?>" />
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
					<input class="input-mini required" type="text" name="rate" id="rate" value="<?php echo $this->userInfo->rate; ?>" />
					<span class="add-on"><?php echo $currencycod.' / '.JText::_('COM_JBLANCE_HOUR'); ?></span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="id_category"><?php echo JText::_('COM_JBLANCE_SKILLS'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<?php 
				$attribs = 'class="inputbox required" size="20" multiple ';
				$categtree = $select->getSelectCategoryTree('id_category[]', explode(',', $this->userInfo->id_category), 'COM_JBLANCE_PLEASE_SELECT', $attribs, '', true);
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
					<label class="control-label" for="custom_field_<?php echo $ct->id.$labelsuffix; ?>"><?php echo JText::_($ct->field_title); ?><span class="redfont"><?php echo ($ct->required)? '*' : ''; ?></span>:</label>
				<div class="controls controls-row">
					<?php $fields->getFieldHTML($ct, $user->id); ?>
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
	else {
		echo JText::sprintf('COM_JBLANCE_CLICK_HERE_FOR_OTHER_PROFILE', $profileUrl).'<BR>';
	}
	?>
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SAVE'); ?>" class="btn btn-primary" />
	</div>
	<input type="hidden" name="option" value="com_jblance">
	<input type="hidden" name="task" value="user.saveprofile">
	<input type="hidden" name="id" value="<?php echo $this->userInfo->id; ?>">
	<?php echo JHTML::_('form.token'); ?>
</form>	
