<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	04 September 2012
 * @file name	:	plugins/search/jblancesearch/jblancesearch.php
 * @copyright   :	Copyright (C) 2012 - 2013 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined( '_JEXEC' ) or die ( '' );

class plgSearchJblanceSearch extends JPlugin {
	
	public function __construct(& $subject, $config){
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	function onContentSearchAreas(){
		static $areas = array(
				'jblance' => 'PLG_SEARCH_JBLANCESEARCH_FREELANCE'
		);
		return $areas;
	}
	
	function onContentSearch($text, $phrase='', $ordering='', $areas=null){
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$now  = JFactory::getDate();
		
		//If the array is not correct, return it:
		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}
		
		//And define the parameters. For example like this..
		$limit		= $this->params->get('search_limit', 50);
		$contentLimit		= $this->params->get('content_limit', 40);
		
		//Use the function trim to delete spaces in front of or at the back of the searching terms
		$text = trim ( $text );
		
		//Return Array when nothing was filled in
		if ($text == '') {
			return array ();
		}
		
		$wheres = array ();
		switch ($phrase) {
			case 'exact':
				$text		= $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
				$wheres2 	= array();
				$wheres2[] 	= 'p.project_title LIKE '.$text;
 				$wheres2[] 	= 'ju.biz_name LIKE '.$text;
 				$wheres2[] 	= 'cv.value LIKE '.$text;
 				$wheres2[] 	= 'p.description LIKE '.$text;
				$queryStrings[] = '(' . implode( ') OR (', $wheres2 ) . ')';
				break;
		
			case 'all':
			case 'any':
			default:
				$words = explode(',', $text);
				$wheres = array();
				foreach ($words as $word) {
					$word		= $db->Quote('%'.$db->getEscaped($word, true).'%', false);
					$wheres2 	= array();
					$wheres2[] 	= 'p.project_title LIKE '.$word;
 					$wheres2[] 	= 'ju.biz_name LIKE '.$word;
 					$wheres2[] 	= 'cv.value LIKE '.$word;
 					$wheres2[] 	= 'p.description LIKE '.$word;
					$wheres[] 	= implode(' OR ', $wheres2);
				}
				$queryStrings[] = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
				break;
		}
		
		$queryStrings[] = "p.approved=1";
		$queryStrings[] = "'$now' > p.start_date ";
		
		$where = implode (' AND ', $queryStrings);
		
		$query ="SELECT DISTINCT p.*,(TO_DAYS(p.start_date) - TO_DAYS(NOW())) AS daydiff FROM #__jblance_project p".
 				" LEFT JOIN #__jblance_user ju ON p.publisher_userid = ju.user_id".
 				" LEFT JOIN #__jblance_custom_field_value cv ON cv.projectid=p.id".
 				" WHERE ".$where.
 				" ORDER BY p.id DESC";//echo $query;
		$db->setQuery ($query, 0, $limit);
		$rows = $db->loadObjectList();
		
		if ($rows) {
			foreach($rows as $key => $row) {
				$rows[$key]->href = 'index.php?option=com_jblance&view=project&layout=detailproject&id='.$row->id;
				$rows[$key]->title = $row->project_title;
				$rows[$key]->text = $row->description;
				$rows[$key]->browsernav = '';
				$rows[$key]->section = self::getCategoryNames($row->id_category);
				$rows[$key]->created = $row->start_date;
				
			}
		}
		
		//Return the search results in an array
		return $rows;
	}
	
	function getCategoryNames($id_categs){
		$db = JFactory::getDBO();
		$query = "SELECT category FROM #__jblance_category c WHERE c.id IN ($id_categs)";
		$db->setQuery($query);
		$cats = $db->loadColumn();
		return implode($cats, ", ");
	}
}
