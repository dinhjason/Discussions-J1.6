<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

require_once(JPATH_COMPONENT.DS.'classes/user.php');



/** 
 * Discussions Index Model 
 */ 
class DiscussionsModelIndex extends JModel { 

	
     
	/**
	 * Frontpage catList array
	 *
	 * @var array
	 */
	var $_data = null;
	
	/**
	 * Number of categories at this level
	 *
	 * @var integer
	 */
	var $_total = 0;
	
	     



	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct() {
		parent::__construct();
	}




	/** 
     * Gets categories data 
     * 
     * @return array 
     */ 
	function getCategories() {

		global $mainframe, $option;
		
		static $items;
		
		if (isset($items)) {
			return $items;
		}

        $params = JComponentHelper::getParams('com_discussions');        
		$_dateformat	= $params->get( 'dateformat', '%d.%m.%Y');
		$_timeformat	= $params->get( 'timeformat', '%H:%i');        		        	        		        		

		$db =& $this->getDBO();		
		
		$user =& JFactory::getUser();
		$logUser = new CofiUser( $user->id);
			
		if ( $logUser->isModerator()) {	// show me all categories				
				$query = "SELECT c.id, c.parent_id, c.name, c.alias, c.description, c.image, c.show_image, c.published, 
						c.counter_posts, c.counter_threads, 
						DATE_FORMAT( c.last_entry_date, '" . $_dateformat . " " . $_timeformat . "') AS last_entry_date, c.last_entry_user_id, u.username,
						CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as slug
						FROM ".$db->nameQuote('#__discussions_categories')."c LEFT JOIN  (".$db->nameQuote('#__users')." u) ON u.id=c.last_entry_user_id 
						WHERE c.published='1' ORDER by c.ordering ASC";
		}
		else { // only show the public forums (privates are hidden)
				$query = "SELECT c.id, c.parent_id, c.name, c.alias, c.description, c.image, c.show_image, c.published, 
						c.counter_posts, c.counter_threads, 
						DATE_FORMAT( c.last_entry_date, '" . $_dateformat . " " . $_timeformat . "') AS last_entry_date, c.last_entry_user_id, u.username,
						CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as slug
						FROM ".$db->nameQuote('#__discussions_categories')."c LEFT JOIN  (".$db->nameQuote('#__users')." u) ON u.id=c.last_entry_user_id 
						WHERE c.private='0' AND c.published='1' ORDER by c.ordering ASC";			
		}

		$db->setQuery( $query );
		$rows = $db->loadObjectList();

		
		$children = array ();
				
		if( count( $rows)){
		
			foreach ( $rows as $row) {
			
				$pt = $row->parent_id;
				
				$list = @$children[$pt] ? $children[$pt] : array ();
				
				array_push( $list, $row);
				
				$children[$pt] = $list;
				
			}
		}
		
		$list = JHTML::_( 'menu.treerecurse', 0, '', array (), $children);
		
		$items = $list;

		return $items;
		
	}

   
   
   

	/** 
     * Gets RSS entries data 
     * 
     * @return array 
     */ 
     function getRSSEntries() { 
          
		global $mainframe;
                    
                    
		// get parameters
		$params = JComponentHelper::getParams('com_discussions');
		$rssSize = $params->get('rssSize', 20);
                                       
    	$db =& $this->getDBO(); 

		$selectQuery = "SELECT m.id, m.parent_id, m.cat_id, m.thread, m.user_id, m.subject, m.message,
							CASE WHEN CHAR_LENGTH(m.alias) THEN CONCAT_WS(':', m.id, m.alias) ELSE m.id END as mslug,	
							CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as cslug,
							m.date, m.published, c.name	as category		 
						FROM " . $db->nameQuote('#__discussions_messages') . " m, " . $db->nameQuote('#__discussions_categories') . " c " .
                            " WHERE m.parent_id=0 AND m.cat_id=c.id AND m.published=1 AND c.private=0" .
						    " ORDER BY m.date DESC";
						    						    
		$this->_data = $this->_getList( $selectQuery, '0', $rssSize);
	
    	return $this->_data;    
                
     }    
   
   
     
     
} 
