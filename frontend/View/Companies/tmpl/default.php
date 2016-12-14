<?php

defined('_JEXEC') or die();

if (count($this->items) < 1) : ?>

	<?php echo JText::_('COM_EMPRESAS_COMMON_NORECORDS') ?>

<?php else: ?>

<div id="empresas" class="lista-empresas">	
	<ul>		
		<?php foreach($this->items as $companies): ?>
			<?php
				$urlCompany = JRoute::_('index.php?option=com_empresas&view=item&catid=' . $companies->catSlug . '&id=' . $companies->empresas_company_id . '-' . $companies->slug); 
			?>
			<li><a href="<?= $urlCompany ?>"><?= $companies->nome; ?></a></li>
			<li><?= $companies->localizacao; ?></li>
			<li><?= $companies->titulo; ?></li><hr>
		<?php endforeach; ?>
	</ul>
	<?= $this->pagination->getListFooter(); ?>	
</div>

<?php endif; ?>