<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted Access');



/**
 * User class
 *
 */
class CofiUser extends JObject {


    var $_id = 0;

    var $_username = null;

    var $_posts = 0;

    var $_avatarStatus = 0;

    var $_avatar = null;

    var $_signatureStatus = 0;

    var $_signature = "";

    var $_title = "";

    var $_zipcode = "";

    var $_city = "";

    var $_country = "";

    var $_show_online_status = 0;


	/* 
	* user is a forum moderator and will see moderator functions 
	*/
    var $_moderator = 0;

	/* 
	* user is moderated. A moderator has to approve this users posts
	*/
    var $_moderated = 0;

	/* 
	* user is in rookie mode. < x posts a moderator has to approve this users posts
	*/
    var $_rookie = 0;

	/* 
	* user is trusted. Maybe known by the site owner. This users posts will bypass rookie mode
	*/
    var $_trusted = 0;

	/* 
	* user is a forum moderator and will get email notifications about to approve posts
	*/
    var $_email_notification = 0;

	/* 
	* user is a forum moderator and will get a notifications about to approve posts on the index page
	*/
    var $_approval_notification = 0;




	/**
	* Constructor
	*
	* @access 	protected
	*/
	function __construct( $userid = 0) {
	
		$this->_id = $userid;

        $db = JFactory::getDBO();

		$query = "SELECT * FROM ".$db->nameQuote('#__discussions_users')." WHERE id='".$userid."'";
        $db->setQuery($query);

        $_user = $db->loadAssoc();

        $this->_username      		= $_user['username'];
        $this->_posts      			= $_user['posts'];
        $this->_avatar     			= $_user['avatar'];
        $this->_signature  			= $_user['signature'];
        $this->_title      			= $_user['title'];
        $this->_zipcode    			= $_user['zipcode'];
        $this->_city       			= $_user['city'];
        $this->_country    			= $_user['country'];
        $this->_show_online_status 	= $_user['show_online_status'];
        
        $this->_moderator  			= $_user['moderator'];
        $this->_moderated  			= $_user['moderated'];
        $this->_rookie     			= $_user['rookie'];
        $this->_trusted    			= $_user['trusted'];

        $this->_website    			= $_user['website'];
        $this->_twitter    			= $_user['twitter'];
        $this->_facebook   			= $_user['facebook'];
        $this->_flickr     			= $_user['flickr'];
        $this->_youtube    			= $_user['youtube'];

        $this->_email_notification  = $_user['email_notification'];
        $this->_approval_notification  = $_user['approval_notification'];

	}


	function setId( $id) {
		$this->_id = $id;
	}

	function setUsername( $username) {
		$this->_username = $username;
	}

	function setPosts( $posts) {
		$this->_posts = $posts;
	}

	function setAvatarStatus( $avatarStatus) {
		$this->_avatarStatus = $avatarStatus;
	}

	function setAvatar( $avatar) {
		$this->_avatar = $avatar;
	}

	function setSignatureStatus( $signatureStatus) {
		$this->_signatureStatus = $signatureStatus;
	}

	function setSignature( $signature) {
		$this->_signature = $signature;
	}

	function setTitle( $title) {
		$this->_title = $title;
	}
	
	function setZipcode( $zipcode) {
		$this->_zipcode = $zipcode;
	}
	
	function setCity( $city) {
		$this->_city = $city;
	}
	
	function setCountry( $country) {
		$this->_country = $country;
	}

	function setModerator( $moderator) {
		$this->_moderator = $moderator;
		
		$db = JFactory::getDBO();
		$sql = "UPDATE ".$db->nameQuote( '#__discussions_users')." SET" . 
					" moderator = " . $db->Quote( $this->_moderator) . 
					" WHERE id = '".$this->_id."'";
		$db->setQuery( $sql);
		$result = $db->query();		
	}

	function setModerated( $moderated) {
		$this->_moderated = $moderated;

		$db = JFactory::getDBO();
		$sql = "UPDATE ".$db->nameQuote( '#__discussions_users')." SET" . 
					" moderated = " . $db->Quote( $this->_moderated) . 
					" WHERE id = '".$this->_id."'";
		$db->setQuery( $sql);
		$result = $db->query();		
	}

	function setRookie( $rookie) {
		$this->_rookie = $rookie;

		$db = JFactory::getDBO();
		$sql = "UPDATE ".$db->nameQuote( '#__discussions_users')." SET" . 
					" rookie = " . $db->Quote( $this->_rookie) . 
					" WHERE id = '".$this->_id."'";
		$db->setQuery( $sql);
		$result = $db->query();		
	}

	function setTrusted( $trusted) {
		$this->_trusted = $trusted;

		$db = JFactory::getDBO();
		$sql = "UPDATE ".$db->nameQuote( '#__discussions_users')." SET" . 
					" trusted = " . $db->Quote( $this->_trusted) . 
					" WHERE id = '".$this->_id."'";
		$db->setQuery( $sql);
		$result = $db->query();		
	}



	function getId() {
		return $this->_id;
	}

	function getUsername() {
		return $this->_username;
	}

	function getPosts() {
		return $this->_posts;
	}

	function getAvatarStatus() {
		return $this->_avatarStatus;
	}

	function getAvatar() {
		return $this->_avatar;
	}

	function getSignatureStatus() {
		return $this->_signatureStatus;
	}
    
	function getSignature() {
		return $this->_signature;
	}

	function getTitle() {
		return $this->_title;
	}

	function getZipcode() {
		return $this->_zipcode;
	}
	function getCity() {
		return $this->_city;
	}
	function getCountry() {
		return $this->_country;
	}
	function getShowOnlineStatus() {
		return $this->_show_online_status;
	}



	function isModerator() {
		return $this->_moderator;
	}

	function isModerated() {
		return $this->_moderated;
	}

	function isRookie() {
		return $this->_rookie;
	}

	function isTrusted() {
		return $this->_trusted;
	}

	function isEmailNotification() {
		return $this->_email_notification;
	}

	function isApprovalNotification() {
		return $this->_approval_notification;
	}


	function getWebsite() {
		return $this->_website;
	}
	function getTwitter() {
		return $this->_twitter;
	}
	function getFacebook() {
		return $this->_facebook;
	}
	function getFlickr() {
		return $this->_flickr;
	}
	function getYoutube() {
		return $this->_youtube;
	}



}


