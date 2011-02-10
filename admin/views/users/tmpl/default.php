<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */
  
defined('_JEXEC') or die('Restricted access'); 



$ordering = ( ($this->lists['order'] == 'ordering' || $this->lists['order'] == 'id, ordering'));
?>

<form action="index.php" method="post" name="adminForm">

	<table class="adminform">
	
		<tr>
		
			<td width="100%">
			  	<?php echo JText::_( 'SEARCH' ); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
			</td>
						
		</tr>
		
	</table>



	<table class="adminlist" cellspacing="1">
	
	<thead>
	
		<tr>
			<th width="5"><?php echo JText::_( 'COFI_NUM' ); ?></th>
			
			<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>

			<th class="title" style="text-align: left;"><?php echo JText::_( 'COFI_USERNAME' ); ?></th>

			<th width="10"><?php echo JText::_( 'COFI_POSTS' ); ?></th>

			
			<th width="100" style="text-align: left;"><?php echo JText::_( 'COFI_TITLE' ); ?></th>

			<th width="100" style="text-align: left;"><?php echo JText::_( 'COFI_COUNTRY' ); ?></th>


			<th width="10"><?php echo JText::_( 'COFI_SHOW_STATUS' ); ?></th>

			<th width="10"><?php echo JText::_( 'COFI_MODERATOR' ); ?></th>

			<th width="10"><?php echo JText::_( 'COFI_EMAIL' ); ?></th>
			
			<th width="10"><?php echo JText::_( 'COFI_APPROVAL' ); ?></th>

			
			<th width="10"><?php echo JText::_( 'COFI_MODERATED' ); ?></th>

			<th width="10"><?php echo JText::_( 'COFI_ROOKIE' ); ?></th>

			<th width="10"><?php echo JText::_( 'COFI_TRUSTED' ); ?></th>

			<th width="10"><?php echo JText::_( 'COFI_IMAGES' ); ?></th>

			<th width="10"><?php echo JText::_( 'ID' ); ?></th>

		</tr>
		
	</thead>



	<tfoot>
	
		<tr>
		
			<td colspan="15">
			
				<?php echo $this->pageNav->getListFooter(); ?>
				
			</td>
			
		</tr>
		
	</tfoot>



	<tbody>

		<?php		
		
		$k = 0;
		$i = 0;
		$n = count( $this->rows );
		
		$rows = &$this->rows;
		
		foreach ( $rows as $row) {
				
			$id = JHTML::_('grid.id', $i, $row->id); 
			$published = JHTML::_('grid.published', $row, $i);	
			
			$link 	= JRoute::_( 'index.php?option=com_discussions&view=user&task=edit&cid[]='. $row->id );
				
			?>

			<tr class="<?php echo "row$k"; ?>">
			
				<td>
					<?php 
					echo $i + 1 + $this->pageNav->limitstart;
					?>
				</td>
				
				<td>
					<?php 
					echo $id; 
					?>
				</td>
																							
				<td>
					
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'COFI_EDIT_USER' );?>::<?php echo $this->escape($row->username); ?>">
						<a href="<?php echo $link; ?>"><?php echo $row->username; ?></a>
					</span>
					
				</td>

				<td align="left">
					<?php 
					echo $row->posts; 
					?>
				</td>
				
				
				<td align="left">
					<?php 
					echo $row->title; 
					?>
				</td>

				<td align="left">
					<?php 
					echo $row->country; 
					?>
				</td>

				<td align="center">										
					<?php 					
					if ( $row->show_online_status) {
						echo "<img src='images/tick.png' width='16' height='16' border='0' />";
					}
					else {
						echo "<img src='images/publish_x.png' width='16' height='16' border='0' />";
					}										
					?>
				</td>

				<td align="center">										
					<?php 					
					if ( $row->moderator) {
						echo "<img src='images/tick.png' width='16' height='16' border='0' />";
					}
					else {
						echo "<img src='images/publish_x.png' width='16' height='16' border='0' />";
					}										
					?>
				</td>

				<td align="center">										
					<?php 					
					if ( $row->email_notification) {
						echo "<img src='images/tick.png' width='16' height='16' border='0' />";
					}
					else {
						echo "<img src='images/publish_x.png' width='16' height='16' border='0' />";
					}										
					?>
				</td>

				<td align="center">										
					<?php 					
					if ( $row->approval_notification) {
						echo "<img src='images/tick.png' width='16' height='16' border='0' />";
					}
					else {
						echo "<img src='images/publish_x.png' width='16' height='16' border='0' />";
					}										
					?>
				</td>


				<td align="center">
					<?php 					
					if ( $row->moderated) {
						echo "<img src='images/tick.png' width='16' height='16' border='0' />";
					}
					else {
						echo "<img src='images/publish_x.png' width='16' height='16' border='0' />";
					}										
					?>
				</td>
				
				<td align="center">
					<?php 					
					if ( $row->rookie) {
						echo "<img src='images/tick.png' width='16' height='16' border='0' />";
					}
					else {
						echo "<img src='images/publish_x.png' width='16' height='16' border='0' />";
					}										
					?>
				</td>
				
				<td align="center">
					<?php 					
					if ( $row->trusted) {
						echo "<img src='images/tick.png' width='16' height='16' border='0' />";
					}
					else {
						echo "<img src='images/publish_x.png' width='16' height='16' border='0' />";
					}										
					?>
				</td>

				<td align="center">
					<?php 					
					if ( $row->images) {
						echo "<img src='images/tick.png' width='16' height='16' border='0' />";
					}
					else {
						echo "<img src='images/publish_x.png' width='16' height='16' border='0' />";
					}										
					?>
				</td>
				
				<td align="center">
					<?php 
					echo $row->id; 
					?>
				</td>

			</tr>
			
			<?php
			$k = 1 - $k;
			$i++;
						
		} 
		?>

	</tbody>

	</table>

	<input type="hidden" name="option" value="com_discussions" />
	<input type="hidden" name="controller" value="users" />
	<input type="hidden" name="view" value="users" />
	<input type="hidden" name="boxchecked" value="0" />	
	<input type="hidden" name="task" value="" />
	
	<?php echo JHTML::_( 'form.token' ); ?>
	
</form>


