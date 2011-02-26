<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2011 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');



/** 
 * RSS Feed View 
 */ 
class DiscussionsViewCategory extends JView { 


	/** 
     * Renders the view 
     * 
     */ 
    function display($tpl = null) { 

		$app = JFactory::getApplication();

		// get parameters
 		$params =& JComponentHelper::getParams('com_discussions');

		$document =& JFactory::getDocument();

		$siteName = $app->getCfg('sitename');

		$_forum = $this->get('CategoryName');		
		
		$document->setTitle( $siteName . " " . JText::_( 'COFI_RSS_NEWTHREADS_IN_META_TITLE') . " " . $_forum);

		$document->setDescription( JText::_( 'COFI_RSS_NEWTHREADS_IN_META_DESCRIPTION' ) . " " . $_forum);
		
						
		$threads =& $this->get('RSSEntries');

		foreach ( $threads as $thread ) {

			$title = $this->escape( $thread->subject );
			$title = html_entity_decode( $title );
			
			$link = JRoute::_('index.php?option=com_discussions&view=thread&catid=' . $thread->cslug . '&thread=' . $thread->mslug );

			$date = ( $thread->date ? date( 'r', strtotime( $thread->date) ) : '' );

			$feeditem = new JFeedItem();
			$feeditem->title 		= $title;
			$feeditem->link 		= $link;
			$feeditem->description 	= $thread->message;
			$feeditem->date			= $date;
			$feeditem->category 	= $thread->category;
			
			$document->addItem( $feeditem );
			
		}


	}
	
	
}