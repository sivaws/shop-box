<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	20 March 2012
 * @file name	:	views/admconfig/tmpl/emailtemplate.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Edit the email templates (jblance)
 */
 defined('_JEXEC') or die('Restricted access'); 

 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');

 $editor = JFactory::getEditor();
 $app  	 = JFactory::getApplication();
 $tempFor = $app->input->get('tempfor', 'subscr-pending', 'string');
 
 $availableTags = array();
 
 $availableTags['subscr-approved-auto'] 	=
 $availableTags['subscr-pending'] 			= array(
	 											"[NAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_USER'), 
	 											"[PLANNAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_PLAN_SUBSCRIBE'), 
	 											"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'), 
	 											"[SITEURL]" => JText::_('COM_JBLANCE_SITE_URL'), 
	 											"[ADMINEMAIL]" => JText::_('COM_JBLANCE_EMAIL_OF_THE_ADMIN')
	 											);
 $availableTags['subscr-approved-admin'] 	= array(
	 											"[NAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_USER'), 
	 											"[PLANNAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_PLAN_SUBSCRIBE'), 
	 											"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'), 
	 											"[SITEURL]" => JText::_('COM_JBLANCE_SITE_URL'), 
	 											);
 $availableTags['subscr-details'] 			= array(
	 											"[NAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_USER'),
	 											"[USEREMAIL]" => JText::_('COM_JBLANCE_EMAIL_ID_OF_THE_USER'),
	 											"[USERNAME]" => JText::_('COM_JBLANCE_USERNAME_OF_THE_USER'),
	 											"[SUBSCRID]" => JText::_('COM_JBLANCE_SUBSCR_ID'),
	 											"[PLANNAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_PLAN_SUBSCRIBE'), 
	 											"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'), 
	 											"[GATEWAY]" => JText::_('COM_JBLANCE_PAYMENT_GATEWAY'), 
	 											"[PLANSTATUS]" => JText::_('COM_JBLANCE_STATUS_PLAN'), 
	 											);
 $availableTags['newuser-facebook-signin'] = 
 $availableTags['newuser-pending-approval'] = 
 $availableTags['newuser-activate'] 		= 
 $availableTags['newuser-login'] 			= 
 $availableTags['newuser-details'] 			= array(
	 											"[NAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_USER'), 
	 											"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'), 
	 											"[ACTLINK]" => JText::_('COM_JBLANCE_ACTIVATION_LINK'),
	 											"[SITEURL]" => JText::_('COM_JBLANCE_SITE_URL'),
	 											"[ADMINURL]" => JText::_('COM_JBLANCE_SITE_BACKEND_URL'),
	 											"[USERNAME]" => JText::_('COM_JBLANCE_USERNAME_OF_THE_USER'),
	 											"[PASSWORD]" => JText::_('COM_JBLANCE_PASSWORD_OF_THE_USER'), 
	 											"[USEREMAIL]" => JText::_('COM_JBLANCE_EMAIL_ID_OF_THE_USER'), 
	 											"[USERTYPE]" => JText::_('COM_JBLANCE_USERGROUP_OF_THE_USER'),
	 											"[STATUS]" => JText::_('COM_JBLANCE_STATUS'),
	 											);
$availableTags['newuser-account-approved'] 	= array(
 												"[NAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_USER'),
 												"[EMAIL]" => JText::_('COM_JBLANCE_EMAIL_ID_OF_THE_USER'),
 												"[USERNAME]" => JText::_('COM_JBLANCE_USERNAME_OF_THE_USER'),
 												"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'),
 												"[SITEURL]" => JText::_('COM_JBLANCE_SITE_URL')
 												);
$availableTags['proj-new-notify'] 			= array(
												"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'),
												"[SITEURL]" => JText::_('COM_JBLANCE_SITE_URL'),
 												"[PROJECTNAME]" => JText::_('COM_JBLANCE_PROJECT_TITLE'),
 												"[CATEGORYNAME]" => JText::_(''),
 												"[CURRENCYSYM]" => JText::_('COM_JBLANCE_CURRENCY_SYMBOL'),
 												"[CURRENCYCODE]" => JText::_(''),
 												"[BUDGETMIN]" => JText::_(''),
 												"[BUDGETMAX]" => JText::_(''),
 												"[STARTDATE]" => JText::_(''),
 												"[EXPIRE]" => JText::_(''),
 												"[PROJECTURL]" => JText::_('')
 												);
$availableTags['proj-pending-approval'] 	= array(
												"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'),
												"[ADMINURL]" => JText::_('COM_JBLANCE_SITE_BACKEND_URL'),
 												"[PROJECTNAME]" => JText::_('COM_JBLANCE_PROJECT_TITLE'),
 												"[PUBLISHERUSERNAME]" => JText::_('')
 												);
$availableTags['proj-approved'] 			= array(
												"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'),
												"[PROJECTURL]" => JText::_(''),
 												"[PROJECTNAME]" => JText::_('COM_JBLANCE_PROJECT_TITLE'),
 												"[PUBLISHERUSERNAME]" => JText::_('')
 												);
$availableTags['proj-newbid-notify'] 		= array(
												"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'),
												"[SITEURL]" => JText::_('COM_JBLANCE_SITE_URL'),
 												"[PUBLISHERNAME]" => JText::_(''),
 												"[PROJECTNAME]" => JText::_('COM_JBLANCE_PROJECT_TITLE'),
 												"[CATEGORYNAME]" => JText::_(''),
 												"[CURRENCYSYM]" => JText::_('COM_JBLANCE_CURRENCY_SYMBOL'),
 												"[CURRENCYCODE]" => JText::_(''),
 												"[BUDGETMIN]" => JText::_(''),
 												"[BUDGETMAX]" => JText::_(''),
 												"[STARTDATE]" => JText::_(''),
 												"[EXPIRE]" => JText::_(''),
 												"[BIDDERNAME]" => JText::_(''),
 												"[BIDDERUSERNAME]" => JText::_(''),
 												"[BIDAMOUNT]" => JText::_(''),
 												"[DELIVERY]" => JText::_('')
 												);
$availableTags['proj-lowbid-notify'] 		= array(
												"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'),
												"[SITEURL]" => JText::_('COM_JBLANCE_SITE_URL'),
 												"[PROJECTNAME]" => JText::_('COM_JBLANCE_PROJECT_TITLE'),
 												"[CURRENCYSYM]" => JText::_('COM_JBLANCE_CURRENCY_SYMBOL'),
 												"[CURRENCYCODE]" => JText::_(''),
 												"[BUDGETMIN]" => JText::_(''),
 												"[BUDGETMAX]" => JText::_(''),
 												"[STARTDATE]" => JText::_(''),
 												"[EXPIRE]" => JText::_(''),
 												"[BIDDERUSERNAME]" => JText::_(''),
 												"[BIDAMOUNT]" => JText::_(''),
 												"[DELIVERY]" => JText::_('')
 												);
$availableTags['proj-bidwon-notify'] 		= 
$availableTags['proj-lowbid-notify'] 		= array(
												"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'),
												"[SITEURL]" => JText::_('COM_JBLANCE_SITE_URL'),
 												"[PROJECTNAME]" => JText::_('COM_JBLANCE_PROJECT_TITLE'),
 												"[BIDDERNAME]" => JText::_(''),
 												"[BIDDERUSERNAME]" => JText::_(''),
 												"[CURRENCYSYM]" => JText::_('COM_JBLANCE_CURRENCY_SYMBOL'),
 												"[CURRENCYCODE]" => JText::_(''),
 												"[BIDAMOUNT]" => JText::_(''),
 												"[DELIVERY]" => JText::_('')
 												);
$availableTags['proj-accept-notify'] 		= 
$availableTags['proj-denied-notify'] 		= array(
												"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'),
												"[SITEURL]" => JText::_('COM_JBLANCE_SITE_URL'),
 												"[PROJECTNAME]" => JText::_('COM_JBLANCE_PROJECT_TITLE'),
 												"[PUBLISHERNAME]" => JText::_(''),
 												"[BIDDERNAME]" => JText::_(''),
 												"[BIDDERUSERNAME]" => JText::_('')
 												);
$availableTags['fin-deposit-alert'] 		= array(
												"[NAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_USER'),
												"[USERNAME]" => JText::_('COM_JBLANCE_USERNAME_OF_THE_USER'),
 												"[INVOICENO]" => JText::_('COM_JBLANCE_INVOICE_NO'),
 												"[ADMINURL]" => JText::_('COM_JBLANCE_SITE_BACKEND_URL'),
 												"[GATEWAY]" => JText::_('COM_JBLANCE_PAYMENT_GATEWAY'), 
 												"[STATUS]" => JText::_('COM_JBLANCE_STATUS'),
 												"[AMOUNT]" => JText::_('COM_JBLANCE_AMOUNT'),
 												"[CURRENCYSYM]" => JText::_('COM_JBLANCE_CURRENCY_SYMBOL')
 												);
$availableTags['fin-witdrw-approved'] 		= 
$availableTags['fin-deposit-approved'] 		= array(
												"[NAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_USER'),
 												"[CURRENCYSYM]" => JText::_('COM_JBLANCE_CURRENCY_SYMBOL'),
 												"[AMOUNT]" => JText::_('COM_JBLANCE_AMOUNT'),
 												"[INVOICENO]" => JText::_('COM_JBLANCE_INVOICE_NO'),
 												"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'),
 												"[SITEURL]" => JText::_('COM_JBLANCE_SITE_URL')
 												);
$availableTags['fin-witdrw-request'] 		= array(
												"[NAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_USER'),
												"[USERNAME]" => JText::_('COM_JBLANCE_USERNAME_OF_THE_USER'),
 												"[INVOICENO]" => JText::_('COM_JBLANCE_INVOICE_NO'),
 												"[ADMINURL]" => JText::_('COM_JBLANCE_SITE_BACKEND_URL'),
 												"[GATEWAY]" => JText::_('COM_JBLANCE_PAYMENT_GATEWAY')
 												);
$availableTags['fin-escrow-accepted'] 		= 
$availableTags['fin-escrow-released'] 		= array(
												"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'),
												"[SITEURL]" => JText::_('COM_JBLANCE_SITE_URL'),
												"[PROJECTNAME]" => JText::_('COM_JBLANCE_PROJECT_TITLE'),
												"[SENDERUSERNAME]" => JText::_(''),
												"[RECEIVEUSERNAME]" => JText::_(''),
 												"[RELEASEDATE]" => JText::_('COM_JBLANCE_RELEASE_DATE'),
 												"[CURRENCYSYM]" => JText::_('COM_JBLANCE_CURRENCY_SYMBOL'),
 												"[AMOUNT]" => JText::_('COM_JBLANCE_AMOUNT'),
 												"[NOTE]" => JText::_('COM_JBLANCE_NOTE')
 												);
$availableTags['pm-new-notify'] 			= array(
												"[RECIPIENT_USERNAME]" => JText::_(''),
												"[SENDER_USERNAME]" => JText::_(''),
												"[MSG_SUBJECT]" => JText::_(''),
 												"[MSG_BODY]" => JText::_(''),
 												"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'),
												"[SITEURL]" => JText::_('COM_JBLANCE_SITE_URL')
 												);
$availableTags['report-default-action'] 	= array(
												"[TYPE]" => JText::_(''),
												"[COUNT]" => JText::_('COM_JBLANCE_COUNT'),
												"[ACTION]" => JText::_(''),
 												"[ITEMLINK]" => JText::_('COM_JBLANCE_ITEM_LINK'),
 												"[SITENAME]" => JText::_('COM_JBLANCE_NAME_OF_THE_SITE'),
 												);
?>
<script type="text/javascript">
<!--
	Joomla.submitbutton = function(task){
		if(document.formvalidator.isValid(document.id('emailtemp-form'))) {
			Joomla.submitform(task, document.getElementById('emailtemp-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'));?>');
		}
	}
//-->
</script>
<table width="100%" >
	<tr>
		<td align="left" width="25%"  valign="top">
			<table width="100%" >
				<tr>
					<td style="vertical-align:top;">
					<div style="width: 230px;">
						<?php
						$link = JRoute::_('index.php?option=com_jblance&view=admconfig&layout=emailtemplate&tempfor=');
						echo JHtml::_('sliders.start', 'panel-sliders', array('useCookie'=>'1'));
						echo JHtml::_('sliders.panel', JText::_('COM_JBLANCE_SUBSCRIPTION'), 'subscription'); ?>
						<div id="jbadmin-menu">
							<a href="<?php echo $link.'subscr-pending'; ?>"><?php echo JText::_('COM_JBLANCE_SUBSCRIPTION_PENDING'); ?></a>
							<a href="<?php echo $link.'subscr-approved-auto'; ?>"><?php echo JText::_('COM_JBLANCE_SUBSCRIPTION_AUTO_APPROVED'); ?></a>
							<a href="<?php echo $link.'subscr-approved-admin'; ?>"><?php echo JText::_('COM_JBLANCE_SUBSCRIPTION_ADMIN_APPROVED'); ?></a>
							<a href="<?php echo $link.'subscr-details'; ?>"><?php echo JText::_('COM_JBLANCE_SUBSCRIPTION_DETAILS'); ?></a>
						</div>
						<?php
						echo JHtml::_('sliders.panel', JText::_('COM_JBLANCE_REGISTRATION'), 'registration');
						?>
						<div id="jbadmin-menu">
							<a href="<?php echo $link.'newuser-details'; ?>"><?php echo JText::_('COM_JBLANCE_NEW_USER_DETAILS'); ?></a>
							<a href="<?php echo $link.'newuser-login'; ?>"><?php echo JText::_('COM_JBLANCE_NEW_USER_LOGIN'); ?></a>
							<a href="<?php echo $link.'newuser-activate'; ?>"><?php echo JText::_('COM_JBLANCE_NEW_USER_ACTIVATE'); ?></a>
							<a href="<?php echo $link.'newuser-pending-approval'; ?>"><?php echo JText::_('COM_JBLANCE_NEW_USER_PENDING_APPROVAL'); ?></a>
							<a href="<?php echo $link.'newuser-account-approved'; ?>"><?php echo JText::_('COM_JBLANCE_NEW_USER_ACCOUNT_APPROVED'); ?></a>
							<a href="<?php echo $link.'newuser-facebook-signin'; ?>"><?php echo JText::_('COM_JBLANCE_NEW_USER_FACEBOOK_SIGNIN'); ?></a>
						</div>
						<?php
						echo JHtml::_('sliders.panel', JText::_('COM_JBLANCE_PROJECT_BIDDING'), 'project-bidding');
						?>
						<div id="jbadmin-menu">
							<a href="<?php echo $link.'proj-new-notify'; ?>"><?php echo JText::_('COM_JBLANCE_NEW_PROJECT'); ?></a>
							<a href="<?php echo $link.'proj-pending-approval'; ?>"><?php echo JText::_('COM_JBLANCE_PROJECT_PENDING_APPROVAL'); ?></a>
							<a href="<?php echo $link.'proj-approved'; ?>"><?php echo JText::_('COM_JBLANCE_PROJECT_APPROVED'); ?></a>
							<a href="<?php echo $link.'proj-newbid-notify'; ?>"><?php echo JText::_('COM_JBLANCE_NEW_BID'); ?></a>
							<a href="<?php echo $link.'proj-lowbid-notify'; ?>"><?php echo JText::_('COM_JBLANCE_LOWER_BID'); ?></a>
							<a href="<?php echo $link.'proj-bidwon-notify'; ?>"><?php echo JText::_('COM_JBLANCE_BID_WON'); ?></a>
							<a href="<?php echo $link.'proj-denied-notify'; ?>"><?php echo JText::_('COM_JBLANCE_BID_DENIED'); ?></a>
							<a href="<?php echo $link.'proj-accept-notify'; ?>"><?php echo JText::_('COM_JBLANCE_BID_ACCEPTED'); ?></a>
							<a href="<?php echo $link.'proj-newforum-notify'; ?>"><?php echo JText::_('COM_JBLANCE_NEW_FORUM_MESSAGE'); ?></a>
						</div>
						<?php
						echo JHtml::_('sliders.panel', JText::_('COM_JBLANCE_FINANCE'), 'finance');
						?>
						<div id="jbadmin-menu">
							<a href="<?php echo $link.'fin-deposit-alert'; ?>"><?php echo JText::_('COM_JBLANCE_DEPOSIT_FUND_DETAILS'); ?></a>
							<a href="<?php echo $link.'fin-deposit-approved'; ?>"><?php echo JText::_('COM_JBLANCE_DEPOSIT_FUND_APPROVED'); ?></a>
							<a href="<?php echo $link.'fin-witdrw-request'; ?>"><?php echo JText::_('COM_JBLANCE_WITHDRAW_FUND_REQUEST'); ?></a>
							<a href="<?php echo $link.'fin-witdrw-approved'; ?>"><?php echo JText::_('COM_JBLANCE_WITHDRAW_REQUEST_APPROVED'); ?></a>
							<a href="<?php echo $link.'fin-escrow-released'; ?>"><?php echo JText::_('COM_JBLANCE_ESCROW_PAYMENT_RELEASED'); ?></a>
							<a href="<?php echo $link.'fin-escrow-accepted'; ?>"><?php echo JText::_('COM_JBLANCE_ESCROW_PAYMENT_ACCEPTED'); ?></a>
						</div>
						<?php
						echo JHtml::_('sliders.panel', JText::_('COM_JBLANCE_PRIVATE_MESSAGE_REPORTING'), 'private-message');
						?>
						<div id="jbadmin-menu">
							<a href="<?php echo $link.'pm-new-notify'; ?>"><?php echo JText::_('COM_JBLANCE_NEW_PM_NOTIFICATION'); ?></a>
							<a href="<?php echo $link.'report-default-action'; ?>"><?php echo JText::_('COM_JBLANCE_REPORTING_DEFAULT_ACTION'); ?></a>
						</div>
						<?php
						echo JHtml::_('sliders.end');
						?>
					</div>
					</td>
				</tr>
			</table>
		</td>
		<td width="75%" valign="top" align="left">
			<form action="index.php" method="post" id="emailtemp-form" name="adminForm" class="form-validate">
				<input type="hidden" name="check" value="post"/>
			    <table class="adminform">
					<tr class="row1">
						<td width="50" colspan="3"><b><?php echo JText::_('COM_JBLANCE_TITLE'); ?></b> :
							<?php echo $this->template->title; ?>
						</td>
					</tr>
					<tr class="row1">
						<td width="50" colspan="3"><label id="subjectmsg" for="subject"><?php echo JText::_('COM_JBLANCE_SUBJECT'); ?></label>&nbsp;<font color="red">*</font> :
							<input class="inputbox required" type="text" name="subject" id="subject" size="135" maxlength="255" value="<?php if(isset($this->template)) echo $this->template->subject; ?>" />
						</td>
					</tr>
					<tr><td height="10" colspan="2"></td></tr>
					<tr class="row2">
						<td colspan="3" valign="top"><label id="descriptionmsg" for="body"><?php echo JText::_('COM_JBLANCE_MESSAGE_BODY'); ?></label>&nbsp;<font color="red">*</font></td>
					</tr>
					<tr>
						<td colspan="2" align="center" width="800">
						<?php
							$editor = JFactory::getEditor();
							if(isset($this->template))
								echo $editor->display('body', $this->template->body, '650', '300', '60', '20', false);
							else
								echo $editor->display('body', '', '550', '300', '60', '20', false);
						?>	
						</td>
					</tr>
					<tr>
						<td width="35%" valign="top">
							<table class="adminform" style="width:50%;">
								<tr class="row1"><th colspan="2"><?php echo JText::_('COM_JBLANCE_AVAILABLE_TAGS'); ?></th></tr>
								<?php 
								if(isset($availableTags[$tempFor])){
									foreach($availableTags[$tempFor] as $key=>$value) { ?>
										<tr><td><?php echo $key; ?></td><td><?php echo $value; ?></td></tr>
								<?php 
									}
								} ?>	
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" height="5"></td>
					<tr>
	    		</table>
				<input type="hidden" name="id" value="<?php echo $this->template->id; ?>" />
				<input type="hidden" name="templatefor" value="<?php echo $this->template->templatefor; ?>" />
				<input type="hidden" name="option" value="com_jblance" />
				<input type="hidden" name="task" value="saveemailtemplate" />
				<?php echo JHTML::_('form.token'); ?>
			</form>
		</td>
	</tr>
</table>				