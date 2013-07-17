<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	14 March 2012
 * @file name	:	views/admconfig/view.html.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view' );

$styles = <<<EOF
#submenu-box {
	display: none !important;
}
EOF;

$document = JFactory::getDocument();
$document->addStyleDeclaration($styles);
$document->addStyleSheet (JURI::base().'components/com_jblance/assets/css/style.css');

class JblanceViewAdmconfig extends JViewLegacy {
	/**
	 * display method of Jblance view
	 * @return void
	 **/
	function display($tpl = null) { ?>
		<?php	
		$link_dashboard 	= JRoute::_('index.php?option=com_jblance&view=admproject&layout=dashboard');
		$link_compsetting	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=config');
		$link_usergroup		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showusergroup');
		$link_plan			= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showplan');
		$link_paymode		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showpaymode');
		$link_customfield	= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showcustomfield');
 		$link_emailtemp		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=emailtemplate&tempfor=subscr-pending');
 		$link_category		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showcategory');
 		$link_budget		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=showbudget');
 		$link_optimise		= JRoute::_('index.php?option=com_jblance&view=admconfig&layout=optimise');
		?>
<!--[if IE]>
<style type="text/css">

table.jbadmin-stat caption {
	display:block;
	font-size:12px !important;
	padding-top: 10px !important;
}

</style>
<![endif]-->

<div id="jbadmin">
	<div class="jbadmin-left">
		<div id="jbadmin-menu">
				<a class="jbadmin-mainmenu jbicon-db-sm" href="<?php echo $link_dashboard; ?>"><?php echo JText::_('COM_JBLANCE_JOOMBRI_DASHBOARD'); ?></a>
				<a class="jbadmin-mainmenu jbicon-component-sm" href="<?php echo $link_compsetting; ?>"><?php echo JText::_('COM_JBLANCE_COMPONENT_SETTINGS'); ?></a>
				<a class="jbadmin-mainmenu jbicon-usergroup-sm" href="<?php echo $link_usergroup; ?>"><?php echo JText::_('COM_JBLANCE_USER_GROUPS'); ?></a>
				<a class="jbadmin-mainmenu jbicon-plan-sm" href="<?php echo $link_plan; ?>"><?php echo JText::_('COM_JBLANCE_SUBSCRIPTION_PLANS'); ?></a>
				<a class="jbadmin-mainmenu jbicon-paymode-sm" href="<?php echo $link_paymode; ?>"><?php echo JText::_('COM_JBLANCE_PAYMENT_GATEWAYS'); ?></a>
				<a class="jbadmin-mainmenu jbicon-customfield-sm" href="<?php echo $link_customfield; ?>"><?php echo JText::_('COM_JBLANCE_CUSTOM_FIELDS'); ?></a>
				<a class="jbadmin-mainmenu jbicon-emailtemp-sm" href="<?php echo $link_emailtemp; ?>"><?php echo JText::_('COM_JBLANCE_EMAIL_TEMPLATES'); ?></a>
				<a class="jbadmin-mainmenu jbicon-category-sm" href="<?php echo $link_category; ?>"><?php echo JText::_('COM_JBLANCE_CATEGORIES'); ?></a>
				<a class="jbadmin-mainmenu jbicon-budget-sm" href="<?php echo $link_budget; ?>"><?php echo JText::_('COM_JBLANCE_BUDGET_RANGE'); ?></a>
				<a class="jbadmin-mainmenu jbicon-optimise-sm" href="<?php echo $link_optimise; ?>"><?php echo JText::_('COM_JBLANCE_OPTIMISE_DATABASE'); ?></a>
		</div>
	</div>
	
		<?php
		$app  	= JFactory::getApplication();
		$layout =  $app->input->get('layout', '', 'string');
		$model	= $this->getModel(); 
		
		if($layout == 'config'){
			$return = $model->getConfig();
			$row = $return[0];
			$params = $return[1];
			$this->assignRef('row', $row);
			$this->assignRef('params', $params);
		}
		elseif($layout == 'showusergroup'){
			$return = $model->getShowUserGroup();
			$rows = $return[0];
			$pageNav = $return[1];
			
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
		}
		elseif($layout == 'editusergroup'){
			$return = $model->getEditUserGroup();
			
			$row = $return[0];
			$fields = $return[1];
			$params = $return[2];
			
			$this->assignRef('row', $row);
			$this->assignRef('fields', $fields);
			$this->assignRef('params', $params);
		}
		elseif($layout == 'showplan'){
			$return = $model->getShowPlan();
			$rows = $return[0];
			$pageNav = $return[1];
			$lists = $return[2];
		
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
			$this->assignRef('lists', $lists);
		}
		elseif($layout == 'editplan'){
			$return = $model->getEditPlan();
			$row = $return[0];
			$params = $return[1];
		
			$this->assignRef('row', $row);
			$this->assignRef('params', $params);
		}
		elseif($layout == 'showpaymode'){
			$return = $model->getShowPaymode();
			$rows 	= $return[0];
			$pageNav = $return[1];
		
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
		}
		elseif($layout == 'editpaymode'){
			$return = $model->getEditPaymode();
			$paymode = $return[0];
			$params = $return[1];
			$form = $return[2];
			$this->assignRef('paymode', $paymode);
			$this->assignRef('params', $params);
			$this->assignRef('form', $form);
		}
		elseif($layout == 'showcustomfield'){
			$return = $model->getShowCustomField();
			$rows = $return[0];
			$pageNav = $return[1];
			$lists = $return[2];
			$fieldfor = $return[3];
			
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
			$this->assignRef('lists', $lists);
			$this->assignRef('fieldfor', $fieldfor);
		}
		elseif($layout == 'editcustomfield'){
			$return = $model->getEditCustomField();
			$row = $return[0];
			$groups = $return[1];
			$lists = $return[2];
			
			$this->assignRef('row', $row);
			$this->assignRef('groups', $groups);
			$this->assignRef('lists', $lists);
		}
		elseif($layout == 'emailtemplate'){
			$template = $model->getEmailTemplate();
			$this->assignRef('template', $template);
		}
		elseif($layout == 'showcategory'){
			$return = $model->getShowCategory();
		
			$rows = $return[0];
			$pageNav = $return[1];
		
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
		}
		elseif($layout == 'editcategory'){
			$row = $model->getEditCategory();
			$this->assignRef('row', $row);
		}
		elseif($layout == 'showbudget'){
			$return = $model->getShowBudget();
		
			$rows = $return[0];
			$pageNav = $return[1];
		
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
		}
		elseif($layout == 'editbudget'){
			$row = $model->getEditBudget();
			$this->assignRef('row', $row);
		}
		elseif($layout == 'optimise'){
			$return = $model->getOptimise();
			$results = $return[0];
			$userIds = $return[1];
			$projectIds = $return[2];
		
			$this->assignRef('results', $results);
			$this->assignRef('userIds', $userIds);
			$this->assignRef('projectIds', $projectIds);
		}
		?>
	<div class="jbadmin-right">
		<?php 
		$this->addToolbar();
		parent::display($tpl); 
		?>
		<table width="100%" style="table-layout:fixed;">
			<tr>
				<td style="vertical-align:top;">
					<?php
						include_once('components/com_jblance/views/joombricredit.php');
					?>
				</td>
			</tr>
		</table>
	</div>
</div>	
<?php	
	} // end of display function
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar(){
		$app  = JFactory::getApplication();
		$layout =  $app->input->get('layout', '', 'string');
		jbimport('toolbar');
		switch ($layout){
	
			//Configuration : config panel
			case 'configpanel':
				JbToolbarHelper::_CONFIG_PANEL();
				break;
	
			//Configuration : All
			case 'config':
				JbToolbarHelper::_CONFIG();
				break;
	
			//Configuration : User Group
			case 'showusergroup':
				JbToolbarHelper::_SHOW_USERGROUP();
				break;
	
			case 'editusergroup' :
				JbToolbarHelper::_EDIT_USERGROUP();
				break;
	
			//Configuration : Subscription Plans for Users
			case 'showplan':
				JbToolbarHelper::_SHOW_PLAN();
				break;
	
			case 'editplan':
				JbToolbarHelper::_EDIT_PLAN();
				break;
	
			//Configuration : Payment Modes
			case 'showpaymode':
				JbToolbarHelper::_SHOW_PAYMODE();
				break;
	
			case 'editpaymode':
				JbToolbarHelper::_EDIT_PAYMODE();
				break;
	
			//custom fields
			case 'showcustomfield':
				JbToolbarHelper::_SHOW_CUSTOM_FIELD();
				break;
	
			case 'editcustomfield':
				JbToolbarHelper::_EDIT_CUSTOM_FIELD();
				break;
	
			//Configuration : Email Templates
			case 'emailtemplate':
				JbToolbarHelper::_EMAIL_TEMPLATE();
				break;
	
			//Configuration : Category
			case 'showcategory':
				JbToolbarHelper::_SHOW_CATEGORY();
				break;
	
			case 'editcategory' :
				JbToolbarHelper::_EDIT_CATEGORY();
				break;
	
			//Configuration : Budget Range
			case 'showbudget':
				JbToolbarHelper::_SHOW_BUDGET();
				break;
	
			case 'editbudget' :
				JbToolbarHelper::_EDIT_BUDGET();
				break;
				
			case 'optimise' :
				JbToolbarHelper::_OPTIMSE();
				break;
	
			default:
				JbToolbarHelper::_DEFAULT();
			break;
		}
	}
} // end of class