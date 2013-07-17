<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	11 June 2012
 * @file name	:	views/user/tmpl/userlist.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	User list page (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $app  = JFactory::getApplication();
 $letter = $app->input->get('letter', '', 'string');
 $actionLetter = (!empty($letter)) ? '&letter='.$letter : '';
 
 $action	= JRoute::_('index.php?option=com_jblance&view=user&layout=userlist'.$actionLetter);
 $actionAll	= JRoute::_('index.php?option=com_jblance&view=user&layout=userlist');
 
 $jbuser = JblanceHelper::get('helper.user');		// create an instance of the class FieldsHelper
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 
 $keyword	  = $app->input->get('keyword', '', 'string');
 $id_categ	  = $app->input->get('id_categ', array(), 'array');
 
 // Load the parameters.
 $params = $app->getParams();
 $show_search = $params->get('show_search', false);
 ?>
<form action="<?php echo $action; ?>" method="post" name="userFormJob" class="form-inline" enctype="multipart/form-data">
<!-- show search fields if enabled -->
	<?php if($show_search) : ?>
	<div class="sp10">&nbsp;</div>
	<div class="row-fluid">
		<div class="span12 text-center">
			<?php $tipMsg = ''; ?>
			<input type="text" name="keyword" id="keyword" value="<?php echo $keyword; ?>" class="input-large hasTip" title="<?php echo $tipMsg; ?>" placeholder="<?php echo JText::_('COM_JBLANCE_KEYWORDS'); ?>" />
  			<?php 
			$attribs = 'class="input-xlarge" size="1"';
			$categtree = $select->getSelectCategoryTree('id_categ[]', $id_categ, 'COM_JBLANCE_ALL_CATEGORIES', $attribs, '', true);
			echo $categtree; ?>
  			<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SEARCH'); ?>" class="btn btn-primary" />
		</div>
	</div>
	<div class="lineseparator"></div>
	<?php endif; ?>

	<div class="jbl_h3title"><?php echo $this->escape($this->params->get('page_heading', JText::_('COM_JBLANCE_USERLIST'))); ?></div>
	<!-- hide alpha index if search form is enabled -->
	<?php if(!$show_search) : ?>
	<div class="btn-group">
	<?php
	echo JHTML::_('link', $actionAll, '#', array('title'=>JText::_('COM_JBLANCE_ALL'), 'class'=>'btn btn-mini')); 
	foreach (range('A', 'Z') as $i) :
		$link_comp_index = JRoute::_('index.php?option=com_jblance&view=user&layout=userlist&letter='.strtolower($i), false);
		if(strcasecmp($letter, $i) == 0)
			echo JHTML::_('link', $link_comp_index, $i, array('title'=>$i, 'class'=>'btn btn-mini active'));
		else
			echo JHTML::_('link', $link_comp_index, $i, array('title'=>$i, 'class'=>'btn btn-mini'));
	endforeach; ?>	
    </div>
	<div class="sp10">&nbsp;</div>
	<?php endif; ?>

	<?php
	for ($i=0, $x=count($this->rows); $i < $x; $i++){
		$row = $this->rows[$i];
		$status = $jbuser->isOnline($row->user_id);		//get user online status
		?>
	<div class="media">
		<?php
		$attrib = 'width=48 height=48 class="img-polaroid"';
		$avatar = JblanceHelper::getThumbnail($row->user_id, $attrib);
		echo !empty($avatar) ? LinkHelper::GetProfileLink($row->user_id, $avatar, '', '', ' pull-left') : '&nbsp;' ?>
		<div class="media-body">
			<h3 class="media-heading"><?php echo LinkHelper::GetProfileLink($row->user_id, $row->name); ?> <small><?php echo $row->username; ?></small></h3>
			<?php if(!empty($row->biz_name)) : ?>
			<strong><?php echo JText::_('COM_JBLANCE_BUSINESS_NAME'); ?> : </strong><?php echo $row->biz_name; ?> |
			<?php endif; ?>
			<strong><?php echo JText::_('COM_JBLANCE_USERGROUP'); ?> : </strong><?php echo $row->grpname; ?> | 
			<?php if($status) : ?>
				<span class="label label-success"><?php echo JText::_('COM_JBLANCE_ONLINE'); ?></span>
			<?php else : ?>
				<span class="label"><?php echo JText::_('COM_JBLANCE_OFFLINE'); ?></span>
			<?php endif; ?>
		</div>
	</div>
	<div class="lineseparator"></div>
	<?php } ?>
	<div class="pagination">
	<?php echo $this->pageNav->getListFooter(); ?>
	</div>
</form> 