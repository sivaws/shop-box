<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	views/admproject/tmpl/editproject.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Projects (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');

 $doc = JFactory::getDocument();
 $doc->addStyleSheet(JURI::root().'components/com_jblance/css/style.css');
 $doc->addScript(JURI::root()."components/com_jblance/js/utility.js");
 
 JblanceHelper::getMultiSelect('id_category', JText::_('COM_JBLANCE_SEARCH_CATEGORIES'));

 $editor = JFactory::getEditor();
 $model = $this->getModel();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper

 $config = JblanceHelper::getConfig();
 $currencycode = $config->currencyCode;
 $currencysym = $config->currencySymbol;
 $dformat = $config->dateFormat;
 $fileLimitConf = $config->projectMaxfileCount;
 
 JText::script('COM_JBLANCE_CLOSE');
 $tableClass = JblanceHelper::getTableClassName();
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task){
		if (task == 'admproject.cancelproject' || document.formvalidator.isValid(document.id('editproject-form'))) {
			Joomla.submitform(task, document.getElementById('editproject-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'));?>');
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="editproject-form" enctype="multipart/form-data" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_PROJECT_INFORMATION'); ?></legend>
			<table class="admintable" width="100%">
					<tr>
						<td>
							<label for="project_title"><?php echo JText::_('COM_JBLANCE_PROJECT_TITLE'); ?><span class="redfont">*</span>:</label>
						</td>
						<td>						
							<input type="text" class="inputbox required" name="project_title" id="project_title" size="60" value="<?php echo $this->row->project_title;?>">
						</td>
					</tr>
					<tr>
						<td>
							<label for="id_category"><?php echo JText::_('COM_JBLANCE_PROJECT_CATEGORIES'); ?><span class="redfont">*</span>:</label>
						</td>
						<td>						
							<?php 
								$attribs = 'class="inputbox required" size="20" multiple ';
								$defaultCategory = empty($this->row->id_category) ? 0 : explode(',', $this->row->id_category);
								$categtree = $select->getSelectCategoryTree('id_category[]', $defaultCategory, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '', true);
								echo $categtree; ?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="status"><?php echo JText::_('COM_JBLANCE_PROJECT_STATUS'); ?><span class="redfont">*</span>:</label>
						</td>
						<td>	
							<?php 
							$attribs = "class='inputbox required' size='1'";
							$list_status = $select->getSelectProjectStatus('status', $this->row->status, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '');
							 echo $list_status; ?>					
						</td>
					</tr>
					<tr>
						<td class="key">
							<label for="publisher_userid"><?php echo JText::_('COM_JBLANCE_PUBLISHER'); ?><span class="redfont">*</span>:</label>
						</td>
						<td>
							<?php echo $this->lists['userlist'];?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<label for="approved"><?php echo JText::_('COM_JBLANCE_APPROVED'); ?>?:</label>
						</td>
						<td><fieldset class="radio">
							<?php $approved = $select->YesNoBool('approved', $this->row->approved);
							echo  $approved;
							?></fieldset>
						</td>
					</tr>
					<tr>
						<td>
							<label for="expires"><?php echo JText::_('COM_JBLANCE_EXPIRES'); ?><span class="redfont">*</span>:</label>
						</td>
						<td >				
							<input type="text" name="expires" id="expires" class="inputbox required" value="<?php echo $this->row->expires; ?>"><?php echo JText::_('COM_JBLANCE_DAYS'); ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<label for="budgetrange"><?php echo JText::_('COM_JBLANCE_BUDGET'); ?><span class="redfont">*</span>:</label>
						</td>
						<td>
							<?php 
							$attribs = 'class="inputbox required"';
							$default = $this->row->budgetmin.'-'.$this->row->budgetmax;
							echo $select->getSelectBudgetRange('budgetrange', $default, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '');
							?>
						</td>
					</tr>
					<!-- <tr>
						<td>
							<label for="budgetmin"><?php echo JText::_('COM_JBLANCE_MINIMUM_BUDGET'); ?><span class="redfont">*</span>:</label>
						</td>
						<td >
							<?php echo $currencysym; ?>
							<input type="text" name="budgetmin" id="budgetmin" class="inputbox required" value="<?php echo $this->row->budgetmin; ?>">
						</td>
					</tr>
					<tr>
						<td>
							<label for="budgetmax"><?php echo JText::_('COM_JBLANCE_MAXIMUM_BUDGET'); ?><span class="redfont">*</span>:</label>
						</td>
						<td >
							<?php echo $currencysym; ?>
							<input type="text" name="budgetmax" id="budgetmax" class="inputbox required" value="<?php echo $this->row->budgetmax; ?>">
						</td>
					</tr> -->
					<tr>
						<td>
							<label for="start_date"><?php echo JText::_('COM_JBLANCE_START_DATE'); ?><span class="redfont">*</span>:</label>
						</td>
						<td>
							<?php 
							$now = JFactory::getDate()->toSql();
							$startdate = (empty($this->row->start_date)) ? $now : $this->row->start_date;
							echo JHTML::_('calendar', $startdate, 'start_date', 'start_date', '%Y-%m-%d', array('class'=>'inputbox required', 'size'=>'20',  'maxlength'=>'32'));
							?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="description"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?><span class="redfont">*</span>:</label>
						</td>
						<td>
							<?php
							echo $editor->display('description', $this->row->description, '100%', '400', '50', '10') ;
							?>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_JBLANCE_ATTACHMENT'); ?>:</td>
						<td>
						 <?php
						  for($i=0; $i < $fileLimitConf; $i++){ ?>
						  <input name="uploadFile<?php echo $i;?>" type="file" id="uploadFile<?php echo $i;?>" style="float:none;" /><br>
						  <?php } ?>
						  <input name="uploadLimit" type="hidden" value="<?php echo $fileLimitConf;?>" />
						  <div class="lineseparator"></div>
						  <?php 
						  foreach($this->projfiles as $projfile){ ?>
						  <input type="checkbox" name=file-id[] value="<?php echo $projfile->id; ?>" style="float:none;" />
						  <?php echo LinkHelper::getDownloadLink('project', $projfile->id, 'admproject.download'); ?> 
						  <br>
						  <?php	
						  }
						  ?>
						</td>
					</tr>										
			</table>
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
		<fieldset class="adminform">
			<legend><?php echo JText::_($pt->field_title); ?></legend>
			<table class="admintable">
				<?php
				foreach($children as $ct){
					if($ct->parent == $pt->id){ ?>
				<tr>
					<td class="key">
						<?php
						$labelsuffix = '';
						if($ct->field_type == 'Checkbox') $labelsuffix = '[]'; //added to validate checkbox
						?>
						<label for="custom_field_<?php echo $ct->id.$labelsuffix; ?>"><?php echo JText::_($ct->field_title); ?><span class="redfont"><?php echo ($ct->required)? '*' : ''; ?></span>:</label>
					</td>
					<td>
						<?php $fields->getFieldHTML($ct, $this->row->id, 'project'); ?>
					</td>
				</tr>
				<?php
					}
				} ?>
			</table>
		</fieldset>
				<?php
			}
		}
		?>
		
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_SEO_OPTIMIZATION'); ?></legend>
			<table class="admintable">
				<tr>
				<?php 
				$tipmsg = JText::_('COM_JBLANCE_META_DESCRIPTION').'::'.JText::_('COM_JBLANCE_META_DESCRIPTION_TIPS');
				?>
				<td class="key"><label for="metadesc" class="hasTip" title="<?php echo $tipmsg; ?>"><?php echo JText::_('COM_JBLANCE_META_DESCRIPTION'); ?>:</label></td>
				<td>
					<textarea name="metadesc" id="metadesc" rows="3" cols="60" class="inputbox"><?php echo $this->row->metadesc; ?></textarea>
				</td>
			</tr>
			<tr>
				<?php 
				$tipmsg = JText::_('COM_JBLANCE_META_KEYWORDS').'::'.JText::_('COM_JBLANCE_META_KEYWORDS_TIPS');
				?>
				<td class="key"><label for="metakey" class="hasTip" title="<?php echo $tipmsg; ?>"><?php echo JText::_('COM_JBLANCE_META_KEYWORDS'); ?>:</label></td>
				<td>
					<textarea name="metakey" id="metakey" rows="3" cols="60" class="inputbox"><?php echo $this->row->metakey; ?></textarea>
				</td>
			</tr>
			</table>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_PROJECT_UPGRADES'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key">
						<label for="is_featured"><?php echo JText::_('COM_JBLANCE_FEATURED_PROJECT'); ?>:</label>
					</td>
					<td><fieldset class="radio">
						<?php $is_featured = $select->YesNoBool('is_featured', $this->row->is_featured);
						echo  $is_featured;
						?></fieldset>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="is_urgent"><?php echo JText::_('COM_JBLANCE_URGENT_PROJECT'); ?>:</label>
					</td>
					<td><fieldset class="radio">
						<?php $is_urgent = $select->YesNoBool('is_urgent', $this->row->is_urgent);
						echo  $is_urgent;
						?></fieldset>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="is_private"><?php echo JText::_('COM_JBLANCE_PRIVATE_PROJECT'); ?>:</label>
					</td>
					<td><fieldset class="radio">
						<?php $is_private = $select->YesNoBool('is_private', $this->row->is_private);
						echo  $is_private;
						?></fieldset>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="is_sealed"><?php echo JText::_('COM_JBLANCE_SEALED_PROJECT'); ?>:</label>
					</td>
					<td><fieldset class="radio">
						<?php $is_sealed = $select->YesNoBool('is_sealed', $this->row->is_sealed);
						echo  $is_sealed;
						?></fieldset>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="is_nda"><?php echo JText::_('COM_JBLANCE_NDA_PROJECT'); ?>:</label>
					</td>
					<td><fieldset class="radio">
						<?php $is_nda = $select->YesNoBool('is_nda', $this->row->is_nda);
						echo  $is_nda;
						?></fieldset>
					</td>
				</tr>
			</table>
		</fieldset>
	</div> 
	<!-- Bids section -->
	<div class="width-40 fltrt">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_ALL_BIDS').' - '.$this->row->project_title; ?></legend>
			<table class="<?php echo $tableClass; ?>">
			<thead>
				<tr>
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
					for($i=0, $n=count($this->bids); $i < $n; $i++){
						$bid 		 = $this->bids[$i];
						$link_lancer = JRoute::_( 'index.php?option=com_jblance&view=admproject&layout=edituser&cid[]='.$bid->user_id);
				?>
				<tr>
					<td><a href="<?php echo $link_lancer; ?>"> <?php echo $bid->username; ?></a></td>
					<td><?php echo JblanceHelper::formatCurrency($bid->amount, true, false, 0); ?></td>
					<td><?php echo $bid->delivery; ?></td> 
					<td nowrap="nowrap"><?php echo JHTML::_('date', $bid->bid_date, $dformat); ?></td>
					<td>
						<?php
						$rate = JblanceHelper::getAvarageRate($bid->user_id, true);
						?>
					</td>
					<td><?php echo JText::_($bid->status); ?></td>
				</tr>
				<?php } ?>
			</tbody>
			</table>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_PUBLIC_CLARIFICATION_BOARD'); ?></legend>
			<div style="max-height: 600px; overflow: auto;">
				<table class="<?php echo $tableClass; ?>">
				<thead>
					<tr>
						<th><?php echo JText::_('COM_JBLANCE_USERNAME'); ?></th>
						<th><?php echo JText::_('COM_JBLANCE_POSTED_ON'); ?></th>
						<th><?php echo JText::_('COM_JBLANCE_MESSAGE'); ?></th>	
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					for($i=0, $n=count($this->forums); $i < $n; $i++){
						$forum = $this->forums[$i];
						$link_user = JRoute::_( 'index.php?option=com_jblance&view=admproject&layout=edituser&cid[]='.$forum->user_id);
						$poster = JFactory::getUser($forum->user_id)->username;
					?>
					<tr id="tr_forum_<?php echo $forum->id; ?>">
						<td nowrap="nowrap"><a href="<?php echo $link_user; ?>"> <?php echo $poster; ?></a></td> 
						<td nowrap="nowrap"><?php echo JHTML::_('date', $forum->date_post, $dformat); ?></td>
						<td><?php echo $forum->message; ?></td>
						<td>
							<a class="remFeed" onclick="processForum('<?php echo $forum->id; ?>', 'admproject.removeForum');" href="javascript:void(0);">
							<img alt="" src="<?php echo JURI::root();?>components/com_jblance/images/remove.gif" title="<?php echo JText::_('COM_JBLANCE_REMOVE'); ?>">
							</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
				</table>
			</div>
		</fieldset>
	</div>
	
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>