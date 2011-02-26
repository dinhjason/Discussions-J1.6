<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	plg_discussions
 * @copyright	Copyright (C) 2011 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');



/**
 * Plugin for Codingfish Discussions
 *
 * @package		Joomla
 * @subpackage	Discussions
 */
class plgSystemDiscussions extends JPlugin {

	/**
	 * Store user method
	 *
	 * Method is called after user data is stored in the database
	 *
	 * @param 	array		holds the new user data
	 * @param 	boolean		true if a new user is stored
	 * @param	boolean		true if user was succesfully stored in the database
	 * @param	string		message
	 */
	function onUserAfterSave( $user, $isnew, $success, $msg) {
		
		if ( $isnew && $success) {
					
			// add a record to #__discussions_users 
			$db = JFactory::getDBO(); 

			// get Rookie Mode setting from com_discussions parameters
			$params = JComponentHelper::getParams('com_discussions');
			
			if ( $params->get('rookie', '0') == 0) { // 0 = no rookie mode
				$rookie	= 0;
			}
			else { // everything else means rookie mode
				$rookie	= 1;				
			}


			$sql = "INSERT INTO " . $db->nameQuote('#__discussions_users') . " SET " . 
					"id=" . $user['id'] . ", " . 
					"username=\"" . $user['username'] . "\", " . 
					"rookie=" . $rookie;
			
			
			$db->setQuery( $sql); 
			$db->query(); 
						
		}
		else { // user is updated
			// currently not needed
		}
		
	}


	function onUserAfterDelete( $user, $success, $msg) {
		
		$db = JFactory::getDBO(); 

		// get Delete Mode setting from com_discussions parameters
		$params = JComponentHelper::getParams('com_discussions');
		
		$deleteMode = $params->get('delete', '0');
		$deleteUser = $params->get('deleteUser', '0');
		
		
		switch( $deleteMode) {
			
			case 1: { // raw

				$sql = 'DELETE FROM '.$db->nameQuote('#__discussions_users') . ' WHERE ' . 
										$db->nameQuote('id').' = '.$user['id'];

				$db->setQuery( $sql); 
				$db->query(); 
				
				break;
			}

			case 2: { // soft

				// 1. update messages table, set all posts of this user to specified userid
				$sql = 'UPDATE '.$db->nameQuote('#__discussions_messages') . 
										' SET ' . 
										$db->nameQuote('user_id') . ' = '. $deleteUser . 
										' WHERE ' . 
										$db->nameQuote('user_id') . ' = ' . $user['id'];

				$db->setQuery( $sql); 
				$db->query(); 
				

				// 2. now delete user from users table
				$sql = 'DELETE FROM '.$db->nameQuote('#__discussions_users') . ' WHERE ' . 
										$db->nameQuote('id').' = '.$user['id'];

				$db->setQuery( $sql); 
				$db->query(); 
								
				break;
			}
			
			default: { // 0 (=disabled) and other
				// do nothing, just keep user in table
				break;
			}
			
			
		}
					

	}


}



