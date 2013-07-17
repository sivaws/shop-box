<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');

// Get the user object.
$user = JFactory::getUser();

// Check if user is allowed to add/edit based on tags permissions.
$canEdit = $user->authorise('core.edit', 'com_tags');
$canCreate = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');

$columns = $this->params->get('tag_columns', 1);
// Avoid division by 0 and negative columns.
if ($columns < 1)
{
	$columns = 1;
}
$bsspans = floor(12 / $columns);
if ($bsspans < 1)
{
	$bsspans = 1;
}

$bscolumns = min($columns, floor(12 / $bsspans));
$n = count($this->items);
?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">

	<?php if ($this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) : ?>
	<div class="filter">

		<?php if ($this->params->get('filter_field') !== '0') : ?>
		<div>
			<label class="filter-search-lbl element-invisible" for="filter-search"><?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL') . '&#160;'; ?></label>
			<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_TAGS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>" />
		</div>
		<?php endif; ?>

		<?php if ($this->params->get('show_pagination_limit')) : ?>
		<div class="btn-group pull-right">
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

	<p><?php echo JText::_('COM_TAGS_NO_TAGS'); ?></p>

<?php else : ?>

	<?php foreach ($this->items as $i => $item) : ?>
	
		<?php if ($n == 1 || $i == 0 || $bscolumns == 1 || $i % $bscolumns == 0) : ?>
		<ul>
		<?php endif; ?>

			<?php if ((!empty($item->access)) && in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
	 		<li>
				<a href="<?php echo JRoute::_(TagsHelperRoute::getTagRoute($item->id . ':' . $item->alias)); ?>"><?php echo $this->escape($item->title); ?></a>

				<?php if ($this->params->get('all_tags_show_tag_hits')) : ?>
				<small>(<?php echo JText::sprintf('JGLOBAL_HITS_COUNT', $item->hits); ?>)</small>
				<?php endif; ?>

				<?php if ($this->params->get('all_tags_show_tag_image') && !empty($item->images)) : ?>

					<?php $images  = json_decode($item->images); ?>
					<?php if (!empty($images->image_intro)): ?>
					<?php $caption = ($images->image_intro_caption) ? htmlspecialchars($images->image_intro_caption) : '' ?>
					<div><img src="<?php echo $images->image_intro; ?>" title="<?php echo $caption; ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>"/></div>
					<?php endif; ?>

				<?php endif; ?>

				<?php if ($this->params->get('all_tags_show_tag_description', 1)) : ?>
				<div><?php echo JHtml::_('string.truncate', $item->description, $this->params->get('tag_list_item_maximum_characters')); ?></div>
				<?php endif; ?>

			</li>
			<?php endif; ?>

		<?php if (($i == 0 && $n == 1) || $i == $n - 1 || $bscolumns == 1 || (($i + 1) % $bscolumns == 0)) : ?>
		</ul>
		<?php endif; ?>

	<?php endforeach; ?>

	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
	<?php echo $this->pagination->getPagesLinks(); ?>
	<?php endif; ?>

<?php endif;?>


