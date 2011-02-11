<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');



class DiscussionsModelForums extends JModel {


	var $_data = null;

	var $_total = null;

	var $_pagination = null;

	var $_table = null;





	function __construct() {

		global $option;
		
		parent::__construct();

		$app = JFactory::getApplication('administrator');
		
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg( 'list_limit')); 
		$limitstart = $app->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0);

		$this->setState( 'limit', $limit); 
		$this->setState( 'limitstart', $limitstart);
		
	}



	/**
	 * Method to get forum list
	 *
	 * @access public
	 * @return array
	 */
	function getData() {

		global $option;
		
		$app = JFactory::getApplication('administrator');
		
		static $items;
		
		if (isset($items)) {
			return $items;
		}

		$db =& $this->getDBO();		
		

		$search 		= $app->getUserStateFromRequest( $option.'.forums.search', 'search', '', 'string' );
		$search 		= $this->_db->getEscaped( trim( JString::strtolower( $search ) ) );
		
		$limit 			= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg( 'list_limit')); 
		$limitstart 	= $app->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0);


		$where = $this->_buildWhere();	

		if ($search) {

			$query = "SELECT * FROM #__discussions_categories " . $where ." ORDER BY parent_id, ordering";
			$db->setQuery( $query );
			$search_rows = $db->loadResultArray();
			
		}

		$query = "SELECT * FROM #__discussions_categories " . $where ." ORDER BY parent_id, ordering";		
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
		

		if ( $search) {
		
			$list1 = array();

			foreach ( $search_rows as $sid ) {
			
				foreach ( $list as $item) {
				
					if ( $item->id == $sid) {
					
						$list1[] = $item;
						
					}
					
				}
				
			}
			
			$list = $list1;
		}

		$total = count( $list );

		jimport('joomla.html.pagination');
		$this->_pagination = new JPagination( $total, $limitstart, $limit );

		$list = array_slice( $list, $this->_pagination->limitstart, $this->_pagination->limit );

		$items = $list;

		return $items;
		
	}




	/**
	 * Method to get a pagination object for forums
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination() {
	
		if ( empty( $this->_pagination)) {
		
			$this->getData();
		
		}

		return $this->_pagination;
	}



	function _buildWhere() {
	
		global $option;
		
		$app = JFactory::getApplication('administrator');
		
		
		$filter_state 		= $app->getUserStateFromRequest( 'com_discussions.forums.filter_state', 'filter_state', '', 'word' );
		$search 			= $app->getUserStateFromRequest( $option.'.forums.search', 'search', '', 'string' );
		$search 			= $this->_db->getEscaped( trim( JString::strtolower( $search ) ) );

		$where = array();
		
		if ( $filter_state ) {
		
			if ( $filter_state == 'P' ) {
			
				$where[] = 'published = 1';
				
			} else if ($filter_state == 'U' ) {
			
				$where[] = 'published = 0';
				
			}
			
		}
		
		if ($search) {
		
			$where[] = 'LOWER(name) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false );
			
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
		
	}



	function publish( $cid = array(), $publish = 1) {

		if (count( $cid )) {
		
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__discussions_categories'
						. ' SET published = ' . (int) $publish
						. ' WHERE id IN ('. $cids .')';
				
			$this->_db->setQuery( $query );
			
			if ( !$this->_db->query()) {
			
				$this->setError( $this->_db->getErrorMsg());
				
				return false;
				
			}
			
		}
		
		return true;
		
	}



	function indentRows( & $rows) {
	
		$children = array ();
				
		if( count( $rows)){
		
			foreach ( $rows as $row) {
			
				$pt = $row->parent_id;
				
				$list = @$children[$pt] ? $children[$pt] : array ();
				
				array_push( $list, $row);
				
				$children[$pt] = $list;
				
			}
		}
		
		$entries = JHTML::_( 'menu.treerecurse', 0, '', array (), $children);
				
		return $entries;
	}



	function orderup() {
	
		$app = JFactory::getApplication('administrator');
		
		$cid = JRequest::getVar('cid');
		
		$row = & JTable::getInstance('Forum', 'Table');
		
		$row->load( $cid[0]);
		
		$row->move( -1, 'parent_id = ' . $row->parent_id);
		
		$row->reorder( 'parent_id = ' . $row->parent_id);
		
		$msg = JText::_('COFI_NEW_ORDERING_SAVED');
		
		$app->redirect('index.php?option=com_discussions&view=forums', $msg);
		
	}



	function orderdown() {
	
		$app = JFactory::getApplication('administrator');
		
		$cid = JRequest::getVar('cid');
		
		$row = & JTable::getInstance('Forum', 'Table');
		
		$row->load( $cid[0]);
		
		$row->move( 1, 'parent_id = ' . $row->parent_id);
		
		$row->reorder( 'parent_id = ' . $row->parent_id);
		
		$msg = JText::_('COFI_NEW_ORDERING_SAVED');
		
		$app->redirect('index.php?option=com_discussions&view=forums', $msg);
		
	}



	function forumsTree( $row = NULL) {
	
		$db = & JFactory::getDBO();
		
		if ( isset($row->id)) {
		
			$idCheck = ' WHERE id != '.( int )$row->id;
			
		}
		else {
		
			$idCheck = null;
			
		}
		
		if ( !isset($row->parent_id)) {
		
			$row->parent_id = 0;
			
		}
		
		$query = "SELECT * FROM #__discussions_categories {$idCheck}";
				
		$query.=" ORDER BY parent_id, ordering";
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		
		if( count( $rows)){
		
			foreach ( $rows as $row) {
			
				$pt = $row->parent_id;

				$list = @$children[$pt] ? $children[$pt] : array ();
				
				array_push( $list, $row);
				
				$children[$pt] = $list;
				
			}
		}
				
		$list = JHTML::_( 'menu.treerecurse', 0, '', array (), $children);
				
		$mitems = array ();
		
		foreach ($list as $entry) {
				
			// $mitems[] = JHTML::_( 'select.option', $entry->id, '&nbsp;&nbsp;&nbsp;'.$entry->treename);
			$mitems[] = JHTML::_( 'select.option', $entry->id, $entry->name);
			
		}
		
		return $mitems;
						
	}



	
}
