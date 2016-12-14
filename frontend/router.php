<?php

defined('_JEXEC') or die();

function EmpresasBuildRoute(&$query)
{
   $segments = array();
   if (isset($query['view']))
   {
		$segments[] = $query['view'];
		unset($query['view']);
   }
   if (isset($query['catid']))
   {
		$segments[] = $query['catid'];
		unset($query['catid']);
   }
   if (isset($query['id']))
   {
		$segments[] = $query['id'];
		unset($query['id']);
   }
   return $segments;
}

function EmpresasParseRoute($segments)
{
   $vars = array();
   switch($segments[0])
   {
	   case 'item':
			$vars['view'] = 'company';
			$vars['catid'] = $segments[1];
			$id = explode(':', $segments[2]);
            $vars['id'] = (int) $id[0];
			break;
	}
   return $vars;
}