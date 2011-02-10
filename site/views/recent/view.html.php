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
 * Recent View
 */
class DiscussionsViewRecent extends JView {


	/**
     * Renders the view
     *
     */
    function display() {

		$_title = "";

		$document   =& JFactory::getDocument();
		$app 		= JFactory::getApplication();
		$pathway	=& $app->getPathway();

		$postings   =& $this->get('Postings');
		$pagination =& $this->get('Pagination');

        $hours      =& $this->get('Hours');

		// get parameters
		$params = &$app->getParams();

		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();

/*
		if (is_object( $menu )) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	JText::_( 'Forums' ));
			}
		} else {
			$params->set('page_title',	JText::_( 'Forums' ));
		}
*/


		//set breadcrumbs
		if( is_object($menu) && $menu->query['view'] != 'recent') {
            
            $_history = JText::_( 'COFI_HISTORY') . " " . $hours . " " . JText::_( 'COFI_HISTORY_HOURS');

            $pathway->addItem( $_history);

		}




		// Set Meta Tags
			
		switch( $hours) {
		
			case "4": {
		
				// 1. Meta Title
				if ( JText::_( 'COFI_RECENT_4H_META_TITLE') == "") {
					$_title = JText::_( 'COFI_HISTORY');
					$_title .=  " " . $hours . " ";
					$_title .= JText::_( 'COFI_HISTORY_HOURS');		
					$document->setTitle( $_title);
				}
				else { // use the meta title configured for this recent page
					$document->setTitle( JText::_( 'COFI_RECENT_4H_META_TITLE'));
				}
		
				// 2. Meta Description
				if ( JText::_( 'COFI_RECENT_4H_META_DESCRIPTION') == "") {
					$document->setDescription( $document->getTitle());
				}
				else { // use the meta description configured for this recent page
					$document->setDescription( JText::_( 'COFI_RECENT_4H_META_DESCRIPTION'));
				}
		
				// 3. Meta Keywords
				if ( JText::_( 'COFI_RECENT_4H_META_KEYWORDS') == "") {
					// $document->setMetaData( "keywords", "");
				}
				else { // use the meta keywords configured for this recent page
					$document->setMetaData( "keywords", JText::_( 'COFI_RECENT_4H_META_KEYWORDS'));
				}				

				break;
				
			}


			case "8": {
		
				// 1. Meta Title
				if ( JText::_( 'COFI_RECENT_8H_META_TITLE') == "") {
					$_title = JText::_( 'COFI_HISTORY');
					$_title .=  " " . $hours . " ";
					$_title .= JText::_( 'COFI_HISTORY_HOURS');		
					$document->setTitle( $_title);
				}
				else { // use the meta title configured for this recent page
					$document->setTitle( JText::_( 'COFI_RECENT_8H_META_TITLE'));
				}
		
				// 2. Meta Description
				if ( JText::_( 'COFI_RECENT_8H_META_DESCRIPTION') == "") {
					$document->setDescription( $document->getTitle());
				}
				else { // use the meta description configured for this recent page
					$document->setDescription( JText::_( 'COFI_RECENT_8H_META_DESCRIPTION'));
				}
		
				// 3. Meta Keywords
				if ( JText::_( 'COFI_RECENT_8H_META_KEYWORDS') == "") {
					// $document->setMetaData( "keywords", "");
				}
				else { // use the meta keywords configured for this recent page
					$document->setMetaData( "keywords", JText::_( 'COFI_RECENT_8H_META_KEYWORDS'));
				}				

				break;
				
			}


			case "12": {
		
				// 1. Meta Title
				if ( JText::_( 'COFI_RECENT_12H_META_TITLE') == "") {
					$_title = JText::_( 'COFI_HISTORY');
					$_title .=  " " . $hours . " ";
					$_title .= JText::_( 'COFI_HISTORY_HOURS');		
					$document->setTitle( $_title);
				}
				else { // use the meta title configured for this recent page
					$document->setTitle( JText::_( 'COFI_RECENT_12H_META_TITLE'));
				}
		
				// 2. Meta Description
				if ( JText::_( 'COFI_RECENT_12H_META_DESCRIPTION') == "") {
					$document->setDescription( $document->getTitle());
				}
				else { // use the meta description configured for this recent page
					$document->setDescription( JText::_( 'COFI_RECENT_12H_META_DESCRIPTION'));
				}
		
				// 3. Meta Keywords
				if ( JText::_( 'COFI_RECENT_12H_META_KEYWORDS') == "") {
					// $document->setMetaData( "keywords", "");
				}
				else { // use the meta keywords configured for this recent page
					$document->setMetaData( "keywords", JText::_( 'COFI_RECENT_12H_META_KEYWORDS'));
				}				

				break;
				
			}

			case "24": {
		
				// 1. Meta Title
				if ( JText::_( 'COFI_RECENT_24H_META_TITLE') == "") {
					$_title = JText::_( 'COFI_HISTORY');
					$_title .=  " " . $hours . " ";
					$_title .= JText::_( 'COFI_HISTORY_HOURS');		
					$document->setTitle( $_title);
				}
				else { // use the meta title configured for this recent page
					$document->setTitle( JText::_( 'COFI_RECENT_24H_META_TITLE'));
				}
		
				// 2. Meta Description
				if ( JText::_( 'COFI_RECENT_24H_META_DESCRIPTION') == "") {
					$document->setDescription( $document->getTitle());
				}
				else { // use the meta description configured for this recent page
					$document->setDescription( JText::_( 'COFI_RECENT_24H_META_DESCRIPTION'));
				}
		
				// 3. Meta Keywords
				if ( JText::_( 'COFI_RECENT_24H_META_KEYWORDS') == "") {
					// $document->setMetaData( "keywords", "");
				}
				else { // use the meta keywords configured for this recent page
					$document->setMetaData( "keywords", JText::_( 'COFI_RECENT_24H_META_KEYWORDS'));
				}				

				break;
				
			}



			default: {
			
				$document->setTitle( JText::_( 'COFI_HISTORY'));
				$document->setDescription( JText::_( 'COFI_HISTORY'));

				break;
				
			}



		}





		$this->assignRef('postings',	$postings);
		$this->assignRef('pagination', $pagination);

		$this->assignRef('params',		$params);

        $this->assignRef('hours',		$hours);

        // display the view
        parent::display();

    }


}
