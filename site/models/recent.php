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

require_once( JPATH_COMPONENT . DS . 'classes/user.php');


/**
 * Discussions Category Model
 */
class DiscussionsModelRecent extends JModel {



	/**
	 * thread List array
	 *
	 * @var array
	 */
	var $_data = null;


	/**
	 * recent total
	 *
	 * @var integer
	 */
	var $_total = null;



	/**
	 * exist status
	 *
	 * @var integer
	 */
	var $_existStatus = null;


    /**
     * hours
     *
     * @var integer
     */
    var $_hours = null;



	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct() {
		global $mainframe;

		parent::__construct();

        $_task = JRequest::getVar('task', 'time');
        $_time = JRequest::getVar('time', '24h');

        switch ( $_time) {

            case "4h": {
                $this->_hours = 4;
                break;
            }

            case "8h": {
                $this->_hours = 8;
                break;
            }

            case "12h": {
                $this->_hours = 12;
                break;
            }

            case "24h": {
                $this->_hours = 24;
                break;
            }

            default: {
                $this->_hours = 24;
                break;
            }

        }


		// get parameters
		$params = JComponentHelper::getParams('com_discussions');

		$_categoryListLength = $params->get('categoryListLength', '20');

		$this->setState('limit', $_categoryListLength, 'int');

		$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));

	}



	/**
     * Gets postings data
     *
     * @return array
     */
     function getPostings() {

		global $mainframe;

     	$_task = JRequest::getVar('task', 'time');
        $_time = JRequest::getVar('time', '24h');


        $db =& $this->getDBO();

        // Load postings if they doesn't exist
        if ( empty( $this->_data)) {
            $selectQuery = $this->_buildSelectQuery();

            $limitstart = $this->getState('limitstart');
            $limit = $this->getState('limit');

            $this->_data = $this->_getList( $selectQuery, $limitstart, $limit);
        }

        // return the posting list data
        return $this->_data;

     }



	/**
	 * Method to get the total number of postings in this range
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal() {
		if ( empty( $this->_total)) {
			$countQuery = $this->_buildCountQuery();
			$this->_total = $this->_getListCount( $countQuery);
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
		if ( empty( $this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}



	function _buildSelectQuery() {

        $_task = JRequest::getVar('task', 'time');
        $_time = JRequest::getVar('time', '24h');

        switch ( $_time) {

            case "4h": {
                $duration = 4;
                break;
            }

            case "8h": {
                $duration = 8;
                break;
            }

            case "12h": {
                $duration = 12;
                break;
            }

            case "24h": {
                $duration = 24;
                break;
            }

            default: {
                $duration = 24;
                break;
            }

        }

        
        $db =& $this->getDBO();


        $params = JComponentHelper::getParams('com_discussions');        
		$_dateformat	= $params->get( 'dateformat', '%d.%m.%Y');
		$_timeformat	= $params->get( 'timeformat', '%H:%i');        		        	        		        		


        $db->setQuery( "SELECT CURRENT_TIMESTAMP()");
        $date_current = $db->loadResult();
        

		$selectQuery = "SELECT m.id, m.parent_id, m.cat_id, m.thread, m.user_id, m.subject," .
							" m.type, DATE_FORMAT( m.date, '" . $_dateformat . " " . $_timeformat . "') AS date_created," .
                    		" m.published" .
						" FROM " . 
							$db->nameQuote( '#__discussions_messages') . " m," .
							$db->nameQuote( '#__discussions_categories') . " c" .
						" WHERE" . 
							" m.cat_id=c.id" . " AND" .
							" c.private='0'" . " AND" .
							" m.published='1'" . " AND" . 
							" c.published='1'" . " AND" . 
							" m.date > DATE_SUB( '" . $date_current . "', INTERVAL " . $duration . " HOUR)" .								
						" ORDER BY m.date DESC";

        return $selectQuery;

	}



	function _buildCountQuery() {

        $_task = JRequest::getVar('task', 'recent');
        $_time = JRequest::getVar('time', '24h');

        switch ( $_time) {

            case "4h": {
                $duration = 4;
                break;
            }

            case "8h": {
                $duration = 8;
                break;
            }

            case "12h": {
                $duration = 12;
                break;
            }

            case "24h": {
                $duration = 24;
                break;
            }

            default: {
                $duration = 24;
                break;
            }

        }

        $db =& $this->getDBO();

        $db->setQuery( "SELECT CURRENT_TIMESTAMP()");
        $date_current = $db->loadResult();



		$countQuery = "SELECT m.* FROM " . 
					$db->nameQuote( '#__discussions_messages') . " m," .
					$db->nameQuote( '#__discussions_categories') . " c" .
				" WHERE" . 
					" m.cat_id=c.id" . " AND" .
					" c.private='0'" . " AND" .
					" m.published='1'" . " AND" . 
					" c.published='1'" . " AND" . 
					" m.date > DATE_SUB( '" . $date_current . "', INTERVAL " . $duration . " HOUR)";
					
		return $countQuery;
	}



    /**
     * Method to get the recent hours
     *
     * @access public
     * @return integer
     */
    function getHours() {

        return $this->_hours;

    }



}
