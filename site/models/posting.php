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
require_once(JPATH_COMPONENT.DS.'classes/helper.php');



/**
 * Discussions Posting Model
 */
class DiscussionsModelPosting extends JModel {


	/**
	 * id
	 *
	 * @var integer
	 */
	var $_id = 0;


	/**
	 * thread
	 *
	 * @var integer
	 */
	var $_thread = 0;


	/**
	 * category id
	 *
	 * @var integer
	 */
	var $_categoryId = 0;



	/**
	 * category slug
	 *
	 * @var String
	 */
	var $_categorySlug = 0;



	/**
	 * category name
	 *
	 * @var String
	 */
	var $_categoryName = null;


	/**
	 * category image
	 *
	 * @var String
	 */
	var $_categoryImage = null;


    /**
     * thread slug
     *
     * @var String
     */
    var $_threadSlug = 0;


	/**
	 * subject
	 *
	 * @var String
	 */
	var $_subject = "";



	/**
	 * headline
	 *
	 * @var String
	 */
	var $_headline = null;



	/**
	 * postSubject
	 *
	 * @var String
	 */
	var $_postSubject = "";



	/**
	 * postText
	 *
	 * @var String
	 */
	var $_postText = "";


	/**
	 * postCatId
	 *
	 * @var integer
	 */
	var $_postCatId = 0;


	/**
	 * postThread
	 *
	 * @var integer
	 */
	var $_postThread = 0;


	/**
	 * postIpAddress
	 *
	 * @var String
	 */
	var $_postIpAddress = "";


	/**
	 * postId
	 *
	 * @var integer
	 */
	var $_postId = 0;

	/**
	 * postParent
	 *
	 * @var integer
	 */
	var $_postParent = 0;



	/**
	 * messageText
	 *
	 * @var String
	 */
	var $_messageText = "";


	/**
	 * task
	 *
	 * @var String
	 */
	var $_task = "";


	/**
	 * dbmode
	 *
	 * @var String
	 */
	var $_dbmode = "";


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
	 * image 1
	 *
	 * @var String
	 */

	var $_image1 = null;

	/**
	 * image 1 description
	 *
	 * @var String
	 */

	var $_image1_description = null;


	/**
	 * image 2
	 *
	 * @var String
	 */

	var $_image2 = null;

	/**
	 * image 2 description
	 *
	 * @var String
	 */

	var $_image2_description = null;


	/**
	 * image 3
	 *
	 * @var String
	 */

	var $_image3 = null;

	/**
	 * image 3 description
	 *
	 * @var String
	 */

	var $_image3_description = null;


	/**
	 * image 4
	 *
	 * @var String
	 */

	var $_image4 = null;

	/**
	 * image 4 description
	 *
	 * @var String
	 */

	var $_image4_description = null;


	/**
	 * image 5
	 *
	 * @var String
	 */

	var $_image5 = null;

	/**
	 * image 5 description
	 *
	 * @var String
	 */

	var $_image5_description = null;



	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct() {
	
		// global $mainframe;

		parent::__construct();

		$app = JFactory::getApplication();


		$user =& JFactory::getUser();

		if ( $user->guest) { // user is not logged in
			$redirectLink = JRoute::_( "index.php?option=com_discussions");
			$app->redirect( $redirectLink, JText::_( 'COFI_NOT_LOGGED_IN' ), "notice");		
		}


     	$this->_task   = JRequest::getString( 'task', '');
     	$this->_dbmode = JRequest::getString( 'dbmode', '');
     	$this->_thread = JRequest::getInt( 'thread', 0);
		$this->_categoryId = JRequest::getInt('catid', 0);
		$this->_categorySlug = JRequest::getString('catid', 0);
				

     	if ( $this->getExistStatus() != null ) { // check if this category exists
		
     		// 1. check if this is a private (moderator only) forum
    	 	if ( $this->getPrivateStatus() == 1 ) {
     	
     			// 2. if it is private -> check if this user is a moderator
				$logUser = new CofiUser( $user->id);
     	
				if ( $logUser->isModerator() == 0) {	// user is not moderator -> kick him out of here
					$redirectLink = JRoute::_( "index.php?option=com_discussions");
					$app->redirect( $redirectLink, JText::_( 'COFI_NO_ACCESS_TO_FORUM' ), "notice");			
     			}
     	
     		}
		
				
			switch ( $this->_task) {
				case "new": {
					$this->_headline = JText::_( 'COFI_HEADLINE_NEW_THREAD' );
					break;
				}
				case "reply": {
					$this->_headline = JText::_( 'COFI_HEADLINE_REPLY' );
					break;
				}
				case "quote": {
					$this->_headline = JText::_( 'COFI_HEADLINE_REPLY_WITH_QUOTATION' );
					break;
				}
				case "edit": {
					$this->checkEditPermission();
					$this->_headline = JText::_( 'COFI_HEADLINE_EDIT_POST' );
					break;
				}

				case "save": {     			
     				$this->savePosting();		
					break;
				}
						
				default: {
					$this->_headline = JText::_( 'COFI_HEADLINE_NEW_THREAD' );
					break;
				}
						
			}
		
		}
        else { // category does not exist
			$redirectLink = JRoute::_( "index.php?option=com_discussions");
			$app->redirect( $redirectLink, JText::_( 'COFI_FORUM_NOT_EXISTS' ), "notice");
        }
		

	}



	/**
     * Get recent postings
     *
     * @return array
     */
     function getPostings() {

        return $this->_data;
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
            $_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $categoryNameQuery = "SELECT name FROM ".$db->nameQuote( '#__discussions_categories')." WHERE id='".$_catid."'";

            $db->setQuery( $categoryNameQuery);
            $this->_categoryName = $db->loadResult();
		}
		return $this->_categoryName;
	}



	/**
	 * Method to get the image of this category
	 *
	 * @access public
	 * @return String
	 */
	function getCategoryImage() {
		if ( empty( $this->_categoryImage)) {
            $_catid = JRequest::getInt('catid', 0);

            $db =& $this->getDBO();

            $categoryImageQuery = "SELECT image FROM ".$db->nameQuote( '#__discussions_categories')." WHERE id='".$_catid."'";

            $db->setQuery( $categoryImageQuery);
            $this->_categoryImage = $db->loadResult();
		}
		return $this->_categoryImage;
	}


    /**
     * Method to get the slug of this thread
     *
     * @access public
     * @return string
     */
    function getThreadSlugByThreadId( $id) {

        $db =& $this->getDBO();

		$sql = "SELECT CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(':', id, alias) ELSE id END as slug
						FROM ".$db->nameQuote('#__discussions_messages')."
						WHERE thread='".$id."' AND parent_id='0' AND published='1' ";

        $db->setQuery( $sql);

        return $db->loadResult();

    }



	/**
	 * Method to get the subject of this posting
	 *
	 * @access public
	 * @return String
	 */
	function getSubject() {
		
		if ( empty( $this->_subject)) {
            $_catid  = JRequest::getInt( 'catid', 0);
            $_thread = JRequest::getInt( 'thread', 0);

            $db =& $this->getDBO();

            $subjectQuery = "SELECT subject FROM ".$db->nameQuote( '#__discussions_messages')." 
                                WHERE cat_id='".$_catid."' AND thread='".$_thread."' AND parent_id='0' AND published='1' ";

            $db->setQuery( $subjectQuery);
            $this->_subject = $db->loadResult();
		}

		return $this->_subject;
			
	}


	/**
	 * Method to get the headline of the write mode
	 *
	 * @access public
	 * @return String
	 */
	function getHeadline() {
		return $this->_headline;
	}


	/**
	 * Method to get the id of this posting
	 *
	 * @access public
	 * @return Integer
	 */
	function getId() {
		
		if ( empty( $this->_id)) {
		
            $this->_id  = JRequest::getInt( 'id', 0);
                        
		}

		return $this->_id;
			
	}


	/**
	 * Method to get the id of this thread
	 *
	 * @access public
	 * @return Integer
	 */
	function getThread() {
		
		if ( empty( $this->_thread)) {
		
            $this->_thread  = JRequest::getInt( 'thread', 0);
                        
		}

		return $this->_thread;
			
	}



	/**
	 * Method to get image 1 of this posting
	 *
	 * @access public
	 * @return String
	 */
	function getImage1() {
		
		if ( empty( $this->_image1)) {
		
            $_id  = JRequest::getInt( 'id', 0);

            $db =& $this->getDBO();

            $sql = "SELECT image1 FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $_id . "'";

            $db->setQuery( $sql);
            
            $this->_image1 = $db->loadResult();
            
		}

		return $this->_image1;
			
	}


	/**
	 * Method to get image 1 description of this posting
	 *
	 * @access public
	 * @return String
	 */
	function getImage1_description() {
		
		if ( empty( $this->_image1_description)) {
		
            $_id  = JRequest::getInt( 'id', 0);

            $db =& $this->getDBO();

            $sql = "SELECT image1_description FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $_id . "'";

            $db->setQuery( $sql);
            
            $this->_image1_description = $db->loadResult();
            
		}

		return $this->_image1_description;
			
	}


	/**
	 * Method to get image 2 of this posting
	 *
	 * @access public
	 * @return String
	 */
	function getImage2() {
		
		if ( empty( $this->_image2)) {
		
            $_id  = JRequest::getInt( 'id', 0);

            $db =& $this->getDBO();

            $sql = "SELECT image2 FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $_id . "'";

            $db->setQuery( $sql);
            
            $this->_image2 = $db->loadResult();
            
		}

		return $this->_image2;
			
	}


	/**
	 * Method to get image 2 description of this posting
	 *
	 * @access public
	 * @return String
	 */
	function getImage2_description() {
		
		if ( empty( $this->_image2_description)) {
		
            $_id  = JRequest::getInt( 'id', 0);

            $db =& $this->getDBO();

            $sql = "SELECT image2_description FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $_id . "'";

            $db->setQuery( $sql);
            
            $this->_image2_description = $db->loadResult();
            
		}

		return $this->_image2_description;
			
	}



	/**
	 * Method to get image 3 of this posting
	 *
	 * @access public
	 * @return String
	 */
	function getImage3() {
		
		if ( empty( $this->_image3)) {
		
            $_id  = JRequest::getInt( 'id', 0);

            $db =& $this->getDBO();

            $sql = "SELECT image3 FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $_id . "'";

            $db->setQuery( $sql);
            
            $this->_image3 = $db->loadResult();
            
		}

		return $this->_image3;
			
	}


	/**
	 * Method to get image 3 description of this posting
	 *
	 * @access public
	 * @return String
	 */
	function getImage3_description() {
		
		if ( empty( $this->_image3_description)) {
		
            $_id  = JRequest::getInt( 'id', 0);

            $db =& $this->getDBO();

            $sql = "SELECT image3_description FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $_id . "'";

            $db->setQuery( $sql);
            
            $this->_image3_description = $db->loadResult();
            
		}

		return $this->_image3_description;
			
	}


	/**
	 * Method to get image 4 of this posting
	 *
	 * @access public
	 * @return String
	 */
	function getImage4() {
		
		if ( empty( $this->_image4)) {
		
            $_id  = JRequest::getInt( 'id', 0);

            $db =& $this->getDBO();

            $sql = "SELECT image4 FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $_id . "'";

            $db->setQuery( $sql);
            
            $this->_image4 = $db->loadResult();
            
		}

		return $this->_image4;
			
	}


	/**
	 * Method to get image 4 description of this posting
	 *
	 * @access public
	 * @return String
	 */
	function getImage4_description() {
		
		if ( empty( $this->_image4_description)) {
		
            $_id  = JRequest::getInt( 'id', 0);

            $db =& $this->getDBO();

            $sql = "SELECT image4_description FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $_id . "'";

            $db->setQuery( $sql);
            
            $this->_image4_description = $db->loadResult();
            
		}

		return $this->_image4_description;
			
	}


	/**
	 * Method to get image 5 of this posting
	 *
	 * @access public
	 * @return String
	 */
	function getImage5() {
		
		if ( empty( $this->_image5)) {
		
            $_id  = JRequest::getInt( 'id', 0);

            $db =& $this->getDBO();

            $sql = "SELECT image5 FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $_id . "'";

            $db->setQuery( $sql);
            
            $this->_image5 = $db->loadResult();
            
		}

		return $this->_image5;
			
	}


	/**
	 * Method to get image 5 description of this posting
	 *
	 * @access public
	 * @return String
	 */
	function getImage5_description() {
		
		if ( empty( $this->_image5_description)) {
		
            $_id  = JRequest::getInt( 'id', 0);

            $db =& $this->getDBO();

            $sql = "SELECT image5_description FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $_id . "'";

            $db->setQuery( $sql);
            
            $this->_image5_description = $db->loadResult();
            
		}

		return $this->_image5_description;
			
	}



	function checkEditPermission() {

		// get parameters
		$params = JComponentHelper::getParams('com_discussions');

		//global $mainframe;
		$app = JFactory::getApplication();
		
		$user =& JFactory::getUser();
		$logUser = new CofiUser( $user->id);
		
		$CofiHelper = new CofiHelper();
		
		$_postId = JRequest::getInt('id', '0');

        $db =& $this->getDBO();

        $sql = "SELECT DATE_FORMAT( date, '%d.%m.%Y %k:%i') AS date FROM " . $db->nameQuote( '#__discussions_messages') . 
        			" WHERE id='".$_postId."' AND published='1' ";

        $db->setQuery( $sql);
        $date = $db->loadResult();


        $sql = "SELECT user_id FROM " . $db->nameQuote( '#__discussions_messages') . 
        			" WHERE id='".$_postId."' AND published='1' ";

        $db->setQuery( $sql);
        $user_id = $db->loadResult();


	
		// redirect	link
		$redirectLink = JRoute::_( "index.php?option=com_discussions&view=index");		


		if ( !$logUser->isModerator()) { // no checks for moderators, they are always allowed to edit


			// 1. check if user is owner
			// if user is not owner, redirect him back to forum index

			if( $user->id != $user_id) {
    			$app->redirect( $redirectLink, JText::_( 'COFI_NOT_POST_OWNER' ), "message"); 			
			}


			// 2. check if we are still in allowed edit time
			// edit timer finished, redirect user back to forum index
			                                                    
	    	$day = substr( $date, 0, 2);  // 1 + 2 char
	    	$month = substr( $date, 3, 2);  // 4 + 5 char
	    	$year = substr( $date, 6, 4);  // 7 - 10 char
	    
	    	$hour = substr( $date, 11, 2);  // 12 + 13 char
	    	$minute = substr( $date, 14, 2);  // 15 + 16 char
	    
	
	    	//date_default_timezone_set ( "Europe/Berlin");
	    
	    	$now = time(); // current unixtime
	    
	    	$posttime = mktime( $hour, $minute, 0, $month, $day, $year); // unixtime from post date
	        
	    	// get editTime in minutes from global parameters
	    	$editTime    = $params->get('editTime', '30');		
	    	$editForever = $params->get('editForever', '1');		
			                        
			if ( $editForever == 0) { // check if users are allowed to edit forever
			                        
	    		if ( ($now - $posttime) > ( $editTime * 60)) {
					$app->redirect( $redirectLink, JText::_( 'COFI_NOT_EDITABLE_ANYMORE' ), "message"); 
	   			}
	   		
	   		}
   		
   		
   		} // moderator						
			
	}



	/**
     * save posting
     *
     * @return int
     */
     function savePosting() {

		//global $mainframe;
		$app = JFactory::getApplication();

        $params = JComponentHelper::getParams('com_discussions');
        
		$_dateformat	= $params->get( 'dateformat', 'd.m.Y');
		$_timeformat	= $params->get( 'timeformat', 'H:i');        		        	        		        		


		$user =& JFactory::getUser();
		$logUser = new CofiUser( $user->id);
		
		$CofiHelper = new CofiHelper();
		
				
		$this->_headline = "";

     	$this->_dbmode = JRequest::getString( 'dbmode', '');
						
		$_postSubject   = JRequest::getString('postSubject', '', 'POST', JREQUEST_ALLOWRAW);							
		$_postSubject   = strip_tags($_postSubject);		
				
		$_postText      = JRequest::getString('postText', '', 'POST', JREQUEST_ALLOWRAW);							
		$_postText 		= strip_tags($_postText);		


		$_image1_description = JRequest::getString('image1_description', '');
		$_image1_description = strip_tags( $_image1_description);

		$_image2_description = JRequest::getString('image2_description', '');
		$_image2_description = strip_tags( $_image2_description);

		$_image3_description = JRequest::getString('image3_description', '');
		$_image3_description = strip_tags( $_image3_description);

		$_image4_description = JRequest::getString('image4_description', '');
		$_image4_description = strip_tags( $_image4_description);

		$_image5_description = JRequest::getString('image5_description', '');
		$_image5_description = strip_tags( $_image5_description);

		$_postCatId     = JRequest::getInt('catid', '0');

		$_postThread    = JRequest::getInt('thread', '0');
		$_postParent    = JRequest::getInt('parent', '0');
		$_postId        = JRequest::getInt('id', '0');


		// get user IP address
		$_postIpAddress = $_SERVER['REMOTE_ADDR'];


		// redirect	link
		$redirectLink = JRoute::_( "index.php?option=com_discussions&view=category&catid=".$this->getCategorySlug());

        
        // check if user is logged in - maybe session has timed out
		if ($user->guest) { 
			// if user is not logged in, kick him back into category
    		$app->redirect( $redirectLink, JText::_( 'COFI_POST_NOT_SAVED' ), "message"); 
		} 
        
        
                        
		// 1. check if subject >= 5 chars
		// todo make minimum subject length configurable
		if ( strlen( $_postSubject) < 5) {
			$isSubjectTooShort = true;
		}
		else {
			$isSubjectTooShort = false;
		}

		// 2. check if text >= 5 chars
		// todo make minimum text length configurable
		if ( strlen( $_postText) < 5) {
			$isTextTooShort = true;
		}
		else {
			$isTextTooShort = false;
		}
        
        

        // check if insert or update 
        
        
        // update
        if ( $this->_dbmode == "update") {

			if ( !$isSubjectTooShort && !$isTextTooShort) { // check if subject and text have minimum length

        		$db =& $this->getDBO();
        		        		
        		// insert last edit time stamp        		
        		$_unixtime = time();
        		        		
        		// todo change date, time calculation        		
        		        		
				// get rid of the percentage symbol %
				$_dateformat = str_replace( "%", "", $_dateformat);
				$_timeformat = str_replace( "%", "", $_timeformat);
				//$_timeformat = "g:i A";
				        		        		        		        		
				$_date = date( $_dateformat, $_unixtime);
				$_time = date( $_timeformat, $_unixtime);     
				        		
        		$_timestamp = "\n\n" . JText::_( 'COFI_EDITED_BY' ) . " " . $user->username . " - " . $_date . " " . $_time;
        		$_postText .= $_timestamp;
        		
        			
				if ( $logUser->isModerator()) { // moderators are allowed to edit all posts
     				$sql = "UPDATE ".$db->nameQuote( '#__discussions_messages') . " SET" . 
     					" message = " . $db->Quote( $_postText) . ", " .
     					" image1_description = " . $db->Quote( $_image1_description) . ", " .
     					" image2_description = " . $db->Quote( $_image2_description) . ", " .
     					" image3_description = " . $db->Quote( $_image3_description) . ", " .
     					" image4_description = " . $db->Quote( $_image4_description) . ", " .
     					" image5_description = " . $db->Quote( $_image5_description) .      					
     					" WHERE id = '".$_postId."'";
				}
				else { // no mod? then user must be owner
     				$sql = "UPDATE ".$db->nameQuote( '#__discussions_messages') . " SET" . 
     					" message = " . $db->Quote( $_postText) . ", " .
     					" image1_description = " . $db->Quote( $_image1_description) . ", " .
     					" image2_description = " . $db->Quote( $_image2_description) . ", " .
     					" image3_description = " . $db->Quote( $_image3_description) . ", " .
     					" image4_description = " . $db->Quote( $_image4_description) . ", " .
     					" image5_description = " . $db->Quote( $_image5_description) .      					
     					" WHERE id = '".$_postId.
     					"' AND user_id = '".$user->id."'";
        		}

        	
        		$db->setQuery( $sql);
        		$result = $db->query();



				// check if there are images to delete
				// get folder name		
				$rootDir = JPATH_ROOT;		

				$cb_image1  = JRequest::getString( 'cb_image1', '', 'POST');				
				$cb_image2  = JRequest::getString( 'cb_image2', '', 'POST');				
				$cb_image3  = JRequest::getString( 'cb_image3', '', 'POST');				
				$cb_image4  = JRequest::getString( 'cb_image4', '', 'POST');				
				$cb_image5  = JRequest::getString( 'cb_image5', '', 'POST');				

				if ( $cb_image1  == "delete") {
				    	$this->del_image( $_postThread, $_postId, "image1", $rootDir, $db, 1);	
				}
			     	    
				if ( $cb_image2  == "delete") {
				    	$this->del_image( $_postThread, $_postId, "image2", $rootDir, $db, 2);	
				}
			     	    
				if ( $cb_image3  == "delete") {
				    	$this->del_image( $_postThread, $_postId, "image3", $rootDir, $db, 3);	
				}
			     	    
				if ( $cb_image4  == "delete") {
				    	$this->del_image( $_postThread, $_postId, "image4", $rootDir, $db, 4);	
				}
			
				if ( $cb_image5  == "delete") {
				    	$this->del_image( $_postThread, $_postId, "image5", $rootDir, $db, 5);	
				}


				if ( $result) { // update went fine
											
					// upload images to id folder
							
					if (isset( $_FILES['image1']) and !$_FILES['image1']['error'] ) {		
				    	$this->add_image( $_postThread, $_postId, "image1", $rootDir, $db, 1);
					}
			
					if (isset( $_FILES['image2']) and !$_FILES['image2']['error'] ) {		
				    	$this->add_image( $_postThread, $_postId, "image2", $rootDir, $db, 2);
					}
				
					if (isset( $_FILES['image3']) and !$_FILES['image3']['error'] ) {		
				    	$this->add_image( $_postThread, $_postId, "image3", $rootDir, $db, 3);
					}
				
					if (isset( $_FILES['image4']) and !$_FILES['image4']['error'] ) {		
				    	$this->add_image( $_postThread, $_postId, "image4", $rootDir, $db, 4);
					}
				
					if (isset( $_FILES['image5']) and !$_FILES['image5']['error'] ) {		
				    	$this->add_image( $_postThread, $_postId, "image5", $rootDir, $db, 5);
					}
			
				}
				

			}

        } // end update
        // insert
        else {

			if ( !$isSubjectTooShort && !$isTextTooShort) { // check if subject and text have minimum length

        		$db =& $this->getDBO();
        		
        		// preset is published and not moderated (normal state)
		        $published = 1;
		        $wfm = 0; // wfm = waiting for moderation


        		if ( $logUser->isModerator() == 0) {  // bypass these checks if user is moderator
        		
	        		// 1. check for rookie mode
	        		        		
					// get Rookie Mode setting from com_discussions parameters
					$rookie = $params->get('rookie', '0');
			        
			        if ( $rookie > 0) { // we are in rookie mode
			        	if ( $logUser->isRookie() == 1) { // user is a rookie
			        		$wfm = 1; // wfm = waiting for moderation
			        		$published = 0;
			        	}
			        }
	
	
	        		// 2. check if this is a moderated user
	        		        		
		        	if ( $logUser->isModerated() == 1) { // user is moderated
		        		$wfm = 1; // wfm = waiting for moderation
		        		$published = 0;
		        	}
	
	
	        		// 3. check if this is a moderated category
	        		        		
		        	if ( $CofiHelper->isCategoryModerated( $_postCatId)) { // category is moderated
		        		$wfm = 1; // wfm = waiting for moderation
		        		$published = 0;
		        	}

				}
				


				// create alias for SEF URL
				jimport( 'joomla.filter.output' );
    			    			
            	$alias = $_postSubject;
    			$alias = JFilterOutput::stringURLSafe($alias);

        		$insert_sql = "INSERT INTO ".$db->nameQuote( '#__discussions_messages') .
            					" ( parent_id, cat_id, thread, user_id, account, name, email, ip, subject, alias, message, image1_description,  image2_description, image3_description, image4_description, image5_description, published, wfm) " .
            					" VALUES ( " .
            					$_postParent . ", " . 
            					$_postCatId . ", " . 
            					$_postThread . ", '" . 
            					$user->id . "', '" . 
            					$user->username . "', '" .
            					$user->name . "', '" . 
            					$user->email . "', '" . 
            					$_postIpAddress .  "', " .
            					$db->Quote( $_postSubject) . ", " . 
            					$db->Quote( $alias) . ", " . 
            					$db->Quote( $_postText) . ", " .
            					$db->Quote( $_image1_description) . ", " .
            					$db->Quote( $_image2_description) . ", " .
            					$db->Quote( $_image3_description) . ", " .
            					$db->Quote( $_image4_description) . ", " .
            					$db->Quote( $_image5_description) . ", " .            					
            					$published . ", " .
            					$wfm . " )";


        		$db->setQuery( $insert_sql);
        		$insert_result = $db->query();


        		// $_postId = last_insert_id();
				$db->setQuery( "SELECT LAST_INSERT_ID() FROM ".$db->nameQuote( '#__discussions_messages'));
				$_postId = $db->loadResult();

				// get parent and set thread to id if 0
				if ( $_postThread == 0) { // no thread id, so it is like id
					$_postThread = $_postId;
    				$sql = "UPDATE ".$db->nameQuote( '#__discussions_messages')." SET thread = '".$_postThread."' WHERE id = '".$_postId."'";
        			$db->setQuery( $sql);
        			$result = $db->query();
				}
				else { // thread is set
					if ( $_postParent == 0) { // no parent id, so it is like thread id
    					$sql = "UPDATE ".$db->nameQuote( '#__discussions_messages')." SET parent_id = '".$_postThread."' WHERE id = '".$_postId."'";
        				$db->setQuery( $sql);
        				$result = $db->query();
					}
				}
						
			
						
				if ( $insert_result) { // if insert was successful update statistics
				
					if ( $published == 1) { // thread goes live, so we can update stats
			
						// set user post counter ++
						$result = $CofiHelper->increaseUserPostCounter( $user->id);
						
						// update thread stats
						$result = $CofiHelper->updateThreadStats( $_postThread);
													
						// update category stats
						$result = $CofiHelper->updateCategoryStats( $_postCatId);									
			
					} // if published
			
				}
																				
			
			
				if ( $insert_result) { // insert went fine
				
					// upload image attachments todo
					// get folder name		
					$rootDir = JPATH_ROOT;		
						
					if (isset( $_FILES['image1']) and !$_FILES['image1']['error'] ) {		
				    	$this->add_image( $_postThread, $_postId, "image1", $rootDir, $db, 1);
					}
			
					if (isset( $_FILES['image2']) and !$_FILES['image2']['error'] ) {		
				    	$this->add_image( $_postThread, $_postId, "image2", $rootDir, $db, 2);
					}
				
					if (isset( $_FILES['image3']) and !$_FILES['image3']['error'] ) {		
				    	$this->add_image( $_postThread, $_postId, "image3", $rootDir, $db, 3);
					}
				
					if (isset( $_FILES['image4']) and !$_FILES['image4']['error'] ) {		
				    	$this->add_image( $_postThread, $_postId, "image4", $rootDir, $db, 4);
					}
				
					if (isset( $_FILES['image5']) and !$_FILES['image5']['error'] ) {		
				    	$this->add_image( $_postThread, $_postId, "image5", $rootDir, $db, 5);
					}
					
					
					
				
					if ( $wfm == 1) { // this post needs moderator approval
						$CofiHelper->sendEmailToModeratorsPostWFM();
						$app->redirect( $redirectLink, JText::_( 'COFI_POST_SAVED_NEEDS_APPROVAL' ), "notice"); 
					}
					else {
                        // redirect	link to last post
                        $redirectLinkToLastPost = $this->getLinkToLastPostByThreadId( $_postThread);
						$app->redirect( $redirectLinkToLastPost, JText::_( 'COFI_POST_SAVED' ), "notice");
					}
				}
				else {
					$app->redirect( $redirectLink, JText::_( 'COFI_POST_NOT_SAVED_INSERT_ERROR' ), "message"); 
				}

			
			
			}
		
		
		} // end insert


		
		if ( $isSubjectTooShort) {
			$app->redirect( $redirectLink, JText::_( 'COFI_POST_NOT_SAVED_SUBJECT_TOO_SHORT' ), "message"); 
		}	
		if ( $isTextTooShort) {
			$app->redirect( $redirectLink, JText::_( 'COFI_POST_NOT_SAVED_TEXT_TOO_SHORT' ), "message"); 
		}	


        // redirect	link to last post
        $redirectLinkToLastPost = $this->getLinkToLastPostByThreadId( $_postThread);
		$app->redirect( $redirectLinkToLastPost, JText::_( 'COFI_POST_SAVED' ), "notice"); 

		
        return 0; // save OK
     }



	/**
	 * Method to get the message text
	 *
	 * @access public
	 * @return String
	 */
	function getMessageText() {		
		$_id = JRequest::getInt('id', '0');

		if ( $_id <> 0) {
		
        	$db =& $this->getDBO();
			$db->setQuery( "SELECT message FROM ".$db->nameQuote( '#__discussions_messages')." WHERE id='".$_id."'");
			$_messageText = $db->loadResult();
		
		}
		else {
			$_messageText = "";
		}		

		return $_messageText;
	}



	/**
	 * Method to get the task
	 *
	 * @access public
	 * @return String
	 */
	function getTask() {
		return $this->_task;
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
     * Method to calculate the link to the last post
     *
     * @access public
     * @return String
     */
    function getLinkToLastPostByThreadId( $thread) {

        $params = JComponentHelper::getParams('com_discussions');
        $CofiHelper = new CofiHelper();

        $_threadListLength 	= $params->get('threadListLength', '20');
        $_numberOfPosts 	= $CofiHelper->getNumberOfPostsByThreadId( $thread);
        $_lastPostId 		= $CofiHelper->getLastPostIdByThreadId( $thread);

        if ( ( $_numberOfPosts % $_threadListLength) == 0) {
            $_start = ( $_numberOfPosts / $_threadListLength) - 1;
        }
        else {
            $_start = floor( $_numberOfPosts / $_threadListLength);
        }

        $_start = $_start * $_threadListLength;

        if ( $_start == 0) {  // first page = no limitstart
            $_lastEntryJumpPoint = "#p" . $_lastPostId;
        }
        else {
            $_lastEntryJumpPoint = "&limitstart=" . $_start ."#p" . $_lastPostId;
        }

        
        $lastTMP = "index.php?option=com_discussions&view=thread&catid=" . $this->getCategorySlug() . "&thread=" . $this->getThreadSlugByThreadId( $thread);
        $lastTMP .= $_lastEntryJumpPoint;
        $last = JRoute::_( $lastTMP);

        return $last;

    }





	function add_image( $thread, $id, $image, $absolute_path, $db, $imagenumber) {
	
	    // get max_imagesize from parameters
		$params = JComponentHelper::getParams('com_discussions');
		$max_image_size = $params->get('maxImageSize', '209715200'); // 200 KByte default		
	
	
	    $discussions_folder = $absolute_path."/images/discussions/";
		if ( !is_dir( $discussions_folder)) {
			mkdir($discussions_folder);
		}	
	    	    	    	    	    	    
	    $thread_folder = $absolute_path."/images/discussions/posts/";
		if ( !is_dir( $thread_folder)) {
			mkdir($thread_folder);
		}		    	    
	    	    
	    $image_folder = $absolute_path."/images/discussions/posts/". $thread . "/";
		if ( !is_dir( $image_folder)) {
			mkdir($image_folder);
		}	

	
	    $image_too_big = 0;
	    if (isset( $_FILES[$image])) {
	        if ( $_FILES[$image]['size'] > $max_image_size) {
	            $image_too_big = 1;
	        }
	    }
	
	
	    if ( $image_too_big == 1) {
	        echo "<font color='#CC0000'>";
	        echo JText::_( 'COFI_UPLOADED_IMAGE_TOO_BIG' );
	        echo "</font>";
	        echo "<br>";
	        echo "<br>";
	    }
	    else {
	        $af_size = GetImageSize ($_FILES[$image]['tmp_name']);
	
	        switch ($af_size[2]) {
	                case 1 : {
	                    $thispicext = 'gif';
	                    break;
	                }
	                case 2 : {
	                    $thispicext = 'jpg';
	                    break;
	                }
	                case 3 : {
	                    $thispicext = 'png';
	                    break;
	                }
	        }
	
	
	
	        // if ( $af_size[2] >= 1 && $af_size[2] <= 3) { // 1=GIF, 2=JPG or 3=PNG
	        if ( $af_size[2] >= 2 && $af_size[2] <= 3) { // 2=JPG or 3=PNG
		
	            chmod ( $_FILES[$image]['tmp_name'], 0644);
	
				
				// 1. if directory ./images/USERID does not exist, create it 
				// 2. create the subdirs for ORIGINAL, LARGE (128) and SMALL(32)
				if ( !is_dir( $image_folder.$id)) {
					mkdir($image_folder.$id);
					mkdir($image_folder.$id."/original"); // ORIGINAL
					mkdir($image_folder.$id."/large"); // LARGE (800)
					mkdir($image_folder.$id."/small"); // SMALL (128)
				}
	
	
				$original_image = $image_folder.$id."/original/".$id . "_" . $imagenumber . "." . $thispicext;
				$large_image = $image_folder.$id."/large/".$id . "_" . $imagenumber . "." . $thispicext;
				$small_image = $image_folder.$id."/small/".$id . "_" . $imagenumber . "." . $thispicext;
	
	
	            // copy original image to folder "original"
	            move_uploaded_file ( $_FILES[$image]['tmp_name'], $original_image);
				
	
	            // create "large" image 800px
	            switch ($af_size[2]) {
	                case 1 : $src = ImageCreateFromGif(  $original_image); break;
	                case 2 : $src = ImageCreateFromJpeg( $original_image); break;
	                case 3 : $src = ImageCreateFromPng(  $original_image); break;
	            }
	
	            $width_before  = ImageSx( $src);
	            $height_before = ImageSy( $src);
	
	            if ( $width_before  >= $height_before) {
	                $width_new = min(800, $width_before);
	                $scale = $width_before / $height_before;
	                $height_new = round( $width_new / $scale);
	            }
	            else {
	                $height_new = min(600, $height_before);
	                $scale = $height_before / $width_before;
	                $width_new = round( $height_new / $scale);
	            }
	
	            $dst = ImageCreateTrueColor( $width_new, $height_new);
	
	            // GD Lib 2
	            ImageCopyResampled( $dst, $src, 0, 0, 0, 0, $width_new, $height_new, $width_before, $height_before);
	
	            switch ($af_size[2]) {
	                case 1 : ImageGIF(  $dst, $large_image); break;
	                case 2 : ImageJPEG( $dst, $large_image); break;
	                case 3 : ImagePNG(  $dst, $large_image); break;
	            }
	
	            imagedestroy( $dst);
	            imagedestroy( $src);
	
	
	            // create "small" image 128px
	            switch ($af_size[2]) {
	                case 1 : $src = ImageCreateFromGif(  $original_image); break;
	                case 2 : $src = ImageCreateFromJpeg( $original_image); break;
	                case 3 : $src = ImageCreateFromPng(  $original_image); break;
	            }
	
	            $width_before  = ImageSx( $src);
	            $height_before = ImageSy( $src);
	
	            if ( $width_before  >= $height_before) {
	                $width_new = min(128, $width_before);
	                $scale = $width_before / $height_before;
	                $height_new = round( $width_new / $scale);
	            }
	            else {
	                $height_new = min(96, $height_before);
	                $scale = $height_before / $width_before;
	                $width_new = round( $height_new / $scale);
	            }
	
	            $dst = ImageCreateTrueColor( $width_new, $height_new);
	
	            // GD Lib 2
	            ImageCopyResampled( $dst, $src, 0, 0, 0, 0, $width_new, $height_new, $width_before, $height_before);
	
	            switch ($af_size[2]) {
	                case 1 : ImageGIF(  $dst, $small_image); break;
	                case 2 : ImageJPEG( $dst, $small_image); break;
	                case 3 : ImagePNG(  $dst, $small_image); break;
	            }
	
	            imagedestroy( $dst);
	            imagedestroy( $src);
	
	
	            // DB update
	            $sql = "UPDATE #__discussions_messages SET ". $image . "='".$id . "_" . $imagenumber . "." .$thispicext ."' WHERE id=".$id;
	
	            $db->setQuery( $sql);
	
	            if ($db->getErrorNum()) {
	                echo $db->stderr();
	            } else {
	                $db->query();
	            }
	
	
	        }
	    }
	}



	function del_image( $thread, $id, $image, $absolute_path, $db, $imagenumber) {
		
		// todo if last image is deleted -> remove all image folders for this entry
		
	    // $image_folder = $absolute_path."/components/com_discussions/assets/images/";
		$image_folder = $absolute_path."/images/discussions/posts/" . $thread . "/";
		
        // get image name
        $sql = "SELECT " . $image . " FROM #__discussions_messages WHERE id=".$id;	
		$db->setQuery( $sql);
		$imagename = $db->loadResult();


		if ( $imagename != "") {

			$original_image = $image_folder.$id."/original/" . $imagename;
			$large_image = $image_folder.$id."/large/" . $imagename;
			$small_image = $image_folder.$id."/small/" . $imagename;

            if ( file_exists( $original_image)) {
                unlink( $original_image);
            }
            if ( file_exists( $large_image)) {
                unlink( $large_image);
            }
            if ( file_exists( $small_image)) {
                unlink( $small_image);
            }
            
            // DB update
            $sql = "UPDATE #__discussions_messages SET ". $image . "='' WHERE id=".$id;

            $db->setQuery( $sql);

            if ($db->getErrorNum()) {
                echo $db->stderr();
            } else {
                $db->query();
            }

		}	
	        
	}







}

