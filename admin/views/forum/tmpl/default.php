<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */
  
defined('_JEXEC') or die('Restricted access'); 

JHTML::_('behavior.tooltip');
?>

<script language="javascript" type="text/javascript">

	//<![CDATA[
	function submitbutton(pressbutton) {
		if ( pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		if (trim( document.adminForm.name.value ) == "") {
			alert( '<?php echo JText::_('COFI_FORUM_MUST_HAVE_NAME', true);?>' );
		} else {
			submitform( pressbutton );
		}
	}
	//]]>
	
</script>



<form action="index.php" method="post" name="adminForm" id="adminForm">

	<table class="admintable" width="100%">
	
		<tbody>
		
			<tr>
			
				<td valign="top">
				
					<fieldset class="adminform">
					
						<legend>
							<?php echo JText::_('COFI_FORUM_DETAILS');?>
						</legend>
						
						<table class="admintable" width="100%">
													

							<tr>
								<td class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_PUBLISHED');	?>
								</td>
								<td style="padding: 10px;">
									<?php echo $this->lists['published']; ?>
								</td>
							</tr>


							<tr>
								<td class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_NAME'); ?>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="text" name="name" id="name" value="<?php echo $this->forum->name; ?>" size="50" maxlength="250" />
								</td>
							</tr>
							
							<tr>
								<td class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_ALIAS'); ?>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="text" name="alias" value="<?php echo $this->forum->alias; ?>" size="50" maxlength="250" />
								</td>
							</tr>


							<tr>
								<td valign="top" class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_DESCRIPTION'); ?>
								</td>
								<td style="padding: 10px;">
				 					<textarea name="description" id="description" rows="5" cols="50" style="width: 100%;"><?php echo $this->forum->description; ?></textarea>									
								</td>
							</tr>


							<tr>
								<td class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_PARENT'); ?>
								</td>
								<td style="padding: 10px;">
								
									<?php 
									echo $this->lists['parent']; 
									?>
								</td>
							</tr>
							
							
							<tr>		
								<td class="key" style="padding: 10px;">
									<label for="private">
										<?php echo JText::_( 'COFI_PRIVATE' ).':'; ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<?php
									$html = JHTML::_('select.booleanlist', 'private', 'class="inputbox"', $this->forum->private);
									echo $html;
									?>
								</td>
							</tr>

							<tr>		
								<td class="key" style="padding: 10px;">
									<label for="moderated">
										<?php echo JText::_( 'COFI_MODERATED' ).':'; ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<?php
									$html = JHTML::_('select.booleanlist', 'moderated', 'class="inputbox"', $this->forum->moderated);
									echo $html;
									?>
								</td>
							</tr>




							<tr>
								<td class="key" style="padding: 20px 10px 10px 10px;">
									&nbsp;
								</td>
								<td style="padding: 10px;">
									&nbsp;
								</td>
							</tr>

							<tr>		
								<td class="key" style="padding: 10px;">
									<label for="show_image">
										<?php echo JText::_( 'COFI_SHOW_IMAGE' ).':'; ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<?php
									$html = JHTML::_('select.booleanlist', 'show_image', 'class="inputbox"', $this->forum->show_image);
									echo $html;
									?>
								</td>
							</tr>

							<tr>
								<td class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_IMAGE'); ?>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="text" name="image" id="image" value="<?php echo $this->forum->image; ?>" size="50" maxlength="250" />
								</td>
							</tr>





							<tr>
								<td class="key" style="padding: 20px 10px 10px 10px;">
									&nbsp;
								</td>
								<td style="padding: 10px;">
									&nbsp;
								</td>
							</tr>

							<tr>
								<td class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_META_TITLE'); ?>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="text" name="meta_title" id="meta_title" value="<?php echo $this->forum->meta_title; ?>" size="50" maxlength="250" />
								</td>
							</tr>

							<tr>
								<td class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_META_DESCRIPTION'); ?>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="text" name="meta_description" id="meta_description" value="<?php echo $this->forum->meta_description; ?>" size="50" maxlength="250" />
								</td>
							</tr>

							<tr>
								<td class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_META_KEYWORDS'); ?>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="text" name="meta_keywords" id="meta_keywords" value="<?php echo $this->forum->meta_keywords; ?>" size="50" maxlength="250" />
								</td>
							</tr>



							<tr>
								<td class="key" style="padding: 20px 10px 10px 10px;">
									&nbsp;
								</td>
								<td style="padding: 10px;">
									&nbsp;
								</td>
							</tr>

							<tr>
								<td valign="top" class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_BANNER_TOP'); ?>
								</td>
								<td style="padding: 10px;">
				 					<textarea name="banner_top" id="banner_top" rows="5" cols="50" style="width: 100%;"><?php echo $this->forum->banner_top; ?></textarea>									
								</td>
							</tr>

							<tr>
								<td valign="top" class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_BANNER_BOTTOM'); ?>
								</td>
								<td style="padding: 10px;">
				 					<textarea name="banner_bottom" id="banner_bottom" rows="5" cols="50" style="width: 100%;"><?php echo $this->forum->banner_bottom; ?></textarea>									
								</td>
							</tr>


						</table>


												
						<input type="hidden" name="option" value="<?php echo $option;?>" />
						<input type="hidden" name="task" value="" />						
						<input type="hidden" name="cid[]" value="<?php echo $this->forum->id; ?>" />
						<input type="hidden" name="view" value="forum" />
						
						<?php echo JHTML::_('form.token'); ?>
						
					</fieldset>
					
				</td>
				
			</tr>
			
		</tbody>
		
	</table>
		
</form>



