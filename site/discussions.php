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


// get the controller 
require_once(JPATH_COMPONENT.DS.'controller.php'); 

require_once(JPATH_COMPONENT.DS.'classes'.DS.'helper.php');


// instantiate and execute the controller 
$controller = new DiscussionsController(); 

$controller->execute(JRequest::getCmd('task', 'display')); 


// redirect 
$controller->redirect();
