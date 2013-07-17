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

	<?php $this->subtemplatename = 'items'; ?>
	<?php echo JLayoutHelper::render('joomla.content.category_default', $this); ?>

</div>