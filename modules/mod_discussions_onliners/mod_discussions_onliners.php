<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	mod_discussions_onliners
 * @copyright	Copyright (C) 2011 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */

defined('_JEXEC') or die('Restricted access');
?>

<style type="text/css">

.cofiOnlinersUserBox {
	width: 75px;
	margin: 2px 3px 2px 2px;
	padding: 5px;
	text-align: center;
	font-size: 11px;
	float: left;
	background: #FFFFFF;
	border: 1px solid #EEEEEE;
}
.cofiOnlinersImageBox {
	width:32px;
	height:32px;
	text-align: center;
	background: white;
	margin: 5px 5px 5px 18px ; 
	padding: 2px; 
	border: 1px solid #ddd;
}
.cofiOnlinersAvatar {
	border:0px;
	margin:0px;
}
.cofiOnlinersDefaultAvatar {
	width:32px;
	height:32px;
	border:0px;
	margin:0px;
}
.cofiOnlinersUsername {
	margin: 3px 0px 0px 0px;
	overflow: hidden;
}
.cofiOnlinersPoweredByText {
	margin-top: 10px;
	margin-bottom: 0px;
	color: #777777;
	font-size: 9px;
}

</style>


<?php

// get website root directory
$_root = JURI::root();

$_number 			= $params->get( 'number', '' );
$_show_poweredby 	= $params->get( 'show_poweredby', 1 );


$db	=& JFactory::getDBO();

$posts = null;

if ( $_number == '') { // default show all users online
	
	$query = 'SELECT DISTINCT s.userid, d.username, d.avatar' .
			' FROM #__session s, #__discussions_users d' .
			' WHERE s.guest=0 AND d.show_online_status=1 AND s.userid=d.id' .
			' ORDER BY s.time DESC';	
			
}
else { // limit the number of users to show
	
	$query = 'SELECT DISTINCT s.userid, d.username, d.avatar' .
			' FROM #__session s, #__discussions_users d' .
			' WHERE s.guest=0 AND d.show_online_status=1 AND s.userid=d.id' .
			' ORDER BY s.time DESC LIMIT ' . $_number;	
				
}


$db->setQuery($query);
$onliners = $db->loadObjectList();

if ($db->getErrorNum()) {
	JError::raiseWarning( 500, $db->stderr() );
}


if (count($onliners)) {
	
    foreach ($onliners as $onliner) {

		$_alt = $onliner->username;
		$_title = $_alt;
	
		echo "<div class='cofiOnlinersUserBox'>";

			echo "<div class='cofiOnlinersImageBox'>";
				if ( $onliner->avatar == "") { // no avatar set, show default one
					$_src = $_root . "components/com_discussions/assets/users/user.png";
	               	echo "<img border='0' alt='" . $_alt . "' title='" . $_title . "' " . "src='" . $_src . "' class='cofiOnlinersDefaultAvatar' >";				
				}
				else {
					$_src = $_root . "images/discussions/users/" . $onliner->userid . "/small/" . $onliner->avatar;
	               	echo "<img border='0' alt='" . $_alt . "' title='" . $_title . "' " . "src='" . $_src . "' class='cofiOnlinersAvatar' >";
				}
			echo "</div>";
							
			echo "<div class='cofiOnlinersUsername'>";
				echo $onliner->username;
			echo "</div>";
			
		echo "</div>";
							
	}
		
}


if ( $_show_poweredby == 1) {
	echo "<br style='clear:left;'/>";
	
	echo "<div class='cofiOnlinersPoweredByText'>";
		echo "Powered by <a href='http://www.codingfish.com/products/discussions' target='_blank' title='Discussions' >Discussions</a>";
	echo "</div>";
}	





