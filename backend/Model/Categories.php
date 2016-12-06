<?php
namespace Y2Studio\Empresas\Admin\Model;

defined('_JEXEC') or die;

use FOF30\Model\DataModel;
use FOF30\Container\Container;

class Categories extends DataModel
{
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
		// Always load the Filters behaviour
		$this->addBehaviour('Filters');
		$this->belongsToMany('companies', 'Companies@com_empresas', 'empresas_category_id', 'empresas_company_id', '#__empresas_categories_companies','category_id','company_id');
	}
	
	protected function onBeforeBuildQuery(\JDatabaseQuery &$query)
	{
		// Set the default ordering by ID, descending
		if (is_null($this->getState('filter_order', null, 'cmd')) && is_null($this->getState('filter_order_Dir', null, 'cmd')))
		{
			$this->setState('filter_order', 'titulo');
			$this->setState('filter_order_Dir', 'ASC');
		}
	}
	
	// Quando deletar uma categoria, deleto todas as empresas vinculadas a esta categoria
	protected function onBeforeDelete($id)
	{
		if ( (int) $id > 0 ) {			
			
			$db = $this->getDbo();
			$query = $db->getQuery(true);
 
			$conditions = array(
				$db->qn('empresas_company_id') . ' IN ( (SELECT ' . $db->qn('company_id') . ' FROM ' . $db->qn('#__empresas_categories_companies') . ' WHERE ' . $db->qn('category_id') .' = '. $id .') )'
			);
 
			$query->delete($db->qn('#__empresas_companies'));
			$query->where($conditions);
 
			$db->setQuery($query);
 
			$result = $db->execute();
	   }
	}
	
	// Metodo que verifica quando estiver editando se nao trocou o titulo
	public function mesmoTitulo($titulo, $categoriaId)
	{	
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select(array(
				$db->qn('titulo'),
			))->from($db->qn('#__empresas_categories')
			)->where($db->qn('titulo') . ' = ' . "'{$titulo}'"
			)->where($db->qn('empresas_category_id') . ' = ' . $categoriaId);
		return count($db->setQuery($query)->loadRow());	
	}
	
	// Metodo que verifica se ja tem este titulo cadastrado
	public function temOTitulo($titulo)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select(array(
				$db->qn('titulo'),
			))->from($db->qn('#__empresas_categories')
			)->where($db->qn('titulo') . ' = ' . "'{$titulo}'");
		return count($db->setQuery($query)->loadRow());
	}
}