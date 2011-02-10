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



/**
* Post Table class
*/
class TablePost extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var int
	 */
	var $parent_id = null;

	/**
	 * @var int
	 */
	var $cat_id = null;

	/**
	 * @var int
	 */
	var $thread = null;

	/**
	 * @var int
	 */
	var $user_id = null;

	/**
	 * @var string
	 */
	var $account = null;

	/**
	 * @var string
	 */
	var $name = null;

	/**
	 * @var string
	 */
	var $email = null;

	/**
	 * @var string
	 */
	var $ip = null;

	/**
	 * @var int
	 */
	var $type = null;

	/**
	 * @var string
	 */
	var $subject = null;

	/**
	 * @var string
	 */
	var $alias = null;

	/**
	 * @var string
	 */
	var $message = null;

	/**
	 * @var datetime
	 */
	var $date = null;

	/**
	 * @var int
	 */
	var $hits = null;

	/**
	 * @var int
	 */
	var $locked = null;

	/**
	 * @var int
	 */
	var $published = null;

	/**
	 * @var int
	 */
	var $counter_replies = null;

	/**
	 * @var datetime
	 */
	var $last_entry_date = null;

	/**
	 * @var int
	 */
	var $last_entry_user_id = null;

	/**
	 * @var int
	 */
	var $last_entry_msg_id = null;

	/**
	 * @var int
	 */
	var $sticky = null;

	/**
	 * @var int
	 */
	var $wfm = null;

	/**
	 * @var string
	 */
	var $params = null;



	function __construct(& $db) {
	
		parent::__construct( '#__discussions_messages', 'id', $db);
		
	}



	function bind($array, $ignore = '') {
	
		if (key_exists( 'params', $array ) && is_array( $array['params'] )) {
		
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
			
		}

		return parent::bind( $array, $ignore);
	}



	function check() {
	

		return true;
	}
	
	
}
