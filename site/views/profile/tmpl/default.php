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


$user =& JFactory::getUser();
$CofiUser = new CofiUser( $user->id);

// get parameters
$params = JComponentHelper::getParams('com_discussions');

// website root directory
$_root = JURI::root();
?>

<div class="codingfish">

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
$htmlBoxProfileTop = $params->get('htmlBoxProfileTop', '');

if ( $htmlBoxProfileTop != "") {
	echo "<div class='cofiHtmlBoxProfileTop'>";
		echo $htmlBoxProfileTop;
	echo "</div>";
}
?>
<!-- HTML Box Top -->



<?php
include( 'components/com_discussions/includes/topmenu.php');
?>





<?php
echo "<h3>";
	echo $this->headline;
echo "</h3>";
?>



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
	            	echo "&nbsp;&raquo;&nbsp;";
	            	echo JText::_( "COFI_PROFILE", true );
	            ?>
	        </td>
	    </tr>
	</table>

	<?php
}
?>
<!-- Breadcrumb -->




<?php

echo "<br />";


echo "<div class='cofiProfileContent'>";


echo "<form action='' method='post' name='postform' id='postform' enctype='multipart/form-data'>";

	echo "<table cellspacing='0' cellpadding='0' width='100%' border='0' class='noborder'>";

		echo "<tr>";
			echo "<td align='left' valign='top' width='100%' class='noborder' style='padding: 10px;' colspan='2'>";

				echo "<div class='cofiProfileUsername'>";
					echo $user->username;
			    echo "</div>";
						
				echo "<div class='cofiProfileName'>";
					echo "&nbsp;&nbsp;&nbsp;(".$user->name.")";
			    echo "</div>";

				echo "<br />";
			echo "</td>";    
		echo "</tr>";


		echo "<tr>";
				
			// left column			
			echo "<td align='left' valign='top' width='50%' class='noborder' style='padding: 10px;' >";
			
				echo "<div class='cofiProfileLocationBox'>";

					echo "<div class='cofiProfileLocationHeader'>";
						echo "<div class='cofiProfileHeader'>";
								echo JText::_( 'COFI_LOCATION' );															
					    echo "</div>";
				    echo "</div>";
	
					echo "<div class='cofiProfileLocationRow'>";
						echo "<div class='cofiProfileLocationLabel'>";
							echo JText::_( 'COFI_ZIPCODE' ) . ": ";
				    	echo "</div>";
						echo "<div class='cofiProfileLocationValue'>";						
	            				echo "<input type='text' name='zipcode' id='zipcode' size='5' maxlength='10' value='".$CofiUser->getZipcode()."'>";	      
				    	echo "</div>";
				    echo "</div>";
	
	
					echo "<div class='cofiProfileLocationRow'>";
						echo "<div class='cofiProfileLocationLabel'>";
							echo JText::_( 'COFI_CITY' ) . ": ";
					    echo "</div>";
						echo "<div class='cofiProfileLocationValue'>";
		            			echo "<input type='text' name='city' id='city' size='30' maxlength='100' value='".$CofiUser->getCity()."'>";
					    echo "</div>";
				    echo "</div>";
	
					echo "<div class='cofiProfileLocationRow'>";
						echo "<div class='cofiProfileLocationLabel'>";
							echo JText::_( 'COFI_COUNTRY' ) . ": ";
					    echo "</div>";
						echo "<div class='cofiProfileLocationValue'>";
								include( 'components/com_discussions/includes/countryselect.php');							
					    echo "</div>";
				    echo "</div>";

			    echo "</div>";


				echo "<div class='cofiProfileStatusBox'>";

					echo "<div class='cofiProfileStatusHeader'>";
						echo "<div class='cofiProfileHeader'>";
							echo JText::_( 'COFI_FORUM_STATUS' );
			    		echo "</div>";
			    	echo "</div>";

					echo "<div class='cofiProfileStatusRow'>";
						echo "<div class='cofiProfileStatusLabel'>";
							echo JText::_( 'COFI_POSTS' ) . ": ";
					    echo "</div>";
						echo "<div class='cofiProfileStatusValue'>";
							echo $CofiUser->getPosts();
					    echo "</div>";
				    echo "</div>";

					echo "<div class='cofiProfileStatusRow'>";
						echo "<div class='cofiProfileStatusLabel'>";
							echo JText::_( 'COFI_MODERATED' ) . ": ";
					    echo "</div>";
						echo "<div class='cofiProfileStatusValue'>";
							if ( $CofiUser->isRookie() || $CofiUser->isModerated()) {
								echo JText::_( 'COFI_YES' );
							}
							else {
								echo JText::_( 'COFI_NO' );
							}
					    echo "</div>";
				    echo "</div>";
			    
			    echo "</div>";



			    							
			echo "</td>";    
			// left column
									 
									 
			// right column		
			echo "<td align='left' valign='top' width='50%' class='noborder' style='padding: 10px;' >";

				echo "<div class='cofiProfileAvatarBox'>";

					echo "<div class='cofiProfileAvatarHeader'>";
						echo "<div class='cofiProfileHeader'>";
							echo JText::_( 'COFI_AVATAR' );
			    		echo "</div>";
			    	echo "</div>";

					echo "<div class='cofiProfileAvatarRow'>";
					    echo "<div class='cofiAvatarBox'>";			    			    
					    	if ( $CofiUser->getAvatar() == "") { 
					        	echo "<img src='" . $_root . "components/com_discussions/assets/users/user.png' class='cofiAvatar' alt='$user->username' />";
					    	}
					    	else { 
					        	echo "<img src='" . $_root . "images/discussions/users/".$user->id."/large/".$CofiUser->getAvatar()."' class='cofiAvatar' alt='$user->username' />";					        	
					    	}			    
					    echo "</div>";
					  
					  
					    if ( $CofiUser->getAvatar() != "") {
				    		echo "<div>";
					        	echo "<input type='checkbox' name='cb_avatar' value='1'> " . JText::_( 'COFI_AVATAR_DELETE' );
				    		echo "</div>";
				    	}
					  					  
					    
				    echo "</div>";

				
					echo "<div class='cofiProfileAvatarRow'>";				    			    
						echo "<div class='cofiTextHeader'>" . JText::_( 'COFI_AVATAR_UPLOAD' ) . ":</div> ";
		                	echo "<input class='cofiText' type='file' name='avatar'>";								
		        		echo "<div class='cofiTextFooter'>" . JText::_( 'COFI_AVATAR_FILE_INFO' ) . "</div> ";
				    echo "</div>";


			    echo "</div>";
			 
			echo "</td>";    
			// right column
									 									    			
		echo "</tr>";



		echo "<tr>";
		
			echo "<td align='left' valign='top' style='padding: 5px;' colspan='2' class='noborder'>";
		
				echo "<div class='cofiProfileSignatureBox'>";

					echo "<div class='cofiProfileSignatureHeader'>";
						echo "<div class='cofiProfileHeader'>";
							echo JText::_( 'COFI_SIGNATURE' );
			    		echo "</div>";
			    	echo "</div>";

					echo "<div class='cofiProfileSignatureRow'>";
					
						echo "<div class='cofiTextHeader'>" . JText::_( 'COFI_TEXT' ) . ":</div> ";
							echo "<div class='cofiText'>";			    			
								echo "<textarea name='signature' cols='70' rows='3' wrap='VIRTUAL' id='signature'>";
										echo $this->signature;
							echo "</textarea>";
						echo "</div>";
						
		        		echo "<div class='cofiTextFooter'>" . JText::_( 'COFI_SIGNATURE_INFO' ) . "</div> ";
					
					
				    echo "</div>";


				    // website
					echo "<div class='cofiProfileFollowMeRow'>";
					
						echo "<div class='cofiProfileFollowMeLabel'>" . JText::_( 'COFI_WEBSITE' ) . ":</div> ";
							echo "<div class='cofiProfileFollowMeValue'>";			    			
		            			echo "<input type='text' name='website' id='website' size='40' maxlength='100' value='".$this->website."'>";
						echo "</div>";
											
				    echo "</div>";


			    
			    echo "</div>";
		
			echo "</td>";    			    			
		echo "</tr>";



		echo "<tr>";
		
			echo "<td align='left' valign='top' style='padding: 5px;' colspan='2' class='noborder'>";
		
				echo "<div class='cofiProfileFollowMeBox'>";

					echo "<div class='cofiProfileFollowMeHeader'>";
						echo "<div class='cofiProfileHeader'>";
							echo JText::_( 'COFI_FOLLOW_ME' );
			    		echo "</div>";
			    	echo "</div>";


			    	echo "<div class='cofiProfileFollowMeRow'>";
			    		echo JText::_( 'COFI_FOLLOW_ME_INFO' ) . ":";
			    	echo "</div>";

				    
				    // twitter
					echo "<div class='cofiProfileFollowMeRow'>";
					
						echo "<div class='cofiProfileFollowMeLabel'>Twitter:</div> ";
							echo "<div class='cofiProfileFollowMeValue'>";			    			
		            			echo "<input type='text' name='twitter' id='twitter' size='40' maxlength='100' value='".$this->twitter."'>";
						echo "</div>";
											
				    echo "</div>";
				    
				    
				    // facebook
					echo "<div class='cofiProfileFollowMeRow'>";
					
						echo "<div class='cofiProfileFollowMeLabel'>Facebook:</div> ";
							echo "<div class='cofiProfileFollowMeValue'>";			    			
		            			echo "<input type='text' name='facebook' id='facebook' size='40' maxlength='100' value='".$this->facebook."'>";
						echo "</div>";
											
				    echo "</div>";


				    // flickr
					echo "<div class='cofiProfileFollowMeRow'>";
					
						echo "<div class='cofiProfileFollowMeLabel'>Flickr:</div> ";
							echo "<div class='cofiProfileFollowMeValue'>";			    			
		            			echo "<input type='text' name='flickr' id='flickr' size='40' maxlength='100' value='".$this->flickr."'>";
						echo "</div>";
											
				    echo "</div>";


				    // youtube
					echo "<div class='cofiProfileFollowMeRow'>";
					
						echo "<div class='cofiProfileFollowMeLabel'>YouTube:</div> ";
							echo "<div class='cofiProfileFollowMeValue'>";			    			
		            			echo "<input type='text' name='youtube' id='youtube' size='40' maxlength='100' value='".$this->youtube."'>";
						echo "</div>";
											
				    echo "</div>";
				    
				    
			    
			    echo "</div>";
		
			echo "</td>";    			    			
		echo "</tr>";
        		





		echo "<tr>";
		
			echo "<td align='left' valign='top' class='noborder' style='padding: 5px;' colspan='2'>";
		
				echo "<div class='cofiProfileFollowMeBox'>";
				
				
					echo "<div class='cofiProfileFollowMeHeader'>";
						echo "<div class='cofiProfileHeader'>";
							echo JText::_( 'COFI_OTHER_SETTINGS' );
			    		echo "</div>";
			    	echo "</div>";


				    // show online status
					echo "<div class='cofiProfileFollowMeRow'>";
					
						echo "<div class='cofiProfileFollowMeLabel'  style='width: 150px;'>" . JText::_( 'COFI_SHOW_ONLINE_STATUS' ) . ":</div> ";
						
						echo "<div class='cofiProfileFollowMeValue'>";
						
							echo "<select id='show_online_status' name='show_online_status'>";	
								if ( $this->show_online_status == 0) {	
									echo "<option value='1'>" . JText::_( 'COFI_YES' ) . "</option>";
									echo "<option value='0' selected='selected'>" . JText::_( 'COFI_NO' ) . "</option>";
								}
								else {
									echo "<option value='1' selected='selected'>" . JText::_( 'COFI_YES' ) . "</option>";
									echo "<option value='0'>" . JText::_( 'COFI_NO' ) . "</option>";
								}
							echo "</select>";
		            			
						echo "</div>";
											
				    echo "</div>";
				
							
			    echo "</div>";

			echo "</td>";    			    			
		echo "</tr>";

        		
        		
        		

		echo "<tr>";
			echo "<td align='left' valign='top' class='noborder' style='padding-left: 10px;' colspan='2'>";
        		
        		echo "<div class='cofiTextButton'>";
					echo "<input type='hidden' name='task' value='save'>";  			            		
					echo "<input class='cofiButton' type='submit' name='submit' value='" . JText::_( 'COFI_SAVE' ) . "'>";
				echo "</div> ";
								
			echo "</td>";    			    			
		echo "</tr>";


	echo "</table>";

echo "</form>";


echo "</div>";
?>



<!-- HTML Box Bottom -->
<?php
$htmlBoxProfileBottom = $params->get('htmlBoxProfileBottom', '');		

if ( $htmlBoxProfileBottom != "") {
	echo "<div class='cofiHtmlBoxProfileBottom'>";
		echo $htmlBoxProfileBottom;
	echo "</div>";
}
?>
<!-- HTML Box Bottom -->


<?php
include( 'components/com_discussions/includes/footer.php');
?>

</div>



