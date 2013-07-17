<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	views/project/tmpl/editproject.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Post / Edit project (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.modal', 'a.jb-modal');
 JHTML::_('behavior.tooltip');
 
 JblanceHelper::getMultiSelect('id_category', JText::_('COM_JBLANCE_SEARCH_SKILLS'));

 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 $finance = JblanceHelper::get('helper.finance');		// create an instance of the class FinanceHelper
 $editor = JFactory::getEditor();
 $user = JFactory::getUser();

 $config = JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $fileLimitConf = $config->projectMaxfileCount;
 $reviewProjects = $config->reviewProjects;

 $isNew = ($this->row->id == 0) ? 1 : 0;
 $title = $isNew ? JText::_('COM_JBLANCE_POST_NEW_PROJECT') : JText::_('COM_JBLANCE_EDIT_PROJECT');

 //get the project upgrade amounts based on the plan
 $plan 				 = JblanceHelper::whichPlan($user->id);
 $featuredProjectFee = $plan->buyFeePerFeaturedProject;
 $urgentProjectFee 	 = $plan->buyFeePerUrgentProject;
 $privateProjectFee	 = $plan->buyFeePerPrivateProject;
 $sealedProjectFee	 = $plan->buyFeePerSealedProject;
 $ndaProjectFee		 = $plan->buyFeePerNDAProject;
 $chargePerProject	 = $plan->buyChargePerProject;
 
 $totalFund = JblanceHelper::getTotalFund($user->id);
 JText::script('COM_JBLANCE_CLOSE');
 
 $ndaFile = JURI::root().'components/com_jblance/images/nda.txt';
?>
<script type="text/javascript">
<!--
	function validateForm(f){
		var valid = document.formvalidator.isValid(f);
		
		if(valid == true){
			var isNew = '<?php echo $isNew?>';
			var grandTotal = 0;
			var totalFund = parseFloat('<?php echo $totalFund; ?>');
			//check for grand_total = charge_per_project + project_upgrade_fee < total_fund for new project
			//grand_total = project_upgrade_fee < total_fund for old project
			if(isNew == 1)
				grandTotal = parseFloat('<?php echo $chargePerProject; ?>') + parseFloat($('totalamount').get('value'));
			else
				grandTotal = parseFloat($('totalamount').get('value'));

			$('subtotal').set('html', grandTotal);

			if(totalFund < grandTotal){
				alert('<?php echo JText::_('COM_JBLANCE_BALANCE_INSUFFICIENT_TO_PROMOTE_PROJECT', true); ?>');
				return false;
			}
			f.check.value='<?php echo JSession::getFormToken(); ?>';//send token
	    }
	    else {
		    var msg = '<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY', true); ?>';
		    if($('expires').hasClass('invalid')){
		    	msg = msg+'\n\n* '+'<?php echo JText::_('COM_JBLANCE_PLEASE_ENTER_VALUE_IN_NUMERIC_ONLY', true); ?>';
		    }
			alert(msg);
			return false;
	    }
		return true;
	}
	
	function updateTotalAmount(el){
		var element = el.name;
		var tot = parseFloat($('totalamount').get('value'));
		var fee = 0;
		
		if(element == 'is_featured')
			fee = parseFloat('<?php echo $featuredProjectFee; ?>');
		else if(element == 'is_urgent')
			fee = parseFloat('<?php echo $urgentProjectFee; ?>');
		else if(element == 'is_private')
			fee = parseFloat('<?php echo $privateProjectFee; ?>');
		else if(element == 'is_sealed')
			fee = parseFloat('<?php echo $sealedProjectFee; ?>');
		else if(element == 'is_nda')
			fee = parseFloat('<?php echo $ndaProjectFee; ?>');

		if($(element).checked){
			tot = parseFloat(tot + fee);
		}
		else {
			tot = parseFloat(tot - fee);
		}
		$('subtotal').set('html', tot);
		$('totalamount').set('value', tot);
	}
//-->
</script>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userFormProject" id="userFormProject" class="form-validate form-horizontal" onsubmit="return validateForm(this);" enctype="multipart/form-data">
	<div class="jbl_h3title"><?php echo $title; ?></div>
	<?php 
	$lastSubscr = $finance->getLastSubscription($user->id);
	if($lastSubscr->projects_allowed > 0) :
	?>
	<div class="bid_project_left" style="float:right">
	    <div><span class="font26"><?php echo $lastSubscr->projects_left; ?></span>/<span><?php echo $lastSubscr->projects_allowed; ?></span></div>
	    <div><?php echo JText::_('COM_JBLANCE_PROJECTS_LEFT'); ?></div>
	</div>
	<?php endif; ?>
	<fieldset>
		<legend><?php echo JText::_('COM_JBLANCE_YOUR_PROJECT_DETAILS'); ?></legend>
		<div class="control-group">
			<label class="control-label" for="project_title"><?php echo JText::_('COM_JBLANCE_PROJECT_TITLE'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<input type="text" class="input-xlarge required" name="project_title" id="project_title" value="<?php echo $this->row->project_title;?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="id_category"><?php echo JText::_('COM_JBLANCE_PROJECT_CATEGORIES'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<?php 
				$attribs = 'class="inputbox required" size="20" multiple ';
				$defaultCategory = empty($this->row->id_category) ? 0 : explode(',', $this->row->id_category);
				$categtree = $select->getSelectCategoryTree('id_category[]', $defaultCategory, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '', true);
				echo $categtree; ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="start_date"><?php echo JText::_('COM_JBLANCE_START_DATE'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				 <?php 
				 $now = JFactory::getDate()->toSql();
				 $startdate = (empty($this->row->start_date)) ? $now : $this->row->start_date;
				 echo JHTML::_('calendar', $startdate, 'start_date', 'start_date', '%Y-%m-%d', array('class'=>'input-small required', 'size'=>'20',  'maxlength'=>'32'));
				 ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="expires"><?php echo JText::_('COM_JBLANCE_EXPIRES'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				 <input type="text" class="input-small required validate-numeric" name="expires" id="expires" value="<?php echo $this->row->expires; ?>" />&nbsp;<?php echo JText::_('COM_JBLANCE_DAYS'); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="budgetrange"><?php echo JText::_('COM_JBLANCE_BUDGET'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<?php 
				$attribs = 'class="input-xlarge required"';
				$default = $this->row->budgetmin.'-'.$this->row->budgetmax;
				echo $select->getSelectBudgetRange('budgetrange', $default, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '');
				?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="description"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?> <span class="redfont">*</span>:</label>
			<div class="controls">
				<?php echo $editor->display('description', $this->row->description, '100%', '400', '50', '10', false); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('COM_JBLANCE_ATTACHMENT'); ?> :</label>
			<div class="controls">
				<?php
				for($i=0; $i < $fileLimitConf; $i++){
				?>
				<input name="uploadFile<?php echo $i;?>" type="file" id="uploadFile<?php echo $i;?>" /><br>
				<?php 
				} ?>
				<input name="uploadLimit" type="hidden" value="<?php echo $fileLimitConf;?>" />
				<?php 
				$tipmsg = JText::_('COM_JBLANCE_ATTACH_FILE').'::'.JText::_('COM_JBLANCE_ALLOWED_FILE_TYPES').' : '.$config->projectFileText.'<br>'.JText::_('COM_JBLANCE_MAXIMUM_FILE_SIZE').' : '.$config->projectMaxsize.' kB';
				?>
				<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
				<div class="lineseparator"></div>
				<?php 
				foreach($this->projfiles as $projfile){ ?>
				<label class="checkbox">
					<input type="checkbox" name=file-id[] value="<?php echo $projfile->id; ?>" />
  					<?php echo LinkHelper::getDownloadLink('project', $projfile->id, 'project.download'); ?>
				</label>
				<?php	
				}
				?>
			</div>
		</div>
	</fieldset>
	
	<?php 
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
				<div class="controls">
					<?php $fields->getFieldHTML($ct, $this->row->id, 'project'); ?>
				</div>
			</div>
			<?php
				}
			} ?>
	</fieldset>
			<?php
		}
	}
	?>
	
	<fieldset>
		<legend><?php echo JText::_('COM_JBLANCE_SEO_OPTIMIZATION'); ?></legend>
		<div class="control-group">
			<label class="control-label" for="metadesc"><?php echo JText::_('COM_JBLANCE_META_DESCRIPTION'); ?>:</label>
			<div class="controls">
				<textarea name="metadesc" id="metadesc" rows="3" class="input-xlarge"><?php echo $this->row->metadesc; ?></textarea>
				<?php 
				$tipmsg = JText::_('COM_JBLANCE_META_DESCRIPTION').'::'.JText::_('COM_JBLANCE_META_DESCRIPTION_TIPS');
				?>
				<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="metakey"><?php echo JText::_('COM_JBLANCE_META_KEYWORDS'); ?>:</label>
			<div class="controls">
				<textarea name="metakey" id="metakey" rows="3" class="input-xlarge"><?php echo $this->row->metakey; ?></textarea>
				<?php 
				$tipmsg = JText::_('COM_JBLANCE_META_KEYWORDS').'::'.JText::_('COM_JBLANCE_META_KEYWORDS_TIPS');
				?>
				<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend><?php echo JText::_('COM_JBLANCE_PROMOTE_YOUR_LISTING'); ?></legend>
		<ul class="upgrades">
			<!-- The project once set as 'Featured' should not be able to change again -->
			<li class="project_upgrades">
				<div class="pad">
					<?php if(!$this->row->is_featured) : ?>
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" class="project_upgrades" onclick="updateTotalAmount(this);" /> 
                    <span class="upgrade featured"></span> 
                    <p><?php echo JText::_('COM_JBLANCE_FEATURED_PROJECT_DESC'); ?></p>
					<span class="price"><?php echo JblanceHelper::formatCurrency($featuredProjectFee); ?></span>
					
					<?php else : ?>
					<span class="upgrade featured"></span>
					<p><?php echo JText::_('COM_JBLANCE_THIS_IS_A_FEATURED_PROJECT'); ?></p>
					<?php endif; ?>
					<div class="clearfix"></div>
				</div>
			</li>
			<!-- The project once set as 'Urgent' should not be able to change again -->
			<li class="project_upgrades">
				<div class="pad">
					<?php if(!$this->row->is_urgent) : ?>
                    <input type="checkbox" id="is_urgent" name="is_urgent" value="1" class="project_upgrades" onclick="updateTotalAmount(this);" /> 
                    <span class="upgrade urgent"></span> 
                    <p><?php echo JText::_('COM_JBLANCE_URGENT_PROJECT_DESC'); ?></p>
					<span class="price"><?php echo JblanceHelper::formatCurrency($urgentProjectFee); ?></span>
					<?php else : ?>
					<span class="upgrade urgent"></span>
					<p><?php echo JText::_('COM_JBLANCE_THIS_IS_AN_URGENT_PROJECT'); ?></p>
					<?php endif; ?>
					<div class="clearfix"></div>
				</div>
			</li>
			<!-- The project once set as 'Private' should not be able to change again -->
			<li class="project_upgrades">
				<div class="pad">
					<?php if(!$this->row->is_private) : ?>
					<input type="checkbox" id="is_private" name="is_private" value="1" class="project_upgrades" onclick="updateTotalAmount(this);" />
                    <span class="upgrade private"></span> 
                    <p><?php echo JText::_('COM_JBLANCE_PRIVATE_PROJECT_DESC'); ?></p>
					<span class="price"><?php echo JblanceHelper::formatCurrency($privateProjectFee); ?></span>
					<?php else : ?>
					<span class="upgrade private"></span>
					<p><?php echo JText::_('COM_JBLANCE_THIS_IS_A_PRIVATE_PROJECT'); ?></p>
					<?php endif; ?>
					<div class="clearfix"></div>
				</div>
			</li>
			<!-- The project once set as 'Sealed' should not be able to change again -->
			<li class="project_upgrades">
				<div class="pad">
					<?php if(!$this->row->is_sealed) : ?>
					<input type="checkbox" id="is_sealed" name="is_sealed" value="1" class="project_upgrades" onclick="updateTotalAmount(this);" />
                    <span class="upgrade sealed"></span> 
                    <p><?php echo JText::_('COM_JBLANCE_SEALED_PROJECT_DESC'); ?></p>
					<span class="price"><?php echo JblanceHelper::formatCurrency($sealedProjectFee); ?></span>
					<?php else : ?>
					<span class="upgrade sealed"></span>
					<p><?php echo JText::_('COM_JBLANCE_THIS_IS_A_SEALED_PROJECT'); ?></p>
					<?php endif; ?>
					<div class="clearfix"></div>
				</div>
			</li>
			<!-- The project once set as 'NDA' should not be able to change again -->
			<li class="project_upgrades">
				<div class="pad">
					<?php if(!$this->row->is_nda) : ?>
					<input type="checkbox" id="is_nda" name="is_nda" value="1" class="project_upgrades" onclick="updateTotalAmount(this);" />
                    <span class="upgrade nda"></span> 
                    <p><?php echo JText::sprintf('COM_JBLANCE_NDA_PROJECT_DESC', $ndaFile); ?></p>
					<span class="price"><?php echo JblanceHelper::formatCurrency($ndaProjectFee); ?></span>
					<?php else : ?>
					<span class="upgrade nda"></span>
					<p><?php echo JText::_('COM_JBLANCE_THIS_IS_A_NDA_PROJECT'); ?></p>
					<?php endif; ?>
					<div class="clearfix"></div>
				</div>
			</li>
			<li class="project_upgrades">
				<div class="pad">
					<div class="row-fluid">
						<div class="span4">
							<?php echo JText::_('COM_JBLANCE_CURRENT_BALANCE') ?> : <span class="font16 boldfont"><?php echo JblanceHelper::formatCurrency($totalFund); ?></span>
						</div>
						<div class="span4">
							<?php if($chargePerProject > 0 && $isNew) : ?>
							<?php echo JText::_('COM_JBLANCE_CHARGE_PER_PROJECT'); ?> : <span class="font16 boldfont"><?php echo JblanceHelper::formatCurrency($chargePerProject); ?></span>
							<?php endif; ?>
						</div>
						<div class="span4">
							<?php echo JText::_('COM_JBLANCE_TOTAL')?> : <span class="font16 boldfont"><?php echo $currencysym; ?><span id="subtotal">0.00</span></span>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</li>
		</ul>
	</fieldset>
	<div class="font14 boldfont">
	<?php 
	if($reviewProjects && !$this->row->approved){ ?>
		<div class="jbbox-info"><?php echo JText::_('COM_JBLANCE_PROJECT_WILL_BE_REVIEWED_BY_ADMIN_BEFORE_LIVE'); ?></div>
	<?php 
	}
	?>
	</div>
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SAVE_PROJECT'); ?>" class="btn btn-primary"/> 
		<input type="button" value="<?php echo JText::_('COM_JBLANCE_CANCEL'); ?>" onclick="javascript:history.back();" class="btn" />
	</div>
	
	<input type="hidden" name="option" value="com_jblance" /> 
	<input type="hidden" name="task" value="project.saveproject" /> 
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="totalamount" id="totalamount" value="0.00" />
	<?php echo JHTML::_('form.token'); ?>
</form>