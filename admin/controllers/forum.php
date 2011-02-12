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

jimport('joomla.application.component.controller');



class DiscussionsControllerForum extends JController {

    function display() {
    
		$task = JRequest::getCmd('task', 'cancel');
			
		switch( $task) {
		
			case 'add' : {
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'view'  , 'forum');
				JRequest::setVar( 'edit', false );
				break;
			} 
			
			case 'edit' : {				
				
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'view'  , 'forum');
				JRequest::setVar( 'edit', true );
				break;
			}

			case 'cancel' : {
												
				JRequest::setVar( 'view'  , 'forums');
				break;
			}
			
			default : {
				break;
			}
			
		}

        
        parent::display();
        
    }



	function save() {
	
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$post	= JRequest::get('post');
		
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		$post['id'] = (int) $cid[0];

		$model = $this->getModel('forum');

		if ( $model->store( $post)) {
		
			$msg = JText::_( 'COFI_FORUM_SAVED' );
			
		} 
		else {
		
			$msg = JText::_( 'COFI_ERROR_SAVING_FORUM' );
			
		}

		$link = 'index.php?option=com_discussions&view=forums';
		
		$this->setRedirect( $link, $msg);
		
	}

        
}
