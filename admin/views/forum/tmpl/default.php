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
	Joomla.submitbutton = function(pressbutton) {
		var form = document.adminForm;
    	if (pressbutton == 'cancel') {
        	form.action.value = 'cancel'
        	submitform('cancel');
        	return;
    	}
 
    	submitform(pressbutton);
    	return;
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
							<?php echo JText::_('COFI_FORUM_DETAILS');?>
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
										<?php echo JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $this->forum->published); ?>
									</fieldset>
								</td>
							</tr>


							<tr>
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_('COFI_NAME'); ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="text" name="name" id="name" value="<?php echo $this->forum->name; ?>" size="50" maxlength="250" />
								</td>
							</tr>
							
							<tr>
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_('COFI_ALIAS'); ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="text" name="alias" value="<?php echo $this->forum->alias; ?>" size="50" maxlength="250" />
								</td>
							</tr>


							<tr>
								<td valign="top" class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_('COFI_DESCRIPTION'); ?>
									</label>
								</td>
								<td style="padding: 10px;">
					 				<textarea name="description" id="description" rows="5" cols="50" style="width: 100%;"><?php echo $this->forum->description; ?></textarea>									
								</td>
							</tr>


							<tr>
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_('COFI_PARENT'); ?>
									</label>
								</td>
								<td style="padding: 10px;">								
									<?php 									
									echo JHTML::_('select.genericlist', $this->forums, 'parent_id', 'class="inputbox"', 'value', 'text', $this->forum->parent_id);
									?>
								</td>
							</tr>
							
							
							<tr>		
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_( 'COFI_PRIVATE' ).':'; ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<fieldset class="radio">
										<?php echo JHTML::_('select.booleanlist',  'private', 'class="inputbox"', $this->forum->private); ?>
									</fieldset>
								</td>
							</tr>

							<tr>		
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_( 'COFI_MODERATED' ).':'; ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<fieldset class="radio">
										<?php echo JHTML::_('select.booleanlist',  'moderated', 'class="inputbox"', $this->forum->moderated); ?>
									</fieldset>
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
									<label>
										<?php echo JText::_( 'COFI_SHOW_IMAGE' ).':'; ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<fieldset class="radio">
										<?php echo JHTML::_('select.booleanlist',  'show_image', 'class="inputbox"', $this->forum->show_image); ?>
									</fieldset>
								</td>
							</tr>

							<tr>
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_('COFI_IMAGE'); ?>
									</label>
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
									<label>
										<?php echo JText::_('COFI_META_TITLE'); ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="text" name="meta_title" id="meta_title" value="<?php echo $this->forum->meta_title; ?>" size="50" maxlength="250" />
								</td>
							</tr>

							<tr>
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_('COFI_META_DESCRIPTION'); ?>
									</label>
								</td>
								<td style="padding: 10px;">
									<input class="text_area" type="text" name="meta_description" id="meta_description" value="<?php echo $this->forum->meta_description; ?>" size="50" maxlength="250" />
								</td>
							</tr>

							<tr>
								<td class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_('COFI_META_KEYWORDS'); ?>
									</label>
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
									<label>
										<?php echo JText::_('COFI_BANNER_TOP'); ?>
									</label>
								</td>
								<td style="padding: 10px;">
				 					<textarea name="banner_top" id="banner_top" rows="5" cols="50" style="width: 100%;"><?php echo $this->forum->banner_top; ?></textarea>									
								</td>
							</tr>

							<tr>
								<td valign="top" class="key" style="padding: 10px;">
									<label>
										<?php echo JText::_('COFI_BANNER_BOTTOM'); ?>
									</label>
								</td>
								<td style="padding: 10px;">
				 					<textarea name="banner_bottom" id="banner_bottom" rows="5" cols="50" style="width: 100%;"><?php echo $this->forum->banner_bottom; ?></textarea>									
								</td>
							</tr>


						</table>


												
						<input type="hidden" name="option" value="com_discussions" />
						<input type="hidden" name="task" value="" />						
						<input type="hidden" name="cid[]" value="<?php echo $this->forum->id; ?>" />
						<input type="hidden" name="view" value="forum" />
						
						<?php echo JHTML::_('form.token'); ?>
											
				</td>
				
			</tr>
			
		</tbody>
		
	</table>

	</fieldset>
		
</form>



