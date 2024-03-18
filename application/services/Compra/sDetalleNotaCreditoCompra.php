<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleNotaCreditoCompra extends MY_Service {

  public $DetalleNotaCreditoCompra = array();

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
        $this->DetalleNotaCreditoCompra = $this->mDetalleComprobanteCompra->DetalleComprobanteCompra;
  }

  function Cargar()
  {
    $this->DetalleNotaCreditoCompra["IdDetalleNotaCreditoCompra"] = "0";
    $this->DetalleNotaCreditoCompra["CodigoMercaderia"] = "";
    $this->DetalleNotaCreditoCompra["AbreviaturaUnidadMedida"] = "";
    $this->DetalleNotaCreditoCompra["NombreProducto"] = "";
    $this->DetalleNotaCreditoCompra["AfectoIGV"] = "1";
    $this->DetalleNotaCreditoCompra["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleNotaCreditoCompra["ISCItem"] = 0.00;
    $this->DetalleNotaCreditoCompra["IGVItem"] = 0.00;
    $this->DetalleNotaCreditoCompra["DescuentoItem"] = "";
    $this->DetalleNotaCreditoCompra["PrecioUnitario"] = "";
    $this->DetalleNotaCreditoCompra["Cantidad"] = "";
    $this->DetalleNotaCreditoCompra["IdReferenciaDCV"] = "";

    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleNotaCreditoCompra" =>$this->DetalleNotaCreditoCompra
    );

    $resultado = array_merge($this->DetalleNotaCreditoCompra,$data);

    return $resultado;
  }

  function ActualizarDetallesComprobanteCompra($IdComprobanteCompra,$data)
  {
      //borrar todos los elementos
      $this->mDetalleComprobanteCompra->BorrarDetalleNotaCreditoCompraPorIdComprobanteCompra($IdComprobanteCompra);

      //insertar todos los elementos
      $resultado=$this->InsertarDetallesComprobanteCompra($IdComprobanteCompra,$data);
      return $resultado;
  }

  function ActualizarDetalleNotaCreditoCompra($data)
  {
    $resultado = $this->mDetalleComprobanteCompra->ActualizarDetalleNotaCreditoCompra($data);

    return $resultado;
  }


  function BorrarDetallesNotaCreditoCompra($data)
  {
    $resultado = $this->mDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($data);

    if(count($resultado) > 0)
    {
      foreach($resultado as $key => $value) {
        // $IdDetalleNotaCreditoCompra=$value["IdDetalleNotaCreditoCompra"];
        $IdDetalleNotaCreditoCompra=$value["IdDetalleComprobanteCompra"];
        $this->mDetalleComprobanteCompra->BorrarDetalleComprobanteCompra($IdDetalleNotaCreditoCompra);
      }
    }
  }

  function InsertarDetallesComprobanteCompra($IdComprobanteCompra,$data)
  {
     for($i=0; $i < count($data) ; $i++) {
       if ($data[$i]["IdProducto"] != null) {
         $data[$i]["IdComprobanteCompra"] = $IdComprobanteCompra;
         $data[$i]["IdDetalleNotaCreditoCompra"]="";
         $data[$i]["NumeroItem"] = $i+1;
         $resultado = $this->mDetalleComprobanteCompra->InsertarDetalleNotaCreditoCompra($data[$i]);
         $data[$i]["IdDetalleNotaCreditoCompra"] = $resultado;
       }
     }

     return $data;
  }

  function ConsultarDetallesComprobanteCompra($data){
    $resultado = $this->mDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($data);

    $this->DetalleNotaCreditoCompra["IdDetalleNotaCreditoCompra"] = "0";
    $this->DetalleNotaCreditoCompra["CodigoMercaderia"] = "";
    $this->DetalleNotaCreditoCompra["AbreviaturaUnidadMedida"] = "";
    $this->DetalleNotaCreditoCompra["NombreProducto"] = "";
    $this->DetalleNotaCreditoCompra["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleNotaCreditoCompra["ISCItem"] = 0.00;
    $this->DetalleNotaCreditoCompra["IGVItem"] = 0.00;
    $this->DetalleNotaCreditoCompra["DescuentoItem"] = "";
    $this->DetalleNotaCreditoCompra["PrecioUnitario"] = "";
    $this->DetalleNotaCreditoCompra["Cantidad"] = "";

    foreach ($resultado as $key => $value) {
      $resultado[$key]["Producto"]=$this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleNotaCreditoCompra"]=$this->DetalleNotaCreditoCompra;
    }

    return $resultado;
  }

  function ValidarDetalleNotaCreditoCompra($data, $i = 0)
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
        $resultado = $resultado.$this->ValidarDetalleNotaCreditoCompra($value,$key+1);
      }

    }

    return $resultado;
  }


}
