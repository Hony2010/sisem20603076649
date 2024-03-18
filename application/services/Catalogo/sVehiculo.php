<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sVehiculo extends MY_Service
{

  public $Vehiculo = array();
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->library('archivo');
    $this->load->library('jsonconverter');
    $this->load->model('Catalogo/mVehiculo');

    $this->Vehiculo = $this->mVehiculo->Vehiculo;
  }

  function Inicializar()
  {
    $this->Vehiculo["IdVehiculo"] = "";
    $this->Vehiculo["NumeroPlaca"] = "";
    $this->Vehiculo["IdRadioTaxiActual"] = ID_RADIO_TAXI_NO_ESPECIFICADO;
    $this->Vehiculo["UltimoKilometraje"] = "";
    $this->Vehiculo["VehiculoNuevo"] = $this->Vehiculo;
    return $this->Vehiculo;
  }

  function ObtenerNumeroFila()
  {
    $resultado=$this->mVehiculo->ObtenerNumeroFila();
    $total=$resultado[0]['NumeroFila'];
    return $total;
  }

  function ObtenerNumeroPagina()
  {
    $data['IdParametroSistema']= ID_NUM_POR_PAGINA_CLIENTE;
    $total = $this->ObtenerNumeroFila();
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      if (($total%$ValorParametroSistema)>0)
      {
        $numeropagina = ($total/$ValorParametroSistema)+1;
        return intval($numeropagina);
      }
      else
      {
        $numeropagina = ($total/$ValorParametroSistema);
        return intval($numeropagina);
      }
    }
  }

  function ObtenerNumeroFilasPorPagina()
  {
    $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_CLIENTE;
    $parametro=$this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
    $numerofilasporpagina=$parametro->ValorParametroSistema;
    return $numerofilasporpagina;
  }

  function ObtenerNumeroTotalVehiculos($data)
  {
      $resultado = $this->mVehiculo->ObtenerNumeroTotalVehiculos($data)[0]['cantidad'];
      return $resultado;
  }

  function ObtenerNumeroPaginaPorConsultaVehiculo($data)
  {
    $data['IdParametroSistema']= ID_NUM_POR_PAGINA_CLIENTE;
    $total = $this->ObtenerNumeroFilaPorConsultaVehiculo($data);
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      if (($total%$ValorParametroSistema)>0)
      {
        $numeropagina = ($total/$ValorParametroSistema)+1;
        return intval($numeropagina);
      }
      else
      {
        $numeropagina = ($total/$ValorParametroSistema);
        return intval($numeropagina);
      }
    }
  }

  function ListarVehiculos($pagina)
  {
    $data['IdParametroSistema']= ID_NUM_POR_PAGINA_CLIENTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      $inicio = ($pagina*$ValorParametroSistema)-$ValorParametroSistema;
      $resultado = $this->mVehiculo->ListarVehiculos($inicio,$ValorParametroSistema);
      return($resultado);
    }
  }

  function ConsultarVehiculos($data,$pagina)
  {
    $data['IdParametroSistema']= ID_NUM_POR_PAGINA_CLIENTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
        $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
        $inicio = ($pagina*$ValorParametroSistema)-$ValorParametroSistema;
        $resultado=$this->mVehiculo->ConsultarVehiculos($inicio,$ValorParametroSistema,$data);
        return $resultado;
    }
  }

  function ListaVehiculos()
  {
    $resultado = $this->mVehiculo->ListaVehiculos();
    return $resultado;
  }

  function InsertarVehiculo($data)
  {
    $resultado = $this->mVehiculo->InsertarVehiculo($data);
    $json = $this->InsertarJSONDesdeVehiculo($resultado);
    return $resultado;
  }

  function ActualizarVehiculo($data)
  {
    $resultado = $this->mVehiculo->ActualizarVehiculo($data);
    $json = $this->ActualizarJSONDesdeVehiculo($resultado);
    return $resultado;
  }

  function ActualizarVehiculoDesdeVenta($data)
  {
    if(array_key_exists("IdVehiculo", $data))
    {
      if(is_numeric($data["IdVehiculo"]))
      {
        $dataVehiculo["IdVehiculo"] = $data["IdVehiculo"];
        $dataVehiculo["IdRadioTaxiActual"] = $data["IdRadioTaxi"];
        $dataVehiculo["UltimoKilometraje"] = $data["KilometrajeVehiculo"];
        $resultado = $this->ActualizarVehiculo($dataVehiculo);
        return $resultado;
      }
    }
    return $data;
  }

  function ValidarVehiculo($data, $option = false) //false: true:
  {
    $data["NumeroPlaca"] = trim($data["NumeroPlaca"]);
    $duplicado = ($option) ? $this->mVehiculo->ObtenerVehiculoPorNumeroPlacaActualizar($data) : $this->mVehiculo->ObtenerVehiculoPorNumeroPlaca($data);
    if($data["NumeroPlaca"] == "")
    {
      return "El Numero de Placa no puede estar vacio.";
    }
    elseif(!empty($duplicado))
    {
      return "Ya existe un vehiculo con esa placa.";
    }
    elseif($data["UltimoKilometraje"] < 0)
    {
      return "El kilometraje no puede ser negativo.";
    }
    else{
      return "";
    }
  }

  function InsertarVehiculoDesdeVehiculo($data)
  {
    $data["UltimoKilometraje"] = (is_string($data["UltimoKilometraje"])) ? str_replace(',',"",$data["UltimoKilometraje"]) : $data["UltimoKilometraje"];
    $validacion = $this->ValidarVehiculo($data);
    if($validacion != "")
    {
      return $validacion;
    }
    else
    {
      $resultado = $this->InsertarVehiculo($data);
      return $resultado;
    }
  }

  function ActualizarVehiculoDesdeVehiculo($data)
  {
    $data["UltimoKilometraje"] = (is_string($data["UltimoKilometraje"])) ? str_replace(',',"",$data["UltimoKilometraje"]) : $data["UltimoKilometraje"];
    $validacion = $this->ValidarVehiculo($data, true);
    if($validacion != "")
    {
      return $validacion;
    }
    else
    {
      $resultado = $this->ActualizarVehiculo($data);
      return $resultado;
    }
  }

  function BorrarVehiculo($data)
  {
    $resultado = $this->mVehiculo->BorrarVehiculo($data);
    $json = $this->BorrarJSONDesdeVehiculo($resultado);
    return $resultado;
  }

  function AgregarVehiculo($data)
  {
    $resultado = $this->mVehiculo->ObtenerVehiculoPorNumeroPlaca($data);
    if(empty($resultado))
    {
      $data["IdVehiculo"] = "";
      $resultado = $this->InsertarVehiculo($data);
      return $resultado;
    }
    else
    {
      return $resultado;
    }
  }

  function AgregarVehiculoDesdeVenta($data)
  {
    // $data["IdVehiculo"] = ;
    $data["IdRadioTaxiActual"] = $data["IdRadioTaxi"];
    // $data["NumeroPlaca"] = "";
    $data["UltimoKilometraje"] = $data["KilometrajeVehiculo"];
    $resultado = $this->AgregarVehiculo($data);
    return $resultado;
  }

  function PrepararFilaVehiculoParaJSON($data)
  {
    $response = array(
      "IdVehiculo" => $data["IdVehiculo"],
      "IdRadioTaxiActual" => $data["IdRadioTaxiActual"],
      "NumeroPlaca" => $data["NumeroPlaca"],
      "UltimoKilometraje" => $data["UltimoKilometraje"]
    );
    return $response;
  }

  function CrearJSONVehiculoTodos()
  {
    $url = DIR_ROOT_ASSETS . '/data/vehiculos/vehiculos.json';
    $data_json = $this->mVehiculo->ListaVehiculos();
    foreach ($data_json as $key => $value) {
      $response = $this->PrepararFilaVehiculoParaJSON($value);
      $data_json[$key] = $response;
    }
    
    $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $data_json);
    return $resultado;
  }
  // function AgregarVehiculoJSON($data)
  // {
  //   $url = DIR_ROOT_ASSETS . '/data/vehiculos/' . $data["IdVehiculo"] . '.json';
  //   $vehiculo = $this->mVehiculo->ObtenerVehiculoPorIdVehiculo($data);

  //   $fila = $this->PrepararFilaVehiculoParaJSON($vehiculo);
  //   $fila = array(0 => $fila);
  //   $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $fila);
  //   return $resultado;
  // }

  // function CrearJSONVehiculoTodos()
  // {
  //   $resultado = $this->mVehiculo->ListarVehiculos();
  //   foreach ($resultado as $key => $value) {
  //     $response = $this->AgregarVehiculoJSON($value);
  //     $resultado[$key] = $response;
  //   }
  //   return $resultado;
  // }

  // function BorrarJSONDesdeVehiculo($data)
  // {
  //   $url = DIR_ROOT_ASSETS . '/data/vehiculos/' . $data["IdVehiculo"] . '.json';
  //   $this->archivo->EliminarArchivo($url);
  //   return $data;
  // }

  //PARA VEHICULO AVANZADO
  function InsertarJSONDesdeVehiculo($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/vehiculos/vehiculos.json';
    $vehiculo = $this->mVehiculo->ObtenerVehiculoPorIdVehiculo($data);
    $fila = $this->PrepararFilaVehiculoParaJSON($vehiculo);
    $resultado = $this->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $fila);
    return $resultado;
  }

  function ActualizarJSONDesdeVehiculo($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/vehiculos/vehiculos.json';
    $vehiculo = $this->mVehiculo->ObtenerVehiculoPorIdVehiculo($data);
    $fila = $this->PrepararFilaVehiculoParaJSON($vehiculo);
    $resultado = $this->jsonconverter->ActualizarFilaEnArchivoJSON($url, $fila, "IdVehiculo");
    return $resultado;
  }

  function BorrarJSONDesdeVehiculo($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/vehiculos/vehiculos.json';
    $resultado = $this->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdVehiculo");
    return $resultado;
  }

}
