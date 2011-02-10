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
 * RSS Feed View 
 */ 
class DiscussionsViewIndex extends JView { 


	/** 
     * Renders the view 
     * 
     */ 
    function display($tpl = null) { 

		global $mainframe;

		// get parameters
		$params = &$mainframe->getParams();

		$document =& JFactory::getDocument();

		$siteName = $mainframe->getCfg('sitename');

		$document->setTitle( $siteName . " " . JText::_( 'COFI_RSS_NEWTHREADS_META_TITLE'));

		$document->setDescription( JText::_( 'COFI_RSS_NEWTHREADS_META_DESCRIPTION' ));
		
		
				
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