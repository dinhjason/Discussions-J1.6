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
class DiscussionsViewPosting extends JView {


	/**
     * Renders the view
     *
     */
    function display() {

		global $mainframe;

		$document =& JFactory::getDocument();

		$app 		= JFactory::getApplication();


		$postings               =& $this->get('Postings');
        $categoryId             =& $this->get('CategoryId');
        $categorySlug           =& $this->get('CategorySlug');
        $categoryName           =& $this->get('CategoryName');
        $categoryImage          =& $this->get('CategoryImage');
        $headline               =& $this->get('Headline');
        $subject                =& $this->get('Subject');
        $messageText            =& $this->get('MessageText');
        $task                   =& $this->get('Task');
        $thread                 =& $this->get('Thread');
        $id                 	=& $this->get('Id');
        $image1                 =& $this->get('Image1');
        $image1_description     =& $this->get('Image1_description');
        $image2                 =& $this->get('Image2');
        $image2_description     =& $this->get('Image2_description');
        $image3                 =& $this->get('Image3');
        $image3_description     =& $this->get('Image3_description');
        $image4                 =& $this->get('Image4');
        $image4_description     =& $this->get('Image4_description');
        $image5                 =& $this->get('Image5');
        $image5_description     =& $this->get('Image5_description');


		// get parameters
//		$params = &$mainframe->getParams();
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



        $this->assignRef('postings', $postings);
		$this->assignRef('categoryId', $categoryId);
		$this->assignRef('categorySlug', $categorySlug);
		$this->assignRef('categoryName', $categoryName);
		$this->assignRef('categoryImage', $categoryImage);
		$this->assignRef('headline', $headline);
		$this->assignRef('subject', $subject);
		$this->assignRef('messageText', $messageText);
		$this->assignRef('task', $task);
		$this->assignRef('thread', $thread);
		$this->assignRef('id', $id);
		$this->assignRef('image1', $image1);
		$this->assignRef('image1_description', $image1_description);
		$this->assignRef('image2', $image2);
		$this->assignRef('image2_description', $image2_description);
		$this->assignRef('image3', $image3);
		$this->assignRef('image3_description', $image3_description);
		$this->assignRef('image4', $image4);
		$this->assignRef('image4_description', $image4_description);
		$this->assignRef('image5', $image5);
		$this->assignRef('image5_description', $image5_description);

		$this->assignRef('params',		$params);


        // display the view
        parent::display();

    }



}
