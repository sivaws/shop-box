<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 April 2012
 * @file name	:	modules/mod_jblanceusers/tmpl/default.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 // no direct access
 defined('_JEXEC') or die('Restricted access');

 if(version_compare(JVERSION, '3.0', '>')){
	JHtml::_('bootstrap.loadCss');
 }
 else {
	jbimport('moobootstrap');
	JHtml::_('moobootstrap.loadCss');
 }

 $config 		= JblanceHelper::getConfig();
 $showUsername 	= $config->showUsername;
 $nameOrUsername = ($showUsername) ? 'username' : 'name';

 $document = JFactory::getDocument();
 $document->addStyleSheet("components/com_jblance/css/style.css");
 $document->addStyleSheet("modules/mod_jblanceusers/css/style.css"); 

 $show_logo = intval($params->get('show_logo', 1));
 $set_Itemid	= intval($params->get('set_itemid', 0));
 $Itemid = ($set_Itemid > 0) ? '&Itemid='.$set_Itemid : '';
 $show_rating = intval($params->get('show_usertype', 0));

for ($i=0, $n=count($rows); $i < $n; $i++) {
	$row = $rows[$i];
	$link_detail = JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&id='.$row->user_id.$Itemid); ?>
<div class="media">
	<?php 
	if($show_logo){
		$attrib = 'class="pull-left media-object img-polaroid" style="width: 32px; height: 32px;"';
		echo JblanceHelper::getThumbnail($row->user_id, $attrib);
	} ?>
	<div class="media-body">
		<h5 class="media-heading"><?php echo LinkHelper::GetProfileLink($row->user_id, $row->$nameOrUsername); ?></h5>
		<?php 
		if($show_rating == 1){ 
			JblanceHelper::getAvarageRate($row->user_id, 1);
		} ?>
	</div>
</div>
<?php
}
?>
