<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	13 March 2012
 * @file name	:	views/admproject/tmpl/edituser.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Users (jblance)
 */
  defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.tooltip', '.hasTip', '');
 jimport('joomla.html.pane');

 $app  	 = JFactory::getApplication();
 $user	 = JFactory::getUser();
 $model  = $this->getModel();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 $cid 	 = $app->input->get('cid', array(), 'array');
 
 $doc = JFactory::getDocument();
 $doc->addScript(JURI::root()."components/com_jblance/js/utility.js");
 $doc->addScript(JURI::root()."components/com_jblance/js/upclick-min.js");
 $doc->addScript(JURI::root()."components/com_jblance/js/ysr-crop.js"); 
 $doc->addStyleSheet(JURI::root().'components/com_jblance/css/style.css');
 
 JblanceHelper::getMultiSelect('id_category', JText::_('COM_JBLANCE_SEARCH_SKILLS'));
 
 $config = JblanceHelper::getConfig();
 $dformat = $config->dateFormat;
 $currencysym = $config->currencySymbol;
 $currencycod = $config->currencyCode;
 
 $hasJBProfile = JblanceHelper::hasJBProfile($cid[0]);	//check if the user has JoomBri profile
 
 if($hasJBProfile){
	 $jbuser = JblanceHelper::get('helper.user');		// create an instance of the class userHelper
	 $userInfo = $jbuser->getUserGroupInfo($cid[0], null);
 }
 
 JText::script('COM_JBLANCE_CLOSE');
 $tableClass = JblanceHelper::getTableClassName();
?>
<script language="javascript" type="text/javascript">
<!--
	window.addEvent('domready', function(){
		createUploadButton('<?php echo $this->row->id; ?>', 'admproject.uploadpicture');
	});
	
	Joomla.submitbutton = function(task){
		if (task == 'admproject.canceluser' || document.formvalidator.isValid(document.id('edituser-form'))) {
			Joomla.submitform(task, document.getElementById('edituser-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'));?>');
		}
	}
//-->
</script>
<form action="index.php" method="post" name="adminForm" id="edituser-form" class="form-validate">
	<?php echo JHtml::_('tabs.start', 'fund-slider'); ?>
	
	<?php echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_GENERAL'), 'general');?>
	<div class="col width-80">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_USER_INFORMATION'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="username"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?><span class="redfont">*</span>:</label>
					</td>
					<td >
						<?php echo $this->lists;?>
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_NAME'); ?><span class="redfont">*</span>:</label>
					</td>
					<td>
						<input class="inputbox required" type="text" name="name" id="name" size="50" maxlength="100" value="<?php echo $this->row->name; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><label for="ug_id"><?php echo JText::_('COM_JBLANCE_USER_GROUP'); ?><span class="redfont">*</span>:</label>
					</td>
					<td >
						<?php echo $this->grpLists;
						if($hasJBProfile){
							echo JHTML::tooltip(JText::_('COM_JBLANCE_CHANGE_USERGROUP_WARNING'), JText::_('COM_JBLANCE_USER_GROUP'), 'tooltip.png', '', '', false);
						}
						?>
					</td>
				</tr>
				<!-- Company Name should be visible only to users who can post job and has JoomBri profile -->
				<?php if($hasJBProfile && $userInfo->allowPostProjects) : ?>
				<tr>
					<td class="key"><label for="biz_name"><?php echo JText::_('COM_JBLANCE_BUSINESS_NAME'); ?><span class="redfont">*</span>:</label>
					</td>
					<td>
						<input class="inputbox required" type="text" name="biz_name" id="biz_name" size="50" maxlength="100" value="<?php echo $this->row->biz_name; ?>" />
					</td>
				</tr>
				<?php endif; ?>
				<!-- Skills and hourly rate should be visible only to users who can work/bid -->
			<?php if($hasJBProfile && $userInfo->allowBidProjects) : ?>
			<tr>
				<td class="key"><label for="rate"><?php echo JText::_('COM_JBLANCE_HOURLY_RATE'); ?><span class="redfont">*</span>:</label>
				</td>
				<td>
					<?php echo $currencysym; ?> 
					<input class="inputbox required" type="text" name="rate" id="rate" size="6" maxlength="10" value="<?php echo $this->row->rate; ?>" />
					<?php echo $currencycod.' / '.JText::_('COM_JBLANCE_HOUR'); ?>
				</td>
			</tr>
			<tr>
				<td class="key"><label for="id_category"><?php echo JText::_('COM_JBLANCE_SKILLS'); ?>:</label>
				</td>
				<td >						
					<?php 
					$attribs = 'class="inputbox required" size="20" multiple ';
					$categtree = $select->getSelectCategoryTree('id_category[]', explode(',', $this->row->id_category), 'COM_JBLANCE_PLEASE_SELECT', $attribs, '', true);
					echo $categtree; ?>
				</td>
			</tr>
			<?php endif; ?>
			</table>
		</fieldset>
	</div>
	<div class="col width-80">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_PROFILE_PICTURE'); ?></legend>
			<table class="admintable">
				<tr>
					<td>
						<div id="divpicture"><?php echo JblanceHelper::getLogo($this->row->id); ?></div><br>
						<?php echo JText::_('COM_JBLANCE_PROFILE_PICTURE'); ?>
						<div class="sp10">&nbsp;</div>
						<div id="ajax-container"></div>
						<input type="button" id="photoupload" value="<?php echo JText::_('COM_JBLANCE_UPLOAD_NEW'); ?>" class="button">
						<input type="button" id="removepicture" value="<?php echo JText::_('COM_JBLANCE_REMOVE_PICTURE'); ?>" onclick="removePicture('<?php echo $this->row->user_id; ?>', 'admproject.removepicture');" class="button" >
					</td>
					<td>
						<div id="divthumb"><?php echo JblanceHelper::getThumbnail($this->row->id); ?></div><br>
						<?php echo JText::_('COM_JBLANCE_THUMBNAIL'); ?>
						<p>
							<a href="javascript:updateThumbnail('admproject.croppicture')" id="update-thumbnail"><?php echo JText::_('COM_JBLANCE_EDIT_THUMBNAIL'); ?></a>
						</p>
					</td>
					<td>
						<!-- show the edit thumbnail if the user has attached any picture -->
						<?php if($this->row->picture) : ?>
						<div id="editthumb" style="display:none; ">
							<?php 
							//get image size
							$imgLoc = JBPROFILE_PIC_PATH.'/'.$this->row->picture;
							$fileAtr = getimagesize($imgLoc);
							$width = $fileAtr[0];
							$height = $fileAtr[1];
							?>
							<div id="imgouter">
							    <div id="cropframe" style="background-image: url('<?php echo JBPROFILE_PIC_URL.$this->row->picture; ?>')">
							        <div id="draghandle"></div>
							        <div id="resizeHandleXY" class="resizeHandle"></div>
							        <div id="cropinfo" rel="Click to crop">
							            <div title="Click to crop" id="cropbtn"></div>
							            <!--<div id="cropdims"></div>-->
							        </div>
							    </div>
							    <div id="imglayer" style="width: <?=$width; ?>px; height: <?=$height ?>px; background-image: url('<?php echo JBPROFILE_PIC_URL.$this->row->picture?>')">
							    </div>
							</div>
							<div id="tmb-container"></div>
							<input type="hidden" id="imgname" name="imgname" value="<?php echo $this->row->picture; ?>">
							<input type="hidden" id="tmbname" name="tmbname" value="<?php echo $this->row->thumb; ?>">
						</div>
						<?php endif; ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	
	<?php echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_PROFILE'), 'profile');?>
	<?php 
		$fields = JblanceHelper::get('helper.fields');		// create an instance of the class FieldsHelper
		
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
	<div class="col width-80">
		<fieldset class="adminform"><legend><?php echo JText::_($pt->field_title); ?></legend>
			<table class="admintable" width="100%">
			<?php 
			foreach($children as $ct){
				if($ct->parent == $pt->id){ ?>
					<tr>
						<td><?php 
							$labelsuffix = '';
							if($ct->field_type == 'Checkbox') $labelsuffix = '[]'; //added to validate checkbox
							?>
							<label for="custom_field_<?php echo $ct->id.$labelsuffix; ?>"><?php echo JText::_($ct->field_title); ?><span class="redfont"><?php echo ($ct->required)? '*' : ''; ?></span>:</label>
						</td>
						<td>
							<?php $fields->getFieldHTML($ct, $cid[0]); ?>
						</td>
					</tr>
				<?php 
				}
			} ?>
			</table>
		</fieldset>
	</div>
			<?php
			}
		}
		
	?>
	
	<?php echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_TRANSACTIONS_HISTORY'), 'trans');?>
	<table width="100%">
		<tr>
			<td valign="top" width="40%">
				<div class="col width-100">
					<fieldset class="adminform">
						<legend><?php echo JText::_('COM_JBLANCE_ADD_DEDUCT_FUND'); ?></legend>
						<table class="admintable">
						<tr>
							<td class="key"><label><?php echo JText::_('COM_JBLANCE_TOTAL_AVAILABLE_BALANCE'); ?>:</label>
							</td>
							<td>
								<?php
								$totalFund = JblanceHelper::getTotalFund($this->row->user_id);
								echo JblanceHelper::formatCurrency($totalFund); ?>
							</td>
						</tr>
						<tr>
							<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_FUNDS'); ?>:</label>
							</td>
							<td >
							<input class="inputbox" type="text" name="fund" id="fund" size="30" maxlength="255" value="0" />
							</td>
						</tr>
						<tr>
							<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_TYPE'); ?>:</label>
							</td>
							<td >
							<select name="type_fund">
								<option value="p"><?php echo JText::_('COM_JBLANCE_ADD'); ?></option>
								<option value="m"><?php echo JText::_('COM_JBLANCE_DEDUCT'); ?></option>
							</select>
							</td>
						</tr>
						<tr>
							<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?>:</label>
							</td>
							<td >
								<input class="inputbox" type="text" name="desc_fund" id="desc_fund" size="60" maxlength="255" />					
							</td>
						</tr>
					    </table>
					</fieldset>
				</div>
			</td>
			<td valign="top" width="60%">
				<div style="max-height: 800px; overflow: auto;">
					<table class="<?php echo $tableClass; ?>">
						<thead>
							<tr class="jbj_rowhead">
								<th>
									<?php echo '#'; ?>
								</th>
								<th width="15%" align="left">
									<?php echo JText::_('COM_JBLANCE_DATE'); ?>
								</th>
								<th width="50%" align="left">
									<?php echo JText::_('COM_JBLANCE_TRANSACTION'); ?>
								</th>
								<th width="15%" align="left">
									<?php echo JText::_('COM_JBLANCE_FUND_IN'); ?>
								</th>
								<th width="15%" align="left">
									<?php echo JText::_('COM_JBLANCE_FUND_OUT'); ?>
								</th>				
							</tr>
						</thead>
						<tbody>
						<?php
						$k = 0;
						for ($i=0, $n=count($this->trans); $i < $n; $i++) {
							$tran = $this->trans[$i];
							?>
							<tr class="<?php echo "row$k"; ?>">
								<td>
									<?php echo $i+1; ?>
								</td>
								<td>
									<?php  echo JHTML::_('date', $tran->date_trans, $dformat); ?>				
								</td>
								<td>
									<?php echo $tran->transaction; ?>
								</td>
								<td align="right">
									<?php echo $tran->fund_plus > 0  ? $tran->fund_plus : " "; ?> 
								</td>
								<td align="right">
									<?php echo $tran->fund_minus > 0  ? $tran->fund_minus : " "; ?> 
								</td>				
							</tr>
							<?php
							$k = 1 - $k;
						}
						?>
						</tbody>
					</table>
				</div>
			</td>
		</tr>
	</table>
	<?php echo JHtml::_('tabs.end'); ?>
	
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="view" value="admproject" />
	<input type="hidden" name="layout" value="edituser" />
	<input type="hidden" name="task" value="">
	<input type="hidden" name="id" value="<?php echo $this->row->jb_id; ?>" />
	<input type="hidden" name="user_id" value="<?php echo $cid[0]; ?>" />
	<input type="hidden" name="cid" value="<?php echo $cid[0]; ?>">
	<?php echo JHTML::_('form.token'); ?>
</form>	
