<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleSaldoInicialCuentaPago extends MY_Service {

  public $DetalleSaldoInicialCuentaPago = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->model("Base");
    $this->load->model('CuentaPago/mDetalleSaldoInicialCuentaPago');
    $this->load->service('Catalogo/sProducto');
    $this->load->service('Catalogo/sMercaderia');
    $this->load->service('Configuracion/General/sConstanteSistema');

    //ESTRUCTURA
    $this->DetalleSaldoInicialCuentaPago = $this->mDetalleSaldoInicialCuentaPago->DetalleSaldoInicialCuentaPago;
    $this->DetalleSaldoInicialCuentaPago["IdDetalleSaldoInicialCuentaPago"] = "0";
    $this->DetalleSaldoInicialCuentaPago["CodigoMercaderia"] = "";
    $this->DetalleSaldoInicialCuentaPago["NombreProducto"] = "";
    $this->DetalleSaldoInicialCuentaPago["Cantidad"] = "";
    $this->DetalleSaldoInicialCuentaPago["PrecioUnitario"] = "";
    $this->DetalleSaldoInicialCuentaPago["SubTotal"] = "";
  }

  function Cargar()
  {
    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleSaldoInicialCuentaPago" =>$this->DetalleSaldoInicialCuentaPago
    );

    $resultado = array_merge($this->DetalleSaldoInicialCuentaPago, $data);
    return $resultado;
  }

  /**FUNCIONES BASE */
  function InsertarDetalleSaldoInicialCuentaPago($data)
  {
    try {
      $resultado = $this->mDetalleSaldoInicialCuentaPago->InsertarDetalleSaldoInicialCuentaPago($data);
      return $resultado;
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function BorrarDetalleSaldoInicialCuentaPago($data)
  {
    try {
      $resultado = $this->mDetalleSaldoInicialCuentaPago->BorrarDetalleSaldoInicialCuentaPago($data);
      return $resultado;
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }
  /**FIN FUNCIONES BASE */

  function ActualizarDetallesSaldoInicialCuentaPago($IdSaldoInicialCuentaPago, $data)
  {
    //borrar todos los elementos
    $this->mDetalleSaldoInicialCuentaPago->BorrarDetalleSaldoInicialCuentaPagoPorIdSaldoInicialCuentaPago($IdSaldoInicialCuentaPago);

    //insertar todos los elementos
    $resultado = $this->InsertarDetallesSaldoInicialCuentaPago($IdSaldoInicialCuentaPago,$data);
    return $resultado;
  }

  function ActualizarDetalleSaldoInicialCuentaPago($data)
  {
    $resultado = $this->mDetalleSaldoInicialCuentaPago->ActualizarDetalleSaldoInicialCuentaPago($data);
    return $resultado;
  }

  function BorrarDetallesSaldoInicialCuentaPago($data)
  {
    foreach($data as $key => $value) {
      $IdDetalleSaldoInicialCuentaPago = $value["IdDetalleSaldoInicialCuentaPago"];
      $this->mDetalleSaldoInicialCuentaPago->BorrarDetalleSaldoInicialCuentaPago($IdDetalleSaldoInicialCuentaPago);
    }
  }

  function BorrarDetallesPorIdSaldoInicialCuentaPago($data) // cambiar a estado E
  {
    $id['IdSaldoInicialCuentaPago'] = $data['IdSaldoInicialCuentaPago'];
    $resultado = $this->mDetalleSaldoInicialCuentaPago->BorrarDetallesPorIdSaldoInicialCuentaPago($id);
    return $resultado;
  }

  function InsertarDetallesSaldoInicialCuentaPago($IdSaldoInicialCuentaPago,$data)
  {
    for($i=0; $i < count($data) ; $i++) {
      if ($data[$i]["IdProducto"] != null) {
        $data[$i]["IdDetalleSaldoInicialCuentaPago"] = "";
        $data[$i]["IdSaldoInicialCuentaPago"] = $IdSaldoInicialCuentaPago;

        $data[$i]["Cantidad"] = (is_string($data[$i]["Cantidad"])) ? str_replace(',',"",$data[$i]["Cantidad"]) : $data[$i]["Cantidad"];
        $data[$i]["PrecioUnitario"] = (is_string($data[$i]["PrecioUnitario"])) ? str_replace(',',"",$data[$i]["PrecioUnitario"]) : $data[$i]["PrecioUnitario"];
        $data[$i]["SubTotal"] = (is_string($data[$i]["SubTotal"])) ? str_replace(',',"",$data[$i]["SubTotal"]) : $data[$i]["SubTotal"];

        $resultado = $this->mDetalleSaldoInicialCuentaPago->InsertarDetalleSaldoInicialCuentaPago($data[$i]);
        $data[$i]["IdDetalleSaldoInicialCuentaPago"] = $resultado;
        $data[$i]["IndicadorEstado"] = ESTADO_ACTIVO;
      }
    }
    return $data;
  }

  function ConsultarDetallesSaldoInicialCuentaPago($data){
    $resultado = $this->mDetalleSaldoInicialCuentaPago->ConsultarDetallesSaldoInicialCuentaPago($data);

    foreach ($resultado as $key => $value) {
      $resultado[$key]["Producto"] = $this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleSaldoInicialCuentaPago"] = $this->DetalleSaldoInicialCuentaPago;
    }

    return $resultado;
  }

  function ValidarDetalleSaldoInicialCuentaPago($data, $sede, $i = 0)
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

  function ValidarDetallesSaldoInicialCuentaPago($data, $sede = null)
  {
    $resultado = "";
    $total = count($data);

    if($total == 0)
      $resultado = $resultado."Ingresar por lo menos un item al comprobante."."\n";

    foreach ($data as $key => $value)
    {
      if($key < ($total - 1))//recorre hasta la penultima
      {
        $resultado = $resultado.$this->ValidarDetalleSaldoInicialCuentaPago($value, $sede, $key+1);
      }
    }

    return $resultado;
  }

}
