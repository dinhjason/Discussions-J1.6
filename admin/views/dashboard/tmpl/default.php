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

$user= &JFactory::getUser();

$db	=& JFactory::getDBO();		
$sql = "SELECT version FROM ".$db->nameQuote('#__discussions_meta')." WHERE id='1'";		
$db->setQuery( $sql);
$version = $db->loadResult();

// website root directory
$_root = JURI::root();
?>


<div id="cpanel" style="float:left;width:50%;">
  
	
	<div style="float:left;">
    	<div class="icon">
	    	<a href="index.php?option=com_discussions&amp;view=forums">
		    	<img alt="<?php echo JText::_('COFI_FORUMS'); ?>" src="components/com_discussions/images/dashboard/forums.png" />
		    	<span><?php echo JText::_('COFI_FORUMS'); ?></span>
	    	</a>
    	</div>
  	</div>


	<div style="float:left;">
    	<div class="icon">
	    	<a href="index.php?option=com_discussions&amp;view=posts">
		    	<img alt="<?php echo JText::_('COFI_POSTS'); ?>" src="components/com_discussions/images/dashboard/posts.png" />
		    	<span><?php echo JText::_('COFI_POSTS'); ?></span>
	    	</a>
    	</div>
  	</div>


  	<div style="float:left;">
    	<div class="icon">
	    	<a href="index.php?option=com_discussions&amp;view=users">
		    	<img alt="<?php echo JText::_('COFI_USERS'); ?>" src="components/com_discussions/images/dashboard/users.png" />
		    	<span><?php echo JText::_('COFI_USERS'); ?></span>
	    	</a>
    	</div>
  	</div>

  
	<div class="clr"></div>
  
</div>





<div id="tabs" style="float:right; width:50%;">

 	<?php
	jimport('joomla.html.pane');
	
	$pane =& JPane::getInstance('Tabs');
	
	echo $pane->startPane('discussionsPane');
	
		echo $pane->startPanel(JText::_('COFI_LATEST_POSTS'), 'latestpoststab');

			echo "<div>";

				$rows = &$this->latestPosts;
			
				echo "<table width='100%' cellspacing='1' cellpadding='2' >";
					
					
    				echo "<thead>";
    				
      					echo "<tr>";
      					
      						echo "<td>";
      							echo JText::_('COFI_DATE');
      						echo "</td>";      					

      						echo "<td>";
      							echo JText::_('COFI_SUBJECT');
      						echo "</td>";      					
      						
      						echo "<td>";
      							echo JText::_('COFI_PUBLISHED');
      						echo "</td>";      					
      						
      					echo "</tr>";
      					
    				echo "</thead>";
					
    				echo "<tbody>";
					
					foreach ( $rows as $row) {
							
						$link 	= JRoute::_( 'index.php?option=com_discussions&view=post&task=edit&cid[]='. $row->id );							
						?>
			
						<tr class="<?php echo "row$k"; ?>">
						
							<td>
								<?php 
								echo $row->postdate; 
								?>
							</td>
							
							<td>
								<?php 
								echo "<a href='" . $link . "'>";
								echo $row->subject; 
								echo "</a>";
								?>
							</td>


							<td>
								<?php 
								if ( $row->published) {
									echo "<img src='" . $_root . "administrator/templates/bluestork/images/admin/tick.png' width='16px' height='16px' />";
								}
								else {
									echo "<img src='" . $_root . "administrator/templates/bluestork/images/admin/publish_r.png' width='16px' height='16px' />";
								}																
								?>
							</td>
							
							
						</tr>
			
						<?php
					}
					
					echo "</tbody>";
			
				echo "</table>";
		
			echo "</div>";
					
		echo $pane->endPanel();

		
		echo $pane->startPanel(JText::_('COFI_ABOUT'), 'abouttab');
			?>
			
			<div style="text-align:center">
			
				<div style="margin: 10px 0px 0px 0px">
					<h1>Discussions</h1>
				</div>

				<div>					
					<h2>
					<?php 
					echo JText::_('COFI_VERSION') . " " . $version; 
					?>
					</h2>					
				</div>
								
				<div>
					<br />
					<h3>
					<?php
					echo JText::_('COFI_BY');
					?>
					</h3>
				</div>
	
				<div style="margin: 10px 0px 10px 0px">
			    	<a href="http://www.codingfish.com" title="Codingfish" target="_blank"><img alt="Codingfish" src="components/com_discussions/images/system/codingfish.png" /></a>
				</div>
	
				<div>
					<a href="http://www.codingfish.com" title="Codingfish" target="_blank">Codingfish</a>
					<br />
					<br />
					Achim Fischer
				</div>


				<div>
					<br />
					<br />
					<h3>
					<?php
					echo JText::_('COFI_CREDITS');
					?>					
					</h3>
				</div>
			
				<div>
					<b>Icons:</b>
					<br />
					<a href="http://www.famfamfam.com" title="FamFamFam Silk Icon Set" target="_blank">Silk Icon Set</a>
					<br />
					<a href="http://www.komodomedia.com" title="Social Network Icon Pack" target="_blank">Social Network Icon Pack</a>
					<br />
					nuvoX Icon Set
					<br />
					<br />
				</div>
							
				<div>
					<b>Libraries:</b>
					<br />
					<a href="http://www.digitalia.be" title="Slimbox" target="_blank">Slimbox</a>
					<br />
					<br />
				</div>
							
							
							
			</div>
			
			<?php
		echo $pane->endPanel();

  
	echo $pane->endPane();
	?>
</div>

<div class="clr"></div>





