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

JHTML::_('stylesheet', 'discussions.css', 'components/com_discussions/assets/');

//require_once(JPATH_COMPONENT.DS.'helpers'.DS.'discussions.php');
require_once(JPATH_COMPONENT.DS.'classes/user.php'); 
$user =& JFactory::getUser();
$logUser = new CofiUser( $user->id);
?>

<div class="codingfish">

<?php

$document =& JFactory::getDocument(); 

// set page description
if ( JText::_( 'COFI_INDEX_META_DESCRIPTION') == "") {
	$pageDescription = "";
	// to joomla 1.6
//	$pageDescriptionSuffix = $mainframe->getCfg('sitename');
	$pageDescriptionSuffix = "";
}


// get parameters
$params = JComponentHelper::getParams('com_discussions');

// website root directory
$_root = JURI::root();


// RSS feed stuff
$useRssFeeds = $params->get('useRssFeeds', 1);		

if ( $useRssFeeds == 1) {

	$_RssTitle = JText::_( 'COFI_RSS_NEW_THREADS' );

	$config =& JFactory::getConfig();
	$_suffix = $config->getValue( 'config.sef_suffix' );

	if ( $_suffix == 0) { // no .html suffix
		$link 		= JRoute::_( 'index.php?option=com_discussions&format=feed');
	}
	else {
		$link 		= JRoute::_( 'index.php?option=com_discussions') . '?format=feed';
	}
	$attribs 	= array('type' => 'application/rss+xml', 'title' => $_RssTitle);

	$document->addHeadLink( $link, 'alternate', 'rel', $attribs);

}
// RSS feed stuff
?>



<?php 
if ( $this->params->def( 'show_page_title', 1 ) ) : 
	?>
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
	<?php 
endif; 
?>



<!-- HTML Box Top -->
<?php
$htmlBoxIndexTop = $params->get('htmlBoxIndexTop', '');

if ( $htmlBoxIndexTop != "") {
	echo "<div class='cofiHtmlBoxIndexTop'>";
		echo $htmlBoxIndexTop;
	echo "</div>";
}
?>
<!-- HTML Box Top -->



<?php
include( 'components/com_discussions/includes/topmenu.php');
?>


<!-- show moderator how many posts wait for approval -->
<?php
if ( $logUser->isModerator() == 1) {

	if ( $logUser->isApprovalNotification() == 1) {

		$countposts = CofiHelper::getPostsWFM();
			
		if ( $countposts > 0) { // here is something to do for the moderator
		?>
			<center>
				<div class="cofiPostsWaitingForApproval">
			
					<?php			
				
	            	$approveLink = JRoute::_('index.php?option=com_discussions&view=moderation&task=approve' );                        
						
	            	echo "<a href='$approveLink' title='" . JText::_( 'COFI_APPROVE_NEW_POSTS' ) . "'>";
						echo "<b>";
						echo $countposts;
						echo "</b>"; 
					
						if ( $countposts == 1) {
							echo " " . JText::_( 'COFI_POST_IS_WAITING_FOR_APPROVAL' );
						}
						else {
							echo " " . JText::_( 'COFI_POSTS_ARE_WAITING_FOR_APPROVAL' );
						}				
					echo "</a>";
					
					?>
					
				</div>
			</center>
			<?php
		}
	
	}
}
?>
<!-- show moderator how many posts wait for approval -->



<table width="100%" border="0" cellspacing="0" cellpadding="0"> 

	<?php 

	$rowColor = 1;
	
	foreach ( $this->categories as $category ) : ?>

    <tr> 
		
			<?php

			if ( $category->show_image == 0) {  // don't show category image
								
				if ( $category->parent_id == 0) {  // container			
					?>
					<td align="center" class="cofiContainer">
					<?php				
				}
				else {
					?>
					<td align="center">
					<?php
				}	
				
				echo "&nbsp;";
				?>
				
				</td> 
				<?php
			}
			else {
				?>
				<td align="center" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexViewTableRowCategoryImage">
					<?php
					if ( $category->image == "") {  // show default category image					
						echo "<img src='" . $_root . "components/com_discussions/assets/categories/default.png' style='border:0px;margin:5px;' />";
					}
					else {
						echo "<img src='" . $_root . "components/com_discussions/assets/categories/".$category->image."' style='border:0px;margin:5px;' />";
					}
					?>
				</td> 
				<?php
			}
						
			?>
			
		
        
			<?php
			if ( $category->parent_id == 0) {  // container			
				?>
				<td class="cofiContainer">	
					<br />
					<br />
					<h2 style="padding: 0px; margin-bottom: 5px;" >
						<?php echo $category->name; ?>
					</h2>
					<?php
					echo $category->description;
					?>
					<br />
					<br />
				</td> 
				<?php
			}
			else {
				if ( JText::_( 'COFI_INDEX_META_DESCRIPTION') == "") {
					$pageDescription .= $category->name . ", ";
				}
				?>
				<td class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexViewTableRowCategoryName"> 

                    <b>
                    <?php                        
                        $catLink = JRoute::_('index.php?option=com_discussions&view=category&catid=' . $this->escape( $category->slug) );                                                
                        echo "<a href='$catLink' title='$category->name'>".$category->name."</a>";
                    ?>
                    </b>

					<br />
					<?php
					echo $category->description; 
					?>
					<br />
				</td> 
				<?php
			}
			?>								
		
		
		<?php
		if ( $category->parent_id == 0) {  // don't show threads, posts and last post
			?>
			<td colspan="3" class="cofiContainer">&nbsp;</td> 
			<?php
		}
		else {
			?>
			
			<td align="center" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexViewTableRowThreads">
				<?php echo $category->counter_threads; ?>
			</td> 
			
			<td align="center" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexViewTableRowPosts">
				<?php echo $category->counter_posts; ?>
			</td> 
			
			<td align="center" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexViewTableRowLastEntry">
				<?php
				
				echo $category->last_entry_date."";
				echo "<br />";
				echo JText::_( 'COFI_BY' ) . " ".$category->username ;
				?>
			</td> 
			<?php
		}
		?>
		
    </tr> 


	<?php
	if ( $category->parent_id == 0) {  // don't show threads, posts and last post
		?>
    	<tr> 
			<td width="53px" align="center" class="cofiTableHeader">
				&nbsp;
			</td> 
        	<td align="left" class="cofiTableHeader"><?php echo JText::_( 'COFI_FORUM' ); ?></td> 
			<td width="70px" align="center" class="cofiTableHeader"><?php echo JText::_( 'COFI_THREADS' ); ?></td> 
			<td width="70px" align="center" class="cofiTableHeader"><?php echo JText::_( 'COFI_POSTS' ); ?></td> 
			<td width="150px" align="center" class="cofiTableHeader"><?php echo JText::_( 'COFI_LAST_ENTRY' ); ?></td> 
    	</tr> 
		<?php
	}
	?>


	<?php 
	// toggle row color
	if ( $rowColor == 1) {
		$rowColor = 2;
	}
	else {
		$rowColor = 1;
	}

	endforeach; 
	
	// set page description
	if ( JText::_( 'COFI_INDEX_META_DESCRIPTION') == "") {
		$document->setDescription( $pageDescription . $pageDescriptionSuffix); 	
	}
	?>



</table>



<?php
include( 'components/com_discussions/includes/share.php');
?>



<!-- RSS feed icon -->
<?php
$showRssFeedIcon = $params->get('showRssFeedIcon', 1);		

if ( $useRssFeeds == 1 && $showRssFeedIcon == 1) {

	echo "<div style='margin: 40px 0px 30px 0px;'>";

		echo "<img src='" . $_root . "/components/com_discussions/assets/icons/rss_16.png' style='margin: 0px 10px 0px 5px;' align='top' />";

		echo "<a href='" . $link .  "'>" . $_RssTitle . "</a>";				

	echo "</div>";

}
?>
<!-- RSS feed icon -->



<!-- HTML Box Bottom -->
<?php
$htmlBoxIndexBottom = $params->get('htmlBoxIndexBottom', '');		

if ( $htmlBoxIndexBottom != "") {
	echo "<div class='cofiHtmlBoxIndexBottom'>";
		echo $htmlBoxIndexBottom;
	echo "</div>";
}
?>
<!-- HTML Box Bottom -->


<?php
include( 'components/com_discussions/includes/footer.php');
?>

</div>


