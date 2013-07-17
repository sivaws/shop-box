<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	20 March 2012
 * @file name	:	views/admproject/tmpl/dashboard.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	JoomBri Admin Dashboard (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

 $link_project	 	= JRoute::_('index.php?option=com_jblance&view=admproject&layout=showproject');
 $link_user 		= JRoute::_('index.php?option=com_jblance&view=admproject&layout=showuser');
 $link_subscr		= JRoute::_('index.php?option=com_jblance&view=admproject&layout=showsubscr');
 $link_deposit		= JRoute::_('index.php?option=com_jblance&view=admproject&layout=showdeposit');
 $link_withdraw		= JRoute::_('index.php?option=com_jblance&view=admproject&layout=showwithdraw');
 $link_escrow		= JRoute::_('index.php?option=com_jblance&view=admproject&layout=showescrow'); 
 $link_reporting	= JRoute::_('index.php?option=com_jblance&view=admproject&layout=showreporting');
 $link_messages		= JRoute::_('index.php?option=com_jblance&view=admproject&layout=managemessage');
 $link_config 		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=configpanel');
 $link_summary		= JRoute::_('index.php?option=com_jblance&view=admproject&layout=showsummary');
 $link_custom_field = JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showcustomfield');
 $link_about		= JRoute::_('index.php?option=com_jblance&view=admproject&layout=about');
 $link_support	 	= 'http://www.joombri.in/forum';
 
 $tableClass = JblanceHelper::getTableClassName();
?>	
<table width="100%">
	<tr>
		<td width="100%" valign="top">
			<table width="100%" border="0">
				<tr>
					<td align="center" width="55%" valign="top">
						<table class="<?php echo $tableClass; ?>">
							<thead>
								<tr><th><?php echo JText::_('COM_JBLANCE_TITLE_DASHBOARD'); ?></th></tr>
							</thead>
							<tbody>
								<tr>
									<td align="center">
										<div id="cpanel" >
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_project; ?>"><img src="components/com_jblance/images/project.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_TITLE_PROJECTS'); ?></span></a></div>
											</div>
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_user; ?>"><img src="components/com_jblance/images/user.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_TITLE_USERS'); ?></span></a></div>
											</div>
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_subscr; ?>"><img src="components/com_jblance/images/plan.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_TITLE_SUBSCRIPTIONS'); ?></span></a></div>
											</div>
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_deposit; ?>"><img src="components/com_jblance/images/deposit.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_TITLE_DEPOSITS'); ?></span></a></div>
											</div>
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_withdraw; ?>"><img src="components/com_jblance/images/withdraw.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_TITLE_WITHDRAWALS'); ?></span></a></div>
											</div>
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_escrow; ?>"><img src="components/com_jblance/images/escrow.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_TITLE_ESCROWS'); ?></span></a></div>
											</div>
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_reporting; ?>"><img src="components/com_jblance/images/reporting.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_TITLE_REPORTINGS'); ?></span></a></div>
											</div>
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_messages; ?>"><img src="components/com_jblance/images/messages.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_TITLE_PRIVATE_MESSAGES'); ?></span></a></div>
											</div>
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_config; ?>"><img src="components/com_jblance/images/config.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_TITLE_CONFIGURATION'); ?></span></a></div>
											</div>
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_summary; ?>"><img src="components/com_jblance/images/report.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_TITLE_SUMMARY'); ?></span></a></div>
											</div>
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_custom_field; ?>"><img src="components/com_jblance/images/customfield.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_CUSTOM_FIELDS'); ?></span></a></div>
											</div>
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_about; ?>"><img src="components/com_jblance/images/about.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_TITLE_ABOUT'); ?></span></a></div>
											</div>
											<div class="jbicon-container">
												<div class="jbicon"> <a href="<?php echo $link_support; ?>"><img src="components/com_jblance/images/support.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_SUPPORT'); ?></span></a></div>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>		
					</td>
					<td width="4%"></td>
					<td width="45%" valign="top">
						<?php
						echo JHtml::_('sliders.start', 'panel-sliders', array('useCookie'=>'1'));
						echo JHtml::_('sliders.panel', JText::_('Welcome to JoomBri!'), 'welcome'); ?>
						<table class="<?php echo $tableClass; ?>">
							<tr>
								<td>
									<p style="font-weight:700;">
										Freelance component developed by BriTech Solutions
									</p>
									<p>
										Thank you for choosing JoomBri as your Freelance Web solution. This dashboard will help you manage projects, users, subscriptions and configure the component.
									</p>
									<p>
										If you require professional support just head on to the forum at 
										<a href="http://www.joombri.in/forum" target="_blank">
										http://www.joombri.in
										</a>
										For developers, you can browse through the wiki based documentations at 
										<a href="http://docs.joombri.in" target="_blank">http://docs.joombri.in</a>
									</p>
									<p>
										If you have any queries related to JoomBri component, kindly use our forum at <a href="http://www.joombri.in/forum">http://www.joombri.in/forum</a>
									</p>
								</td>
							</tr>
						</table>
						<?php
							echo JHtml::_('sliders.panel', JText::_('COM_JBLANCE_JOOMBRI_STATISTICS'), 'stats');
						?>
							<table class="<?php echo $tableClass; ?>">
								<tr>
									<td>
										<?php echo JText::_('COM_JBLANCE_TOTAL_USERS').': '; ?>
									</td>
									<td align="center">
										<strong><?php echo $this->users; ?></strong>
									</td>
								</tr>
								<tr>
									<td>
										<?php echo JText::_('COM_JBLANCE_TOTAL_JOOMBRI_USERS').': '; ?>
									</td>
									<td align="center">
										<strong><?php echo $this->jbusers; ?></strong>
									</td>
								</tr>
								<tr>
									<td>
										<?php echo JText::_('COM_JBLANCE_TOTAL_PROJECTS').': '; ?>
									</td>
									<td align="center">
										<strong><?php echo $this->projects; ?></strong>
									</td>
								</tr>
							</table>
						<?php echo JHtml::_('sliders.end'); ?>
					</td>
				</tr>	
			</table>	
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top"></td>
	</tr>
</table>