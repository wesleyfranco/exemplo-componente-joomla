<?php
namespace Y2Studio\Empresas\Admin\Controller;

defined('_JEXEC') or die;

use FOF30\Controller\DataController;
use Y2Studio\Empresas\Admin\Helper\Helper;

class Company extends DataController
{
	protected function onBeforeApplySave(&$data)
	{
		if (empty($data['slug'])) {
			// Antes de salvar gero o slug
			$data['slug'] = \JFilterOutput::stringURLSafe($data['nome']);
		}
		if (!empty($data['publish_up'])) {
			$data['publish_up'] = Helper::convertDateToBD($data['publish_up']);
		}
		if (!empty($data['publish_down'])) {
			$data['publish_down'] = Helper::convertDateToBD($data['publish_down']);
		}
		$nome = $data['nome'];
		$localizacao = $data['localizacao'];
		$imagem = $data['imagem'];
		$categoriaId = (int) $data['empresas_category_id'];
		$empresaId = (int) $data['empresas_company_id'];
		$inicioPublicacao = $data['publish_up'];
		$fimPublicacao = $data['publish_down'];
		$companiesModel = $this->getModel();
		
		// Se tiver editando a empresa
		if ($empresaId > 0) {				
			$eAMesmaCategoria = $companiesModel->mesmoNomeMesmaCategoria($nome, $empresaId, $categoriaId);	
		}
		// Se for um novo registro ou nao for o mesmo nome e a mesma categoria eu valido os dados
		if (!isset($eAMesmaCategoria) or $eAMesmaCategoria < 1) {
			$temEmpresaNestaCategoria = $companiesModel->temAEmpresaNestaCategoria($nome, $categoriaId);
			// Se ja tem esta empresa cadastrada na mesma categoria
			if ($temEmpresaNestaCategoria > 0) {
				// Seto os campos do formulario com os dados enviados
				$companiesModel->setFieldValue('nome', $nome);
				$companiesModel->setFieldValue('localizacao', $localizacao);
				$companiesModel->setFieldValue('imagem', $imagem);
				$companiesModel->setFieldValue('empresas_category_id', $categoriaId);
				$companiesModel->setFieldValue('publish_up', $inicioPublicacao);
				$companiesModel->setFieldValue('publish_down', $fimPublicacao);
				throw new \RuntimeException(\JText::_('COM_EMPRESAS_COMPANY_EXISTE'));
			}
		}
	}
	
	/**
	* @param $data
	* @param $result
	*/
	protected function onAfterApplySave(&$data, $result)
	{
	   if ($result)
	   {
		  $this->syncRelations($data);
	   }
	}

	/**
	 * @param $data
	 *
	 * @throws \FOF30\Model\DataModel\Relation\Exception\RelationNotFound
	 */
	protected function syncRelations($data)
	{
	   $model     = $this->getModel();
	   $relations = $model->getRelations()->getRelationNames();

	   // Sync each model relation
	   foreach ($relations as $relationName)
	   {
		  $relation = $model->getRelations()->getRelation($relationName);

		  // Process only many to many relations
		  if ($relation instanceof \FOF30\Model\DataModel\Relation\BelongsToMany)
		  {
			 //$ids = (array) $data[$relationName];
			$ids = (array) $data['empresas_category_id'];
			 
			// Reset relation records
			$model->$relationName->reset();


			 // Load the foreign items
			 $relationModel = $relation->getForeignModel();
			 $newItems      = $relationModel->where($relationModel->getIdFieldName(), 'in', $ids)->get();


			 // Attach the given items
			 foreach ($newItems as $newItem)
			 {
				$model->$relationName->add($newItem);
			 }

			 // Sync the relation
			 $model->getRelations()->save($relationName);
		  }
	   }
	}
}