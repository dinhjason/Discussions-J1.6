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
	 * Category class
	 * 
	 */ 
class Category extends JObject { 


	var $_name = null;
	var $_description = null;


	function setName( $name) {
		$this->_name = $name;	
	} 

	function setDescription( $description) {
		$this->_description = $description;	
	} 


	function getName() {
		return $this->_name;	
	} 

	function getDescription() {
		return $this->_description;
	} 

} 

