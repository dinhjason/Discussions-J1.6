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


class DiscussionsViewDashboard extends JView {

	function display($tpl = null) {
	
		$model = &$this->getModel();
		
		$latestEntries = $model->getLatestEntries();
		$this->assignRef('latestEntries',$latestEntries);
				
		$numOfEntries = $model->countEntries();
		$this->assignRef('numOfItems',$numOfItems);
		
		$numOfCategories = $model->countCategories();
		$this->assignRef('numOfCategories',$numOfCategories);
	
		$user = & JFactory::getUser();
	
		JToolBarHelper::title( "Discussions - " . JText::_('COFI_DASHBOARD'), "discussions.png");		
		
		JToolBarHelper::preferences('com_discussions', '600', '800');
		
	
		JSubMenuHelper::addEntry(JText::_('COFI_DASHBOARD'), 'index.php?option=com_discussions', true);
		JSubMenuHelper::addEntry(JText::_('COFI_FORUMS'), 'index.php?option=com_discussions&view=forums');
		JSubMenuHelper::addEntry(JText::_('COFI_POSTS'), 'index.php?option=com_discussions&view=posts');
		JSubMenuHelper::addEntry(JText::_('COFI_USERS'), 'index.php?option=com_discussions&view=users');

			
			
		require_once ( JPATH_COMPONENT.DS.'models'.DS.'posts.php');
		$postsModel = new DiscussionsModelPosts;
		$latestPosts = $postsModel->latestPosts( 10);  // get 10 latest posts		
		$this->assignRef( 'latestPosts', $latestPosts);

			
			
		parent::display($tpl);
	}

}
