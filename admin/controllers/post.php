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



class DiscussionsControllerPost extends JController {

    function display() {
    
        JRequest::setVar('view', 'post');


		switch( $this->getTask()) {
		
			case 'add' : {
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'view'  , 'post');
				JRequest::setVar( 'edit', false );
				break;
			} 
			
			case 'edit' : {
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'view'  , 'post');
				JRequest::setVar( 'edit', true );
				break;
			}

			case 'cancel' : {
				JRequest::setVar( 'view'  , 'posts');
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

		$model = $this->getModel('post');

		if ( $model->store( $post)) {
		
			$msg = JText::_( 'COFI_POST_SAVED' );
			
		} 
		else {
		
			$msg = JText::_( 'COFI_ERROR_SAVING_POST' );
			
		}

		$link = 'index.php?option=com_discussions&view=posts';
		
		$this->setRedirect( $link, $msg);
		
	}



        
}
