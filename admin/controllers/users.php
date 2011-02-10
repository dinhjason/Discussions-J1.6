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



class DiscussionsControllerUsers extends JController {


    function display() {
    
        JRequest::setVar('view', 'users');
        
        parent::display();
        
    }
        
                

	function edit() {	
	
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		JRequest::setVar( 'view', 'user' );
		
		JRequest::setVar( 'hidemainmenu', 1 );

		$model 	= $this->getModel('user');
		
		parent::display();
		
	}

        
        
}
