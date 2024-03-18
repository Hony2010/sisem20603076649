<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleNotaCredito extends MY_Service {

  public $DetalleNotaCredito = array();

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
        $this->DetalleNotaCredito = $this->mDetalleComprobanteVenta->DetalleComprobanteVenta;
  }

  function Cargar()
  {
    $this->DetalleNotaCredito["IdDetalleNotaCredito"] = "0";
    $this->DetalleNotaCredito["CodigoMercaderia"] = "";
    $this->DetalleNotaCredito["AbreviaturaUnidadMedida"] = "";
    $this->DetalleNotaCredito["NombreProducto"] = "";
    $this->DetalleNotaCredito["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleNotaCredito["ISCItem"] = 0.00;
    $this->DetalleNotaCredito["IGVItem"] = 0.00;
    $this->DetalleNotaCredito["DescuentoItem"] = "";
    $this->DetalleNotaCredito["PrecioUnitario"] = "";
    $this->DetalleNotaCredito["Cantidad"] = "";
    $this->DetalleNotaCredito["IdReferenciaDCV"] = "";

    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleNotaCredito" =>$this->DetalleNotaCredito
    );

    $resultado = array_merge($this->DetalleNotaCredito,$data);

    return $resultado;
  }

  function ActualizarDetallesComprobanteVenta($IdComprobanteVenta,$data)
  {
      //borrar todos los elementos
      $this->mDetalleComprobanteVenta->BorrarDetalleNotaCreditoPorIdComprobanteVenta($IdComprobanteVenta);

      //insertar todos los elementos
      $resultado=$this->InsertarDetallesComprobanteVenta($IdComprobanteVenta,$data);
      return $resultado;
  }

  function ActualizarDetalleNotaCredito($data)
  {
    $resultado = $this->mDetalleComprobanteVenta->ActualizarDetalleNotaCredito($data);

    return $resultado;
  }


  function BorrarDetallesNotaCredito($data)
  {
    $resultado = $this->mDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($data);

    if(count($resultado) > 0)
    {
      foreach($resultado as $key => $value) {
        // $IdDetalleNotaCredito=$value["IdDetalleNotaCredito"];
        $IdDetalleNotaCredito=$value["IdDetalleComprobanteVenta"];
        $this->mDetalleComprobanteVenta->BorrarDetalleComprobanteVenta($IdDetalleNotaCredito);
      }
    }
  }

  function InsertarDetallesComprobanteVenta($IdComprobanteVenta,$data)
  {
     for($i=0; $i < count($data) ; $i++) {
       if ($data[$i]["IdProducto"] != null) {
         $data[$i]["IdComprobanteVenta"] = $IdComprobanteVenta;
         $data[$i]["IdDetalleNotaCredito"]="";
         $data[$i]["NumeroItem"] = $i+1;
         $resultado = $this->mDetalleComprobanteVenta->InsertarDetalleNotaCredito($data[$i]);
         $data[$i]["IdDetalleNotaCredito"] = $resultado;
       }
     }

     return $data;
  }

  function ConsultarDetallesComprobanteVenta($data){
    $resultado = $this->mDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($data);

    $this->DetalleNotaCredito["IdDetalleNotaCredito"] = "0";
    $this->DetalleNotaCredito["CodigoMercaderia"] = "";
    $this->DetalleNotaCredito["AbreviaturaUnidadMedida"] = "";
    $this->DetalleNotaCredito["NombreProducto"] = "";
    $this->DetalleNotaCredito["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleNotaCredito["ISCItem"] = 0.00;
    $this->DetalleNotaCredito["IGVItem"] = 0.00;
    $this->DetalleNotaCredito["DescuentoItem"] = "";
    $this->DetalleNotaCredito["PrecioUnitario"] = "";
    $this->DetalleNotaCredito["Cantidad"] = "";

    foreach ($resultado as $key => $value) {
      $resultado[$key]["Producto"]=$this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleNotaCredito"]=$this->DetalleNotaCredito;
    }

    return $resultado;
  }

  function ValidarDetalleNotaCredito($data, $i = 0)
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
        $resultado = $resultado.$this->ValidarDetalleNotaCredito($value,$key+1);
      }

    }

    return $resultado;
  }


}
