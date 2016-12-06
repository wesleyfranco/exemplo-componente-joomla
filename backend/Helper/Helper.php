<?php
namespace Y2Studio\Empresas\Admin\Helper;

defined('_JEXEC') or die;

class Helper
{
	public static function convertDateToBD($date)
	{
		$date = explode('-',$date);
		$mes = $date[1];
		$dia = $date[0];
		$anoHora = explode(' ', $date[2]);
		$ano = $anoHora[0];
		//$hora = $anoHora[1];
		$hora = date('H:i:s');
		return $ano . '-' . $mes . '-' . $dia . ' ' . $hora;
	}
	
	public static function convertDateToBR($date)
	{
		$date = explode('-',$date);
		$ano = $date[0];
		$mes = $date[1];
		$diaHora = explode(' ', $date[2]);
		$dia = $diaHora[0];
		$hora = $diaHora[1];
		return $dia . '-' . $mes . '-' . $ano . ' ' . $hora;
	}
}