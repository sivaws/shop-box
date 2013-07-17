<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	14 March 2012
 * @file name	:	views/admconfig/tmpl/config.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Users (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 jbimport('integration.integration');

 $model = $this->getModel();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper

 $uploadLimit = ini_get('upload_max_filesize');
 $uploadLimit = str_ireplace ('M', ' MB', $uploadLimit);
 
 $user = JFactory::getUser();
 $isSuperAdmin = false;
 if(isset($user->groups[8]))
 	$isSuperAdmin = true;
 
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<?php
	echo JHtml::_('tabs.start', 'panel-tabs', array('useCookie'=>'1'));
	echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_GENERAL'), 'general'); ?>
	<div class="width-100">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_GENERAL'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_WELCOME_TITLE'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[welcomeTitle]" id="paramsWelcomeTitle" size="60" maxlength="100" value="<?php echo $this->params->welcomeTitle; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_CURRENCY_SYMBOL'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[currencySymbol]" id="paramsCurrencySymbol" size="5" maxlength="10" value="<?php echo $this->params->currencySymbol; ?>" />
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_CURRENCY_SYMBOL_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_CURRENCY_CODE'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[currencyCode]" id="paramsCurrencyCode" size="5" maxlength="10" value="<?php echo $this->params->currencyCode; ?>" />
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_CURRENCY_CODE_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_DEFAULT_DATE_FORMAT'); ?>:</label>
					</td>
					<td>
						<?php $list_dformat = $model->getselectDateFormat('params[dateFormat]', $this->params->dateFormat);
						 echo $list_dformat; ?>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_DEFAULT_DATE_FORMAT_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_ARTICLE_ID_TNS'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[termArticleId]" id="paramsTermArticleId" size="5" maxlength="5" value="<?php echo $this->params->termArticleId; ?>" />
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_ARTICLE_ID_TNS_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_RSS_LIMIT'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[rssLimit]" id="paramsRssLimit" size="5" maxlength="5" value="<?php echo $this->params->rssLimit; ?>" />
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_RSS_LIMIT_RSS_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_SHOW_RSS'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
							<?php $enable_rss = $select->YesNoBool('params[showRss]', $this->params->showRss);
							echo  $enable_rss; ?>
						</fieldset>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_SHOW_RSS_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="showJoombriCredit"><?php echo JText::_('COM_JBLANCE_SHOW_JOOMBRI_CREDIT'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
							<?php $show_jbcredit = $select->YesNoBool('params[showJoombriCredit]', $this->params->showJoombriCredit);
							echo  $show_jbcredit; ?>
						</fieldset>	
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_SHOW_JOOMBRI_CREDIT_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="reviewProjects"><?php echo JText::_('COM_JBLANCE_REVIEW_PROJECTS'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
							<?php $reviewProjects = $select->YesNoBool('params[reviewProjects]', $this->params->reviewProjects);
							echo  $reviewProjects; ?>
						</fieldset>	
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_REVIEW_PROJECTS_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="enableAddThis"><?php echo JText::_('COM_JBLANCE_SOCIAL_BOOKMARKING'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
							<?php echo $select->YesNoBool('params[enableAddThis]', $this->params->enableAddThis); ?>
						</fieldset>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_SOCIAL_BOOKMARKING_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="enableAddThis"><?php echo JText::_('COM_JBLANCE_ADDTHIS_PUBLISHERID'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[addThisPubid]" id="addThisPubid" size="20" maxlength="25" value="<?php echo $this->params->addThisPubid; ?>" />
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_ADDTHIS_PUBLISHERID_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_SHOW_FEEDS_DASHBOARD'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
							<?php echo $select->YesNoBool('params[showFeedsDashboard]', $this->params->showFeedsDashboard); ?>
						</fieldset>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_SHOW_FEEDS_DASHBOARD_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_DASHBOARD_FEEDS_LIMIT'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[feedLimitDashboard]" id="feedLimitDashboard" size="5" maxlength="5" value="<?php echo $this->params->feedLimitDashboard; ?>" />
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_DASHBOARD_FEEDS_LIMIT_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_DISPLAY_USERNAME'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
							<?php $showUsername = $select->YesNoBool('params[showUsername]', $this->params->showUsername);
							echo  $showUsername; ?>
						</fieldset>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_DISPLAY_USERNAME_EXAMPLE'); ?>	
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="width-100">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_REPORTINGS'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="enableReporting"><?php echo JText::_('COM_JBLANCE_ENABLE_REPORTING'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
							<?php echo $select->YesNoBool('params[enableReporting]', $this->params->enableReporting); ?>
						</fieldset>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_ENABLE_REPORTING_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="maxReport"><?php echo JText::_('COM_JBLANCE_REPORT_EXECUTE_DEFAULT_ACTION_LIMIT'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[maxReport]" id="maxReport" size="5" maxlength="5" value="<?php echo $this->params->maxReport; ?>" />&nbsp;<?php echo JText::_('COM_JBLANCE_REPORTS'); ?>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_REPORT_EXECUTE_DEFAULT_ACTION_LIMIT_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="enableGuestReporting"><?php echo JText::_('COM_JBLANCE_GUEST_REPORTING'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
							<?php echo $select->YesNoBool('params[enableGuestReporting]', $this->params->enableGuestReporting); ?>
						</fieldset>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_GUEST_REPORTING_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="reportCategory"><?php echo JText::_('COM_JBLANCE_REPORT_CATEGORIES'); ?>:</label>
					</td>
					<td>
						<textarea name="params[reportCategory]" id="reportCategory" rows="6" cols="30"><?php echo $this->params->reportCategory; ?></textarea>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_REPORT_CATEGORIES_EXAMPLE'); ?>	
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_SENDER_INFORMATION'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_FROM_NAME'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[mailFromName]" id="paramsMailFromName" size="60" maxlength="100" value="<?php echo $this->params->mailFromName; ?>" />
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_FROM_NAME_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_FROM_ADDRESS'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[mailFromAddress]" id="paramsMailFromAddress" size="60" maxlength="100" value="<?php echo $this->params->mailFromAddress; ?>" />
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_FROM_ADDRESS_EXAMPLE'); ?>	
					</td>
				</tr>
				<!-- <tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_REPLYTO_NAME'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[mailReplyName]" id="paramsMailReplyName" size="60" maxlength="100" value="<?php echo $this->params->mailReplyName; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_REPLYTO_ADDRESS'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[mailReplyAddress]" id="paramsMailReplyAddress" size="60" maxlength="100" value="<?php echo $this->params->mailReplyAddress; ?>" />
					</td>
				</tr> -->
			</table>
		</fieldset>

	</div>
	<?php echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_PAYMENT'), 'payment'); ?>
	<div class="width-100">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_INVOICE_DETAILS'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_MY_INVOICE_DETAILS'); ?>:</label>
					</td>
					<td>
						<textarea name="params[invoiceDetails]" id="paramsInvoiceDetails" rows="6" cols="30"><?php echo $this->params->invoiceDetails; ?></textarea>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_MY_INVOICE_DETAILS_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_INVOICE_FORMAT_FUND_DEPOSIT'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[invoiceFormatDeposit]" id="paramsInvoiceFormatDeposit" size="20" maxlength="30" value="<?php echo $this->params->invoiceFormatDeposit; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_( 'COM_JBLANCE_INVOICE_FORMAT_PLANS' ); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[invoiceFormatPlan]" id="paramsInvoiceFormatPlan" size="20" maxlength="30" value="<?php echo $this->params->invoiceFormatPlan; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_INVOICE_FORMAT_FUND_WITHDRAWAL'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[invoiceFormatWithdraw]" id="paramsInvoiceFormatWithdraw" size="20" maxlength="30" value="<?php echo $this->params->invoiceFormatWithdraw; ?>" />
					</td>
				</tr>
		    </table>
		    <table style="width:25%;" class="adminform">
				<tr class="row1"><td> <strong><?php echo JText::_('COM_JBLANCE_AVAILABLE_TAGS'); ?></strong></td></tr>
				<tr><td>[ID] :  <?php echo JText::_('COM_JBLANCE_ID_FUNDTRANSFER_OR_SUBSCRIPTION'); ?></td></tr>
				<tr><td>[TIME] :  <?php echo JText::_('COM_JBLANCE_UNIX_TIME_OF_PURCHASE'); ?></td></tr>
				<tr><td>[YYYY] :  <?php echo JText::_('COM_JBLANCE_YEAR_OF_DATE_PURCHASE'); ?></td></tr>
				<tr><td>[USERID] :  <?php echo JText::_('COM_JBLANCE_USER_ID_OF_THE_BUYER'); ?></td></tr>
			</table>
		</fieldset>
	</div>
	<div class="width-100">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_FUND_TAX'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_MIN_FUND_DEPOSIT'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[fundDepositMin]" id="paramsfundDepositMin" size="5" maxlength="5" value="<?php echo $this->params->fundDepositMin; ?>" />
					</td>
					<td width="50%"><?php echo JText::_('COM_JBLANCE_MIN_FUND_DEPOSIT_EXAMPLE'); ?>
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_MIN_WITHDRAW'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[withdrawMin]" id="paramsWithdrawMin" size="5" maxlength="5" value="<?php echo $this->params->withdrawMin; ?>" />
					</td>
					<td width="50%"><?php echo JText::_('COM_JBLANCE_MIN_WITHDRAW_EXAMPLE'); ?>
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_TAX_NAME'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[taxName]" id="paramsTaxName" size="25" maxlength="30" value="<?php echo $this->params->taxName; ?>" />
					</td>
					<td width="50%"><?php echo JText::_('COM_JBLANCE_TAX_NAME_EXAMPLE'); ?>
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_TAX_IN_PERCENT'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[taxPercent]" id="paramsTaxpercent" size="8" maxlength="8" value="<?php echo $this->params->taxPercent; ?>" /> %</td>
					<td width="50%"><?php echo JText::_('COM_JBLANCE_TAX_PERCENT_EXAMPLE'); ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="width-100">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_ENABLE_DISABLE_PAYMENT_OPTIONS'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="checkfundPickuser"><?php echo JText::_('COM_JBLANCE_CHECKFUND_PICKUSER'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
							<?php $checkfundPickuser = $select->YesNoBool('params[checkfundPickuser]', $this->params->checkfundPickuser);
							echo  $checkfundPickuser; ?>
						</fieldset>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_CHECKFUND_PICKUSER_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="checkfundAcceptoffer"><?php echo JText::_('COM_JBLANCE_CHECKFUND_ACCEPTOFFER'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
							<?php $checkfundAcceptoffer = $select->YesNoBool('params[checkfundAcceptoffer]', $this->params->checkfundAcceptoffer);
							echo  $checkfundAcceptoffer; ?>
						</fieldset>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_CHECKFUND_ACCEPTOFFER_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="enableEscrowPayment"><?php echo JText::_('COM_JBLANCE_ENABLE_ESCROW_PAYMENT'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
							<?php $enableEscrowPayment = $select->YesNoBool('params[enableEscrowPayment]', $this->params->enableEscrowPayment);
							echo  $enableEscrowPayment; ?>
						</fieldset>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_ENABLE_ESCROW_PAYMENT_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="enableWithdrawFund"><?php echo JText::_('COM_JBLANCE_ENABLE_WITHDRAW_FUND'); ?>:</label>
					</td>
					<td>
						<fieldset class="radio">
							<?php $enableWithdrawFund = $select->YesNoBool('params[enableWithdrawFund]', $this->params->enableWithdrawFund);
							echo  $enableWithdrawFund; ?>
						</fieldset>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_ENABLE_WITHDRAW_FUND_EXAMPLE'); ?>	
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<?php echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_UPLOADS'), 'uploads'); ?>
	<div class="width-100">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_PROJECT_FILES'); ?></legend>
			<table class="admintable">
			
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_LEGAL_MIME_TYPES'); ?>:</label>
					</td>
					<td>
						<?php if($isSuperAdmin) : ?>
							<textarea name="params[projectFileType]" id="paramsProjectFileType" rows="6" cols="30"><?php echo $this->params->projectFileType; ?></textarea>
						<?php else : ?>
							<?php echo $this->params->projectFileType; ?>
							<input type="hidden" name="params[projectFileType]" value="<?php echo $this->params->projectFileType; ?>" />
						<?php endif; ?>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_LEGAL_MIME_TYPES_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_TEXT_UPLOAD_FIELD'); ?>:</label>
					</td>
					<td>
						<textarea name="params[projectFileText]" id="paramsProjectFileText" rows="3" cols="30"><?php echo $this->params->projectFileText; ?></textarea>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_TEXT_UPLOAD_FIELD_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_MAX_SIZE_IN_KB'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[projectMaxsize]" id="paramsProjectMaxsize" size="10" maxlength="10" value="<?php echo $this->params->projectMaxsize; ?>" />&nbsp;KB
					</td> 
					<td width="50%">
						<?php echo JText::sprintf('COM_JBLANCE_MAX_SIZE_IN_KB_EXAMPLE', $uploadLimit); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_MAX_FILES_PER_PROJECT'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[projectMaxfileCount]" id="paramsProjectMaxfileCount" size="10" maxlength="10" value="<?php echo $this->params->projectMaxfileCount; ?>" />
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_MAX_FILES_PER_PROJECT_EXAMPLE'); ?>	
					</td>
				</tr>
		    </table>
		</fieldset>
	</div>
	<div class="width-100">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_IMAGES'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_LEGAL_MIME_TYPES'); ?>:</label>
					</td>
					<td>
						<?php if($isSuperAdmin) : ?>
							<textarea name="params[imgFileType]" id="paramsImgFileType" rows="6" cols="30"><?php echo $this->params->imgFileType; ?></textarea>
						<?php else : ?>
							<?php echo $this->params->imgFileType; ?>
							<input type="hidden" name="params[imgFileType]" value="<?php echo $this->params->imgFileType; ?>" />
						<?php endif; ?>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_LEGAL_MIME_TYPES_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_TEXT_UPLOAD_FIELD'); ?>:</label>
					</td>
					<td>
						<textarea name="params[imgFileText]" id="paramsImgFileText" rows="3" cols="30"><?php echo $this->params->imgFileText; ?></textarea>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_TEXT_UPLOAD_FIELD_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_WIDTH'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[imgWidth]" id="paramsImgwidth" size="10" maxlength="10" value="<?php echo $this->params->imgWidth; ?>" />&nbsp;px
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_HEIGHT'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[imgHeight]" id="paramsImgHeight" size="10" maxlength="10" value="<?php echo $this->params->imgHeight; ?>" />&nbsp;px
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_MAX_SIZE_IN_KB'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[imgMaxsize]" id="imgMaxsize" size="10" maxlength="10" value="<?php echo $this->params->imgMaxsize; ?>" />&nbsp;KB
					</td>
					<td width="50%">
						<?php echo JText::sprintf('COM_JBLANCE_MAX_SIZE_IN_KB_EXAMPLE', $uploadLimit); ?>	
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<?php echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_INTEGRATION'), 'integration'); ?>
	<div class="width-100">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_INTEGRATION_OPTIONS'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_PROFILES_USER_LIST'); ?>:</label>
					</td>
					<td>
						<?php echo JoomBriIntegration::getConfigOptions('profile'); ?>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_PROFILES_USER_LIST_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_PROFILE_PICTURE'); ?>:</label>
					</td>
					<td>
						<?php echo JoomBriIntegration::getConfigOptions('avatar'); ?>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_PROFILE_PICTURE_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_REGISTRATION'); ?>:</label>
					</td>
					<td>
						<?php 
						$link_plugin = JRoute::_('index.php?option=com_plugins&view=plugins&filter_folder=system');
						?>
						<a href="<?php echo $link_plugin; ?>"><?php echo JText::_('COM_JBLANCE_CLICK_HERE'); ?></a>
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_REGISTRATION_EXAMPLE'); ?>	
					</td>
				</tr>
		    </table>
		</fieldset>
	</div>
	<div class="width-100">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JBLANCE_FACEBOOK_CONNECT'); ?></legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_FACEBOOK_API_KEY'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[fbApikey]" id="paramsFbApikey" size="20" maxlength="100" value="<?php echo $this->params->fbApikey; ?>" />
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_FACEBOOK_API_KEY_EXAMPLE'); ?>	
					</td>
				</tr>
				<tr>
					<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_FACEBOOK_APP_SECRET'); ?>:</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="params[fbAppsecret]" id="paramsFbAppsecret" size="40" maxlength="100" value="<?php echo $this->params->fbAppsecret; ?>" />
					</td>
					<td width="50%">
						<?php echo JText::_('COM_JBLANCE_FACEBOOK_APP_SECRET_EXAMPLE'); ?>	
					</td>
				</tr>
		    </table>
		</fieldset>
	</div>
	<?php echo JHtml::_('tabs.end'); ?>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
    <?php echo JHTML::_('form.token'); ?>
</form>
