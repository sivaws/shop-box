<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 March 2012
 * @file name	:	views/admconfig/tmpl/editcustomfield.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Edit Custom Field (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHTML::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 
 $app    = JFactory::getApplication();
 $type   = $app->input->get('type', '', 'string');
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
?>
<script language="javascript" type="text/javascript">
<!--
	Joomla.submitbutton = function(task){
		if (task == 'admconfig.cancelcustomfield' || document.formvalidator.isValid(document.id('editcustomfield-form'))) {
			Joomla.submitform(task, document.getElementById('editcustomfield-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'));?>');
		}
	}

	window.addEvent('domready', function(){
		val = '<?php echo $this->row->value_type; ?>';
		type = '<?php echo $this->row->field_type; ?>';
		chooseVal(val);
		changeType(type);
	});
	
	var chooseVal = function(val){
		if(val == 'database'){
			document.getElementById('con_customValue').style.display = 'none';
			document.getElementById('con_databaseValue').style.display = '';
		}
		else {
			document.getElementById('con_customValue').style.display = '';
			document.getElementById('con_databaseValue').style.display = 'none';
		}
	}

	var changeType = function(type){
		if(type == 'Radio' || type == 'Checkbox' || type == 'Select' || type == 'Multiple Select'){
			$('radiocheckselect').setStyle('display', '');
			//show the searchPage and value type 'Custom/Database' only to 'Select'
			if(type == 'Select'){
				$('con_value_type').setStyle('display', '');
			}
			else {
				$('con_value_type').setStyle('display', 'none');
				chooseVal('custom');
			}
		}
		else {
			$('radiocheckselect').setStyle('display', 'none');
		}
		//show the search option only for the fg. fields
		if(type == 'Radio' || type == 'Checkbox' || type == 'Select' || type == 'Multiple Select' || type == 'Location')
			$('con_searchPage').setStyle('display', '');
		else
			$('con_searchPage').setStyle('display', 'none');
		
	}
//-->
</script>
<form action="index.php" method="post" id="editcustomfield-form" name="adminForm" class="form-validate">
<?php if($type != 'group'): ?><!-- Input fields to be shown while creating new "Field" -->

	<div class="col width-60">
		<fieldset class="adminform">
		<legend><?php echo JText::_('COM_JBLANCE_FIELD_PROPERTIES'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label><?php echo JText::_('COM_JBLANCE_FIELD_FOR'); ?>:</label>
					</td>
					<td>
						<?php echo $this->lists['field_type'];?>
					</td>
				</tr>
				<tr>
					<td class="key"><label for="field_title"><?php echo JText::_('COM_JBLANCE_FIELD_TITLE'); ?>:</label>
					</td>
					<td>
					<input class="inputbox required" type="text" name="field_title" id="field_title" size="60" maxlength="255" value="<?php echo $this->row->field_title; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><label><?php echo JText::_('COM_JBLANCE_FIELD_TYPE'); ?>:</label>
					</td>
					<td>
					<?php 
						// it is unadvisable to change the array content
						$types = array('Textbox', 'Textarea', 'Radio', 'Checkbox', 'Select', 'Multiple Select', 'URL', 'Email', 'Date', 'Birthdate');
						foreach($types as $key=>$value){
							$options[] = JHTML::_('select.option', $value, JText::_($value));
						}
						$fields = JHTML::_('select.genericlist', $options, 'field_type', "class='inputbox' size='1' onchange='changeType(this.value);'", 'value', 'text', $this->row->field_type);
						echo $fields;
						?>
					</td>
				</tr>
				<tr>
					<td class="key"><label for="parent"><?php echo JText::_('COM_JBLANCE_SELECT_GROUP'); ?>:</label>
					</td>
					<td>
						<?php echo $this->groups; ?>
					</td>
				</tr>
				<tr>
					<td class="key"><label for="class"><?php echo JText::_('COM_JBLANCE_ADDITIONAL_CSS_CLASS'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="class" id="class" size="60" maxlength="255" value="<?php echo $this->row->class; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><label for="class"><?php echo JText::_('COM_JBLANCE_TIPS'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="tips" id="tips" size="60" maxlength="255" value="<?php echo $this->row->tips; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><label><?php echo JText::_('JPUBLISHED'); ?>:</label>
					</td>
					<td>
						<input type="checkbox" name="published" value="1" <?php echo ($this->row->published == 1) ? 'checked' : ''; ?> />
					</td>
				</tr>
				<tr>
					<td class="key"><label><?php echo JText::_('COM_JBLANCE_REQUIRED'); ?>:</label>
					</td>
					<td>
						<input type="checkbox" name="required" value="1" <?php echo ($this->row->required == 1) ? 'checked' : ''; ?> />
					</td>
				</tr>
				<tr>
					<td class="key"><label><?php echo JText::_('COM_JBLANCE_VISIBLE'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
						<?php 
						$options 	 = array();
						$options[] = JHTML::_('select.option', 'all', JText::_('COM_JBLANCE_ALL'));
						$options[] = JHTML::_('select.option', 'personal', JText::_('COM_JBLANCE_PERSONAL'));
						$lists = JHTML::_('select.radiolist',  $options, 'visible', '', 'value', 'text', $this->row->visible);
						echo $lists;
						?>
						</fieldset>
					</td>
				</tr>
				 <tr id="con_searchPage" style="display:none;">
					<td class="key"><?php echo JText::_('COM_JBLANCE_SEARCH_PAGE'); ?>:</td>
					<td>
						<fieldset class="radio">
						<?php $searchPage = $select->YesNoBool('searchPage', $this->row->searchPage);
					    echo  $searchPage; ?>
						</fieldset>
					</td>
		        </tr>
			</table>
		</fieldset>
	</div>

	<div class="col width-70" id="radiocheckselect">
		<fieldset class="adminform">
		<legend><?php echo JText::_('COM_JBLANCE_FOR_RADIO_CHECKBOX_SELECT_MULTIPLE'); ?></legend>
			<table class="admintable" width="100%">
				<tr id="con_value_type">
					<td class="key" width="25%"><label><?php echo JText::_('COM_JBLANCE_VALUE_TYPE'); ?>:</label>
					</td>
					<td width="75%">
						<fieldset class="radio">
						<?php
						$put = array();
						$this->row->value_type = (!empty($this->row->value_type)) ? $this->row->value_type : 'custom';
						$put[] = JHTML::_('select.option', 'custom', JText::_('COM_JBLANCE_CUSTOM'));
						//$put[] = JHTML::_('select.option', 'database', JText::_('COM_JBLANCE_DATABASE'));
						echo JHTML::_('select.radiolist', $put, 'value_type', "onchange='chooseVal(this.value);'", 'value', 'text', $this->row->value_type);
						?>
						</fieldset>
					</td>
				</tr>
				<tr id="con_customValue">
					<td class="key"><label for="customValues"><?php echo JText::_('COM_JBLANCE_VALUES'); ?>:</label>
					</td>
					<td>
						<textarea rows="4" cols="50" name="customValues" id="customValues"><?php echo $this->row->value; ?></textarea>
				 		<div style="clear:both; width: 90%;"><?php echo JText::_('COM_JBLANCE_SEPARATED_SEMI_COLUMN'); ?></span>
					</td>
				</tr>
				<!-- <tr id="con_databaseValue">
					<td class="key"><label for="databaseValue"><?php echo JText::_('COM_JBLANCE_VALUES'); ?>:</label>
					</td>
					<td>
						<?php 
						$select = JblanceHelper::get('helper.select');
						//echo $select->getSelectDatabaseTables('databaseValues', $this->row->value); ?>
					</td>
				</tr> -->
				<tr>
					<td class="key"><label><?php echo JText::_('COM_JBLANCE_SHOW_TYPE'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
						<?php
						$put = array();
						$this->row->show_type = (!empty($this->row->show_type)) ? $this->row->show_type : 'left-to-right';
						$put[] = JHTML::_('select.option', 'left-to-right', JText::_('COM_JBLANCE_LEFT_RIGHT'));
						$put[] = JHTML::_('select.option', 'top-to-bottom', JText::_('COM_JBLANCE_TOP_BOTTOM'));
						echo JHTML::_('select.radiolist', $put, 'show_type', '', 'value', 'text', $this->row->show_type);
						?>
						</fieldset>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>

	<?php else: ?><!-- Input fields to be shown while creating new "Group" -->
	<div class="col width-60">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_GROUP_PROPERTIES'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label><?php echo JText::_('COM_JBLANCE_GROUP_FOR'); ?>:</label>
					</td>
					<td>
						<?php echo $this->lists['field_type'];?>
					</td>
				</tr>
				<tr>
					<td class="key"><label for="field_title"><?php echo JText::_('COM_JBLANCE_GROUP_TITLE'); ?>:</label>
					</td>
					<td>
					<input class="inputbox required" type="text" name="field_title" id="field_title" size="60" maxlength="255" value="<?php echo $this->row->field_title; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><label for="gdesc"><?php echo JText::_('COM_JBLANCE_GROUP_DESCRIPTION'); ?>:</label>
					</td>
					<td>
						<textarea id="gdesc" name="gdesc" rows="6" cols="50"><?php echo $this->row->gdesc; ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="key"><label><?php echo JText::_('JPUBLISHED'); ?>:</label>
					</td>
					<td>
						<input type="checkbox" name="published" value="1" <?php  echo ($this->row->published == 1) ? 'checked' : '';?> >
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<?php endif; ?>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="view" value="admconfig" />
	<input type="hidden" name="layout" value="editcustomfield" />
	<input type="hidden" name="task" value="savefield" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="type" value="<?php echo $type; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
