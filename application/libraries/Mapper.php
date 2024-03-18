<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapper {

	public function map($origen,$destino,$ignore = null) {
		$resultado = [];
		foreach($destino as $key=>$value) {
			if(array_key_exists($key,$origen)) {
				if($origen[$key]!==null) {
						$resultado[$key] = mb_strtoupper($origen[$key]);
				}
			}
		}

		if($ignore != null) {
			foreach ($ignore as $key => $value) {
				$resultado[$key] = $origen[$key];
			}
		}

		return $resultado;
	}

	public function map_real($origen,$destino) {
		$resultado = [];
		foreach($destino as $key=>$value) {
			if(array_key_exists($key,$origen)) {
				if($origen[$key]!==null) {
						$resultado[$key] = $origen[$key];
				}
			}
		}

		return $resultado;
	}

}
