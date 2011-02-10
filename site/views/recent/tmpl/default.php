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

// get parameters
$params = JComponentHelper::getParams('com_discussions');

// website root directory
$_root = JURI::root();

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



echo "<h2 style='padding-left: 0px;'>";
    echo JText::_( "COFI_HISTORY");
    echo " " . $this->hours . " ";
    echo JText::_( 'COFI_HISTORY_HOURS');
echo "</h2>";
echo "<br />";
?>



<!-- Breadcrumb -->
<?php
$showBreadcrumbRow = $params->get('breadcrumb', '0');

if ( $showBreadcrumbRow == "1") {
	?>

	<table style="margin-top: 5px;">
	    <tr>
	        <td>
	            <?php
	            $menuLinkHome     = JRoute::_( 'index.php?option=com_discussions');
	            $menuText = JSite::getMenu()->getActive()->name;
	            echo "<a href='$menuLinkHome'>" . $menuText . "</a>";
	            ?>
	        </td>
	        <td>
	            <?php
	            echo "&nbsp;&raquo;&nbsp;";
	            echo JText::_( "COFI_HISTORY");
                echo " " . $this->hours . " ";
                echo JText::_( 'COFI_HISTORY_HOURS');
	            ?>
	        </td>
	    </tr>
	</table>

	<?php
}
?>
<!-- Breadcrumb -->





<!-- Pagination Links -->
<table width="100%" style="margin-bottom:10px;">
    <tr>
        <td>
            <?php
            echo $this->pagination->getPagesLinks();
            ?>
        </td>
        <td>
            <?php
            echo $this->pagination->getPagesCounter();
            ?>
        </td>

    </tr>
</table>
<!-- Pagination Links -->



<table width="100%" border="0" cellspacing="0" cellpadding="0">



    <tr>
		<td width="100px"   align="center" class="cofiTableHeader"><?php echo JText::_( 'COFI_AUTHOR' ); ?></td>
        <td                 align="left"   class="cofiTableHeader"><?php echo JText::_( 'COFI_RECENT_POST_TITLE' ); ?></td>
    </tr>



	<?php
	$rowColor = 1;

	foreach ( $this->postings as $posting ) : ?>

    	<tr>

			<td align="center" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexTableRowReplies">

            <?php
            $CofiUser = new CofiUser( $posting->user_id);

            // $entryUsername = $CofiHelper->getUsernameById( $posting->user_id);
			$entryUsername = $CofiUser->getUsername();



            echo "<div class='cofiCategoryAvatarBox'>";

                if ( $CofiUser->getAvatar() == "") { // display default avatar
                    echo "<img src='" . $_root . "components/com_discussions/assets/users/user.png' class='cofiCategoryDefaultAvatar' alt='" . $entryUsername . "' title='" . $entryUsername . "' />";
                }
                else { // display uploaded avatar
                    echo "<img src='" . $_root . "images/discussions/users/".$posting->user_id."/small/".$CofiUser->getAvatar()."' class='cofiCategoryAvatar' alt='" . $entryUsername . "' title='" . $entryUsername . "' />";
                }

            echo "</div>";


            ?>

			</td>



            <td align="left" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexTableRowTopic">

                <?php

                    $_hoverSubject 	= $posting->subject;
                    $_hoverSubject 	= str_replace( '\'', '"', $_hoverSubject);

                    $_categorySlug 	= $CofiHelper->getCategorySlugById( $posting->cat_id);
                    $_categoryName 	= $CofiHelper->getCategoryNameById( $posting->cat_id);
                    $_threadSlug 	= $CofiHelper->getThreadSlugById( $posting->thread);

                    $_postingJumpPoint = $CofiHelper->getPostingJumpPointByThreadIdAndPostingId( $posting->thread, $posting->id);

                    $postingLinkTMP = "index.php?option=com_discussions&view=thread&catid=" . $_categorySlug . "&thread=" . $_threadSlug;
                    $postingLinkTMP .= $_postingJumpPoint;
                    $postingLink = JRoute::_( $postingLinkTMP);

                    echo "<a href='$postingLink' title='".$_hoverSubject."'>".$posting->subject."</a>";



                    echo "<div class='cofiIndexTableRowTopicSubtitle'>";

                        echo JText::_( 'COFI_POSTED' ) . " " . $posting->date_created . " " . JText::_( 'COFI_BY' ) . " ";

                        echo "<b>";
                          echo $entryUsername;
                        echo "</b>";

                        echo " " . JText::_( 'COFI_IN' ) . " ";

                        $forum_link = JRoute::_('index.php?option=com_discussions&view=category&catid=' . $CofiHelper->getCategorySlugById( $posting->cat_id));
                        echo "<a href='" . $forum_link . "' title=\"" . $_categoryName . "\" >";
                            echo $_categoryName;
                        echo "</a>";

                    echo "</div>";
                ?>

            </td>




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
<table width="100%" style="margin-top:10px;">
    <tr>
        <td>
            <?php
            echo $this->pagination->getPagesLinks();
            ?>
        </td>
        <td>
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

	<table style="margin-top: 5px;">
	    <tr>
	        <td>
	            <?php
	            echo "<a href='$menuLinkHome'>" . $menuText . "</a>";
	            ?>
	        </td>
	        <td>
	            <?php
	            echo "&nbsp;&raquo;&nbsp;";
                echo JText::_( "COFI_HISTORY");
                echo " " . $this->hours . " ";
                echo JText::_( 'COFI_HISTORY_HOURS');                    
	            ?>
	        </td>
	    </tr>
	</table>

	<?php
}
?>
<!-- Breadcrumb -->



<?php
include( 'components/com_discussions/includes/share.php');
?>


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



