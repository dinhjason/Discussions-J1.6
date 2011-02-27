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


// version of new installed extension
$version = "1.3";




$componentInstaller =& JInstaller::getInstance();
$installer = new JInstaller();

$db =& JFactory::getDBO();



// check if Discussions system plugin is already installed
$pathToPlgDiscussionsSystem = $componentInstaller->getPath('source') . DS . 'plugins' . DS . 'system';

$query = 'SELECT COUNT(*)'
			. ' FROM ' . $db->nameQuote('#__extensions')
			. ' WHERE ' . $db->nameQuote('element') . ' = '
			. $db->Quote('discussions')
			. ' AND ' . $db->nameQuote('type') . ' = '
			. $db->Quote('plugin')
			. ' AND ' . $db->nameQuote('folder') . ' = '
			. $db->Quote('system');
			
$db->setQuery($query);

$discussionsSystemPluginInstalled = (bool)$db->loadResult();

if ( $discussionsSystemPluginInstalled) {

	// upgrade the Discussions system plugin
	if ( !$installer->install( $pathToPlgDiscussionsSystem)) {
	
		echo "Failed to upgrade the Discussions system plugin!";
		echo "<br />";
	
	} 
	else {
	
		echo "Successfully upgraded the Discussions system plugin";
		echo "<br />";
	
	}
	
} 
else {

	// install the Discussions system plugin
	if ( !$installer->install( $pathToPlgDiscussionsSystem)) {
	
		echo "Failed to install the Discussions system plugin!";
		echo "<br />";
	
	} 
	else {
	
		echo "Successfully installed the Discussions system plugin";
		echo "<br />";
	
	}

}


// enable Discussions system plugin
$query = 'UPDATE ' . $db->nameQuote('#__extensions')
       	. ' SET ' . $db->nameQuote('enabled') . ' = 1'
       	. ' WHERE ' . $db->nameQuote('element') . ' = ' . $db->Quote('discussions')
       	. ' AND ' .   $db->nameQuote('type')  . ' = ' . $db->Quote('plugin')
       	. ' AND ' .   $db->nameQuote('folder')  . ' = ' . $db->Quote('system');
       	
$db->setQuery($query);

if (!$db->query()) {

    echo "Failed to enable the Discussions system plugin!";
	echo "<br />";
    
} 
else {

    echo "Successfully enabled the Discussions system plugin";
	echo "<br />";
    
}
    


// check if Discussions search plugin is already installed
$pathToPlgDiscussionsSearch = $componentInstaller->getPath('source') . DS . 'plugins' . DS . 'search';

$query = 'SELECT COUNT(*)'
			. ' FROM ' . $db->nameQuote('#__extensions')
			. ' WHERE ' . $db->nameQuote('element') . ' = '
			. $db->Quote('discussions')
			. ' AND ' . $db->nameQuote('type') . ' = '
			. $db->Quote('plugin')
			. ' AND ' . $db->nameQuote('folder') . ' = '
			. $db->Quote('search');
			
$db->setQuery($query);

$discussionsSearchPluginInstalled = (bool)$db->loadResult();

if ( $discussionsSearchPluginInstalled) {

	// upgrade the Discussions search plugin
	if ( !$installer->install( $pathToPlgDiscussionsSearch)) {
	
		echo "Failed to upgrade the Discussions search plugin!";
		echo "<br />";
	
	} 
	else {
	
		echo "Successfully upgraded the Discussions search plugin";
		echo "<br />";
	
	}
	
} 
else {

	// install the Discussions search plugin
	if ( !$installer->install( $pathToPlgDiscussionsSearch)) {
	
		echo "Failed to install the Discussions search plugin!";
		echo "<br />";
	
	} 
	else {
	
		echo "Successfully installed the Discussions search plugin";
		echo "<br />";
	
	}

}

// enabled Discussions search plugin
$query = 'UPDATE ' . $db->nameQuote('#__extensions')
       	. ' SET ' . $db->nameQuote('enabled') . ' = 1'
       	. ' WHERE ' . $db->nameQuote('element') . ' = ' . $db->Quote('discussions')
       	. ' AND ' .   $db->nameQuote('type')  . ' = ' . $db->Quote('plugin')
       	. ' AND ' .   $db->nameQuote('folder')  . ' = ' . $db->Quote('search');
       	
$db->setQuery($query);

if (!$db->query()) {

    echo "Failed to enable the Discussions search plugin!";
	echo "<br />";
    
} 
else {

    echo "Successfully enabled the Discussions search plugin";
	echo "<br />";
    
}



// 1. get/set version information
$db->setQuery( 'SELECT COUNT(*) FROM `#__discussions_meta`');

if ( $db->loadResult() == 0) { // no record found = fresh installation

	$db->setQuery( "INSERT INTO `#__discussions_meta` ( id, version) VALUES ('1', '" . $version . "')");
	$db->query();
	
}
else { // upgrade

	// get current version
	$db->setQuery( "SELECT version FROM `#__discussions_meta` WHERE id='1'");
	$_version = $db->loadResult();
			
	switch ( $_version) {
	
		case "1.0": { // upgrade 1.0 -> new version

			echo "Upgrading from 1.0 to " . $version;
			echo "<br />";


			// new fields						
			$sql = "ALTER TABLE `#__discussions_users` ADD COLUMN `show_online_status` tinyint(1) DEFAULT '1'";
			$db->setQuery( $sql);
			$db->query();			


			// new indexes
			$sql = "ALTER TABLE `#__discussions_messages` ADD INDEX `idx_published` (published)";
			$db->setQuery( $sql);
			$db->query();			
				
			$sql = "ALTER TABLE `#__discussions_messages` ADD INDEX `idx_wfm` (wfm)";
			$db->setQuery( $sql);
			$db->query();			

			$sql = "ALTER TABLE `#__discussions_messages` ADD INDEX `idx_date` (date)";
			$db->setQuery( $sql);
			$db->query();			
										
		}


		case "1.1": { // upgrade 1.1 -> new version

			echo "Upgrading from 1.1 to " . $version;
			echo "<br />";

			// new fields						
			$sql = "ALTER TABLE `#__discussions_categories` ADD COLUMN `meta_title` varchar(255) DEFAULT ''";
			$db->setQuery( $sql);
			$db->query();			

			$sql = "ALTER TABLE `#__discussions_categories` ADD COLUMN `meta_description` varchar(255) DEFAULT ''";
			$db->setQuery( $sql);
			$db->query();			

			$sql = "ALTER TABLE `#__discussions_categories` ADD COLUMN `meta_keywords` varchar(255) DEFAULT ''";
			$db->setQuery( $sql);
			$db->query();			
					
			$sql = "ALTER TABLE `#__discussions_categories` ADD COLUMN `banner_top` text DEFAULT ''";
			$db->setQuery( $sql);
			$db->query();			
					
			$sql = "ALTER TABLE `#__discussions_categories` ADD COLUMN `banner_bottom` text DEFAULT ''";
			$db->setQuery( $sql);
			$db->query();			
					
					
			break;
		}


		case "1.2": { // upgrade 1.2 -> new version

			echo "Upgrading from 1.2 to " . $version;
			echo "<br />";

			// new fields											
					
			break;
		}


		
		default: {
			break;
		}
		
	}

	// done. set new version
	$db->setQuery( "UPDATE `#__discussions_meta` SET id='1', version='" . $version . "'");
	$db->query();

	echo "Upgrade done";
	echo "<br />";
	
}



// 2. if we are doing a new installation get all users from users table
$db->setQuery( 'SELECT COUNT(*) FROM `#__discussions_users`');

if ( $db->loadResult() == 0) { // no records found = fresh installation

	$db->setQuery( "INSERT INTO `#__discussions_users` ( id, username) SELECT id, username FROM `#__users` ORDER BY id ASC");
	$db->query();
	
}



// 3. if there are no forums -> install some sample data
$db->setQuery( 'SELECT COUNT(*) FROM `#__discussions_categories`');

if ( $db->loadResult() == 0) { // no records found = fresh installation

	$sql = "INSERT INTO `#__discussions_categories` ( 
				id, parent_id, name, alias, description, show_image, published
			) VALUES (
				'1', '0', 'Demo Container', '', 'Top level forums act like containers', '0', '1'
			)";

	$db->setQuery( $sql);
	$db->query();
	

	$sql = "INSERT INTO `#__discussions_categories` ( 
				id, parent_id, name, alias, description, show_image, published
			) VALUES (
				'2', '1', 'Demo Forum', 'demo-forum', 'Demo Forum', '1', '1'
			)";

	$db->setQuery( $sql);
	$db->query();
		
}



echo "<br />";
echo "Have fun with Discussions " . $version;
echo "<br />";
echo "<br />";















