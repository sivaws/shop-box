<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	13 March 2012
 * @file name	:	helpers/fields.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Helper Class to generate Fields (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class FieldsHelper {
	
	var $id	= null;
	
	//6.ShowCustom
	function getFieldHTML($ct, $id = null, $type = 'profile'){
		if(!empty($ct)){
			$row = null;
			if(!empty($id)){
				$db = JFactory::getDBO();
				if($type == 'project'){
					$query = "SELECT * FROM #__jblance_custom_field_value WHERE projectid='$id' AND fieldid='$ct->id'";
				}
				else {
					$query = "SELECT * FROM #__jblance_custom_field_value WHERE userid='$id' AND fieldid='$ct->id'";
				}
			
				$db->setQuery($query);
				$row = $db->loadObject();
			}
			$title = JText::_($ct->field_title).' :: '.JText::_($ct->tips);
			$val = (!empty($row->value)) ? $row->value : null;
			$req = '';
			if($ct->required){
				if($ct->field_type == 'Radio') $req = 'validate-Radio';
				elseif($ct->field_type == 'Checkbox') $req = 'validate-checkbox';
				elseif($ct->field_type == 'Multiple select') $req = 'validate-dropdown';
				else $req = 'required';
			}
			switch($ct->field_type){
				case 'Textbox':
					?>
					<input class="hasTip <?php echo $ct->class.' '.$req; ?>" type="text" name="custom_field_<?php echo $ct->id; ?>" id="custom_field_<?php echo $ct->id; ?>" size="60" value="<?php echo $val; ?>" title="<?php echo $title; ?>" />
				<?php
				break;
				case 'Textarea':
					//$val = (!empty($row[$ct->id]->valuetext)) ? $row[$ct->id]->valuetext : null;
					?>
					<textarea class="hasTip <?php echo $ct->class.' '.$req; ?>" rows="4" cols="50" name="custom_field_<?php echo $ct->id; ?>" id="custom_field_<?php echo $ct->id; ?>" title="<?php echo $title; ?>" ><?php echo $val; ?></textarea>
					<?php
				break;
				case 'Radio':
					if(!empty($ct->value)){
						$values = explode(";", $ct->value);
						$put = array();
						$inline = ($ct->show_type == 'left-to-right') ? 'inline' : '';	// if it is left-to-right, add inline css
						foreach($values as $value){
							if($value){
								$checked = ($val == $value) ? ' checked': '';
								?>
								<label class="radio <?php echo $inline?>">
								<input class="hasTip <?php echo $ct->class.' '.$req; ?>" type="radio" name="custom_field_<?php echo $ct->id; ?>" title="<?php echo $title; ?>" value="<?php echo $value; ?>"<?php echo $checked; ?>> <?php echo $value; ?>
								</label>
							<?php
							}
						}
					}
				break;
				case 'Checkbox':
					if(!empty($ct->value)){
						$values = explode(";", $ct->value);
						$put = array();
						if($ct->show_type == 'left-to-right'){
							$x = 0;
							foreach($values as $value){
								if($value){
									$checked = (in_array($value, explode(";", $val))) ? ' checked': '';
									?>
									<input id="c<?php echo $ct->id;?>b<?php echo $x; ?>" class="hasTip <?php echo $ct->class.' '.$req; ?>" type="checkbox" title="<?php echo $title; ?>" name="custom_field_<?php echo $ct->id; ?>[]" value="<?php echo $value; ?>"<?php echo $checked; ?>> <?php echo $value; ?>&nbsp;
									<?php
									$x++;
								}
							}
						}
						else {
							$x = 0;
							foreach($values as $value){
								if($value){
									$checked = (in_array($value, explode(";",$val))) ? ' checked': '';
									?>
									<input id="c<?php echo $ct->id;?>b<?php echo $x; ?>" class="hasTip <?php echo $ct->class.' '.$req; ?>" type="checkbox" title="<?php echo $title; ?>" name="custom_field_<?php echo $ct->id; ?>[]" value="<?php echo $value; ?>"<?php echo $checked; ?>> <?php echo $value; ?><br />
									<?php
									$x++;
								}
							}
						}
					}
				break;
				case 'Select':
					if($ct->value_type == 'custom'){
						if(!empty($ct->value)){
							$values = explode(";", $ct->value);
							$put = array();
							foreach($values as $value){
								if($value){
									$put[] = JHTML::_('select.option', $value, JText::_($value), 'value', 'text');
								}
							}
							echo JHTML::_('select.genericlist', $put, 'custom_field_'.$ct->id, "class='hasTip $ct->class $req' title='$title' size='1'", 'value', 'text', $val);
						}
					}
					else {
						$select = JblanceHelper::get('helper.select');		// create an instance of the class selectHelper
						$attribs = "class='hasTip $ct->class $req' title='$title' size='1'";
						echo $select->getSelectTable($ct->value, 'custom_field_'.$ct->id, $val, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '');
					}
				break;
				case 'Multiple Select':
					if(!empty($ct->value)){
						$values = explode(";", $ct->value);
						$put = array();
						$val =  explode(";", $val);
						foreach($values as $value){
							if($value){
								$put[] = JHTML::_('select.option', $value, JText::_($value), 'value', 'text');
							}
						}
						$size = (count($put) < 5) ? count($put) : 5;
						echo JHTML::_('select.genericlist', $put, 'custom_field_'.$ct->id.'[]', "class='hasTip $ct->class $req' title='$title' size='$size' multiple", 'value', 'text', $val );
						echo '&nbsp;';
						echo JText::_('COM_JBLANCE_HOLD_CTRL_FOR_MULTIPLE_SELECT');
					}
				break;
				case 'Location':
					$select = JblanceHelper::get('helper.select');		// create an instance of the class selectHelper
					$attribs = "class='hasTip $ct->class $req' size='10'";
					$loctree = $select->getSelectLocationTree('custom_field_'.$ct->id, $val, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '');
					echo $loctree;
				break;
				case 'Location2':
					$doc = JFactory::getDocument();
					$doc->addScript("components/com_jblance/js/Autocompleter.js");
					$doc->addStyleSheet("components/com_jblance/css/Autocompleter.css");
					
					$js = "\nwindow.addEvent('domready', function() {";
					$js .= "\n\tnew Autocompleter.Request.JSON('custom_field_$ct->id', 'index.php?option=com_jblance&task=getlocation', {";
					$js .= "\n'postVar': 'custom_field_$ct->id',
					postData: {'fieldName': 'custom_field_$ct->id'},
					'overflow': true,
					'selectMode': 'type-ahead'
					});
					});\n";
					$doc->addScriptDeclaration($js);
					?>
					<input class="hasTip <?php echo $ct->class.' '.$req; ?>" type="text" name="custom_field_<?php echo $ct->id; ?>" id="custom_field_<?php echo $ct->id; ?>" size="60" value="<?php echo $val; ?>" title="<?php echo $title; ?>" />
					
					<?php
				break;
				case 'URL':
					?>
					<input class="hasTip <?php echo $ct->class.' '.$req; ?>" type="text" name="custom_field_<?php echo $ct->id; ?>" id="custom_field_<?php echo $ct->id; ?>" size="45" value="<?php echo $val; ?>" title="<?php echo $title; ?>" />
					<?php
				break;
				case 'Email':
					?>
					<input class="hasTip <?php echo $ct->class.' '.$req; ?>" type="text" name="custom_field_<?php echo $ct->id; ?>" id="custom_field_<?php echo $ct->id; ?>" size="45" value="<?php echo $val; ?>" title="<?php echo $title; ?>" />
					<?php
				break;
				case 'Date':
					echo JHTML::_('calendar', $val, 'custom_field_'.$ct->id, 'custom_field_'.$ct->id, '%Y-%m-%d', array('class'=>$ct->class.' hasTip '.$req, 'title'=>$title));
				break;
				case 'Birthdate':
					$doc = JFactory::getDocument();
					$doc->addScript(JURI::root()."components/com_jblance/js/jason-calendar.js");
					$translation = self::translateCalendar();
					
					if ($translation)
						$doc->addScriptDeclaration($translation);
					
					if(!empty($val))
						echo "<script>DateInput('custom_field_$ct->id', true, 'YYYY-MM-DD', '$val')</script>";
					else
						echo "<script>DateInput('custom_field_$ct->id', true, 'YYYY-MM-DD')</script>";
				break;
			}
			?>
				<span class="fr">
				<?php
				$access = (!empty($row->access)) ? $row->access : 0;
				$select = JblanceHelper::get('helper.select');
				$privacy = $select->getSelectPrivacy('field_access_'.$ct->id, $access, $ct->visible);
				echo $privacy;
				?>
				</span>	
		<?php 

		
		}
	}
	
	protected static function translateCalendar(){
		static $jsscript = 0;
	
		if ($jsscript == 0){
			$return = 'WeekDays = new Array ("' . JText::_('SUNDAY', true) . '", "' . JText::_('MONDAY', true) . '", "'
			. JText::_('TUESDAY', true) . '", "' . JText::_('WEDNESDAY', true) . '", "' . JText::_('THURSDAY', true) . '", "'
			. JText::_('FRIDAY', true) . '", "' . JText::_('SATURDAY', true) . '", "' . JText::_('SUNDAY', true) . '");'
			. ' WeekDaysShort = new Array ("' . JText::_('SUN', true) . '", "' . JText::_('MON', true) . '", "' . JText::_('TUE', true) . '", "'
			. JText::_('WED', true) . '", "' . JText::_('THU', true) . '", "' . JText::_('FRI', true) . '", "' . JText::_('SAT', true) . '", "'
			. JText::_('SUN', true) . '"); MonthNames = new Array ("' . JText::_('JANUARY', true) . '", "'
			. JText::_('FEBRUARY', true) . '", "' . JText::_('MARCH', true) . '", "' . JText::_('APRIL', true) . '", "' . JText::_('MAY', true)
			. '", "' . JText::_('JUNE', true) . '", "' . JText::_('JULY', true) . '", "' . JText::_('AUGUST', true) . '", "'
			. JText::_('SEPTEMBER', true) . '", "' . JText::_('OCTOBER', true) . '", "' . JText::_('NOVEMBER', true) . '", "'
			. JText::_('DECEMBER', true) . '");' . ' MonthNamesShort = new Array ("' . JText::_('JANUARY_SHORT', true) . '", "'
			. JText::_('FEBRUARY_SHORT', true) . '", "' . JText::_('MARCH_SHORT', true) . '", "' . JText::_('APRIL_SHORT', true) . '", "'
			. JText::_('MAY_SHORT', true) . '", "' . JText::_('JUNE_SHORT', true) . '", "' . JText::_('JULY_SHORT', true) . '", "'
			. JText::_('AUGUST_SHORT', true) . '", "' . JText::_('SEPTEMBER_SHORT', true) . '", "' . JText::_('OCTOBER_SHORT', true) . '", "'
			. JText::_('NOVEMBER_SHORT', true) . '", "' . JText::_('DECEMBER_SHORT', true) . '");'
			. ' CalendarTT = {};CalendarTT["TODAY"] = "' . JText::_('JLIB_HTML_BEHAVIOR_GO_TODAY', true) . '";'
			. ' CalendarTT["YEAR"] = "' . JText::_('JYEAR', true) . '"; CalendarTT["MONTH"] = "' . JText::_('', true) . '";'
			. ' CalendarTT["DATE"] = "' . JText::_('JDATE', true) . '"; CalendarTT["CALENDAR"] = "' . JText::_('JLIB_HTML_CALENDAR', true) . '";'
			;
			$jsscript = 1;
			return $return;
		}
		else {
			return false;
		}
	}
	
	//7.showCustomFieldValue
	function getFieldHTMLValues($ct = null, $id = null, $type = 'profile'){
		if(!empty($ct)){
			$row = null;
			if(!empty($id)){
				$db = JFactory::getDBO();
				if($type == 'project'){
					$query = "SELECT * FROM #__jblance_custom_field_value WHERE projectid='$id' AND fieldid='$ct->id'";
				}
				else {
					$query = "SELECT * FROM #__jblance_custom_field_value WHERE userid='$id' AND fieldid='$ct->id'";
				}
				$db->setQuery($query);
				$row = $db->loadObject();
			}
			$val = (!empty($row->value)) ? $row->value : null;
			switch($ct->field_type){
				case 'Textbox':
				case 'Location2':
				case 'Radio':
					$val = $val;
					break;
				case 'Textarea':
					$val = nl2br($val);
					break;
				case 'Select':
					//if the field is Database type, then join with the correspondng table to get the actual value.
					if($ct->value_type == 'database'){
						$select  = JblanceHelper::get('helper.select');
						$colName = $select->getColumnName($row[$ct->id]->tblName);
						$query = "SELECT $colName FROM ".$row[$ct->id]->tblName." WHERE id=".$row[$ct->id]->value ." AND published=1";
						$db->setQuery($query);
						$val = $db->loadResult();
					}
					else
						$val = $val;
					break;
				case 'Checkbox':
				case 'Multiple Select':
					$val = explode(";", $val);
					$val = implode(", ", $val);
					$val = $val;
					break;
				case 'Location':
					$temp = JblanceHelper::getFullLocationTree($val);
					$val = implode (', ', $temp);
					break;
				case 'URL':
					if(!empty($val)){
						if(preg_match("#https?://#", $val) === 0){
							$val = 'http://'.$val;
						}
						$val = '<a href='.$val.' target="_blank">'.$val.'</a>';
					}
					else {
						$val = '';
					}
					break;
				case 'Email':
					$val = ($val != '') ? '<a href=mailto:'.$val.' target="_blank">'.$val.'</a>' : '';
					break;
				case 'Date':
				case 'Birthdate':
					$config = JblanceHelper::getConfig();
					$dformat = $config->dateFormat;
					$val = ($val != '') ? JHTML::_('date', $val, $dformat, false) : '';
					break;
			}
			if(!empty($val)){
				$val  = $this->getFieldPrivacy($val, $row->access, $row->userid, $ct->visible);
				echo $val;
			}
			else 
				echo '<span class="redfont">'.JText::_('COM_JBLANCE_NOT_MENTIONED').'</span>';
		}
	}
		
	function getFieldPrivacy($val, $access, $targetId, $visible){
		$user = JFactory::getUser();
		$myId = $user->id;
		
		$returnVal = '<span class="label label-inverse">'.JText::_('COM_JBLANCE_CONFIDENTIAL').'</span>';
		
		// if the field is personal, return it confidential
		if($visible == 'personal')
			return $returnVal;
		
		switch($access){
			case 0 :
				$returnVal = $val;
				break;
			case 10 :
				if(!$user->guest)
					$returnVal = $val;
				break;
			case 20 :
				if($myId == $targetId)
					$returnVal = $val;
				break;
			default:
				$returnVal = JText::_('COM_JBLANCE_CONFIDENTIAL');
				break;
		}
		return $returnVal;
	}
	
	//6.ShowCustom
	function getSearchFieldHTML($custom = null, $sfield = null, $type = 'profile'){
		if(!empty($custom)){
			$row = null;
			
			if(count($sfield)){
				foreach($sfield as $key=>$value){
					$row[$key] = $value;
				}
			} ?>
			<table class="" width="100%">
			<?php foreach($custom as $ct){?>
				<tr>
					<td class="key" valign="top">
						<?php 
							$title = JText::_($ct->field_title).':: '.JText::_($ct->tips);
							$labelsuffix = '';
							if($ct->field_type == 'Checkbox') $labelsuffix = '[]'; //added to validate checkbox
						?>
						<label title="<?php echo $title; ?>" class="hasTip" for="custom_field_<?php echo $ct->id.$labelsuffix; ?>"><?php echo JText::_($ct->field_title); ?><span class="redfont"><?php echo ($ct->required)? '*' : ''; ?></span>:</label>
					</td>
					<td>
						<?php
						$val = (!empty($row[$ct->id])) ? $row[$ct->id] : null;
						$req = '';
						if($ct->required){
							if($ct->field_type == 'radio') $req = 'validate-radio';
							elseif($ct->field_type == 'Checkbox') $req = 'validate-checkbox';
							elseif($ct->field_type == 'Multiple Select') $req = 'validate-dropdown';
							else $req = 'required';
						}
						switch($ct->field_type){
						case 'Radio':
							if(!empty($ct->value)){
								$values = explode(";", $ct->value);
								$put = array();
								if($ct->show_type == 'left-to-right'){
									foreach($values as $value){
										if($value){
											$put[] = JHTML::_('select.option',  $value, JText::_($value));
										}
									}
									echo JHTML::_('select.radiolist', $put, 'sfields['.$ct->id.']', "class='hasTip $ct->class $req' title='$title'", 'value', 'text', $val);?>
								<?php 
								}
								else {
									foreach($values as $value){
										if($value){
											$checked = ($val == $value) ? ' checked': '';
										?>
											<input class="hasTip <?php echo $ct->class.' '.$req; ?>" type="radio" name="sfields[<?php echo $ct->id; ?>]" title="<?php echo $title; ?>" value="<?php echo $value; ?>"<?php echo $checked; ?>> <?php echo $value; ?><br />
										<?php
										}
									}
								}
							}
						break;
						case 'Checkbox':
							if(!empty($ct->value)){
								$values = explode(";", $ct->value);
								$put = array();
								if($ct->show_type == 'left-to-right'){
									$x = 0;
									foreach($values as $value){
										if($value){
											if(!empty($val))
												$checked = (in_array($value, $val)) ? ' checked': '';
											?>
											<input id="c<?php echo $ct->id;?>b<?php echo $x; ?>" class="hasTip <?php echo $ct->class.' '.$req; ?>" type="checkbox" title="<?php echo $title; ?>" name="sfields[<?php echo $ct->id; ?>][]" value="<?php echo $value; ?>"<?php echo $checked; ?>> <?php echo $value; ?>&nbsp;
											<?php
											$x++;
										}
									}
								}
								else {
									$x = 0;
									foreach($values as $value){
										if($value){
											if(!empty($val))
												$checked = (in_array($value, $val)) ? ' checked': '';
											?>
											<input id="c<?php echo $ct->id;?>b<?php echo $x; ?>" class="hasTip <?php echo $ct->class.' '.$req; ?>" type="checkbox" title="<?php echo $title; ?>" name="sfields[<?php echo $ct->id; ?>][]" value="<?php echo $value; ?>"<?php echo $checked; ?>> <?php echo $value; ?><br />
											<?php
											$x++;
										}
									}
								}
							}
						break;
						case 'Select':
							if($ct->value_type == 'custom'){
								if(!empty($ct->value)){
									$values = explode(";", $ct->value);
									$put = array();
									foreach($values as $value){
										if($value){
											$put[] = JHTML::_('select.option', $value, JText::_($value), 'value', 'text');
										}
									}
									echo JHTML::_('select.genericlist', $put, 'sfields['.$ct->id.']', "class='hasTip $ct->class $req' title='$title' size='1'", 'value', 'text', $val);
								}
							}
							else {
								$select = JblanceHelper::get('helper.select');
								$attribs = "class='hasTip $ct->class $req' title='$title' size='1'";
								echo $select->getSelectTable($ct->value, 'sfields['.$ct->id.']', $val, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '');
							}
						break;
						case 'Location':
							$select = JblanceHelper::get('helper.select');		// create an instance of the class selectHelper
							$attribs = "class='hasTip $ct->class $req' size='10'";
							$loctree = $select->getSelectLocationTree('sfields['.$ct->id.']', $val, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '');
							echo $loctree;
						break;
						case 'Multiple Select':
							if(!empty($ct->value)){
								$values = explode(";", $ct->value);
								$put = array();
								foreach($values as $value){
									if($value){
										$put[] = JHTML::_('select.option', $value, JText::_($value), 'value', 'text');
									}
								}
								$size = (count($put) < 5) ? count($put) : 5;
								echo JHTML::_('select.genericlist', $put, 'sfields['.$ct->id.'][]', "class='hasTip $ct->class $req' title='$title' size='$size' multiple", 'value', 'text', $val);
								echo '&nbsp;';
								echo JText::_('COM_JBLANCE_HOLD_CTRL_FOR_MULTIPLE_SELECT');
							}
						break;
					}																																																																																																									
						?>
					</td>
				</tr>
			<?php } ?>
			</table>
			<?php
		}
	}
	
	//1.Insert Custom Field
	function saveFieldValues($type, $id, $post){
		$db = JFactory::getDBO();
		
		$query = "SELECT * FROM #__jblance_custom_field WHERE field_for='$type' AND published=1";
		$db->setQuery($query);
		$custom = $db->loadObjectList();
		
		if(count($custom)){
			foreach($custom as $ct){
				if(isset($post['custom_field_'.$ct->id])){
					if($type == 'project'){
						$query = "SELECT COUNT(*) FROM #__jblance_custom_field_value WHERE fieldid='$ct->id' AND projectid='$id'";
					}
					else {
						$query = "SELECT COUNT(*) FROM #__jblance_custom_field_value WHERE fieldid='$ct->id' AND userid='$id'";
					}
					$db->setQuery($query);
					//$fvalue = 'value';
					$value = $post['custom_field_'.$ct->id];
					$value = (is_array($value)) ? implode(";", $value) : addslashes($value); // $value will be array in case of multiple select and checkbox
					$access = $post['field_access_'.$ct->id];
					
					switch($ct->field_type){
						case 'Textarea':
							//$fvalue = 'valuetext';
							break;
						case 'Checkbox':
						case 'Multiple Select':
							//$value = (is_array($value)) ? implode(";", $value) : $value;
							break;
					}
					if($db->loadResult()){
						if($type == 'project'){
							$query = "UPDATE #__jblance_custom_field_value SET value='$value', access='$access' WHERE fieldid='$ct->id' AND projectid='$id'";
						}
						else {
							$query = "UPDATE #__jblance_custom_field_value SET value='$value', access='$access' WHERE fieldid='$ct->id' AND userid='$id'";
						}
					}
					else {
						if($type == 'project'){
							$query = "INSERT INTO #__jblance_custom_field_value (fieldid, projectid, value, access) VALUES ('$ct->id', '$id', '$value', '$access')";
						}
						else {
							$query = "INSERT INTO #__jblance_custom_field_value (fieldid, userid, value, access) VALUES ('$ct->id', '$id', '$value', '$access')";
						}
					}//echo $query;exit;
					$db->setQuery($query);
					if(!$db->execute()){
						JError::raiseError(500, $db->getErrorMsg());
					}
				}
			}
		}
	}
	

	/**
	 * Return the custom profile data based on the given field code
	 *
	 * @param	string	$fieldCode	The field code that is given for the specific field.
	 */	 	
	public function getFieldValue($fieldId, $user_project_id, $field_type = 'profile'){
		
		$db		= JFactory::getDBO();
		
		$user_or_project = ($field_type == 'profile') ? 'b.userid' : 'b.projectid';

		/* $query	= "SELECT b.*,a.field_type,a.value_type,a.value AS tblName FROM #__jblance_custom_field AS a "
				. "INNER JOIN #__jblance_custom_field_value AS b ON b.fieldid=a.id AND $user_or_project=".$db->quote($user_project_id)." "
				. "INNER JOIN #__jblance_user AS c ON c.user_id= b.userid "
				. "LEFT JOIN #__jblance_usergroup_field AS d ON c.ug_id = d.parent AND d.field_id = b.fieldid "
				. "WHERE b.fieldid =".$db->quote($fieldId);echo $query.'<br>'; */
		$query	= "SELECT b.*,a.field_type,a.value_type,a.value AS tblName FROM #__jblance_custom_field AS a ".
				  "INNER JOIN #__jblance_custom_field_value AS b ON b.fieldid=a.id AND $user_or_project=".$db->quote($user_project_id)." ".
				  "WHERE b.fieldid =".$db->quote($fieldId);//echo $query.'<br>';
				
		$db->setQuery($query);
		$result	= $db->loadObject();

		if($db->getErrorNum()){
			JError::raiseError(500, $db->stderr());
		}
		
		//if the field is Database type, then join with the correspondng table to get the actual value.
		if($result->value_type == 'database'){
			$colName = selectHelper::getColumnName($result->tblName);
			$query = "SELECT $colName FROM $result->tblName WHERE id=$result->value AND published=1 ORDER BY ordering";
			$db->setQuery($query);
			$result->value = $db->loadResult();
		}
		
		//if the field is Database type, then join with the correspondng table to get the actual value.
		/* if($result->field_type == 'Textarea'){
			$result->value = $result->valuetext;
		} */
		
		return $result->value;
	}
	
	
	public function &getUserGroupTypeFields($groupId){
		$app	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		
		//get all the fields
		$query	= "SELECT * FROM #__jblance_custom_field ".
				  "WHERE published=1 ".
				  "ORDER BY ordering";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		//get the id for the usergroup
		$query	= 'SELECT '.$db->quoteName('field_id').' FROM '.$db->quoteName('#__jblance_usergroup_field')
				. ' WHERE '.$db->quoteName('parent').'='.$db->quote($groupId);
		$db->setQuery($query);
		$filterIds	= $db->loadColumn();
		
		$parents = $children = array();
		
		foreach($rows as $ct){
			if($ct->parent == 0){
				if(self::hasChild($ct->id, $filterIds))	//add if the parent has children, else not
					$parents[] = $ct;
			}
			else {
				if(in_array($ct->id, $filterIds))			//add if the children is available for the particular usergroup
					$children[] = $ct;
			}
		}
		$ordered = '';
		$data = array();
		
		if(count($parents)){
			foreach($parents as $pt){
				$ordered[] = $pt;
				foreach($children as $ct){
					if($ct->parent == $pt->id){
						$ordered[]= $ct;
					}
				}
			}
			$rows = $ordered;
		}
		
		return $rows;
	}
	
	function hasChild($parentId, $filterIds){
		$db 	= JFactory::getDBO();
		$query = "SELECT id FROM #__jblance_custom_field WHERE parent=".$db->Quote($parentId);
		$db->setQuery($query);
		$childIds	= $db->loadColumn();
		
		if(array_intersect($childIds, $filterIds))
			return true;
	}

}

?>