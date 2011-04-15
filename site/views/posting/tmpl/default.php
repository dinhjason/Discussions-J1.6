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

// get parameters
$params = JComponentHelper::getParams('com_discussions');
$replyListLength = $params->get('replyListLength', '0');
$_useFlickr 	 = $params->get( 'useFlickr', '0');  // 0 no, 1 yes
$_useYouTube 	 = $params->get( 'useYouTube', '0');  // 0 no, 1 yes


// website root directory
$_root = JURI::root();
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
$htmlBoxPostingTop = $params->get('htmlBoxPostingTop', '');

if ( $htmlBoxPostingTop != "") {
	echo "<div class='cofiHtmlBoxPostingTop'>";
		echo $htmlBoxPostingTop;
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
				$menuText = $app->getMenu()->getActive()->title;	
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
	            if ( $this->subject != "") {
	            	echo "&nbsp;&raquo;&nbsp;";
	            		            	
	            	if ( $this->task == "new") {
	            		echo $this->headline;
	            	}
	            	else {	            	
	            		echo $this->subject;
	            	}	            	
	            }
	            ?>
	        </td>
	    </tr>
	</table>

	<?php
}
?>
<!-- Breadcrumb -->




<?php

    echo "<form action='' method='post' name='postform' id='postform' enctype='multipart/form-data'>";

    	echo "<table cellspacing='1' cellpadding='0' width='100%' class='noborder'>";

    		echo "<tr>";    		
    			echo "<td class='noborder' style='padding: 5px;' >";

    				echo "<div class='cofiSubjectHeader'>" . JText::_( 'COFI_SUBJECT' ) . ":</div> ";
   					
					if ( $this->task == "new") { // new posting

            			echo "<div class='cofiSubject'>";
            				echo "<input type='text' name='postSubject' id='postSubject' size='50' maxlength='80'>";	
            			echo "</div>";
            			
            			echo "<div class='cofiSubjectFooter'>" . JText::_( 'COFI_MIN_5_CHARS' ) . "</div> ";

					}
					else {
						$_reSubject = "Re: ".$this->subject;
						
            			echo "<div class='cofiSubject'>";
						echo $_reSubject;
            			echo "</div>";

						$_reSubject = str_replace( '"', "'", $_reSubject);						
						?>
						
            			<input type="hidden" name="postSubject" id="postSubject" value="<?php echo $_reSubject; ?>">	

						<?php
					}
            		
    			echo "</td>";
    		echo "</tr>";

    		echo "<tr>";
    			echo "<td align='left' valign='top' class='noborder' style='padding: 5px;' >";
    			
    				echo "<div class='cofiTextHeader'>" . JText::_( 'COFI_TEXT' ) . ":</div> ";
   					   			
   					echo "<div class='cofiText'>";			    			
   						echo "<textarea name='postText' cols='80' rows='20' wrap='VIRTUAL' id='postText'>";
							if ( $this->task == "quote") {
   								echo "[quote]" . $this->messageText . "[/quote]";
							}
							if ( $this->task == "edit") {
   								echo $this->messageText;
							}
							
							
    					echo "</textarea>";
    				echo "</div>";

            		echo "<div class='cofiTextFooter'>" . JText::_( 'COFI_MIN_5_CHARS' ) . "</div> ";





					if ( $CofiHelper->isPermittedForImageAttachmentsById( $user->id) ) {
					

						// image attachments (if configured)
		    			// get # of images from parameters
						$params = JComponentHelper::getParams('com_discussions');
						$images = $params->get('images', '3'); // 3 default		
	
		
						if ( $this->task == "quote") {
							$this->image1 = "";
							$this->image1_description = "";
							$this->image2 = "";
							$this->image2_description = "";
							$this->image3 = "";
							$this->image3_description = "";
							$this->image4 = "";
							$this->image4_description = "";
							$this->image5 = "";
							$this->image5_description = "";	
						} 
	
	
						if ( $images > 0) {
							// image
				    		echo "<div class='cofiImageHeader cofiFirstImageHeader' >" . JText::_( 'COFI_IMAGE_1' ) . ":</div> ";
	
							// image show/delete								
					    	if ( $this->image1 != "") { 
	   							echo "<div>";
						        	echo "<img src='" . $_root . "images/discussions/posts/".$this->thread."/" . $this->id . "/small/" . $this->image1 . "' alt='" . $this->subject . "' align='top' class='cofiAttachmentImageEdit' />";
						        	
		        				echo "<input type='checkbox' name='cb_image1' class='cofiAttachmentCheckboxEdit' value='delete'> " . JText::_( 'COFI_IMAGE_DELETE' );
						        	
	   							echo "</div>";
					    	}			    
	
							// image upload			   							
	            			echo "<div class='cofiImage'>";
								echo "<input type='file' name='image1' id='image1' value='' size='50' maxlength='250' />";
	            			echo "</div>";
	            			
	            			echo "<div class='cofiImageFooter'>" . JText::_( 'COFI_IMAGE_1_HELP' ) . "</div> ";
	
	            			// Description
				    		echo "<div class='cofiImageHeader'>" . JText::_( 'COFI_IMAGE_1_DESCRIPTION' ) . ":</div> ";
				   							
	            			echo "<div class='cofiImageDescription'>";
								echo "<input type='text' name='image1_description' id='image1_description' size='50' maxlength='80' value='" . $this->image1_description . "' >";
	            			echo "</div>";
	            			
	            			echo "<div class='cofiImageFooter'>" . JText::_( 'COFI_IMAGE_1_DESCRIPTION_HELP' ) . "</div> ";
						}
	
	
						if ( $images > 1) {
							// image
				    		echo "<div class='cofiImageHeader cofiFirstImageHeader'>" . JText::_( 'COFI_IMAGE_2' ) . ":</div> ";
	
							// image show/delete								
					    	if ( $this->image2 != "") { 
	   							echo "<div>";
						        	echo "<img src='" . $_root . "images/discussions/posts/".$this->thread."/" . $this->id . "/small/" . $this->image2 . "' alt='" . $this->subject . "' align='top' class='cofiAttachmentImageEdit' />";
						        	
		        				echo "<input type='checkbox' name='cb_image2' class='cofiAttachmentCheckboxEdit' value='delete'> " . JText::_( 'COFI_IMAGE_DELETE' );
						        	
	   							echo "</div>";
					    	}			    
	
							// image upload			   										   							
	            			echo "<div class='cofiImage'>";
								echo "<input type='file' name='image2' id='image2' value='' size='50' maxlength='250' />";
	            			echo "</div>";
	            			
	            			echo "<div class='cofiImageFooter'>" . JText::_( 'COFI_IMAGE_2_HELP' ) . "</div> ";
	
	            			// Description
				    		echo "<div class='cofiImageHeader'>" . JText::_( 'COFI_IMAGE_2_DESCRIPTION' ) . ":</div> ";
				   							
	            			echo "<div class='cofiImageDescription'>";
								echo "<input type='text' name='image2_description' id='image2_description' size='50' maxlength='80' value='" . $this->image2_description . "'>";
	            			echo "</div>";
	            			
	            			echo "<div class='cofiImageFooter'>" . JText::_( 'COFI_IMAGE_2_DESCRIPTION_HELP' ) . "</div> ";
						}
	
						
						if ( $images > 2) {
							// image
				    		echo "<div class='cofiImageHeader cofiFirstImageHeader'>" . JText::_( 'COFI_IMAGE_3' ) . ":</div> ";
	
							// image show/delete								
					    	if ( $this->image3 != "") { 
	   							echo "<div>";
						        	echo "<img src='" . $_root . "images/discussions/posts/".$this->thread."/" . $this->id . "/small/" . $this->image3 . "' alt='" . $this->subject . "' align='top' class='cofiAttachmentImageEdit' />";
						        	
		        				echo "<input type='checkbox' name='cb_image3' class='cofiAttachmentCheckboxEdit' value='delete'> " . JText::_( 'COFI_IMAGE_DELETE' );
						        	
	   							echo "</div>";
					    	}			    
	
							// image upload			   										   							
	            			echo "<div class='cofiImage'>";
								echo "<input type='file' name='image3' id='image3' value='' size='50' maxlength='250' />";
	            			echo "</div>";
	            			
	            			echo "<div class='cofiImageFooter'>" . JText::_( 'COFI_IMAGE_3_HELP' ) . "</div> ";
	
	            			// Description
				    		echo "<div class='cofiImageHeader'>" . JText::_( 'COFI_IMAGE_3_DESCRIPTION' ) . ":</div> ";
				   							
	            			echo "<div class='cofiImageDescription'>";
								echo "<input type='text' name='image3_description' id='image3_description' size='50' maxlength='80' value='" . $this->image3_description . "' >";
	            			echo "</div>";
	            			
	            			echo "<div class='cofiImageFooter'>" . JText::_( 'COFI_IMAGE_3_DESCRIPTION_HELP' ) . "</div> ";
						}
						
						
						if ( $images > 3) {
							// image
				    		echo "<div class='cofiImageHeader cofiFirstImageHeader'>" . JText::_( 'COFI_IMAGE_4' ) . ":</div> ";
				   							
							// image show/delete								
					    	if ( $this->image4 != "") { 
	   							echo "<div>";
						        	echo "<img src='" . $_root . "images/discussions/posts/".$this->thread."/" . $this->id . "/small/" . $this->image4 . "' alt='" . $this->subject . "' align='top' class='cofiAttachmentImageEdit' />";
						        	
		        				echo "<input type='checkbox' name='cb_image4' class='cofiAttachmentCheckboxEdit' value='delete'> " . JText::_( 'COFI_IMAGE_DELETE' );
						        	
	   							echo "</div>";
					    	}			    
	
							// image upload			   							
	            			echo "<div class='cofiImage'>";
								echo "<input type='file' name='image4' id='image4' value='' size='50' maxlength='250' />";
	            			echo "</div>";
	            			
	            			echo "<div class='cofiImageFooter'>" . JText::_( 'COFI_IMAGE_4_HELP' ) . "</div> ";
	
	            			// Description
				    		echo "<div class='cofiImageHeader'>" . JText::_( 'COFI_IMAGE_4_DESCRIPTION' ) . ":</div> ";
				   							
	            			echo "<div class='cofiImageDescription'>";
								echo "<input type='text' name='image4_description' id='image4_description' size='50' maxlength='80' value='" . $this->image4_description . "'>";
	            			echo "</div>";
	            			
	            			echo "<div class='cofiImageFooter'>" . JText::_( 'COFI_IMAGE_4_DESCRIPTION_HELP' ) . "</div> ";
						}
	
						if ( $images > 4) {
							// image
				    		echo "<div class='cofiImageHeader cofiFirstImageHeader'>" . JText::_( 'COFI_IMAGE_5' ) . ":</div> ";
				   							
							// image show/delete								
					    	if ( $this->image5 != "") { 
	   							echo "<div>";
						        	echo "<img src='" . $_root . "images/discussions/posts/".$this->thread."/" . $this->id . "/small/" . $this->image5 . "' alt='" . $this->subject . "' align='top' class='cofiAttachmentImageEdit' />";
						        	
		        				echo "<input type='checkbox' name='cb_image5' class='cofiAttachmentCheckboxEdit' value='delete'> " . JText::_( 'COFI_IMAGE_DELETE' );
						        	
	   							echo "</div>";
					    	}			    
	
							// image upload			   							
	            			echo "<div class='cofiImage'>";
								echo "<input type='file' name='image5' id='image5' value='' size='50' maxlength='250' />";
	            			echo "</div>";
	            			
	            			echo "<div class='cofiImageFooter'>" . JText::_( 'COFI_IMAGE_5_HELP' ) . "</div>";
	
	            			// Description
				    		echo "<div class='cofiImageHeader'>" . JText::_( 'COFI_IMAGE_5_DESCRIPTION' ) . ":</div> ";
				   							
	            			echo "<div class='cofiImageDescription'>";
								echo "<input type='text' name='image5_description' id='image5_description' size='50' maxlength='80' value='" . $this->image5_description . "' >";
	            			echo "</div>";
	            			
	            			echo "<div class='cofiImageFooter'>" . JText::_( 'COFI_IMAGE_5_DESCRIPTION_HELP' ) . "</div> ";
						}
					
					}




            		echo "<div class='cofiTextButton'>";

    					switch ( $this->task) {
    						case "edit": {
								echo "<input type='hidden' name='dbmode' value='update'>";
								break;
							}
							default: {
								echo "<input type='hidden' name='dbmode' value='insert'>";    			
								break;
							}					
						}
						echo "<input type='hidden' name='task' value='save'>";  			
						echo "<input class='cofiButton' type='submit' name='submit' value='" . JText::_( 'COFI_SAVE' ) ."'>";
					
					echo "</div> ";


    			echo "</td>";
    			    			
    		echo "</tr>";


    	echo "</table>";

    echo "</form>";



    // display recent x posts if task = reply or quote
    if ( $this->task == "reply" || $this->task == "quote" ) {

        if ( $replyListLength > 0) {

            echo "<div class='cofiPostRecentX'>";


                echo "<div class='cofiPostRecentHeader'>";

                    echo JText::_( 'COFI_REPLY_RECENT1' );

                    echo " " . $replyListLength . " ";

                    echo JText::_( 'COFI_REPLY_RECENT2' );

                echo "</div>";



                echo "<div class='cofiPostRecent'>";

                    echo $CofiHelper->getReplyRecentListByThreadId( $this->thread, $replyListLength);

                echo "</div>";


            echo "</div>";
        }

    }
?>





<div class="cofiPostHelp">

    <div class="cofiTextFormatHeader">
    	<?php echo JText::_( 'COFI_FORMAT_YOUR_TEXT' ); ?>:
    </div>
    				
    <div class="cofiTextFormat">
    		
		<table cellspacing="0px" cellpadding="10px" width="100%" class="noborder">

			<tr>
				<td class="noborder">
					<b><?php echo JText::_( 'COFI_FORMAT_BOLD' ); ?></b>
					<br />
					[b]bold[/b]
					<br />
					<br />
				</td>
				<td class="noborder">
					<i><?php echo JText::_( 'COFI_FORMAT_ITALICS' ); ?></i>
					<br />
					[i]italics[/i]
					<br />
					<br />
				</td>
				<td class="noborder">
					<u><?php echo JText::_( 'COFI_FORMAT_UNDERLINE' ); ?></u>
					<br />
					[u]underline[/u]
					<br />
					<br />
				</td>
			</tr>

			<tr>
				<td class="noborder">
					<s><?php echo JText::_( 'COFI_FORMAT_STRIKE_THROUGH' ); ?></s>
					<br />
					[s]strikethrough[/s]
					<br />
					<br />
				</td>
				<td class="noborder">
					<big><?php echo JText::_( 'COFI_FORMAT_BIG' ); ?></big>
					<br />
					[big]big[/big]
					<br />
					<br />
				</td>
				<td class="noborder">
					<small><?php echo JText::_( 'COFI_FORMAT_SMALL' ); ?></small>
					<br />
					[small]small[/small]
					<br />
					<br />
				</td>
			</tr>

			<tr>
				<td valign="top" class="noborder">
					<?php echo JText::_( 'COFI_FORMAT_UNORDERED_LIST' ); ?>
					<br />
					[ul]
					<br />
					[li]first[/li]
					<br />
					[li]second[/li]
					<br />
					[/ul]
					<br />
					<br />
				</td>
				<td valign="top" class="noborder">
					<?php echo JText::_( 'COFI_FORMAT_ORDERED_LIST' ); ?>
					<br />
					[ol]
					<br />
					[li]first[/li]
					<br />
					[li]second[/li]
					<br />
					[/ol]
					<br />
					<br />
				</td>
				<td valign="top" class="noborder">
					<?php echo JText::_( 'COFI_FORMAT_LINK' ); ?>
					<br />
					[url]http://www.codingfish.com[/url]
					<br />
					[url=http://www.codingfish.com]Codingfish[/url]
					<br />
					<br />
				</td>
			</tr>

            <tr>
                <td valign="top" class="noborder">
                    <?php echo JText::_( 'COFI_FORMAT_QUOTE' ); ?>
                   <br />
                   [quote]quoted text[/quote]
                   <br />
                   <br />
                </td>
                <td valign="top" class="noborder">
                	<?php
                	if ( $_useFlickr == 1) {                
                    	echo JText::_( 'COFI_FLICKR_PHOTO' );
                   		echo "<br />";
                   		echo "[flickr=PHOTOID]";
                   	}
                   	else {
                   		echo "&nbsp;";                   	
                   	}
                   	?>
                   	<br />
                   	<br />
                </td>
                <td valign="top" class="noborder">
                	<?php
                	if ( $_useYouTube == 1) {                
                    	echo JText::_( 'COFI_YOUTUBE_VIDEO' );
                   		echo "<br />";
                   		echo "[youtube=VIDEOID]";
                   	}
                   	else {
                   		echo "&nbsp;";                   	
                   	}
                   	?>
                   	<br />
                   	<br />
                </td>
            </tr>




			<tr>
				<td colspan="3" class="noborder">
					&nbsp;
				</td>
			</tr>
				  
				  
			<tr>
				<td class="noborder">
					<?php echo "<img src='" . $_root . "components/com_discussions/assets/emoticons/smile.gif' />"; ?> &nbsp;&nbsp; :-)
					<br />
					<br />
				</td>
				<td class="noborder">
					<?php echo "<img src='" . $_root . "components/com_discussions/assets/emoticons/wink.gif' />"; ?> &nbsp;&nbsp; ;-)
					<br />
					<br />
				</td>
				<td class="noborder">
					<?php echo "<img src='" . $_root . "components/com_discussions/assets/emoticons/sad.gif' />"; ?> &nbsp;&nbsp; :-(
					<br />
					<br />
				</td>
			</tr>

			<tr>
				<td class="noborder">
					<?php echo "<img src='" . $_root . "components/com_discussions/assets/emoticons/laugh.gif' />"; ?> &nbsp;&nbsp; :-D
					<br />
					<br />
				</td>
				<td class="noborder">
					<?php echo "<img src='" . $_root . "components/com_discussions/assets/emoticons/kiss.gif' />"; ?> &nbsp;&nbsp; ;-*
					<br />
					<br />
				</td>
				<td class="noborder">
					<?php echo "<img src='" . $_root . "components/com_discussions/assets/emoticons/cool.gif' />"; ?> &nbsp;&nbsp; 8-)
					<br />
					<br />
				</td>
			</tr>

			<tr>
				<td class="noborder">
					<?php echo "<img src='" . $_root . "components/com_discussions/assets/emoticons/thumbup.gif' />"; ?> &nbsp;&nbsp; (Y)
					<br />
					<br />
				</td>
				<td class="noborder">
					<?php echo "<img src='" . $_root . "components/com_discussions/assets/emoticons/thumbdown.gif' />"; ?> &nbsp;&nbsp; (N)
					<br />
					<br />
				</td>
				<td class="noborder">
					<?php echo "<img src='" . $_root . "components/com_discussions/assets/emoticons/tongue.gif' />"; ?> &nbsp;&nbsp; :-P
					<br />
					<br />
				</td>
			</tr>

			<tr>
				<td class="noborder">
					<?php echo "<img src='" . $_root . "components/com_discussions/assets/emoticons/crying.gif' />"; ?> &nbsp;&nbsp; :'(
					<br />
					<br />
				</td>
				<td class="noborder">
					<?php echo "<img src='" . $_root . "components/com_discussions/assets/emoticons/innocent.gif' />"; ?> &nbsp;&nbsp; O:-)
					<br />
					<br />
				</td>
				<td class="noborder">
					<?php echo "<img src='" . $_root . "components/com_discussions/assets/emoticons/devil.gif' />"; ?> &nbsp;&nbsp; >:-)
					<br />
					<br />
				</td>
			</tr>

		</table>
    		    				    				
    </div>

</div>





<!-- HTML Box Bottom -->
<?php
$htmlBoxPostingBottom = $params->get('htmlBoxPostingBottom', '');		

if ( $htmlBoxPostingBottom != "") {
	echo "<div class='cofiHtmlBoxPostingBottom'>";
		echo $htmlBoxPostingBottom;
	echo "</div>";
}
?>
<!-- HTML Box Bottom -->


<?php
include( 'components/com_discussions/includes/footer.php');
?>

</div>


