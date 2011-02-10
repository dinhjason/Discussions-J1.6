<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */

// Check to ensure this file is included in Joomla! 
defined('_JEXEC') or die('Restricted Access'); 


jimport('joomla.application.component.controller'); 



/** 
 * Discussions Controller Index
 * 
 */ 
class DiscussionsController extends JController { 

	/** 
	 * Display 
	 * 
	 */ 
	function display() {
					
			// Set a default view if none exists
			if ( ! JRequest::getCmd( 'view' ) ) {
				JRequest::setVar('view', 'index' );
			}
				
	        // display index
	        parent::display(); 
	
	} 


} 

