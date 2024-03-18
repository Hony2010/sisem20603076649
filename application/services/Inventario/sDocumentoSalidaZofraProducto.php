<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDocumentoSalidaZofraProducto extends MY_Service {

  public $DocumentoSalidaZofraProducto = array();

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
    $this->load->model('Inventario/mDocumentoSalidaZofraProducto');
    $this->load->model('Catalogo/mMercaderia');

    $this->DocumentoSalidaZofraProducto = $this->mDocumentoSalidaZofraProducto->DocumentoSalidaZofraProducto;
  }

  function Cargar()
  {

  }

  // function BorrarDocumentoSalidaZofraProductoNotaSalida($data)
  // {
  //   $productoalmacen = $this->mDocumentoSalidaZofraProducto->ObtenerDocumentoSalidaZofraProductoPorProductoAlmacen($data);
  //
  //   if(count($productoalmacen)>0)
  //   {
  //     $nuevacantidad = $productoalmacen[0]["StockDocumentoSalidaZofra"] + $data["CantidadSalida"];
  //     $nueva_data["IdDocumentoSalidaZofraProducto"] = $productoalmacen[0]["IdDocumentoSalidaZofraProducto"];
  //     $nueva_data["StockDocumentoSalidaZofra"] = $nuevacantidad;
  //     $actualizacion = $this->ActualizarDocumentoSalidaZofraProducto($nueva_data);
  //     return $actualizacion;
  //   }
  // }
  //
  // function BorrarDocumentoSalidaZofraProductoNotaEntrada($data)
  // {
  //   $productoalmacen = $this->mDocumentoSalidaZofraProducto->ObtenerDocumentoSalidaZofraProductoPorProductoAlmacen($data);
  //
  //   if(count($productoalmacen)>0)
  //   {
  //     $nuevacantidad = $productoalmacen[0]["StockDocumentoSalidaZofra"] - $data["CantidadEntrada"];
  //     $nueva_data["IdDocumentoSalidaZofraProducto"] = $productoalmacen[0]["IdDocumentoSalidaZofraProducto"];
  //     $nueva_data["StockDocumentoSalidaZofra"] = $nuevacantidad;
  //     $actualizacion = $this->ActualizarDocumentoSalidaZofraProducto($nueva_data);
  //     return $actualizacion;
  //   }
  // }

  function AgregarDocumentoSalidaZofraProductoNotaSalida($data)
  {

    $productoalmacen = $this->mDocumentoSalidaZofraProducto->ObtenerDocumentoSalidaZofraProductoPorId($data);

    if(count($productoalmacen)>0)
    {
      $nuevacantidad = $productoalmacen[0]["StockDocumentoSalidaZofra"] - $data["CantidadSalida"];
      $nueva_data["IdDocumentoSalidaZofraProducto"] = $productoalmacen[0]["IdDocumentoSalidaZofraProducto"];
      $nueva_data["StockDocumentoSalidaZofra"] = $nuevacantidad;
      $actualizacion = $this->ActualizarDocumentoSalidaZofraProducto($nueva_data);
      $actualizacion['IdDocumentoSalidaZofra'] = $productoalmacen[0]["IdDocumentoSalidaZofra"];
      return $actualizacion;
    }
    else {
      $nuevacantidad = 0 - $data["CantidadSalida"];
      $data["StockDocumentoSalidaZofra"] = $nuevacantidad;

      $insercion = $this->InsertarDocumentoSalidaZofraProducto($data);
      // $actualizacion['IdDocumentoSalidaZofra'] = $productoalmacen[0]["IdDocumentoSalidaZofra"];
      return $insercion;
    }

  }

  /*Se normaliza para inventario inicial*/
  function ActualizarDocumentoSalidaZofraProductoInventarioInicial($data)
  {
    $productoalmacen = $this->mDocumentoSalidaZofraProducto->ObtenerDocumentoSalidaZofraProductoPorProductoAlmacen($data);

    if(count($productoalmacen)>0)
    {

      $nuevacantidad = $productoalmacen[0]["StockDocumentoSalidaZofra"] - $data["CantidadEntrada"];
      $nueva_data["IdDocumentoSalidaZofraProducto"] = $productoalmacen[0]["IdDocumentoSalidaZofraProducto"];
      $nueva_data["StockDocumentoSalidaZofra"] = $nuevacantidad;

      $actualizacion = $this->ActualizarDocumentoSalidaZofraProducto($nueva_data);
    }
  }
  /*Fin Normalizacion*/

  /*Se normaliza para nota entrada*/
  function ActualizarDocumentoSalidaZofraProductoNotaEntrada($data)
  {
    $productoalmacen = $this->mDocumentoSalidaZofraProducto->ObtenerDocumentoSalidaZofraProductoPorProductoAlmacen($data);

    if(count($productoalmacen)>0)
    {

      $nuevacantidad = $productoalmacen[0]["StockDocumentoSalidaZofra"] - $data["CantidadEntrada"];
      $nueva_data["IdDocumentoSalidaZofraProducto"] = $productoalmacen[0]["IdDocumentoSalidaZofraProducto"];
      $nueva_data["StockDocumentoSalidaZofra"] = $nuevacantidad;

      $actualizacion = $this->ActualizarDocumentoSalidaZofraProducto($nueva_data);
    }
  }
  /*Fin Normalizacion*/

  function ActualizarDocumentoSalidaZofraProductoNotaSalida($data)
  {
    $productoalmacen = $this->mDocumentoSalidaZofraProducto->ObtenerDocumentoSalidaZofraProductoPorProductoAlmacen($data);
    if(count($productoalmacen)>0)
    {
      $nuevacantidad = $productoalmacen[0]["StockDocumentoSalidaZofra"] + $data["CantidadSalida"];
      $nueva_data["IdDocumentoSalidaZofraProducto"] = $productoalmacen[0]["IdDocumentoSalidaZofraProducto"];
      $nueva_data["StockDocumentoSalidaZofra"] = $nuevacantidad;
      $actualizacion = $this->ActualizarDocumentoSalidaZofraProducto($nueva_data);
    }
  }

  function BorrarDocumentosSalidaZofraProducto($data)
  {
    $productoalmacen = $this->mDocumentoSalidaZofraProducto->ObtenerDocumentoSalidaZofraProductoPorProductoAlmacen($data);
    if(count($productoalmacen)>0)
    {
      $this->BorrarDocumentoSalidaZofraProducto($productoalmacen[0]);
    }
  }

  /*Se normaliza para Nota Entrada*/
  function AgregarDocumentoSalidaZofraProductoNotaEntrada($data)
  {
    $productoalmacen = $this->mDocumentoSalidaZofraProducto->ObtenerDocumentoSalidaZofraProductoPorProductoAlmacen($data);
    if(count($productoalmacen)>0)
    {
      $nuevacantidad = $productoalmacen[0]["StockDocumentoSalidaZofra"] + $data["CantidadEntrada"];
      $nueva_data["IdDocumentoSalidaZofraProducto"] = $productoalmacen[0]["IdDocumentoSalidaZofraProducto"];
      $nueva_data["StockDocumentoSalidaZofra"] = $nuevacantidad;

      $actualizacion = $this->ActualizarDocumentoSalidaZofraProducto($nueva_data);
      $actualizacion["Estado"] = 1; //Actualizacion
      return $actualizacion;
    }
    else {
      $nuevacantidad = 0 + $data["CantidadEntrada"];
      $data["StockDocumentoSalidaZofra"] = $nuevacantidad;

      $insercion = $this->InsertarDocumentoSalidaZofraProducto($data);
      $insercion["Estado"] = 0; //Insercion
      return $insercion;
    }
  }
  /*Fin Normalizacion*/

  /*Se normaliza para inventario inicial*/
  function AgregarDocumentoSalidaZofraProductoInventarioInicial($data)
  {
    $productoalmacen = $this->mDocumentoSalidaZofraProducto->ObtenerDocumentoSalidaZofraProductoPorProductoAlmacen($data);

    if(count($productoalmacen)>0)
    {
      $nuevacantidad = $productoalmacen[0]["StockDocumentoSalidaZofra"] + ($data["CantidadInicial"] - $data["AnteriorInicial"]);
      $nueva_data["IdDocumentoSalidaZofraProducto"] = $productoalmacen[0]["IdDocumentoSalidaZofraProducto"];
      $nueva_data["StockDocumentoSalidaZofra"] = $nuevacantidad;
      $actualizacion = $this->ActualizarDocumentoSalidaZofraProducto($nueva_data);
      return $actualizacion;
    }
    else {
      $nuevacantidad = 0 + $data["CantidadInicial"] - $data["AnteriorInicial"];
      $data["StockDocumentoSalidaZofra"] = $nuevacantidad;

      $insercion = $this->InsertarDocumentoSalidaZofraProducto($data);
      return $insercion;
    }
  }
  /*fin inventario inicial*/

  function InsertarDocumentoSalidaZofraProducto($data)
  {
    try {

      $resultadoValidacion = "";

      if($resultadoValidacion == "")
      {
        $resultado= $this->mDocumentoSalidaZofraProducto->InsertarDocumentoSalidaZofraProducto($data);

        return $resultado;
      }
      else
      {
        $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
        return $resultado;
      }
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function ActualizarDocumentoSalidaZofraProducto($data)
  {
    try {
      $resultadoValidacion = "";

      if($resultadoValidacion == "")
      {
        $resultado=$this->mDocumentoSalidaZofraProducto->ActualizarDocumentoSalidaZofraProducto($data);

        return $resultado;
      }
      else
      {
        throw new Exception(nl2br($resultadoValidacion));
      }
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function BorrarDocumentoSalidaZofraProducto($data)
  {
    $this->mDocumentoSalidaZofraProducto->BorrarDocumentoSalidaZofraProducto($data);

    return "";
  }

  // function BorrarMovimientosAlmacenNotaEntrada($data)
  // {
  //   $resultado = $this->mDocumentoSalidaZofraProducto->ObtenerMovimientosPorNotaEntrada($data);
  //   if(count($resultado) > 0)
  //   {
  //     foreach ($resultado as $key => $value) {
  //       // code...
  //       $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);
  //
  //       $value["SaldoFisico"] = $mercaderia["SaldoFisico"] - $value["CantidadEntrada"];
  //       $actualizar = $this->mMercaderia->ActualizarMercaderia($value);
  //
  //       $data_movimiento["IdDocumentoSalidaZofraProducto"] = $value["IdDocumentoSalidaZofraProducto"];
  //       $this->mDocumentoSalidaZofraProducto->BorrarDocumentoSalidaZofraProducto($data_movimiento);
  //     }
  //   }
  //
  //   return $resultado;
  // }

  function BorrarMovimientosAlmacenNotaSalida($data)
  {
    $resultado = $this->mDocumentoSalidaZofraProducto->ObtenerMovimientosPorNotaSalida($data);

    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        // code...
        $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);

        $value["SaldoFisico"] = $mercaderia["SaldoFisico"] + $value["CantidadSalida"];
        $actualizar = $this->mMercaderia->ActualizarMercaderia($value);

        $data_movimiento["IdDocumentoSalidaZofraProducto"] = $value["IdDocumentoSalidaZofraProducto"];
        $this->mDocumentoSalidaZofraProducto->BorrarDocumentoSalidaZofraProducto($data_movimiento);
      }
    }

    return $resultado;
  }

  function InsertarInventarioInicialEnDocumentoSalidaZofraProducto($data)
  {
    $inicial = $this->ValidarProductoInventarioInicial($data);
    if(is_array($inicial))
    {
      $salida = $this->ReordenarMovimientosAlmacenPoProducto($inicial);
      if($salida)
      {
        $salida["SaldoFisico"] = $salida["CantidadInicial"];
        $this->mMercaderia->ActualizarMercaderia($salida);
      }
      return $salida;
    }
    else {
      $resultado = $this->InsertarDocumentoSalidaZofraProductoInventarioInicial($data);
      if($resultado)
      {
        $this->mMercaderia->ActualizarMercaderia($resultado);
      }
      return $resultado;
    }
  }


  function ProductosEnDocumentoSalidaZofraProducto()
  {
    $resultado = $this->mDocumentoSalidaZofraProducto->ProductosEnDocumentoSalidaZofraProducto();
    return $resultado;
  }

  function SedesPorProductoEnDocumentoSalidaZofraProducto($data)
  {
    $resultado = $this->mDocumentoSalidaZofraProducto->SedesPorProductoEnDocumentoSalidaZofraProducto($data);
    return $resultado;
  }

  function ValidarProductoInventarioInicial($data)
  {
    $resultado = $this->mDocumentoSalidaZofraProducto->ConsularProductoInventarioInicial($data);
    if(count($resultado) > 0)
    {
      $this->BorrarDocumentoSalidaZofraProducto($resultado[0]);
      $nuevo = $this->InsertarDocumentoSalidaZofraProductoInventarioInicial($data);
      $data["IdDocumentoSalidaZofraProducto"] = $nuevo["IdDocumentoSalidaZofraProducto"];
      return $data;
    }
    else {
      // code...
      return "";
    }
  }

  function ConsultarListasDocumentoSalidaZofraPorIdProducto($data)
  {
    $resultado = $this->mDocumentoSalidaZofraProducto->ConsultarListasDocumentoSalidaZofraPorIdProducto($data);
    $response = array();
    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        $lista["IdDocumentoSalidaZofraProducto"] = $value["IdDocumentoSalidaZofraProducto"];
        $lista["IdAsignacionSede"] = $value["IdAsignacionSede"];
        $lista["IdProducto"] = $value["IdProducto"];
        $lista["IdDocumentoSalidaZofra"] = $value["IdDocumentoSalidaZofra"];
        $lista["StockDocumentoSalidaZofra"] = $value["StockDocumentoSalidaZofra"];
        $lista["NumeroDocumentoSalidaZofra"] = $value["NumeroDocumentoSalidaZofra"];
        array_push($response, $lista);
      }
    }
    return $response;
  }

  function ValidarProductoEnDocumentoSalidaZofraProductoParaMercaderia($data)
  {
    $resultado = $this->mDocumentoSalidaZofraProducto->ValidarProductoEnDocumentoSalidaZofraProductoParaMercaderia($data);
    $response = count($resultado);
    return $response;
  }

}
