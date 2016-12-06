<?php
    
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

jimport('joomla.form.formfield');

class JFormFieldCategoryList extends JFormField
{
    protected $type = 'CategoryList';

    public function getInput() 
	{
		// Id da empresa vindo da url
		$empresaId = JRequest::getInt('id');
		
		// Pego a categoria desta empresa
		$db = JFactory::getDbo();
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
				$db->qn('cc') . '.' . $db->qn('category_id')	
			)->where($db->qn('e') . '.' . $db->qn('empresas_company_id') . ' = ' . $empresaId);
		$categoria_id = $db->setQuery($query)->loadRow();
		
		// Pego a lista de categorias
		$query2 = $db->getQuery(true);
		$query2->select('empresas_category_id, titulo')->from('`#__empresas_categories`')->order($db->qn('titulo') . ' ASC');
		$rows = $db->setQuery($query2)->loadObjectlist();
		$options[''] = JText::_('COM_EMPRESAS_COMMON_SELECT');
		foreach($rows as $row){
			$options[$row->empresas_category_id] = $row->titulo;
		}
		
		// Retorno a lista de categorias
		return JHTML::_('select.genericlist', $options, $this->name, $attribs = null, 'empresas_category_id', 'titulo', $categoria_id);
	}
}