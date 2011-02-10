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
 * Category View 
 */ 
class DiscussionsViewCategory extends JView { 


	/** 
     * Renders the view 
     * 
     */ 
    function display() { 
				
		global $mainframe;

		$document =& JFactory::getDocument();
		
		//$pathway	= &$mainframe->getPathway();
		$app 		= JFactory::getApplication();
		$pathway	= &$app->getPathway();
				
		$threads                	=& $this->get('Threads');
		$pagination             	=& $this->get('Pagination');

        $categoryId             	=& $this->get('CategoryId');
        $categoryName           	=& $this->get('CategoryName');
		$categoryDescription    	=& $this->get('CategoryDescription');
		$categoryImage          	=& $this->get('CategoryImage');
        $categorySlug           	=& $this->get('CategorySlug');

        $forumBannerTop         	=& $this->get('ForumBannerTop');
        $forumBannerBottom     	 	=& $this->get('ForumBannerBottom');

        $categoryMetaTitle      	=& $this->get('CategoryMetaTitle');
        $categoryMetaDescription	=& $this->get('CategoryMetaDescription');
        $categoryMetaKeywords      	=& $this->get('CategoryMetaKeywords');


		// get parameters
		//$params = &$mainframe->getParams();
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
				
		// Set Meta Tags
		
		// 1. Meta Title
		if ( $categoryMetaTitle == "") {
			$document->setTitle( $categoryName);
		}
		else { // use the meta title configured for this forum
			$document->setTitle( $categoryMetaTitle);
		}

		// 2. Meta Description
		if ( $categoryMetaDescription == "") {
			$document->setDescription( $categoryDescription);
		}
		else { // use the meta description configured for this forum
			$document->setDescription( $categoryMetaDescription);
		}

		// 3. Meta Keywords
		if ( $categoryMetaKeywords == "") {
			// $document->setMetaData( "keywords", "");
		}
		else { // use the meta keywords configured for this forum
			$document->setMetaData( "keywords", $categoryMetaKeywords);
		}



		//set breadcrumbs
		if( is_object($menu) && $menu->query['view'] != 'category') {
			$pathway->addItem( $categoryName, '');
		}


        
		$this->assignRef('threads',	$threads);
		$this->assignRef('pagination', $pagination);

		$this->assignRef('categoryId', $categoryId);
		$this->assignRef('categoryName', $categoryName);
		$this->assignRef('categoryDescription', $categoryDescription);
		$this->assignRef('categoryImage', $categoryImage);
		$this->assignRef('categorySlug', $categorySlug);
		
		$this->assignRef('forumBannerTop', $forumBannerTop);
		$this->assignRef('forumBannerBottom', $forumBannerBottom);		
                
		$this->assignRef('params',		$params);
           
                
        // display the view 
        parent::display(); 

    }


}
