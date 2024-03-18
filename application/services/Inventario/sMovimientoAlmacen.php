<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sMovimientoAlmacen extends MY_Service {

  public $MovimientoAlmacen = array();
  public $DetalleMovimientoAlmacen = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('shared');
    $this->load->library('sesionusuario');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->library('reporter');
    $this->load->library('imprimir');
    $this->load->helper("date");
    $this->load->model("Base");
    $this->load->model('Inventario/mMovimientoAlmacen');
    $this->load->model('Catalogo/mMercaderia');
    $this->load->service('Inventario/sAlmacenMercaderia');
    $this->load->service("Compra/sDocumentoReferenciaCostoAgregado");
    $this->load->service("Compra/sDocumentoReferenciaCompra");

    $this->MovimientoAlmacen = $this->mMovimientoAlmacen->MovimientoAlmacen;
  }

  function Cargar() {  
    
  }

  function InsertarMovimientoAlmacenNotaSalida($data) {

    $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($data);
    $data["FechaMovimiento"] = convertToDate($data["FechaEmision"]);

    $saldofisico = (array_key_exists("SaldoFisico", $mercaderia) == false || strlen($mercaderia["SaldoFisico"]) == 0) ? 0 : $mercaderia["SaldoFisico"];
    if (is_string($saldofisico)) {
      $saldofisico = str_replace(',', "", $saldofisico);
    }
    if (is_string($data["Cantidad"])) {
      $data["Cantidad"] = str_replace(',', "", $data["Cantidad"]);
    }

    $data["SaldoFisico"] = $saldofisico - $data["Cantidad"];
    $data["CantidadSalida"] = $data["Cantidad"];
    $resultado = $this->InsertarMovimientoAlmacen($data);

    if ($resultado) {
      $movimiento = $this->mMovimientoAlmacen->ObtenerMovimientoAlmacenPorIdMovimientoAlmacen($resultado);
      //CAMBIOS PARA DC - DI - CON IDICADORES EN 1
      if ($movimiento["IndicadorDocumentoIngresoZofra"] != 1) {
        $output = $this->sAlmacenMercaderia->AgregarAlmacenMercaderiaNotaSalida($resultado);
        $actualizar = $this->mMercaderia->ActualizarMercaderia($resultado);
      }
    }

    return $resultado;
  }

  function InsertarMovimientoAlmacenNotaEntrada($data) {
    $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($data);

    $data["FechaMovimiento"] = convertToDate($data["FechaEmision"]);
    $saldofisico = (array_key_exists("SaldoFisico", $mercaderia) == false || strlen($mercaderia["SaldoFisico"]) == 0) ? 0 : $mercaderia["SaldoFisico"];
    if (is_string($saldofisico)) {
      $saldofisico = str_replace(',', "", $saldofisico);
    }
    if (is_string($data["Cantidad"])) {
      $data["Cantidad"] = str_replace(',', "", $data["Cantidad"]);
    }
    
    $data["SaldoFisico"] = $saldofisico + $data["Cantidad"];
    $data["CantidadEntrada"] = $data["Cantidad"];
    $resultado = $this->InsertarMovimientoAlmacen($data);

    if ($resultado) {
      $movimiento = $this->mMovimientoAlmacen->ObtenerMovimientoAlmacenPorIdMovimientoAlmacen($resultado);
      //CAMBIOS PARA DC - DI - CON IDICADORES EN 1
      if ($movimiento["IndicadorDocumentoIngresoZofra"] != 1) {
        //NO INSERTAMOS NADA EN EL ALMACEN, SOLO APLICABLE A COMPRA QUE SON INGRESOS
        $output = $this->sAlmacenMercaderia->AgregarAlmacenMercaderiaNotaEntrada($resultado);
        $actualizar = $this->mMercaderia->ActualizarMercaderia($resultado);
      }
    }

    return $resultado;
  }

  function InsertarMovimientoAlmacenInventarioInicial($data)
  {
    $data["FechaMovimiento"] = convertToDate($data["FechaInventario"]);
    if (is_string($data["CantidadInicial"])) {
      $data["CantidadInicial"] = str_replace(',', "", $data["CantidadInicial"]);
    }
    $data["SaldoFisico"] = $data["CantidadInicial"];
    $data["CantidadEntrada"] = $data["CantidadInicial"];
    $resultado = $this->InsertarMovimientoAlmacen($data);

    return $resultado;
  }

  
  function InsertarMovimientoAlmacen($data) {
    try {
      $resultadoValidacion = "";

      if ($resultadoValidacion == "") {
        $resultado = $this->mMovimientoAlmacen->InsertarMovimientoAlmacen($data);

        return $resultado;

      } else {
        $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
        return $resultado;
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  public function InsertarMovimientoAlmacenDesdeComprobante($data) {
    foreach ($data["DetallesComprobanteVenta"] as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $value["RazonSocial"] = $data["RazonSocial"];
        $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
        $value["NombreAlmacen"] = $data["NombreAlmacen"];
        $value["FechaEmision"] = $data["FechaEmision"];
        $value["IdNotaEntrada"] = $data["IdNotaEntrada"];
        $this->InsertarMovimientoAlmacenNotaEntrada($value);
      }
    }
  }

  public function ActualizarMovimientosAlmacenCostoAgregado($data) {    
    foreach ($data as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $movimiento = $this->sDocumentoReferenciaCostoAgregado->ObtenerMovimientoAlmacenPorCostoAgregadoComprobanteCompra($value);
        if (count($movimiento) > 0) {
          $costounitarioagregado = $movimiento[0]["CostoUnitarioAgregado"] + $value["MontoProrrateadoPorUnidad"];
          $data_actualizar["IdMovimientoAlmacen"] = $movimiento[0]["IdMovimientoAlmacen"];
          $data_actualizar["CostoUnitarioAgregado"] = $costounitarioagregado;
          $this->ActualizarMovimientoAlmacen($data_actualizar);
        }
      }
    }
  }

  public function ActualizarMovimientosAlmacenNotaDebitoCompra($data) {
    foreach ($data as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $movimiento = $this->sDocumentoReferenciaCompra->ObtenerMovimientoAlmacenPorNotaComprobanteCompra($value);
        if (count($movimiento) > 0) {
          $costounitarioagregado = $movimiento[0]["CostoUnitarioAgregado"] + $value["CostoUnitario"];
          $data_actualizar["IdMovimientoAlmacen"] = $movimiento[0]["IdMovimientoAlmacen"];
          $data_actualizar["CostoUnitarioAgregado"] = $costounitarioagregado;
          $this->ActualizarMovimientoAlmacen($data_actualizar);
        }
      }
    }
  }

  public function DescontarParaActualizarMovimientosAlmacenCostoAgregado($data) {
    $documento = $this->sDocumentoReferenciaCostoAgregado->ConsultarDocumentosReferenciaCostoAgregado($data);
    foreach ($documento as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $movimiento = $this->sDocumentoReferenciaCostoAgregado->ObtenerMovimientoAlmacenPorCostoAgregadoComprobanteCompra($value);
        if (count($movimiento) > 0) {
          $costounitarioagregado = $movimiento[0]["CostoUnitarioAgregado"] - $value["MontoProrrateadoPorUnidad"];
          $data_actualizar["IdMovimientoAlmacen"] = $movimiento[0]["IdMovimientoAlmacen"];
          $data_actualizar["CostoUnitarioAgregado"] = $costounitarioagregado;
          $this->ActualizarMovimientoAlmacen($data_actualizar);
        }
      }
    }
    return $documento;
  }

  public function DescontarParaActualizarMovimientosAlmacenNotaDebitoCompra($data) {
    $documento = $this->sDocumentoReferenciaCompra->ConsultarDocumentosReferenciaNotaComprobanteCompra($data);
    foreach ($documento as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $movimiento = $this->sDocumentoReferenciaCompra->ObtenerMovimientoAlmacenPorNotaComprobanteCompra($value);
        if (count($movimiento) > 0) {
          $costounitarioagregado = $movimiento[0]["CostoUnitarioAgregado"] - $value["CostoUnitario"];
          $data_actualizar["IdMovimientoAlmacen"] = $movimiento[0]["IdMovimientoAlmacen"];
          $data_actualizar["CostoUnitarioAgregado"] = $costounitarioagregado;
          $this->ActualizarMovimientoAlmacen($data_actualizar);
        }
      }
    }
    return $documento;
  }

  //SE CREAN FUNCIONES PARA LA ACTUALIZACION DE NOTA CREDITO Y DEBITO
  public function ActualizarMovimientosAlmacenNotaCreditoCompra($data) {
    foreach ($data as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $movimiento = $this->sDocumentoReferenciaCompra->ObtenerMovimientoAlmacenPorNotaComprobanteCompra($value);
        if (count($movimiento) > 0) {
          $costounitarioagregado = $movimiento[0]["CostoUnitarioDeducido"] + $value["CostoItem"];
          $data_actualizar["IdMovimientoAlmacen"] = $movimiento[0]["IdMovimientoAlmacen"];
          $data_actualizar["CostoUnitarioDeducido"] = $costounitarioagregado;
          $this->ActualizarMovimientoAlmacen($data_actualizar);
        }
      }
    }
  }

  public function DescontarParaActualizarMovimientosAlmacenNotaCreditoCompra($data) {
    $documento = $this->sDocumentoReferenciaCompra->ConsultarDocumentosReferenciaNotaComprobanteCompra($data);
    foreach ($documento as $key => $value) {
      if (is_numeric($value["IdProducto"])) {
        $movimiento = $this->sDocumentoReferenciaCompra->ObtenerMovimientoAlmacenPorNotaComprobanteCompra($value);
        if (count($movimiento) > 0) {
          $costounitarioagregado = $movimiento[0]["CostoUnitarioDeducido"] - $value["CostoItem"];
          $data_actualizar["IdMovimientoAlmacen"] = $movimiento[0]["IdMovimientoAlmacen"];
          $data_actualizar["CostoUnitarioDeducido"] = $costounitarioagregado;
          $this->ActualizarMovimientoAlmacen($data_actualizar);
        }
      }
    }
  }

  function ActualizarMovimientoAlmacen($data) {
    try {
      $resultadoValidacion = "";

      if ($resultadoValidacion == "") {
        $resultado = $this->mMovimientoAlmacen->ActualizarMovimientoAlmacen($data);
        return $resultado;
      } else {
        throw new Exception(nl2br($resultadoValidacion));
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function BorrarMovimientosAlmacenNotaEntrada($data)
  {
    $resultado = $this->mMovimientoAlmacen->ObtenerMovimientosPorNotaEntrada($data);
    if (count($resultado) > 0) {
      foreach ($resultado as $key => $value) {
        // code...
        $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);

        $value["SaldoFisico"] = $mercaderia["SaldoFisico"] - $value["CantidadEntrada"];
        $actualizar = $this->mMercaderia->ActualizarMercaderia($value);

        $this->sAlmacenMercaderia->ActualizarAlmacenMercaderiaNotaEntrada($value);

        $data_movimiento["IdMovimientoAlmacen"] = $value["IdMovimientoAlmacen"];
        $this->mMovimientoAlmacen->BorrarMovimientoAlmacen($data_movimiento);
      }
    }

    return $resultado;
  }

  function BorrarMovimientosAlmacenNotaSalida($data)
  {
    $resultado = $this->mMovimientoAlmacen->ObtenerMovimientosPorNotaSalida($data);

    if (count($resultado) > 0) {
      foreach ($resultado as $key => $value) {
        // code...
        $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);

        $value["SaldoFisico"] = $mercaderia["SaldoFisico"] + $value["CantidadSalida"];
        $actualizar = $this->mMercaderia->ActualizarMercaderia($value);

        $this->sAlmacenMercaderia->ActualizarAlmacenMercaderiaNotaSalida($value);

        $data_movimiento["IdMovimientoAlmacen"] = $value["IdMovimientoAlmacen"];
        $this->mMovimientoAlmacen->BorrarMovimientoAlmacen($data_movimiento);
      }
    }

    return $resultado;
  }

  function BorrarMovimientosAlmacenInventarioInicial($data)
  {
    $resultado = $this->mMovimientoAlmacen->ObtenerMovimientosPorInventarioInicial($data);

    if (count($resultado) > 0) {
      foreach ($resultado as $key => $value) {
        // code...
        $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);

        $value["SaldoFisico"] = $mercaderia["SaldoFisico"] + $value["CantidadSalida"];
        $actualizar = $this->mMercaderia->ActualizarMercaderia($value);

        $this->sAlmacenMercaderia->ActualizarAlmacenMercaderiaInventarioInicial($value);

        $data_movimiento["IdMovimientoAlmacen"] = $value["IdMovimientoAlmacen"];
        $this->mMovimientoAlmacen->BorrarMovimientoAlmacen($data_movimiento);
      }
    }

    return $resultado;
  }

  function InsertarInventarioInicialEnMovimientoAlmacen($data)
  {
    $inicial = $this->ValidarProductoInventarioInicial($data);

    if (is_array($inicial)) {
      $salida = $this->ReordenarMovimientosAlmacenPorProducto($inicial);
      
      if (is_numeric($salida)) {
        $data["AnteriorInicial"] = $inicial["AnteriorInicial"];
        $output = $this->sAlmacenMercaderia->AgregarAlmacenMercaderiaInventarioInicial($data);        
        $data["SaldoFisico"] = $salida;
        $this->mMercaderia->ActualizarMercaderia($data);
      }
      return $data;
    } else {
      $resultado = $this->InsertarMovimientoAlmacenInventarioInicial($data);

      if ($resultado) {
        $resultado["CantidadInicial"] = $resultado["CantidadEntrada"];
        $salida = $this->ReordenarMovimientosAlmacenPorProducto($resultado);
        if (is_numeric($salida)) {
          $data["AnteriorInicial"] = 0;
          $output = $this->sAlmacenMercaderia->AgregarAlmacenMercaderiaInventarioInicial($data);

          $resultado["SaldoFisico"] = $salida;
          $this->mMercaderia->ActualizarMercaderia($resultado);
        }
      }
      return $resultado;
    }
  }

  /*Aqui se actualiza en Inventario Inicial y con cambios de producto*/
  function ActualizarInventarioInicialEnMovimientoAlmacen($data)
  {
    $inicial = "";

    $resultado = $this->mMovimientoAlmacen->ConsultarProductoAlmacenInventarioInicial($data);
    if (count($resultado) > 0) {
      $this->BorrarMovimientoAlmacen($resultado[0]);
      $nuevo = $this->InsertarMovimientoAlmacenInventarioInicial($data);
      $data["IdMovimientoAlmacen"] = $nuevo["IdMovimientoAlmacen"];
      $data["AnteriorInicial"] = $resultado[0]["CantidadEntrada"];
      $inicial = $data;
    } else {      
      $inicial = "";
    }
    $salida = $this->ReordenarMovimientosAlmacenPorProducto($inicial);

    if (is_numeric($salida)) {
      $data["AnteriorInicial"] = $inicial["AnteriorInicial"];
      $output = $this->sAlmacenMercaderia->AgregarAlmacenMercaderiaInventarioInicial($data);
      $data["SaldoFisico"] = $salida;
      $this->mMercaderia->ActualizarMercaderia($data);
    }
    return $data;
  }

  function ReordenarMovimientosAlmacenPorProducto($data) {
    $sedes = $this->mMovimientoAlmacen->SedesPorProductoEnMovimientoAlmacen($data);
    $cantidadtotal = 0;
    foreach ($sedes as $key => $value) {
      $data["IdAsignacionSede"] = $value["IdAsignacionSede"];
      $response = $this->mMovimientoAlmacen->ObtenerMovimientosPorProductoSede($data);      
      $cantidadprevia = 0;
      foreach ($response as $key2 => $value2) {
        if ($this->shared->ValidarNuloNumericoYCero($value2["CantidadEntrada"])) {
          $value2["SaldoFisico"] = $cantidadprevia - $value2["CantidadSalida"];
        } else {
          $value2["SaldoFisico"] = $cantidadprevia + $value2["CantidadEntrada"];
        }
        $cantidadprevia = $value2["SaldoFisico"];
        $this->ActualizarMovimientoAlmacen($value2);
      }

      $cantidadtotal += $cantidadprevia;
    }

    return $cantidadtotal;
  }

  //Se recalculan los movimientos en almacen por cantidades
  function CalcularCantidadesProductoPorMovimientoAlmacen($data, $todos = true)
  {
    $producto = $data["IdProducto"];
    $sede = $data["IdAsignacionSede"];
    
    if (is_numeric($producto)) {
      if (is_numeric($sede)) {
        $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data, $todos);
      } else {
        $data1["IdProducto"] = $data["IdProducto"];
        $sedes = $this->SedesPorProductoEnMovimientoAlmacen($data1);
        foreach ($sedes as $key2 => $value2) {
          $data1["IdAsignacionSede"] = $value2["IdAsignacionSede"];
          $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1, $todos);
        }
      }
    } else {
      $productos = $this->ProductosEnMovimientoAlmacen();
      foreach ($productos as $key => $value) {
        if (is_numeric($sede)) {
          $data1["IdProducto"] = $value["IdProducto"];
          $data1["IdAsignacionSede"] = $sede;
          $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1, $todos);
        } else {
          $data1["IdProducto"] = $value["IdProducto"];
          $sedes = $this->SedesPorProductoEnMovimientoAlmacen($data1);
          foreach ($sedes as $key2 => $value2) {
            $data1["IdAsignacionSede"] = $value2["IdAsignacionSede"];
            $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1, $todos);
          }
        }
      }
    }

    return "";
  }

  function RecalcularCantidadesMovimientosAlmacenPorProducto($data, $todos = true)
  {
    if ($todos) {
      $resultado = $this->mMovimientoAlmacen->ObtenerMovimientosPorProductoSede($data);
    } else {
      $resultado = $this->mMovimientoAlmacen->ObtenerMovimientosPorProductoSedeFiltrado($data);
    }

    $cantidadprevia = 0;
    foreach ($resultado as $key => $value) {
      if ($this->shared->ValidarNuloNumericoYCero($value["CantidadEntrada"])) {       
        $value["SaldoFisico"] = $cantidadprevia - $value["CantidadSalida"];
      } else {
        $value["SaldoFisico"] = $cantidadprevia + $value["CantidadEntrada"];
      }
      $cantidadprevia = $value["SaldoFisico"];
      $this->ActualizarMovimientoAlmacen($value);
    }

    return $cantidadprevia;
  }

  function ProductosEnMovimientoAlmacen()
  {
    $resultado = $this->mMovimientoAlmacen->ProductosEnMovimientoAlmacen();
    return $resultado;
  }

  function SedesPorProductoEnMovimientoAlmacen($data)
  {
    $resultado = $this->mMovimientoAlmacen->SedesPorProductoEnMovimientoAlmacen($data);
    return $resultado;
  }

  //PARA ACTUALIZAR EL COSTOUNITARIOFINAL
  function ActualizarCostoUnitarioPromedioEnAlmacenMercaderia($data)
  {
    $almacenmercaderia = $this->sAlmacenMercaderia->ObtenerAlmacenMercaderiaPorProductoAlmacen($data);
    if (count($almacenmercaderia) > 0) {
      $almacenmercaderia[0]["CostoUnitarioPromedio"] = $data["CostoUnitarioPromedio"];
      $this->sAlmacenMercaderia->ActualizarAlmacenMercaderia($almacenmercaderia[0]);
    }
  }

  //Se recalculan cantidades y costos de almacen
  function CalcularProductosPorMovimientoAlmacen($data, $todos = true)
  {
    $producto = $data["IdProducto"];
    $sede = $data["IdAsignacionSede"];
    // $data1 = $data;
    if (is_numeric($producto)) {
      if (is_numeric($sede)) {
        $movimiento = $this->RecalcularValoresMovimientosAlmacenPorProducto($data, $todos);
        if (is_array($movimiento)) {
          $this->ActualizarCostoUnitarioPromedioEnAlmacenMercaderia($movimiento);
        }
      } else {
        $data1["IdProducto"] = $data["IdProducto"];
        $sedes = $this->SedesPorProductoEnMovimientoAlmacen($data1);
        foreach ($sedes as $key2 => $value2) {
          $data1["IdAsignacionSede"] = $value2["IdAsignacionSede"];
          $movimiento = $this->RecalcularValoresMovimientosAlmacenPorProducto($data1, $todos);
          if (is_array($movimiento)) {
            $this->ActualizarCostoUnitarioPromedioEnAlmacenMercaderia($movimiento);
          }
        }
      }
    } else {
      $productos = $this->ProductosEnMovimientoAlmacen();
      foreach ($productos as $key => $value) {
        if (is_numeric($sede)) {
          $data1["IdProducto"] = $value["IdProducto"];
          $data1["IdAsignacionSede"] = $sede;
          $movimiento = $this->RecalcularValoresMovimientosAlmacenPorProducto($data1, $todos);
          if (is_array($movimiento)) {
            $this->ActualizarCostoUnitarioPromedioEnAlmacenMercaderia($movimiento);
          }
        } else {
          $data1["IdProducto"] = $value["IdProducto"];
          $sedes = $this->SedesPorProductoEnMovimientoAlmacen($data1);
          foreach ($sedes as $key2 => $value2) {
            $data1["IdAsignacionSede"] = $value2["IdAsignacionSede"];
            $movimiento = $this->RecalcularValoresMovimientosAlmacenPorProducto($data1, $todos);
            if (is_array($movimiento)) {
              $this->ActualizarCostoUnitarioPromedioEnAlmacenMercaderia($movimiento);
            }
          }
        }
      }
    }

    return "";
  }

  function RecalcularValoresMovimientosAlmacenPorProducto($data, $todos = true)
  {
    if ($todos) {
      $resultado = $this->mMovimientoAlmacen->ObtenerMovimientosPorProductoSede($data);
    } else {
      $resultado = $this->mMovimientoAlmacen->ObtenerMovimientosPorProductoSedeFiltrado($data);
    }

    $cantidadprevia = 0;
    $costounitariocalculado = 0;
    $totalvalorizado = 0;    
    $anterior_fila = null;
    
    foreach ($resultado as $key => $value) {      
      if ($this->shared->ValidarNuloNumericoYCero($value["CantidadEntrada"])) {
          $value["SaldoFisico"] = $cantidadprevia - $value["CantidadSalida"];  
      } else {
          $value["SaldoFisico"] = $cantidadprevia + $value["CantidadEntrada"];
      }
      
      $cantidadprevia = $value["SaldoFisico"];      
      $procesar_costos = true;
      $documento = null;
      if (is_numeric($value["IdNotaSalida"])) {
        //CV: Se toman las facturas y boletas
        //CC: Se toman las Notas de Credito
        $documento = $this->mMovimientoAlmacen->ObtenerMovimientoAlmacenPorNotaSalida($value);

        if ($documento) {
          if ($documento[0]["DescripcionTipoOperacion"] == 'C') {
            $consulta["IdProducto"] = $value["IdProducto"];
            $consulta["IdAsignacionSede"] = $documento[0]["SedeDescripcion"];
            $consulta["IdComprobanteCompra"] = $documento[0]["IdDocumentoReferencia"];
            $resultado = $this->mMovimientoAlmacen->ObtenerMovimientoAlmacenPorNotaEntradaComprobanteCompra($consulta);

            if (count($resultado) > 0) {              
              $value["CostoUnitarioAdquisicion"] = $resultado[0]["CostoUnitarioAdquisicion"];
            }
          } else if ($documento[0]["DescripcionTipoOperacion"] == 'V') {
            if ($anterior_fila != null) {
              $value["CostoUnitarioTotal"] = $anterior_fila["CostoUnitarioPromedio"];
              $procesar_costos = false;
            }
          }
        }
      } else if (is_numeric($value["IdNotaEntrada"])) {
        //CV: Se toman las Notas de Credito
        //CC: Se toman las facturas y boletas
        $documento =  $this->mMovimientoAlmacen->ObtenerMovimientoAlmacenPorNotaEntrada($value);
        if ($documento) {
          if ($documento[0]["DescripcionTipoOperacion"] == 'V') {
            $consulta["IdProducto"] = $value["IdProducto"];
            $consulta["IdAsignacionSede"] = $documento[0]["SedeDescripcion"];
            $consulta["IdComprobanteVenta"] = $documento[0]["IdDocumentoReferencia"];
            $resultado = $this->mMovimientoAlmacen->ObtenerMovimientoAlmacenPorNotaSalidaComprobanteVenta($consulta);
            if (count($resultado) > 0) {
              $value["CostoUnitarioAdquisicion"] = $resultado[0]["CostoUnitarioTotal"];
            }
          } else if ($documento[0]["DescripcionTipoOperacion"] == 'C') {
            //En las compras toma el valor que se le da
          }
        }
      } else if (is_numeric($value["IdInventarioInicial"])) {
        //NO SE REALIZAN PROCESOS AUN
      }

      $costounitario = (!($value["CostoUnitarioAdquisicion"] == '' || is_null($value["CostoUnitarioAdquisicion"]))) ? $value["CostoUnitarioAdquisicion"] : 0;
      $costoagregado = (!($value["CostoUnitarioAgregado"] == '' || is_null($value["CostoUnitarioAgregado"]))) ? $value["CostoUnitarioAgregado"] : 0;
      $costodeducido = (!($value["CostoUnitarioDeducido"] == '' || is_null($value["CostoUnitarioDeducido"]))) ? $value["CostoUnitarioDeducido"] : 0;

      if ($procesar_costos) {
        $costounitariocalculado = ($costounitario + $costoagregado) - $costodeducido;
        $value["CostoUnitarioTotal"] = $costounitariocalculado;
      }

      $valorizadoentrada = 0;
      $valorizadosalida = 0;
      if (is_numeric($value["CantidadEntrada"])) {        
        $costounitariototal = (float) $value["CostoUnitarioTotal"];        
        $valorizadoentrada = $costounitariototal * $value["CantidadEntrada"];
        $value["EntradaValorado"] = $valorizadoentrada;
      } else {        
        $costounitariototal = (float) $value["CostoUnitarioTotal"];
        $valorizadosalida = $costounitariototal * $value["CantidadSalida"];
        $value["SalidaValorado"] = $valorizadosalida;
      }
      $totalvalorizado = ($valorizadoentrada + $totalvalorizado) - $valorizadosalida;
      $value["SaldoValorado"] = $totalvalorizado;

      if ($cantidadprevia == 0) {
        $costopromediounitario = "";
      } else {
        $costopromediounitario = $totalvalorizado / $cantidadprevia;
      }
      $value["CostoUnitarioPromedio"] = $costopromediounitario;

      $this->ActualizarMovimientoAlmacen($value);
      $anterior_fila = $value;
    }

    return $anterior_fila;
  }  

  function ValidarProductoInventarioInicial($data) {
    $resultado = $this->mMovimientoAlmacen->ConsultarProductoAlmacenInventarioInicial($data);
    if (count($resultado) > 0) {
      $this->BorrarMovimientoAlmacen($resultado[0]);
      $nuevo = $this->InsertarMovimientoAlmacenInventarioInicial($data);
      $data["IdMovimientoAlmacen"] = $nuevo["IdMovimientoAlmacen"];
      $data["AnteriorInicial"] = $resultado[0]["CantidadEntrada"];
      return $data;
    } else {
      // code...
      return "";
    }
  }



  //NUEVAS FUNCIONES PARA OBTENER MOVIMIENTOS POR NOTAS
  function ObtenerMovimientosAlmacenNotaEntrada($data)
  {
    $resultado = $this->mMovimientoAlmacen->ObtenerMovimientosPorNotaEntrada($data);

    return $resultado;
  }

  function ObtenerMovimientosAlmacenNotaSalida($data)
  {
    $resultado = $this->mMovimientoAlmacen->ObtenerMovimientosPorNotaSalida($data);

    return $resultado;
  }

  //FUNCION PARA ACTUALIZAR FECHAS
  function ActualizarFechaParaInventariosInicial($data)
  {
    $resultado = $this->mMovimientoAlmacen->ActualizarFechaParaInventariosInicial($data);
    return $resultado;
  }

  //PARA LA LISTA DE PRECIOS
  function RecalcularCostoUnitarioPromedioPorProducto($data)
  {
    $data["IdAsignacionSede"] = "%";
    $todos = false;
    if ($todos) {
      $resultado = $this->mMovimientoAlmacen->ObtenerMovimientosPorProductoSede($data);
    } else {
      $resultado = $this->mMovimientoAlmacen->ObtenerMovimientosPorProductoSedeFiltrado($data);
    }

    $cantidadprevia = 0;
    $costounitariocalculado = 0;
    $totalvalorizado = 0;

    $anterior_fila = null;
    foreach ($resultado as $key => $value) {
      if ($this->shared->ValidarNuloNumericoYCero($value["CantidadEntrada"])) {
        $value["SaldoFisico"] = $cantidadprevia - $value["CantidadSalida"];
      } else {
        $value["SaldoFisico"] = $cantidadprevia + $value["CantidadEntrada"];        
      }
      
      $cantidadprevia = $value["SaldoFisico"];

      $procesar_costos = true;
      $documento = null;
      if (is_numeric($value["IdNotaSalida"])) {
        //CV: Se toman las facturas y boletas
        //CC: Se toman las Notas de Credito
        $documento = $this->mMovimientoAlmacen->ObtenerMovimientoAlmacenPorNotaSalida($value);

        if ($documento) {
          if ($documento[0]["DescripcionTipoOperacion"] == 'C') {
            $consulta["IdProducto"] = $value["IdProducto"];
            $consulta["IdAsignacionSede"] = $documento[0]["SedeDescripcion"];
            $consulta["IdComprobanteCompra"] = $documento[0]["IdDocumentoReferencia"];
            $resultado = $this->mMovimientoAlmacen->ObtenerMovimientoAlmacenPorNotaEntradaComprobanteCompra($consulta);

            if (count($resultado) > 0) {
              // $value["CostoUnitarioAdquisicion"] = $resultado[0]["CostoUnitarioTotal"];
              $value["CostoUnitarioAdquisicion"] = $resultado[0]["CostoUnitarioAdquisicion"];
            }
          } else if ($documento[0]["DescripcionTipoOperacion"] == 'V') {
            if ($anterior_fila != null) {
              $value["CostoUnitarioTotal"] = $anterior_fila["CostoUnitarioPromedio"];
              $procesar_costos = false;
            }
          }
        }
      } else if (is_numeric($value["IdNotaEntrada"])) {
        //CV: Se toman las Notas de Credito
        //CC: Se toman las facturas y boletas
        $documento =  $this->mMovimientoAlmacen->ObtenerMovimientoAlmacenPorNotaEntrada($value);
        if ($documento) {
          if ($documento[0]["DescripcionTipoOperacion"] == 'V') {
            $consulta["IdProducto"] = $value["IdProducto"];
            $consulta["IdAsignacionSede"] = $documento[0]["SedeDescripcion"];
            $consulta["IdComprobanteVenta"] = $documento[0]["IdDocumentoReferencia"];
            $resultado = $this->mMovimientoAlmacen->ObtenerMovimientoAlmacenPorNotaSalidaComprobanteVenta($consulta);
            if (count($resultado) > 0) {
              $value["CostoUnitarioAdquisicion"] = $resultado[0]["CostoUnitarioTotal"];
            }
          } else if ($documento[0]["DescripcionTipoOperacion"] == 'C') {
            //En las compras toma el valor que se le da
          }
        }
      } else if (is_numeric($value["IdInventarioInicial"])) {
        //NO SE REALIZAN PROCESOS AUN
      }

      $costounitario = (!($value["CostoUnitarioAdquisicion"] == '' || is_null($value["CostoUnitarioAdquisicion"]))) ? $value["CostoUnitarioAdquisicion"] : 0;
      $costoagregado = (!($value["CostoUnitarioAgregado"] == '' || is_null($value["CostoUnitarioAgregado"]))) ? $value["CostoUnitarioAgregado"] : 0;
      $costodeducido = (!($value["CostoUnitarioDeducido"] == '' || is_null($value["CostoUnitarioDeducido"]))) ? $value["CostoUnitarioDeducido"] : 0;

      if ($procesar_costos) {
        $costounitariocalculado = ($costounitario + $costoagregado) - $costodeducido;
        $value["CostoUnitarioTotal"] = $costounitariocalculado;
      }

      $valorizadoentrada = 0;
      $valorizadosalida = 0;
      if (is_numeric($value["CantidadEntrada"])) {
        $costounitariototal = (float) $value["CostoUnitarioTotal"];
        $valorizadoentrada = $costounitariototal * $value["CantidadEntrada"];
        $value["EntradaValorado"] = $valorizadoentrada;
      } else {
        $costounitariototal = (float) $value["CostoUnitarioTotal"];
        $valorizadosalida = $costounitariototal * $value["CantidadSalida"];
        $value["SalidaValorado"] = $valorizadosalida;
      }
      $totalvalorizado = ($valorizadoentrada + $totalvalorizado) - $valorizadosalida;
      $value["SaldoValorado"] = $totalvalorizado;

      if ($cantidadprevia == 0) {
        $costopromediounitario = 0;
      } else {
        $costopromediounitario = $totalvalorizado / $cantidadprevia;
      }
      $value["CostoUnitarioPromedio"] = $costopromediounitario;      
      $anterior_fila = $value;
    }

    return $anterior_fila;
  }

  function BorrarMovimientoAlmacen($data) {
    
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();

    $resultado=$this->ActualizarMovimientoAlmacen($data);
    return $resultado;
  }

  function BorrarMovimientosAlmacenPorDetalleTransferenciaAlmacen($data) {
    
    $dataDetalleTransferenciaAlmacen = $data["DetallesTransferenciaAlmacen"][0];    
    $resultadoMovimientosAlmacen = $this->ObtenerMovimientoAlmacenPorIdTransferenciaAlmacenYIdProducto($dataDetalleTransferenciaAlmacen);
    
    if( count($resultadoMovimientosAlmacen) > 0 ) {
    
      foreach($resultadoMovimientosAlmacen as $key => $value) {
        $resultadoMovimientosAlmacen[$key] = $this->BorrarMovimientoAlmacen($value);
      }

      //devolver los saldos origen
      $dataAlmacenMercaderia["IdProducto"] = $dataDetalleTransferenciaAlmacen["IdProducto"];
      $dataAlmacenMercaderia["IdAsignacionSede"] = $data["IdAsignacionSedeOrigen"];
      $dataAlmacenMercaderia["Cantidad"] = $dataDetalleTransferenciaAlmacen["Cantidad"];
      $resultadoAlmacenMercaderia = $this->sAlmacenMercaderia->AumentarStockAlmacenMercaderia($dataAlmacenMercaderia);

      //actualizar los saldos de almacenmercaderia destino
      $dataAlmacenMercaderia["IdAsignacionSede"] = $data["IdAsignacionSedeDestino"];
      $resultadoAlmacenMercaderia = $this->sAlmacenMercaderia->DisminuirStockAlmacenMercaderia($dataAlmacenMercaderia);

      return $resultadoMovimientosAlmacen;
    }
    else {
      return "No existe movimientos de almacen del detalle a procesar.";
    }
    
  }

  function ObtenerMovimientoAlmacenPorIdTransferenciaAlmacenYIdProducto($data) {
    $resultado = $this->mMovimientoAlmacen->ObtenerMovimientoAlmacenPorIdTransferenciaAlmacenYIdProducto($data);
    return $resultado;
  }

  function InsertarMovimientosAlmacenPorDetalleTransferenciaAlmacen($data) {
    //Generar el movimiento de salida como origen    
    $dataDetalleTransferenciaAlmacen=$data["DetallesTransferenciaAlmacen"][0];
    $dataAlmacenMercaderia["IdAsignacionSede"] = $data["IdAsignacionSedeOrigen"];
    $dataAlmacenMercaderia["IdProducto"] = $dataDetalleTransferenciaAlmacen["IdProducto"];
    $dataAlmacenMercaderia["Cantidad"] = $dataDetalleTransferenciaAlmacen["Cantidad"];
    $resultadoAlmacenMercaderia = $this->sAlmacenMercaderia->DisminuirStockAlmacenMercaderia($dataAlmacenMercaderia);
        
    $dataMovimientoAlmacen=$this->MovimientoAlmacen;
    $dataMovimientoAlmacen["IdTransferenciaAlmacen"]=$data["IdTransferenciaAlmacen"];
    $dataMovimientoAlmacen["IdProducto"]=$dataDetalleTransferenciaAlmacen["IdProducto"];
    $dataMovimientoAlmacen["IdAsignacionSede"]=$data["IdAsignacionSedeOrigen"];
    $dataMovimientoAlmacen["CodigoSede"]=$data["CodigoSedeOrigen"];
    $dataMovimientoAlmacen["NombreSede"]=$data["NombreSedeOrigen"];
    $dataMovimientoAlmacen["CodigoTipoDocumento"]=$data["CodigoTipoDocumento"];
    $dataMovimientoAlmacen["FechaMovimiento"] = convertToDate($data["FechaTraslado"]);            
    $dataMovimientoAlmacen["MotivoMovimiento"] = $data["NombreTipoDocumento"];
    $dataMovimientoAlmacen["NombreAlmacen"] = $data["NombreAlmacen"];      
    $dataMovimientoAlmacen["CantidadSalida"] = $dataDetalleTransferenciaAlmacen["Cantidad"];
    $dataMovimientoAlmacen["SaldoFisico"] = $resultadoAlmacenMercaderia["StockMercaderia"];

    $resultado[0] = $this->InsertarMovimientoAlmacen($dataMovimientoAlmacen);
    
    //Generar el movimiento de entrada como destino    
    $dataAlmacenMercaderia["IdAsignacionSede"] = $data["IdAsignacionSedeDestino"];
    $dataAlmacenMercaderia["IdProducto"] = $dataDetalleTransferenciaAlmacen["IdProducto"];
    $dataAlmacenMercaderia["Cantidad"] = $dataDetalleTransferenciaAlmacen["Cantidad"];    
    $resultadoAlmacenMercaderia = $this->sAlmacenMercaderia->AumentarStockAlmacenMercaderia($dataAlmacenMercaderia);
    
    $dataMovimientoAlmacen=$this->MovimientoAlmacen;
    $dataMovimientoAlmacen["IdTransferenciaAlmacen"]=$data["IdTransferenciaAlmacen"];
    $dataMovimientoAlmacen["IdProducto"]=$dataDetalleTransferenciaAlmacen["IdProducto"];
    $dataMovimientoAlmacen["IdAsignacionSede"]=$data["IdAsignacionSedeDestino"];
    $dataMovimientoAlmacen["CodigoSede"]=$data["CodigoSedeDestino"];
    $dataMovimientoAlmacen["NombreSede"]=$data["NombreSedeDestino"];
    $dataMovimientoAlmacen["CodigoTipoDocumento"]=$data["CodigoTipoDocumento"];
    $dataMovimientoAlmacen["FechaMovimiento"] = convertToDate($data["FechaTraslado"]);            
    $dataMovimientoAlmacen["MotivoMovimiento"] = $data["NombreTipoDocumento"];
    $dataMovimientoAlmacen["NombreAlmacen"] = $data["NombreAlmacen"];
    $dataMovimientoAlmacen["CantidadEntrada"] = $dataDetalleTransferenciaAlmacen["Cantidad"];
    $dataMovimientoAlmacen["SaldoFisico"] = $resultadoAlmacenMercaderia["StockMercaderia"];
    
    $resultado[1] = $this->sMovimientoAlmacen->InsertarMovimientoAlmacen($dataMovimientoAlmacen);              

    return $resultado;
  }
  

}
