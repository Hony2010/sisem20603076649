<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleNotaDebito extends MY_Service {

  public $DetalleNotaDebito = array();

  public function __construct()
  {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->library('herencia');
        $this->load->model('Venta/mDetalleComprobanteVenta');
        $this->load->service('Catalogo/sProducto');
        $this->DetalleNotaDebito = $this->mDetalleComprobanteVenta->DetalleComprobanteVenta;
  }

  function Cargar()
  {
    $this->DetalleNotaDebito["IdDetalleNotaDebito"] = "0";
    $this->DetalleNotaDebito["CodigoMercaderia"] = "";
    $this->DetalleNotaDebito["AbreviaturaUnidadMedida"] = "";
    $this->DetalleNotaDebito["NombreProducto"] = "";
    $this->DetalleNotaDebito["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleNotaDebito["ISCItem"] = 0.00;
    $this->DetalleNotaDebito["IGVItem"] = 0.00;
    $this->DetalleNotaDebito["DescuentoItem"] = "";
    $this->DetalleNotaDebito["PrecioUnitario"] = "";
    $this->DetalleNotaDebito["Cantidad"] = "";
    $this->DetalleNotaDebito["IdReferenciaDCV"] = "";

    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleNotaDebito" =>$this->DetalleNotaDebito
    );

    $resultado = array_merge($this->DetalleNotaDebito,$data);

    return $resultado;
  }

  function ActualizarDetallesComprobanteVenta($IdComprobanteVenta,$data)
  {
      //borrar todos los elementos
      $this->mDetalleComprobanteVenta->BorrarDetalleNotaDebitoPorIdComprobanteVenta($IdComprobanteVenta);

      //insertar todos los elementos
      $resultado=$this->InsertarDetallesComprobanteVenta($IdComprobanteVenta,$data);
      return $resultado;
  }

  function ActualizarDetalleNotaDebito($data)
  {
    $resultado = $this->mDetalleComprobanteVenta->ActualizarDetalleNotaDebito($data);

    return $resultado;
  }


  function BorrarDetallesComprobanteVenta($data)
  {
    foreach($data as $key => $value) {
      $IdDetalleNotaDebito=$value["IdDetalleNotaDebito"];
      $this->mDetalleComprobanteVenta->BorrarDetalleNotaDebito($IdDetalleNotaDebito);
    }
  }

  function InsertarDetallesComprobanteVenta($IdComprobanteVenta,$data)
  {
     for($i=0; $i < count($data) ; $i++) {
       if ($data[$i]["IdProducto"] != null) {
         $data[$i]["IdComprobanteVenta"] = $IdComprobanteVenta;
         $data[$i]["IdDetalleNotaDebito"]="";
         $data[$i]["NumeroItem"] = $i+1;
         $resultado = $this->mDetalleComprobanteVenta->InsertarDetalleNotaDebito($data[$i]);
         $data[$i]["IdDetalleNotaDebito"] = $resultado;
       }
     }

     return $data;
  }

  function ConsultarDetallesComprobanteVenta($data){
    $resultado = $this->mDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($data);

    $this->DetalleNotaDebito["IdDetalleNotaDebito"] = "0";
    $this->DetalleNotaDebito["CodigoMercaderia"] = "";
    $this->DetalleNotaDebito["AbreviaturaUnidadMedida"] = "";
    $this->DetalleNotaDebito["NombreProducto"] = "";
    $this->DetalleNotaDebito["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleNotaDebito["ISCItem"] = 0.00;
    $this->DetalleNotaDebito["IGVItem"] = 0.00;
    $this->DetalleNotaDebito["DescuentoItem"] = "";
    $this->DetalleNotaDebito["PrecioUnitario"] = "";
    $this->DetalleNotaDebito["Cantidad"] = "";

    foreach ($resultado as $key => $value) {
      $resultado[$key]["Producto"]=$this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleNotaDebito"]=$this->DetalleNotaDebito;
    }

    return $resultado;
  }

  function ValidarDetalleNotaDebito($data, $i = 0)
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

  function ValidarDetallesComprobanteVenta($data)
  {
    $resultado="";
    $total =count($data);

    if($total == 0)
      $resultado =$resultado."Ingresar por lo menos un item al comprobante."."\n";

    foreach ($data as $key => $value)
    {

      if($key < ($total - 1))//recorre hasta la penultima
      {
        $resultado = $resultado.$this->ValidarDetalleNotaDebito($value,$key+1);
      }

    }

    return $resultado;
  }


}
