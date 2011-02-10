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
			alert( '<?php echo JText::_('COFI_POST_MUST_HAVE_SUBJECT', true);?>' );
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
							<?php echo JText::_('COFI_POST_DETAILS');?>
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
									<?php echo JText::_('COFI_SUBJECT'); ?>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="text" name="subject" id="subject" value="<?php echo $this->post->subject; ?>" size="50" maxlength="250" />
								</td>
							</tr>
							
							<tr>
								<td class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_ALIAS'); ?>
								</td>
								<td style="padding: 10px;">
									<?php
									if ( $this->post->id == $this->post->thread) { // OP									
										?>
										<input class="text_area" type="text" name="alias" value="<?php echo $this->post->alias; ?>" size="50" maxlength="250" />
										<?php
									}
									else { // don't allow alias changes on sub posts
										echo $this->post->alias;
									}
									?>
								</td>
							</tr>


							<tr>
								<td valign="top" class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_TEXT'); ?>
								</td>
								<td style="padding: 10px;">
				 					<textarea name="message" id="message" rows="5" cols="50" style="width: 100%;"><?php echo $this->post->message; ?></textarea>									
								</td>
							</tr>							
							


							<tr>
								<td class="key" style="padding: 10px;">
									<?php echo JText::_('COFI_TYPE'); ?>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="type" name="type" value="<?php echo $this->post->type; ?>" size="5" maxlength="5" />
								</td>
							</tr>
														
							

							<tr>		
								<td class="key" style="padding: 10px;">
									<label for="sticky">
										<?php echo JText::_( 'COFI_STICKY' ).':'; ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<?php
									$html = JHTML::_('select.booleanlist', 'sticky', 'class="inputbox"', $this->post->sticky);
									echo $html;
									?>
								</td>
							</tr>

							<tr>		
								<td class="key" style="padding: 10px;">
									<label for="locked">
										<?php echo JText::_( 'COFI_LOCKED' ).':'; ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<?php
									$html = JHTML::_('select.booleanlist', 'locked', 'class="inputbox"', $this->post->locked);
									echo $html;
									?>
								</td>
							</tr>

							<tr>		
								<td class="key" style="padding: 10px;">
									<label for="wfm">
										<?php echo JText::_( 'COFI_WAITING_FOR_MODERATION' ).':'; ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<?php
									$html = JHTML::_('select.booleanlist', 'wfm', 'class="inputbox"', $this->post->wfm);
									echo $html;
									?>
								</td>
							</tr>



						</table>
												
						<input type="hidden" name="option" value="<?php echo $option;?>" />
						<input type="hidden" name="task" value="" />						
						<input type="hidden" name="cid[]" value="<?php echo $this->post->id; ?>" />
						<input type="hidden" name="view" value="post" />
						
						<?php echo JHTML::_('form.token'); ?>
						
					</fieldset>
					
				</td>
				
			</tr>
			
		</tbody>
		
	</table>
		
</form>



