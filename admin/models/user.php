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


class DiscussionsModelUser extends JModel {

	var $_id = null;

	var $_data = null;



	function __construct() {

		parent::__construct();

		$array  = JRequest::getVar( 'cid', array(0), '', 'array');
		$edit	= JRequest::getVar( 'edit', true);
		
		if($edit) {
		
			$this->setId( (int)$array[0]);
			
		}
						
	}



	function setId( $id) {
	
		$this->_id		= $id;
		
		$this->_data	= null;
		
	}



	function &getData() {
	
		if ( $this->_loadData()) {

			$user = &JFactory::getUser();

		}
		else  {
			$this->_initData();
		}

		return $this->_data;
	}



	function _loadData() {
	
		if (empty($this->_data)) {
		
			$query = 'SELECT * FROM #__discussions_users WHERE id = '.(int) $this->_id;
					
			$this->_db->setQuery($query);
			
			$this->_data = $this->_db->loadObject();
			
			return (boolean) $this->_data;
			
		}
		
		return true;
		
	}



	function store( $data) {
	
		$row =& $this->getTable();

		if ( !$row->bind($data)) {
		
			$this->setError($this->_db->getErrorMsg());
			
			return false;
			
		}

		if ( !$row->check()) {
		
			$this->setError( $this->_db->getErrorMsg());
			
			return false;
			
		}

		if ( !$row->store()) {
		
			$this->setError( $this->_db->getErrorMsg());
			
			return false;
			
		}

		return true;
	}



	function _initData() {

		if (empty($this->_data)) {
		
			$user = new stdClass();
			
			$user->id			= 0;
			$user->username		= "";
			$user->view			= 0;
			$user->ordering		= 0;
			$user->posts		= 0;
			$user->status		= 0;
			
			$user->avatar		= "";
			$user->signature	= "";
			$user->title		= "";

			$user->zipcode		= "";
			$user->city			= "";
			$user->country		= "";
			
			$user->moderator	= 0;
			$user->moderated	= 0;
			$user->rookie		= 0;
			$user->trusted		= 0;
			
			$user->images		= 0;
			$user->files		= 0;			

			$user->website		= "";
			$user->twitter		= "";
			$user->facebook		= "";
			$user->flickr		= "";
			$user->youtube		= "";

			$user->email_notification		= 0;
			$user->approval_notification	= 0;
			$user->show_online_status		= 0;						
									
			$this->_data		= $user;
			
			return (boolean) $this->_data;
			
		}
		
		return true;
		
	}



}