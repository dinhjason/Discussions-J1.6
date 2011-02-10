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
* User Table class
*/
class TableUser extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var string
	 */
	var $username = null;

	/**
	 * @var int
	 */
	var $view = null;

	/**
	 * @var int
	 */
	var $ordering = null;

	/**
	 * @var int
	 */
	var $posts = null;

	/**
	 * @var int
	 */
	var $status = null;

	/**
	 * @var string
	 */
	var $avatar = null;

	/**
	 * @var string
	 */
	var $signature = null;

	/**
	 * @var string
	 */
	var $title = null;

	/**
	 * @var string
	 */
	var $zipcode = null;

	/**
	 * @var string
	 */
	var $city = null;

	/**
	 * @var string
	 */
	var $country = null;

	/**
	 * @var int
	 */
	var $moderator = null;

	/**
	 * @var int
	 */
	var $moderated = null;

	/**
	 * @var int
	 */
	var $rookie = null;

	/**
	 * @var int
	 */
	var $trusted = null;

	/**
	 * @var int
	 */
	var $images = null;

	/**
	 * @var int
	 */
	var $files = null;

	/**
	 * @var string
	 */
	var $website = null;

	/**
	 * @var string
	 */
	var $twitter = null;

	/**
	 * @var string
	 */
	var $facebook = null;

	/**
	 * @var string
	 */
	var $flickr = null;

	/**
	 * @var string
	 */
	var $youtube = null;

	/**
	 * @var int
	 */
	var $email_notification = null;

	/**
	 * @var int
	 */
	var $approval_notification = null;

	/**
	 * @var int
	 */
	var $show_online_status = null;



	/**
	 * @var string
	 */
	var $params = null;



	function __construct(& $db) {
	
		parent::__construct( '#__discussions_users', 'id', $db);
		
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
