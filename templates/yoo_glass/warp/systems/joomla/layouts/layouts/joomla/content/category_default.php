<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('JPATH_BASE') or die;

$params  = $displayData->params;
$extension = $displayData->get('category')->extension;
$canEdit = $params->get('access-edit');
$tagsData  = $displayData->get('category')->tags->itemTags;

?>

<?php if ($params->get('show_page_heading')) : ?>
<h1 class="title"><?php echo $displayData->escape($params->get('page_heading')); ?></h1>
<?php endif; ?>

<?php if($params->get('show_category_title', 1)) : ?>
<h2 class="subtitle"><?php echo JHtml::_('content.prepare', $displayData->get('category')->title, '', $extension.'.category'); ?></h1>
<?php endif; ?>

<?php if (($params->get('show_description', 1) && $displayData->get('category')->description) || ($params->def('show_description_image', 1) && $displayData->get('category')->getParams()->get('image'))) : ?>
<div class="description">
	<?php if ($params->get('show_description_image') && $displayData->get('category')->getParams()->get('image')) : ?>
		<img src="<?php echo $displayData->get('category')->getParams()->get('image'); ?>"/>
	<?php endif; ?>
	<?php if ($params->get('show_description') && $displayData->get('category')->description) : ?>
		<?php echo JHtml::_('content.prepare', $displayData->get('category')->description, '', $extension .'.category'); ?>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php echo $displayData->loadTemplate($displayData->subtemplatename); ?>

<?php if ($displayData->get('show_tags', 1)) : ?>
	<?php echo JLayoutHelper::render('joomla.content.tags', $tagsData); ?>
<?php endif; ?>

<?php if ($displayData->get('children') && $displayData->maxLevel != 0) : ?>
<div class="children">
	<h3><?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?></h3>
	<?php echo $displayData->loadTemplate('children'); ?>
</div>
<?php endif; ?>

