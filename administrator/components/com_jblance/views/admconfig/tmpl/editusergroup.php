<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	14 March 2012
 * @file name	:	views/admconfig/tmpl/editusergroup.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Edit User Group (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.tooltip');
 
 $model = $this->getModel();
 $editor = JFactory::getEditor();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 $tableClass = JblanceHelper::getTableClassName();
 
 $user = JFactory::getUser();
 $isSuperAdmin = false;
 if(isset($user->groups[8]))
 	$isSuperAdmin = true;
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task){
		if (task == 'admconfig.cancelusergroup' || document.formvalidator.isValid(document.id('editusergroup-form'))) {
			Joomla.submitform(task, document.getElementById('editusergroup-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'));?>');
		}
	}
</script>
<form name="adminForm" id="editusergroup-form" action="index.php" method="POST" enctype="multipart/form-data" class="form-validate">
	<div class="width-70 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_GROUP_DETAILS');?></legend>
			<p><?php //echo JText::_('COM_JBLANCE_GROUP_DETAILS_INFO');?></p>
			<table class="admintable">
				<tbody>
					<tr>
						<td class="key">
							<span><?php echo JText::_('COM_JBLANCE_GROUP_TITLE'); ?></span>
						</td>
						<td valign="top">
							<input type="text" maxlength="50" size="50" id="name" name="name" class="inputbox required" value="<?php echo $this->row->name; ?>">
						</td>
					</tr>
					<tr>
						<td class="key"><label class="hasTip" for="approval" title="<?php echo JText::_('COM_JBLANCE_REQUIRE_APPROVAL_TIPS'); ?>"><?php echo JText::_('COM_JBLANCE_REQUIRE_APPROVAL'); ?>:</label></td>
						<td><fieldset class="radio"><?php $approval = $select->YesNoBool('approval', $this->row->approval);
						    echo  $approval; ?>
						</fieldset></td>
			        </tr>
					<tr>
						<td class="key"><label class="hasTip" for="skipPlan" title="<?php echo JText::_('COM_JBLANCE_SKIP_PLAN_TIPS'); ?>"><?php echo JText::_('COM_JBLANCE_SKIP_PLAN'); ?>?:</label></td>
						<td><fieldset class="radio"><?php $skipPlan = $select->YesNoBool('skipPlan', $this->row->skipPlan);
						    echo  $skipPlan; ?>
						</fieldset></td>
			        </tr>
					<tr>
						<td class="key"><label class="hasTip" for="joomla_ug_id" title="<?php echo JText::_('COM_JBLANCE_JOOMLA_USER_GROUP_TIPS'); ?>"><?php echo JText::_('COM_JBLANCE_JOOMLA_USER_GROUP'); ?>:</label></td>
						<td>
						<?php 
						if($isSuperAdmin) : 
							echo JHtml::_('access.usergroups', 'joomla_ug_id', explode(',', $this->row->joomla_ug_id), true);
						else :
							echo $model->getJoomlaUserGroupTitles($this->row->joomla_ug_id);
						endif; ?>	
						</td>
			        </tr>
					<tr style="display:none;">
						<td class="key"><?php echo JText::_('COM_JBLANCE_FREE_MODE'); ?>:</td>
						<td><fieldset class="radio"><?php $freeMode = $select->YesNoBool('freeMode', $this->row->freeMode);
						    echo  $freeMode; ?>
						</fieldset></td>
			        </tr>
			        <tr valign="top">
	    				<td class="key"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?>:</td>
	    				<td>
	    					<?php echo $editor->display('description', $this->row->description, '550', '300', '20', '25');?>
	    				</td>
	    			</tr>
					</tbody>
				</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_PERMISSION_DASHBOARD_SETTINGS'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label class="hasTip" for="params[allowBidProjects]" title="<?php echo JText::_('COM_JBLANCE_BID_PROJECTS_TIPS'); ?>"><?php echo JText::_('COM_JBLANCE_BID_PROJECTS'); ?>:</label></td>
					<td><fieldset class="radio">
						<?php 
						$val = isset($this->params['allowBidProjects']) ? $this->params['allowBidProjects'] : 0;
						$allowBidProjects = $select->YesNoBool('params[allowBidProjects]', $val);
						echo  $allowBidProjects; ?></fieldset>
					</td>
				</tr>
				<tr>
					<td class="key"><label class="hasTip" for="params[allowPostProjects]" title="<?php echo JText::_('COM_JBLANCE_POST_PROJECTS_TIPS'); ?>"><?php echo JText::_('COM_JBLANCE_POST_PROJECTS'); ?>:</label></td>
					<td><fieldset class="radio">
						<?php 
						$val = isset($this->params['allowPostProjects']) ? $this->params['allowPostProjects'] : 0;
						$allowPostProjects = $select->YesNoBool('params[allowPostProjects]', $val);
						echo  $allowPostProjects; ?></fieldset>
					</td>
				</tr>
				<tr>
					<td class="key"><label class="hasTip" for="params[searchUserGroupIds]" title="<?php echo JText::_('COM_JBLANCE_SEARCH_USER_GROUPS_TIPS'); ?>"><?php echo JText::_('COM_JBLANCE_SEARCH_USER_GROUPS'); ?>:</label></td>
					<td>
						<?php 
						$val = isset($this->params['searchUserGroupIds']) ? $this->params['searchUserGroupIds'] : 0; ?>
						<input class="inputbox" type="text" name="params[searchUserGroupIds]" id="searchUserGroupIds" size="10" maxlength="10" value="<?php echo $val; ?>" />
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="width-50 fltlft">
		<fieldset>
			<legend><?php echo JText::_('COM_JBLANCE_ASSIGN_FIELDS');?></legend>
			<p><?php echo JText::_('COM_JBLANCE_ASSIGN_FIELDS_FOR_USERGROUP_INFO'); ?></p>
			<table class="<?php echo $tableClass; ?>">
				<thead>
					<tr class="title">
						<th width="1%">#</th>
						<th style="text-align: left;">
							<?php echo JText::_('COM_JBLANCE_NAME');?>
						</th>
						<!--<th width="15%" style="text-align: center;">
							<?php echo JText::_('COM_JBLANCE_FIELD_CODE');?>
						</th>
						--><th width="15%" style="text-align: center;">
							<?php echo JText::_('COM_JBLANCE_FIELD_TYPE');?>
						</th>
						<th width="1%" align="center">
							<?php echo JText::_('COM_JBLANCE_INCLUDE');?>
						</th>
					</tr>
				</thead>
				<?php
				$count	= 0;
				$i		= 0;
	
				foreach($this->fields as $field){
					if($field->field_type == 'group'){ ?>
				<tr class="parent">
					<td  style="background-color: #EEEEEE;">&nbsp;</td>
					<td colspan="4" style="background-color: #EEEEEE;">
						<strong><?php echo JText::_('COM_JBLANCE_GROUPS');?>:
							<span><?php echo $field->field_title;?></span>
						</strong>
						<div style="clear: both;"></div>
						<input type="hidden" name="parents[]" value="<?php echo $field->id;?>" />
					</td>
				</tr>
					<?php
						$i	= 0;	// Reset count
					}
					else if($field->field_type != 'group'){
						// Process publish / unpublish images
						++$i;
					?>
				<tr class="row<?php echo $i%2;?>" id="rowid<?php echo $field->id;?>">
					<td><?php echo $i;?></td>
					<td><span><?php echo $field->field_title;?></span></td>
					<!--<td align="center"><?php //echo $field->fieldcode; ?></td>
					--><td align="center"><?php echo $field->field_type;?></td>
					<td align="center" id="publish<?php echo $field->id;?>">
						<input type="checkbox" name="fields[]" value="<?php echo $field->id;?>"<?php echo $this->row->isChild($field->id) ? ' checked="checked"' : '';?> />
					</td>
				</tr>
			<?php
					}
				$count++;
			}
			?>
			</table>
		</fieldset>
	</div>


<input type="hidden" name="option" value="com_jblance" />
<input type="hidden" name="task" value="saveusergroup" />
<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHTML::_('form.token'); ?>	
</form>