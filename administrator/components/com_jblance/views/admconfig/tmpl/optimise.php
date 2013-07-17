<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	14 March 2012
 * @file name	:	views/admconfig/tmpl/config.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Users (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $tableClass = JblanceHelper::getTableClassName();
 ?>
<form action="index.php" method="post" name="adminForm">
	<div class="jbadmin-welcome">
		<h3><?php echo JText::_('COM_JBLANCE_OPTIMISE_JOOMBRI_DATABASE');?></h3><br>
		<p><?php echo JText::_('COM_JBLANCE_OPTIMISE_JOOMBRI_DATABASE_DESC');?></p>
	</div>
	<div class="width-50 fltlft">
		<table class="<?php echo $tableClass; ?>">
			<thead>
				<tr>
					<th width="10">
						<?php echo JText::_('COM_JBLANCE_PENDING_ACTIONS'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if(count($this->results) > 0){
				$k = 0;
				foreach($this->results as $result){ ?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
					<?php echo $result; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
				}
			}
			else {
			?>
				<tr>
					<td><?php echo JText::_('COM_JBLANCE_NOTHING_TO_OPTIMISE'); ?></td>
				</tr>
			<?php 
			}?>
			</tbody>
		</table>
	</div>
	<div class="width-50 fltlft">
		<div class="jbadmin-welcome">
			<table class="adminform" border="0">
				<thead>
					<tr valign="middle" style="align:center;">
						<th>
							<?php echo JText::_('COM_JBLANCE_OPTIMISE'); ?>
						</th>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_JBLANCE_OPTIMISE_CONFIRM'); ?>
						</td>
					</tr>
				</thead>
				
				<tr valign="top" >
					<td>
						<input class="button" type="button" name="submit_app" value="<?php echo JText::_('COM_JBLANCE_OPTIMISE'); ?>" onclick="this.form.submit()"/>
					</td>
				</tr>
				<input type="hidden" name="actiontype" value="1" />
			</table>
		</div>
	</div>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="admconfig.optimise" />
	<input type="hidden" name="userIds" value="<?php echo $this->userIds; ?>" />
	<input type="hidden" name="projectIds" value="<?php echo $this->projectIds; ?>" />
</form>