<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	20 March 2012
 * @file name	:	views/admproject/tmpl/about.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	JoomBri Admin Dashboard (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
?>
<table>
	<tr>
		<td width="100%">
			<img src="http://www.joombri.in/images/documents/joombri-logo.png" border="0" alt="JoomBri!" />
		</td>
	</tr>
	<tr>
		<td>		
			<h3>About the Team</h3>
			<p>
			The team behind JoomBri, BriTech Solutions is in the software
			development and maintenance for more than two years. Our aim is to develop softwares to enhance open source technologies like Joomla!,
			and yet agile in emerging technologies as we continuously explore the constantly changing frontier of
			software development.
			</p>
			<p>Please visit <a href="http://www.britech.in">www.britech.in </a>to find out more about us.</p>
		</td>
	</tr>
	<tr>
		<td>
			<div style="font-weight: 700;">
				<?php echo JText::sprintf('COM_JBLANCE_VERSION', $this->version); ?>
			</div>
		</td>
	</tr>
</table>