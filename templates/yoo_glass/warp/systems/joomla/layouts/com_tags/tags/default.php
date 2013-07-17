<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

// Note that there are certain parts of this layout used only when there is exactly one tag.

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
$description = $this->params->get('all_tags_description');
$descriptionImage = $this->params->get('all_tags_description_image');

?>

<div id="system">

	<?php if ($this->state->get('show_page_heading')) : ?>
	<h1 class="title"><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif;?>

	<?php if ((!empty($description)) || ($this->params->get('all_tags_show_description_image') && !empty($descriptionImage))):?>
	<div class="description">

		<?php if ($this->params->get('all_tags_show_description_image') && !empty($descriptionImage)):?>
		<?php echo '<img src="' . $descriptionImage . '">';?>
		<?php endif;?>

		<?php if (!empty($description)) : ?>
		<?php echo $description; ?>
		<?php endif;?>

	</div>
	<?php endif;?>

	<?php echo $this->loadTemplate('items'); ?>

</div>