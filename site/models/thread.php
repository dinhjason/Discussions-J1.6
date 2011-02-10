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
class DiscussionsModelThread extends JModel {



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
	 * subject
	 *
	 * @var String
	 */
	var $_subject = null;


	/**
	 * thread id
	 *
	 * @var integer
	 */
	var $_thread = 0;


	/**
	 * thread id
	 *
	 * @var integer
	 */
	var $_threadId = 0;


	/**
	 * thread alias
	 *
	 * @var String
	 */
	var $_threadAlias = null;


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
	 * meta description
	 *
	 * @var String
	 */
	var $_metaDescription = null;


	/**
	 * meta keywords
	 *
	 * @var String
	 */
	var $_metaKeywords = null;



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
		
		$_threadListLength = $params->get('threadListLength', '20');	
				
		$this->setState('limit', $_threadListLength, 'int');		
		
		$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
	}



	/**
     * Gets Threads data
     *
     * @return array
     */
     function getPostings() {

		global $mainframe;

     	$_catid = JRequest::getVar('catid', 0);

        $_categoryId = $_catid;


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

		// Load the postings if they doesn't exist
		if (empty($this->_data)) {
			$selectQuery = $this->_buildSelectQuery();

            $limitstart = $this->getState('limitstart');
            $limit = $this->getState('limit');

			$this->_data = $this->_getList( $selectQuery, $limitstart, $limit);
		}

        // return the post list data
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
		if (empty($this->_total)) {
			$countQuery = $this->_buildCountQuery();
			$this->_total = $this->_getListCount($countQuery);
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
     	$_catid  = JRequest::getVar('catid', 0);
     	$_thread = JRequest::getVar('thread', 0);

        $params = JComponentHelper::getParams('com_discussions');        
		$_dateformat	= $params->get( 'dateformat', '%d.%m.%Y');
		$_timeformat	= $params->get( 'timeformat', '%H:%i');

        $db =& $this->getDBO();

		$selectQuery = "SELECT id, parent_id, cat_id, thread, user_id, type, subject, message,
                    counter_replies, DATE_FORMAT( date, '" . $_dateformat . " " . $_timeformat . "') AS date, hits, sticky, 
                    image1, image1_description, 
                    image2, image2_description,
                    image3, image3_description, 
                    image4, image4_description,
                    image5, image5_description,
                    published
					FROM ".$db->nameQuote('#__discussions_messages')."
					WHERE cat_id='".$_catid."' AND thread='".$_thread."' AND published='1'
					ORDER BY id ASC";

        return $selectQuery;
	}



	function _buildCountQuery() {
     	$_catid  = JRequest::getVar('catid', 0);
     	$_thread = JRequest::getVar('thread', 0);

        $db =& $this->getDBO();

		$countQuery = "SELECT * FROM ".$db->nameQuote('#__discussions_messages')." WHERE cat_id='".$_catid."' AND thread='".$_thread."' AND published='1'";
		return $countQuery;
	}



	/**
	 * Method to get the category id of this thread
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
	 * Method to get the category slug of this thread
	 *
	 * @access public
	 * @return string
	 */
	function getCategorySlug() {
     	$this->_categoryId = JRequest::getVar('catid', 0);
     	
		return $this->_categoryId;
	}



	/**
	 * Method to get the name of this category
	 *
	 * @access public
	 * @return String
	 */
	function getCategoryName() {
		if ( empty( $this->_categoryName)) {
            $_catid = JRequest::getVar('catid', 0);

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
            $_catid = JRequest::getVar('catid', 0);

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
            $_catid = JRequest::getVar('catid', 0);

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
            $_catid = JRequest::getVar('catid', 0);

            $db =& $this->getDBO();

            $forumBannerTopQuery = "SELECT banner_top FROM ".$db->nameQuote( '#__discussions_categories')." WHERE id='".$_catid."'";

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
            $_catid = JRequest::getVar('catid', 0);

            $db =& $this->getDBO();

            $forumBannerBottomQuery = "SELECT banner_bottom FROM ".$db->nameQuote( '#__discussions_categories')." WHERE id='".$_catid."'";

            $db->setQuery( $forumBannerBottomQuery);
            $this->_forumBannerBottom = $db->loadResult();
		}
		return $this->_forumBannerBottom;
	}





	/**
	 * Method to get the subject of this thread
	 *
	 * @access public
	 * @return String
	 */
	function getSubject() {
		if ( empty( $this->_subject)) {
            $_catid  = JRequest::getVar('catid', 0);
            $_thread = JRequest::getVar('thread', 0);

            $db =& $this->getDBO();

            $subjectQuery = "SELECT subject FROM ".$db->nameQuote( '#__discussions_messages')." 
                                WHERE cat_id='".$_catid."' AND thread='".$_thread."' AND parent_id='0' AND published='1' ";


            $db->setQuery( $subjectQuery);
            $this->_subject = $db->loadResult();
		}

		return $this->_subject;
	}


	/**
	 * Method to get the id of this thread
	 *
	 * @access public
	 * @return integer
	 */
	function getThread() {
		if ( empty( $this->_threadId)) {
            $this->_thread = JRequest::getVar('thread', 0);
		}

		return $this->_thread;
	}


	/**
	 * Method to get the id of this thread
	 *
	 * @access public
	 * @return integer
	 */
	function getThreadId() {
     	$this->_threadId = JRequest::getVar('thread', 0);

		list( $this->_threadId, $this->_threadAlias) = explode(':', $this->_threadId, 2);     	
     	
		return $this->_threadId;
	}



	/**
	 * Method to get the thread slug of this thread
	 *
	 * @access public
	 * @return string
	 */
	function getThreadSlug() {
     	$this->_threadId = JRequest::getVar('thread', 0);
     	
		return $this->_threadId;
	}



	/**
	 * Method to get the sticky status of this thread
	 *
	 * @access public
	 * @return integer
	 */
	function getStickyStatus() {
	
    	$_catid  = JRequest::getVar('catid', 0);
        $_thread = JRequest::getVar('thread', 0);

        $db =& $this->getDBO();

        $sql = "SELECT sticky FROM ".$db->nameQuote( '#__discussions_messages')." 
                                WHERE cat_id='".$_catid."' AND thread='".$_thread."' AND parent_id='0' ";


        $db->setQuery( $sql);
        $_stickyStatus = $db->loadResult();

		return $_stickyStatus;
	}


	/**
	 * Method to get the locked status of this thread
	 *
	 * @access public
	 * @return integer
	 */
	function getLockedStatus() {
	
    	$_catid  = JRequest::getVar('catid', 0);
        $_thread = JRequest::getVar('thread', 0);

        $db =& $this->getDBO();

        $sql = "SELECT locked FROM ".$db->nameQuote( '#__discussions_messages')." 
                                WHERE cat_id='".$_catid."' AND thread='".$_thread."' AND parent_id='0' ";


        $db->setQuery( $sql);
        $_lockedStatus = $db->loadResult();

		return $_lockedStatus;
	}



	/**
	 * Method to get the private status of this category
	 *
	 * @access public
	 * @return integer
	 */
	function getPrivateStatus() {
		if ( empty( $this->_privateStatus)) {
            $_catid = JRequest::getVar('catid', 0);

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
            $_catid = JRequest::getVar('catid', 0);

            $db =& $this->getDBO();

            $sql = "SELECT parent_id FROM ".$db->nameQuote( '#__discussions_categories')." WHERE id='".$_catid."' AND parent_id<>'0'";

            $db->setQuery( $sql);
            $this->_existStatus = $db->loadResult();
		}
		
		return $this->_existStatus;
	}



	/**
	 * Method to get the Meta Description of this thread
	 *
	 * @access public
	 * @return String
	 */
	function getMetaDescription() {
	
		if ( empty( $this->_metaDescription)) {
		
            $_catid  = JRequest::getVar('catid', 0);
            $_thread = JRequest::getVar('thread', 0);

            $db =& $this->getDBO();

            $query = "SELECT message FROM ".$db->nameQuote( '#__discussions_messages')." 
                                WHERE cat_id='".$_catid."' AND thread='".$_thread."' AND parent_id='0' AND published='1' ";


            $db->setQuery( $query);
            $this->_metaDescription = $db->loadResult();
            
		}

		return $this->_metaDescription;
		
	}



	/**
	 * Method to get the Meta Keywords of this thread (take the ones from the category)
	 *
	 * @access public
	 * @return String
	 */
	function getMetaKeywords() {
	
		if ( empty( $this->_metaKeywords)) {
		
 			$_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $query = "SELECT meta_keywords FROM " . $db->nameQuote( '#__discussions_categories') . " WHERE id='" . $_catid . "'";

            $db->setQuery( $query);
            $this->_metaKeywords = $db->loadResult();
            
		}

		return $this->_metaKeywords;
		
	}





}


