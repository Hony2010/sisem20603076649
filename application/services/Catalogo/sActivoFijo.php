<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sActivoFijo extends MY_Service
{

  public $ActivoFijo = array();
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
    $this->load->model('Catalogo/mActivoFijo');
    $this->load->service('Catalogo/sProducto');
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->ActivoFijo = $this->mActivoFijo->ActivoFijo;
    $this->Producto = $this->sProducto->Producto;
    $this->ActivoFijo = $this->herencia->Heredar($this->Producto, $this->ActivoFijo);
  }

  function ObtenerNumeroFila()
  {
    $resultado = $this->mActivoFijo->ObtenerNumeroFila();
    $total = $resultado[0]['NumeroFila'];
    return $total;
  }

  function Inicializar()
  {
    $this->ActivoFijo["IdMarca"] = 0;
    $this->ActivoFijo["IdModelo"] = 0;
    $this->ActivoFijo["NombreModelo"] = "";
    $this->ActivoFijo["NombreTipoActivo"] = "";
    $this->ActivoFijo["NombreMarca"] = "";
    $this->ActivoFijo["TipoActivo"] = 0;
    $this->ActivoFijo["CodigoTipoPrecio"] = "";
    $this->ActivoFijo["CodigoTipoAfectacionIGV"] = "";
    $this->ActivoFijo["IdUnidadMedida"] = ID_UNIDAD_MEDIDA_UNIDAD;
    $this->ActivoFijo["CodigoTipoSistemaISC"] = CODIGO_TIPO_SISTEMA_ISC_NO_AFECTO;
    $this->ActivoFijo["IdTipoSistemaISC"] = ID_TIPO_SISTEMA_ISC_NO_AFECTO;
    $this->ActivoFijo["IdTipoTributo"] = ID_TIPO_TRIBUTO_IGV;

    $this->ActivoFijo["EstadoProducto"] = 1; //1: SE MUESTRA JSON | 0: NO SE MUESTRA JSON
    $this->ActivoFijo["IndicadorEstadoProducto"] = true;
    return $this->ActivoFijo;
  }


  function ObtenerRangoPagina()
  {
    $data['IdParametroSistema'] = ID_NUM_POR_RANGO_PAGINA_ACTIVOFIJO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $total = $this->ObtenerNumeroFila();
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerNumeroFilaPorConsultaActivoFijo($data)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_ACTIVOFIJO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $total = $this->ObtenerNumeroFila();
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      if (($total % $ValorParametroSistema) > 0) {
        $numeropagina = ($total / $ValorParametroSistema) + 1;
        return intval($numeropagina);
      } else {
        $numeropagina = ($total / $ValorParametroSistema);
        return intval($numeropagina);
      }
    }
  }

  function ObtenerNumeroPagina()
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_ACTIVOFIJO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $total = $this->ObtenerNumeroFila();
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      if (($total % $ValorParametroSistema) > 0) {
        $numeropagina = ($total / $ValorParametroSistema) + 1;
        return intval($numeropagina);
      } else {
        $numeropagina = ($total / $ValorParametroSistema);
        return intval($numeropagina);
      }
    }
  }

  function ObtenerNumeroPaginaPorConsultaActivoFijo($data)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_ACTIVOFIJO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $total = $this->ObtenerNumeroFilaPorConsultaActivoFijo($data);
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      if (($total % $ValorParametroSistema) > 0) {
        $numeropagina = ($total / $ValorParametroSistema) + 1;
        return intval($numeropagina);
      } else {
        $numeropagina = ($total / $ValorParametroSistema);
        return intval($numeropagina);
      }
    }
  }

  function ListarActivosFijo($pagina)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_ACTIVOFIJO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      $inicio = ($pagina * $ValorParametroSistema) - $ValorParametroSistema;
      $resultado = $this->mActivoFijo->ListarActivosFijo($inicio, $ValorParametroSistema);
      foreach ($resultado as $key => $value) {
        $resultado[$key]["IndicadorEstadoProducto"] = ($value["EstadoProducto"] == 0) ? false : true;
      }
      return $resultado;
    }
  }

  function ValidarCodigoActivoFijo($data)
  {
    $codigo = $data["CodigoActivoFijo"];
    if ($codigo == "") {
      return "Debe ingresar el código del Activo Fijo";
    } else if (strlen($codigo) > 10) {
      return "El código debe tener como máximo 10 caracteres";
    } else {
      return "";
    }
  }

  function ValidarAnoActivoFijo($data)
  {
    $ano = $data["Ano"];
    if ($ano == "") {
      return "";
    } else if (!is_numeric($ano)) {
      return "El año debe ser un número.";
    } else {
      return "";
    }
  }

  function ValidarActivoFijo($data)
  {
    $nombre = $this->sProducto->ValidarNombreProducto($data);
    $codigo = $this->ValidarCodigoActivoFijo($data);
    $ano = $this->ValidarAnoActivoFijo($data);

    if ($codigo != "") {
      return $codigo;
    } else if ($nombre != "") {
      return $nombre;
    } else if ($ano != "") {
      return $ano;
    } else {
      return "";
    }
  }

  function ValidarExistenciaCodigoActivoFijoParaInsertar($data)
  {
    $resultado = $this->mActivoFijo->ObtenerCodigoActivoFijoParaInsertar($data);
    if (Count($resultado) > 0) {
      return "Este código ya fue registrado";
    } else {
      return "";
    }
  }

  function ValidarExistenciaCodigoActivoFijoParaActualizar($data)
  {
    $resultado = $this->mActivoFijo->ObtenerCodigoActivoFijoParaActualizar($data);
    if (Count($resultado) > 0) {
      return "Este código ya fue registrado";
    } else {
      return "";
    }
  }

  function ObtenerTributoParaActivoFijo($data)
  {
    $idtributo = "";
    switch ($data["CodigoTipoAfectacionIGV"]) {
      case '10':
        // code...
        $idtributo = ID_TIPO_TRIBUTO_IGV;
        break;
      case '20':
        // code...
        $idtributo = ID_TIPO_TRIBUTO_EXONERADO;
        break;
      case '30':
        // code...
        $idtributo = ID_TIPO_TRIBUTO_INAFECTO;
        break;
      case '40':
        // code...
        $idtributo = ID_TIPO_TRIBUTO_EXPORTACION;
        break;
      default:
        // code...
        $idtributo = ID_TIPO_TRIBUTO_IGV;
        break;
    }

    return $idtributo;
  }

  function InsertarActivoFijo($data)
  {
    $data["CodigoActivoFijo"] = trim($data["CodigoActivoFijo"]);
    $data["NombreLargoProducto"] = trim($data["NombreLargoProducto"]);
    $resultado1 = $this->ValidarActivoFijo($data);
    $resultado2 = $this->ValidarExistenciaCodigoActivoFijoParaInsertar($data);

    if ($resultado1 != "") {
      return $resultado1;
    } else if ($resultado2) {
      return $resultado2;
    } else {
      $data["IdTipoTributo"] = $this->ObtenerTributoParaActivoFijo($data);
      $producto = $this->sProducto->InsertarProducto($data);

      if (is_string($producto) && $producto != "") {
        return $producto;
      } else {
        $data["IdProducto"] = $producto["IdProducto"];
        $resultado = $this->mActivoFijo->InsertarActivoFijo($data);

        return $resultado;
      }
    }
  }

  function ActualizarActivoFijo($data)
  {
    $data["CodigoActivoFijo"] = trim($data["CodigoActivoFijo"]);
    $data["NombreLargoProducto"] = trim($data["NombreLargoProducto"]);
    $resultado1 = $this->ValidarActivoFijo($data);
    $resultado2 = $this->ValidarExistenciaCodigoActivoFijoParaActualizar($data);

    if ($resultado1 != "") {
      return $resultado1;
    } else if ($resultado2) {
      return $resultado2;
    } else {
      $data["IdTipoTributo"] = $this->ObtenerTributoParaActivoFijo($data);
      $data['IndicadorEstado'] = ESTADO_ACTIVO;
      $producto = $this->sProducto->ActualizarProducto($data);

      if (is_string($producto) && $producto != "") {
        return $producto;
      } else {
        $this->mActivoFijo->ActualizarActivoFijo($data);
        return "";
      }
    }
  }

  function BorrarActivoFijo($data)
  {
    $resultadoventa = $this->sProducto->ValidarExistenciaProductoEnDetalleComprobanteVenta($data);
    if ($resultadoventa != "") {
      return $resultadoventa;
    } else {
      $resultado = $this->mProducto->BorrarProducto($data);
      return "";
    }
  }

  function ConsultarActivoFijo($data, $pagina)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_ACTIVOFIJO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      $inicio = ($pagina * $ValorParametroSistema) - $ValorParametroSistema;
      $resultado = $this->mActivoFijo->ConsultarActivoFijo($inicio, $ValorParametroSistema, $data);
      foreach ($resultado as $key => $value) {
        $resultado[$key]["IndicadorEstadoProducto"] = ($value["EstadoProducto"] == 0) ? false : true;
      }
      return $resultado;
    }
  }
  function ObtenerActivoFijoPorCodigoActivoFijo($data)
  {
    $resultado = $this->mActivoFijo->ObtenerActivoFijoPorCodigoActivoFijo($data);
    return $resultado;
  }

  function ObtenerFilaActivoFijoParaJSON($data)
  {
    $activofijo = (array) $this->mActivoFijo->ObtenerActivoFijoPorIdProducto($data);
    $response = array(
      "IdProducto" => $activofijo["IdProducto"],
      "CodigoActivoFijo" => $activofijo["CodigoActivoFijo"],
      "NombreProducto" => $activofijo["NombreProducto"],
      "NombreLargoProducto" => $activofijo["NombreLargoProducto"],
      "IdTipoAfectacionIGV" => $activofijo["IdTipoAfectacionIGV"],
      "CodigoTipoAfectacionIGV" => $activofijo["CodigoTipoAfectacionIGV"],
      "IdTipoSistemaISC" => $activofijo["IdTipoSistemaISC"],
      "CodigoTipoSistemaISC" => $activofijo["CodigoTipoSistemaISC"],
      "IdTipoPrecio" => $activofijo["IdTipoPrecio"],
      "CodigoTipoPrecio" => $activofijo["CodigoTipoPrecio"],
      "IdTipoTributo" => $activofijo["IdTipoTributo"],
      "AbreviaturaUnidadMedida" => $activofijo["AbreviaturaUnidadMedida"]
    );

    return $response;
  }

  // nueva actualizacion de json

  function ObtenerDataJSONFilaActivoFijo($data)
  {
    $activoFijo = (array) $this->mActivoFijo->ObtenerActivoFijoPorIdProducto($data);
    $response = array(
      "IdProducto" => $activoFijo["IdProducto"],
      "NombreProducto" => $activoFijo["NombreProducto"],
      "NombreLargoProducto" => $activoFijo["NombreLargoProducto"],
      "CodigoActivoFijo" => $activoFijo["CodigoActivoFijo"],
      "EstadoProducto" => $activoFijo["EstadoProducto"]
    );
    return $response;
  }

  function PrepararDataJSONActivosFijos()
  {
    $response = array();
    $activosFijos = $this->mActivoFijo->ConsultarActivoFijoParaJSON();
    foreach ($activosFijos as $key => $value) {
      $nueva_fila = array(
        "IdProducto" => $value["IdProducto"],
        "NombreProducto" => $value["NombreProducto"],
        "NombreLargoProducto" => $value["NombreLargoProducto"],
        "CodigoActivoFijo" => $value["CodigoActivoFijo"],
        "EstadoProducto" => $value["EstadoProducto"]
      );

      array_push($response, $nueva_fila);
    }

    return $response;
  }

  function ActualizarProductoJSON($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/productos/' . $data["IdProducto"] . '.json';
    $fila = $this->ObtenerFilaActivoFijoParaJSON($data);

    $fila = array(0 => $fila);
    $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $fila);
    return $resultado;
  }

  function CrearJSONActivoFijoTodos()
  {
    //PARA CREAR EL JSON ActivoFijo
    $url = DIR_ROOT_ASSETS . '/data/activofijo/activosfijos.json';
    $data_json = $this->PrepararDataJSONActivosFijos();
    $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $data_json);

    foreach ($data_json as $key => $value) {
      $this->ActualizarProductoJSON($value);
    }
    return $resultado;
  }

  function InsertarJSONDesdeActivoFijo($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/activofijo/activosfijos.json';
    $fila = $this->ObtenerDataJSONFilaActivoFijo($data);
    $resultado2 = $this->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $fila);

    $resultado = $this->ActualizarProductoJSON($data);
    return $resultado;
  }

  function ActualizarJSONDesdeActivoFijo($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/activofijo/activosfijos.json';
    $fila = $this->ObtenerDataJSONFilaActivoFijo($data);
    $resultado2 = $this->jsonconverter->ActualizarFilaEnArchivoJSON($url, $fila, "IdProducto");

    $resultado = $this->ActualizarProductoJSON($data);
    return $resultado;
  }

  function BorrarJSONDesdeActivoFijo($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/activofijo/activosfijos.json';
    $resultado = $this->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdProducto");

    $url = DIR_ROOT_ASSETS . '/data/productos/' . $data["IdProducto"] . '.json';
    $this->archivo->EliminarArchivo($url);
    return $resultado;
  }
}
