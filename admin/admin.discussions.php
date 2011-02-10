<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
$user = & JFactory::getUser();

$db	=& JFactory::getDBO();		
$sql = "SELECT version FROM ".$db->nameQuote('#__discussions_meta')." WHERE id='1'";		
$db->setQuery( $sql);
$version = $db->loadResult();


$view = JRequest::getWord('view');

$document = & JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_discussions/assets/discussions.css');


$controller = JRequest::getWord('view', 'dashboard');

require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
$classname = 'DiscussionsController'.$controller;

$controller = new $classname();
$controller->registerTask('saveAndNew', 'save');
$controller->execute(JRequest::getWord('task'));
$controller->redirect();

?>


<div id="diFooter">
	<div id="diFooterVersion">
		Discussions v<?php echo $version; ?>	
	</div>
	<div id="diFooterCopyright">
		(c) 2010-2011 <a href="http://www.codingfish.com" target="_blank" title="Codingfish">Codingfish</a>
	</div>
</div>
