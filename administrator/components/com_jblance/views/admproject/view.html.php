<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	views/admproject/view.html.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

$document = JFactory::getDocument();
$document->addStyleSheet (JURI::base().'components/com_jblance/assets/css/style.css');

class JblanceViewAdmproject extends JViewLegacy {
	/**
	 * display method of Jblance view
	 * @return void
	 **/
	function display($tpl = null){
		$app  	= JFactory::getApplication();
		$layout =  $app->input->get('layout', 'dashboard', 'string');
		$model	= $this->getModel();
		
		if($layout == 'dashboard' or $layout == ''){
			$return  = $model->getDashboard();
			$users 	 = $return[0];
			$jbusers = $return[1];
			$projects 	 = $return[3];

			$this->assignRef('users', $users);
			$this->assignRef('jbusers', $jbusers);
			$this->assignRef('projects', $projects);
		}
		elseif($layout == 'showproject'){
			$return  = $model->getShowProject();
			$rows 	 = $return[0];
			$pageNav = $return[1];
			$lists 	 = $return[2];

			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
			$this->assignRef( 'lists', $lists );
		}
		elseif($layout == 'editproject'){
			$return 	= $model->getEditProject();
			$row 		= $return[0];
			$projfiles 	= $return[1];
			$bids 		= $return[2];
			$lists 		= $return[3];
			$fields 	= $return[4];
			$forums 	= $return[5];

			$this->assignRef('row', $row);
			$this->assignRef('projfiles', $projfiles);
			$this->assignRef('bids', $bids);
			$this->assignRef('lists', $lists);
			$this->assignRef('fields', $fields);
			$this->assignRef('forums', $forums);
		}
		elseif($layout == 'showuser'){
			$return  = $model->getShowUser();
			$rows 	 = $return[0];
			$pageNav = $return[1];
			$lists 	 = $return[2];

			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
			$this->assignRef('lists', $lists);
		}
		elseif($layout == 'edituser'){
			$return   = $model->getEditUser();
			$row 	  = $return[0];
			$lists 	  = $return[1];
			$grpLists = $return[2];
			$trans 	  = $return[3];
			$fields   = $return[4];

			$this->assignRef('row', $row);
			$this->assignRef('lists', $lists);
			$this->assignRef('grpLists', $grpLists);
			$this->assignRef('trans', $trans);
			$this->assignRef('fields', $fields);
		}
		elseif($layout == 'showsubscr'){
			$return  = $model->getShowSubscr();
			$rows 	 = $return[0];
			$pageNav = $return[1];
			$lists 	 = $return[2];
		
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
			$this->assignRef('lists', $lists);
		}
		elseif($layout == 'editsubscr'){
			$return = $model->getEditSubscr();
			$row = $return[0];
			$users = $return[1];
			$plans = $return[2];
			$lists = $return[3];
		
			$this->assignRef('row', $row);
			$this->assignRef('users', $users);
			$this->assignRef('plans', $plans);
			$this->assignRef('lists', $lists);
		}
		elseif($layout == 'showdeposit'){
			$return = $model->getShowDeposit();
			$rows = $return[0];
			$pageNav = $return[1];
			$lists 	 = $return[2];
		
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
			$this->assignRef('lists', $lists);
		}
		elseif($layout == 'showwithdraw'){
			$return = $model->getShowWithdraw();
			$rows = $return[0];
			$pageNav = $return[1];
			$lists 	 = $return[2];
		
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
			$this->assignRef('lists', $lists);
		}
		elseif($layout == 'showescrow'){
			$return = $model->getShowsEscrow();
			$rows = $return[0];
			$pageNav = $return[1];
		
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
		}
		elseif($layout == 'showsummary'){
			$return 	= $model->getShowSummary();
			$deposits 	= $return[0];
			$withdraws 	= $return[1];
			$project 	= $return[2];
			$subscrs 	= $return[3];
			$lists 	 	= $return[4];
		
			$this->assignRef('deposits', $deposits);
			$this->assignRef('withdraws', $withdraws);
			$this->assignRef('project', $project);
			$this->assignRef('subscrs', $subscrs);
			$this->assignRef('lists', $lists);
		}
		elseif($layout == 'showreporting'){
			$return = $model->getShowReporting();
			$rows = $return[0];
			$pageNav = $return[1];
		
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
		}
		elseif($layout == 'detailreporting'){
			$return = $model->getDetailReporting();
			$rows = $return[0];
			$pageNav = $return[1];
		
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
		}
		elseif($layout == 'managemessage'){
			$return = $model->getManageMessage();
			$rows = $return[0];
			$pageNav = $return[1];
			$threads = $return[2];
			$lists = $return[3];
		
			$this->assignRef('rows', $rows);
			$this->assignRef('pageNav', $pageNav);
			$this->assignRef('threads', $threads);
			$this->assignRef('lists', $lists);
		}
		elseif($layout == 'about'){
			$filename = JPATH_COMPONENT.'/jblance.xml';
			$xml 	  = simplexml_load_file($filename);
			$this->assign('version', $xml->{'version'});
		}
		elseif($layout == 'invoice'){
			$return = $model->getInvoice();
			$row = $return[0];
			//$billingAddress = $return[1];
		
			$this->assignRef('row', $row);
			//$this->assignRef('billing', $billingAddress);
		}
		
		$this->addToolbar();
		parent::display($tpl); 
?>
<table width="100%" style="table-layout:fixed;">
	<tr>
		<td style="vertical-align:top;">
		<?php
		$app = JFactory::getApplication();
		$print = $app->input->get('print', 0, 'int');
		if(!$print)
			include_once('components/com_jblance/views/joombricredit.php');
		?>
		</td>
	</tr>
</table>
<?php	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar(){
		$app  	= JFactory::getApplication();
		$layout =  $app->input->get('layout', 'dashboard', 'string');
		jbimport('toolbar');
		switch ($layout){
			//Dashboard
			case 'dashboard':
				JbToolbarHelper::_DASHBOARD();
				break;
			
			//Projects
			case 'showproject':
				JbToolbarHelper::_SHOW_PROJECT();
				break;
			
			case 'editproject' :
				JbToolbarHelper::_EDIT_PROJECT();
				break;
			
			//user
			case 'showuser':
				JbToolbarHelper::_SHOW_USER();
				break;
			
			case 'edituser' :
				JbToolbarHelper::_EDIT_USER();
				break;
			
			//Subscription
			case 'showsubscr':
				JbToolbarHelper::_SHOW_SUBSCR();
				break;
			
			case 'editsubscr':
				JbToolbarHelper::_EDIT_SUBSCR();
				break;
			
			//Deposit
			case 'showdeposit':
				JbToolbarHelper::_SHOW_DEPOSIT();
				break;
			
			//Withdrawals
			case 'showwithdraw':
				JbToolbarHelper::_SHOW_WITHDRAW();
				break;
			
			//Escrows
			case 'showescrow':
				JbToolbarHelper::_SHOW_ESCROW();
				break;
			
			//Reporting
			case 'showreporting':
				JbToolbarHelper::_SHOW_REPORTING();
				break;
			
			//Messages
			case 'managemessage':
				JbToolbarHelper::_SHOW_MESSAGE();
				break;
			
			default:
				JbToolbarHelper::_DEFAULT();
			break;
		}
	}
}