<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sOtraVenta extends MY_Service
{

  public $OtraVenta = array();
  public $Producto = array();

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
    $this->load->model('Catalogo/mOtraVenta');
    $this->load->service('Catalogo/sProducto');
    $this->OtraVenta = $this->mOtraVenta->OtraVenta;
    $this->Producto = $this->sProducto->Producto;
    $this->OtraVenta = $this->herencia->Heredar($this->Producto, $this->OtraVenta);
  }

  function Inicializar()
  {
    $this->OtraVenta['NombreTipoAfectacionIGV'] = "";
    $this->OtraVenta['NombreTipoProducto'] = "";
    $this->OtraVenta['CodigoTipoSistemaISC'] = "";
    $this->OtraVenta['CodigoTipoPrecio'] = "";
    $this->OtraVenta['CodigoTipoAfectacionIGV'] = "";
    $this->OtraVenta['IdTipoAfectacionIGV'] = ID_AFECTACION_IGV_GRAVADO;
    $this->OtraVenta['CodigoTipoSistemaISC'] = CODIGO_TIPO_SISTEMA_ISC_NO_AFECTO;
    $this->OtraVenta['IdTipoSistemaISC'] = ID_TIPO_SISTEMA_ISC_NO_AFECTO;
    $this->OtraVenta['IdUnidadMedida'] = ID_UNIDAD_MEDIDA_UNIDAD;
    $this->OtraVenta["IdTipoTributo"] = ID_TIPO_TRIBUTO_IGV;

    $this->OtraVenta["EstadoProducto"] = 1; //1: SE MUESTRA JSON | 0: NO SE MUESTRA JSON
    $this->OtraVenta["IndicadorEstadoProducto"] = true;

    return $this->OtraVenta;
  }

  function ListarOtrasVenta()
  {
    $resultado = $this->mOtraVenta->ListarOtrasVenta();
    foreach ($resultado as $key => $value) {
      $resultado[$key]["IndicadorEstadoProducto"] = ($value["EstadoProducto"] == 0) ? false : true;
    }
    return $resultado;
  }

  function ValidarOtraVenta($data)
  {
    return "";
  }

  function ObtenerTributoParaOtraVenta($data)
  {
    $idtributo = "";
    switch ($data["CodigoTipoAfectacionIGV"]) {
      case '10':
        $idtributo = ID_TIPO_TRIBUTO_IGV;
        break;
      case '20':
        $idtributo = ID_TIPO_TRIBUTO_EXONERADO;
        break;
      case '30':
        $idtributo = ID_TIPO_TRIBUTO_INAFECTO;
        break;
      case '40':
        $idtributo = ID_TIPO_TRIBUTO_EXPORTACION;
        break;
      default:
        $idtributo = ID_TIPO_TRIBUTO_IGV;
        break;
    }

    return $idtributo;
  }

  function InsertarOtraVenta($data)
  {
    $data["NombreLargoProducto"] = trim($data["NombreLargoProducto"]);
    $resultado = $this->ValidarOtraVenta($data);

    if ($resultado != "") {
      return $resultado;
    } else {
      $data["IdTipoTributo"] = $this->ObtenerTributoParaOtraVenta($data);
      $producto = $this->sProducto->InsertarProducto($data);

      if (is_string($producto) && $producto != "") {
        return $producto;
      } else {
        $data["IdProducto"] = $producto["IdProducto"];
        $resultado = $this->mOtraVenta->InsertarOtraVenta($data);

        return $resultado;
      }
    }
  }

  function ActualizarOtraVenta($data)
  {
    $data["NombreLargoProducto"] = trim($data["NombreLargoProducto"]);
    $resultado = $this->ValidarOtraVenta($data);

    if ($resultado != "") {
      return $resultado;
    } else {
      $data["IdTipoTributo"] = $this->ObtenerTributoParaOtraVenta($data);
      $data['IndicadorEstado'] = ESTADO_ACTIVO;
      $producto = $this->sProducto->ActualizarProducto($data);

      if (is_string($producto) && $producto != "") {
        return $producto;
      } else {
        $resultado = $this->mOtraVenta->ActualizarOtraVenta($data);
        return "";
      }
    }
  }

  function BorrarOtraVenta($data)
  {
    $resultadoventa = $this->sProducto->ValidarExistenciaProductoEnDetalleComprobanteVenta($data);
    if ($resultadoventa != "") {
      return $resultadoventa;
    } else {
      $resultado = $this->sProducto->BorrarProducto($data);
      return "";
    }
  }

  function ObtenerOtraVentaPorIdProducto($data)
  {
    $resultado = $this->mOtraVenta->ObtenerOtraVentaPorIdProducto($data);
    return $resultado;
  }

  function ObtenerFilaOtraVentaParaJSON($data)
  {
    $otraventa = (array) $this->mOtraVenta->ObtenerOtraVentaPorIdProducto($data);
    $response = array(
      "IdProducto" => $otraventa["IdProducto"],
      "CodigoOtraVenta" => $otraventa["CodigoOtraVenta"],
      "NombreProducto" => $otraventa["NombreProducto"],
      "NombreLargoProducto" => $otraventa["NombreLargoProducto"],
      "IdTipoAfectacionIGV" => $otraventa["IdTipoAfectacionIGV"],
      "CodigoTipoAfectacionIGV" => $otraventa["CodigoTipoAfectacionIGV"],
      "IdTipoSistemaISC" => $otraventa["IdTipoSistemaISC"],
      "CodigoTipoSistemaISC" => $otraventa["CodigoTipoSistemaISC"],
      "IdTipoPrecio" => $otraventa["IdTipoPrecio"],
      "CodigoTipoPrecio" => $otraventa["CodigoTipoPrecio"],
      "IdTipoTributo" => $otraventa["IdTipoTributo"],
      "AbreviaturaUnidadMedida" => $otraventa["AbreviaturaUnidadMedida"]
    );

    return $response;
  }

  // nueva actualizacion de json

  function ObtenerDataJSONFilaOtraVenta($data)
  {
    $otraVenta = (array) $this->mOtraVenta->ObtenerOtraVentaPorIdProducto($data);
    $response = array(
      "IdProducto" => $otraVenta["IdProducto"],
      "NombreProducto" => $otraVenta["NombreProducto"],
      "NombreLargoProducto" => $otraVenta["NombreLargoProducto"],
      "CodigoOtraVenta" => $otraVenta["CodigoOtraVenta"],
      "EstadoProducto" => $otraVenta["EstadoProducto"]
    );
    return $response;
  }

  function PrepararDataJSONOtrasVentas()
  {
    $response = array();
    $otrasVenta = $this->mOtraVenta->ConsultarOtraVentaParaJSON();
    foreach ($otrasVenta as $key => $value) {
      $nueva_fila = array(
        "IdProducto" => $value["IdProducto"],
        "NombreProducto" => $value["NombreProducto"],
        "NombreLargoProducto" => $value["NombreLargoProducto"],
        "CodigoOtraVenta" => $value["CodigoOtraVenta"],
        "EstadoProducto" => $value["EstadoProducto"]
      );

      array_push($response, $nueva_fila);
    }

    return $response;
  }

  function ActualizarProductoJSON($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/productos/' . $data["IdProducto"] . '.json';
    $fila = $this->ObtenerFilaOtraVentaParaJSON($data);

    $fila = array(0 => $fila);
    $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $fila);
    return $resultado;
  }

  function CrearJSONOtraVentaTodos()
  {
    //PARA CREAR EL JSON OtrasVenta
    $url = DIR_ROOT_ASSETS . '/data/otraventa/otrasventas.json';
    $data_json = $this->PrepararDataJSONOtrasVentas();
    $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $data_json);

    foreach ($data_json as $key => $value) {
      $this->ActualizarProductoJSON($value);
    }
    return $resultado;
  }

  function InsertarJSONDesdeOtraVenta($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/otraventa/otrasventas.json';
    $fila = $this->ObtenerDataJSONFilaOtraVenta($data);
    $resultado2 = $this->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $fila);

    $resultado = $this->ActualizarProductoJSON($data);
    return $resultado;
  }

  function ActualizarJSONDesdeOtraVenta($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/otraventa/otrasventas.json';
    $fila = $this->ObtenerDataJSONFilaOtraVenta($data);
    $resultado2 = $this->jsonconverter->ActualizarFilaEnArchivoJSON($url, $fila, "IdProducto");

    $resultado = $this->ActualizarProductoJSON($data);
    return $resultado;
  }

  function BorrarJSONDesdeOtraVenta($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/otraventa/otrasventas.json';
    $resultado = $this->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdProducto");

    $url = DIR_ROOT_ASSETS . '/data/productos/' . $data["IdProducto"] . '.json';
    $this->archivo->EliminarArchivo($url);
    return $resultado;
  }
}
