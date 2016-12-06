<?php
namespace Y2Studio\Empresas\Site\View\Companies;

defined('_JEXEC') or die;

use Y2Studio\Empresas\Site\Model\Companies;

class Html extends \FOF30\View\DataView\Html
{
	protected function onBeforeBrowse()
	{
		parent::onBeforeBrowse();
		$companiesModel = $this->container->factory->model('Companies')->tmpInstance();
		$app = \JFactory::getApplication('site');
		$componentParams = $app->getParams('com_empresas');
		$limit = $componentParams->get('num_empresas_por_pagina', 5);
		$limitstart	= \JRequest::getVar('limitstart',0,'int');
		$total = count($companiesModel->getData());	
		$companiesAndCategoriesEnabled = $companiesModel->getData($limit, $limitstart);
		// Assign variables		
		$this->items = $companiesAndCategoriesEnabled;
		$this->pagination = new \JPagination($total, $limitstart, $limit);
	}
}