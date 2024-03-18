<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sParametroSistema extends MY_Service
{

  public $ParametroSistema = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->model('Configuracion/General/mParametroSistema');
    $this->ParametroSistema = $this->mParametroSistema->ParametroSistema;
  }

  function ObtenerParametroSistemaPorId($data)
  {
    $resultado = $this->mParametroSistema->ObtenerParametroSistemaPorId($data);
    $indicador = $resultado[0]->IndicadorEstado;
    $nombre = $resultado[0]->NombreParametroSistema;
    if ($indicador == ESTADO_ELIMINADO) {
      return ("Este parámetro fue eliminado: " . $nombre . " / Llamar al administrador del sistema");
    } else {
      return $resultado;
    }
  }

  function ObtenerParametroSistemaPorIdGrupo($data)
  {
    $resultado = $this->mParametroSistema->ObtenerParametroSistemaPorIdGrupo($data);
    foreach ($resultado as $parametro) {
      $indicador = $parametro->IndicadorEstado;
      $nombre = $parametro->NombreParametroSistema;
      if ($indicador == ESTADO_ELIMINADO) {
        return ("Este parámetro fue eliminado: " . $nombre . " / Llamar al administrador del sistema");
      }
    }
    return $resultado;
  }

  function ObtenerParametroSistemaPorIdGrupoCarpeta($data)
  {
    $resultado = $this->mParametroSistema->ObtenerParametroSistemaPorIdGrupo($data);
    $data_carpeta = array();
    foreach ($resultado as $parametro) {
      $data_carpeta[$parametro->NombreParametroSistema] = $parametro->ValorParametroSistema;
    }
    return $data_carpeta;
  }

  function ObtenerNumeroFila()
  {
    $resultado = $this->mParametroSistema->ObtenerNumeroFila();
    return $resultado;
  }

  function ObtenerNumeroPagina()
  {
    $total = $this->ObtenerNumeroFila();

    if (($total % NUM_FILAS_POR_PAGINA) > 0) {
      $numeropagina = ($total / NUM_FILAS_POR_PAGINA) + 1;
      return intval($numeropagina);
    } else {
      $numeropagina = ($total / NUM_FILAS_POR_PAGINA);
      return intval($numeropagina);
    }
  }

  function ListarParametrosSistema($pagina)
  {
    $total = $this->ObtenerNumeroFila();
    $inicio = ($pagina * NUM_FILAS_POR_PAGINA) - NUM_FILAS_POR_PAGINA;
    $resultado = $this->mParametroSistema->ListarParametrosSistema($inicio);
    return ($resultado);
  }

  function ValidarNombreParametroSistema($data)
  {
    $nombre = $data["NombreParametroSistema"];
    if ($nombre == "") {
      return "Debe ingresar el nombre del Parámetro de Sistema";
    } else {
      return "";
    }
  }

  function ValidarValorParametroSistema($data)
  {
    $valor = $data["ValorParametroSistema"];
    /*if (!is_numeric($valor))
          {
            return "Debe ingresar un valor numérico";
          }
          else if ($valor == "")
          {
            return "Debe ingresar el valor del Parámetro de Sistema";
          }
          else
          {
            return "";
          }*/
    return "";
  }

  function ValidarParametroSistema($data)
  {
    $nombre = $this->ValidarNombreParametroSistema($data);
    //$valor = $this->ValidarValorParametroSistema($data);

    if ($nombre != "") {
      return $nombre;
    } else {
      /*if ($valor != "")
            {
              return $valor;
            }
            else
            {
              return "";
            }*/
      return "";
    }
  }

  function InsertarParametroSistema($data)
  {
    $resultado = $this->ValidarParametroSistema($data);

    if ($resultado != "") {
      return $resultado;
    } else {
      $resultado = $this->mParametroSistema->InsertarParametroSistema($data);
      return $resultado;
    }
  }

  function ActualizarParametroSistema($data)
  {
    $resultado = $this->ValidarParametroSistema($data);
    if ($resultado != "") {
      return $resultado;
    } else {
      $resultado = $this->mParametroSistema->ActualizarParametroSistema($data);
      return "";
    }
  }

  function BorrarParametroSistema($data)
  {
    $resultado = $this->mParametroSistema->BorrarParametroSistema($data);
    return $resultado;
  }

  function ConsultarParametroSistema($data)
  {
    $resultado = $this->mParametroSistema->ConsultarParametroSistema($data);
    return $resultado;
  }

  function ObtenerParametroSistemaPorIdParametroSistema($data)
  {
    $resultado = $this->mParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($data);
    return $resultado;
  }

  function ActualizarParametroSistemaPorGrupo($data)
  {
    $parametros = [];
    foreach ($data as $key => $value) {
      $value = ["IdParametroSistema" => $value["IdParametroSistema"], "ValorParametroSistema" => $value["ValorParametroSistema"]];
      $resultado = $this->mParametroSistema->ActualizarParametroSistema($value);
      array_push($parametros, $resultado);
    }
    return $parametros;
  }

    	
	function ObtenerParametrosSistemaPorIdGrupo($data) {      
      $resultado = $this->ObtenerParametroSistemaPorIdGrupo($data);
      $parametros = [];
      foreach ($resultado as $key => $value) {
          $parametros[str_replace(' ', '', $value->NombreParametroSistema)] = $value;
      }

      return $parametros;
  }

  function ObtenerParametroSistemaPorNombreParametroSistema($data) {
    $resultado = $this->mParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);
		return $resultado;
  }
}
