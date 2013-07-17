<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	13 July 2012
 * @file name	:	plugins/joombri/jblancefeeds/jblancefeeds.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 // no direct access
 defined('_JEXEC') or die('Restricted access');
 
 jimport( 'joomla.plugin.plugin' );

 require_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/jblance.php');

class plgJoomBriJblanceFeeds extends JPlugin {

	function plgJoombriJblanceFeeds(& $subject, $config){
		parent::__construct($subject, $config);
		JPlugin::loadLanguage('plg_joombri_jblancefeeds', JPATH_ADMINISTRATOR);	//Load Language file.
	}

	function onBuyerPickFreelancer($actorid, $targetid, $projectid){
		$now = JFactory::getDate();
		$feedsHelper = JblanceHelper::get('helper.feeds');		// create an instance of the class FeedsHelper

		$act 		  = new stdClass();
		$act->actor   = $actorid;
		$act->target  = $targetid;
		$act->project_id  = $projectid;
		$act->title   = JText::sprintf("PLG_JBLANCE_BUYER_PICKED_FREELANCER", '{actor}', '{target}', '{projectid}');
		$act->content = '';
		$act->status  = 0;
		$act->access = 20; //0-Public; 10-Site members; 20-Only me; 30-No body
		$act->created	= $now->toSql();
		$feedsHelper->add($act);
	}

	function onFreelancerAcceptBid($actorid, $targetid, $projectid){
		$now = JFactory::getDate();
		$feedsHelper = JblanceHelper::get('helper.feeds');		// create an instance of the class FeedsHelper

		$act 		  = new stdClass();
		$act->actor   = $actorid;
		$act->target  = $targetid;
		$act->project_id  = $projectid;
		$act->title   = JText::sprintf("PLG_JBLANCE_FREELANCER_ACCEPTED_BID", '{actor}', '{projectid}');
		$act->content = '';
		$act->status  = 0;
		$act->access = 20; //0-Public; 10-Site members; 20-Only me; 30-No body
		$act->created	= $now->toSql();
		$feedsHelper->add($act);
	}

	function onFreelancerDenyBid($actorid, $targetid, $projectid){
		$now = JFactory::getDate();
		$feedsHelper = JblanceHelper::get('helper.feeds');		// create an instance of the class FeedsHelper

		$act 		  = new stdClass();
		$act->actor   = $actorid;
		$act->target  = $targetid;
		$act->project_id  = $projectid;
		$act->title   = JText::sprintf("PLG_JBLANCE_FREELANCER_DENIED_BID", '{actor}', '{projectid}');
		$act->content = '';
		$act->status  = 0;
		$act->access = 20; //0-Public; 10-Site members; 20-Only me; 30-No body
		$act->created	= $now->toSql();
		$feedsHelper->add($act);
	}

	function onBuyerReleaseEscrow($actorid, $targetid, $projectid){
		$now = JFactory::getDate();
		$feedsHelper = JblanceHelper::get('helper.feeds');		// create an instance of the class FeedsHelper

		$act 		  = new stdClass();
		$act->actor   = $actorid;
		$act->target  = $targetid;
		$act->project_id  = $projectid;
		//Title has to be different for project and other reason.
		$act->title = ($projectid > 0) ? JText::sprintf("PLG_JBLANCE_BUYER_RELEASE_ESCROW_FOR_PROJECT", '{actor}', '{projectid}') : JText::sprintf("PLG_JBLANCE_BUYER_RELEASE_ESCROW", '{actor}');
		$act->content = '';
		$act->status  = 0;
		$act->access = 20; //0-Public; 10-Site members; 20-Only me; 30-No body
		$act->created	= $now->toSql();
		$feedsHelper->add($act);
	}

	function onFreelancerAcceptEscrow($actorid, $targetid, $projectid){
		$now = JFactory::getDate();
		$feedsHelper = JblanceHelper::get('helper.feeds');		// create an instance of the class FeedsHelper

		$act 		  = new stdClass();
		$act->actor   = $actorid;
		$act->target  = $targetid;
		$act->project_id  = $projectid;
		$act->title   = ($projectid > 0) ? JText::sprintf("PLG_JBLANCE_FREELANCER_ACCEPT_ESCROW_FOR_PROJECT", '{actor}', '{projectid}') : JText::sprintf("PLG_JBLANCE_FREELANCER_ACCEPT_ESCROW", '{actor}', '{projectid}');
		$act->content = '';
		$act->status  = 0;
		$act->access = 20; //0-Public; 10-Site members; 20-Only me; 30-No body
		$act->created	= $now->toSql();
		$feedsHelper->add($act);
	}

	function onUserRating($actorid, $targetid, $projectid){
		$now = JFactory::getDate();
		$feedsHelper = JblanceHelper::get('helper.feeds');		// create an instance of the class FeedsHelper

		$act 		  = new stdClass();
		$act->actor   = $actorid;
		$act->target  = $targetid;
		$act->project_id  = $projectid;
		$act->title   = JText::sprintf("PLG_JBLANCE_USER_RATED_ANOTHER_USER", '{actor}', '{target}', '{projectid}');
		$act->content = '';
		$act->status  = 0;
		$act->access = 20; //0-Public; 10-Site members; 20-Only me; 30-No body
		$act->created	= $now->toSql();
		$feedsHelper->add($act);
	}

}