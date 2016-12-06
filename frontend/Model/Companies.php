<?php
namespace Y2Studio\Empresas\Site\Model;

defined('_JEXEC') or die;

class Companies extends \Y2Studio\Empresas\Admin\Model\Companies
{
	public function getData($limit = NULL, $limitstart = NULL)
	{
		// Pego todas as empresas que estejam habilitadas, 
		// que a data de publicacao seja menor ou igual a data de hoje 
		// e a data de fim da publicacao seja maior ou igual a de hoje
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query
			->select(array(
				$db->qn('e') . '.*',
				$db->qn('c') . '.titulo'
			))
			->from($db->qn('#__empresas_companies') . ' AS ' . $db->qn('e'))
			->join('INNER', $db->qn('#__empresas_categories_companies') . ' AS ' . $db->qn('cc') . ' ON ' .
				$db->qn('e') . '.' . $db->qn('empresas_company_id') . ' = ' .
				$db->qn('cc') . '.' . $db->qn('company_id'))
			->join('INNER', $db->qn('#__empresas_categories') . ' AS ' . $db->qn('c') . ' ON ' .
				$db->qn('c') . '.' . $db->qn('empresas_category_id') . ' = ' .
				$db->qn('cc') . '.' . $db->qn('category_id')	
			)
			->where($db->qn('e') . '.' . $db->qn('enabled') . ' = 1')
			->where($db->qn('c') . '.' . $db->qn('enabled') . ' = 1')
			->where($db->qn('e') . '.' . $db->qn('publish_up') . " <= NOW()")
			->where('('.$db->qn('e') . '.' . $db->qn('publish_down') . " = '0000-00-00 00:00:00' OR " . $db->qn('e') . '.' . $db->qn('publish_down') . " >= NOW()".')')
			->where('('.$db->qn('e') . '.' . $db->qn('publish_down') . ' >= ' . $db->qn('e') . '.' . $db->qn('publish_up'). ' OR ' . $db->qn('e') . '.' . $db->qn('publish_down') . " = '0000-00-00 00:00:00' " . ')')
			->where($db->qn('c') . '.' . $db->qn('publish_up') . " <= NOW()")
			->where('('.$db->qn('c') . '.' . $db->qn('publish_down') . " = '0000-00-00 00:00:00' OR " . $db->qn('c') . '.' . $db->qn('publish_down') . " >= NOW()".')')
			->where('('.$db->qn('c') . '.' . $db->qn('publish_down') . ' >= ' . $db->qn('c') . '.' . $db->qn('publish_up'). ' OR ' . $db->qn('c') . '.' . $db->qn('publish_down') . " = '0000-00-00 00:00:00' " . ')')
			->order($db->qn('e') . '.nome ASC');
			if (!is_null($limit) and !is_null($limitstart)) {
				$query->setLimit($limit, $limitstart);
			}	
		return $db->setQuery($query)->loadObjectList();
	}
}