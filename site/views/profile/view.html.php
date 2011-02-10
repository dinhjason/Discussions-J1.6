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
 * Posting View
 */
class DiscussionsViewProfile extends JView {


	/**
     * Renders the view
     *
     */
    function display() {

//		global $mainframe;

		$document 	=& JFactory::getDocument();
		$app 		= JFactory::getApplication();
		$pathway	= &$app->getPathway();


        $headline   		=& $this->get('Headline');
        $signature			=& $this->get('Signature');
        $task       		=& $this->get('Task');
        $zipcode			=& $this->get('Zipcode');
        $city	   			=& $this->get('City');
        $country			=& $this->get('Country');
        $website			=& $this->get('Website');
        $twitter			=& $this->get('Twitter');
        $facebook			=& $this->get('Facebook');
        $flickr				=& $this->get('Flickr');
        $youtube			=& $this->get('Youtube');
        $show_online_status	=& $this->get('ShowOnlineStatus');


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

		$document->setTitle( $params->get( 'page_title' ) );
*/

		//set breadcrumbs
		if( is_object($menu) && $menu->query['view'] != 'profile') {
			$pathway->addItem( 'Profile', '');
		}


		$this->assignRef('headline', $headline);
        $this->assignRef('signature', $signature);
		$this->assignRef('task', $task);
        $this->assignRef('zipcode', $zipcode);
        $this->assignRef('city', $city);
        $this->assignRef('country', $country);
        $this->assignRef('website', $website);
        $this->assignRef('twitter', $twitter);
        $this->assignRef('facebook', $facebook);
        $this->assignRef('flickr', $flickr);
        $this->assignRef('youtube', $youtube);
        $this->assignRef('show_online_status', $show_online_status);

		$this->assignRef('params',		$params);


        // display the view
        parent::display();

    }



}
