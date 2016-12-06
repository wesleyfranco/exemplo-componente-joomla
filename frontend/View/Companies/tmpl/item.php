<?php 
if (isset($_SERVER['HTTP_REFERER']))
{
	$goBackURL  = $_SERVER['HTTP_REFERER'];
}
?>
<?php if (!empty($this->item->imagem)) : ?>
	<img src="<?= $this->item->imagem; ?>" style="width: 200px; height: 200px"><hr>
<?php endif; ?>
<?= $this->item->nome; ?><br>
<?= $this->item->localizacao; ?><br>
<?php foreach($this->item->categories as $category): ?>
	<?= $category->titulo; ?>
<?php endforeach; ?>
<br><br>
<?php if (!empty($goBackURL)) : ?>
	<a href="<?= $goBackURL ?>">Voltar</a>
<?php endif; ?>	
