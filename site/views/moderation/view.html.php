<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');



/** 
 * Moderation View 
 */ 
class DiscussionsViewModeration extends JView { 


	/** 
     * Renders the view 
     * 
     */ 
    function display() { 
				
        $task                   =& $this->get('Task');
        $thread                 =& $this->get('Thread');
        $categoryFrom           =& $this->get('CategoryFrom');
        $categoryTo           	=& $this->get('CategoryTo');
		$postingsWFM            =& $this->get('PostingsWFM');


		$this->assignRef('task', $task);
		$this->assignRef('thread', $thread);
		$this->assignRef('categoryFrom', $categoryFrom);
		$this->assignRef('categoryTo', $categoryTo);
        $this->assignRef('postingsWFM', $postingsWFM);
                
        // display the view 
        parent::display(); 

    }


}

?>