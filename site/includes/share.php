<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */
 
$params = JComponentHelper::getParams('com_discussions');
$shareCode = $params->get('shareCode', '');		

if ( $shareCode != "") {
	echo "<div class='cofiShareCode'>";
		echo $shareCode;
	echo "</div>";
}

