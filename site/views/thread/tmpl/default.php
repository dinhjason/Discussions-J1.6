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

require_once( JPATH_COMPONENT.DS.'classes/user.php');
require_once( JPATH_COMPONENT.DS.'classes/helper.php');
?>

<div class="codingfish">

<?php

echo "<script type='text/javascript'>";
	echo "function confirmdelete() { ";
 		echo "return confirm('" . JText::_( 'COFI_CONFIRM_DELETE' ) . "');";
	echo "}"; 		
echo "</script>";




$user =& JFactory::getUser();
$logUser = new CofiUser( $user->id);
$CofiHelper = new CofiHelper();


// set page title and description
$document =& JFactory::getDocument(); 

$title = $document->getTitle();


// $siteName = $mainframe->getCfg('sitename');

$title = $this->subject . " - " . $this->categoryName;

//$title = $title . " | " . $siteName;

$document->setTitle( $title); 

//$document->setDescription( $this->subject . " " . $this->categoryName); 


$_metaDescription = trim( ereg_replace( "\n", " ", $this->metaDescription));

$_metaDescription = ereg_replace( "\r", " ", $_metaDescription);

$_metaDescription = ereg_replace( "\"", " ", $_metaDescription);
$_metaDescription = ereg_replace( "<", " ", $_metaDescription);
$_metaDescription = ereg_replace( ">", " ", $_metaDescription);
$_metaDescription = ereg_replace( "/", " ", $_metaDescription);

$_metaDescription = ereg_replace( ' +', ' ', $_metaDescription);

$document->setDescription( substr ( $_metaDescription, 0, 160)); 


if ( $this->metaKeywords == "") {
	// $document->setMetaData( "keywords", "");
}
else { // use the meta keywords configured for this forum
	$document->setMetaData( "keywords", $this->metaKeywords);
}




// get parameters
$params = JComponentHelper::getParams('com_discussions');


$_imagesDisplayMode 	= $params->get( 'imagesDisplayMode', 0); // 0 Browser, 1 Slimbox, 2 RokBox
$_includeMootoolsJS 	= $params->get( 'includeMootoolsJS', 0); // 0 no, 1 yes
$_includeSlimboxJS  	= $params->get( 'includeSlimboxJS', 0);  // 0 no, 1 yes
$_usePrimezilla 		= $params->get( 'usePrimezilla', 0);  // 0 no, 1 yes
$_callPrimezillaMode 	= $params->get( 'callPrimezillaMode', 0); // 0 username, 1 userid

// Flickr
$_useFlickr 	      	= $params->get( 'useFlickr', 0);  // 0 no, 1 yes
$_flickr_display_mode 	= $params->get( 'flickr_display_mode', 0); // 0 Browser, 1 Slimbox, 2 RokBox, 3 YOOeffects
$_flickr_cache_mode   	= $params->get( 'flickr_cache_mode', 0 ); // 0 = off, 1 = on
$_flickr_cache_time   	= $params->get( 'flickr_cache_time', 7200 ); // default 7200 seconds = 2 hours

// YouTube
$_useYouTube 	      	= $params->get( 'useYouTube', '0');  // 0 no, 1 yes
$_youtube_video_width 	= $params->get( 'youtube_video_width', '640');  // default 640 pixel
$_youtube_video_height	= $params->get( 'youtube_video_height', '385');  // default 385 pixel


if ( $_useFlickr == 1) {

    $_flickr_apikey     = $params->get( 'flickr_apikey', '');
    require_once( JPATH_COMPONENT.DS.'includes/phpflickr/phpFlickr.php');

    $f = new phpFlickr( $_flickr_apikey);

    if ( $_flickr_cache_mode == 1) {

        $config = new JConfig();

        $_host 	    = $config->host;
        $_db 		= $config->db;
        $_dbprefix  = $config->dbprefix;
        $_user 	    = $config->user;
        $_password  = $config->password;

        $_flickr_connect = "mysql://" . $_user . ":" . $_password . "@" . $_host . "/" . $_db;

        $_flickr_cache_table = $_dbprefix . "discussions_flickr_cache";

        $f->enableCache(
            "db",
            $_flickr_connect,
            $_flickr_cache_time,
            $_flickr_cache_table
        );

    }

}



if ( $_imagesDisplayMode == 1) { // Slimbox
	$assets = JURI::root() . "components/com_discussions/assets";
	$document->addStyleSheet( $assets.'/css/slimbox.css');
}


// website root directory
$_root = JURI::root();
?>


<!-- Javascript functions -->

<?php
if ( $_includeMootoolsJS == 1) { // include Mootools JS
	echo "<script type=\"text/javascript\" src=\"" . $assets . "/js/mootools.js\"></script>";
}	

if ( $_includeSlimboxJS == 1) { // include Slimbox JS
	echo "<script type=\"text/javascript\" src=\"" . $assets . "/js/slimbox.js\"></script>";
}	
?>

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
$htmlBoxThreadTop = $params->get('htmlBoxThreadTop', '');

if ( $htmlBoxThreadTop != "") {
	echo "<div class='cofiHtmlBoxThreadTop'>";
		echo $htmlBoxThreadTop;
	echo "</div>";
}
?>
<!-- HTML Box Top -->



<?php
include( 'components/com_discussions/includes/topmenu.php');
?>



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

	echo "<table width='100%' border='0' class='noborder' style='margin-top:10px;'>";
	
	    echo "<tr>";
	
	    	echo "<td width='100%' align='center' class='noborder'>";
	    			// todo make configurable !!!
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

	echo "<table width='100%' class='noborder' style='margin:20px 0px 20px 0px;' border='0' >";
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
                
        
	echo "<table class='noborder' style='margin:20px 0px 20px 0px;'>";

    	echo "<tr>";
        
        	echo "<td width='16' align='center' valign='middle' class='noborder' style='padding-left: 0px;'>";
            	echo "<img src='" . $_root . "components/com_discussions/assets/system/lastentry.png' style='margin-left: 5px; margin-right: 5px; border:0px;' />";
        	echo "</td>";
        	
        	echo "<td align='left' valign='middle' class='noborder'>";
				$menuLinkLastTMP = "index.php?option=com_discussions&view=thread&catid=" . $this->categorySlug . "&thread=" . $this->threadSlug;
				$menuLinkLastTMP .= $this->lastEntryJumpPoint;
            	$menuLinkLast = JRoute::_( $menuLinkLastTMP);
            	echo "<a href='".$menuLinkLast."'>" . JText::_( 'COFI_GOTO_LAST_ENTRY' ) . "</a>";
        	echo "</td>";        


    	echo "</tr>";
        
        
        
    echo "</table>";
    
}
else { // user is logged in

	echo "<table class='noborder' style='margin:20px 0px 20px 0px;'>";
	
    	echo "<tr>";       	
    	
    		if ( $this->lockedStatus == 0 || $logUser->isModerator()) { // thread is not locked or user is moderator
        		echo "<td width='16' align='center' valign='middle' class='noborder' style='padding-left: 0px;' >";
            		echo "<img src='" . $_root . "components/com_discussions/assets/threads/reply.png' style='margin-left: 15px; margin-right: 5px; border:0px;' />";
        		echo "</td>";
        		echo "<td align='left' valign='middle' class='noborder'>";
            		$menuLinkReplyTMP = "index.php?option=com_discussions&view=posting&task=reply&catid=".$this->categorySlug."&thread=".$this->thread."&parent=".$this->threadId;
            		$menuLinkReply = JRoute::_( $menuLinkReplyTMP);
            		echo "<a href='".$menuLinkReply."'>" . JText::_( 'COFI_REPLY1' ) . "</a>";
        		echo "</td>";
			}

        	echo "<td width='16' align='center' valign='middle' class='noborder' style='padding-left: 20px;'>";
            	echo "<img src='" . $_root . "components/com_discussions/assets/threads/new.png' style='margin-left: 5px; margin-right: 5px; border:0px;' />";
        	echo "</td>";
        	
        	echo "<td align='left' valign='middle' class='noborder'>";
            	$menuLinkNewTMP = "index.php?option=com_discussions&view=posting&task=new&catid=" . $this->categorySlug;
            	$menuLinkNew = JRoute::_( $menuLinkNewTMP);
            	echo "<a href='".$menuLinkNew."'>" . JText::_( 'COFI_NEW_THREAD' ) . "</a>";
        	echo "</td>";                	
        	
        	echo "<td width='16' align='center' valign='middle' class='noborder' style='padding-left: 20px;'>";
            	echo "<img src='" . $_root . "components/com_discussions/assets/system/lastentry.png' style='margin-left: 5px; margin-right: 5px; border:0px;' />";
        	echo "</td>";
        	
        	echo "<td align='left' valign='middle' class='noborder'>";
				$menuLinkLastTMP = "index.php?option=com_discussions&view=thread&catid=" . $this->categorySlug . "&thread=" . $this->threadSlug;
				$menuLinkLastTMP .= $this->lastEntryJumpPoint;
            	$menuLinkLast = JRoute::_( $menuLinkLastTMP);
            	echo "<a href='".$menuLinkLast."'>" . JText::_( 'COFI_GOTO_LAST_ENTRY' ) . "</a>";
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
	            $menuText = JSite::getMenu()->getActive()->name;
	            echo "<a href='$menuLinkHome'>" . $menuText . "</a>";
	            ?>
	        </td>
	        <td class="noborder">
	            <?php
	            $menuLinkCategoryTMP = "index.php?option=com_discussions&view=category&catid=".$this->categorySlug;
	            $menuLinkCategory = JRoute::_( $menuLinkCategoryTMP);
	            echo "&nbsp;&raquo;&nbsp;";
	            echo "<a href='$menuLinkCategory'>".$this->categoryName."</a>";
	            ?>
	        </td>
	        <td class="noborder">
	            <?php
	            echo "&nbsp;&raquo;&nbsp;";
	            echo $this->subject;
	            ?>
	        </td>
	    </tr>
	</table>

	<?php
}
?>
<!-- Breadcrumb -->




<!-- Pagination Links -->
<table width="100%" class="noborder" style="margin-bottom: 5px;">
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



<table width="100%" border="0" cellspacing="0" cellpadding="5" class="noborder">

	<?php
	$rowColor = 1;
	$counter  = 1;

	foreach ( $this->postings as $posting ) : ?>

    	<tr>

			<td width="100" align="center" valign="top" class="cofiThreadTableRow<?php echo $rowColor; ?> cofiThreadBorder1" >
                <?php

                // show avatar and username
                echo "<div class='cofiAvatarBox'>";
                $CofiUser = new CofiUser( $posting->user_id);

                // get $post->username;
				// $opUserUsername = $CofiHelper->getUsernameById( $posting->user_id);
                $opUserUsername = $CofiUser->getUsername();

                
                if ( $CofiUser->getAvatar() == "") { // display default avatar
                    echo "<img src='" . $_root . "components/com_discussions/assets/users/user.png' class='cofiAvatar' alt='$opUserUsername' title='$opUserUsername' />";
                }
                else { // display uploaded avatar
                    echo "<img src='" . $_root . "images/discussions/users/".$posting->user_id."/large/".$CofiUser->getAvatar()."' class='cofiAvatar' alt='$opUserUsername' title='$opUserUsername' />";
                }
                echo "</div>";



                // display social media buttons
				$twitter  = $CofiUser->getTwitter();
				$facebook = $CofiUser->getFacebook();
				$flickr   = $CofiUser->getFlickr();
				$youtube  = $CofiUser->getYoutube();
				
				if( $twitter != "" || $facebook != "" || $flickr != "" || $youtube != "" || $_usePrimezilla == "1") {
				
                	echo "<div class='cofiSocialMediaBox'>";

	                if ( $twitter != "") {
	                	echo "<a href='http://" . $twitter . "' title='" . $opUserUsername . " on Twitter' target='_blank' >";
						echo "<img src='" . $_root . "components/com_discussions/assets/icons/twitter_16.png' style='margin: 10px 5px 10px 5px;' />";  
						echo "</a>";              
	                }
	
	                if ( $facebook != "") {
	                	echo "<a href='http://" . $facebook . "' title='" . $opUserUsername . " on Facebook' target='_blank' >";
						echo "<img src='" . $_root . "components/com_discussions/assets/icons/facebook_16.png' style='margin: 10px 5px 10px 5px;' />";
						echo "</a>";              
	                }
	
	                if ( $flickr != "") {
	                	echo "<a href='http://" . $flickr . "' title='" . $opUserUsername . " on Flickr' target='_blank' >";
						echo "<img src='" . $_root . "components/com_discussions/assets/icons/flickr_16.png' style='margin: 10px 5px 10px 5px;' />";
						echo "</a>";              
	                }
	
	                if ( $youtube != "") {
	                	echo "<a href='http://" . $youtube . "' title='" . $opUserUsername . " on YouTube' target='_blank' >";
						echo "<img src='" . $_root . "components/com_discussions/assets/icons/youtube_16.png' style='margin: 10px 5px 10px 5px;' />";
						echo "</a>";              
	                }


					if ( $user->guest) { // do nothing
						echo "<br />";
					}
					else {
		                if ( $_usePrimezilla == "1" && $opUserUsername != "-") {
		                
	
		                    $_username = strtolower( $opUserUsername);
		                    
		                    
							if ( $this->pzitemid == 0) { // got no itemid
							
								if ( $_callPrimezillaMode == 0) { // username
									$linkPrimezilla  = JRoute::_( 'index.php?option=com_primezilla&view=message&task=new&username=' . $_username);
								}
								else { // userid
									$linkPrimezilla  = JRoute::_( 'index.php?option=com_primezilla&view=message&task=new&userid=' . $posting->user_id);
								}
								
							}
							else {
							
								if ( $_callPrimezillaMode == 0) { // username
									$linkPrimezilla  = JRoute::_( 'index.php?option=com_primezilla&view=message&task=new&username=' . $_username . '&Itemid=' . $this->pzitemid);
								}
								else { // userid
									$linkPrimezilla  = JRoute::_( 'index.php?option=com_primezilla&view=message&task=new&userid=' . $posting->user_id . '&Itemid=' . $this->pzitemid);
								}
								
							}		
     				            				            	
	            			echo "<a href='" . $linkPrimezilla . "' title='" . JText::_( 'COFI_MESSAGE_TO' ) . " " . $opUserUsername . "' >";
							echo "<img src='" . $_root . "components/com_discussions/assets/icons/pn_16.png' style='margin: 10px 5px 10px 5px;' />";
							echo "</a>";              										
	
		                }
	                }


                	echo "</div>";

				}
				else {
                	echo "<br />";                
				}




                // display username
                echo "<b>";
                	echo $opUserUsername;
                echo "</b>";
                echo "<br />";
                


				echo "<div class='cofiAvatarColumnPosts'>";
					$_posts = $CofiUser->getPosts();
					
					if ( $_posts == 1) {
                		echo $_posts . " " . JText::_( 'COFI_POST' );
					}
					else {
                		echo $_posts . " " . JText::_( 'COFI_POSTSCOUNTER' );
					}					
                echo "</div>";



				// online status
				echo "<div class='cofiAvatarColumnOnlineStatus'>";
				
					if ( $user->guest) { // user is not logged in

						echo "<div class='cofiAvatarColumnOnlineStatusOffline'>";
							echo JText::_( 'COFI_OFFLINE_GUEST' );
						echo "</div>";
					
					}
					else { // user is logged in
							
						if ( $CofiUser->getShowOnlineStatus() && $CofiHelper->isUserOnlineById( $posting->user_id)) {
							echo "<div class='cofiAvatarColumnOnlineStatusOnline'>";
								echo JText::_( 'COFI_ONLINE' );
							echo "</div>";
						}
						else {
							echo "<div class='cofiAvatarColumnOnlineStatusOffline'>";
								echo JText::_( 'COFI_OFFLINE' );
							echo "</div>";
						}
					
					}
				
				echo "</div>";



				// location
				echo "<div class='cofiAvatarColumnLocation'>";
				
				echo JText::_( 'COFI_LOCATION' ) . ":";
                echo "<br />";


                $city    = $CofiUser->getCity();
                $country = $CofiUser->getCountry();

                if ( $city != "" || $country != "") {
                
                	if ( $city != "") {	
						echo $city;
                		if ( $country != "") {	
                			echo "<br />";
                		}
					}
                	if ( $country != "") {	
						echo $country;
					}
					
				}
				else { // nothing set
					echo JText::_( 'COFI_NO_LOCATION' );
				}
				echo "</div>";



                // rank
				echo "<div class='cofiAvatarColumnTitel'>";
                if ( $CofiUser->getTitle() != "") { // display title

                    echo "<i>";
                    echo $CofiUser->getTitle();
                    echo "</i>";
                    echo "<br />";

                    switch ($CofiUser->getTitle()) {
                    
                        case "Community Manager":
                        case "Administrator": {
                            echo "<img src='" . $_root . "components/com_discussions/assets/system/administrator.png' style='width:16px;border:0px;margin:5px;' />";
                            break;
                        }
                        case "Moderator": {
                            echo "<img src='" . $_root . "components/com_discussions/assets/system/moderator.png' style='width:16px;border:0px;margin:5px;' />";
                            break;
                        }
                        default: {
                            break;
                        }
                    }
                    
                }                
                echo "</div>";

                ?>
			</td>

			<td align="left" valign="top" class="cofiThreadTableRow<?php echo $rowColor; ?> cofiThreadBorder2" >



				<?php
                //echo "<div style='width: 745px; overflow:hidden;'>";

				
				// anchor
				echo "<a name='p" . $posting->id . "'></a>";				
				
                echo $posting->date;
                	         
                	                						
				$pageOffset = JRequest::getVar('limitstart', 0, '', 'int');
				
				if ( $pageOffset == 0) { // first page off this thread

					if ( $counter == 1) { // h3 and icons in first row only
										
            			echo "<h3 style='font-weight: bold; margin: 3px 0px 1px 0px;'>";
            				echo $posting->subject;
            			echo "</h3>";
				
				
						echo "<div class='cofiSocialMediaButtonRow'>";
						
							echo "<div class='cofiSocialMediaButton1'>";
								$socialMediaButton1 = $params->get('socialMediaButton1', '');
								echo $socialMediaButton1;						
							echo "</div>";
	
							echo "<div class='cofiSocialMediaButton2'>";
								$socialMediaButton2 = $params->get('socialMediaButton2', '');
								echo $socialMediaButton2;						
							echo "</div>";
										
							echo "<div class='cofiSocialMediaButton3'>";
								$socialMediaButton3 = $params->get('socialMediaButton3', '');		
								echo $socialMediaButton3;						
							echo "</div>";
							
						echo "</div>";

						echo "<div class='clr' style='margin-bottom:5px'></div>";
					
					}
					else {
					
						echo "<div style='margin: 5px 0px 5px 0px;'>";
	                		echo "<b>";
	                			echo $posting->subject;
	                		echo "</b>";	
						echo "</div>";
						
					}
					
				}
				else { // following pages
					echo "<div style='margin: 5px 0px 5px 0px;'>";
                		echo "<b>";
                			echo $posting->subject;
                		echo "</b>";	
					echo "</div>";					
				}
					


                echo "<div class='cofiHorizontalRuler'></div>";

                $message = $posting->message;    
                            
                // transfer bbcode into html code
				$message = $CofiHelper->replace_bb_tags( $message);

                // transfer emoticon code into html image code
				$message = $CofiHelper->replace_emoticon_tags( $message);				

                if ( $_useFlickr == 1) {
                    // transfer flickr tags into image code
                    $message = $CofiHelper->replace_flickr_tags( $f, $_flickr_display_mode, $posting->id, $message);
                }


                if ( $_useYouTube == 1) {
                    // transfer youtube tags into inline code
                    $message = $CofiHelper->replace_youtube_tags( $_youtube_video_width, $_youtube_video_height, $message);
                }


				// close html tags
				$message = $CofiHelper->close_html_tags( $message);

				$message = nl2br( $message);


				echo "<span class='cofiMessage'>";
                	echo $message;
				echo "</span>";
                
                echo "<br />";
                echo "<br />";


				// image attachements
				if 	( 	$posting->image1 != "" ||
						$posting->image2 != "" ||
						$posting->image3 != "" ||
						$posting->image4 != "" ||
						$posting->image5 != "" ) { // found attachement(s)
				
					
					echo "<div class='cofiImageAttachmentRow'>";
					

					$_titleprefix = "";
					switch ( $_imagesDisplayMode) { // set rel and target
					
						case 1: { // Slimbox
							$_linktag = " rel='lightbox-" . $posting->id . "' ";
							break;
						}

						case 2: { // RokBox
							$_linktag = " rel='rokbox (" . $posting->id . ")' ";
							$_titleprefix = $posting->subject . " :: ";
							break;
						}

						case 3: { // YOOeffects
                            $_linktag = " rel='shadowbox[" . $posting->id . "]' ";
                            break;
                        }

						default: { // Set to Browser display by default
							$_linktag = " target='_blank' ";
							break;
						}

					
					}


				    if ( $posting->image1 != "") { 
   						echo "<div class='cofiImageAttachment1'>";

							echo "<a href='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/large/".$posting->image1."' " .$_linktag . " title='".$_titleprefix.$posting->image1_description . "' >";
							echo "<img src='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/small/".$posting->image1."' alt='.".$posting->image1_description."' class='cofiAttachmentImageEdit' />";
							echo "</a>";
					        	
   						echo "</div>";
				    }			    

				    if ( $posting->image2 != "") { 
   						echo "<div class='cofiImageAttachment2'>";

							echo "<a href='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/large/".$posting->image2."' " . $_linktag . " title='".$_titleprefix.$posting->image2_description . "' >";
							echo "<img src='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/small/".$posting->image2."' alt='.".$posting->image2_description."' class='cofiAttachmentImageEdit' />";
							echo "</a>";
					        	
   						echo "</div>";
				    }			    

				    if ( $posting->image3 != "") { 
   						echo "<div class='cofiImageAttachment3'>";

							echo "<a href='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/large/".$posting->image3."' " . $_linktag . " title='".$_titleprefix.$posting->image3_description . "' >";
							echo "<img src='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/small/".$posting->image3."' alt='.".$posting->image3_description."' class='cofiAttachmentImageEdit' />";
							echo "</a>";
					        	
   						echo "</div>";
				    }			    

				    if ( $posting->image4 != "") { 
   						echo "<div class='cofiImageAttachment4'>";

							echo "<a href='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/large/".$posting->image4."' " . $_linktag . " title='".$_titleprefix.$posting->image4_description . "' >";
							echo "<img src='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/small/".$posting->image4."' alt='.".$posting->image4_description."' class='cofiAttachmentImageEdit' />";
							echo "</a>";
					        	
   						echo "</div>";
				    }			    

				    if ( $posting->image5 != "") { 
   						echo "<div class='cofiImageAttachment5'>";

							echo "<a href='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/large/".$posting->image5."' " . $_linktag . " title='".$_titleprefix.$posting->image5_description . "' >";
							echo "<img src='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/small/".$posting->image5."' alt='.".$posting->image5_description."' class='cofiAttachmentImageEdit' />";
							echo "</a>";
					        	
   						echo "</div>";
				    }			    

					echo "<div class='clr' style='margin-bottom:5px'></div>";
									
					echo "</div>";
				
				
					echo "<br />";
					echo "<br />";				
				}



                $signature = nl2br($CofiUser->getSignature());
                if ( $signature != "") { // display signature hr if one is present
                    echo "<div class='cofiHorizontalRuler'></div>";
                    echo $signature;
                }

				$website = $CofiUser->getWebsite();
                if ( $website != "") { // display website 
                	echo "<br />";                
                	if ( $signature == "") { // draw hr if no signature is available
                    	echo "<div class='cofiHorizontalRuler'></div>";
                    }
                    echo "<a href='http://" . $website . "' target='_blank' title='" . $website . "' rel='nofollow'>";
                    	echo $website;
                    echo "</a>";
                }



                echo "<br />";
                echo "<br />";





                if ( !$user->guest) { // user is logged in. display menue in post

                	echo "<div class='cofiPostMenu'>";

							if ( $this->lockedStatus == 0 || $logUser->isModerator()) { // thread is not locked or user is moderator
							
					        	// reply to post
								echo "<div class='cofiPostMenuItem'>";

									echo "<div class='cofiPostMenuIcon'>";
									
					            		echo "<img src='" . $_root . "components/com_discussions/assets/threads/reply.png' />";
					            		
					            	echo "</div>";

									echo "<div class='cofiPostMenuText'>";
					            	
						                echo "<div class='cofiPostMenuLinks'>";
						                    $menuLinkReplyTMP = "index.php?option=com_discussions&view=posting&task=reply&catid=".$this->categorySlug."&thread=".$this->thread."&parent=".$posting->id;
						                    $menuLinkReply = JRoute::_( $menuLinkReplyTMP);
						                    echo "<a href='".$menuLinkReply."'>" . JText::_( 'COFI_REPLY2' ) . "</a>";
						                echo "</div>";
						                
						        	echo "</div>";
						                
								echo "</div>";
					
					
					        	// reply to post with quote 
								echo "<div class='cofiPostMenuItem'>";
								
									echo "<div class='cofiPostMenuIcon'>";
									
					            		echo "<img src='" . $_root . "components/com_discussions/assets/threads/quote.png' />";
					            		
					            	echo "</div>";

					            	echo "<div class='cofiPostMenuText'>";
					            	
						            	echo "<div class='cofiPostMenuLinks'>";
						            	
											$menuLinkQuoteTMP = "index.php?option=com_discussions&view=posting&task=quote&catid=".$this->categorySlug."&thread=".$this->thread."&parent=".$posting->id."&id=".$posting->id;
											$menuLinkQuote = JRoute::_( $menuLinkQuoteTMP);
											echo "<a href='".$menuLinkQuote."'>" . JText::_( 'COFI_QUOTE2' ) . "</a>";
						            	echo "</div>";
						            	
						            echo "</div>";	
						            
								echo "</div>";
					
					
					
								// edit post
					        	// check if user is post owner or has moderator rights
					                                
					        	$date = $posting->date;
					        
					        	$day = substr( $date, 0, 2);  // 1 + 2 char
					        	$month = substr( $date, 3, 2);  // 4 + 5 char
					        	$year = substr( $date, 6, 4);  // 7 - 10 char
					        
					        	$hour = substr( $date, 11, 2);  // 12 + 13 char
					        	$minute = substr( $date, 14, 2);  // 15 + 16 char
					        
					
					        	//date_default_timezone_set ( "Europe/Berlin");
					        
					        	$now = time(); // current unixtime
					        
					        	$posttime = mktime( $hour, $minute, 0, $month, $day, $year); // unixtime from post date
					        
					        	$isUserEditable = true;
					        
					        	// get editTime in minutes from global parameters
					        	$editTime = $params->get('editTime', '30');		
								                        
					        	if ( ($now - $posttime) > ( $editTime * 60)) {
					        		$isUserEditable = false;
					       		}
					       		
					       		$editForever = $params->get('editForever', '1');		
					       		if ( $editForever == 1) {
					        		$isUserEditable = true;
					        	}
					       		
					        
					        	if ( $logUser->isModerator() || ( ($logUser->getId() == $CofiUser->getId()) && $isUserEditable == true)) {
					        	
									echo "<div class='cofiPostMenuItem'>";
									
										echo "<div class='cofiPostMenuIcon'>";

						                	echo "<img src='" . $_root . "components/com_discussions/assets/threads/edit.png' />";
						                	
						            	echo "</div>";	

										echo "<div class='cofiPostMenuText'>";

						                	echo "<div class='cofiPostMenuLinks'>";
						                	
												$menuLinkEditTMP = "index.php?option=com_discussions&view=posting&task=edit&catid=".$this->categorySlug."&thread=".$this->thread."&parent=".$posting->id."&id=".$posting->id;
												$menuLinkEdit = JRoute::_( $menuLinkEditTMP);
												echo "<a href='".$menuLinkEdit."'>" . JText::_( 'COFI_EDIT' ) . "</a>";
												
						                	echo "</div>";
						                	
					            		echo "</div>";						                	
						                	
					            	echo "</div>";
					        	}
					
							} // if locked == 0 or user is moderator
							
							else {
									echo "<div class='cofiPostMenuItem'>";

										echo "<div class='cofiPostMenuIcon'>";
									
											echo "<img src='" . $_root . "components/com_discussions/assets/threads/lock.png' />";

					            		echo "</div>";						                	
						
										echo "<div class='cofiPostMenuText'>";

											echo "<div class='cofiPostMenuLinks'>";
											
												echo JText::_( 'COFI_THREAD_IS_LOCKED' );									
												
											echo "</div>";

					            		echo "</div>";						                	
										
									echo "</div>";
							
							}
							
					
							
							// delete post / thread
							if ( $logUser->isModerator()) {
						
									echo "<div class='cofiPostMenuItem'>";
									
										echo "<div class='cofiPostMenuIcon'>";
										
											echo "<img src='" . $_root . "components/com_discussions/assets/threads/delete.png' />";

					            		echo "</div>";						                	

										echo "<div class='cofiPostMenuText'>";
						
											echo "<div class='cofiPostMenuLinks'>";
						
												$menuLinkDeleteTMP = "index.php?option=com_discussions&view=moderation&task=delete&id=".$posting->id;
												$menuLinkDelete = JRoute::_( $menuLinkDeleteTMP);
										
												echo "<a href='".$menuLinkDelete."' onclick='return confirmdelete();'>" . JText::_( 'COFI_DELETE' ) . "</a>";
						
											echo "</div>";

					            		echo "</div>";						                	
										
									echo "</div>";
							}						
							
							
							
							
							// check if it is the first post in a thread (parent_id == 0)
					        if ( $posting->parent_id == 0) {
							
								// check if user has moderator rights
								if ( $logUser->isModerator()) {
							
									// move thread
									echo "<div class='cofiPostMenuItem'>";

										echo "<div class='cofiPostMenuIcon'>";
									
											echo "<img src='" . $_root . "components/com_discussions/assets/threads/move.png' />";

					            		echo "</div>";						                	

										echo "<div class='cofiPostMenuText'>";
						
											echo "<div class='cofiPostMenuLinks'>";
						
												$menuLinkMoveTMP = "index.php?option=com_discussions&view=moderation&task=move&catid=".$this->categorySlug."&thread=".$this->thread;
												$menuLinkMove = JRoute::_( $menuLinkMoveTMP);
											
												echo "<a href='".$menuLinkMove."'>" . JText::_( 'COFI_MOVE' ) . "</a>";
						
											echo "</div>";

					            		echo "</div>";						                	
										
									echo "</div>";
					
					
									// sticky or unsticky thread
									echo "<div class='cofiPostMenuItem'>";

										echo "<div class='cofiPostMenuIcon'>";
									
											echo "<img src='" . $_root . "components/com_discussions/assets/threads/sticky.png' />";

					            		echo "</div>";						                	

										echo "<div class='cofiPostMenuText'>";
						
											echo "<div class='cofiPostMenuLinks'>";
						
												if ( $this->stickyStatus == 0) { // thread is not sticky
													$menuLinkStickyTMP = "index.php?option=com_discussions&view=moderation&task=sticky&catid=".$this->categorySlug."&thread=".$this->thread;
													$menuLinkSticky = JRoute::_( $menuLinkStickyTMP);
													echo "<a href='".$menuLinkSticky."'>" . JText::_( 'COFI_STICKY' ) . "</a>";
												}
												else {
													$menuLinkUnstickyTMP = "index.php?option=com_discussions&view=moderation&task=unsticky&catid=".$this->categorySlug."&thread=".$this->thread;
													$menuLinkUnsticky = JRoute::_( $menuLinkUnstickyTMP);
													echo "<a href='".$menuLinkUnsticky."'>" . JText::_( 'COFI_UNSTICKY' ) . "</a>";
												}
						
											echo "</div>";

					            		echo "</div>";						                	
										
									echo "</div>";
					
					
									// close thread
									echo "<div class='cofiPostMenuItem'>";
									
										echo "<div class='cofiPostMenuIcon'>";
									
											echo "<img src='" . $_root . "components/com_discussions/assets/threads/lock.png' />";

					            		echo "</div>";						                	

										echo "<div class='cofiPostMenuText'>";
										
											echo "<div class='cofiPostMenuLinks'>";
						
												if ( $this->lockedStatus == 0) { // thread is not locked
													$menuLinkLockTMP = "index.php?option=com_discussions&view=moderation&task=lock&catid=".$this->categorySlug."&thread=".$this->thread;
													$menuLinkLock = JRoute::_( $menuLinkLockTMP);
													echo "<a href='".$menuLinkLock."'>" . JText::_( 'COFI_LOCK' ) . "</a>";
												}
												else {
													$menuLinkUnlockTMP = "index.php?option=com_discussions&view=moderation&task=unlock&catid=".$this->categorySlug."&thread=".$this->thread;
													$menuLinkUnlock = JRoute::_( $menuLinkUnlockTMP);
													echo "<a href='".$menuLinkUnlock."'>" . JText::_( 'COFI_UNLOCK' ) . "</a>";
												}
											
											echo "</div>";
										
					            		echo "</div>";						                	
										
									echo "</div>";
								
								}
					        
					        }

                	echo "</div>";
                	
                	echo "<br />";
                	

                }

                ?>

			</td>

    	</tr>

    	<tr>
			<td align="center" class="noborder">
			
			<?php
			
			if ( $counter == 1) {
				// for future use (banner after 1 post)
				echo "&nbsp;";								
			}
			else {
				echo "&nbsp;";
			}
			
			?>
			
			</td>
    	</tr>

		<?php
        $rowColor = 2;

		$counter++;
		
	endforeach;
	?>

</table>



<!-- Pagination Links -->
<table width="100%" class="noborder">
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
	            echo "<a href='$menuLinkCategory'>".$this->categoryName."</a>";
	            ?>
	        </td>
	        <td class="noborder">
	            <?php
	            echo "&nbsp;&raquo;&nbsp;";
	            echo $this->subject;
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
	    			// todo make configurable !!!
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


<!-- HTML Box Bottom -->
<?php
$htmlBoxThreadBottom = $params->get('htmlBoxThreadBottom', '');		

if ( $htmlBoxThreadBottom != "") {
	echo "<div class='cofiHtmlBoxThreadBottom'>";
		echo $htmlBoxThreadBottom;
	echo "</div>";
}
?>
<!-- HTML Box Bottom -->


<?php
include( 'components/com_discussions/includes/footer.php');
?>

</div>


