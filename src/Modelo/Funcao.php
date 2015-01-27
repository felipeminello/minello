<?php
namespace Modelo;

class Funcao {
	public static function post($var) {
		if (isset($_POST[$var]))
			return $_POST[$var];
		else
			return false;
	}
	
	public static function get($var) {
		if (isset($_GET[$var]))
			return $_GET[$var];
		else
			return false;
	}
	
	public static function checkEmail($str) {
		return filter_var($str, FILTER_VALIDATE_EMAIL);
	}
	
	public static function checkString($str) {
		if(is_array($str))
			foreach($str as $k => $v)
				$str[$k] = self::checkString($v);
		elseif(is_string($str))
			$str = filter_var($str, FILTER_SANITIZE_STRING);

		return $str;
	}
	
	public static function decode($str) {
		if(is_array($str))
			foreach($str as $k => $v)
				$str[$k] = self::decode($v);
		elseif(is_string($str))
			$str = utf8_decode($str);

		return $str;
	}
	
	public static function checkInt($str) {
		if(is_array($str))
			foreach($str as $k => $v)
				$str[$k] = self::checkInt($v);
		elseif(is_string($str))
			$str = filter_var($str, FILTER_VALIDATE_INT);

		return $str;
	}
	
	public static function checkData($str, $converter = 0) {
		$str = self::checkString($str);
		$data = substr($str, 0, 10);
		
		if (strpos($data, '/')) {
			$arrayData = explode('/', $data);

			$dia = $arrayData[0];
			$mes = $arrayData[1];
			$ano = $arrayData[2];
		} elseif (strpos($data, '-')) {
			$arrayData = explode('-', $data);
			
			$dia = $arrayData[2];
			$mes = $arrayData[1];
			$ano = $arrayData[0];
		} else {
			return false;
		}
		
		if (checkdate($mes, $dia, $ano)) {
			if ($converter == 1) {
				return $dia.'/'.$mes.'/'.$ano;
			} elseif ($converter == 2) {
				return $ano.'-'.$mes.'-'.$dia;
			} else {
				return $data;
			}
		} else
			return false;
	}
	
	public static function getDiaSemana($data) {
		$data = self::checkData($data, 1);
		
		if (!empty($data)) {
			$time = self::geraTimestamp($data);
			
			return date('N', $time);
		} else {
			return false;
		}
	}
	
	public static function slug($string){
		$string = preg_replace("`\[.*\]`U","",$string);
		$string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$string);
		$string = htmlentities($string, ENT_COMPAT, 'ISO-8859-1');
		$string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string );
		$string = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $string);
		
		return strtolower(trim($string, '-'));
	}
	
}