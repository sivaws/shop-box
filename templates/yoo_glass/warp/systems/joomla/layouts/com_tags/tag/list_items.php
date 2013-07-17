<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('_JEXEC') or die;

JHtml::_('behavior.framework');

$n			= count($this->items);
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">

	<?php if ($this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) :?>
	<div class="filter">

		<?php if ($this->params->get('filter_field') !== '0') :?>
		<div>
			<label class="filter-search-lbl element-invisible" for="filter-search"><?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL').'&#160;'; ?></label>
			<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_TAGS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>" />
		</div>
		<?php endif; ?>

		<?php if ($this->params->get('show_pagination_limit')) : ?>
		<div>
			<label for="limit" class="element-invisible"><?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?></label>
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

	<table class="zebra" border="0" cellspacing="0" cellpadding="0">

		<?php if ($this->params->get('show_headings')) : ?>
		<thead>
			<tr>
				<th align="left"><?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'c.core_title', $listDirn, $listOrder); ?></th>

				<?php if ($date = $this->params->get('tag_list_show_date')) : ?>
				<th align="left">
					<?php if ($date == "created") : ?>
						<?php echo JHtml::_('grid.sort', 'COM_TAGS_'.$date.'_DATE', 'c.core_created_time', $listDirn, $listOrder); ?>
					<?php elseif ($date == "modified") : ?>
						<?php echo JHtml::_('grid.sort', 'COM_TAGS_'.$date.'_DATE', 'c.core_modified_time', $listDirn, $listOrder); ?>
					<?php elseif ($date == "published") : ?>
						<?php echo JHtml::_('grid.sort', 'COM_TAGS_'.$date.'_DATE', 'c.core_publish_up', $listDirn, $listOrder); ?>
					<?php endif; ?>
				</th>
				<?php endif; ?>

			</tr>
		</thead>
		<?php endif; ?>

		<tbody>

			<?php foreach ($this->items as $i => $item) : ?>

				<?php if ($this->items[$i]->core_state == 0) : ?>
				<tr class="system-unpublished <?php if ($i % 2 == '0') { echo 'odd'; } else { echo 'even'; } ?>">
				<?php else: ?>
				<tr class="<?php if ($i % 2 == '0') { echo 'odd'; } else { echo 'even'; } ?>">
				<?php endif; ?>

					<td>
						<a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>"><?php echo $this->escape($item->core_title); ?></a>
					</td>

					<?php if ($this->params->get('tag_list_show_date')) : ?>
					<td><?php echo JHtml::_('date', $item->displayDate, $this->escape($this->params->get('date_format', JText::_('DATE_FORMAT_LC3')))); ?></td>
					<?php endif; ?>

				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
	<?php echo $this->pagination->getPagesLinks(); ?>
	<?php endif; ?>

<?php endif; ?>


