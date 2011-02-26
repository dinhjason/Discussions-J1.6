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
 * Discussions Moderation Model
 */
class DiscussionsModelModeration extends JModel {



	/**
	 * categoryFrom id
	 *
	 * @var integer
	 */
	var $_categoryFrom = 0;



	/**
	 * categoryTo id
	 *
	 * @var integer
	 */
	var $_categoryTo = 0;



	/**
	 * thread id
	 *
	 * @var integer
	 */
	var $_thread = 0;



	/**
	 * task
	 *
	 * @var String
	 */
	var $_task = "";



	/**
	 * headline
	 *
	 * @var String
	 */
	var $_headline = "";



	/**
	 * Posts WFM list array
	 *
	 * @var array
	 */
	var $_data = null;


	/**
	 * post id
	 *
	 * @var integer
	 */
	var $_post = 0;




	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct() {
	
		parent::__construct();

		$app = JFactory::getApplication();

     	$this->_task   = JRequest::getString( 'task', '');

     	$this->_thread = JRequest::getInt( 'thread', 0);

     	$this->_categoryFrom = JRequest::getInt( 'catid', 0);
     	$this->_categoryTo = JRequest::getInt( 'catidto', 0);

     	$this->_post = JRequest::getInt( 'post', 0);
     	if ( $this->_post == 0) { $this->_post = JRequest::getInt( 'id', 0);}
				
		
		$user =& JFactory::getUser();
		$logUser = new CofiUser( $user->id);
		
		
		if ( $logUser->isModerator()) {
				
			switch ( $this->_task) {

				case "move": {
					$this->_headline = JText::_( 'COFI_MOVE_THREAD' );	
				
					if ( $this->_categoryTo > 0) { // move it now
						$this->moveThread();
					}			
					break;
				}

				case "sticky": {				
					$this->stickyThread();
					break;
				}

				case "unsticky": {				
					$this->unstickyThread();
					break;
				}

				case "lock": {				
					$this->lockThread();
					break;
				}

				case "unlock": {				
					$this->unlockThread();
					break;
				}

				case "accept": {
					$this->acceptPost( $this->_post);
					break;
				}

				case "deny": {
					$this->denyPost( $this->_post);
					break;
				}

				case "createmsgaliases": {
					$this->createMsgAliases();
					break;
				}

				case "delete": {
					$this->deletePost( $this->_post);
					break;
				}
						
				default: {
					break;
				}
						
			}
		
		}
		else { // not allowed
			// redirect	link
			$redirectLink = JRoute::_( "index.php?option=com_discussions&view=category&catid=".$this->_categoryFrom);
			$app->redirect( $redirectLink, JText::_( 'COFI_NO_ACCESS_TO_MODERATOR_FUNCTIONS' ), "notice");		
		}
		

	}



	/**
     * Make thread sticky
     *
     * @return integer
     */
     private function stickyThread() {

		$app = JFactory::getApplication();

        $db =& $this->getDBO();

		// make thread sticky
     	$sql = "UPDATE ".$db->nameQuote( '#__discussions_messages') . 
     						" SET sticky = '1'" . 
     						" WHERE thread = '".$this->_thread."' AND parent_id='0'";
        	
        $db->setQuery( $sql);
        $result = $db->query();

		// redirect	link
		$redirectLink = JRoute::_( "index.php?option=com_discussions&view=category&catid=".$this->_categoryFrom);
		$app->redirect( $redirectLink, JText::_( 'COFI_THREAD_MADE_STICKY' ), "notice"); 

        return 0; // sticky OK

	}



	/**
     * Make thread unsticky
     *
     * @return integer
     */
     private function unstickyThread() {

		$app = JFactory::getApplication();

        $db =& $this->getDBO();

		// make thread unsticky
     	$sql = "UPDATE ".$db->nameQuote( '#__discussions_messages') . 
     						" SET sticky = '0'" . 
     						" WHERE thread = '".$this->_thread."' AND parent_id='0'";
        	
        $db->setQuery( $sql);
        $result = $db->query();

		// redirect	link
		$redirectLink = JRoute::_( "index.php?option=com_discussions&view=category&catid=".$this->_categoryFrom);
		$app->redirect( $redirectLink, JText::_( 'COFI_THREAD_MADE_UNSTICKY' ), "notice"); 

        return 0; // unsticky OK

	}



	/**
     * Lock thread
     *
     * @return integer
     */
     private function lockThread() {

		$app = JFactory::getApplication();

        $db =& $this->getDBO();

		// lock thread
     	$sql = "UPDATE ".$db->nameQuote( '#__discussions_messages') . 
     						" SET locked = '1'" . 
     						" WHERE thread = '".$this->_thread."' AND parent_id='0'";
        	
        $db->setQuery( $sql);
        $result = $db->query();


		// redirect	link
		$redirectLink = JRoute::_( "index.php?option=com_discussions&view=category&catid=".$this->_categoryFrom);
		$app->redirect( $redirectLink, JText::_( 'COFI_THREAD_LOCKED' ), "notice"); 

        return 0; // lock OK

	}



	/**
     * Unlock thread
     *
     * @return integer
     */
     private function unlockThread() {

		$app = JFactory::getApplication();

        $db =& $this->getDBO();

		// unlock thread
     	$sql = "UPDATE ".$db->nameQuote( '#__discussions_messages') . 
     						" SET locked = '0'" . 
     						" WHERE thread = '".$this->_thread."' AND parent_id='0'";
        	
        $db->setQuery( $sql);
        $result = $db->query();

		// redirect	link
		$redirectLink = JRoute::_( "index.php?option=com_discussions&view=category&catid=".$this->_categoryFrom);
		$app->redirect( $redirectLink, JText::_( 'COFI_THREAD_UNLOCKED' ), "notice"); 

        return 0; // unlock OK

	}



	/**
     * Move Thread from one category to another
     *
     * @return integer
     */
     private function moveThread() {

		$app = JFactory::getApplication();

        $db =& $this->getDBO();

		$CofiHelper = new CofiHelper();

		// move category
     	$sql = "UPDATE ".$db->nameQuote( '#__discussions_messages') . 
					" SET cat_id = '".$this->_categoryTo."'" . 
					" WHERE thread = '".$this->_thread."'";
        	
        $db->setQuery( $sql);
        $result = $db->query();

		// call helper function to update stats of both categories (from and to)
		$result = $CofiHelper->updateCategoryStats( $this->_categoryFrom);									
		$result = $CofiHelper->updateCategoryStats( $this->_categoryTo);

		// redirect	link
		$redirectLink = JRoute::_( "index.php?option=com_discussions");
		$app->redirect( $redirectLink, JText::_( 'COFI_THREAD_MOVED' ), "notice"); 

        return 0; // move OK
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
	 * Method to get the thread
	 *
	 * @access public
	 * @return String
	 */
	function getThread() {
		return $this->_thread;
	}



	/**
	 * Method to get the from category
	 *
	 * @access public
	 * @return integer
	 */
	function getCategoryFrom() {
		return $this->_categoryFrom;
	}



	/**
	 * Method to get the to category
	 *
	 * @access public
	 * @return integer
	 */
	function getCategoryTo() {
		return $this->_categoryTo;
	}



	/**
     * Accept single post
     *
     * @return integer
     */
     private function acceptPost( $post) {

		$app = JFactory::getApplication();
		
		$CofiHelper = new CofiHelper();

		// call helper function to accept post and update stats
		$result = $CofiHelper->acceptPost( $post);		

		// redirect	link
		$redirectLink = JRoute::_( "index.php?option=com_discussions&view=moderation&task=approve");
		$app->redirect( $redirectLink, JText::_( 'COFI_POST_ACCEPTED' ), "notice"); 

        return 0;

	}




	/**
     * Deny single post
     *
     * @return integer
     */
     private function denyPost( $post) {
		
		$app = JFactory::getApplication();
		
		$CofiHelper = new CofiHelper();

		// call helper function to deny post
		$result = $CofiHelper->denyPost( $post);		

		// redirect	link
		$redirectLink = JRoute::_( "index.php?option=com_discussions&view=moderation&task=approve");
		$app->redirect( $redirectLink, JText::_( 'COFI_POST_DENIED' ), "notice"); 

        return 0;

	}




	/**
     * Gets Threads data
     *
     * @return array
     */
     function getPostingsWFM() {

        $db =& $this->getDBO();

		// Load the postings if they doesn't exist
		if (empty( $this->_data)) {
			$selectWFMQuery = $this->_buildSelectWFMQuery();

            $limitstart = $this->getState('limitstart');
            $limit = $this->getState('limit');

			$this->_data = $this->_getList( $selectWFMQuery, $limitstart, $limit);
		}

        // return the post list data
        return $this->_data;
            
     }




	function _buildSelectWFMQuery() {
		
        $params 		= JComponentHelper::getParams('com_discussions');
		$_dateformat	= $params->get( 'dateformat', '%d.%m.%Y');
		$_timeformat	= $params->get( 'timeformat', '%H:%i');        		        	        		        				
	
        $db =& $this->getDBO();
        
		$wfmquery = "SELECT id, parent_id, cat_id, thread, user_id, type, subject, message,
                    DATE_FORMAT( date, '" . $_dateformat . " " . $_timeformat . "') AS date, published, wfm
					FROM ".$db->nameQuote('#__discussions_messages')."
					WHERE wfm='1' ORDER BY id ASC";
					
        return $wfmquery;

	}



	function createMsgaliases() {
	
        $db =& $this->getDBO();

		$sql = "SELECT id, subject, alias FROM " . $db->nameQuote('#__discussions_messages');
        $db->setQuery($sql);

        $rows = $db->loadAssocList();
        
		if( count( $rows)) {

			echo "Creating aliases...";
			echo "<br />";
		
			foreach ($rows as $row) {
			
		        $m_id      	= $row['id'];
		        $m_subject  = $row['subject'];
						
				// create alias for SEF URL
				jimport( 'joomla.filter.output' );
    			    			
    			$m_alias = JFilterOutput::stringURLSafe($m_subject);
				
     			$sqlu = "UPDATE ".$db->nameQuote( '#__discussions_messages') . 
							" SET alias = '".$m_alias."'" . 
							" WHERE id = '".$m_id."'";
        	
        		$db->setQuery( $sqlu);
        		$result = $db->query();
								
			}
			
			echo "<br />";
			echo "done";
			echo "<br />";
			echo "<br />";
		
		}
	
        return $result;
	}



	/**
     * Delete post / thread
     *
     * @return integer
     */
     private function deletePost( $post) {

		$app = JFactory::getApplication();

		$CofiHelper = new CofiHelper();
		
        $db =& $this->getDBO();


		// 1. get thread id		
        $_threadQuery = "SELECT thread FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $post . "'";
        $db->setQuery( $_threadQuery);
        $_threadId = $db->loadResult();


		// 2. get parent id		
        $_parentQuery = "SELECT parent_id FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $post . "'";
        $db->setQuery( $_parentQuery);
        $_parentId = $db->loadResult();
		
		
		// 3. get category id		
        $_categoryQuery = "SELECT cat_id FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $post . "'";
        $db->setQuery( $_categoryQuery);
        $_categoryId = $db->loadResult();


		// 4. get user id		
        $_userQuery = "SELECT user_id FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $post . "'";
        $db->setQuery( $_userQuery);
        $_userId = $db->loadResult();
						
				
		// Check if thread id = post id, means this is the original post OP
		// if OP then delete all posts in this thread (delete thread)
		// if not OP then delete single post
		if ( $_threadId == $post) {

			// get post ids in this thread
	     	$sql = "SELECT id FROM" . $db->nameQuote( '#__discussions_messages') . " WHERE thread='" . $_threadId . "'";	        
	        $db->setQuery( $sql);
			$_postList = $db->loadAssocList();
			
			// remove images of these posts
			if( count( $_postList)) {		

				foreach ($_postList as $_post) {

					$p_id = $_post['id'];

					// remove images
					$CofiHelper->deleteImagesByPostId( $p_id);

				}
				
				$rootDir = JPATH_ROOT;
				$threadfolder 	= $rootDir . "/images/discussions/posts/" . $_threadId;
		    	if (is_dir( $threadfolder)) {
		    		rmdir( $threadfolder );
		    	}

			}								
			
			// get users who posted in this thread
	     	$sql = "SELECT DISTINCT user_id FROM" . $db->nameQuote( '#__discussions_messages') . " WHERE thread='" . $_threadId . "'";	        
	        $db->setQuery( $sql);
			$_userList = $db->loadAssocList();						

			// delete thread ( all posts in it) from db
	     	$sql = "DELETE FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE thread='" . $_threadId . "'";	        
	        $db->setQuery( $sql);
			$db->query();	
			
			// now update user stats					
			if( count( $_userList)) {		

				foreach ($_userList as $_user) {

					$u_id = $_user['user_id'];			

					// update user stats
					$result = $CofiHelper->updateUserStats( $u_id);					

				}

			}								
								
		}
		else { // not OP
		
			// remove images belonging to this post
			$CofiHelper->deleteImagesByPostId( $post);
		
			// delete post from db
	     	$sql = "DELETE FROM " . $db->nameQuote( '#__discussions_messages') . " WHERE id='" . $post . "'";	        
	        $db->setQuery( $sql);
			$db->query();	
		
			// change parent id of possible replies to this post
     		$sql = "UPDATE " . $db->nameQuote( '#__discussions_messages') . 
     					" SET parent_id = '" . $_parentId . "'" . 
     					" WHERE parent_id = '" . $post . "'";
        	
        	$db->setQuery( $sql);
        	$result = $db->query();

			// update category stats
			$result = $CofiHelper->updateThreadStats( $_threadId);

			// update user stats
			$result = $CofiHelper->updateUserStats( $_userId);
								
		}
		
		// update category stats
		$result = $CofiHelper->updateCategoryStats( $_categoryId);									

		// redirect	link
		$redirectLink = JRoute::_( "index.php?option=com_discussions&view=index");
		$app->redirect( $redirectLink, JText::_( 'COFI_POST_DELETED' ), "notice"); 

        return 0;

	}



}

