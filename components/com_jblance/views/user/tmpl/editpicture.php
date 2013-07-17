<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	22 March 2012
 * @file name	:	views/user/tmpl/editpicture.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Edit profile picture (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.tooltip');

 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 $doc->addScript("components/com_jblance/js/upclick-min.js"); 
 $doc->addScript("components/com_jblance/js/ysr-crop.js"); 

 $user= JFactory::getUser();
 $model = $this->getModel();
 $config = JblanceHelper::getConfig();
?>
<script type="text/javascript">
<!--
	window.addEvent('domready', function(){
		createUploadButton('<?php echo $this->row->user_id; ?>', 'user.uploadpicture');
	});
//-->
</script>
<?php include_once(JPATH_COMPONENT.'/views/profilemenu.php'); ?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="profilePicture" class="form-validate" onsubmit="return validateForm(this);">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PROFILE_PICTURE'); ?></div>
	<div id="divpicture">
		<?php 
		$attrib = 'class="thumbnail"';
		echo JblanceHelper::getLogo($this->row->user_id, $attrib); ?>
	</div>
	<div class="sp10">&nbsp;</div>
	<div id="ajax-container"></div>
	<?php 
	$tipmsg = JText::_('COM_JBLANCE_ATTACH_IMAGE').'::'.JText::_('COM_JBLANCE_ALLOWED_FILE_TYPES').' : <em>'.$config->imgFileText.'</em><br>'.JText::_('COM_JBLANCE_MAXIMUM_FILE_SIZE').' : <em>'.$config->imgMaxsize.'kB</em>';
	?>
	<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
	<input type="button" id="photoupload" value="<?php echo JText::_('COM_JBLANCE_UPLOAD_NEW'); ?>" class="btn btn-primary">
	<input type="button" id="removepicture" value="<?php echo JText::_('COM_JBLANCE_REMOVE_PICTURE'); ?>" onclick="removePicture('<?php echo $this->row->user_id; ?>', 'user.removepicture');" class="btn btn-danger" >

	<input type="hidden" name="option" value="com_jblance">
	<input type="hidden" name="task" value="">
	<?php echo JHTML::_('form.token'); ?>
</form>	

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="profileThumbnail" class="form-validate" onsubmit="return validateForm(this);">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_THUMBNAIL'); ?></div>
	<div class="fl">
		<div id="divthumb">
			<?php 
			$attrib = 'class="thumbnail"';
			echo JblanceHelper::getThumbnail($this->row->user_id, $attrib); ?>
		</div>
		<?php if($this->row->picture) : ?>
		<p>
			<a href="javascript:updateThumbnail('user.croppicture')" id="update-thumbnail"><?php echo JText::_('COM_JBLANCE_EDIT_THUMBNAIL'); ?></a>
		</p>
		<?php endif; ?>
	</div>
	
	<!-- show the edit thumbnail if the user has attached any picture -->
	
	<div id="editthumb" style="position:relative; left:200px; float:left; display:none; ">
	<?php if($this->row->picture) : ?>
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
		        <div id="cropinfo">
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
	<?php endif; ?>
	</div>
	<div style="clear:both;"></div>
	<input type="hidden" name="option" value="com_jblance">
	<input type="hidden" name="task" value="">
	<?php echo JHTML::_('form.token'); ?>
</form>	
