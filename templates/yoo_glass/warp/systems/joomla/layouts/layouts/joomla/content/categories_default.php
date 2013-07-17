<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

?>

<?php if ($displayData->params->get('show_page_heading')) : ?>
<h1 class="title"><?php echo $displayData->escape($displayData->params->get('page_heading')); ?></h1>
<?php endif; ?>

<?php if ($displayData->params->get('show_base_description') && ($displayData->params->get('categories_description') || $displayData->parent->description)) : ?>
<div class="description">
	<?php
		if ($displayData->params->get('categories_description')) {
			echo JHtml::_('content.prepare', $displayData->params->get('categories_description'), '',  $displayData->extension . '.categories');
		} elseif ($displayData->parent->description) {
			echo JHtml::_('content.prepare', $displayData->parent->description, '', $displayData->parent->extension . '.categories');
		}
	?>
</div>
<?php endif; ?>