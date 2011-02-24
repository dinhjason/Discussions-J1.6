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


$user =& JFactory::getUser();
$logUser = new CofiUser( $user->id);
$CofiHelper = new CofiHelper();

$app = JFactory::getApplication();

// website root directory
$_root = JURI::root();
?>

<div class="codingfish">

<!-- Codingfish Extension Icon -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="noborder"> 

    <tr> 
        <td  colspan="2" align="left" class="noborder">
        	<h1 class="componentheading">
				<?php
				$app->getMenu()->getActive()->title;
				?>
			</h1>
		</td> 
		
		<td class="noborder"> 
        	&nbsp;        	
		</td> 
    </tr> 

    <tr> 		
		<td colspan="2" class="noborder"> 
        	&nbsp;
		</td> 
    </tr> 

</table>
<!-- Codingfish Extension Icon -->


<?php
include( 'components/com_discussions/includes/topmenu.php');
?>

<center>
	<br />
	<b>
	*** FOR MODERATORS ONLY ***
	</b>
</center>



<table width="100%" border="0" cellspacing="0" cellpadding="0" class="noborder"> 

	<?php 

		switch ( $this->task) {
		
			case "move": {
			
				echo "<br />";	
				echo "Select new category for this Thread";
				echo "<br />";	
				echo "<br />";	
				
    			echo "<form action='' method='post' name='postform' id='postform'>";

					echo "<input type='hidden' name='task' value='move'>";  			
					echo "<input type='hidden' name='catid' value='".$this->categoryFrom."'>";
					echo "<input type='hidden' name='thread' value='".$this->thread."'>";

            		echo $CofiHelper->getMoveToSelectBox();		
						
					echo "<br />";	
					echo "<br />";	
					echo "<br />";	
					echo "<br />";	
					echo "<input type='submit' name='submit' value='Move thread'>";

    			echo "</form>";
    			
				echo "<br />";	
				echo "<br />";	
				
				break;
			}


			case "approve": {

				echo "<br />";	
				echo "<h2 class='contentheading'>" . JText::_( 'COFI_MODERATION_POSTS_WAITING' ) . "</h2>";
				echo "<br />";	

				echo "<table width='100%' border='0' cellspacing='0' cellpadding='5' class='noborder'>";
				
					$rowColor = 2;
				
					foreach ( $this->postingsWFM as $posting ) : ?>
				
				    	<tr>
				
							<td width="100" align="center" valign="top" class="cofiThreadTableRow<?php echo $rowColor; ?> cofiModerationBorder1" >
				                <?php
				
				
                				// get $post->username;
								$opUserUsername = $CofiHelper->getUsernameById( $posting->user_id);
				
				                // show avatar
				                echo "<div class='cofiAvatarBox'>";
				                $CofiUser = new CofiUser( $posting->user_id);
				                if ( $CofiUser->getAvatar() == "") { // display default avatar
				                    echo "<img src='" . $_root . "components/com_discussions/assets/users/user.png' class='cofiAvatar' alt='$opUserUsername' />";
				                }
				                else { // display uploaded avatar
				                    echo "<img src='" . $_root . "images/discussions/users/".$posting->user_id."/large/".$CofiUser->getAvatar()."' class='cofiAvatar' alt='$opUserUsername' />";
				                }
				                echo "</div>";
				
				
				                // display username
				                echo "<br />";                
				                echo "<b>";
				                	echo $opUserUsername;
				                echo "</b>";
				                echo "<br />";
				
				
								echo "<div class='cofiAvatarColumnPosts'>";
				                	echo $CofiUser->getPosts()." posts";
				                echo "</div>";
				                
				
								// location
								echo "<div class='cofiAvatarColumnLocation'>";
								echo "Location:";
				                echo "<br />";
				                $city = $CofiUser->getCity();
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
									echo "n.a.";
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
				                        case "Community Manager": {
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
				
							<td align="left" valign="top" class="cofiThreadTableRow<?php echo $rowColor; ?> cofiModerationBorder2" >
								<?php
				                echo $posting->date;
				                
				                echo "<h2>";
				                echo $posting->subject;
				                echo "</h2>";
				
				                echo "<div class='cofiHorizontalRuler'></div>";
				
				                $message = $posting->message;    
				                            
				                // transfer bbcode into html code
								$message = $CofiHelper->replace_bb_tags( $message);
				
								// close html tags				
								$message = $CofiHelper->close_html_tags( $message);
				
								$message = nl2br( $message);
								
				
								echo "<span class='cofiMessage'>";
				                	echo $message;
								echo "</span>";
				                
				                echo "<br />";
				                echo "<br />";
				
				                $signature = nl2br($CofiUser->getSignature());
				                if ( $signature != "") { // display signature hr if one is present
				                    echo "<div class='cofiHorizontalRuler'></div>";
				                    echo $signature;
				                }
				
				                echo "<br />";
				                echo "<br />";
				
								// moderation menu
				                echo "<div class='cofiPostMenu'>";
				
				                echo "<table width='100%' border='0' cellspacing='0' cellpadding='5' class='noborder'>";
				
				                    echo "<tr>";
											
										// check if user has moderator rights
										if ( $logUser->isModerator()) {
										
											// accept post
											echo "<td width='16' align='center' valign='middle' class='noborder'>";
												echo "<img src='" . $_root . "components/com_discussions/assets/threads/accept.png' style='margin-left: 5px; border:0px;' />";
											echo "</td>";
				
											echo "<td width='20' align='left' valign='middle' class='noborder'>";
												echo "<span class='cofiPostMenuLinks'>";
				
				        							$menuLinkAcceptTMP = "index.php?option=com_discussions&view=moderation&task=accept&post=".$posting->id;
				        							$menuLinkAccept = JRoute::_( $menuLinkAcceptTMP);
				        							echo "<a href='".$menuLinkAccept."'>" . JText::_( 'COFI_MODERATION_ACCEPT' ) . "</a>";
				
												echo "</span>";
											echo "</td>";
				
				
											echo "<td class='noborder'>";
												echo "&nbsp;&nbsp;&nbsp;";
											echo "</td>";				
				
				
											// deny post
											echo "<td width='16' align='center' valign='middle' class='noborder'>";
												echo "<img src='" . $_root . "components/com_discussions/assets/threads/deny.png' style='margin-left: 5px; border:0px;' />";
											echo "</td>";
				
											echo "<td width='20' align='left' valign='middle' class='noborder'>";
												echo "<span class='cofiPostMenuLinks'>";
				
				        							$menuLinkDenyTMP = "index.php?option=com_discussions&view=moderation&task=deny&post=".$posting->id;
				        							$menuLinkDeny = JRoute::_( $menuLinkDenyTMP);
				        							echo "<a href='".$menuLinkDeny."'>" . JText::_( 'COFI_MODERATION_DENY' ) . "</a>";
				
												echo "</span>";
											echo "</td>";


											echo "<td class='noborder'>";
												echo "&nbsp;&nbsp;&nbsp;";
											echo "</td>";				


					                        echo "<td class='noborder'>";
					                            echo "<b>" . $CofiHelper->getCategoryNameById( $posting->cat_id) . "</b>";
					                        echo "</td>";

				
										}
										else {				                                                
					                        echo "<td class='noborder'>";
					                            echo "&nbsp;";
					                        echo "</td>";
										}
				
				
				                    echo "</tr>";
				
				                echo "</table>";
				
				                echo "</div>";
								// moderation menu
				
							echo "</td>";
				
				    	echo "</tr>";
				
				
				    	echo "<tr>";
							echo "<td class='noborder'>";
							echo "</td>";
				    	echo "</tr>";
				
				
				        $rowColor = 2;
				
					endforeach;
				
				echo "</table>";
							
				echo "<br />";	
				echo "<br />";	
				
				break;
			}
			
			
			
			default: {			
				echo "<br />";	
				echo "<br />";	
				
				break;
			}
			
				
		
		}

	?>



</table>



<?php
include( 'components/com_discussions/includes/footer.php');
?>

</div>

