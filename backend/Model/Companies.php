<?php
namespace Y2Studio\Empresas\Admin\Model;

defined('_JEXEC') or die;

use FOF30\Model\DataModel;
use FOF30\Container\Container;

class Companies extends DataModel
{
	use Mixin\Assertions;
	
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
		// Always load the Filters behaviour
		$this->addBehaviour('Filters');
		$this->belongsToMany('categories', 'Categories@com_empresas', 'empresas_company_id', 'empresas_category_id', '#__empresas_categories_companies','company_id','category_id');
	}

	public function check()
	{		
		$this->assertNotEmpty($this->nome, 'COM_EMPRESAS_COMPANY_ERR_NOME_EMPTY');
		$this->assertNotEmpty($this->localizacao, 'COM_EMPRESAS_COMPANY_ERR_LOCALIZACAO_EMPTY');
		$task = $this->input->get('task');
		// Se o select de categoria nao for nulo ou a acao nao for cancelar valido o campo
		if (!is_null($this->empresas_category_id) and $task !== 'cancel') {
			// Check for categories
			$this->assertNotEmpty($this->empresas_category_id, 'COM_EMPRESAS_COMPANY_ERR_CATEGORIA_EMPTY');
		}
		
		parent::check();		
	}
	
	protected function onBeforeBuildQuery(\JDatabaseQuery &$query)
	{
		$filtro = $this->getState('filter_order', null, 'cmd');
		$ordem = $this->getState('filter_order_Dir', null, 'cmd');

		// Set the default ordering by ID, descending
		if (is_null($ordem) && is_null($filtro))
		{
			$this->setState('filter_order', $this->getIdFieldName());
			$this->setState('filter_order_Dir', 'DESC');
		}		
	}
	
	public function onAfterBuildQuery(\JDatabaseQuery $q, $overrideLimits = false)
	{
		$categoria = $this->getState('titulo', 0, 'int');
		
		$filtro = \JRequest::getString('filter_order');		
		$ordem = \JRequest::getString('filter_order_Dir');
		
		// Se filtrou pela categoria
		if ($categoria > 0)
		{
			$db = $this->getDbo();
			$q->join('INNER', $db->qn('#__empresas_categories_companies') . ' ON ' .
				$db->qn('empresas_company_id') . ' = ' . $db->qn('company_id'))		
			->where(
				$q->qn('category_id') . ' = ' . $categoria
			);
		} else {
			// Se ordenou pela coluna categoria (nome do campo e titulo)
			if($filtro == 'titulo') {
				// Seto o estado do filtro para titulo
				$this->setState('filter_order', $filtro);		
				$this->setState('filter_order_Dir', $ordem);
				
				$db = $this->getDbo();
				$q->join('INNER', $db->qn('#__empresas_categories_companies') . ' AS ' . $db->qn('cc') . ' ON ' .
					$db->qn('empresas_company_id') . ' = ' .
					$db->qn('cc') . '.' . $db->qn('company_id')
				)->join('INNER', $db->qn('#__empresas_categories') . ' AS ' . $db->qn('c') . ' ON ' .
					$db->qn('c') . '.' . $db->qn('empresas_category_id') . ' = ' .
					$db->qn('cc') . '.' . $db->qn('category_id')	
				);
					
				$q->clear('order');
				$q->order("{$filtro} {$ordem}");
			}
		}		
	}
	
	// Metodo que verifica se a pessoa esta editando mais nao mudou o nome nem a categoria
	public function mesmoNomeMesmaCategoria($nome, $empresaId, $categoriaId)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		// Verifico se e o mesmo nome e a mesma categoria, se for, deixo editar e nao valido os dados
		$query
			->select(array(
				$db->qn('empresas_category_company_id'),
			))
			->from($db->qn('#__empresas_categories_companies') . ' AS ' . $db->qn('cc'))		
			->join('INNER', $db->qn('#__empresas_companies') . ' AS ' . $db->qn('e') . ' ON ' .
				$db->qn('e') . '.' . $db->qn('empresas_company_id') . ' = ' .
				$db->qn('cc') . '.' . $db->qn('company_id'))
			->where($db->qn('e') . '.' . $db->qn('nome') . " = '{$nome}'")					
			->where($db->qn('cc') . '.' . $db->qn('company_id') . ' = ' . $empresaId)
			->where($db->qn('cc') . '.' . $db->qn('category_id') . ' = ' . $categoriaId
			);
			echo $query;
		return count($db->setQuery($query)->loadRow());	
	}
	
	// Metodo que verifica se ja tem a mesma empresa cadastrada na mesma categoria
	public function temAEmpresaNestaCategoria($nome, $categoriaId)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query
			->select(array(
				$db->qn('c') . '.' . $db->qn('empresas_category_id') . ' AS categoria_id'  ,
			))
			->from($db->qn('#__empresas_companies') . ' AS ' . $db->qn('e'))
			->join('INNER', $db->qn('#__empresas_categories_companies') . ' AS ' . $db->qn('cc') . ' ON ' .
				$db->qn('e') . '.' . $db->qn('empresas_company_id') . ' = ' .
				$db->qn('cc') . '.' . $db->qn('company_id'))
			->join('INNER', $db->qn('#__empresas_categories') . ' AS ' . $db->qn('c') . ' ON ' .
				$db->qn('c') . '.' . $db->qn('empresas_category_id') . ' = ' .
				$db->qn('cc') . '.' . $db->qn('category_id'))	
			->where($db->qn('e') . '.' . $db->qn('nome') . " = '{$nome}'")
			->where($db->qn('c') . '.' . $db->qn('empresas_category_id') . ' = ' .  $categoriaId
			);
		return count($db->setQuery($query)->loadRow());	
	}
}