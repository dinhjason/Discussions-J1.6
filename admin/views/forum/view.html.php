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

jimport('joomla.application.component.view');


class DiscussionsViewForum extends JView {

	function display($tpl = null) {
	
		$model = &$this->getModel();
			
		$user = & JFactory::getUser();
			
		$edit = JRequest::getVar('edit',true);
		$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
		
		
		JToolBarHelper::title(   "Discussions - " . JText::_('COFI_FORUM') . ': <small><small>[ ' . $text.' ]</small></small>', "discussions.png" );		
		
		JToolBarHelper::save();
		
		if ( !$edit) {
//			JToolBarHelper::cancel();
			JToolBarHelper::cancel('discussions.cancel', 'JTOOLBAR_CANCEL');
		} 
		else {
//			JToolBarHelper::cancel( 'cancel', 'Close' );
			JToolBarHelper::cancel('discussions.cancel', 'JTOOLBAR_CLOSE');
			
		}		
		
		JToolBarHelper::divider();
		
		JToolBarHelper::preferences('com_discussions', '500', '600');
		
	
		JSubMenuHelper::addEntry(JText::_('COFI_DASHBOARD'), 'index.php?option=com_discussions');
		JSubMenuHelper::addEntry(JText::_('COFI_FORUMS'), 'index.php?option=com_discussions&view=forums', true);
		JSubMenuHelper::addEntry(JText::_('COFI_POSTS'), 'index.php?option=com_discussions&view=posts');
		JSubMenuHelper::addEntry(JText::_('COFI_USERS'), 'index.php?option=com_discussions&view=users');


		$forum	=& $this->get('data');


		$lists = array();
						
		$forums[] = JHTML::_('select.option', '0', JText::_('Top'));
		require_once ( JPATH_COMPONENT.DS.'models'.DS.'forums.php');
		$forumsModel = new DiscussionsModelForums;
		$tree = $forumsModel->forumsTree( $forum);
		$forums = array_merge( $forums, $tree);		
		$lists['parent'] = JHTML::_('select.genericlist', $forums, 'parent_id', 'class="inputbox"', 'value', 'text', $forum->parent_id);
				
		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $forum->published );
		

		$this->assignRef( 'lists', $lists);
		$this->assignRef( 'forum', $forum);

		
		parent::display( $tpl);
	}

}
