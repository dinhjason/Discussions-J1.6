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
			JToolBarHelper::cancel();
		} 
		else {
			JToolBarHelper::cancel();
		}		
		
		JToolBarHelper::divider();
		
		JToolBarHelper::preferences('com_discussions', '600', '800');
		

		$forum	=& $this->get('data');


		$lists = array();
						
		$forums[] = JHTML::_('select.option', '0', JText::_('Top'));
		require_once ( JPATH_COMPONENT.DS.'models'.DS.'forums.php');
		$forumsModel = new DiscussionsModelForums;
		$tree = $forumsModel->forumsTree( $forum);
		$forums = array_merge( $forums, $tree);		
						

		$this->assignRef( 'forum', $forum);

		$this->assignRef( 'forums', $forums);
		
		
		parent::display( $tpl);
	}

}
