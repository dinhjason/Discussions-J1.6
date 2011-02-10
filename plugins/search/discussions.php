<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	plg_discussions_search
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');



/**
 * Search Plugin for Codingfish Discussions
 *
 * @package		Joomla
 * @subpackage	Discussions
 * @since 		1.5
 */
class plgSearchDiscussions extends JPlugin {

	 
	/**
	* Get an array of search areas
	*
	* @return array
	*/
	function &onSearchAreas() {
		static $areas = array('discussions' => 'Forum');
		return $areas;
	}



	/**
	* Discussions search. Gets an array of objects, each
	* of which contains the instance variables title, text,
	* href, section, created, and browsernav
	*
	* @param string $text Search string
	* @param string $phrase Matching option, exact|any|all
	* @param string $ordering What to order by, newest|oldest|popular|alpha|category
	* @param array $areas Areas in which to search, null if search all
	* @return array Objects representing foobars
	*/
	function onSearch($text, $phrase='', $ordering='', $areas=null) {

		// check we can handle the requested search
		if (is_array($areas) && !in_array('discussions', $areas)) {
			// not one of our areas... leave it alone!
			return array();
		}
		
		
		// get the things we will need
		$db =& JFactory::getDBO();		
		
		
		// build SQL conditions WHERE clause
		$conditions = '';
		
		switch ($phrase) {
		
			case 'exact':
				// build an exact match LIKE condition
				$text = $db->Quote('%'.$db->getEscaped($text, true).'%', false);
				$conditions = $db->nameQuote('subject') . " LIKE $text" . " OR " . $db->nameQuote('message') . " LIKE $text";
				break;
				
			case 'all':
			case 'any':
			default:
				// prepare the words individually
				$wordConditions = array();
				foreach (preg_split("~\s+~", $text) as $word) {
					$word = $db->Quote('%'.$db->getEscaped($word, true).'%', false);
					$wordConditions[] = $db->nameQuote('subject') . " LIKE $word"  . " OR " . $db->nameQuote('message') . " LIKE $word";
				}
				// determine the glue and put it all together!
				$glue = ($phrase == 'all') ? ') AND (' : ') OR (';
				$conditions = '('.implode($glue, $wordConditions).')';
			break;
			
		}		
		
		
		// determine ordering
		switch ($ordering) {
		
			case 'popular':
				$order = $db->nameQuote('hits') . ' DESC';
				break;
				
			case 'alpha':
			case 'category':
				$order = $db->nameQuote('cat_id') . ' ASC';
				break;
				
			case "oldest":
				$order = $db->nameQuote('created') . ' ASC';
				break;
				
			case "newest":
			default:
				$order = $db->nameQuote('date') . ' DESC';
				break;
		}		
						
		
		// complete the query
		$query = 'SELECT m.thread, m.subject AS title, m.message AS text, m.date AS created,'
				. 'c.name AS section, '	
				. 'm.thread AS threadid, '
				. 'c.id AS categoryid, '
				. 'c.alias AS categoryalias, '
				. "CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as catslug, "
				. "CASE WHEN CHAR_LENGTH(m.alias) THEN CONCAT_WS(':', m.thread, m.alias) ELSE m.thread END as slug,	"
				. $db->Quote('2') . ' AS browsernav '
				. ' FROM #__discussions_messages m, #__discussions_categories c'
				. " WHERE ($conditions) AND m.cat_id = c.id"
			    . ' AND m.published = 1'
			    . ' AND c.private = 0'
				. " ORDER BY $order";
				
		$db->setQuery($query);
		$rows = $db->loadObjectList();		

	
		// get Discussions Itemid	
		$sqlitemid = "SELECT id FROM " . $db->nameQuote( '#__menu') . " WHERE link LIKE '%com_discussions%' AND parent = '0' AND published = '1'";
		$db->setQuery( $sqlitemid);
		$itemid = $db->loadResult();	
			
		
		foreach($rows as $key => $row) {			
				
            $rows[$key]->href = JRoute::_('index.php?option=com_discussions&view=thread&catid='.$row->catslug.'&thread='.$row->slug.'&Itemid='.$itemid );

		}
		
		
		return $rows;		
	
	}



}

