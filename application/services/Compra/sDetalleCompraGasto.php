<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleCompraGasto extends MY_Service {

  public $DetalleCompraGasto = array();

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
    $this->DetalleCompraGasto = $this->mDetalleComprobanteCompra->DetalleComprobanteCompra;

    $this->DetalleCompraGasto["IdDetalleCompraGasto"] = "0";
    $this->DetalleCompraGasto["CodigoMercaderia"] = "";
    $this->DetalleCompraGasto["AbreviaturaUnidadMedida"] = "UND";
    $this->DetalleCompraGasto["NombreProducto"] = "";
    $this->DetalleCompraGasto["AfectoIGV"] = "1";
    $this->DetalleCompraGasto["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleCompraGasto["ISCItem"] = 0.00;
    $this->DetalleCompraGasto["IGVItem"] = 0.00;
    $this->DetalleCompraGasto["DescuentoItem"] = "";
    $this->DetalleCompraGasto["PrecioUnitario"] = "";
    $this->DetalleCompraGasto["Cantidad"] = "";
    $this->DetalleCompraGasto["IdReferenciaDCV"] = "";
    $this->DetalleCompraGasto["CostoUnitario"] = "";
    //$this->DetalleCompraGasto["IdTipoPrecio"] = 1;
  }

  function Cargar()
  {
    $data = array(
      //"NuevoDetalleCompraGasto" =>$this->DetalleCompraGasto,
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleCompraGasto" =>$this->DetalleCompraGasto
    );

    $resultado = array_merge($this->DetalleCompraGasto,$data);
    return $resultado;
  }

  function ActualizarDetallesComprobanteCompra($IdComprobanteCompra,$data)
  {
      //borrar todos los elementos
      $this->mDetalleComprobanteCompra->BorrarDetalleCompraGastoPorIdComprobanteCompra($IdComprobanteCompra);

      //insertar todos los elementos
      $resultado=$this->InsertarDetallesComprobanteCompra($IdComprobanteCompra,$data);
      return $resultado;
  }

  function ActualizarDetalleCompraGasto($data)
  {
    $resultado = $this->mDetalleComprobanteCompra->ActualizarDetalleCompraGasto($data);

    return $resultado;
  }


  function BorrarDetallesComprobanteCompra($data)
  {
    foreach($data as $key => $value) {
      $IdDetalleCompraGasto=$value["IdDetalleCompraGasto"];
      $this->mDetalleComprobanteCompra->BorrarDetalleCompraGasto($IdDetalleCompraGasto);
    }
  }

  function InsertarDetallesComprobanteCompra($IdComprobanteCompra,$data)
  {
     for($i=0; $i < count($data) ; $i++) {
       if ($data[$i]["IdProducto"] != null) {
         $data[$i]["IdComprobanteCompra"] = $IdComprobanteCompra;
         $data[$i]["IdDetalleCompraGasto"]="";
         $data[$i]["NumeroItem"] = $i+1;
         $resultado = $this->mDetalleComprobanteCompra->InsertarDetalleCompraGasto($data[$i]);
         $data[$i]["IdDetalleCompraGasto"] = $resultado;
       }
     }

     return $data;
  }

  function ConsultarDetallesCompraGasto($data){
    $resultado = $this->mDetalleComprobanteCompra->ConsultarDetallesCompraGasto($data);

    foreach ($resultado as $key => $value) {
      $resultado[$key]["Producto"]=$this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleCompraGasto"]=$this->DetalleCompraGasto;
    }

    return $resultado;
  }

  function ValidarDetalleCompraGasto($data, $i = 0)
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
        $resultado = $resultado.$this->ValidarDetalleCompraGasto($value,$key+1);
      }

    }

    return $resultado;
  }


}
