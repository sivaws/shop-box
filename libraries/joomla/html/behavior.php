<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Utility class for javascript behaviors
 *
 * @package     Joomla.Platform
 * @subpackage  HTML
 * @since       11.1
 */
abstract class JHtmlBehavior
{
	/**
	 * @var   array   array containing information for loaded files
	 */
	protected static $loaded = array();

	/**
	 * Method to load the MooTools framework into the document head
	 *
	 * If debugging mode is on an uncompressed version of MooTools is included for easier debugging.
	 *
	 * @param   string  $extras  MooTools file to load
	 * @param   mixed   $debug   Is debugging mode on? [optional]
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function framework($extras = false, $debug = null)
	{
		$type = $extras ? 'more' : 'core';

		// Only load once
		if (!empty(self::$loaded[__METHOD__][$type]))
		{
			return;
		}

		// If no debugging value is set, use the configuration setting
		if ($debug === null)
		{
			$config = JFactory::getConfig();
			$debug = $config->get('debug');
		}

		if ($type != 'core' && empty(self::$loaded[__METHOD__]['core']))
		{
			self::framework(false, $debug);
		}

		JHtml::_('script', 'system/mootools-' . $type . '.js', false, true, false, false, $debug);
		JHtml::_('script', 'system/core.js', false, true);
		self::$loaded[__METHOD__][$type] = true;

		return;
	}

	/**
	 * Add unobtrusive javascript support for image captions.
	 *
	 * @param   string  $selector  The selector for which a caption behaviour is to be applied.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function caption($selector = 'img.caption')
	{
		// Only load once
		if (isset(self::$loaded[__METHOD__][$selector]))
		{
			return;
		}

		// Include MooTools framework
		self::framework();

		JHtml::_('script', 'system/caption.js', true, true);

		// Attach caption to document
		JFactory::getDocument()->addScriptDeclaration(
			"window.addEvent('load', function() {
				new JCaption('" . $selector . "');
			});"
		);

		// Set static array
		self::$loaded[__METHOD__][$selector] = true;
	}

	/**
	 * Add unobtrusive javascript support for form validation.
	 *
	 * To enable form validation the form tag must have class="form-validate".
	 * Each field that needs to be validated needs to have class="validate".
	 * Additional handlers can be added to the handler for username, password,
	 * numeric and email. To use these add class="validate-email" and so on.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function formvalidation()
	{
		// Only load once
		if (isset(self::$loaded[__METHOD__]))
		{
			return;
		}

		// Add validate.js language strings
		JText::script('JLIB_FORM_FIELD_INVALID');

		// Include MooTools framework
		self::framework();

		JHtml::_('script', 'system/validate.js', true, true);
		self::$loaded[__METHOD__] = true;
	}

	/**
	 * Add unobtrusive javascript support for submenu switcher support in
	 * Global Configuration and System Information.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function switcher()
	{
		// Only load once
		if (isset(self::$loaded[__METHOD__]))
		{
			return;
		}

		// Include MooTools framework
		self::framework();

		JHtml::_('script', 'system/switcher.js', true, true);

		$script = "
			document.switcher = null;
			window.addEvent('domready', function(){
				toggler = document.id('submenu');
				element = document.id('config-document');
				if (element) {
					document.switcher = new JSwitcher(toggler, element, {cookieName: toggler.getProperty('class')});
				}
			});";

		JFactory::getDocument()->addScriptDeclaration($script);
		self::$loaded[__METHOD__] = true;
	}

	/**
	 * Add unobtrusive javascript support for a combobox effect.
	 *
	 * Note that this control is only reliable in absolutely positioned elements.
	 * Avoid using a combobox in a slider or dynamic pane.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function combobox()
	{
		if (isset(self::$loaded[__METHOD__]))
		{
			return;
		}
		// Include MooTools framework
		self::framework();

		JHtml::_('script', 'system/combobox.js', true, true);
		self::$loaded[__METHOD__] = true;
	}

	/**
	 * Add unobtrusive javascript support for a hover tooltips.
	 *
	 * Add a title attribute to any element in the form
	 * title="title::text"
	 *
	 *
	 * Uses the core Tips class in MooTools.
	 *
	 * @param   string  $selector  The class selector for the tooltip.
	 * @param   array   $params    An array of options for the tooltip.
	 *                             Options for the tooltip can be:
	 *                             - maxTitleChars  integer   The maximum number of characters in the tooltip title (defaults to 50).
	 *                             - offsets        object    The distance of your tooltip from the mouse (defaults to {'x': 16, 'y': 16}).
	 *                             - showDelay      integer   The millisecond delay the show event is fired (defaults to 100).
	 *                             - hideDelay      integer   The millisecond delay the hide hide is fired (defaults to 100).
	 *                             - className      string    The className your tooltip container will get.
	 *                             - fixed          boolean   If set to true, the toolTip will not follow the mouse.
	 *                             - onShow         function  The default function for the show event, passes the tip element
	 *                               and the currently hovered element.
	 *                             - onHide         function  The default function for the hide event, passes the currently
	 *                               hovered element.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function tooltip($selector = '.hasTip', $params = array())
	{
		$sig = md5(serialize(array($selector, $params)));

		if (isset(self::$loaded[__METHOD__][$sig]))
		{
			return;
		}

		// Include MooTools framework
		self::framework(true);

		// Setup options object
		$opt['maxTitleChars'] = (isset($params['maxTitleChars']) && ($params['maxTitleChars'])) ? (int) $params['maxTitleChars'] : 50;

		// Offsets needs an array in the format: array('x'=>20, 'y'=>30)
		$opt['offset']    = (isset($params['offset']) && (is_array($params['offset']))) ? $params['offset'] : null;
		$opt['showDelay'] = (isset($params['showDelay'])) ? (int) $params['showDelay'] : null;
		$opt['hideDelay'] = (isset($params['hideDelay'])) ? (int) $params['hideDelay'] : null;
		$opt['className'] = (isset($params['className'])) ? $params['className'] : null;
		$opt['fixed']     = (isset($params['fixed']) && ($params['fixed'])) ? true : false;
		$opt['onShow']    = (isset($params['onShow'])) ? '\\' . $params['onShow'] : null;
		$opt['onHide']    = (isset($params['onHide'])) ? '\\' . $params['onHide'] : null;

		$options = JHtml::getJSObject($opt);

		// Attach tooltips to document
		JFactory::getDocument()->addScriptDeclaration(
			"window.addEvent('domready', function() {
			$$('$selector').each(function(el) {
				var title = el.get('title');
				if (title) {
					var parts = title.split('::', 2);
					el.store('tip:title', parts[0]);
					el.store('tip:text', parts[1]);
				}
			});
			var JTooltips = new Tips($$('$selector'), $options);
		});"
		);

		// Set static array
		self::$loaded[__METHOD__][$sig] = true;

		return;
	}

	/**
	 * Add unobtrusive javascript support for modal links.
	 *
	 * @param   string  $selector  The selector for which a modal behaviour is to be applied.
	 * @param   array   $params    An array of parameters for the modal behaviour.
	 *                             Options for the modal behaviour can be:
	 *                            - ajaxOptions
	 *                            - size
	 *                            - shadow
	 *                            - overlay
	 *                            - onOpen
	 *                            - onClose
	 *                            - onUpdate
	 *                            - onResize
	 *                            - onShow
	 *                            - onHide
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function modal($selector = 'a.modal', $params = array())
	{
		$document = JFactory::getDocument();

		// Load the necessary files if they haven't yet been loaded
		if (!isset(self::$loaded[__METHOD__]))
		{
			// Include MooTools framework
			self::framework(true);

			// Load the javascript and css
			JHtml::_('script', 'system/modal.js', true, true);
			JHtml::_('stylesheet', 'system/modal.css', array(), true);
		}

		$sig = md5(serialize(array($selector, $params)));

		if (isset(self::$loaded[__METHOD__][$sig]))
		{
			return;
		}

		// Setup options object
		$opt['ajaxOptions']   = (isset($params['ajaxOptions']) && (is_array($params['ajaxOptions']))) ? $params['ajaxOptions'] : null;
		$opt['handler']       = (isset($params['handler'])) ? $params['handler'] : null;
		$opt['parseSecure']   = (isset($params['parseSecure'])) ? (bool) $params['parseSecure'] : null;
		$opt['closable']      = (isset($params['closable'])) ? (bool) $params['closable'] : null;
		$opt['closeBtn']      = (isset($params['closeBtn'])) ? (bool) $params['closeBtn'] : null;
		$opt['iframePreload'] = (isset($params['iframePreload'])) ? (bool) $params['iframePreload'] : null;
		$opt['iframeOptions'] = (isset($params['iframeOptions']) && (is_array($params['iframeOptions']))) ? $params['iframeOptions'] : null;
		$opt['size']          = (isset($params['size']) && (is_array($params['size']))) ? $params['size'] : null;
		$opt['shadow']        = (isset($params['shadow'])) ? $params['shadow'] : null;
		$opt['overlay']       = (isset($params['overlay'])) ? $params['overlay'] : null;
		$opt['onOpen']        = (isset($params['onOpen'])) ? $params['onOpen'] : null;
		$opt['onClose']       = (isset($params['onClose'])) ? $params['onClose'] : null;
		$opt['onUpdate']      = (isset($params['onUpdate'])) ? $params['onUpdate'] : null;
		$opt['onResize']      = (isset($params['onResize'])) ? $params['onResize'] : null;
		$opt['onMove']        = (isset($params['onMove'])) ? $params['onMove'] : null;
		$opt['onShow']        = (isset($params['onShow'])) ? $params['onShow'] : null;
		$opt['onHide']        = (isset($params['onHide'])) ? $params['onHide'] : null;

		if (isset($params['fullScreen']) && (bool) $params['fullScreen'])
		{
			$opt['size']      = array('x' => '\\window.getSize().x-80', 'y' => '\\window.getSize().y-80');
		}

		$options = JHtml::getJSObject($opt);

		// Attach modal behavior to document
		$document
			->addScriptDeclaration(
			"
		window.addEvent('domready', function() {

			SqueezeBox.initialize(" . $options . ");
			SqueezeBox.assign($$('" . $selector . "'), {
				parse: 'rel'
			});
		});"
		);

		// Set static array
		self::$loaded[__METHOD__][$sig] = true;

		return;
	}

	/**
	 * JavaScript behavior to allow shift select in grids
	 *
	 * @param   string  $id  The id of the form for which a multiselect behaviour is to be applied.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function multiselect($id = 'adminForm')
	{
		// Only load once
		if (isset(self::$loaded[__METHOD__][$id]))
		{
			return;
		}

		// Include MooTools framework
		self::framework();

		JHtml::_('script', 'system/multiselect.js', true, true);

		// Attach multiselect to document
		JFactory::getDocument()->addScriptDeclaration(
			"window.addEvent('domready', function() {
				new Joomla.JMultiSelect('" . $id . "');
			});"
		);

		// Set static array
		self::$loaded[__METHOD__][$id] = true;

		return;
	}

	/**
	 * Add unobtrusive javascript support for a collapsible tree.
	 *
	 * @param   string  $id      An index
	 * @param   array   $params  An array of options.
	 * @param   array   $root    The root node
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function tree($id, $params = array(), $root = array())
	{
		// Include MooTools framework
		self::framework();

		JHtml::_('script', 'system/mootree.js', true, true, false, false);
		JHtml::_('stylesheet', 'system/mootree.css', array(), true);

		if (isset(self::$loaded[__METHOD__][$id]))
		{
			return;
		}

		// Setup options object
		$opt['div']   = (array_key_exists('div', $params)) ? $params['div'] : $id . '_tree';
		$opt['mode']  = (array_key_exists('mode', $params)) ? $params['mode'] : 'folders';
		$opt['grid']  = (array_key_exists('grid', $params)) ? '\\' . $params['grid'] : true;
		$opt['theme'] = (array_key_exists('theme', $params)) ? $params['theme'] : JHtml::_('image', 'system/mootree.gif', '', array(), true, true);

		// Event handlers
		$opt['onExpand'] = (array_key_exists('onExpand', $params)) ? '\\' . $params['onExpand'] : null;
		$opt['onSelect'] = (array_key_exists('onSelect', $params)) ? '\\' . $params['onSelect'] : null;
		$opt['onClick']  = (array_key_exists('onClick', $params)) ? '\\' . $params['onClick']
		: '\\function(node){  window.open(node.data.url, node.data.target != null ? node.data.target : \'_self\'); }';

		$options = JHtml::getJSObject($opt);

		// Setup root node
		$rt['text']     = (array_key_exists('text', $root)) ? $root['text'] : 'Root';
		$rt['id']       = (array_key_exists('id', $root)) ? $root['id'] : null;
		$rt['color']    = (array_key_exists('color', $root)) ? $root['color'] : null;
		$rt['open']     = (array_key_exists('open', $root)) ? '\\' . $root['open'] : true;
		$rt['icon']     = (array_key_exists('icon', $root)) ? $root['icon'] : null;
		$rt['openicon'] = (array_key_exists('openicon', $root)) ? $root['openicon'] : null;
		$rt['data']     = (array_key_exists('data', $root)) ? $root['data'] : null;
		$rootNode = JHtml::getJSObject($rt);

		$treeName = (array_key_exists('treeName', $params)) ? $params['treeName'] : '';

		$js = '		window.addEvent(\'domready\', function(){
			tree' . $treeName . ' = new MooTreeControl(' . $options . ',' . $rootNode . ');
			tree' . $treeName . '.adopt(\'' . $id . '\');})';

		// Attach tooltips to document
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);

		// Set static array
		self::$loaded[__METHOD__][$id] = true;

		return;
	}

	/**
	 * Add unobtrusive javascript support for a calendar control.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function calendar()
	{
		// Only load once
		if (isset(self::$loaded[__METHOD__]))
		{
			return;
		}

		$document = JFactory::getDocument();
		$tag = JFactory::getLanguage()->getTag();

		JHtml::_('stylesheet', 'system/calendar-jos.css', array(' title' => JText::_('JLIB_HTML_BEHAVIOR_GREEN'), ' media' => 'all'), true);
		JHtml::_('script', $tag . '/calendar.js', false, true);
		JHtml::_('script', $tag . '/calendar-setup.js', false, true);

		$translation = self::_calendartranslation();

		if ($translation)
		{
			$document->addScriptDeclaration($translation);
		}
		self::$loaded[__METHOD__] = true;
	}

	/**
	 * Add unobtrusive javascript support for a color picker.
	 *
	 * @return  void
	 *
	 * @since   11.2
	 */
	public static function colorpicker()
	{
		// Only load once
		if (isset(self::$loaded[__METHOD__]))
		{
			return;
		}

		// Include jQuery
		JHtml::_('jquery.framework');

		JHtml::_('script', 'jui/jquery.minicolors.min.js', false, true);
		JHtml::_('stylesheet', 'jui/jquery.minicolors.css', false, true);
		JFactory::getDocument()->addScriptDeclaration("
				jQuery(document).ready(function (){
					jQuery('.minicolors').each(function() {
						jQuery(this).minicolors({
							control: jQuery(this).attr('data-control') || 'hue',
							position: jQuery(this).attr('data-position') || 'right',
							theme: 'bootstrap'
						});
					});
				});
			"
		);

		self::$loaded[__METHOD__] = true;
	}

	/**
	 * Add unobtrusive javascript support for a simple color picker.
	 *
	 * @return  void
	 *
	 * @since   11.2
	 */
	public static function simplecolorpicker()
	{
		// Only load once
		if (isset(self::$loaded[__METHOD__]))
		{
			return;
		}

		// Include jQuery
		JHtml::_('jquery.framework');

		JHtml::_('script', 'jui/jquery.simplecolors.min.js', false, true);
		JHtml::_('stylesheet', 'jui/jquery.simplecolors.css', false, true);
		JFactory::getDocument()->addScriptDeclaration("
				jQuery(document).ready(function (){
					jQuery('select.simplecolors').simplecolors();
				});
			"
		);

		self::$loaded[__METHOD__] = true;
	}

	/**
	 * Keep session alive, for example, while editing or creating an article.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function keepalive()
	{
		// Only load once
		if (isset(self::$loaded[__METHOD__]))
		{
			return;
		}

		// Include MooTools framework
		self::framework();

		$config = JFactory::getConfig();
		$lifetime = ($config->get('lifetime') * 60000);
		$refreshTime = ($lifetime <= 60000) ? 30000 : $lifetime - 60000;

		// Refresh time is 1 minute less than the liftime assined in the configuration.php file.

		// The longest refresh period is one hour to prevent integer overflow.
		if ($refreshTime > 3600000 || $refreshTime <= 0)
		{
			$refreshTime = 3600000;
		}

		$document = JFactory::getDocument();
		$script = '';
		$script .= 'function keepAlive() {';
		$script .= '	var myAjax = new Request({method: "get", url: "index.php"}).send();';
		$script .= '}';
		$script .= ' window.addEvent("domready", function()';
		$script .= '{ keepAlive.periodical(' . $refreshTime . '); }';
		$script .= ');';

		$document->addScriptDeclaration($script);
		self::$loaded[__METHOD__] = true;

		return;
	}

	/**
	 * Highlight some words via Javascript.
	 *
	 * @param   array   $terms      Array of words that should be highlighted.
	 * @param   string  $start      ID of the element that marks the begin of the section in which words
	 *                              should be highlighted. Note this element will be removed from the DOM.
	 * @param   string  $end        ID of the element that end this section.
	 *                              Note this element will be removed from the DOM.
	 * @param   string  $className  Class name of the element highlights are wrapped in.
	 * @param   string  $tag        Tag that will be used to wrap the highlighted words.
	 *
	 * @return  void
	 *
	 * @since   11.4
	 */
	public static function highlighter(array $terms, $start = 'highlighter-start', $end = 'highlighter-end', $className = 'highlight', $tag = 'span')
	{
		$sig = md5(serialize(array($terms, $start, $end)));

		if (isset(self::$loaded[__METHOD__][$sig]))
		{
			return;
		}

		JHtml::_('script', 'system/highlighter.js', true, true);

		$terms = str_replace('"', '\"', $terms);

		$document = JFactory::getDocument();
		$document->addScriptDeclaration("
			window.addEvent('domready', function () {
				var start = document.id('" . $start . "');
				var end = document.id('" . $end . "');
				if (!start || !end || !Joomla.Highlighter) {
					return true;
				}
				highlighter = new Joomla.Highlighter({
					startElement: start,
					endElement: end,
					className: '" . $className . "',
					onlyWords: false,
					tag: '" . $tag . "'
				}).highlight([\"" . implode('","', $terms) . "\"]);
				start.dispose();
				end.dispose();
			});
		");

		self::$loaded[__METHOD__][$sig] = true;

		return;
	}

	/**
	 * Break us out of any containing iframes
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public static function noframes()
	{
		// Only load once
		if (isset(self::$loaded[__METHOD__]))
		{
			return;
		}

		// Include MooTools framework
		self::framework();

		$js = "window.addEvent('domready', function () {if (top == self) {document.documentElement.style.display = 'block'; }" .
			" else {top.location = self.location; }});";
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('html { display:none }');
		$document->addScriptDeclaration($js);

		JResponse::setHeader('X-Frames-Options', 'SAME-ORIGIN');

		self::$loaded[__METHOD__] = true;
	}

	/**
	 * Internal method to get a JavaScript object notation string from an array
	 *
	 * @param   array  $array  The array to convert to JavaScript object notation
	 *
	 * @return  string  JavaScript object notation representation of the array
	 *
	 * @since   11.1
	 * @deprecated  13.3 Use JHtml::getJSObject() instead.
	 */
	protected static function _getJSObject($array = array())
	{
		JLog::add('JHtmlBehavior::_getJSObject() is deprecated. JHtml::getJSObject() instead..', JLog::WARNING, 'deprecated');

		JHtml::getJSObject($array);
	}

	/**
	 * Internal method to translate the JavaScript Calendar
	 *
	 * @return  string  JavaScript that translates the object
	 *
	 * @since   11.1
	 */
	protected static function _calendartranslation()
	{
		static $jsscript = 0;

		// Guard clause, avoids unnecessary nesting
		if ($jsscript)
		{
			return false;
		}

		$jsscript = 1;

		// To keep the code simple here, run strings through JText::_() using array_map()
		$callback = array('JText','_');
		$weekdays_full = array_map(
			$callback, array(
				'SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'
			)
		);
		$weekdays_short = array_map(
			$callback,
			array(
				'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'
			)
		);
		$months_long = array_map(
			$callback, array(
				'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE',
				'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'
			)
		);
		$months_short = array_map(
			$callback, array(
				'JANUARY_SHORT', 'FEBRUARY_SHORT', 'MARCH_SHORT', 'APRIL_SHORT', 'MAY_SHORT', 'JUNE_SHORT',
				'JULY_SHORT', 'AUGUST_SHORT', 'SEPTEMBER_SHORT', 'OCTOBER_SHORT', 'NOVEMBER_SHORT', 'DECEMBER_SHORT'
			)
		);

		// This will become an object in Javascript but define it first in PHP for readability
		$text = array(
			'INFO'			=> JText::_('JLIB_HTML_BEHAVIOR_ABOUT_THE_CALENDAR'),

			'ABOUT'			=> "DHTML Date/Time Selector\n"
				. "(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n"
				. "For latest version visit: http://www.dynarch.com/projects/calendar/\n"
				. "Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details."
				. "\n\n"
				. JText::_('JLIB_HTML_BEHAVIOR_DATE_SELECTION')
				. JText::_('JLIB_HTML_BEHAVIOR_YEAR_SELECT')
				. JText::_('JLIB_HTML_BEHAVIOR_MONTH_SELECT')
				. JText::_('JLIB_HTML_BEHAVIOR_HOLD_MOUSE'),

			'ABOUT_TIME'	=> "\n\n"
				. "Time selection:\n"
				. "- Click on any of the time parts to increase it\n"
				. "- or Shift-click to decrease it\n"
				. "- or click and drag for faster selection.",

			'PREV_YEAR'		=> JText::_('JLIB_HTML_BEHAVIOR_PREV_YEAR_HOLD_FOR_MENU'),
			'PREV_MONTH'	=> JText::_('JLIB_HTML_BEHAVIOR_PREV_MONTH_HOLD_FOR_MENU'),
			'GO_TODAY'		=> JText::_('JLIB_HTML_BEHAVIOR_GO_TODAY'),
			'NEXT_MONTH'	=> JText::_('JLIB_HTML_BEHAVIOR_NEXT_MONTH_HOLD_FOR_MENU'),
			'SEL_DATE'		=> JText::_('JLIB_HTML_BEHAVIOR_SELECT_DATE'),
			'DRAG_TO_MOVE'	=> JText::_('JLIB_HTML_BEHAVIOR_DRAG_TO_MOVE'),
			'PART_TODAY'	=> JText::_('JLIB_HTML_BEHAVIOR_TODAY'),
			'DAY_FIRST'		=> JText::_('JLIB_HTML_BEHAVIOR_DISPLAY_S_FIRST'),
			'WEEKEND'		=> "0,6",
			'CLOSE'			=> JText::_('JLIB_HTML_BEHAVIOR_CLOSE'),
			'TODAY'			=> JText::_('JLIB_HTML_BEHAVIOR_TODAY'),
			'TIME_PART'		=> JText::_('JLIB_HTML_BEHAVIOR_SHIFT_CLICK_OR_DRAG_TO_CHANGE_VALUE'),
			'DEF_DATE_FORMAT'	=> "%Y-%m-%d",
			'TT_DATE_FORMAT'	=> JText::_('JLIB_HTML_BEHAVIOR_TT_DATE_FORMAT'),
			'WK'			=> JText::_('JLIB_HTML_BEHAVIOR_WK'),
			'TIME'			=> JText::_('JLIB_HTML_BEHAVIOR_TIME')
		);

		return 'Calendar._DN = ' . json_encode($weekdays_full) . ';'
			. ' Calendar._SDN = ' . json_encode($weekdays_short) . ';'
			. ' Calendar._FD = 0;'
			. ' Calendar._MN = ' . json_encode($months_long) . ';'
			. ' Calendar._SMN = ' . json_encode($months_short) . ';'
			. ' Calendar._TT = ' . json_encode($text) . ';';
	}
}
