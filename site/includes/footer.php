<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */
 
require_once(JPATH_COMPONENT.DS.'classes/helper.php');
$CofiHelper = new CofiHelper();

// get parameters
//$params = &$mainframe->getParams();
$params =& JComponentHelper::getParams('com_discussions');

$_showFooter = $params->get('showFooter', '1');
?>

<div class="cofiFooter">

	<?php
	if ( $_showFooter == '1' ) { // display codingfish footer
		?>
			Discussions v<?php echo $CofiHelper->getVersion(); ?>
			<br />
			(c) 2010-2011 <a href="http://www.codingfish.com" target="_blank" title="Codingfish">Codingfish</a>
		<?php
	}
	else {
		?>
		<!--
		<?php echo "Discussions v" . $CofiHelper->getVersion(); ?>
		http://www.codingfish.com
		-->
		<?php
	}
	?>

</div>
