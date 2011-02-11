<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



/*
 * Make sure the user is authorized to view this page
 */
$user = & JFactory::getUser();
//if (!$user->authorize( 'com_discussions', 'manage' )) {
//	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
//}


require_once (JPATH_COMPONENT.DS.'controller.php');

$controller	= new DiscussionsController( );


$controller->execute( JRequest::getCmd('task'));

$controller->redirect();