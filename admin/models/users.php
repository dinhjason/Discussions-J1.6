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


class DiscussionsModelUsers extends JModel {


	var $_data = null;

	var $_total = null;

	var $_pagination = null;

	var $_table = null;





	function __construct() {
	
		parent::__construct();

		$option = "com_discussions";
		
		$app 		= JFactory::getApplication();		

		$limit		= $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );

		$limitstart	= $app->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);

		$this->setState('limitstart', $limitstart);

	}



	function getData() {

		if ( empty( $this->_data)) {
		
			$query = $this->_buildQuery();
						
			$this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit'));
			
		}

		return $this->_data;
	}



	function getTotal() {

		if ( empty( $this->_total)) {
		
			$sql = $this->_buildCountQuery();
			
			$db	=& JFactory::getDBO();

			$db->setQuery( $sql);

			$_counter = $db->loadResult();

			$this->_total = $_counter;

		}

		return $this->_total;
		
	}



	function getPagination() {

		if (empty($this->_pagination)) {
		
			jimport('joomla.html.pagination');
			
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
			
		}

		return $this->_pagination;
		
	}




	function _buildQuery() {

		$where		= $this->_buildContentWhere();
		
		$orderby	= $this->_buildContentOrderBy();
		
		$query = "SELECT id, username, view, ordering, posts, status, avatar, signature, title, zipcode, city, country, moderator, email_notification, approval_notification, moderated, rookie, trusted, images, files, show_online_status " .
					" FROM #__discussions_users " . $where . $orderby;

		return $query;
		
	}


	function _buildCountQuery() {

		$where		= $this->_buildContentWhere();
				
		$query = "SELECT count(*) FROM #__discussions_users " . $where;

		return $query;
		
	}



	function _buildContentOrderBy() {
	
		$option = "com_discussions";

		$app = JFactory::getApplication();		

		$filter_order		= $app->getUserStateFromRequest( $option.'filter_order',		'filter_order',		'ordering',	'cmd' );
		
		$filter_order_Dir	= $app->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'',	'word' );

		if ($filter_order == 'ordering'){
		
			$orderby 	= ' ORDER BY username ASC '.$filter_order_Dir;
			
		} 
		else {
		
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , id ';
			
		}

		return $orderby;
		
	}



	function _buildContentWhere() {
	
		$option = "com_discussions";
		
		$app = JFactory::getApplication();		
		
		$db					=& JFactory::getDBO();
		
		$filter_state		= $app->getUserStateFromRequest( $option.'filter_state', 'filter_state',	'',	'word' );
		$search				= $app->getUserStateFromRequest( $option.'search', 'search', '', 'string' );
		$search				= JString::strtolower( $search );

		$where = array();

		if ($search) {
			$where[] = 'LOWER(username) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}
		

		$where = ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
		
	}

	
	
	
}
