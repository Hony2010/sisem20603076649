<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleSaldoInicialCuentaCobranza extends MY_Service {

  public $DetalleSaldoInicialCuentaCobranza = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->model("Base");
    $this->load->model('CuentaCobranza/mDetalleSaldoInicialCuentaCobranza');
    $this->load->service('Catalogo/sProducto');
    $this->load->service('Catalogo/sMercaderia');
    $this->load->service('Configuracion/General/sConstanteSistema');

    //ESTRUCTURA
    $this->DetalleSaldoInicialCuentaCobranza = $this->mDetalleSaldoInicialCuentaCobranza->DetalleSaldoInicialCuentaCobranza;
    $this->DetalleSaldoInicialCuentaCobranza["IdDetalleSaldoInicialCuentaCobranza"] = "0";
    $this->DetalleSaldoInicialCuentaCobranza["CodigoMercaderia"] = "";
    $this->DetalleSaldoInicialCuentaCobranza["NombreProducto"] = "";
    $this->DetalleSaldoInicialCuentaCobranza["Cantidad"] = "";
    $this->DetalleSaldoInicialCuentaCobranza["PrecioUnitario"] = "";
    $this->DetalleSaldoInicialCuentaCobranza["SubTotal"] = "";
  }

  function Cargar()
  {
    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleSaldoInicialCuentaCobranza" =>$this->DetalleSaldoInicialCuentaCobranza
    );

    $resultado = array_merge($this->DetalleSaldoInicialCuentaCobranza, $data);
    return $resultado;
  }

  /**FUNCIONES BASE */
  function InsertarDetalleSaldoInicialCuentaCobranza($data)
  {
    try {
      $resultado = $this->mDetalleSaldoInicialCuentaCobranza->InsertarDetalleSaldoInicialCuentaCobranza($data);
      return $resultado;
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function BorrarDetalleSaldoInicialCuentaCobranza($data)
  {
    try {
      $resultado = $this->mDetalleSaldoInicialCuentaCobranza->BorrarDetalleSaldoInicialCuentaCobranza($data);
      return $resultado;
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }
  /**FIN FUNCIONES BASE */

  function ActualizarDetallesSaldoInicialCuentaCobranza($IdSaldoInicialCuentaCobranza, $data)
  {
    //borrar todos los elementos
    $this->mDetalleSaldoInicialCuentaCobranza->BorrarDetalleSaldoInicialCuentaCobranzaPorIdSaldoInicialCuentaCobranza($IdSaldoInicialCuentaCobranza);

    //insertar todos los elementos
    $resultado = $this->InsertarDetallesSaldoInicialCuentaCobranza($IdSaldoInicialCuentaCobranza,$data);
    return $resultado;
  }

  function ActualizarDetalleSaldoInicialCuentaCobranza($data)
  {
    $resultado = $this->mDetalleSaldoInicialCuentaCobranza->ActualizarDetalleSaldoInicialCuentaCobranza($data);
    return $resultado;
  }

  function BorrarDetallesSaldoInicialCuentaCobranza($data)
  {
    foreach($data as $key => $value) {
      $IdDetalleSaldoInicialCuentaCobranza = $value["IdDetalleSaldoInicialCuentaCobranza"];
      $this->mDetalleSaldoInicialCuentaCobranza->BorrarDetalleSaldoInicialCuentaCobranza($IdDetalleSaldoInicialCuentaCobranza);
    }
  }

  function BorrarDetallesPorIdSaldoInicialCuentaCobranza($data) // cambiar a estado E
  {
    $id['IdSaldoInicialCuentaCobranza'] = $data['IdSaldoInicialCuentaCobranza'];
    $resultado = $this->mDetalleSaldoInicialCuentaCobranza->BorrarDetallesPorIdSaldoInicialCuentaCobranza($id);
    return $resultado;
  }

  function InsertarDetallesSaldoInicialCuentaCobranza($IdSaldoInicialCuentaCobranza,$data)
  {
    for($i=0; $i < count($data) ; $i++) {
      if ($data[$i]["IdProducto"] != null) {
        $data[$i]["IdDetalleSaldoInicialCuentaCobranza"] = "";
        $data[$i]["IdSaldoInicialCuentaCobranza"] = $IdSaldoInicialCuentaCobranza;

        $data[$i]["Cantidad"] = (is_string($data[$i]["Cantidad"])) ? str_replace(',',"",$data[$i]["Cantidad"]) : $data[$i]["Cantidad"];
        $data[$i]["PrecioUnitario"] = (is_string($data[$i]["PrecioUnitario"])) ? str_replace(',',"",$data[$i]["PrecioUnitario"]) : $data[$i]["PrecioUnitario"];
        $data[$i]["SubTotal"] = (is_string($data[$i]["SubTotal"])) ? str_replace(',',"",$data[$i]["SubTotal"]) : $data[$i]["SubTotal"];

        $resultado = $this->mDetalleSaldoInicialCuentaCobranza->InsertarDetalleSaldoInicialCuentaCobranza($data[$i]);
        $data[$i]["IdDetalleSaldoInicialCuentaCobranza"] = $resultado;
        $data[$i]["IndicadorEstado"] = ESTADO_ACTIVO;
      }
    }

    return $data;
  }

  function ConsultarDetallesSaldoInicialCuentaCobranza($data){
    $resultado = $this->mDetalleSaldoInicialCuentaCobranza->ConsultarDetallesSaldoInicialCuentaCobranza($data);

    foreach ($resultado as $key => $value) {
      $resultado[$key]["Producto"] = $this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleSaldoInicialCuentaCobranza"] = $this->DetalleSaldoInicialCuentaCobranza;
    }

    return $resultado;
  }

  function ValidarDetalleSaldoInicialCuentaCobranza($data, $sede, $i = 0)
  {
    $resultado = "";

    if(strlen($data["IdProducto"]) == 0)
    {
      $resultado = $resultado."En el ".($i)."° item del comprobante de venta, no se han encontrado resultados para tu búsqueda de cliente."."\n";
    }

    $cantidad = (is_string($data["Cantidad"])) ? str_replace(',', '', $data["Cantidad"]) : $data["Cantidad"];
    $preciounitario = (is_string($data["PrecioUnitario"])) ? str_replace(',', '', $data["PrecioUnitario"]) : $data["PrecioUnitario"];
    $subtotal = (is_string($data["SubTotal"])) ? str_replace(',', '', $data["SubTotal"]) : $data["SubTotal"];

    if($cantidad <= 0 || !is_numeric($cantidad) )
    {
      $resultado = $resultado."En el ".($i)."° item del comprobante de venta la cantidad debe ser mayor que cero y numérico."."\n";
    }

    if($preciounitario < 0 || !is_numeric($preciounitario) )
    {
      $resultado = $resultado ."En el ".($i)."° item del comprobante de venta el precio debe ser mayor que o igual que cero y numérico."."\n";
    }

    if($subtotal < 0)
    {
      $resultado = $resultado."En el ".($i)."° item del comprobante de venta el descuento no debe ser mayor al importe."."\n";
    }

    return $resultado;
  }

  function ValidarDetallesSaldoInicialCuentaCobranza($data, $sede = null)
  {
    $resultado = "";
    $total = count($data);

    if($total == 0)
      $resultado = $resultado."Ingresar por lo menos un item al comprobante."."\n";

    foreach ($data as $key => $value)
    {
      if($key < ($total - 1))//recorre hasta la penultima
      {
        $resultado = $resultado.$this->ValidarDetalleSaldoInicialCuentaCobranza($value, $sede, $key+1);
      }
    }

    return $resultado;
  }

}
