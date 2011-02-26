<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	mod_discussions_recentx
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */

defined('_JEXEC') or die('Restricted access');
?>

<style type="text/css">

.cofiRecentRow {
	width: 100%;
	border-bottom: 1px dotted #AAAAAA;
	padding-top: 5px;
	padding-bottom: 5px;
}

.cofiRecentIcon {
	float: left;
	width: 20px;
}

/* shows # of replies in this thread */
.cofiRecentReplies {
	float: left;
	width: 20px;
	margin: 1px 0px 0px 0px;
	padding: 5px 5px 5px 5px;
	font-size: 14px;
	color: #777777;
	background: #F5F5F5;
	text-align: center;
	border: 1px solid #AAAAAA; 
}

.cofiRecentText {
	float: left;
	margin-left: 7px;
	margin-right: 5px;
	font-size: 11px;
	color: #777777;
}


.cofiRecentSubjectText {
	color: #777777;
}

.cofiRecentDateForumText {
	color: #777777;
	font-size: 9px;
}

.cofiRecentDateForumText a {
	color: #777777;
	font-size: 9px;
}



.cofiRecentHistory {
    margin-top: 10px;
    margin-bottom: 0px;
    color: #777777;
    font-size: 11px;    
}


.cofiRecentPoweredByText {
	margin-top: 10px;
	margin-bottom: 0px;
	color: #777777;
	font-size: 9px;
}

.cofiRecentHeaderText {
	margin-top: 0px;
	margin-bottom: 0px;
	padding-bottom: 5px;
	color: #777777;
	font-size: 9px;
	border-bottom: 1px dotted #AAAAAA;	
}

</style>




<?php
$_number 			= $params->get( 'number', 5 );
$_length 			= $params->get( 'length', 25 );
$_show_poweredby 	= $params->get( 'show_poweredby', 1 );
$_more 				= $params->get( 'more', '>' );
$_replies 			= $params->get( 'replies', 'Replies' );
$_topic 			= $params->get( 'topic', 'Topic' );
$_show_history 	    = $params->get( 'show_history', 1 );
$_history 			= $params->get( 'history', 'History' );
$_hours 			= $params->get( 'hours', 'h' );


$db		  =& JFactory::getDBO();

$posts = null;

$query = 'SELECT m.id as msg_id, m.alias, m.cat_id, c.id, c.alias, c.name, m.thread, m.subject,' .
			' CASE WHEN CHAR_LENGTH(m.alias) THEN CONCAT_WS(\':\', m.thread, m.alias) ELSE m.thread END as mslug,' .
			' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as cslug,' .
			' DATE_FORMAT( m.last_entry_date, "%d.%m.%Y %k:%i") AS createdate' .
			' FROM #__discussions_messages m, #__discussions_categories c' .
			' WHERE m.parent_id=0 AND m.cat_id=c.id AND m.published=1 AND c.private = 0' .
			' ORDER BY m.last_entry_date DESC LIMIT ' . $_number;

$db->setQuery($query);
$posts = $db->loadObjectList();

if ($db->getErrorNum()) {
	JError::raiseWarning( 500, $db->stderr() );
}

echo "<div class='cofiRecentHeaderText'>";
	echo "<b>";
	echo $_replies;
	echo "&nbsp;";
	echo $_topic;
	echo "</b>";
echo "</div>";


if (count($posts)) {
	
    foreach ($posts as $post) {

		// get Discussions Itemid	
		$sqlitemid = "SELECT id FROM ".$db->nameQuote( '#__menu')." WHERE link LIKE '%com_discussions%' AND level = '1' AND published = '1'";
		$db->setQuery( $sqlitemid);
		$itemid = $db->loadResult();	

	
		// get # of replies for this thread	
		$sql = "SELECT count(*) FROM ".$db->nameQuote( '#__discussions_messages')." WHERE thread='".$post->msg_id."' AND parent_id != '0' AND published = '1'";
		$db->setQuery( $sql);
		$replies = $db->loadResult();	
	
	
		echo "<div class='cofiRecentRow'>";
	
			echo "<div class='cofiRecentReplies'>";
				echo $replies;
			echo "</div>";

			echo "<div class='cofiRecentText'>";
	
				echo "<div class='cofiRecentSubjectText'>";
				
                    $link = JRoute::_('index.php?option=com_discussions&view=thread&catid=' . $post->cslug . '&thread=' . $post->mslug . '&Itemid=' . $itemid);
					
					$longsubject = strip_tags( $post->subject);
					$i_longsubjectlength = strlen( $longsubject);
					$shortsubject = substr( strip_tags( $longsubject), 0, $_length);
														
	    			echo "<a href='".$link."' title=\"".$longsubject."\" >";
						echo $shortsubject;
						if ( $i_longsubjectlength > $_length) { // display 'more'
							echo $_more;
						}
					echo "</a>";
				echo "</div>";
	
				echo "<div class='cofiRecentDateForumText'>";
				
					echo $post->createdate;
					
					echo "&nbsp;&nbsp;";
				
					$forum_link = JRoute::_('index.php?option=com_discussions&view=category&catid=' . $post->cslug . '&Itemid=' . $itemid);
					echo "<a href='" . $forum_link . "' title=\"" . $post->name . "\" style='color: #777777;' >";
						echo $post->name;
					echo "</a>";
					
				echo "</div>";
				
			echo "</div>";
			echo "<br style='clear:left;'/>";
		
		echo "</div>";
				
	}
}

if ( $_show_history == 1) {
	echo "<div class='cofiRecentHistory'>";

        $_linkTime4h  = JRoute::_( 'index.php?option=com_discussions&view=recent&task=recent&time=4h&Itemid=' . $itemid);
        $_linkTime8h  = JRoute::_( 'index.php?option=com_discussions&view=recent&task=recent&time=8h&Itemid=' . $itemid);
        $_linkTime12h = JRoute::_( 'index.php?option=com_discussions&view=recent&task=recent&time=12h&Itemid=' . $itemid);
        $_linkTime24h = JRoute::_( 'index.php?option=com_discussions&view=recent&task=recent&time=24h&Itemid=' . $itemid);

        echo $_history . ":&nbsp;&nbsp;&nbsp;";
    
		echo "<a href='" . $_linkTime4h . "' title='4" . $_hours ."' >4" . $_hours . "</a>&nbsp;&nbsp;&nbsp;";
        echo "<a href='" . $_linkTime8h . "' title='8" . $_hours ."' >8" . $_hours . "</a>&nbsp;&nbsp;&nbsp;";
        echo "<a href='" . $_linkTime12h . "' title='12" . $_hours ."' >12" . $_hours . "</a>&nbsp;&nbsp;&nbsp;";
        echo "<a href='" . $_linkTime24h . "' title='24" . $_hours ."' >24" . $_hours . "</a>";
    echo "</div>";
}



if ( $_show_poweredby == 1) {
	echo "<div class='cofiRecentPoweredByText'>";
		echo "Powered by <a href='http://www.codingfish.com/products/discussions' target='_blank' title='Discussions' >Discussions</a>";
	echo "</div>";
}




