<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	30 May 2012
 * @file name	:	views/message/tmpl/read.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Read a message thread (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.tooltip');
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 $doc->addScript("components/com_jblance/js/upclick-min.js");
 
 $user 			= JFactory::getUser();
 $config 		= JblanceHelper::getConfig();
 $dformat 		= $config->dateFormat;
 $showUsername 	= $config->showUsername;
 
 $nameOrUsername = ($showUsername) ? 'username' : 'name';
 
 //process the from and to id based on the current user and the parent message.
 //if parent fromId is equal to current userid, no need to swap them
 if($user->id == $this->parent->idFrom){
 	$idFrom = $this->parent->idFrom;
 	$idTo = $this->parent->idTo;
 }
 else {
 	$idFrom = $this->parent->idTo;
 	$idTo = $this->parent->idFrom;
 }
 
 $link_inbox = JRoute::_('index.php?option=com_jblance&view=message&layout=inbox');
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

window.addEvent('domready', function(){
	attachFile('uploadmessage', 'message.attachfile');
});
//-->
</script>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userFormMessage" id="userFormMessage" class="form-validate" onsubmit="return validateForm(this);" enctype="multipart/form-data">
 	<div class="pull-right"><a href="<?php echo $link_inbox; ?>" class="btn btn-primary"><span><?php echo JText::_('COM_JBLANCE_INBOX'); ?></span></a></div>
	<div class="sp10">&nbsp;</div>
	<div class="jbl_h3title"><?php echo $this->parent->subject; ?></div>
	<?php
	for($i=0, $x=count($this->rows); $i < $x; $i++){
		$row = $this->rows[$i];
		$userDtl = JFactory::getUser($row->idFrom);
	?>
	<div class="media jb-borderbtm-dot"  id="jbl_feed_item_<?php echo $row->id; ?>">
		<?php
		$attrib = 'width=56 height=56 class="img-polaroid"';
		$avatar = JblanceHelper::getThumbnail($row->idFrom, $attrib);
		echo !empty($avatar) ? LinkHelper::GetProfileLink($row->idFrom, $avatar, '', '', ' pull-left') : '&nbsp;' ?>
		<div class="media-body">
			<h5 class="media-heading"><?php echo LinkHelper::GetProfileLink($row->idFrom, $userDtl->$nameOrUsername); ?></h5>
			<?php echo $row->message; ?>
        	<p>
        		<i class="icon-calendar"></i> <?php echo JHtml::_('date', $row->date_sent, $dformat, true); ?> | 
        		<span id="feed_hide_<?php echo $row->id; ?>" class="help-inline">
					<a class="btn btn-link" onclick="processMessage('<?php echo $row->id; ?>', 'message.processmessage');" href="javascript:void(0);">
						<i class="icon-remove"></i> <?php echo JText::_('COM_JBLANCE_REMOVE'); ?>
					</a>
				</span>
				<!-- Show attachment if found -->
				<?php
				if(!empty($row->attachment)) : ?>
					 | <span>
						<i class="icon-download"></i>
				<?php echo LinkHelper::getDownloadLink('message', $row->id, 'message.download'); ?>
					</span>
				<?php	
				endif;
				?>
        	</p>
		</div>
	</div>
	<?php
	}
	?>
	<div class="control-group">
		<label class="control-label" for="message"><?php echo JText::_('COM_JBLANCE_WRITE_A_REPLY'); ?>:</label>
		<div class="controls">
			<textarea name="message" id="message" rows="5" class="input-xlarge required"></textarea>
			<div id="ajax-container-uploadmessage"></div>
			<div id="file-attached-uploadmessage"></div>
			<?php $tipmsg = JText::_('COM_JBLANCE_ATTACH_FILE').'::'.JText::_('COM_JBLANCE_ALLOWED_FILE_TYPES').' : '.$config->projectFileText.'<br>'.JText::_('COM_JBLANCE_MAXIMUM_FILE_SIZE').' : '.$config->projectMaxsize.' kB';
			?>
			<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
			<input type="button" id="uploadmessage" value="<?php echo JText::_('COM_JBLANCE_ATTACH_FILE'); ?>" class="btn">
		</div>
	</div>
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SEND'); ?>" class="btn btn-primary" />
	</div>
	
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="message.sendmessage" />	
	<input type="hidden" name="idFrom" value="<?php echo $idFrom; ?>" />
	<input type="hidden" name="idTo" value="<?php echo $idTo; ?>" />
	<input type="hidden" name="id" value="0" />
	<input type="hidden" name="subject" value="<?php echo $this->parent->subject;?>" />
	<input type="hidden" name="project_id" value="<?php echo $this->parent->project_id;?>" />
	<input type="hidden" name="parent" value="<?php echo $this->parent->id;?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>