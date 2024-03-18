<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDuaProducto extends MY_Service {

  public $DuaProducto = array();

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
    $this->load->model('Inventario/mDuaProducto');
    $this->load->model('Catalogo/mMercaderia');

    $this->DuaProducto = $this->mDuaProducto->DuaProducto;
  }

  function Cargar()
  {

  }

  function AgregarDuaProductoNotaSalida($data)
  {
    $productoalmacen = $this->mDuaProducto->ObtenerDuaProductoPorId($data);
    if(count($productoalmacen)>0)
    {
      $nuevacantidad = $productoalmacen[0]["StockDua"] - $data["CantidadSalida"];
      $nueva_data["IdDuaProducto"] = $productoalmacen[0]["IdDuaProducto"];
      $nueva_data["StockDua"] = $nuevacantidad;
      $actualizacion = $this->ActualizarDuaProducto($nueva_data);
      $actualizacion['IdDua'] = $productoalmacen[0]["IdDua"];
      return $actualizacion;
    }
    else {
      $nuevacantidad = 0 - $data["CantidadSalida"];
      $data["StockDua"] = $nuevacantidad;

      $insercion = $this->InsertarDuaProducto($data);
      // $insercion['IdDua'] = $productoalmacen[0]["IdDua"];
      return $insercion;
    }

  }

  /*Se normaliza para inventario inicial*/
  function ActualizarDuaProductoInventarioInicial($data)
  {
    $productoalmacen = $this->mDuaProducto->ObtenerDuaProductoPorProductoDua($data);

    if(count($productoalmacen)>0)
    {

      $nuevacantidad = $productoalmacen[0]["StockDua"] - $data["CantidadEntrada"];
      $nueva_data["IdDuaProducto"] = $productoalmacen[0]["IdDuaProducto"];
      $nueva_data["StockDua"] = $nuevacantidad;

      $actualizacion = $this->ActualizarDuaProducto($nueva_data);
    }
  }
  /*Fin Normalizacion*/

  /*Se normaliza para nota entrada*/
  function ActualizarDuaProductoNotaEntrada($data)
  {
    $dataDuaProducto = $this->mDuaProducto->ObtenerDuaProductoPorProductoDua($data);

    if(count($dataDuaProducto)>0)
    {

      $nuevacantidad = $dataDuaProducto[0]["StockDua"] - $data["CantidadEntrada"];
      $nueva_data["IdDuaProducto"] = $dataDuaProducto[0]["IdDuaProducto"];
      $nueva_data["StockDua"] = $nuevacantidad;

      $actualizacion = $this->ActualizarDuaProducto($nueva_data);
    }
  }
  /*Fin Normalizacion*/

  function ActualizarDuaProductoNotaSalida($data)
  {
    $dataDuaProducto = $this->mDuaProducto->ObtenerDuaProductoPorProductoDua($data);
    if(count($dataDuaProducto)>0)
    {
      $nuevacantidad = $dataDuaProducto[0]["StockDua"] + $data["CantidadSalida"];
      $nueva_data["IdDuaProducto"] = $dataDuaProducto[0]["IdDuaProducto"];
      $nueva_data["StockDua"] = $nuevacantidad;
      $actualizacion = $this->ActualizarDuaProducto($nueva_data);
    }
  }

  function BorrarDuasProducto($data)
  {
    $productoalmacen = $this->mDuaProducto->ObtenerDuaProductoPorProductoDua($data);
    if(count($productoalmacen)>0)
    {
      $this->BorrarDuaProducto($productoalmacen[0]);
    }
  }

  /*Se normaliza para Nota Entrada*/
  function AgregarDuaProductoNotaEntrada($data)
  {
    $dataDuaProducto = $this->mDuaProducto->ObtenerDuaProductoPorProductoDua($data);

    if(count($dataDuaProducto)>0)
    {
      $nuevacantidad = $dataDuaProducto[0]["StockDua"] + $data["CantidadEntrada"];
      $nueva_data["IdDuaProducto"] = $dataDuaProducto[0]["IdDuaProducto"];
      $nueva_data["StockDua"] = $nuevacantidad;
      $nueva_data["NumeroItemDua"] = $data["NumeroItemDua"];
      $nueva_data["IdAsignacionSede"] = $data["IdAsignacionSede"];

      $actualizacion = $this->ActualizarDuaProducto($nueva_data);
      $actualizacion["Estado"] = 1; //Actualizacion
      return $actualizacion;
    }
    else {
      $nuevacantidad = 0 + $data["CantidadEntrada"];
      $data["StockDua"] = $nuevacantidad;

      $insercion = $this->InsertarDuaProducto($data);
      $insercion["Estado"] = 0; //Insercion
      return $insercion;
    }
  }
  /*Fin Normalizacion*/

  /*Se normaliza para inventario inicial*/
  function AgregarDuaProductoInventarioInicial($data)
  {
    $productoalmacen = $this->mDuaProducto->ObtenerDuaProductoPorProductoDua($data);

    if(count($productoalmacen)>0)
    {
      $nuevacantidad = $productoalmacen[0]["StockDua"] + ($data["CantidadInicial"] - $data["AnteriorInicial"]);
      $nueva_data["IdDuaProducto"] = $productoalmacen[0]["IdDuaProducto"];
      $nueva_data["StockDua"] = $nuevacantidad;
      $nueva_data["NumeroItemDua"] = $data["NumeroItemDua"];
      $nueva_data["IdAsignacionSede"] = $data["IdAsignacionSede"];

      $actualizacion = $this->ActualizarDuaProducto($nueva_data);
      return $actualizacion;
    }
    else {
      $nuevacantidad = 0 + $data["CantidadInicial"] - $data["AnteriorInicial"];
      $data["StockDua"] = $nuevacantidad;
      $insercion = $this->InsertarDuaProducto($data);
      return $insercion;
    }
  }
  /*fin inventario inicial*/

  function InsertarDuaProducto($data)
  {
    try {

      $resultadoValidacion = "";

      if($resultadoValidacion == "")
      {
        $resultado= $this->mDuaProducto->InsertarDuaProducto($data);

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

  function ActualizarDuaProducto($data)
  {
    try {
      $resultadoValidacion = "";

      if($resultadoValidacion == "")
      {
        $resultado=$this->mDuaProducto->ActualizarDuaProducto($data);

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

  function BorrarDuaProducto($data)
  {
    $this->mDuaProducto->BorrarDuaProducto($data);

    return "";
  }

 
  function BorrarMovimientosAlmacenNotaSalida($data)
  {
    $resultado = $this->mDuaProducto->ObtenerMovimientosPorNotaSalida($data);

    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);

        $value["SaldoFisico"] = $mercaderia["SaldoFisico"] + $value["CantidadSalida"];
        $actualizar = $this->mMercaderia->ActualizarMercaderia($value);

        $data_movimiento["IdDuaProducto"] = $value["IdDuaProducto"];
        $this->mDuaProducto->BorrarDuaProducto($data_movimiento);
      }
    }

    return $resultado;
  }

  function InsertarInventarioInicialEnDuaProducto($data)
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
      $resultado = $this->InsertarDuaProductoInventarioInicial($data);
      if($resultado)
      {
        $this->mMercaderia->ActualizarMercaderia($resultado);
      }
      return $resultado;
    }
  }


  function ProductosEnDuaProducto()
  {
    $resultado = $this->mDuaProducto->ProductosEnDuaProducto();
    return $resultado;
  }

  function SedesPorProductoEnDuaProducto($data)
  {
    $resultado = $this->mDuaProducto->SedesPorProductoEnDuaProducto($data);
    return $resultado;
  }

  function ValidarProductoInventarioInicial($data)
  {
    $resultado = $this->mDuaProducto->ConsularProductoInventarioInicial($data);
    if(count($resultado) > 0)
    {
      $this->BorrarDuaProducto($resultado[0]);
      $nuevo = $this->InsertarDuaProductoInventarioInicial($data);
      $data["IdDuaProducto"] = $nuevo["IdDuaProducto"];
      return $data;
    }
    else {
      // code...
      return "";
    }
  }

  // function ConsultarListasDuaPorIdProducto($data)
  // {
  //   $resultado = $this->mDuaProducto->ConsultarListasDuaPorIdProducto($data);
  //   $response = array();
  //   if(count($resultado) > 0)
  //   {
  //     foreach ($resultado as $key => $value) {
  //       $lista["IdDuaProducto"] = $value["IdDuaProducto"];
  //       $lista["IdAsignacionSede"] = $value["IdAsignacionSede"];
  //       $lista["IdProducto"] = $value["IdProducto"];
  //       $lista["IdComprobanteCompra"] = $value["IdComprobanteCompra"];
  //       $lista["StockDua"] = $value["StockDua"];
  //       $lista["NumeroComprobanteCompra"] = $value["NumeroComprobanteCompra"];
  //       array_push($response, $lista);
  //     }
  //   }
  //   return $response;
  // }

  function ConsultarListasDuaPorIdProducto($data)
  {
    $resultado = $this->mDuaProducto->ConsultarListasDuaPorIdProducto($data);
    return $resultado;
  }

  function ValidarProductoEnDuaProductoParaMercaderia($data)
  {
    $resultado = $this->mDuaProducto->ValidarProductoEnDuaProductoParaMercaderia($data);
    $response = count($resultado);
    return $response;
  }

}
