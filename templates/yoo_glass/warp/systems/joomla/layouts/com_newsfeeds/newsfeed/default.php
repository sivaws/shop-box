<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die;

?>

<div id="system">

	<?php if ($this->params->get('page_heading')) : ?>
	<h1 class="page-title"><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif; ?>

	<h1 class="title"><a href="<?php echo $this->item->link; ?>" target="_blank"><?php echo str_replace('&apos;', "'", $this->item->name); ?></a></h1>

	<?php if ($this->params->get('show_feed_description') || (isset($this->newsfeed->image['url']) && isset($this->newsfeed->image['title']) && $this->params->get('show_feed_image'))) :?>
	<div class="description">
		<?php if (isset($this->rssDoc->image) && isset($this->rssDoc->imagetitle) && $this->params->get('show_feed_image')) : ?>
			<img src="<?php echo $this->rssDoc->image; ?>" alt="<?php echo $this->rssDoc->image->decription; ?>" />
		<?php endif; ?>
		<?php echo str_replace('&apos;', "'", $this->rssDoc->description); ?>
	</div>
	<?php endif; ?>

	<?php if ($this->params->get('show_tags', 1)) : ?>
		<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
		<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
	<?php endif; ?>

	<?php if (!empty($this->rssDoc[0])){ ?>
	<ul class="space">
		<?php for ($i = 0; $i < $this->item->numarticles; $i++) {  ?>

	<?php
		$uri = !empty($this->rssDoc[$i]->guid) || !is_null($this->rssDoc[$i]->guid) ? $this->rssDoc[$i]->guid : $this->rssDoc[$i]->uri;
		$uri = substr($uri, 0, 4) != 'http' ? $this->item->link : $uri;
		$text = !empty($this->rssDoc[$i]->content) ||  !is_null($this->rssDoc[$i]->content) ? $this->rssDoc[$i]->content : $this->rssDoc[$i]->description;
	?>
			<li>
				<?php if (!empty($this->rssDoc[$i]->uri)) : ?>
					<h1 class="title"><a href="<?php echo $this->rssDoc[$i]->uri; ?>" target="_blank">
					<?php  echo $this->rssDoc[$i]->title; ?></a></h1>
				<?php else : ?>
					<h3><?php  echo '<a target="_blank" href="' .$this->rssDoc[$i]->uri . '">' .$this->rssDoc[$i]->title. '</a>' ?></h3>
				<?php  endif; ?>
				<?php if ($this->params->get('show_item_description') && !empty($text)) : ?>
					<div class="feed-item-description">
					<?php if($this->params->get('show_feed_image', 0) == 0)
					{
						$text = JFilterOutput::stripImages($text);
					}
					$text = JHtml::_('string.truncate', $text, $this->params->get('feed_character_count'));
						echo str_replace('&apos;', "'", $text);
					?>

					</div>
				<?php endif; ?>
				</li>
			<?php } ?>
			</ul>
		<?php } ?>

</div>
