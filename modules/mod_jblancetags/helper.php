<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 April 2012
 * @file name	:	modules/mod_jblancetags/helper.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class joomulusHelper {

	var $options;
	
	// gets module params
	function joomulusHelper($option) {
		$this->options = $option;
	}

//*********************** tags from module parameters
	function joomulus_tagwords_sd ($set_Itemid) { // build tagwords from SD list
		$Itemid = ($set_Itemid > 0) ? '&amp;Itemid='.$set_Itemid : '';
		
		//get keywords from the project table
		$now = date('Y-m-d'); // H:i:s', time() + ( $app->getCfg('offset') * 60 * 60 ) );
		//$where = "`is_active`='y' AND `publish_date` <= '$now' AND `expire_date` >= '$now' AND `expire_date` <> '0000-00-00 00:00:00'";
		$where = 'approved=1';
		
		$data = '';
		$data .= $this->getWords('jblance_project', '`project_title`, `description`', $where);
		$wordArray = $this->parseString($data, $this->options['tagCount']);
		
		$tagcloud2 ='';
		$tagcloud = '<tags>';
		
		//check if wordarray is empty array
		if(!empty($wordArray)){
			$biggest = explode('~', $wordArray[0]);
			$smallest = explode('~', $wordArray[count($wordArray)-1]);
	
			$biggest = $biggest[0];
			$smallest = $smallest[0];
			$difference = $biggest - $smallest;
		}
		else 
			$difference = 0;
		
		
		$maxSize = 25; $minSize = 10;
		$fontDifference = $maxSize-$minSize;

		//randomizes the content
		shuffle($wordArray);
		
		foreach ($wordArray as $word)
		{
			$details = explode('~',$word);
			if ($difference == 0) {
				$percent = 1;
			} else {
				$percent = round(($details[0] - $smallest) / $difference,1);
			}
			$fontSize = round($minSize + ($fontDifference*$percent));
			
			
			$url = JRoute::_( JURI::base() . 'index.php?option=com_jblance&amp;view=project&amp;layout=searchproject&amp;keyword='.$details[1].'&amp;x=60&amp;y=12'.$Itemid);
			$tagcloud2 .= "<a href='".$url."' target='".$this->options['target']."' style='font-size:".$fontSize."'>".$details[1]."</a>\n ";
		}
		
		if (trim($tagcloud2)=='') {
			$msg = JText::_('COM_JBLANCE_PROJECTS_NOT_FOUND');
			$tagcloud2 = "<a href='".JURI::base()."' target='".$this->options['target']."' style='font-size:20'>$msg</a>\n ";
		}
		$tagcloud .= $tagcloud2.'</tags>';
		return $tagcloud;
	}
	function joomulus_altdiv_sd ($tags) { // build alternative div content (only contents inside the div!!!)
		return '<p>'.$tags.'</p>';
	}
	function joomulus_flashvars_sd($tags) { // build specific flashvars for this mode
		$tagcloud2 = 'mode: "tags", ';
		$tagcloud2 .= 'tagcloud: "'. urlencode($tags) . '" ';
		return $tagcloud2;	
	}

//******************* build flash HTML with SWFobject 
	function joomulus_createflashcode($tags, $altdiv, $subflashvars ) {
		global $joomlusModCount;
		global $mainframe;
		$doc = JFactory::getDocument();
		$name = 'modJoomulus';
		$version = $name.' 1.0.7.6';
		$soname = $name.'Instance';
		$divname = $name.$joomlusModCount;
		$flashtag='';
		$chaine='';
		//gets SWF based on user params
		//$mainframe->addCustomHeadTag("\n<!-- SWFObject embed by Geoff Stearns geoff@deconcept.com http://blog.deconcept.com/swfobject/ -->");
		$doc->addCustomTag("\n<!-- SWFObject embed by Geoff Stearns geoff@deconcept.com http://blog.deconcept.com/swfobject/ -->");
		if ($this->options['swfobject']!='0') {
			//$mainframe->addCustomHeadTag("\n<script type=\"text/javascript\" src=\"".JURI::base()."modules/mod_jblancetags/swfobject.js\" ></script>\n");
			$doc->addCustomTag("\n<script type=\"text/javascript\" src=\"".JURI::base()."modules/mod_jblancetags/swfobject.js\" ></script>\n");
		}		
		$movie = JURI::base().'modules/mod_jblancetags/tagcloud'.$this->options['language'].'.swf';

		// load  expressinstall.swf file,  ideally this would be  module parameter .. 
		$expressinstall = '"'.JURI::base().'modules/mod_jblancetags/expressinstall.swf"';
	
		// add alternate div contents
		$chaine .= '<div class="modJoomulus_'.$this->options['moduleclass_sfx'].'" id="'.$divname.'">' . $altdiv . '</div>';
		
		$flashtag .= '<script type="text/javascript" >';
		$flashtag .= '	var flashvars = {';
		if ( $this->options['distr'] == '1' ) { $flashtag .= 'distr: "true",'; } else { $flashtag .= 'distr: "false",'; }
		$flashtag .= 'tcolor: "0x'.$this->options['tcolor'].'",';
		if ( $this->options['tcolor2'] == "" ) { $flashtag .= 'tcolor2: "0x'.$this->options['tcolor'].'",'; } else { $flashtag .= 'tcolor2: "0x'.$this->options['tcolor2'].'",'; }
		$flashtag .= 'hicolor: "0x'.$this->options['hicolor'].'",';
		$flashtag .= 'tspeed: "'.$this->options['speed'].'",';
		$flashtag .= 'scale_x: "'.$this->options['scale_x'].'",';
		$flashtag .= 'scale_y: "'.$this->options['scale_y'].'",';
		// add mode-specific flashvars and close flashvars section
		$flashtag .= $subflashvars . '}; ';
		$flashtag .= '	var params = {';
		if ( $this->options['trans'] == '1' ) { $flashtag .= 'wmode: "transparent",'; }
		$flashtag .= 'bgcolor: "'.$this->options['bgcolor'].'",';
		$flashtag .= 'allowscriptaccess: "sameDomain"';
		$flashtag .= '};';
		$flashtag .= ' var attributes = {';
		$flashtag .= '};';
		$flashtag .= ' var rnumber = Math.floor(Math.random()*9999999);'; // force loading of movie to fix IE weirdness
		$flashtag .= ' swfobject.embedSWF("'.$movie.'?r="+rnumber, "'.$divname.'", "'.$this->options['width'].'", "'.$this->options['height'].'", "9.0.115",'.$expressinstall.', flashvars, params, attributes);';
		$flashtag .= '</script>';
		// adds javascript to head page for loading joomulus SWF
		//$mainframe->addCustomHeadTag("\n". $flashtag."\n");
		$doc->addCustomTag("\n". $flashtag."\n");
		return $chaine;
	}
	
	//get the keywoard for the tag
	function getWords($table = 'content', $colList = '*', $where = ''){
		$dbo = JFactory::getDBO();
		$sql = 'SELECT '.$colList.' FROM #__'.$table;
		
		if (!empty($where)) { $sql .= ' WHERE '.$where; }
		
		$sql .= ' LIMIT 0, 300';
		
		$dbo->setQuery($sql);
		$dbo->execute();

		if ($results = $dbo->loadColumn()){
			//place them into 1 string without html
			$wordList = $this->concatonateWords($results);
		}
		else {
			$wordList = '';
		}

		return $wordList;
	}
	
	function concatonateWords($dataObj){
		$words = '';
		$words = implode(' ',$dataObj);
		$words = strip_tags($words);
		return $words;		
	}
	
	function parseString($string, $count = 25){

		//filters through words to get occurence value.
		$topList = array();			
		$wordList = explode(' ', $string);
		$uniqueWordList = array_unique($wordList);

		foreach ($uniqueWordList as $word){
			if(strlen($word) > 3){
				$wordCount = array_keys($wordList,$word,false);
				$wordCount = count($wordCount);
				//glitch in rsort puts 1,2,3 above 15, 16 etc
				if(strlen($wordCount) < 2) { $wordCount = '0'.$wordCount; } 
				$topList[$word] = $wordCount .'~'. $word;
			}
		}
		//return if toplist is empty - no rows/record
		if(empty($topList))
			return array();
			
		//sorts the array descending and only returns the ones the
		//amount the user wants.
		rsort($topList);
		$i = 1;
		$finalList = array();
		while ($i <= $count && $i <= count($topList)){
			array_push($finalList,$topList[$i-1]);
			$i++;
		}

		return $finalList;
	}
}	
?>