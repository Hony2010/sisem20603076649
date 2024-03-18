<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sServicio extends MY_Service
{

  public $Servicio = array();
  public $Producto = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('archivo');
    $this->load->library('jsonconverter');
    $this->load->model('Catalogo/mServicio');
    $this->load->service('Catalogo/sProducto');
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->Servicio = $this->mServicio->Servicio;
    $this->Producto = $this->sProducto->Producto;
  }

  function ObtenerNumeroFila()
  {
    $resultado = $this->mServicio->ObtenerNumeroFila();
    $total = $resultado[0]['NumeroFila'];
    return $total;
  }

  function Inicializar()
  {
    $this->Servicio["Foto"] = "";
    $this->Servicio["IdFamiliaProducto"] = 0;
    $this->Servicio["IdSubFamiliaProducto"] = 0;
    $this->Servicio["IdLineaProducto"] = 0;
    $this->Servicio["NombreFamiliaProducto"] = "";
    $this->Servicio["NombreProducto"] = "";
    $this->Servicio["NombreLargoProducto"] = "";
    $this->Servicio["NombreSubFamiliaProducto"] = "";
    $this->Servicio["NombreTipoServicio"] = "";
    $this->Servicio["CodigoTipoPrecio"] = "";
    // $this->Servicio["CodigoTipoAfectacionIGV"] = "";
    $this->Servicio["IdUnidadMedida"] = ID_UNIDAD_MEDIDA_ZZ;
    $this->Servicio["CodigoTipoSistemaISC"] = CODIGO_TIPO_SISTEMA_ISC_NO_AFECTO;
    $this->Servicio["IdTipoSistemaISC"] = ID_TIPO_SISTEMA_ISC_NO_AFECTO;
    $this->Servicio["IdTipoTributo"] = ID_TIPO_TRIBUTO_IGV;
    $this->Servicio["IdTipoServicio"] = ID_TIPO_SERVICIO_SERVICIO;

    $this->Servicio["IdTipoPrecio"] = ID_TIPO_PRECIO_UNITARIO;
    $this->Servicio["IdTipoAfectacionIGV"] = ID_AFECTACION_IGV_GRAVADO;
    $this->Servicio["CodigoTipoAfectacionIGV"] = CODIGO_AFECTACION_IGV_GRAVADO;

    $this->Servicio["CodigoAutomatico"] = 0;

    $this->Servicio["EstadoProducto"] = 1; //1: SE MUESTRA JSON | 0: NO SE MUESTRA JSON
    $this->Servicio["IndicadorEstadoProducto"] = true;

    return $this->Servicio;
  }

  function ObtenerNumeroFilaPorConsultaServicio($data)
  {
    $resultado = $this->mServicio->ObtenerNumeroFilaPorConsultaServicio($data);
    $total = $resultado[0]['NumeroFila'];
    return $total;
  }

  function ObtenerNumeroPagina()
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_SERVICIO;
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

  function ObtenerNumeroFilasPorPagina()
  {
    $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_SERVICIO;
    $parametro=$this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
    $numerofilasporpagina=$parametro->ValorParametroSistema;
    return $numerofilasporpagina;
  }

  function ObtenerNumeroTotalServicios($data)
  {
    $parametroRubroRepuesto = $this->sConstanteSistema->ObtenerParametroRubroRepuesto();
    
    if($parametroRubroRepuesto == 1) { 
      $resultado = $this->mServicio->ObtenerNumeroTotalServiciosAvanzada($data)[0]["cantidad"];
    }
    else {
      $resultado = $this->mServicio->ObtenerNumeroTotalServicios($data)[0]["cantidad"];
    }
      
    return $resultado;
  }  

  function ObtenerNumeroPaginaPorConsultaServicio($data)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_SERVICIO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $total = $this->ObtenerNumeroFilaPorConsultaServicio($data);
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

  function ListarServicios($pagina)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_SERVICIO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      $inicio = ($pagina * $ValorParametroSistema) - $ValorParametroSistema;
      $resultado = $this->mServicio->ListarServicios($inicio, $ValorParametroSistema);
      foreach ($resultado as $key => $value) {
        $resultado[$key]["IndicadorEstadoProducto"] = ($value["EstadoProducto"] == 0) ? false : true;
      }
      return $resultado;
    }
  }

  function ValidarCodigoServicio($data)
  {
    $codigo = $data["CodigoServicio"];
    if ($codigo == "") {
      return "Debe ingresar el código del Servicio";
    } else if (strlen($codigo) > 10) {
      return "El código debe tener como máximo 10 caracteres";
    } else {
      return "";
    }
  }

  function ValidarServicio($data)
  {
    $codigo = $this->ValidarCodigoServicio($data);
    $nombre = $this->sProducto->ValidarNombreProducto($data);

    if ($codigo != "") {
      return $codigo;
    } else if ($nombre != "") {
      return $codigo;
    } else {
      return "";
    }
  }

  function ValidarExistenciaCodigoServicioParaInsertar($data)
  {
    $resultado = $this->mServicio->ObtenerCodigoServicioParaInsertar($data);
    if (Count($resultado) > 0) {
      return "Este código ya fue registrado";
    } else {
      return "";
    }
  }

  function ValidarExistenciaCodigoServicioParaActualizar($data)
  {
    $resultado = $this->mServicio->ObtenerCodigoServicioParaActualizar($data);

    if (Count($resultado) > 0) {
      return "Este código ya fue registrado";
    } else {
      return "";
    }
  }

  function ObtenerTributoParaServicio($data)
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

  function InsertarServicio($data)
  {
    if ($data['CodigoAutomatico'] == '0') {
      $data["CodigoServicio"] = trim($this->ObtenerUltimoCodigoServicio());
    }
    $data["NombreLargoProducto"] = trim($data["NombreLargoProducto"]);
    $resultado1 = $this->ValidarServicio($data);
    $resultado2 = $this->ValidarExistenciaCodigoServicioParaInsertar($data);

    if ($resultado1 != "") {
      return $resultado1;
    } else if ($resultado2) {
      return $resultado2;
    } else {
      $data["IdTipoTributo"] = $this->ObtenerTributoParaServicio($data);
      $producto = $this->sProducto->InsertarProducto($data);

      if (is_string($producto) && $producto != "") {
        return $producto;
      } else {
        $data["IdProducto"] = $producto["IdProducto"];
        $data["NombreProducto"] = $producto["NombreProducto"];
        $resultado = $this->mServicio->InsertarServicio($data);
        $resultado = array_merge($resultado, $producto);
        return $resultado;
      }
    }
  }

  function ActualizarServicio($data)
  {
    $data["CodigoServicio"] = trim($data["CodigoServicio"]);
    $data["NombreLargoProducto"] = trim($data["NombreLargoProducto"]);
    $resultado1 = $this->ValidarServicio($data);
    $resultado2 = $this->ValidarExistenciaCodigoServicioParaActualizar($data);

    if ($resultado1 != "") {
      return $resultado1;
    } else if ($resultado2) {
      return $resultado2;
    } else {
      $data["IdTipoTributo"] = $this->ObtenerTributoParaServicio($data);
      $producto = $this->sProducto->ActualizarProducto($data);

      if (is_string($producto) && $producto != "") {
        return $producto;
      } else {
        $resultado = $this->mServicio->ActualizarServicio($data);
        return "";
      }
    }
  }

  function BorrarServicio($data)
  {
    $resultadoventa = $this->sProducto->ValidarExistenciaProductoEnDetalleComprobanteVenta($data);
    if ($resultadoventa != "") {
      return $resultadoventa;
    } else {
      $resultado = $this->sProducto->BorrarProducto($data);
      return $resultado;
    }
  }

  function ConsultarServicio($data, $pagina)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_SERVICIO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      $inicio = ($pagina * $ValorParametroSistema) - $ValorParametroSistema;
      $resultado = $this->mServicio->ConsultarServicio($inicio, $ValorParametroSistema, $data);
      foreach ($resultado as $key => $value) {
        $resultado[$key]["IndicadorEstadoProducto"] = ($value["EstadoProducto"] == 0) ? false : true;
      }
      return $resultado;
    }
  }

  function ObtenerUrlCarpetaImagenes()
  {
    $data['IdParametroSistema'] = ID_URL_CARPETA_IMAGENES_SERVICIO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerServicioPorIdProducto($data)
  {
    $resultado = $this->mServicio->ObtenerServicioPorIdProducto($data);
    return $resultado;
  }

  function ObtenerServicioPorCodigoServicio($data)
  {
    $resultado = $this->mServicio->ObtenerServicioPorCodigoServicio($data);
    return $resultado;
  }

  function ObtenerFilaServicioParaJSON($data)
  {
    $servicio = (array) $this->mServicio->ObtenerServicioPorIdProducto($data);
    $response = array(
      "IdProducto" => $servicio["IdProducto"],
      "CodigoServicio" => $servicio["CodigoServicio"],
      "NombreProducto" => $servicio["NombreProducto"],
      "NombreLargoProducto" => $servicio["NombreLargoProducto"],
      "IdTipoAfectacionIGV" => $servicio["IdTipoAfectacionIGV"],
      "CodigoTipoAfectacionIGV" => $servicio["CodigoTipoAfectacionIGV"],
      "IdTipoSistemaISC" => $servicio["IdTipoSistemaISC"],
      "CodigoTipoSistemaISC" => $servicio["CodigoTipoSistemaISC"],
      "IdTipoPrecio" => $servicio["IdTipoPrecio"],
      "CodigoTipoPrecio" => $servicio["CodigoTipoPrecio"],
      "PrecioUnitario" => $servicio["PrecioUnitario"],
      "IdTipoTributo" => $servicio["IdTipoTributo"],
      "AbreviaturaUnidadMedida" => $servicio["AbreviaturaUnidadMedida"]
    );

    return $response;
  }
  // nueva actualizacion de json

  function ObtenerDataJSONFilaServicio($data)
  {    
    $servicio = (array) $this->mServicio->ObtenerServicioPorIdProducto($data);
    
    $response = array(
      "IdProducto" => $servicio["IdProducto"],
      "NombreProducto" => $servicio["NombreProducto"],
      "NombreLargoProducto" => $servicio["NombreLargoProducto"],
      "CodigoServicio" => $servicio["CodigoServicio"],
      "EstadoProducto" => $servicio["EstadoProducto"]
    );
    return $response;
  }

  function PrepararDataJSONServicios()
  {
    $response = array();
    $servicios = $this->mServicio->ConsultarServicioParaJSON();
    foreach ($servicios as $key => $value) {
      $nueva_fila = array(
        "IdProducto" => $value["IdProducto"],
        "NombreProducto" => $value["NombreProducto"],
        "NombreLargoProducto" => $value["NombreLargoProducto"],
        "CodigoServicio" => $value["CodigoServicio"],
        "EstadoProducto" => $value["EstadoProducto"]
      );

      array_push($response, $nueva_fila);
    }

    return $response;
  }

  function ActualizarProductoJSON($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/productos/' . $data["IdProducto"] . '.json';
    $fila = $this->ObtenerFilaServicioParaJSON($data);

    $fila = array(0 => $fila);
    $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $fila);
    return $resultado;
  }

  function CrearJSONServicioTodos()
  {
    //PARA CREAR EL JSON Servicios
    $url = DIR_ROOT_ASSETS . '/data/servicio/servicios.json';
    $data_json = $this->PrepararDataJSONServicios();
    $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $data_json);

    foreach ($data_json as $key => $value) {
      $this->ActualizarProductoJSON($value);
    }
    return $resultado;
  }

  function InsertarJSONDesdeServicio($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/servicio/servicios.json';
    $fila = $this->ObtenerDataJSONFilaServicio($data);
    $resultado2 = $this->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $fila);

    $resultado = $this->ActualizarProductoJSON($data);
    return $resultado;
  }

  function ActualizarJSONDesdeServicio($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/servicio/servicios.json';
    $fila = $this->ObtenerDataJSONFilaServicio($data);
    $resultado2 = $this->jsonconverter->ActualizarFilaEnArchivoJSON($url, $fila, "IdProducto");

    $resultado = $this->ActualizarProductoJSON($data);
    return $resultado;
  }

  function BorrarJSONDesdeServicio($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/servicio/servicios.json';
    $resultado = $this->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdProducto");

    $url = DIR_ROOT_ASSETS . '/data/productos/' . $data["IdProducto"] . '.json';
    $this->archivo->EliminarArchivo($url);
    return $resultado;
  }

  function ObtenerUltimoCodigoServicio()
  {
    $resultado = $this->mServicio->ObtenerUltimoCodigoServicio();
    $valor = (int) $resultado[0]['MaximoValor'];
    if ($valor > 0) {
      $nuevoCodigo = $valor + 1;
      return $nuevoCodigo;
    } else {
      return '1';
    }
  }

  function ConsultarServicios($data,$numeropagina,$numerofilasporpagina)
  {
    $numerofilainicio=$numerofilasporpagina * ($numeropagina - 1);

    $parametroRubroRepuesto = $this->sConstanteSistema->ObtenerParametroRubroRepuesto();
    
    if($parametroRubroRepuesto == 1) {
      $resultado = $this->mServicio->ConsultarServicioAvanzada($data,$numerofilainicio,$numerofilasporpagina);
    }
    else {
      $resultado = $this->mServicio->ConsultarServicio($data,$numerofilainicio,$numerofilasporpagina);
    }

    foreach ($resultado as $key => $value) {      
      //$resultado[$key]["IndicadorAfectoICBPER"] = ($value["AfectoICBPER"] == 0) ? false : true;
      $resultado[$key]["IndicadorEstadoProducto"] = ($value["EstadoProducto"] == 0) ? false : true;      
    }
    return $resultado;
  }
}
