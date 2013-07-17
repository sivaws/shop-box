<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');

// Get the user object.
$user = JFactory::getUser();

// Check if user is allowed to add/edit based on tags permissions.
// Do we really have to make it so people can see unpublished tags???
$canEdit = $user->authorise('core.edit', 'com_tags');
$canCreate = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');
$items = $this->items;
$n = count($this->items);

?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">

	<?php if ($this->params->get('show_headings') || $this->params->get('filter_field') !== '0' || $this->params->get('show_pagination_limit')) :?>
	<div class="filter">

		<?php if ($this->params->get('filter_field') != 'hide') :?>
		<div>
			<label for="filter-search"><?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL').'&#160;'; ?></label>
			<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_TAGS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>" />
		</div>
		<?php endif; ?>

		<?php if ($this->params->get('show_pagination_limit')) : ?>
		<div>
			<label for="limit"><?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		<?php endif; ?>

		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
		<input type="hidden" name="task" value="" />

	</div>
	<?php endif; ?>

</form>

<?php if ($this->items == false || $n == 0) : ?>

	<p><?php echo JText::_('COM_TAGS_NO_ITEMS'); ?></p>

<?php else : ?>

	<div class="items">

		<?php foreach ($items as $i => $item) : ?>
		<article class="item">

			<?php if ($item->core_state != 0) : ?>
			<h1 class="title"><a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>"><?php echo $this->escape($item->core_title); ?></a></h1>
			<?php endif; ?>

			<?php $images  = json_decode($item->core_images);?>
			<?php if ($this->params->get('tag_list_show_item_image', 1) == 1 && !empty($images->image_intro)) :?>
			<img src="<?php echo htmlspecialchars($images->image_intro);?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>">
			<?php endif; ?>

			<?php if ($this->params->get('tag_list_show_item_description', 1)) : ?>
			<div class="content clearfix"><?php echo JHtml::_('string.truncate', $item->core_body, $this->params->get('tag_list_item_maximum_characters')); ?></div>
			<?php endif; ?>

		</article>
		<?php endforeach; ?>

	</div>

	<?php if ($this->params->get('show_pagination')) : ?>
	<?php echo $this->pagination->getPagesLinks(); ?>
	<?php endif; ?>

<?php endif; ?>
