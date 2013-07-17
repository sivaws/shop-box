<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	28 March 2012
 * @file name	:	modules/mod_jblancecategory/tmpl/default.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */

 // no direct access
 defined('_JEXEC') or die('Restricted access'); 

 $set_Itemid	= intval($params->get('set_itemid', 0));
 $Itemid = ($set_Itemid > 0) ? '&amp;Itemid='.$set_Itemid : '';

 $document = JFactory::getDocument(); 
 $document->addStyleSheet("components/com_jblance/css/style.css"); 
 $document->addStyleSheet("modules/mod_jblancecategory/css/style.css");
 
 //calculate span with
 $spanCount = 12 / $total_column;
 $span = 'span'.$spanCount;
 
 if(count($rows) > 0){ ?>
 

 	<?php
	foreach($rows as $row){ ?>
<div class="row-fluid">
	<div class="span12">
		<h4 style="border-bottom: 1px solid #eeeeee;"><strong><?php echo $row->category; ?></strong></h4>
	<?php
	$subs = ModJblanceCategoryHelper::getSubCategories($row->id, $show_empty_count, '', '');
	foreach ($subs as $sub){ 
		$link_proj_categ = JRoute::_('index.php?option=com_jblance&amp;view=project&amp;layout=searchproject&amp;id_categ='.$sub->id.'&amp;type=category'.$Itemid); ?>
		<div class="test">
			<div class="<?php echo $span; ?>">
				<a href="<?php echo $link_proj_categ;?>" class="jbl_subcatlink">
				<?php echo $sub->category; ?>
				<?php
				if($show_count){
					echo '('.$sub->thecount.')';
				}
				?>
				</a>
			</div>
		</div>
	<?php
	}
	?>
	</div>
</div>
<?php 
	} 	
} 
?>