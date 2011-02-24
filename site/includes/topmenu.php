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

$app = JFactory::getApplication();

$user =& JFactory::getUser();


$menuLinkHome    = JRoute::_( 'index.php?option=com_discussions');
$menuLinkSearch  = '';
$menuLinkProfile = JRoute::_( 'index.php?option=com_discussions&view=profile&task=profile');

$menuLinkTime4h  = JRoute::_( 'index.php?option=com_discussions&view=recent&task=recent&time=4h');
$menuLinkTime8h  = JRoute::_( 'index.php?option=com_discussions&view=recent&task=recent&time=8h');
$menuLinkTime12h = JRoute::_( 'index.php?option=com_discussions&view=recent&task=recent&time=12h');
$menuLinkTime24h = JRoute::_( 'index.php?option=com_discussions&view=recent&task=recent&time=24h');



echo "<div class='cofiMainmenuRow'>";

    echo "<div class='cofiMainmenuItemFirst'>";
		$menuText = $app->getMenu()->getActive()->title;
        echo "<a href='$menuLinkHome'>" . $menuText . "</a>";
    echo "</div>";


    if ( !$user->guest) { // user is logged in
        echo "<div class='cofiMainmenuItem'>";
            echo "<a href='$menuLinkProfile'>" . JText::_( "COFI_PROFILE", true ) . "</a>";
        echo "</div>";
    }


    echo "<div class='cofiMainmenuItemRecentText'>";
        echo JText::_( "COFI_HISTORY", true );
        echo ":";
    echo "</div>";

    echo "<div class='cofiMainmenuItem'>";
        echo "<a href='$menuLinkTime4h'>" . JText::_( "COFI_TIME_4H", true ) . "</a>";
    echo "</div>";

    echo "<div class='cofiMainmenuItem'>";
        echo "<a href='$menuLinkTime8h'>" . JText::_( "COFI_TIME_8H", true ) . "</a>";
    echo "</div>";

    echo "<div class='cofiMainmenuItem'>";
        echo "<a href='$menuLinkTime12h'>" . JText::_( "COFI_TIME_12H", true ) . "</a>";
    echo "</div>";

    echo "<div class='cofiMainmenuItem'>";
        echo "<a href='$menuLinkTime24h'>" . JText::_( "COFI_TIME_24H", true ) . "</a>";
    echo "</div>";

echo "</div>";

echo "<br style='clear:left;'>";

