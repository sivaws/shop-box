<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	helpers/jblance.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */

// No direct access
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
require_once (JPATH_ROOT.'/components/com_jblance/defines.jblance.php');
/**
 * Jblance helper.
 */

function jbimport($path){
	require_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/'.str_replace( '.', '/', $path).'.php');
}

class JblanceHelper {
	
	public static function get($path){
		list($group, $class) = explode('.', $path);
		include_once($class.'.php');
		$className = ucfirst($class).ucfirst($group);
		if(!class_exists($className)) return null;
		return new $className();
	}
	
	public static function hasJBProfile($userid){
		$db = JFactory::getDBO();
		$query = "SELECT u.id FROM #__jblance_user u ".
				 "WHERE u.user_id = ".$db->quote($userid);
		$db->setQuery($query);
	
		if($db->loadResult())
			return 1;
		else
			return 0;
	}
	
	public static function getConfig(){
		$config = JTable::getInstance('config', 'Table');
		$config->load(1);
	
		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadString($config->params);
		$params = $registry->toObject();
		return $params;
	}
	
	public static function getLogo($userid, $att = ''){
		$db = JFactory::getDBO();
	
		//get the JoomBri picture
		$query = "SELECT picture FROM #__jblance_user WHERE user_id=".$db->quote($userid);
		$db->setQuery($query);
		$jbpic = $db->loadResult();
		
		$imgpath = JBPROFILE_PIC_PATH.'/'.$jbpic;
		$imgurl = JBPROFILE_PIC_URL.$jbpic.'?'.time();
		
		if(JFile::exists($imgpath)){
			return "<img src=$imgurl $att alt='img'>";
		}
		elseif($userid){
			$imgurl = JURI::root().'components/com_jblance/images/nophoto_big.png';
			return "<img src=$imgurl $att alt='img'>";
		}
	}
	
	public static function getThumbnail($userid, $att = ''){
		$avatars = self::getAvatarIntegration();
		return $avatars->getLink($userid, $att);
	}
	
	//return true if the user group id is set to free mode.
	public static function isFreeMode($ugid){
		$db	= JFactory::getDBO();
		$query = "SELECT freeMode FROM `#__jblance_usergroup` WHERE id=".$db->quote($ugid);
		$db->setQuery($query);
		$freeMode = $db->loadResult();
		return $freeMode;
	}
	
	public static function getTooltip(){
		$toolTipArray = array('className' => 'jbtooltip');
		JHTML::_('behavior.tooltip', '.jbtooltip', $toolTipArray);
	}
	
	public static function getGwayName($gwCode){
		if($gwCode != 'byadmin'){
			$db = JFactory::getDBO();
			$query = "SELECT gateway_name FROM #__jblance_paymode WHERE gwcode=".$db->quote($gwCode);
			$db->setQuery($query);
			$gwayName = $db->loadResult();
		}
		else
			$gwayName = 'By Admin';
	
		return $gwayName;
	}
	
	public static function getPaymodeInfo($gwCode){
		if($gwCode != 'byadmin'){
			$db = JFactory::getDBO();
			$query = "SELECT * FROM #__jblance_paymode WHERE gwcode=".$db->quote($gwCode);
			$db->setQuery($query);
			$config = $db->loadObject();
			
			//convert the params to object
			$registry = new JRegistry;
			$registry->loadString($config->params);
			$params = $registry->toObject();
			
			//bind the $params object to $plan and make one object
			foreach($params as $k => $v){
				$config->$k = $v;
			}
		
			return $config;
		}
		else
			return 'By Admin';
	
	}
	
	/**
	 * Update the transaction of the users to the transaction table (#__jblance_transaction)
	 * @param int $userid
	 * @param string $transDtl
	 * @param int $amount
	 * @param int $plusMinus
	 */
	public static function updateTransaction($userid, $transDtl, $amount, $plusMinus){
		$app = JFactory::getApplication();
		$now = JFactory::getDate();
		//Insert the transaction into the transaction table in case the amount is greater than zero
		if($amount > 0){
			$row_trans	= JTable::getInstance('transaction', 'Table');
			$row_trans->date_trans  = $now->toSql();
			$row_trans->transaction = $transDtl;
			$row_trans->user_id  = $userid;
	
			if($plusMinus == 1)
				$row_trans->fund_plus = $amount;
			elseif($plusMinus == -1)
				$row_trans->fund_minus = $amount;
	
			// pre-save checks
			if(!$row_trans->check()) {
				JError::raiseError(500, $row_trans->getError());
			}
			if(!$row_trans->store()){
				JError::raiseError(500, $row_trans->getError());
			}
			$row_trans->checkin();
			return $row_trans;
		}
	}
	
	public static function getPaymentStatus($approved){
		$lang = JFactory::getLanguage();
		$lang->load('com_jblance', JPATH_SITE);
	
		if($approved == 0)
			$status = '<span class="bluefont">'.JText::_('COM_JBLANCE_PAYMENT_PENDING').'</span>';
		elseif($approved == 1)
			$status = '<span class="greenfont">'.JText::_('COM_JBLANCE_COMPLETED').'</span>';
		elseif($approved == 2)
			$status = '<span class="redfont">'.JText::_('COM_JBLANCE_CANCELLED').'</span>';
		return $status;
	}
	
	public static function getApproveStatus($approved){
		$lang = JFactory::getLanguage();
		$lang->load('com_jblance', JPATH_SITE);
	
		if($approved == 0)
			$status = '<span class="bluefont">'.JText::_('COM_JBLANCE_PENDING').'</span>';
		elseif($approved == 1)
			$status = '<span class="greenfont">'.JText::_('COM_JBLANCE_APPROVED').'</span>';
		return $status;
	}
	
	//get the total available fund of the user
	public static function getTotalFund($userid){
		$db	= JFactory::getDBO();
		$total_fund = 0;
		$query = "SELECT (SUM(fund_plus)-SUM(fund_minus)) FROM #__jblance_transaction WHERE user_id = ".$db->quote($userid);
		$db->setQuery($query);
		$total_fund = $db->loadResult();
		return $total_fund;
	}
	
	public static function isAuthenticated($userid, $layout){
		$app = JFactory::getApplication();
		$config = JblanceHelper::getConfig();
		$guestReporting = $config->enableGuestReporting;
	
		$noLoginLayouts = array('planadd', 'check_out', 'bank_transfer', 'listproject', 'detailproject', 'searchproject', 'userlist');	//these are the layouts that doesn't require login
		
		//if the guest reporting is enabled, then set the report layout to nologin layouts
		if($guestReporting)
			$noLoginLayouts[] = 'report';
		
		if(in_array($layout, $noLoginLayouts)){
			return true;
		}
	
		//if the user is not logged in
		if($userid == 0){
			//return to same page after login
			$returnUrl = JFactory::getURI()->toString();
			$msg = JText::_('COM_JBLANCE_MUST_BE_LOGGED_IN_TO_ACCESS_THIS_PAGE');
			$link_login  = JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($returnUrl), false);
			$app->redirect($link_login, $msg);
		}
		if(self::hasJBProfile($userid)){
			//check if the user is authorized to do an action/section
			$isAuthorized = self::isAuthorized($userid, $layout);
			if(!$isAuthorized){
				$msg = JText::_('COM_JBLANCE_NOT_AUTHORIZED_TO_ACCESS_THIS_PAGE');
				$return	= JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
				$app->redirect($return, $msg);
			}
		}
		else {
			$msg = JText::_('COM_JBLANCE_NOT_AUTHORIZED_TO_ACCESS_THIS_PAGE_CHOOSE_YOUR_ROLE');
			$return	= JRoute::_('index.php?option=com_jblance&view=guest&layout=showfront', false);
			$app->redirect($return, $msg);
		}
	
	}
	
	public static function isAuthorized($userid, $layout){
		$jbuser = self::get('helper.user');
		$ugInfo = $jbuser->getUserGroupInfo($userid, null);
	
		//get the array of layouts the current user is not authorized
		$denied = self::deniedLayouts($userid);
	
		if(in_array($layout, $denied))
			return 0;	//denied
		else
			return 1;	//allowed
	}
	
	public static function deniedLayouts($userid){
		$jbuser 				= self::get('helper.user');
		$ugInfo 				= $jbuser->getUserGroupInfo($userid, null);
		$config 				= JblanceHelper::getConfig();
		$enableEscrowPayment 	= $config->enableEscrowPayment;
 		$enableWithdrawFund 	= $config->enableWithdrawFund;
		$deniedLayouts 			= array();
	
		/* //if the user group is in free-Mode, then set the following layouts as denied ones
		if(JBLANCE_FREE_MODE){
			$deniedLayouts[] = 'buycredit';
			$deniedLayouts[] = 'planadd';
			$deniedLayouts[] = 'planhistory';
			$deniedLayouts[] = 'showcredit';
		} */
		//check if escrow payment is enabled
		if(!$enableEscrowPayment){
			$deniedLayouts[] = 'escrow';
		}
		//check if fund withdraw is enabled
		if(!$enableWithdrawFund){
			$deniedLayouts[] = 'withdrawfund';
		}
	
		//get the array of layouts the current user is not authorized
		if(!$ugInfo->allowPostProjects){
			$deniedLayouts[] = 'showmyproject';
			$deniedLayouts[] = 'editproject';
			$deniedLayouts[] = 'pickuser';
		}
		if(!$ugInfo->allowBidProjects){
			$deniedLayouts[] = 'showmybid';
			$deniedLayouts[] = 'placebid';
		}
	
		return $deniedLayouts;
	}
	
	public static function getCategoryNames($id_categs){
		$db = JFactory::getDBO();
		$query = "SELECT category,id FROM #__jblance_category c WHERE c.id IN ($id_categs)";
		$db->setQuery($query);
		$cats = $db->loadColumn();
		if($cats)
			return implode($cats, ", ");
		else 
			return '';
	}
	
	public static function getAvarageRate($userid, $html = true){
		$db = JFactory::getDBO();
		$lang = JFactory::getLanguage();
		$lang->load('com_jblance', JPATH_SITE);
		
		//get the average rating value
		$query = "SELECT AVG((quality_clarity+communicate+expertise_payment+professional+hire_work_again)/5) AS rating FROM #__jblance_rating ".
				 "WHERE target=".$db->quote($userid)." AND quality_clarity <> 0";
		$db->setQuery($query);
		$avg = $db->loadResult();
		$avg = round($avg, 2);
		
		//get the no of rating the user has received
		$query = "SELECT COUNT(*) AS count FROM #__jblance_rating ".
				"WHERE target=".$db->quote($userid)." AND quality_clarity <> 0";
		$db->setQuery($query);
		$count = $db->loadResult();
		
		if($html == false){
			return $avg;
		}
		else { 
			JHTML::_('behavior.tooltip');
		?>
		<div class="rating_bar hasTip" title="<?php echo JText::sprintf('COM_JBLANCE_RATING_VALUE_TOOLTIP', $avg); ?>">
			<div style="width:<?php echo $avg*10*2; ?>%" class=""><!-- convert the rating into percent --></div>
		</div>
		<div>(<?php echo JText::sprintf('COM_JBLANCE_COUNT_REVIEWS', $count); ?>)</div>
		<?php
		return $avg;
		}
	}
	
	public static function getUserRateProject($userid, $projectid){
		$db = JFactory::getDBO();
		$query = "SELECT (quality_clarity+communicate+expertise_payment+professional+hire_work_again)/5 AS rating FROM #__jblance_rating ".
				 "WHERE target=".$userid." AND project_id = ".$projectid;
		$db->setQuery($query);
		$rating = $db->loadResult();
		$rating = round($rating, 2);
		return $rating;
	}
	
	public static function getRatingHTML($rate, $tooltip=''){
		$rate = round($rate, 1);
		JHtml::_('behavior.tooltip');
	?>
		<div class="rating_bar fl">
			<div style="width:<?php echo $rate*10*2; ?>%" class=""><!-- convert the rating into percent --></div>
		</div>
		<div class="fl"><?php echo "(".number_format($rate, 1).")"; ?></div>
		<div class="clearfix"></div>
	<?php
	}
	
	//2.Which Plan to Use?
	public static function whichPlan($userid = null){
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$is_expired = false;
	
		$jbuser = self::get('helper.user');
		$ug_id = $jbuser->getUserGroupInfo($userid, null)->id;
	
		$query = "SELECT MAX(id) FROM #__jblance_plan_subscr WHERE user_id = $userid AND approved = 1";
		$db->setQuery($query);
		$id_max = $db->loadResult();
	
		if($id_max){
			//check if the plan is expired or not
			$query = 'SELECT (TO_DAYS(s.date_expire) - TO_DAYS(NOW())) daysleft FROM  #__jblance_plan_subscr s WHERE s.id='.$id_max;
			$db->setQuery($query);
			$days_left = $db->loadResult();
	
			if($days_left < 0)
				$is_expired = true;
		}
	
		if(!$id_max || $is_expired){	//user has no active plan or it is expired. so choose the default plan (free plan)
			$query = "SELECT * FROM #__jblance_plan WHERE `default_plan` = 1 AND ug_id=".$db->quote($ug_id);
		}
		else {
			$query = "SELECT * FROM #__jblance_plan WHERE id = (
			SELECT plan_id FROM #__jblance_plan_subscr WHERE id = ".$db->quote($id_max)." )";
		}
		$db->setQuery($query);
		$plan = $db->loadObject();
	
		//convert the params to object
		$registry = new JRegistry;
		$registry->loadString($plan->params);
		$params = $registry->toObject();
	
		//bind the $params object to $plan and make one object
		foreach ($params as $k => $v){
			$plan->$k = $v;
		}
	
		return $plan;
	}
		
	public static function countUnreadMsg($msgid = 0){
		$db = JFactory::getDBO();
		$user	= JFactory::getUser();
		
		if($msgid > 0)
			$query = "SELECT COUNT(is_read) isRead FROM #__jblance_message WHERE idTo=$user->id AND (id=$msgid OR parent=$msgid) AND is_read=0 AND deleted=0";
		else 
			$query = "SELECT COUNT(is_read) isRead FROM #__jblance_message WHERE idTo=$user->id AND is_read=0 AND deleted=0";
			
		$db->setQuery($query);
		$total 	= $db->loadResult();
		return $total;
	}
	
	/**
	 * Get JoomBri profile integration object
	 *
	 * Returns the global {@link JoombriProfile} object, only creating it if it doesn't already exist.
	 *
	 * @return object JoombriProfile
	 */
	public static function getProfile(){
		jbimport('integration.profile');
		return JoombriProfile::getInstance();
	}
	
	/**
	 * Get Joombri avatar integration object
	 *
	 * Returns the global {@link JoombriAvatar} object, only creating it if it doesn't already exist.
	 *
	 * @return object JoombriAvatar
	 */
	public static function getAvatarIntegration(){
		jbimport('integration.avatar');
		return JoombriAvatar::getInstance();
	}

	/**
	 * Return the amount formatted with currency symbol and/or code
	 * 
	 * @param float $amount Amount to be formatted
	 * @param boolean $setCurrencySymbol Prefix currency symbol 
	 * @param boolean $setCurrencyCode Suffix currency code
	 * @param integer $decimal No of decimal points
	 * @return string Formatted currency
	 */
	public static function formatCurrency($amount, $setCurrencySymbol = true, $setCurrencyCode = false, $decimal = 2){
		
		$config 	= self::getConfig();
		$currencySym = $config->currencySymbol;
		$currencyCod = $config->currencyCode;
		$formatted = number_format($amount, $decimal, '.', ',');
		
		if($setCurrencySymbol)
			$formatted = $currencySym.' '.$formatted;
		
		if($setCurrencyCode)
			$formatted .= ' '.$currencyCod;
		
		return $formatted;
	}
	
	public static function showRemainingDHM($endDate, $type = 'LONG'){
		
		$now = JFactory::getDate();
		$diff = self::dateTimeDiff($now, $endDate);//print_r($diff);
		
		if($now > $endDate)
			return JText::_('COM_JBLANCE_PROJECT_EXPIRED_'.$type);
		
		if($diff->y > 0)
			$formatted = JText::sprintf('COM_JBLANCE_YEAR_MONTHS_'.$type,$diff->y, $diff->m);
		elseif($diff->m > 0)
			$formatted = JText::sprintf('COM_JBLANCE_MONTHS_DAYS_'.$type,$diff->m, $diff->d);
		elseif($diff->d > 0)
			$formatted = JText::sprintf('COM_JBLANCE_DAYS_HOURS_'.$type,$diff->d, $diff->h);
		elseif($diff->h > 0)
			$formatted = JText::sprintf('COM_JBLANCE_HOURS_MINUTES_'.$type, $diff->h, $diff->i);
		elseif($diff->i > 0)
			$formatted = JText::sprintf('COM_JBLANCE_MINUTES_SECS_'.$type, $diff->i, $diff->s);
		else
			$formatted = JText::sprintf('COM_JBLANCE_SECS_'.$type, $diff->s);
		
		return $formatted;
	}
	
	public static function showTimePastDHM($startDate, $type = 'LONG'){
		
		$now = JFactory::getDate();
		$diff = self::dateTimeDiff($now, $startDate);//print_r($diff);
		
		if($diff->y > 0)
			$formatted = JText::sprintf('COM_JBLANCE_YEAR_MONTHS_'.$type,$diff->y, $diff->m);
		elseif($diff->m > 0)
			$formatted = JText::sprintf('COM_JBLANCE_MONTHS_DAYS_'.$type,$diff->m, $diff->d);
		elseif($diff->d > 0)
			$formatted = JText::sprintf('COM_JBLANCE_DAYS_HOURS_'.$type,$diff->d, $diff->h);
		elseif($diff->h > 0)
			$formatted = JText::sprintf('COM_JBLANCE_HOURS_MINUTES_'.$type, $diff->h, $diff->i);
		elseif($diff->i > 0)
			$formatted = JText::sprintf('COM_JBLANCE_MINUTES_SECS_'.$type, $diff->i, $diff->s);
		else
			$formatted = JText::sprintf('COM_JBLANCE_SECS_'.$type, $diff->s);
		
		$formatted .= ' '.JText::_('COM_JBLANCE_AGO');
		
		return $formatted;
	}
	
	/**
	 * @param date $fromdate
	 * @param date $toDate
	 * @return stdClass
	 */
	public static function dateTimeDiff($fromdate, $todate){
	
		$alt_diff = new stdClass();
		$alt_diff->y =  floor(abs($fromdate->format('U') - $todate->format('U')) / (60*60*24*365));
		$alt_diff->m =  floor((floor(abs($fromdate->format('U') - $todate->format('U')) / (60*60*24)) - ($alt_diff->y * 365))/30);
		$alt_diff->d =  floor(floor(abs($fromdate->format('U') - $todate->format('U')) / (60*60*24)) - ($alt_diff->y * 365) - ($alt_diff->m * 30));
		$alt_diff->h =  floor( floor(abs($fromdate->format('U') - $todate->format('U')) / (60*60)) - ($alt_diff->y * 365*24) - ($alt_diff->m * 30 * 24 )  - ($alt_diff->d * 24) );
		$alt_diff->i = floor( floor(abs($fromdate->format('U') - $todate->format('U')) / (60)) - ($alt_diff->y * 365*24*60) - ($alt_diff->m * 30 * 24 *60)  - ($alt_diff->d * 24 * 60) -  ($alt_diff->h * 60) );
		$alt_diff->s =  floor( floor(abs($fromdate->format('U') - $todate->format('U'))) - ($alt_diff->y * 365*24*60*60) - ($alt_diff->m * 30 * 24 *60*60)  - ($alt_diff->d * 24 * 60*60) -  ($alt_diff->h * 60*60) -  ($alt_diff->i * 60) );
		$alt_diff->invert =  (($fromdate->format('U') - $todate->format('U')) > 0)? 0 : 1 ;
		
		/* $alt_diff->d =  floor(floor(abs($fromdate->format('U') - $todate->format('U')) / (60*60*24)) );
		$alt_diff->h =  floor( floor(abs($fromdate->format('U') - $todate->format('U')) / (60*60))  - ($alt_diff->d * 24) );
		$alt_diff->i = floor( floor(abs($fromdate->format('U') - $todate->format('U')) / (60)) -  ($alt_diff->d * 24 * 60) -  ($alt_diff->h * 60) );
		$alt_diff->s =  floor( floor(abs($fromdate->format('U') - $todate->format('U'))) -  ($alt_diff->d * 24 * 60*60) -  ($alt_diff->h * 60*60) -  ($alt_diff->i * 60) );
		$alt_diff->invert =  (($fromdate->format('U') - $todate->format('U')) > 0)? 0 : 1 ; */
	
		return $alt_diff;
	}
	
	public static function getFeeds($limit, $notify = ''){
	
		$user 	= JFactory::getUser();
		$feeds  = self::get('helper.feeds');		// create an instance of the class FeedsHelper
	
		$feeds =  $feeds->getFeedsData($user->id, $limit, $notify);
		return $feeds;
	}
	
	public static function parseTitle($title){
		$title = str_replace(array(" ", "&", "`", "~", "!", "@", "#", "$", "%", "^", "*", "(", ")", "+", "_", "=", "{", "}", "[", "]", ":", ";", "'", "\"", "<", ">", ",", ".", "/", "?"), "-", strip_tags(strtolower($title)));
		for($n=1; $n<=10; $n++){
			$title = str_replace(array("--", "---", "----"), "-", $title);
	
			if(substr($title, 0, 1) == "-")
				$title = substr($title, 1);
	
			if(substr($title, -1, 1) == "-")
				$title = substr($title, 0, -1);
		}
		return $title;
	}
	
	public static function getMultiSelect($element, $title){
		$doc = JFactory::getDocument();
 		$doc->addScript(JURI::root()."components/com_jblance/js/multipleSelectFilter.js");
 		$doc->addStyleSheet(JURI::root()."components/com_jblance/css/multipleSelect.css");
 		
 		$js = "
 				window.addEvent('domready', function() {
					if($('$element')){
						var mySelect = new multipleSelectFilter('$element', {
							'initLength':'200',
							'initialTxt':'$title',
							//'minChars': '1',
							'charWidth':'8'
						});
					}
				});
 		";
 		$doc->addScriptDeclaration($js);
	}
	
	public static function getTableClassName(){
		if(version_compare(JVERSION, '1.6.0', 'ge')  && version_compare(JVERSION, '3.0.0', 'lt')) 
 			return 'adminlist';
 		elseif(version_compare(JVERSION, '3.0.0', 'ge'))
 			return 'table table-striped';
	}
	
	/**
	 * Identify whether the order is deposit or plan purchase
	 * 
	 * @param string $invoice_num
	 * @return string
	 */
	public static function identifyDepositOrPlan($invoice_num){
		$db = JFactory::getDbo();
		$type = '';
		$return = array();
		
		// search for invoice number in plan subscription table
		$query = "SELECT id FROM #__jblance_plan_subscr p WHERE p.invoiceNo = ".$db->quote($invoice_num);
		$db->setQuery($query);
		$result = $db->loadResult();
		
		//if result is empty search for invoice number in deposit table
		if($result){
			$return['type'] = 'plan';
			$return['id'] = $result;
		}
		else {
			$query = "SELECT id FROM #__jblance_deposit d WHERE d.invoiceNo = ".$db->quote($invoice_num);
			$db->setQuery($query);
			$result = $db->loadResult();
			if($result){
				$return['type'] = 'deposit';
				$return['id'] = $result;
			}
			else {
				$return = array();
			}
		}
		return $return;
	}
	
	public static function approveSubscription($subscrid){
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$row	= JTable::getInstance('plansubscr', 'Table');
		$row->load($subscrid);
	
		$query = "SELECT p.* FROM #__jblance_plan p WHERE p.id=".$row->plan_id;
		$db->setQuery($query);
		$plan = $db->loadObject();
	
		// Update the transaction table if not approved
		if(!$row->approved){
			$transDtl = JText::_('COM_JBLANCE_BUY_SUBSCR').' - '.$plan->name;
			$row_trans = JblanceHelper::updateTransaction($row->user_id, $transDtl, $row->fund, 1);
	
			//save status subscription "approved"
			$now = JFactory::getDate();
			$date_approve = $now->toSql();
			$now->modify("+$plan->days $plan->days_type");
			$date_expires = $now->toSql();
	
			$row->approved = 1;
			$row->date_approval = $date_approve;
			$row->date_expire = $date_expires;
			$row->gateway_id = time();
			$row->trans_id = $row_trans->id;
			$row->access_count = 1;
	
			if(!$row->check())
				JError::raiseError(500, $row->getError());
	
			if(!$row->store())
				JError::raiseError(500, $row->getError());
	
			$row->checkin();
	
			$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
			$jbmail->alertAdminSubscr($row->id, $row->user_id);
			$jbmail->alertUserSubscr($row->id, $row->user_id);
			
			return $row;
		}
	}
	
	
	function approveFundDeposit($deposit_id){
		$row = JTable::getInstance('deposit', 'Table');
		$row->load($deposit_id);
	
		// Update the transaction table if not approved
		if(!$row->approved){
			$transDtl = JText::_('COM_JBLANCE_DEPOSIT_FUNDS');
			$row_trans = JblanceHelper::updateTransaction($row->user_id, $transDtl, $row->amount, 1);
	
			//save status billing "approved"
			$now = JFactory::getDate();
			$date_approve = $now->toSql();
			$row->approved = 1;
			$row->date_approval = $date_approve;
			$row->trans_id = $row_trans->id;
	
			if(!$row->check())
				JError::raiseError(500, $row->getError());
	
			if(!$row->store())
				JError::raiseError(500, $row->getError());
	
			$row->checkin();
	
			$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
			$jbmail->sendAdminDepositFund($row->id);
			
			return $row;
		}
	}
	
	//3.Status of member's plan
	public static function planStatus($userid = null){
		$app = JFactory::getApplication();
		$db  = JFactory::getDBO();
		$now = JFactory::getDate();
	
		$query = "SELECT MAX(id) FROM #__jblance_plan_subscr WHERE approved=1 AND user_id=".$db->quote($userid);
		$db->setQuery($query);
		$id_max = $db->loadResult();
	
		if(!$id_max)	//user has no active plan. so choose the default plan (free plan)
			return 2;
	
		$query = "SELECT * FROM #__jblance_plan_subscr WHERE id=".$db->quote($id_max);
		$db->setQuery($query);
		$last_subscr = $db->loadObject();
	
		$query = "SELECT * FROM #__jblance_plan WHERE id=".$db->quote($last_subscr->plan_id);
		$db->setQuery($query);
		$last_plan = $db->loadObject();
	
		if($now > $last_subscr->date_expire)
			return 1;	// The user's subscr has expired
		else
			return null;
	}
	
	public static function processMessage(){
		$app  	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$msgid 	= $app->input->get('msgid', '', 'int');
		
		$query = "UPDATE #__jblance_message SET deleted=1 WHERE id=".$msgid." OR parent=".$msgid;
		$db->setQuery($query);
		$db->execute();
		
		echo 'OK';
		exit;
	}
	
	public static function getProgressBar($currStep=0){
	
		$totalStep = self::getTotalSteps();
		$width = intval(($currStep/$totalStep) * 100) ;
	
		$html = '<div class="progress progress-striped">'.
				'<div class="bar" style="width:'.$width.'%;"></div>'.
				'</div>';
		return $html;
	}
	
	public static function getTotalSteps(){
		$user = JFactory::getUser();
		$session 	= JFactory::getSession();
		$skipPlan 	= $session->get('skipPlan', 0, 'register');
		$total = 4;
		
		if($user->guest){
			if($skipPlan)
				$total -= 1;
		}
		else {
			if($skipPlan)
				$total -= 2;
			else 
				$total -= 1;
				
		}
		return $total;
	}
	
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = ''){

		JSubMenuHelper::addEntry(JText::_('COM_JBLANCE_TITLE_DASHBOARD'), 'index.php?option=com_jblance&view=admproject&layout=dashboard', $vName == 'dashboard');
		JSubMenuHelper::addEntry(JText::_('COM_JBLANCE_TITLE_PROJECTS'), 'index.php?option=com_jblance&view=admproject&layout=showproject', $vName == 'showproject');
		JSubMenuHelper::addEntry(JText::_('COM_JBLANCE_TITLE_USERS'), 'index.php?option=com_jblance&view=admproject&layout=showuser', $vName == 'showuser');
		JSubMenuHelper::addEntry(JText::_('COM_JBLANCE_TITLE_SUBSCRIPTIONS'), 'index.php?option=com_jblance&view=admproject&layout=showsubscr', $vName == 'showsubscr');
		JSubMenuHelper::addEntry(JText::_('COM_JBLANCE_TITLE_DEPOSITS'), 'index.php?option=com_jblance&view=admproject&layout=showdeposit', $vName == 'showdeposit');
		JSubMenuHelper::addEntry(JText::_('COM_JBLANCE_TITLE_WITHDRAWALS'), 'index.php?option=com_jblance&view=admproject&layout=showwithdraw', $vName == 'showwithdraw');
		JSubMenuHelper::addEntry(JText::_('COM_JBLANCE_TITLE_ESCROWS'), 'index.php?option=com_jblance&view=admproject&layout=showescrow', $vName == 'showescrow');
		JSubMenuHelper::addEntry(JText::_('COM_JBLANCE_TITLE_REPORTINGS'), 'index.php?option=com_jblance&view=admproject&layout=showreporting', $vName == 'showreporting');
		JSubMenuHelper::addEntry(JText::_('COM_JBLANCE_TITLE_CONFIGURATION'), 'index.php?option=com_jblance&view=admconfig&layout=configpanel', $vName == 'configpanel');
		JSubMenuHelper::addEntry(JText::_('COM_JBLANCE_TITLE_SUMMARY'), 'index.php?option=com_jblance&view=admproject&layout=showsummary', $vName == 'showsummary');
		JSubMenuHelper::addEntry(JText::_('COM_JBLANCE_TITLE_ABOUT'), 'index.php?option=com_jblance&view=admproject&layout=about', $vName == 'about');
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions(){
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_jblance';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	
	
}

class JBMediaHelper {
	
	public static function uploadFile($post, $project){
		$app 	= JFactory::getApplication();
		$projfile	= JTable::getInstance('projectfile', 'Table');
		
		//check if path exists, else create
		if(!file_exists(JBPROJECT_PATH)){
			if(mkdir(JBPROJECT_PATH)){
				JPath::setPermissions(JBPROJECT_PATH, '0777');
				if(file_exists(JPATH_SITE.'/images/index.html')){
					copy(JPATH_SITE.'/images/index.html', JBPROJECT_PATH.'/index.html');
				}
			}
		}
		
		//REMOVE THE FILES `IF` CHECKED
		$removeFiles = $app->input->get('file-id', null, 'array');
		if(!empty($removeFiles)){
			foreach($removeFiles as $removeFileId){
				$projfile->load($removeFileId);
				$old_doc = $projfile->file_name;
				$delete = JBPROJECT_PATH.'/'.$old_doc;
				unlink($delete);
				$projfile->delete($removeFileId);
			}
		}
		
		$uploadLimit = $post['uploadLimit'];
		for($i = 0; $i < $uploadLimit; $i++){
			$file = $_FILES['uploadFile'. $i];
		
			if($file['size'] > 0){
				//check if the resume file can be uploaded
				$err = null;
				if(!self::canUpload($file, $err, 'project', $project->id)){
					// The file can't be upload
					$app->enqueueMessage(JText::_($err).' - '.JText::sprintf('COM_JBLANCE_ERROR_FILE_NAME', $file['name']), 'error');
					continue;	//continues goes to the for loop but break breaks the for loop
				}
		
				self::uploadEachFile($file, $project, $projfile);
			}	// end of file size
		}	//upload file loop end
	}
	
	function uploadEachFile($file, $project, $projfile){
		//get the new file name
		$new_doc = "proj_".$project->id."_".strtotime("now")."_".$file['name'];
		$new_doc = preg_replace('/[[:space:]]/', '_',$new_doc);	//replace space in the file name with _
		$new_doc = JFile::makeSafe($new_doc);
		$dest = JBPROJECT_PATH.'/'.$new_doc;
		$soure = $file['tmp_name'];
		// Move uploaded file
		$uploaded = JFile::upload($soure, $dest);
		
		$projfile->id = 0;
		$projfile->project_id = $project->id;
		$projfile->file_name = $new_doc;
		$projfile->show_name = JFile::makeSafe($file['name']);
		$projfile->hash = md5_file($file['tmp_name']);
		
		// pre-save checks
		if(!$projfile->check()){
			JError::raiseError(500, $projfile->getError());
		}
		// save the changes
		if(!$projfile->store()){
			JError::raiseError(500, $projfile->getError());
		}
		$projfile->checkin();
	}
	
	public static function messageAttachFile(){
		
		$response = array();
		$file = $_FILES['uploadmessage'];
		
		if($file['size'] > 0){
			//check if the resume file can be uploaded
			$err = null;
			if(!self::canUpload($file, $err, 'message', '')){
				// The file can't be upload
				$response['result'] = 'NO';
				$response['msg'] = $err;
				echo json_encode($response); exit;
			}
			
			if(!file_exists(JBMESSAGE_PATH)){
				if(mkdir(JBMESSAGE_PATH)){
					JPath::setPermissions(JBMESSAGE_PATH, '0777');
					if(file_exists(JPATH_SITE.'/images/index.html')){
						copy(JPATH_SITE.'/images/index.html', JBMESSAGE_PATH.'/index.html');
					}
				}
			}
		
			//get the new file name
			$new_doc = "msg_".strtotime("now")."_".$file['name'];
			$new_doc = preg_replace('/[[:space:]]/', '_', $new_doc);	//replace space in the file name with _
			$new_doc = JFile::makeSafe($new_doc);
			$dest = JBMESSAGE_PATH.'/'.$new_doc;
			$soure = $file['tmp_name'];
			// Move uploaded file
			$uploaded = JFile::upload($soure, $dest);
			
			$response['result'] = 'OK';
			$response['attachvalue'] = $file['name'].";".$new_doc;
			$response['attachname'] = $file['name'];
			$response['msg'] = JText::_('COM_JBLANCE_FILE_ATTACHED_SUCCESSFULLY');
			echo json_encode($response); exit;
		}
	}
	
	public static function portfolioAttachFile(){
		
		$app  = JFactory::getApplication();
		$elementID = $app->input->get('elementID', '', 'string');
	
		$response = array();
		$file = $_FILES[$elementID];
		
		//get the type whether portfolio image or file
		if($elementID == 'portfoliopicture'){
			$type = 'picture';
			$docPrefix = 'pic_';
		}
		elseif($elementID == 'portfolioattachment'){
			$type = 'project';
			$docPrefix = 'file_';
		}
	
		if($file['size'] > 0){
			//check if the resume file can be uploaded
			$err = null;
			if(!self::canUpload($file, $err, $type, '')){
				// The file can't be upload
				$response['result'] = 'NO';
				$response['msg'] = $err;
				echo json_encode($response); exit;
			}
	
			if(!file_exists(JBPORTFOLIO_PATH)){
				if(mkdir(JBPORTFOLIO_PATH)){
					JPath::setPermissions(JBPORTFOLIO_PATH, '0777');
					if(file_exists(JPATH_SITE.'/images/index.html')){
						copy(JPATH_SITE.'/images/index.html', JBPORTFOLIO_PATH.'/index.html');
					}
				}
			}
	
			//get the new file name
			$new_doc = $docPrefix.strtotime("now")."_".$file['name'];
			$new_doc = preg_replace('/[[:space:]]/', '_', $new_doc);	//replace space in the file name with _
			$new_doc = JFile::makeSafe($new_doc);
			$dest = JBPORTFOLIO_PATH.'/'.$new_doc;
			$soure = $file['tmp_name'];
			// Move uploaded file
			$uploaded = JFile::upload($soure, $dest);
	
			$response['result'] = 'OK';
			$response['attachvalue'] = $file['name'].";".$new_doc;
			$response['attachname'] = $file['name'];
			$response['msg'] = JText::_('COM_JBLANCE_FILE_ATTACHED_SUCCESSFULLY');
			echo json_encode($response); exit;
		}
	}
	
	public static function canUpload($file, &$err, $attachType, $projectId){
		
		$lang = JFactory::getLanguage();
		$lang->load('com_jblance', JPATH_SITE);
		$config = JblanceHelper::getConfig();
		$db 	= JFactory::getDBO();

		if($file['error'] != 0){
			$err = JText::_('COM_JBLANCE_UPLOAD_FILE_ERROR');
			return false;
		}

		if($attachType == 'project'){
			//check if the file type is allowed
			$type = $config->projectFileType;
			$allowed = explode(',', $type);
			$format = $file['type'];
			if(!preg_match('/(.*)\.(zip|docx)/', $file['name'])){
				if(!in_array($format, $allowed)){
					$err = JText::_('COM_JBLANCE_FILE_TYPE_NOT_ALLOWED');
					return false;
				}
			}
			//check for the maximum file size
			$maxSize = $config->projectMaxsize;
			if((int)$file['size'] / 1024 > $maxSize){
				$err = JText::sprintf('COM_JBLANCE_FILE_EXCEEDS_LIMIT', $maxSize);
				return false;
			}
			//check for max file count allowed per project
			$fileLimitConf = $config->projectMaxfileCount;
			$query	= "SELECT COUNT(f.id) FROM #__jblance_project_file f WHERE f.project_id='".$projectId."' AND f.is_nda_file=0";
			$db->setQuery($query);
			$fileCount = $db->loadResult();
			
			if($fileCount >= $fileLimitConf){
				$err = JText::sprintf('COM_JBLANCE_MAX_FILE_FOR_PROJECT_EXCEEDED_ALLOWED_COUNT', $fileLimitConf);
				return false;
			}
		}
		
		if($attachType == 'picture'){
			//check if the file type is allowed
			$type = $config->imgFileType;
			$allowed = explode(',', $type);
			$format = $file['type'];
			if(!in_array($format, $allowed)){
				$err = JText::_('COM_JBLANCE_FILE_TYPE_NOT_ALLOWED');
				return false;
			}
			//check for the maximum file size
			$maxSize = $config->imgMaxsize;
			if((int)$file['size'] / 1024 > $maxSize){
				$err = JText::sprintf('COM_JBLANCE_FILE_EXCEEDS_LIMIT', $maxSize);
				return false;
			}
		}
		
		if($attachType == 'message'){
			//check if the file type is allowed
			$type = $config->projectFileType;
			$allowed = explode(',', $type);
			$format = $file['type'];
			if(!preg_match('/(.*)\.(zip|docx)/', $file['name'])){
				if(!in_array($format, $allowed)){
					$err = JText::_('COM_JBLANCE_FILE_TYPE_NOT_ALLOWED');
					return false;
				}
			}
			//check for the maximum file size
			$maxSize = $config->projectMaxsize;
			if((int)$file['size'] / 1024 > $maxSize){
				$err = JText::sprintf('COM_JBLANCE_PICTURE_EXCEEDS_LIMIT', $maxsize);
				return false;
			}
		}
		
		if($attachType == 'video'){
			$type = 'video/x-flv';
			$allowed = explode(',', $type);
			$format = $file['type'];

			if(!in_array($format, $allowed)){
				$err = 'COM_JBLANCE_YOUR_FILE_IS_IGNORED';
				return false;
			}

			$maxSize = $config->vidMaxsize;
			if((int)$file['size'] / 1024 > $maxSize){
				$err = JText::sprintf('COM_JBLANCE_FILE_EXCEEDS_LIMIT', $maxSize);
				return false;
			}
		}
		return true;
	}

	//3.Upload Photo
	public static function uploadPictureMedia(){
		$app  = JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$lang->load('com_jblance', JPATH_SITE);

		//UPLOAD FILE
		$file = $_FILES['photo'];
		$userid = $app->input->get('userid', '', 'int');
		$response = array();

		$jbuser = JblanceHelper::get('helper.user');
		$jbuserid = $jbuser->getUser($userid)->id;//echo $jbuserid;

		$row	= JTable::getInstance('jbuser', 'Table');
		$row->load($jbuserid);//print_r($row);

		$oldpicloc = JBPROFILE_PIC_PATH.'/'.$row->picture;
		$oldtmbloc = JBPROFILE_PIC_PATH.'/'.$row->thumb;

		$newpicname = $userid.'_'.strtotime('now').'_pic'.'.jpg';
		$newtmbname = $userid.'_'.strtotime('now').'_tmb'.'.jpg';

		$config = JblanceHelper::getConfig();
		//$allowed = array('image/pjpeg', 'image/jpeg', 'image/jpg', 'image/png', 'image/x-png', 'image/gif', 'image/ico', 'image/x-icon');
		$type = $config->imgFileType;
		$allowed = explode(',', $type);
		$pwidth  = $config->imgWidth;
		$pheight = $config->imgHeight;
		$maxsize = $config->imgMaxsize;
		if($file['size'] > 0 &&  ($file['size'] / 1024  < $maxsize)){
			if(!file_exists(JPATH_SITE.'/images/jblance')){
				if(mkdir(JPATH_SITE.'/images/jblance')){
					JPath::setPermissions(JPATH_SITE.'/images/jblance', '0777');
					if(file_exists(JPATH_SITE.'/images/index.html')){
						copy(JPATH_SITE.'/images/index.html', JPATH_SITE.'/images/jblance/index.html');
					}
				}
			}
			if($file['error'] != 0){
				echo JText::_('COM_JBLANCE_UPLOAD_PHOTO_ERROR');
				exit;
			}
			if($file['size'] == 0){
				$file = null;
			}
			if(!in_array($file['type'], $allowed)){
				$file = null;
				$response['result'] = 'NO';
				$response['msg'] = JText::_('COM_JBLANCE_FILE_TYPE_NOT_ALLOWED');
				echo json_encode($response); exit;
			}
			if($file != null){
				$dest = JBPROFILE_PIC_PATH.'/'.$newpicname;//echo $dest;exit;
				$dest_tmb = JBPROFILE_PIC_PATH.'/'.$newtmbname;

				if(JFile::exists($oldpicloc)){
					$delpic = unlink($oldpicloc);
					$deltmb = unlink($oldtmbloc);
				}
				$soure = $file['tmp_name'];
				
				$uploaded = JFile::upload($soure, $dest);
				$fileAtr = getimagesize($dest);
				$widthOri = $fileAtr[0];
				$heightOri = $fileAtr[1];
				$type = $fileAtr['mime'];
				$img = false;
				switch ($type){
					case 'image/jpeg':
					case 'image/jpg':
					case 'image/pjpeg':
						$img = imagecreatefromjpeg($dest);
						break;
					case 'image/ico':
						$img = imagecreatefromico($dest);
						break;
					case 'image/x-png':
					case 'image/png':
						$img = imagecreatefrompng($dest);
						break;
					case 'image/gif':
						$img = imagecreatefromgif($dest);
						break;
				}
				if(!$img){
					return false;
				}
				$curr = @getimagesize($dest);
				$perc_w = $pwidth / $widthOri;
				$perc_h = $pheight / $heightOri;
				if(($widthOri < $pwidth) && ($heightOri < $pheight)){
					//return;
				}
				if($perc_h > $perc_w){
					$pwidth = $pwidth;
					$pheight = round($heightOri * $perc_w);
				}
				else {
					$pheight = $pheight;
					$pwidth = round($widthOri * $perc_h);
				}
				$nwimg = imagecreatetruecolor($pwidth, $pheight);

				if(($fileAtr[2] == IMAGETYPE_GIF) || ($fileAtr[2] == IMAGETYPE_PNG)){
					$trnprt_indx = imagecolortransparent($img);
					// If we have a specific transparent color
					if($trnprt_indx >= 0){
						$trnprt_color = imagecolorsforindex($img, $trnprt_indx);	// Get the original image's transparent color's RGB values
						$trnprt_indx  = imagecolorallocate($nwimg, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);	// Allocate the same color in the new image resource
						imagefill($nwimg, 0, 0, $trnprt_indx);			// Completely fill the background of the new image with allocated color.
						imagecolortransparent($nwimg, $trnprt_indx);	// Set the background color for new image to transparent
					}
					// Always make a transparent background color for PNGs that don't have one allocated already
					elseif($fileAtr[2] == IMAGETYPE_PNG){
						imagealphablending($nwimg, false);	 // Turn off transparency blending (temporarily)
						$color = imagecolorallocatealpha($nwimg, 0, 0, 0, 127);	 // Create a new transparent color for image
						imagefill($nwimg, 0, 0, $color);	// Completely fill the background of the new image with allocated color.
						imagesavealpha($nwimg, true);	 	// Restore transparency blending
					}
				}

				imagecopyresampled($nwimg, $img, 0, 0, 0, 0, $pwidth, $pheight, $widthOri, $heightOri);

				//create thumb
				$tmb = @imagecreatetruecolor(64 ,64);
				imagecopyresampled($tmb, $nwimg, 0, 0, 0, 0, 64, 64, $pwidth, $pheight);
				//imagecopy($tmb, $nwimg, 0, 0, 0, 0, $pwidth, $pheight);

				switch($fileAtr[2]){
					case IMAGETYPE_GIF:
						imagegif($nwimg, $dest);
						imagegif($tmb, $dest_tmb);
						break;
					case IMAGETYPE_JPEG:
						imagejpeg($nwimg, $dest);
						imagejpeg($tmb, $dest_tmb);
						break;
					case IMAGETYPE_PNG:
						imagepng($nwimg, $dest);
						imagepng($tmb, $dest_tmb);
						break;
					default:
						return false;
				}

				imagedestroy($tmb);
				imagedestroy($nwimg);
				imagedestroy($img);

				$row->picture = $newpicname;
				$row->thumb = $newtmbname;

				// pre-save checks
				if (!$row->check()){
					JError::raiseError(500, $row->getError());
				}
				// save the changes
				if (!$row->store()){
					JError::raiseError(500, $row->getError());
				}
				$row->checkin();

				$response['result'] = 'OK';
				$response['image'] = JBPROFILE_PIC_URL.$newpicname.'?'.time();
				$response['thumb'] = JBPROFILE_PIC_URL.$newtmbname.'?'.time();
				$response['width'] = $pwidth;$response['height'] = $pheight;
				$response['imgname'] = $newpicname;$response['tmbname'] = $newtmbname;
				$response['msg'] = JText::_('COM_JBLANCE_PICTURE_UPLOADED_SUCCESSFULLY');
				$response['return'] = JRoute::_('index.php?option=com_jblance&view=user&layout=editpicture', false);
				echo json_encode($response); exit;
			}
		}
		else {
			if($file['size'] / 1024  > $maxsize){
				$response['result'] = 'NO';
				$response['msg'] = JText::sprintf('COM_JBLANCE_PICTURE_EXCEEDS_LIMIT', $maxsize);
				echo json_encode($response); exit;
			}
		}
	}

	public static function removePictureMedia(){
		$app  = JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$lang->load('com_jblance', JPATH_SITE);

		$userid = $app->input->get('userid', 0, 'int');

		$jbuser = JblanceHelper::get('helper.user');
		$jbuserid = $jbuser->getUser($userid)->id;

		$row	= JTable::getInstance('jbuser', 'Table');
		$row->load($jbuserid);

		$destpic = JBPROFILE_PIC_PATH.'/'.$row->picture;
		$desttmb = JBPROFILE_PIC_PATH.'/'.$row->thumb;

		$response = array();

		if(JFile::exists($destpic)){
			$delpic = unlink($destpic);
			$deltmb = unlink($desttmb);

			$row->picture = '';
			$row->thumb = '';
			// pre-save checks
			if (!$row->check()){
				JError::raiseError(500, $row->getError());
			}
			// save the changes
			if (!$row->store()){
				JError::raiseError(500, $row->getError());
			}
			$row->checkin();

			$response['result'] = 'OK';
			$response['msg'] = JText::_('COM_JBLANCE_PICTURE_REMOVED_SUCCESSFULLY');
			echo json_encode($response); exit;
		}
		else {
			$response['result'] = 'NO';
			$response['msg'] = JText::_('COM_JBLANCE_FILE_DOES_NOT_EXIST');
			echo json_encode($response); exit;
		}
	}

	public static function cropPictureMedia(){
		$lang = JFactory::getLanguage();
		$lang->load('com_jblance', JPATH_SITE);

		$url = JBPROFILE_PIC_PATH.'/'.$_POST['imgLoc'];
		$tmb = JBPROFILE_PIC_PATH.'/'.$_POST['tmbLoc'];

		$response = array();

		if(JFile::exists($url)){

			$width = $_POST['cropW'];
			$height = $_POST['cropH'];
			$left = $_POST['cropX'];
			$top = $_POST['cropY'];

			header ("Content-type: image/jpg");
			$src 	= 	@imagecreatefromjpeg($url);
			$im	 	=	@imagecreatetruecolor($width, $height);

			imagecopy($im, $src, 0, 0, $left, $top, $width, $height);
			imagejpeg($im, $tmb, 100);
			imagedestroy($im);

			$response['result'] = 'OK';
			$response['msg'] = JText::_('COM_JBLANCE_THUMBNAIL_SAVED_SUCCESSFULLY');
			$response['return'] = JRoute::_('index.php?option=com_jblance&view=user&layout=editpicture', false);
		}
		else {
			$response['result'] = 'NO';
			$response['msg'] = JText::_('COM_JBLANCE_ERROR_SAVING_THUMBNAIL');
		}
		echo json_encode($response); exit;
	}
	
	public static function getFileInfo($type, $id){
		$db		= JFactory::getDBO();
		$fileInfo = array();
		if($type == 'portfolio'){
			$query = "SELECT attachment FROM #__jblance_portfolio WHERE id=".$db->quote($id);
			$db->setQuery($query);
	
			$attachment = explode(";", $db->loadResult());
			$showName = $attachment[0];
			$fileName = $attachment[1];
	
			$fileInfo['fileUrl'] = JBPORTFOLIO_URL.$fileName;
			$fileInfo['filePath'] = JBPORTFOLIO_PATH.'/'.$fileName;
			$fileInfo['fileName'] = $fileName;
			$fileInfo['showName'] = $showName;
		}
		elseif($type == 'project'){
			$query = "SELECT file_name,show_name FROM #__jblance_project_file WHERE id=".$db->quote($id);
			$db->setQuery($query);
	
			$projFile = $db->loadObject();
			$showName = $projFile->show_name;
			$fileName = $projFile->file_name;
	
			$fileInfo['fileUrl'] = JBPROJECT_URL.$fileName;
			$fileInfo['filePath'] = JBPROJECT_PATH.'/'.$fileName;
			$fileInfo['fileName'] = $fileName;
			$fileInfo['showName'] = $showName;
		}
		elseif($type == 'message'){
			$query = "SELECT attachment FROM #__jblance_message WHERE id=".$db->quote($id);
			$db->setQuery($query);
	
			$attachment = explode(";", $db->loadResult());
			$showName = $attachment[0];
			$fileName = $attachment[1];
	
			$fileInfo['fileUrl'] = JBMESSAGE_URL.$fileName;
			$fileInfo['filePath'] = JBMESSAGE_PATH.'/'.$fileName;
			$fileInfo['fileName'] = $fileName;
			$fileInfo['showName'] = $showName;
		}
		elseif($type == 'nda'){
			$query = "SELECT attachment FROM #__jblance_bid WHERE id=".$db->quote($id);
			$db->setQuery($query);
	
			$attachment = explode(";", $db->loadResult());
			$showName = $attachment[0];
			$fileName = $attachment[1];
	
			$fileInfo['fileUrl'] = JBBIDNDA_URL.$fileName;
			$fileInfo['filePath'] = JBBIDNDA_PATH.'/'.$fileName;
			$fileInfo['fileName'] = $fileName;
			$fileInfo['showName'] = $showName;
		}
	
		return $fileInfo;
	}
	
	public static function downloadFile(){
		$app  	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$type 	= $app->input->get('type', '', 'string');
		$id 	= $app->input->get('id', 0, 'int');
	
		$fileInfo = self::getFileInfo($type, $id);
	
		$filePath = $fileInfo['filePath'];
		$fileUrl = $fileInfo['fileUrl'];
		$showName = $fileInfo['showName'];
	
		self::setDownloadHeader($filePath, $fileUrl, $showName);
	
	}
	
	function setDownloadHeader($filePath, $fileUrl, $fileName){
		$view_types = array();
		$view_types = explode(',', 'html,htm,txt,pdf,doc,jpg,jpeg,png,gif');
	
		clearstatcache();
	
		if (!file_exists($filePath))
			$len = 0;
		else
			$len = filesize($filePath);
	
		$filename = basename($filePath);
		$file_extension = strtolower(substr(strrchr($filename,"."),1));
		$ctype = self::datei_mime($file_extension);//$ctype = 'application/force-download';
		ob_end_clean();
	
		// needed for MS IE - otherwise content disposition is not used?
		if(ini_get('zlib.output_compression'))
			ini_set('zlib.output_compression', 'Off');
	
		header("Cache-Control: public, must-revalidate");
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		// header("Pragma: no-cache");  // Problems with MS IE
		header("Expires: 0");
		header("Content-Description: File Transfer");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		header("Content-Type: " . $ctype);
		header("Content-Length: ".(string)$len);
	
		if(!in_array($file_extension, $view_types))
			header('Content-Disposition: attachment; filename="'.$fileName.'"');
		else
			header('Content-Disposition: inline; filename="'.$fileName.'"');	// view file in browser
	
		header("Content-Transfer-Encoding: binary\n");
	
		@readfile($filePath);
		exit;
	}
	
	function datei_mime($filetype) {
	
		switch ($filetype) {
			case "ez":  $mime="application/andrew-inset"; break;
			case "hqx": $mime="application/mac-binhex40"; break;
			case "cpt": $mime="application/mac-compactpro"; break;
			case "doc": $mime="application/msword"; break;
			case "bin": $mime="application/octet-stream"; break;
			case "dms": $mime="application/octet-stream"; break;
			case "lha": $mime="application/octet-stream"; break;
			case "lzh": $mime="application/octet-stream"; break;
			case "exe": $mime="application/octet-stream"; break;
			case "class": $mime="application/octet-stream"; break;
			case "dll": $mime="application/octet-stream"; break;
			case "oda": $mime="application/oda"; break;
			case "pdf": $mime="application/pdf"; break;
			case "ai":  $mime="application/postscript"; break;
			case "eps": $mime="application/postscript"; break;
			case "ps":  $mime="application/postscript"; break;
			case "xls": $mime="application/vnd.ms-excel"; break;
			case "ppt": $mime="application/vnd.ms-powerpoint"; break;
			case "wbxml": $mime="application/vnd.wap.wbxml"; break;
			case "wmlc": $mime="application/vnd.wap.wmlc"; break;
			case "wmlsc": $mime="application/vnd.wap.wmlscriptc"; break;
			case "vcd": $mime="application/x-cdlink"; break;
			case "pgn": $mime="application/x-chess-pgn"; break;
			case "csh": $mime="application/x-csh"; break;
			case "dvi": $mime="application/x-dvi"; break;
			case "spl": $mime="application/x-futuresplash"; break;
			case "gtar": $mime="application/x-gtar"; break;
			case "hdf": $mime="application/x-hdf"; break;
			case "js":  $mime="application/x-javascript"; break;
			case "nc":  $mime="application/x-netcdf"; break;
			case "cdf": $mime="application/x-netcdf"; break;
			case "swf": $mime="application/x-shockwave-flash"; break;
			case "tar": $mime="application/x-tar"; break;
			case "tcl": $mime="application/x-tcl"; break;
			case "tex": $mime="application/x-tex"; break;
			case "texinfo": $mime="application/x-texinfo"; break;
			case "texi": $mime="application/x-texinfo"; break;
			case "t":   $mime="application/x-troff"; break;
			case "tr":  $mime="application/x-troff"; break;
			case "roff": $mime="application/x-troff"; break;
			case "man": $mime="application/x-troff-man"; break;
			case "me":  $mime="application/x-troff-me"; break;
			case "ms":  $mime="application/x-troff-ms"; break;
			case "ustar": $mime="application/x-ustar"; break;
			case "src": $mime="application/x-wais-source"; break;
			case "zip": $mime="application/x-zip"; break;
			case "au":  $mime="audio/basic"; break;
			case "snd": $mime="audio/basic"; break;
			case "mid": $mime="audio/midi"; break;
			case "midi": $mime="audio/midi"; break;
			case "kar": $mime="audio/midi"; break;
			case "mpga": $mime="audio/mpeg"; break;
			case "mp2": $mime="audio/mpeg"; break;
			case "mp3": $mime="audio/mpeg"; break;
			case "aif": $mime="audio/x-aiff"; break;
			case "aiff": $mime="audio/x-aiff"; break;
			case "aifc": $mime="audio/x-aiff"; break;
			case "m3u": $mime="audio/x-mpegurl"; break;
			case "ram": $mime="audio/x-pn-realaudio"; break;
			case "rm":  $mime="audio/x-pn-realaudio"; break;
			case "rpm": $mime="audio/x-pn-realaudio-plugin"; break;
			case "ra":  $mime="audio/x-realaudio"; break;
			case "wav": $mime="audio/x-wav"; break;
			case "pdb": $mime="chemical/x-pdb"; break;
			case "xyz": $mime="chemical/x-xyz"; break;
			case "bmp": $mime="image/bmp"; break;
			case "gif": $mime="image/gif"; break;
			case "ief": $mime="image/ief"; break;
			case "jpeg": $mime="image/jpeg"; break;
			case "jpg": $mime="image/jpeg"; break;
			case "jpe": $mime="image/jpeg"; break;
			case "png": $mime="image/png"; break;
			case "tiff": $mime="image/tiff"; break;
			case "tif": $mime="image/tiff"; break;
			case "wbmp": $mime="image/vnd.wap.wbmp"; break;
			case "ras": $mime="image/x-cmu-raster"; break;
			case "pnm": $mime="image/x-portable-anymap"; break;
			case "pbm": $mime="image/x-portable-bitmap"; break;
			case "pgm": $mime="image/x-portable-graymap"; break;
			case "ppm": $mime="image/x-portable-pixmap"; break;
			case "rgb": $mime="image/x-rgb"; break;
			case "xbm": $mime="image/x-xbitmap"; break;
			case "xpm": $mime="image/x-xpixmap"; break;
			case "xwd": $mime="image/x-xwindowdump"; break;
			case "msh": $mime="model/mesh"; break;
			case "mesh": $mime="model/mesh"; break;
			case "silo": $mime="model/mesh"; break;
			case "wrl": $mime="model/vrml"; break;
			case "vrml": $mime="model/vrml"; break;
			case "css": $mime="text/css"; break;
			case "asc": $mime="text/plain"; break;
			case "txt": $mime="text/plain"; break;
			case "gpg": $mime="text/plain"; break;
			case "rtx": $mime="text/richtext"; break;
			case "rtf": $mime="text/rtf"; break;
			case "wml": $mime="text/vnd.wap.wml"; break;
			case "wmls": $mime="text/vnd.wap.wmlscript"; break;
			case "etx": $mime="text/x-setext"; break;
			case "xsl": $mime="text/xml"; break;
			case "flv": $mime="video/x-flv"; break;
			case "mpeg": $mime="video/mpeg"; break;
			case "mpg": $mime="video/mpeg"; break;
			case "mpe": $mime="video/mpeg"; break;
			case "qt":  $mime="video/quicktime"; break;
			case "mov": $mime="video/quicktime"; break;
			case "mxu": $mime="video/vnd.mpegurl"; break;
			case "avi": $mime="video/x-msvideo"; break;
			case "movie": $mime="video/x-sgi-movie"; break;
			case "asf": $mime="video/x-ms-asf"; break;
			case "asx": $mime="video/x-ms-asf"; break;
			case "wm":  $mime="video/x-ms-wm"; break;
			case "wmv": $mime="video/x-ms-wmv"; break;
			case "wvx": $mime="video/x-ms-wvx"; break;
			case "ice": $mime="x-conference/x-cooltalk"; break;
			case "rar": $mime="application/x-rar"; break;
			default:    $mime="application/octet-stream"; break;
		}
		return $mime;
	}
}