<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

defined('JPATH_BASE') or die;

JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php');

?>

<?php if (!empty($displayData)) : ?>
<p class="taxonomy">Tags:
	<?php foreach ($displayData as $i => $tag) : ?>
		<?php if (in_array($tag->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id')))) : ?>
			<?php $tagParams = new JRegistry($tag->params); ?>
			<?php $link_class = $tagParams->get('tag_link_class', 'label label-info'); ?>
			<a href="<?php echo JRoute::_(TagsHelperRoute::getTagRoute($tag->tag_id . ':' . $tag->alias)) ?>"><?php echo $this->escape($tag->title); ?></a>
		<?php endif; ?>
	<?php endforeach; ?>
</p>
<?php endif; ?>
