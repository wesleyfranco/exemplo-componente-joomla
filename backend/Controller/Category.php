<?php
namespace Y2Studio\Empresas\Admin\Controller;

defined('_JEXEC') or die;

use FOF30\Controller\DataController;
use Y2Studio\Empresas\Admin\Helper\Helper;

class Category extends DataController
{
	protected function onBeforeApplySave(&$data)
	{
		if (empty($data['slug'])) {
			// Antes de salvar gero o slug
			$data['slug'] = \JFilterOutput::stringURLSafe($data['titulo']);
		}
		if (!empty($data['publish_up'])) {
			$data['publish_up'] = Helper::convertDateToBD($data['publish_up']);
		}
		if (!empty($data['publish_down'])) {
			$data['publish_down'] = Helper::convertDateToBD($data['publish_down']);
		}
		$titulo = $data['titulo'];
		$inicioPublicacao = $data['publish_up'];
		$fimPublicacao = $data['publish_down'];
		$categoriaId = (int) $data['empresas_category_id'];
		$categoriesModel = $this->getModel();
		// Se tiver editando a categoria
		if ($categoriaId > 0) {
			$eOMesmoTitulo = $categoriesModel->mesmoTitulo($titulo, $categoriaId);
		}
		// Se for um novo registro ou nao for o mesmo titulo eu valido os dados
		if (!isset($eOMesmoTitulo) or $eOMesmoTitulo < 1) {
			$temCategoria = $categoriesModel->temOTitulo($titulo);
		}
		// Se ja tem esta categoria cadastrada
		if ($temCategoria > 0) {
			$categoriesModel->setFieldValue('titulo', $titulo);
			$categoriesModel->setFieldValue('publish_up', $inicioPublicacao);
			$categoriesModel->setFieldValue('publish_down', $fimPublicacao);
			throw new \RuntimeException(\JText::_('COM_EMPRESAS_CATEGORY_EXISTE'));
		}
	}
}