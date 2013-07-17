<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	29 November 2012
 * @file name	:	views/user/tmpl/viewportfolio.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Lets user to view porfolio (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $row = $this->row;
 $config = JblanceHelper::getConfig();
 $dformat = $config->dateFormat;
?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userForm">
	<div class="jbl_h3title">
		<?php echo JText::_('COM_JBLANCE_PORTFOLIO_DETAILS').' - '.$row->title; ?>
	</div>
	<div class="row-fluid">
		<?php
		if($this->row->picture){
			$attachment = explode(";", $this->row->picture);
			$showName = $attachment[0];
			$fileName = $attachment[1];
			$imgLoc = JBPORTFOLIO_URL.$fileName;
		?>
		<p class="jb-aligncenter"><img src='<?php echo $imgLoc; ?>' class="img-polaroid" style="max-width: 450px; width: 95%" /></p>
		<?php 
		} ?>
		<h4><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?>:</h4>
		<p><?php echo $row->description; ?></p>
		
		<h4><?php echo JText::_('COM_JBLANCE_SKILLS'); ?>:</h4>
		<p><?php echo JblanceHelper::getCategoryNames($row->id_category); ?></p>
		
		<h4><?php echo JText::_('COM_JBLANCE_WEB_ADDRESS'); ?>:</h4>
		<p><?php echo !empty($row->link) ? $row->link : '<span class="redfont">'.JText::_('COM_JBLANCE_NOT_MENTIONED').'</span>'; ?></p>
		
		<h4><?php echo JText::_('COM_JBLANCE_DURATION'); ?>:</h4>
		<p>
			<?php
			if( ($row->start_date != "0000-00-00 00:00:00" ) && ($row->finish_date!= "0000-00-00 00:00:00") ){
			?>
				<?php echo JHTML::_('date', $this->row->start_date, $dformat).' &harr; '.JHTML::_('date', $this->row->finish_date, $dformat); ?>
			<?php 
			}
			else
				echo '<span class="redfont">'.JText::_('COM_JBLANCE_NOT_MENTIONED').'</span>';
			?>
		</p>
		
		<?php if($row->attachment){ ?>
		<h4><?php echo JText::_('COM_JBLANCE_ATTACHMENT'); ?>:</h4>
		<p><?php echo LinkHelper::getDownloadLink('portfolio', $this->row->id, 'user.download'); ?></p>
			<?php } ?>
	</div>
</form>