<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	14 March 2012
 * @file name	:	views/admconfig/tmpl/configpanel.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Users (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $link_dashboard 	= JRoute::_('index.php?option=com_jblance');
 $link_compsetting	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=config');
 $link_usergroup	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showusergroup');
 $link_plan			= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showplan');
 $link_paymode		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showpaymode');
 $link_customfield	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showcustomfield');
 $link_emailtemp	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=emailtemplate&tempfor=subscr-pending');
 $link_category		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showcategory');
 $link_budget		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showbudget');
 $link_optimise		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=optimise');
?>
<div class="jbadmin-welcome">
	<h3><?php echo JText::_('COM_JBLANCE_CONFIG');?></h3>
	<p><?php echo JText::_('COM_JBLANCE_CONFIG_DESC');?></p>
</div>
<div style="border:1px solid #ddd; background:#FBFBFB;">
	<table class="thisform">
		<tr class="thisform">
			<td width="100%" valign="top" class="thisform">
				<div id="cpanel">
					<div class="jbicon-container">
						<div class="jbicon"> <a href="<?php echo $link_dashboard; ?>" title="<?php echo JText::_('COM_JBLANCE_JOOMBRI_DASHBOARD');?>"><img src="components/com_jblance/images/dashboard.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_JOOMBRI_DASHBOARD'); ?></span></a></div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon"> <a href="<?php echo $link_compsetting; ?>" title="<?php echo JText::_('COM_JBLANCE_COMPONENT_SETTINGS');?>"><img src="components/com_jblance/images/component.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_COMPONENT_SETTINGS'); ?></span></a></div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon"> <a href="<?php echo $link_usergroup; ?>" title="<?php echo JText::_('COM_JBLANCE_USER_GROUPS');?>"><img src="components/com_jblance/images/usergroup.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_USER_GROUPS'); ?></span></a></div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon"> <a href="<?php echo $link_plan; ?>" title="<?php echo JText::_('COM_JBLANCE_SUBSCRIPTION_PLANS');?>"><img src="components/com_jblance/images/plan.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_SUBSCRIPTION_PLANS'); ?></span></a></div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon"> <a href="<?php echo $link_paymode; ?>" title="<?php echo JText::_('COM_JBLANCE_PAYMENT_GATEWAYS');?>"><img src="components/com_jblance/images/paymode.png" align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_PAYMENT_GATEWAYS'); ?></span></a></div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon"> <a href="<?php echo $link_customfield; ?>" title="<?php echo JText::_('COM_JBLANCE_CUSTOM_FIELDS');?>"><img src="components/com_jblance/images/customfield.png" align="middle" border="0" width="48" alt="" /><span><?php echo JText::_('COM_JBLANCE_CUSTOM_FIELDS'); ?></span></a></div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon"> <a href="<?php echo $link_emailtemp; ?>" title="<?php echo JText::_('COM_JBLANCE_EMAIL_TEMPLATES');?>"><img src="components/com_jblance/images/emailtemp.png" align="middle" border="0" width="48" alt="" /><span><?php echo JText::_('COM_JBLANCE_EMAIL_TEMPLATES'); ?></span></a></div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon"> <a href="<?php echo $link_category; ?>" title="<?php echo JText::_('COM_JBLANCE_CATEGORIES');?>"><img src="components/com_jblance/images/category.png"  align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_CATEGORIES'); ?></span></a></div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon"> <a href="<?php echo $link_budget; ?>" title="<?php echo JText::_('COM_JBLANCE_BUDGET_RANGE');?>"><img src="components/com_jblance/images/budget.png"  align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_BUDGET_RANGE'); ?></span></a></div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon"> <a href="<?php echo $link_optimise; ?>" title="<?php echo JText::_('COM_JBLANCE_OPTIMISE_DATABASE');?>"><img src="components/com_jblance/images/optimise.png"  align="middle" border="0" alt="" /><span><?php echo JText::_('COM_JBLANCE_OPTIMISE_DATABASE'); ?></span></a></div>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>