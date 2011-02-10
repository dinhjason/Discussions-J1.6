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



class DiscussionsControllerForums extends JController {


    function display() {
    
        JRequest::setVar('view', 'forums');
        
        parent::display();
        
    }
        
                
	function publish() {

		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if ( !is_array( $cid ) || count( $cid ) < 1) {
		
			$msg = '';
			
			JError::raiseWarning(500, JText::_( 'SELECT ITEM PUBLISH' ) );
			
		} 
		else {

			$model = $this->getModel( 'forums');

			if( !$model->publish( $cid, 1)) {
				JError::raiseError( 500, $model->getError());
			}

			$msg 	= JText::_( 'COFI_FORUM_PUBLISHED');
		
			$cache = &JFactory::getCache('com_discussions');
			$cache->clean();
			
		}

		$this->setRedirect( 'index.php?option=com_discussions&view=forums', $msg );
	}
        
        
	function unpublish() {

		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if ( !is_array( $cid ) || count( $cid ) < 1) {
		
			$msg = '';
			
			JError::raiseWarning(500, JText::_( 'SELECT ITEM UNPUBLISH' ) );
			
		} 
		else {

			$model = $this->getModel( 'forums');

			if( !$model->publish( $cid, 0)) {
				JError::raiseError( 500, $model->getError());
			}

			$msg 	= JText::_( 'COFI_FORUM_UNPUBLISHED');
		
			$cache = &JFactory::getCache('com_discussions');
			$cache->clean();
			
		}

		$this->setRedirect( 'index.php?option=com_discussions&view=forums', $msg );
	}




	function orderup() {

		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
		
			$id = $cid[0];
			
		} 
		else {
		
			$this->setRedirect( 'index.php?option=com_discussions&view=forums', JText::_( 'No Items Selected') );
			
			return false;
			
		}

		$model =& $this->getModel( 'forums' );
		
		if ( $model->orderup( $id)) {
		
			$msg = JText::_( 'Forum Moved Up' );
			
		} 
		else {
		
			$msg = $model->getError();
			
		}
		
		$this->setRedirect( 'index.php?option=com_discussions&view=forums', $msg );
		
	}



	function orderdown() {
		
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
		
			$id = $cid[0];
			
		} 
		else {
		
			$this->setRedirect( 'index.php?option=com_discussions&view=forums', JText::_( 'No Items Selected') );
			
			return false;
		}

		$model =& $this->getModel( 'forums' );
		
		if ( $model->orderdown( $id)) {
		
			$msg = JText::_( 'Forum Moved Down' );
			
		} 
		else {
		
			$msg = $model->getError();
			
		}
		
		$this->setRedirect( 'index.php?option=com_discussions&view=forums', $msg );
		
	}




	function edit() {	
	
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		JRequest::setVar( 'view', 'forum' );
		
		JRequest::setVar( 'hidemainmenu', 1 );

		$model 	= $this->getModel('forum');
		
		parent::display();
		
	}



	function add() {	
	
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		JRequest::setVar( 'view', 'forum' );
		
		JRequest::setVar( 'hidemainmenu', 1 );

		$model 	= $this->getModel('forum');
		
		parent::display();
		
	}



	function remove() {

		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		
		JArrayHelper::toInteger( $cid);

		if (count( $cid ) < 1) {
		
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
			
		}

		$model = $this->getModel('forum');
		
		if( !$model->delete( $cid)) {
		
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
			
		}

		$this->setRedirect( 'index.php?option=com_discussions&view=forums' );
		
	}




        
        
}
