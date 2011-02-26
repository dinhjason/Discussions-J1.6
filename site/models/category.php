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

jimport('joomla.application.component.model');

require_once(JPATH_COMPONENT.DS.'classes/user.php');


/** 
 * Discussions Category Model 
 */ 
class DiscussionsModelCategory extends JModel { 

	
     
	/**
	 * thread List array
	 *
	 * @var array
	 */
	var $_data = null;


	/**
	 * category total
	 *
	 * @var integer
	 */
	var $_total = null;


	/**
	 * category id
	 *
	 * @var integer
	 */
	var $_categoryId = 0;


	/**
	 * category name
	 *
	 * @var String
	 */
	var $_categoryName = null;


	/**
	 * category alias
	 *
	 * @var String
	 */
	var $_categoryAlias = null;


	/**
	 * category slug
	 *
	 * @var String
	 */
	var $_categorySlug = null;


	/**
	 * category description
	 *
	 * @var String
	 */
	var $_categoryDescription = null;


	/**
	 * category image
	 *
	 * @var String
	 */
	var $_categoryImage = null;


	/**
	 * forum banner top
	 *
	 * @var String
	 */
	var $_forumBannerTop = null;

	/**
	 * forum banner bottom
	 *
	 * @var String
	 */
	var $_forumBannerBottom = null;



	/**
	 * private status
	 *
	 * @var integer
	 */
	var $_privateStatus = null;


	/**
	 * exist status
	 *
	 * @var integer
	 */
	var $_existStatus = null;



	/**
	 * category meta title
	 *
	 * @var String
	 */
	var $_categoryMetaTitle = null;

	/**
	 * category meta description
	 *
	 * @var String
	 */
	var $_categoryMetaDescription = null;

	/**
	 * category meta keywords
	 *
	 * @var String
	 */
	var $_categoryMetaKeywords = null;


	/**
	 * rss thread list array
	 *
	 * @var array
	 */
	var $_rss_data = null;




	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct() {
		global $mainframe;

		parent::__construct();
		
		// get parameters
		$params = JComponentHelper::getParams('com_discussions');
		
		$_categoryListLength = $params->get('categoryListLength', '20');	
				
		$this->setState('limit', $_categoryListLength, 'int');
		
		$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));

	}



	/** 
     * Gets Threads data 
     * 
     * @return array 
     */ 
     function getThreads() { 
          
		global $mainframe;
          
//     	$_catid = JRequest::getVar('catid', 0);
 		$_catid = JRequest::getInt('catid', 0);


     	if ( $this->getExistStatus() != null ) { // check if this category exists
     	
     		// 1. check if this is a private (moderator only) forum
    	 	if ( $this->getPrivateStatus() == 1 ) {
     	
     			// 2. if it is private -> check if this user is a moderator
				$user =& JFactory::getUser();
				$logUser = new CofiUser( $user->id);
     	
				if ( $logUser->isModerator() == 0) {	// user is not moderator -> kick him out of here
					$redirectLink = JRoute::_( "index.php?option=com_discussions");
					$mainframe->redirect( $redirectLink, JText::_( 'COFI_NO_ACCESS_TO_FORUM' ), "notice");			
     			}
     	
     		}
     
          
        	$db =& $this->getDBO(); 

			// Load threads if they doesn't exist
			if (empty($this->_data)) {
				$selectQuery = $this->_buildSelectQuery();

    	        $limitstart = $this->getState('limitstart');
 	           $limit = $this->getState('limit');
	
				$this->_data = $this->_getList( $selectQuery, $limitstart, $limit);
			}
		
        	// return the category list data 
        	return $this->_data;    
         
        }
        else { // category does not exist
			$redirectLink = JRoute::_( "index.php?option=com_discussions");
			$mainframe->redirect( $redirectLink, JText::_( 'COFI_FORUM_NOT_EXISTS' ), "notice");
        }
        
        
     } 



	/**
	 * Method to get the total number of threads in this category
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal() {

// 		$_catid = JRequest::getVar('catid', 0);
 		$_catid = JRequest::getInt('catid', 0);

		if ( empty( $this->_total)) {
		
			$db	=& JFactory::getDBO();

			$sql = "SELECT count(*) FROM " . $db->nameQuote('#__discussions_messages') . " WHERE cat_id='".$_catid."' AND parent_id='0' AND published='1'";
			
			$db->setQuery( $sql);

			$_counter = $db->loadResult();

			$this->_total = $_counter;

		}

		return $this->_total;
		
	}



	/**
	 * Method to get a pagination object
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination() {
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}



	function _buildSelectQuery() {
//     	$_catid = JRequest::getVar('catid', 0);
 		$_catid = JRequest::getInt('catid', 0);


        $params = JComponentHelper::getParams('com_discussions');        
		$_dateformat	= $params->get( 'dateformat', '%d.%m.%Y');
		$_timeformat	= $params->get( 'timeformat', '%H:%i');        		        	        		        		


        $db =& $this->getDBO();

		$selectQuery = "SELECT id, parent_id, cat_id, thread, user_id, subject, 
							CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(':', id, alias) ELSE id END as slug,		
							type, DATE_FORMAT( date, '" . $_dateformat . " " . $_timeformat . "') AS date,
                    		counter_replies, DATE_FORMAT( last_entry_date, '" . $_dateformat . " " . $_timeformat . "') AS last_entry_date,
                    		last_entry_user_id, hits, locked, sticky, published
						FROM ".$db->nameQuote('#__discussions_messages')."
						WHERE cat_id='".$_catid."' AND parent_id='0' AND published='1'
						ORDER BY sticky DESC, last_entry_msg_id DESC";

        return $selectQuery;
	}


	/**
	 * Method to get the id of this category
	 *
	 * @access public
	 * @return integer
	 */
	function getCategoryId() {
     	$this->_categoryId = JRequest::getVar('catid', 0);

		list( $this->_categoryId, $this->_categoryAlias) = explode(':', $this->_categoryId, 2);     	
     	
		return $this->_categoryId;
	}


	/**
	 * Method to get the slug of this category
	 *
	 * @access public
	 * @return string
	 */
	function getCategorySlug() {
     	$this->_categorySlug = JRequest::getVar('catid', 0);

		return $this->_categorySlug;
	}



	/**
	 * Method to get the name of this category
	 *
	 * @access public
	 * @return String
	 */
	function getCategoryName() {
		if ( empty( $this->_categoryName)) {
//            $_catid = JRequest::getVar('catid', 0);
 			$_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $categoryNameQuery = "SELECT name FROM ".$db->nameQuote( '#__discussions_categories')." WHERE id='".$_catid."'";

            $db->setQuery( $categoryNameQuery);
            $this->_categoryName = $db->loadResult();
		}
		return $this->_categoryName;
	}


	/**
	 * Method to get the description of this category
	 *
	 * @access public
	 * @return String
	 */
	function getCategoryDescription() {
		if ( empty( $this->_categoryDescription)) {
//            $_catid = JRequest::getVar('catid', 0);
 			$_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $categoryDescriptionQuery = "SELECT description FROM ".$db->nameQuote( '#__discussions_categories')." WHERE id='".$_catid."'";

            $db->setQuery( $categoryDescriptionQuery);
            $this->_categoryDescription = $db->loadResult();
		}
		return $this->_categoryDescription;
	}


	/**
	 * Method to get the image of this category
	 *
	 * @access public
	 * @return String
	 */
	function getCategoryImage() {
		if ( empty( $this->_categoryImage)) {
//            $_catid = JRequest::getVar('catid', 0);
 			$_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $categoryImageQuery = "SELECT image FROM ".$db->nameQuote( '#__discussions_categories')." WHERE id='".$_catid."'";

            $db->setQuery( $categoryImageQuery);
            $this->_categoryImage = $db->loadResult();
		}
		return $this->_categoryImage;
	}


	/**
	 * Method to get the top banner of this forum
	 *
	 * @access public
	 * @return String
	 */
	function getForumBannerTop() {

		if ( empty( $this->_forumBannerTop)) {

 			$_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $forumBannerTopQuery = "SELECT banner_top FROM " . $db->nameQuote( '#__discussions_categories') . " WHERE id='" . $_catid . "'";

            $db->setQuery( $forumBannerTopQuery);
            $this->_forumBannerTop = $db->loadResult();
		}
		return $this->_forumBannerTop;
	}


	/**
	 * Method to get the bottom banner of this forum
	 *
	 * @access public
	 * @return String
	 */
	function getForumBannerBottom() {

		if ( empty( $this->_forumBannerBottom)) {

 			$_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $forumBannerBottomQuery = "SELECT banner_bottom FROM " . $db->nameQuote( '#__discussions_categories') . " WHERE id='" . $_catid . "'";

            $db->setQuery( $forumBannerBottomQuery);
            $this->_forumBannerBottom = $db->loadResult();
		}
		return $this->_forumBannerBottom;
	}


	/**
	 * Method to get the private status of this category
	 *
	 * @access public
	 * @return integer
	 */
	function getPrivateStatus() {
		if ( empty( $this->_privateStatus)) {
//            $_catid = JRequest::getVar('catid', 0);
 			$_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $sql = "SELECT private FROM ".$db->nameQuote( '#__discussions_categories')." WHERE id='".$_catid."'";

            $db->setQuery( $sql);
            $this->_privateStatus = $db->loadResult();
		}
		return $this->_privateStatus;
	}



	/**
	 * Method to check if this category exists
	 *
	 * @access public
	 * @return integer
	 */
	function getExistStatus() {
		if ( empty( $this->_existStatus)) {
//            $_catid = JRequest::getVar('catid', 0);
 			$_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $sql = "SELECT parent_id FROM ".$db->nameQuote( '#__discussions_categories')." WHERE id='".$_catid."' AND parent_id<>'0'";

            $db->setQuery( $sql);
            $this->_existStatus = $db->loadResult();
		}
		return $this->_existStatus;
	}




	/**
	 * Method to get the meta title of this category
	 *
	 * @access public
	 * @return String
	 */
	function getCategoryMetaTitle() {
	
		if ( empty( $this->_categoryMetaTitle)) {
		
 			$_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $categoryMetaTitleQuery = "SELECT meta_title FROM " . $db->nameQuote( '#__discussions_categories') . " WHERE id='" . $_catid . "'";

            $db->setQuery( $categoryMetaTitleQuery);
            $this->_categoryMetaTitle = $db->loadResult();
		}
		
		return $this->_categoryMetaTitle;
		
	}

	/**
	 * Method to get the meta description of this category
	 *
	 * @access public
	 * @return String
	 */
	function getCategoryMetaDescription() {
	
		if ( empty( $this->_categoryMetaDescription)) {
		
 			$_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $categoryMetaDescriptionQuery = "SELECT meta_description FROM " . $db->nameQuote( '#__discussions_categories') . " WHERE id='" . $_catid . "'";

            $db->setQuery( $categoryMetaDescriptionQuery);
            $this->_categoryMetaDescription = $db->loadResult();
		}
		
		return $this->_categoryMetaDescription;
		
	}

	/**
	 * Method to get the meta keywords of this category
	 *
	 * @access public
	 * @return String
	 */
	function getCategoryMetaKeywords() {
	
		if ( empty( $this->_categoryMetaKeywords)) {
		
 			$_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $categoryMetaKeywordsQuery = "SELECT meta_keywords FROM " . $db->nameQuote( '#__discussions_categories') . " WHERE id='" . $_catid . "'";

            $db->setQuery( $categoryMetaKeywordsQuery);
            $this->_categoryMetaKeywords = $db->loadResult();
		}
		
		return $this->_categoryMetaKeywords;
		
	}



	/** 
     * Gets RSS entries data 
     * 
     * @return array 
     */ 
     function getRSSEntries() { 
                              
 		$_catid = JRequest::getInt('catid', 0);

		// get parameters
		$params = JComponentHelper::getParams('com_discussions');
		$rssSize = $params->get('rssSize', 20);
                                       
    	$db =& $this->getDBO(); 

		$selectQuery = "SELECT m.id, m.parent_id, m.cat_id, m.thread, m.user_id, m.subject, m.message,
							CASE WHEN CHAR_LENGTH(m.alias) THEN CONCAT_WS(':', m.id, m.alias) ELSE m.id END as mslug,	
							CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as cslug,
							m.date, m.published, c.name	as category		 
						FROM " . $db->nameQuote('#__discussions_messages') . " m, " . $db->nameQuote('#__discussions_categories') . " c " .
                            " WHERE m.cat_id=" . $_catid . " AND m.parent_id=0 AND m.cat_id=c.id AND m.published=1 AND c.private=0" .
						    " ORDER BY m.date DESC";
						    						    
		$this->_rss_data = $this->_getList( $selectQuery, '0', $rssSize);
	
    	return $this->_rss_data;    
                
     }    





} 
