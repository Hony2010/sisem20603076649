<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleCompraCostoAgregado extends MY_Service {

  public $DetalleCompraCostoAgregado = array();

  public function __construct()
  {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->library('herencia');
        $this->load->model('Compra/mDetalleComprobanteCompra');
        $this->load->service('Catalogo/sProducto');
        $this->DetalleCompraCostoAgregado = $this->mDetalleComprobanteCompra->DetalleComprobanteCompra;
  }

  function Cargar()
  {
    $this->DetalleCompraCostoAgregado["IdDetalleCompraCostoAgregado"] = "0";
    $this->DetalleCompraCostoAgregado["CodigoMercaderia"] = "";
    $this->DetalleCompraCostoAgregado["AbreviaturaUnidadMedida"] = "UND";
    $this->DetalleCompraCostoAgregado["NombreProducto"] = "";
    $this->DetalleCompraCostoAgregado["AfectoIGV"] = "1";
    $this->DetalleCompraCostoAgregado["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleCompraCostoAgregado["ISCItem"] = 0.00;
    $this->DetalleCompraCostoAgregado["IGVItem"] = 0.00;
    $this->DetalleCompraCostoAgregado["DescuentoItem"] = "";
    $this->DetalleCompraCostoAgregado["DescuentoUnitario"] = 0;
    $this->DetalleCompraCostoAgregado["CostoUnitario"] = "";
    $this->DetalleCompraCostoAgregado["PrecioUnitario"] = "";
    $this->DetalleCompraCostoAgregado["Cantidad"] = "";
    $this->DetalleCompraCostoAgregado["IdReferenciaDCV"] = "";
    $this->DetalleCompraCostoAgregado["ValorVentaItem"] = "0";

    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleCompraCostoAgregado" =>$this->DetalleCompraCostoAgregado
    );

    $resultado = array_merge($this->DetalleCompraCostoAgregado,$data);

    return $resultado;
  }

  function ActualizarDetallesComprobanteCompra($IdComprobanteCompra,$data)
  {
      //borrar todos los elementos
      $this->mDetalleComprobanteCompra->BorrarDetalleCompraCostoAgregadoPorIdComprobanteCompra($IdComprobanteCompra);

      //insertar todos los elementos
      $resultado=$this->InsertarDetallesComprobanteCompra($IdComprobanteCompra,$data);
      return $resultado;
  }

  function ActualizarDetalleCompraCostoAgregado($data)
  {
    $resultado = $this->mDetalleComprobanteCompra->ActualizarDetalleCompraCostoAgregado($data);

    return $resultado;
  }


  function BorrarDetallesComprobanteCompra($data)
  {
    foreach($data as $key => $value) {
      $IdDetalleCompraCostoAgregado=$value["IdDetalleCompraCostoAgregado"];
      $this->mDetalleComprobanteCompra->BorrarDetalleCompraCostoAgregado($IdDetalleCompraCostoAgregado);
    }
  }

  function InsertarDetallesComprobanteCompra($IdComprobanteCompra,$data)
  {
     for($i=0; $i < count($data) ; $i++) {
       if ($data[$i]["IdProducto"] != null) {
         $data[$i]["IdComprobanteCompra"] = $IdComprobanteCompra;
         $data[$i]["IdDetalleCompraCostoAgregado"]="";
         $data[$i]["NumeroItem"] = $i+1;
         $resultado = $this->mDetalleComprobanteCompra->InsertarDetalleCompraCostoAgregado($data[$i]);
         $data[$i]["IdDetalleCompraCostoAgregado"] = $resultado;
       }
     }

     return $data;
  }

  function ConsultarDetallesCompraCostoAgregado($data){
    $resultado = $this->mDetalleComprobanteCompra->ConsultarDetallesCompraCostoAgregado($data);

    $this->DetalleCompraCostoAgregado["IdDetalleCompraCostoAgregado"] = "0";
    $this->DetalleCompraCostoAgregado["CodigoMercaderia"] = "";
    $this->DetalleCompraCostoAgregado["AbreviaturaUnidadMedida"] = "";
    $this->DetalleCompraCostoAgregado["NombreProducto"] = "";
    $this->DetalleCompraCostoAgregado["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleCompraCostoAgregado["ISCItem"] = 0.00;
    $this->DetalleCompraCostoAgregado["IGVItem"] = 0.00;
    $this->DetalleCompraCostoAgregado["DescuentoItem"] = "";
    $this->DetalleCompraCostoAgregado["PrecioUnitario"] = "";
    $this->DetalleCompraCostoAgregado["Cantidad"] = "";

    foreach ($resultado as $key => $value) {
      $resultado[$key]["Producto"]=$this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleCompraCostoAgregado"]=$this->DetalleCompraCostoAgregado;
    }

    return $resultado;
  }

  function ValidarDetalleCompraCostoAgregado($data, $i = 0)
  {
    $resultado="";

    if(strlen($data["IdProducto"]) == 0)
    {
      $resultado = $resultado."En el ".($i)."° item del comprobante de venta, no se han encontrado resultados para tu búsqueda de cliente."."\n";
    }

    if($data["Cantidad"] <= 0 || !is_numeric($data["Cantidad"]) )
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de venta la cantidad debe ser mayor que cero y numérico."."\n";
    }

    if($data["PrecioUnitario"] < 0 || !is_numeric($data["PrecioUnitario"]) )
    {
      $resultado =$resultado ."En el ".($i)."° item del comprobante de venta el precio debe ser mayor que o igual que cero y numérico."."\n";
    }

    if($data["DescuentoItem"] < 0 || !is_numeric($data["DescuentoItem"]) )
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de venta el descuento debe ser mayor que o igual que cero y numérico."."\n";
    }

    if($data["SubTotal"] < 0)
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de venta el descuento no debe ser mayor al importe."."\n";
    }

    return $resultado;
  }

  function ValidarDetallesComprobanteCompra($data)
  {
    $resultado="";
    $total =count($data);

    if($total == 0)
      $resultado =$resultado."Ingresar por lo menos un item al comprobante."."\n";

    foreach ($data as $key => $value)
    {

      if($key < ($total - 1))//recorre hasta la penultima
      {
        $resultado = $resultado.$this->ValidarDetalleCompraCostoAgregado($value,$key+1);
      }

    }

    return $resultado;
  }


}
