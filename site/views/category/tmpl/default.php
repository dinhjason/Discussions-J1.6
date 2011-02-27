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


require_once(JPATH_COMPONENT.DS.'classes/user.php'); 
require_once(JPATH_COMPONENT.DS.'classes/helper.php');

$app = JFactory::getApplication();

$user =& JFactory::getUser();
$logUser = new CofiUser( $user->id);

$CofiHelper = new CofiHelper();

// get parameters
$params = JComponentHelper::getParams('com_discussions');

// website root directory
$_root = JURI::root();

$document =& JFactory::getDocument(); 


// RSS feed stuff
$useRssFeeds = $params->get('useRssFeeds', 1);		

if ( $useRssFeeds == 1) {

	$_RssTitle         = JText::_( 'COFI_RSS_NEW_THREADS' );
	$_RssTitleCategory = JText::_( 'COFI_RSS_NEW_THREADS_IN' ) . " " . $this->categoryName ;

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


	if ( $_suffix == 0) { // no .html suffix		
		$linkCategory 	= JRoute::_( 'index.php?option=com_discussions&view=category&catid=' . $this->categorySlug . '&format=feed' );	
	}
	else {
		$linkCategory 	= JRoute::_( 'index.php?option=com_discussions&view=category&catid=' . $this->categorySlug )  . '?format=feed';	
	}
	$attribsCategory 	= array('type' => 'application/rss+xml', 'title' => $_RssTitleCategory);
	$document->addHeadLink( $linkCategory, 'alternate', 'rel', $attribsCategory);

}
// RSS feed stuff
?>



<div class="codingfish">


<!-- Javascript functions -->
<script type="text/javascript">

    function callURL(obj) {

        $catid 		= obj.options[obj.selectedIndex].value;
		var length 	= slugsarray.length;

		for(var k=0; k < slugsarray.length; k++) {
			
			// if selected index found jump to category
			if ( slugsarray[k][0] == $catid) {
         		location.href = slugsarray[k][1];
        	}

		}			

    }

</script>
<!-- Javascript functions -->



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
$htmlBoxCategoryTop = $params->get('htmlBoxCategoryTop', '');

if ( $htmlBoxCategoryTop != "") {
	echo "<div class='cofiHtmlBoxCategoryTop'>";
		echo $htmlBoxCategoryTop;
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



<!-- Category icon, name and description -->
<table width="100%" class="noborder" style="margin-bottom:10px;">
    <tr>

        <!-- category image -->
        <td width="50" class="noborder">
            <?php
			if ( $this->categoryImage == "") {  // show default category image
				echo "<img src='" . $_root . "components/com_discussions/assets/categories/default.png' style='border:0px;margin:5px;' />";
			}
			else {
				echo "<img src='" . $_root . "components/com_discussions/assets/categories/".$this->categoryImage."' style='border:0px;margin:5px;' />";
			}
            ?>
        </td>
        <!-- category image -->

        <!-- category name and description -->
        <td align="left" class="noborder">
            <?php
            echo "<h2 style='padding-left: 0px;'>";
                echo $this->categoryName;
            echo "</h2>";
            echo $this->categoryDescription;
            ?>
        </td>
        <!-- category name and description -->

        <!-- category quick select box -->
        <td align="left" class="noborder">
            <?php
            echo $CofiHelper->getQuickJumpSelectBox( $this->categoryId);
            ?>
        </td>
        <!-- category quick select box -->

    </tr>
</table>
<!-- Category icon, name and description -->



<?php
// Forum specific top banner

if ( $this->forumBannerTop != "") {

	echo "<table width='100%' border='0'  class='noborder' style='margin-top:10px;'>";
	
	    echo "<tr>";
	
		echo "<td width='100%' align='center' class='noborder'>";
				?>

				<script type='text/javascript'>

				<?php			
		        echo $this->forumBannerTop;
				?>

				</script>

				<?php
		echo "</td>";
	
	    echo "</tr>";
				
	echo "</table>";

}
			
// Forum specific top banner
?>



<!-- Post, Reply,... Links -->
<?php
if ( $user->guest) { // user is not logged in

	echo "<table width='100%' class='noborder' style='margin:20px 0px 20px 0px;'>";
    	echo "<tr>";
        	echo "<td width='100%' align='left' valign='middle' class='noborder'>";                	
        		$registerURL = "index.php?option=com_user&view=register";
        		$loginURL    = "index.php?option=com_user&view=login";
        	
            	echo JText::_( 'COFI_NO_PUBLIC_WRITE' );
            	
            	echo "<a href='" . JRoute::_( $loginURL) . "' >" . JText::_( 'COFI_NO_PUBLIC_WRITE_LOGIN' ) . "</a>";
            	echo JText::_( 'COFI_OR' );
            	echo "<a href='" . JRoute::_( $registerURL) . "' >" . JText::_( 'COFI_NO_PUBLIC_WRITE_REGISTER' ) . "</a>";
            echo "</td>";
        echo "</tr>";
    echo "</table>";
    
}
else { // user is logged in

	echo "<table width='50%' class='noborder' style='margin:20px 0px 20px 0px;'>";
    	echo "<tr>";       	

        	echo "<td width='16' align='center' valign='middle' class='noborder'>";
            	echo "<img src='" . $_root . "components/com_discussions/assets/threads/new.png' style='margin-left: 5px; margin-right: 5px; border:0px;' />";
        	echo "</td>";
        	echo "<td align='left' valign='middle' class='noborder'>";
            	$menuLinkNewTMP = "index.php?option=com_discussions&view=posting&task=new&catid=".$this->categorySlug;
            	$menuLinkNew = JRoute::_( $menuLinkNewTMP);
            	echo "<a href='".$menuLinkNew."'>" . JText::_( 'COFI_NEW_THREAD' ) . "</a>";
        	echo "</td>";        
        	
    	echo "</tr>";
	echo "</table>";
}
?>
<!-- Post, Reply,... Links -->




<!-- Breadcrumb -->
<?php
$showBreadcrumbRow = $params->get('breadcrumb', '0');		

if ( $showBreadcrumbRow == "1") {
	?>

	<table class="noborder" style="margin-top: 5px;">
	    <tr>
	        <td class="noborder">
	            <?php
	            $menuLinkHome     = JRoute::_( 'index.php?option=com_discussions');
				$menuText = $app->getMenu()->getActive()->title;	
	            echo "<a href='$menuLinkHome'>" . $menuText . "</a>";
	            ?>
	        </td>
	        <td class="noborder">
	            <?php
	            echo "&nbsp;&raquo;&nbsp;";
	            echo $this->categoryName;
	            ?>
	        </td>
	    </tr>
	</table>
	
	<?php
}
?>
<!-- Breadcrumb -->





<!-- Pagination Links -->
<table width="100%" class="noborder" style="margin-bottom:10px;">
    <tr>
        <td class="noborder">
            <?php
            echo $this->pagination->getPagesLinks();
            ?>
        </td>
        <td class="noborder">
            <?php
            echo $this->pagination->getPagesCounter();
            ?>
        </td>

    </tr>
</table>
<!-- Pagination Links -->



<table width="100%" border="0" cellspacing="0" cellpadding="0" class="noborder"> 

    <tr> 
		<td width="70px"  align="center" class="cofiTableHeader"><?php echo JText::_( 'COFI_REPLIES' ); ?></td>
		<td width="48px"  align="center" class="cofiTableHeader"><?php echo JText::_( 'COFI_TYPE' ); ?></td>
        <td               align="left"   class="cofiTableHeader"><?php echo JText::_( 'COFI_TOPIC' ); ?></td>
		<td width="200px" align="center" class="cofiTableHeader"><?php echo JText::_( 'COFI_LAST_POST' ); ?></td> 
		<td width="35px"  align="center" class="cofiTableHeader">&nbsp;</td>
    </tr> 



	<?php 
	$rowColor = 1;
	
	foreach ( $this->threads as $thread ) : ?>

    	<tr> 

			<td align="center" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexTableRowReplies">	
				<?php
				echo "<span class='cofiIndexTableRowRepliesCounter'>"; 
					echo $thread->counter_replies;
				echo "</span>";
				?>
			</td> 


			<td align="center" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexTableRowIcon">	
				<?php
				
				switch ( $thread->type) {  // check the type of this thread (discussion, question...)
				
					case 1: { // discussion
						echo "<img src='" . $_root . "components/com_discussions/assets/system/discussion.png' alt='Discussion' title='Discussion' />";					
						break;
					}
					case 2: { // question
						echo "<img src='" . $_root . "components/com_discussions/assets/system/question.png' alt='Question' title='Question' />";
						break;
					}
					case 3: { // important
						echo "<img src='" . $_root . "components/com_discussions/assets/system/important.png' alt='Important' title='Important' />";
						break;
					}

					default: { // type unknown? Then it's a discussion ;-)
						echo "<img src='" . $_root . "components/com_discussions/assets/system/discussion.png' alt='Discussion' title='Discussion' />";					
						break;
					}					
				
				}
								
				?>
			</td> 


			<td align="left" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexTableRowTopic">

                <?php
                
                	$_hoverSubject = $thread->subject;
                	$_hoverSubject = str_replace( '\'', '"', $_hoverSubject);
                
                                
                    $threadLink = JRoute::_('index.php?option=com_discussions&view=thread&catid='.$this->categorySlug.'&thread='.$thread->slug );

                    echo "<a href='$threadLink' title='".$_hoverSubject."'>".$thread->subject."</a>";
					
					if ( $thread->locked == 1) { // show lock symbol
						echo "<img src='" . $_root . "components/com_discussions/assets/threads/lock.png' style='margin-left: 5px; border:0px;' />";
					}
					
					echo "<div class='cofiIndexTableRowTopicSubtitle'>";
							
					echo JText::_( 'COFI_POSTED' ) . " " . $thread->date . " " . JText::_( 'COFI_BY' ) . " ";
					
					echo "<b>";
					                  
                  	echo $CofiHelper->getUsernameById( $thread->user_id);
                                      
					echo "</b>";
					
					// echo " Views: ".$thread->hits;
					
					echo "</div>";
                ?>

			</td> 

									
			<td width="200" align="center" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexTableRowLastPost">

                <table width="100%" cellspacing="0" cellpadding="0" border="0" class="noborder">
                    <tr>
                        <td width="32" align="left" class="noborder">
                            <?php
                                                        
                            $CofiUser = new CofiUser( $thread->last_entry_user_id);

					    	echo "<div class='cofiCategoryAvatarBox'>";

                                // $lastEntryUserUsername = $CofiHelper->getUsernameById( $thread->last_entry_user_id);
                                $lastEntryUserUsername = $CofiUser->getUsername();

                                if ( $CofiUser->getAvatar() == "") { // display default avatar
                                    echo "<img src='" . $_root . "components/com_discussions/assets/users/user.png' class='cofiCategoryDefaultAvatar' alt='$lastEntryUserUsername' title='$lastEntryUserUsername' />";
                                }
                                else { // display uploaded avatar
                                    echo "<img src='" . $_root . "images/discussions/users/".$thread->last_entry_user_id."/small/".$CofiUser->getAvatar()."' class='cofiCategoryAvatar' alt='$lastEntryUserUsername' title='$lastEntryUserUsername' />";
                                }
                                                        
                            echo "</div>";
                            
                            
                            ?>
                        </td>
                        <td align="left" valign="center" class="noborder" style="padding-left: 5px;">
                            <?php
                            echo $thread->last_entry_date;
                            ?>
                            <br />
                            <?php echo JText::_( 'COFI_BY' ); ?>:&nbsp;
                            <?php                                                        
                            echo $lastEntryUserUsername;
                            ?>
                        </td>
                        <td width="20" align="right" valign="center" class="noborder">
                            <?php
                            
							// calculate jump point to last entry
							$_threadListLength 	= $params->get('threadListLength', '20');							
							//$_numberOfPosts 	= $CofiHelper->getNumberOfPostsByThreadId( $thread->id);
							$_numberOfPosts 	= $thread->counter_replies + 1;							
							$_lastPostId 		= $CofiHelper->getLastPostIdByThreadId( $thread->id);
									
							if ( ( $_numberOfPosts % $_threadListLength) == 0) {
								$_start = ( $_numberOfPosts / $_threadListLength) - 1;
							}
							else {		
								$_start = floor( $_numberOfPosts / $_threadListLength);
							}
					
							$_start = $_start * $_threadListLength;					
							
							if ( $_start == 0) {  // first page = no limitstart
								$_lastEntryJumpPoint = "#p" . $_lastPostId;		
							}
							else {
								$_lastEntryJumpPoint = "&limitstart=" . $_start ."#p" . $_lastPostId;
							}
                                                        
							$menuLinkLastTMP = "index.php?option=com_discussions&view=thread&catid=" . $this->categorySlug . "&thread=" . $thread->slug;
							$menuLinkLastTMP .= $_lastEntryJumpPoint;							
            				$menuLinkLast = JRoute::_( $menuLinkLastTMP);
            				
            				echo "<a href='" . $menuLinkLast . "' title='" . JText::_( 'COFI_GOTO_LAST_ENTRY' ) . ": " . $thread->subject .  "' rel='nofollow' >";
            				echo "<img src='" . $_root . "components/com_discussions/assets/system/lastentry.png' style='border:0px;' />";
            				echo "</a>";                                                                                    
                            ?>
                        </td>
                        
                    </tr>
                </table>

			</td> 


			<!-- sticky -->		
			<td width="32" align="center" class="cofiIndexTableRow<?php echo $rowColor; ?>  cofiIndexTableRowSticky">
					<?php
					if ( $thread->sticky == 0) {  // do not show icon
						echo "&nbsp;";
					}
					else { // show sticky icon
						echo "<img src='" . $_root . "components/com_discussions/assets/threads/sticky.png' style='border:0px;margin:5px;' alt='Sticky' title='Sticky' />";
					}
					?>
			</td> 
			<!-- sticky -->		
		
		
    	</tr> 




		<?php 
		// toggle row color
		if ( $rowColor == 1) {
			$rowColor = 2;
		}
		else {
			$rowColor = 1;
		}

	endforeach; 
	?>

</table>



<!-- Pagination Links -->
<table width="100%" class="noborder" style="margin-top:10px;">
    <tr> 
        <td class="noborder">
            <?php
            echo $this->pagination->getPagesLinks();
            ?>
        </td>
        <td class="noborder">
            <?php
            echo $this->pagination->getPagesCounter();
            ?>
        </td>

    </tr>
</table>
<!-- Pagination Links -->



<!-- Breadcrumb -->
<?php
if ( $showBreadcrumbRow == "1") {
	?>

	<table class="noborder" style="margin-top: 5px;">
	    <tr>
	        <td class="noborder">
	            <?php
	            echo "<a href='$menuLinkHome'>" . $menuText . "</a>";
	            ?>
	        </td>
	        <td class="noborder">
	            <?php
	            echo "&nbsp;&raquo;&nbsp;";
	            echo $this->categoryName;
	            ?>
	        </td>
	    </tr>
	</table>
	
	<?php
}
?>
<!-- Breadcrumb -->



<?php
// Forum specific bottom banner

if ( $this->forumBannerBottom != "") {

	echo "<table width='100%' border='0' class='noborder' style='margin-top:10px;'>";
	
	    echo "<tr>";
	
	    	echo "<td width='100%' align='center' class='noborder'>";
					?>
		
					<script type='text/javascript'>
		
					<?php			
		            echo $this->forumBannerBottom;
					?>
		
					</script>
		
					<?php			
	    	echo "</td>";
	
	    echo "</tr>";
				
	echo "</table>";
	
}

// Forum specific bottom banner
?>



<?php
include( 'components/com_discussions/includes/share.php');
?>



<!-- RSS feed icon -->
<?php
$showRssFeedIcon = $params->get('showRssFeedIcon', 1);		

if ( $useRssFeeds == 1 && $showRssFeedIcon == 1) {

	echo "<div style='margin: 30px 0px 10px 0px;'>";

		echo "<img src='" . $_root . "/components/com_discussions/assets/icons/rss_16.png' style='margin: 0px 10px 0px 5px;' align='top' />";
		echo "<a href='" . $link .  "'>" . $_RssTitle . "</a>";				

	echo "</div>";

	echo "<div style='margin: 0px 0px 20px 0px;'>";		
		echo "<img src='" . $_root . "/components/com_discussions/assets/icons/rss_16.png' style='margin: 0px 10px 0px 5px;' align='top' />";		
		echo "<a href='" . $linkCategory .  "'>" . $_RssTitleCategory . "</a>";						
	echo "</div>";

}
?>
<!-- RSS feed icon -->



<!-- HTML Box Bottom -->
<?php
$htmlBoxCategoryBottom = $params->get('htmlBoxCategoryBottom', '');		

if ( $htmlBoxCategoryBottom != "") {
	echo "<div class='cofiHtmlBoxCategoryBottom'>";
		echo $htmlBoxCategoryBottom;
	echo "</div>";
}
?>
<!-- HTML Box Bottom -->


<?php
include( 'components/com_discussions/includes/footer.php');
?>

</div>
