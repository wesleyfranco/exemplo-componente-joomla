<?php
defined('_JEXEC') or die();
JHtml::_('behavior.tooltip');
JHtml::_('formbehavior.chosen', 'select');
?>

<?php echo $this->getRenderedForm(); ?>