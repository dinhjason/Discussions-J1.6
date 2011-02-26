<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2011 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */
  
defined('_JEXEC') or die('Restricted access'); 

JHTML::_('behavior.tooltip');
?>

<script language="javascript" type="text/javascript">

	//<![CDATA[
	Joomla.submitbutton = function(pressbutton) {
		
		var form = document.adminForm;
				
		if (pressbutton == 'cancel') {
			
	    	form.action.value = 'cancel'
	    	submitform('cancel');
	    	return;
	
		} 
		
		if (pressbutton == 'save') {
			
			if ( document.adminForm.subject.value == "") {
				
				alert( '<?php echo JText::_('COFI_POST_MUST_HAVE_SUBJECT', true);?>' );
		    	return;
						
			} else {
				
		    	form.action.value = 'save'
		    	submitform('save');
		    	return;
		
			}
		} 
		
		submitform( pressbutton );		
	}		
	//]]>
	
</script>



<form action="index.php" method="post" name="adminForm" id="adminForm">

	<fieldset class="adminform">

	<table class="admintable" width="100%">
	
		<tbody>
		
			<tr>
			
				<td valign="top">
									
						<legend>
							<?php echo JText::_('COFI_POST_DETAILS');?>
						</legend>
						
						<table class="admintable" width="100%">
													

							<tr>
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_('COFI_PUBLISHED');	?>
									</label>
								</td>
								<td style="padding: 10px;">
									<fieldset class="radio">
										<?php echo $this->lists['published']; ?>
									</fieldset>
								</td>
							</tr>


							<tr>
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_('COFI_SUBJECT'); ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="text" name="subject" id="subject" value="<?php echo $this->post->subject; ?>" size="50" maxlength="250" />
								</td>
							</tr>
							
							<tr>
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_('COFI_ALIAS'); ?>
									</label>
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
									<label>
										<?php echo JText::_('COFI_TEXT'); ?>
									</label>
								</td>
								<td style="padding: 10px;">
				 					<textarea name="message" id="message" rows="5" cols="50" style="width: 100%;"><?php echo $this->post->message; ?></textarea>									
								</td>
							</tr>							
							


							<tr>
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_('COFI_TYPE'); ?>
									</label>
								</td>
								<td style="padding: 10px;">									
									<?php									
									$arr = array(
										JHTML::_('select.option', '1', JText::_('COFI_DISCUSSION') ),
									  	JHTML::_('select.option', '2', JText::_('COFI_QUESTION') ),
									  	JHTML::_('select.option', '3', JText::_('COFI_IMPORTANT') )
									);
									echo JHTML::_('select.genericlist', $arr, 'type', 'class="inputbox"', 'value', 'text', $this->post->type);
									?>
								</td>
							</tr>
														
							

							<tr>		
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_( 'COFI_STICKY' ).':'; ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<fieldset class="radio">								
										<?php
										$html = JHTML::_('select.booleanlist', 'sticky', 'class="inputbox"', $this->post->sticky);
										echo $html;
										?>
									</fieldset>
								</td>
							</tr>

							<tr>		
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_( 'COFI_LOCKED' ).':'; ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<fieldset class="radio">								
										<?php
										$html = JHTML::_('select.booleanlist', 'locked', 'class="inputbox"', $this->post->locked);
										echo $html;
										?>
									</fieldset>
								</td>
							</tr>

							<tr>		
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_( 'COFI_WAITING_FOR_MODERATION' ).':'; ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<fieldset class="radio">
										<?php
										$html = JHTML::_('select.booleanlist', 'wfm', 'class="inputbox"', $this->post->wfm);
										echo $html;
										?>
									</fieldset>	
								</td>
							</tr>



						</table>
												
						<input type="hidden" name="option" value="com_discussions" />
						<input type="hidden" name="task" value="" />						
						<input type="hidden" name="cid[]" value="<?php echo $this->post->id; ?>" />
						<input type="hidden" name="view" value="post" />
						
						<?php echo JHTML::_('form.token'); ?>
											
				</td>
				
			</tr>
			
		</tbody>
		
	</table>

	</fieldset>
		
</form>



