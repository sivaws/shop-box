<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 March 2012
 * @file name	:	views/admconfig/tmpl/editplan.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Plans(jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 jimport('joomla.html.pane');
  
 $editor = JFactory::getEditor();
 $model = $this->getModel();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class selectHelper
 
 $config = JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 
 $tableClass = JblanceHelper::getTableClassName();
 ?>
<script type="text/javascript">
<!--
	Joomla.submitbutton = function(task){
		if (task == 'admconfig.cancelplan' || document.formvalidator.isValid(document.id('editplan-form'))) {
			Joomla.submitform(task, document.getElementById('editplan-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'));?>');
		}
	}
//-->
</script>
<form action="index.php" method="post" id="editplan-form" name="adminForm" class="form-validate">
	<fieldset class="adminform">
	<legend><?php echo JText::_('COM_JBLANCE_PLAN_SETTINGS'); ?></legend>
	<table class="admintable">
		<tr>
          	<td class="key"><?php echo JText::_('COM_JBLANCE_USER_GROUP'); ?>:</td>
          	<td>
          		<?php 
          		$group = $select->getSelectUserGroups('ug_id', $this->row->ug_id, 'COM_JBLANCE_SELECT_USERGROUP', '', '');
		    	echo  $group; ?>
          	</td>
			<td width="50%">
				<?php //echo JText::_('COM_JBLANCE_EXAMPLE_DURATION'); ?>	
			</td>
        </tr>
		<tr>
			<td class="key"><?php echo JText::_('COM_JBLANCE_PLAN_NAME'); ?>: </td>
			<td><input type="text" name="name" class="textbox required" size="60" value="<?php echo $this->row->name;?>"></td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_PLAN_NAME_EXAMPLE'); ?>	
			</td>
		</tr>
        <tr>
          	<td class="key"><?php echo JText::_('COM_JBLANCE_DURATION'); ?>:</td>
          	<td><input type="text" name="days" id="days" size="5" value="<?php echo $this->row->days;?>">
          		<?php $dur = $model->getSelectDuration('days_type', $this->row->days_type, 0, '');
			    echo  $dur; ?>
          	</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_DURATION_EXAMPLE'); ?>	
			</td>
        </tr>
        <tr>
          	<td class="key"><?php echo JText::_('COM_JBLANCE_LIMIT'); ?>:</td>
          	<td><input type="text" id="tml" name="time_limit" size="5" value="<?php echo $this->row->time_limit;?>"><?php echo JText::_('COM_JBLANCE_TIMES'); ?></td>
        	<td width="50%">
				<?php echo JText::_('COM_JBLANCE_LIMIT_TIMES_EXAMPLE'); ?>	
			</td>
		</tr>
        <tr>
			<td class="key"><?php echo JText::_('COM_JBLANCE_PRICE'); ?>:</td>
			<td><input type="text" id="prs" name="price" size="5" value="<?php echo $this->row->price;?>"></td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_PLAN_PRICE_EXAMPLE'); ?>	
			</td>
		</tr>
        <tr>
          	<td class="key"><?php echo JText::_('COM_JBLANCE_NEXT_TIME_DISCOUNT'); ?>:</td>
          	<td><input type="text" name="discount" id="dsk" size="5" value="<?php echo $this->row->discount;?>"></td>
        	<td width="50%">
				<?php echo JText::_('COM_JBLANCE_NEXT_TIME_DISCOUNT_EXAMPLE'); ?>	
			</td>
		</tr>
		 <tr>
			<td class="key"><?php echo JText::_('JPUBLISHED'); ?>:</td>
			<td><fieldset class="radio"><?php $published = $select->YesNoBool('published', $this->row->published);
			    echo  $published; ?>
			</fieldset></td>
        </tr>
        <tr>
			<td class="key"><?php echo JText::_('COM_JBLANCE_INVISIBLE'); ?>:</td>
			<td><fieldset class="radio"><?php $invisible = $select->YesNoBool('invisible', $this->row->invisible);
			    echo  $invisible; ?>
			</fieldset></td>
        </tr>
		<tr>
			<td class="key"><?php echo JText::_('COM_JBLANCE_ALERT_ADMIN_ON_SUBSCRIBE_EVENT'); ?>:</td>
			<td><fieldset class="radio"><?php $alert_admin = $select->YesNoBool('alert_admin', $this->row->alert_admin);
				echo  $alert_admin; ?>
			</fieldset></td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_ALERT_ADMIN_ON_SUBSCRIBE_EVENT_EXAMPLE'); ?>	
			</td>
        </tr>
	</table>
	</fieldset>
	
	<fieldset class="adminform">
	<legend><?php echo JText::_('COM_JBLANCE_FUND_SETTINGS'); ?></legend>
	<?php echo JHtml::_('sliders.start', 'credit-slider'); ?>
	<?php echo JHtml::_('sliders.panel', JText::_('COM_JBLANCE_GENERAL'), 'credit-general'); ?>
	<table class="<?php echo $tableClass; ?>">
		<tr>
			<td class="key"><?php echo JText::_('COM_JBLANCE_BONUS_FUND'); ?>:</td>
			<td><input type="text" name="bonusFund" id="bonusFund" size="5" value="<?php echo $this->row->bonusFund; ?>"></td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_BONUS_FUND_EXAMPLE'); ?>	
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_('COM_JBLANCE_PORTFOLIO_ITEMS'); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['portfolioCount']) ? $this->params['portfolioCount'] : 0; ?>
				<input type="text" name="params[portfolioCount]" id="portfolioCount"  size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_PORTFOLIO_ITEMS_EXAMPLE'); ?>	
			</td>
		</tr>
	</table>
        <!--  section for buyer type of user group -->
        <?php echo JHtml::_('sliders.panel', JText::_('COM_JBLANCE_FUND_SETTINGS_USERS_POSTING_PROJECTS'), 'fund-buyer'); ?>
	<table class="<?php echo $tableClass; ?>">
        <tr>
			<td class="key"><?php echo JText::sprintf('COM_JBLANCE_PROJECT_FEE_IN_AMT', $currencysym); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['buyFeeAmtPerProject']) ? $this->params['buyFeeAmtPerProject'] : 0; ?>
				<input type="text" name="params[buyFeeAmtPerProject]" id="buyFeeAmtPerProject"  size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_PROJECT_FEE_IN_AMT_EXAMPLE'); ?>	
			</td>
		</tr>
        <tr>
			<td class="key"><?php echo JText::_('COM_JBLANCE_PROJECT_FEE_IN_PERCENT'); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['buyFeePercentPerProject']) ? $this->params['buyFeePercentPerProject'] : 0; ?>
				<input type="text" name="params[buyFeePercentPerProject]" id="buyFeePercentPerProject" size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_PROJECT_FEE_IN_PERCENT_EXAMPLE'); ?>	
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::sprintf('COM_JBLANCE_CHARGE_PER_PROJECT_IN_AMT', $currencysym); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['buyChargePerProject']) ? $this->params['buyChargePerProject'] : 0; ?>
				<input type="text" name="params[buyChargePerProject]" id="buyChargePerProject" size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_CHARGE_PER_PROJECT_IN_AMT_EXAMPLE'); ?>	
			</td>
		</tr>
        <tr>
			<td class="key"><?php echo JText::sprintf('COM_JBLANCE_FEATURED_PROJECT_FEE_IN_AMT', $currencysym); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['buyFeePerFeaturedProject']) ? $this->params['buyFeePerFeaturedProject'] : 0; ?>
				<input type="text" name="params[buyFeePerFeaturedProject]" id="buyFeePerFeaturedProject" size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_FEATURED_PROJECT_FEE_IN_AMT_EXAMPLE'); ?>	
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::sprintf('COM_JBLANCE_URGENT_PROJECT_FEE_IN_AMT', $currencysym); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['buyFeePerUrgentProject']) ? $this->params['buyFeePerUrgentProject'] : 0; ?>
				<input type="text" name="params[buyFeePerUrgentProject]" id="buyFeePerUrgentProject" size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_URGENT_PROJECT_FEE_IN_AMT_EXAMPLE'); ?>	
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::sprintf('COM_JBLANCE_PRIVATE_PROJECT_FEE_IN_AMT', $currencysym); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['buyFeePerPrivateProject']) ? $this->params['buyFeePerPrivateProject'] : 0; ?>
				<input type="text" name="params[buyFeePerPrivateProject]" id="buyFeePerPrivateProject" size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_PRIVATE_PROJECT_FEE_IN_AMT_EXAMPLE'); ?>	
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::sprintf('COM_JBLANCE_SEALED_PROJECT_FEE_IN_AMT', $currencysym); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['buyFeePerSealedProject']) ? $this->params['buyFeePerSealedProject'] : 0; ?>
				<input type="text" name="params[buyFeePerSealedProject]" id="buyFeePerSealedProject" size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_SEALED_PROJECT_FEE_IN_AMT_EXAMPLE'); ?>	
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::sprintf('COM_JBLANCE_NDA_PROJECT_FEE_IN_AMT', $currencysym); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['buyFeePerNDAProject']) ? $this->params['buyFeePerNDAProject'] : 0; ?>
				<input type="text" name="params[buyFeePerNDAProject]" id="buyFeePerNDAProject" size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_NDA_PROJECT_FEE_IN_AMT_EXAMPLE'); ?>	
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_('COM_JBLANCE_PROJECTS_ALLOWED'); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['buyProjectCount']) ? $this->params['buyProjectCount'] : 0; ?>
				<input type="text" name="params[buyProjectCount]" id="buyProjectCount" size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_PROJECTS_ALLOWED_EXAMPLE'); ?>	
			</td>
		</tr>
	</table>
		  <?php echo JHtml::_('sliders.panel', JText::_('COM_JBLANCE_FUND_SETTINGS_USERS_SEEKING_PROJECTS'), 'fund-freelancer');?>
	<table class="<?php echo $tableClass; ?>">
		<!--  section for freelancer type of user group -->
       <tr>
			<td class="key"><?php echo JText::sprintf('COM_JBLANCE_PROJECT_FEE_IN_AMT', $currencysym); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['flFeeAmtPerProject']) ? $this->params['flFeeAmtPerProject'] : 0; ?>
				<input type="text" name="params[flFeeAmtPerProject]" id="flFeeAmtPerProject"  size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_FREELANCER_PROJECT_FEE_IN_AMT_EXAMPLE'); ?>	
			</td>
		</tr>
        <tr>
			<td class="key"><?php echo JText::_('COM_JBLANCE_PROJECT_FEE_IN_PERCENT'); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['flFeePercentPerProject']) ? $this->params['flFeePercentPerProject'] : 0; ?>
				<input type="text" name="params[flFeePercentPerProject]" id="flFeePercentPerProject"  size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_FREELANCER_PROJECT_FEE_IN_PERCENT_EXAMPLE'); ?>	
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::sprintf('COM_JBLANCE_CHARGE_PER_BID_IN_AMT', $currencysym); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['flChargePerBid']) ? $this->params['flChargePerBid'] : 0; ?>
				<input type="text" name="params[flChargePerBid]" id="flChargePerBid" size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_CHARGE_PER_BID_IN_AMT_EXAMPLE'); ?>	
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_('COM_JBLANCE_BIDS_ALLOWED'); ?>:</td>
			<td>
				<?php 
				$val = isset($this->params['flBidCount']) ? $this->params['flBidCount'] : 0; ?>
				<input type="text" name="params[flBidCount]" id="flBidCount" size="5" value="<?php echo $val; ?>">
			</td>
			<td width="50%">
				<?php echo JText::_('COM_JBLANCE_BIDS_ALLOWED_EXAMPLE'); ?>	
			</td>
		</tr>	
	</table>
	<?php echo JHtml::_('sliders.end'); ?>
	</fieldset>
       
    <fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_JBLANCE_DESCRIPTION' ); ?></legend>
	<table class="admintable">  
        <tr valign="top">
          <td colspan="2"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?>:<BR>
		  		<?php echo $editor->display('description',$this->row->description ,'550', '200', '20', '25');?>
			</td>
        </tr>
        <tr valign="top">
			<td colspan="2"><BR><hr><?php echo JText::_('COM_JBLANCE_FINAL_MESSAGE'); ?>:<BR>
				<textarea name="finish_msg" id="finish_msg" rows="6" cols="30"><?php echo $this->row->finish_msg; ?></textarea>        		
			</td>
        </tr>
        </table>
		</fieldset>

	<INPUT type="hidden" name="id" value="<?php echo $this->row->id; ?>">
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
